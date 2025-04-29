<?php

// ████████╗███████╗ ██████╗██╗  ██╗███╗   ██╗██╗ ██████╗ ██╗   ██╗███████╗
// ╚══██╔══╝██╔════╝██╔════╝██║  ██║████╗  ██║██║██╔═══██╗██║   ██║██╔════╝
//    ██║   █████╗  ██║     ███████║██╔██╗ ██║██║██║   ██║██║   ██║█████╗
//    ██║   ██╔══╝  ██║     ██╔══██║██║╚██╗██║██║██║▄▄ ██║██║   ██║██╔══╝
//    ██║   ███████╗╚██████╗██║  ██║██║ ╚████║██║╚██████╔╝╚██████╔╝███████╗
//    ╚═╝   ╚══════╝ ╚═════╝╚═╝  ╚═╝╚═╝  ╚═══╝╚═╝ ╚══▀▀═╝  ╚═════╝ ╚══════╝

$message = "";

// ███╗   ███╗ ██████╗ ██████╗ ██╗███████╗    ███████╗ ██████╗ ██╗
// ████╗ ████║██╔═══██╗██╔══██╗██║██╔════╝    ██╔════╝██╔═══██╗██║
// ██╔████╔██║██║   ██║██║  ██║██║█████╗      ███████╗██║   ██║██║
// ██║╚██╔╝██║██║   ██║██║  ██║██║██╔══╝      ╚════██║██║▄▄ ██║██║
// ██║ ╚═╝ ██║╚██████╔╝██████╔╝██║██║██╗      ███████║╚██████╔╝███████╗
// ╚═╝     ╚═╝ ╚═════╝ ╚═════╝ ╚═╝╚═╝╚═╝      ╚══════╝ ╚══▀▀═╝ ╚══════╝

if ( (isset($_POST["technique_valid"])) || (isset($data["add_valid"])) ) {

    $arr = ["marque", "plus_marque", "plus_marque_nom", "reference", "serial_number", "base_index"];
    foreach ($arr as &$value) {
        $$value = isset($_POST[$value]) ? htmlentities(trim($_POST[$value])) : "";
    }	
 
	//  ╔╗╔╔═╗╦ ╦╦  ╦╔═╗╦  ╦  ╔═╗  ╔╦╗╔═╗╦═╗╔═╗ ╦ ╦╔═╗
	//	║║║║ ║║ ║╚╗╔╝║╣ ║  ║  ║╣   ║║║╠═╣╠╦╝║═╬╗║ ║║╣
	//	╝╚╝╚═╝╚═╝ ╚╝ ╚═╝╩═╝╩═╝╚═╝  ╩ ╩╩ ╩╩╚═╚═╝╚╚═╝╚═╝
    if ($marque == "plus_marque") {
        if (!empty($plus_marque_nom)) {  
            $sth = $dbh->prepare("INSERT INTO marque (marque_nom) VALUES (:nom)");
            $sth->execute([':nom' => $plus_marque_nom]);
            $marque = return_last_id("marque_index", "marque");

        	$marques = is_array($marques) ? $marques : [];
            array_push($marques, ["marque_index" => $marque, "marque_nom" => $plus_marque_nom]);
        } else {
        	$message.="<p class=\"error_message\" id=\"disappear_delay\">Le nom de la nouvelle marque ne peut être vide, marque indéfinie.</p>";
        	$error=1;
        	$marque="0";
        }
    }


	//  ╦ ╦╔═╗╔╦╗╔═╗╔╦╗╔═╗  ╔═╗╔═╗ ╦    ╔═╗ ╦ ╦╔═╗╦═╗╦ ╦
	//	║ ║╠═╝ ║║╠═╣ ║ ║╣   ╚═╗║═╬╗║    ║═╬╗║ ║║╣ ╠╦╝╚╦╝
	//	╚═╝╩  ═╩╝╩ ╩ ╩ ╚═╝  ╚═╝╚═╝╚╩═╝  ╚═╝╚╚═╝╚═╝╩╚═ ╩ 
	if (!$error) {
		// Préparation de la requête de mise à jour
		$sth = $dbh->prepare("
		    UPDATE base
		    SET marque = :marque,
		        reference = :reference,
		        serial_number = :serial_number
		    WHERE base_index = :base_index
		");

		// Exécution de la requête avec des paramètres liés
		$modif_result = $sth->execute([
		    ':marque' => $marque,
		    ':reference' => $reference,
		    ':serial_number' => $serial_number,
		    ':base_index'     => $i
		]);

		$message .= $message_success_modif;

	}
	// Avant d’afficher, on doit ajouter les nouvelles infos dans les arrays concernés
	$data[0]["marque"] = $marque;
	$data[0]["serial_number"] = $serial_number;
	$data[0]["reference"] = $reference;

}

// Réinitialisation des valeurs pour un nouveau formulaire
if (isset($added)) {
	$data[0]["marque"] = $marque;
	$data[0]["serial_number"] = $serial_number;
	$data[0]["reference"] = $reference;
}

// ███████╗ ██████╗ ██████╗ ███╗   ███╗██╗   ██╗██╗      █████╗ ██╗██████╗ ███████╗
// ██╔════╝██╔═══██╗██╔══██╗████╗ ████║██║   ██║██║     ██╔══██╗██║██╔══██╗██╔════╝
// █████╗  ██║   ██║██████╔╝██╔████╔██║██║   ██║██║     ███████║██║██████╔╝█████╗
// ██╔══╝  ██║   ██║██╔══██╗██║╚██╔╝██║██║   ██║██║     ██╔══██║██║██╔══██╗██╔══╝
// ██║     ╚██████╔╝██║  ██║██║ ╚═╝ ██║╚██████╔╝███████╗██║  ██║██║██║  ██║███████╗
// ╚═╝      ╚═════╝ ╚═╝  ╚═╝╚═╝     ╚═╝ ╚═════╝ ╚══════╝╚═╝  ╚═╝╚═╝╚═╝  ╚═╝╚══════╝

echo "<div id=\"bloc\" style=\"background:#b4e287; vertical-align:top;\">";
echo "<h1>Technique</h1>";

echo $message;

$quick = (isset($_GET["quick_page"])) ? "&quick_page=" . $_GET["quick_page"] . "&quick_name=" . $_GET["quick_name"] : "";
if ($write) echo "<form method=\"post\" action=\"?BASE=$database&i=" . $i . $quick . "\">";
    
    
// ╦═╗╔═╗╔═╗╔═╗╦═╗╔═╗╔╗╔╔═╗╔═╗  ╔═╗╔═╗╔╗╔╔═╗╔╦╗╦═╗╦ ╦╔═╗╔╦╗╔═╗╦ ╦╦═╗
// ╠╦╝║╣ ╠╣ ║╣ ╠╦╝║╣ ║║║║  ║╣   ║  ║ ║║║║╚═╗ ║ ╠╦╝║ ║║   ║ ║╣ ║ ║╠╦╝
// ╩╚═╚═╝╚  ╚═╝╩╚═╚═╝╝╚╝╚═╝╚═╝  ╚═╝╚═╝╝╚╝╚═╝ ╩ ╩╚═╚═╝╚═╝ ╩ ╚═╝╚═╝╩╚═ 
echo "<fieldset><legend>Référence constructeur</legend>";

// ########### marque ###########
echo "<label for=\"marque\">Marque : </label>\n";
echo "<select name=\"marque\" onchange=\"display(this,'plus_marque','plus_marque');\" id=\"marque\">";
echo "<option value=\"0\" "; if (isset($data[0])) { if ($data[0]["marque"] == "0") echo "selected"; } echo ">— Aucune marque spécifiée —</option>";
echo "<option value=\"plus_marque\" "; if (isset($data[0]["marque"])) { if ($data[0]["marque"] == "plus_marque") echo "selected"; } echo ">− Nouvelle marque : −</option>";
option_selecteur((isset($data[0])) ? $data[0]["marque"] : "", $marques, "marque_index", "marque_nom");
echo "</select><br/>";
echo "<script>
    \$j(document).ready(function() {
        // Initialisation de Select2
        \$j('#marque').select2({
            width: '270px'
        });
    });
</script>";

// ########### + marque ###########
echo "\n\n\n";
echo "<fieldset id=\"plus_marque\" class=\"subfield\" style=\"display: none;\"><legend class=\"subfield\">Nouvelle Marque</legend>";
echo "<label for=\"plus_marque_nom\">Nom* :</label>\n";
$deja_marque = dejadanslabase("SELECT DISTINCT `marque_nom` FROM `marque` ");
echo "<input value=\"\" name=\"plus_marque_nom\" type=\"text\"  pattern=\"^(?!(" . $deja_marque . ")$).*$\" oninvalid=\"setCustomValidity('Déjà dans la base')\" oninput=\"setCustomValidity('')\" / >\n";
echo "</fieldset>";
echo "\n\n\n";

// ########### reference ###########
echo "<label for=\"reference\">Référence : </label>\n";
echo "<input value=\""; if (isset($data[0])) echo $data[0]["reference"]; echo "\" name=\"reference\" type=\"text\" id=\"reference\">";
echo "<br/>";

// ########### serial_number ###########
echo "<label for=\"serial_number\">Numéro de série : </label>\n";
echo "<input value=\""; if (isset($data[0])) echo $data[0]["serial_number"]; echo "\" name=\"serial_number\" type=\"text\" id=\"serial_number\"><br/>";

echo "</fieldset>";

//  ╔═╗╦ ╦╔╗ ╔╦╗╦╔╦╗
//  ╚═╗║ ║╠╩╗║║║║ ║
//  ╚═╝╚═╝╚═╝╩ ╩╩ ╩ 
if ($write) echo "<p style=\"text-align:center;\"><input name=\"technique_valid\" value=\"Enregistrer\" type=\"submit\" class=\"little_button\" /></p>";
if ($write) echo "</form>";

echo "<p style=\"text-align:right;\"><small>* champ obligatoire</small></p>";

echo "</div>";
?>

