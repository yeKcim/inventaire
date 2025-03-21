<?php
/*
███████╗███╗   ██╗████████╗██████╗ ███████╗████████╗██╗███████╗███╗   ██╗
██╔════╝████╗  ██║╚══██╔══╝██╔══██╗██╔════╝╚══██╔══╝██║██╔════╝████╗  ██║
█████╗  ██╔██╗ ██║   ██║   ██████╔╝█████╗     ██║   ██║█████╗  ██╔██╗ ██║
██╔══╝  ██║╚██╗██║   ██║   ██╔══██╗██╔══╝     ██║   ██║██╔══╝  ██║╚██╗██║
███████╗██║ ╚████║   ██║   ██║  ██║███████╗   ██║   ██║███████╗██║ ╚████║
╚══════╝╚═╝  ╚═══╝   ╚═╝   ╚═╝  ╚═╝╚══════╝   ╚═╝   ╚═╝╚══════╝╚═╝  ╚═══╝
*/
$error_noebox="";
$message="";

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
        $$value= isset($_POST[$value]) ? trim($_POST[$value]) : "" ;
    }

    if ( ($e_designation=="")||($e_frequence=="") ) $error_emptyinput="Fréquence et Désignation sont des champs obligatoires";
    else {
        $e_frequence=$e_frequence*$e_frequence_multipli;

        $add_result = $dbh->query("INSERT INTO entretien (e_id, e_frequence, e_lastdate, e_designation, e_detail) VALUES ($i,\"$e_frequence\", \"".date("Y-m-d")."\", \"".$e_designation."\", \"".$e_detail."\")");        
        
        $error_emptyinput="";
        $message.= ($add_result!=1) ? $message_success_add : $message_error_add;
    }
}


/*  ╔╦╗╔═╗╔╦╗╦╔═╗  ╔═╗╔╗╔╦╗╦═╗╔═╗╔╦╗╦╔═╗╔╗╔
    ║║║║ ║ ║║║╠╣   ║╣ ║║║║ ╠╦╝║╣  ║ ║║╣ ║║║
    ╩ ╩╚═╝═╩╝╩╚    ╚═╝╝╚╝╩ ╩╚═╚═╝ ╩ ╩╚═╝╝╚╝    */
$modif_entretien= isset($_POST["modif_entretien"]) ? $_POST["modif_entretien"] : "" ;

if ($modif_entretien!="") {
    $arr = array("e_effectuele", "e_effectuerpar", "plus_intervant_prenom", "plus_intervant_nom", "plus_intervant_mail", "plus_intervant_phone");
    foreach ($arr as &$value) {
        $$value= isset($_POST[$value]) ? trim($_POST[$value]) : "" ;
    }

    if ($e_effectuerpar=="plus_intervant") {
        $plus_intervant_nom=mb_strtoupper($plus_intervant_nom);
        $plus_intervant_phone=phone_display("$plus_intervant_phone","");

        $modif_result = $dbh->query(str_replace("\"\"", "NULL","INSERT INTO utilisateur (utilisateur_nom, utilisateur_prenom, utilisateur_mail, utilisateur_phone) VALUES ('".$plus_intervant_nom."', '".$plus_intervant_prenom."','".$plus_intervant_mail."','".$plus_intervant_phone."') ;"));
        /* TODO : prévoir le cas où la personne existe déjà */

        if (!isset($modif_result)) $message.=$message_error_add;
        else {
            $message.=$message_success_add;
	    $e_effectuerpar=return_last_id("utilisateur_index","utilisateur");
            // on ajoute cette entrée dans le tableau des utilisateurs
	    array_push($utilisateurs, array("utilisateur_index" => $e_effectuerpar, "utilisateur_nom"  => $plus_intervant_nom, "utilisateur_prenom" => $plus_intervant_prenom, "utilisateur_mail" => $plus_intervant_mail, "utilisateur_phone" =>phone_display("$plus_intervant_phone",".") ) );
        }
    }

    if (isset($_POST["ebox"])) {
        $alle="";
        foreach ($_POST["ebox"] as $ek => $ed) $alle.=" e_index = $ek OR";
        $alle=substr($alle, 0, -2);
        $effectuepar_sql= ($e_effectuerpar!="0") ? ", e_effectuerpar = '$e_effectuerpar'" : "";

        $e_effectuele=($e_effectuele==NULL) ? "0000-00-00" : $e_effectuele;
        
	$modif_result = $dbh->query(str_replace("\"\"", "NULL","UPDATE entretien SET e_lastdate = '".$e_effectuele."' $effectuepar_sql WHERE $alle ;"));
	$message.= (!isset($modif_result)) ? $message_error_modif : $message_success_modif;
    }
    else $error_noebox="Vous devez cocher au moins une case d’entretien";

}

/*  ╔═╗╦ ╦╔═╗╔═╗╦═╗╦╔╦╗  ╔═╗╔╗╔╦╗╦═╗╔═╗╔╦╗╦╔═╗╔╗╔
    ╚═╗║ ║╠═╝╠═╝╠╦╝║║║║  ║╣ ║║║║ ╠╦╝║╣  ║ ║║╣ ║║║
    ╚═╝╚═╝╩  ╩  ╩╚═╩╩ ╩  ╚═╝╝╚╝╩ ╩╚═╚═╝ ╩ ╩╚═╝╝╚╝   */
$arr = array("e_del", "del_e_confirm");
foreach ($arr as &$value) {
    $$value= isset($_POST[$value]) ? $_POST[$value] : "" ;
}

if ($del_e_confirm=="Confirmer la suppression") {
    $delresult = $dbh->exec("DELETE FROM entretien WHERE e_index=$e_del AND e_id=$i;");
    $message.=($delresult!="") ? $message_success_del : $message_error_del;
}


/*
 █████╗ ██████╗ ██████╗  █████╗ ██╗   ██╗
██╔══██╗██╔══██╗██╔══██╗██╔══██╗╚██╗ ██╔╝
███████║██████╔╝██████╔╝███████║ ╚████╔╝
██╔══██║██╔══██╗██╔══██╗██╔══██║  ╚██╔╝
██║  ██║██║  ██║██║  ██║██║  ██║   ██║
╚═╝  ╚═╝╚═╝  ╚═╝╚═╝  ╚═╝╚═╝  ╚═╝   ╚═╝
*/
// entretiens
$sth = $dbh->query("SELECT e_index, e_frequence, e_lastdate, e_designation, e_detail, e_effectuerpar FROM entretien WHERE e_id=$i ORDER BY e_designation ASC ;");
$entretiens = ($sth) ? $sth->fetchAll(PDO::FETCH_ASSOC) : FALSE ;
if ($sth) $sth->closeCursor();


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

    echo $message;

    $quick= ( isset($_GET["quick_page"]) ) ? "&quick_page=".$_GET["quick_page"]."&quick_name=".$_GET["quick_name"]."" : "";
    if ($write) echo "<form method=\"post\" action=\"?BASE=$database&i=".$i."".$quick."\">";


/*  ╔╗╔╔═╗╦ ╦╦  ╦╔═╗╦    ╔═╗╔╗╔╦╗╦═╗╔═╗╔╦╗╦╔═╗╔╗╔
    ║║║║ ║║ ║╚╗╔╝║╣ ║    ║╣ ║║║║ ╠╦╝║╣  ║ ║║╣ ║║║
    ╝╚╝╚═╝╚═╝ ╚╝ ╚═╝╩═╝  ╚═╝╝╚╝╩ ╩╚═╚═╝ ╩ ╩╚═╝╝╚╝   */

    echo "<fieldset><legend>Nouvel entretien</legend>";

            /* ########### frequence ########### */
        echo "<label for=\"e_frequence\">Fréquence* : </label>\n";
        echo "<input value=\"\" name=\"e_frequence\" type=\"text\" id=\"e_frequence\" size=\"4\">";

        echo "<select name=\"e_frequence_multipli\" id=\"e_frequence_multipli\">";
        echo "<option value=\"1\">jours</option>";
        echo "<option value=\"30\" selected>mois</option>";
        echo "<option value=\"365\">ans</option>";
        echo "</select> <br/>";

            /* ########### Désignation ########### */
        echo "<label for=\"e_designation\">Désignation* : </label>\n";
        echo "<input value=\"\" name=\"e_designation\" type=\"text\" id=\"e_designation\"><br/>";

        /* ########### Détails ########### */
        echo "<label for=\"e_detail\" style=\"vertical-align: top;\"> Détails :</label>\n";
        echo "<textarea name=\"e_detail\" rows=\"4\" cols=\"33\"></textarea><br/>";

	$error_emptyinput= (!isset($error_emptyinput)) ? "" : $error_emptyinput ;
        if ($error_emptyinput!="") echo "<p class=\"error_message\">$error_emptyinput</p>";

        /* ########### submit ########### */
        echo "<label for=\"add_entretien\" > &nbsp;</label>\n";
        echo "<input name=\"add_entretien\" value=\"Ajouter\" type=\"submit\" class=\"little_button\" />";


    echo "</fieldset>";

/*  ╔═╗╔╗╔╦╗╦═╗╔═╗╔╦╗╦╔═╗╔╗╔╔═╗
    ║╣ ║║║║ ╠╦╝║╣  ║ ║║╣ ║║║╚═╗
    ╚═╝╝╚╝╩ ╩╚═╚═╝ ╩ ╩╚═╝╝╚╝╚═╝ */

    echo "<fieldset><legend>Entretiens</legend>";

if ( empty($entretiens[0]) ) echo "Aucun entretien spécifié.";
else {

        $today=date("Y-m-d");

        echo "<table id=\"entretien\" style=\"border:none;\">";

        echo "<thead><tr>";
            echo "<th width=\"3%\">&nbsp;</th>";
            echo "<th style=\"text-align:left;\">Désignation</th>";
            echo "<th style=\"text-align:left;\">Fréquence</th>";
            echo "<th style=\"text-align:left;\">Proch. intervention</th>";
            echo "<th width=\"3%\">&nbsp;</th>";
        echo "</tr></thead>";

        foreach ($entretiens as $e) {

            $f=$e["e_frequence"];
            $date_derniere_intervention=$e["e_lastdate"];
            $date_prochaine_intervention = date("Y-m-d", strtotime($date_derniere_intervention." +$f days") );
            $retard = round( ( strtotime($today) - strtotime($date_prochaine_intervention) ) / 86400 );

            echo "<tr>";

            // ***** La checkbox *****
            echo "<td><input type=\"checkbox\" id=\"ebox[".$e["e_index"]."]\" name=\"ebox[".$e["e_index"]."]\" value=\"1\"></td>";

            // ***** La désignation *****
            echo "<td>";
            if ($e["e_detail"]!="") echo "<abbr title=\"".$e["e_detail"]."\">";
            echo $e["e_designation"];
            if ($e["e_detail"]!="") echo "</abbr>";
            echo "</td>";

            // ***** La fréquence *****
            echo "<td>";
            $f_an=$f/365; $f_mois=$f/30;
            if ($f>=365) echo "$f_an an";
            elseif ($f>=30) echo "$f_mois moi";
            else echo "$f jour";
            if ( ($f!=1)&&($f_an!=1) ) echo "s";
            echo "</td>";

            // ***** Prochaine intervention *****
            echo "<td>";
            if ($retard>0) echo "<span style=\"color:#cc0000;\">";
            else {
                if (-$retard<$f*0.05) echo "<span style=\"color:#f57900;\">";
                else echo "<span style=\"color:#3a4a46;\">";
            }

	    $keys = array_keys(array_column($utilisateurs, 'utilisateur_index'), $e["e_effectuerpar"]);
	    
	    if ( array_key_exists(0,$keys) ) $key=$keys[0];;
	    
            echo "<abbr title=\"dernier entretien effectué ";
            if ($e["e_effectuerpar"]!=0) echo "par ".$utilisateurs["$key"]["utilisateur_prenom"]." ".$utilisateurs["$key"]["utilisateur_nom"]." ";
            echo "le ".dateformat($date_derniere_intervention,"fr")."\">";
            echo "<strong>".dateformat($date_prochaine_intervention,"fr")."</strong>";
            echo "</abbr>";

            if ($retard==0) echo " (à faire aujourd’hui)</span>";
            {   if ($retard>0) echo " (retard : $retard jour";
                else echo " (reste : ".abs($retard)." jour";
                if (abs($retard)!=1) echo "s";
                echo ")</span>";
            }
            echo "</td>";

            // ***** La suppression *****
            echo "<td style=\"text-align:right;\">";
            if ($write) echo "<span id=\"linkbox\" onclick=\"TINY.box.show({url:'0_del_confirm.php?BASE=$database&i=$i&e=".$e["e_index"]."".$quick."',width:280,height:110})\" title=\"cet entretien n’est plus nécessaire\">×</span>";
            else echo "&nbsp;";
            echo "</td>";

            echo "</tr>";
        }

        echo "</table>";
        echodatatables("entretien");

        echo "<br/>&nbsp; &nbsp;↳ Cochez les entretiens renseignés.<br/>";

        /* ########### Le ########### */
        echo "<label for=\"e_effectuele\" style=\"vertical-align: top;\"> Le <abbr title=\"si aucun calendrier n’aide à la saisie : YYYY-MM-DD\"><strong>ⓘ</strong></abbr> :</label>\n";
        echo "<input value=\"".date("Y-m-d")."\" name=\"e_effectuele\" type=\"date\" id=\"e_effectuele\"><br/>";

        /* ########### Par ########### */
        echo "<label for=\"e_effectuerpar\">Par : </label>\n";
        echo "<select name=\"e_effectuerpar\" onchange=\"display(this,'plus_intervant','plus_intervant');\" id=\"e_effectuerpar\">";
        echo "<option value=\"0\" "; if ($e["e_effectuerpar"]=="0") echo "selected"; echo ">— Aucun intervenant spécifié —</option>";
        option_selecteur($e["e_effectuerpar"], $utilisateurs, "utilisateur_index", "utilisateur_nom", "utilisateur_prenom");
        echo "<option value=\"plus_intervant\" "; if ($e["e_effectuerpar"]=="plus_intervant") echo "selected"; echo ">Nouvel intervenant :</option>";
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

        if ($error_noebox!="") echo "<p class=\"error_message\">$error_noebox</p>";

        /* ########### submit ########### */
        if ($write) {
            echo "<label for=\"modif_entretien\" > &nbsp;</label>\n";
            echo "<input name=\"modif_entretien\" value=\"Entretien effectué\" type=\"submit\" class=\"little_button\" />";
        }
    }
    echo "</fieldset>";

    if ($write) echo "</form>";
    
    echo "<p style=\"text-align:right;\"><small>* champ obligatoire</small></p>"; 

echo "</div>";

?>
