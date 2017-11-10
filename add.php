<?php
$titre="Ajouter une entrée";
require_once("./0_connect.php");
if ($database=="") require_once("./0_baseselector.php");
require_once("./0_connect_db.php");
require_once("./0_tables_sql_commun.php");
require_once("./0_head.php");
?>

<body>

<?php

require_once("./0_fonctions.php");
$error="";
$success="";

/*
 █████╗      ██╗ ██████╗ ██╗   ██╗████████╗    ███╗   ███╗██╗   ██╗███████╗ ██████╗ ██╗
██╔══██╗     ██║██╔═══██╗██║   ██║╚══██╔══╝    ████╗ ████║╚██╗ ██╔╝██╔════╝██╔═══██╗██║
███████║     ██║██║   ██║██║   ██║   ██║       ██╔████╔██║ ╚████╔╝ ███████╗██║   ██║██║
██╔══██║██   ██║██║   ██║██║   ██║   ██║       ██║╚██╔╝██║  ╚██╔╝  ╚════██║██║▄▄ ██║██║
██║  ██║╚█████╔╝╚██████╔╝╚██████╔╝   ██║       ██║ ╚═╝ ██║   ██║   ███████║╚██████╔╝███████╗
╚═╝  ╚═╝ ╚════╝  ╚═════╝  ╚═════╝    ╚═╝       ╚═╝     ╚═╝   ╚═╝   ╚══════╝ ╚══▀▀═╝ ╚══════╝
*/
if ( isset($_POST["add_valid"]) ) {
    $data=array();
    $arr = array("lab_id", "id_man", "categorie", "plus_categorie_nom", "plus_categorie_abbr", "marque", "plus_marque_nom", "reference", "serial_number", "plus_tags", "designation", "vendeur", "plus_vendeur_nom", "plus_vendeur_web", "plus_vendeur_remarque", "prix", "contrat", "plus_contrat_nom", "contrat_type", "plus_contrat_type_nom", "tutelle", "plus_tutelle", "bon_commande", "num_inventaire", "responsable_achat", "plus_responsable_achat_prenom", "plus_responsable_achat_nom", "plus_responsable_achat_mail", "plus_responsable_achat_phone", "date_achat", "garantie", "utilisateur", "plus_utilisateur_prenom", "plus_utilisateur_nom", "plus_utilisateur_mail", "plus_utilisateur_phone", "localisation", "plus_localisation_bat", "plus_localisation_piece", "sortie", "raison_sortie", "plus_raison_sortie_nom", "integration");
    foreach ($arr as &$value) {
        $data["$value"]= isset($_POST[$value]) ? htmlentities($_POST[$value]) : "" ;
    }

    // ╦  ╦╔═╗╦═╗╦╔═╗╦╔═╗╔═╗╔╦╗╦╔═╗╔╗╔  ╔╦╗╦╔╗╔╦╔╦╗╦ ╦╔╦╗  ╦╔╗╔╔═╗╦ ╦╔╦╗
    // ╚╗╔╝║╣ ╠╦╝║╠╣ ║║  ╠═╣ ║ ║║ ║║║║  ║║║║║║║║║║║║ ║║║║  ║║║║╠═╝║ ║ ║
    //  ╚╝ ╚═╝╩╚═╩╚  ╩╚═╝╩ ╩ ╩ ╩╚═╝╝╚╝  ╩ ╩╩╝╚╝╩╩ ╩╚═╝╩ ╩  ╩╝╚╝╩  ╚═╝ ╩
    if ( ($data["categorie"]=="0")&&($data["designation"]=="") ) $error.="<p class=\"error_message\">Merci de remplir au minimum Administratif→Désignation ou Technique→Catégorie</p>";



    // ╔═╗╦  ╦╔╦╗   ╔╦╗╔═╗╔╗╔  ╔═╗╔═╗╔╦╗  ╔╦╗╔═╗╔═╗╦╔╗╔╦
    // ╚═╗║  ║ ║║   ║║║╠═╣║║║  ║╣ ╚═╗ ║    ║║║╣ ╠╣ ║║║║║
    // ╚═╝╩  ╩═╩╝═══╩ ╩╩ ╩╝╚╝  ╚═╝╚═╝ ╩   ═╩╝╚═╝╚  ╩╝╚╝╩
    if ( ($data["lab_id"]=="manual_id")&&($data["id_man"]=="") ) $error.="<p class=\"error_message\">L’id manuel ne peut pas être laissé vide</p>";


    // ╔═╗ ╦╔═╗╦ ╦╔╦╗  ╔╗╔╔═╗╦ ╦╦  ╦╔═╗╔═╗╦ ╦  ╦  ╦╔═╗╔╗╔╔╦╗╔═╗╦ ╦╦═╗
    // ╠═╣ ║║ ║║ ║ ║   ║║║║ ║║ ║╚╗╔╝║╣ ╠═╣║ ║  ╚╗╔╝║╣ ║║║ ║║║╣ ║ ║╠╦╝
    // ╩ ╩╚╝╚═╝╚═╝ ╩   ╝╚╝╚═╝╚═╝ ╚╝ ╚═╝╩ ╩╚═╝   ╚╝ ╚═╝╝╚╝═╩╝╚═╝╚═╝╩╚═
    if ($data["vendeur"]=="plus_vendeur") {
        if ($data["plus_vendeur_nom"]=="") {
            $error.="<p class=\"error_message\">Merci de remplir au minimum le nom du nouveau vendeur</p>";
            $data["vendeur"]="0";
        }
        else {
	    $sth = $dbh->query("INSERT INTO vendeur (vendeur_nom, vendeur_web, vendeur_remarques) VALUES (\"".$data["plus_vendeur_nom"]."\",\"".$data["plus_vendeur_web"]."\",\"".$data["plus_vendeur_remarque"]."\") ;");
            // TODO : prévoir le cas où existe déjà
	    $data["vendeur"]=return_last_id("vendeur_index","vendeur");
            // on ajoute cette entrée dans le tableau des vendeurs (utilisé pour le select)
	    array_push($vendeurs, array("vendeur_index" => $data["vendeur"], "vendeur_nom" => $plus_vendeur_nom, "vendeur_web" => $plus_vendeur_web, "vendeur_remarques" => $plus_vendeur_remarque ) );
        }
    }

    // ╔═╗ ╦╔═╗╦ ╦╔╦╗  ╔╗╔╔═╗╦ ╦╦  ╦╔═╗╔═╗╦ ╦  ╔╦╗╦ ╦╔═╗╔═╗  ╔═╗╔═╗╔╗╔╦╗╦═╗╔═╗╔╦╗
    // ╠═╣ ║║ ║║ ║ ║   ║║║║ ║║ ║╚╗╔╝║╣ ╠═╣║ ║   ║ ╚╦╝╠═╝║╣   ║  ║ ║║║║║ ╠╦╝╠═╣ ║
    // ╩ ╩╚╝╚═╝╚═╝ ╩   ╝╚╝╚═╝╚═╝ ╚╝ ╚═╝╩ ╩╚═╝   ╩  ╩ ╩  ╚═╝  ╚═╝╚═╝╝╚╝╩ ╩╚═╩ ╩ ╩
    if ($data["contrat_type"]=="plus_contrat_type") {
        if ($data["plus_contrat_type_nom"]=="") {
            $error.="<p class=\"error_message\">Merci de spécifier un type de contrat</p>";
            $data["contrat_type"]="0";
        }
        else {
            $sth = $dbh->query("INSERT INTO contrat_type (contrat_type_cat) VALUES (\"".$data["plus_contrat_type_nom"]."\") ;");
            /* TODO : prévoir le cas où existe déjà */
	    $data["contrat_type"]=return_last_id("contrat_type_index","contrat_type");
            // on ajoute cette entrée dans le tableau des types de contrats (utilisé pour le select)
	    array_push($types_contrats, array("contrat_type_index" => $data["contrat_type"], "contrat_type_cat" => $data["plus_contrat_type_nom"] ) );
        }
    }

    // ╔═╗ ╦╔═╗╦ ╦╔╦╗  ╔╗╔╔═╗╦ ╦╦  ╦╔═╗╔═╗╦ ╦  ╔═╗╔═╗╔╗╔╦╗╦═╗╔═╗╔╦╗
    // ╠═╣ ║║ ║║ ║ ║   ║║║║ ║║ ║╚╗╔╝║╣ ╠═╣║ ║  ║  ║ ║║║║║ ╠╦╝╠═╣ ║
    // ╩ ╩╚╝╚═╝╚═╝ ╩   ╝╚╝╚═╝╚═╝ ╚╝ ╚═╝╩ ╩╚═╝  ╚═╝╚═╝╝╚╝╩ ╩╚═╩ ╩ ╩
    if ($data["contrat"]=="plus_contrat") {
        if ($data["plus_contrat_nom"]=="") {
            $error.="<p class=\"error_message\">Merci de spécifier le nom du nouveau contrat</p>";
            $data["contrat"]="0";
        }
        else {
            $sth = $dbh->query("INSERT INTO contrat (contrat_nom, contrat_type) VALUES (\"".$data["plus_contrat_nom"]."\",\"".$data["contrat_type"]."\") ;");
            /* TODO : prévoir le cas où existe déjà */
	    $data["contrat"]=return_last_id("contrat_index","contrat");
            // on ajoute cette entrée dans le tableau des contrats (utilisé pour le select)
	    array_push($contrats, array("contrat_index" => $data["contrat"], "contrat_nom" => $data["plus_contrat_nom"], "contrat_type" => $data["contrat_type"] ) );

        }
    }

    // ╔═╗ ╦╔═╗╦ ╦╔╦╗  ╔╗╔╔═╗╦ ╦╦  ╦╔═╗╦  ╦  ╔═╗  ╔╦╗╦ ╦╔╦╗╔═╗╦  ╦  ╔═╗
    // ╠═╣ ║║ ║║ ║ ║   ║║║║ ║║ ║╚╗╔╝║╣ ║  ║  ║╣    ║ ║ ║ ║ ║╣ ║  ║  ║╣
    // ╩ ╩╚╝╚═╝╚═╝ ╩   ╝╚╝╚═╝╚═╝ ╚╝ ╚═╝╩═╝╩═╝╚═╝   ╩ ╚═╝ ╩ ╚═╝╩═╝╩═╝╚═╝
    if ($data["tutelle"]=="plus_tutelle") {
        if ($data["plus_tutelle"]=="") {
            $error.="<p class=\"error_message\">Merci de spécifier un nom de nouvelle tutelle</p>";
            $data["tutelle"]="0";
        }
        else {
            $sth = $dbh->query("INSERT INTO tutelle (tutelle_nom) VALUES (\"".$data["plus_tutelle"]."\") ;");
            /* TODO : prévoir le cas où existe déjà */
	    $data["tutelle"]=return_last_id("tutelle_index","tutelle");
            // on ajoute cette entrée dans le tableau des types de contrats (utilisé pour le select)
	    array_push($tutelles, array("tutelle_index" => $data["tutelle"], "tutelle_nom" => $data["plus_tutelle"] ) );
        }
    }

    // ╔═╗ ╦╔═╗╦ ╦╔╦╗  ╔╗╔╔═╗╦ ╦╦  ╦╔═╗╦    ╔═╗╔═╗╦ ╦╔═╗╔╦╗╔═╗╦ ╦╦═╗
    // ╠═╣ ║║ ║║ ║ ║   ║║║║ ║║ ║╚╗╔╝║╣ ║    ╠═╣║  ╠═╣║╣  ║ ║╣ ║ ║╠╦╝
    // ╩ ╩╚╝╚═╝╚═╝ ╩   ╝╚╝╚═╝╚═╝ ╚╝ ╚═╝╩═╝  ╩ ╩╚═╝╩ ╩╚═╝ ╩ ╚═╝╚═╝╩╚═
    if ($data["responsable_achat"]=="plus_responsable_achat") {
        if ($data["plus_responsable_achat_nom"]=="") {
            $error.="<p class=\"error_message\">Merci de spécifier au moins un nom pour le nouveau responsable d’achat</p>";
            $data["responsable_achat"]="0";
        }
        else {
            $data["plus_responsable_achat_nom"]=mb_strtoupper($data["plus_responsable_achat_nom"]);
            $data["plus_responsable_achat_phone"]=phone_display("".$data["plus_responsable_achat_phone"]."","");

	    $sth = $dbh->query("INSERT INTO utilisateur (utilisateur_nom, utilisateur_prenom, utilisateur_mail, utilisateur_phone) VALUES (\"".$data["plus_responsable_achat_nom"]."\", \"".$data["plus_responsable_achat_prenom"]."\",\"".$data["plus_responsable_achat_mail"]."\",\"".$data["plus_responsable_achat_phone"]."\") ; ");
            /* TODO : prévoir le cas où existe déjà */
	    $data["responsable_achat"]=return_last_id("utilisateur_index","utilisateur");
            // on ajoute cette entrée dans le tableau des utilisateurs (utilisé pour le select)
	    array_push($utilisateurs, array("utilisateur_index" => $data["responsable_achat"], "utilisateur_nom" => $data["plus_responsable_achat_nom"], "utilisateur_prenom" => $data["plus_responsable_achat_prenom"], "utilisateur_mail" => $data["plus_responsable_achat_mail"], "utilisateur_phone" => phone_display("".$data["plus_responsable_achat_phone"]."",".") ) );
        }
    }

    // ╔╗╔╔═╗╦ ╦╦  ╦╔═╗╦  ╦  ╔═╗  ╔╦╗╔═╗╦═╗╔═╗ ╦ ╦╔═╗
    // ║║║║ ║║ ║╚╗╔╝║╣ ║  ║  ║╣   ║║║╠═╣╠╦╝║═╬╗║ ║║╣
    // ╝╚╝╚═╝╚═╝ ╚╝ ╚═╝╩═╝╩═╝╚═╝  ╩ ╩╩ ╩╩╚═╚═╝╚╚═╝╚═╝
    if ($data["marque"]=="plus_marque") {
        if ($data["plus_marque_nom"]=="") {
            $error.="<p class=\"error_message\">Merci de spécifier un nom de nouvelle marque</p>";
            $data["marque"]="0";
        }
        else {
	    $sth = $dbh->query("INSERT INTO marque (marque_nom) VALUES (\"".$data["plus_marque_nom"]."\") ;");
            /* TODO : prévoir le cas où existe déjà */
            $data["marque"]=return_last_id("marque_index","marque");
	    // on ajoute cette entrée dans le tableau des marques (utilisé pour le select)
	    array_push($marques, array("marque_index" => $data["marque"], "marque_nom" => $data["plus_marque_nom"] ) );
        }
    }

    //  ╔╗╔╔═╗╦ ╦╦  ╦╔═╗╦  ╦  ╔═╗  ╔═╗╔═╗╔╦╗╔═╗╔═╗╔═╗╦═╗╦╔═╗
    //  ║║║║ ║║ ║╚╗╔╝║╣ ║  ║  ║╣   ║  ╠═╣ ║ ║╣ ║ ╦║ ║╠╦╝║║╣
    //  ╝╚╝╚═╝╚═╝ ╚╝ ╚═╝╩═╝╩═╝╚═╝  ╚═╝╩ ╩ ╩ ╚═╝╚═╝╚═╝╩╚═╩╚═╝
    if ($data["categorie"]=="plus_categorie") {
        if ( ($data["plus_categorie_nom"]=="") || ($data["plus_categorie_abbr"]=="") ) {
            $error.="<p class=\"error_message\">Merci de spécifier un nom et une abbréviation pour la nouvelle catégorie</p>";
            $data["categorie"]="0";
        }
        //elseif ( !ctype_alpha($data["plus_categorie_abbr"]) ) {
        //    $error.="<p class=\"error_message\">Attention, l’abbréviation doit contenir uniquement des lettres</p>";
        //    $data["categorie"]="0";
        //} Cette fonction pose problème pour les caractères spéciaux, TODO trouver une méthode pour interdire les chiffres…
        else {
            $sth = $dbh->query("INSERT INTO categorie (categorie_lettres, categorie_nom) VALUES (\"".$data["plus_categorie_abbr"]."\",\"".$data["plus_categorie_nom"]."\") ;") ;
            /* TODO : prévoir le cas où existe déjà */
	    $data["categorie"]=return_last_id("categorie_index", "categorie");
            // on ajoute cette entrée dans le tableau des catégories (utilisé pour le select)
	    array_push($categories, array("categorie_index" => $data["categorie"], "categorie_nom" => $data["plus_categorie_nom"], "categorie_lettres" => $data["plus_categorie_abbr"] ) );
            // TODO Attention l’abréviation ne doit contenir que des lettres !
        }
    }

    if ($data["lab_id"]=="manual_id")  $data["lab_id"]=$data["id_man"];
    else $data["lab_id"]=new_lab_id($data["categorie"]);

    $data["date_achat"]=($data["date_achat"]=="") ? "0000-00-00" : $data["date_achat"];
    $data["garantie"]=($data["garantie"]=="") ? "0000-00-00" : $data["garantie"];
    // TODO : vérifier que les dates sont bien au bon format !

    if ($error=="") {
        // ╔═╗╔═╗╦  ╔═╗╦ ╦╦    ╔╦╗╦ ╦  ╔╗╔╔═╗╦ ╦╦  ╦╔═╗╔═╗╦ ╦  ╦
        // ║  ╠═╣║  ║  ║ ║║     ║║║ ║  ║║║║ ║║ ║╚╗╔╝║╣ ╠═╣║ ║  ║
        // ╚═╝╩ ╩╩═╝╚═╝╚═╝╩═╝  ═╩╝╚═╝  ╝╚╝╚═╝╚═╝ ╚╝ ╚═╝╩ ╩╚═╝  ╩
	$i=return_last_id("base_index", "base") + 1;

	$add_result = $dbh->query("INSERT INTO base (base_index, lab_id, categorie, serial_number, reference, designation, utilisateur, localisation, date_localisation, tutelle, contrat, bon_commande, num_inventaire, vendeur, marque, date_achat, responsable_achat, garantie, prix, date_sortie, sortie, raison_sortie, integration) VALUES ('".$i."', '".$data["lab_id"]."', '".$data["categorie"]."', '".$data["serial_number"]."', '".$data["reference"]."', '".$data["designation"]."', '0', '0', '0000-00-00', '".$data["tutelle"]."', '".$data["contrat"]."', '".$data["bon_commande"]."', '".$data["num_inventaire"]."', '".$data["vendeur"]."', '".$data["marque"]."', '".$data["date_achat"]."', '".$data["responsable_achat"]."', '".$data["garantie"]."', '".$data["prix"]."', '0000-00-00', '0', '0', '0') ;" );
        if (!isset($add_result)) $error.=$message_error_add;
        else {
            $success.="<p class=\"success_message\">";
            $success.="L’entrée a été ajoutée à la base de donnée.<br/>";
            $success.="Vous pouvez directement ajouter une nouvelle entrée<br/>";
            $success.="ou <a href=\"info.php?BASE=$database&i=$i\" target=\"_blank\"><strong>→ Compléter les informations de ".$data["lab_id"]." #$i</strong></a>";
            $success.="</p>";

            $data=array(
                "base_index"=>$i,              "lab_id"=>"",                "categorie"=>"0",
                "serial_number"=>"",           "reference"=>"",             "designation"=>"",
                "utilisateur"=>"0",            "localisation"=>"",          "date_localisation"=>"",
                "tutelle"=>"0",                "contrat"=>"0",              "bon_commande"=>"",
                "num_inventaire"=>"",
                "vendeur"=>"0",                "marque"=>"0",               "date_achat"=>"",
                "responsable_achat"=>"0",      "garantie"=>"",              "prix"=>"",
                "date_sortie"=>"",             "sortie"=>"",                "raison_sortie"=>"",
                "integration"=>""   );
        }
    }
}
/*
███████╗██╗███╗   ██╗ ██████╗ ███╗   ██╗    ██╗███╗   ██╗██╗████████╗    ██╗   ██╗ █████╗ ██████╗ ██╗ █████╗ ██████╗ ██╗     ███████╗███████╗
██╔════╝██║████╗  ██║██╔═══██╗████╗  ██║    ██║████╗  ██║██║╚══██╔══╝    ██║   ██║██╔══██╗██╔══██╗██║██╔══██╗██╔══██╗██║     ██╔════╝██╔════╝
███████╗██║██╔██╗ ██║██║   ██║██╔██╗ ██║    ██║██╔██╗ ██║██║   ██║       ██║   ██║███████║██████╔╝██║███████║██████╔╝██║     █████╗  ███████╗
╚════██║██║██║╚██╗██║██║   ██║██║╚██╗██║    ██║██║╚██╗██║██║   ██║       ╚██╗ ██╔╝██╔══██║██╔══██╗██║██╔══██║██╔══██╗██║     ██╔══╝  ╚════██║
███████║██║██║ ╚████║╚██████╔╝██║ ╚████║    ██║██║ ╚████║██║   ██║        ╚████╔╝ ██║  ██║██║  ██║██║██║  ██║██████╔╝███████╗███████╗███████║
╚══════╝╚═╝╚═╝  ╚═══╝ ╚═════╝ ╚═╝  ╚═══╝    ╚═╝╚═╝  ╚═══╝╚═╝   ╚═╝         ╚═══╝  ╚═╝  ╚═╝╚═╝  ╚═╝╚═╝╚═╝  ╚═╝╚═════╝ ╚══════╝╚══════╝╚══════╝
*/
else { // Initialisation de toutes les variable
    $data=array(
        "base_index"=>$i,              "lab_id"=>"",                "categorie"=>"0",
        "serial_number"=>"",           "reference"=>"",             "designation"=>"",
        "utilisateur"=>"0",            "localisation"=>"",          "date_localisation"=>"",
        "tutelle"=>"0",                "contrat"=>"0",              "bon_commande"=>"",
        "num_inventaire"=>"",
        "vendeur"=>"0",                "marque"=>"0",               "date_achat"=>"",
        "responsable_achat"=>"0",      "garantie"=>"",              "prix"=>"",
        "date_sortie"=>"",             "sortie"=>"",                "raison_sortie"=>"",
        "integration"=>""   );
}




/*
██████╗ ██╗███████╗██████╗ ██╗      █████╗ ██╗   ██╗    ██████╗ ██╗      ██████╗  ██████╗███████╗
██╔══██╗██║██╔════╝██╔══██╗██║     ██╔══██╗╚██╗ ██╔╝    ██╔══██╗██║     ██╔═══██╗██╔════╝██╔════╝
██║  ██║██║███████╗██████╔╝██║     ███████║ ╚████╔╝     ██████╔╝██║     ██║   ██║██║     ███████╗
██║  ██║██║╚════██║██╔═══╝ ██║     ██╔══██║  ╚██╔╝      ██╔══██╗██║     ██║   ██║██║     ╚════██║
██████╔╝██║███████║██║     ███████╗██║  ██║   ██║       ██████╔╝███████╗╚██████╔╝╚██████╗███████║
╚═════╝ ╚═╝╚══════╝╚═╝     ╚══════╝╚═╝  ╚═╝   ╚═╝       ╚═════╝ ╚══════╝ ╚═════╝  ╚═════╝╚══════╝
*/

$write=false;
$fieldset_tags="Cette fonctionnalité n’est activée qu’une fois l’entrée enregistrée dans la base.";
$fieldset_compatibilite="Cette fonctionnalité n’est activée qu’une fois l’entrée enregistrée dans la base.";

echo "<p>Ajouter une entrée :</p>";

echo $success;

echo "<form method=\"post\" action=\"\">";

echo "<div id=\"container\">";
    require_once("./blocs/administratif.php");
    require_once("./blocs/technique.php");


    /*  ╔═╗╦ ╦╔╗ ╔╦╗╦╔╦╗
        ╚═╗║ ║╠╩╗║║║║ ║
        ╚═╝╚═╝╚═╝╩ ╩╩ ╩     */
    echo "<div id=\"bloc\" style=\"background:#f3f3f3; vertical-align:top;\">";
    echo "<h1>Validation</h1>";
    echo "<p style=\"text-align:center;\">";
    echo "<input name=\"add_valid\" value=\"Ajouter\" type=\"submit\" class=\"big_button\" />";
    echo "</p>"; // TODO Ajouter un bouton réinitialiser

    echo $error;

    echo "</div>";

echo "</div>";

echo "</form>";

?>


</body>
</html>

<?php
$dbh = null;
?>
