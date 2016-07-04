<?php

require_once("./config.php");

@mysql_connect($connecthost,$connectlogin,$connectpasse) or die ("Impossible de se connecter");
@mysql_select_db($database) or die ("Impossible de se connecter à la base");

?>
