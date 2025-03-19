<?php
$i= ( !isset($i) ) ? htmlentities($_GET["i"]) : $i ; // GET i

/*
 █████╗ ██████╗ ██████╗  █████╗ ██╗   ██╗
██╔══██╗██╔══██╗██╔══██╗██╔══██╗╚██╗ ██╔╝
███████║██████╔╝██████╔╝███████║ ╚████╔╝
██╔══██║██╔══██╗██╔══██╗██╔══██║  ╚██╔╝
██║  ██║██║  ██║██║  ██║██║  ██║   ██║
╚═╝  ╚═╝╚═╝  ╚═╝╚═╝  ╚═╝╚═╝  ╚═╝   ╚═╝
*/

/* ########### INFORMATIONS COMPOSANT ########### */
// Tous les résultats dans un array
$sth = $dbh->query("SELECT * FROM base WHERE base_index=$i ;");
$data = ($sth) ? $sth->fetchAll(PDO::FETCH_ASSOC) : FALSE ;
if ($sth) $sth->closeCursor();
?>
