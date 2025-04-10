 <?php

$titre="Ajouter une nouvelle base d’inventaire";
require_once("./0_connect.php");
require_once("./0_head.php");

echo "<body>";
$error="";
$success="";

/*  ██████╗██████╗ ███████╗ █████╗ ████████╗██╗ ██████╗ ███╗   ██╗    ██████╗  █████╗ ███████╗███████╗
   ██╔════╝██╔══██╗██╔════╝██╔══██╗╚══██╔══╝██║██╔═══██╗████╗  ██║    ██╔══██╗██╔══██╗██╔════╝██╔════╝
   ██║     ██████╔╝█████╗  ███████║   ██║   ██║██║   ██║██╔██╗ ██║    ██████╔╝███████║███████╗█████╗
   ██║     ██╔══██╗██╔══╝  ██╔══██║   ██║   ██║██║   ██║██║╚██╗██║    ██╔══██╗██╔══██║╚════██║██╔══╝
   ╚██████╗██║  ██║███████╗██║  ██║   ██║   ██║╚██████╔╝██║ ╚████║    ██████╔╝██║  ██║███████║███████╗
    ╚═════╝╚═╝  ╚═╝╚══════╝╚═╝  ╚═╝   ╚═╝   ╚═╝ ╚═════╝ ╚═╝  ╚═══╝    ╚═════╝ ╚═╝  ╚═╝╚══════╝╚══════╝ */

// Récupération des datas POST
$postData = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

if ($postData!=null) {
	//var_dump($postData);
	$name_db=$postData["name_db"];
	
	$color=$postData["color"];
	
	$blocs="";
	foreach ($postData["modules"] as &$bloc) $blocs.=$bloc.",";
	$blocs=substr($blocs, 0, -1);

	// Création de la base
	$newdb = $prefix . $name_db;
	try {
		$dbh = new PDO("mysql:host=$connecthost", $connectlogin, $connectpasse);
		$dbh->exec("CREATE DATABASE `$newdb`;") or die(print_r($dbh->errorInfo(), true));
	} catch (PDOException $e) {  
		die("DB ERROR: " . $e->getMessage());
	}
	echo "<p class=\"success_message\">Base $newdb créée avec succès.</p>";

	// Sélection de la nouvelle base
	$dbh->exec("USE `$newdb`;");

	// Insertion des tables
	$database = $name_db;
	require_once("./0_connect_db.php");
	require_once("./0_fonctions.php");
	$add_tables = file_get_contents("./database_add.sql");
	$qr = $dbh->exec($add_tables);
	if ($qr === false) {
		echo "<p class=\"error_message\">Erreur lors de la création des tables, merci de contacter votre administrateur.</p>";
	} else {
		echo "<p class=\"success_message\">Tables ajoutées à $newdb.</p>";
	}

	// Mise à jour de SETTINGS
	$updateSettings = "UPDATE `SETTINGS` 
		               SET `color` = '{$color}', `blocs` = '{$blocs}' 
		               WHERE `i` = 1;";
		               
	$result = $dbh->exec($updateSettings);
	if ($result === false) {
		$errorInfo = $dbh->errorInfo();
		echo "<p class=\"error_message\">Erreur lors de la mise à jour de SETTINGS : " . implode(' - ', $errorInfo) . "</p>";
	} else {
		echo "<p class=\"success_message\">SETTINGS modifiés avec succès.</p><p>Vous pouvez fermer ce cadre.</p>";
	}

	// Création du dossier correspondant à la nouvelle base dans files/
	$dir = $dossierdesfichiers . $name_db;
	if (!file_exists($dir)) {
		$umask_bak = umask(0);
		if (!mkdir($dir, 0775, true)) {
		    $error = error_get_last();
		    umask($umask_bak);
		    die("Erreur lors de la création du dossier '$dir' : " . $error['message']);
		}
		umask($umask_bak);
	}

}


/*
███████╗ ██████╗ ██████╗ ███╗   ███╗██╗   ██╗██╗      █████╗ ██╗██████╗ ███████╗
██╔════╝██╔═══██╗██╔══██╗████╗ ████║██║   ██║██║     ██╔══██╗██║██╔══██╗██╔════╝
█████╗  ██║   ██║██████╔╝██╔████╔██║██║   ██║██║     ███████║██║██████╔╝█████╗
██╔══╝  ██║   ██║██╔══██╗██║╚██╔╝██║██║   ██║██║     ██╔══██║██║██╔══██╗██╔══╝
██║     ╚██████╔╝██║  ██║██║ ╚═╝ ██║╚██████╔╝███████╗██║  ██║██║██║  ██║███████╗
╚═╝      ╚═════╝ ╚═╝  ╚═╝╚═╝     ╚═╝ ╚═════╝ ╚══════╝╚═╝  ╚═╝╚═╝╚═╝  ╚═╝╚══════╝
*/
else {

	echo "<p><strong>Ajouter une nouvelle base</strong></p>";
	
	echo "<form method=\"post\" action=\"?\">";





	// NOM
	echo "<label for=\"name_db\" style=\"vertical-align: top;\">Nom&nbsp;:</label>\n";
	echo "<input type=\"text\" name=\"name_db\" class=\"restricted-input\" pattern=\"[A-Za-z0-9_-]{1,20}\" required title=\"« 20 max alphanumérique - et _ »\" /><br/><br/>";




	// COULEUR
	$random_color = sprintf('#%06X', mt_rand(0, 0xFFFFFF));
	echo "<label for=\"color\" style=\"vertical-align: top;\">
		<abbr title=\"Pour différencier visuellement la base parmi d’autres. Comme nous mettons des étiquettes, nous choisissons la couleur de l’étiquette correspondante\">
		Couleur</abbr>&nbsp;:</label>";
	echo "<input type=\"color\" id=\"color\" name=\"color\" value=\"".$random_color."\" /><br/><br/>";




	// MODULES
	echo "<label for=\"modules\" style=\"vertical-align: top;\">
		<abbr title=\"Vous pouvez activer/désactiver certaines fonction et glisser/déposer pour modifier l’ordre\">
		Modules</abbr>&nbsp;:</label>";
	$dir = "./modules/";
	echo "\n<fieldset style=\"margin-left:120px; width:200px;\">\n\n";
	echo "<ol id=\"modules\">";
	  foreach (scandir($dir) as $item) {
		if ($item === '.' || $item === '..') continue;
		if (is_dir($dir . DIRECTORY_SEPARATOR . $item)) {
		    echo "<li>⇅<input type=\"checkbox\" name=\"modules[]\" value=\"{$item}\" checked> {$item}</li>";
		}
	  }
	echo "</ol>";
	echo "<script src=\"sortable/Sortable.min.js\"></script>
		<script>
		  Sortable.create(document.getElementById('modules'), {
			animation: 150
		  });
		</script>";

echo "\n</fieldset>\n\n";
	 
	echo "<p><input name=\"add_db\" value=\"Créer\" type=\"submit\" class=\"little_button\" /></p>";
	echo "</form>";
}


?>
