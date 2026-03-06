PACOCEAN MAGHREB 🚢
Système de Gestion Logistique Maritime
Un projet web complet de logistique internationale développé pour le transport maritime, permettant le suivi des cargaisons, l'analyse des risques et la gestion des contacts clients.
📋 Table des Matières
Description
Fonctionnalités
Structure du Projet
Installation
Configuration
Utilisation
Base de Données
Captures d'Écran
Technologies Utilisées
Auteur
📝 Description
PACOCEAN MAGHREB est une application web de logistique internationale basée à Casablanca, Maroc. Le système permet aux clients de :
Suivre leurs cargaisons en temps réel
Analyser les risques associés aux transports
Contacter l'entreprise via un formulaire
Découvrir les services maritimes proposés
✨ Fonctionnalités
🏠 Page d'Accueil (index.php / index.html)
Design moderne avec effet parallaxe
Présentation des services maritimes (FCL, LCL, Reefer, etc.)
Statistiques animées (150+ pays, 50K+ cargaisons)
Formulaire de suivi rapide
Section "À propos" avec valeurs de l'entreprise
Carte Google Maps intégrée
Support multilingue (Français/Anglais)
🔍 Suivi de Cargaison (tracking.php)
Recherche par numéro de suivi
Affichage détaillé des informations :
Client, origine, destination
Statut actuel et localisation
Date d'arrivée prévue (ETA)
Poids et valeur de la cargaison
Barre de progression visuelle
Timeline des mises à jour
⚠️ Analyse des Risques (risk_analysis.php)
Évaluation automatique des risques (score 0-100)
4 niveaux de risque : Faible 🟢 | Modéré 🟡 | Élevé 🟠 | Critique 🔴
Facteurs analysés :
Type de marchandise (dangereuse, réfrigérée, etc.)
Risque de retard (basé sur l'ETA)
Zone géopolitique (Canal de Suez, etc.)
Progression du transport
Recommandations automatiques selon le niveau de risque
Historique des analyses sauvegardé en base
📧 Contact (contact.php)
Formulaire de contact avec validation
Envoi d'email (fonction mail() PHP)
Sauvegarde automatique en base de données
Design responsive avec animations
Affichage des coordonnées de l'entreprise
Carte de localisation
📨 Contact avec Gmail (contact2.php)
Version alternative pour envoi via SMTP Gmail
Configuration personnalisable
📁 Structure du Projet
pacocean-maghreb/
│
├── 📄 index.php              # Page d'accueil principale (PHP)
├── 📄 index.html             # Version statique HTML
├── 📄 tracking.php           # Système de suivi des cargaisons
├── 📄 risk_analysis.php      # Tableau de bord d'analyse des risques
├── 📄 contact.php            # Formulaire de contact standard
├── 📄 contact2.php           # Formulaire avec envoi Gmail
├── 📄 init.sql               # Script d'initialisation de la base de données
│
└── 📁 assets/              # (Optionnel) Images, CSS, JS supplémentaires
🚀 Installation
Prérequis
Serveur Web : Apache/Nginx
PHP : Version 7.4 ou supérieure
Base de Données : MySQL/MariaDB
phpMyAdmin : (recommandé pour la gestion de la BDD)
Étapes d'Installation
1. Cloner ou télécharger le projet
  cd /var/www/html  # ou htdocs pour XAMPP
  git clone [url-du-projet] pacocean
  cd pacocean
2. Configurer la base de données
Ouvrir phpMyAdmin : http://localhost/phpmyadmin
Créer une nouvelle base de données nommée pacocean_db
Importer le fichier init.sql :
Cliquer sur l'onglet Importer
Sélectionner init.sql
Cliquer Exécuter
3. Vérifier la connexion BDD
Les fichiers PHP utilisent ces paramètres par défaut :
$serveur = "localhost";
$utilisateur = "root";
$motdepasse = "";        // Modifier selon votre config
$basededonnees = "pacocean_db";
Si vous avez un mot de passe MySQL, modifiez ces lignes dans :
tracking.php
risk_analysis.php
contact.php
contact2.php
4. Accéder au site
  http://localhost/pacocean/index.php
⚙️ Configuration
Configuration Email (contact.php)
Par défaut, le formulaire utilise la fonction mail() de PHP. Pour une configuration Gmail, utilisez contact2.php :
// Dans contact2.php
$gmail_user = "votre-email@gmail.com";
$gmail_password = "votre-mot-de-passe-app";  // Mot de passe d'application Gmail
$destinataire = "contact@pacocean.ma";
Numéros de Suivi de Test
Le fichier init.sql crée automatiquement ces cargaisons de démonstration :
| Numéro       | Client                | Statut     | Progression |
| ------------ | --------------------- | ---------- | ----------- |
| PAC-2024-001 | Entreprise Alami SARL | En transit | 65%         |
| PAC-2024-002 | TechMaroc Ltd         | Arrivé     | 90%         |
| PAC-2024-003 | Export Textile        | Collecté   | 25%         |
| PAC-2024-004 | AgriExport            | Embarqué   | 35%         |
| PAC-2024-005 | Pharma International  | Livré      | 100%        |
🎯 Utilisation
Pour les Clients
Suivre une cargaison :
Aller sur tracking.php
Entrer le numéro de suivi (ex: PAC-2024-001)
Voir les détails en temps réel
Analyser les risques :
Aller sur risk_analysis.php
Entrer le numéro de suivi
Consulter le score et les recommandations
Contacter l'entreprise :
Aller sur contact.php
Remplir le formulaire
Recevoir une confirmation
Pour les Administrateurs
Messages de contact : Table contact_messages dans phpMyAdmin
Analyses de risques : Table risk_analysis avec historique
Alertes : Table risk_alerts pour les notifications
🛠️ Technologies Utilisées
| Technologie          | Utilisation                        |
| -------------------- | ---------------------------------- |
| **PHP 7.4+**         | Backend et logique métier          |
| **MySQL/MariaDB**    | Base de données relationnelle      |
| **HTML5**            | Structure des pages                |
| **CSS3**             | Styles modernes avec variables CSS |
| **JavaScript**       | Interactivité et animations        |
| **Font Awesome 6.4** | Icônes vectorielles                |
| **Google Maps**      | Intégration cartographique         |
Fonctionnalités CSS Modernes
Flexbox et Grid Layout
Variables CSS (:root)
Backdrop-filter (effet de flou)
Animations @keyframes
Media queries (responsive)
Dégradés linéaires
📱 Responsive Design
Le site est entièrement responsive et s'adapte à :
💻 Ordinateurs de bureau (> 968px)
📱 Tablettes (768px - 968px)
📲 Smartphones (< 768px)
🔒 Sécurité
Protection contre les injections SQL avec mysqli_real_escape_string()
Échappement des sorties HTML avec htmlspecialchars()
Validation des emails avec filter_var()
Requêtes préparées recommandées pour les mises à jour
👨‍💻 Auteur
Projet développé pour : PACOCEAN MAGHREB
Localisation : Casablanca, Maroc 🇲🇦
Année : 2026
📄 Licence
Ce projet est destiné à un usage éducatif et professionnel.
Tous droits réservés © 2024 PACOCEAN MAGHREB.
🤝 Support
Pour toute question ou suggestion :
📧 Email : contact@pacocean.ma
📍 Adresse : Boulevard d'Anfa, Casablanca 20000
<div align="center">
🌊 PACOCEAN MAGHREB - Votre Partenaire Logistique Mondial 🌊
</div>
