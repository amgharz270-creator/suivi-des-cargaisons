<?php
// risk_analysis.php - Tableau de bord d'analyse des risques
$serveur = "localhost";
$utilisateur = "root"; 
$motdepasse = "";
$basededonnees = "pacocean_db";

$connexion = mysqli_connect($serveur, $utilisateur, $motdepasse, $basededonnees);

if (!$connexion) {
    die("Erreur de connexion : " . mysqli_connect_error());
}

$resultat = null;
$erreur = null;
$risques = [];
$score_total = 0;

// Traitement de la recherche
if (isset($_GET['numero']) && !empty($_GET['numero'])) {
    $numero_suivi = mysqli_real_escape_string($connexion, $_GET['numero']);
    
    // Récupérer les infos de la cargaison avec jointure sur les risques
    $sql = "SELECT t.*, r.score_risque, r.niveau_risque, r.facteurs_risque, r.recommandations, r.date_analyse
            FROM tracking t
            LEFT JOIN risk_analysis r ON t.id = r.tracking_id
            WHERE t.numero_suivi = '$numero_suivi'";
    
    $query = mysqli_query($connexion, $sql);
    
    if (mysqli_num_rows($query) > 0) {
        $resultat = mysqli_fetch_assoc($query);
        
        // Si pas d'analyse existante, calculer automatiquement
        if (empty($resultat['score_risque'])) {
            $analyse = calculerRisques($resultat, $connexion);
            $score_total = $analyse['score'];
            $risques = $analyse['facteurs'];
            
            // Sauvegarder l'analyse
            sauvegarderAnalyse($connexion, $resultat['id'], $analyse);
            
            // Recharger les données
            $query = mysqli_query($connexion, $sql);
            $resultat = mysqli_fetch_assoc($query);
        } else {
            $score_total = $resultat['score_risque'];
            $risques = json_decode($resultat['facteurs_risque'], true) ?? [];
        }
    } else {
        $erreur = "Numéro de suivi non trouvé !";
    }
}

// Fonction de calcul des risques
function calculerRisques($cargaison, $connexion) {
    $score = 0;
    $facteurs = [];
    
    // 1. Risque Type de Marchandise (0-30 points)
    $type_risque = 0;
    switch(strtolower($cargaison['type_service'])) {
        case 'dangereux':
        case 'imdg':
            $type_risque = 30;
            $facteurs[] = [
                'type' => 'Marchandise Dangereuse',
                'niveau' => 'critique',
                'score' => 30,
                'icon' => 'fa-exclamation-triangle',
                'description' => 'Produits chimiques/batteries nécessitent manipulation spéciale'
            ];
            break;
        case 'réfrigéré':
        case 'reefer':
            $type_risque = 20;
            $facteurs[] = [
                'type' => 'Fret Réfrigéré',
                'niveau' => 'eleve',
                'score' => 20,
                'icon' => 'fa-snowflake',
                'description' => 'Dépendance à la chaîne du froid'
            ];
            break;
        case 'aérien':
            $type_risque = 15;
            $facteurs[] = [
                'type' => 'Transport Aérien',
                'niveau' => 'modere',
                'score' => 15,
                'icon' => 'fa-plane',
                'description' => 'Contraintes de poids et sécurité aéroportuaire'
            ];
            break;
        default:
            $type_risque = 10;
            $facteurs[] = [
                'type' => 'Fret Standard',
                'niveau' => 'faible',
                'score' => 10,
                'icon' => 'fa-box',
                'description' => 'Risque standard du transport maritime'
            ];
    }
    $score += $type_risque;
    
    // 2. Risque Retard (0-25 points)
    if (!empty($cargaison['date_eta'])) {
        $eta = new DateTime($cargaison['date_eta']);
        $aujourdhui = new DateTime();
        $interval = $aujourdhui->diff($eta);
        $jours_restant = $interval->days;
        
        if ($interval->invert == 1) { // Date dépassée
            $retard_risque = min(25, 10 + ($jours_restant * 3));
            $facteurs[] = [
                'type' => 'Retard Critique',
                'niveau' => $retard_risque > 20 ? 'critique' : 'eleve',
                'score' => $retard_risque,
                'icon' => 'fa-clock',
                'description' => "Retard de $jours_restant jours - Impact client élevé"
            ];
            $score += $retard_risque;
        } elseif ($jours_restant <= 2) {
            $facteurs[] = [
                'type' => 'Échéance Proche',
                'niveau' => 'modere',
                'score' => 10,
                'icon' => 'fa-hourglass-half',
                'description' => "Arrivée prévue dans $jours_restant jours"
            ];
            $score += 10;
        }
    }
    
    // 3. Risque Localisation/Géopolitique (0-25 points)
    $zones_risque = ['Canal de Suez', 'Golfe d\'Aden', 'Mer de Chine', 'Ukraine', 'Russie'];
    $localisation = strtolower($cargaison['localisation_actuelle']);
    $geo_risque = 0;
    
    foreach ($zones_risque as $zone) {
        if (stripos($localisation, strtolower($zone)) !== false) {
            $geo_risque = 25;
            $facteurs[] = [
                'type' => 'Zone Géopolitique',
                'niveau' => 'eleve',
                'score' => 25,
                'icon' => 'fa-globe-africa',
                'description' => "Navigation dans $zone - Surveillance accrue requise"
            ];
            break;
        }
    }
    
    if ($geo_risque == 0 && stripos($localisation, 'port') !== false) {
        $geo_risque = 5;
        $facteurs[] = [
            'type' => 'Zone Portuaire',
            'niveau' => 'faible',
            'score' => 5,
            'icon' => 'fa-anchor',
            'description' => 'Risques standard de congestion portuaire'
        ];
    }
    $score += $geo_risque;
    
    // 4. Risque Progression (0-20 points)
    $progression = intval($cargaison['progression']);
    if ($progression < 30) {
        $prog_risque = 15;
        $facteurs[] = [
            'type' => 'Début de Trajet',
            'niveau' => 'modere',
            'score' => 15,
            'icon' => 'fa-play-circle',
            'description' => 'Phase initiale - Incertitudes de départ'
        ];
    } elseif ($progression > 85 && $progression < 100) {
        $prog_risque = 8;
        $facteurs[] = [
            'type' => 'Phase Finale',
            'niveau' => 'faible',
            'score' => 8,
            'icon' => 'fa-flag-checkered',
            'description' => 'Dernière ligne droite - Risque de dernière minute'
        ];
    } else {
        $prog_risque = 5;
        $facteurs[] = [
            'type' => 'En Cours de Route',
            'niveau' => 'faible',
            'score' => 5,
            'icon' => 'fa-route',
            'description' => 'Progression normale'
        ];
    }
    $score += $prog_risque;
    
    // Déterminer le niveau global
    if ($score >= 70) $niveau = 'critique';
    elseif ($score >= 50) $niveau = 'eleve';
    elseif ($score >= 30) $niveau = 'modere';
    else $niveau = 'faible';
    
    return [
        'score' => min($score, 100),
        'niveau' => $niveau,
        'facteurs' => $facteurs
    ];
}

// Sauvegarder l'analyse en base
function sauvegarderAnalyse($connexion, $tracking_id, $analyse) {
    $score = $analyse['score'];
    $niveau = $analyse['niveau'];
    $facteurs = json_encode($analyse['facteurs'], JSON_UNESCAPED_UNICODE);
    $reco = genererRecommandations($analyse);
    
    $sql = "INSERT INTO risk_analysis (tracking_id, score_risque, niveau_risque, facteurs_risque, recommandations) 
            VALUES ($tracking_id, $score, '$niveau', '$facteurs', '$reco')
            ON DUPLICATE KEY UPDATE 
            score_risque = $score, 
            niveau_risque = '$niveau', 
            facteurs_risque = '$facteurs', 
            recommandations = '$reco',
            date_analyse = CURRENT_TIMESTAMP";
    
    mysqli_query($connexion, $sql);
}

// Générer recommandations automatiques
function genererRecommandations($analyse) {
    $recos = [];
    
    if ($analyse['score'] >= 70) {
        $recos[] = "🚨 ALERTE MANAGER : Briefing sécurité immédiat requis";
        $recos[] = "📞 Contacter client pour gestion d'attente";
        $recos[] = "📋 Activer plan de contingence";
    }
    if ($analyse['score'] >= 50) {
        $recos[] = "⚠️ Surveillance renforcée toutes les 4h";
        $recos[] = "📊 Mise à jour client quotidienne";
    }
    if ($analyse['score'] >= 30) {
        $recos[] = "✅ Monitoring standard suffisant";
        $recos[] = "📧 Notification automatique si changement";
    } else {
        $recos[] = "✅ Transport sans risque particulier";
        $recos[] = "🔄 Suivi normal";
    }
    
    return implode("\n", $recos);
}

mysqli_close($connexion);

// Fonction helper pour les couleurs
function getRiskColor($niveau) {
    switch($niveau) {
        case 'critique': return ['#dc3545', '#721c24', '🔴'];
        case 'eleve': return ['#fd7e14', '#7c2d12', '🟠'];
        case 'modere': return ['#ffc107', '#856404', '🟡'];
        default: return ['#28a745', '#155724', '🟢'];
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analyse des Risques - PACOCEAN MAGHREB</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #0066cc;
            --secondary: #003d7a;
            --accent: #00a8ff;
            --dark: #1a1a2e;
            --light: #f8f9fa;
            --white: #ffffff;
            --success: #28a745;
            --warning: #ffc107;
            --danger: #dc3545;
            --orange: #fd7e14;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            color: var(--dark);
            min-height: 100vh;
        }

        /* Navigation */
        nav {
            position: fixed;
            top: 0;
            width: 100%;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 1rem 5%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 1000;
            box-shadow: 0 2px 20px rgba(0,0,0,0.1);
        }

        .logo {
            font-size: 1.8rem;
            font-weight: bold;
            color: var(--primary);
            display: flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
        }

        .nav-links {
            display: flex;
            gap: 2rem;
            list-style: none;
            align-items: center;
        }

        .nav-links a {
            text-decoration: none;
            color: var(--dark);
            font-weight: 500;
            transition: color 0.3s;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .nav-links a:hover, .nav-links a.active {
            color: var(--primary);
        }

        .nav-links a i {
            font-size: 1.1rem;
        }

        /* Conteneur principal */
        .container {
            max-width: 1000px;
            margin: 100px auto 40px;
            padding: 0 20px;
        }

        /* Header */
        .page-header {
            text-align: center;
            margin-bottom: 2rem;
            animation: fadeInDown 0.8s ease;
        }

        .page-header h1 {
            font-size: 2.2rem;
            color: var(--secondary);
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.8rem;
        }

        .page-header p {
            color: #666;
            font-size: 1.1rem;
        }

        /* Carte de recherche */
        .search-card {
            background: white;
            padding: 2rem;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
            animation: fadeInUp 0.6s ease;
        }

        .search-form {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .search-form input {
            flex: 1;
            min-width: 300px;
            padding: 1rem 1.5rem;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            font-size: 1.1rem;
            transition: all 0.3s;
        }

        .search-form input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(0, 102, 204, 0.1);
        }

        .search-form button {
            padding: 1rem 2rem;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .search-form button:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(0, 102, 204, 0.3);
        }

        .examples {
            margin-top: 1rem;
            font-size: 0.9rem;
            color: #888;
        }

        /* Message d'erreur */
        .alert-error {
            background: #fff5f5;
            border-left: 4px solid var(--danger);
            color: var(--danger);
            padding: 1.5rem;
            border-radius: 12px;
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            animation: shake 0.5s ease;
        }

        /* Résultat - Score Card */
        .score-card {
            background: white;
            border-radius: 20px;
            padding: 2.5rem;
            box-shadow: 0 15px 50px rgba(0,0,0,0.1);
            animation: fadeInUp 0.8s ease;
            margin-bottom: 2rem;
        }

        .score-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .score-display {
            width: 200px;
            height: 200px;
            margin: 0 auto 1.5rem;
            position: relative;
        }

        .score-circle {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            position: relative;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }

        .score-number {
            font-size: 3.5rem;
            line-height: 1;
        }

        .score-label {
            font-size: 0.9rem;
            text-transform: uppercase;
            margin-top: 0.5rem;
            opacity: 0.9;
        }

        .niveau-badge {
            display: inline-block;
            padding: 0.5rem 1.5rem;
            border-radius: 25px;
            color: white;
            font-weight: 600;
            font-size: 1.1rem;
            margin-top: 1rem;
            text-transform: uppercase;
        }

        /* Info cargaison */
        .cargo-info {
            background: var(--light);
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }

        .cargo-info h3 {
            color: var(--secondary);
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }

        .info-item {
            background: white;
            padding: 1rem;
            border-radius: 10px;
            border-left: 4px solid var(--primary);
        }

        .info-item label {
            font-size: 0.8rem;
            color: #888;
            text-transform: uppercase;
        }

        .info-item .value {
            font-weight: 600;
            color: var(--dark);
            margin-top: 0.2rem;
        }

        /* Facteurs de risque */
        .risks-section {
            margin-top: 2rem;
        }

        .risks-section h3 {
            color: var(--secondary);
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .risk-item {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            border-left: 5px solid;
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            transition: transform 0.3s, box-shadow 0.3s;
            animation: slideIn 0.5s ease;
        }

        .risk-item:hover {
            transform: translateX(5px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }

        .risk-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.3rem;
            flex-shrink: 0;
        }

        .risk-content {
            flex: 1;
        }

        .risk-title {
            font-weight: 600;
            font-size: 1.1rem;
            margin-bottom: 0.3rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .risk-score {
            padding: 0.2rem 0.8rem;
            border-radius: 15px;
            color: white;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .risk-desc {
            color: #666;
            font-size: 0.95rem;
            margin-top: 0.3rem;
        }

        /* Recommandations */
        .reco-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px;
            padding: 2rem;
            margin-top: 2rem;
        }

        .reco-section h3 {
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .reco-list {
            list-style: none;
        }

        .reco-list li {
            padding: 0.8rem 0;
            border-bottom: 1px solid rgba(255,255,255,0.2);
            display: flex;
            align-items: center;
            gap: 0.8rem;
        }

        .reco-list li:last-child {
            border-bottom: none;
        }

        /* Footer */
        footer {
            background: var(--dark);
            color: white;
            padding: 2rem 5%;
            text-align: center;
            margin-top: 3rem;
        }

        footer a {
            color: var(--accent);
            text-decoration: none;
            margin: 0 1rem;
        }

        /* Animations */
        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes slideIn {
            from { opacity: 0; transform: translateX(-20px); }
            to { opacity: 1; transform: translateX(0); }
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-10px); }
            75% { transform: translateX(10px); }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .page-header h1 {
                font-size: 1.5rem;
                flex-direction: column;
            }
            
            .score-display {
                width: 150px;
                height: 150px;
            }
            
            .score-number {
                font-size: 2.5rem;
            }
            
            .search-form {
                flex-direction: column;
            }
            
            .search-form input,
            .search-form button {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav>
        <a href="index.php" class="logo">
            <i class="fas fa-ship"></i>
            <span>PACOCEAN</span>
        </a>
        <ul class="nav-links">
            <li><a href="index.php#home"><i class="fas fa-home"></i> Accueil</a></li>
            <li><a href="index.php#services"><i class="fas fa-cogs"></i> Services</a></li>
            <li><a href="tracking.php"><i class="fas fa-search-location"></i> Suivi</a></li>
            <li><a href="risk_analysis.php" class="active"><i class="fas fa-exclamation-triangle"></i> Analyse des Risques</a></li>
            <li><a href="contact.php"><i class="fas fa-envelope"></i> Contact</a></li>
        </ul>
    </nav>

    <div class="container">
        <!-- Header -->
        <div class="page-header">
            <h1>
                <i class="fas fa-shield-alt"></i>
                Analyse des Risques
            </h1>
            <p>Évaluez les risques associés à vos cargaisons en temps réel</p>
        </div>

        <!-- Formulaire de recherche -->
        <div class="search-card">
            <form method="GET" class="search-form">
                <input 
                    type="text" 
                    name="numero" 
                    placeholder="Entrez le numéro de suivi (ex: PAC-2024-002)" 
                    value="<?php echo isset($_GET['numero']) ? htmlspecialchars($_GET['numero']) : ''; ?>" 
                    required
                >
                <button type="submit">
                    <i class="fas fa-search"></i>
                    Analyser les Risques
                </button>
            </form>
            <div class="examples">
                <i class="fas fa-info-circle"></i>
                Essayez : PAC-2024-001, PAC-2024-002, PAC-2024-003, PAC-2024-004, PAC-2024-005
            </div>
        </div>

        <?php if ($erreur): ?>
            <div class="alert-error">
                <i class="fas fa-exclamation-circle fa-2x"></i>
                <div>
                    <strong><?php echo htmlspecialchars($erreur); ?></strong>
                    <br><small>Vérifiez le numéro et réessayez</small>
                </div>
            </div>
        <?php endif; ?>

        <?php if ($resultat): ?>
            <?php 
            list($color, $darkColor, $emoji) = getRiskColor($resultat['niveau_risque'] ?? 'faible');
            ?>
            
            <!-- Score Card -->
            <div class="score-card">
                <div class="score-header">
                    <div class="score-display">
                        <div class="score-circle" style="background: linear-gradient(135deg, <?php echo $color; ?>, <?php echo $darkColor; ?>);">
                            <span class="score-number"><?php echo $score_total; ?></span>
                            <span class="score-label">/ 100</span>
                        </div>
                    </div>
                    <h2 style="color: <?php echo $color; ?>; margin-bottom: 0.5rem;">
                        <?php echo $emoji; ?> Niveau <?php echo ucfirst($resultat['niveau_risque'] ?? 'Faible'); ?>
                    </h2>
                    <span class="niveau-badge" style="background: <?php echo $color; ?>;">
                        <?php echo strtoupper($resultat['niveau_risque'] ?? 'FAIBLE'); ?>
                    </span>
                </div>

                <!-- Info Cargaison -->
                <div class="cargo-info">
                    <h3><i class="fas fa-box"></i> Cargaison #<?php echo htmlspecialchars($resultat['numero_suivi']); ?></h3>
                    <div class="info-grid">
                        <div class="info-item">
                            <label>Client</label>
                            <div class="value"><?php echo htmlspecialchars($resultat['nom_client']); ?></div>
                        </div>
                        <div class="info-item">
                            <label>Route</label>
                            <div class="value"><?php echo htmlspecialchars($resultat['origine']); ?> → <?php echo htmlspecialchars($resultat['destination']); ?></div>
                        </div>
                        <div class="info-item">
                            <label>Service</label>
                            <div class="value"><?php echo htmlspecialchars($resultat['type_service']); ?></div>
                        </div>
                        <div class="info-item">
                            <label>Statut</label>
                            <div class="value"><?php echo htmlspecialchars($resultat['statut']); ?></div>
                        </div>
                    </div>
                </div>

                <!-- Facteurs de Risque -->
                <div class="risks-section">
                    <h3><i class="fas fa-list-ul"></i> Facteurs de Risque Identifiés</h3>
                    
                    <?php foreach ($risques as $index => $risque): 
                        $rColor = $risque['niveau'] == 'critique' ? '#dc3545' : 
                                 ($risque['niveau'] == 'eleve' ? '#fd7e14' : 
                                 ($risque['niveau'] == 'modere' ? '#ffc107' : '#28a745'));
                    ?>
                        <div class="risk-item" style="border-left-color: <?php echo $rColor; ?>; animation-delay: <?php echo $index * 0.1; ?>s;">
                            <div class="risk-icon" style="background: <?php echo $rColor; ?>;">
                                <i class="fas <?php echo $risque['icon']; ?>"></i>
                            </div>
                            <div class="risk-content">
                                <div class="risk-title">
                                    <?php echo $risque['type']; ?>
                                    <span class="risk-score" style="background: <?php echo $rColor; ?>;">
                                        +<?php echo $risque['score']; ?> pts
                                    </span>
                                </div>
                                <div class="risk-desc"><?php echo $risque['description']; ?></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Recommandations -->
                <?php if (!empty($resultat['recommandations'])): ?>
                <div class="reco-section">
                    <h3><i class="fas fa-clipboard-check"></i> Recommandations Actions</h3>
                    <ul class="reco-list">
                        <?php 
                        $recos = explode("\n", $resultat['recommandations']);
                        foreach ($recos as $reco): 
                            if (trim($reco)):
                        ?>
                            <li><?php echo htmlspecialchars(trim($reco)); ?></li>
                        <?php 
                            endif;
                        endforeach; 
                        ?>
                    </ul>
                </div>
                <?php endif; ?>

                <!-- Date d'analyse -->
                <p style="text-align: center; color: #888; margin-top: 1.5rem; font-size: 0.9rem;">
                    <i class="fas fa-clock"></i>
                    Dernière analyse : <?php echo date('d/m/Y à H:i', strtotime($resultat['date_analyse'] ?? 'now')); ?>
                </p>
            </div>
        <?php endif; ?>
    </div>

    <footer>
        <p>&copy; 2024 PACOCEAN MAGHREB. Tous droits réservés.</p>
        <div>
            <a href="index.php">Accueil</a>
            <a href="tracking.php">Suivi</a>
            <a href="contact.php">Contact</a>
        </div>
    </footer>

    <script>
        // Animation du score
        window.addEventListener('load', () => {
            const circle = document.querySelector('.score-circle');
            if (circle) {
                circle.style.transform = 'scale(0)';
                setTimeout(() => {
                    circle.style.transition = 'transform 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275)';
                    circle.style.transform = 'scale(1)';
                }, 100);
            }
        });
    </script>
</body>
</html>