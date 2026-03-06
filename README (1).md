# PACOCEAN MAGHREB 🚢

**Système de Gestion Logistique Maritime**

Un projet web complet de logistique internationale développé pour le transport maritime, permettant le suivi des cargaisons, l'analyse des risques et la gestion des contacts clients.

---

## 📋 Table des Matières

- [Description](#description)
- [Fonctionnalités](#fonctionnalités)
- [Structure du Projet](#structure-du-projet)
- [Installation](#installation)
- [Configuration](#configuration)
- [Utilisation](#utilisation)
- [Base de Données](#base-de-données)
- [Captures d'Écran](#captures-décran)
- [Technologies Utilisées](#technologies-utilisées)
- [Auteur](#auteur)

---

## 📝 Description

PACOCEAN MAGHREB est une application web de logistique internationale basée à Casablanca, Maroc. Le système permet aux clients de :

- Suivre leurs cargaisons en temps réel
- Analyser les risques associés aux transports
- Contacter l'entreprise via un formulaire
- Découvrir les services maritimes proposés

---

## ✨ Fonctionnalités

### 🏠 Page d'Accueil (`index.php` / `index.html`)
- Design moderne avec effet parallaxe
- Présentation des services maritimes (FCL, LCL, Reefer, etc.)
- Statistiques animées (150+ pays, 50K+ cargaisons)
- Formulaire de suivi rapide
- Section "À propos" avec valeurs de l'entreprise
- Carte Google Maps intégrée
- Support multilingue (Français/Anglais)

### 🔍 Suivi de Cargaison (`tracking.php`)
- Recherche par numéro de suivi
- Affichage détaillé des informations :
  - Client, origine, destination
  - Statut actuel et localisation
  - Date d'arrivée prévue (ETA)
  - Poids et valeur de la cargaison
- Barre de progression visuelle
- Timeline des mises à jour

### ⚠️ Analyse des Risques (`risk_analysis.php`)
- Évaluation automatique des risques (score 0-100)
- 4 niveaux de risque : Faible 🟢 | Modéré 🟡 | Élevé 🟠 | Critique 🔴
- Facteurs analysés :
  - Type de marchandise (dangereuse, réfrigérée, etc.)
  - Risque de retard (basé sur l'ETA)
  - Zone géopolitique (Canal de Suez, etc.)
  - Progression du transport
- Recommandations automatiques selon le niveau de risque
- Historique des analyses sauvegardé en base

### 📧 Contact (`contact.php`)
- Formulaire de contact avec validation
- Envoi d'email (fonction `mail()` PHP)
- Sauvegarde automatique en base de données
- Design responsive avec animations
- Affichage des coordonnées de l'entreprise
- Carte de localisation

### 📨 Contact avec Gmail (`contact2.php`)
- Version alternative pour envoi via SMTP Gmail
- Configuration personnalisable

---

## 📁 Structure du Projet

```
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
```

---

## 🚀 Installation

### Prérequis
- **Serveur Web** : Apache/Nginx
- **PHP** : Version 7.4 ou supérieure
- **Base de Données** : MySQL/MariaDB
- **phpMyAdmin** : (recommandé pour la gestion de la BDD)

### Étapes d'Installation

#### 1. Cloner ou télécharger le projet
```bash
cd /var/www/html  # ou htdocs pour XAMPP
git clone [url-du-projet] pacocean
cd pacocean
```

#### 2. Configurer la base de données
1. Ouvrir **phpMyAdmin** : `http://localhost/phpmyadmin`
2. Créer une nouvelle base de données nommée `pacocean_db`
3. Importer le fichier `init.sql` :
   - Cliquer sur l'onglet **Importer**
   - Sélectionner `init.sql`
   - Cliquer **Exécuter**

#### 3. Vérifier la connexion BDD
Les fichiers PHP utilisent ces paramètres par défaut :
```php
$serveur = "localhost";
$utilisateur = "root";
$motdepasse = "";        // Modifier selon votre config
$basededonnees = "pacocean_db";
```

**Si vous avez un mot de passe MySQL**, modifiez ces lignes dans :
- `tracking.php`
- `risk_analysis.php`
- `contact.php`
- `contact2.php`

#### 4. Accéder au site
```
http://localhost/pacocean/index.php
```

---

## ⚙️ Configuration

### Configuration Email (contact.php)
Par défaut, le formulaire utilise la fonction `mail()` de PHP. Pour une configuration Gmail, utilisez `contact2.php` :

```php
// Dans contact2.php
$gmail_user = "votre-email@gmail.com";
$gmail_password = "votre-mot-de-passe-app";  // Mot de passe d'application Gmail
$destinataire = "contact@pacocean.ma";
```

### Numéros de Suivi de Test
Le fichier `init.sql` crée automatiquement ces cargaisons de démonstration :

| Numéro | Client | Statut | Progression |
|--------|--------|--------|-------------|
| PAC-2024-001 | Entreprise Alami SARL | En transit | 65% |
| PAC-2024-002 | TechMaroc Ltd | Arrivé | 90% |
| PAC-2024-003 | Export Textile | Collecté | 25% |
| PAC-2024-004 | AgriExport | Embarqué | 35% |
| PAC-2024-005 | Pharma International | Livré | 100% |

---

## 🎯 Utilisation

### Pour les Clients

1. **Suivre une cargaison** :
   - Aller sur `tracking.php`
   - Entrer le numéro de suivi (ex: PAC-2024-001)
   - Voir les détails en temps réel

2. **Analyser les risques** :
   - Aller sur `risk_analysis.php`
   - Entrer le numéro de suivi
   - Consulter le score et les recommandations

3. **Contacter l'entreprise** :
   - Aller sur `contact.php`
   - Remplir le formulaire
   - Recevoir une confirmation

### Pour les Administrateurs

- **Messages de contact** : Table `contact_messages` dans phpMyAdmin
- **Analyses de risques** : Table `risk_analysis` avec historique
- **Alertes** : Table `risk_alerts` pour les notifications

---

## 🗄️ Base de Données

### Tables Principales

#### `tracking`
```sql
- id (INT, PK, Auto-incrément)
- numero_suivi (VARCHAR) - Numéro unique de suivi
- nom_client (VARCHAR) - Nom du client
- email_client (VARCHAR) - Email du client
- origine (VARCHAR) - Port/Ville de départ
- destination (VARCHAR) - Port/Ville d'arrivée
- type_service (VARCHAR) - Maritime/Aérien/Terrestre
- statut (VARCHAR) - En transit/Arrivé/Livré/etc.
- localisation_actuelle (VARCHAR) - Position actuelle
- date_eta (DATE) - Date d'arrivée estimée
- progression (INT) - Pourcentage de progression (0-100)
- description (TEXT) - Description de la cargaison
- poids (DECIMAL) - Poids en kg
- valeur (DECIMAL) - Valeur marchande
- date_creation (TIMESTAMP)
- date_modification (TIMESTAMP)
```

#### `contact_messages`
```sql
- id (INT, PK)
- nom (VARCHAR) - Nom de l'expéditeur
- email (VARCHAR) - Email de l'expéditeur
- sujet (VARCHAR) - Sujet du message
- message (TEXT) - Contenu du message
- statut (VARCHAR) - nouveau/lu/traité
- date_envoi (TIMESTAMP)
```

#### `risk_analysis`
```sql
- id (INT, PK)
- tracking_id (INT, FK) - Lien vers la cargaison
- score_risque (INT) - Score calculé (0-100)
- niveau_risque (ENUM) - faible/modere/eleve/critique
- facteurs_risque (JSON) - Détails des facteurs
- recommandations (TEXT) - Actions recommandées
- date_analyse (TIMESTAMP)
```

#### `risk_alerts`
```sql
- id (INT, PK)
- tracking_id (INT, FK)
- type_alerte (VARCHAR) - Type de risque détecté
- message (TEXT) - Description de l'alerte
- statut (ENUM) - actif/resolu/ignore
- date_creation (TIMESTAMP)
- date_resolution (TIMESTAMP)
```

---

## 🛠️ Technologies Utilisées

| Technologie | Utilisation |
|-------------|-------------|
| **PHP 7.4+** | Backend et logique métier |
| **MySQL/MariaDB** | Base de données relationnelle |
| **HTML5** | Structure des pages |
| **CSS3** | Styles modernes avec variables CSS |
| **JavaScript** | Interactivité et animations |
| **Font Awesome 6.4** | Icônes vectorielles |
| **Google Maps** | Intégration cartographique |

### Fonctionnalités CSS Modernes
- Flexbox et Grid Layout
- Variables CSS (`:root`)
- Backdrop-filter (effet de flou)
- Animations @keyframes
- Media queries (responsive)
- Dégradés linéaires

---

## 📱 Responsive Design

Le site est entièrement responsive et s'adapte à :
- 💻 Ordinateurs de bureau (> 968px)
- 📱 Tablettes (768px - 968px)
- 📲 Smartphones (< 768px)

---

## 🔒 Sécurité

- Protection contre les injections SQL avec `mysqli_real_escape_string()`
- Échappement des sorties HTML avec `htmlspecialchars()`
- Validation des emails avec `filter_var()`
- Requêtes préparées recommandées pour les mises à jour

---

## 🎨 Palette de Couleurs

```css
:root {
    --primary: #0066cc;      /* Bleu principal */
    --secondary: #003d7a;    /* Bleu foncé */
    --accent: #00a8ff;       /* Bleu clair */
    --dark: #1a1a2e;         /* Noir bleuté */
    --light: #f8f9fa;        /* Gris très clair */
    --success: #28a745;      /* Vert */
    --warning: #ffc107;      /* Jaune */
    --danger: #dc3545;       /* Rouge */
    --orange: #fd7e14;       /* Orange */
}
```

---

## 📝 Notes pour Développeurs

### Améliorations Possibles
1. **API REST** : Créer une API pour l'accès mobile
2. **Authentification** : Système de login client/admin
3. **Notifications** : Alertes email automatiques
4. **PDF** : Génération de bordereaux de suivi
5. **Chatbot** : Assistant virtuel pour le support
6. **Dashboard Admin** : Interface d'administration complète

### Bonnes Pratiques Actuelles
- Code commenté en français
- Structure MVC simplifiée
- Réutilisation des composants (navbar, footer)
- Gestion des erreurs utilisateur

---

## 👨‍💻 Auteur

**Projet développé pour** : PACOCEAN MAGHREB  
**Localisation** : Casablanca, Maroc 🇲🇦  
**Année** : 2024

---

## 📄 Licence

Ce projet est destiné à un usage éducatif et professionnel.  
Tous droits réservés © 2024 PACOCEAN MAGHREB.

---

## 🤝 Support

Pour toute question ou suggestion :
- 📧 Email : contact@pacocean.ma
- 📍 Adresse : Boulevard d'Anfa, Casablanca 20000

---

<div align="center">

**🌊 PACOCEAN MAGHREB - Votre Partenaire Logistique Mondial 🌊**

</div>
