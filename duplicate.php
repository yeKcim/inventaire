<?php
$titre = "Dupliquer une entrée";
require_once("./0_connect.php");
if ($database == "") require_once("./0_baseselector.php");
require_once("./0_connect_db.php");
require_once("./0_tables_sql_commun.php");
require_once("./0_head.php");
?>

<body>

<?php
require_once("./0_fonctions.php");
$error = "";
$success = "";

$copyid = isset($_GET["i"]) ? htmlentities($_GET["i"]) : "";

/*
  ██████╗ ██████╗ ██████╗ ██╗   ██╗    ██╗   ██╗ █████╗ ██████╗ ██╗ █████╗ ██████╗ ██╗     ███████╗███████╗
 ██╔════╝██╔═══██╗██╔══██╗╚██╗ ██╔╝    ██║   ██║██╔══██╗██╔══██╗██║██╔══██╗██╔══██╗██║     ██╔════╝██╔════╝
 ██║     ██║   ██║██████╔╝ ╚████╔╝     ██║   ██║███████║██████╔╝██║███████║██████╔╝██║     █████╗  ███████╗
 ██║     ██║   ██║██╔═══╝   ╚██╔╝      ╚██╗ ██╔╝██╔══██║██╔══██╗██║██╔══██║██╔══██╗██║     ██╔══╝  ╚════██║
 ╚██████╗╚██████╔╝██║        ██║        ╚████╔╝ ██║  ██║██║  ██║██║██║  ██║██████╔╝███████╗███████╗███████║
  ╚═════╝ ╚═════╝ ╚═╝        ╚═╝         ╚═══╝  ╚═╝  ╚═╝╚═╝  ╚═╝╚═╝╚═╝  ╚═╝╚═════╝ ╚══════╝╚══════╝╚══════╝
*/

$paste = [];

// Récupération des données de la base
$sql = "SELECT categorie, reference, designation, tutelle, contrat, bon_commande, vendeur, marque, date_achat, responsable_achat, garantie, prix, sortie, raison_sortie, lab_id FROM base WHERE base_index = ?;";
$sth = $dbh->prepare($sql);
$sth->execute([$copyid]);
$copy = $sth->fetchAll(PDO::FETCH_ASSOC);
$sth->closeCursor();

$paste["lab_id"] = new_lab_id($copy[0]["categorie"]);
$paste["id"] = return_last_id("base_index", "base") + 1;






/*
 ██████╗  █████╗ ███████╗████████╗███████╗    ██╗   ██╗ █████╗ ██████╗ ██╗ █████╗ ██████╗ ██╗     ███████╗███████╗
 ██╔══██╗██╔══██╗██╔════╝╚══██╔══╝██╔════╝    ██║   ██║██╔══██╗██╔══██╗██║██╔══██╗██╔══██╗██║     ██╔════╝██╔════╝
 ██████╔╝███████║███████╗   ██║   █████╗      ██║   ██║███████║██████╔╝██║███████║██████╔╝██║     █████╗  ███████╗
 ██╔═══╝ ██╔══██║╚════██║   ██║   ██╔══╝      ╚██╗ ██╔╝██╔══██║██╔══██╗██║██╔══██║██╔══██╗██║     ██╔══╝  ╚════██║
 ██║     ██║  ██║███████║   ██║   ███████╗     ╚████╔╝ ██║  ██║██║  ██║██║██║  ██║██████╔╝███████╗███████╗███████║
 ╚═╝     ╚═╝  ╚═╝╚══════╝   ╚═╝   ╚══════╝      ╚═══╝  ╚═╝  ╚═╝╚═╝  ╚═╝╚═╝╚═╝  ╚═╝╚═════╝ ╚══════╝╚══════╝╚══════╝
*/

if (isset($_POST["add_valid"])) {


	// #################### INSERT IN BASE ####################
	$sql = "INSERT INTO base (base_index, lab_id, categorie, reference, designation, tutelle, contrat, bon_commande, vendeur, marque, date_achat, responsable_achat, garantie, prix, sortie, raison_sortie) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";
	$sth = $dbh->prepare($sql);
	$sth->execute([
		$paste["id"], $paste["lab_id"], $copy[0]["categorie"], $copy[0]["reference"], $copy[0]["designation"], $copy[0]["tutelle"], $copy[0]["contrat"], $copy[0]["bon_commande"], $copy[0]["vendeur"], $copy[0]["marque"], $copy[0]["date_achat"], $copy[0]["responsable_achat"], $copy[0]["garantie"], $copy[0]["prix"], $copy[0]["sortie"], $copy[0]["raison_sortie"]
	]);
	$sth->closeCursor();

	// #################### INSERT IN CARAC ####################
	$sql = "SELECT carac_valeur, carac_caracteristique_id FROM carac WHERE carac_id = ?;";
	$sth = $dbh->prepare($sql);
	$sth->execute([$copyid]);
	$copy_carac = $sth->fetchAll(PDO::FETCH_ASSOC);
	$sth->closeCursor();

	$sql = "INSERT INTO carac (carac_valeur, carac_id, carac_caracteristique_id) VALUES (?, ?, ?);";
	$sth = $dbh->prepare($sql);
	foreach ($copy_carac as $cc) {
		$sth->execute([$cc["carac_valeur"], $paste["id"], $cc["carac_caracteristique_id"]]);
	}
	$sth->closeCursor();

	// #################### INSERT IN COMPATIB ####################
	$sql = "SELECT compatib_id1, compatib_id2 FROM compatibilite WHERE compatib_id1 = ? OR compatib_id2 = ?;";
	$sth = $dbh->prepare($sql);
	$sth->execute([$copyid, $copyid]);
	$copy_compatibilite = $sth->fetchAll(PDO::FETCH_ASSOC);
	$sth->closeCursor();

	$sql = "INSERT INTO compatibilite (compatib_id1, compatib_id2) VALUES (?, ?);";
	$sth = $dbh->prepare($sql);
	foreach ($copy_compatibilite as $cc) {
		$A = ($cc["compatib_id1"] == $copyid) ? $paste["id"] : $cc["compatib_id1"];
		$B = ($cc["compatib_id2"] == $copyid) ? $paste["id"] : $cc["compatib_id2"];
		$sth->execute([$A, $B]);
	}
	$sth->closeCursor();

	// #################### INSERT IN ENTRETIEN ####################
	$sql = "SELECT e_frequence, e_lastdate, e_designation, e_detail, e_effectuerpar FROM entretien WHERE e_id = ?;";
	$sth = $dbh->prepare($sql);
	$sth->execute([$copyid]);
	$copy_entretien = $sth->fetchAll(PDO::FETCH_ASSOC);
	$sth->closeCursor();

	$sql = "INSERT INTO entretien (e_id, e_frequence, e_lastdate, e_designation, e_detail) VALUES (?, ?, ?, ?, ?);";
	$sth = $dbh->prepare($sql);
	foreach ($copy_entretien as $ce) {
		$sth->execute([$paste["id"], $ce["e_frequence"], $ce["e_lastdate"], $ce["e_designation"], $ce["e_detail"]]);
	}
	$sth->closeCursor();



    $success .= "<p class=\"success_message\">";
    $success .= "L’entrée #" . $copyid . " a été dupliquée dans la base de donnée.<br/>";
    $success .= "Rendez-vous sur la page de <a href=\"info.php?BASE=$database&i=" . $paste["id"] . "\" target=\"_blank\"><strong>" . $paste["lab_id"] . " (#" . $paste["id"] . ")</strong></a> pour compléter ses informations";
    $success .= "</p>";
    $duplication_done=1;
}


/*
██████╗ ██╗███████╗██████╗ ██╗      █████╗ ██╗   ██╗    ██████╗ ██╗      ██████╗  ██████╗███████╗
██╔══██╗██║██╔════╝██╔══██╗██║     ██╔══██╗╚██╗ ██╔╝    ██╔══██╗██║     ██╔═══██╗██╔════╝██╔════╝
██║  ██║██║███████╗██████╔╝██║     ███████║ ╚████╔╝     ██████╔╝██║     ██║   ██║██║     ███████╗
██║  ██║██║╚════██║██╔═══╝ ██║     ██╔══██║  ╚██╔╝      ██╔══██╗██║     ██║   ██║██║     ╚════██║
██████╔╝██║███████║██║     ███████╗██║  ██║   ██║       ██████╔╝███████╗╚██████╔╝╚██████╗███████║
╚═════╝ ╚═╝╚══════╝╚═╝     ╚══════╝╚═╝  ╚═╝   ╚═╝       ╚═════╝ ╚══════╝ ╚═════╝  ╚═════╝╚══════╝
*/

$write = false;
echo "<form method=\"post\" action=\"\">";
echo "<div id=\"container\">";

    /*  ╔═╗╦ ╦╔╗ ╔╦╗╦╔╦╗
        ╚═╗║ ║╠╩╗║║║║ ║
        ╚═╝╚═╝╚═╝╩ ╩╩ ╩     */
	echo "<div id=\"bloc\" style=\"background:#f3f3f3; vertical-align:top;\">";

		echo "<h1>Dupliquer #" . $copyid . "</h1>";

		echo $success;
		echo "Entrée à dupliquer&nbsp;: #" . $copyid . " - ".$paste["lab_id"]."<br/>";
		echo "Base de données&nbsp;: " . $database . "<br/>";

		echo "<p style=\"text-align:center;\">";

		if (!isset($duplication_done)) echo "<input name=\"add_valid\" value=\"Valider la duplication\" type=\"submit\" class=\"big_button\" />";
		else echo "<input name=\"add_valid\" value=\"Valider encore une duplication\" type=\"submit\" class=\"big_button\" />";
		
		echo "</p>";

		echo $error;

	echo "</div>";

echo "</div>";

echo "</form>";

echo "</body></html>";
$dbh = null;

?>

