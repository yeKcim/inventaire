<?php
/*
 ██████╗ █████╗ ██████╗  █████╗  ██████╗
██╔════╝██╔══██╗██╔══██╗██╔══██╗██╔════╝
██║     ███████║██████╔╝███████║██║
██║     ██╔══██║██╔══██╗██╔══██║██║
╚██████╗██║  ██║██║  ██║██║  ██║╚██████╗
 ╚═════╝╚═╝  ╚═╝╚═╝  ╚═╝╚═╝  ╚═╝ ╚═════╝
*/

$message="";

/*
███╗   ███╗ ██████╗ ██████╗ ██╗███████╗    ███████╗ ██████╗ ██╗
████╗ ████║██╔═══██╗██╔══██╗██║██╔════╝    ██╔════╝██╔═══██╗██║
██╔████╔██║██║   ██║██║  ██║██║█████╗      ███████╗██║   ██║██║
██║╚██╔╝██║██║   ██║██║  ██║██║██╔══╝      ╚════██║██║▄▄ ██║██║
██║ ╚═╝ ██║╚██████╔╝██████╔╝██║██║██╗      ███████║╚██████╔╝███████╗
╚═╝     ╚═╝ ╚═════╝ ╚═════╝ ╚═╝╚═╝╚═╝      ╚══════╝ ╚══▀▀═╝ ╚══════╝
*/
/*  ╔═╗╔═╗╦═╗╔═╗╔═╗
    ║  ╠═╣╠╦╝╠═╣║
    ╚═╝╩ ╩╩╚═╩ ╩╚═╝ */
if (isset($_POST["carac_valid"])) {
    $sql = "DELETE FROM carac WHERE carac_id = ?;";
    $sth = $dbh->prepare($sql);
    $delcount = $sth->execute([$i]);

    if (isset($_POST["carac"])) {
        $allc = [];
        foreach ($_POST["carac"] as $ck => $cd) {
            if ($cd != "") {
                $allc[] = [$cd, $i, $ck];
            }
        }

        $sql = "INSERT INTO carac (carac_valeur, carac_id, carac_caracteristique_id) VALUES (?, ?, ?);";
        $sth = $dbh->prepare($sql);

        $modif_result = true;
        foreach ($allc as $values) {
            if (!$sth->execute($values)) {
                $modif_result = false;
                break;
            }
        }

        $sth->closeCursor();
        $message .= (!$modif_result) ? $message_error_modif : $message_success_modif;
    }
}





/*  ╔╗╔╔═╗╦ ╦╦  ╦╔═╗╦  ╦  ╔═╗  ╔═╗╔═╗╦═╗╔═╗╔═╗
    ║║║║ ║║ ║╚╗╔╝║╣ ║  ║  ║╣   ║  ╠═╣╠╦╝╠═╣║
    ╝╚╝╚═╝╚═╝ ╚╝ ╚═╝╩═╝╩═╝╚═╝  ╚═╝╩ ╩╩╚═╩ ╩╚═╝  */
if (isset($_POST["new_carac_valid"])) {
    $arr = ["nom_carac", "unite_carac", "symbole_carac"];
    foreach ($arr as &$value) {
        $$value = isset($_POST[$value]) ? htmlentities(trim($_POST[$value])) : "";
    }

    if ($nom_carac != "" || $symbole_carac != "") {
        if ($nom_carac != "" && $symbole_carac != "") {
            $sql = "SELECT COUNT(*) FROM caracteristiques WHERE nom_carac = :nom_carac OR symbole_carac = :symbole_carac;";
            $sth = $dbh->prepare($sql);
            $sth->execute([':nom_carac' => $nom_carac, ':symbole_carac' => $symbole_carac]);
            $count_carac = $sth->fetchColumn();
            $sth->closeCursor();

            if ($count_carac != 0) {
                $message .= "<p class=\"error_message\">Nom ou symbôle déjà utilisé.</p>";
            } else {
                $sql = "INSERT INTO caracteristiques (nom_carac, unite_carac, symbole_carac) VALUES (:nom_carac, :unite_carac, :symbole_carac);";
                $sth = $dbh->prepare($sql);
                $modif_result = $sth->execute([':nom_carac' => $nom_carac, ':unite_carac' => $unite_carac, ':symbole_carac' => $symbole_carac]);
                $sth->closeCursor();

                if ($modif_result) {
                    $message .= "<p class=\"success_message\" id=\"disappear_delay\">La nouvelle caractéristique a été ajoutée.</p>";
                    $nom_carac = ""; $unite_carac = ""; $symbole_carac = "";
                } else {
                    $message .= "<p class=\"error_message\" id=\"disappear_delay\">Une erreur est survenue. La nouvelle caractéristique n’a pas été ajoutée.</p>";
                }
            }
        }
    }
}



/*
 █████╗ ██████╗ ██████╗  █████╗ ██╗   ██╗
██╔══██╗██╔══██╗██╔══██╗██╔══██╗╚██╗ ██╔╝
███████║██████╔╝██████╔╝███████║ ╚████╔╝
██╔══██║██╔══██╗██╔══██╗██╔══██║  ╚██╔╝
██║  ██║██║  ██║██║  ██║██║  ██║   ██║
╚═╝  ╚═╝╚═╝  ╚═╝╚═╝  ╚═╝╚═╝  ╚═╝   ╚═╝
*/

// Sélection de toutes les caractéristiques
$sql = "SELECT * FROM caracteristiques WHERE carac != 0 ORDER BY nom_carac ASC;";
$sth = $dbh->prepare($sql);
$sth->execute();
$allcaracs = $sth->fetchAll(PDO::FETCH_ASSOC);
$sth->closeCursor();

// Caractéristiques de l'élément 'i'
$sql = "SELECT carac, carac_valeur FROM caracteristiques, carac, base WHERE carac_id = base_index AND carac_caracteristique_id = carac AND base_index = ? AND carac != 0 ORDER BY base.base_index ASC, carac ASC;";
$sth = $dbh->prepare($sql);
$sth->execute([$i]);
$caracs_i = $sth->fetchAll(PDO::FETCH_ASSOC);
$sth->closeCursor();

// Caractéristiques de la catégorie
$sql = "SELECT DISTINCT carac_caracteristique_id FROM carac, caracteristiques, base WHERE carac_id = base_index AND carac_caracteristique_id = carac AND categorie = ?;";
$sth = $dbh->prepare($sql);
$sth->execute([$data[0]["categorie"]]);
$car_of_cat = $sth->fetchAll(PDO::FETCH_ASSOC);
$sth->closeCursor();

// Cas de création d’une nouvelle caracteristique, pour garder les champs remplis en cas d’erreur
$arr = array("nom_carac", "unite_carac", "symbole_carac");
foreach ($arr as $value) { $$value= isset($$value) ? "".$$value."" : "" ; }

/*
███████╗ ██████╗ ██████╗ ███╗   ███╗██╗   ██╗██╗      █████╗ ██╗██████╗ ███████╗
██╔════╝██╔═══██╗██╔══██╗████╗ ████║██║   ██║██║     ██╔══██╗██║██╔══██╗██╔════╝
█████╗  ██║   ██║██████╔╝██╔████╔██║██║   ██║██║     ███████║██║██████╔╝█████╗
██╔══╝  ██║   ██║██╔══██╗██║╚██╔╝██║██║   ██║██║     ██╔══██║██║██╔══██╗██╔══╝
██║     ╚██████╔╝██║  ██║██║ ╚═╝ ██║╚██████╔╝███████╗██║  ██║██║██║  ██║███████╗
╚═╝      ╚═════╝ ╚═╝  ╚═╝╚═╝     ╚═╝ ╚═════╝ ╚══════╝╚═╝  ╚═╝╚═╝╚═╝  ╚═╝╚══════╝
*/

echo "<div id=\"bloc\" style=\"background:#daefc5; vertical-align:top;\">";

    echo "<h1>Caractéristiques</h1>";

    echo $message;

    $quick= ( isset($_GET["quick_page"]) ) ? "&quick_page=".$_GET["quick_page"]."&quick_name=".$_GET["quick_name"]."" : "";

    if ($write) echo "<form method=\"post\" action=\"?BASE=$database&i=".$i."".$quick."\">";

/*  ╔═╗╔═╗╦═╗╔═╗╔═╗╔╦╗╔═╗╦═╗╦╔═╗╔╦╗╦╔═╗ ╦ ╦╔═╗╔═╗
    ║  ╠═╣╠╦╝╠═╣║   ║ ║╣ ╠╦╝║╚═╗ ║ ║║═╬╗║ ║║╣ ╚═╗
    ╚═╝╩ ╩╩╚═╩ ╩╚═╝ ╩ ╚═╝╩╚═╩╚═╝ ╩ ╩╚═╝╚╚═╝╚═╝╚═╝   */
    echo "\n<fieldset><legend>Caractéristiques</legend>\n\n";

    echo "<label for=\"significatives[]\">Significatives : </label>\n";

    echo "\n<select  style=\"width:250px;\" class=\"select2\"  multiple=\"multiple\" tabindex=\"6\" name=\"significatives[]\" id=\"multiple\">\n"; // TODO : Ne pas effacer les case en dessous lorsque l’on modifie "Significatives" (cases remplies mais non sauvegarder)

    foreach ($allcaracs as $c) {

        echo "<option ";

	$keys = array_keys(array_column($car_of_cat, 'carac_caracteristique_id'), $c["carac"]);
	if (array_key_exists("0",$keys)) echo "selected=\"selected\" ";
        //if (in_array($c["carac"], $car_of_cat)) echo "selected=\"selected\" ";


        echo "value=\"";

        /* ####### Label ####### */
        echo "<label for='carac["; if (array_key_exists("0", $c)) echo $c[0]; echo "]'>";
		echo "<abbr title='".str_replace("'", "’", $c["nom_carac"])."' >".str_replace("'", "’", $c["symbole_carac"])."</abbr> "; // TODO : ne supporte pas les apostrophe dans $c["nom_carac"] ! voir exemple avec « longueur d’onde »

        if ( ($c["unite_carac"]!="bool")&&($c["unite_carac"]!="") ) echo "(".str_replace("'", "’", $c["unite_carac"]).")"; // Si ce n’est pas un booléen on affiche l’unité
        echo " : </label>\n";

	$keys = array_keys(array_column($caracs_i, 'carac'), $c["carac"]);

        if ($c["unite_carac"]=="bool") {
            echo "\n\t<select name='carac[".$c["carac"]."]' id='carac_".$c["carac"]."_'>";
            echo "\n\t\t<option value=''>Non renseigné</option>";
            echo "\n\t\t<option value='1' "; if (array_key_exists("0",$keys)) { if ($caracs_i[$keys[0]]["carac_valeur"]=="1") echo 'selected';} echo ">Oui</option>";
            echo "\n\t\t<option value='0' "; if (array_key_exists("0",$keys)) { if ($caracs_i[$keys[0]]["carac_valeur"]=="0") echo 'selected';} echo ">Non</option>";
            echo "\n\t</select>\n";
            /*select2 pour recherche */
			echo "<script>
			\$j(document).ready(function() {
				\$j('#carac_".$c["carac"]."_').select2({
					minimumResultsForSearch: Infinity
				});
			});
			</script>";
						
        }
        else {
	    echo "\t<input value='";
	    if (array_key_exists("0",$keys)) echo str_replace("'", "’", $caracs_i[$keys[0]]["carac_valeur"]);
	    echo "' name='carac[".$c["carac"]."]' type='text' id='carac[".$c["carac"]."]'>";
	}
        echo "\">";
        echo $c["nom_carac"]." (".$c["symbole_carac"].")";
        echo "</option>";
    }

    echo "\n</select>\n\n";
    /*select2 pour recherche */
	  echo "<script>
		\$j(document).ready(function() {
			\$j('#multiple').select2({
				placeholder: \"Sélectionnez les caractéristiques significatives\",
		    	allowClear: true
			});
		});
	  </script>";
    


echo "<fieldset id=\"caracs\" class=\"subfield\"><legend class=\"subfield\">Caractéristiques :</legend>";
    echo "<pi></pi>";
echo "</fieldset>";
    echo "<script>
        function displayVals() {
          var multipleValues = $( \"#multiple\" ).val() || [];
          $( \"pi\" ).html( \"\" + multipleValues.join( \"<br/>\" ) );
        }
        $( \"select\" ).change( displayVals );
        displayVals();
    </script>";

    if ($write) echo "<p style=\"text-align:center;\"><input name=\"carac_valid\" value=\"Enregistrer\" type=\"submit\"  class=\"little_button\" /></p>"; // TODO Ajouter un bouton réinitialiser ?

    if ($write) echo "</form>";

    echo "</fieldset>";




    if ($write) echo "<form method=\"post\" action=\"?BASE=$database&i=".$i."".$quick."\">";

/*  ╔╗╔╔═╗╦ ╦╦  ╦╔═╗╦  ╦  ╔═╗  ╔═╗╔═╗╦═╗╔═╗╔═╗
    ║║║║ ║║ ║╚╗╔╝║╣ ║  ║  ║╣   ║  ╠═╣╠╦╝╠═╣║
    ╝╚╝╚═╝╚═╝ ╚╝ ╚═╝╩═╝╩═╝╚═╝  ╚═╝╩ ╩╩╚═╩ ╩╚═╝  */
    echo "<fieldset><legend>Nouvelle caractéristique</legend>";

    // TODO des catégories de caractéristiques ?
    echo "Si la caractéristique n’est pas présente dans la liste ci-dessus…<br/>";

    echo "<label for=\"nom_carac\">Nom* :</label>\n";
    $deja_nomcarac=dejadanslabase("SELECT DISTINCT `nom_carac` FROM `caracteristiques`");
    echo "<input value=\"$nom_carac\" name=\"nom_carac\" type=\"text\" required pattern=\"^(?!(".$deja_nomcarac.")$).*$\" oninvalid=\"setCustomValidity('Déjà dans la base')\" oninput=\"setCustomValidity('')\" /><br/>\n";

    echo "<label for=\"unite_carac\">";
    echo "<abbr title=\"µm, %,… si oui/non, entrez « bool »\">Unité</abbr>";
    echo " :</label>\n";
    echo "<input value=\"$unite_carac\" name=\"unite_carac\" type=\"text\" /><br/>\n";

    echo "<label for=\"symbole_carac\">";
    echo "<abbr title=\"Plus court possible (ex: λ, ω₀, Tvisible,…)\">Symbôle* </abbr>";
    echo ":</label>\n";
    $deja_symbole=dejadanslabase("SELECT DISTINCT `symbole_carac` FROM `caracteristiques`");
    echo "<input value=\"$symbole_carac\" name=\"symbole_carac\" type=\"text\" required pattern=\"^(?!(".$deja_symbole.")$).*$\" oninvalid=\"setCustomValidity('Déjà dans la base')\" oninput=\"setCustomValidity('')\" /><br/>\n";


    /*  ╔═╗╦ ╦╔╗ ╔╦╗╦╔╦╗
        ╚═╗║ ║╠╩╗║║║║ ║
        ╚═╝╚═╝╚═╝╩ ╩╩ ╩     */
    if ($write) echo "<p style=\"text-align:center;\"><input name=\"new_carac_valid\" value=\"Ajouter\" type=\"submit\"  class=\"little_button\" /></p>"; // TODO Ajouter un bouton réinitialiser ?

    if ($write) echo "</form>";
    
        echo "<p style=\"text-align:right;\"><small>* champ obligatoire</small></p>"; 

    echo "</fieldset>";

echo "</div>";

?>
