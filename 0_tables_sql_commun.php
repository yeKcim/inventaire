<?php

// $categories
$sth = $dbh->query("SELECT * FROM categorie WHERE categorie_index!=0 ORDER BY categorie_nom ASC ;");
$categories = ($sth) ? $sth->fetchAll(PDO::FETCH_ASSOC) : FALSE ;
if ($sth) $sth->closeCursor();

// $types_contrats
$sth = $dbh->query("SELECT * FROM contrat_type WHERE contrat_type_index!=0 ORDER BY contrat_type.contrat_type_cat ASC ;");
$types_contrats = ($sth) ? $sth->fetchAll(PDO::FETCH_ASSOC) : FALSE ;
if ($sth) $sth->closeCursor();

// utilisateurs
$sth = $dbh->query("SELECT DISTINCT(utilisateur_index) utilisateur_index, utilisateur_nom, utilisateur_prenom, utilisateur_mail, utilisateur_phone FROM utilisateur WHERE utilisateur_index!=0 ORDER BY utilisateur_nom ASC ;");
$utilisateurs = ($sth) ? $sth->fetchAll(PDO::FETCH_ASSOC) : FALSE ;
if ($sth) $sth->closeCursor();

// marque
$sth = $dbh->query("SELECT * FROM marque WHERE marque_index!=0 ORDER BY marque_nom ASC ;");
$marques = ($sth) ? $sth->fetchAll(PDO::FETCH_ASSOC) : FALSE ;
if ($sth) $sth->closeCursor();

?>
