<?php
require_once("./0_tables_sql_commun.php");
#########################################################################
#                                                                       #
#                FORMULAIRE DE RESTRICTION D’AFFICHAGE                  #
#                                                                       #
#########################################################################
echo "<form method=\"get\" action=\"?\">";

#########################################################################
#                        IOT : in/out/tmp                               #
#########################################################################
//selecteur_chosen("IOT", $iot, "— Répertorié (sauf sorti déf.) —", "iot_index", "iot_nom");
$iot=array();
$iot[0]=array("iot_index"=> "0,2", "iot_nom" => "— Inventaire —");
$iot[1]=array("iot_index"=> "0", "iot_nom" => "Uniquement non sorti");
$iot[2]=array("iot_index"=> "2", "iot_nom" => "Uniquement sorti temp (prêt, réparation,…)");
$iot[3]=array("iot_index"=> "1", "iot_nom" => "Uniquement sorti définitivement");
echo "<select name=\"IOT\" onchange=\"submit();\" data-placeholder=\"Choose…\" class=\"chosen-select\" tabindex=\"0\">";
foreach ($iot as $i) {
	echo "<option value=\"".$i["iot_index"]."\" ";
	if ($IOT==$i["iot_index"]) echo "selected";
	echo ">".$i["iot_nom"]."</option>";
}
echo "</select> ";

//$IOT= ($IOT=="") ? "1,2" : $IOT ;
$IOT_CMD= ($IOT!="") ? "AND sortie IN ($IOT)" : "AND sortie IN (0,2)" ;

#########################################################################
#                        CAT : categorie                                #
#########################################################################
// $categories in tables_sql_commun.php
selecteur_chosen("CAT", $categories, "Toutes catégories", "categorie_index", "categorie_nom", "categorie_lettres", "display()");
$CAT_CMD= ($CAT!="") ? "AND categorie_index IN ($CAT)" : "" ;


/*#########################################################################
#                           SEA : Recherche                             #
#########################################################################
echo " ";
echo "  <input value=\"$SEA_textbox\" name=\"SEA\" type=\"text\" onFocus=\"if (this.value=='$SEA_textbox') {this.value=''}\">
            <input value=\"Chercher\" type=\"submit\">";
$SEA_CMD= ($SEA!="") ? "AND (lab_id LIKE '%$SEA%' OR categorie LIKE '%$SEA%' OR reference LIKE '%$SEA%' OR designation LIKE '%$SEA%' OR marque_nom LIKE '%$SEA%' OR vendeur_nom LIKE '%$SEA%' OR serial_number LIKE '%$SEA%' OR num_inventaire LIKE '%$SEA%' OR bon_commande LIKE '%$SEA%')" : "";

⇒ useless, datatables!
*/$SEA_CMD="";

echo "<br/>";
#########################################################################
#                        TYC : type de contrat                          #
#########################################################################
// $types_contrats in tables_sql_commun.php
selecteur_chosen("TYC", $types_contrats, "Tous types de contrat", "contrat_type_index", "contrat_type_cat");
$TYC_CMD= ($TYC!="") ? "AND contrat_type_index=$TYC" : "" ;

#########################################################################
#                            CON : contrat                              #
#########################################################################
$LIM_CON= ($TYC!="") ? "AND contrat_type=\"$TYC\" " : "" ; // si type de contrat sélectionné, formulaire contrats se limite
$sth = $dbh->query("SELECT contrat_index, contrat_nom, contrat_type FROM contrat, contrat_type WHERE contrat_index!=0 AND contrat_type_index!=0 AND contrat_type=contrat_type.contrat_type_index $LIM_CON ORDER BY contrat_nom ASC ;");
$contrats = ($sth) ? $sth->fetchAll(PDO::FETCH_ASSOC) : FALSE ;
selecteur_chosen("CON", $contrats, "Tous contrats", "contrat_index", "contrat_nom");
$CON_CMD= ($CON!="") ? "AND contrat_index=$CON" : "" ;
if ($sth) $sth->closeCursor();

#########################################################################
#                      RES : Responsable achat                          #
#########################################################################
$sth = $dbh->query("SELECT DISTINCT (utilisateur_index) responsable_achat, utilisateur_nom, utilisateur_prenom, utilisateur_mail, utilisateur_phone FROM utilisateur, base WHERE responsable_achat=utilisateur_index AND utilisateur_index!=0 ORDER BY utilisateur_nom, utilisateur_prenom ASC ;");
$responsables = ($sth) ? $sth->fetchAll(PDO::FETCH_ASSOC) : FALSE ;
selecteur_chosen("RES", $responsables, "Tous responsables achat", "responsable_achat", "utilisateur_nom", "utilisateur_prenom");
$RES_CMD= ($RES!="") ? "AND responsable_achat=$RES" : "" ;
if ($sth) $sth->closeCursor();

#########################################################################
#                         UTL : Utilisateur                             #
#########################################################################
$sth = $dbh->query("SELECT DISTINCT(utilisateur_index) utilisateur_index, utilisateur_nom, utilisateur_prenom, utilisateur_mail, utilisateur_phone FROM utilisateur, base WHERE utilisateur=utilisateur_index AND utilisateur_index!=0 ORDER BY utilisateur_nom ASC ;");
$utilisateurs = ($sth) ? $sth->fetchAll(PDO::FETCH_ASSOC) : FALSE ;
selecteur_chosen("UTL", $utilisateurs, "Tous les utilisateurs", "utilisateur_index", "utilisateur_nom", "utilisateur_prenom");
$UTL_CMD= ($UTL!="") ? "AND utilisateur=$UTL" : "" ;
if ($sth) $sth->closeCursor();

#########################################################################
#                             RESET BUTTON                              #
#########################################################################
echo " &nbsp; <a href=\"?BASE=".$database."\" title=\"RESET\">↺</a>";

// TODO tags

echo "<br/><br/>";

echo "<input id=\"BASE\" name=\"BASE\" type=\"hidden\" value=\"$database\">";

echo "</form>";

?>
