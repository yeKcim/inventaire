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



/*
██████╗  ██████╗ ███████╗███████╗██╗███████╗██████╗ ███████╗
██╔══██╗██╔═══██╗██╔════╝██╔════╝██║██╔════╝██╔══██╗██╔════╝
██║  ██║██║   ██║███████╗███████╗██║█████╗  ██████╔╝███████╗
██║  ██║██║   ██║╚════██║╚════██║██║██╔══╝  ██╔══██╗╚════██║
██████╔╝╚██████╔╝███████║███████║██║███████╗██║  ██║███████║
╚═════╝  ╚═════╝ ╚══════╝╚══════╝╚═╝╚══════╝╚═╝  ╚═╝╚══════╝
*/

function icone($file) {
    $info = new SplFileInfo($file);
    if ("mime-icons/".$info->getExtension().".png" != FALSE) {
        echo "<img src=\"mime-icons/".$info->getExtension().".png\" /> ";
    }
    else echo "<img src=\"mime-icons/unknown.png\" /> ";
}

function is_dir_empty($dir) {
    if (!is_readable($dir)) return NULL;
    $handle = opendir($dir);
    while (false !== ($entry = readdir($handle))) {
        if ($entry != "." && $entry != "..") return FALSE;
    }
    return TRUE;
}

function displayDir($i, $dir, $del=FALSE) {
    global $racine;
    $quick= ( isset($_GET["quick_page"]) ) ? "&quick_page=".$_GET["quick_page"]."&quick_name=".$_GET["quick_name"]."" : "";
    // Si le dossier n’existe pas, on le crée
    if (!file_exists("$racine$dir")) {
        mkdir("$racine$dir", 0775);
        echo "Dossier créé. ";
    }
    // Si le dossier est vide on l’indique
    if (is_dir_empty("$racine$dir")) {
        echo "Aucun fichier trouvé dans $dir";
    }
    // Si le dossier n’est pas vide on liste
    else {
        $files = scandir("$racine$dir");
        if ($files != FALSE) {
        echo "<ul>";
            foreach ($files as $f) {
                if (($f!=".")&&($f!="..")) {
                    echo "<li>";
                    icone($f);
                    echo "<a href=\"$dir$f\" target=\"_blank\">$f</a>";
                    echo " (".formatBytes(filesize("$dir$f"),"0")."o)";
                    if ($del) echo " <span id=\"linkbox\" onclick=\"TINY.box.show({url:'0_del_confirm.php?i=$i&f=".$racine."".$dir."".$f."".$quick."',width:280,height:110})\" title=\"supprimer ce fichier (".$f.")\">×</span>";
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
        global $categories;
        if ($t["sortie"]=="1") echo "<strike>";
        echo "<a href=\"info.php?i=".$t["base_index"]."\" title=\"#".$t["base_index"]."\" target=\"_blank\">";
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



$message_error_add="<p class=\"error_message\" id=\"disappear_delay\">Une erreur inconnue est survenue. L’entrée n’a pas été ajoutée.</p>";
$message_success_add="<p class=\"success_message\" id=\"disappear_delay\">L’entrée a été ajoutée à la base de donnée.</p>";

$message_error_modif="<p class=\"error_message\" id=\"disappear_delay\">Une erreur inconnue est survenue. L’entrée n’a pas été modifiée.</p>";
$message_success_modif="<p class=\"success_message\" id=\"disappear_delay\">L’entrée a été modifiée dans la base de donnée.</p>";

$message_error_del="<p class=\"error_message\" id=\"disappear_delay\">Une erreur inconnue est survenue. L’entrée n’a pas été supprimée.</p>";
$message_success_del="<p class=\"success_message\" id=\"disappear_delay\">L’entrée a été supprimée de la base de donnée.</p>";

?>
