<?php
$BASE= isset($_GET["BASE"]) ? htmlentities($_GET["BASE"]) : "" ;

echo "<form method=\"get\" action=\"?\">";

$nb_base=0;
$list_bases=array();
while( ( $db = $dbs->fetchColumn( 0 ) ) !== false )
{     if (strpos($db, $prefix) !== false ) {
        $list_bases[]="<option value=\"".str_replace($prefix, "", $db)."\">".str_replace($prefix, "", $db)."</option>";
        $first_base = ($nb_base==0) ? str_replace($prefix, "", $db) : $first_base ;
	$nb_base=$nb_base+1;
      }
}

if ($nb_base=="0") echo "Aucun inventaire détecté !";
elseif ($nb_base=="1") $database = ($BASE=="") ? $first_base : $BASE ;
//else $database = ($BASE=="") ? $first_base : $BASE ;

echo "<p style=\"text-align:center;\">";
echo "<select name=\"BASE\" onchange=\"submit();\" data-placeholder=\"Choose…\" class=\"chosen-select\" tabindex=\"0\">";
  echo "<option value=\"\">− Sélectionnez une base −</option>";
  foreach ($list_bases as $d) {
        echo str_replace("value=\"".str_replace($prefix, "", $database)."\">", "value=\"".str_replace($prefix, "", $database)."\" selected>", $d);
  }
echo "</select> ";

if ($i!="") echo "<input id=\"i\" name=\"i\" type=\"hidden\" value=\"$i\">";


echo "<a href=\"\" title=\"Ajouter une nouvelle base d’inventaire\" target=\"_blank\">+</a>";

echo "</p>";
echo "</form>";

?>

