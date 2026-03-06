<?php
// contact.php - VERSION MODERNISÉE
$message_envoye = false;
$erreur = false;

if (isset($_POST['envoyer'])) {
    $nom = $_POST['nom'];
    $email = $_POST['email'];
    $sujet = $_POST['sujet'];
    $message = $_POST['message'];
    
    if (empty($nom) || empty($email) || empty($message)) {
        $erreur = "Tous les champs obligatoires doivent être remplis.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erreur = "Adresse email invalide.";
    } else {
        $destinataire = "contact@pacocean.ma";
        $sujet_email = "Nouveau message du site - " . $sujet;
        
        $corps_message = "
        Nouveau message reçu depuis le site PACOCEAN :
        
        Nom : $nom
        Email : $email
        Sujet : $sujet
        
        Message :
        $message
        
        ---
        Envoyé depuis le site web le " . date('d/m/Y à H:i:s');
        
        $headers = "From: $email\r\n";
        $headers .= "Reply-To: $email\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
        
        if (mail($destinataire, $sujet_email, $corps_message, $headers)) {
            $message_envoye = true;
            sauvegarder_en_base($nom, $email, $sujet, $message);
        } else {
            $erreur = "Erreur lors de l'envoi du message. Réessayez plus tard.";
        }
    }
}

function sauvegarder_en_base($nom, $email, $sujet, $message) {
    $serveur = "localhost";
    $utilisateur = "root"; 
    $motdepasse = "";
    $basededonnees = "pacocean_db";
    
    $connexion = mysqli_connect($serveur, $utilisateur, $motdepasse, $basededonnees);
    
    if ($connexion) {
        $nom = mysqli_real_escape_string($connexion, $nom);
        $email = mysqli_real_escape_string($connexion, $email);
        $sujet = mysqli_real_escape_string($connexion, $sujet);
        $message = mysqli_real_escape_string($connexion, $message);
        
        $sql = "INSERT INTO contact_messages (nom, email, sujet, message) 
                VALUES ('$nom', '$email', '$sujet', '$message')";
        
        mysqli_query($connexion, $sql);
        mysqli_close($connexion);
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact - PACOCEAN MAGHREB</title>
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

        .container {
            max-width: 1200px;
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

        .content-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            margin-bottom: 3rem;
        }

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

        .alert-success {
            background: #d4edda;
            border-left: 4px solid var(--success);
            color: var(--success);
        }

        .alert-error {
            background: #fff5f5;
            border-left: 4px solid var(--danger);
            color: var(--danger);
        }

        .form-card {
            background: white;
            padding: 2.5rem;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            animation: fadeInLeft 0.8s ease;
        }

        .form-card h2 {
            color: var(--secondary);
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: var(--dark);
        }

        .form-group label .required {
            color: var(--danger);
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 1rem;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 1rem;
            font-family: inherit;
            transition: all 0.3s;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(0, 102, 204, 0.1);
        }

        .form-group textarea {
            resize: vertical;
            min-height: 150px;
        }

        .btn {
            padding: 1rem 2.5rem;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 15px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
        }

        .btn:hover {
            background: var(--secondary);
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(0, 102, 204, 0.3);
        }

        .btn-block {
            width: 100%;
            justify-content: center;
        }

        .info-card {
            background: white;
            padding: 2.5rem;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            animation: fadeInRight 0.8s ease;
        }

        .info-card h2 {
            color: var(--secondary);
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .contact-item {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            margin-bottom: 1.5rem;
            padding: 1.5rem;
            background: var(--light);
            border-radius: 15px;
            transition: all 0.3s;
        }

        .contact-item:hover {
            background: #e3f2fd;
            transform: translateX(5px);
        }

        .contact-item i {
            font-size: 1.5rem;
            color: var(--primary);
            margin-top: 0.2rem;
        }

        .contact-item-content h4 {
            margin-bottom: 0.3rem;
            color: var(--secondary);
        }

        .contact-item-content p {
            color: #666;
        }

        .social-links {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
        }

        .social-links a {
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--primary);
            color: white;
            border-radius: 50%;
            text-decoration: none;
            transition: all 0.3s;
        }

        .social-links a:hover {
            background: var(--secondary);
            transform: translateY(-5px);
        }

        .map-container {
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            height: 400px;
            margin-top: 2rem;
        }

        .map-container iframe {
            width: 100%;
            height: 100%;
            border: none;
        }

        .success-content {
            text-align: center;
            padding: 2rem;
        }

        .success-content i {
            font-size: 5rem;
            color: var(--success);
            margin-bottom: 1rem;
        }

        .success-content h2 {
            color: var(--success);
            margin-bottom: 1rem;
        }

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

        @keyframes fadeInLeft {
            from {
                opacity: 0;
                transform: translateX(-30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes fadeInRight {
            from {
                opacity: 0;
                transform: translateX(30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @media (max-width: 968px) {
            .content-grid {
                grid-template-columns: 1fr;
            }

            .page-header h1 {
                font-size: 1.8rem;
            }
        }
    </style>
</head>
<body>
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

    <div class="container">
        <div class="page-header">
            <h1><i class="fas fa-envelope"></i> Contactez-Nous</h1>
            <p>Nous sommes à votre écoute pour toute question ou demande</p>
        </div>

        <?php if ($message_envoye): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <div>
                    <strong>Message envoyé avec succès !</strong><br>
                    <small>Nous vous répondrons dans les plus brefs délais.</small>
                </div>
            </div>
            <div class="form-card success-content">
                <i class="fas fa-paper-plane"></i>
                <h2>Merci pour votre message !</h2>
                <p>Notre équipe vous contactera sous 24-48 heures.</p>
                <br>
                <a href="index.php" class="btn">
                    <i class="fas fa-home"></i>
                    Retour à l'accueil
                </a>
            </div>

        <?php else: ?>
            
            <?php if ($erreur): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i>
                    <div>
                        <strong>Erreur :</strong> <?php echo htmlspecialchars($erreur); ?>
                    </div>
                </div>
            <?php endif; ?>

            <div class="content-grid">
                <div class="form-card">
                    <h2><i class="fas fa-paper-plane"></i> Envoyez-nous un message</h2>
                    <form method="POST" action="">
                        <div class="form-group">
                            <label for="nom">
                                Nom complet <span class="required">*</span>
                            </label>
                            <input 
                                type="text" 
                                id="nom" 
                                name="nom" 
                                value="<?php echo isset($_POST['nom']) ? htmlspecialchars($_POST['nom']) : ''; ?>" 
                                placeholder="Votre nom"
                                required
                            >
                        </div>
                        
                        <div class="form-group">
                            <label for="email">
                                Adresse email <span class="required">*</span>
                            </label>
                            <input 
                                type="email" 
                                id="email" 
                                name="email" 
                                value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" 
                                placeholder="votre@email.com"
                                required
                            >
                        </div>
                        
                        <div class="form-group">
                            <label for="sujet">Sujet</label>
                            <input 
                                type="text" 
                                id="sujet" 
                                name="sujet" 
                                value="<?php echo isset($_POST['sujet']) ? htmlspecialchars($_POST['sujet']) : ''; ?>" 
                                placeholder="Objet de votre demande"
                            >
                        </div>
                        
                        <div class="form-group">
                            <label for="message">
                                Message <span class="required">*</span>
                            </label>
                            <textarea 
                                id="message" 
                                name="message" 
                                placeholder="Décrivez votre demande en détail..."
                                required
                            ><?php echo isset($_POST['message']) ? htmlspecialchars($_POST['message']) : ''; ?></textarea>
                        </div>
                        
                        <button type="submit" name="envoyer" class="btn btn-block">
                            <i class="fas fa-paper-plane"></i>
                            Envoyer le message
                        </button>
                    </form>
                </div>

                <div class="info-card">
                    <h2><i class="fas fa-info-circle"></i> Nos Coordonnées</h2>
                    
                    <div class="contact-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <div class="contact-item-content">
                            <h4>Adresse</h4>
                            <p>Boulevard d'Anfa<br>Casablanca 20000, Maroc</p>
                        </div>
                    </div>

                    <div class="contact-item">
                        <i class="fas fa-phone"></i>
                        <div class="contact-item-content">
                            <h4>Téléphone</h4>
                            <p>+212 522 XX XX XX</p>
                        </div>
                    </div>

                    <div class="contact-item">
                        <i class="fas fa-envelope"></i>
                        <div class="contact-item-content">
                            <h4>Email</h4>
                            <p>contact@pacocean.ma</p>
                        </div>
                    </div>

                    <div class="contact-item">
                        <i class="fas fa-clock"></i>
                        <div class="contact-item-content">
                            <h4>Horaires d'ouverture</h4>
                            <p>Lun-Ven : 8h00 - 18h00<br>Sam : 9h00 - 13h00</p>
                        </div>
                    </div>

                    <div class="social-links">
                        <a href="#" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" title="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#" title="Instagram"><i class="fab fa-instagram"></i></a>
                        <a href="#" title="Twitter"><i class="fab fa-twitter"></i></a>
                    </div>
                </div>
            </div>

            <div class="map-container">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d106421.67575757576!2d-7.6177!3d33.5731!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xda7cd4778aa113b%3A0xb06c1d84f310fd3!2sCasablanca!5e0!3m2!1sen!2sma!4v1234567890" allowfullscreen="" loading="lazy"></iframe>
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
</body>
</html>