<?php
/*
███████╗███╗   ██╗████████╗██████╗ ███████╗████████╗██╗███████╗███╗   ██╗
██╔════╝████╗  ██║╚══██╔══╝██╔══██╗██╔════╝╚══██╔══╝██║██╔════╝████╗  ██║
█████╗  ██╔██╗ ██║   ██║   ██████╔╝█████╗     ██║   ██║█████╗  ██╔██╗ ██║
██╔══╝  ██║╚██╗██║   ██║   ██╔══██╗██╔══╝     ██║   ██║██╔══╝  ██║╚██╗██║
███████╗██║ ╚████║   ██║   ██║  ██║███████╗   ██║   ██║███████╗██║ ╚████║
╚══════╝╚═╝  ╚═══╝   ╚═╝   ╚═╝  ╚═╝╚══════╝   ╚═╝   ╚═╝╚══════╝╚═╝  ╚═══╝
*/


















/*
███████╗ ██████╗ ██████╗ ███╗   ███╗██╗   ██╗██╗      █████╗ ██╗██████╗ ███████╗
██╔════╝██╔═══██╗██╔══██╗████╗ ████║██║   ██║██║     ██╔══██╗██║██╔══██╗██╔════╝
█████╗  ██║   ██║██████╔╝██╔████╔██║██║   ██║██║     ███████║██║██████╔╝█████╗  
██╔══╝  ██║   ██║██╔══██╗██║╚██╔╝██║██║   ██║██║     ██╔══██║██║██╔══██╗██╔══╝  
██║     ╚██████╔╝██║  ██║██║ ╚═╝ ██║╚██████╔╝███████╗██║  ██║██║██║  ██║███████╗
╚═╝      ╚═════╝ ╚═╝  ╚═╝╚═╝     ╚═╝ ╚═════╝ ╚══════╝╚═╝  ╚═╝╚═╝╚═╝  ╚═╝╚══════╝
*/
echo "<div id=\"bloc\" style=\"background:#d299a3; vertical-align:top;\">";

    echo "<h1>Entretien</h1>";
    
    echo "<form method=\"post\" action=\"?i=$i\">";
    
    
/*  ╔╗╔╔═╗╦ ╦╦  ╦╔═╗╦    ╔═╗╔╗╔╦╗╦═╗╔═╗╔╦╗╦╔═╗╔╗╔
    ║║║║ ║║ ║╚╗╔╝║╣ ║    ║╣ ║║║║ ╠╦╝║╣  ║ ║║╣ ║║║
    ╝╚╝╚═╝╚═╝ ╚╝ ╚═╝╩═╝  ╚═╝╝╚╝╩ ╩╚═╚═╝ ╩ ╩╚═╝╝╚╝   */

    echo "<fieldset><legend>Nouvel entretien</legend>";
    
            /* ########### frequence ########### */
        echo "<label for=\"e_frequence\">Fréquence : </label>\n";
        echo "<input value=\"\" name=\"e_frequence\" type=\"date\" id=\"e_frequence\" size=\"4\">";

        echo "<select name=\"e_frequence_multipli\" id=\"e_frequence_multipli\">";
        echo "<option value=\"1\">jours</option>";
        echo "<option value=\"30\" selected>mois</option>"; 
        echo "<option value=\"365\">Non</option>";
        echo "</select> <br/>";

            /* ########### Désignation ########### */
        echo "<label for=\"e_designation\">Désignation : </label>\n";
        echo "<input value=\"\" name=\"e_designation\" type=\"text\" id=\"e_designation\"><br/>";

        /* ########### Détails ########### */
        echo "<label for=\"e_detail\" style=\"vertical-align: top;\"> Détails :</label>\n";
        echo "<textarea name=\"e_detail\" rows=\"4\" cols=\"33\"></textarea><br/>";
        
        /* ########### submit ########### */
        echo "<input name='add_entretien' value='Ajouter' type='submit'>";


    echo "</fieldset>";

/*  ╔═╗╔╗╔╦╗╦═╗╔═╗╔╦╗╦╔═╗╔╗╔╔═╗
    ║╣ ║║║║ ╠╦╝║╣  ║ ║║╣ ║║║╚═╗
    ╚═╝╝╚╝╩ ╩╚═╚═╝ ╩ ╩╚═╝╝╚╝╚═╝ */

    echo "<fieldset><legend>Entretiens</legend>";

    $entretien=array(0,0);

        echo "<table style=\"border:none;\">";
        foreach ($entretien as $e) {
            echo "<tr>";
            echo "<td><input type=\"checkbox\" id=\"cbox1\" value=\"1\"></td>";
            echo "<td>designation un peu longue que ce passe-t-il ? (détails)</td>";
            echo "<td>date (retard ou reste)</td>";
            echo "<td style=\"text-align:right;\"><span id=\"linkbox\" onclick=\"TINY.box.show({url:'del_confirm.php?i=$i&e=\$e[0]',width:280,height:110})\" title=\"cet entretien n’est plus nécessaire\">×<span></td>";
            echo "</tr>";
        }
        echo "</table>";

        /* ########### Le ########### */
        echo "<label for=\"e_effectuele\" style=\"vertical-align: top;\"> Le :</label>\n";
        echo "<input value=\"\" name=\"e_effectuele\" type=\"text\" id=\"e_effectuele\"><br/>";

        /* ########### Par ########### */
        echo "<label for=\"e_effectuepar\" style=\"vertical-align: top;\"> par :</label>\n";
        echo "<input value=\"\" name=\"e_effectuepar\" type=\"text\" id=\"e_effectuepar\"><br/>";

        /* ########### submit ########### */
        echo "<input name=\"modif_entretien\" value=\"Entretien effectué\" type=\"submit\">";

    echo "</fieldset>";




    echo "</form>";

echo "</div>";

?>
