-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  mar. 12 juin 2018 à 12:27
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
-- Base de données :  `meme`
--

-- --------------------------------------------------------

--
-- Structure de la table `images`
--

DROP TABLE IF EXISTS `images`;
CREATE TABLE IF NOT EXISTS `images` (
  `id_images` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `ima_category` varchar(255) NOT NULL,
  `ima_title` varchar(255) NOT NULL,
  `ima_url` varchar(255) NOT NULL,
  PRIMARY KEY (`id_images`),
  UNIQUE KEY `id_images_UNIQUE` (`id_images`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `memes`
--

DROP TABLE IF EXISTS `memes`;
CREATE TABLE IF NOT EXISTS `memes` (
  `id_memes` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `mem_title` varchar(255) NOT NULL,
  `mem_description` longtext NOT NULL,
  `mem_text` varchar(255) NOT NULL,
  `mem_blob` blob NOT NULL,
  PRIMARY KEY (`id_memes`),
  UNIQUE KEY `id_memes_UNIQUE` (`id_memes`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
