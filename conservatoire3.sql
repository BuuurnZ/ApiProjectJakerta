-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:8889
-- Généré le : mer. 12 juin 2024 à 08:40
-- Version du serveur : 5.7.39
-- Version de PHP : 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `conservatoire3`
--

-- --------------------------------------------------------

--
-- Structure de la table `CLASSE`
--

CREATE TABLE `CLASSE` (
  `IDCLASSE` int(11) NOT NULL,
  `IDINSTRUMENT` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `CLASSE`
--

INSERT INTO `CLASSE` (`IDCLASSE`, `IDINSTRUMENT`) VALUES
(1, 1),
(2, 2);

-- --------------------------------------------------------

--
-- Structure de la table `CLASSE_ELEVE`
--

CREATE TABLE `CLASSE_ELEVE` (
  `IDCLASSE` int(11) NOT NULL,
  `IDELEVE` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `CLASSE_ELEVE`
--

INSERT INTO `CLASSE_ELEVE` (`IDCLASSE`, `IDELEVE`) VALUES
(2, 2);

-- --------------------------------------------------------

--
-- Structure de la table `ELEVE`
--

CREATE TABLE `ELEVE` (
  `IDELEVE` int(11) NOT NULL,
  `IDUTILISATEUR` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `ELEVE`
--

INSERT INTO `ELEVE` (`IDELEVE`, `IDUTILISATEUR`) VALUES
(2, 4);

-- --------------------------------------------------------

--
-- Structure de la table `INSTRUMENT`
--

CREATE TABLE `INSTRUMENT` (
  `IDINSTRUMENT` int(11) NOT NULL,
  `LIBELLE` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `INSTRUMENT`
--

INSERT INTO `INSTRUMENT` (`IDINSTRUMENT`, `LIBELLE`) VALUES
(1, 'Piano'),
(2, 'Guitare'),
(3, 'Violon');

-- --------------------------------------------------------

--
-- Structure de la table `INSTRUMENT_UTILISATEUR`
--

CREATE TABLE `INSTRUMENT_UTILISATEUR` (
  `IDINSTRUMENT` int(11) NOT NULL,
  `IDUTILISATEUR` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `INSTRUMENT_UTILISATEUR`
--

INSERT INTO `INSTRUMENT_UTILISATEUR` (`IDINSTRUMENT`, `IDUTILISATEUR`) VALUES
(1, 4),
(2, 4),
(3, 4),
(1, 43),
(2, 43),
(3, 43),
(2, 44),
(3, 44);

-- --------------------------------------------------------

--
-- Structure de la table `PROFESSEUR`
--

CREATE TABLE `PROFESSEUR` (
  `IDPROFESSEUR` int(11) NOT NULL,
  `IDUTILISATEUR` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `PROFESSEUR`
--

INSERT INTO `PROFESSEUR` (`IDPROFESSEUR`, `IDUTILISATEUR`) VALUES
(1, 2),
(2, 3),
(3, 5),
(26, 43),
(27, 44);

-- --------------------------------------------------------

--
-- Structure de la table `SEANCE`
--

CREATE TABLE `SEANCE` (
  `IDSEANCE` int(11) NOT NULL,
  `IDPROFESSEUR` int(11) NOT NULL,
  `IDCLASSE` int(11) NOT NULL,
  `DATE` date DEFAULT NULL,
  `HEUREDEBUT` time DEFAULT NULL,
  `HEUREFIN` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `SEANCE`
--

INSERT INTO `SEANCE` (`IDSEANCE`, `IDPROFESSEUR`, `IDCLASSE`, `DATE`, `HEUREDEBUT`, `HEUREFIN`) VALUES
(1, 1, 1, '2024-05-16', '10:00:00', '11:00:00'),
(2, 2, 2, '2024-05-17', '14:00:00', '15:00:00'),
(3, 3, 1, '2024-05-18', '09:00:00', '10:00:00');

-- --------------------------------------------------------

--
-- Structure de la table `UTILISATEUR`
--

CREATE TABLE `UTILISATEUR` (
  `IDUTILISATEUR` int(11) NOT NULL,
  `NOM` varchar(50) DEFAULT NULL,
  `PRENOM` varchar(50) DEFAULT NULL,
  `TELEPHONE` varchar(50) DEFAULT NULL,
  `ADRESSE` varchar(50) DEFAULT NULL,
  `MAIL` varchar(50) DEFAULT NULL,
  `MDP` varchar(50) DEFAULT NULL,
  `EST_ADMIN` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `UTILISATEUR`
--

INSERT INTO `UTILISATEUR` (`IDUTILISATEUR`, `NOM`, `PRENOM`, `TELEPHONE`, `ADRESSE`, `MAIL`, `MDP`, `EST_ADMIN`) VALUES
(2, 'Durand', 'Marie', '987654321', '5 avenue des Étoiles', 'marie.durand@mail.com', 'mdp456', 1),
(3, 'Martin', 'Paul', '654321098', '12 rue des Champs', 'paul.martin@mail.com', 'azerty', 0),
(4, 'Lefevre', 'Sophie', '234567890', '7 rue des Lilas', 'sophie.lefevre@mail.com', 'mdp78', 0),
(5, 'Garcia', 'Carlos', '478956231', '25 boulevard des Arts', 'carlos.garcia@mail.com', 'password', 0),
(40, 'Flo', 'Ger', '0633372813', '121 rue du role ', 'ger@gmail.com', 'azer', 1),
(43, 'rrrr', 'rrrr', '0633382691', 'zerrez', 'rrr@g.c', 'ffff', 0),
(44, 'test', 'prof', '0633382691', 'rze', 'prof@e.co', 'ez', 0),
(45, 'eeee', 'eeee', '0633382691', 'rrrrr', 'eee@g.c', 'rrrr', 1);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `CLASSE`
--
ALTER TABLE `CLASSE`
  ADD PRIMARY KEY (`IDCLASSE`),
  ADD KEY `FK_CONCERNE` (`IDINSTRUMENT`);

--
-- Index pour la table `CLASSE_ELEVE`
--
ALTER TABLE `CLASSE_ELEVE`
  ADD PRIMARY KEY (`IDCLASSE`,`IDELEVE`),
  ADD KEY `FK_APPARTIENT` (`IDELEVE`);

--
-- Index pour la table `ELEVE`
--
ALTER TABLE `ELEVE`
  ADD PRIMARY KEY (`IDELEVE`),
  ADD KEY `FK_HERITAGE_1` (`IDUTILISATEUR`);

--
-- Index pour la table `INSTRUMENT`
--
ALTER TABLE `INSTRUMENT`
  ADD PRIMARY KEY (`IDINSTRUMENT`);

--
-- Index pour la table `INSTRUMENT_UTILISATEUR`
--
ALTER TABLE `INSTRUMENT_UTILISATEUR`
  ADD PRIMARY KEY (`IDINSTRUMENT`,`IDUTILISATEUR`),
  ADD KEY `FK_JOUE` (`IDUTILISATEUR`);

--
-- Index pour la table `PROFESSEUR`
--
ALTER TABLE `PROFESSEUR`
  ADD PRIMARY KEY (`IDPROFESSEUR`),
  ADD KEY `FK_HERITAGE_2` (`IDUTILISATEUR`);

--
-- Index pour la table `SEANCE`
--
ALTER TABLE `SEANCE`
  ADD PRIMARY KEY (`IDSEANCE`),
  ADD KEY `FK_PARTICIPE` (`IDCLASSE`),
  ADD KEY `FK_SUPERVISE` (`IDPROFESSEUR`);

--
-- Index pour la table `UTILISATEUR`
--
ALTER TABLE `UTILISATEUR`
  ADD PRIMARY KEY (`IDUTILISATEUR`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `CLASSE`
--
ALTER TABLE `CLASSE`
  MODIFY `IDCLASSE` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `ELEVE`
--
ALTER TABLE `ELEVE`
  MODIFY `IDELEVE` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `INSTRUMENT`
--
ALTER TABLE `INSTRUMENT`
  MODIFY `IDINSTRUMENT` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `PROFESSEUR`
--
ALTER TABLE `PROFESSEUR`
  MODIFY `IDPROFESSEUR` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT pour la table `SEANCE`
--
ALTER TABLE `SEANCE`
  MODIFY `IDSEANCE` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `UTILISATEUR`
--
ALTER TABLE `UTILISATEUR`
  MODIFY `IDUTILISATEUR` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `CLASSE`
--
ALTER TABLE `CLASSE`
  ADD CONSTRAINT `FK_CONCERNE` FOREIGN KEY (`IDINSTRUMENT`) REFERENCES `INSTRUMENT` (`IDINSTRUMENT`) ON DELETE CASCADE;

--
-- Contraintes pour la table `CLASSE_ELEVE`
--
ALTER TABLE `CLASSE_ELEVE`
  ADD CONSTRAINT `FK_APPARTIENT` FOREIGN KEY (`IDELEVE`) REFERENCES `ELEVE` (`IDELEVE`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_APPARTIENT2` FOREIGN KEY (`IDCLASSE`) REFERENCES `CLASSE` (`IDCLASSE`) ON DELETE CASCADE;

--
-- Contraintes pour la table `ELEVE`
--
ALTER TABLE `ELEVE`
  ADD CONSTRAINT `FK_HERITAGE_1` FOREIGN KEY (`IDUTILISATEUR`) REFERENCES `UTILISATEUR` (`IDUTILISATEUR`) ON DELETE CASCADE;

--
-- Contraintes pour la table `INSTRUMENT_UTILISATEUR`
--
ALTER TABLE `INSTRUMENT_UTILISATEUR`
  ADD CONSTRAINT `FK_JOUE` FOREIGN KEY (`IDUTILISATEUR`) REFERENCES `UTILISATEUR` (`IDUTILISATEUR`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_JOUE2` FOREIGN KEY (`IDINSTRUMENT`) REFERENCES `INSTRUMENT` (`IDINSTRUMENT`) ON DELETE CASCADE;

--
-- Contraintes pour la table `PROFESSEUR`
--
ALTER TABLE `PROFESSEUR`
  ADD CONSTRAINT `FK_HERITAGE_2` FOREIGN KEY (`IDUTILISATEUR`) REFERENCES `UTILISATEUR` (`IDUTILISATEUR`) ON DELETE CASCADE;

--
-- Contraintes pour la table `SEANCE`
--
ALTER TABLE `SEANCE`
  ADD CONSTRAINT `FK_PARTICIPE` FOREIGN KEY (`IDCLASSE`) REFERENCES `CLASSE` (`IDCLASSE`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_SUPERVISE` FOREIGN KEY (`IDPROFESSEUR`) REFERENCES `PROFESSEUR` (`IDPROFESSEUR`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
