<?php

require_once("0_config.php");

$dbname = rawurlencode("$prefix$database");

try {
	$dbh = new PDO(
		"mysql:host=$connecthost;dbname=$dbname;charset=utf8mb4",
		$connectlogin,
		$connectpasse,
		[
		    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
		    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
		    PDO::ATTR_PERSISTENT => true
		]
	);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage()); // Gère l'erreur proprement
}


?>





