<?php
$i= isset($_GET["i"]) ? htmlentities($_GET["i"]) : "" ; // GET i
$titre="Informations détaillées #$i";
require_once("./connect.php");
require_once("./tables_sql_commun.php");
require_once("./head.php");
?>
<body>

<?php

require_once("./fonctions.php");
$error="";
$success="";


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
$query_table = mysql_query ("SELECT * FROM base WHERE base_index=$i ;");
while ($l = mysql_fetch_row($query_table)) {
    $data=array(
        "base_index"=>$l[0],                  "lab_id"=>$l[1],                  "categorie"=>$l[2],
        "serial_number"=>utf8_encode($l[3]),  "reference"=>utf8_encode($l[4]),  "designation"=>$l[5],
        "utilisateur"=>$l[6],                 "localisation"=>$l[7],            "date_localisation"=>$l[8],
        "tutelle"=>$l[9],                     "contrat"=>$l[10],                "bon_commande"=>utf8_encode($l[11]),
        "num_inventaire"=>utf8_encode($l[12]),
        "vendeur"=>$l[13],                    "marque"=>$l[14],                 "date_achat"=>$l[15],
        "responsable_achat"=>$l[16],          "garantie"=>$l[17],               "prix"=>$l[18],
        "date_sortie"=>$l[19],                "sortie"=>$l[20],                 "raison_sortie"=>$l[21],
        "integration"=>$l[22]
    );
}

/*
██████╗ ██╗███████╗██████╗ ██╗      █████╗ ██╗   ██╗    ██████╗ ██╗      ██████╗  ██████╗███████╗
██╔══██╗██║██╔════╝██╔══██╗██║     ██╔══██╗╚██╗ ██╔╝    ██╔══██╗██║     ██╔═══██╗██╔════╝██╔════╝
██║  ██║██║███████╗██████╔╝██║     ███████║ ╚████╔╝     ██████╔╝██║     ██║   ██║██║     ███████╗
██║  ██║██║╚════██║██╔═══╝ ██║     ██╔══██║  ╚██╔╝      ██╔══██╗██║     ██║   ██║██║     ╚════██║
██████╔╝██║███████║██║     ███████╗██║  ██║   ██║       ██████╔╝███████╗╚██████╔╝╚██████╗███████║
╚═════╝ ╚═╝╚══════╝╚═╝     ╚══════╝╚═╝  ╚═╝   ╚═╝       ╚═════╝ ╚══════╝ ╚═════╝  ╚═════╝╚══════╝
*/

$write=true;

echo "<div id=\"container\">";
    require_once("./blocs/entretien.php");
echo "</div>";

?>


</body>
</html>
