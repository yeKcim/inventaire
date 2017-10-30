<?php

/*
 █████╗ ██████╗ ██████╗  █████╗ ██╗   ██╗
██╔══██╗██╔══██╗██╔══██╗██╔══██╗╚██╗ ██╔╝
███████║██████╔╝██████╔╝███████║ ╚████╔╝
██╔══██║██╔══██╗██╔══██╗██╔══██║  ╚██╔╝
██║  ██║██║  ██║██║  ██║██║  ██║   ██║
╚═╝  ╚═╝╚═╝  ╚═╝╚═╝  ╚═╝╚═╝  ╚═╝   ╚═╝
*/

$table = "
SELECT base_index, lab_id, categorie, categorie_nom, reference, designation, marque, marque_nom, vendeur, vendeur_nom,
	vendeur_web, vendeur_remarques, serial_number, localisation, localisation_batiment, localisation_piece, date_localisation,
	vendeur_nom, marque_nom, raison_sortie, raison_sortie_nom, utilisateur, responsable_achat,
	utilisateur_nom as `responsable_nom`, utilisateur_prenom as `responsable_prenom`, utilisateur_mail as `responsable_mail`,
	utilisateur_phone as `responsable_phone`,
	date_achat, prix, contrat, contrat_nom,
	num_inventaire, integration
FROM base, categorie, marque, vendeur, localisation, contrat, contrat_type, utilisateur, raison_sortie
WHERE categorie=categorie_index AND marque=marque_index AND vendeur=vendeur_index AND
	localisation=localisation_index AND contrat=contrat_index AND raison_sortie=raison_sortie_index
AND contrat_index=contrat AND contrat_type=contrat_type_index AND responsable_achat=utilisateur_index
$IOT_CMD $CAT_CMD $TYC_CMD $CON_CMD $SEA_CMD $RES_CMD $UTL_CMD
$ORDER ;
";

$sth = $dbh->query($table);
$tableau = $sth->fetchAll(PDO::FETCH_ASSOC);

//liste des base_index affichés
$b_i="";
foreach ($tableau as &$t) { $b_i.="".$t["base_index"].","; }
$b_i=substr($b_i, 0, -1); // suppression du dernier caractère

//liste des journaux correspondants
$sth = $dbh->query("SELECT historique_id, COUNT(*) as nb_entree FROM historique, base WHERE historique_id=base_index AND base_index IN ($b_i) GROUP BY historique_id ORDER BY historique_id ASC;");
$tableau_journaux = $sth->fetchAll(PDO::FETCH_ASSOC);

//liste des ensembles parmi les éléments affichés
$sth = $dbh->query("SELECT base_index, integration FROM base WHERE integration IN ($b_i) ORDER BY base_index ASC ;");
$tableau_parents = $sth->fetchAll(PDO::FETCH_ASSOC);

//liste des caracs correspondantes
$tableau_carac=array();
$sth = $dbh->query("SELECT base_index, categorie, carac_valeur, carac, nom_carac, unite_carac, symbole_carac FROM caracteristiques, carac, base WHERE carac_id=base_index AND carac_caracteristique_id=carac AND base_index IN ($b_i) AND carac!=0 ORDER BY base.base_index ASC, carac ASC;");
$table_carac = $sth->fetchAll(PDO::FETCH_ASSOC);
foreach ($table_carac as &$l) {
	if ($l["unite_carac"]=="bool") { $unit=""; $value= ($l["carac_valeur"]=="1") ? "oui" : "non" ; }
	elseif ($l["carac_valeur"]=="∞") { $unit="" ; $value=$l["carac_valeur"];} // do not display unit if value is infinite
	else { $unit=$l["unite_carac"] ; $value=$l["carac_valeur"];}

	$tableau_carac[$l["base_index"]].="<span title=\"".$l["nom_carac"]."\"><span style=\"color:#2e3436;\">".$l[symbole_carac]."</span>:";
        $tableau_carac[$l["base_index"]].="<span style=\"color:#75507b;\">".$value."".$unit."</span></span> ; "; // À REVOIR
}

//date du jour
$today=date("Y-m-d");
//liste des entretiens correspondants
$sth = $dbh->query("SELECT e_id, e_index, e_frequence, e_lastdate, e_designation FROM entretien WHERE e_id IN ($b_i) ORDER BY e_index ASC ;");
$table_entretien = $sth->fetchAll(PDO::FETCH_ASSOC);
foreach ($table_entretien as &$l) {
    $f=$l["e_frequence"];
    $date_derniere_intervention=$l[e_lastdate];
    $date_prochaine_intervention = date("Y-m-d", strtotime($date_derniere_intervention." +$f days") );
    $retard = round( ( strtotime($today) - strtotime($date_prochaine_intervention) ) / 86400 );

    $tableau_entretien[$l["e_id"]]=(isset($tableau_entretien[$l["e_id"]])) ? $tableau_entretien[$l["e_id"]] : "";
    $tableau_entretien[$l["e_id"]].="<span style=\"color:";
    if ($retard>0)                  $tableau_entretien[$l["e_id"]].="#cc0000";
    else {  if (-$retard<$f*0.1)    $tableau_entretien[$l["e_id"]].="#f57900";
            else                    $tableau_entretien[$l["e_id"]].="#4e9a06";
    }
    $tableau_entretien[$l["e_id"]].=";\" title=\"".$l["e_designation"]." (".dateformat($date_prochaine_intervention,"fr").")\"><strong>";
    if ($retard>0)                  $tableau_entretien[$l["e_id"]].="⚠";
    else {  if (-$retard<$f*0.1)    $tableau_entretien[$l["e_id"]].="⌛";
            else                    $tableau_entretien[$l["e_id"]].="☑";
    }
    $tableau_entretien[$l["e_id"]].="</strong></span> ";

}


#########################################################################
#          Si du matériel sorti est affiché, afficher état              #
#########################################################################
if ($IOT!="0") {
    $raison_sortie=array();
    $sth = $dbh->query("SELECT * FROM raison_sortie WHERE raison_sortie_index!=0");
    $raison_sortie = $sth->fetchAll(PDO::FETCH_ASSOC);
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

/*  ╔═╗╔╗╔╦╗╔═╗╔╦╗╔═╗  ╔╦╗╔═╗╔╗ ╦  ╔═╗╔═╗╦ ╦
    ║╣ ║║║║ ║╣  ║ ║╣    ║ ╠═╣╠╩╗║  ║╣ ╠═╣║ ║
    ╚═╝╝╚╝╩ ╚═╝ ╩ ╚═╝   ╩ ╩ ╩╚═╝╩═╝╚═╝╩ ╩╚═╝    */
echo "<tr>";
     echo "<th>Id Labo<br/>";                                            	orderbylink("lab_id");              echo "</td>";
     echo "<th>Catégorie<br/>";                                          	orderbylink("categorie");           echo "</td>";
     echo "<th style=\"background:#bab987;\">Désignation<br/>";          	orderbylink("designation");         echo "</td>";
     echo "<th style=\"background:#a4b395;\">Caractéristiques<br/>";     	echo "&nbsp;";                      echo "</td>";
     echo "<th style=\"background:#8AAA6D;\">Marque<br/>";               	orderbylink("marque");              echo "</td>";
     echo "<th style=\"background:#8AAA6D;\">Référence fabricant<br/>";  	orderbylink("reference");           echo "</td>";
     echo "<th style=\"background:#BA944D;\">Fichiers liés à<br/>cette référence fabricant<br/>";   echo "&nbsp;";  echo "</td>";
     echo "<th style=\"background:#8AAA6D;\">Numéro de série<br/>";      	orderbylink("serial_number");       echo "</td>";
     echo "<th style=\"background:#bab987;\">n° d’inventaire<br/>";      	orderbylink("num_inventaire");      echo "</td>";
     echo "<th style=\"background:#bab987;\">Achat<br/>";                	orderbylink("prix");                echo "</td>";
     echo "<th style=\"background:#c19aaa;\">Entretiens<br/>";           	echo "&nbsp;";                      echo "</td>";
     echo "<th style=\"background:#BA944D;\">Fichiers de l’entrée<br/>"; 	echo "&nbsp;";                      echo "</td>";
     echo "<th style=\"background:#a786a2;\">Journal<br/>";              	echo "&nbsp;";                      echo "</td>";
     echo "<th style=\"background:#96a5bc;\">Localisation<br/>";         	orderbylink("localisation");        echo "</td>";
    if ($IOT!="0")  echo "<th style=\"background:#96a5bc;\">État<br/>";  	orderbylink("raison_sortie");       echo "</td>";
echo "</tr>";

/*  ╦  ╦╔═╗╔╗╔╔═╗╔═╗  ╔╦╗╔═╗  ╦═╗╔═╗╔═╗╦ ╦╦ ╔╦╗╔═╗╔╦╗╔═╗
    ║  ║║ ╦║║║║╣ ╚═╗   ║║║╣   ╠╦╝║╣ ╚═╗║ ║║  ║ ╠═╣ ║ ╚═╗
    ╩═╝╩╚═╝╝╚╝╚═╝╚═╝  ═╩╝╚═╝  ╩╚═╚═╝╚═╝╚═╝╩═╝╩ ╩ ╩ ╩ ╚═╝    */
foreach ($tableau as &$t) {
    echo "<tr>";

        // ********** Id Labo **********
        echo "<td><a href=\"info.php?i=".$t["base_index"]."\" title=\"#".$t["base_index"]."\" target=\"_blank\">";
        echo "<strong>";
        if ($t["lab_id"]=="") echo "#".$t["base_index"].""; else echo $t["lab_id"];
        echo "</strong>";
        echo "</a></td>";

        // ********** Catégorie **********
        echo "<td>".$t["categorie_nom"]."</td>";

        // ********** Désignation **********
        echo "<td>";
        echo "<span id=\"linkbox\" onclick=\"TINY.box.show({iframe:'quick.php?i=".$t["base_index"]."&quick_page=administratif&quick_name=Administratif',width:440,height:750,closejs:function(){location.reload()}})\" title=\"modification rapide administratif\">";
        if ($t["designation"]!="") echo $t["designation"];
        else echo "-";
        echo "</span>";
        echo "</td>";

        // ********** Caractéristiques **********
        echo "<td>";

        echo "<span id=\"linkbox\" onclick=\"TINY.box.show({iframe:'quick.php?i=".$t["base_index"]."&quick_page=caracteristiques&quick_name=Caractéristiques', width:440,height:750,closejs:function(){location.reload()}})\" title=\"modification rapide caracteristiques\">";

        if (isset($tableau_carac[$t["base_index"]]) ) echo substr($tableau_carac[$t["base_index"]], 0, -2);
        else echo "-";

        echo "</span>";

	// ********** Intégration **********
        $keys = array_keys(array_column($tableau_parents, 'integration'), $t["base_index"]);
	if ($t["integration"]!="0") echo "<br/><a href=\"info.php?i=".$t["integration"]."\" target=\"_blank\" title=\"intégré dans…\">↰ #".$t["integration"]."</a>";

	elseif (isset ($tableau_parents[$keys[0]])) {
		echo "<br/>↳ ";
		foreach ($keys as $k) echo "#".$tableau_parents[$k]["base_index"]." ";
	}
	else {
		echo "&nbsp;";
	}
        echo "</td>";


        // ********** Marque  **********
        echo "<td>";

        echo "<span id=\"linkbox\" onclick=\"TINY.box.show({iframe:'quick.php?i=".$t["base_index"]."&quick_page=technique&quick_name=Technique',width:440,height:750,closejs:function(){location.reload()}})\" title=\"";
        if ($t["vendeur"]!="-") echo "vendu par ".$t["vendeur_nom"]."";
        echo "\">";
	if ($t["marque"]!="") echo $t["marque_nom"]; else echo "-";

	echo "</span>";
        echo "</td>";

        // ********** Référence **********
        echo "<td>";
        echo "<span id=\"linkbox\" onclick=\"TINY.box.show({iframe:'quick.php?i=".$t["base_index"]."&quick_page=technique&quick_name=Technique',width:440,height:750,closejs:function(){location.reload()}})\" title=\"modification rapide technique\">";
        if ($t["reference"]!="") echo $t["reference"];
        else echo "-";
        echo "</span>";
        echo "</td>";

        // ********** Fichiers globaux **********
        echo "<td>";

        $m=str_replace('/', "_", $t["marque_nom"]);
        $r=str_replace('/', "_", $t["reference"]);
        $dir="files/".$m."-".$r;

        if (file_exists("$racine$dir")) {
            if ( ! is_dir_empty("$racine$dir")) {
                $files = scandir("$racine$dir");
                if ($files != FALSE) {
                    foreach ($files as $f) {
                        if (($f!=".")&&($f!="..")) {
                            echo "<a href=\"$dir/$f\" target=\"_blank\" title=\"".$f."\">";
                            icone($f);
                            echo "</a> ";}
                    }
               echo "<span id=\"linkbox\" onclick=\"TINY.box.show({ iframe:'quick.php?i=".$t["base_index"]."&quick_page=documents&quick_name=Documents',width:440,height:750,closejs:function(){location.reload()}})\" title=\"modification rapide documents\">";
                    echo "+</span>";
                    $nofiles=false;
                }
            }
            else $nofiles=true;
        }
        else $nofiles=true;

        if ($nofiles) echo "<span id=\"linkbox\" onclick=\"TINY.box.show({ iframe:'quick.php?i=".$t["base_index"]."&quick_page=documents&quick_name=Documents',width:440,height:750,closejs:function(){location.reload()}})\" title=\"modification rapide documents\">-</span>";


        echo "</td>";

        // ********** Serial number **********
        echo "<td>";
        echo "<span id=\"linkbox\" onclick=\"TINY.box.show({iframe:'quick.php?i=".$t["base_index"]."&quick_page=technique&quick_name=Technique',width:440,height:750,closejs:function(){location.reload()}})\" title=\"modification rapide technique\">";
        if ($t["serial_number"]!="") echo $t["serial_number"]; else echo "-";
        echo "</span>";
        echo "</td>";

        // ********** N° d’inventaire **********
        echo "<td>";
        echo "<span id=\"linkbox\" onclick=\"TINY.box.show({iframe:'quick.php?i=".$t["base_index"]."&quick_page=administratif&quick_name=Administratif',width:440,height:750,closejs:function(){location.reload()}})\" title=\"modification rapide administratif\">";
        if ($t["num_inventaire"]!="") echo $t["num_inventaire"]; else echo "-";
        echo "</span>";
        echo "</td>";

        // ********** Achat **********
        echo "<td>";

        echo "<span id=\"linkbox\" onclick=\"TINY.box.show({iframe:'quick.php?i=".$t["base_index"]."&quick_page=administratif&quick_name=Administratif',width:440,height:750,closejs:function(){location.reload()}})\" title=\"modification rapide administratif\">";

        echo "<span title=\"";
        if ($t["responsable_achat"]!="0") echo "Par ".$t["responsable_prenom"]." ".$t["responsable_nom"]." ";
        if ($t["date_achat"]!="0000-00-00") echo "le ".dateformat($t["date_achat"],"fr")."";
        echo "\">";
        if ($t["prix"]!="0") echo "".$t["prix"]."€";
        if ($t["contrat"]!="0")echo " sur ".$t["contrat_nom"]."";
        if ( ($t["prix"]=="0") && ($t["contrat"]=="0") ) echo "-";
        echo "</span>";

        echo "</span>";

        echo "</td>";

        // ********** Entretiens **********
        echo "<td>";

        echo "<span id=\"linkbox\" onclick=\"TINY.box.show({ iframe:'quick.php?i=".$t["base_index"]."&quick_page=entretien&quick_name=Entretien',width:440,height:750,closejs:function(){location.reload()}})\" title=\"modification rapide entretien\">";
        if (isset($tableau_entretien[$t["base_index"]]) ) {
            echo $tableau_entretien[$t["base_index"]];
        }
        else echo "-";
        echo "</span>";

        echo "</td>";

        // ********** Fichiers **********
        echo "<td>";

        $dir="files/".$t["base_index"]."/";
        if (file_exists("$racine$dir")) {
            if ( ! is_dir_empty("$racine$dir")) {
                $files = scandir("$racine$dir");
                if ($files != FALSE) {
                    foreach ($files as $f) {
                        if (($f!=".")&&($f!="..")) {
                            echo "<a href=\"files/".$t["base_index"]."/".$f."\" target=\"_blank\" title=\"".$f."\">";
                            icone($f);
                            echo "</a> ";}
                    }
               echo "<span id=\"linkbox\" onclick=\"TINY.box.show({ iframe:'quick.php?i=".$t["base_index"]."&quick_page=documents&quick_name=Documents',width:440,height:750,closejs:function(){location.reload()}})\" title=\"modification rapide documents\">";
                    echo "+</span>";
                    $nofiles=false;
                }
            }
            else $nofiles=true;
        }
        else $nofiles=true;

        if ($nofiles) echo "<span id=\"linkbox\" onclick=\"TINY.box.show({ iframe:'quick.php?i=".$t["base_index"]."&quick_page=documents&quick_name=Documents',width:440,height:750,closejs:function(){location.reload()}})\" title=\"modification rapide documents\">-</span>";


        echo "</td>";

        // ********** Journal **********
        echo "<td>";

        echo "<span id=\"linkbox\" onclick=\"TINY.box.show({ iframe:'quick.php?i=".$t["base_index"]."&quick_page=journal&quick_name=Journal',width:440,height:750,closejs:function(){location.reload()}})\" title=\"modification rapide journal\">";

	if (isset($t["base_index"])) $keys = array_keys(array_column($tableau_journaux, 'historique_id'), $t["base_index"]); if (isset(keys[0])) $key=$keys[0];
	if ( isset($key) ) echo "<sup>".$tableau_journaux[$key]["nb_entree"]."</sup> <img src=\"mime-icons/txt.png\" />" ;
	else echo "-" ;

        echo "</span>";

        echo "</td>";

        // ********** Localisation **********
        echo "<td>";
        echo "<span id=\"linkbox\" onclick=\"TINY.box.show({iframe:'quick.php?i=".$t["base_index"]."&quick_page=utilisation&quick_name=Utilisation',width:440,height:750,closejs:function(){location.reload()}})\" title=\"modification rapide utilisation\">";

	if (isset($t["utilisateur"])) $keys = array_keys(array_column($utilisateurs, 'utilisateur_index'), $t["utilisateur"]); if (isset(keys[0])) $key=$keys[0];
        if ($t["utilisateur"]!=0) echo "<span title=\"Utilisé par ".$utilisateurs[$key]["utilisateur_prenom"]." ".$utilisateurs[$key]["utilisateur_nom"]." ";
        else echo "<span title=\"";
        if ($t["localisation"]!=0) echo "le ".dateformat($t["date_localisation"],"fr")."";
        echo "\">";

        echo "".$t["localisation_batiment"]." ".$t["localisation_piece"]."";

        echo "</span>";
        echo "</span>";
        echo "</td>";

        // ********** État **********
        if ($IOT!="0") {
            echo "<td>";
            echo "<span id=\"linkbox\" onclick=\"TINY.box.show({iframe:'quick.php?i=".$t["base_index"]."&quick_page=utilisation&quick_name=Utilisation',width:440,height:750,closejs:function(){location.reload()},closejs:function(){location.reload()}})\" title=\"modification rapide utilisation\">";
	    echo $t["raison_sortie_nom"];
            echo "</span>";
            echo "</td>";
        }



    echo "</tr></a>";
}

echo "</table>";

?>
