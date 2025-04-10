<?php
$i= isset($_GET["i"]) ? htmlentities($_GET["i"]) : "" ; // GET i
$titre="Informations détaillées #$i";
require_once("./0_fonctions.php");
require_once("./0_connect.php");
if ($database=="") require_once("./0_baseselector.php");
require_once("./0_connect_db.php");
require_once("./0_tables_sql_commun.php");
require_once("./0_head.php");
require_once("./0_settings.php");
echo "<!-- ########### BODY ########### --><body>";

$error="";
$success="";

require_once("./0_array_info_de_i.php");

/*
██████╗ ██╗███████╗██████╗ ██╗      █████╗ ██╗   ██╗    ██████╗ ██╗      ██████╗  ██████╗███████╗
██╔══██╗██║██╔════╝██╔══██╗██║     ██╔══██╗╚██╗ ██╔╝    ██╔══██╗██║     ██╔═══██╗██╔════╝██╔════╝
██║  ██║██║███████╗██████╔╝██║     ███████║ ╚████╔╝     ██████╔╝██║     ██║   ██║██║     ███████╗
██║  ██║██║╚════██║██╔═══╝ ██║     ██╔══██║  ╚██╔╝      ██╔══██╗██║     ██║   ██║██║     ╚════██║
██████╔╝██║███████║██║     ███████╗██║  ██║   ██║       ██████╔╝███████╗╚██████╔╝╚██████╗███████║
╚═════╝ ╚═╝╚══════╝╚═╝     ╚══════╝╚═╝  ╚═╝   ╚═╝       ╚═════╝ ╚══════╝ ╚═════╝  ╚═════╝╚══════╝
*/

$write=true;

echo "<p>Informations #$i :</p>";

echo "<div id=\"container\">";
foreach ($SETTINGS_modules as $m) {
	require_once("./modules/{$m}/bloc.php");
}

echo "</div>";



/*
    require_once("./modules/administratif/bloc.php");
    require_once("./modules/technique/bloc.php");
    require_once("./modules/caracteristiques/bloc.php");
    require_once("./modules/documents/bloc.php");
    require_once("./modules/entretien/bloc.php");
    require_once("./modules/utilisation/bloc.php");
    require_once("./modules/journal/bloc.php");

*/

?>


</body>
</html>

<?php
$dbh = null;
?>
