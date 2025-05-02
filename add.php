<?php
$titre="Ajouter une entrée";
require_once("./0_connect.php");
if ($database=="") require_once("./0_baseselector.php");
require_once("./0_connect_db.php");
require_once("./0_tables_sql_commun.php");
require_once("./0_head.php");
echo "<body>";


require_once("./0_fonctions.php");
require_once("./0_settings.php");
$error="";


$message= "";

$data = array_map(fn($v) => htmlentities($v), $_POST);
    
if ( isset($data["add_valid"]) ) {

    // Ajout d’une entrée minimale qui sera ensuite modifiée par les blocs
	$added = $dbh->exec("INSERT INTO base (categorie) VALUES (0)");
	$i = $dbh->lastInsertId();
	
	if (isset($i)) {
		$message .= "<p class=\"success_message\">";
		$message .= "L’entrée a été ajoutée à la base de donnée.<br/>";
		$message .= "Vous pouvez directement ajouter une nouvelle entrée<br/>";
		$message .= "ou <a href=\"info.php?BASE=$database&i=$i\" target=\"_blank\"><strong>→ Compléter les informations de #{$i}</strong></a>";
		$message .= "</p>";
	} else {
		$error .= $message_error_add;
	}
}

// ██████╗ ██╗███████╗██████╗ ██╗      █████╗ ██╗   ██╗    ██████╗ ██╗      ██████╗  ██████╗███████╗
// ██╔══██╗██║██╔════╝██╔══██╗██║     ██╔══██╗╚██╗ ██╔╝    ██╔══██╗██║     ██╔═══██╗██╔════╝██╔════╝
// ██║  ██║██║███████╗██████╔╝██║     ███████║ ╚████╔╝     ██████╔╝██║     ██║   ██║██║     ███████╗
// ██║  ██║██║╚════██║██╔═══╝ ██║     ██╔══██║  ╚██╔╝      ██╔══██╗██║     ██║   ██║██║     ╚════██║
// ██████╔╝██║███████║██║     ███████╗██║  ██║   ██║       ██████╔╝███████╗╚██████╔╝╚██████╗███████║
// ╚═════╝ ╚═╝╚══════╝╚═╝     ╚══════╝╚═╝  ╚═╝   ╚═╝       ╚═════╝ ╚══════╝ ╚═════╝  ╚═════╝╚══════╝

$write=false;

echo "<p>Ajouter une entrée :</p>";

echo $message;

echo "<form method=\"post\" action=\"\"id=\"main_form\" >";

echo "<div id=\"container\">";

	$indexmax=3;
	for ($index = 0; $index < $indexmax && $index < count($SETTINGS_modules); $index++) {
		if ( ($SETTINGS_modules[$index] == "journal")
			 || ($SETTINGS_modules[$index] == "entretien")
			 || ($SETTINGS_modules[$index] == "documents")
		) $indexmax++;
		else require_once("./modules/{$SETTINGS_modules[$index]}/bloc.php");
	}

    // ╔═╗╦ ╦╔╗ ╔╦╗╦╔╦╗
    // ╚═╗║ ║╠╩╗║║║║ ║
    // ╚═╝╚═╝╚═╝╩ ╩╩ ╩ 
    echo "<div id=\"bloc\" style=\"background:#f3f3f3; vertical-align:top;\">";
    echo "<h1>Validation</h1>";
    echo "<p style=\"text-align:center;\">";
    echo "<input name=\"add_valid\" value=\"Ajouter\" type=\"submit\" class=\"big_button\" form=\"main_form\"/>";
    echo "</p>"; // TODO Ajouter un bouton réinitialiser

    echo $error;

    echo "</div>";

echo "</div>";

echo "</form>";

echo "</body></html>";
$dbh = null;
