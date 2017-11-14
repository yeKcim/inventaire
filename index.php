<?php
$titre="Inventaire";
require_once("./0_head.php");
?>

<!-- ########### BODY ########### -->
<body>

<?php

require_once("./0_connect.php");
require_once("./0_baseselector.php");
require_once("./0_connect_db.php");

require_once("./0_fonctions.php");

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

$ORDER= ($ORDER=="") ? "ORDER BY categorie ASC, length(lab_id), lab_id ASC" : "$ORDER, base_index ASC";

//echo "<p style=\"text-align:right;\">";
echo "<span id=\"linkbox\" onclick=\"TINY.box.show({iframe:'add.php?BASE=$database',width:900,height:700,closejs:function(){location.reload()}})\"><input class=\"big_button\" value=\"Ajouter une nouvelle entrée\" type=\"submit\" style=\"float: right;\"/></span>";
//echo "</p>";

require_once("./0_form.php");
require_once("./0_listing.php");

?>

<script type="text/javascript">
    function openJS(){alert('loaded')}
    function closeJS(){alert('closed')}
</script>

</body>

</html>

<?php
$dbh = null;
?>
