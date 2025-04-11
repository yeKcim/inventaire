<?php
if (!isset($t) || !is_array($t)) return ['thead' => '', 'tbody' => '', 'tfoot' => ''];

// ╔═╗╔╗╔╔╦╗╔═╗╔╦╗╔═╗
// ║╣ ║║║ ║ ║╣  ║ ║╣ 
// ╚═╝╝╚╝ ╩ ╚═╝ ╩ ╚═╝
$th="<th style=\"background:#8AAA6D;\">Marque</th>
     <th style=\"background:#8AAA6D;\">Référence fabricant</th>
     <th style=\"background:#8AAA6D;\">Numéro de série</th>
     ";

// ╔═╗╔═╗╦  ╔═╗╔╗╔╔╗╔╔═╗╔═╗
// ║  ║ ║║  ║ ║║║║║║║║╣ ╚═╗
// ╚═╝╚═╝╩═╝╚═╝╝╚╝╝╚╝╚═╝╚═╝

$td="";

// ********** Marque  **********
$td.="<td>";
	$td.="<span id=\"linkbox\" onclick=\"TINY.box.show({iframe:'quick.php?BASE={$database}&i={$t["base_index"]}&quick_page=technique&quick_name=Technique',width:440,height:750,closejs:function(){location.reload()}})\" title=\"";
	if ($t["vendeur"]!="-") $td.="vendu par {$t["vendeur_nom"]}";
	$td.="\">";
	if ($t["marque"]!="") $td.= $t["marque_nom"]; else $td.="-";

	$td.="</span>";
$td.="</td>";

// ********** Référence **********
$td.="<td>";
	$td.=spanquick("technique",$t["base_index"]);
	if ($t["reference"]!="") $td.=$t["reference"];
	else $td.="-";
	$td.="</span>";
$td.="</td>";

// ********** Serial number **********
$td.="<td>";
	$td.=spanquick("technique",$t["base_index"]);
	if ($t["serial_number"]!="") $td.=$t["serial_number"]; else $td.="-";
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

