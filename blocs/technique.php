<?php

/*
████████╗███████╗ ██████╗██╗  ██╗███╗   ██╗██╗ ██████╗ ██╗   ██╗███████╗
╚══██╔══╝██╔════╝██╔════╝██║  ██║████╗  ██║██║██╔═══██╗██║   ██║██╔════╝
   ██║   █████╗  ██║     ███████║██╔██╗ ██║██║██║   ██║██║   ██║█████╗  
   ██║   ██╔══╝  ██║     ██╔══██║██║╚██╗██║██║██║▄▄ ██║██║   ██║██╔══╝  
   ██║   ███████╗╚██████╗██║  ██║██║ ╚████║██║╚██████╔╝╚██████╔╝███████╗
   ╚═╝   ╚══════╝ ╚═════╝╚═╝  ╚═╝╚═╝  ╚═══╝╚═╝ ╚══▀▀═╝  ╚═════╝ ╚══════╝
*/



/*
 █████╗ ██████╗ ██████╗  █████╗ ██╗   ██╗
██╔══██╗██╔══██╗██╔══██╗██╔══██╗╚██╗ ██╔╝
███████║██████╔╝██████╔╝███████║ ╚████╔╝ 
██╔══██║██╔══██╗██╔══██╗██╔══██║  ╚██╔╝  
██║  ██║██║  ██║██║  ██║██║  ██║   ██║   
╚═╝  ╚═╝╚═╝  ╚═╝╚═╝  ╚═╝╚═╝  ╚═╝   ╚═╝   
*/
$query_table_tags_list = mysql_query ("SELECT * FROM tags_list WHERE tags_list_index!=0 ORDER BY tags_list_nom ASC ;");
$tags = array();
while ($l = mysql_fetch_row($query_table_tags_list)) {
    $tags[$l[0]]=array($l[0],utf8_encode($l[1]));
}

// tags_i les tags de $i
$query_table_tag_i = mysql_query ("SELECT tags_index FROM tags WHERE tags_id=$i ;");
$tags_i = array();
while ($l = mysql_fetch_row($query_table_tag_i)) {
    $tags_i[]=$l[0];
}

// compatibilité de $i
$query_table_compatibilite = mysql_query ("SELECT * FROM compatibilite WHERE compatib_id1=\"$i\" OR compatib_id2=\"$i\" ;");
$compatibilite = array();
while ($l = mysql_fetch_row($query_table_compatibilite)) {
    $compatibilite[]= ($l[1]==$i) ? $l[2] : $l[1] ;
}

// tous les lab_id classé par catégorie
$query_table_labid_cat = mysql_query ("SELECT base_index, lab_id, categorie, categorie_lettres, categorie_nom FROM base, categorie WHERE categorie=categorie_index ORDER BY categorie_nom ASC ;");
$labids_cat = array();
while ($l = mysql_fetch_row($query_table_labid_cat)) {
    $labids_cat[$l[0]]=array( $l[0], utf8_encode($l[1]), $l[2], utf8_encode($l[3]), utf8_encode($l[4]) );
}


// marque
$query_table_marque = mysql_query ("SELECT * FROM marque WHERE marque_index!=0 ORDER BY marque_nom ASC ;");
$marques = array();
while ($l = mysql_fetch_row($query_table_marque)) {
    $marques[$l[0]]=array($l[0],utf8_encode($l[1]));
}



/*
███╗   ███╗ ██████╗ ██████╗ ██╗███████╗    ███████╗ ██████╗ ██╗     
████╗ ████║██╔═══██╗██╔══██╗██║██╔════╝    ██╔════╝██╔═══██╗██║     
██╔████╔██║██║   ██║██║  ██║██║█████╗      ███████╗██║   ██║██║     
██║╚██╔╝██║██║   ██║██║  ██║██║██╔══╝      ╚════██║██║▄▄ ██║██║     
██║ ╚═╝ ██║╚██████╔╝██████╔╝██║██║██╗      ███████║╚██████╔╝███████╗
╚═╝     ╚═╝ ╚═════╝ ╚═════╝ ╚═╝╚═╝╚═╝      ╚══════╝ ╚══▀▀═╝ ╚══════╝
*/
if ( isset($_POST["technique_valid"]) ) {

    $arr = array("categorie", "plus_categorie_nom", "plus_categorie_abbr", "marque", "plus_marque", "plus_marque_nom", "reference", "serial_number", "plus_tags");
    foreach ($arr as &$value) {
        $$value= isset($_POST[$value]) ? htmlentities($_POST[$value]) : "" ;
    }


/*  ╔═╗╔═╗╔╦╗╔═╗╔═╗╔╦╗╦╔╗ ╦  ╦╔╦╗╔═╗╔═╗
    ║  ║ ║║║║╠═╝╠═╣ ║ ║╠╩╗║  ║ ║ ║╣ ╚═╗
    ╚═╝╚═╝╩ ╩╩  ╩ ╩ ╩ ╩╚═╝╩═╝╩ ╩ ╚═╝╚═╝ */
    // Supprimer tous les compatibilités de cette entrée pour réinitialiser
    mysql_query ("DELETE FROM compatibilite WHERE compatib_id1=$i OR compatib_id2=$i ;");

    // Ajout des compatibilités
    if (isset($_POST["compatibilite"])) {
        $allc="";
        foreach ($_POST["compatibilite"] as $c) $allc.= "(".$c[0].",$i),";
        $allc=substr($allc, 0, -1); // suppression du dernier caractère
        mysql_query ("INSERT INTO compatibilite (compatib_id1, compatib_id2) VALUES $allc ; ");
    }
    // refaire compatibilité de $i
    $query_table_compatibilite = mysql_query ("SELECT * FROM compatibilite WHERE compatib_id1=\"$i\" OR compatib_id2=\"$i\" ;");
    $compatibilite = array();
    while ($l = mysql_fetch_row($query_table_compatibilite)) {
        $compatibilite[]= ($l[1]==$i) ? $l[2] : $l[1] ;
    }



/*  ╔╦╗╔═╗╔═╗╔═╗
     ║ ╠═╣║ ╦╚═╗
     ╩ ╩ ╩╚═╝╚═╝    */
    // Supprimer tous les tags de cette entrée pour réinitialiser
    mysql_query ("DELETE FROM tags WHERE tags_id=$i;");

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
            mysql_query("INSERT INTO tags_list (tags_list_index, tags_list_nom) VALUES $allnewtags ;");
            // Nouveaux tags dans tags de $i
            $allnewtags_index="";
            // tagnew_i les tags de $i
            $query_table_tagnew_i = mysql_query ("SELECT tags_list_index FROM tags_list WHERE tags_list_nom IN ($allnewtagscomma) ;");
            while ($nti = mysql_fetch_row($query_table_tagnew_i)) $allnewtags_index.= "('".$nti[0]."','$i')," ;
            $allnewtags_index=substr($allnewtags_index, 0, -1); // suppression du dernier caractère
            mysql_query ("INSERT INTO tags (tags_index, tags_id) VALUES $allnewtags_index ; ");
        }
    }


    // Ajout des tags
    if (isset($_POST["tags"])) {
        $allt="";
        foreach ($_POST["tags"] as $ta) $allt.= "(".$ta.",$i),";
        $allt=substr($allt, 0, -1); // suppression du dernier caractère
        mysql_query ("INSERT INTO tags (tags_index, tags_id) VALUES $allt ;");
    }
    
    
    // refaire tags et tags de $i avant affichage
    // $tags_list
    $query_table_tags_list = mysql_query ("SELECT * FROM tags_list WHERE tags_list_index!=0 ORDER BY tags_list_nom ASC ;");
    $tags = array();
    while ($l = mysql_fetch_row($query_table_tags_list)) {
        $tags[$l[0]]=array($l[0],utf8_encode($l[1]));
    }

    // tags_i les tags de $i
    $query_table_tag_i = mysql_query ("SELECT tags_index FROM tags WHERE tags_id=$i ;");
    $tags_i = array();
    while ($l = mysql_fetch_row($query_table_tag_i)) {
        $tags_i[]=$l[0];
    }


/*  ╔╗╔╔═╗╦ ╦╦  ╦╔═╗╦  ╦  ╔═╗  ╔╦╗╔═╗╦═╗╔═╗ ╦ ╦╔═╗
    ║║║║ ║║ ║╚╗╔╝║╣ ║  ║  ║╣   ║║║╠═╣╠╦╝║═╬╗║ ║║╣ 
    ╝╚╝╚═╝╚═╝ ╚╝ ╚═╝╩═╝╩═╝╚═╝  ╩ ╩╩ ╩╩╚═╚═╝╚╚═╝╚═╝  */
    if ($marque=="plus_marque") {
        mysql_query ("INSERT INTO marque (marque_nom) VALUES ('".$plus_marque_nom."') ; ");
        /* TODO : prévoir le cas où la marque existe déjà */
        $query_table_marquenew = mysql_query ("SELECT marque_index FROM marque ORDER BY marque_index DESC LIMIT 1 ;");
        while ($l = mysql_fetch_row($query_table_marquenew)) $marque=$l[0];
        // on ajoute cette entrée dans le tableau des marques (utilisé pour le select)
        $marques[$marque]=array( $marque, utf8_encode($plus_marque_nom) );
    }
    

/*  ╔╗╔╔═╗╦ ╦╦  ╦╔═╗╦  ╦  ╔═╗  ╔═╗╔═╗╔╦╗╔═╗╔═╗╔═╗╦═╗╦╔═╗
    ║║║║ ║║ ║╚╗╔╝║╣ ║  ║  ║╣   ║  ╠═╣ ║ ║╣ ║ ╦║ ║╠╦╝║║╣ 
    ╝╚╝╚═╝╚═╝ ╚╝ ╚═╝╩═╝╩═╝╚═╝  ╚═╝╩ ╩ ╩ ╚═╝╚═╝╚═╝╩╚═╩╚═╝    */
    if ($categorie=="plus_categorie") {
        mysql_query ("INSERT INTO categorie (categorie_lettres, categorie_nom) VALUES (\"".$plus_categorie_abbr."\",\"".$plus_categorie_nom."\") ; ");
        
        /* TODO : prévoir le cas où le vendeur existe déjà */
        $query_table_categorienew = mysql_query ("SELECT categorie_index FROM categorie ORDER BY categorie_index DESC LIMIT 1 ;");
        while ($l = mysql_fetch_row($query_table_categorienew)) $categorie=$l[0];
        // on ajoute cette entrée dans le tableau des catégories (utilisé pour le select)
        $categories[$categorie]=array( $categorie,utf8_encode($plus_categorie_nom),utf8_encode($plus_categorie_abbr) );
        // TODO Attetion l’abréviation ne doit contenir que des lettres !
    }



/*  ╦  ╔═╗╔╗  ╦╔╦╗
    ║  ╠═╣╠╩╗ ║ ║║
    ╩═╝╩ ╩╚═╝┘╩═╩╝  */
    // Si on change la catégorie, il est nécessaire de changer également le lab_id !
    if ($data["categorie"]!=$categorie) $data["lab_id"] = new_lab_id($categorie);


/*  ╦ ╦╔═╗╔╦╗╔═╗╔╦╗╔═╗  ╔═╗╔═╗ ╦    ╔═╗ ╦ ╦╔═╗╦═╗╦ ╦
    ║ ║╠═╝ ║║╠═╣ ║ ║╣   ╚═╗║═╬╗║    ║═╬╗║ ║║╣ ╠╦╝╚╦╝
    ╚═╝╩  ═╩╝╩ ╩ ╩ ╚═╝  ╚═╝╚═╝╚╩═╝  ╚═╝╚╚═╝╚═╝╩╚═ ╩     */
    mysql_query ("UPDATE base SET marque='".$marque."', reference='".$reference."', serial_number='".$serial_number."', categorie='".$categorie."', lab_id='".$data["lab_id"]."' WHERE base.base_index = $i;" );

    // Avant d’afficher on doit ajouter les nouvelles infos dans les array concernés…
    $data["marque"]=$marque;
    $data["serial_number"]=$serial_number;
    $data["reference"]=$reference;
    $data["categorie"]=$categorie;

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
    
    if ($write) echo "<form method=\"post\" action=\"?i=$i\">";

/*  ╦═╗╔═╗╔═╗╔═╗╦═╗╔═╗╔╗╔╔═╗╔═╗  ╦╔╗╔╦╗╔═╗╦═╗╔╗╔╔═╗
    ╠╦╝║╣ ╠╣ ║╣ ╠╦╝║╣ ║║║║  ║╣   ║║║║║ ║╣ ╠╦╝║║║║╣ 
    ╩╚═╚═╝╚  ╚═╝╩╚═╚═╝╝╚╝╚═╝╚═╝  ╩╝╚╝╩ ╚═╝╩╚═╝╚╝╚═╝    */
    echo "<fieldset><legend>Référence interne</legend>";

        /* ########### categorie ########### */
        echo "<label for=\"categorie\">Catégorie : </label>\n";
        echo "<select name=\"categorie\" onchange=\"display(this,'plus_categorie','plus_categorie');\" id=\"categorie\">";
        echo "<option value=\"0\" "; if ($data["categorie"]=="0") echo "selected"; echo ">— Aucune catégorie spécifiée —</option>"; 
        option_selecteur($data["categorie"], $categories, "2");
        echo "<option value=\"plus_categorie\" "; if ($data["categorie"]=="plus_categorie") echo "selected"; echo ">Nouvelle catégorie :</option>";
        echo "</select><br/>";
        
            /* ########### + categorie ########### */
            echo "\n\n\n";
            echo "<fieldset id=\"plus_categorie\" class=\"subfield\" style=\"display: none;\"><legend class=\"subfield\">Nouvelle Catégorie</legend>";
                echo "<label for=\"plus_categorie_nom\">Nom :</label>\n";
                echo "<input value=\"\" name=\"plus_categorie_nom\" type=\"text\">\n";
                echo "<label for=\"plus_categorie_abbr\">Abbréviation <abbr title=\"4 caractères max, pas de chiffres\"><strong>ⓘ</strong></abbr> :</label>\n";
                echo "<input value=\"\" name=\"plus_categorie_abbr\" type=\"text\" maxlength=\"4\">\n";
            echo "</fieldset>";
            echo "\n\n\n";

        /* ########### lab_id ########### */
        echo "<label for=\"lab_id\">Identifiant labo : </label>\n";
        echo "<strong>".$data["lab_id"]."</strong>"; // TODO Ajouter un bouton pour choiser cette entrée manuellement (via tinybox ?)
        echo "<br/>";

    echo "</fieldset>";

/*  ╦═╗╔═╗╔═╗╔═╗╦═╗╔═╗╔╗╔╔═╗╔═╗  ╔═╗╔═╗╔╗╔╔═╗╔╦╗╦═╗╦ ╦╔═╗╔╦╗╔═╗╦ ╦╦═╗
    ╠╦╝║╣ ╠╣ ║╣ ╠╦╝║╣ ║║║║  ║╣   ║  ║ ║║║║╚═╗ ║ ╠╦╝║ ║║   ║ ║╣ ║ ║╠╦╝
    ╩╚═╚═╝╚  ╚═╝╩╚═╚═╝╝╚╝╚═╝╚═╝  ╚═╝╚═╝╝╚╝╚═╝ ╩ ╩╚═╚═╝╚═╝ ╩ ╚═╝╚═╝╩╚═   */
    echo "<fieldset><legend>Références Constructeur</legend>";

        /* ########### marque ########### */
        echo "<label for=\"marque\">Marque : </label>\n";
        echo "<select name=\"marque\" onchange=\"display(this,'plus_marque','plus_marque');\" id=\"marque\">";
        echo "<option value=\"0\" "; if ($data["marque"]=="0") echo "selected"; echo ">— Aucune marque spécifiée —</option>"; 
        option_selecteur($data["marque"], $marques);
        echo "<option value=\"plus_marque\" "; if ($data["marque"]=="plus_marque") echo "selected"; echo ">Nouvelle marque :</option>";
        echo "</select><br/>";
        
            /* ########### + marque ########### */
            echo "\n\n\n";
            echo "<fieldset id=\"plus_marque\" class=\"subfield\" style=\"display: none;\"><legend class=\"subfield\">Nouvelle Marque</legend>";
                echo "<label for=\"plus_marque_nom\">Nom :</label>\n";
                echo "<input value=\"\" name=\"plus_marque_nom\" type=\"text\">\n";
            echo "</fieldset>";
            echo "\n\n\n";

        /* ########### reference ########### */
        echo "<label for=\"reference\">Référence : </label>\n";
        echo "<input value=\"".$data["reference"]."\" name=\"reference\" type=\"text\" id=\"reference\">";
        echo "<br/>";

        /* ########### serial_number ########### */
        echo "<label for=\"serial_number\">Numéro de série : </label>\n";
        echo "<input value=\"".$data["serial_number"]."\" name=\"serial_number\" type=\"text\" id=\"serial_number\"><br/>";

    echo "</fieldset>";


/*  ╔╦╗╔═╗╔═╗╔═╗
     ║ ╠═╣║ ╦╚═╗
     ╩ ╩ ╩╚═╝╚═╝    */
   echo "<fieldset id=\"tags\"><legend>Mots clés</legend>";

    if ( isset($fieldset_tags) ) echo "".$fieldset_tags."";
    else {
        echo "<label for=\"tags[]\">Tags :</label>";
        echo "<select data-placeholder=\"Aucun tag renseigné\" style=\"width:250px;\" class=\"chosen-select\"  multiple=\"multiple\" tabindex=\"6\" name=\"tags[]\">";
        echo "<option value=\"\"></option>";
        foreach ($tags as $t2) {
            if (in_array($t2[0], $tags_i)) $select=" selected=\"selected\"";
            else $select="";
            echo "<option value=\"".$t2[0]."\" $select>".$t2[1]."</option>";
        }
        echo "</select>";
        
        echo "
          <script type=\"text/javascript\">
            var config = {
              '.chosen-select'           : {no_results_text:'Oops, nothing found!'},
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
    echo "<fieldset><legend>Compatibilité</legend>";

    if ( isset($fieldset_compatibilite) ) echo "".$fieldset_tags."";
    else {
        echo "<label for=\"compatibilite[]\">Élements compatibles : </label>\n";

        echo "<select data-placeholder=\"Aucune compatibilité renseignée\" style=\"width:250px;\" class=\"chosen-select\"  multiple=\"multiple\" tabindex=\"6\" name=\"compatibilite[]\">";
        echo "<option value=\"\"></option>";
        $cat="";
        foreach ($labids_cat as $li) {
            if ( ($cat!=$li[2])&&($cat!="") ) echo "</optgroup>";
            if ($cat!=$li[2]) echo "<optgroup label=\"".$li[4]."\">";
            if (in_array($li[0], $compatibilite)) $select=" selected=\"selected\"";
            else $select="";

            if ($li[0]!=$i) echo "<option value=\"".$li[0]."\" $select>".$li[1]." #".$li[0]."</option>";
            $cat=$li[2];
        }
        echo "</select>";

        echo "
          <script type=\"text/javascript\">
            var config = {
              '.chosen-select'           : {no_results_text:'Oops, nothing found!'},
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
