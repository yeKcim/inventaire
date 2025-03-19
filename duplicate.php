<?php
$titre="Dupliquer une entrée";
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

$copyid= isset($_GET["i"]) ? htmlentities($_GET["i"]) : "" ;

/*
  ██████╗ ██████╗ ██████╗ ██╗   ██╗    ██╗   ██╗ █████╗ ██████╗ ██╗ █████╗ ██████╗ ██╗     ███████╗███████╗
 ██╔════╝██╔═══██╗██╔══██╗╚██╗ ██╔╝    ██║   ██║██╔══██╗██╔══██╗██║██╔══██╗██╔══██╗██║     ██╔════╝██╔════╝
 ██║     ██║   ██║██████╔╝ ╚████╔╝     ██║   ██║███████║██████╔╝██║███████║██████╔╝██║     █████╗  ███████╗
 ██║     ██║   ██║██╔═══╝   ╚██╔╝      ╚██╗ ██╔╝██╔══██║██╔══██╗██║██╔══██║██╔══██╗██║     ██╔══╝  ╚════██║
 ╚██████╗╚██████╔╝██║        ██║        ╚████╔╝ ██║  ██║██║  ██║██║██║  ██║██████╔╝███████╗███████╗███████║
  ╚═════╝ ╚═════╝ ╚═╝        ╚═╝         ╚═══╝  ╚═╝  ╚═╝╚═╝  ╚═╝╚═╝╚═╝  ╚═╝╚═════╝ ╚══════╝╚══════╝╚══════╝
*/
$paste=array();

// $copy
$sth = $dbh->query("SELECT categorie,reference,designation,tutelle,contrat,bon_commande,vendeur,marque,date_achat,responsable_achat,garantie,prix,sortie,raison_sortie FROM base WHERE base_index=$copyid ;");
$copy = ($sth) ? $sth->fetchAll(PDO::FETCH_ASSOC) : FALSE ;
if ($sth) $sth->closeCursor();
$paste["lab_id"]=new_lab_id($copy[0]["categorie"]);

$paste["id"]=return_last_id("base_index", "base") + 1;

// #################### INSERT IN BASE ####################
$paste["base"]=str_replace("\"\"", "NULL","INSERT INTO base (base_index, lab_id, categorie,reference,designation,tutelle,contrat,bon_commande,vendeur,marque,date_achat,responsable_achat,garantie,prix,sortie,raison_sortie) VALUES (\"".$paste["id"]."\", \"".$paste["lab_id"]."\", \"".$copy[0]["categorie"]."\", \"".$copy[0]["reference"]."\", \"".$copy[0]["designation"]."\", \"".$copy[0]["tutelle"]."\", \"".$copy[0]["contrat"]."\", \"".$copy[0]["bon_commande"]."\", \"".$copy[0]["vendeur"]."\", \"".$copy[0]["marque"]."\", \"".$copy[0]["date_achat"]."\", \"".$copy[0]["responsable_achat"]."\", \"".$copy[0]["garantie"]."\", \"".$copy[0]["prix"]."\", \"".$copy[0]["sortie"]."\", \"".$copy[0]["raison_sortie"]."\") ;");

// #################### INSERT IN CARAC ####################
$sth = $dbh->query("SELECT carac_valeur, carac_caracteristique_id FROM carac WHERE carac_id=$copyid ;");
$copy_carac = ($sth) ? $sth->fetchAll(PDO::FETCH_ASSOC) : FALSE ;
if ($sth) $sth->closeCursor();
$VALUES="";
foreach ($copy_carac as $cc) {$VALUES.= "(\"".$cc["carac_valeur"]."\", \"".$paste["id"]."\",\"".$cc["carac_caracteristique_id"]."\"),";}
if ($VALUES!="") $paste["carac"]=str_replace("\"\"", "NULL", "INSERT INTO `carac` (`carac_valeur`, `carac_id`, `carac_caracteristique_id`) VALUES ".rtrim($VALUES,",")." ;");
else $paste["carac"]="";

// #################### INSERT IN COMPATIB ####################
$sth = $dbh->query("SELECT compatib_id1, compatib_id2 FROM compatibilite WHERE compatib_id1=".$copyid." OR compatib_id2=".$copyid." ;");
$copy_compatibilite = ($sth) ? $sth->fetchAll(PDO::FETCH_ASSOC) : FALSE ;
if ($sth) $sth->closeCursor();
$VALUES="";
foreach ($copy_compatibilite as $cc) {
    $A= ($cc["compatib_id1"]==$copyid) ? $paste["id"] : $cc["compatib_id1"];
    $B= ($cc["compatib_id2"]==$copyid) ? $paste["id"] : $cc["compatib_id2"];
    $VALUES.= "(\"".$A."\", \"".$B."\"),";
}
if ($VALUES!="") $paste["compatibilite"]=str_replace("\"\"", "NULL", "INSERT INTO `compatibilite` (`compatib_id1`, `compatib_id2`) VALUES ".rtrim($VALUES,",")." ;");
else $paste["compatibilite"]="";

// #################### INSERT IN ENTRETIEN ####################
$sth = $dbh->query("SELECT e_frequence, e_lastdate, e_designation, e_detail, e_effectuerpar FROM entretien WHERE e_id=$copyid ;");
$copy_entretien = ($sth) ? $sth->fetchAll(PDO::FETCH_ASSOC) : FALSE ;
if ($sth) $sth->closeCursor();
$VALUES="";
foreach ($copy_entretien as $ce) {$VALUES.= "(\"".$paste["id"]."\", \"".$ce[e_frequence]."\", \"".$ce[e_lastdate]."\", \"".$ce[e_designation]."\", \"".$ce[e_detail]."\"),";}
if ($VALUES!="") $paste["entretien"]=str_replace("\"\"", "NULL", "INSERT INTO `entretien` (`e_id`, `e_frequence`, `e_lastdate`, `e_designation`, `e_detail`) VALUES ".rtrim($VALUES,",")." ;");
else $paste["entretien"]="";

// #################### INSERT IN TAGS ####################
// TODO     


/*
 ██████╗  █████╗ ███████╗████████╗███████╗    ██╗   ██╗ █████╗ ██████╗ ██╗ █████╗ ██████╗ ██╗     ███████╗███████╗
 ██╔══██╗██╔══██╗██╔════╝╚══██╔══╝██╔════╝    ██║   ██║██╔══██╗██╔══██╗██║██╔══██╗██╔══██╗██║     ██╔════╝██╔════╝
 ██████╔╝███████║███████╗   ██║   █████╗      ██║   ██║███████║██████╔╝██║███████║██████╔╝██║     █████╗  ███████╗
 ██╔═══╝ ██╔══██║╚════██║   ██║   ██╔══╝      ╚██╗ ██╔╝██╔══██║██╔══██╗██║██╔══██║██╔══██╗██║     ██╔══╝  ╚════██║
 ██║     ██║  ██║███████║   ██║   ███████╗     ╚████╔╝ ██║  ██║██║  ██║██║██║  ██║██████╔╝███████╗███████╗███████║
 ╚═╝     ╚═╝  ╚═╝╚══════╝   ╚═╝   ╚══════╝      ╚═══╝  ╚═╝  ╚═╝╚═╝  ╚═╝╚═╝╚═╝  ╚═╝╚═════╝ ╚══════╝╚══════╝╚══════╝
*/
if ( isset($_POST["add_valid"]) ) {
    $add_result = $dbh->query($paste["base"]." ".$paste["carac"]." ".$paste["compatibilite"]." ".$paste["entretien"]);

    if (!isset($add_result)) $error.=$message_error_add;
    else {
        $success.="<p class=\"success_message\">";
        $success.="L’entrée #".$copyid." a été dupliquée dans la base de donnée.<br/>";
        $success.="Rendez-vous sur la page de <a href=\"info.php?BASE=$database&i=".$paste["id"]."\" target=\"_blank\"><strong>".$paste["lab_id"]." (#".$paste["id"].")</strong></a> pour compléter ses informations";
        $success.="</p>";
    }
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
echo "<form method=\"post\" action=\"\">";
echo "<div id=\"container\">";

    /*  ╔═╗╦ ╦╔╗ ╔╦╗╦╔╦╗
        ╚═╗║ ║╠╩╗║║║║ ║
        ╚═╝╚═╝╚═╝╩ ╩╩ ╩     */
    echo "<div id=\"bloc\" style=\"background:#f3f3f3; vertical-align:top;\">";

    echo "<h1>Dupliquer #".$copyid."</h1>";

    echo $success;
    echo "Entrée à dupliquer&nbsp;: #".$copyid."<br/>";
    echo "Base de données&nbsp;: ".$database."<br/>";

    echo "<p style=\"text-align:center;\">";
    echo "<input name=\"add_valid\" value=\"Valider la duplication\" type=\"submit\" class=\"big_button\" />";
    echo "</p>"; // TODO Ajouter un bouton réinitialiser

    echo $error;

    echo "</div>";

echo "</div>";

echo "</form>";

?>


</body>
</html>

<?php
$dbh = null;
?>
