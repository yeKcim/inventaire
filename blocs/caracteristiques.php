<?php
/*
 ██████╗ █████╗ ██████╗  █████╗  ██████╗
██╔════╝██╔══██╗██╔══██╗██╔══██╗██╔════╝
██║     ███████║██████╔╝███████║██║     
██║     ██╔══██║██╔══██╗██╔══██║██║     
╚██████╗██║  ██║██║  ██║██║  ██║╚██████╗
 ╚═════╝╚═╝  ╚═╝╚═╝  ╚═╝╚═╝  ╚═╝ ╚═════╝
*/

$message_newcarac="";

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
    mysql_query ("DELETE FROM carac WHERE carac_id=$i;");

    // Ajout des caracteristiques
    if (isset($_POST["carac"])) {
        $allc="";
        foreach ($_POST["carac"] as $ck => $cd) {
            $allc.= ($cd!="") ? "(\"$cd\",\"$i\",\"$ck\")," : "";
        }
        $allc=substr($allc, 0, -1); // suppression du dernier caractère
        mysql_query ("INSERT INTO carac (carac_valeur, carac_id, carac_caracteristique_id) VALUES $allc ; ");
    }


/*  ╔╗╔╔═╗╦ ╦╦  ╦╔═╗╦  ╦  ╔═╗  ╔═╗╔═╗╦═╗╔═╗╔═╗
    ║║║║ ║║ ║╚╗╔╝║╣ ║  ║  ║╣   ║  ╠═╣╠╦╝╠═╣║  
    ╝╚╝╚═╝╚═╝ ╚╝ ╚═╝╩═╝╩═╝╚═╝  ╚═╝╩ ╩╩╚═╩ ╩╚═╝  */
    $error_nouvelle_carac="";
    // TODO : Vérifier que la catégorie n’existe pas déjà + que le symbole n’est pas déjà pris.
    // Création d’une nouvelle caracteristique
    $arr = array("nom_carac", "unite_carac", "symbole_carac");
    foreach ($arr as &$value) {
        $$value= isset($_POST[$value]) ? htmlentities($_POST[$value]) : "" ;
    }
    // Si au moins un champ de « Nouvelle caracteristique » est rempli :
    if ( ($nom_carac!="")||($symbole_carac!="") ) {
        // Si tous les champs sont remplis, on crée la nouvelle carac
        if ( ($nom_carac!="")&&($symbole_carac!="") ) {
        
            // Si la nouvelle carac existe déjà (nom ou symbôle)
            $query_count_carac = mysql_query ("SELECT COUNT(*) FROM caracteristiques WHERE nom_carac=\"$nom_carac\" OR symbole_carac=\"$symbole_carac\" ; ");
            while ($l = mysql_fetch_row($query_count_carac)) $count_carac=$l[0] ;
            if ($count_carac!=0) $error_nouvelle_carac.="<p class=\"error_message\">Nom ou symbôle déjà utilisé.</p>";
            else {
                $add_carac_result=mysql_query ("INSERT INTO caracteristiques (nom_carac, unite_carac, symbole_carac) VALUES (\"".$nom_carac."\", \"".$unite_carac."\", \"".$symbole_carac."\"); ");
                
                if ($add_carac_result==1) { 
                    $message_newcarac.="<p class=\"success_message\" id=\"disappear_delay\">La nouvelle caractéristique a été ajoutée.</p>";
                    $nom_carac=""; $unite_carac=""; $symbole_carac="";
                }
                else { $message_newcarac.="<p class=\"error_message\" id=\"disappear_delay\">Une erreur est survenue. La nouvelle caractéristique n’a pas été ajoutée.</p>"; }
            }
        } // Sinon on indique un message d’erreur
        else $error_nouvelle_carac.="<p class=\"error_message\">Nom et Symbôle sont des champs obligatoires.</p>";
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

// allcaracs
$allcaracs=array();
$query_table_allcarac = mysql_query ("SELECT * FROM caracteristiques WHERE carac!=0 ORDER BY nom_carac ASC");
while ($l = mysql_fetch_row($query_table_allcarac)) {
    $allcaracs[]=array($l[0],utf8_encode($l[1]),utf8_encode($l[2]),utf8_encode($l[3]) );
}

// caracs de i
$caracs_i=array();
$query_table_carac_i = mysql_query ("SELECT carac, carac_valeur FROM caracteristiques, carac, base WHERE carac_id=base_index AND carac_caracteristique_id=carac AND base_index='$i' AND carac!=0 ORDER BY base.base_index ASC, carac ASC");
while ($l = mysql_fetch_row($query_table_carac_i)) $caracs_i[$l[0]]=$l[1];

// caracs_of_categorie
$car_of_cat=array();
$query_table_car_of_cat = mysql_query ("SELECT DISTINCT carac_caracteristique_id FROM carac, caracteristiques, base WHERE carac_id=base_index AND carac_caracteristique_id=carac AND categorie='".$data["categorie"]."'");
while ($l = mysql_fetch_row($query_table_car_of_cat)) $car_of_cat[]=$l[0];


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
    
    $quick= ( isset($_GET["quick_page"]) ) ? "&quick_page=".$_GET["quick_page"]."&quick_name=".$_GET["quick_name"]."" : "";
    if ($write) echo "<form method=\"post\" action=\"?i=".$i."".$quick."\">";

/*  ╔═╗╔═╗╦═╗╔═╗╔═╗╔╦╗╔═╗╦═╗╦╔═╗╔╦╗╦╔═╗ ╦ ╦╔═╗╔═╗
    ║  ╠═╣╠╦╝╠═╣║   ║ ║╣ ╠╦╝║╚═╗ ║ ║║═╬╗║ ║║╣ ╚═╗
    ╚═╝╩ ╩╩╚═╩ ╩╚═╝ ╩ ╚═╝╩╚═╩╚═╝ ╩ ╩╚═╝╚╚═╝╚═╝╚═╝   */
    echo "<fieldset><legend>Caractéristiques</legend>";

echo "<label for=\"significatives[]\">Significatives : </label>";

echo "<select data-placeholder=\"Caractéristiques significatives\" style=\"width:250px;\" class=\"chosen-select\"  multiple=\"multiple\" tabindex=\"6\" name=\"significatives[]\" id=\"multiple\">"; // TODO : Ne pas effacer les case en dessous lorsque l’on modifie "Significatives" (cases remplies mais non sauvegarder)

    foreach ($allcaracs as $c) {
        echo "<option ";
        if (in_array($c[0], $car_of_cat)) echo "selected=\"selected\" ";
        echo "value=\"";

        /* ####### Label ####### */
        echo "<label for='carac[".$c[0]."]'><abbr title='".str_replace("'", "’", $c[1])."' >".str_replace("'", "’", $c[3])."</abbr> "; // TODO : ne supporte pas les apostrophe dans $c[1] ! voir exemple avec « longueur d’onde »
        if ( ($c[2]!="bool")&&($c[2]!="") ) echo "(".str_replace("'", "’", $c[2]).")"; // Si ce n’est pas un booléen on affiche l’unité
        echo " : </label>\n";

        if ($c[2]=="bool") {
            echo "<select name='carac[".$c[0]."]' id='carac[".$c[0]."]'>";
            echo "<option value=''>Non renseigné</option>";
            echo "<option value='1' "; if ($caracs_i[$c[0]]=="1") echo 'selected'; echo ">Oui</option>"; 
            echo "<option value='0' "; if ($caracs_i[$c[0]]=="0") echo 'selected'; echo ">Non</option>";
            echo "</select>";
        }
        else echo "<input value='".str_replace("'", "’", $caracs_i[$c[0]])."' name='carac[".$c[0]."]' type='text' id='carac[".$c[0]."]'>";

        echo "\">";
        echo $c[1];
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
    

    
    
    echo "</fieldset>";


/*  ╔╗╔╔═╗╦ ╦╦  ╦╔═╗╦  ╦  ╔═╗  ╔═╗╔═╗╦═╗╔═╗╔═╗
    ║║║║ ║║ ║╚╗╔╝║╣ ║  ║  ║╣   ║  ╠═╣╠╦╝╠═╣║  
    ╝╚╝╚═╝╚═╝ ╚╝ ╚═╝╩═╝╩═╝╚═╝  ╚═╝╩ ╩╩╚═╩ ╩╚═╝  */
    echo "<fieldset><legend>Nouvelle caractéristique</legend>";

    // TODO des catégories de caractéristiques ?
    
    echo $message_newcarac;
    
    echo "Si la caractéristique n’est pas présente dans la liste ci-dessus…";
    
    echo "<label for=\"nom_carac\">Nom :</label>\n";
    echo "<input value=\"$nom_carac\" name=\"nom_carac\" type=\"text\">\n";
    
    echo "<label for=\"unite_carac\">";
    echo "<abbr title=\"µm, %,… si oui/non, entrez « bool »\">Unité</abbr>";
    echo " :</label>\n";
    echo "<input value=\"$unite_carac\" name=\"unite_carac\" type=\"text\">\n";
    
    echo "<label for=\"symbole_carac\">";
    echo "<abbr title=\"Plus court possible (ex: λ, ω₀, Tvisible,…)\">Symbôle</abbr>";
    echo ":</label>\n";
    echo "<input value=\"$symbole_carac\" name=\"symbole_carac\" type=\"text\">\n";
    echo "".$error_nouvelle_carac."";
    echo "</fieldset>";


/*  ╔═╗╦ ╦╔╗ ╔╦╗╦╔╦╗
    ╚═╗║ ║╠╩╗║║║║ ║ 
    ╚═╝╚═╝╚═╝╩ ╩╩ ╩     */
if ($write) echo "<p style=\"text-align:center;\"><input name=\"carac_valid\" value=\"Enregistrer\" type=\"submit\"  class=\"little_button\" /></p>"; // TODO Ajouter un bouton réinitialiser


    if ($write) echo "</form>";

echo "</div>";

?>
