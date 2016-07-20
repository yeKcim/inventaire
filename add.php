<?php
$titre="Ajouter une entrée";
require_once("./connect.php");
require_once("./tables_sql_commun.php");


// on fait le my sql ici s’il n’y a pas eu de problème ainsi on se redirige vers la page de modification dans le head
// en ajoutant un message pour indiquer l’identifiant labo qui a été assigné

require_once("./head.php");
?>

<body>

<?php


//Calcule du nouveau $i
$inew = mysql_query ("SELECT base_index FROM base ORDER BY base_index DESC LIMIT 1 ;");
while ($l = mysql_fetch_row($inew)) $i=$l[0]+1;

require_once("./fonctions.php");

$error="";

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
    $data=array();
    $arr = array("categorie", "plus_categorie_nom", "plus_categorie_abbr", "marque", "plus_marque_nom", "reference", "serial_number", "plus_tags", "designation", "vendeur", "plus_vendeur_nom", "plus_vendeur_web", "plus_vendeur_remarque", "prix", "contrat", "plus_contrat_nom", "contrat_type", "plus_contrat_type_nom", "tutelle", "num_inventaire", "responsable_achat", "plus_responsable_achat_prenom", "plus_responsable_achat_nom", "plus_responsable_achat_mail", "plus_responsable_achat_phone", "date_achat", "garantie", "utilisateur", "plus_utilisateur_prenom", "plus_utilisateur_nom", "plus_utilisateur_mail", "plus_utilisateur_phone", "localisation", "plus_localisation_bat", "plus_localisation_piece", "sortie", "raison_sortie", "plus_raison_sortie_nom", "integration");
    foreach ($arr as &$value) {
        $data["$value"]= isset($_POST[$value]) ? htmlentities($_POST[$value]) : "" ;
    }

    
    if ( ($data["categorie"]=="0")&&($data["designation"]=="") ) $error.="<p class=\"error_message\">Merci de remplir au minimum Administratif→Désignation ou Technique→Catégorie</p>";
    
    
    
    /*
    if ($data["categorie"]=="plus_categorie")
        "plus_categorie_nom"
        "plus_categorie_abbr"
    if ($data["marque"]=="plus_marque")
        "plus_marque_nom"
    "plus_tags"
    tags[] ????
    compatibilite[] ????
    if ($data["vendeur"]=="plus_vendeur")
        "plus_vendeur_nom"
        "plus_vendeur_web"
        "plus_vendeur_remarque"
    if ($data["contrat"]=="plus_contrat")
        "plus_contrat_nom"
        if ($data["contrat_type"]=="plus_contrat_type")
            "plus_contrat_type_nom"
    if ($data["tutelle"]=="plus_tutelle")
    if ($data["responsable_achat"]=="plus_responsable_achat")
        "plus_responsable_achat_prenom"
        "plus_responsable_achat_nom"
        "plus_responsable_achat_mail"
        "plus_responsable_achat_phone"
        */
    $datamysql=array();
    
    $datamysql["reference"]=$data["reference"];
    $datamysql["serial_number"]=$data["serial_number"];
    $datamysql["prix"]=$data["prix"];
    $datamysql["num_inventaire"]=$data["num_inventaire"];
    $datamysql["serial_number"]=$data["serial_number"];
    $datamysql["designation"]=$data["designation"];
    $datamysql["date_achat"]=($data["date_achat"]=="") ? "0000-00-00" : dateformat($data["date_achat"],"en");
    $datamysql["garantie"]=($data["garantie"]=="") ? "0000-00-00" : dateformat($data["garantie"],"en");
    $datamysql["base_index"]=$i;
    $datamysql["base_index"]=$i;
    $datamysql["lab_id"] = new_lab_id($data["categorie"]);
    
    $mysql="INSERT
        INTO base (base_index, lab_id, categorie, serial_number, reference, designation, utilisateur, localisation, date_localisation, tutelle, contrat, num_inventaire, vendeur, marque, date_achat, responsable_achat, garantie, prix, date_sortie, sortie, raison_sortie, integration)
        VALUES ('".$i."', '".$datamysql["lab_id"]."', '***categorie***', '".$datamysql["serial_number"]."', '".$datamysql["reference"]."', '".$datamysql["designation"]."', '0', '0', '0000-00-00', '***tutelle***', '***contrat***', '".$datamysql["num_inventaire"]."', '***vendeur***', '***marque***', '".$datamysql["date_achat"]."', '***responsable_achat***', '".$datamysql["garantie"]."', '".$datamysql["prix"]."', '0000-00-00', '0', '0', '0'); ";
    
    
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

echo "<p style=\"text-align:center;\">Champs obligatoires :<br/><strong>Administratif→Désignation</strong><br/>ou <strong>Technique→Catégorie</strong></p>"; // TODO faire en sorte de vérifier que cette condition est bien remplie

echo "<form method=\"post\" action=\"?a=$i\">";

echo "<div id=\"container\">";
    require_once("./blocs/administratif.php");
    require_once("./blocs/technique.php");


    /*  ╔═╗╦ ╦╔╗ ╔╦╗╦╔╦╗
        ╚═╗║ ║╠╩╗║║║║ ║ 
        ╚═╝╚═╝╚═╝╩ ╩╩ ╩     */
    echo "<div id=\"bloc\" style=\"background:#f3f3f3; vertical-align:top;\">";
    echo "<h1>Validation</h1>";
    echo "<p style=\"text-align:center;\">";
    echo "<input name=\"add_valid\" value=\"Ajouter\" type=\"submit\" style=\"padding: 10px 50px;\">";
    echo "</p>"; // TODO Ajouter un bouton réinitialiser
    
    echo $error;



    echo $mysql;



    echo "</div>";

echo "</div>";

echo "</form>"

?>


</body>
</html>
