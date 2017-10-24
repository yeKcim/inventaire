<?php
/*
 █████╗ ██████╗ ███╗   ███╗██╗███╗   ██╗██╗███████╗████████╗██████╗  █████╗ ████████╗██╗███████╗
██╔══██╗██╔══██╗████╗ ████║██║████╗  ██║██║██╔════╝╚══██╔══╝██╔══██╗██╔══██╗╚══██╔══╝██║██╔════╝
███████║██║  ██║██╔████╔██║██║██╔██╗ ██║██║███████╗   ██║   ██████╔╝███████║   ██║   ██║█████╗
██╔══██║██║  ██║██║╚██╔╝██║██║██║╚██╗██║██║╚════██║   ██║   ██╔══██╗██╔══██║   ██║   ██║██╔══╝
██║  ██║██████╔╝██║ ╚═╝ ██║██║██║ ╚████║██║███████║   ██║   ██║  ██║██║  ██║   ██║   ██║██║
╚═╝  ╚═╝╚═════╝ ╚═╝     ╚═╝╚═╝╚═╝  ╚═══╝╚═╝╚══════╝   ╚═╝   ╚═╝  ╚═╝╚═╝  ╚═╝   ╚═╝   ╚═╝╚═╝
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

// tutelles
$sth = $dbh->query("SELECT * FROM tutelle WHERE tutelle_index!=0 ORDER BY tutelle_nom ASC ;");
$tutelles = $sth->fetchAll(PDO::FETCH_ASSOC);
// vendeur
$sth = $dbh->query("SELECT * FROM vendeur WHERE vendeur_index!=0 ORDER BY vendeur_nom ASC ;");
$vendeurs = $sth->fetchAll(PDO::FETCH_ASSOC);
// contrats
$sth = $dbh->query("SELECT DISTINCT contrat_index, contrat_nom, contrat_type FROM contrat, contrat_type WHERE contrat_type!=0 AND contrat_index!=0 ORDER BY contrat_nom ASC ;");
$contrats = $sth->fetchAll(PDO::FETCH_ASSOC);

/*
███╗   ███╗ ██████╗ ██████╗ ██╗███████╗    ███████╗ ██████╗ ██╗
████╗ ████║██╔═══██╗██╔══██╗██║██╔════╝    ██╔════╝██╔═══██╗██║
██╔████╔██║██║   ██║██║  ██║██║█████╗      ███████╗██║   ██║██║
██║╚██╔╝██║██║   ██║██║  ██║██║██╔══╝      ╚════██║██║▄▄ ██║██║
██║ ╚═╝ ██║╚██████╔╝██████╔╝██║██║██╗      ███████║╚██████╔╝███████╗
╚═╝     ╚═╝ ╚═════╝ ╚═════╝ ╚═╝╚═╝╚═╝      ╚══════╝ ╚══▀▀═╝ ╚══════╝
*/
if ( isset($_POST["administratif_valid"]) ) {

    $arr = array("designation","vendeur","plus_vendeur_nom","plus_vendeur_web","plus_vendeur_remarque","prix","contrat","plus_contrat_nom", "contrat_type", "plus_contrat_type_nom", "tutelle", "plus_tutelle", "bon_commande", "num_inventaire", "responsable_achat", "plus_responsable_achat_prenom", "plus_responsable_achat_nom", "plus_responsable_achat_mail", "plus_responsable_achat_phone", "date_achat", "garantie");
    foreach ($arr as &$value) {
        $$value= isset($_POST[$value]) ? htmlentities($_POST[$value]) : "" ;
    }


    /* ########### Ajout d’un nouveau vendeur ########### */
    if ($vendeur=="plus_vendeur") {
        // TODO : Si les infos sont vides !
        mysql_query ("INSERT INTO vendeur (vendeur_nom, vendeur_web, vendeur_remarques) VALUES (\"".$plus_vendeur_nom."\",\"".$plus_vendeur_web."\",\"".$plus_vendeur_remarque."\") ; ");

        /* TODO : prévoir le cas où le vendeur existe déjà */
        $query_table_vendeurnew = mysql_query ("SELECT vendeur_index FROM vendeur ORDER BY vendeur_index DESC LIMIT 1 ;");
        while ($l = mysql_fetch_row($query_table_vendeurnew)) $vendeur=$l[0];

        // on ajoute cette entrée dans le tableau des vendeurs (utilisé pour le select)
        $vendeurs[$vendeur]=array($vendeur,utf8_encode($plus_vendeur_nom),utf8_encode($plus_vendeur_web),utf8_encode($plus_vendeur_remarque));
    }


    if ($contrat_type=="plus_contrat_type") {
        mysql_query ("INSERT INTO contrat_type (contrat_type_cat) VALUES ('".$plus_contrat_type_nom."') ; ");

        /* TODO : prévoir le cas où le type de contrat existe déjà */
        $query_table_contrattypenew = mysql_query ("SELECT contrat_type_index FROM contrat_type ORDER BY contrat_type_index DESC LIMIT 1 ;");
        while ($l = mysql_fetch_row($query_table_contrattypenew)) $contrat_type=$l[0];

        // on ajoute cette entrée dans le tableau des types de contrats (utilisé pour le select)
        $types_contrats[$contrat_type]=array( $contrat_type, utf8_encode($plus_contrat_type_nom) );
    }


    if ($contrat=="plus_contrat") {
        mysql_query ("INSERT INTO contrat (contrat_nom, contrat_type) VALUES ('".$plus_contrat_nom."','".$contrat_type."') ; ");
        /* TODO : prévoir le cas où le contrat existe déjà */
        $query_table_contratnew = mysql_query ("SELECT contrat_index FROM contrat ORDER BY contrat_index DESC LIMIT 1 ;");
        while ($l = mysql_fetch_row($query_table_contratnew)) $contrat=$l[0];

        // on ajoute cette entrée dans le tableau des types de contrats (utilisé pour le select)
        $contrats[$contrat]=array( $contrat, utf8_encode($plus_contrat_nom), $contrat_type );
    }


    if ($tutelle=="plus_tutelle") {
        mysql_query ("INSERT INTO tutelle (tutelle_nom) VALUES ('".$plus_tutelle."') ; ");
        /* TODO : prévoir le cas où le contrat existe déjà */
        $query_table_tutellenew = mysql_query ("SELECT tutelle_index FROM tutelle ORDER BY tutelle_index DESC LIMIT 1 ;");
        while ($l = mysql_fetch_row($query_table_tutellenew)) $tutelle=$l[0];

        // on ajoute cette entrée dans le tableau des types de contrats (utilisé pour le select)
        $tutelles[$tutelle]=array( $tutelle, utf8_encode($plus_tutelle) );
    }



    if ($responsable_achat=="plus_responsable_achat") {

        $plus_responsable_achat_nom=mb_strtoupper($plus_responsable_achat_nom);
        $plus_responsable_achat_phone=phone_display("$plus_responsable_achat_phone","");

        mysql_query ("INSERT INTO utilisateur (utilisateur_nom, utilisateur_prenom, utilisateur_mail, utilisateur_phone) VALUES ('".$plus_responsable_achat_nom."', '".$plus_responsable_achat_prenom."','".$plus_responsable_achat_mail."','".$plus_responsable_achat_phone."') ; ");
        /* TODO : prévoir le cas où le contrat existe déjà */
        $query_table_utilisateurnew = mysql_query ("SELECT utilisateur_index FROM utilisateur ORDER BY utilisateur_index DESC LIMIT 1 ;");
        while ($l = mysql_fetch_row($query_table_utilisateurnew)) $responsable_achat=$l[0];

        // on ajoute cette entrée dans le tableau des types de contrats (utilisé pour le select)
        $utilisateurs[$responsable_achat]=array( $responsable_achat, utf8_encode($plus_responsable_achat_nom), utf8_encode($plus_responsable_achat_prenom), utf8_encode($plus_responsable_achat_mail), phone_display("$plus_responsable_achat_phone",".") );

    }

// TODO : prix avec une virgule ou un point ?

/*  ╦ ╦╔═╗╔╦╗╔═╗╔╦╗╔═╗  ╔═╗╔═╗ ╦    ╔═╗ ╦ ╦╔═╗╦═╗╦ ╦
    ║ ║╠═╝ ║║╠═╣ ║ ║╣   ╚═╗║═╬╗║    ║═╬╗║ ║║╣ ╠╦╝╚╦╝
    ╚═╝╩  ═╩╝╩ ╩ ╩ ╚═╝  ╚═╝╚═╝╚╩═╝  ╚═╝╚╚═╝╚═╝╩╚═ ╩     */
    $modif_result=mysql_query ("UPDATE base SET designation=\"".$designation."\", vendeur=\"".$vendeur."\", prix=\"".$prix."\", contrat=\"".$contrat."\", date_achat=\"".dateformat($date_achat,"en")."\", garantie=\"".dateformat($garantie,"en")."\", bon_commande=\"".$bon_commande."\", num_inventaire=\"".$num_inventaire."\", tutelle=\"".$tutelle."\", responsable_achat=\"".$responsable_achat."\" WHERE base.base_index = $i;" );

    $message.= ($modif_result!=1) ? $message_error_modif : $message_success_modif;


    // Avant d’afficher on doit ajouter les nouvelles infos dans les array concernés…
    $data[0]["designation"]=$designation;
    $data[0]["vendeur"]=$vendeur;
    $data[0]["prix"]=$prix;
    $data[0]["contrat"]=$contrat;
    $data[0]["date_achat"]=dateformat($date_achat,"en");
    $data[0]["garantie"]=dateformat($garantie,"en");
    $data[0]["bon_commande"]=$bon_commande;
    $data[0]["num_inventaire"]=$num_inventaire;
    $data[0]["tutelle"]=$tutelle;
    $data[0]["plus_tutelle"]=$plus_tutelle;
    $data[0]["responsable_achat"]=$responsable_achat;


}


/*
███████╗ ██████╗ ██████╗ ███╗   ███╗██╗   ██╗██╗      █████╗ ██╗██████╗ ███████╗
██╔════╝██╔═══██╗██╔══██╗████╗ ████║██║   ██║██║     ██╔══██╗██║██╔══██╗██╔════╝
█████╗  ██║   ██║██████╔╝██╔████╔██║██║   ██║██║     ███████║██║██████╔╝█████╗
██╔══╝  ██║   ██║██╔══██╗██║╚██╔╝██║██║   ██║██║     ██╔══██║██║██╔══██╗██╔══╝
██║     ╚██████╔╝██║  ██║██║ ╚═╝ ██║╚██████╔╝███████╗██║  ██║██║██║  ██║███████╗
╚═╝      ╚═════╝ ╚═╝  ╚═╝╚═╝     ╚═╝ ╚═════╝ ╚══════╝╚═╝  ╚═╝╚═╝╚═╝  ╚═╝╚══════╝
*/
echo "<div id=\"bloc\" style=\"background:#fcf3a3; vertical-align:top;\">";

    echo "<h1>Administratif</h1>";

    echo $message;

    $quick= ( isset($_GET["quick_page"]) ) ? "&quick_page=".$_GET["quick_page"]."&quick_name=".$_GET["quick_name"]."" : "";
    if ($write) echo "<form method=\"post\" action=\"?i=".$i."".$quick."\">";


/*  ╔═╗╦═╗╔═╗╔╦╗╦ ╦╦╔╦╗
    ╠═╝╠╦╝║ ║ ║║║ ║║ ║
    ╩  ╩╚═╚═╝═╩╝╚═╝╩ ╩  */
    echo "<fieldset><legend>Produit</legend>";

        /* ########### designation ########### */
        echo "<label for=\"designation\" style=\"vertical-align: top;\">Désignation :</label>\n";
        echo "<input name=\"designation\" type=\"text\" id=\"designation\" size=\"34\"";
        echo "value=\"";
        echo ($data[0]["designation"]!="") ? $data[0]["designation"] : "";
        echo "\" ><br/>\n";

        /* ########### vendeur ########### */
        echo "<label for=\"vendeur\">Vendeur ";
        if ( ($data[0]["vendeur"]!="0")&&(($data[0]["vendeur"]!="")) ) {

	    $keys = array_keys(array_column($vendeurs, 'vendeur_index'), $data[0]["vendeur"]); $key=$keys[0];
            echo " <a href=\"".$vendeurs[$key]["vendeur_web"]."\" title=\"site web\" target=\"_blank\"><strong>↗</strong></a>";
            echo "<abbr title=\"".$vendeurs[$key]["vendeur_remarques"]."\"><strong>ⓘ</strong></abbr>";
        }
        echo " :</label>\n";
        echo "<select name=\"vendeur\" id=\"vendeur\" onchange=\"display(this,'plus_vendeur','plus_vendeur');\" >";
        echo "<option value=\"0\" "; if ($data[0]["vendeur"]=="0") echo "selected"; echo ">— Aucun vendeur spécifié —</option>"; 
        option_selecteur($data[0]["vendeur"], $vendeurs, "vendeur_index", "vendeur_nom");
        echo "<option value=\"plus_vendeur\" "; if ($data[0]["vendeur"]=="plus_vendeur") echo "selected"; echo ">Nouveau vendeur :</option>";
        echo "</select>";
        echo "<br/>";


        /* ########### + vendeur ########### */
        echo "\n\n\n";
        echo "<fieldset id=\"plus_vendeur\" class=\"subfield\" style=\"display: none;\"><legend  class=\"subfield\">Nouveau Vendeur</legend>";

            echo "<label for=\"plus_vendeur_nom\">Nom :</label>\n";
            echo "<input value=\"\" name=\"plus_vendeur_nom\" type=\"text\"><br/>\n";

            echo "<label for=\"plus_vendeur_web\">Site web :</label>\n";
            echo "<input value=\"\" name=\"plus_vendeur_web\" type=\"text\"><br/>\n";

            echo "<label for=\"plus_vendeur_remarque\">Remarque :</label>\n";
            echo "<input value=\"\" name=\"plus_vendeur_remarque\" type=\"text\"><br/>\n";

        echo "</fieldset>";
        echo "\n\n\n";

        /* ########### prix ########### */
        echo "<label for=\"prix\">Prix (€) : </label>\n";
        echo "<input value=\"";
        echo ($data[0]["prix"]!="0") ? $data[0]["prix"] : "";
        echo "\" name=\"prix\" type=\"text\" id=\"prix\"><br/>";

    echo "</fieldset>";


/*  ╔═╗╔═╗╦ ╦╔═╗╔╦╗╔═╗╦ ╦╦═╗
    ╠═╣║  ╠═╣║╣  ║ ║╣ ║ ║╠╦╝
    ╩ ╩╚═╝╩ ╩╚═╝ ╩ ╚═╝╚═╝╩╚═    */
    echo "<fieldset><legend>Acheteur</legend>";

        /* ########### contrat ########### */
        echo "<label for=\"contrat\">Contrat : </label>\n";
        echo "<select name=\"contrat\" onchange=\"display(this,'plus_contrat','plus_contrat');\" id=\"contrat\">";
        echo "<option value=\"0\" "; if ($data[0]["contrat"]=="0") echo "selected"; echo ">— Aucun contrat spécifié —</option>";
        option_selecteur($data[0]["contrat"], $contrats, "contrat_index", "contrat_nom");
        echo "<option value=\"plus_contrat\" "; if ($data[0]["contrat"]=="plus_contrat") echo "selected"; echo ">Nouveau contrat :</option>";
        echo "</select><br/>";

        /* ########### + contrat ########### */
        echo "\n\n\n";
        echo "<fieldset id=\"plus_contrat\" class=\"subfield\" style=\"display: none;\"><legend class=\"subfield\">Nouveau Contrat</legend>";

            echo "<label for=\"plus_contrat_nom\">Nom :</label>\n";
            echo "<input value=\"\" name=\"plus_contrat_nom\" type=\"text\"><br/>\n";

            echo "<label for=\"contrat_type\">Type de contrat :</label>\n";
               echo "<select name=\"contrat_type\" onchange=\"display(this,'plus_contrat_type','plus_contrat_type');\" id=\"contrat_type\">";
                   echo "<option value=\"0\" selected >— Aucun type de contrat spécifié —</option>"; 
                   option_selecteur("0", $types_contrats, "contrat_type_index", "contrat_type_cat");
                   echo "<option value=\"plus_contrat_type\" >Nouveau type de contrat :</option>";
               echo "</select><br/>";

                    /* ########### + type contrat ########### */
                    echo "\n\n\n";
                    echo "<fieldset id=\"plus_contrat_type\" class=\"subfield\" style=\"display: none;\"><legend class=\"subfield\">Nouveau Type de contrat</legend>";
                        echo "<label for=\"plus_contrat_type_nom\">Type :</label>\n";
                        echo "<input value=\"\" name=\"plus_contrat_type_nom\" type=\"text\">\n";
                    echo "</fieldset>";
                    echo "\n\n\n";

        echo "</fieldset>";
        echo "\n\n\n";

        /* ########### tutelle ########### */
        echo "<label for=\"tutelle\">Tutelle : </label>\n";
        echo "<select name=\"tutelle\" onchange=\"display(this,'plus_tutelle','plus_tutelle');\" id=\"tutelle\">";
        echo "<option value=\"0\" "; if ($data[0]["tutelle"]=="") echo "selected"; echo ">— Aucune tutelle spécifiée —</option>"; 
        option_selecteur($data[0]["tutelle"], $tutelles, "tutelle_index", "tutelle_nom");
        echo "<option value=\"plus_tutelle\" "; if ($data[0]["tutelle"]=="plus_tutelle") echo "selected"; echo ">Nouvelle tutelle :</option>";
        echo "</select><br/>";
        
            /* ########### + tutelle ########### */
            echo "\n\n\n";
            echo "<fieldset id=\"plus_tutelle\" class=\"subfield\" style=\"display: none;\"><legend class=\"subfield\">Nouvelle tutelle</legend>";
                echo "<label for=\"plus_tutelle\">Tutelle :</label>\n";
                echo "<input value=\"\" name=\"plus_tutelle\" type=\"text\">\n";
            echo "</fieldset>";
            echo "\n\n\n";
        
        /* ########### bon de commande ########### */
        echo "<label for=\"bon_commande\">Bon de commande : </label>\n";
        echo "<input value=\"".$data[0]["bon_commande"]."\" name=\"bon_commande\" type=\"text\" id=\"bon_commande\">";
        echo "<br/>";

        /* ########### num_inventaire ########### */
        echo "<label for=\"num_inventaire\">N° d’inventaire : </label>\n";
        echo "<input value=\"".$data[0]["num_inventaire"]."\" name=\"num_inventaire\" type=\"text\" id=\"num_inventaire\">";
        echo "<br/>";

        /* ########### responsable_achat ########### */
        echo "<label for=\"responsable_achat\">Acheteur ";

	$keys = array_keys(array_column($utilisateurs, 'utilisateur_index'), $data[0]["responsable_achat"]); $key=$keys[0];

        if ( ($data[0]["responsable_achat"]!="0")&&(($data[0]["responsable_achat"]!="")) ) {
            echo "<a href=\"mailto:".$utilisateurs[$key]["utilisateur_mail"]."\" title=\"".$utilisateurs[$key]["utilisateur_mail"]."\"><strong>✉</strong></a> ";
        echo "<abbr title=\"".phone_display("".$utilisateurs[$key]["utilisateur_phone"]."",".")."\"><strong>☏</strong></abbr>";
        }

        echo ": </label>\n";
        echo "<select name=\"responsable_achat\" onchange=\"display(this,'plus_responsable_achat','plus_responsable_achat');\" id=\"responsable_achat\">";
        echo "<option value=\"0\" "; if ($data[0]["responsable_achat"]=="0") echo "selected"; echo ">— Aucun responsable achat spécifié —</option>"; 
																	

        option_selecteur($data[0]["responsable_achat"], $utilisateurs, "utilisateur_index", "utilisateur_nom", "utilisateur_prenom");

																	
        echo "<option value=\"plus_responsable_achat\" "; if ($data[0]["responsable_achat"]=="plus_responsable_achat") echo "selected"; echo ">Nouveau responsable achat :</option>";
        echo "</select>";


            /* ########### + responsable_achat ########### */
            echo "\n\n\n";
            echo "<fieldset id=\"plus_responsable_achat\" class=\"subfield\" style=\"display: none;\"><legend class=\"subfield\">Nouveau responsable achat</legend>";
                echo "<label for=\"plus_responsable_achat_prenom\">Prénom :</label>\n";
                echo "<input value=\"\" name=\"plus_responsable_achat_prenom\" type=\"text\"><br/>\n";

                echo "<label for=\"plus_responsable_achat_nom\">NOM :</label>\n";
                echo "<input value=\"\" name=\"plus_responsable_achat_nom\" type=\"text\"><br/>\n";

                echo "<label for=\"plus_responsable_achat_mail\">Mail :</label>\n";
                echo "<input value=\"\" name=\"plus_responsable_achat_mail\" type=\"text\"><br/>\n";

                echo "<label for=\"plus_responsable_achat_phone\"><abbr title=\"juste les chiffres sans séparateur\">Téléphone</abbr> :</label>\n";
                echo "<input value=\"\" name=\"plus_responsable_achat_phone\" type=\"text\"><br/>\n";

            echo "</fieldset>";
            echo "\n\n\n";

    echo "</fieldset>";


/*  ╔╦╗╔═╗╔╦╗╔═╗╔═╗
     ║║╠═╣ ║ ║╣ ╚═╗
    ═╩╝╩ ╩ ╩ ╚═╝╚═╝  */
    echo "<fieldset><legend>Dates</legend>";

        /* ########### date_achat ########### */

        echo "<label for=\"achat\">Achat <abbr title=\"JJ/MM/AAAA (si jour ou mois inconnu → 01)\"><strong>ⓘ</strong></abbr> :</label>\n";
	echo "<input value=\"";
        if ($data[0]["date_achat"]!="0000-00-00") echo $data[0]["date_achat"]; //echo dateformat($data[0]["date_achat"],"en");
	echo "\" name=\"date_achat\" type=\"date\" id=\"achat\"/>";
        echo "<br/>";

        /* ########### garantie ########### */
        echo "<label for=\"garantie\">Fin de garantie <abbr title=\"JJ/MM/AAAA (si jour ou mois inconnu → 01)\"><strong>ⓘ</strong></abbr> :</label>\n";
        echo "<input value=\"";
        if ($data[0]["garantie"]!="0000-00-00") echo $data[0]["garantie"]; //dateformat($data[0]["garantie"],"en");
        echo "\" name=\"garantie\" type=\"date\" id=\"garantie\" /><br/>";

    echo "</fieldset>";

/*  ╔═╗╦ ╦╔╗ ╔╦╗╦╔╦╗
    ╚═╗║ ║╠╩╗║║║║ ║
    ╚═╝╚═╝╚═╝╩ ╩╩ ╩     */
if ($write) echo "<p style=\"text-align:center;\"><input name=\"administratif_valid\" value=\"Enregistrer\" type=\"submit\" class=\"little_button\" /></p>"; // TODO Ajouter un bouton réinitialiser

    if ($write) echo "</form>";



echo "</div>";

?>
