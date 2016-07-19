<?php require_once("./head.html"); ?>

<body>

<?php
require_once("./connect.php");
require_once("./tables_sql_commun.php");
$i= isset($_GET["i"]) ? htmlentities($_GET["i"]) : "" ; // GET i
require_once("./fonctions.php");



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
        "tutelle"=>$l[9],                     "contrat"=>$l[10],                "num_inventaire"=>utf8_encode($l[11]),
        "vendeur"=>$l[12],                    "marque"=>$l[13],                 "date_achat"=>$l[14],
        "responsable_achat"=>$l[15],          "garantie"=>$l[16],               "prix"=>$l[17],
        "date_sortie"=>$l[18],                "sortie"=>$l[19],                 "raison_sortie"=>$l[20],
        "integration"=>$l[21]
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

echo "<p>Informations #$i :</p>";

echo "<div id=\"container\">";
    require_once("./blocs/administratif.php");
    require_once("./blocs/technique.php");
    require_once("./blocs/caracteristiques.php");
    require_once("./blocs/documents.php");
    require_once("./blocs/utilisation.php");
    require_once("./blocs/journal.php");
    require_once("./blocs/entretien.php");
echo "</div>";

?>


</body>
</html>
