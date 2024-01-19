-- bdd_table.sql

-- Utilisation de la base de données
USE cabinet_medical;

-- Table pour stocker les informations sur les usagers
CREATE TABLE IF NOT EXISTS usagers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    civilite VARCHAR(10) NOT NULL,
    nom VARCHAR(50) NOT NULL,
    prenom VARCHAR(50) NOT NULL,
    adresse VARCHAR(255) NOT NULL,
    date_naissance DATE NOT NULL,
    lieu_naissance VARCHAR(100) NOT NULL,
    num_secu_sociale VARCHAR(15) NOT NULL
);

-- Table pour stocker les informations sur les médecins
CREATE TABLE IF NOT EXISTS medecins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    civilite VARCHAR(10) NOT NULL,
    nom VARCHAR(50) NOT NULL,
    prenom VARCHAR(50) NOT NULL
);

-- Table pour stocker les rendez-vous
CREATE TABLE IF NOT EXISTS rendez_vous (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_usager INT,
    id_medecin INT,
    date_consultation DATE NOT NULL,
    heure_consultation TIME NOT NULL,
    duree_consultation INT DEFAULT 30,
    FOREIGN KEY (id_usager) REFERENCES usagers(id),
    FOREIGN KEY (id_medecin) REFERENCES medecins(id)
);

CREATE TABLE IF NOT EXISTS utilisateurs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom_utilisateur VARCHAR(50) NOT NULL,
    mot_de_passe_hash VARCHAR(255) NOT NULL
);

