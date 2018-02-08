-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Client :  127.0.0.1
-- Généré le :  Mar 28 Mars 2017 à 14:31
-- Version du serveur :  5.7.14
-- Version de PHP :  5.6.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `blackjack`
--

-- --------------------------------------------------------

--
-- Structure de la table `cards`
--

CREATE TABLE `cards` (
  `Card_Name` varchar(255) NOT NULL,
  `Card_Value` int(2) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Contenu de la table `cards`
--

INSERT INTO `cards` (`Card_Name`, `Card_Value`) VALUES
('1_of_clubs', 1),
('2_of_clubs', 2),
('3_of_clubs', 3),
('4_of_clubs', 4),
('5_of_clubs', 5),
('6_of_clubs', 6),
('7_of_clubs', 7),
('8_of_clubs', 8),
('9_of_clubs', 9),
('10_of_clubs', 10),
('jack_of_clubs', 10),
('queen_of_clubs', 10),
('king_of_clubs', 10),
('1_of_spades', 1),
('2_of_spades', 2),
('3_of_spades', 3),
('4_of_spades', 4),
('5_of_spades', 5),
('6_of_spades', 6),
('7_of_spades', 7),
('8_of_spades', 8),
('9_of_spades', 9),
('10_of_spades', 10),
('jack_of_spades', 10),
('queen_of_spades', 10),
('king_of_spades', 10),
('1_of_hearts', 1),
('2_of_hearts', 2),
('3_of_hearts', 3),
('4_of_hearts', 4),
('5_of_hearts', 5),
('6_of_hearts', 6),
('7_of_hearts', 7),
('8_of_hearts', 8),
('9_of_hearts', 9),
('10_of_hearts', 10),
('jack_of_hearts', 10),
('queen_of_hearts', 10),
('king_of_hearts', 10),
('king_of_diamonds', 10),
('queen_of_diamonds', 10),
('jack_of_diamonds', 10),
('10_of_diamonds', 10),
('9_of_diamonds', 9),
('8_of_diamonds', 8),
('7_of_diamonds', 7),
('6_of_diamonds', 6),
('5_of_diamonds', 5),
('4_of_diamonds', 4),
('3_of_diamonds', 3),
('2_of_diamonds', 2),
('1_of_diamonds', 1);

-- --------------------------------------------------------

--
-- Structure de la table `game`
--

CREATE TABLE `game` (
  `Card_Name` varchar(255) NOT NULL,
  `Card_Value` int(2) NOT NULL,
  `Player_Name` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `players`
--

CREATE TABLE `players` (
  `Player_Name` varchar(255) NOT NULL,
  `MDP` varchar(255) NOT NULL,
  `Account` int(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Contenu de la table `players`
--

INSERT INTO `players` (`Player_Name`, `MDP`, `Account`) VALUES
('Benjamin', 'Aze', 100),
('Ben', '', 100);

-- --------------------------------------------------------

--
-- Structure de la table `sabot`
--

CREATE TABLE `sabot` (
  `Card_Name` varchar(255) NOT NULL,
  `Card_Value` int(2) NOT NULL,
  `ID` int(4) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Index pour les tables exportées
--

--
-- Index pour la table `sabot`
--
ALTER TABLE `sabot`
  ADD KEY `ID` (`ID`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `sabot`
--
ALTER TABLE `sabot`
  MODIFY `ID` int(4) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
