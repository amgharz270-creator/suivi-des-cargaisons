<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PACOCEAN MAGHREB - Logistique Internationale</title>
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
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow-x: hidden;
            color: var(--dark);
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
            transition: all 0.3s;
        }

        nav.scrolled {
            padding: 0.5rem 5%;
            background: rgba(255, 255, 255, 0.98);
        }

        .logo {
            font-size: 1.8rem;
            font-weight: bold;
            color: var(--primary);
            display: flex;
            align-items: center;
            gap: 0.5rem;
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
            position: relative;
        }

        .nav-links a:hover {
            color: var(--primary);
        }

        .nav-links a::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--primary);
            transition: width 0.3s;
        }

        .nav-links a:hover::after {
            width: 100%;
        }

        .lang-switch {
            background: var(--primary);
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s;
        }

        .lang-switch:hover {
            background: var(--secondary);
            transform: translateY(-2px);
        }

        .mobile-menu {
            display: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: var(--primary);
        }

        /* Hero Section avec Parallaxe */
        .hero {
            height: 100vh;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .parallax-bg {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 120%;
            background: linear-gradient(135deg, rgba(0,61,122,0), rgba(0,102,204,0)), 
                        url('https://images.unsplash.com/photo-1566576721346-d4a3b4eaeb55?w=1920') center/cover;
            transform: translateZ(-1px) scale(1.5);
            z-index: -1;
        }

        .hero-content {
            text-align: center;
            color: white;
            z-index: 2;
            padding: 2rem;
            animation: fadeInUp 1s ease;
        }

        .hero h1 {
            font-size: 4rem;
            margin-bottom: 1rem;
            text-shadow: 2px 2px 10px rgba(0,0,0,0.3);
        }

        .hero p {
            font-size: 1.5rem;
            margin-bottom: 2rem;
            text-shadow: 1px 1px 5px rgba(0,0,0,0.3);
        }

        .cta-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn {
            padding: 1rem 2rem;
            border: none;
            border-radius: 30px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary {
            background: white;
            color: var(--primary);
        }

        .btn-primary:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(255,255,255,0.3);
        }

        .btn-secondary {
            background: transparent;
            color: white;
            border: 2px solid white;
        }

        .btn-secondary:hover {
            background: white;
            color: var(--primary);
            transform: translateY(-5px);
        }

        /* Stats Section */
        .stats {
            background: var(--light);
            padding: 4rem 5%;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 2rem;
        }

        .stat-card {
            text-align: center;
            padding: 2rem;
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-10px);
        }

        .stat-card i {
            font-size: 3rem;
            color: var(--primary);
            margin-bottom: 1rem;
        }

        .stat-card h3 {
            font-size: 2.5rem;
            color: var(--primary);
            margin-bottom: 0.5rem;
        }

        /* Services Section avec Parallaxe */
        .services {
            padding: 6rem 5%;
            background: linear-gradient(to bottom, white, var(--light));
            position: relative;
        }

        .section-title {
            text-align: center;
            font-size: 2.5rem;
            color: var(--secondary);
            margin-bottom: 3rem;
            position: relative;
        }

        .section-title::after {
            content: '';
            display: block;
            width: 100px;
            height: 4px;
            background: var(--primary);
            margin: 1rem auto;
            border-radius: 2px;
        }

        .services-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
        }

        .service-card {
            background: white;
            padding: 2.5rem;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
        }

        .service-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(90deg, var(--primary), var(--accent));
            transform: scaleX(0);
            transition: transform 0.3s;
        }

        .service-card:hover::before {
            transform: scaleX(1);
        }

        .service-card:hover {
            transform: translateY(-15px);
            box-shadow: 0 20px 60px rgba(0,102,204,0.2);
        }

        .service-card i {
            font-size: 3.5rem;
            color: var(--primary);
            margin-bottom: 1.5rem;
        }

        .service-card h3 {
            font-size: 1.5rem;
            margin-bottom: 1rem;
            color: var(--secondary);
        }

        /* Tracking Section avec image parallaxe */
        .tracking {
            padding: 6rem 5%;
            background: linear-gradient(135deg, rgba(0,61,122,0), rgba(0,102,204,0)),
                        url('https://images.unsplash.com/photo-1578575437130-527eed3abbec?w=1920') center/cover fixed;
            color: white;
            position: relative;
        }

        .tracking-container {
            max-width: 800px;
            margin: 0 auto;
            background: rgba(255,255,255,0.1);
            backdrop-filter: blur(20px);
            padding: 3rem;
            border-radius: 30px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }

        .tracking-form {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
            flex-wrap: wrap;
        }

        .tracking-form input {
            flex: 1;
            min-width: 250px;
            padding: 1.2rem;
            border: none;
            border-radius: 15px;
            font-size: 1.1rem;
            background: rgba(255,255,255,0.9);
        }

        .tracking-form button {
            padding: 1.2rem 2.5rem;
            background: white;
            color: var(--primary);
            border: none;
            border-radius: 15px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }

        .tracking-form button:hover {
            transform: scale(1.05);
            box-shadow: 0 10px 30px rgba(255,255,255,0.3);
        }

        /* About Section */
        .about {
            padding: 6rem 5%;
            background: white;
        }

        .about-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4rem;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
        }

        .about-text h2 {
            font-size: 2.5rem;
            color: var(--secondary);
            margin-bottom: 1.5rem;
        }

        .about-text p {
            font-size: 1.1rem;
            line-height: 1.8;
            margin-bottom: 1rem;
            color: #555;
        }

        .values {
            display: grid;
            gap: 1.5rem;
            margin-top: 2rem;
        }

        .value-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            background: var(--light);
            border-radius: 10px;
            transition: all 0.3s;
        }

        .value-item:hover {
            background: var(--primary);
            color: white;
            transform: translateX(10px);
        }

        .value-item i {
            font-size: 2rem;
            color: var(--primary);
        }

        .value-item:hover i {
            color: white;
        }

        .about-image {
            position: relative;
            height: 500px;
            border-radius: 30px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0,0,0,0.2);
        }

        .about-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s;
        }

        .about-image:hover img {
            transform: scale(1.1);
        }

        /* Contact Section */
        .contact {
            padding: 6rem 5%;
            background: var(--light);
        }

        .contact-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 3rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .contact-info {
            background: white;
            padding: 2.5rem;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        }

        .contact-item {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            margin-bottom: 2rem;
            padding: 1.5rem;
            background: var(--light);
            border-radius: 15px;
            transition: all 0.3s;
        }

        .contact-item:hover {
            background: var(--primary);
            color: white;
            transform: translateX(10px);
        }

        .contact-item i {
            font-size: 2rem;
            color: var(--primary);
            min-width: 40px;
        }

        .contact-item:hover i {
            color: white;
        }

        .map-container {
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            height: 500px;
        }

        .map-container iframe {
            width: 100%;
            height: 100%;
            border: none;
        }

        /* Footer */
        footer {
            background: var(--dark);
            color: white;
            padding: 3rem 5%;
            text-align: center;
        }

        .footer-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .footer-section h3 {
            margin-bottom: 1rem;
            color: var(--accent);
        }

        .footer-section a {
            color: white;
            text-decoration: none;
            display: block;
            margin-bottom: 0.5rem;
            transition: color 0.3s;
        }

        .footer-section a:hover {
            color: var(--accent);
        }

        .social-links {
            display: flex;
            gap: 1rem;
            justify-content: center;
            margin-top: 1rem;
        }

        .social-links a {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--primary);
            border-radius: 50%;
            transition: all 0.3s;
        }

        .social-links a:hover {
            background: var(--accent);
            transform: translateY(-5px);
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

        .fade-in {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.8s ease;
        }

        .fade-in.visible {
            opacity: 1;
            transform: translateY(0);
        }

        /* Responsive */
        @media (max-width: 968px) {
            .nav-links {
                position: fixed;
                top: 70px;
                right: -100%;
                width: 100%;
                height: calc(100vh - 70px);
                background: rgba(255,255,255,0.98);
                flex-direction: column;
                padding: 2rem;
                transition: right 0.3s;
            }

            .nav-links.active {
                right: 0;
            }

            .mobile-menu {
                display: block;
            }

            .hero h1 {
                font-size: 2.5rem;
            }

            .hero p {
                font-size: 1.2rem;
            }

            .about-content,
            .contact-grid {
                grid-template-columns: 1fr;
            }

            .about-image {
                height: 300px;
            }
        }

        @media (max-width: 568px) {
            .hero h1 {
                font-size: 2rem;
            }

            .tracking-form {
                flex-direction: column;
            }

            .tracking-form input,
            .tracking-form button {
                width: 100%;
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

    <!-- Hero Section -->
    <section class="hero" id="home">
        <div class="parallax-bg"></div>
        <div class="hero-content">
            <h1 data-fr="Votre Partenaire Logistique Mondial" data-en="Your Global Logistics Partner">Votre Partenaire Logistique Mondial</h1>
            <p data-fr="Transport maritime international - Solutions complètes pour vos cargaisons" 
               data-en="International Maritime Transport - Complete Solutions for Your Cargo">
               Transport maritime international - Solutions complètes pour vos cargaisons
            </p>
            <div class="cta-buttons">
                <a href="#tracking" class="btn btn-primary">
                    <span data-fr="Suivre ma cargaison" data-en="Track My Cargo">Suivre ma cargaison</span>
                </a>
                <a href="#services" class="btn btn-secondary">
                    <span data-fr="Nos Services" data-en="Our Services">Nos Services</span>
                </a>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats">
        <div class="stat-card fade-in">
            <i class="fas fa-globe"></i>
            <h3>150+</h3>
            <p data-fr="Pays desservis" data-en="Countries Served">Pays desservis</p>
        </div>
        <div class="stat-card fade-in">
            <i class="fas fa-box"></i>
            <h3>50K+</h3>
            <p data-fr="Cargaisons livrées" data-en="Cargo Delivered">Cargaisons livrées</p>
        </div>
        <div class="stat-card fade-in">
            <i class="fas fa-users"></i>
            <h3>2000+</h3>
            <p data-fr="Clients satisfaits" data-en="Satisfied Clients">Clients satisfaits</p>
        </div>
        <div class="stat-card fade-in">
            <i class="fas fa-clock"></i>
            <h3>24/7</h3>
            <p data-fr="Support client" data-en="Customer Support">Support client</p>
        </div>
    </section>

    <!-- Services Section -->
    <section class="services" id="services">
        <h2 class="section-title" data-fr="Nos Services Maritimes" data-en="Our Maritime Services">Nos Services Maritimes</h2>
        <div class="services-grid">
            <div class="service-card fade-in">
                <i class="fas fa-ship"></i>
                <h3 data-fr="Fret Maritime" data-en="Maritime Freight">Fret Maritime</h3>
                <p data-fr="Transport de conteneurs et cargaisons volumineuses par voie maritime. Solutions FCL et LCL adaptées à vos besoins."
                   data-en="Container and bulk cargo transport by sea. FCL and LCL solutions tailored to your needs.">
                   Transport de conteneurs et cargaisons volumineuses par voie maritime. Solutions FCL et LCL adaptées à vos besoins.
                </p>
            </div>
        </div>
    </section>

    <!-- Tracking Section -->
    <section class="tracking" id="tracking">
        <div class="tracking-container">
            <h2 class="section-title" style="color: white;" 
                data-fr="Suivez Votre Cargaison" data-en="Track Your Cargo">
                Suivez Votre Cargaison
            </h2>
            <p style="text-align: center; font-size: 1.1rem; margin-bottom: 2rem;">
                <span data-fr="Entrez votre numéro de suivi pour connaître l'état de votre cargaison en temps réel"
                      data-en="Enter your tracking number to know the status of your cargo in real time">
                    Entrez votre numéro de suivi pour connaître l'état de votre cargaison en temps réel
                </span>
            </p>
            <form class="tracking-form" action="tracking.php" method="GET">
                <input type="text" name="numero" placeholder="Ex: PAC-2024-001" required>
                <button type="submit">
                    <i class="fas fa-search"></i>
                    <span data-fr=" Rechercher" data-en=" Search"> Rechercher</span>
                </button>
            </form>
            <p style="text-align: center; margin-top: 1rem; font-size: 0.9rem; opacity: 0.9;">
                <span data-fr="Exemples: PAC-2024-001, PAC-2024-002, PAC-2024-003"
                      data-en="Examples: PAC-2024-001, PAC-2024-002, PAC-2024-003">
                    Exemples: PAC-2024-001, PAC-2024-002, PAC-2024-003
                </span>
            </p>
        </div>
    </section>

    <!-- About Section -->
    <section class="about" id="about">
        <div class="about-content">
            <div class="about-text fade-in">
                <h2 data-fr="À Propos de PACOCEAN MAGHREB" data-en="About PACOCEAN MAGHREB">À Propos de PACOCEAN MAGHREB</h2>
                <p data-fr="Leader en logistique internationale basée à Casablanca, nous offrons des solutions complètes de transport maritime depuis plus de 15 ans. Notre expertise est centrée sur le fret maritime et la logistique portuaire."
                   data-en="Leader in international logistics based in Casablanca, we have been offering complete maritime transport solutions for over 15 years. Our expertise is focused on maritime freight and port logistics.">
                    Leader en logistique internationale basée à Casablanca, nous offrons des solutions complètes de transport maritime depuis plus de 15 ans. Notre expertise est centrée sur le fret maritime et la logistique portuaire.
                </p>
                <p data-fr="Nous sommes conformes aux normes internationales ISO 9001, ISO 14001 et respectons les régulations IATA et IMO."
                   data-en="We comply with international standards ISO 9001, ISO 14001 and respect IATA and IMO regulations.">
                    Nous sommes conformes aux normes internationales ISO 9001, ISO 14001 et respectons les régulations IATA et IMO.
                </p>
                <div class="values">
                    <div class="value-item">
                        <i class="fas fa-check-circle"></i>
                        <span data-fr="Excellence opérationnelle" data-en="Operational excellence">Excellence opérationnelle</span>
                    </div>
                    <div class="value-item">
                        <i class="fas fa-shield-alt"></i>
                        <span data-fr="Sécurité et conformité" data-en="Safety and compliance">Sécurité et conformité</span>
                    </div>
                    <div class="value-item">
                        <i class="fas fa-leaf"></i>
                        <span data-fr="Engagement environnemental" data-en="Environmental commitment">Engagement environnemental</span>
                    </div>
                    <div class="value-item">
                        <i class="fas fa-handshake"></i>
                        <span data-fr="Partenariat de confiance" data-en="Trusted partnership">Partenariat de confiance</span>
                    </div>
                </div>
            </div>
            <div class="about-image fade-in">
                <img src="https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?w=800" alt="PACOCEAN Team">
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="contact" id="contact">
        <h2 class="section-title" data-fr="Contactez-Nous" data-en="Contact Us">Contactez-Nous</h2>
        <div class="contact-grid">
            <div class="contact-info fade-in">
                <h3 data-fr="Nos Coordonnées" data-en="Our Contact Information">Nos Coordonnées</h3>
                <div class="contact-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <div>
                        <h4 data-fr="Adresse" data-en="Address">Adresse</h4>
                        <p>Boulevard d'Anfa, Casablanca 20000, Maroc</p>
                    </div>
                </div>
                <div class="contact-item">
                    <i class="fas fa-phone"></i>
                    <div>
                        <h4 data-fr="Téléphone" data-en="Phone">Téléphone</h4>
                        <p>+212 522 XX XX XX</p>
                    </div>
                </div>
                <div class="contact-item">
                    <i class="fas fa-envelope"></i>
                    <div>
                        <h4>Email</h4>
                        <p>contact@pacocean.ma</p>
                    </div>
                </div>
                <div class="contact-item">
                    <i class="fas fa-clock"></i>
                    <div>
                        <h4 data-fr="Horaires" data-en="Hours">Horaires</h4>
                        <p data-fr="Lun-Ven: 8h-18h | Sam: 9h-13h" data-en="Mon-Fri: 8am-6pm | Sat: 9am-1pm">Lun-Ven: 8h-18h | Sam: 9h-13h</p>
                    </div>
                </div>
                <a href="contact.php" class="btn btn-primary" style="margin-top: 1rem; width: 100%;">
                    <span data-fr="Formulaire de Contact" data-en="Contact Form">Formulaire de Contact</span>
                </a>
                <div class="social-links">
                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                    <a href="#"><i class="fab fa-linkedin-in"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                </div>
            </div>
            <div class="map-container fade-in">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d106421.67575757576!2d-7.6177!3d33.5731!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xda7cd4778aa113b%3A0xb06c1d84f310fd3!2sCasablanca!5e0!3m2!1sen!2sma!4v1234567890" allowfullscreen="" loading="lazy"></iframe>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="footer-content">
            <div class="footer-section">
                <h3>PACOCEAN MAGHREB</h3>
                <p data-fr="Votre partenaire de confiance en logistique internationale depuis 2009."
                   data-en="Your trusted partner in international logistics since 2009.">
                    Votre partenaire de confiance en logistique internationale depuis 2009.
                </p>
            </div>
            <div class="footer-section">
                <h3 data-fr="Services" data-en="Services">Services</h3>
                <a href="#" data-fr="Fret Maritime" data-en="Maritime Freight">Fret Maritime</a>
            </div>
            <div class="footer-section">
                <h3 data-fr="Liens Rapides" data-en="Quick Links">Liens Rapides</h3>
                <a href="#about" data-fr="À propos" data-en="About">À propos</a>
                <a href="tracking.php" data-fr="Suivi de cargaison" data-en="Cargo Tracking">Suivi de cargaison</a>
                <a href="contact.php" data-fr="Contact" data-en="Contact">Contact</a>
                <a href="#" data-fr="Politique de confidentialité" data-en="Privacy Policy">Politique de confidentialité</a>
            </div>
        </div>
        <hr style="border-color: rgba(255,255,255,0.1); margin: 2rem 0;">
        <p>&copy; 2024 PACOCEAN MAGHREB. <span data-fr="Tous droits réservés" data-en="All rights reserved">Tous droits réservés</span>.</p>
    </footer>

    <script>
        // Variables globales
        let currentLang = 'fr';

        // Fonction pour changer la langue
        function toggleLanguage() {
            currentLang = currentLang === 'fr' ? 'en' : 'fr';
            const elements = document.querySelectorAll('[data-fr][data-en]');
            
            elements.forEach(element => {
                element.textContent = element.getAttribute(`data-${currentLang}`);
            });
            
            document.querySelector('.lang-switch').textContent = currentLang === 'fr' ? 'EN' : 'FR';
        }

        // Menu mobile
        function toggleMenu() {
            document.getElementById('navLinks').classList.toggle('active');
        }

        // Effet parallaxe sur scroll
        window.addEventListener('scroll', () => {
            const scrolled = window.pageYOffset;
            const parallax = document.querySelector('.parallax-bg');
            const navbar = document.getElementById('navbar');
            
            // Parallaxe
            if (parallax) {
                parallax.style.transform = `translateY(${scrolled * 0.5}px)`;
            }
            
            // Navbar scrolled effect
            if (scrolled > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        // Animation au scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                }
            });
        }, observerOptions);

        document.querySelectorAll('.fade-in').forEach(el => {
            observer.observe(el);
        });

        // Smooth scroll pour les liens de navigation
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                    // Fermer le menu mobile après clic
                    document.getElementById('navLinks').classList.remove('active');
                }
            });
        });

        // Animation des chiffres dans les stats
        function animateNumbers() {
            const stats = document.querySelectorAll('.stat-card h3');
            stats.forEach(stat => {
                const target = parseInt(stat.textContent.replace(/\D/g, ''));
                const suffix = stat.textContent.replace(/[0-9]/g, '');
                let current = 0;
                const increment = target / 50;
                
                const timer = setInterval(() => {
                    current += increment;
                    if (current >= target) {
                        stat.textContent = target + suffix;
                        clearInterval(timer);
                    } else {
                        stat.textContent = Math.floor(current) + suffix;
                    }
                }, 30);
            });
        }

        // Observer pour lancer l'animation des stats
        const statsSection = document.querySelector('.stats');
        if (statsSection) {
            const statsObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        animateNumbers();
                        statsObserver.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.5 });
            
            statsObserver.observe(statsSection);
        }
    </script>
</body>
</html>