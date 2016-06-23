<?php

$connecthost="localhost";
$connectlogin="www-user";
$connectpasse="Mais-Ou#Est+Donc_0r!Ni42Car?";
$connectbase="optique";
@mysql_connect($connecthost,$connectlogin,$connectpasse) or die ("Impossible de se connecter");
@mysql_select_db($connectbase) or die ("Impossible de se connecter à la base");

?>
