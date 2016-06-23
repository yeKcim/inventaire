<!-- ########### HEAD ########### -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="fr" dir="ltr" xml:lang="fr" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="robots" content="noindex,nofollow" />
    <title>Base de données OPTIQUE</title>
    <link rel="stylesheet" type="text/css" href="./style.css">
    
    <link rel="stylesheet" href="tinybox/tinybox.css" />
    <script type="text/javascript" src="tinybox/tinybox.js"></script>

</head>

<!-- ########### BODY ########### -->
<body>

<?php
require_once("./connect.php");

/* ########### GET ########### */
$arr = array("CAT","TYC","CON","SEA","RES","UTL","IOT","ORDER");
// récupération
foreach ($arr as &$value) {
    $$value= isset($_GET[$value]) ? htmlentities($_GET[$value]) : "" ;
}

// adaptation pour sea
$default_SEA_textbox="Mot ou portion de mot";
$SEA_textbox= ($SEA=="") ? $default_SEA_textbox : $SEA ;
$SEA= ($SEA==$default_SEA_textbox) ? "" : $SEA ;

// allget
$ALL_GET="";
$arr_in_get = array("CAT","TYC","CON","SEA","RES","UTL","IOT");
foreach ($arr_in_get as &$value) { $ALL_GET="$ALL_GET&$value=".$$value.""; }
$ALL_GET=str_replace(" ", "%20", $ALL_GET);

$ORDER= ($ORDER=="") ? "ORDER BY categorie ASC, lab_id ASC" : "$ORDER, base_index ASC";

/* ########### FONCTIONS ########### */
require_once("./fonctions.php");


echo "<h1><a href=\"?\">Base de données OPTIQUE</a></h1>";
require_once("./form.php");
require_once("./listing.php");


?>

<script type="text/javascript">
    function openJS(){alert('loaded')}
    function closeJS(){alert('closed')}
</script>

</body>

</html>
