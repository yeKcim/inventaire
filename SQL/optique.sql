-- phpMyAdmin SQL Dump
-- version 4.2.12deb2+deb8u1
-- http://www.phpmyadmin.net
--
-- Client :  localhost
-- Généré le :  Jeu 23 Juin 2016 à 15:55
-- Version du serveur :  5.5.49-0+deb8u1
-- Version de PHP :  5.6.22-0+deb8u1

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
-- Structure de la table `base_optique`
--

CREATE TABLE IF NOT EXISTS `base_optique` (
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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Contenu de la table `base_optique`
--

INSERT INTO `base_optique` (`base_index`, `lab_id`, `categorie`, `serial_number`, `reference`, `designation`, `utilisateur`, `localisation`, `date_localisation`, `tutelle`, `contrat`, `num_inventaire`, `vendeur`, `marque`, `date_achat`, `responsable_achat`, `garantie`, `prix`, `date_sortie`, `sortie`, `raison_sortie`, `integration`) VALUES
(1, 'Lun1', 1, '', 'LG5', 'Laser Safety Glasses, Pink Lenses, 61% Visible Light Transmission, Universal Style ', 2, 12, '2016-06-07', 2, 1, '', 1, 5, '2016-05-26', 1, '0000-00-00', 145, '0000-00-00', 0, 0, 0),
(2, 'Lun2', 1, '', 'LG12', 'Laser Safety Glasses, Amber Lenses, 11% Visible Light Transmission, Universal Style', 0, 12, '2016-06-07', 0, 0, '', 1, 5, '2016-05-26', 1, '0000-00-00', 262, '0000-00-00', 0, 0, 0),
(3, 'Lun3', 1, '', 'LG5B', 'Laser Safety Glasses, Pink Lenses, 61% Visible Light Transmission, Sport Style ', 2, 12, '2016-06-07', 2, 1, '', 1, 5, '2016-05-26', 1, '0000-00-00', 146, '0000-00-00', 2, 5, 0),
(4, 'Lun4', 1, '', 'LG5', 'fake temporaire', 2, 12, '2016-06-07', 1, 3, '', 1, 5, '2016-05-26', 1, '0000-00-00', 145, '0000-00-00', 0, 0, 0);

-- --------------------------------------------------------

--
-- Structure de la table `carac`
--

CREATE TABLE IF NOT EXISTS `carac` (
`carac_index` int(11) NOT NULL,
  `carac_valeur` text NOT NULL COMMENT 'Différentes possibilités : λ=1550 λ= 800 1024 1550 (si plusieurs valeurs, séparées par espaces) λ=800-1500 (si intervalle, séparées par -)',
  `carac_id` int(11) NOT NULL,
  `carac_caracteristique_id` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

--
-- Contenu de la table `carac`
--

INSERT INTO `carac` (`carac_index`, `carac_valeur`, `carac_id`, `carac_caracteristique_id`) VALUES
(1, '61', 1, 3),
(2, '61', 3, 3),
(3, '11', 2, 3),
(4, '1', 1, 4),
(5, '1', 2, 4),
(6, '0', 3, 4),
(7, '1550', 4, 1);

-- --------------------------------------------------------

--
-- Structure de la table `caracteristiques`
--

CREATE TABLE IF NOT EXISTS `caracteristiques` (
`carac` int(11) NOT NULL,
  `nom_carac` text NOT NULL,
  `unite_carac` text NOT NULL,
  `symbole_carac` tinytext NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='le type de caractéristique (λ, matériau, ω₀,…)';

--
-- Contenu de la table `caracteristiques`
--

INSERT INTO `caracteristiques` (`carac`, `nom_carac`, `unite_carac`, `symbole_carac`) VALUES
(0, 'Aucune caractéristique', '', ''),
(1, 'Longueur d''onde', 'nm', '&lambda;'),
(2, 'Diamètre', 'mm', '&empty;'),
(3, 'Transmission Visible', '%', 'Tvisible'),
(4, 'Surlunettes', 'bool', 'SurLun');

-- --------------------------------------------------------

--
-- Structure de la table `categorie`
--

CREATE TABLE IF NOT EXISTS `categorie` (
`categorie_index` int(11) NOT NULL,
  `categorie_lettres` tinytext NOT NULL,
  `categorie_nom` tinytext NOT NULL COMMENT 'Polariseur, Photodiode, Laser, Coupleur,…'
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Contenu de la table `categorie`
--

INSERT INTO `categorie` (`categorie_index`, `categorie_lettres`, `categorie_nom`) VALUES
(0, '---', 'Aucune catégorie définie'),
(1, 'Lun', 'Lunettes'),
(2, 'Las', 'Lasers');

-- --------------------------------------------------------

--
-- Structure de la table `compatibilite`
--

CREATE TABLE IF NOT EXISTS `compatibilite` (
`compatib_index` int(11) NOT NULL,
  `compatib_id1` int(11) NOT NULL,
  `compatib_id2` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Contenu de la table `compatibilite`
--

INSERT INTO `compatibilite` (`compatib_index`, `compatib_id1`, `compatib_id2`) VALUES
(1, 1, 4),
(2, 4, 2);

-- --------------------------------------------------------

--
-- Structure de la table `contrat`
--

CREATE TABLE IF NOT EXISTS `contrat` (
`contrat_index` int(11) NOT NULL,
  `contrat_nom` text NOT NULL COMMENT 'Nom du contrat CPER, ANR, ASTRID,… sur laquelle le composant a été acheté (Ex : OSMOTUS, PHENIX,…',
  `contrat_type` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Contenu de la table `contrat`
--

INSERT INTO `contrat` (`contrat_index`, `contrat_nom`, `contrat_type`) VALUES
(0, 'Aucun contrat', 0),
(1, 'PHENIX', 1),
(2, 'OSMOTUS', 1),
(3, 'HIPPOMOS', 3);

-- --------------------------------------------------------

--
-- Structure de la table `contrat_type`
--

CREATE TABLE IF NOT EXISTS `contrat_type` (
`contrat_type_index` int(11) NOT NULL,
  `contrat_type_cat` text NOT NULL COMMENT 'ANR, ASTRID, CPER,…'
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Contenu de la table `contrat_type`
--

INSERT INTO `contrat_type` (`contrat_type_index`, `contrat_type_cat`) VALUES
(0, 'Aucun type de contrat'),
(1, 'ANR'),
(2, 'ASTRID'),
(3, 'EDA');

-- --------------------------------------------------------

--
-- Structure de la table `historique`
--

CREATE TABLE IF NOT EXISTS `historique` (
`historique_index` int(11) NOT NULL,
  `historique_date` date NOT NULL,
  `historique_texte` text NOT NULL,
  `historique_id` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8;

--
-- Contenu de la table `historique`
--

INSERT INTO `historique` (`historique_index`, `historique_date`, `historique_texte`, `historique_id`) VALUES
(1, '2016-06-17', 'Nettoyage de la fenêtre', 4),
(2, '2016-06-08', 'Envoyé en réparation par Ludovic pour cause de panne', 4),
(3, '2016-06-20', 'Ceci est un texte long pour test. Ce n’est pas très facile de faire une base de données pour tout gérer. Par contre c’est enrichissant et intéressant.', 4),
(21, '2016-06-22', 'Ceci est un test', 1),
(22, '2016-06-22', 'C&rsquo;est fonctionnel', 1),
(24, '2016-06-22', 'Presque bien ; pas parfaitement', 1),
(30, '2016-06-23', 'test lambda : &lambda;\r\nCela fonctionne ?', 4),
(35, '2016-06-23', '&empty; test', 4),
(40, '2016-06-23', 'test &theta;&lambda;&Lambda;&alpha;&Alpha;&omega;â‚€&beta; COâ‚‚ E=mc&sup2;', 2);

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
(0, 'Aucun bâtiment', 'Aucune pièce'),
(1, '11B', '900'),
(2, '11B', '907'),
(3, '11B', '913'),
(4, '11B', '055'),
(5, '11B', '053'),
(6, '11B', '052'),
(7, '11B', '003'),
(8, '11B', '021'),
(9, '11B', '022'),
(10, '11B', '029'),
(11, '11B', '028'),
(12, '11B', '034'),
(13, '11B', '030'),
(14, '11B', '033'),
(15, '11B', '041'),
(16, '11E', '914');

-- --------------------------------------------------------

--
-- Structure de la table `marque`
--

CREATE TABLE IF NOT EXISTS `marque` (
`marque_index` int(11) NOT NULL,
  `marque_nom` text NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Contenu de la table `marque`
--

INSERT INTO `marque` (`marque_index`, `marque_nom`) VALUES
(0, 'Aucune marque'),
(1, 'Coherent'),
(2, 'Spectra-Physics'),
(3, 'Melles Griot'),
(4, 'II-VI'),
(5, 'Thorlabs');

-- --------------------------------------------------------

--
-- Structure de la table `raison_sortie`
--

CREATE TABLE IF NOT EXISTS `raison_sortie` (
`raison_sortie_index` int(11) NOT NULL,
  `raison_sortie_nom` text NOT NULL COMMENT 'raison pour laquelle le produit est sorti de base de données (perte, casse, intégration définitive, réparation en cours…)'
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

--
-- Contenu de la table `raison_sortie`
--

INSERT INTO `raison_sortie` (`raison_sortie_index`, `raison_sortie_nom`) VALUES
(0, 'Aucune raison'),
(1, 'Casse'),
(2, 'Perte'),
(5, 'Réparation en cours'),
(6, 'Intégration');

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

INSERT INTO `tags` (`tags_index`, `tags_id`) VALUES
(1, 1),
(1, 2),
(1, 3);

-- --------------------------------------------------------

--
-- Structure de la table `tags_list`
--

CREATE TABLE IF NOT EXISTS `tags_list` (
`tags_list_index` int(11) NOT NULL,
  `tags_list_nom` text NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Contenu de la table `tags_list`
--

INSERT INTO `tags_list` (`tags_list_index`, `tags_list_nom`) VALUES
(0, 'Aucun tag'),
(1, 'protection'),
(2, 'fabrication labo');

-- --------------------------------------------------------

--
-- Structure de la table `tutelle`
--

CREATE TABLE IF NOT EXISTS `tutelle` (
`tutelle_index` int(11) NOT NULL,
  `tutelle_nom` text NOT NULL COMMENT 'CNRS, Université Rennes 1,…'
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Contenu de la table `tutelle`
--

INSERT INTO `tutelle` (`tutelle_index`, `tutelle_nom`) VALUES
(0, 'Aucune tutelle'),
(1, 'Université de Rennes 1'),
(2, 'CNRS');

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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Contenu de la table `utilisateur`
--

INSERT INTO `utilisateur` (`utilisateur_index`, `utilisateur_nom`, `utilisateur_prenom`, `utilisateur_mail`, `utilisateur_phone`) VALUES
(0, 'Aucun utilisateur', '', '', ''),
(1, 'CARRÉ', 'Anthony', 'anthony.carre@univ-rennes1.fr', '0223235032'),
(2, 'LOAS', 'Goulc''hen', 'goulc-hen.loas@univ-rennes1.fr', '0223236881');

-- --------------------------------------------------------

--
-- Structure de la table `vendeur`
--

CREATE TABLE IF NOT EXISTS `vendeur` (
`vendeur_index` int(11) NOT NULL,
  `vendeur_nom` text NOT NULL,
  `vendeur_web` text NOT NULL COMMENT 'adresse du site web',
  `vendeur_remarques` text NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Contenu de la table `vendeur`
--

INSERT INTO `vendeur` (`vendeur_index`, `vendeur_nom`, `vendeur_web`, `vendeur_remarques`) VALUES
(0, 'Aucun vendeur', '', ''),
(1, 'Thorlabs', 'http://thorlabs.de', ''),
(2, 'Magasin de chimie UR1', 'http://magchimie.univ-rennes1.fr/', ''),
(3, 'Grosseron', 'https://www.grosseron.com', ''),
(5, 'RS', 'http://fr.rs-online.com', '');

--
-- Index pour les tables exportées
--

--
-- Index pour la table `base_optique`
--
ALTER TABLE `base_optique`
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
-- AUTO_INCREMENT pour la table `base_optique`
--
ALTER TABLE `base_optique`
MODIFY `base_index` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT pour la table `carac`
--
ALTER TABLE `carac`
MODIFY `carac_index` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT pour la table `caracteristiques`
--
ALTER TABLE `caracteristiques`
MODIFY `carac` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT pour la table `categorie`
--
ALTER TABLE `categorie`
MODIFY `categorie_index` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT pour la table `compatibilite`
--
ALTER TABLE `compatibilite`
MODIFY `compatib_index` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT pour la table `contrat`
--
ALTER TABLE `contrat`
MODIFY `contrat_index` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT pour la table `contrat_type`
--
ALTER TABLE `contrat_type`
MODIFY `contrat_type_index` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT pour la table `historique`
--
ALTER TABLE `historique`
MODIFY `historique_index` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=41;
--
-- AUTO_INCREMENT pour la table `localisation`
--
ALTER TABLE `localisation`
MODIFY `localisation_index` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT pour la table `marque`
--
ALTER TABLE `marque`
MODIFY `marque_index` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT pour la table `raison_sortie`
--
ALTER TABLE `raison_sortie`
MODIFY `raison_sortie_index` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT pour la table `tags_list`
--
ALTER TABLE `tags_list`
MODIFY `tags_list_index` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT pour la table `tutelle`
--
ALTER TABLE `tutelle`
MODIFY `tutelle_index` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
MODIFY `utilisateur_index` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT pour la table `vendeur`
--
ALTER TABLE `vendeur`
MODIFY `vendeur_index` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
