-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3307
-- Généré le : ven. 25 juil. 2025 à 02:07
-- Version du serveur : 11.5.2-MariaDB
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
CREATE DATABASE IF NOT EXISTS `gestion_depenses` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `gestion_depenses`;

-- --------------------------------------------------------

--
-- Structure de la table `categorie`
--

DROP TABLE IF EXISTS `categorie`;
CREATE TABLE IF NOT EXISTS `categorie` (
  `ID_CATEGORIE` int(11) NOT NULL,
  `ID_TYPE` int(11) NOT NULL,
  `NOM_CATEGORIE` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`ID_CATEGORIE`),
  KEY `FK_CATEGORI_PEUT_ETRE_TYPE` (`ID_TYPE`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Structure de la table `operation`
--

DROP TABLE IF EXISTS `operation`;
CREATE TABLE IF NOT EXISTS `operation` (
  `ID_OPERATIONS_` int(11) NOT NULL,
  `ID_UTILISATEUR` int(11) NOT NULL,
  `ID_CATEGORIE` int(11) NOT NULL,
  `MONTANT` int(11) DEFAULT NULL,
  `DATE_OPERATION` date DEFAULT NULL,
  `DESCRIPTION` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`ID_OPERATIONS_`),
  KEY `FK_OPERATIO_APPARTENI_CATEGORI` (`ID_CATEGORIE`),
  KEY `FK_OPERATIO_EFFECTUER_UTILISAT` (`ID_UTILISATEUR`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Structure de la table `type`
--

DROP TABLE IF EXISTS `type`;
CREATE TABLE IF NOT EXISTS `type` (
  `ID_TYPE` int(11) NOT NULL,
  `NOM_TYPE` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`ID_TYPE`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

DROP TABLE IF EXISTS `utilisateur`;
CREATE TABLE IF NOT EXISTS `utilisateur` (
  `ID_UTILISATEUR` int(11) NOT NULL,
  `NOM_UTILISATEUR` varchar(255) DEFAULT NULL,
  `PRENOM` varchar(255) DEFAULT NULL,
  `EMAIL` varchar(255) DEFAULT NULL,
  `MOT_DE_PASSE` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`ID_UTILISATEUR`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
