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
if ( isset($_POST["carac_valid"]) ) {

/*  ╔═╗╔═╗╦═╗╔═╗╔═╗
    ║  ╠═╣╠╦╝╠═╣║
    ╚═╝╩ ╩╩╚═╩ ╩╚═╝ */
    // Supprimer tous les compatibilités de cette entrée pour réinitialiser
    $delcount = $dbh->exec("DELETE FROM carac WHERE carac_id=$i;");

    // Ajout des caracteristiques
    if (isset($_POST["carac"])) {
        $allc="";
        foreach ($_POST["carac"] as $ck => $cd) {
            $allc.= ($cd!="") ? "(\"$cd\",\"$i\",\"$ck\")," : "";
        }
        $allc=substr($allc, 0, -1); // suppression du dernier caractère
	$modif_result = $dbh->query(str_replace("\"\"", "NULL","INSERT INTO carac (carac_valeur, carac_id, carac_caracteristique_id) VALUES $allc ;"));
	$message.= (!isset($modif_result)) ? $message_error_modif : $message_success_modif;

    }
}



if ( isset($_POST["new_carac_valid"]) ) {
/*  ╔╗╔╔═╗╦ ╦╦  ╦╔═╗╦  ╦  ╔═╗  ╔═╗╔═╗╦═╗╔═╗╔═╗
    ║║║║ ║║ ║╚╗╔╝║╣ ║  ║  ║╣   ║  ╠═╣╠╦╝╠═╣║
    ╝╚╝╚═╝╚═╝ ╚╝ ╚═╝╩═╝╩═╝╚═╝  ╚═╝╩ ╩╩╚═╩ ╩╚═╝  */
    // TODO : Vérifier que la catégorie n’existe pas déjà + que le symbole n’est pas déjà pris.
    // Création d’une nouvelle caracteristique
    $arr = array("nom_carac", "unite_carac", "symbole_carac");
    foreach ($arr as &$value) {
        $$value= isset($_POST[$value]) ? htmlentities(trim($_POST[$value])) : "" ;
    }
    // Si au moins un champ de « Nouvelle caracteristique » est rempli :
    if ( ($nom_carac!="")||($symbole_carac!="") ) {
        // Si tous les champs sont remplis, on crée la nouvelle carac
        if ( ($nom_carac!="")&&($symbole_carac!="") ) {
            // Si la nouvelle carac existe déjà (nom ou symbôle)
	    $sth = $dbh->query("SELECT COUNT(*) FROM caracteristiques WHERE nom_carac=\"$nom_carac\" OR symbole_carac=\"$symbole_carac\" ;");
	    $count_carac = ($sth) ? $sth->fetchAll(PDO::FETCH_ASSOC) : FALSE ;
	    if ($sth) $sth->closeCursor();
	    $count_carac[0]["COUNT(*)"];
	}
       /* Récupère le nombre de lignes qui correspond à la requête SELECT */
        if ($count_carac[0]["COUNT(*)"]!=0) $message.="<p class=\"error_message\">Nom ou symbôle déjà utilisé.</p>";
        else {
	    $sth = $dbh->query(str_replace("\"\"", "NULL","INSERT INTO caracteristiques (nom_carac, unite_carac, symbole_carac) VALUES (\"".$nom_carac."\", \"".$unite_carac."\", \"".$symbole_carac."\"); "));
            if ( isset($sth) ) {
                $message.="<p class=\"success_message\" id=\"disappear_delay\">La nouvelle caractéristique a été ajoutée.</p>";
                $nom_carac=""; $unite_carac=""; $symbole_carac="";
            }
            else { $message.="<p class=\"error_message\" id=\"disappear_delay\">Une erreur est survenue. La nouvelle caractéristique n’a pas été ajoutée.</p>"; }
        }
    } // Sinon on indique un message d’erreur
    //else $message.="<p class=\"error_message\">Nom et Symbôle sont des champs obligatoires.</p>";
}


/*
 █████╗ ██████╗ ██████╗  █████╗ ██╗   ██╗
██╔══██╗██╔══██╗██╔══██╗██╔══██╗╚██╗ ██╔╝
███████║██████╔╝██████╔╝███████║ ╚████╔╝
██╔══██║██╔══██╗██╔══██╗██╔══██║  ╚██╔╝
██║  ██║██║  ██║██║  ██║██║  ██║   ██║
╚═╝  ╚═╝╚═╝  ╚═╝╚═╝  ╚═╝╚═╝  ╚═╝   ╚═╝
*/

// allcaracs
$sth = $dbh->query("SELECT * FROM caracteristiques WHERE carac!=0 ORDER BY nom_carac ASC ;");
$allcaracs = ($sth) ? $sth->fetchAll(PDO::FETCH_ASSOC) : FALSE ;
if ($sth) $sth->closeCursor();

// caracs de i
$sth = $dbh->query("SELECT carac, carac_valeur FROM caracteristiques, carac, base WHERE carac_id=base_index AND carac_caracteristique_id=carac AND base_index='$i' AND carac!=0 ORDER BY base.base_index ASC, carac ASC ;");
$caracs_i = ($sth) ? $sth->fetchAll(PDO::FETCH_ASSOC) : FALSE ;
if ($sth) $sth->closeCursor();

// caracs_of_categorie
$sth = $dbh->query("SELECT DISTINCT carac_caracteristique_id FROM carac, caracteristiques, base WHERE carac_id=base_index AND carac_caracteristique_id=carac AND categorie='".$data[0]["categorie"]."'");
$car_of_cat = ($sth) ? $sth->fetchAll(PDO::FETCH_ASSOC) : FALSE ;
if ($sth) $sth->closeCursor();

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
    echo "<fieldset><legend>Caractéristiques</legend>";

    echo "<label for=\"significatives[]\">Significatives : </label>";

    echo "<select data-placeholder=\"Caractéristiques significatives\" style=\"width:250px;\" class=\"chosen-select\"  multiple=\"multiple\" tabindex=\"6\" name=\"significatives[]\" id=\"multiple\">"; // TODO : Ne pas effacer les case en dessous lorsque l’on modifie "Significatives" (cases remplies mais non sauvegarder)

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
            echo "<select name='carac[".$c["carac"]."]' id='carac[".$c["carac"]."]'>";
            echo "<option value=''>Non renseigné</option>";
            echo "<option value='1' "; if ($caracs_i[$keys[0]]["carac_valeur"]=="1") echo 'selected'; echo ">Oui</option>";
            echo "<option value='0' "; if ($caracs_i[$keys[0]]["carac_valeur"]=="0") echo 'selected'; echo ">Non</option>";
            echo "</select>";
        }
        else {
	    echo "<input value='";
	    if (array_key_exists("0",$keys)) echo str_replace("'", "’", $caracs_i[$keys[0]]["carac_valeur"]);
	    echo "' name='carac[".$c["carac"]."]' type='text' id='carac[".$c["carac"]."]'>";
	}
        echo "\">";
        echo $c["nom_carac"];
        echo "</option>";
    }

    echo "</select>";
    echo "<script type=\"text/javascript\">
        var config = {
          '.chosen-select'           : {no_results_text:'Oops, nothing found!', width:\"250px\"},
        }
        for (var selector in config) {
          $(selector).chosen(config[selector]);
        }
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

    echo "<label for=\"nom_carac\">Nom :</label>\n";
    echo "<input value=\"$nom_carac\" name=\"nom_carac\" type=\"text\"><br/>\n";

    echo "<label for=\"unite_carac\">";
    echo "<abbr title=\"µm, %,… si oui/non, entrez « bool »\">Unité</abbr>";
    echo " :</label>\n";
    echo "<input value=\"$unite_carac\" name=\"unite_carac\" type=\"text\"><br/>\n";

    echo "<label for=\"symbole_carac\">";
    echo "<abbr title=\"Plus court possible (ex: λ, ω₀, Tvisible,…)\">Symbôle</abbr>";
    echo ":</label>\n";
    echo "<input value=\"$symbole_carac\" name=\"symbole_carac\" type=\"text\"><br/>\n";


    /*  ╔═╗╦ ╦╔╗ ╔╦╗╦╔╦╗
        ╚═╗║ ║╠╩╗║║║║ ║
        ╚═╝╚═╝╚═╝╩ ╩╩ ╩     */
    if ($write) echo "<p style=\"text-align:center;\"><input name=\"new_carac_valid\" value=\"Ajouter\" type=\"submit\"  class=\"little_button\" /></p>"; // TODO Ajouter un bouton réinitialiser ?

    if ($write) echo "</form>";

    echo "</fieldset>";


echo "</div>";

?>
