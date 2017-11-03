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
    $sth = $dbh->query("SELECT historique_index FROM historique WHERE historique_date=\"".$date_info."\" AND historique_texte=\"".$histo."\" AND historique_id=\"".$i."\";");
    $query_do_i_insert_histo = ($sth) ? $sth->fetchAll(PDO::FETCH_ASSOC) : FALSE ;

    if (!isset($query_do_i_insert_histo[0]) ) {
	$sth = $dbh->query("INSERT INTO historique (historique_index, historique_date, historique_texte, historique_id) VALUES (NULL, \"".$date_info."\", \"".$histo."\", \"".$i."\");");
    }
    else {/* TODO: écrire un message comme quoi l’entrée était déjà dans la base donc n’a pas été ajoutée*/}
}

// Suppression d’une entrée dans l’historique
if ($del_h_confirm=="Confirmer la suppression") {
    $delcount = $dbh->exec("DELETE FROM historique WHERE historique_index=$h AND historique_id=$i;");
}


/*
 █████╗ ██████╗ ██████╗  █████╗ ██╗   ██╗
██╔══██╗██╔══██╗██╔══██╗██╔══██╗╚██╗ ██╔╝
███████║██████╔╝██████╔╝███████║ ╚████╔╝
██╔══██║██╔══██╗██╔══██╗██╔══██║  ╚██╔╝
██║  ██║██║  ██║██║  ██║██║  ██║   ██║
╚═╝  ╚═╝╚═╝  ╚═╝╚═╝  ╚═╝╚═╝  ╚═╝   ╚═╝
*/
// historique
$sth = $dbh->query("SELECT * FROM historique WHERE historique_id=$i ORDER BY historique_date DESC, historique_index DESC ;");
$historique = ($sth) ? $sth->fetchAll(PDO::FETCH_ASSOC) : FALSE ;



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

    $quick= ( isset($_GET["quick_page"]) ) ? "&quick_page=".$_GET["quick_page"]."&quick_name=".$_GET["quick_name"]."" : "";
    if ($write) echo "<form method=\"post\" action=\"?i=".$i."".$quick."\">";

    echo "<fieldset><legend>Nouvelle information</legend>";

        echo "<label for=\"date_info\">Date <abbr title=\"si aucun calendrier n’aide à la saisie : YYYY-MM-DD\"><strong>ⓘ</strong></abbr> :</label>\n";
        echo "<input value=\"".date("Y-m-d")."\" name=\"date_info\" type=\"date\" id=\"date_info\"/><br/>";

        echo "<label for=\"histo\" style=\"vertical-align: top;\"> Information :</label>\n";
        echo "<textarea name=\"histo\" rows=\"4\" cols=\"33\"></textarea><br/>";
        if ($write) {
            echo "<label for=\"add_historique\" > &nbsp;</label>\n";
            echo "<input name=\"add_historique\" value=\"Ajouter\" type=\"submit\" class=\"little_button\" />";
        }

    echo "</fieldset>";


    echo "<fieldset><legend>Historique - Remarques</legend>";


if ( empty($historique[0]) ) echo "Aucune intervention spécifiée.";
else {
    echo "<table style=\"border:none;\">";
    foreach ($historique as $h) {
        echo "<tr>";
        echo "<td style=\"padding-right: 10px; vertical-align: top;\"><strong>".dateformat($h["historique_date"],"fr")."</strong></td>";
        echo "<td>".$h["historique_texte"]."</td>";
        echo "<td style=\"text-align:right;\">";
        if ($write) echo "<span id=\"linkbox\" onclick=\"TINY.box.show({url:'0_del_confirm.php?i=$i&h=".$h["historique_index"]."".$quick."',width:280,height:110})\" title=\"supprimer cette entrée (".$h["historique_index"].") du journal\">×</span>";
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
