<?php

// ########### liens ▲ ▼ ###########
function orderbylink($id) {
    global $ALL_GET, $ORDER;
    if ($id=="lab_id") {
		if ($ORDER=="ORDER BY $id ASC")  echo "▲ "; else echo "<a href=?$ALL_GET&ORDER=ORDER%20BY%20length($id)%20ASC,%20$id%20ASC>▲</a> ";
		if ($ORDER=="ORDER BY $id DESC") echo "▼"; else echo "<a href=?$ALL_GET&ORDER=ORDER%20BY%20length($id)%20DESC,%20$id%20DESC>▼</a> ";
	}
    else {
		if ($ORDER=="ORDER BY $id ASC")  echo "▲ "; else echo "<a href=?$ALL_GET&ORDER=ORDER%20BY%20$id%20ASC>▲</a> ";
		if ($ORDER=="ORDER BY $id DESC") echo "▼"; else echo "<a href=?$ALL_GET&ORDER=ORDER%20BY%20$id%20DESC>▼</a> ";
	}
}

function dateformat($date, $to="fr") {
    if ($to=="en") {$date = explode('/',$date); $dateout="".$date[2]."-".$date[1]."-".$date[0]."";}
    else {$date = explode('-',$date); $dateout="".$date[2]."/".$date[1]."/".$date[0]."";}
    if ($dateout=="//") $dateout="";
    return $dateout;
}

function formatBytes($size, $precision = 2)
{   $base = log($size, 1024);
    $suffixes = array('', 'K', 'M', 'G', 'T');
    return round(pow(1024, $base - floor($base)), $precision) .' '. $suffixes[floor($base)];
}


// in_array only operates on a one dimensional array
function in_array_r($item , $array){
    return preg_match('/"'.$item.'"/i' , json_encode($array));
}

function phone_display($n, $display) {
    $char = array(" ", "&nbsp;", ".", "-", ",", " ", "_");
    if ($display=="") $n = str_replace($char, "", $n);
    else $n = wordwrap($n, 2, $display,1);
    return "$n";
}

/*
███████╗███████╗██╗     ███████╗ ██████╗████████╗
██╔════╝██╔════╝██║     ██╔════╝██╔════╝╚══██╔══╝
███████╗█████╗  ██║     █████╗  ██║        ██║
╚════██║██╔══╝  ██║     ██╔══╝  ██║        ██║
███████║███████╗███████╗███████╗╚██████╗   ██║
╚══════╝╚══════╝╚══════╝╚══════╝ ╚═════╝   ╚═╝
*/

function selecteur($nom, $table, $intitule, $A="0", $B="1", $complement="0",$complement_display="") {
    global $$nom;
    echo "<select name=\"$nom\" onchange=\"submit();\">";
    echo "<option value=\"\" "; if ($$nom=="") echo "selected"; echo ">— $intitule —</option>";
    foreach ($table as &$l){
        $selected= ($$nom==$l[$A]) ? "selected > $nom =" : " > " ;
        $complement_info= ( ($complement!=0)||(array_key_exists($complement, $l)) ) ? "$l[$complement]" : "";
	$c= ($complement_display!="") ? "($complement_info)" : "$complement_info";
        echo "<option value=\"$l[$A]\" $selected $l[$B] $c</option>";
    }
    echo "</select> ";
}


function selecteur_chosen($nom, $table, $intitule, $A="0", $B="1", $complement="0",$complement_display="") {
    global $$nom;
    echo "<select name=\"$nom\" onchange=\"submit();\" data-placeholder=\"Choose…\" class=\"chosen-select\" tabindex=\"0\" >";
    echo "<option value=\"\" "; if ($$nom=="") echo "selected"; echo ">— $intitule —</option>";
    foreach ($table as &$l){
        $selected= ($$nom==$l[$A]) ? "selected > $nom =" : " > " ;
        $complement_info= ( ($complement!=0)||(array_key_exists($complement, $l)) ) ? "$l[$complement]" : "";
        $c= ($complement_display!="") ? "($complement_info)" : "$complement_info";
        echo "<option value=\"$l[$A]\" $selected $l[$B] $c</option>";
    }
    echo "</select> ";
    echo "<script type=\"text/javascript\">
        var config = {
          '.chosen-select'           : {no_results_text:'Aucun résultat pour :', width:\"250px\"},
        }
        for (var selector in config) {
          $(selector).chosen(config[selector]);
        }
      </script>";

}



function option_selecteur($select, $table, $A="0", $B="1", $complement="0",$complement_display="") {
    foreach ($table as &$l){
        $selected= ($select==$l[$A]) ? "selected >" : " >" ;
	$complement_info= ( ($complement!=0)||(array_key_exists($complement, $l)) ) ? "$l[$complement]" : "" ;
	$c= ($complement_display!="") ? "($complement_info)" : "$complement_info";
        echo "<option value=\"$l[$A]\" $selected $l[$B] $c</option>";
    }
}


function echodatatables($tableid,$iDisplayLength="10") {
echo "<script type=\"text/javascript\" charset=\"utf8\" src=\"datatables/jquery.dataTables.min.js\"></script>\n";
echo " <script>\n";
  echo "\$(function(){\n";
    echo "\$(\"#".$tableid."\").dataTable({\n";
        echo "\"lengthMenu\": [5, 10, 25, 50 ],\n"; // ne fonctionne pas ?
//        echo "\"bStateSave\": true,\n";
//        echo "\"fnStateSave\": function (oSettings, oData) {\n";
//            echo "localStorage.setItem( 'DataTables', JSON.stringify(oData) );\n";
//        echo "},\n";
//        echo "\"fnStateLoad\": function (oSettings) {\n";
//            echo "return JSON.parse( localStorage.getItem('DataTables') );\n";
//        echo "},\n";
//        echo "\"iCookieDuration\": 60,\n"; // 2 minute
        echo "sPaginationType: \"two_button\",\n";
        echo "bPaginate: true,\n";
        echo "bLengthChange: true,\n";
        echo "iDisplayLength: ".$iDisplayLength.",\n";
        echo "aaSorting: [],\n";
    echo "});\n";
  echo "})\n";
  echo "</script>\n";
}


/*
██████╗  ██████╗ ███████╗███████╗██╗███████╗██████╗ ███████╗
██╔══██╗██╔═══██╗██╔════╝██╔════╝██║██╔════╝██╔══██╗██╔════╝
██║  ██║██║   ██║███████╗███████╗██║█████╗  ██████╔╝███████╗
██║  ██║██║   ██║╚════██║╚════██║██║██╔══╝  ██╔══██╗╚════██║
██████╔╝╚██████╔╝███████║███████║██║███████╗██║  ██║███████║
╚═════╝  ╚═════╝ ╚══════╝╚══════╝╚═╝╚══════╝╚═╝  ╚═╝╚══════╝
*/

function is_dir_empty($dir) {
    if (!is_readable($dir)) return NULL;
    $handle = opendir($dir);
    while (false !== ($entry = readdir($handle))) {
        if ($entry != "." && $entry != "..") return FALSE;
    }
    return TRUE;
}

function displayDir($database, $i, $dir, $del=FALSE) {
    global $racine;
    global $dossierdesfichiers;
    $quick= ( isset($_GET["quick_page"]) ) ? "&quick_page=".$_GET["quick_page"]."&quick_name=".$_GET["quick_name"]."" : "";
    // Si le dossier n’existe pas, on le crée
    if (!file_exists("$dir")) {
        mkdir("$dir", 0775);
        echo "Dossier ".str_replace($dossierdesfichiers, "", "$dir")." créé. ";
    }
    // Si le dossier est vide on l’indique
    if (is_dir_empty("$dir")) {
        echo "Aucun fichier trouvé dans ".str_replace($dossierdesfichiers, "", "$dir")."";
    }
    // Si le dossier n’est pas vide on liste
    else {
        $files = scandir("$dir");
        if ($files != FALSE) {
        $tableid=str_replace('/', "", $dir);
        echo "\n\n<table id=\"".$tableid."\">";
        echo "<thead><tr>";
        echo "<th width=\"5%\"><span title=\"Type\">.*</span></th>";
        echo "<th style=\"text-align:left;\">Fichier</th>";
        echo "<th width=\"15%\">Taille</th>";
        if ($del)  echo "<th width=\"10%\">Suppr.</th>";
        echo "</tr></thead>\n";
            foreach ($files as $f) {
                if (($f!=".")&&($f!="..")) {
                    echo "<tr>";
                    echo "<td><span style=\"display:none;\">".extension($f)."</span><span title=\"".extension($f)."\">".icone($f)."</span></td>";
                    echo "<td><a href=\"".str_replace($racine, "", "$dir$f")."\" target=\"_blank\">$f</a></td>";
                    echo "<td style=\"text-align:right;\"><span style=\"display:none;\">".filesize("$dir$f")."</span>".formatBytes(filesize("$dir$f"),"0")."o</td>";
                    if ($del) echo "<td style=\"text-align:center;\"><span id=\"linkbox\" onclick=\"TINY.box.show({url:'0_del_confirm.php?BASE=$database&i=$i&f=".$dir.$f."".$quick."',width:280,height:110})\" title=\"supprimer ce fichier (".$f.")\">×</span></td>";
                    echo"</tr>\n";
                }
            }
        echo "</table>";
        echodatatables($tableid,"5");
        }
    }
}


function extension($file) {
    $info = new SplFileInfo($file);
    return $info->getExtension();
}


function icone($f) {
    if ("mime-icons/".extension($f).".png" != FALSE) $r="<img src=\"mime-icons/".extension($f).".png\" /> ";
    else $r="<img src=\"mime-icons/unknown.png\" /> ";
    return $r;
}


function display_dir_compact($d) {
    global $database; global $racine;
    $dir=str_replace("$racine", "", $d);
    $r="";
    if (file_exists("$d")) {
        if ( ! is_dir_empty("$d")) {
            $files = scandir("$d");
            if ($files != FALSE) {
                foreach ($files as $f) {
                    if (($f!=".")&&($f!="..")) {
                        $r.="<a href=\"$dir/$f\" target=\"_blank\" title=\"".$f."\">";
                        $r.=icone($f);
                        $r.="</a> ";}
                }
            }
            else $r=false;
        }
        else $r=false;
    }
    return $r;
}






/*
███╗   ███╗ █████╗ ██╗  ██╗    ███████╗██╗███████╗███████╗    ██╗   ██╗██████╗ ██╗      ██████╗  █████╗ ██████╗
████╗ ████║██╔══██╗╚██╗██╔╝    ██╔════╝██║╚══███╔╝██╔════╝    ██║   ██║██╔══██╗██║     ██╔═══██╗██╔══██╗██╔══██╗
██╔████╔██║███████║ ╚███╔╝     ███████╗██║  ███╔╝ █████╗      ██║   ██║██████╔╝██║     ██║   ██║███████║██║  ██║
██║╚██╔╝██║██╔══██║ ██╔██╗     ╚════██║██║ ███╔╝  ██╔══╝      ██║   ██║██╔═══╝ ██║     ██║   ██║██╔══██║██║  ██║
██║ ╚═╝ ██║██║  ██║██╔╝ ██╗    ███████║██║███████╗███████╗    ╚██████╔╝██║     ███████╗╚██████╔╝██║  ██║██████╔╝
╚═╝     ╚═╝╚═╝  ╚═╝╚═╝  ╚═╝    ╚══════╝╚═╝╚══════╝╚══════╝     ╚═════╝ ╚═╝     ╚══════╝ ╚═════╝ ╚═╝  ╚═╝╚═════╝
 from drupal */

function file_upload_max_size() {
  static $max_size = -1;
  if ($max_size < 0) {
    // Start with post_max_size.
    $max_size = parse_size(ini_get('post_max_size'));
    // If upload_max_size is less, then reduce. Except if upload_max_size is
    // zero, which indicates no limit.
    $upload_max = parse_size(ini_get('upload_max_filesize'));
    if ($upload_max > 0 && $upload_max < $max_size) {
      $max_size = $upload_max;
    }
  }
  return $max_size;
}

function parse_size($size) {
  $unit = preg_replace('/[^bkmgtpezy]/i', '', $size); // Remove the non-unit characters from the size.
  $size = preg_replace('/[^0-9\.]/', '', $size); // Remove the non-numeric characters from the size.
  if ($unit) {
    // Find the position of the unit in the ordered string which is the power of magnitude to multiply a kilobyte by.
    return round($size * pow(1024, stripos('bkmgtpezy', $unit[0])));
  }
  else {
    return round($size);
  }
}

function new_lab_id($categorie) {
    global $dbh;
    // quelle est l’abbréviation de la catégorie ?
    $sth = $dbh->query("SELECT categorie_lettres FROM categorie WHERE categorie_index='".$categorie."' ;");
    $tabbr = ($sth) ? $sth->fetchAll(PDO::FETCH_ASSOC) : FALSE ;
    $abbr=$tabbr[0]["categorie_lettres"];
    // recherche du labid max
    $sth = $dbh->query("SELECT lab_id FROM base WHERE categorie='".$categorie."' ORDER BY lab_id ASC ;");
    $allid = ($sth) ? $sth->fetchAll(PDO::FETCH_ASSOC) : FALSE ;
    // on supprime les lettres des lab_id, on met les chiffres dans un tableau
    $allidnum=array();
    foreach ($allid as $a) array_push ( $allidnum, preg_replace('`[^0-9]`', '', $a["lab_id"]) );
    $newid=max($allidnum)+1;
    $new_lab_id="".$abbr."".$newid."";
    // TODO : Vérifier avant qu’aucune autre entrée ainsi nommée n’existe ! dans le cas d’un nommage manuel
    $new_lab_id = ($categorie==0) ? "" : $new_lab_id;
    return $new_lab_id;
}

function return_last_id($col,$table) {
    global $dbh;
    $sth = $dbh->query("SELECT $col FROM $table ORDER BY $col DESC LIMIT 1 ;");
    $t = ($sth) ? $sth->fetchAll(PDO::FETCH_ASSOC) : FALSE ;
    return $t[0][$col];
}

function quickdisplayincarac ($t) {
    global $categories; global $database;
    if ($t["sortie"]=="1") echo "<strike>";
    echo "<a href=\"info.php?BASE=$database&i=".$t["base_index"]."\" title=\"#".$t["base_index"]."\" target=\"_blank\">";
    echo $t["lab_id"];
    echo "</a>";
    if (isset($t["categorie"])) {
        $keys = array_keys(array_column($categories, 'categorie_index'), $t["categorie"]);
        if (isset($keys[0])) {
            echo "&nbsp;: ";
            echo "<abbr title=\"Catégorie&nbsp;: ".$categories[$keys[0]]["categorie_nom"]." [".$categories[$keys[0]]["categorie_lettres"]."] \">";
            echo $t["designation"];
            echo " </abbr>";
         }
    }
    else echo "&nbsp;: ".$t["designation"]." ";
    if (isset($t["reference"])) echo " {".$t["reference"]."} ";
    if ($t["sortie"]=="1") echo "</strike>";
}

function quickdisplaymini ($t) {
        global $categories;
        global $database;
        if ($t["sortie"]=="1") echo "<strike>";
        echo "<a href=\"info.php?BASE=$database&i=".$t["base_index"]."\" title=\"#".$t["base_index"]."";
        if (isset($t["designation"])) echo " ".$t["designation"]." ";
        if (isset($t["reference"])) echo "{".$t["reference"]."} ";
        echo "\" target=\"_blank\">";
        echo $t["lab_id"];
        echo "</a>";
        if ($t["sortie"]=="1") echo "</strike>";
}

function quickdisplayincarac_b ($t) {
    global $categories;
	$txt="";
        if ($t["sortie"]=="1") $txt.="<strike>";
        $txt.="<a href='info.php?BASE=$database&i=".$t["base_index"]."' title='#".$t["base_index"]."' target='_blank'>";
        $txt.=$t["lab_id"];
        $txt.="</a>";
        if (isset($t["categorie"])) {
            $keys = array_keys(array_column($categories, 'categorie_index'), $t["categorie"]);
            if (isset($keys[0])) {
                $txt.="&nbsp;: ";
                $txt.="<abbr title='Catégorie&nbsp;: ".$categories[$keys[0]]["categorie_nom"]." [".$categories[$keys[0]]["categorie_lettres"]."] '>";
                $txt.=$t["designation"];
                $txt.=" </abbr>";
             }
        }
        else $txt.="&nbsp;: ".$t["designation"]." ";
        if (isset($t["reference"])) $txt.=" {".$t["reference"]."} ";
        if ($t["sortie"]=="1") $txt.="</strike>";
	return $txt;
}

function spanquick($p,$i) {
  global $database;
  $s="<span id=\"linkbox\" ";
  $s.="onclick=\"TINY.box.show({iframe:'quick.php?BASE=$database&i=".$i."&quick_page=".$p."&quick_name=".$p."', width:440,height:750,closejs:function(){location.reload()}})\"";
  $s.="title=\"modification rapide ".$p."\">";
  return $s;
}

function vnum($s) {
    $last_char=substr($s, -1);
    $f_char=substr_replace($s, "", -1);
    $f_char=number_format($f_char);
    //if (is_numeric($f_char))
  switch ($last_char) { // https://fr.wikipedia.org/wiki/Pr%C3%A9fixes_du_Syst%C3%A8me_international_d%27unit%C3%A9s
    case "P": $v_num = $f_char*10^15;  break;
    case "T": $v_num = $f_char*10^12;  break;
    case "G": $v_num = $f_char*10^9;   break;
    case "M": $v_num = $f_char*10^6;   break;
    case "k": $v_num = $f_char*10^3;   break;
    case "c": $v_num = $f_char*10^-2;  break;
    case "m": $v_num = $f_char*10^-3;  break;
    case "µ": $v_num = $f_char*10^-6;  break;
    case "μ": $v_num = $f_char*10^-6;  break;
    case "n": $v_num = $f_char*10^-9;  break;
    case "p": $v_num = $f_char*10^-12; break;
    case "f": $v_num = $f_char*10^-15; break;
    default: $v_num=$s;
  }
  return $v_num;
}






$message_error_add="<p class=\"error_message\" id=\"disappear_delay\">Une erreur inconnue est survenue. L’entrée n’a pas été ajoutée.</p>";
$message_success_add="<p class=\"success_message\" id=\"disappear_delay\">L’entrée a été ajoutée à la base de donnée.</p>";

$message_error_modif="<p class=\"error_message\" id=\"disappear_delay\">Une erreur inconnue est survenue. L’entrée n’a pas été modifiée.</p>";
$message_success_modif="<p class=\"success_message\" id=\"disappear_delay\">L’entrée a été modifiée dans la base de donnée.</p>";

$message_error_del="<p class=\"error_message\" id=\"disappear_delay\">Une erreur inconnue est survenue. L’entrée n’a pas été supprimée.</p>";
$message_success_del="<p class=\"success_message\" id=\"disappear_delay\">L’entrée a été supprimée de la base de donnée.</p>";

?>
