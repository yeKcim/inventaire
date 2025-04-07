<?php

// Récupération des catégories
$sth = $dbh->prepare("SELECT * FROM categorie WHERE categorie_index != 0 ORDER BY categorie_nom ASC;");
$sth->execute();
$categories = ($sth->rowCount() > 0) ? $sth->fetchAll(PDO::FETCH_ASSOC) : FALSE;
$sth->closeCursor();

// Récupération des types de contrats
$sth = $dbh->prepare("SELECT * FROM contrat_type WHERE contrat_type_index != 0 ORDER BY contrat_type.contrat_type_cat ASC;");
$sth->execute();
$types_contrats = ($sth->rowCount() > 0) ? $sth->fetchAll(PDO::FETCH_ASSOC) : FALSE;
$sth->closeCursor();

// Récupération des utilisateurs
$sth = $dbh->prepare("SELECT DISTINCT utilisateur_index, utilisateur_nom, utilisateur_prenom, utilisateur_mail, utilisateur_phone 
                      FROM utilisateur WHERE utilisateur_index != 0 ORDER BY utilisateur_nom ASC;");
$sth->execute();
$utilisateurs = ($sth->rowCount() > 0) ? $sth->fetchAll(PDO::FETCH_ASSOC) : FALSE;
$sth->closeCursor();

// Récupération des marques
$sth = $dbh->prepare("SELECT * FROM marque WHERE marque_index != 0 ORDER BY marque_nom ASC;");
$sth->execute();
$marques = ($sth->rowCount() > 0) ? $sth->fetchAll(PDO::FETCH_ASSOC) : FALSE;
$sth->closeCursor();

?>

