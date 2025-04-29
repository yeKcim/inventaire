<?php

/*
██╗███╗   ██╗███████╗ ██████╗ ███████╗    ███╗   ███╗██╗███╗   ██╗██╗███╗   ███╗ █████╗ ██╗     ███████╗███████╗
██║████╗  ██║██╔════╝██╔═══██╗██╔════╝    ████╗ ████║██║████╗  ██║██║████╗ ████║██╔══██╗██║     ██╔════╝██╔════╝
██║██╔██╗ ██║█████╗  ██║   ██║███████╗    ██╔████╔██║██║██╔██╗ ██║██║██╔████╔██║███████║██║     █████╗  ███████╗
██║██║╚██╗██║██╔══╝  ██║   ██║╚════██║    ██║╚██╔╝██║██║██║╚██╗██║██║██║╚██╔╝██║██╔══██║██║     ██╔══╝  ╚════██║
██║██║ ╚████║██║     ╚██████╔╝███████║    ██║ ╚═╝ ██║██║██║ ╚████║██║██║ ╚═╝ ██║██║  ██║███████╗███████╗███████║
╚═╝╚═╝  ╚═══╝╚═╝      ╚═════╝ ╚══════╝    ╚═╝     ╚═╝╚═╝╚═╝  ╚═══╝╚═╝╚═╝     ╚═╝╚═╝  ╚═╝╚══════╝╚══════╝╚══════╝
                                       
*/
$message = "";

/*
 █████╗ ██████╗ ██████╗  █████╗ ██╗   ██╗
██╔══██╗██╔══██╗██╔══██╗██╔══██╗╚██╗ ██╔╝
███████║██████╔╝██████╔╝███████║ ╚████╔╝
██╔══██║██╔══██╗██╔══██╗██╔══██║  ╚██╔╝
██║  ██║██║  ██║██║  ██║██║  ██║   ██║
╚═╝  ╚═╝╚═╝  ╚═╝╚═╝  ╚═╝╚═╝  ╚═╝   ╚═╝
*/
// Tous les lab_id classés par catégorie
$sth = $dbh->prepare("SELECT base_index, lab_id, categorie, categorie_lettres, categorie_nom FROM base, categorie WHERE categorie = categorie_index ORDER BY categorie_nom ASC;");
$sth->execute();
$labids_cat = $sth->fetchAll(PDO::FETCH_ASSOC);
$sth->closeCursor();

/*
███╗   ███╗ ██████╗ ██████╗ ██╗███████╗    ███████╗ ██████╗ ██╗
████╗ ████║██╔═══██╗██╔══██╗██║██╔════╝    ██╔════╝██╔═══██╗██║
██╔████╔██║██║   ██║██║  ██║██║█████╗      ███████╗██║   ██║██║
██║╚██╔╝██║██║   ██║██║  ██║██║██╔══╝      ╚════██║██║▄▄ ██║██║
██║ ╚═╝ ██║╚██████╔╝██████╔╝██║██║██╗      ███████║╚██████╔╝███████╗
╚═╝     ╚═╝ ╚═════╝ ╚═════╝ ╚═╝╚═╝╚═╝      ╚══════╝ ╚══▀▀═╝ ╚══════╝
*/

if ( (isset($_POST["minimum_valid"])) || (isset($data["add_valid"])) ) {

    $arr = ["categorie", "plus_categorie_nom", "plus_categorie_abbr", "lab_id", "id_man", "designation"];
    foreach ($arr as &$value) {
        $$value = isset($_POST[$value]) ? htmlentities(trim($_POST[$value])) : "";
    }
    
	/*  ╔╗╔╔═╗╦ ╦╦  ╦╔═╗╦  ╦  ╔═╗  ╔═╗╔═╗╔╦╗╔═╗╔═╗╔═╗╦═╗╦╔═╗
		║║║║ ║║ ║╚╗╔╝║╣ ║  ║  ║╣   ║  ╠═╣ ║ ║╣ ║ ╦║ ║╠╦╝║║╣
		╝╚╝╚═╝╚═╝ ╚╝ ╚═╝╩═╝╩═╝╚═╝  ╚═╝╩ ╩ ╩ ╚═╝╚═╝╚═╝╩╚═╩╚═╝    */
    if ($categorie=="plus_categorie") {

		if ( (empty($plus_categorie_abbr)) || (empty($plus_categorie_nom) ) ) {
			$message.= "<p class=\"error_message\" id=\"disappear_delay\">Pour créer une nouvelle catégorie, remplir les champs obligatoires</p>";
			$error=1;
			$categorie="";
		}
		else {
			$sth = $dbh->prepare("INSERT INTO categorie (categorie_lettres, categorie_nom) VALUES (:lettres, :nom)");
			$sth->execute([
				':lettres' => !empty($plus_categorie_abbr) ? $plus_categorie_abbr : null,
				':nom' => !empty($plus_categorie_nom) ? $plus_categorie_nom : null
			]);
			/* TODO : prévoir le cas où la catégorie existe déjà */
			$categorie=return_last_id("categorie_index","categorie");
			// on ajoute cette entrée dans le tableau des catégories (utilisé pour le select)
			
			$categories = is_array($categories) ? $categories : [];
			array_push($categories, array(
				"categorie_index"   => $categorie,       // L'ID retourné
				"categorie_nom"     => $plus_categorie_nom,  // Le nom complet
				"categorie_lettres" => $plus_categorie_abbr  // L'abréviation
			));
		}
    }
    
    if ( ($lab_id=="manual_id") && ( empty($id_man) ) ) {
		$message.= "<p class=\"error_message\" id=\"disappear_delay\">Si vous sélectionnez Id manuel, renseignez l’identifiant !</p>";
		$error=1;
		$lab_id="";
    }


	/*  ╦ ╦╔═╗╔╦╗╔═╗╔╦╗╔═╗  ╔═╗╔═╗ ╦    ╔═╗ ╦ ╦╔═╗╦═╗╦ ╦
		║ ║╠═╝ ║║╠═╣ ║ ║╣   ╚═╗║═╬╗║    ║═╬╗║ ║║╣ ╠╦╝╚╦╝
		╚═╝╩  ═╩╝╩ ╩ ╩ ╚═╝  ╚═╝╚═╝╚╩═╝  ╚═╝╚╚═╝╚═╝╩╚═ ╩     */
	if (!$error) {
	
	
	/*  ╦  ╔═╗╔╗  ╦╔╦╗
		║  ╠═╣╠╩╗ ║ ║║
		╩═╝╩ ╩╚═╝┘╩═╩╝  */
		// Si on change la catégorie, il est nécessaire de changer également le lab_id !
		if ($data[0]["categorie"]!=$categorie) {
			$data[0]["lab_id"]=new_lab_id($categorie);
			$lab_id=$data[0]["lab_id"];
		}

		if ($lab_id=="manual_id") {
		    if ($id_man!="") $lab_id=$id_man;
		    else {/* si manuel mais vide → auto */}
		}
	
		// Préparation de la requête de mise à jour
		$sth = $dbh->prepare("
		    UPDATE base
		    SET designation = :designation,
		        categorie = :categorie,
		        lab_id = :lab_id
		    WHERE base_index = :base_index
		");

		// Exécution de la requête avec des paramètres liés
		$modif_result = $sth->execute([
		    ':designation' => $designation,
		    ':categorie' => $categorie,
		    ':base_index' => $i	,
		    ':lab_id' => $lab_id
		]);

		$message .= $message_success_modif;

	}
	// Avant d’afficher, on doit ajouter les nouvelles infos dans les arrays concernés
	$data[0]["designation"] = $designation;
	$data[0]["categorie"] = $categorie;
}

// Réinitialisation des valeurs pour un nouveau formulaire
if (isset($added)) {
	$data[0]["designation"] = $designation;
	$data[0]["categorie"] = $categorie;
}

/*
███████╗ ██████╗ ██████╗ ███╗   ███╗██╗   ██╗██╗      █████╗ ██╗██████╗ ███████╗
██╔════╝██╔═══██╗██╔══██╗████╗ ████║██║   ██║██║     ██╔══██╗██║██╔══██╗██╔════╝
█████╗  ██║   ██║██████╔╝██╔████╔██║██║   ██║██║     ███████║██║██████╔╝█████╗
██╔══╝  ██║   ██║██╔══██╗██║╚██╔╝██║██║   ██║██║     ██╔══██║██║██╔══██╗██╔══╝
██║     ╚██████╔╝██║  ██║██║ ╚═╝ ██║╚██████╔╝███████╗██║  ██║██║██║  ██║███████╗
╚═╝      ╚═════╝ ╚═╝  ╚═╝╚═╝     ╚═╝ ╚═════╝ ╚══════╝╚═╝  ╚═╝╚═╝╚═╝  ╚═╝╚══════╝
*/
echo "<div id=\"bloc\" style=\"background:#a1a1a1; vertical-align:top;\">";
echo "<h1>Informations minimales</h1>";

echo $message;

$quick = (isset($_GET["quick_page"])) ? "&quick_page=" . $_GET["quick_page"] . "&quick_name=" . $_GET["quick_name"] : "";
if ($write) echo "<form method=\"post\" action=\"?BASE=$database&i=" . $i . $quick . "\">";

/*  ╦═╗╔═╗╔═╗╔═╗╦═╗╔═╗╔╗╔╔═╗╔═╗  ╦╔╗╔╦╗╔═╗╦═╗╔╗╔╔═╗
    ╠╦╝║╣ ╠╣ ║╣ ╠╦╝║╣ ║║║║  ║╣   ║║║║║ ║╣ ╠╦╝║║║║╣
    ╩╚═╚═╝╚  ╚═╝╩╚═╚═╝╝╚╝╚═╝╚═╝  ╩╝╚╝╩ ╚═╝╩╚═╝╚╝╚═╝    */
    
//echo "<fieldset><legend>Référence interne</legend>";

	/* ########### categorie ########### */
	echo "<label for=\"categorie\">Catégorie* : </label>\n";
	echo "<select name=\"categorie\" onchange=\"display(this,'plus_categorie','plus_categorie');\" id=\"categorie\" required>";
	echo "<option value=\"0\" "; if (isset($data[0])) {if ($data[0]["categorie"]=="0") echo "selected";} echo ">— Aucune catégorie spécifiée —</option>";
	echo "<option value=\"plus_categorie\" "; if (isset($data[0])) {if ($data[0]["categorie"]=="plus_categorie") echo "selected";} echo ">— Nouvelle catégorie : —</option>";
	option_selecteur(  (isset($data[0])) ? $data[0]["categorie"] : ""  , $categories, "categorie_index", "categorie_nom", "categorie_lettres", "display()");
	echo "</select><br/>\n\n";

	echo "<script>\n
		\$j(document).ready(function() {
		    // Initialisation de Select2
		    \$j('#categorie').select2({
		        width: '270px'
		    });

		    // Validation personnalisée
		    $('#categorie').on('change', function() {
		        if ($(this).val() === \"0\") {
		            $(this)[0].setCustomValidity('Champ obligatoire');
		        } else {
		            $(this)[0].setCustomValidity('');
		        }
		    });

		    // Vérification avant la soumission du formulaire
		    $('form').on('submit', function(event) {
		        if ($('#categorie').val() === \"0\") {
		            event.preventDefault(); // Empêche la soumission
		            $('#categorie')[0].setCustomValidity('Champ obligatoire');
		            $('#categorie')[0].reportValidity(); // Affiche le message d'erreur
		        }
		    });
		});
	</script>";


	/* ########### + categorie ########### */
	echo "\n\n\n";
	echo "<fieldset id=\"plus_categorie\" class=\"subfield\" style=\"display: none;\"><legend class=\"subfield\">Nouvelle Catégorie</legend>";
		echo "<label for=\"plus_categorie_nom\">Nom* :</label>\n";
		$deja_catnom=dejadanslabase("SELECT DISTINCT `categorie_nom` FROM `categorie` ");
		echo "<input value=\"\" name=\"plus_categorie_nom\" type=\"text\" pattern=\"^(?!(".$deja_catnom.")$).*$\" oninvalid=\"setCustomValidity('Déjà dans la base')\" oninput=\"setCustomValidity('')\" /><br/>\n";

		echo "<label for=\"plus_categorie_abbr\">Abbréviation* <abbr title=\"4 caractères max, pas de chiffres\"><strong>ⓘ</strong></abbr> :</label>\n";
		$deja_abrev=dejadanslabase("SELECT DISTINCT `categorie_lettres` FROM `categorie` ;");
		echo "<input value=\"\" name=\"plus_categorie_abbr\" type=\"text\" maxlength=\"4\" minlength=\"1\" pattern=\"^(?!($deja_abrev))([A-Za-z]{1,4})$\" id=\"plus_categorie_abbr\" oninvalid=\"setCustomValidity('Abbréviation déjà utilisée')\" oninput=\"setCustomValidity('')\" >\n";
		
		echo"<script>
		  document.getElementById('plus_categorie_abbr').addEventListener('input', function(e) {
			// Remplacer tout caractère qui n'est pas une lettre A-Z ou a-z par rien
			this.value = this.value.replace(/[^A-Za-z]/g, '');
		  });
		</script>";

	echo "</fieldset>";
	echo "\n\n\n";

        /* ########### lab_id ########### */
        echo "<label for=\"lab_id\">";

        echo "Identifiant labo* :</label>\n";

	echo "<select name=\"lab_id\" onchange=\"display(this,'manual_id','manual_id');\" id=\"lab_id\">";
		 echo "<option value=\""; if (isset($data[0])) echo $data[0]["lab_id"]; echo"\" ";

			$lab_id = (!isset($lab_id)) ? "" : $lab_id;
			$id_man = (!isset($id_man)) ? "" : $id_man;

			if (isset($data[0])) {if ($lab_id==$data[0]["lab_id"]) echo "selected";}	echo ">";
			if (isset($fieldset_tags)) echo "Auto";
			else {
			    if ($id_man=="") echo $data[0]["lab_id"];
			    else echo "$id_man";
			
		echo "</option>"; }
		echo "<option value=\"manual_id\" ";
		echo ">Manuel</option>";
        echo "</select><br/>";
        /*select2 pour recherche */
		echo "<script>
			\$j(document).ready(function() {
				\$j('#lab_id').select2({
					width: '200px',
					minimumResultsForSearch: Infinity,});
			});
		</script>";

        /* ########### + id_manuel ########### */
        echo "\n\n\n";
        echo "<fieldset id=\"manual_id\" class=\"subfield\" style=\"display: none;\"><legend class=\"subfield\">Id Manuel</legend>";
            echo "<label for=\"id_man\">Id* :</label>\n";


            $deja_idman=dejadanslabase("SELECT DISTINCT `lab_id` FROM `base` ;");
			echo "<input value=\"\" name=\"id_man\" type=\"text\" pattern=\"^(?!(".$deja_idman.")$).*$\" oninvalid=\"setCustomValidity('Déjà dans la base')\" oninput=\"setCustomValidity('')\" / > \n";

        echo "</fieldset>";
        echo "\n\n\n";
        echo "<br/>";

    echo "</fieldset>";
    
//echo "</fieldset>";    
    

//echo "<fieldset><legend>Description</legend>";

        /* ########### designation ########### */
        echo "<label for=\"designation\" style=\"vertical-align: top;\">Désignation* :</label>\n";
        echo "<input name=\"designation\" type=\"text\" id=\"designation\" size=\"31px\" required ";
        echo "value=\""; if (isset($data[0])) { echo ($data[0]["designation"]!="") ? $data[0]["designation"] : "";} echo "\" ><br/>\n";

//echo "</fieldset>";

/*  ╔═╗╦ ╦╔╗ ╔╦╗╦╔╦╗
    ╚═╗║ ║╠╩╗║║║║ ║
    ╚═╝╚═╝╚═╝╩ ╩╩ ╩     */
if ($write) echo "<p style=\"text-align:center;\"><input name=\"minimum_valid\" value=\"Enregistrer\" type=\"submit\" class=\"little_button\" /></p>";
if ($write) echo "</form>";

echo "<p style=\"text-align:right;\"><small>* champ obligatoire</small></p>";

echo "</div>";
?>

