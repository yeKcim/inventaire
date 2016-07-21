-- phpMyAdmin SQL Dump
-- version 4.2.12deb2+deb8u1
-- http://www.phpmyadmin.net
--
-- Client :  localhost
-- Généré le :  Jeu 21 Juillet 2016 à 14:56
-- Version du serveur :  5.5.49-0+deb8u1
-- Version de PHP :  5.6.23-0+deb8u1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données :  `optique`
--

-- --------------------------------------------------------

--
-- Structure de la table `base`
--

CREATE TABLE IF NOT EXISTS `base` (
`base_index` int(11) NOT NULL,
  `lab_id` tinytext NOT NULL COMMENT 'Sous la forme P1, L4, Mod2, POG23 Commence par categorie_lettres Identifiant donné par l’équipe',
  `categorie` int(11) NOT NULL,
  `serial_number` text NOT NULL,
  `reference` text NOT NULL COMMENT 'Référence du composant chez vendeur ou fabriquant',
  `designation` text NOT NULL,
  `utilisateur` int(11) NOT NULL COMMENT 'Sous la forme adresse mail',
  `localisation` int(11) NOT NULL,
  `date_localisation` date NOT NULL COMMENT 'Date à laquelle la localisation à été renseignée (rempli automatiquement)',
  `tutelle` int(11) NOT NULL,
  `contrat` int(11) NOT NULL,
  `bon_commande` text NOT NULL,
  `num_inventaire` text NOT NULL COMMENT 'Numéro d’inventaire des tutelles',
  `vendeur` int(11) NOT NULL,
  `marque` int(11) NOT NULL,
  `date_achat` date NOT NULL,
  `responsable_achat` int(11) NOT NULL,
  `garantie` date NOT NULL COMMENT 'date de fin de garantie',
  `prix` decimal(11,0) NOT NULL,
  `date_sortie` date NOT NULL COMMENT 'date à laquelle le produit est sortie de base de données',
  `sortie` tinyint(1) NOT NULL COMMENT '0 pour non sortie 1 pour sortie définitive 2 pour sortie temporaire',
  `raison_sortie` int(11) NOT NULL,
  `integration` int(11) NOT NULL COMMENT 'Si le composant est intégré dans un ensemble qui est répertorié'
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `carac`
--

CREATE TABLE IF NOT EXISTS `carac` (
`carac_index` int(11) NOT NULL,
  `carac_valeur` text NOT NULL COMMENT 'Différentes possibilités : λ=1550 λ= 800 1024 1550 (si plusieurs valeurs, séparées par espaces) λ=800-1500 (si intervalle, séparées par -)',
  `carac_id` int(11) NOT NULL,
  `carac_caracteristique_id` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=127 DEFAULT CHARSET=utf8;

--
-- Contenu de la table `carac`
--


-- --------------------------------------------------------

--
-- Structure de la table `caracteristiques`
--

CREATE TABLE IF NOT EXISTS `caracteristiques` (
`carac` int(11) NOT NULL,
  `nom_carac` text NOT NULL,
  `unite_carac` text NOT NULL,
  `symbole_carac` tinytext NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COMMENT='le type de caractéristique (λ, matériau, ω₀,…)';

--
-- Contenu de la table `caracteristiques`
--

INSERT INTO `caracteristiques` (`carac`, `nom_carac`, `unite_carac`, `symbole_carac`) VALUES
(0, '-', '', ''),


-- --------------------------------------------------------

--
-- Structure de la table `categorie`
--

CREATE TABLE IF NOT EXISTS `categorie` (
`categorie_index` int(11) NOT NULL,
  `categorie_lettres` tinytext NOT NULL,
  `categorie_nom` tinytext NOT NULL COMMENT 'Polariseur, Photodiode, Laser, Coupleur,…'
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

--
-- Contenu de la table `categorie`
--

INSERT INTO `categorie` (`categorie_index`, `categorie_lettres`, `categorie_nom`) VALUES
(0, '---', '-'),

-- --------------------------------------------------------

--
-- Structure de la table `compatibilite`
--

CREATE TABLE IF NOT EXISTS `compatibilite` (
`compatib_index` int(11) NOT NULL,
  `compatib_id1` int(11) NOT NULL,
  `compatib_id2` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=89 DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- Structure de la table `contrat`
--

CREATE TABLE IF NOT EXISTS `contrat` (
`contrat_index` int(11) NOT NULL,
  `contrat_nom` text NOT NULL COMMENT 'Nom du contrat CPER, ANR, ASTRID,… sur laquelle le composant a été acheté (Ex : OSMOTUS, PHENIX,…',
  `contrat_type` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

--
-- Contenu de la table `contrat`
--

INSERT INTO `contrat` (`contrat_index`, `contrat_nom`, `contrat_type`) VALUES
(0, '-', 0),


-- --------------------------------------------------------

--
-- Structure de la table `contrat_type`
--

CREATE TABLE IF NOT EXISTS `contrat_type` (
`contrat_type_index` int(11) NOT NULL,
  `contrat_type_cat` text NOT NULL COMMENT 'ANR, ASTRID, CPER,…'
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Contenu de la table `contrat_type`
--

INSERT INTO `contrat_type` (`contrat_type_index`, `contrat_type_cat`) VALUES
(0, '-'),


-- --------------------------------------------------------

--
-- Structure de la table `entretien`
--

CREATE TABLE IF NOT EXISTS `entretien` (
`e_index` int(11) NOT NULL,
  `e_id` int(11) NOT NULL,
  `e_frequence` int(11) NOT NULL,
  `e_lastdate` date NOT NULL,
  `e_designation` text NOT NULL,
  `e_detail` longtext NOT NULL,
  `e_effectuerpar` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

--



-- --------------------------------------------------------

--
-- Structure de la table `historique`
--

CREATE TABLE IF NOT EXISTS `historique` (
`historique_index` int(11) NOT NULL,
  `historique_date` date NOT NULL,
  `historique_texte` text NOT NULL,
  `historique_id` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- Structure de la table `localisation`
--

CREATE TABLE IF NOT EXISTS `localisation` (
`localisation_index` int(11) NOT NULL,
  `localisation_batiment` text NOT NULL,
  `localisation_piece` text NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

--
-- Contenu de la table `localisation`
--

INSERT INTO `localisation` (`localisation_index`, `localisation_batiment`, `localisation_piece`) VALUES
(0, '-', '-'),

-- --------------------------------------------------------

--
-- Structure de la table `marque`
--

CREATE TABLE IF NOT EXISTS `marque` (
`marque_index` int(11) NOT NULL,
  `marque_nom` text NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

--
-- Contenu de la table `marque`
--

INSERT INTO `marque` (`marque_index`, `marque_nom`) VALUES
(0, '-'),

-- --------------------------------------------------------

--
-- Structure de la table `raison_sortie`
--

CREATE TABLE IF NOT EXISTS `raison_sortie` (
`raison_sortie_index` int(11) NOT NULL,
  `raison_sortie_nom` text NOT NULL COMMENT 'raison pour laquelle le produit est sorti de base de données (perte, casse, intégration définitive, réparation en cours…)'
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

--
-- Contenu de la table `raison_sortie`
--

INSERT INTO `raison_sortie` (`raison_sortie_index`, `raison_sortie_nom`) VALUES
(0, '-'),

-- --------------------------------------------------------

--
-- Structure de la table `tags`
--

CREATE TABLE IF NOT EXISTS `tags` (
  `tags_index` int(11) NOT NULL,
  `tags_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `tags`
--


-- --------------------------------------------------------

--
-- Structure de la table `tags_list`
--

CREATE TABLE IF NOT EXISTS `tags_list` (
`tags_list_index` int(11) NOT NULL,
  `tags_list_nom` text NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8;

--
-- Contenu de la table `tags_list`
--

INSERT INTO `tags_list` (`tags_list_index`, `tags_list_nom`) VALUES
(0, '-'),


-- --------------------------------------------------------

--
-- Structure de la table `tutelle`
--

CREATE TABLE IF NOT EXISTS `tutelle` (
`tutelle_index` int(11) NOT NULL,
  `tutelle_nom` text NOT NULL COMMENT 'CNRS, Université Rennes 1,…'
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Contenu de la table `tutelle`
--

INSERT INTO `tutelle` (`tutelle_index`, `tutelle_nom`) VALUES
(0, '-'),


-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE IF NOT EXISTS `utilisateur` (
`utilisateur_index` int(11) NOT NULL,
  `utilisateur_nom` tinytext NOT NULL,
  `utilisateur_prenom` tinytext NOT NULL,
  `utilisateur_mail` tinytext NOT NULL,
  `utilisateur_phone` tinytext NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

--
-- Contenu de la table `utilisateur`
--

INSERT INTO `utilisateur` (`utilisateur_index`, `utilisateur_nom`, `utilisateur_prenom`, `utilisateur_mail`, `utilisateur_phone`) VALUES
(0, '-', '', '', ''),

-- --------------------------------------------------------

--
-- Structure de la table `vendeur`
--

CREATE TABLE IF NOT EXISTS `vendeur` (
`vendeur_index` int(11) NOT NULL,
  `vendeur_nom` text NOT NULL,
  `vendeur_web` text NOT NULL COMMENT 'adresse du site web',
  `vendeur_remarques` text NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

--
-- Contenu de la table `vendeur`
--

INSERT INTO `vendeur` (`vendeur_index`, `vendeur_nom`, `vendeur_web`, `vendeur_remarques`) VALUES
(0, '-', '', ''),

--
-- Index pour les tables exportées
--

--
-- Index pour la table `base`
--
ALTER TABLE `base`
 ADD PRIMARY KEY (`base_index`), ADD UNIQUE KEY `index` (`base_index`);

--
-- Index pour la table `carac`
--
ALTER TABLE `carac`
 ADD PRIMARY KEY (`carac_index`), ADD UNIQUE KEY `carac_index` (`carac_index`), ADD KEY `carac_index_2` (`carac_index`);

--
-- Index pour la table `caracteristiques`
--
ALTER TABLE `caracteristiques`
 ADD PRIMARY KEY (`carac`), ADD UNIQUE KEY `carac` (`carac`);

--
-- Index pour la table `categorie`
--
ALTER TABLE `categorie`
 ADD PRIMARY KEY (`categorie_index`);

--
-- Index pour la table `compatibilite`
--
ALTER TABLE `compatibilite`
 ADD UNIQUE KEY `compatib_index` (`compatib_index`);

--
-- Index pour la table `contrat`
--
ALTER TABLE `contrat`
 ADD PRIMARY KEY (`contrat_index`), ADD UNIQUE KEY `contrat_index` (`contrat_index`);

--
-- Index pour la table `contrat_type`
--
ALTER TABLE `contrat_type`
 ADD PRIMARY KEY (`contrat_type_index`), ADD UNIQUE KEY `contrat_type_index` (`contrat_type_index`);

--
-- Index pour la table `entretien`
--
ALTER TABLE `entretien`
 ADD PRIMARY KEY (`e_index`);

--
-- Index pour la table `historique`
--
ALTER TABLE `historique`
 ADD PRIMARY KEY (`historique_index`), ADD UNIQUE KEY `historique_index` (`historique_index`);

--
-- Index pour la table `localisation`
--
ALTER TABLE `localisation`
 ADD PRIMARY KEY (`localisation_index`), ADD UNIQUE KEY `localisation_index` (`localisation_index`);

--
-- Index pour la table `marque`
--
ALTER TABLE `marque`
 ADD PRIMARY KEY (`marque_index`), ADD UNIQUE KEY `marque_index` (`marque_index`);

--
-- Index pour la table `raison_sortie`
--
ALTER TABLE `raison_sortie`
 ADD PRIMARY KEY (`raison_sortie_index`), ADD UNIQUE KEY `raison_sortie_index` (`raison_sortie_index`);

--
-- Index pour la table `tags_list`
--
ALTER TABLE `tags_list`
 ADD PRIMARY KEY (`tags_list_index`), ADD UNIQUE KEY `tags_index` (`tags_list_index`);

--
-- Index pour la table `tutelle`
--
ALTER TABLE `tutelle`
 ADD PRIMARY KEY (`tutelle_index`), ADD UNIQUE KEY `tutelle_index` (`tutelle_index`);

--
-- Index pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
 ADD PRIMARY KEY (`utilisateur_index`);

--
-- Index pour la table `vendeur`
--
ALTER TABLE `vendeur`
 ADD PRIMARY KEY (`vendeur_index`), ADD UNIQUE KEY `vendeur_index` (`vendeur_index`), ADD UNIQUE KEY `vendeur_index_3` (`vendeur_index`), ADD KEY `vendeur_index_2` (`vendeur_index`), ADD KEY `vendeur_index_4` (`vendeur_index`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `base`
--
ALTER TABLE `base`
MODIFY `base_index` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT pour la table `carac`
--
ALTER TABLE `carac`
MODIFY `carac_index` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=127;
--
-- AUTO_INCREMENT pour la table `caracteristiques`
--
ALTER TABLE `caracteristiques`
MODIFY `carac` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT pour la table `categorie`
--
ALTER TABLE `categorie`
MODIFY `categorie_index` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT pour la table `compatibilite`
--
ALTER TABLE `compatibilite`
MODIFY `compatib_index` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=89;
--
-- AUTO_INCREMENT pour la table `contrat`
--
ALTER TABLE `contrat`
MODIFY `contrat_index` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT pour la table `contrat_type`
--
ALTER TABLE `contrat_type`
MODIFY `contrat_type_index` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT pour la table `entretien`
--
ALTER TABLE `entretien`
MODIFY `e_index` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT pour la table `historique`
--
ALTER TABLE `historique`
MODIFY `historique_index` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=42;
--
-- AUTO_INCREMENT pour la table `localisation`
--
ALTER TABLE `localisation`
MODIFY `localisation_index` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT pour la table `marque`
--
ALTER TABLE `marque`
MODIFY `marque_index` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT pour la table `raison_sortie`
--
ALTER TABLE `raison_sortie`
MODIFY `raison_sortie_index` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT pour la table `tags_list`
--
ALTER TABLE `tags_list`
MODIFY `tags_list_index` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=40;
--
-- AUTO_INCREMENT pour la table `tutelle`
--
ALTER TABLE `tutelle`
MODIFY `tutelle_index` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
MODIFY `utilisateur_index` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT pour la table `vendeur`
--
ALTER TABLE `vendeur`
MODIFY `vendeur_index` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=17;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
