-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  ven. 29 juin 2018 à 12:27
-- Version du serveur :  5.7.19
-- Version de PHP :  7.1.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `wetransferlike`
--

-- --------------------------------------------------------

--
-- Structure de la table `migration_versions`
--

DROP TABLE IF EXISTS `migration_versions`;
CREATE TABLE IF NOT EXISTS `migration_versions` (
  `version` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `migration_versions`
--

INSERT INTO `migration_versions` (`version`) VALUES
('20180627142644');

-- --------------------------------------------------------

--
-- Structure de la table `transfer`
--

DROP TABLE IF EXISTS `transfer`;
CREATE TABLE IF NOT EXISTS `transfer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email_expediteur` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_destinataire` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_copie` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `url_file` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `transfer`
--

INSERT INTO `transfer` (`id`, `email_expediteur`, `email_destinataire`, `email_copie`, `url_file`) VALUES
(3, '. expediteur@gmail.com . ', ' . destinataire@gmail.com . ', ' . 1 . ', '. assets/file_uploaded/2c2dce.jpg . '),
(4, '. expediteur@gmail.com . ', ' . destinataire@gmail.com . ', ' . 1 . ', '. assets/file_uploaded/2c2dce.jpg . '),
(5, ' expediteur@gmail.com', 'destinataire@gmail.com', '1', 'assets/file_uploaded/youtube.jpg'),
(6, ' expediteur@gmail.com', 'destinataire@gmail.com', '1', 'assets/file_uploaded/George_Owen_Squierz.jpg'),
(7, 'destinataire@gmail.com', 'expediteur@gmail.com', '1', 'app/assets/file_uploaded/Hulu-logo.jpg');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
