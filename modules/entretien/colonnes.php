<?php
if (!isset($t) || !is_array($t)) return ['thead' => '', 'tbody' => '', 'tfoot' => ''];

// ╔═╗╔╗╔╔╦╗╔═╗╔╦╗╔═╗
// ║╣ ║║║ ║ ║╣  ║ ║╣ 
// ╚═╝╝╚╝ ╩ ╚═╝ ╩ ╚═╝
$th="<th style=\"background:#c19aaa;\">Entretiens</th>
     ";

// ╔═╗╔═╗╦  ╔═╗╔╗╔╔╗╔╔═╗╔═╗
// ║  ║ ║║  ║ ║║║║║║║║╣ ╚═╗
// ╚═╝╚═╝╩═╝╚═╝╝╚╝╝╚╝╚═╝╚═╝

$td="";

// ********** Entretiens **********
$td.="<td>";
	$td.=spanquick("entretien",$t["base_index"]);
	
	if (  isset($te) && is_array($te) && array_key_exists($t["base_index"], $te)  ) {
		$td.=$te[$t["base_index"]];
	} else $td.="-";

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

