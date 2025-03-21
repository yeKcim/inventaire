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
$sth = $dbh->query("SELECT base_index, lab_id, categorie, reference, designation, sortie FROM base WHERE base_index!=\"$i\" ORDER BY lab_id ASC ;");
$lab_ids = ($sth) ? $sth->fetchAll(PDO::FETCH_ASSOC) : FALSE ;
if ($sth) $sth->closeCursor();

// raison_sortie
$sth = $dbh->query("SELECT * FROM raison_sortie WHERE raison_sortie_index!=0 ORDER BY raison_sortie_nom ASC ;");
$raison_sorties = ($sth) ? $sth->fetchAll(PDO::FETCH_ASSOC) : FALSE ;
if ($sth) $sth->closeCursor();

// localisation
$sth = $dbh->query("SELECT * FROM localisation WHERE localisation_index!=0 ORDER BY localisation_batiment ASC, localisation_piece ASC ;");
$localisations = ($sth) ? $sth->fetchAll(PDO::FETCH_ASSOC) : FALSE ;
if ($sth) $sth->closeCursor();

// tous les enfants
$sth = $dbh->query("SELECT base_index, lab_id, designation FROM base WHERE integration=\"$i\" ORDER BY lab_id ASC ;");
$kids = ($sth) ? $sth->fetchAll(PDO::FETCH_ASSOC) : FALSE ;
if ($sth) $sth->closeCursor();

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
        $$value= isset($_POST[$value]) ? trim($_POST[$value]) : "" ;
    }

    if ($utilisateur=="plus_utilisateur") {
        $plus_utilisateur_nom=mb_strtoupper($plus_utilisateur_nom);
        $plus_utilisateur_phone=phone_display("$plus_utilisateur_phone","");
	$sth = $dbh->query(str_replace("\"\"", "NULL","INSERT INTO utilisateur (utilisateur_nom, utilisateur_prenom, utilisateur_mail, utilisateur_phone) VALUES (\"".$plus_utilisateur_nom."\", \"".$plus_utilisateur_prenom."\",\"".$plus_utilisateur_mail."\",\"".$plus_utilisateur_phone."\") ;"));
        /* TODO : prévoir le cas où le contrat existe déjà */
	$utilisateur=return_last_id("utilisateur_index","utilisateur");

        // on ajoute cette entrée dans le tableau des utilisateurs (utilisé pour le select)
	array_push($utilisateurs, array("utilisateur_index" => $utilisateur, "utilisateur_nom" => $plus_utilisateur_nom, "utilisateur_prenom" => $plus_utilisateur_prenom, "utilisateur_mail" => $plus_utilisateur_mail, "utilisateur_phone" => phone_display("$plus_utilisateur_phone",".") ) );
    }

    if ($localisation=="plus_localisation") {
        $sth = $dbh->query(str_replace("\"\"", "NULL","INSERT INTO localisation (localisation_batiment, localisation_piece) VALUES (\"".$plus_localisation_bat."\", \"".$plus_localisation_piece."\" );"));
        
        /* TODO : prévoir le cas où la nouvelle localisation existe déjà */
	$localisation=return_last_id("localisation_index","localisation");
	
        // on ajoute cette entrée dans le tableau des localisations (utilisé pour le select)
	array_push($localisations, array("localisation_index" => $localisation, "localisation_batiment" => $plus_localisation_bat, "localisation_piece" => $plus_localisation_piece ) );
    }


    if ($raison_sortie=="plus_raison_sortie") {
        $sth = $dbh->query(str_replace("\"\"", "NULL","INSERT INTO raison_sortie (raison_sortie_nom) VALUES (\"".$plus_raison_sortie_nom."\");"));
        /* TODO : prévoir le cas où le contrat existe déjà */
	$raison_sortie=return_last_id("raison_sortie_index","raison_sortie");
        // on ajoute cette entrée dans le tableau des raisons de sortie (utilisé pour le select)
	array_push($vendeurs, array("raison_sortie_index" => $raison_sortie, "raison_sortie_nom" => $plus_raison_sortie_nom ) );
    }

$raison_sortie = ($sortie==0) ? "0" : $raison_sortie ;

/*  ╦ ╦╔═╗╔╦╗╔═╗╔╦╗╔═╗  ╔═╗╔═╗ ╦    ╔═╗ ╦ ╦╔═╗╦═╗╦ ╦
    ║ ║╠═╝ ║║╠═╣ ║ ║╣   ╚═╗║═╬╗║    ║═╬╗║ ║║╣ ╠╦╝╚╦╝
    ╚═╝╩  ═╩╝╩ ╩ ╩ ╚═╝  ╚═╝╚═╝╚╩═╝  ╚═╝╚╚═╝╚═╝╩╚═ ╩     */

    // Si la localisation change, on modifie la date de localisation pour mettre aujourd’hui
    $change_date_localisation= ($data[0]["localisation"]==$localisation) ? "" : ", date_localisation=\"".date("y.m.d")."\"";

    $modif_result = $dbh->query(str_replace("\"\"", "NULL","UPDATE base SET utilisateur=\"".$utilisateur."\", localisation=\"".$localisation."\", sortie=\"".$sortie."\", integration=\"".$integration."\", raison_sortie=\"".$raison_sortie."\" $change_date_localisation WHERE base.base_index = $i;"));
    $message.= (!isset($modif_result)) ? $message_error_modif : $message_success_modif;


    // Si l’integration change, ajout d’une entrée autotomatiquement dans le journal
    if ($data[0]["integration"]!=$integration) {
        $add_journal= "INSERT INTO historique (historique_index, historique_date, historique_texte, historique_id) VALUES (NULL, \"".date("y.m.d")."\", \"<!--auto-->" ;

	$add_journal.= ($integration=="0") ? "Fin de l’intégration à :<br/> → " : "Intégration à :<br/> → " ;

        $keys = ($integration=="0") ? array_keys(array_column($lab_ids, 'base_index'), $data[0]["integration"]) : array_keys(array_column($lab_ids, 'base_index'), $integration) ;
        if (isset($keys[0])) $txt_in=quickdisplayincarac_b($lab_ids[$keys[0]]);
	else $txt_in = ($integration=="0") ? "<a href='info.php?BASE=".$database."&i=".$data[0]["integration"]."' target='_blank'>#".$data[0]["integration"]."</a>" : "<a href='info.php?BASE=".$database."&i=".$integration."' target='_blank'>#".$integration."</a>";

	$add_journal.=$txt_in."\", \"".$i."\");" ;
        $sth = $dbh->query(str_replace("\"\"", "NULL","$add_journal"));
        //$message. = (!isset($sth))? "<p class=\"error_message\" id=\"disappear_delay\">Une erreur inconnue est survenue. La modification n’a pas été ajoutée automatiquement au journal.</p>" : "<p class=\"success_message\" id=\"disappear_delay\">La modification a été auto$


    }

    // Avant d’afficher on doit ajouter les nouvelles infos dans les array concernés…
    $data[0]["utilisateur"]=$utilisateur;
    $data[0]["localisation"]=$localisation;
    $data[0]["sortie"]=$sortie;
    $data[0]["raison_sortie"] = $raison_sortie ;
    $data[0]["integration"]=$integration;

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

    echo $message ;

    $quick= ( isset($_GET["quick_page"]) ) ? "&quick_page=".$_GET["quick_page"]."&quick_name=".$_GET["quick_name"]."" : "";
    if ($write) echo "<form method=\"post\" action=\"?BASE=$database&i=".$i."".$quick."\">";

/*  ╦ ╦╔╦╗╦ ╦  ╦╔═╗╔═╗╔╦╗╦╔═╗╔╗╔
    ║ ║ ║ ║ ║  ║╚═╗╠═╣ ║ ║║ ║║║║
    ╚═╝ ╩ ╩ ╩═╝╩╚═╝╩ ╩ ╩ ╩╚═╝╝╚╝   */
    echo "<fieldset><legend>Utilisation</legend>";

        /* ########### utilisateur ########### */
        echo "<label for=\"utilisateur\">Utilisateur : </label>\n";
        echo "<select name=\"utilisateur\" onchange=\"display(this,'plus_utilisateur','plus_utilisateur');\" id=\"utilisateur\">";
        echo "<option value=\"0\" "; if ($data[0]["utilisateur"]=="0") echo "selected"; echo ">— Aucun utilisateur spécifié —</option>";
        echo "<option value=\"plus_utilisateur\" "; if ($data[0]["utilisateur"]=="plus_utilisateur") echo "selected"; echo ">− Nouvel utilisateur : −</option>";
    	option_selecteur($data[0]["utilisateur"], $utilisateurs, "utilisateur_index", "utilisateur_nom", "utilisateur_prenom");
        echo "</select><br/>";

            /* ########### + utilisateur ########### */
            echo "\n\n\n";
            echo "<fieldset id=\"plus_utilisateur\" class=\"subfield\" style=\"display: none;\"><legend class=\"subfield\">Nouvel utilisateur</legend>";
                echo "<label for=\"plus_utilisateur_prenom\">Prénom :</label>\n";
                echo "<input value=\"\" name=\"plus_utilisateur_prenom\" type=\"text\"><br/>\n";

                echo "<label for=\"plus_utilisateur_nom\">NOM* :</label>\n";
                echo "<input value=\"\" name=\"plus_utilisateur_nom\" type=\"text\"><br/>\n";

                echo "<label for=\"plus_utilisateur_mail\">Mail :</label>\n";
                echo "<input value=\"\" name=\"plus_utilisateur_mail\" type=\"text\"><br/>\n";

                echo "<label for=\"plus_utilisateur_phone\"><abbr title=\"juste les chiffres sans séparateur\">Téléphone</abbr> :</label>\n";
                echo "<input value=\"\" name=\"plus_utilisateur_phone\" type=\"number\">\n";

            echo "</fieldset>";
            echo "\n\n\n";

        /* ########### localisation ########### */
        echo "<label for=\"localisation\">Localisation : </label>\n";
        echo "<select name=\"localisation\" onchange=\"display(this,'plus_localisation','plus_localisation');\" > id=\"localisation\"";
        echo "<option value=\"0\" "; if ($data[0]["localisation"]=="0") echo "selected"; echo ">— Aucune localisation spécifiée —</option>";
        echo "<option value=\"plus_localisation\" "; if ($data[0]["localisation"]=="plus_localisation") echo "selected"; echo ">− Nouvelle localisation : −</option>";

        option_selecteur($data[0]["localisation"], $localisations, "localisation_index", "localisation_batiment", "localisation_piece");
        echo "</select>";

        if ( ($data[0]["date_localisation"]!="") && ($data[0]["date_localisation"]!="0000-00-00") )
            echo " <abbr title=\"le ".dateformat($data[0]["date_localisation"],"fr")."\"><strong>ⓘ</strong></abbr>";


            /* ########### + localisation ########### */
            echo "\n\n\n";
            echo "<fieldset id=\"plus_localisation\" class=\"subfield\" style=\"display: none;\"><legend class=\"subfield\">Nouvelle localisation</legend>";
                echo "<label for=\"plus_localisation_bat\">Bâtiment* :</label>\n";
                echo "<input value=\"\" name=\"plus_localisation_bat\" type=\"text\"><br/>\n";
                echo "<label for=\"plus_localisation_piece\">Pièce* :</label>\n";
                echo "<input value=\"\" name=\"plus_localisation_piece\" type=\"text\">\n";
            echo "</fieldset>";
            echo "\n\n\n";

    /* ########### carte pour aider à localiser ########### */
    $utilisation_localisation_help = "./$database/utilisation_localisation_help.jpg";
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
            echo "<option value=\"0\" "; if ($data[0]["sortie"]=="") echo "selected"; echo ">Inventorié</option>";
            echo "<option value=\"1\" "; if ($data[0]["sortie"]=="1") echo "selected"; echo ">Sortie définitive d’inventaire</option>";
            echo "<option value=\"2\" "; if ($data[0]["sortie"]=="2") echo "selected"; echo ">Sortie temporaire d’inventaire</option>";
        echo "</select>";

        if ( ($data[0]["sortie"]!="0") && ($data[0]["date_sortie"]!="") && ($data[0]["date_sortie"]!="0000-00-00") )
        echo " <abbr title=\"le ".dateformat($data[0]["date_sortie"],"fr")."\"><strong>ⓘ</strong></abbr>"; /* seulement si sortie… !!! */


        /* ########### raison_sortie ########### */

        $disp= ($data[0]["sortie"]=="0") ? "none" : "block";

        echo "<span id=\"0\" style=\"display:$disp;\">";
        echo "<label for=\"raison_sortie\">Raison de sortie : </label>\n"; /* seulement si sortie… !!! */
        echo "<select name=\"raison_sortie\" onchange=\"display(this,'plus_raison_sortie','plus_raison_sortie');\" id=\"raison_sortie\">";
        echo "<option value=\"0\" "; if ($data[0]["raison_sortie"]=="0") echo "selected"; echo ">— Aucune raison spécifiée —</option>";
        echo "<option value=\"plus_raison_sortie\" "; if ($data[0]["raison_sortie"]=="plus_raison_sortie") echo "selected"; echo ">−Nouvelle raison : −</option>";
        option_selecteur($data[0]["raison_sortie"], $raison_sorties, "raison_sortie_index", "raison_sortie_nom");
        echo "</select>";
        echo "</span>";



                    /* ########### + raison_sortie ########### */
                    echo "\n\n\n";
                    echo "<fieldset id=\"plus_raison_sortie\" class=\"subfield\" style=\"display: none;\"><legend class=\"subfield\">Nouvellle raison de sortie</legend>";
                        echo "<label for=\"plus_raison_sortie_nom\">Raison :</label>\n";

                        $deja_raison=dejadanslabase("SELECT DISTINCT `raison_sortie_nom` FROM `raison_sortie`");
                        echo "<input value=\"\" name=\"plus_raison_sortie_nom\" type=\"text\"  pattern=\"^(?!(".$deja_raison.")$).*$\" oninvalid=\"setCustomValidity('Déjà dans la base')\" oninput=\"setCustomValidity('')\" />\n";
                    echo "</fieldset>";
                    echo "\n\n\n";

    echo "</fieldset>";


/*  ╦╔╗╔╦╗╔═╗╔═╗╦═╗╔═╗╔╦╗╦╔═╗╔╗╔
    ║║║║║ ║╣ ║ ╦╠╦╝╠═╣ ║ ║║ ║║║║
    ╩╝╚╝╩ ╚═╝╚═╝╩╚═╩ ╩ ╩ ╩╚═╝╝╚╝  */
    echo "<fieldset><legend>Intégration (composant intégré à un autre ou faisant parti d’un lot)</legend>";

        echo "<label for=\"integration\">Intégré dans :</label>\n";

        echo "<select name=\"integration\" id=\"integration\" >";
        echo "<option value=\"0\" "; if ($data[0]["integration"]=="0") echo "selected"; echo ">— Aucune intégration spécifiée —</option>";
        option_selecteur($data[0]["integration"], $lab_ids, "base_index", "lab_id");
        echo "</select>";

        if (isset($data[0]["integration"])) { if ( ($data[0]["integration"]!="0") && ($data[0]["integration"]!="") )
            echo " <a href=\"info.php?BASE=".$database."&i=".$data[0]["integration"]."\" target=\"_blank\"><strong>↗</strong></a>";
        }

        if (!empty($kids) ) {
            echo "<br/>Parent de :\n";
            echo "<ul>";
                foreach ($kids as $k) echo "<li><a href=\"?i=".$k["base_index"]."&BASE=".$database."\" target=\"_blank\">".$k["lab_id"]." (#".$k["base_index"].")</a>&nbsp;: ".$k["designation"]."</li>";
            echo "</ul>";
        }

    echo "</fieldset>";

/*  ╔═╗╦ ╦╔╗ ╔╦╗╦╔╦╗
    ╚═╗║ ║╠╩╗║║║║ ║
    ╚═╝╚═╝╚═╝╩ ╩╩ ╩     */
    if ($write) echo "<p style=\"text-align:center;\"><input name=\"utilisation_valid\" value=\"Enregistrer\" type=\"submit\" class=\"little_button\" /></p>"; // TODO Ajouter un bouton réinitialiser

    if ($write) echo "</form>";
    
    echo "<p style=\"text-align:right;\"><small>* champ obligatoire</small></p>"; 

echo "</div>";

?>
