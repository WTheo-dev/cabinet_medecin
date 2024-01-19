-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : ven. 19 jan. 2024 à 19:48
-- Version du serveur : 10.4.28-MariaDB
-- Version de PHP : 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- Création de la base de données
CREATE DATABASE IF NOT EXISTS cabinet_medical CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
--
-- Base de données : `cabinet_medical`
--

-- --------------------------------------------------------

--
-- Structure de la table `medecins`
--

CREATE TABLE `medecins` (
  `id` int(11) NOT NULL,
  `civilite` varchar(10) NOT NULL,
  `nom` varchar(50) NOT NULL,
  `prenom` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `medecins`
--

INSERT INTO `medecins` (`id`, `civilite`, `nom`, `prenom`) VALUES
(2, 'M.', 'Lefevre', 'Pierrick'),
(3, 'M.', 'Stoffel', 'Maxime'),
(4, 'Mme', 'ElleLouche', 'Françoise'),
(5, 'M.', 'FaSol', 'Rémi'),
(6, 'Mme', 'Carena', 'Emma'),
(7, 'M.', 'Térieur', 'Alex'),
(8, 'Mme', 'Afeeling', 'Agathe'),
(15, 'M.', 'Cover', 'Harry'),
(16, 'Mme', 'Ancieux', 'Cecile');

-- --------------------------------------------------------

--
-- Structure de la table `rendez_vous`
--

CREATE TABLE `rendez_vous` (
  `id` int(11) NOT NULL,
  `id_usager` int(11) DEFAULT NULL,
  `id_medecin` int(11) DEFAULT NULL,
  `date_consultation` date NOT NULL,
  `heure_consultation` time NOT NULL,
  `duree_consultation` int(11) DEFAULT 30
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `rendez_vous`
--

INSERT INTO `rendez_vous` (`id`, `id_usager`, `id_medecin`, `date_consultation`, `heure_consultation`, `duree_consultation`) VALUES
(16, 4, 6, '2024-08-15', '15:02:00', 20),
(19, 9, 3, '2024-12-15', '17:36:00', 30),
(20, 10, 5, '2024-08-07', '22:15:00', 30),
(25, 26, 8, '2024-07-17', '13:22:00', 30),
(28, 5, 2, '2024-10-09', '19:46:00', 30);

-- --------------------------------------------------------

--
-- Structure de la table `usagers`
--

CREATE TABLE `usagers` (
  `id` int(11) NOT NULL,
  `civilite` varchar(10) NOT NULL,
  `nom` varchar(50) NOT NULL,
  `prenom` varchar(50) NOT NULL,
  `adresse` varchar(255) NOT NULL,
  `date_naissance` date NOT NULL,
  `lieu_naissance` varchar(100) NOT NULL,
  `num_secu_sociale` varchar(15) NOT NULL,
  `id_medecin_referent` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `usagers`
--

INSERT INTO `usagers` (`id`, `civilite`, `nom`, `prenom`, `adresse`, `date_naissance`, `lieu_naissance`, `num_secu_sociale`, `id_medecin_referent`) VALUES
(3, 'M.', 'Dupont', 'Dimitri', '12 rue les moulins', '2000-03-22', 'Perpignan', '123456789012340', NULL),
(4, 'Mme', 'Brun', 'Lisa', '12 rue de la paix', '2003-07-14', 'Rivesaltes', '123456789012323', NULL),
(5, 'M.', 'Tard', 'Michel', '1 rue les coquelins', '2000-03-05', 'Lyon', '123456789012341', 2),
(7, 'M.', 'Piquemal', 'Lea', '13 rue les uilo', '2002-08-14', 'Perpignan', '123456789012302', 2),
(9, 'M.', 'Samitier', 'Pierre', '13 rue vincent delamama', '2003-12-11', 'Avignon', '123456789012310', 6),
(10, 'Mme', 'Piquemal', 'Lou', '12 villa la mama', '2003-02-08', 'Port-Leucate', '123456789145893', 3),
(19, 'M.', 'Alain', 'Terieur', '12 rue de la paix', '2009-02-18', 'Perpignan', '123456789012389', 7),
(21, 'M.', 'Chevalier', 'Jacques', '12 rue de la paix', '2012-08-11', 'Montauban', '123456459012355', 6),
(23, 'M.', 'Izre', 'François', '1 rue la peche', '2008-12-17', 'Montpellier', '123456459012370', 5),
(25, 'M.', 'Girafe', 'Clara', '12 rue de la paye', '2004-06-08', 'Paris', '123456789012308', NULL),
(26, 'M.', 'Piquea', 'John', '56 avenue de rangueil', '1999-12-25', 'Marseille', '123456789012306', NULL),
(27, 'M.', 'Stofy', 'Gérard', '1 rue les coquillettes', '1940-03-03', 'Bruxelles', '123456789012345', 8),
(28, 'M.', 'Tard', 'Zan', '12 rue de la paix', '2001-12-22', 'Font-Romeu', '123456789012346', 7);

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

CREATE TABLE `utilisateurs` (
  `id` int(11) NOT NULL,
  `nom_utilisateur` varchar(50) NOT NULL,
  `motdepasse` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id`, `nom_utilisateur`, `motdepasse`) VALUES
(1, 'admin', 'motdepasse');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `medecins`
--
ALTER TABLE `medecins`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `rendez_vous`
--
ALTER TABLE `rendez_vous`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_rdv` (`id_medecin`,`date_consultation`,`heure_consultation`),
  ADD KEY `id_usager` (`id_usager`);

--
-- Index pour la table `usagers`
--
ALTER TABLE `usagers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_usagers_medecin_referent` (`id_medecin_referent`);

--
-- Index pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `medecins`
--
ALTER TABLE `medecins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT pour la table `rendez_vous`
--
ALTER TABLE `rendez_vous`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT pour la table `usagers`
--
ALTER TABLE `usagers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `rendez_vous`
--
ALTER TABLE `rendez_vous`
  ADD CONSTRAINT `rendez_vous_ibfk_1` FOREIGN KEY (`id_usager`) REFERENCES `usagers` (`id`),
  ADD CONSTRAINT `rendez_vous_ibfk_2` FOREIGN KEY (`id_medecin`) REFERENCES `medecins` (`id`);

--
-- Contraintes pour la table `usagers`
--
ALTER TABLE `usagers`
  ADD CONSTRAINT `fk_usagers_medecin_referent` FOREIGN KEY (`id_medecin_referent`) REFERENCES `medecins` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
