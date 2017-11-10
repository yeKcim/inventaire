<?php
$dbname="$prefix$database";
$dbh = new PDO("mysql:host=localhost;dbname=$dbname", $connectlogin, $connectpasse, array(
    PDO::ATTR_PERSISTENT => true
));

#@mysqli_connect($connecthost,$connectlogin,$connectpasse) or die ("Impossible de se connecter");
#@mysqli_select_db($database) or die ("Impossible de se connecter à la base");

?>
