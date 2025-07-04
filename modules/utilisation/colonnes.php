<?php
if (!isset($t) || !is_array($t)) return ['thead' => '', 'tbody' => '', 'tfoot' => ''];

global $IOT;

// ╔═╗╔╗╔╔╦╗╔═╗╔╦╗╔═╗
// ║╣ ║║║ ║ ║╣  ║ ║╣ 
// ╚═╝╝╚╝ ╩ ╚═╝ ╩ ╚═╝
$th="<th style=\"background:#96a5bc;\">Intégré à</th>
	 <th style=\"background:#96a5bc;\">Intègre</th>
	 <th style=\"background:#96a5bc;\">Localisation</th>";

if ($IOT!="0") { $th.="<th style=\"background:#96a5bc;\">État</th>"; }


// ╔═╗╔═╗╦  ╔═╗╔╗╔╔╗╔╔═╗╔═╗
// ║  ║ ║║  ║ ║║║║║║║║╣ ╚═╗
// ╚═╝╚═╝╩═╝╚═╝╝╚╝╝╚╝╚═╝╚═╝


$td="";
// ********** Intégré à **********
$td .= "<td>";
	if (isset($tableau_parents) && is_array($tableau_parents)) {
		$keys = array_keys(array_column($tableau_parents, 'integration'), $t["base_index"]);
	} else {
		$keys = [];
	}
	if ($t["integration"] != "0") {
		$td .= spanquick("utilisation", $t["base_index"]);
		$td .= "➡</span>&nbsp;";
		if (isset($tableau_enfants) && is_array($tableau_enfants)) {
		    $keys = array_keys(array_column($tableau_enfants, 'base_index'), $t["integration"]);
		    if (isset($keys[0])) $td.=quickdisplaymini($tableau_enfants[$keys[0]]);
		}
	} else {
		$td .= spanquick("utilisation", $t["base_index"]);
		$td .= "-</span>";
	}
$td .= "</td>";

// ********** Intègre **********
$td.="<td>";
	if (isset($tableau_parents) && is_array($tableau_parents)) {
		$keys = array_keys(array_column($tableau_parents, 'integration'), $t["base_index"]);
	} else {
		$keys = [];
	}
	// Intégration parent de
	if (array_key_exists("0", $keys)) {
		if (array_key_exists($keys[0], $tableau_parents)) {
			foreach ($keys as $k) {
	   			$td.="⬉&nbsp;";
	   			$td.=quickdisplaymini($tableau_parents[$k]);
	   			$td.="<br/>";
		   	}
		}
	}
	$td .= spanquick("utilisation", $t["base_index"]);
	$td .= "-</span>";
$td.="</td>";




// ********** Localisation **********
$td.="<td>";
	$td.=spanquick("utilisation",$t["base_index"]);
	if (array_key_exists("utilisateur", $t)) {
		if (isset($utilisateurs) && is_array($utilisateurs)) {
			$keys = array_keys(array_column($utilisateurs, 'utilisateur_index'), $t["utilisateur"]);
		} else {
			$keys = [];
		}
		if (array_key_exists("0",$keys)) $key=$keys[0]; else $key=null;
	}
	if ($t["utilisateur"] != 0 && $key != null) {
		$td.="<span title=\"Utilisé par {$utilisateurs[$key]["utilisateur_prenom"]} {$utilisateurs[$key]["utilisateur_nom"]} ";
	}
	else $td.="<span title=\"";
	if ($t["localisation"]!=0) {
		$td.="le ";
		$td.=dateformat($t["date_localisation"],"fr");
	}
	$td.="\">";
	$td.="{$t["localisation_batiment"]} {$t["localisation_piece"]}";
	$td.="</span>";
	$td.="</span>";
$td.="</td>";

// ********** État **********
if ($IOT!="0") {
    $td.="<td>";
    $td.=spanquick("utilisation",$t["base_index"]);
    $td.=$t["raison_sortie_nom"];
    $td.="</span>";
    $td.="</td>";
}

// ╦═╗╔═╗╔╦╗╦ ╦╦═╗╔╗╔
// ╠╦╝║╣  ║ ║ ║╠╦╝║║║
// ╩╚═╚═╝ ╩ ╚═╝╩╚═╝╚╝
return [
    "thead" => $th,
    "tbody" => $td,
    "tfoot" => $th,
];


?>

