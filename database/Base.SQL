-- phpMyAdmin SQL Dump
-- version 3.3.9.2
-- http://www.phpmyadmin.net
--
-- Serveur: 127.0.0.1
-- Généré le : Mer 04 Mai 2011 à 10:21
-- Version du serveur: 5.1.49
-- Version de PHP: 5.3.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `trombinoscope`
--

-- --------------------------------------------------------

--
-- Structure de la table `association`
--

CREATE TABLE IF NOT EXISTS `association` (
  `ID_ASSOCIATION` bigint(4) NOT NULL AUTO_INCREMENT,
  `NOM` char(32) COLLATE latin1_general_ci DEFAULT NULL,
  `IMAGE` char(128) COLLATE latin1_general_ci NOT NULL,
  `SITE` varchar(128) COLLATE latin1_general_ci NOT NULL,
  `PRESIDENT` bigint(5) NOT NULL,
  `VICE_PRESIDENT` bigint(5) NOT NULL,
  `SECRETAIRE` bigint(5) NOT NULL,
  `TRESORIER` bigint(5) NOT NULL,
  PRIMARY KEY (`ID_ASSOCIATION`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=5 ;

--
-- Contenu de la table `association`
--

INSERT INTO `association` (`ID_ASSOCIATION`, `NOM`, `IMAGE`, `SITE`, `PRESIDENT`, `VICE_PRESIDENT`, `SECRETAIRE`, `TRESORIER`) VALUES
(1, 'EPSILab', 'associations/epsilab.png', 'http://www.epsilab.net', 12, 33, 24, 23),
(2, 'EPSINUX', 'associations/epsinux.png', 'http://www.epsinux.org', 4, 5, 13, 15),
(3, 'EPSIGame', 'associations/epsigame.png', 'http://www.epsigame.org/', 3, 59, 51, 49),
(4, 'EPSIMotion', 'associations/epsimotion.png', 'http://www.p0k3.fr', 17, 8, 27, 9);
