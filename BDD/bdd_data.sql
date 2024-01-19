-- bdd_data.sql

-- Utilisation de la base de données
USE cabinet_medical;

-- Insertion d'exemples d'usagers
INSERT INTO usagers (civilite, nom, prenom, adresse, date_naissance, lieu_naissance, num_secu_sociale)
VALUES
('M.', 'Dupont', 'Jean', '12 Rue de la République, 75001 Paris', '1980-01-15', 'Paris', '123456789012345'),
('Mme', 'Martin', 'Sophie', '8 Avenue des Champs-Élysées, 75008 Paris', '1975-05-20', 'Nice', '987654321098765');

-- Insertion d'exemples de médecins
INSERT INTO medecins (civilite, nom, prenom)
VALUES
('Dr.', 'Dubois', 'Claire'),
('Pr.', 'Lefevre', 'Pierre');

-- Insertion d'exemples de rendez-vous
INSERT INTO rendez_vous (id_usager, id_medecin, date_consultation, heure_consultation, duree_consultation)
VALUES
(1, 1, '2024-02-01', '10:00:00', 30),
(2, 2, '2024-02-02', '14:30:00', 45);

INSERT INTO utilisateurs (nom_utilisateur, mot_de_passe_hash) VALUES ('votre_nom_utilisateur', 'votre_mot_de_passe_hash');

