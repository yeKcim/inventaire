<?php

function include_module($module_path, $t = null) {
	if (!isset($t) || !is_array($t)) return ['thead' => '', 'tbody' => '', 'tfoot' => ''];
	return include $module_path;
}

/*stat*/
$prix_total=0;
$entretiens_late=0;
$entretiens_soon=0;
$entretiens_done=0;
/*endstat*/

//date du jour
$today=date("Y-m-d");

/*
 █████╗ ██████╗ ██████╗  █████╗ ██╗   ██╗
██╔══██╗██╔══██╗██╔══██╗██╔══██╗╚██╗ ██╔╝
███████║██████╔╝██████╔╝███████║ ╚████╔╝
██╔══██║██╔══██╗██╔══██╗██╔══██║  ╚██╔╝
██║  ██║██║  ██║██║  ██║██║  ██║   ██║
╚═╝  ╚═╝╚═╝  ╚═╝╚═╝  ╚═╝╚═╝  ╚═╝   ╚═╝
*/

// Construire la requête SQL avec les clauses dynamiques
$table = "
SELECT base_index, lab_id, categorie, categorie_nom, categorie_lettres, reference, designation, marque, marque_nom, vendeur, vendeur_nom,
       vendeur_web, vendeur_remarques, serial_number, localisation, localisation_batiment, localisation_piece, date_localisation,
       vendeur_nom, marque_nom, raison_sortie, raison_sortie_nom, utilisateur, responsable_achat,
       utilisateur_nom as `responsable_nom`, utilisateur_prenom as `responsable_prenom`, utilisateur_mail as `responsable_mail`,
       utilisateur_phone as `responsable_phone`,
       date_achat, prix, contrat, contrat_nom,
       num_inventaire, integration
FROM base, categorie, marque, vendeur, localisation, contrat, contrat_type, utilisateur, raison_sortie
WHERE categorie = categorie_index
  AND marque = marque_index
  AND vendeur = vendeur_index
  AND localisation = localisation_index
  AND contrat = contrat_index
  AND raison_sortie = raison_sortie_index
  AND contrat_index = contrat
  AND contrat_type = contrat_type_index
  AND responsable_achat = utilisateur_index
  $IOT_CMD $CAT_CMD $TYC_CMD $CON_CMD $SEA_CMD $RES_CMD $UTL_CMD
  $ORDER;
";
// Préparer la requête
$sth = $dbh->prepare($table);
// Exécuter la requête
$sth->execute();
// Récupérer les résultats
$tableau = $sth->fetchAll(PDO::FETCH_ASSOC);
// Fermer le curseur
$sth->closeCursor();

//liste des base_index affichés
$b_i="";
foreach ($tableau as &$t) { $b_i.="".$t["base_index"].","; }
$b_i= ($b_i=="") ? "" : substr($b_i, 0, -1); // suppression du dernier caractère

if (!empty($b_i)) {
	$tableau_journaux = array();
	// Supposons que $b_i est un tableau d'IDs
	$b_i_array = is_array($b_i) ? $b_i : explode(',', $b_i); // au cas où ce soit une chaîne
	$placeholders = implode(',', array_fill(0, count($b_i_array), '?'));
	//liste des journaux correspondants
	$sql = "SELECT historique_id, COUNT(*) as nb_entree 
		    FROM historique, base 
		    WHERE historique_id = base_index 
		      AND base_index IN ($placeholders) 
		    GROUP BY historique_id 
		    ORDER BY historique_id ASC";
	$sth = $dbh->prepare($sql);
	$sth->execute($b_i_array);
	$tableau_journaux = ($sth) ? $sth->fetchAll(PDO::FETCH_ASSOC) : false;
	if ($sth) $sth->closeCursor();
	
	//liste des ensembles parmi les éléments affichés
	$tableau_parents = array();
	// S'assurer que $b_i est un tableau (ex: [1,2,3])
	$b_i_array = is_array($b_i) ? $b_i : explode(',', $b_i);
	$placeholders = implode(',', array_fill(0, count($b_i_array), '?'));
	$sql = "SELECT base_index, integration, lab_id, categorie, reference, designation, sortie
		    FROM base
		    WHERE integration IN ($placeholders)
		    ORDER BY base_index ASC";
	$sth = $dbh->prepare($sql);
	$sth->execute($b_i_array);
	$tableau_parents = ($sth) ? $sth->fetchAll(PDO::FETCH_ASSOC) : false;
	if ($sth) $sth->closeCursor();


	//liste des caracs correspondantes
	$table_carac = array();
	// Assurer que $b_i est bien un tableau
	$b_i_array = is_array($b_i) ? $b_i : explode(',', $b_i);
	$placeholders = implode(',', array_fill(0, count($b_i_array), '?'));
	$sql = "SELECT base_index, categorie, carac_valeur, carac, nom_carac, unite_carac, symbole_carac
		    FROM caracteristiques
		    JOIN carac ON carac_caracteristique_id = carac
		    JOIN base ON carac_id = base_index
		    WHERE base_index IN ($placeholders) AND carac != 0
		    ORDER BY base.base_index ASC, carac ASC";
	$sth = $dbh->prepare($sql);
	$sth->execute($b_i_array);

	$table_carac = ($sth) ? $sth->fetchAll(PDO::FETCH_ASSOC) : array();

	if ($sth) $sth->closeCursor();

	$tc=array(); $td_c=array();
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
	$tableau_entretien = array();
	// Assurer que $b_i est bien un tableau
	$b_i_array = is_array($b_i) ? $b_i : explode(',', $b_i);
	$placeholders = implode(',', array_fill(0, count($b_i_array), '?'));
	$sql = "SELECT e_id, e_index, e_frequence, e_lastdate, e_designation
		    FROM entretien
		    WHERE e_id IN ($placeholders)
		    ORDER BY e_index ASC";
	$sth = $dbh->prepare($sql);
	$sth->execute($b_i_array);
	$tableau_entretien = ($sth) ? $sth->fetchAll(PDO::FETCH_ASSOC) : array();

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
	$b_e_array = is_array($b_e) ? $b_e : explode(',', $b_e);
	$b_e = implode(',', $b_e_array);

	// Assurer que $b_e est bien une chaîne valide de valeurs pour IN
	$placeholders = implode(',', array_fill(0, count($b_e_array), '?'));

	$sql = "SELECT base_index, lab_id, categorie, reference, designation, sortie
		    FROM base
		    WHERE base_index IN ($placeholders)
		    ORDER BY base_index ASC";

	$sth = $dbh->prepare($sql);
	$sth->execute($b_e_array);

	$tableau_enfants = ($sth) ? $sth->fetchAll(PDO::FETCH_ASSOC) : FALSE;

	if ($sth) $sth->closeCursor();
} else {
	    $tableau_enfants = [];
}

/*#######################################################################
#          Si du matériel sorti est affiché, afficher état              #
#######################################################################*/
if ($IOT!="0") {
    $raison_sortie=array();
	$sth = $dbh->prepare("SELECT * FROM raison_sortie WHERE raison_sortie_index != 0");
	$sth->execute();
	$raison_sortie = ($sth) ? $sth->fetchAll(PDO::FETCH_ASSOC) : false;
	if ($sth) $sth->closeCursor();
    $display_raison_sortie=1;
}
else $display_raison_sortie=0;



// ███╗   ███╗ ██████╗ ██████╗ ██╗   ██╗██╗     ███████╗███████╗
// ████╗ ████║██╔═══██╗██╔══██╗██║   ██║██║     ██╔════╝██╔════╝
// ██╔████╔██║██║   ██║██║  ██║██║   ██║██║     █████╗  ███████╗
// ██║╚██╔╝██║██║   ██║██║  ██║██║   ██║██║     ██╔══╝  ╚════██║
// ██║ ╚═╝ ██║╚██████╔╝██████╔╝╚██████╔╝███████╗███████╗███████║
// ╚═╝     ╚═╝ ╚═════╝ ╚═════╝  ╚═════╝ ╚══════╝╚══════╝╚══════╝
$modules = [];
foreach ($SETTINGS_modules as $m) {
    $modules[] = include_module("./modules/{$m}/colonnes.php", $t ?? null);
}


// ████████╗ █████╗ ██████╗ ██╗     ███████╗ █████╗ ██╗   ██╗
// ╚══██╔══╝██╔══██╗██╔══██╗██║     ██╔════╝██╔══██╗██║   ██║
//    ██║   ███████║██████╔╝██║     █████╗  ███████║██║   ██║
//    ██║   ██╔══██║██╔══██╗██║     ██╔══╝  ██╔══██║██║   ██║
//    ██║   ██║  ██║██████╔╝███████╗███████╗██║  ██║╚██████╔╝
//    ╚═╝   ╚═╝  ╚═╝╚═════╝ ╚══════╝╚══════╝╚═╝  ╚═╝ ╚═════╝


echo "<table id=\"listing\">";

// ╔═╗╔╗╔╦╗╔═╗╔╦╗╔═╗  ╔╦╗╔═╗╔╗ ╦  ╔═╗╔═╗╦ ╦
// ║╣ ║║║║ ║╣  ║ ║╣    ║ ╠═╣╠╩╗║  ║╣ ╠═╣║ ║
// ╚═╝╝╚╝╩ ╚═╝ ╩ ╚═╝   ╩ ╩ ╩╚═╝╩═╝╚═╝╩ ╩╚═╝
echo "<thead>";
echo "<tr>";
	foreach ($modules as $module) {
		echo $module['thead'];
	}
	echo "<th>&nbsp;</th>";
echo "</tr>";
echo "</thead>";


// ╦  ╦╔═╗╔╗╔╔═╗╔═╗  ╔╦╗╔═╗  ╦═╗╔═╗╔═╗╦ ╦╦ ╔╦╗╔═╗╔╦╗╔═╗
// ║  ║║ ╦║║║║╣ ╚═╗   ║║║╣   ╠╦╝║╣ ╚═╗║ ║║  ║ ╠═╣ ║ ╚═╗
// ╩═╝╩╚═╝╝╚╝╚═╝╚═╝  ═╩╝╚═╝  ╩╚═╚═╝╚═╝╚═╝╩═╝╩ ╩ ╩ ╩ ╚═╝
foreach ($tableau as &$t) {
    echo "<tr>";
	foreach ($SETTINGS_modules as $m) {
		$data = include "./modules/{$m}/colonnes.php";
		echo $data['tbody'];
	}
                             
    // ********** Outils **********
    echo "<td>";
    echo "<span id=\"linkbox\" onclick=\"TINY.box.show({iframe:'duplicate.php?BASE={$database}&i={$t["base_index"]}',
    		width:440,height:750,closejs:function(){location.reload()}})\" title=\"Dupliquer cette entrée\">";
    echo "✚";
    echo "</span>";
    echo "</td>";

    echo "</tr>";

}

// ╔═╗╦╔═╗╔╦╗  ╔╦╗╔═╗  ╔═╗╔═╗╔═╗╔═╗
// ╠═╝║║╣  ║║   ║║║╣   ╠═╝╠═╣║ ╦║╣ 
// ╩  ╩╚═╝═╩╝  ═╩╝╚═╝  ╩  ╩ ╩╚═╝╚═╝
echo "<tfoot>";
echo "<tr>";
	foreach ($modules as $module) {
		echo $module['tfoot'];
	}
    echo "<th>&nbsp;</th>";
echo "</tr>";
echo "</tfoot>";

echo "</table>";



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
