<?php require_once("./head.html"); ?>

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
