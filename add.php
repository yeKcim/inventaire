<?php
$titre="Ajouter une entrée";
require_once("./connect.php");
require_once("./tables_sql_commun.php");


// on fait le my sql ici s’il n’y a pas eu de problème ainsi on se redirige vers la page de modification dans le head

require_once("./head.php");
?>

<body>

<?php


//Calcule du nouveau $i
$inew = mysql_query ("SELECT base_index FROM base ORDER BY base_index DESC LIMIT 1 ;");
while ($l = mysql_fetch_row($inew)) $i=$l[0]+1;

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
if ( isset($_POST["add_valid"]) ) {


}
else { // Initialisation de toutes les variable
    $data=array(
        "base_index"=>$i,              "lab_id"=>"",                "categorie"=>"0",
        "serial_number"=>"",           "reference"=>"",             "designation"=>"",
        "utilisateur"=>"0",            "localisation"=>"",          "date_localisation"=>"",
        "tutelle"=>"0",                "contrat"=>"0",              "num_inventaire"=>"",
        "vendeur"=>"0",                "marque"=>"0",               "date_achat"=>"",
        "responsable_achat"=>"0",      "garantie"=>"",              "prix"=>"",
        "date_sortie"=>"",             "sortie"=>"",                "raison_sortie"=>"",
        "integration"=>""   );
}




/*
██████╗ ██╗███████╗██████╗ ██╗      █████╗ ██╗   ██╗    ██████╗ ██╗      ██████╗  ██████╗███████╗
██╔══██╗██║██╔════╝██╔══██╗██║     ██╔══██╗╚██╗ ██╔╝    ██╔══██╗██║     ██╔═══██╗██╔════╝██╔════╝
██║  ██║██║███████╗██████╔╝██║     ███████║ ╚████╔╝     ██████╔╝██║     ██║   ██║██║     ███████╗
██║  ██║██║╚════██║██╔═══╝ ██║     ██╔══██║  ╚██╔╝      ██╔══██╗██║     ██║   ██║██║     ╚════██║
██████╔╝██║███████║██║     ███████╗██║  ██║   ██║       ██████╔╝███████╗╚██████╔╝╚██████╗███████║
╚═════╝ ╚═╝╚══════╝╚═╝     ╚══════╝╚═╝  ╚═╝   ╚═╝       ╚═════╝ ╚══════╝ ╚═════╝  ╚═════╝╚══════╝
*/

$write=false;

echo "<p>Ajouter une entrée (#$i) :</p>";

echo "<form method=\"post\" action=\"?a=$i\">";

echo "<div id=\"container\">";

    require_once("./blocs/administratif.php");
    require_once("./blocs/technique.php");
    require_once("./blocs/utilisation.php");

echo "</div>";

/*  ╔═╗╦ ╦╔╗ ╔╦╗╦╔╦╗
    ╚═╗║ ║╠╩╗║║║║ ║ 
    ╚═╝╚═╝╚═╝╩ ╩╩ ╩     */
    echo "<p style=\"text-align:center;\"><input name='add_valid' value='Ajouter' type='submit'></p>"; // TODO Ajouter un bouton réinitialiser
    


?>


</body>
</html>
