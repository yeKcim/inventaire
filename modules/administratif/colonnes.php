<?php
if (!isset($t) || !is_array($t)) return ['thead' => '', 'tbody' => '', 'tfoot' => ''];


// ╔═╗╔╗╔╔╦╗╔═╗╔╦╗╔═╗
// ║╣ ║║║ ║ ║╣  ║ ║╣ 
// ╚═╝╝╚╝ ╩ ╚═╝ ╩ ╚═╝
$th="<th style=\"background:#bab987;\">n° d’inventaire</th>
     <th style=\"background:#bab987;\">Achat</th>
     ";

// ╔═╗╔═╗╦  ╔═╗╔╗╔╔╗╔╔═╗╔═╗
// ║  ║ ║║  ║ ║║║║║║║║╣ ╚═╗
// ╚═╝╚═╝╩═╝╚═╝╝╚╝╝╚╝╚═╝╚═╝

$td="";

// ********** N° d’inventaire **********
$td.="<td>";
	$td.=spanquick("administratif",$t["base_index"]);
	if ($t["num_inventaire"]!="") $td.=$t["num_inventaire"]; else $td.="-";
	$td.="</span>";
$td.="</td>";

// ********** Achat **********
$td.="<td>";
	$td.=spanquick("administratif",$t["base_index"]);
	$td.="<span title=\"";
	if ($t["responsable_achat"]!="0") $td.="Par {$t["responsable_prenom"]} {$t["responsable_nom"]} ";
	if ($t["date_achat"]!="0000-00-00") {
		$td.="le {"; 
		$td.=dateformat($t["date_achat"],"fr");
	}
	$td.="\">";
	if ($t["prix"]!="0") { $td.="{$t["prix"]}€"; /*stat*/$prix_total=$prix_total+$t["prix"];/*endstat*/}
	if ($t["contrat"]!="0")$td.=" sur {$t["contrat_nom"]}";
	if ( ($t["prix"]=="0") && ($t["contrat"]=="0") ) $td.="-";
	$td.="</span>";
	$td.="</span>";

$td.="</td>";

        
        
        

// ╦═╗╔═╗╔╦╗╦ ╦╦═╗╔╗╔
// ╠╦╝║╣  ║ ║ ║╠╦╝║║║
// ╩╚═╚═╝ ╩ ╚═╝╩╚═╝╚╝
return [
    "thead" => $th,
    "tbody" => $td,
    "tfoot" => $th,
];


?>

