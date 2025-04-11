<?php



if (!isset($th_c)) {
	$th_c="";
	if ($CAT!="") {
		// Si une seule catégorie est affichée on met les caractéristiques pertinentes dans un tableau
		$sth = $dbh->prepare("SELECT DISTINCT carac, nom_carac, unite_carac, symbole_carac 
				              FROM caracteristiques, carac, base 
				              WHERE carac_id = base_index 
				                AND carac_caracteristique_id = carac 
				                AND categorie = :cat 
				                AND carac != 0 
				              ORDER BY carac ASC");

		$sth->execute([':cat' => $CAT]);

		$carac_categorie = ($sth) ? $sth->fetchAll(PDO::FETCH_ASSOC) : false;

		if ($sth) $sth->closeCursor();

	  	$style="background-color:rgba(212, 224, 200, 0.45);";

			foreach ($carac_categorie as $cc) {
				//on ajoute une case dans th
				$th_c.="<th style=\"background:#8faaa4;vertical-align:top;\">";
				$th_c.="<span title=\"".$cc["nom_carac"]."\"><span style=\"color:#2e3436;\">".$cc["symbole_carac"]."</span>";
				if ($cc["unite_carac"]!="") $th_c.="<br/>(".$cc["unite_carac"].")";
				$th_c.="</th>";

				// Initialisation de la variable $val si elle n'est pas définie
				if (!isset($val)) {
					$val = [];
				}

				//on ajoute une case dans tr
				if (is_array($val)) {
					foreach ($val as $k => $v) {
						if (!isset($val[$k]["echo"])) {
						    $val[$k]["echo"] = "";
						}
						$val[$k]["echo"] .= "<td style=\"".$style."\">".spanquick("caracteristiques", $k);
						
						if (isset($v[$cc["carac"]])) {
						    //todo:on ajoute la valeur numérique en commentaire ou cachée
						    //$val[$k]["echo"] .= "<strike>".vnum($v[$cc["carac"]])."</strike> ";
						    $val[$k]["echo"] .= "<span style=\"display:none;\">".vnum($v[$cc["carac"]])."</span> ";
						    $val[$k]["echo"] .= $v[$cc["carac"]];
						} else {
						    $val[$k]["echo"] .= "-";
						}
						
						$val[$k]["echo"] .= "</span></td>";
					}
				}
		}
	}
}




// ╔═╗╔╗╔╔╦╗╔═╗╔╦╗╔═╗
// ║╣ ║║║ ║ ║╣  ║ ║╣ 
// ╚═╝╝╚╝ ╩ ╚═╝ ╩ ╚═╝
if ($th_c!="") $th=$th_c;
else $th="\n\n<th style=\"background:#8faaa4;\">Caractéristiques</th>\n\n";


// ╔═╗╔═╗╦  ╔═╗╔╗╔╔╗╔╔═╗╔═╗
// ║  ║ ║║  ║ ║║║║║║║║╣ ╚═╗
// ╚═╝╚═╝╩═╝╚═╝╝╚╝╝╚╝╚═╝╚═╝

$td="";
// ********** Caractéristiques **********
if ($CAT=="") {
    $td.="<td>";
    $td.=spanquick("caracteristiques",$t["base_index"]);

    if (array_key_exists($t["base_index"], $tc)) $td.=substr($tc[$t["base_index"]], 0, -2);
    else $td.="-";

    $td.="</span>";
}
elseif ($th_c=="") {
	$td.= "<td style=\"".$style."\">";
	$td.=spanquick("caracteristiques",$t["base_index"]);
	$td.="-</span></td>";
} else {
	if (isset($val[$t["base_index"]]["echo"])) $td.=$val[$t["base_index"]]["echo"];
	else {
		foreach ($carac_categorie as $c) { 
			$td.="<td style=\"".$style."\">";
			$td.=spanquick("caracteristiques",$t["base_index"]);
			$td.="-</span></td>";
		}
	}
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

