-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mer. 05 nov. 2025 à 15:05
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
  `NOM_CATEGORIE` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`ID_CATEGORIE`),
  KEY `FK_CATEGORI_PEUT_ETRE_TYPE` (`ID_TYPE`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `categorie`
--

INSERT INTO `categorie` (`ID_CATEGORIE`, `ID_TYPE`, `NOM_CATEGORIE`) VALUES
(1, 1, 'Salaire'),
(2, 2, 'Dejeuner'),
(3, 2, 'Internet'),
(4, 2, 'Courses'),
(5, 1, 'Bourse'),
(6, 1, 'Vente'),
(7, 2, 'scolarité'),
(8, 2, 'Fourniture'),
(9, 2, 'Aliment\r\n'),
(10, 2, 'xbeeeeeeeeeet'),
(11, 1, 'exec'),
(12, 1, 'exec'),
(13, 2, 'exec'),
(14, 2, 'exec'),
(15, 1, 'akashooooo'),
(16, 2, 'akashiiiii');

-- --------------------------------------------------------

--
-- Structure de la table `operation`
--

DROP TABLE IF EXISTS `operation`;
CREATE TABLE IF NOT EXISTS `operation` (
  `ID_OPERATIONS_` int NOT NULL AUTO_INCREMENT,
  `ID_UTILISATEUR` int NOT NULL,
  `ID_CATEGORIE` int NOT NULL,
  `MONTANT` int DEFAULT NULL,
  `DATE_OPERATION` date DEFAULT NULL,
  `DESCRIPTION` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`ID_OPERATIONS_`),
  KEY `FK_OPERATIO_APPARTENI_CATEGORI` (`ID_CATEGORIE`),
  KEY `FK_OPERATIO_EFFECTUER_UTILISAT` (`ID_UTILISATEUR`)
) ENGINE=MyISAM AUTO_INCREMENT=85 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `operation`
--

INSERT INTO `operation` (`ID_OPERATIONS_`, `ID_UTILISATEUR`, `ID_CATEGORIE`, `MONTANT`, `DATE_OPERATION`, `DESCRIPTION`) VALUES
(1, 5, 1, 200000, '2025-08-07', 'houhaaa salaire du boulot'),
(2, 5, 2, 50000, '2025-08-06', 'j\'avais faimm'),
(3, 5, 3, 20000, '2025-08-06', 'enfin la fibre'),
(82, 5, 5, 2522, '2025-10-07', '66'),
(6, 5, 6, 100000, '2025-10-02', 'vente d\'ordinateur'),
(7, 5, 7, 60000, '2025-10-01', 'paiement de la scolarité'),
(8, 5, 8, 10000, '2025-08-20', 'Pour la rentrée scolaire\r\n'),
(9, 5, 9, 300000, '2025-10-06', 'denrée alimentaire'),
(10, 5, 10, 1000000, '2025-10-06', 'xbet'),
(76, 5, 9, 1234, '2025-10-09', 'depense'),
(83, 5, 11, 63333, '2025-10-06', '669'),
(81, 5, 5, 1000, '2025-10-12', 'dvs'),
(79, 5, 8, 111, '2025-10-09', 'p'),
(78, 5, 4, 111, '2025-09-17', 'm'),
(80, 5, 16, 123333, '2025-10-09', 'HMMMMM'),
(74, 5, 4, 1000000, '2025-10-02', 'essay'),
(84, 5, 15, 2000000, '2025-10-13', '663'),
(71, 5, 6, 200000, '2025-10-02', 'akashi');

-- --------------------------------------------------------

--
-- Structure de la table `type`
--

DROP TABLE IF EXISTS `type`;
CREATE TABLE IF NOT EXISTS `type` (
  `ID_TYPE` int NOT NULL,
  `NOM_TYPE` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
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
  `NOM_UTILISATEUR` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `PRENOM` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `EMAIL` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `MOT_DE_PASSE` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `CODE` int NOT NULL,
  `FLAG_REINITIALISATION` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID_UTILISATEUR`)
) ENGINE=MyISAM AUTO_INCREMENT=63 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`ID_UTILISATEUR`, `NOM_UTILISATEUR`, `PRENOM`, `EMAIL`, `MOT_DE_PASSE`, `CODE`, `FLAG_REINITIALISATION`) VALUES
(5, 'Akashi', 'Daouda', 'sarrdavid20@gmail.com', '$argon2i$v=19$m=65536,t=4,p=1$Y24vQ1d2d25mNm16VEd5RQ$LlFu6WwUU41+TS2ifIrHYSsaL5UrCBF2FlRF5k6nFI4', 936852, 1),
(55, 'Tatsumi', 'daouda', 'test20@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$UmVKN1JRWVNSYTRoOE05OQ$ZIQxNPMNlQ+4Ket2AmojjTr7gV3OX86HvnmW+sR1qFI', 0, 0),
(56, 'sarr', 'daouda', 'sarrrdavid@yahoo.com', '$argon2id$v=19$m=65536,t=4,p=1$ZDdaODlueWY1V3VIMTBpVQ$q8w1OZ800DSDbwdK7uyWjW8GZdnAo/5afflPX9ImYzo', 0, 0),
(57, 'fdv', 'daouda', 'sarrrdavid2@yahoo.com', '$argon2id$v=19$m=65536,t=4,p=1$QzRWLmJMdG1QTkxBNlBFUA$cup1KLRCU4qIpy8EVQEB5slWfYlNklX9K3fK5qAHsW4', 0, 0),
(58, 'sarr', 'daouda', 'sarrrdavihd@yahoo.com', '$argon2id$v=19$m=65536,t=4,p=1$SjVJR2tNRTdadmJIVVJBYg$L6P+xM9KJog+uJmAVOI+SQWfr8zQ+7Rxu50ryp4a208', 0, 0),
(59, 'sarr', 'daouda', 'sarrrdavid@yahoo.fr', '$argon2id$v=19$m=65536,t=4,p=1$OWQ1VUkzZy55Wm45MDMwVw$JEtoP6YR/fiILceC8tuZFRBbLRhUytghMTH7rHlFdr0', 0, 0),
(60, 'sarr', 'daouda', 'sarrrdasvid@yahoo.com', '$argon2id$v=19$m=65536,t=4,p=1$Qmh1M1NKQXRMUXNicURIdw$wH8+Cau4pTrKRb+/bMfpZ8a4al8C1qEPBcQlLMcIJOA', 0, 0),
(61, 'sarr', 'daouda', 'sarrrdavid20@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$Zjhtb0VnOG1zck9xYlQzTQ$6c//eIbQzQ0eaxrwWcQUXuE8eLQ7LJLlll7nz+690EQ', 0, 0),
(62, 'LY', 'ROSE', 'ROSE@GMAIL.com', '$argon2id$v=19$m=65536,t=4,p=1$d2dLMUZEaHBLTFBuNG9law$5vnwmqGKXF54+sIA1xWDT712tI5RsczHYt1oLjFp+mk', 0, 0);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
