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
███╗   ███╗ ██████╗ ██████╗ ██╗███████╗    ███████╗ ██████╗ ██╗     
████╗ ████║██╔═══██╗██╔══██╗██║██╔════╝    ██╔════╝██╔═══██╗██║     
██╔████╔██║██║   ██║██║  ██║██║█████╗      ███████╗██║   ██║██║     
██║╚██╔╝██║██║   ██║██║  ██║██║██╔══╝      ╚════██║██║▄▄ ██║██║     
██║ ╚═╝ ██║╚██████╔╝██████╔╝██║██║██╗      ███████║╚██████╔╝███████╗
╚═╝     ╚═╝ ╚═════╝ ╚═════╝ ╚═╝╚═╝╚═╝      ╚══════╝ ╚══▀▀═╝ ╚══════╝
*/

/*  ╔═╗ ╦╔═╗╦ ╦╔╦╗  ╔═╗╔╗╔╦╗╦═╗╔═╗╔╦╗╦╔═╗╔╗╔
    ╠═╣ ║║ ║║ ║ ║   ║╣ ║║║║ ╠╦╝║╣  ║ ║║╣ ║║║
    ╩ ╩╚╝╚═╝╚═╝ ╩   ╚═╝╝╚╝╩ ╩╚═╚═╝ ╩ ╩╚═╝╝╚╝    */
if ( isset($_POST["add_entretien"]) ) {
    $arr = array("e_frequence", "e_frequence_multipli", "e_designation", "e_detail");
    foreach ($arr as &$value) {
        $$value= isset($_POST[$value]) ? htmlentities($_POST[$value]) : "" ;
    }
    $e_frequence=$e_frequence*$e_frequence_multipli;
    mysql_query ("INSERT INTO entretien (e_id, e_frequence, e_lastdate, e_designation, e_detail) VALUES ($i,\"$e_frequence\", \"".date("Y-m-d")."\", \"".$e_designation."\", \"".$e_detail."\"); ");
}

/*  ╔╦╗╔═╗╔╦╗╦╔═╗  ╔═╗╔╗╔╦╗╦═╗╔═╗╔╦╗╦╔═╗╔╗╔
    ║║║║ ║ ║║║╠╣   ║╣ ║║║║ ╠╦╝║╣  ║ ║║╣ ║║║
    ╩ ╩╚═╝═╩╝╩╚    ╚═╝╝╚╝╩ ╩╚═╚═╝ ╩ ╩╚═╝╝╚╝    */
if ($modif_entretien=="Entretien effectué") {
    
    
    
    
}


/* TODO
    if ($utilisateur=="plus_utilisateur") {
        $plus_utilisateur_nom=mb_strtoupper($plus_utilisateur_nom);
        $plus_utilisateur_phone=phone_display("$plus_utilisateur_phone","");
        mysql_query ("INSERT INTO utilisateur (utilisateur_nom, utilisateur_prenom, utilisateur_mail, utilisateur_phone) VALUES ('".$plus_utilisateur_nom."', '".$plus_utilisateur_prenom."','".$plus_utilisateur_mail."','".$plus_utilisateur_phone."') ; ");
        // TODO : prévoir le cas où le contrat existe déjà
        $query_table_utilisateurnew = mysql_query ("SELECT utilisateur_index FROM utilisateur ORDER BY utilisateur_index DESC LIMIT 1 ;");
        while ($l = mysql_fetch_row($query_table_utilisateurnew)) $utilisateur=$l[0];
        // on ajoute cette entrée dans le tableau des utilisateurs (utilisé pour le select)
        $utilisateurs[$utilisateur]=array( $utilisateur, utf8_encode($plus_utilisateur_nom), utf8_encode($plus_utilisateur_prenom), utf8_encode($plus_utilisateur_mail), phone_display("$plus_utilisateur_phone",".") );
    }
*/



/*
 █████╗ ██████╗ ██████╗  █████╗ ██╗   ██╗
██╔══██╗██╔══██╗██╔══██╗██╔══██╗╚██╗ ██╔╝
███████║██████╔╝██████╔╝███████║ ╚████╔╝ 
██╔══██║██╔══██╗██╔══██╗██╔══██║  ╚██╔╝  
██║  ██║██║  ██║██║  ██║██║  ██║   ██║   
╚═╝  ╚═╝╚═╝  ╚═╝╚═╝  ╚═╝╚═╝  ╚═╝   ╚═╝   
*/
$query_table_entretien = mysql_query ("SELECT e_index, e_frequence, e_lastdate, e_designation, e_detail, e_effectuerpar FROM entretien WHERE e_id=$i ORDER BY e_designation ASC ;");
$entretiens = array();
while ($l = mysql_fetch_row($query_table_entretien)) {
    $entretiens[]=array($l[0], $l[1], $l[2], utf8_encode($l[3]), utf8_encode($l[4]), $l[5]);
}


/*
███████╗ ██████╗ ██████╗ ███╗   ███╗██╗   ██╗██╗      █████╗ ██╗██████╗ ███████╗
██╔════╝██╔═══██╗██╔══██╗████╗ ████║██║   ██║██║     ██╔══██╗██║██╔══██╗██╔════╝
█████╗  ██║   ██║██████╔╝██╔████╔██║██║   ██║██║     ███████║██║██████╔╝█████╗  
██╔══╝  ██║   ██║██╔══██╗██║╚██╔╝██║██║   ██║██║     ██╔══██║██║██╔══██╗██╔══╝  
██║     ╚██████╔╝██║  ██║██║ ╚═╝ ██║╚██████╔╝███████╗██║  ██║██║██║  ██║███████╗
╚═╝      ╚═════╝ ╚═╝  ╚═╝╚═╝     ╚═╝ ╚═════╝ ╚══════╝╚═╝  ╚═╝╚═╝╚═╝  ╚═╝╚══════╝
*/
echo "<div id=\"bloc\" style=\"background:#f998a9; vertical-align:top;\">";

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
        echo "<option value=\"365\">ans</option>";
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

if ( empty($entretiens) ) echo "Aucun entretien spécifié.";
else {

        $today=date("Y-m-d");

        echo "<table style=\"border:none;\">";

        echo "<tr>";
            echo "<th>&nbsp;</th>";
            echo "<th style=\"text-align:left;\">Désignation</th>";
            echo "<th style=\"text-align:left;\">Fréquence</th>";
            echo "<th style=\"text-align:left;\">Prochaine intervention</th>";
            echo "<th>&nbsp;</th>";
        echo "</tr>";
            
        foreach ($entretiens as $e) {

            $f=$e[1];
            $date_derniere_intervention=$e[2];
            $date_prochaine_intervention = date("Y-m-d", strtotime($date_derniere_intervention." +$f days") );

           // $retard = date_diff($today, $date_prochaine_intervention);

            $retard = round( ( strtotime($today) - strtotime($date_prochaine_intervention) ) / 86400 );

            echo "<tr>";
            echo "<td><input type=\"checkbox\" id=\"ebox[".$e[0]."]\" value=\"1\"></td>";

            echo "<td>";
            if ($e[4]!="") echo "<abbr title=\"".$e[4]."\">";
            echo $e[3];
            if ($e[4]!="") echo "</abbr>";
            echo "</td>";

            echo "<td>";
            $f_an=$f/365; $f_mois=$f/30;
            if ($f>=365) echo "$f_an an";
            elseif ($f>=30) echo "$f_mois moi";
            else echo "$f jour";
            if ( ($f!=1)&&($f_an!=1) ) echo "s";
            echo "</td>";
            
            echo "<td>";
            if ($retard>0) echo "<span style=\"color:#cc0000;\">";
            else {
                if (-$retard<$f*0.05) echo "<span style=\"color:#f57900;\">";
                else echo "<span style=\"color:#3465a4;\">";
            }
            echo "<abbr title=\"dernier entretien effectué ";
            if ($e[5]!=0) echo "par ".$utilisateurs[$e[5]][2]." ".$utilisateurs[$e[5]][1]." ";
            echo "le ".$date_derniere_intervention."\">";
            echo "<strong>".dateformat($date_prochaine_intervention,"fr")."</strong>";
            echo "</abbr>";
            
            if ($retard>0) echo " (retard : $retard jours !)";
            else { echo " (reste : ".abs($retard)." jours)"; }
            echo "</span>";
            
            echo "</td>";
            
            echo "<td style=\"text-align:right;\"><span id=\"linkbox\" onclick=\"TINY.box.show({url:'del_confirm.php?i=$i&e=\$e[0]',width:280,height:110})\" title=\"cet entretien n’est plus nécessaire\">×<span></td>";
            echo "</tr>";
        }

        echo "</table>";

        /* ########### Le ########### */
        echo "<label for=\"e_effectuele\" style=\"vertical-align: top;\"> Le :</label>\n";
        echo "<input value=\"\" name=\"e_effectuele\" type=\"text\" id=\"e_effectuele\"><br/>";

        /* ########### Par ########### */
        echo "<label for=\"e_effectuepar\">Par : </label>\n";
        echo "<select name=\"e_effectuepar\" onchange=\"display(this,'plus_intervant','plus_intervant');\" id=\"e_effectuepar\">";
        echo "<option value=\"0\" "; if ($entretiens[5]=="0") echo "selected"; echo ">— Aucun intervenant spécifié —</option>"; 
        option_selecteur($entretiens[5], $utilisateurs, "2");
        echo "<option value=\"plus_intervant\" "; if ($entretiens[5]=="plus_intervant") echo "selected"; echo ">Nouvel intervenant :</option>";
        echo "</select><br/>";

            /* ########### + utilisateur ########### */
            echo "\n\n\n";
            echo "<fieldset id=\"plus_intervant\" class=\"subfield\" style=\"display: none;\"><legend class=\"subfield\">Nouvel intervant</legend>";
                echo "<label for=\"plus_intervant_prenom\">Prénom :</label>\n";
                echo "<input value=\"\" name=\"plus_intervant_prenom\" type=\"text\">\n";

                echo "<label for=\"plus_intervant_nom\">Nom :</label>\n";
                echo "<input value=\"\" name=\"plus_intervant_nom\" type=\"text\">\n";
                
                echo "<label for=\"plus_intervant_mail\">Mail :</label>\n";
                echo "<input value=\"\" name=\"plus_intervant_mail\" type=\"text\">\n";
                
                echo "<label for=\"plus_intervant_phone\">Téléphone :</label>\n";
                echo "<input value=\"\" name=\"plus_intervant_phone\" type=\"text\">\n";

            echo "</fieldset>";
            echo "\n\n\n";



        /* ########### submit ########### */
        echo "<input name=\"modif_entretien\" value=\"Entretien effectué\" type=\"submit\">";

    }
    
    echo "</fieldset>";




    echo "</form>";

echo "</div>";

?>
