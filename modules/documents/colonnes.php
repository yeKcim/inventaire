<?php
if (!isset($t) || !is_array($t)) return ['thead' => '', 'tbody' => '', 'tfoot' => ''];

// ╔═╗╔╗╔╔╦╗╔═╗╔╦╗╔═╗
// ║╣ ║║║ ║ ║╣  ║ ║╣ 
// ╚═╝╝╚╝ ╩ ╚═╝ ╩ ╚═╝
$th="<th style=\"background:#BA944D;\">Fichiers<br/>référence</th>
	 <th style=\"background:#BA944D;\">Fichiers<br/>entrée</th>
     ";

// ╔═╗╔═╗╦  ╔═╗╔╗╔╔╗╔╔═╗╔═╗
// ║  ║ ║║  ║ ║║║║║║║║╣ ╚═╗
// ╚═╝╚═╝╩═╝╚═╝╝╚╝╝╚╝╚═╝╚═╝

$td="";

		// ********** Fichiers référence **********
        $td.="<td>";
		    $m=str_replace('/', "_", $t["marque_nom"]);
		    $r=str_replace('/', "_", $t["reference"]);
		    $dir="files/$database/".$m."-".$r;
		        //$dir=str_replace("&", "&amp;", $dir);
		        $dir=str_replace("&", "amp", $dir);
		        $dir=str_replace(";", "semicolon", $dir);
		    if (file_exists("$racine$dir")) {
		        $ddir=display_dir_compact("$racine$dir");
		        if ($ddir) $td.=$ddir; else $nofiles=true;
		    }
		    else $nofiles=true;
		    $td.=spanquick("documents",$t["base_index"]);
		    if (isset($nofiles)) $td.="-</span>";
		    else $td.="+</span>";
        $td.="</td>";
      

        // ********** Fichiers entrée **********
        $td.="<td>";
		    $dir="files/$database/".$t["base_index"]."";
		    if (file_exists("$racine$dir")) {
		        $ddir=display_dir_compact("$racine$dir");
		        if ($ddir) $td.=$ddir; else $nofiles=true;
		    }
		    else $nofiles=true;
		    $td.=spanquick("documents",$t["base_index"]);
		    if (isset($nofiles)) $td.="-</span>";
		    else $td.="+</span>";
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

