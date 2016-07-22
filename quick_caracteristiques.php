<?php
$i= isset($_GET["i"]) ? htmlentities($_GET["i"]) : "" ; // GET i
$titre="Caractéristiques de #$i";
require_once("./0_connect.php");
require_once("./0_tables_sql_commun.php");
require_once("./0_head.php");
?>

<body>
<?php
require_once("./0_fonctions.php");
$error="";
$success="";

require_once("./0_array_info_de_i.php");

$write=true;

echo "<div id=\"container\">";
    require_once("./blocs/caracteristiques.php");
echo "</div>";

?>

</body>
</html>
