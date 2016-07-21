<?php
/*
     ██╗ ██████╗ ██╗   ██╗██████╗ ███╗   ██╗ █████╗ ██╗     
     ██║██╔═══██╗██║   ██║██╔══██╗████╗  ██║██╔══██╗██║     
     ██║██║   ██║██║   ██║██████╔╝██╔██╗ ██║███████║██║     
██   ██║██║   ██║██║   ██║██╔══██╗██║╚██╗██║██╔══██║██║     
╚█████╔╝╚██████╔╝╚██████╔╝██║  ██║██║ ╚████║██║  ██║███████╗
 ╚════╝  ╚═════╝  ╚═════╝ ╚═╝  ╚═╝╚═╝  ╚═══╝╚═╝  ╚═╝╚══════╝
*/


/* ########### POST ########### */
$arr = array("add_historique","del_h_confirm","h");
foreach ($arr as &$value) {
    $$value= isset($_POST[$value]) ? htmlentities($_POST[$value]) : "" ;
}


/*
███╗   ███╗ ██████╗ ██████╗ ██╗███████╗    ███████╗ ██████╗ ██╗     
████╗ ████║██╔═══██╗██╔══██╗██║██╔════╝    ██╔════╝██╔═══██╗██║     
██╔████╔██║██║   ██║██║  ██║██║█████╗      ███████╗██║   ██║██║     
██║╚██╔╝██║██║   ██║██║  ██║██║██╔══╝      ╚════██║██║▄▄ ██║██║     
██║ ╚═╝ ██║╚██████╔╝██████╔╝██║██║██╗      ███████║╚██████╔╝███████╗
╚═╝     ╚═╝ ╚═════╝ ╚═════╝ ╚═╝╚═╝╚═╝      ╚══════╝ ╚══▀▀═╝ ╚══════╝
*/
// ajout d’une entrée dans l’historique
if ($add_historique=="Ajouter") {
    $arr = array("date_info", "histo");
    foreach ($arr as &$value) {
        $$value= isset($_POST[$value]) ? htmlentities($_POST[$value]) : "" ;
    }
    $query_do_i_insert_histo = mysql_query ("SELECT historique_index FROM historique WHERE historique_date=\"".dateformat($date_info,"en")."\" AND historique_texte=\"".$histo."\" AND historique_id=\"".$i."\";");
    if (!isset(mysql_fetch_row($query_do_i_insert_histo)[0]) ) {
        mysql_query ("INSERT INTO historique (historique_index, historique_date, historique_texte, historique_id) VALUES (NULL, \"".dateformat($date_info,"en")."\", \"".$histo."\", \"".$i."\"); ");
    }
}

// Suppression d’une entrée dans l’historique
if ($del_h_confirm=="Confirmer la suppression") {
    mysql_query ("DELETE FROM historique WHERE historique_index=$h AND historique_id=$i;");
    // TODO ajouter l’information effacée dans trash ? avec l’ip et l’heure ?
}


// Array historique
$query_table_historique = mysql_query ("SELECT * FROM historique WHERE historique_id=$i ORDER BY historique_date DESC, historique_index DESC ;");
$historique = array();
while ($l = mysql_fetch_row($query_table_historique)) {
    $historique[$l[0]]=array($l[0],$l[1],utf8_encode($l[2]));
}


/*
███████╗ ██████╗ ██████╗ ███╗   ███╗██╗   ██╗██╗      █████╗ ██╗██████╗ ███████╗
██╔════╝██╔═══██╗██╔══██╗████╗ ████║██║   ██║██║     ██╔══██╗██║██╔══██╗██╔════╝
█████╗  ██║   ██║██████╔╝██╔████╔██║██║   ██║██║     ███████║██║██████╔╝█████╗  
██╔══╝  ██║   ██║██╔══██╗██║╚██╔╝██║██║   ██║██║     ██╔══██║██║██╔══██╗██╔══╝  
██║     ╚██████╔╝██║  ██║██║ ╚═╝ ██║╚██████╔╝███████╗██║  ██║██║██║  ██║███████╗
╚═╝      ╚═════╝ ╚═╝  ╚═╝╚═╝     ╚═╝ ╚═════╝ ╚══════╝╚═╝  ╚═╝╚═╝╚═╝  ╚═╝╚══════╝
*/
echo "<div id=\"bloc\" style=\"background:#ad7fa8; vertical-align:top;\">";

    echo "<h1>Journal</h1>";
    
    if ($write) echo "<form method=\"post\" action=\"?i=$i\">";

    echo "<fieldset><legend>Nouvelle information</legend>";

        echo "<label for=\"date_info\">Date <abbr title=\"JJ/MM/AAAA\"><strong>ⓘ</strong></abbr>:</label>\n";
        echo "<input value=\"".date("d/m/Y")."\" name=\"date_info\" type=\"date\" id=\"date_info\"/>";
        
        echo "<label for=\"histo\" style=\"vertical-align: top;\"> Information :</label>\n";
        echo "<textarea name=\"histo\" rows=\"4\" cols=\"33\"></textarea><br/>";
        if ($write) {
            echo "<label for=\"add_historique\" > &nbsp;</label>\n";
            echo "<input name=\"add_historique\" value=\"Ajouter\" type=\"submit\" class=\"little_button\" />";
        }

    echo "</fieldset>";


    echo "<fieldset><legend>Historique - Remarques</legend>";


if ( empty($historique) ) echo "Aucune intervention spécifiée.";
else {
    echo "<table style=\"border:none;\">";
    foreach ($historique as $h) {
        echo "<tr>";
        echo "<td style=\"padding-right: 10px; vertical-align: top;\"><strong>".dateformat($h[1],"fr")."</strong></td>";
        echo "<td>".$h[2]."</td>";
        echo "<td style=\"text-align:right;\">";
        if ($write) echo "<span id=\"linkbox\" onclick=\"TINY.box.show({url:'del_confirm.php?i=$i&h=".$h[0]."',width:280,height:110})\" title=\"supprimer cette entrée (".$h[0].") du journal\">×</span>";
        else echo "&nbsp;";
        echo "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    }
    
    echo "</fieldset>";

    if ($write) echo "</form>";

echo "</div>";

?>
