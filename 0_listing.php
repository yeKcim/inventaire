<?php

/*stat*/
$prix_total=0;
$entretiens_late=0;
$entretiens_soon=0;
$entretiens_done=0;
/*endstat*/

/*
 █████╗ ██████╗ ██████╗  █████╗ ██╗   ██╗
██╔══██╗██╔══██╗██╔══██╗██╔══██╗╚██╗ ██╔╝
███████║██████╔╝██████╔╝███████║ ╚████╔╝
██╔══██║██╔══██╗██╔══██╗██╔══██║  ╚██╔╝
██║  ██║██║  ██║██║  ██║██║  ██║   ██║
╚═╝  ╚═╝╚═╝  ╚═╝╚═╝  ╚═╝╚═╝  ╚═╝   ╚═╝
*/

$table = "
SELECT base_index, lab_id, categorie, categorie_nom, categorie_lettres, reference, designation, marque, marque_nom, vendeur, vendeur_nom,
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
$tableau = ($sth) ? $sth->fetchAll(PDO::FETCH_ASSOC) : FALSE;
if ($sth) $sth->closeCursor();

//liste des base_index affichés
$b_i="";
foreach ($tableau as &$t) { $b_i.="".$t["base_index"].","; }
$b_i= ($b_i=="") ? "" : substr($b_i, 0, -1); // suppression du dernier caractère

if (!empty($b_i)) {
	//liste des journaux correspondants
	$sth = $dbh->query("SELECT historique_id, COUNT(*) as nb_entree FROM historique, base WHERE historique_id=base_index AND base_index IN ($b_i) GROUP BY historique_id ORDER BY historique_id ASC;");
	$tableau_journaux = ($sth) ? $sth->fetchAll(PDO::FETCH_ASSOC) : FALSE;
	if ($sth) $sth->closeCursor();
	
	//liste des ensembles parmi les éléments affichés
$sth = $dbh->query("SELECT base_index, integration, lab_id, categorie, reference, designation, sortie FROM base WHERE integration IN ($b_i) ORDER BY base_index ASC ; ");
$tableau_parents = ($sth) ? $sth->fetchAll(PDO::FETCH_ASSOC) : FALSE ;
if ($sth) $sth->closeCursor();

	//liste des caracs correspondantes
	$sth = $dbh->query("SELECT base_index, categorie, carac_valeur, carac, nom_carac, unite_carac, symbole_carac FROM caracteristiques, carac, base WHERE carac_id=base_index AND carac_caracteristique_id=carac AND base_index IN ($b_i) AND carac!=0 ORDER BY base.base_index ASC, carac ASC;");
	$table_carac = ($sth) ? $sth->fetchAll(PDO::FETCH_ASSOC) : array() ;
	if ($sth) $sth->closeCursor();
	$tc=array(); $td_c=array(); $th_c="";
	$val=array();
	foreach ($table_carac as $l) {
		$li=$l["base_index"]; $lc=$l["carac"];
		if (!isset($val[$li])) $val[$li]=array();
		if ($l["unite_carac"]=="bool") { $unit=""; $value= ($l["carac_valeur"]=="1") ? "oui" : "non" ; }
		elseif ($l["carac_valeur"]=="∞") { $unit="" ; $value=$l["carac_valeur"];} // do not display unit if value is infinite
		else { $unit=$l["unite_carac"] ; $value=$l["carac_valeur"];}
		if (!array_key_exists($l["base_index"], $tc)) $tc[$l["base_index"]]="";
		$nom_carac_abbr="<span title=\"".$l["nom_carac"]."\"><span style=\"color:#2e3436;\">".$l["symbole_carac"]."</span>";
		$tc[$l["base_index"]].=$nom_carac_abbr.":";
		$tc[$l["base_index"]].="<span style=\"color:#75507b;\">".$value."".$unit."</span></span> ; ";
		$val[$li][$lc]=$value;
	}
	
	//liste des entretiens correspondants
	$sth = $dbh->query("SELECT e_id, e_index, e_frequence, e_lastdate, e_designation FROM entretien WHERE e_id IN ($b_i) ORDER BY e_index ASC ;");
	$tableau_entretien = ($sth) ? $sth->fetchAll(PDO::FETCH_ASSOC) : array() ;
	if ($sth) $sth->closeCursor();
	$te=array();
	foreach ($tableau_entretien as $l) {
	    $f=$l["e_frequence"];
	    $date_derniere_intervention=$l["e_lastdate"];
	    $date_prochaine_intervention = date("Y-m-d", strtotime($date_derniere_intervention." +$f days") );
	    $retard = round( ( strtotime($today) - strtotime($date_prochaine_intervention) ) / 86400 );

	    $te[$l["e_id"]]=(array_key_exists($l["e_id"], $te)) ? $te[$l["e_id"]] : "";
	    $te[$l["e_id"]].="<span style=\"color:";
	    if ($retard>0)                  $te[$l["e_id"]].="#cc0000";
	    else {  if (-$retard<$f*0.1)    $te[$l["e_id"]].="#f57900";
		    else                    $te[$l["e_id"]].="#4e9a06";
	    }
	    $te[$l["e_id"]].=";\" title=\"".$l["e_designation"]." (".dateformat($date_prochaine_intervention,"fr").")\"><strong>";
	    if ($retard>0)                  { $te[$l["e_id"]].="⚠"; /*stat*/$entretiens_late=$entretiens_late+1;/*endstat*/ }
	    else {  if (-$retard<$f*0.1)    { $te[$l["e_id"]].="⌛";/*stat*/$entretiens_soon=$entretiens_soon+1;/*endstat*/ }
		    else                    { $te[$l["e_id"]].="☑"; /*stat*/$entretiens_done=$entretiens_done+1;/*endstat*/ }
	    }
	    $te[$l["e_id"]].="</strong></span> ";

	}

} else {
	    $tableau_journaux = [];
	    $tableau_parents = [];
	    $tableau_entretien = [];
}



//liste des base_index affichés
$b_e="";
foreach ($tableau as &$t) { $b_e.=($t["integration"]!="0") ? $t["integration"]."," : ""; }
if ($b_e!="") {
	$b_e=substr($b_e, 0, -1); // suppression du dernier caractère
	$sth = $dbh->query("SELECT base_index, lab_id, categorie, reference, designation, sortie FROM base WHERE base_index IN ($b_e) ORDER BY base_index ASC ; ");
	$tableau_enfants = ($sth) ? $sth->fetchAll(PDO::FETCH_ASSOC) : FALSE ;
	if ($sth) $sth->closeCursor();
} else {
	    $tableau_enfants = [];
}

$th_c="";

if ($CAT!="") {
  // Si une seule catégorie est affichée on met les caractéristiques pertinentes dans un tableau
  $sth = $dbh->query("SELECT DISTINCT carac, nom_carac, unite_carac, symbole_carac FROM caracteristiques, carac, base WHERE carac_id=base_index AND carac_caracteristique_id=carac AND categorie=".$CAT." AND carac!=0 ORDER BY carac ASC ;");
  $carac_categorie = ($sth) ? $sth->fetchAll(PDO::FETCH_ASSOC) : FALSE ;
  if ($sth) $sth->closeCursor();

  $style="background-color:rgba(212, 224, 200, 0.45);";

  foreach ($carac_categorie as $cc) {
    //on ajoute une case dans th
    $th_c.="<th style=\"background:#a4b395;vertical-align:top;\">";
    $th_c.="<span title=\"".$cc["nom_carac"]."\"><span style=\"color:#2e3436;\">".$cc["symbole_carac"]."</span>";
    if ($cc["unite_carac"]!="") $th_c.="<br/>(".$cc["unite_carac"].")";
    $th_c.="</th>";
    //on ajoute une case dans tr
    foreach ($val as $k => $v) {
	if (!isset($val[$k]["echo"])) $val[$k]["echo"]="";
	$val[$k]["echo"].="<td style=\"".$style."\">".spanquick("caracteristiques",$k);
	if (isset($v[$cc["carac"]])) {
																
	    //todo:on ajoute la valeur numérique en commentaire ou cachée
	    //$val[$k]["echo"].="<strike>".vnum($v[$cc["carac"]])."</strike> ";
	    $val[$k]["echo"].="<span style=\"display:none;\">".vnum($v[$cc["carac"]])."</span> ";
	    $val[$k]["echo"].=$v[$cc["carac"]];
																
	}
	else $val[$k]["echo"].="-";
	$val[$k]["echo"].="</span></td>";
    }
  }
}

//date du jour
$today=date("Y-m-d");

/*#######################################################################
#          Si du matériel sorti est affiché, afficher état              #
#######################################################################*/
if ($IOT!="0") {
    $raison_sortie=array();
    $sth = $dbh->query("SELECT * FROM raison_sortie WHERE raison_sortie_index!=0");
    $raison_sortie = ($sth) ? $sth->fetchAll(PDO::FETCH_ASSOC) : FALSE ;
    if ($sth) $sth->closeCursor();
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
echo "<thead>";
echo "<tr>";
     echo "<th>							Id Labo";                   	echo "</th>";
     echo "<th>							Catégorie";                 	echo "</th>";
     echo "<th style=\"background:#bab987;\">			Désignation";               	echo "</th>";

     if ($th_c!="") echo $th_c;
     else echo "<th style=\"background:#a4b395;\">		Caractéristiques";          	echo "</th>";
     if ($CAT!="") echo "<th style=\"background:rgb(150, 165, 188);\">Intègre</th>";

     echo "<th style=\"background:#8AAA6D;\">			Marque";                    	echo "</th>";
     echo "<th style=\"background:#8AAA6D;\">			Référence fabricant";       	echo "</th>";
     echo "<th style=\"background:#BA944D;\">			Fichiers<br/>référence";    	echo "</th>";
     echo "<th style=\"background:#8AAA6D;\">			Numéro de série";           	echo "</th>";
     echo "<th style=\"background:#bab987;\">			n° d’inventaire";           	echo "</th>";
     echo "<th style=\"background:#bab987;\">			Achat";                     	echo "</th>";
     echo "<th style=\"background:#c19aaa;\">			Entretiens";                	echo "</th>";
     echo "<th style=\"background:#BA944D;\">			Fichiers<br/>entrée";       	echo "</th>";
     echo "<th style=\"background:#a786a2;\">			Journal";                   	echo "</th>";
     echo "<th style=\"background:#96a5bc;\">           Intégré à";                     echo "</th>";
     echo "<th style=\"background:#96a5bc;\">			Localisation";              	echo "</th>";
    if ($IOT!="0")  echo "<th style=\"background:#96a5bc;\">	État";       			echo "</th>";
     echo "<th>                                         &nbsp;";                        echo "</th>";
echo "</tr>";
echo "</thead>";

/*  ╦  ╦╔═╗╔╗╔╔═╗╔═╗  ╔╦╗╔═╗  ╦═╗╔═╗╔═╗╦ ╦╦ ╔╦╗╔═╗╔╦╗╔═╗
    ║  ║║ ╦║║║║╣ ╚═╗   ║║║╣   ╠╦╝║╣ ╚═╗║ ║║  ║ ╠═╣ ║ ╚═╗
    ╩═╝╩╚═╝╝╚╝╚═╝╚═╝  ═╩╝╚═╝  ╩╚═╚═╝╚═╝╚═╝╩═╝╩ ╩ ╩ ╩ ╚═╝    */
foreach ($tableau as &$t) {
    echo "<tr>";

        // ********** Id Labo **********
        echo "<td><a href=\"info.php?BASE=$database&i=".$t["base_index"]."\" title=\"#".$t["base_index"]."\" target=\"_blank\">";
        echo "<strong>";
        if ($t["lab_id"]=="") echo "#".$t["base_index"]."";
        else {
            echo "<span style=\"display:none;\">".preg_replace("/[^a-zA-Z]+/", "", $t["lab_id"])."-".sprintf( "%06d", preg_replace("/[^0-9]+/", "", $t["lab_id"]) )."</span> ";
            echo $t["lab_id"];
        }
        echo "</strong>";
        echo "</a></td>";

        // ********** Catégorie **********
        echo "<td>";
	if ($CAT=="") echo "<a href=\"?BASE=$database&CAT=".$t["categorie"]."\" style=\"color:#000;\" title=\"Afficher les entrées de la catégorie [".$t["categorie_lettres"]."]\">";
	echo $t["categorie_nom"];
	if ($CAT=="") echo "</a>";
	echo "</td>";

        // ********** Désignation **********
        echo "<td>";
        echo spanquick("administratif",$t["base_index"]);
        if ($t["designation"]!="") echo $t["designation"];
        else echo "-";
        echo "</span>";
        echo "</td>";

        // ********** Caractéristiques **********
    if ($CAT=="") {
        echo "<td>";

	    echo spanquick("caracteristiques",$t["base_index"]);

	    if (array_key_exists($t["base_index"], $tc)) echo substr($tc[$t["base_index"]], 0, -2);
        else echo "-";

        echo "</span>";
    }
    elseif ($th_c=="") echo "<td style=\"".$style."\">".spanquick("caracteristiques",$t["base_index"])."-</span></td>";
    else {
	if (isset($val[$t["base_index"]]["echo"])) echo $val[$t["base_index"]]["echo"];
	else foreach ($carac_categorie as $c) echo "<td style=\"".$style."\">".spanquick("caracteristiques",$t["base_index"])."-</span></td>";
    }


    if ($CAT!="") echo "<td>";

        $keys = array_keys(array_column($tableau_parents, 'integration'), $t["base_index"]);
        // Intégration parent de
        if (array_key_exists("0", $keys)) {
            if (array_key_exists($keys[0], $tableau_parents)) {
               if ($CAT!="") { foreach ($keys as $k) {echo "⬉&nbsp;"; quickdisplaymini($tableau_parents[$k]); echo "<br/>";}  }
               else {
                 echo "<ul>";
                 foreach ($keys as $k) { echo "<li style=\"list-style-type: '⬉';\">&nbsp;"; quickdisplayincarac($tableau_parents[$k]); echo "</li>";}
                 echo "</ul>";
               }
            }
        }
        else { if ($CAT!="") echo "<a href=\"\" title=\"todo\">-</a>";}

echo "</td>";


        // ********** Marque  **********
        echo "<td>";

        echo "<span id=\"linkbox\" onclick=\"TINY.box.show({iframe:'quick.php?BASE=$database&i=".$t["base_index"]."&quick_page=technique&quick_name=Technique',width:440,height:750,closejs:function(){location.reload()}})\" title=\"";
        if ($t["vendeur"]!="-") echo "vendu par ".$t["vendeur_nom"]."";
        echo "\">";
	if ($t["marque"]!="") echo $t["marque_nom"]; else echo "-";

	echo "</span>";
        echo "</td>";

        // ********** Référence **********
        echo "<td>";
        echo spanquick("technique",$t["base_index"]);
        if ($t["reference"]!="") echo $t["reference"];
        else echo "-";
        echo "</span>";
        echo "</td>";

        // ********** Fichiers globaux **********
        echo "<td>";

        $m=str_replace('/', "_", $t["marque_nom"]);
        $r=str_replace('/', "_", $t["reference"]);
        $dir="files/$database/".$m."-".$r;

        
            //$dir=str_replace("&", "&amp;", $dir);
            $dir=str_replace("&", "amp", $dir);
            $dir=str_replace(";", "semicolon", $dir);
        


        if (file_exists("$racine$dir")) {
            $ddir=display_dir_compact("$racine$dir");
            if ($ddir) echo $ddir; else $nofiles=true;
        }
        else $nofiles=true;
        if ($nofiles) echo spanquick("documents",$t["base_index"])."-</span>";
        else echo spanquick("documents",$t["base_index"])."+</span>";

        echo "</td>";

        // ********** Serial number **********
        echo "<td>";
        echo spanquick("technique",$t["base_index"]);
        if ($t["serial_number"]!="") echo $t["serial_number"]; else echo "-";
        echo "</span>";
        echo "</td>";

        // ********** N° d’inventaire **********
        echo "<td>";
        echo spanquick("administratif",$t["base_index"]);
        if ($t["num_inventaire"]!="") echo $t["num_inventaire"]; else echo "-";
        echo "</span>";
        echo "</td>";

        // ********** Achat **********
        echo "<td>";

        echo spanquick("administratif",$t["base_index"]);

        echo "<span title=\"";
        if ($t["responsable_achat"]!="0") echo "Par ".$t["responsable_prenom"]." ".$t["responsable_nom"]." ";
        if ($t["date_achat"]!="0000-00-00") echo "le ".dateformat($t["date_achat"],"fr")."";
        echo "\">";
        if ($t["prix"]!="0") { echo "".$t["prix"]."€"; /*stat*/$prix_total=$prix_total+$t["prix"];/*endstat*/}
        if ($t["contrat"]!="0")echo " sur ".$t["contrat_nom"]."";
        if ( ($t["prix"]=="0") && ($t["contrat"]=="0") ) echo "-";
        echo "</span>";

        echo "</span>";

        echo "</td>";

        // ********** Entretiens **********
        echo "<td>";

        echo spanquick("entretien",$t["base_index"]);
	if (array_key_exists($t["base_index"], $te)) echo $te[$t["base_index"]]; else echo "-";
        echo "</span>";

        echo "</td>";

        // ********** Fichiers **********
        echo "<td>";

        $dir="files/$database/".$t["base_index"]."";
        if (file_exists("$racine$dir")) {
            $ddir=display_dir_compact("$racine$dir");
            if ($ddir) echo $ddir; else $nofiles=true;
        }
        else $nofiles=true;
        if ($nofiles) echo spanquick("documents",$t["base_index"])."-</span>";
        else echo spanquick("documents",$t["base_index"])."+</span>";

        echo "</td>";

        // ********** Journal **********
        echo "<td>";

        echo spanquick("journal",$t["base_index"]);

	if (array_key_exists("base_index", $t)) {
		$keys = array_keys(array_column($tableau_journaux, 'historique_id'), $t["base_index"]);
		if (array_key_exists("0",$keys)) echo "<sup>".$tableau_journaux[$keys[0]]["nb_entree"]."</sup> <img src=\"mime-icons/txt.png\" />" ;
		else echo "-" ;
	}
	else echo "-" ;

        echo "</span>";

        echo "</td>";

        // ********** Intégré à **********
        echo "<td>";
        $keys = array_keys(array_column($tableau_parents, 'integration'), $t["base_index"]);
        if ($t["integration"]!="0") {
		echo spanquick("utilisation",$t["base_index"])."➡</span>&nbsp;";
                $keys = array_keys(array_column($tableau_enfants, 'base_index'), $t["integration"]);
                if (isset($keys[0])) quickdisplaymini($tableau_enfants[$keys[0]]);
        }
	else echo spanquick("utilisation",$t["base_index"])."-</span>";
        echo "</td>";


        // ********** Localisation **********
        echo "<td>";
        echo spanquick("utilisation",$t["base_index"]);

	if (array_key_exists("utilisateur", $t)) { $keys = array_keys(array_column($utilisateurs, 'utilisateur_index'), $t["utilisateur"]); if (array_key_exists("0",$keys)) $key=$keys[0]; }
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
            echo spanquick("utilisation",$t["base_index"]);
	        echo $t["raison_sortie_nom"];
            echo "</span>";
            echo "</td>";
        }


                                
        // ********** Outils **********
        echo "<td>";
        echo "<span id=\"linkbox\" onclick=\"TINY.box.show({iframe:'duplicate.php?BASE=$database&i=".$t["base_index"]."',width:440,height:750,closejs:function(){location.reload()}})\" title=\"Dupliquer cette entrée\">";
        echo "✚";
        echo "</span>";
        echo "</td>";
                                




    echo "</tr>";

}

echo "</table>";

//active datatables for this table
echo "<script type=\"text/javascript\" charset=\"utf8\" src=\"datatables/jquery.dataTables.min.js\"></script>\n";
echo " <script>\n";
  echo "\$(function(){\n";
    echo "\$(\"#listing\").dataTable({\n";
        echo "\"bStateSave\": true,\n";
        echo "\"fnStateSave\": function (oSettings, oData) {\n";
            echo "localStorage.setItem( 'DataTables', JSON.stringify(oData) );\n";
        echo "},\n";
        echo "\"fnStateLoad\": function (oSettings) {\n";
            echo "return JSON.parse( localStorage.getItem('DataTables') );\n";
        echo "},\n";
        echo "\"iCookieDuration\": 60,\n"; // 2 minute
        echo "sPaginationType: \"two_button\",\n";
        echo "bPaginate: true,\n";
        echo "bLengthChange: true,\n";
        echo "iDisplayLength: 25,\n";
        echo "aaSorting: [],\n";
    echo "});\n";
  echo "})\n";
  echo "</script>\n";

/*stat*/
//echo "<h2>Statistiques</h2>";
echo "<br/><br/><hr/>";
echo "<ul>";
echo "<li>Nombre d’entrées : ".count($tableau)."</li>" ;
echo "<li>Prix total (des entrées renseignées) : ".number_format($prix_total, 0, ',', ' ')." €</li>" ;
echo "<li>Entretiens : ";
	echo "<span style=\"color:#cc0000\">".$entretiens_late."⚠</span>";
	echo " - ";
	echo "<span style=\"color:#f57900\">".$entretiens_soon."⌛</span>";
	echo " - ";
	echo "<span style=\"color:#4e9a06\">".$entretiens_done."☑</span>";
echo "</li>";
echo "</ul>";
/*endstat*/

?>
