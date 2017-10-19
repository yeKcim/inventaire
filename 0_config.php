<?php

$connecthost="localhost";
$connectlogin="<YOUR_LOGIN>";
$connectpasse="<YOUR_PASSWORD>";

$database="<YOUR_DATABASE>";

$extensions= array("pdf","jpg","png","svg","txt", "gif", "dxf", "sldprt", "step", "zip", "7z");    // liste des extensions autorisées pour l’envoi de fichiers
$racine="/var/www/";                            // dossier où est installé « Inventaire »
$dossierdesfichiers="".$racine."files/";        // dossier où sont placés les fichiers (doit être 775)
$trash="".$dossierdesfichiers."trash/";         // dossier corbeille des fichiers




?>
