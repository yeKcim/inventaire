<?php

/*
████████╗███████╗ ██████╗██╗  ██╗███╗   ██╗██╗ ██████╗ ██╗   ██╗███████╗
╚══██╔══╝██╔════╝██╔════╝██║  ██║████╗  ██║██║██╔═══██╗██║   ██║██╔════╝
   ██║   █████╗  ██║     ███████║██╔██╗ ██║██║██║   ██║██║   ██║█████╗
   ██║   ██╔══╝  ██║     ██╔══██║██║╚██╗██║██║██║▄▄ ██║██║   ██║██╔══╝
   ██║   ███████╗╚██████╗██║  ██║██║ ╚████║██║╚██████╔╝╚██████╔╝███████╗
   ╚═╝   ╚══════╝ ╚═════╝╚═╝  ╚═╝╚═╝  ╚═══╝╚═╝ ╚══▀▀═╝  ╚═════╝ ╚══════╝
*/

$message="";

/*
 █████╗ ██████╗ ██████╗  █████╗ ██╗   ██╗
██╔══██╗██╔══██╗██╔══██╗██╔══██╗╚██╗ ██╔╝
███████║██████╔╝██████╔╝███████║ ╚████╔╝
██╔══██║██╔══██╗██╔══██╗██╔══██║  ╚██╔╝
██║  ██║██║  ██║██║  ██║██║  ██║   ██║
╚═╝  ╚═╝╚═╝  ╚═╝╚═╝  ╚═╝╚═╝  ╚═╝   ╚═╝
*/
// tags
$sth = $dbh->query("SELECT * FROM tags_list WHERE tags_list_index!=0 ORDER BY tags_list_nom ASC ;");
$tags = $sth->fetchAll(PDO::FETCH_ASSOC);

if (!isset($fieldset_tags)) {
	// tags_i les tags de $i
	$sth = $dbh->query("SELECT tags_index FROM tags WHERE tags_id=$i ;");
	$tags = $sth->fetchAll(PDO::FETCH_ASSOC);
}

if (!isset($fieldset_compatibilite)) {
	// compatibilité de $i
	$sth = $dbh->query("SELECT * FROM compatibilite WHERE compatib_id1=\"$i\" OR compatib_id2=\"$i\" ;");
	$compatibilite = $sth->fetchAll(PDO::FETCH_ASSOC);
}

// tous les lab_id classé par catégorie
$sth = $dbh->query("SELECT base_index, lab_id, categorie, categorie_lettres, categorie_nom FROM base, categorie WHERE categorie=categorie_index ORDER BY categorie_nom ASC ;");
$labids_cat = $sth->fetchAll(PDO::FETCH_ASSOC);


/*
███╗   ███╗ ██████╗ ██████╗ ██╗███████╗    ███████╗ ██████╗ ██╗
████╗ ████║██╔═══██╗██╔══██╗██║██╔════╝    ██╔════╝██╔═══██╗██║
██╔████╔██║██║   ██║██║  ██║██║█████╗      ███████╗██║   ██║██║
██║╚██╔╝██║██║   ██║██║  ██║██║██╔══╝      ╚════██║██║▄▄ ██║██║
██║ ╚═╝ ██║╚██████╔╝██████╔╝██║██║██╗      ███████║╚██████╔╝███████╗
╚═╝     ╚═╝ ╚═════╝ ╚═════╝ ╚═╝╚═╝╚═╝      ╚══════╝ ╚══▀▀═╝ ╚══════╝
*/
if ( isset($_POST["technique_valid"]) ) {

    $arr = array("categorie", "plus_categorie_nom", "plus_categorie_abbr", "lab_id", "id_man", "marque", "plus_marque", "plus_marque_nom", "reference", "serial_number", "plus_tags");
    foreach ($arr as &$value) {
        $$value= isset($_POST[$value]) ? htmlentities($_POST[$value]) : "" ;
    }


/*  ╔═╗╔═╗╔╦╗╔═╗╔═╗╔╦╗╦╔╗ ╦  ╦╔╦╗╔═╗╔═╗
    ║  ║ ║║║║╠═╝╠═╣ ║ ║╠╩╗║  ║ ║ ║╣ ╚═╗
    ╚═╝╚═╝╩ ╩╩  ╩ ╩ ╩ ╩╚═╝╩═╝╩ ╩ ╚═╝╚═╝ */
    // Supprimer tous les compatibilités de cette entrée pour réinitialiser
    $delcount = $dbh->exec("DELETE FROM compatibilite WHERE compatib_id1=$i OR compatib_id2=$i ;");

    // Ajout des compatibilités
    if (isset($_POST["compatibilite"])) {
        $allc="";
        foreach ($_POST["compatibilite"] as $c) $allc.= "(".$c[0].",$i),";
        $allc=substr($allc, 0, -1); // suppression du dernier caractère
	$sth = $dbh->query("INSERT INTO compatibilite (compatib_id1, compatib_id2) VALUES $allc ; ");
    }
    // refaire compatibilité de $i
    $sth = $dbh->query("SELECT * FROM compatibilite WHERE compatib_id1=\"$i\" OR compatib_id2=\"$i\" ;");
    $compatibilite_query = $sth->fetchAll(PDO::FETCH_ASSOC);
    $compatibilite = array();
    foreach ($compatibilite_query as $c) {
    	$compatibilite[]= ($l["compatib_id1"]==$i) ? $l["compatib_id2"] : $l["compatib_id1"] ;
    }

/*  ╔╦╗╔═╗╔═╗╔═╗
     ║ ╠═╣║ ╦╚═╗
     ╩ ╩ ╩╚═╝╚═╝    */
    // Supprimer tous les tags de cette entrée pour réinitialiser
    $delcount = $dbh->exec("DELETE FROM tags WHERE tags_id=$i;");

    // Nouveaux tags
    if ($plus_tags!="") {
        // TODO : une page administration permettant de supprimer des tags ou d’en fusionner,…
        // Nouveaux tags dans tags_list
        $new_tag = explode(',',$plus_tags);
        $allnewtags=""; $allnewtagscomma="";

        foreach ($new_tag as &$nt) {
            $nt= ($nt[0]!=" ") ? $nt : substr($nt,1) ; // supp premier caractère si c’est un espace
            if ( in_array_r($nt,$tags) ) $allnewtagscomma.= "'$nt',"; // Recherche si le tag est déjà dans $tags
            else { // si le tag n’existe pas déjà, on l’ajoute dans la liste des tags à créer
                $allnewtags.= "(NULL,'$nt'),";
                $allnewtagscomma.= "'$nt',";
            }
        }
        $allnewtags=substr($allnewtags, 0, -1); // suppression du dernier caractère
        $allnewtagscomma=substr($allnewtagscomma, 0, -1); // suppression du dernier caractère
        if ($allnewtagscomma!="") {
		$sth = $dbh->query("INSERT INTO tags_list (tags_list_index, tags_list_nom) VALUES $allnewtags ;");
	        // Nouveaux tags dans tags de $i
	        $allnewtags_index="";
        	// tagnew_i les tags de $i
		$sth = $dbh->query("SELECT tags_list_index FROM tags_list WHERE tags_list_nom IN ($allnewtagscomma) ;");
		$table_tagnew_i = $sth->fetchAll(PDO::FETCH_ASSOC);
		foreach ($table_tagnew_i as $nt) $allnewtags_index.= "('".$nt["tags_list_index"]."','$i')," ;
	        $allnewtags_index=substr($allnewtags_index, 0, -1); // suppression du dernier caractère
		$sth = $dbh->query("INSERT INTO tags (tags_index, tags_id) VALUES $allnewtags_index ;");
        }
    }


    // Ajout des tags
    if (isset($_POST["tags"])) {
        $allt="";
        foreach ($_POST["tags"] as $ta) $allt.= "(".$ta.",$i),";
        $allt=substr($allt, 0, -1); // suppression du dernier caractère
	$sth = $dbh->query("INSERT INTO tags (tags_index, tags_id) VALUES $allt ;");

    }

    // refaire tags et tags de $i avant affichage
    // $tags_list
    $sth = $dbh->query("SELECT * FROM tags_list WHERE tags_list_index!=0 ORDER BY tags_list_nom ASC ;");
    $tags = $sth->fetchAll(PDO::FETCH_ASSOC);

    // tags_i les tags de $i
    $sth = $dbh->query("SELECT tags_index FROM tags WHERE tags_id=$i ;");
    $tags_i = $sth->fetchAll(PDO::FETCH_ASSOC);


/*  ╔╗╔╔═╗╦ ╦╦  ╦╔═╗╦  ╦  ╔═╗  ╔╦╗╔═╗╦═╗╔═╗ ╦ ╦╔═╗
    ║║║║ ║║ ║╚╗╔╝║╣ ║  ║  ║╣   ║║║╠═╣╠╦╝║═╬╗║ ║║╣
    ╝╚╝╚═╝╚═╝ ╚╝ ╚═╝╩═╝╩═╝╚═╝  ╩ ╩╩ ╩╩╚═╚═╝╚╚═╝╚═╝  */
    if ($marque=="plus_marque") {
	$sth = $dbh->query("INSERT INTO marque (marque_nom) VALUES ('".$plus_marque_nom."') ;");
        /* TODO : prévoir le cas où la marque existe déjà */
	$marque=return_last_id("marque_index","marque");

        // on ajoute cette entrée dans le tableau des marques (utilisé pour le select)
	array_push($marques, array("marque_index" => $marque, "marque_nom" => $plus_marque_nom ) );
    }


/*  ╔╗╔╔═╗╦ ╦╦  ╦╔═╗╦  ╦  ╔═╗  ╔═╗╔═╗╔╦╗╔═╗╔═╗╔═╗╦═╗╦╔═╗
    ║║║║ ║║ ║╚╗╔╝║╣ ║  ║  ║╣   ║  ╠═╣ ║ ║╣ ║ ╦║ ║╠╦╝║║╣
    ╝╚╝╚═╝╚═╝ ╚╝ ╚═╝╩═╝╩═╝╚═╝  ╚═╝╩ ╩ ╩ ╚═╝╚═╝╚═╝╩╚═╩╚═╝    */
    if ($categorie=="plus_categorie") {
	$sth = $dbh->query("INSERT INTO categorie (categorie_lettres, categorie_nom) VALUES (\"".$plus_categorie_abbr."\",\"".$plus_categorie_nom."\") ;");
        /* TODO : prévoir le cas où le vendeur existe déjà */
	$categorie=return_last_id("categorie_index","categorie");

        // on ajoute cette entrée dans le tableau des catégories (utilisé pour le select)
	array_push($categories, array("categorie_index" => $categorie, "categorie_lettres" => $plus_categorie_nom, "categorie_nom" => $plus_categorie_abbr ) );
        // TODO Attention l’abréviation ne doit contenir que des lettres !
    }



/*  ╦  ╔═╗╔╗  ╦╔╦╗
    ║  ╠═╣╠╩╗ ║ ║║
    ╩═╝╩ ╩╚═╝┘╩═╩╝  */
    // Si on change la catégorie, il est nécessaire de changer également le lab_id !
    if ($data[0]["categorie"]!=$categorie) $data[0]["lab_id"]=new_lab_id($categorie);


/*  ╦ ╦╔═╗╔╦╗╔═╗╔╦╗╔═╗  ╔═╗╔═╗ ╦    ╔═╗ ╦ ╦╔═╗╦═╗╦ ╦
    ║ ║╠═╝ ║║╠═╣ ║ ║╣   ╚═╗║═╬╗║    ║═╬╗║ ║║╣ ╠╦╝╚╦╝
    ╚═╝╩  ═╩╝╩ ╩ ╩ ╚═╝  ╚═╝╚═╝╚╩═╝  ╚═╝╚╚═╝╚═╝╩╚═ ╩     */
    $modif_result = $dbh->query("UPDATE base SET marque='".$marque."', reference='".$reference."', serial_number='".$serial_number."', categorie='".$categorie."', lab_id='".$lab_id."' WHERE base.base_index = $i;");
    $message.= (!isset($modif_result)) ? $message_error_modif : $message_success_modif;

    // Avant d’afficher on doit ajouter les nouvelles infos dans les array concernés…
    $data[0]["marque"]=$marque;
    $data[0]["serial_number"]=$serial_number;
    $data[0]["reference"]=$reference;
    $data[0]["categorie"]=$categorie;

}


/*
███████╗ ██████╗ ██████╗ ███╗   ███╗██╗   ██╗██╗      █████╗ ██╗██████╗ ███████╗
██╔════╝██╔═══██╗██╔══██╗████╗ ████║██║   ██║██║     ██╔══██╗██║██╔══██╗██╔════╝
█████╗  ██║   ██║██████╔╝██╔████╔██║██║   ██║██║     ███████║██║██████╔╝█████╗
██╔══╝  ██║   ██║██╔══██╗██║╚██╔╝██║██║   ██║██║     ██╔══██║██║██╔══██╗██╔══╝
██║     ╚██████╔╝██║  ██║██║ ╚═╝ ██║╚██████╔╝███████╗██║  ██║██║██║  ██║███████╗
╚═╝      ╚═════╝ ╚═╝  ╚═╝╚═╝     ╚═╝ ╚═════╝ ╚══════╝╚═╝  ╚═╝╚═╝╚═╝  ╚═╝╚══════╝
*/
echo "<div id=\"bloc\" style=\"background:#b4e287; vertical-align:top;\">";

    echo "<h1>Technique</h1>";

    echo $message;

    $quick= ( isset($_GET["quick_page"]) ) ? "&quick_page=".$_GET["quick_page"]."&quick_name=".$_GET["quick_name"]."" : "";
    if ($write) echo "<form method=\"post\" action=\"?i=".$i."".$quick."\">";

/*  ╦═╗╔═╗╔═╗╔═╗╦═╗╔═╗╔╗╔╔═╗╔═╗  ╦╔╗╔╦╗╔═╗╦═╗╔╗╔╔═╗
    ╠╦╝║╣ ╠╣ ║╣ ╠╦╝║╣ ║║║║  ║╣   ║║║║║ ║╣ ╠╦╝║║║║╣
    ╩╚═╚═╝╚  ╚═╝╩╚═╚═╝╝╚╝╚═╝╚═╝  ╩╝╚╝╩ ╚═╝╩╚═╝╚╝╚═╝    */
    echo "<fieldset><legend>Référence interne</legend>";

        /* ########### categorie ########### */
        echo "<label for=\"categorie\">Catégorie : </label>\n";
        echo "<select name=\"categorie\" onchange=\"display(this,'plus_categorie','plus_categorie');\" id=\"categorie\">";
        echo "<option value=\"0\" "; if ($data[0]["categorie"]=="0") echo "selected"; echo ">— Aucune catégorie spécifiée —</option>";
        option_selecteur($data[0]["categorie"], $categories, "categorie_index", "categorie_nom", "categorie_lettres", "display()");
        echo "<option value=\"plus_categorie\" "; if ($data[0]["categorie"]=="plus_categorie") echo "selected"; echo ">Nouvelle catégorie :</option>";
        echo "</select><br/>";

            /* ########### + categorie ########### */
            echo "\n\n\n";
            echo "<fieldset id=\"plus_categorie\" class=\"subfield\" style=\"display: none;\"><legend class=\"subfield\">Nouvelle Catégorie</legend>";
                echo "<label for=\"plus_categorie_nom\">Nom :</label>\n";
                echo "<input value=\"\" name=\"plus_categorie_nom\" type=\"text\"><br/>\n";
                echo "<label for=\"plus_categorie_abbr\">Abbréviation <abbr title=\"4 caractères max, pas de chiffres\"><strong>ⓘ</strong></abbr> :</label>\n";
                echo "<input value=\"\" name=\"plus_categorie_abbr\" type=\"text\" maxlength=\"4\">\n";
            echo "</fieldset>";
            echo "\n\n\n";

        /* ########### lab_id ########### */
        echo "<label for=\"lab_id\">";

        echo "Identifiant labo :</label>\n";

	echo "<select name=\"lab_id\" onchange=\"display(this,'manual_id','manual_id');\" id=\"lab_id\">";
		echo "<option value=\"".$data[0]["lab_id"]."\" ";
			if ($lab_id==$data[0]["lab_id"]) echo "selected";	echo ">";
			if (isset($fieldset_tags)) echo "Auto"; else echo $data[0]["lab_id"];
		echo "</option>";
		echo "<option value=\"manual_id\">Manuel (non fonctionnel)</option>";
        echo "</select><br/>";

        /* ########### + id_manuel ########### */
        echo "\n\n\n";
        echo "<fieldset id=\"manual_id\" class=\"subfield\" style=\"display: none;\"><legend class=\"subfield\">Id Manuel</legend>";
            echo "<label for=\"id_man\">Id :</label>\n";	echo "<input value=\"\" name=\"id_man\" type=\"text\">\n";
        echo "</fieldset>";
        echo "\n\n\n";
        echo "<br/>";

    echo "</fieldset>";

/*  ╦═╗╔═╗╔═╗╔═╗╦═╗╔═╗╔╗╔╔═╗╔═╗  ╔═╗╔═╗╔╗╔╔═╗╔╦╗╦═╗╦ ╦╔═╗╔╦╗╔═╗╦ ╦╦═╗
    ╠╦╝║╣ ╠╣ ║╣ ╠╦╝║╣ ║║║║  ║╣   ║  ║ ║║║║╚═╗ ║ ╠╦╝║ ║║   ║ ║╣ ║ ║╠╦╝
    ╩╚═╚═╝╚  ╚═╝╩╚═╚═╝╝╚╝╚═╝╚═╝  ╚═╝╚═╝╝╚╝╚═╝ ╩ ╩╚═╚═╝╚═╝ ╩ ╚═╝╚═╝╩╚═   */
    echo "<fieldset><legend>Références Constructeur</legend>";

        /* ########### marque ########### */
        echo "<label for=\"marque\">Marque : </label>\n";
        echo "<select name=\"marque\" onchange=\"display(this,'plus_marque','plus_marque');\" id=\"marque\">";
        echo "<option value=\"0\" "; if ($data[0]["marque"]=="0") echo "selected"; echo ">— Aucune marque spécifiée —</option>"; 
        option_selecteur($data[0]["marque"], $marques, "marque_index", "marque_nom");
        echo "<option value=\"plus_marque\" "; if ($data[0]["marque"]=="plus_marque") echo "selected"; echo ">Nouvelle marque :</option>";
        echo "</select><br/>";

            /* ########### + marque ########### */
            echo "\n\n\n";
            echo "<fieldset id=\"plus_marque\" class=\"subfield\" style=\"display: none;\"><legend class=\"subfield\">Nouvelle Marque</legend>";
                echo "<label for=\"plus_marque_nom\">Nom :</label>\n";		echo "<input value=\"\" name=\"plus_marque_nom\" type=\"text\">\n";
            echo "</fieldset>";
            echo "\n\n\n";

        /* ########### reference ########### */
        echo "<label for=\"reference\">Référence : </label>\n";			echo "<input value=\"".$data[0]["reference"]."\" name=\"reference\" type=\"text\" id=\"reference\">";
        echo "<br/>";

        /* ########### serial_number ########### */
        echo "<label for=\"serial_number\">Numéro de série : </label>\n";	echo "<input value=\"".$data[0]["serial_number"]."\" name=\"serial_number\" type=\"text\" id=\"serial_number\"><br/>";

    echo "</fieldset>";


/*  ╔╦╗╔═╗╔═╗╔═╗
     ║ ╠═╣║ ╦╚═╗
     ╩ ╩ ╩╚═╝╚═╝    */
   echo "<fieldset id=\"tags\"><legend>Mots clés (fonction désactivée pour l’instant)</legend>";

    if ( isset($fieldset_tags) ) echo "".$fieldset_tags."";
    else {
        echo "<label for=\"tags[]\">Tags :</label>";
        echo "<select data-placeholder=\"Aucun tag renseigné\" style=\"width:250px;\" class=\"chosen-select\"  multiple=\"multiple\" tabindex=\"6\" name=\"tags[]\">";
        echo "<option value=\"\"></option>";
        foreach ($tags as $t2) {
            if (in_array($t2["tags_index"], $tags_i)) $select=" selected=\"selected\"";	else $select="";
            echo "<option value=\"".$t2["tags_index"]."\" $select>".$t2["tags_id"]."</option>";
        }
        echo "</select>";

        echo "
          <script type=\"text/javascript\">
            var config = {
              '.chosen-select'           : {no_results_text:'Oops, nothing found!', width:\"250px\"},
            }
            for (var selector in config) {
              $(selector).chosen(config[selector]);
            }
          </script>";

        echo "<label for=\"plus_tags\">Nouveaux tags <abbr title=\"séparés d’une virgule\"><strong>ⓘ</strong></abbr> :</label>";
        echo "<input value=\"\" name=\"plus_tags\" type=\"text\">\n";
    }

    echo "</fieldset>";


/*  ╔═╗╔═╗╔╦╗╔═╗╔═╗╔╦╗╦╔╗ ╦ ╦  ╦╔╦╗╔═╗
    ║  ║ ║║║║╠═╝╠═╣ ║ ║╠╩╗║ ║  ║ ║ ║╣
    ╚═╝╚═╝╩ ╩╩  ╩ ╩ ╩ ╩╚═╝╩ ╩═╝╩ ╩ ╚═╝  */
    echo "<fieldset><legend>Compatibilité (fonction désactivée pour l’instant)</legend>";

    if ( isset($fieldset_compatibilite) ) echo "".$fieldset_tags."";
    else {
        echo "<label for=\"compatibilite[]\">Élements compatibles : </label>\n";

        echo "<select data-placeholder=\"Aucune compatibilité renseignée\" style=\"width:250px;\" class=\"chosen-select\"  multiple=\"multiple\" tabindex=\"6\" name=\"compatibilite[]\">";
        echo "<option value=\"\"></option>";
        $cat="";
        foreach ($labids_cat as $li) { // base_index, lab_id, categorie, categorie_lettres, categorie_nom
            if ( ($cat!=$li["categorie"])&&($cat!="") ) echo "</optgroup>";
            if ($cat!=$li["categorie"]) echo "<optgroup label=\"".$li["categorie_nom"]."\">";
            if (in_array($li["base_index"], $compatibilite)) $select=" selected=\"selected\"";
            else $select="";

            if ($li["base_index"]!=$i) echo "<option value=\"".$li["base_index"]."\" $select>".$li["lab_id"]." #".$li["base_index"]."</option>";
            $cat=$li["categorie"];
        }
        echo "</select>";

        echo "
          <script type=\"text/javascript\">
            var config = {
              '.chosen-select'           : {no_results_text:'Oops, nothing found!', width:\"250px\"},
            }
            for (var selector in config) {
              $(selector).chosen(config[selector]);
            }
          </script>";
        }

    echo "</fieldset>";

/*  ╔═╗╦ ╦╔╗ ╔╦╗╦╔╦╗
    ╚═╗║ ║╠╩╗║║║║ ║
    ╚═╝╚═╝╚═╝╩ ╩╩ ╩     */
    if ($write) echo "<p style=\"text-align:center;\"><input name=\"technique_valid\" value=\"Enregistrer\" type=\"submit\" class=\"little_button\" /></p>"; // TODO Ajouter un bouton réinitialiser

    if ($write) echo "</form>";

echo "</div>";

?>
