-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : sam. 30 août 2025 à 22:01
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
-- Base de données : `gestion_depense`
--

-- --------------------------------------------------------

--
-- Structure de la table `categorie`
--

DROP TABLE IF EXISTS `categorie`;
CREATE TABLE IF NOT EXISTS `categorie` (
  `ID_CATEGORIE` int NOT NULL,
  `ID_TYPE` int NOT NULL,
  `NOM_CATEGORIE` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`ID_CATEGORIE`),
  KEY `FK_CATEGORI_PEUT_ETRE_TYPE` (`ID_TYPE`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `categorie`
--

INSERT INTO `categorie` (`ID_CATEGORIE`, `ID_TYPE`, `NOM_CATEGORIE`) VALUES
(1, 1, 'Salaire'),
(2, 2, 'Dejeuner'),
(3, 2, 'Internet'),
(4, 2, 'Courses'),
(5, 1, 'Bourse');

-- --------------------------------------------------------

--
-- Structure de la table `operation`
--

DROP TABLE IF EXISTS `operation`;
CREATE TABLE IF NOT EXISTS `operation` (
  `ID_OPERATIONS_` int NOT NULL,
  `ID_UTILISATEUR` int NOT NULL,
  `ID_CATEGORIE` int NOT NULL,
  `MONTANT` int DEFAULT NULL,
  `DATE_OPERATION` date DEFAULT NULL,
  `DESCRIPTION` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`ID_OPERATIONS_`),
  KEY `FK_OPERATIO_APPARTENI_CATEGORI` (`ID_CATEGORIE`),
  KEY `FK_OPERATIO_EFFECTUER_UTILISAT` (`ID_UTILISATEUR`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `operation`
--

INSERT INTO `operation` (`ID_OPERATIONS_`, `ID_UTILISATEUR`, `ID_CATEGORIE`, `MONTANT`, `DATE_OPERATION`, `DESCRIPTION`) VALUES
(1, 1, 1, 200000, '2025-08-07', 'houhaaa salaire du boulot'),
(2, 1, 2, 3000, '2025-08-06', 'j\'avais faimm'),
(3, 3, 2, 20000, '2025-08-06', 'enfin la fibre'),
(4, 4, 2, 30000, '2025-12-20', 'le frigo etait vide'),
(5, 5, 1, 60000, '2025-10-06', 'bourse d\'etat\r\n');

-- --------------------------------------------------------

--
-- Structure de la table `type`
--

DROP TABLE IF EXISTS `type`;
CREATE TABLE IF NOT EXISTS `type` (
  `ID_TYPE` int NOT NULL,
  `NOM_TYPE` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`ID_TYPE`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `NOM_UTILISATEUR` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `PRENOM` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `EMAIL` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `MOT_DE_PASSE` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `CODE` int NOT NULL,
  `FLAG_REINITIALISATION` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID_UTILISATEUR`)
) ENGINE=MyISAM AUTO_INCREMENT=56 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`ID_UTILISATEUR`, `NOM_UTILISATEUR`, `PRENOM`, `EMAIL`, `MOT_DE_PASSE`, `CODE`, `FLAG_REINITIALISATION`) VALUES
(5, 'Tatsumi', 'daouda', 'sarrdavid20@gmail.com', '$argon2i$v=19$m=65536,t=4,p=1$OExhclAuOFlGR1I1V01HbQ$OrSgMdsPbs4apUFrJS0vIlaci8AvQKzu4XxLMI89ULU', 0, 0),
(55, 'Tatsumi', 'daouda', 'test20@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$UmVKN1JRWVNSYTRoOE05OQ$ZIQxNPMNlQ+4Ket2AmojjTr7gV3OX86HvnmW+sR1qFI', 0, 0);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
