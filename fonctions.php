<?php

// ########### liens ▲ ▼ ###########
function orderbylink($id) {
    global $ALL_GET, $ORDER;
    if ($ORDER=="ORDER BY $id ASC") echo "▲ "; else echo "<a href=?$ALL_GET&ORDER=ORDER%20BY%20$id%20ASC>▲</a> ";
    if ($ORDER=="ORDER BY $id DESC") echo "▼"; else echo "<a href=?$ALL_GET&ORDER=ORDER%20BY%20$id%20DESC>▼</a> ";
}

function dateformat($date, $to="fr") {
    if ($to=="en") {$date = explode('/',$date); $dateout="".$date[2]."-".$date[1]."-".$date[0]."";}
    else {$date = explode('-',$date); $dateout="".$date[2]."/".$date[1]."/".$date[0]."";}
    if ($dateout=="//") $dateout="";
    return $dateout;
    // TODO : Vérifier que la date en input est au bon format…
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

function selecteur($nom, $table, $intitule, $complement="0") {
    global $$nom;
    echo "<select name=\"$nom\" onchange=\"submit();\">";
    echo "<option value=\"\" "; if ($$nom=="") echo "selected"; echo ">— $intitule —</option>"; 
    foreach ($table as &$l){
        $selected= ($$nom==$l[0]) ? "selected > $nom =" : " > " ;
        $complement_info= ($complement==0) ? "" : "$l[$complement]" ;
        echo "<option value=\"$l[0]\" $selected $l[1] $complement_info</option>"; 
    }
    echo "</select> ";
}

function option_selecteur($select, $table, $complement="0") {
    foreach ($table as &$l){
        $selected= ($select==$l[0]) ? "selected >" : " >" ;
        $complement_info= ($complement==0) ? "" : "$l[$complement]" ;
        echo "<option value=\"$l[0]\" $selected $l[1] $complement_info</option>"; 
    }
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

function displayDir($dir, $del=FALSE) {
    $racine = "/var/www/";

    // Si le dossier n’existe pas, on le crée
    if (!file_exists("$racine$dir")) {
        mkdir("$racine$dir", 0775);
        echo "Dossier créé. ";
    }
    $tempi = explode("/", $dir);
    $i=$tempi[1];
    
    // Si le dossier est vide on l’indique
    if (is_dir_empty("$racine$dir")) {
        echo "Aucun fichier trouvé";
    }
    // Si le dossier n’est pas vide on liste
    else {
        $files = scandir("$racine$dir");
        if ($files != FALSE) {
        echo "<ul>";
            foreach ($files as $f) {
                if (($f!=".")&&($f!="..")) {
                    echo "<li>";
                    echo "<a href=\"$dir$f\" target=\"_blank\">$f</a>";
                    echo " (".formatBytes(filesize("$dir$f"),"0")."o)";
                    if ($del) echo " <span id=\"linkbox\" onclick=\"TINY.box.show({url:'del_confirm.php?i=$i&f=".$f."',width:280,height:110})\" title=\"supprimer ce fichier (".$f.")\">×</span>";
                    echo"</li>";
                }
            }
        echo "</ul>";
        }
    }

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
    // quelle est l’abbréviation de la catégorie ?
    $query_table_abbr = mysql_query ("SELECT categorie_lettres FROM categorie WHERE categorie_index='".$categorie."' ;");
    while ($l = mysql_fetch_row($query_table_abbr)) $abbr=$l[0];
    // recherche du labid max
    $allid=array();
    $query_table_labid = mysql_query ("SELECT lab_id FROM base WHERE categorie='".$categorie."' ORDER BY lab_id ASC ;");
    // on supprime les lettres des lab_id, on met les chiffres dans un tableau
    while ($lid = mysql_fetch_row($query_table_labid)) array_push ( $allid, preg_replace('`[^0-9]`', '', $lid[0]) ); 
    $newid=max($allid)+1;
    $new_lab_id="".$abbr."".$newid."";
    // TODO : Vérifier avant qu’aucune autre entrée ainsi nommée n’existe ! dans le cas d’un nommage manuel
    $new_lab_id = ($categorie==0) ? "" : $new_lab_id;
    return $new_lab_id;
}




?>
