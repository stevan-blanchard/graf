-- phpMyAdmin SQL Dump
-- version 4.9.5deb2
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : mar. 02 fév. 2021 à 16:26
-- Version du serveur :  10.3.25-MariaDB-0ubuntu0.20.04.1
-- Version de PHP : 7.4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `graf`
--

-- --------------------------------------------------------

--
-- Structure de la table `author`
--

CREATE TABLE `author` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `includeInProjects`
--

CREATE TABLE `includeInProjects` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `color` varchar(7) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `includeInProjects`
--

INSERT INTO `includeInProjects` (`id`, `name`, `color`) VALUES
(1, 'aucune', '#0000ff'),
(2, 'communication', '#FF00FC'),
(3, 'administratif', '#0eff00');

-- --------------------------------------------------------

--
-- Structure de la table `membre`
--

CREATE TABLE `membre` (
  `id` int(11) NOT NULL,
  `author_id` int(1) NOT NULL,
  `pseudo` varchar(255) NOT NULL,
  `mail` varchar(255) NOT NULL,
  `password` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `RAF`
--

CREATE TABLE `RAF` (
  `id` int(11) NOT NULL,
  `author_id` int(11) NOT NULL,
  `description` varchar(255) NOT NULL,
  `duration` decimal(11,2) NOT NULL,
  `priority` int(4) NOT NULL,
  `deadline` date NOT NULL,
  `includeInProject_id` int(11) NOT NULL,
  `un_tiers` int(1) DEFAULT NULL,
  `deux_tiers` int(1) DEFAULT NULL,
  `trois_tiers` int(1) DEFAULT NULL,
  `observation` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `old_RAF`
--

CREATE TABLE `old_RAF` (
  `id` int(11) NOT NULL,
  `raf_id` int(11) NOT NULL,
  `author_id` int(11) NOT NULL,
  `description` varchar(255) NOT NULL,
  `duration` decimal(11,2) NOT NULL,
  `priority` int(4) NOT NULL,
  `deadline` date NOT NULL,
  `includeInProject_id` int(11) NOT NULL,
  `un_tiers` int(1) DEFAULT NULL,
  `deux_tiers` int(1) DEFAULT NULL,
  `trois_tiers` int(1) DEFAULT NULL,
  `observation` varchar(255) DEFAULT NULL,
  `date_modif`  varchar(255)  NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
--
-- Index pour les tables déchargées
--

--
-- Index pour la table `author`
--
ALTER TABLE `author`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `includeInProjects`
--
ALTER TABLE `includeInProjects`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `membre`
--
ALTER TABLE `membre`
  ADD PRIMARY KEY (`id`),
  ADD KEY `author_id` (`author_id`);

--
-- Index pour la table `RAF`
--
ALTER TABLE `RAF`
  ADD PRIMARY KEY (`id`),
  ADD KEY `author_id` (`author_id`),
  ADD KEY `includeInProject_id` (`includeInProject_id`);
--
-- Index pour la table `author`
--
ALTER TABLE `old_RAF`
  ADD PRIMARY KEY (`id`);


--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `author`
--
ALTER TABLE `author`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `includeInProjects`
--
ALTER TABLE `includeInProjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `membre`
--
ALTER TABLE `membre`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `RAF`
--
ALTER TABLE `RAF`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `old_RAF`
--
ALTER TABLE `old_RAF`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `membre`
--
ALTER TABLE `membre`
  ADD CONSTRAINT `membre_ibfk_1` FOREIGN KEY (`author_id`) REFERENCES `author` (`id`);

--
-- Contraintes pour la table `RAF`
--
ALTER TABLE `RAF`
  ADD CONSTRAINT `RAF_ibfk_1` FOREIGN KEY (`author_id`) REFERENCES `author` (`id`),
  ADD CONSTRAINT `RAF_ibfk_2` FOREIGN KEY (`includeInProject_id`) REFERENCES `includeInProjects` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
