<?php
$i= isset($_GET["i"]) ? htmlentities($_GET["i"]) : "" ; // GET i
$titre="Informations détaillées #$i";
require_once("./0_connect.php");
if ($database=="") require_once("./0_baseselector.php");
require_once("./0_connect_db.php");
require_once("./0_tables_sql_commun.php");
require_once("./0_head.php");
?>
<body>

<?php

require_once("./0_fonctions.php");
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
    require_once("./blocs/administratif.php");
    require_once("./blocs/technique.php");
    require_once("./blocs/caracteristiques.php");
    require_once("./blocs/documents.php");
    require_once("./blocs/entretien.php");
    require_once("./blocs/utilisation.php");
    require_once("./blocs/journal.php");

echo "</div>";

?>


</body>
</html>

<?php
$dbh = null;
?>
