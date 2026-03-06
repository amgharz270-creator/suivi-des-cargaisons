-- BASE DE DONNÉES SIMPLE POUR PACOCEAN - EXERCICE LYCÉE
-- À importer dans phpMyAdmin

-- Créer la base de données
CREATE DATABASE pacocean_db;
USE pacocean_db;

-- Table pour le tracking des cargaisons
CREATE TABLE tracking (
    id INT AUTO_INCREMENT PRIMARY KEY,
    numero_suivi VARCHAR(50) NOT NULL,
    nom_client VARCHAR(100) NOT NULL,
    email_client VARCHAR(100) NOT NULL,
    origine VARCHAR(100) NOT NULL,
    destination VARCHAR(100) NOT NULL,
    type_service VARCHAR(50) NOT NULL,
    statut VARCHAR(50) NOT NULL,
    localisation_actuelle VARCHAR(100) NOT NULL,
    date_eta DATE,
    progression INT DEFAULT 0,
    description TEXT,
    poids DECIMAL(10,2),
    valeur DECIMAL(15,2),
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    date_modification TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
-- Table pour l'analyse des risques
CREATE TABLE IF NOT EXISTS risk_analysis (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tracking_id INT NOT NULL,
    score_risque INT DEFAULT 0,
    niveau_risque ENUM('faible', 'modere', 'eleve', 'critique') DEFAULT 'faible',
    facteurs_risque JSON,
    recommandations TEXT,
    date_analyse TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_tracking (tracking_id),
    FOREIGN KEY (tracking_id) REFERENCES tracking(id) ON DELETE CASCADE
);

-- Table pour l'historique des alertes
CREATE TABLE IF NOT EXISTS risk_alerts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tracking_id INT NOT NULL,
    type_alerte VARCHAR(50),
    message TEXT,
    statut ENUM('actif', 'resolu', 'ignore') DEFAULT 'actif',
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    date_resolution TIMESTAMP NULL,
    FOREIGN KEY (tracking_id) REFERENCES tracking(id) ON DELETE CASCADE
);
-- Table pour les messages de contact
CREATE TABLE contact_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    sujet VARCHAR(200),
    message TEXT NOT NULL,
    statut VARCHAR(20) DEFAULT 'nouveau',
    date_envoi TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insérer quelques exemples de cargaisons pour tester
INSERT INTO tracking (numero_suivi, nom_client, email_client, origine, destination, type_service, statut, localisation_actuelle, date_eta, progression, description, poids, valeur) VALUES 

('PAC-2024-001', 'Entreprise Alami SARL', 'contact@alami.ma', 'Shanghai, Chine', 'Port de Casablanca', 'Maritime', 'En transit', 'Canal de Suez', '2024-12-20', 65, 'Matériel électronique', 15000.00, 250000.00),

('PAC-2024-002', 'TechMaroc Ltd', 'info@techmaroc.com', 'Paris, France', 'Aéroport Mohammed V', 'Aérien', 'Arrivé', 'Casablanca', '2024-12-08', 90, 'Pièces automobiles', 500.00, 45000.00),

('PAC-2024-003', 'Export Textile', 'export@textile.ma', 'Tanger', 'Marseille, France', 'Terrestre', 'Collecté', 'Tanger Port', '2024-12-15', 25, 'Vêtements', 8000.00, 180000.00),

('PAC-2024-004', 'AgriExport', 'contact@agri.ma', 'Agadir', 'Rotterdam', 'Maritime', 'Embarqué', 'Port Agadir', '2024-12-18', 35, 'Produits agricoles', 22000.00, 95000.00),

('PAC-2024-005', 'Pharma International', 'urgence@pharma.com', 'Lyon, France', 'Rabat', 'Aérien', 'Livré', 'Rabat', '2024-12-05', 100, 'Médicaments', 150.00, 120000.00);

-- Insérer un exemple de message de contact
INSERT INTO contact_messages (nom, email, sujet, message) VALUES 
('Mohammed Benjelloun', 'mbenjelloun@gmail.com', 'Demande de devis', 'Bonjour, je souhaite avoir un devis pour expédier 3 palettes de Casablanca vers Paris. Merci !');

-- Voir les données créées
SELECT * FROM tracking;
SELECT * FROM contact_messages;