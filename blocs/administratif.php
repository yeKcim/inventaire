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

if ($sth) $sth->closeCursor();

// Récupération des tutelles
$sth = $dbh->prepare("SELECT * FROM tutelle WHERE tutelle_index!=0 ORDER BY tutelle_nom ASC;");
$sth->execute();
$tutelles = $sth->fetchAll(PDO::FETCH_ASSOC);

// Récupération des vendeurs
$sth = $dbh->prepare("SELECT * FROM vendeur WHERE vendeur_index!=0 ORDER BY vendeur_nom ASC;");
$sth->execute();
$vendeurs = $sth->fetchAll(PDO::FETCH_ASSOC);

// Récupération des contrats
$sth = $dbh->prepare("SELECT DISTINCT contrat_index, contrat_nom, contrat_type 
                      FROM contrat 
                      WHERE contrat_index!=0 
                      ORDER BY contrat_nom ASC;");
$sth->execute();
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
        $$value= isset($_POST[$value]) ? trim($_POST[$value]) : "" ;
    }

    /* ########### Ajout d’un nouveau vendeur ########### */

	if ($vendeur == "plus_vendeur") {
		if (!empty($plus_vendeur_nom)) { // Vérifier que le nom n’est pas vide
		    $sth = $dbh->prepare("INSERT INTO vendeur (vendeur_nom, vendeur_web, vendeur_remarques) VALUES (?, ?, ?)");
		    $sth->execute([$plus_vendeur_nom, $plus_vendeur_web, $plus_vendeur_remarque]);
		    $vendeur = return_last_id("vendeur_index", "vendeur"); // $vendeur devrait contenir l'ID numérique
			$sth->closeCursor();

			// Ajout dans le tableau des vendeurs
			$vendeurs[] = [
			    "vendeur_index" => $vendeur,
			    "vendeur_nom" => $plus_vendeur_nom,
			    "vendeur_web" => $plus_vendeur_web,
			    "vendeur_remarques" => $plus_vendeur_remarque
			];
		} else {
			   		echo "<p class='error_message'>Erreur : Le nom du nouveau vendeur est vide, l’entrée a été définie comme non spécifiée.</p>";
			   		$vendeur="0";
		}
	}

	if ($contrat_type == "plus_contrat_type") {

		$sth = $dbh->prepare("INSERT INTO contrat_type (contrat_type_cat) VALUES (:plus_contrat_type_nom)");				// Prépare la requête avec un paramètre lié
		$sth->execute([':plus_contrat_type_nom' => !empty($plus_contrat_type_nom) ? $plus_contrat_type_nom : null]);		// Exécute avec une valeur NULL si la variable est vide
		$contrat_type = return_last_id("contrat_type_index", "contrat_type");												// Récupère l'ID du dernier enregistrement
		array_push($types_contrats, ["contrat_type_index" => $contrat_type,"contrat_type_cat" => $plus_contrat_type_nom]);	// Ajoute l'entrée dans le tableau
		$sth->closeCursor();																								// Ferme le curseur
	}
    
/* ########### Ajout d’un nouveau contrat ########### */
	if ($contrat == "plus_contrat") {
		if (!empty($plus_contrat_nom)) { // Vérifier que le nom n’est pas vide
		    $sth = $dbh->prepare("INSERT INTO contrat (contrat_nom, contrat_type) VALUES (?, ?)");
		    $sth->execute([$plus_contrat_nom, $contrat_type]);

		    $contrat = return_last_id("contrat_index", "contrat");

		    // Ajout dans le tableau des contrats
		    $contrats[] = [
		        "contrat_index" => $contrat,
		        "contrat_nom" => $plus_contrat_nom,
		        "contrat_type" => $contrat_type
		    ];
		} else {
		    echo "<p class='error_message'>Erreur : Le nom du contrat est vide.</p>";
		}
	}

	if ($tutelle == "plus_tutelle") {
		if (!empty($plus_tutelle)) { // Vérifier que le nom n’est pas vide
			$sth = $dbh->prepare("INSERT INTO tutelle (tutelle_nom) VALUES (:plus_tutelle)"); 		// Prépare la requête avec un paramètre lié
			$sth->execute([':plus_tutelle' => !empty($plus_tutelle) ? $plus_tutelle : null]); 		// Exécute avec une valeur NULL si la variable est vide
			$tutelle = return_last_id("tutelle_index", "tutelle"); 									// Récupère l'ID du dernier enregistrement
			array_push($tutelles, ["tutelle_index" => $tutelle, "tutelle_nom" => $plus_tutelle]); 	// Ajoute l'entrée dans le tableau
			$sth->closeCursor(); 																	// Ferme le curseur
		} else {
		   	echo "<p class='error_message'>Erreur : Le nom de la nouvelle tutelle est vide.</p>";
		}															
	}

    if ($responsable_achat=="plus_responsable_achat") {
        $plus_responsable_achat_nom=mb_strtoupper($plus_responsable_achat_nom);
        $plus_responsable_achat_phone=phone_display("$plus_responsable_achat_phone","");
        $sth = $dbh->query(str_replace("\"\"", "NULL","INSERT INTO utilisateur (utilisateur_nom, utilisateur_prenom, utilisateur_mail, utilisateur_phone) VALUES ('".$plus_responsable_achat_nom."', '".$plus_responsable_achat_prenom."','".$plus_responsable_achat_mail."','".$plus_responsable_achat_phone."') ; "));
        /* TODO : prévoir le cas où le contrat existe déjà */
	$responsable_achat=return_last_id("utilisateur_index","utilisateur");
        // on ajoute cette entrée dans le tableau des types de contrats (utilisé pour le select)
        array_push($utilisateurs, array("utilisateur_index" => $responsable_achat, "utilisateur_nom" => $plus_responsable_achat_nom, "utilisateur_prenom" => $plus_responsable_achat_prenom, "utilisateur_mail" => $plus_responsable_achat_mail, "utilisateur_phone" => $plus_responsable_achat_phone) );
        if ($sth) $sth->closeCursor();
    }

// TODO : prix avec une virgule ou un point ?

/*  ╦ ╦╔═╗╔╦╗╔═╗╔╦╗╔═╗  ╔═╗╔═╗ ╦  
    ║ ║╠═╝ ║║╠═╣ ║ ║╣   ╚═╗║═╬╗║  
    ╚═╝╩  ═╩╝╩ ╩ ╩ ╚═╝  ╚═╝╚═╝╚╩═╝     */
    $date_achat=($date_achat==NULL) ? "0000-00-00" : $date_achat;
    $garantie=($garantie==NULL) ? "0000-00-00" : $garantie;

	$sql = "UPDATE base 
		    SET designation        = :designation,
		        vendeur            = :vendeur,
		        prix               = :prix,
		        contrat            = :contrat,
		        date_achat         = :date_achat,
		        garantie           = :garantie,
		        bon_commande       = :bon_commande,
		        num_inventaire     = :num_inventaire,
		        tutelle            = :tutelle,
		        responsable_achat  = :responsable_achat
		    WHERE base_index = :i";

	$sth = $dbh->prepare($sql);
	$sth->execute([
		':designation'       => ($designation       === "" ? null : $designation),
		':vendeur'           => ($vendeur           === "" ? "0" : $vendeur),
		':prix'              => ($prix              === "" ? null : $prix),
		':contrat'           => ($contrat           === "" ? "0" : $contrat),
		':date_achat'        => ($date_achat        === "" ? null : $date_achat),
		':garantie'          => ($garantie          === "" ? null : $garantie),
		':bon_commande'      => ($bon_commande      === "" ? null : $bon_commande),
		':num_inventaire'    => ($num_inventaire    === "" ? null : $num_inventaire),
		':tutelle'           => ($tutelle           === "" ? "0" : $tutelle),
		':responsable_achat' => ($responsable_achat === "" ? "0" : $responsable_achat),
		':i'                 => $i
	]);
	
	

    $message.= (!isset($modif_result)) ? $message_success_modif : $message_error_modif;

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
        echo "<label for=\"designation\" style=\"vertical-align: top;\">Désignation* :</label>\n";
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
            echo "<label for=\"plus_vendeur_nom\">Nom* :</label>\n";
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
        
##################################################################$
        
        
        /* ########### + contrat ########### */
        echo "\n\n\n";
        echo "<fieldset id=\"plus_contrat\" class=\"subfield\" style=\"display: none;\"><legend class=\"subfield\">Nouveau Contrat</legend>";

            echo "<label for=\"plus_contrat_nom\">Contrat* :</label>\n";
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
                        echo "<label for=\"plus_contrat_type_nom\">Type* :</label>\n";

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
                echo "<label for=\"plus_tutelle\">Tutelle* :</label>\n";

                $deja_tut=dejadanslabase("SELECT DISTINCT `tutelle_nom` FROM `tutelle`");
                echo "<input value=\"\" name=\"plus_tutelle\" type=\"text\" pattern=\"^(?!(".$deja_tut.")$).*$\" oninvalid=\"setCustomValidity('Déjà dans la base')\"  oninput=\"setCustomValidity('')\"  / >\n";
            echo "</fieldset>";
            echo "\n\n\n";

        /* ########### bon de commande ########### */
        echo "<label for=\"bon_commande\"><abbr title=\"Bon de commande\">BDC</abbr>&nbsp;: </label>\n";
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
                echo "<label for=\"plus_responsable_achat_prenom\">Prénom :</label>\n";			echo "<input value=\"\" name=\"plus_responsable_achat_prenom\" type=\"text\"><br/>\n";
                echo "<label for=\"plus_responsable_achat_nom\">NOM* :</label>\n";				echo "<input value=\"\" name=\"plus_responsable_achat_nom\" type=\"text\"><br/>\n";
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
    
    echo "<p style=\"text-align:right;\"><small>* champ obligatoire</small></p>"; 

echo "</div>";

?>
