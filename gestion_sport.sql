-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : ven. 15 nov. 2024 à 09:37
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `gestion_sport`
--

-- --------------------------------------------------------

--
-- Structure de la table `joueurs`
--

CREATE TABLE `joueurs` (
  `idJoueur` int(11) NOT NULL,
  `Numéro_de_licence` char(10) NOT NULL,
  `Nom` varchar(20) DEFAULT NULL,
  `Prenom` varchar(20) DEFAULT NULL,
  `Date_de_naissance` date DEFAULT NULL,
  `Taille` double DEFAULT NULL,
  `Poids` tinyint(4) DEFAULT NULL,
  `Commentaire` text DEFAULT NULL,
  `Statut` varchar(8) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `joueurs`
--

INSERT INTO `joueurs` (`idJoueur`, `Numéro_de_licence`, `Nom`, `Prenom`, `Date_de_naissance`, `Taille`, `Poids`, `Commentaire`, `Statut`) VALUES
(1, 'VOL1234567', 'Dubois', 'Antoine', '1996-08-14', 1.98, 85, 'Excellent au bloc, très bon timing.', 'Actif'),
(2, 'VOL2345678', 'Lambert', 'Thomas', '1999-02-10', 1.9, 80, 'Serveur puissant, très bon en attaque.', 'Actif'),
(3, 'VOL3456789', 'Morel', 'Julien', '1994-12-05', 1.92, 88, 'Réceptionneur efficace, solide en défense.', 'Actif'),
(4, 'VOL4567890', 'Lemoine', 'Alexandre', '1998-04-19', 2.01, 95, 'Très bon smash, puissant au filet.', 'Actif'),
(5, 'VOL5678901', 'Rousseau', 'Maxime', '1997-09-25', 1.85, 78, 'Excellent passeur, précis et rapide.', 'Actif'),
(6, 'VOL6789012', 'Perrin', 'Nicolas', '2000-07-17', 1.94, 83, 'Très bon en défense, endurant.', 'Actif'),
(7, 'VOL7890123', 'Garnier', 'Lucas', '1993-11-30', 1.88, 82, 'Serveur habile, très bon au filet.', 'Actif'),
(8, 'VOL8901234', 'Leclerc', 'Romain', '1995-01-23', 1.95, 87, 'Grand bloqueur, très fort en attaque.', 'Actif'),
(9, 'VOL9012345', 'Dupuis', 'Adrien', '1994-05-16', 1.87, 79, 'Très bonne vision du jeu, passeur agile.', 'Actif'),
(10, 'VOL0123456', 'Meyer', 'François', '1996-03-22', 1.93, 84, 'Bon en réception, physique solide.', 'Actif'),
(11, 'VOL1123456', 'Masson', 'David', '1999-06-11', 1.91, 81, 'Très mobile, excellent en défense.', 'Actif'),
(12, 'VOL7654321', 'Ayala', 'Ulysse', '2006-05-10', 1.67, 53, 'Petit mais vif, bonnes réceptions', 'Actif');

-- --------------------------------------------------------

--
-- Structure de la table `matchs`
--

CREATE TABLE `matchs` (
  `Date_heure_match` datetime NOT NULL,
  `Nom_equipe_adverse` varchar(30) DEFAULT NULL,
  `Rencontre_domicile` tinyint(1) DEFAULT NULL,
  `Score` varchar(33) DEFAULT NULL,
  `Resultat` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `matchs`
--

INSERT INTO `matchs` (`Date_heure_match`, `Nom_equipe_adverse`, `Rencontre_domicile`, `Score`, `Resultat`) VALUES
('2024-01-15 18:30:00', 'Tigers Volley', 1, '25-20, 22-25, 25-18, 23-25, 15-12', 1),
('2024-01-20 15:00:00', 'Eagles Volley', 0, '20-25, 25-23, 19-25, 25-18, 13-15', 0),
('2024-02-05 17:00:00', 'Sharks Volley', 1, '25-17, 25-22, 25-20', 1),
('2024-02-10 16:00:00', 'Wolves Volley', 0, '23-25, 26-24, 25-23, 25-21', 1),
('2024-02-20 19:00:00', 'Bulls Volley', 1, '25-20, 18-25, 25-22, 25-19', 1),
('2024-03-03 14:30:00', 'Falcons Volley', 0, '22-25, 25-22, 20-25, 25-21, 13-15', 0),
('2024-03-10 17:30:00', 'Lions Volley', 1, '25-18, 25-20, 25-19', 1),
('2024-03-15 18:00:00', 'Panthers Volley', 0, '18-25, 25-23, 22-25, 21-25', 0),
('2024-03-22 20:00:00', 'Bears Volley', 1, '25-23, 25-20, 25-17', 1),
('2024-04-01 16:00:00', 'Hawks Volley', 0, '23-25, 25-21, 25-18, 22-25, 12-15', 0),
('2024-04-08 18:00:00', 'Rhinos Volley', 1, '25-22, 26-24, 25-19', 1);

-- --------------------------------------------------------

--
-- Structure de la table `participer`
--

CREATE TABLE `participer` (
  `idJoueur` int(11) NOT NULL,
  `Date_heure_match` datetime NOT NULL,
  `Role_titulaire` tinyint(1) DEFAULT NULL,
  `Poste` varchar(20) DEFAULT NULL,
  `Note` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `participer`
--

INSERT INTO `participer` (`idJoueur`, `Date_heure_match`, `Role_titulaire`, `Poste`, `Note`) VALUES
(1, '2024-01-15 18:30:00', 1, 'Attaquant', 7),
(1, '2024-01-20 15:00:00', 1, 'Attaquant', 6),
(1, '2024-02-05 17:00:00', 1, 'Attaquant', 9),
(1, '2024-04-01 16:00:00', 1, 'Attaquant', 8),
(2, '2024-01-15 18:30:00', 1, 'Passeur', 8),
(2, '2024-01-20 15:00:00', 1, 'Passeur', 7),
(2, '2024-02-05 17:00:00', 1, 'Passeur', 8),
(2, '2024-04-01 16:00:00', 1, 'Passeur', 7),
(3, '2024-01-15 18:30:00', 1, 'Libero', 6),
(3, '2024-01-20 15:00:00', 1, 'Libero', 8),
(3, '2024-02-05 17:00:00', 1, 'Libero', 7),
(3, '2024-04-01 16:00:00', 1, 'Centre', 8),
(4, '2024-01-15 18:30:00', 1, 'Attaquant', 7),
(4, '2024-01-20 15:00:00', 1, 'Attaquant', 9),
(4, '2024-02-05 17:00:00', 1, 'Attaquant', 9),
(4, '2024-04-01 16:00:00', 1, 'Attaquant', 9),
(5, '2024-01-15 18:30:00', 1, 'Centre', 9),
(5, '2024-01-20 15:00:00', 1, 'Centre', 7),
(5, '2024-02-05 17:00:00', 1, 'Centre', 8),
(5, '2024-04-01 16:00:00', 1, 'Centre', 8),
(6, '2024-01-15 18:30:00', 1, 'Attaquant', 8),
(6, '2024-01-20 15:00:00', 0, 'Remplaçant', 5),
(6, '2024-02-05 17:00:00', 1, 'Centre', 9),
(6, '2024-04-01 16:00:00', 0, 'Remplaçant', 6),
(7, '2024-01-15 18:30:00', 0, 'Remplaçant', 5),
(7, '2024-01-20 15:00:00', 0, 'Remplaçant', 6),
(7, '2024-02-05 17:00:00', 0, 'Remplaçant', 5),
(7, '2024-04-01 16:00:00', 0, 'Remplaçant', 5),
(8, '2024-01-15 18:30:00', 0, 'Remplaçant', 6),
(8, '2024-02-05 17:00:00', 0, 'Remplaçant', 6),
(8, '2024-04-01 16:00:00', 0, 'Remplaçant', 7),
(9, '2024-01-15 18:30:00', 0, 'Remplaçant', 7),
(9, '2024-02-05 17:00:00', 0, 'Remplaçant', 5),
(10, '2024-01-15 18:30:00', 0, 'Remplaçant', 6),
(10, '2024-02-05 17:00:00', 0, 'Remplaçant', 6),
(11, '2024-02-05 17:00:00', 0, 'Remplaçant', 7),
(12, '2024-01-20 15:00:00', 0, 'Remplaçant', 7),
(12, '2024-02-05 17:00:00', 0, 'Remplaçant', 8),
(12, '2024-04-01 16:00:00', 1, 'Libero', 9);

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `Id_Utilisateur` int(11) NOT NULL,
  `login` varchar(50) DEFAULT NULL,
  `mdp` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `joueurs`
--
ALTER TABLE `joueurs`
  ADD PRIMARY KEY (`idJoueur`),
  ADD UNIQUE KEY `Numéro_de_licence` (`Numéro_de_licence`);

--
-- Index pour la table `matchs`
--
ALTER TABLE `matchs`
  ADD PRIMARY KEY (`Date_heure_match`);

--
-- Index pour la table `participer`
--
ALTER TABLE `participer`
  ADD PRIMARY KEY (`idJoueur`,`Date_heure_match`),
  ADD KEY `Date_heure_match` (`Date_heure_match`);

--
-- Index pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`Id_Utilisateur`),
  ADD UNIQUE KEY `login` (`login`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `joueurs`
--
ALTER TABLE `joueurs`
  MODIFY `idJoueur` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `Id_Utilisateur` int(11) NOT NULL AUTO_INCREMENT;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `participer`
--
ALTER TABLE `participer`
  ADD CONSTRAINT `participer_ibfk_1` FOREIGN KEY (`idJoueur`) REFERENCES `joueurs` (`idJoueur`),
  ADD CONSTRAINT `participer_ibfk_2` FOREIGN KEY (`Date_heure_match`) REFERENCES `matchs` (`Date_heure_match`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
