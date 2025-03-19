<?php

require_once("0_config.php");

$dbname = "$prefix$database";

try {
    $dbh = new PDO(
        "mysql:host=$connecthost;dbname=$dbname;charset=utf8mb4",
        $connectlogin,
        $connectpasse,
        array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,  // Active la gestion des erreurs avec exceptions
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Mode de récupération par défaut (tableau associatif)
            PDO::ATTR_PERSISTENT => true // Connexion persistante
        )
    );
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage()); // Gère l'erreur proprement
}


?>
