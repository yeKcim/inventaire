<?php

require_once("./0_config.php");

try {
    // Connexion à la base de données
    $dblist = new PDO("mysql:host=$connecthost", $connectlogin, $connectpasse);
    
    // Préparer et exécuter la requête pour lister les bases de données
    $sth = $dblist->prepare('SHOW DATABASES');
    $sth->execute();

    // Récupérer toutes les bases de données
    $dbs = $sth->fetchAll(PDO::FETCH_ASSOC);

    // Fermeture du curseur
    $sth->closeCursor();
} catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}

// Récupération de la base de données sélectionnée
$database = isset($_POST["BASE"]) ? htmlentities($_POST["BASE"]) : (isset($_GET["BASE"]) ? htmlentities($_GET["BASE"]) : "");
?>

