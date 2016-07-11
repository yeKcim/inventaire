<?php
/*
 ██████╗ █████╗ ██████╗  █████╗  ██████╗
██╔════╝██╔══██╗██╔══██╗██╔══██╗██╔════╝
██║     ███████║██████╔╝███████║██║     
██║     ██╔══██║██╔══██╗██╔══██║██║     
╚██████╗██║  ██║██║  ██║██║  ██║╚██████╗
 ╚═════╝╚═╝  ╚═╝╚═╝  ╚═╝╚═╝  ╚═╝ ╚═════╝
*/


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
            $allc.= ($cd!="") ? "($cd,$i,$ck)," : "";
        }
        $allc=substr($allc, 0, -1); // suppression du dernier caractère
        mysql_query ("INSERT INTO carac (carac_valeur, carac_id, carac_caracteristique_id) VALUES $allc ; ");
    }


/*  ╔╗╔╔═╗╦ ╦╦  ╦╔═╗╦  ╦  ╔═╗  ╔═╗╔═╗╦═╗╔═╗╔═╗
    ║║║║ ║║ ║╚╗╔╝║╣ ║  ║  ║╣   ║  ╠═╣╠╦╝╠═╣║  
    ╝╚╝╚═╝╚═╝ ╚╝ ╚═╝╩═╝╩═╝╚═╝  ╚═╝╩ ╩╩╚═╩ ╩╚═╝  */
    // TODO : Vérifier que la catégorie n’existe pas déjà + que le symbole n’est pas déjà pris.
    // Création d’une nouvelle caracteristique
    $arr = array("nom_carac", "unite_carac", "symbole_carac");
    foreach ($arr as &$value) {
        $$value= isset($_POST[$value]) ? htmlentities($_POST[$value]) : "" ;
    }
    mysql_query ("INSERT INTO caracteristiques (nom_carac, unite_carac, symbole_carac) VALUES (\"".$nom_carac."\", \"".$unite_carac."\", \"".$symbole_carac."\"); ");


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
    
    echo "<form method=\"post\" action=\"?i=$i\">";

/*  ╔═╗╔═╗╦═╗╔═╗╔═╗╔╦╗╔═╗╦═╗╦╔═╗╔╦╗╦╔═╗ ╦ ╦╔═╗╔═╗
    ║  ╠═╣╠╦╝╠═╣║   ║ ║╣ ╠╦╝║╚═╗ ║ ║║═╬╗║ ║║╣ ╚═╗
    ╚═╝╩ ╩╩╚═╩ ╩╚═╝ ╩ ╚═╝╩╚═╩╚═╝ ╩ ╩╚═╝╚╚═╝╚═╝╚═╝   */
    echo "<fieldset><legend>Caractéristiques</legend>";

echo "<label for='significatives[]'>Significatives : </label>";

echo "<select data-placeholder=\"Caractéristiques significatives\" style=\"width:250px;\" class=\"chosen-select\"  multiple=\"multiple\" tabindex=\"6\" name=\"significatives[]\" id=\"multiple\">";

    foreach ($allcaracs as $c) {
        echo "<option ";
        if (in_array($c[0], $car_of_cat)) echo "selected=\"selected\" ";
        echo "value=\"";

        /* ####### Label ####### */
        echo "<label for='carac[".$c[0]."]'><abbr title='".$c[1]."' >".$c[3]."</abbr> "; // TODO : ne supporte pas les apostrophe dans $c[1] ! voir exemple avec « longueur d’onde »
        if ($c[2]!="bool") echo "(".$c[2].")"; // Si ce n’est pas un booléen on affiche l’unité
        echo " : </label>\n";

        if ($c[2]=="bool") {
            echo "<select name='carac[".$c[0]."]' id='carac[".$c[0]."]'>";
            echo "<option value=''>Non renseigné</option>";
            echo "<option value='1' "; if ($caracs_i[$c[0]]=="1") echo 'selected'; echo ">Oui</option>"; 
            echo "<option value='0' "; if ($caracs_i[$c[0]]=="0") echo 'selected'; echo ">Non</option>";
            echo "</select>";
        }
        else echo "<input value='".$caracs_i[$c[0]]."' name='carac[".$c[0]."]' type='text' id='carac[".$c[0]."]'>";

        echo "\">";
        echo $c[1];
        echo "</option>";
    }
    
    
    echo "</select>";
    echo "<script type=\"text/javascript\">
        var config = {
          '.chosen-select'           : {no_results_text:'Oops, nothing found!'},
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
    
    echo "Si la caractéristique n’est pas présente dans la liste ci-dessus…";
    
    echo "<label for=\"nom_carac\">Nom :</label>\n";
    echo "<input value=\"\" name=\"nom_carac\" type=\"text\">\n";
    
    echo "<label for=\"unite_carac\">";
    echo "<abbr title=\"µm, %,… si oui/non, entrez « bool »\">Unité</abbr>";
    echo " :</label>\n";
    echo "<input value=\"\" name=\"unite_carac\" type=\"text\">\n";
    
    echo "<label for=\"symbole_carac\">";
    echo "<abbr title=\"Plus court possible (ex: λ, ω₀, Tvisible,…)\">Symbôle</abbr>";
    echo ":</label>\n";
    echo "<input value=\"\" name=\"symbole_carac\" type=\"text\">\n";
    
    echo "</fieldset>";


/*  ╔═╗╦ ╦╔╗ ╔╦╗╦╔╦╗
    ╚═╗║ ║╠╩╗║║║║ ║ 
    ╚═╝╚═╝╚═╝╩ ╩╩ ╩     */
    echo "<p style=\"text-align:center;\"><input name='carac_valid' value='Enregistrer' type='submit'></p>"; // TODO Ajouter un bouton réinitialiser


    echo "</form>";

echo "</div>";

?>
