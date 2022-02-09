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
$tutelles = ($sth) ? $sth->fetchAll(PDO::FETCH_ASSOC) : FALSE ;
// vendeur
$sth = $dbh->query("SELECT * FROM vendeur WHERE vendeur_index!=0 ORDER BY vendeur_nom ASC ;");
$vendeurs = ($sth) ? $sth->fetchAll(PDO::FETCH_ASSOC) : FALSE ;
// contrats
$sth = $dbh->query("SELECT DISTINCT contrat_index, contrat_nom, contrat_type FROM contrat, contrat_type WHERE contrat_index!=0 ORDER BY contrat_nom ASC ;");
$contrats = ($sth) ? $sth->fetchAll(PDO::FETCH_ASSOC) : FALSE ;

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
        $$value= isset($_POST[$value]) ? htmlentities(trim($_POST[$value])) : "" ;
    }

    /* ########### Ajout d’un nouveau vendeur ########### */
    if ($vendeur=="plus_vendeur") {
        // TODO : Si les infos sont vides !
//$sth = $dbh->query(str_replace("\"\"", "NULL","INSERT INTO vendeur (vendeur_nom, vendeur_web, vendeur_remarques) VALUES (\"".$plus_vendeur_nom."\",\"".$plus_vendeur_web."\",\"".$plus_vendeur_remarque."\") ;")); ##### Si on met NULL dans web, ça déconne
    $sth = $dbh->query("INSERT INTO vendeur (vendeur_nom, vendeur_web, vendeur_remarques) VALUES (\"".$plus_vendeur_nom."\",\"".$plus_vendeur_web."\",\"".$plus_vendeur_remarque."\") ;");
        /* TODO : prévoir le cas où le vendeur existe déjà */
	$vendeur=return_last_id("vendeur_index","vendeur");
        // on ajoute cette entrée dans le tableau des vendeurs (utilisé pour le select)
	array_push($vendeurs, array("vendeur_index" => $vendeur, "vendeur_nom" => $plus_vendeur_nom, "vendeur_web" => $plus_vendeur_web, "vendeur_remarques" =>$plus_vendeur_remarque ) );
    }

    if ($contrat_type=="plus_contrat_type") {
	$sth = $dbh->query(str_replace("\"\"", "NULL","INSERT INTO contrat_type (contrat_type_cat) VALUES ('".$plus_contrat_type_nom."') ;"));
        /* TODO : prévoir le cas où le type de contrat existe déjà */
	$contrat_type=return_last_id("contrat_type_index","contrat_type");
        // on ajoute cette entrée dans le tableau des types de contrats (utilisé pour le select)
        array_push($types_contrats, array("contrat_type_index" => $contrat_type, "contrat_type_cat" => $plus_contrat_type_nom ) );
    }

    if ($contrat=="plus_contrat") {
//$sth = $dbh->query(str_replace("\"\"", "NULL","INSERT INTO contrat (contrat_nom, contrat_type) VALUES ('".$plus_contrat_nom."','".$contrat_type."') ;")); ##### Si on met NULL, ça déconne
		$sth = $dbh->query("INSERT INTO contrat (contrat_nom, contrat_type) VALUES ('".$plus_contrat_nom."','".$contrat_type."') ;");
        /* TODO : prévoir le cas où le contrat existe déjà */
	$contrat=return_last_id("contrat_index","contrat");
        // on ajoute cette entrée dans le tableau des types de contrats (utilisé pour le select)
        array_push($contrats, array("contrat_nom" => $plus_contrat_nom, "contrat_type" => $contrat_type ) );
    }

    if ($tutelle=="plus_tutelle") {
        $sth = $dbh->query(str_replace("\"\"", "NULL","INSERT INTO tutelle (tutelle_nom) VALUES ('".$plus_tutelle."') ;"));
        /* TODO : prévoir le cas où le contrat existe déjà */
	$tutelle=return_last_id("tutelle_index","tutelle");
        // on ajoute cette entrée dans le tableau des types de contrats (utilisé pour le select)
        array_push($tutelles, array("tutelle_index" => $tutelle, "tutelle_nom" => $plus_tutelle ) );
    }

    if ($responsable_achat=="plus_responsable_achat") {
        $plus_responsable_achat_nom=mb_strtoupper($plus_responsable_achat_nom);
        $plus_responsable_achat_phone=phone_display("$plus_responsable_achat_phone","");
        $sth = $dbh->query(str_replace("\"\"", "NULL","INSERT INTO utilisateur (utilisateur_nom, utilisateur_prenom, utilisateur_mail, utilisateur_phone) VALUES ('".$plus_responsable_achat_nom."', '".$plus_responsable_achat_prenom."','".$plus_responsable_achat_mail."','".$plus_responsable_achat_phone."') ; "));
        /* TODO : prévoir le cas où le contrat existe déjà */
	$responsable_achat=return_last_id("utilisateur_index","utilisateur");
        // on ajoute cette entrée dans le tableau des types de contrats (utilisé pour le select)
        array_push($utilisateurs, array("utilisateur_index" => $responsable_achat, "utilisateur_nom" => $plus_responsable_achat_nom, "utilisateur_prenom" => $plus_responsable_achat_prenom, "utilisateur_mail" => $plus_responsable_achat_mail, "utilisateur_phone" => $plus_responsable_achat_phone) );
    }

// TODO : prix avec une virgule ou un point ?

/*  ╦ ╦╔═╗╔╦╗╔═╗╔╦╗╔═╗  ╔═╗╔═╗ ╦    ╔═╗ ╦ ╦╔═╗╦═╗╦ ╦
    ║ ║╠═╝ ║║╠═╣ ║ ║╣   ╚═╗║═╬╗║    ║═╬╗║ ║║╣ ╠╦╝╚╦╝
    ╚═╝╩  ═╩╝╩ ╩ ╩ ╚═╝  ╚═╝╚═╝╚╩═╝  ╚═╝╚╚═╝╚═╝╩╚═ ╩     */
    $date_achat=($date_achat==NULL) ? "0000-00-00" : $date_achat;
    $garantie=($garantie==NULL) ? "0000-00-00" : $garantie;

    $modif_result = $dbh->query(str_replace("\"\"", "NULL","UPDATE base SET designation=\"".$designation."\", vendeur=\"".$vendeur."\", prix=\"".$prix."\", contrat=\"".$contrat."\", date_achat=\"".$date_achat."\", garantie=\"".$garantie."\", bon_commande=\"".$bon_commande."\", num_inventaire=\"".$num_inventaire."\", tutelle=\"".$tutelle."\", responsable_achat=\"".$responsable_achat."\" WHERE base.base_index = $i;"));

    $message.= (!isset($modif_result)) ? $message_error_modif : $message_success_modif;

    // Avant d’afficher on doit ajouter les nouvelles infos dans les array concernés…
    $data[0]["designation"]=$designation;
    $data[0]["vendeur"]=$vendeur;
    $data[0]["prix"]=$prix;
    $data[0]["contrat"]=$contrat;
    $data[0]["date_achat"]=$date_achat;
    $data[0]["garantie"]=$garantie;
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
    if ($write) echo "<form method=\"post\" action=\"?BASE=$database&i=".$i."".$quick."\">";

/*  ╔═╗╦═╗╔═╗╔╦╗╦ ╦╦╔╦╗
    ╠═╝╠╦╝║ ║ ║║║ ║║ ║
    ╩  ╩╚═╚═╝═╩╝╚═╝╩ ╩  */
    echo "<fieldset><legend>Produit</legend>";

        /* ########### designation ########### */
        echo "<label for=\"designation\" style=\"vertical-align: top;\">Désignation :</label>\n";
        echo "<input name=\"designation\" type=\"text\" id=\"designation\" size=\"34\"";
        echo "value=\""; if (isset($data[0])) { echo ($data[0]["designation"]!="") ? $data[0]["designation"] : "";} echo "\" ><br/>\n";

        /* ########### vendeur ########### */
        echo "<label for=\"vendeur\">Vendeur ";

		if (isset($data[0])) {
		if ( ($data[0]["vendeur"]!="0")&&(($data[0]["vendeur"]!="")) ) {
	    $keys = array_keys(array_column($vendeurs, 'vendeur_index'), $data[0]["vendeur"]); $key=$keys[0];
            echo " <a href=\"".$vendeurs[$key]["vendeur_web"]."\" title=\"site web\" target=\"_blank\"><strong>↗</strong></a>";
            echo "<abbr title=\"".$vendeurs[$key]["vendeur_remarques"]."\"><strong>ⓘ</strong></abbr>";
        }}
        echo " :</label>\n";
        echo "<select name=\"vendeur\" id=\"vendeur\" onchange=\"display(this,'plus_vendeur','plus_vendeur');\" >";
        echo "<option value=\"0\" "; if (isset($data[0])) {if ($data[0]["vendeur"]=="0") echo "selected";} echo ">— Aucun vendeur spécifié —</option>";
        echo "<option value=\"plus_vendeur\" "; if (isset($data[0])) {if ($data[0]["vendeur"]=="plus_vendeur") echo "selected";} echo ">− Nouveau vendeur : −</option>";
        option_selecteur( (isset($data[0])) ? $data[0]["vendeur"] : "" , $vendeurs, "vendeur_index", "vendeur_nom");
        echo "</select>";
        echo "<br/>";

        /* ########### + vendeur ########### */
        echo "\n\n\n";
        echo "<fieldset id=\"plus_vendeur\" class=\"subfield\" style=\"display: none;\"><legend  class=\"subfield\">Nouveau Vendeur</legend>";
            echo "<label for=\"plus_vendeur_nom\">Nom :</label>\n";
                $deja_vendeur=dejadanslabase("SELECT DISTINCT `vendeur_nom` FROM `vendeur` ");
                echo "<input value=\"\" name=\"plus_vendeur_nom\" type=\"text\" pattern=\"^(?!(".$deja_vendeur.")$).*$\" oninvalid=\"setCustomValidity('Déjà dans la base')\" oninput=\"setCustomValidity('')\" /><br/>\n";
            echo "<label for=\"plus_vendeur_web\">Site web :</label>\n";   	echo "<input value=\"\" name=\"plus_vendeur_web\" type=\"text\"><br/>\n";
            echo "<label for=\"plus_vendeur_remarque\">Remarque :</label>\n";	echo "<input value=\"\" name=\"plus_vendeur_remarque\" type=\"text\"><br/>\n";
        echo "</fieldset>";
        echo "\n\n\n";

        /* ########### prix ########### */
        echo "<label for=\"prix\">Prix (€) : </label>\n";
        echo "<input value=\""; if(isset($data[0])) {echo ($data[0]["prix"]!="0") ? $data[0]["prix"] : "";} echo "\" name=\"prix\" type=\"text\" id=\"prix\" pattern=\"^[0-9]{1,10}$\"><br/>";

    echo "</fieldset>";

/*  ╔═╗╔═╗╦ ╦╔═╗╔╦╗╔═╗╦ ╦╦═╗
    ╠═╣║  ╠═╣║╣  ║ ║╣ ║ ║╠╦╝
    ╩ ╩╚═╝╩ ╩╚═╝ ╩ ╚═╝╚═╝╩╚═    */
    echo "<fieldset><legend>Acheteur</legend>";

        /* ########### contrat ########### */
        echo "<label for=\"contrat\">Contrat : </label>\n";
        echo "<select name=\"contrat\" onchange=\"display(this,'plus_contrat','plus_contrat');\" id=\"contrat\">";
        echo "<option value=\"0\" "; if (isset($data[0])) {if ($data[0]["contrat"]=="0") echo "selected";} echo ">— Aucun contrat spécifié —</option>";
        echo "<option value=\"plus_contrat\" "; if (isset($data[0])) {if ($data[0]["contrat"]=="plus_contrat") echo "selected";} echo ">− Nouveau contrat : −</option>";
        option_selecteur( (isset($data[0])) ? $data[0]["contrat"] : "", $contrats, "contrat_index", "contrat_nom");
        echo "</select><br/>";

        /* ########### + contrat ########### */
        echo "\n\n\n";
        echo "<fieldset id=\"plus_contrat\" class=\"subfield\" style=\"display: none;\"><legend class=\"subfield\">Nouveau Contrat</legend>";

            echo "<label for=\"plus_contrat_nom\">Nom :</label>\n";
            $deja_contrat=dejadanslabase("SELECT DISTINCT `contrat_nom` FROM `contrat`");
            echo "<input value=\"\" name=\"plus_contrat_nom\" type=\"text\" pattern=\"^(?!(".$deja_contrat.")$).*$\" oninvalid=\"setCustomValidity('Déjà dans la base')\"  oninput=\"setCustomValidity('')\" /><br/>\n";

            echo "<label for=\"contrat_type\">Type de contrat :</label>\n";
               echo "<select name=\"contrat_type\" onchange=\"display(this,'plus_contrat_type','plus_contrat_type');\" id=\"contrat_type\">";
                   echo "<option value=\"0\" selected >— Aucun type de contrat spécifié —</option>";
                   echo "<option value=\"plus_contrat_type\" >− Nouveau type de contrat : −</option>";
                   option_selecteur("0", $types_contrats, "contrat_type_index", "contrat_type_cat");
               echo "</select><br/>";

                    /* ########### + type contrat ########### */
                    echo "\n\n\n";
                    echo "<fieldset id=\"plus_contrat_type\" class=\"subfield\" style=\"display: none;\"><legend class=\"subfield\">Nouveau Type de contrat</legend>";
                        echo "<label for=\"plus_contrat_type_nom\">Type :</label>\n";

                        $deja_contrattype=dejadanslabase("SELECT DISTINCT `contrat_type_cat` FROM `contrat_type`");
                        echo "<input value=\"\" name=\"plus_contrat_type_nom\" type=\"text\"  pattern=\"^(?!(".$deja_contrattype.")$).*$\" oninvalid=\"setCustomValidity('Déjà dans la base')\"  oninput=\"setCustomValidity('')\" / >\n";
                    echo "</fieldset>";
                    echo "\n\n\n";

        echo "</fieldset>";
        echo "\n\n\n";

        /* ########### tutelle ########### */
        echo "<label for=\"tutelle\">Tutelle : </label>\n";
        echo "<select name=\"tutelle\" onchange=\"display(this,'plus_tutelle','plus_tutelle');\" id=\"tutelle\">";
        echo "<option value=\"0\" "; if (isset($data[0])) {if ($data[0]["tutelle"]=="") echo "selected";} echo ">— Aucune tutelle spécifiée —</option>";
        echo "<option value=\"plus_tutelle\" "; if (isset($data[0])) { if ($data[0]["tutelle"]=="plus_tutelle") echo "selected";} echo ">− Nouvelle tutelle : −</option>";
        option_selecteur( (isset($data[0])) ? $data[0]["tutelle"] : "" , $tutelles, "tutelle_index", "tutelle_nom");
        echo "</select><br/>";

            /* ########### + tutelle ########### */
            echo "\n\n\n";
            echo "<fieldset id=\"plus_tutelle\" class=\"subfield\" style=\"display: none;\"><legend class=\"subfield\">Nouvelle tutelle</legend>";
                echo "<label for=\"plus_tutelle\">Tutelle :</label>\n";

                $deja_tut=dejadanslabase("SELECT DISTINCT `tutelle_nom` FROM `tutelle`");
                echo "<input value=\"\" name=\"plus_tutelle\" type=\"text\" pattern=\"^(?!(".$deja_tut.")$).*$\" oninvalid=\"setCustomValidity('Déjà dans la base')\"  oninput=\"setCustomValidity('')\"  / >\n";
            echo "</fieldset>";
            echo "\n\n\n";

        /* ########### bon de commande ########### */
        echo "<label for=\"bon_commande\">Bon de commande : </label>\n";
        echo "<input value=\""; if (isset($data[0])) echo $data[0]["bon_commande"]; echo "\" name=\"bon_commande\" type=\"text\" id=\"bon_commande\">";
        echo "<br/>";

        /* ########### num_inventaire ########### */
        echo "<label for=\"num_inventaire\">N° d’inventaire : </label>\n";
        echo "<input value=\""; if (isset($data[0])) echo $data[0]["num_inventaire"]; echo "\" name=\"num_inventaire\" type=\"text\" id=\"num_inventaire\">";
        echo "<br/>";

        /* ########### responsable_achat ########### */
        echo "<label for=\"responsable_achat\">Acheteur ";

		$d= (isset($data[0])) ? $data[0]["responsable_achat"] : "";
		$keys = array_keys(array_column($utilisateurs, 'utilisateur_index'), $d); if (isset($keys[0])) {$key=$keys[0];}

		if (isset($data[0]))
		{ if ( ($data[0]["responsable_achat"]!="0")&&(($data[0]["responsable_achat"]!="")) ) {
            echo "<a href=\"mailto:".$utilisateurs[$key]["utilisateur_mail"]."\" title=\"".$utilisateurs[$key]["utilisateur_mail"]."\"><strong>✉</strong></a> ";
            echo "<abbr title=\"".phone_display("".$utilisateurs[$key]["utilisateur_phone"]."",".")."\"><strong>☏</strong></abbr>";
        }}

        echo ": </label>\n";
        echo "<select name=\"responsable_achat\" onchange=\"display(this,'plus_responsable_achat','plus_responsable_achat');\" id=\"responsable_achat\">";
        echo "<option value=\"0\" "; if (isset($data[0])) { if ($data[0]["responsable_achat"]=="0") echo "selected";} echo ">— Aucun responsable achat spécifié —</option>";
        echo "<option value=\"plus_responsable_achat\" "; if (isset($data[0])) {if ($data[0]["responsable_achat"]=="plus_responsable_achat") echo "selected";} echo ">− Nouveau responsable achat : −</option>";
        option_selecteur( (isset($data[0])) ? $data[0]["responsable_achat"] : "" , $utilisateurs, "utilisateur_index", "utilisateur_nom", "utilisateur_prenom");
        echo "</select>";

            /* ########### + responsable_achat ########### */
            echo "\n\n\n";
            echo "<fieldset id=\"plus_responsable_achat\" class=\"subfield\" style=\"display: none;\"><legend class=\"subfield\">Nouveau responsable achat</legend>";
                echo "<label for=\"plus_responsable_achat_prenom\">Prénom :</label>\n";				echo "<input value=\"\" name=\"plus_responsable_achat_prenom\" type=\"text\"><br/>\n";
                echo "<label for=\"plus_responsable_achat_nom\">NOM :</label>\n";				echo "<input value=\"\" name=\"plus_responsable_achat_nom\" type=\"text\"><br/>\n";
                echo "<label for=\"plus_responsable_achat_mail\">Mail :</label>\n";				echo "<input value=\"\" name=\"plus_responsable_achat_mail\" type=\"text\"><br/>\n";
                echo "<label for=\"plus_responsable_achat_phone\"><abbr title=\"juste les chiffres sans séparateur\">Téléphone</abbr> :</label>\n";	echo "<input value=\"\" name=\"plus_responsable_achat_phone\" type=\"number\" /><br/>\n";
            echo "</fieldset>";
            echo "\n\n\n";

    echo "</fieldset>";

/*  ╔╦╗╔═╗╔╦╗╔═╗╔═╗
     ║║╠═╣ ║ ║╣ ╚═╗
    ═╩╝╩ ╩ ╩ ╚═╝╚═╝  */
    echo "<fieldset><legend>Dates</legend>";

        /* ########### date_achat ########### */
        echo "<label for=\"achat\">Achat <abbr title=\"si aucun calendrier n’aide à la saisie : YYYY-MM-DD ; si jour ou mois inconnu → 01\"><strong>ⓘ</strong></abbr> :</label>\n";
	echo "<input value=\"";		if (isset($data[0])) {if ($data[0]["date_achat"]!="0000-00-00") echo $data[0]["date_achat"];} echo "\" name=\"date_achat\" type=\"date\" id=\"achat\"/><br/>";

        /* ########### garantie ########### */
        echo "<label for=\"garantie\">Fin de garantie <abbr title=\"si aucun calendrier n’aide à la saisie : YYYY-MM-DD ; si jour ou mois inconnu → 01\"><strong>ⓘ</strong></abbr> :</label>\n";
        echo "<input value=\""; if (isset($data[0])) {if ($data[0]["garantie"]!="0000-00-00") echo $data[0]["garantie"];} echo "\" name=\"garantie\" type=\"date\" id=\"garantie\" /><br/>";

    echo "</fieldset>";

/*  ╔═╗╦ ╦╔╗ ╔╦╗╦╔╦╗
    ╚═╗║ ║╠╩╗║║║║ ║
    ╚═╝╚═╝╚═╝╩ ╩╩ ╩     */
    if ($write) echo "<p style=\"text-align:center;\"><input name=\"administratif_valid\" value=\"Enregistrer\" type=\"submit\" class=\"little_button\" /></p>"; // TODO Ajouter un bouton réinitialiser
    if ($write) echo "</form>";

echo "</div>";

?>
