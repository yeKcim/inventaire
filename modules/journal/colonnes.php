<?php
if (!isset($t) || !is_array($t)) return ['thead' => '', 'tbody' => '', 'tfoot' => ''];

// ╔═╗╔╗╔╔╦╗╔═╗╔╦╗╔═╗
// ║╣ ║║║ ║ ║╣  ║ ║╣ 
// ╚═╝╝╚╝ ╩ ╚═╝ ╩ ╚═╝
$th="<th style=\"background:#a786a2;\">Journal</th>
     ";

// ╔═╗╔═╗╦  ╔═╗╔╗╔╔╗╔╔═╗╔═╗
// ║  ║ ║║  ║ ║║║║║║║║╣ ╚═╗
// ╚═╝╚═╝╩═╝╚═╝╝╚╝╝╚╝╚═╝╚═╝

$td="";
// ********** Journal **********
$td .= "<td>";
if (is_array($t) && array_key_exists("base_index", $t)) {
    $td .= spanquick("journal", $t["base_index"]);

    if (isset($tableau_journaux) && is_array($tableau_journaux)) {
        $keys = array_keys(array_column($tableau_journaux, 'historique_id'), $t["base_index"]);
        if (isset($keys[0])) {
            $td .= "<sup>{$tableau_journaux[$keys[0]]["nb_entree"]}</sup> <img src=\"mime-icons/txt.png\" />";
        } else {
            $td .= "-";
        }
    } else {
        $td .= "-";
    }

} else {
    $td .= "-</span>";
}
$td .= "</td>";





// ╦═╗╔═╗╔╦╗╦ ╦╦═╗╔╗╔
// ╠╦╝║╣  ║ ║ ║╠╦╝║║║
// ╩╚═╚═╝ ╩ ╚═╝╩╚═╝╚╝
return [
    "thead" => $th,
    "tbody" => $td,
    "tfoot" => $th,
];


?>

