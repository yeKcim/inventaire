<?php

$arr = array("i", "quick_page", "quick_name");
foreach ($arr as &$value) {
    $$value= isset($_GET[$value]) ? $_GET[$value] : "" ;
}

$titre="$quick_name #$i";
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
    echo "".$data["lab_id"]." #".$i."<br/>";
    require_once("./blocs/$quick_page.php");
echo "</div>";

?>

</body>
</html>
