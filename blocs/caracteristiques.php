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
 █████╗ ██████╗ ██████╗  █████╗ ██╗   ██╗
██╔══██╗██╔══██╗██╔══██╗██╔══██╗╚██╗ ██╔╝
███████║██████╔╝██████╔╝███████║ ╚████╔╝ 
██╔══██║██╔══██╗██╔══██╗██╔══██║  ╚██╔╝  
██║  ██║██║  ██║██║  ██║██║  ██║   ██║   
╚═╝  ╚═╝╚═╝  ╚═╝╚═╝  ╚═╝╚═╝  ╚═╝   ╚═╝   
*/

// caracs
$caracs=array();
$query_table_carac = mysql_query ("SELECT base_index, categorie, carac_valeur, carac, nom_carac, unite_carac, symbole_carac FROM caracteristiques, carac, base WHERE carac_id=base_index AND carac_caracteristique_id=carac AND base_index=$i AND carac!=0 ORDER BY base.base_index ASC, carac ASC");
while ($l = mysql_fetch_row($query_table_carac)) {
    $caracs[$l[3]]=array($l[0],$l[1],utf8_encode($l[2]),$l[3],utf8_encode($l[4]),utf8_encode($l[5]),utf8_encode($l[6]) );
}




/*
███╗   ███╗ ██████╗ ██████╗ ██╗███████╗    ███████╗ ██████╗ ██╗     
████╗ ████║██╔═══██╗██╔══██╗██║██╔════╝    ██╔════╝██╔═══██╗██║     
██╔████╔██║██║   ██║██║  ██║██║█████╗      ███████╗██║   ██║██║     
██║╚██╔╝██║██║   ██║██║  ██║██║██╔══╝      ╚════██║██║▄▄ ██║██║     
██║ ╚═╝ ██║╚██████╔╝██████╔╝██║██║██╗      ███████║╚██████╔╝███████╗
╚═╝     ╚═╝ ╚═════╝ ╚═════╝ ╚═╝╚═╝╚═╝      ╚══════╝ ╚══▀▀═╝ ╚══════╝
*/
if ( isset($_POST["carac_valid"]) ) {



}


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
    ║  ╠═╣╠╦╝╠═╣║   ║ ║╣ ╠╦╝║╚═╗ ║ ║║═╬╗║ ║║╣ ╚═╗   OLD
    ╚═╝╩ ╩╩╚═╩ ╩╚═╝ ╩ ╚═╝╩╚═╩╚═╝ ╩ ╩╚═╝╚╚═╝╚═╝╚═╝   */
    echo "<fieldset><legend>Caractéristiques OLD</legend>";

        // TODO il serait intéressant d’afficher toutes les caractéristiques que des éléments ont rempli dans la même catégorie même si pour l’élément en question c’est vide.

        foreach ($caracs as $c) {
        
            echo "<label for=\"carac".$c[3]."\">";
            echo "<abbr title=\"$c[4]\" >".$c[6]."</abbr>";        
        
            if ($c[5]=="bool") {
                echo " : </label>\n";
                echo "<select name=\"carac".$c[3]."\" onchange=\"submit();\" id=\"carac".$c[3]."\">";
                    echo "<option value=\"1\" "; if ($c[2]=="1") echo "selected"; echo ">Oui</option>"; 
                    echo "<option value=\"0\" "; if ($c[2]=="0") echo "selected"; echo ">Non</option>";
                echo "</select>";
            }
            else {
                echo " (".$c[5].") : </label>\n"; 
                echo "<input value=\"".$c[2]."\" name=\"carac".$c[3]."\" type=\"text\" id=\"carac".$c[3]."\">";
            }
            echo "<br/>";
        }
        
    echo "<a href=\"\">➕</a>";
        
    echo "</fieldset>";



/*  ╔═╗╔═╗╦═╗╔═╗╔═╗╔╦╗╔═╗╦═╗╦╔═╗╔╦╗╦╔═╗ ╦ ╦╔═╗╔═╗
    ║  ╠═╣╠╦╝╠═╣║   ║ ║╣ ╠╦╝║╚═╗ ║ ║║═╬╗║ ║║╣ ╚═╗
    ╚═╝╩ ╩╩╚═╩ ╩╚═╝ ╩ ╚═╝╩╚═╩╚═╝ ╩ ╩╚═╝╚╚═╝╚═╝╚═╝   */

    echo "<fieldset><legend>Caractéristiques</legend>";

echo "<label for='significatives[]'>Significatives : </label>";

echo "<select data-placeholder=\"Your Favorite Football Team\" style=\"width:250px;\" class=\"chosen-select\"  multiple=\"multiple\" tabindex=\"6\" name=\"significatives[]\" id=\"multiple\">";

    echo "<option selected=\"selected\" value=\"<label for='carac1'>Carac 1 : </label><input value='carac1' name='carac1' type='text' id='carac1'>\">Carac 1</option>";
    echo "<option value=\"<label for='carac2'>Carac 2 : </label><input value='carac2' name='carac2' type='text' id='carac2'>\">Carac 2</option>";
    echo "<option selected=\"selected\" value=\"<label for='carac3'>Carac 3 : </label><input value='carac3' name='carac3' type='text' id='carac3'>\">Carac 3</option>";
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


    echo "</fieldset>";


/*  ╔═╗╦ ╦╔╗ ╔╦╗╦╔╦╗
    ╚═╗║ ║╠╩╗║║║║ ║ 
    ╚═╝╚═╝╚═╝╩ ╩╩ ╩     */
    echo "<p style=\"text-align:center;\"><input name='carac_valid' value='Enregistrer' type='submit'></p>"; // TODO Ajouter un bouton réinitialiser


    echo "</form>";

echo "</div>";

?>
