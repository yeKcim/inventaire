<?php
// Vérifie si 'i' est présent dans $_GET, sinon définit une valeur par défaut (ici 0)
$i = isset($_GET["i"]) ? (int)$_GET["i"] : 0;

// Prépare la requête SQL avec un paramètre nommé pour éviter les injections
$stmt = $dbh->prepare("SELECT * FROM base WHERE base_index = :index");
$stmt->bindParam(':index', $i, PDO::PARAM_INT);

// Exécute la requête
$stmt->execute();

// Récupère tous les résultats dans un tableau associatif
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);
$stmt->closeCursor();
?>
