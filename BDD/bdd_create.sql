-- bdd_create.sql

-- Création de l'utilisateur
CREATE USER IF NOT EXISTS 'utilisateur'@'localhost' IDENTIFIED BY 'password';

-- Création de la base de données
CREATE DATABASE IF NOT EXISTS cabinet_medical CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

-- Attribution des privilèges à l'utilisateur
GRANT ALL PRIVILEGES ON cabinet_medical.* TO 'utilisateur'@'localhost';

-- Rechargement des privilèges
FLUSH PRIVILEGES;
