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

// Requête pour la table 'base'
$sql = "SELECT base_index, lab_id, categorie, reference, designation, sortie, integration FROM base WHERE base_index != :base_index ORDER BY lab_id ASC;";
$sth = $dbh->prepare($sql);
$sth->execute([':base_index' => $i]);
$lab_ids = $sth->fetchAll(PDO::FETCH_ASSOC);
$sth->closeCursor();

// Requête pour la table 'raison_sortie'
$sql = "SELECT * FROM raison_sortie WHERE raison_sortie_index != 0 ORDER BY raison_sortie_nom ASC;";
$sth = $dbh->prepare($sql);
$sth->execute();
$raison_sorties = $sth->fetchAll(PDO::FETCH_ASSOC);
$sth->closeCursor();

// Requête pour la table 'localisation'
$sql = "SELECT * FROM localisation WHERE localisation_index != 0 ORDER BY localisation_batiment ASC, localisation_piece ASC;";
$sth = $dbh->prepare($sql);
$sth->execute();
$localisations = $sth->fetchAll(PDO::FETCH_ASSOC);
$sth->closeCursor();


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
		// Requête préparée pour insérer un nouvel utilisateur
		$sql = "INSERT INTO utilisateur (utilisateur_nom, utilisateur_prenom, utilisateur_mail, utilisateur_phone) VALUES (:nom, :prenom, :mail, :phone);";
		$sth = $dbh->prepare($sql);

		// Exécution de la requête avec les paramètres
		$sth->execute([
			':nom' => $plus_utilisateur_nom,
			':prenom' => $plus_utilisateur_prenom,
			':mail' => $plus_utilisateur_mail,
			':phone' => $plus_utilisateur_phone
		]);

		// Fermeture du curseur
		$sth->closeCursor();
        /* TODO : prévoir le cas où le contrat existe déjà */
	$utilisateur=return_last_id("utilisateur_index","utilisateur");

        // on ajoute cette entrée dans le tableau des utilisateurs (utilisé pour le select)
	array_push($utilisateurs, array("utilisateur_index" => $utilisateur, "utilisateur_nom" => $plus_utilisateur_nom, "utilisateur_prenom" => $plus_utilisateur_prenom, "utilisateur_mail" => $plus_utilisateur_mail, "utilisateur_phone" => phone_display("$plus_utilisateur_phone",".") ) );
    }

    if ($localisation=="plus_localisation") {
		// Requête préparée pour insérer une nouvelle localisation
		$sql = "INSERT INTO localisation (localisation_batiment, localisation_piece) VALUES (:batiment, :piece);";
		$sth = $dbh->prepare($sql);

		// Exécution de la requête avec les paramètres
		$sth->execute([
			':batiment' => $plus_localisation_bat,
			':piece' => $plus_localisation_piece
		]);

		// Fermeture du curseur
		$sth->closeCursor();
        
        /* TODO : prévoir le cas où la nouvelle localisation existe déjà */
	$localisation=return_last_id("localisation_index","localisation");
	
        // on ajoute cette entrée dans le tableau des localisations (utilisé pour le select)
	array_push($localisations, array("localisation_index" => $localisation, "localisation_batiment" => $plus_localisation_bat, "localisation_piece" => $plus_localisation_piece ) );
    }


    if ($raison_sortie=="plus_raison_sortie") {
		// Requête préparée pour insérer une nouvelle raison de sortie
		$sql = "INSERT INTO raison_sortie (raison_sortie_nom) VALUES (:raison_sortie_nom);";
		$sth = $dbh->prepare($sql);

		// Exécution de la requête avec les paramètres
		$sth->execute([
			':raison_sortie_nom' => $plus_raison_sortie_nom
		]);

		// Fermeture du curseur
		$sth->closeCursor();
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

	// Requête préparée pour mettre à jour la table 'base'
	$sql = "UPDATE base
			SET utilisateur = :utilisateur,
			    localisation = :localisation,
			    sortie = :sortie,
			    integration = :integration,
			    raison_sortie = :raison_sortie
			    $change_date_localisation
			WHERE base_index = :base_index;";

	$sth = $dbh->prepare($sql);

	// Exécution de la requête avec les paramètres
	$sth->execute([
		':utilisateur' => $utilisateur,
		':localisation' => $localisation,
		':sortie' => $sortie,
		':integration' => $integration,
		':raison_sortie' => $raison_sortie,
		':base_index' => $i
	]);

	// Fermeture du curseur
	$sth->closeCursor();

    $message.= (!isset($modif_result)) ? $message_error_modif : $message_success_modif;


    // Si l’integration change, ajout d’une entrée autotomatiquement dans le journal
    if ($data[0]["integration"]!=$integration) {
        $add_journal= "INSERT INTO historique (historique_index, historique_date, historique_texte, historique_id) VALUES (NULL, \"".date("y.m.d")."\", \"<!--auto-->" ;

	$add_journal.= ($integration=="0") ? "Fin de l’intégration à :<br/> → " : "Intégration à :<br/> → " ;

        $keys = ($integration=="0") ? array_keys(array_column($lab_ids, 'base_index'), $data[0]["integration"]) : array_keys(array_column($lab_ids, 'base_index'), $integration) ;
        if (isset($keys[0])) $txt_in=quickdisplayincarac_b($lab_ids[$keys[0]]);
	else $txt_in = ($integration=="0") ? "<a href='info.php?BASE=".$database."&i=".$data[0]["integration"]."' target='_blank'>#".$data[0]["integration"]."</a>" : "<a href='info.php?BASE=".$database."&i=".$integration."' target='_blank'>#".$integration."</a>";

	$add_journal.=$txt_in."\", \"".$i."\");" ;
	// Exemple de requête préparée pour insérer dans la table 'journal'
	$sql = "INSERT INTO journal (colonne1, colonne2) VALUES (:valeur1, :valeur2);";
	$sth = $dbh->prepare($sql);

	// Exécution de la requête avec les paramètres
	$sth->execute([
		':valeur1' => $valeur1,
		':valeur2' => $valeur2
	]);

	// Fermeture du curseur
	$sth->closeCursor();
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
        echo "</select><br />\n";
		/*select2 pour recherche */
		echo "<script>
			\$j(document).ready(function() {
				\$j('#utilisateur').select2({width: '270px'});
			});
		</script>";


            /* ########### + utilisateur ########### */
            echo "\n\n\n";
            echo "<fieldset id=\"plus_utilisateur\" class=\"subfield\" style=\"display: none;\"><legend class=\"subfield\">Nouvel utilisateur</legend>";
                echo "<label for=\"plus_utilisateur_prenom\">Prénom :</label>\n";
                echo "<input value=\"\" name=\"plus_utilisateur_prenom\" type=\"text\"><br/>\n";

                echo "<label for=\"plus_utilisateur_nom\">NOM* :</label>\n";
                echo "<input value=\"\" name=\"plus_utilisateur_nom\" type=\"text\"><br/>\n";

                echo "<label for=\"plus_utilisateur_mail\">Mail :</label>\n";				
                
                echo "<input type=\"text\" id=\"email\" name=\"plus_utilisateur_mail\" />
				  <script>
					\$j(document).ready(function(){
					  \$j(\"#email\").inputmask({ alias: \"email\" });
					});
				  </script><br/>\n";
                
                
                echo "<label for=\"plus_utilisateur_phone\">Téléphone :</label>\n";
                echo" <input type=\"tel\" id=\"phone\" name=\"plus_utilisateur_phone\" />
				  <script>
					\$j(document).ready(function(){
					  \$j(\"#phone\").inputmask(\"99 99 99 99 99\"); // Masque pour numéro de téléphone
					});
				  </script><br/>\n";

            echo "</fieldset>";
            echo "\n\n\n";

        /* ########### localisation ########### */
        echo "<label for=\"localisation\">Localisation : </label>\n";
        echo "<select name=\"localisation\" onchange=\"display(this,'plus_localisation','plus_localisation');\" id=\"localisation\" >";
        echo "<option value=\"0\" "; if ($data[0]["localisation"]=="0") echo "selected"; echo ">— Aucune localisation spécifiée —</option>";
        echo "<option value=\"plus_localisation\" "; if ($data[0]["localisation"]=="plus_localisation") echo "selected"; echo ">− Nouvelle localisation : −</option>";

        option_selecteur($data[0]["localisation"], $localisations, "localisation_index", "localisation_batiment", "localisation_piece");
        echo "</select>";
		/*select2 pour recherche */
		echo "<script>
			\$j(document).ready(function() {
				\$j('#localisation').select2({width: '200px'});
			});
		</script>";		
		

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
        /*select2 pour recherche */
		echo "<script>
			\$j(document).ready(function() {
				\$j('#etat').select2({
					minimumResultsForSearch: Infinity,
					width: '200px'
				});
			});
		</script>";
		
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


		// Intégré dans
        echo "<label for=\"integration\">est intégré dans :</label>\n";

        echo "<select name=\"integration\" id=\"integration\" >";
       
        echo "<option value=\"0\" "; if ($data[0]["integration"]=="0") echo "selected"; echo ">— Aucune intégration spécifiée —</option>";
        option_selecteur($data[0]["integration"], $lab_ids, "base_index", "lab_id");
        echo "</select>";
        /*select2 pour recherche */
		echo "<script>
			\$j(document).ready(function() {
				\$j('#integration').select2({width: '210px'});
			});
		</script>";
		
		// Lien vers parent
        if (isset($data[0]["integration"])) { if ( ($data[0]["integration"]!="0") && ($data[0]["integration"]!="") )
            echo " <a href=\"info.php?BASE=".$database."&i=".$data[0]["integration"]."\" target=\"_blank\">";
            echo "<strong>↗</strong></a>";
        }




		echo "<br/>&nbsp;<br/>";
		
		
		
		
		
		
		echo "<label for=\"parentde[]\">Intègre : </label>\n";

		echo "<select class=\"select2\" multiple=\"multiple\" tabindex=\"6\" name=\"parentde[]\" id=\"multiple\">";
		foreach ($lab_ids as $all_ids) {
			echo "<option value=\"".$all_ids["base_index"]."\" ";
			echo ($all_ids["integration"]==$i) ? " selected " : "";
			echo ">[".$all_ids["lab_id"]."] ";
			echo mb_substr($all_ids["designation"], 0, 35);
			echo (mb_strlen($all_ids["designation"]) > 35) ? " …" : "";
			echo "</option><br/>";
		}
		echo "</select>";
		echo "<script>
				\$j(document).ready(function() {
					\$j('#multiple').select2({
						placeholder: \"Sélectionnez les éléments intégrés\",
						allowClear: true,
						width:\"270px\"
					});
				});
			  </script>";



    echo "</fieldset>";

/*  ╔═╗╦ ╦╔╗ ╔╦╗╦╔╦╗
    ╚═╗║ ║╠╩╗║║║║ ║
    ╚═╝╚═╝╚═╝╩ ╩╩ ╩     */
    if ($write) echo "<p style=\"text-align:center;\"><input name=\"utilisation_valid\" value=\"Enregistrer\" type=\"submit\" class=\"little_button\" /></p>"; // TODO Ajouter un bouton réinitialiser

    if ($write) echo "</form>";
    
    echo "<p style=\"text-align:right;\"><small>* champ obligatoire</small></p>"; 

echo "</div>";

?>
