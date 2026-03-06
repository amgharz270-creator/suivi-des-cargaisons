<?php
// tracking.php - VERSION MODERNISÉE
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

if (isset($_GET['numero']) && !empty($_GET['numero'])) {
    $numero_suivi = mysqli_real_escape_string($connexion, $_GET['numero']);
    $sql = "SELECT * FROM tracking WHERE numero_suivi = '$numero_suivi'";
    $query = mysqli_query($connexion, $sql);
    
    if (mysqli_num_rows($query) > 0) {
        $resultat = mysqli_fetch_assoc($query);
    } else {
        $erreur = "Numéro de suivi non trouvé !";
    }
}

mysqli_close($connexion);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suivi de Cargaison - PACOCEAN MAGHREB</title>
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
            --danger: #dc3545;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, var(--light) 0%, #e3f2fd 100%);
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
        }

        .nav-links a:hover {
            color: var(--primary);
        }

        /* Conteneur principal */
        .container {
            max-width: 900px;
            margin: 120px auto 60px;
            padding: 0 20px;
        }

        .page-header {
            text-align: center;
            margin-bottom: 3rem;
            animation: fadeInDown 0.8s ease;
        }

        .page-header h1 {
            font-size: 2.5rem;
            color: var(--secondary);
            margin-bottom: 0.5rem;
        }

        .page-header p {
            font-size: 1.1rem;
            color: #666;
        }

        /* Formulaire de recherche */
        .search-card {
            background: white;
            padding: 2.5rem;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
            animation: fadeInUp 0.8s ease;
        }

        .search-form {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .search-form input {
            flex: 1;
            min-width: 250px;
            padding: 1rem 1.5rem;
            border: 2px solid #e0e0e0;
            border-radius: 15px;
            font-size: 1.1rem;
            transition: all 0.3s;
        }

        .search-form input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(0, 102, 204, 0.1);
        }

        .search-form button {
            padding: 1rem 2.5rem;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 15px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .search-form button:hover {
            background: var(--secondary);
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(0, 102, 204, 0.3);
        }

        .examples {
            margin-top: 1rem;
            font-size: 0.9rem;
            color: #666;
        }

        /* Messages */
        .alert {
            padding: 1.5rem;
            border-radius: 15px;
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            animation: fadeInUp 0.8s ease;
        }

        .alert i {
            font-size: 1.5rem;
        }

        .alert-error {
            background: #fff5f5;
            border-left: 4px solid var(--danger);
            color: var(--danger);
        }

        /* Carte de résultat */
        .result-card {
            background: white;
            padding: 2.5rem;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            animation: fadeInUp 0.8s ease;
        }

        .result-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 2rem;
            padding-bottom: 1.5rem;
            border-bottom: 2px solid var(--light);
        }

        .result-header i {
            font-size: 3rem;
            color: var(--primary);
        }

        .result-header h2 {
            font-size: 2rem;
            color: var(--secondary);
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .info-item {
            background: var(--light);
            padding: 1.5rem;
            border-radius: 15px;
            transition: all 0.3s;
        }

        .info-item:hover {
            background: #e3f2fd;
            transform: translateX(5px);
        }

        .info-item label {
            display: block;
            font-size: 0.85rem;
            color: #666;
            margin-bottom: 0.5rem;
            text-transform: uppercase;
            font-weight: 600;
        }

        .info-item .value {
            font-size: 1.1rem;
            color: var(--dark);
            font-weight: 500;
        }

        .status-badge {
            display: inline-block;
            padding: 0.5rem 1.5rem;
            background: var(--success);
            color: white;
            border-radius: 20px;
            font-weight: 600;
            font-size: 1rem;
        }

        /* Barre de progression */
        .progress-section {
            margin: 2rem 0;
            padding: 2rem;
            background: linear-gradient(135deg, #e3f2fd 0%, var(--light) 100%);
            border-radius: 15px;
        }

        .progress-section h3 {
            margin-bottom: 1rem;
            color: var(--secondary);
        }

        .progress-bar {
            background: #e0e0e0;
            height: 30px;
            border-radius: 15px;
            overflow: hidden;
            position: relative;
            box-shadow: inset 0 2px 5px rgba(0,0,0,0.1);
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--primary), var(--accent));
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            padding-right: 1rem;
            color: white;
            font-weight: 600;
            transition: width 1s ease;
            box-shadow: 0 2px 10px rgba(0, 102, 204, 0.3);
        }

        /* Timeline */
        .timeline {
            margin-top: 2rem;
        }

        .timeline-item {
            display: flex;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .timeline-icon {
            width: 40px;
            height: 40px;
            background: var(--primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            flex-shrink: 0;
        }

        .timeline-content {
            flex: 1;
            padding: 1rem;
            background: var(--light);
            border-radius: 10px;
        }

        /* Footer */
        footer {
            background: var(--dark);
            color: white;
            padding: 2rem 5%;
            text-align: center;
            margin-top: 4rem;
        }

        footer a {
            color: var(--accent);
            text-decoration: none;
            margin: 0 1rem;
        }

        footer a:hover {
            text-decoration: underline;
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .page-header h1 {
                font-size: 1.8rem;
            }

            .search-form {
                flex-direction: column;
            }

            .search-form input,
            .search-form button {
                width: 100%;
            }

            .info-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav id="navbar">
    <div class="logo">
        <i class="fas fa-ship"></i>
        <span>PACOCEAN</span>
    </div>
    <ul class="nav-links" id="navLinks">
        <li><a href="#home" data-fr="Accueil" data-en="Home"><i class="fas fa-home"></i> Accueil</a></li>
        <li><a href="#services" data-fr="Services" data-en="Services"><i class="fas fa-cogs"></i> Services</a></li>
        <li><a href="tracking.php" data-fr="Suivi" data-en="Tracking"><i class="fas fa-search-location"></i> Suivi</a></li>
        <li><a href="risk_analysis.php" data-fr="Analyse des Risques" data-en="Risk Analysis" style="color: #dc3545;"><i class="fas fa-exclamation-triangle"></i> Analyse des Risques</a></li>
        <li><a href="#about" data-fr="À propos" data-en="About"><i class="fas fa-info-circle"></i> À propos</a></li>
        <li><a href="contact.php" data-fr="Contact" data-en="Contact"><i class="fas fa-envelope"></i> Contact</a></li>
        <li><button class="lang-switch" onclick="toggleLanguage()">EN</button></li>
    </ul>
    <div class="mobile-menu" onclick="toggleMenu()">
        <i class="fas fa-bars"></i>
    </div>
</nav>

    <!-- Conteneur principal -->
    <div class="container">
        <!-- En-tête de page -->
        <div class="page-header">
            <h1><i class="fas fa-search-location"></i> Suivi de Cargaison</h1>
            <p>Suivez votre cargaison en temps réel</p>
        </div>

        <!-- Formulaire de recherche -->
        <div class="search-card">
            <form method="GET" class="search-form">
                <input 
                    type="text" 
                    name="numero" 
                    placeholder="Entrez votre numéro de suivi (ex: PAC-2024-001)" 
                    value="<?php echo isset($_GET['numero']) ? htmlspecialchars($_GET['numero']) : ''; ?>" 
                    required
                >
                <button type="submit">
                    <i class="fas fa-search"></i>
                    <span>Rechercher</span>
                </button>
            </form>
            <div class="examples">
                <i class="fas fa-info-circle"></i>
                Exemples de numéros : PAC-2024-001, PAC-2024-002, PAC-2024-003
            </div>
        </div>

        <?php if ($erreur): ?>
            <!-- Message d'erreur -->
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <div>
                    <strong>Erreur :</strong> <?php echo htmlspecialchars($erreur); ?>
                    <br><small>Veuillez vérifier votre numéro de suivi et réessayer.</small>
                </div>
            </div>
        <?php endif; ?>

        <?php if ($resultat): ?>
            <!-- Résultats -->
            <div class="result-card">
                <div class="result-header">
                    <i class="fas fa-box"></i>
                    <div>
                        <h2>Informations de Cargaison</h2>
                        <p>Numéro : <?php echo htmlspecialchars($resultat['numero_suivi']); ?></p>
                    </div>
                </div>

                <!-- Grille d'informations -->
                <div class="info-grid">
                    <div class="info-item">
                        <label><i class="fas fa-user"></i> Client</label>
                        <div class="value"><?php echo htmlspecialchars($resultat['nom_client']); ?></div>
                    </div>
                    <div class="info-item">
                        <label><i class="fas fa-map-marker-alt"></i> Origine</label>
                        <div class="value"><?php echo htmlspecialchars($resultat['origine']); ?></div>
                    </div>
                    <div class="info-item">
                        <label><i class="fas fa-flag-checkered"></i> Destination</label>
                        <div class="value"><?php echo htmlspecialchars($resultat['destination']); ?></div>
                    </div>
                    <div class="info-item">
                        <label><i class="fas fa-truck"></i> Service</label>
                        <div class="value"><?php echo htmlspecialchars($resultat['type_service']); ?></div>
                    </div>
                    <div class="info-item">
                        <label><i class="fas fa-info-circle"></i> Statut</label>
                        <div class="value">
                            <span class="status-badge"><?php echo htmlspecialchars($resultat['statut']); ?></span>
                        </div>
                    </div>
                    <div class="info-item">
                        <label><i class="fas fa-location-arrow"></i> Localisation</label>
                        <div class="value"><?php echo htmlspecialchars($resultat['localisation_actuelle']); ?></div>
                    </div>
                    <?php if ($resultat['date_eta']): ?>
                    <div class="info-item">
                        <label><i class="fas fa-calendar-check"></i> Arrivée Prévue</label>
                        <div class="value"><?php echo date('d/m/Y', strtotime($resultat['date_eta'])); ?></div>
                    </div>
                    <?php endif; ?>
                    <?php if ($resultat['poids']): ?>
                    <div class="info-item">
                        <label><i class="fas fa-weight"></i> Poids</label>
                        <div class="value"><?php echo htmlspecialchars($resultat['poids']); ?> kg</div>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Progression -->
                <div class="progress-section">
                    <h3><i class="fas fa-chart-line"></i> Progression du Transport</h3>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: <?php echo $resultat['progression']; ?>%">
                            <?php echo $resultat['progression']; ?>%
                        </div>
                    </div>
                </div>

                <?php if ($resultat['description']): ?>
                <div class="info-item" style="grid-column: 1 / -1;">
                    <label><i class="fas fa-file-alt"></i> Description</label>
                    <div class="value"><?php echo nl2br(htmlspecialchars($resultat['description'])); ?></div>
                </div>
                <?php endif; ?>

                <!-- Dates -->
                <div class="timeline">
                    <div class="timeline-item">
                        <div class="timeline-icon"><i class="fas fa-calendar-plus"></i></div>
                        <div class="timeline-content">
                            <strong>Créé le</strong><br>
                            <?php echo date('d/m/Y à H:i', strtotime($resultat['date_creation'])); ?>
                        </div>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-icon"><i class="fas fa-sync"></i></div>
                        <div class="timeline-content">
                            <strong>Dernière mise à jour</strong><br>
                            <?php echo date('d/m/Y à H:i', strtotime($resultat['date_modification'])); ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Footer -->
    <footer>
        <p>&copy; 2024 PACOCEAN MAGHREB. Tous droits réservés.</p>
        <div>
            <a href="index.php">Accueil</a>
            <a href="contact.php">Contact</a>
            <a href="tracking.php">Suivi</a>
        </div>
    </footer>

    <script>
        // Animation de la barre de progression
        window.addEventListener('load', () => {
            const progressFill = document.querySelector('.progress-fill');
            if (progressFill) {
                const width = progressFill.style.width;
                progressFill.style.width = '0%';
                setTimeout(() => {
                    progressFill.style.width = width;
                }, 100);
            }
        });
    </script>
</body>
</html>