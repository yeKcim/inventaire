<?php


// ╔═╗╔╗╔╔╦╗╔═╗╔╦╗╔═╗
// ║╣ ║║║ ║ ║╣  ║ ║╣ 
// ╚═╝╝╚╝ ╩ ╚═╝ ╩ ╚═╝

$th="\n\n
<th>Id Labo</th>
<th>Catégorie</th>
<th>Désignation</th>
\n\n";


// ╔═╗╔═╗╦  ╔═╗╔╗╔╔╗╔╔═╗╔═╗
// ║  ║ ║║  ║ ║║║║║║║║╣ ╚═╗
// ╚═╝╚═╝╩═╝╚═╝╝╚╝╝╚╝╚═╝╚═╝
$td="";
// ********** Id Labo **********
$td.="\n\n<td><a href=\"info.php?BASE={$database}&i={$t["base_index"]}\" title=\"#{$t["base_index"]}\" target=\"_blank\">";
$td.="<strong>";
if ($t["lab_id"]=="") $td.="#{$t["base_index"]}";
else {
    $td.="<span style=\"display:none;\">";
    $td.=preg_replace("/[^a-zA-Z]+/", "", $t["lab_id"]);
    $td.="-";
    $td.=sprintf( "%06d", preg_replace("/[^0-9]+/", "", $t["lab_id"]) );
    $td.="</span> ";
    $td.=$t["lab_id"];
}
$td.="</strong>";
$td.="</a></td>";

// ********** Catégorie **********
$td.="<td>";
if ($CAT=="") $td.="<a href=\"?BASE=$database&CAT={$t["categorie"]}\" style=\"color:#000;\" title=\"Afficher les entrées de la catégorie [{$t["categorie_lettres"]}]\">";
$td.=$t["categorie_nom"];
if ($CAT=="") $td.="</a>";
$td.="</td>";

// ********** Désignation **********
$td.="<td>";
$td.=spanquick("administratif",$t["base_index"]);
if ($t["designation"]!="") $td.=$t["designation"];
else $td.="-";
$td.="</span>";
$td.="</td>\n\n";


// ╦═╗╔═╗╔╦╗╦ ╦╦═╗╔╗╔
// ╠╦╝║╣  ║ ║ ║╠╦╝║║║
// ╩╚═╚═╝ ╩ ╚═╝╩╚═╝╚╝
return [
    "thead" => $th,
    "tbody" => $td,
    "tfoot" => $th,
];











?>

