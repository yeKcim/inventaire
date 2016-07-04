<?php

/*
 █████╗ ██████╗ ██████╗  █████╗ ██╗   ██╗
██╔══██╗██╔══██╗██╔══██╗██╔══██╗╚██╗ ██╔╝
███████║██████╔╝██████╔╝███████║ ╚████╔╝ 
██╔══██║██╔══██╗██╔══██╗██╔══██║  ╚██╔╝  
██║  ██║██║  ██║██║  ██║██║  ██║   ██║   
╚═╝  ╚═╝╚═╝  ╚═╝╚═╝  ╚═╝╚═╝  ╚═╝   ╚═╝   
*/

$table = "SELECT base_index, lab_id, categorie, categorie_nom, reference, designation, marque, marque_nom, vendeur, vendeur_nom, vendeur_web, vendeur_remarques, serial_number, localisation, localisation_batiment, localisation_piece, date_localisation, vendeur_nom, marque_nom, raison_sortie, utilisateur, responsable_achat, date_achat, prix, contrat
FROM base, categorie, marque, vendeur, localisation, contrat, contrat_type
WHERE categorie=categorie_index AND marque=marque_index AND vendeur=vendeur_index AND localisation=localisation_index
AND contrat_index=contrat AND contrat_type=contrat_type_index
$IOT_CMD $CAT_CMD $TYC_CMD $CON_CMD $SEA_CMD $RES_CMD $UTL_CMD
$ORDER ;";


// Tous les résultats dans un array
$query_table = mysql_query ($table);
while ($l = mysql_fetch_row($query_table)) {
    $tableau[$l[0]]=array(
        "base_index"=>$l[0],                "lab_id"=>$l[1],                            "categorie"=>$l[3],
        "reference"=>utf8_encode($l[4]),    "designation"=>utf8_encode($l[5]),          "marque"=>utf8_encode($l[7]),
        "vendeur"=>utf8_encode($l[9]),      "serial_number"=>utf8_encode($l[12]),       "raison_sortie"=>$l[19],
        "utilisateur"=>utf8_encode($l[20]), "responsable_achat"=>utf8_encode($l[21]),   "date_achat"=>$l[22],
        "prix"=>$l[23],                     "contrat"=>$l[24],                          
        "localisation"=> array($l[14],$l[15],$l[16])
    );
}


//liste des base_index affichés
$b_i="";
foreach ($tableau as &$t) { $b_i.="".$t["base_index"].","; }
$b_i=substr($b_i, 0, -1); // suppression du dernier caractère

$table_carac="SELECT base_index, categorie, carac_valeur, carac, nom_carac, unite_carac, symbole_carac FROM caracteristiques, carac, base WHERE carac_id=base_index AND carac_caracteristique_id=carac AND base_index IN ($b_i) AND carac!=0 ORDER BY base.base_index ASC, carac ASC";

$tableau_carac=array();
$query_table_carac = mysql_query ($table_carac);
while ($l = mysql_fetch_row($query_table_carac)) {
    if ($l[5]=="bool") { $unit=""; $value= ($l[2]=="1") ? "oui" : "non" ; }
    else               { $unit=$l[5] ; $value=$l[2];}
    $tableau_carac[$l[0]].="<span title=\"".$l[4]."\">$l[6]=$value$unit</span>, ";
}


#########################################################################
#          Si du matériel sorti est affiché, afficher état              #
#########################################################################
if ($IOT!="0") {
    $raison_sortie=array();
    $table_raison_sortie="SELECT * FROM raison_sortie WHERE raison_sortie_index!=0";
    $query_table_table_raison_sortie = mysql_query ($table_raison_sortie);
    while ($l = mysql_fetch_row($query_table_table_raison_sortie)) { $raison_sortie[$l[0]]=utf8_encode($l[1]); }
    $display_raison_sortie=1;
}
else $display_raison_sortie=0;

/*
████████╗ █████╗ ██████╗ ██╗     ███████╗ █████╗ ██╗   ██╗
╚══██╔══╝██╔══██╗██╔══██╗██║     ██╔════╝██╔══██╗██║   ██║
   ██║   ███████║██████╔╝██║     █████╗  ███████║██║   ██║
   ██║   ██╔══██║██╔══██╗██║     ██╔══╝  ██╔══██║██║   ██║
   ██║   ██║  ██║██████╔╝███████╗███████╗██║  ██║╚██████╔╝
   ╚═╝   ╚═╝  ╚═╝╚═════╝ ╚══════╝╚══════╝╚═╝  ╚═╝ ╚═════╝ 
*/

echo "<table id=\"listing\">";

#########################################################################
#                              Entête                                   #
#########################################################################
echo "<tr>";
    # echo "<th>&nbsp;<br/>&nbsp;</td>"; // À voir plus tard
    echo "<th>Id Labo<br/>";                orderbylink("lab_id");              echo "</td>";
    echo "<th>Catégorie<br/>";              orderbylink("categorie");           echo "</td>";
    echo "<th>Référence<br/>";              orderbylink("reference");           echo "</td>";
    echo "<th>Désignation<br/>";            orderbylink("designation");         echo "</td>";
    echo "<th>Caractéristiques<br/>";       echo "&nbsp;";                      echo "</td>";
    echo "<th>Marque<br/>";                 orderbylink("marque");              echo "</td>";
    echo "<th>Numéro de série<br/>";        orderbylink("serial_number");       echo "</td>";
    if ($IOT!="0") echo "<th>État<br/>";    orderbylink("raison_sortie");       echo "</td>";
    echo "<th>Localisation<br/>";           orderbylink("localisation");        echo "</td>";
    echo "<th>Achat<br/>";                  orderbylink("prix");             echo "</td>";
echo "</tr>";

#########################################################################
#                           Les résultats                               #
#########################################################################
foreach ($tableau as &$t) {
    echo "<tr>";
        echo "<td style=\"text-align:center;\"><span id=\"linkbox\" onclick=\"TINY.box.show({iframe:'info.php?i=".$t["base_index"]."',width:1280,height:800})\" title=\"#".$t["base_index"]."\">".$t["lab_id"]."<span></td>";
        echo "<td>".$t["categorie"]."</td>";
        echo "<td>".$t["reference"]."</td>";
        echo "<td>".$t["designation"]."</td>";
        echo "<td>".substr($tableau_carac[$t["base_index"]], 0, -2)."</td>";
        echo "<td><span title=\"vendu par ".$t["vendeur"]."\">".$t["marque"]."</span></td>";
        echo "<td>".$t["serial_number"]."</td>";
        if ($IOT!="0") echo "<td>".$raison_sortie[$t["raison_sortie"]]."</td>";
        echo "<td><span title=\"Utilisé par ".$utilisateurs[$t["utilisateur"]][2]." ".$utilisateurs[$t["utilisateur"]][1]." le ".dateformat($t["localisation"][2],"fr")."\">".$t["localisation"][0]." ".$t["localisation"][1]."</span></td>";
        echo "<td><span title=\"Par ".$responsables[$t["responsable_achat"]][2]." ".$responsables[$t["responsable_achat"]][1]." le ".dateformat($t["date_achat"],"fr")."\">".$t["prix"]."€ sur ".$contrats[$t["contrat"]][1]."</span></td>";
    echo "</tr>";
}

echo "</table>";


?>

