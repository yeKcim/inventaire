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
//Aurait été plus propre en passant par selecteur()
echo "<select name=\"IOT\" onchange=\"submit();\">";
    echo "<option value=\"0,2\" "; if ($IOT=="0,2") echo "selected"; echo ">— Répertorié (sauf sorti déf.) —</option>"; 
    echo "<option value=\"0\" "; if ($IOT=="0") echo "selected"; echo ">Uniquement non sorti</option>"; 
    echo "<option value=\"2\" "; if ($IOT=="2") echo "selected"; echo ">Uniquement sorti temp (prêt, réparation,…)</option>";
    echo "<option value=\"1\" "; if ($IOT=="1") echo "selected"; echo ">Uniquement sorti définitivement</option>";
echo "</select> ";
echo "<br/>";

//$IOT= ($IOT=="") ? "1,2" : $IOT ;
$IOT_CMD= ($IOT!="") ? "AND sortie IN ($IOT)" : "AND sortie IN (0,2)" ;


#########################################################################
#                        CAT : categorie                                #
#########################################################################
// $categories in tables_sql_commun.php
selecteur("CAT", $categories, "Toutes catégories", "2");
$CAT_CMD= ($CAT!="") ? "AND categorie_index=$CAT" : "" ;
 
#########################################################################
#                        TYC : type de contrat                          #
#########################################################################
// $types_contrats in tables_sql_commun.php
selecteur("TYC", $types_contrats, "Tous types de contrat");
$TYC_CMD= ($TYC!="") ? "AND contrat_type_index=$TYC" : "" ;

#########################################################################
#                            CON : contrat                              #
#########################################################################
$LIM_CON= ($TYC!="") ? "AND contrat_type=\"$TYC\" " : "" ; // si type de contrat sélectionné, formulaire contrats se limite

$table_contrat = "SELECT * FROM contrat, contrat_type WHERE contrat_index!=0 AND contrat_type_index!=0 $LIM_CON ORDER BY contrat_nom ASC ;";
$query_table_contrat = mysql_query ($table_contrat);
$contrats = array();
while ($contrat = mysql_fetch_row($query_table_contrat)) {
    $contrats[$contrat[0]]=array($contrat[0],utf8_encode($contrat[1]));
}
selecteur("CON", $contrats, "Tous contrats");
$CON_CMD= ($CON!="") ? "AND contrat_index=$CON" : "" ;

#########################################################################
#                      RES : Responsable achat                          #
#########################################################################
$table_responsable = "SELECT DISTINCT (utilisateur_index) responsable_achat, utilisateur_nom, utilisateur_prenom, utilisateur_mail, utilisateur_phone FROM utilisateur, base WHERE responsable_achat=utilisateur_index AND utilisateur_index!=0 ORDER BY utilisateur_nom,utilisateur_prenom ASC ;";
$query_table_responsable = mysql_query ($table_responsable);
$responsables = array();
while ($responsable = mysql_fetch_row($query_table_responsable)) {
    $responsables[$responsable[0]]=array($responsable[0],utf8_encode($responsable[1]),utf8_encode($responsable[2]),$responsable[3],$responsable[4]);
}
selecteur("RES", $responsables, "Tous responsables achat", "2");
$RES_CMD= ($RES!="") ? "AND responsable_achat=$RES" : "" ;

#########################################################################
#                         UTL : Utilisateur                             #
#########################################################################
$table_utilisateur = "SELECT DISTINCT(utilisateur_index) utilisateur_index, utilisateur_nom, utilisateur_prenom, utilisateur_mail, utilisateur_phone FROM utilisateur, base WHERE utilisateur=utilisateur_index AND utilisateur_index!=0 ORDER BY utilisateur_nom ASC ;";
$query_table_utilisateur = mysql_query ($table_utilisateur);
$utilisateurs = array();
while ($utilisateur = mysql_fetch_row($query_table_utilisateur)) {
    $utilisateurs[$utilisateur[0]]=array($utilisateur[0],utf8_encode($utilisateur[1]),utf8_encode($utilisateur[2]),$utilisateur[3],$utilisateur[4]);
}
selecteur("UTL", $utilisateurs, "Tous les utilisateurs", "2");
$UTL_CMD= ($UTL!="") ? "AND utilisateur=$UTL" : "" ;

#########################################################################
#                           SEA : Recherche                             #
#########################################################################
echo "<br/>";
echo "  <input value=\"$SEA_textbox\" name=\"SEA\" type=\"text\" onFocus=\"if (this.value=='$SEA_textbox') {this.value=''}\">
	    <input value=\"Chercher\" type=\"submit\">";
$SEA=utf8_encode($SEA);
$SEA_CMD= ($SEA!="") ? "AND (lab_id LIKE '%$SEA%' OR categorie LIKE '%$SEA%' OR reference LIKE '%$SEA%' OR designation LIKE '%$SEA%' OR marque_nom LIKE '%$SEA%' OR vendeur_nom LIKE '%$SEA%' OR serial_number LIKE '%$SEA%' OR num_inventaire LIKE '%$SEA%' OR bon_commande LIKE '%$SEA%')" : "";


// TODO tags

echo "<br/><br/>";
echo "</form>";

?>
