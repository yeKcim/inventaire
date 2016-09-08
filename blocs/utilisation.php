<?php
/*
██╗   ██╗████████╗██╗██╗     ██╗███████╗ █████╗ ████████╗██╗ ██████╗ ███╗   ██╗
██║   ██║╚══██╔══╝██║██║     ██║██╔════╝██╔══██╗╚══██╔══╝██║██╔═══██╗████╗  ██║
██║   ██║   ██║   ██║██║     ██║███████╗███████║   ██║   ██║██║   ██║██╔██╗ ██║
██║   ██║   ██║   ██║██║     ██║╚════██║██╔══██║   ██║   ██║██║   ██║██║╚██╗██║
╚██████╔╝   ██║   ██║███████╗██║███████║██║  ██║   ██║   ██║╚██████╔╝██║ ╚████║
 ╚═════╝    ╚═╝   ╚═╝╚══════╝╚═╝╚══════╝╚═╝  ╚═╝   ╚═╝   ╚═╝ ╚═════╝ ╚═╝  ╚═══╝
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

// tous les lab_id (utilisé uniquement pour intégration, TODO à supprimer)
$query_table_lab_id = mysql_query ("SELECT base_index, lab_id FROM base WHERE base_index!=\"$i\" ORDER BY lab_id ASC ;");
$lab_ids = array();
while ($l = mysql_fetch_row($query_table_lab_id)) {
    $lab_ids[$l[0]]=array($l[0],"#".$l[0]."", utf8_encode($l[1]));
}

// raison_sortie
$query_table_raison_sortie = mysql_query ("SELECT * FROM raison_sortie WHERE raison_sortie_index!=0 ORDER BY raison_sortie_nom ASC  ;");
$raison_sorties = array();
while ($l = mysql_fetch_row($query_table_raison_sortie)) {
    $raison_sorties[$l[0]]=array($l[0],utf8_encode($l[1]));
}

// localisation
$query_table_localisation = mysql_query ("SELECT * FROM localisation WHERE localisation_index!=0 ORDER BY localisation_batiment ASC, localisation_piece ASC ;");
$localisations = array();
while ($l = mysql_fetch_row($query_table_localisation)) {
    $localisations[$l[0]]=array($l[0],utf8_encode($l[1]),utf8_encode($l[2]));
}



/*
███╗   ███╗ ██████╗ ██████╗ ██╗███████╗    ███████╗ ██████╗ ██╗     
████╗ ████║██╔═══██╗██╔══██╗██║██╔════╝    ██╔════╝██╔═══██╗██║     
██╔████╔██║██║   ██║██║  ██║██║█████╗      ███████╗██║   ██║██║     
██║╚██╔╝██║██║   ██║██║  ██║██║██╔══╝      ╚════██║██║▄▄ ██║██║     
██║ ╚═╝ ██║╚██████╔╝██████╔╝██║██║██╗      ███████║╚██████╔╝███████╗
╚═╝     ╚═╝ ╚═════╝ ╚═════╝ ╚═╝╚═╝╚═╝      ╚══════╝ ╚══▀▀═╝ ╚══════╝
*/
if ( isset($_POST["utilisation_valid"]) ) {

    $arr = array("utilisateur", "plus_utilisateur_prenom", "plus_utilisateur_nom", "plus_utilisateur_mail", "plus_utilisateur_phone", "localisation", "plus_localisation_bat", "plus_localisation_piece", "sortie", "raison_sortie", "plus_raison_sortie_nom", "integration");
    foreach ($arr as &$value) {
        $$value= isset($_POST[$value]) ? htmlentities($_POST[$value]) : "" ;
    }


    if ($utilisateur=="plus_utilisateur") {
        $plus_utilisateur_nom=mb_strtoupper($plus_utilisateur_nom);
        $plus_utilisateur_phone=phone_display("$plus_utilisateur_phone","");
        mysql_query ("INSERT INTO utilisateur (utilisateur_nom, utilisateur_prenom, utilisateur_mail, utilisateur_phone) VALUES (\"".$plus_utilisateur_nom."\", \"".$plus_utilisateur_prenom."\",\"".$plus_utilisateur_mail."\",\"".$plus_utilisateur_phone."\") ; ");
        /* TODO : prévoir le cas où le contrat existe déjà */
        $query_table_utilisateurnew = mysql_query ("SELECT utilisateur_index FROM utilisateur ORDER BY utilisateur_index DESC LIMIT 1 ;");
        while ($l = mysql_fetch_row($query_table_utilisateurnew)) $utilisateur=$l[0];
        // on ajoute cette entrée dans le tableau des utilisateurs (utilisé pour le select)
        $utilisateurs[$utilisateur]=array( $utilisateur, utf8_encode($plus_utilisateur_nom), utf8_encode($plus_utilisateur_prenom), utf8_encode($plus_utilisateur_mail), phone_display("$plus_utilisateur_phone",".") );
    }


    if ($localisation=="plus_localisation") {
        mysql_query ("INSERT INTO localisation (localisation_batiment, localisation_piece) VALUES (\"".$plus_localisation_bat."\", \"".$plus_localisation_piece."\" ); ");
        /* TODO : prévoir le cas où le contrat existe déjà */
        $query_table_localisationnew = mysql_query ("SELECT localisation_index FROM localisation ORDER BY localisation_index DESC LIMIT 1 ;");
        while ($l = mysql_fetch_row($query_table_localisationnew)) $localisation=$l[0];
        // on ajoute cette entrée dans le tableau des localisations (utilisé pour le select)
        $localisations[$localisation]=array( $localisation, utf8_encode($plus_localisation_bat), utf8_encode($plus_localisation_piece) );
    }


    if ($raison_sortie=="plus_raison_sortie") {
        mysql_query ("INSERT INTO raison_sortie (raison_sortie_nom) VALUES (\"".$plus_raison_sortie_nom."\"); ");
        /* TODO : prévoir le cas où le contrat existe déjà */
        $query_table_raisonnew = mysql_query ("SELECT raison_sortie_index FROM raison_sortie ORDER BY raison_sortie_index DESC LIMIT 1 ;");
        while ($l = mysql_fetch_row($query_table_raisonnew)) $raison_sortie=$l[0];
        // on ajoute cette entrée dans le tableau des raisons de sortie (utilisé pour le select)
        $raison_sorties[$raison_sortie]=array($raison_sortie,utf8_encode($plus_raison_sortie_nom));

    }

$raison_sortie = ($sortie==0) ? "0" : $raison_sortie ;

/*  ╦ ╦╔═╗╔╦╗╔═╗╔╦╗╔═╗  ╔═╗╔═╗ ╦    ╔═╗ ╦ ╦╔═╗╦═╗╦ ╦
    ║ ║╠═╝ ║║╠═╣ ║ ║╣   ╚═╗║═╬╗║    ║═╬╗║ ║║╣ ╠╦╝╚╦╝
    ╚═╝╩  ═╩╝╩ ╩ ╩ ╚═╝  ╚═╝╚═╝╚╩═╝  ╚═╝╚╚═╝╚═╝╩╚═ ╩     */
    
    // Si la localisation change, on modifie la date de localisation pour mettre aujourd’hui
    $change_date_localisation= ($data["localisation"]==$localisation) ? "" : ", date_localisation=\"".date("y.m.d")."\"";

    $modif_result=mysql_query ("UPDATE base SET utilisateur=\"".$utilisateur."\", localisation=\"".$localisation."\", sortie=\"".$sortie."\", integration=\"".$integration."\", raison_sortie=\"".$raison_sortie."\" $change_date_localisation WHERE base.base_index = $i;" );

    $message.= ($modif_result!=1) ? $message_error_modif : $message_success_modif;

    // Avant d’afficher on doit ajouter les nouvelles infos dans les array concernés…
    $data["utilisateur"]=$utilisateur;
    $data["localisation"]=$localisation;
    $data["sortie"]=$sortie;
    $data["raison_sortie"] = $raison_sortie ;
    $data["integration"]=$integration;

}







/*
███████╗ ██████╗ ██████╗ ███╗   ███╗██╗   ██╗██╗      █████╗ ██╗██████╗ ███████╗
██╔════╝██╔═══██╗██╔══██╗████╗ ████║██║   ██║██║     ██╔══██╗██║██╔══██╗██╔════╝
█████╗  ██║   ██║██████╔╝██╔████╔██║██║   ██║██║     ███████║██║██████╔╝█████╗  
██╔══╝  ██║   ██║██╔══██╗██║╚██╔╝██║██║   ██║██║     ██╔══██║██║██╔══██╗██╔══╝  
██║     ╚██████╔╝██║  ██║██║ ╚═╝ ██║╚██████╔╝███████╗██║  ██║██║██║  ██║███████╗
╚═╝      ╚═════╝ ╚═╝  ╚═╝╚═╝     ╚═╝ ╚═════╝ ╚══════╝╚═╝  ╚═╝╚═╝╚═╝  ╚═╝╚══════╝
*/
echo "<div id=\"bloc\" style=\"background:#c3d1e1; vertical-align:top;\">";

    echo "<h1>Utilisation</h1>";
    
    echo $message;
    
    $quick= ( isset($_GET["quick_page"]) ) ? "&quick_page=".$_GET["quick_page"]."&quick_name=".$_GET["quick_name"]."" : "";
    if ($write) echo "<form method=\"post\" action=\"?i=".$i."".$quick."\">";

/*  ╦ ╦╔╦╗╦ ╦  ╦╔═╗╔═╗╔╦╗╦╔═╗╔╗╔
    ║ ║ ║ ║ ║  ║╚═╗╠═╣ ║ ║║ ║║║║
    ╚═╝ ╩ ╩ ╩═╝╩╚═╝╩ ╩ ╩ ╩╚═╝╝╚╝   */
    echo "<fieldset><legend>Utilisation</legend>";

        /* ########### utilisateur ########### */
        echo "<label for=\"utilisateur\">Utilisateur : </label>\n";
        echo "<select name=\"utilisateur\" onchange=\"display(this,'plus_utilisateur','plus_utilisateur');\" id=\"utilisateur\">";
        echo "<option value=\"0\" "; if ($data["utilisateur"]=="0") echo "selected"; echo ">— Aucun utilisateur spécifié —</option>"; 
        option_selecteur($data["utilisateur"], $utilisateurs, "2");
        echo "<option value=\"plus_utilisateur\" "; if ($data["utilisateur"]=="plus_utilisateur") echo "selected"; echo ">Nouvel utilisateur :</option>";
        echo "</select><br/>";

            /* ########### + utilisateur ########### */
            echo "\n\n\n";
            echo "<fieldset id=\"plus_utilisateur\" class=\"subfield\" style=\"display: none;\"><legend class=\"subfield\">Nouvel utilisateur</legend>";
                echo "<label for=\"plus_utilisateur_prenom\">Prénom :</label>\n";
                echo "<input value=\"\" name=\"plus_utilisateur_prenom\" type=\"text\">\n";

                echo "<label for=\"plus_utilisateur_nom\">Nom :</label>\n";
                echo "<input value=\"\" name=\"plus_utilisateur_nom\" type=\"text\">\n";
                
                echo "<label for=\"plus_utilisateur_mail\">Mail :</label>\n";
                echo "<input value=\"\" name=\"plus_utilisateur_mail\" type=\"text\">\n";
                
                echo "<label for=\"plus_utilisateur_phone\">Téléphone :</label>\n";
                echo "<input value=\"\" name=\"plus_utilisateur_phone\" type=\"text\">\n";

            echo "</fieldset>";
            echo "\n\n\n";
            

        /* ########### localisation ########### */
        echo "<label for=\"localisation\">Localisation : </label>\n";
        echo "<select name=\"localisation\" onchange=\"display(this,'plus_localisation','plus_localisation');\" > id=\"localisation\"";
        echo "<option value=\"0\" "; if ($data["localisation"]=="0") echo "selected"; echo ">— Aucune localisation spécifiée —</option>"; 
        option_selecteur($data["localisation"], $localisations, "2");
        echo "<option value=\"plus_localisation\" "; if ($data["localisation"]=="plus_localisation") echo "selected"; echo ">Nouvelle localisation :</option>";
        echo "</select>";

        if ( ($data["date_localisation"]!="") && ($data["date_localisation"]!="0000-00-00") )
            echo " <abbr title=\"le ".dateformat($data["date_localisation"],"fr")."\"><strong>ⓘ</strong></abbr>";


            /* ########### + localisation ########### */
            echo "\n\n\n";
            echo "<fieldset id=\"plus_localisation\" class=\"subfield\" style=\"display: none;\"><legend class=\"subfield\">Nouvelle localisation</legend>";
                echo "<label for=\"plus_localisation_bat\">Bâtiment :</label>\n";
                echo "<input value=\"\" name=\"plus_localisation_bat\" type=\"text\">\n";
                echo "<label for=\"plus_localisation_piece\">Pièce :</label>\n";
                echo "<input value=\"\" name=\"plus_localisation_piece\" type=\"text\">\n";
            echo "</fieldset>";
            echo "\n\n\n";

    /* ########### carte pour aider à localiser ########### */
    $utilisation_localisation_help = "./blocs/utilisation_localisation_help.jpg";
    if (file_exists("$utilisation_localisation_help")) {

        list($width, $height, $type) = getimagesize("$utilisation_localisation_help");

        echo " <a href=\"".$utilisation_localisation_help."\" target=\"_blank\" title=\"Plan\">plan</a>";
        //echo " <span id=\"linkbox\" onclick=\"TINY.box.show({image:'".$utilisation_localisation_help."',width:$width,height:$height})\" title=\"Plan\">plan</span>";
    }
    else { echo " <span title=\"fonctionnalité à venir\">&nbsp;</span>"; }


    echo "</fieldset>";

/*  ╦╔╗╔╦  ╦╔═╗╔╗╔╦╗╔═╗╦╦═╗╔═╗
    ║║║║╚╗╔╝║╣ ║║║║ ╠═╣║╠╦╝║╣ 
    ╩╝╚╝ ╚╝ ╚═╝╝╚╝╩ ╩ ╩╩╩╚═╚═╝  */
    echo "<fieldset><legend>Inventaire</legend>";

        /* ########### sortie ########### */
        echo "<label for=\"sortie\">État : </label>\n";
        echo "<select name=\"sortie\" id=\"etat\" onchange=\"hide(this,'0','0');\">";
            echo "<option value=\"0\" "; if ($data["sortie"]=="") echo "selected"; echo ">Inventorié</option>";
            echo "<option value=\"1\" "; if ($data["sortie"]=="1") echo "selected"; echo ">Sortie définitive d’inventaire</option>";
            echo "<option value=\"2\" "; if ($data["sortie"]=="2") echo "selected"; echo ">Sortie temporaire d’inventaire</option>";
        echo "</select>";
        
        if ( ($data["sortie"]!="0") && ($data["date_sortie"]!="") && ($data["date_sortie"]!="0000-00-00") )
        echo " <abbr title=\"le ".dateformat($data["date_sortie"],"fr")."\"><strong>ⓘ</strong></abbr>"; /* seulement si sortie… !!! */


        /* ########### raison_sortie ########### */
        
        $disp= ($data["sortie"]=="0") ? "none" : "block";
        
        echo "<span id=\"0\" style=\"display:$disp;\">";
        echo "<label for=\"raison_sortie\">Raison de sortie : </label>\n"; /* seulement si sortie… !!! */
        echo "<select name=\"raison_sortie\" onchange=\"display(this,'plus_raison_sortie','plus_raison_sortie');\" id=\"raison_sortie\">";
        echo "<option value=\"0\" "; if ($data["raison_sortie"]=="0") echo "selected"; echo ">— Aucune raison spécifiée —</option>"; 
        option_selecteur($data["raison_sortie"], $raison_sorties);
        echo "<option value=\"plus_raison_sortie\" "; if ($data["raison_sortie"]=="plus_raison_sortie") echo "selected"; echo ">Nouvelle raison :</option>";
        echo "</select>";   
        echo "</span>";



                    /* ########### + raison_sortie ########### */
                    echo "\n\n\n";
                    echo "<fieldset id=\"plus_raison_sortie\" class=\"subfield\" style=\"display: none;\"><legend class=\"subfield\">Nouvellle raison de sortie</legend>";
                        echo "<label for=\"plus_raison_sortie_nom\">Raison :</label>\n";
                        echo "<input value=\"\" name=\"plus_raison_sortie_nom\" type=\"text\">\n";
                    echo "</fieldset>";
                    echo "\n\n\n";
                 
    echo "</fieldset>";   


/*  ╦╔╗╔╦╗╔═╗╔═╗╦═╗╔═╗╔╦╗╦╔═╗╔╗╔
    ║║║║║ ║╣ ║ ╦╠╦╝╠═╣ ║ ║║ ║║║║
    ╩╝╚╝╩ ╚═╝╚═╝╩╚═╩ ╩ ╩ ╩╚═╝╝╚╝  */
    echo "<fieldset><legend>Intégration (composant intégré à un autre ou faisant parti d’un lot)</legend>";

        echo "<label for=\"integration\">Intégré dans :</label>\n";

        echo "<select name=\"integration\" id=\"integration\" >";
        echo "<option value=\"0\" "; if ($data["integration"]=="0") echo "selected"; echo ">— Aucune intégration spécifiée —</option>"; 
        option_selecteur($data["integration"], $lab_ids, "2");
        echo "</select>";

        if ( ($data["integration"]!="0") && ($data["integration"]!="") )
            echo " <a href=\"info.php?i=".$data["integration"]."\"><strong>↗</strong></a>";

    echo "</fieldset>";

/*  ╔═╗╦ ╦╔╗ ╔╦╗╦╔╦╗
    ╚═╗║ ║╠╩╗║║║║ ║ 
    ╚═╝╚═╝╚═╝╩ ╩╩ ╩     */
    if ($write) echo "<p style=\"text-align:center;\"><input name=\"utilisation_valid\" value=\"Enregistrer\" type=\"submit\" class=\"little_button\" /></p>"; // TODO Ajouter un bouton réinitialiser

    if ($write) echo "</form>";

echo "</div>";

?>
