-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le : mar. 29 avr. 2025 à 13:55
-- Version du serveur : 10.11.11-MariaDB-0+deb12u1
-- Version de PHP : 8.2.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `phpprojet`
--

-- --------------------------------------------------------

--
-- Structure de la table `tickets`
--

CREATE TABLE `tickets` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `sujet` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `statut` varchar(50) DEFAULT 'ouvert'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `tickets`
--

INSERT INTO `tickets` (`id`, `nom`, `email`, `sujet`, `message`, `created_at`, `statut`) VALUES
(1, 'Dallet', 'alexandreadalletpro@gmail.com', 'Bug', 'bug css', '2025-04-21 23:58:07', 'en cours'),
(2, 'dd', 'alexandreadalletpro@gmail.com', 'ddd', 'dddd', '2025-04-22 06:34:43', 'ouvert'),
(3, 'dd', 'alexandreadalletpro@gmail.com', 'ddd', 'dddd', '2025-04-22 06:36:22', 'ouvert'),
(4, 'dd', 'alexandreadalletpro@gmail.com', 'ddd', 'dddd', '2025-04-22 06:37:35', 'ouvert'),
(5, 'ds', 'alexandreadalletpro@gmail.com', 'ds', 'sd', '2025-04-22 06:40:29', 'ouvert'),
(6, 'ds', 'alexandreadalletpro@gmail.com', 'ds', 'sd', '2025-04-22 06:42:29', 'ouvert'),
(7, 's', 'alexandreadalletpro@gmail.com', 'a', 'a', '2025-04-22 07:20:43', 'ouvert'),
(8, 'H', 'alexandredalletpro@gmail.com', 'H', 'G', '2025-04-22 13:59:17', 'en cours');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `tickets`
--
ALTER TABLE `tickets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
