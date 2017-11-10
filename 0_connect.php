<?php

require_once("./0_config.php");

try {
  $dblist = new PDO( "mysql:host=$connecthost", $connectlogin, $connectpasse );
  $dbs = $dblist->query( 'SHOW DATABASES' );
}
catch (Exception $e) {
        die('Erreur : ' . $e->getMessage());
}

if (isset($_POST["BASE"])) $database = htmlentities($_POST["BASE"]) ; else $database= isset($_GET["BASE"]) ? htmlentities($_GET["BASE"]) : "" ;
?>
