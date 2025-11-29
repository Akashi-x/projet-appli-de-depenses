-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : sam. 29 nov. 2025 à 13:09
-- Version du serveur : 9.1.0
-- Version de PHP : 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `gestion_depenses`
--

-- --------------------------------------------------------

--
-- Structure de la table `categorie`
--

DROP TABLE IF EXISTS `categorie`;
CREATE TABLE IF NOT EXISTS `categorie` (
  `ID_CATEGORIE` int NOT NULL AUTO_INCREMENT,
  `ID_TYPE` int NOT NULL,
  `NOM_CATEGORIE` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`ID_CATEGORIE`),
  KEY `ID_TYPE` (`ID_TYPE`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `categorie`
--

INSERT INTO `categorie` (`ID_CATEGORIE`, `ID_TYPE`, `NOM_CATEGORIE`) VALUES
(6, 1, 'Bourse'),
(4, 2, 'Loyer'),
(3, 1, 'Divers'),
(5, 2, 'Alimentation '),
(8, 2, 'Divers');

-- --------------------------------------------------------

--
-- Structure de la table `operation`
--

DROP TABLE IF EXISTS `operation`;
CREATE TABLE IF NOT EXISTS `operation` (
  `ID_OPERATIONS_` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `ID_UTILISATEUR` int NOT NULL,
  `ID_CATEGORIE` int NOT NULL,
  `MONTANT` int DEFAULT NULL,
  `DATE_OPERATION` date DEFAULT NULL,
  `DESCRIPTION` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`ID_OPERATIONS_`),
  KEY `FK_OPERATIO_APPARTENI_CATEGORI` (`ID_CATEGORIE`),
  KEY `FK_OPERATIO_EFFECTUER_UTILISAT` (`ID_UTILISATEUR`)
) ENGINE=MyISAM AUTO_INCREMENT=1240 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `operation`
--

INSERT INTO `operation` (`ID_OPERATIONS_`, `ID_UTILISATEUR`, `ID_CATEGORIE`, `MONTANT`, `DATE_OPERATION`, `DESCRIPTION`) VALUES
(1229, 5, 1, 100000, '2025-10-11', 'vente de sac de riz'),
(1227, 5, 3, 10000, '2025-10-12', 'vente parfum'),
(1212, 1, 3, 3000, '2025-10-03', 'fourniture'),
(1231, 5, 4, 80000, '2025-10-05', 'paiement mensuel du loyer'),
(1221, 5, 2, 300000, '2025-10-03', 'billet maroc pour la can'),
(1202, 1, 3, 3000, '2025-08-06', 'parfum'),
(1213, 1, 3, 12000, '2025-10-11', 'achat naillot'),
(1232, 5, 4, 100000, '2025-11-02', 'paiement mensuel du loyer'),
(1228, 5, 1, 100000, '2025-09-11', 'vente de sac de riz'),
(1233, 5, 1, 30000, '2025-11-02', 'vente de sac de riz'),
(1234, 5, 3, 111, '2025-10-28', '111'),
(1235, 5, 5, 11110, '2025-11-11', 'on a fait des course'),
(1236, 5, 3, 215212, '2025-11-11', 'Argent de poche'),
(1237, 5, 2, 10000, '2025-11-11', 'transport pou bea'),
(1238, 5, 6, 40000, '2025-11-12', 'paiment ecobank'),
(1239, 5, 5, 2500, '2025-11-12', 'Maiga');

-- --------------------------------------------------------

--
-- Structure de la table `role`
--

DROP TABLE IF EXISTS `role`;
CREATE TABLE IF NOT EXISTS `role` (
  `ID_ROLE` int NOT NULL AUTO_INCREMENT,
  `NOM_ROLE` varchar(255) NOT NULL,
  PRIMARY KEY (`ID_ROLE`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `role`
--

INSERT INTO `role` (`ID_ROLE`, `NOM_ROLE`) VALUES
(1, 'admin'),
(2, 'utilisateur');

-- --------------------------------------------------------

--
-- Structure de la table `type`
--

DROP TABLE IF EXISTS `type`;
CREATE TABLE IF NOT EXISTS `type` (
  `ID_TYPE` int NOT NULL AUTO_INCREMENT,
  `NOM_TYPE` varchar(255) NOT NULL,
  PRIMARY KEY (`ID_TYPE`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `type`
--

INSERT INTO `type` (`ID_TYPE`, `NOM_TYPE`) VALUES
(1, 'Revenu'),
(2, 'Depense');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

DROP TABLE IF EXISTS `utilisateur`;
CREATE TABLE IF NOT EXISTS `utilisateur` (
  `ID_UTILISATEUR` int NOT NULL AUTO_INCREMENT,
  `ID_ROLE` int NOT NULL,
  `NOM_UTILISATEUR` varchar(255) DEFAULT NULL,
  `PRENOM` varchar(255) DEFAULT NULL,
  `EMAIL` varchar(255) DEFAULT NULL,
  `MOT_DE_PASSE` varchar(255) DEFAULT NULL,
  `CODE` int DEFAULT NULL,
  `FLAG_REINITIALISATION` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`ID_UTILISATEUR`),
  KEY `ID_ROLE` (`ID_ROLE`)
) ENGINE=MyISAM AUTO_INCREMENT=64 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`ID_UTILISATEUR`, `ID_ROLE`, `NOM_UTILISATEUR`, `PRENOM`, `EMAIL`, `MOT_DE_PASSE`, `CODE`, `FLAG_REINITIALISATION`) VALUES
(5, 1, 'Akashi', 'daouda', 'sarrdavid20@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$dEVPQ0M5cGlPVjBjL0UwMQ$cZpHBCZxJdS+zTlCRMPKQU8QsNyO9Ui731zFH6Q5Zk8', 0, 0),
(55, 2, 'Tatsumi', 'daouda', 'test20@gmail.com', '$argon2id$v=19$m=...SYTRoOE05OQ$ZIQxNPMNlQ+4Ket2AmojjTr7gV3OX86HvnmW+sR1qFI', 0, 0),
(56, 2, 'sarr', 'daouda', 'sarrrdavid@yahoo.com', '$argon2id$v=19$m...1V3VIMTBpVQ$q8w1OZ800DSDbwdK7uyWjW8GZdnAo/5afflPX9ImYzo', 0, 0),
(57, 2, 'fdv', 'daouda', 'sarrrdavid2@yahoo.com', '$argon2id$v=19$m...QTkxBNlBFUA$cup1KLRCU4qIpy8EVQEB5slWfYlNklX9K3fK5qAHsW4', 0, 0),
(58, 2, 'sarr', 'daouda', 'sarrrdavihd@yahoo.com', '$argon2id$v=19$...admJIVVJBYg$L6P+xM9KJog+uJmAVOI+SQWfr8zQ+7Rxu50ryp4a208', 0, 0),
(59, 2, 'sarr', 'daouda', 'sarrrdavid@yahoo.fr', '$argon2id$v=19$m=...5Wm45MDMwVw$JEtoP6YR/fiILceC8tuZFRBbLRhUytghMTH7rHlFdr0', 0, 0),
(62, 0, 'sarr', 'daouda', 'sarrdavid20@gmail.fr', '$argon2id$v=19$m=65536,t=4,p=1$MHpUdHQuRUp3YmlmeWViMg$4Jb2a/dzoVcX8uvqUigL2j5awipLasG6cIapLYOslCE', NULL, NULL);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
