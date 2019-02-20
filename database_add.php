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
if ( isset($_POST["add_db"]) ) {
    $data=array();
    $arr = array("name_db");
    foreach ($arr as &$value) {
        $$value= isset($_POST[$value]) ? htmlentities($_POST[$value]) : "" ;
    }
    if ($name_db!="") {
	    // Création de la base
	    $newdb=$prefix.$name_db;
	    try {
    	      $dbh = new PDO("mysql:host=$connecthost", $connectlogin, $connectpasse);
    	      $dbh->exec("CREATE DATABASE `$newdb`;")
    	      or die(print_r($dbh->errorInfo(), true));
  	    } catch (PDOException $e) {  die("DB ERROR: ". $e->getMessage());  }
  	    echo "<p class=\"success_message\">Base $newdb créée avec succès.</p>";

	    // Insertion des tables
	    $database=$name_db;
	    require_once("./0_connect_db.php");
	    require_once("./0_fonctions.php");
	    $add_tables = file_get_contents("./database_add.sql");
	    $qr = $dbh->exec($add_tables);
	    if ($qr) echo "<p class=\"error_message\">Erreur lors de la création des tables, merci de contacter votre administrateur.</p>";
	    else echo "<p class=\"success_message\">Tables ajoutées à $newdb.</p><p>Vous pouvez fermer ce cadre.</p>";

        // Création du dossier correspondant à la nouvelle base dans files/
        $dir="".$dossierdesfichiers.$name_db;
        if (!file_exists("$dir")) { $umask_bak=umask(0); mkdir("$dir", 0775); umask($umask_bak); }
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
  echo "<label for=\"name_db\" style=\"vertical-align: top;\">Nom de l’inventaire :</label>\n";
  echo "<input type=\"text\" name=\"name_db\" pattern=\"^[A-Za-z0-9_-]{1,20}$\" title=\"« 20 max alphanumérique - et _ »\" />";

  echo "<p><input name=\"add_db\" value=\"Créer\" type=\"submit\" class=\"little_button\" /></p>";
  echo "</form>";
}


?>
