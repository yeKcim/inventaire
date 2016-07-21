<?php
$titre="Ajouter une entrée";
require_once("./connect.php");
require_once("./tables_sql_commun.php");
require_once("./head.php");
?>

<body>

<?php

require_once("./fonctions.php");
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
    $arr = array("categorie", "plus_categorie_nom", "plus_categorie_abbr", "marque", "plus_marque_nom", "reference", "serial_number", "plus_tags", "designation", "vendeur", "plus_vendeur_nom", "plus_vendeur_web", "plus_vendeur_remarque", "prix", "contrat", "plus_contrat_nom", "contrat_type", "plus_contrat_type_nom", "tutelle", "plus_tutelle", "bon_commande", "num_inventaire", "responsable_achat", "plus_responsable_achat_prenom", "plus_responsable_achat_nom", "plus_responsable_achat_mail", "plus_responsable_achat_phone", "date_achat", "garantie", "utilisateur", "plus_utilisateur_prenom", "plus_utilisateur_nom", "plus_utilisateur_mail", "plus_utilisateur_phone", "localisation", "plus_localisation_bat", "plus_localisation_piece", "sortie", "raison_sortie", "plus_raison_sortie_nom", "integration");
    foreach ($arr as &$value) {
        $data["$value"]= isset($_POST[$value]) ? htmlentities($_POST[$value]) : "" ;
    }

    // ╦  ╦╔═╗╦═╗╦╔═╗╦╔═╗╔═╗╔╦╗╦╔═╗╔╗╔  ╔╦╗╦╔╗╔╦╔╦╗╦ ╦╔╦╗  ╦╔╗╔╔═╗╦ ╦╔╦╗
    // ╚╗╔╝║╣ ╠╦╝║╠╣ ║║  ╠═╣ ║ ║║ ║║║║  ║║║║║║║║║║║║ ║║║║  ║║║║╠═╝║ ║ ║ 
    //  ╚╝ ╚═╝╩╚═╩╚  ╩╚═╝╩ ╩ ╩ ╩╚═╝╝╚╝  ╩ ╩╩╝╚╝╩╩ ╩╚═╝╩ ╩  ╩╝╚╝╩  ╚═╝ ╩ 
    if ( ($data["categorie"]=="0")&&($data["designation"]=="") ) $error.="<p class=\"error_message\">Merci de remplir au minimum Administratif→Désignation ou Technique→Catégorie</p>";
    
    
    // ╔═╗ ╦╔═╗╦ ╦╔╦╗  ╔╗╔╔═╗╦ ╦╦  ╦╔═╗╔═╗╦ ╦  ╦  ╦╔═╗╔╗╔╔╦╗╔═╗╦ ╦╦═╗
    // ╠═╣ ║║ ║║ ║ ║   ║║║║ ║║ ║╚╗╔╝║╣ ╠═╣║ ║  ╚╗╔╝║╣ ║║║ ║║║╣ ║ ║╠╦╝
    // ╩ ╩╚╝╚═╝╚═╝ ╩   ╝╚╝╚═╝╚═╝ ╚╝ ╚═╝╩ ╩╚═╝   ╚╝ ╚═╝╝╚╝═╩╝╚═╝╚═╝╩╚═
    if ($data["vendeur"]=="plus_vendeur") {
        if ($data["plus_vendeur_nom"]=="") {
            $error.="<p class=\"error_message\">Merci de remplir au minimum le nom du nouveau vendeur</p>";
            $data["vendeur"]="0";
        }
        else {
            mysql_query ("INSERT INTO vendeur (vendeur_nom, vendeur_web, vendeur_remarques) VALUES (\"".$data["plus_vendeur_nom"]."\",\"".$data["plus_vendeur_web"]."\",\"".$data["plus_vendeur_remarque"]."\") ; ");
            // TODO : prévoir le cas où existe déjà
            $query_table_vendeurnew = mysql_query ("SELECT vendeur_index FROM vendeur ORDER BY vendeur_index DESC LIMIT 1 ;");
            while ($l = mysql_fetch_row($query_table_vendeurnew)) $data["vendeur"]=$l[0];
            // on ajoute cette entrée dans le tableau des vendeurs (utilisé pour le select)
            $vendeurs[$data["vendeur"]]=array($data["vendeur"],"".utf8_encode($plus_vendeur_nom)."","".utf8_encode($plus_vendeur_web)."","".utf8_encode($plus_vendeur_remarque)."");
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
            mysql_query ("INSERT INTO contrat_type (contrat_type_cat) VALUES (\"".$data["plus_contrat_type_nom"]."\") ; ");
            /* TODO : prévoir le cas où existe déjà */
            $query_table_contrattypenew = mysql_query ("SELECT contrat_type_index FROM contrat_type ORDER BY contrat_type_index DESC LIMIT 1 ;");
            while ($l = mysql_fetch_row($query_table_contrattypenew)) $data["contrat_type"]=$l[0];
            // on ajoute cette entrée dans le tableau des types de contrats (utilisé pour le select)
            $types_contrats[$data["contrat_type"]]=array( $data["contrat_type"], "".utf8_encode($data["plus_contrat_type_nom"])."" );
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
            mysql_query ("INSERT INTO contrat (contrat_nom, contrat_type) VALUES (\"".$data["plus_contrat_nom"]."\",\"".$data["contrat_type"]."\") ; ");
            /* TODO : prévoir le cas où existe déjà */
            $query_table_contratnew = mysql_query ("SELECT contrat_index FROM contrat ORDER BY contrat_index DESC LIMIT 1 ;");
            while ($l = mysql_fetch_row($query_table_contratnew)) $data["contrat"]=$l[0];
            // on ajoute cette entrée dans le tableau des contrats (utilisé pour le select)
            $contrats[$data["contrat"]]=array( $data["contrat"], "".utf8_encode($data["plus_contrat_nom"])."", $data["contrat_type"] );
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
            mysql_query ("INSERT INTO tutelle (tutelle_nom) VALUES (\"".$data["plus_tutelle"]."\") ; ");
            /* TODO : prévoir le cas où existe déjà */
            $query_table_tutellenew = mysql_query ("SELECT tutelle_index FROM tutelle ORDER BY tutelle_index DESC LIMIT 1 ;");
            while ($l = mysql_fetch_row($query_table_tutellenew)) $data["tutelle"]=$l[0];
            // on ajoute cette entrée dans le tableau des types de contrats (utilisé pour le select)
            $tutelles[$data["tutelle"]]=array( $data["tutelle"], "".utf8_encode($data["plus_tutelle"]."") );
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
            
            mysql_query ("INSERT INTO utilisateur (utilisateur_nom, utilisateur_prenom, utilisateur_mail, utilisateur_phone) VALUES (\"".$data["plus_responsable_achat_nom"]."\", \"".$data["plus_responsable_achat_prenom"]."\",\"".$data["plus_responsable_achat_mail"]."\",\"".$data["plus_responsable_achat_phone"]."\") ; ");
            /* TODO : prévoir le cas où existe déjà */
            $query_table_utilisateurnew = mysql_query ("SELECT utilisateur_index FROM utilisateur ORDER BY utilisateur_index DESC LIMIT 1 ;");
            while ($l = mysql_fetch_row($query_table_utilisateurnew)) $data["responsable_achat"]=$l[0];
            // on ajoute cette entrée dans le tableau des utilisateurs (utilisé pour le select)
            $utilisateurs[$data["responsable_achat"]]=array( $data["responsable_achat"], "".utf8_encode($data["plus_responsable_achat_nom"])."", "".utf8_encode($data["plus_responsable_achat_prenom"])."", "".utf8_encode($data["plus_responsable_achat_mail"])."", "".phone_display("".$data["plus_responsable_achat_phone"]."",".")."" );
        }
    }

    // ╔╗╔╔═╗╦ ╦╦  ╦╔═╗╦  ╦  ╔═╗  ╔╦╗╔═╗╦═╗╔═╗ ╦ ╦╔═╗
    // ║║║║ ║║ ║╚╗╔╝║╣ ║  ║  ║╣   ║║║╠═╣╠╦╝║═╬╗║ ║║╣ 
    // ╝╚╝╚═╝╚═╝ ╚╝ ╚═╝╩═╝╩═╝╚═╝  ╩ ╩╩ ╩╩╚═╚═╝╚╚═╝╚═╝  */
    if ($data["marque"]=="plus_marque") {
        if ($data["plus_marque_nom"]=="") {
            $error.="<p class=\"error_message\">Merci de spécifier un nom de nouvelle marque</p>";
            $data["marque"]="0";
        }
        else {
            mysql_query ("INSERT INTO marque (marque_nom) VALUES (\"".$data["plus_marque_nom"]."\") ; ");
            /* TODO : prévoir le cas où existe déjà */
            $query_table_marquenew = mysql_query ("SELECT marque_index FROM marque ORDER BY marque_index DESC LIMIT 1 ;");
            while ($l = mysql_fetch_row($query_table_marquenew)) $data["marque"]=$l[0];
            // on ajoute cette entrée dans le tableau des marques (utilisé pour le select)
            $marques[$data["marque"]]=array( $data["marque"], "".utf8_encode($data["plus_marque_nom"])."" );
        }
    }
    

/*  ╔╗╔╔═╗╦ ╦╦  ╦╔═╗╦  ╦  ╔═╗  ╔═╗╔═╗╔╦╗╔═╗╔═╗╔═╗╦═╗╦╔═╗
    ║║║║ ║║ ║╚╗╔╝║╣ ║  ║  ║╣   ║  ╠═╣ ║ ║╣ ║ ╦║ ║╠╦╝║║╣ 
    ╝╚╝╚═╝╚═╝ ╚╝ ╚═╝╩═╝╩═╝╚═╝  ╚═╝╩ ╩ ╩ ╚═╝╚═╝╚═╝╩╚═╩╚═╝    */
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
            mysql_query ("INSERT INTO categorie (categorie_lettres, categorie_nom) VALUES (\"".$data["plus_categorie_abbr"]."\",\"".$data["plus_categorie_nom"]."\") ; ");
            /* TODO : prévoir le cas où existe déjà */
            $query_table_categorienew = mysql_query ("SELECT categorie_index FROM categorie ORDER BY categorie_index DESC LIMIT 1 ;");
            while ($l = mysql_fetch_row($query_table_categorienew)) $data["categorie"]=$l[0];
            // on ajoute cette entrée dans le tableau des catégories (utilisé pour le select)
            $categories[$data["categorie"]]=array( $data["categorie"],"".utf8_encode($data["plus_categorie_nom"])."","(".utf8_encode($data["plus_categorie_abbr"]).")" );
            // TODO Attention l’abréviation ne doit contenir que des lettres !
        }
    }
    


    $data["lab_id"]=new_lab_id($data["categorie"]);

    $data["date_achat"]=($data["date_achat"]=="") ? "0000-00-00" : dateformat($data["date_achat"],"en");
    $data["garantie"]=($data["garantie"]=="") ? "0000-00-00" : dateformat($data["garantie"],"en");
    // TODO : vérifier que les dates sont bien au bon format !

    if ($error=="") {
        
        // ╔═╗╔═╗╦  ╔═╗╦ ╦╦    ╔╦╗╦ ╦  ╔╗╔╔═╗╦ ╦╦  ╦╔═╗╔═╗╦ ╦  ╦
        // ║  ╠═╣║  ║  ║ ║║     ║║║ ║  ║║║║ ║║ ║╚╗╔╝║╣ ╠═╣║ ║  ║
        // ╚═╝╩ ╩╩═╝╚═╝╚═╝╩═╝  ═╩╝╚═╝  ╝╚╝╚═╝╚═╝ ╚╝ ╚═╝╩ ╩╚═╝  ╩
        $inew = mysql_query ("SELECT base_index FROM base ORDER BY base_index DESC LIMIT 1 ;");
        while ($l = mysql_fetch_row($inew)) $i=$l[0]+1;
        
        $add_result= mysql_query ("INSERT INTO base (base_index, lab_id, categorie, serial_number, reference, designation, utilisateur, localisation, date_localisation, tutelle, contrat, bon_commande, num_inventaire, vendeur, marque, date_achat, responsable_achat, garantie, prix, date_sortie, sortie, raison_sortie, integration) VALUES ('".$i."', '".$data["lab_id"]."', '".$data["categorie"]."', '".$data["serial_number"]."', '".$data["reference"]."', '".$data["designation"]."', '0', '0', '0000-00-00', '".$data["tutelle"]."', '".$data["contrat"]."', '".$data["bon_commande"]."', '".$data["num_inventaire"]."', '".$data["vendeur"]."', '".$data["marque"]."', '".$data["date_achat"]."', '".$data["responsable_achat"]."', '".$data["garantie"]."', '".$data["prix"]."', '0000-00-00', '0', '0', '0'); ");
        
        if ($add_result!=1) $error.="<p class=\"error_message\">Une erreur inconnue est survenue. L’entrée n’a pas été ajoutée.</p>";
        else {
            $success.="<p class=\"success_message\">";
            $success.="L’entrée a été ajoutée à la base de donnée.<br/>";
            $success.="Vous pouvez directement ajouter une nouvelle entrée<br/>";
            $success.="ou <a href=\"info.php?i=$i\" target=\"_blank\"><strong>→ Compléter les informations de ".$data["lab_id"]." #$i</strong></a>";
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

echo "</form>"

?>


</body>
</html>
