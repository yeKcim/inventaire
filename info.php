<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="fr" dir="ltr" xml:lang="fr" xmlns="http://www.w3.org/1999/xhtml">
<!--
██╗  ██╗███████╗ █████╗ ██████╗ 
██║  ██║██╔════╝██╔══██╗██╔══██╗
███████║█████╗  ███████║██║  ██║
██╔══██║██╔══╝  ██╔══██║██║  ██║
██║  ██║███████╗██║  ██║██████╔╝
╚═╝  ╚═╝╚══════╝╚═╝  ╚═╝╚═════╝ 
-->
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="robots" content="noindex,nofollow" />
    <title>Informations détaillées</title>
    <link rel="stylesheet" type="text/css" href="./style.css">
    
    <!-- ascii : http://patorjk.com/software/taag/#p=display&h=2&v=0&f=ANSI%20Shadow&t= -->
    
    <!-- tinybox pour les popup layout -->
    <script type="text/javascript" src="tinybox/tinybox.js"></script>
    <link rel="stylesheet" href="tinybox/tinybox.css" />
    
    <!-- dropzone pour drag&drop uploader
    <script src="dropzone/dropzone.js"></script>
    <link rel="stylesheet" href="dropzone/dropzone.css">    -->
    
    <!-- Show/Hide form -->
    <script type="text/javascript">
        // <![CDATA[
        function display(obj,id1,id2) {
            txt = obj.options[obj.selectedIndex].value;
            document.getElementById(id1).style.display = 'none';
            document.getElementById(id2).style.display = 'none';
            if ( txt.match(id1) ) { document.getElementById(id1).style.display = 'block'; }
            if ( txt.match(id2) ) { document.getElementById(id2).style.display = 'block'; }
        }
        // ]]>
    </script>

    <script type="text/javascript">
        // <![CDATA[
        function hide(obj,id1,id2) {
            txt = obj.options[obj.selectedIndex].value;
            document.getElementById(id1).style.display = 'block';
            document.getElementById(id2).style.display = 'block';
            if ( txt.match(id1) ) { document.getElementById(id1).style.display = 'none'; }
            if ( txt.match(id2) ) { document.getElementById(id2).style.display = 'none'; }
        }
        // ]]>
    </script>


</head>


<!--
██████╗  ██████╗ ██████╗ ██╗   ██╗
██╔══██╗██╔═══██╗██╔══██╗╚██╗ ██╔╝
██████╔╝██║   ██║██║  ██║ ╚████╔╝ 
██╔══██╗██║   ██║██║  ██║  ╚██╔╝  
██████╔╝╚██████╔╝██████╔╝   ██║   
╚═════╝  ╚═════╝ ╚═════╝    ╚═╝   
-->
<body>

<?php
require_once("./connect.php");
require_once("./tables_sql_commun.php");
$i= isset($_GET["i"]) ? htmlentities($_GET["i"]) : "" ; // GET i
require_once("./fonctions.php");


/*
 █████╗ ██████╗ ██████╗  █████╗ ██╗   ██╗
██╔══██╗██╔══██╗██╔══██╗██╔══██╗╚██╗ ██╔╝
███████║██████╔╝██████╔╝███████║ ╚████╔╝ 
██╔══██║██╔══██╗██╔══██╗██╔══██║  ╚██╔╝  
██║  ██║██║  ██║██║  ██║██║  ██║   ██║   
╚═╝  ╚═╝╚═╝  ╚═╝╚═╝  ╚═╝╚═╝  ╚═╝   ╚═╝   
*/

/* ########### INFORMATIONS COMPOSANT ########### */
// Tous les résultats dans un array
$query_table = mysql_query ("SELECT * FROM base WHERE base_index=$i ;");
while ($l = mysql_fetch_row($query_table)) {
    $data=array(
        "base_index"=>$l[0],                  "lab_id"=>$l[1],                  "categorie"=>$l[2],
        "serial_number"=>utf8_encode($l[3]),  "reference"=>utf8_encode($l[4]),  "designation"=>$l[5],
        "utilisateur"=>$l[6],                 "localisation"=>$l[7],            "date_localisation"=>$l[8],
        "tutelle"=>$l[9],                     "contrat"=>$l[10],                "num_inventaire"=>utf8_encode($l[11]),        
        "vendeur"=>$l[12],                    "marque"=>$l[13],                 "date_achat"=>$l[14],
        "responsable_achat"=>$l[15],          "garantie"=>$l[16],               "prix"=>$l[17],
        "date_sortie"=>$l[18],                "sortie"=>$l[19],                 "raison_sortie"=>$l[20],
        "integration"=>$l[21]
    );
}

/* ########### tables ########### */

// utilisateurs
$query_table_utilisateur = mysql_query ("SELECT DISTINCT(utilisateur_index) utilisateur_index, utilisateur_nom, utilisateur_prenom, utilisateur_mail, utilisateur_phone FROM utilisateur WHERE utilisateur_index!=0 ORDER BY utilisateur_nom ASC ;");
$utilisateurs = array();
while ($utilisateur = mysql_fetch_row($query_table_utilisateur)) {
    $utilisateurs[$utilisateur[0]]=array(   $utilisateur[0],                    utf8_encode($utilisateur[1]),
                                            utf8_encode($utilisateur[2]),       $utilisateur[3],
                                            $utilisateur[4]
                                        );
}

// categories
// $categories in tables_sql_commun.php

// types de contrats
// $types_contrats in tables_sql_commun.php

// contrats
$query_table_contrat = mysql_query ("SELECT * FROM contrat, contrat_type WHERE contrat_type!=0 AND contrat_index!=0 ORDER BY contrat_nom ASC ;");
$contrats = array();
while ($l = mysql_fetch_row($query_table_contrat)) {
    $contrats[$l[0]]=array($l[0],utf8_encode($l[1]));
}

// localisation
$query_table_localisation = mysql_query ("SELECT * FROM localisation WHERE localisation_index!=0 ORDER BY localisation_batiment ASC, localisation_piece ASC ;");
$localisations = array();
while ($l = mysql_fetch_row($query_table_localisation)) {
    $localisations[$l[0]]=array($l[0],utf8_encode($l[1]),utf8_encode($l[2]));
}

// tutelles
$query_table_tutelle = mysql_query ("SELECT * FROM tutelle WHERE tutelle_index!=0 ORDER BY tutelle_nom ASC ;");
$tutelles = array();
while ($l = mysql_fetch_row($query_table_tutelle)) {
    $tutelles[$l[0]]=array($l[0],utf8_encode($l[1]));
}

// vendeur
$query_table_vendeur = mysql_query ("SELECT * FROM vendeur WHERE vendeur_index!=0 ORDER BY vendeur_nom ASC ;");
$vendeurs = array();
while ($l = mysql_fetch_row($query_table_vendeur)) {
    $vendeurs[$l[0]]=array($l[0],utf8_encode($l[1]),utf8_encode($l[2]),utf8_encode($l[3]));
}

// marque
$query_table_marque = mysql_query ("SELECT * FROM marque WHERE marque_index!=0 ORDER BY marque_nom ASC ;");
$marques = array();
while ($l = mysql_fetch_row($query_table_marque)) {
    $marques[$l[0]]=array($l[0],utf8_encode($l[1]));
}


// raison_sortie
$query_table_raison_sortie = mysql_query ("SELECT * FROM raison_sortie WHERE raison_sortie_index!=0 ORDER BY raison_sortie_nom ASC  ;");
$raison_sorties = array();
while ($l = mysql_fetch_row($query_table_raison_sortie)) {
    $raison_sorties[$l[0]]=array($l[0],utf8_encode($l[1]));
}


// caracteristiques
$query_table_caracteristique = mysql_query ("SELECT * FROM caracteristiques WHERE carac!=0 ORDER BY nom_carac ASC ;");
$caracteristiques = array();
while ($l = mysql_fetch_row($query_table_caracteristique)) {
    $caracteristiques[$l[0]]=array($l[0],utf8_encode($l[1]),utf8_encode($l[2]),utf8_encode($l[3]));
}


// caracs
$caracs=array();
$query_table_carac = mysql_query ("SELECT base_index, categorie, carac_valeur, carac, nom_carac, unite_carac, symbole_carac FROM caracteristiques, carac, base WHERE carac_id=base_index AND carac_caracteristique_id=carac AND base_index=$i AND carac!=0 ORDER BY base.base_index ASC, carac ASC");
while ($l = mysql_fetch_row($query_table_carac)) {
    $caracs[$l[3]]=array($l[0],$l[1],utf8_encode($l[2]),$l[3],utf8_encode($l[4]),utf8_encode($l[5]),utf8_encode($l[6]) );
}


// tous les lab_id
$query_table_lab_id = mysql_query ("SELECT base_index, lab_id FROM base WHERE base_index!=\"$i\" ORDER BY lab_id ASC ;");
$lab_ids = array();
while ($l = mysql_fetch_row($query_table_lab_id)) {
    $lab_ids[$l[0]]=array($l[0],"#".$l[0]."", utf8_encode($l[1]));
}

// compatibilité
$query_table_compatibilite = mysql_query ("SELECT * FROM compatibilite WHERE compatib_id1=\"$i\" OR compatib_id2=\"$i\"  ;");
$compatibilite = array();
while ($l = mysql_fetch_row($query_table_compatibilite)) {
    $num= ($l[1]==$i) ? $l[2] : $l[1] ;
    $compatibilite[$l[0]]=array($l[0],$num);
}



/*
██████╗ ██╗███████╗██████╗ ██╗      █████╗ ██╗   ██╗    ██████╗ ██╗      ██████╗  ██████╗███████╗
██╔══██╗██║██╔════╝██╔══██╗██║     ██╔══██╗╚██╗ ██╔╝    ██╔══██╗██║     ██╔═══██╗██╔════╝██╔════╝
██║  ██║██║███████╗██████╔╝██║     ███████║ ╚████╔╝     ██████╔╝██║     ██║   ██║██║     ███████╗
██║  ██║██║╚════██║██╔═══╝ ██║     ██╔══██║  ╚██╔╝      ██╔══██╗██║     ██║   ██║██║     ╚════██║
██████╔╝██║███████║██║     ███████╗██║  ██║   ██║       ██████╔╝███████╗╚██████╔╝╚██████╗███████║
╚═════╝ ╚═╝╚══════╝╚═╝     ╚══════╝╚═╝  ╚═╝   ╚═╝       ╚═════╝ ╚══════╝ ╚═════╝  ╚═════╝╚══════╝
*/

echo "<p>Informations #$i :</p>";

echo "<div id=\"container\">";

/*
 █████╗ ██████╗ ███╗   ███╗██╗███╗   ██╗██╗███████╗████████╗██████╗  █████╗ ████████╗██╗███████╗
██╔══██╗██╔══██╗████╗ ████║██║████╗  ██║██║██╔════╝╚══██╔══╝██╔══██╗██╔══██╗╚══██╔══╝██║██╔════╝
███████║██║  ██║██╔████╔██║██║██╔██╗ ██║██║███████╗   ██║   ██████╔╝███████║   ██║   ██║█████╗  
██╔══██║██║  ██║██║╚██╔╝██║██║██║╚██╗██║██║╚════██║   ██║   ██╔══██╗██╔══██║   ██║   ██║██╔══╝  
██║  ██║██████╔╝██║ ╚═╝ ██║██║██║ ╚████║██║███████║   ██║   ██║  ██║██║  ██║   ██║   ██║██║     
╚═╝  ╚═╝╚═════╝ ╚═╝     ╚═╝╚═╝╚═╝  ╚═══╝╚═╝╚══════╝   ╚═╝   ╚═╝  ╚═╝╚═╝  ╚═╝   ╚═╝   ╚═╝╚═╝     
*/


/* ########### Si des modifications dans la partie administrative ########### */
if ( isset($_POST["administratif_valid"]) ) {

    $arr = array("designation","vendeur","plus_vendeur_nom","plus_vendeur_web","plus_vendeur_remarque","prix","contrat","plus_contrat_nom", "contrat_type", "plus_contrat_type_nom", "tutelle", "plus_tutelle", "num_inventaire", "responsable_achat", "plus_responsable_achat_prenom", "plus_responsable_achat_nom", "plus_responsable_achat_mail", "plus_responsable_achat_phone", "date_achat", "garantie");
    foreach ($arr as &$value) {
        $$value= isset($_POST[$value]) ? htmlentities($_POST[$value]) : "" ;
    }


    /* ########### Ajout d’un nouveau vendeur ########### */
    if ($vendeur=="plus_vendeur") {
        mysql_query ("INSERT INTO vendeur (vendeur_nom, vendeur_web, vendeur_remarques) VALUES (\"".$plus_vendeur_nom."\",\"".$plus_vendeur_web."\",\"".$plus_vendeur_remarque."\") ; ");
        
        /* TODO : prévoir le cas où le vendeur existe déjà */
        $query_table_vendeurnew = mysql_query ("SELECT vendeur_index FROM vendeur ORDER BY vendeur_index DESC LIMIT 1 ;");
        while ($l = mysql_fetch_row($query_table_vendeurnew)) $vendeur=$l[0];
        
        // on ajoute cette entrée dans le tableau des vendeurs (utilisé pour le select)
        $vendeurs[$vendeur]=array($vendeur,utf8_encode($plus_vendeur_nom),utf8_encode($plus_vendeur_web),utf8_encode($plus_vendeur_remarque));
    }


    if ($contrat_type=="plus_contrat_type") {
        mysql_query ("INSERT INTO contrat_type (contrat_type_cat) VALUES ('".$plus_contrat_type_nom."') ; ");
        
        /* TODO : prévoir le cas où le type de contrat existe déjà */
        $query_table_contrattypenew = mysql_query ("SELECT contrat_type_index FROM contrat_type ORDER BY contrat_type_index DESC LIMIT 1 ;");
        while ($l = mysql_fetch_row($query_table_contrattypenew)) $contrat_type=$l[0];
        
        // on ajoute cette entrée dans le tableau des types de contrats (utilisé pour le select)
        $types_contrats[$contrat_type]=array( $contrat_type, utf8_encode($plus_contrat_type_nom) );
    }


    if ($contrat=="plus_contrat") {
        mysql_query ("INSERT INTO contrat (contrat_nom, contrat_type) VALUES ('".$plus_contrat_nom."','".$contrat_type."') ; ");
        /* TODO : prévoir le cas où le contrat existe déjà */
        $query_table_contratnew = mysql_query ("SELECT contrat_index FROM contrat ORDER BY contrat_index DESC LIMIT 1 ;");
        while ($l = mysql_fetch_row($query_table_contratnew)) $contrat=$l[0];
        
        // on ajoute cette entrée dans le tableau des types de contrats (utilisé pour le select)
        $contrats[$contrat]=array( $contrat, utf8_encode($plus_contrat_nom), $contrat_type );
    }


    if ($tutelle=="plus_tutelle") {
        mysql_query ("INSERT INTO tutelle (tutelle_nom) VALUES ('".$plus_tutelle."') ; ");
        /* TODO : prévoir le cas où le contrat existe déjà */
        $query_table_tutellenew = mysql_query ("SELECT tutelle_index FROM tutelle ORDER BY tutelle_index DESC LIMIT 1 ;");
        while ($l = mysql_fetch_row($query_table_tutellenew)) $tutelle=$l[0];
        
        // on ajoute cette entrée dans le tableau des types de contrats (utilisé pour le select)
        $tutelles[$tutelle]=array( $tutelle, utf8_encode($plus_tutelle) );
    }



    if ($responsable_achat=="plus_responsable_achat") {

        $plus_responsable_achat_nom=mb_strtoupper($plus_responsable_achat_nom);
        $plus_responsable_achat_phone=phone_display("$plus_responsable_achat_phone","");
        
        mysql_query ("INSERT INTO utilisateur (utilisateur_nom, utilisateur_prenom, utilisateur_mail, utilisateur_phone) VALUES ('".$plus_responsable_achat_nom."', '".$plus_responsable_achat_prenom."','".$plus_responsable_achat_mail."','".$plus_responsable_achat_phone."') ; ");
        /* TODO : prévoir le cas où le contrat existe déjà */
        $query_table_utilisateurnew = mysql_query ("SELECT utilisateur_index FROM utilisateur ORDER BY utilisateur_index DESC LIMIT 1 ;");
        while ($l = mysql_fetch_row($query_table_utilisateurnew)) $responsable_achat=$l[0];
        
        // on ajoute cette entrée dans le tableau des types de contrats (utilisé pour le select)
        $utilisateurs[$responsable_achat]=array( $responsable_achat, utf8_encode($plus_responsable_achat_nom), utf8_encode($plus_responsable_achat_prenom), utf8_encode($plus_responsable_achat_mail), phone_display("$plus_responsable_achat_phone",".") );

    }




    mysql_query ("UPDATE base SET designation='".$designation."', vendeur='".$vendeur."', prix='".$prix."', contrat='".$contrat."', date_achat='".dateformat($date_achat,"en")."', garantie='".dateformat($garantie,"en")."', num_inventaire='".$num_inventaire."', tutelle='".$tutelle."', responsable_achat='".$responsable_achat."' WHERE base.base_index = $i;" );




    // Avant d’afficher on doit ajouter les nouvelles infos dans les array concernés…
    $data["designation"]=$designation;
    $data["vendeur"]=$vendeur;
    $data["prix"]=$prix;
    $data["contrat"]=$contrat;
    $data["date_achat"]=dateformat($date_achat,"en");
    $data["garantie"]=dateformat($garantie,"en");
    $data["num_inventaire"]=$num_inventaire;
    $data["tutelle"]=$tutelle;
    $data["plus_tutelle"]=$plus_tutelle;
    $data["responsable_achat"]=$responsable_achat;


}



echo "<div id=\"bloc\" style=\"background:#fcf3a3; vertical-align:top;\">";
    
    echo "<h1>Administratif</h1>";
    
    echo "<form method=\"post\" action=\"?i=$i\">";

    echo "<fieldset><legend>Produit</legend>";

        /* ########### designation ########### */
        echo "<label for=\"designation\" style=\"vertical-align: top;\">Désignation :</label>\n";
        echo "<textarea name=\"designation\" rows=\"4\" cols=\"33\" id=\"designation\">".$data["designation"]."</textarea><br/>";
      
        /* ########### vendeur ########### */
        echo "<label for=\"vendeur\">Vendeur ";
        echo " <a href=\"".$vendeurs[$data["vendeur"]][2]."\" title=\"site web\" target=\"_blank\"><strong>↗</strong></a> <strong><abbr title=\"".$vendeurs[$data["vendeur"]][3]."\"><strong>ⓘ</strong></abbr></strong>";
        echo " :</label>\n";
        echo "<select name=\"vendeur\" id=\"vendeur\" onchange=\"display(this,'plus_vendeur','plus_vendeur');\" >";
        echo "<option value=\"0\" "; if ($data["vendeur"]=="0") echo "selected"; echo ">— Aucun vendeur spécifié —</option>"; 
        option_selecteur($data["vendeur"], $vendeurs);
        echo "<option value=\"plus_vendeur\" "; if ($data["vendeur"]=="plus_vendeur") echo "selected"; echo ">Nouveau vendeur :</option>";
        echo "</select>";
        echo "<br/>";


        /* ########### + vendeur ########### */
        echo "\n\n\n";
        echo "<fieldset id=\"plus_vendeur\" class=\"subfield\" style=\"display: none;\"><legend  class=\"subfield\">Nouveau Vendeur</legend>";
    
            echo "<label for=\"plus_vendeur_nom\">Nom :</label>\n";
            echo "<input value=\"\" name=\"plus_vendeur_nom\" type=\"text\">\n";
        
            echo "<label for=\"plus_vendeur_web\">Site web :</label>\n";
            echo "<input value=\"\" name=\"plus_vendeur_web\" type=\"text\">\n";       
        
            echo "<label for=\"plus_vendeur_remarque\">Remarque :</label>\n";
            echo "<input value=\"\" name=\"plus_vendeur_remarque\" type=\"text\">\n";        
        
        echo "</fieldset>";
        echo "\n\n\n";

        /* ########### prix ########### */
        echo "<label for=\"prix\">Prix (€) : </label>\n";
        echo "<input value=\"".$data["prix"]."\" name=\"prix\" type=\"text\" id=\"prix\"><br/>";

    echo "</fieldset>";

    echo "<fieldset><legend>Acheteur</legend>";

        /* ########### contrat ########### */
        echo "<label for=\"contrat\">Contrat : </label>\n";
        echo "<select name=\"contrat\" onchange=\"display(this,'plus_contrat','plus_contrat');\" id=\"contrat\">";
        echo "<option value=\"0\" "; if ($data["contrat"]=="0") echo "selected"; echo ">— Aucun contrat spécifié —</option>"; 
        option_selecteur($data["contrat"], $contrats);
        echo "<option value=\"plus_contrat\" "; if ($data["contrat"]=="plus_contrat") echo "selected"; echo ">Nouveau contrat :</option>";
        echo "</select><br/>";

        /* ########### + contrat ########### */
        echo "\n\n\n";
        echo "<fieldset id=\"plus_contrat\" class=\"subfield\" style=\"display: none;\"><legend class=\"subfield\">Nouveau Contrat</legend>";
    
            echo "<label for=\"plus_contrat_nom\">Nom :</label>\n";
            echo "<input value=\"\" name=\"plus_contrat_nom\" type=\"text\">\n";
        
            echo "<label for=\"contrat_type\">Type de contrat :</label>\n";
               echo "<select name=\"contrat_type\" onchange=\"display(this,'plus_contrat_type','plus_contrat_type');\" id=\"contrat_type\">";
                   echo "<option value=\"0\" selected >— Aucun type de contrat spécifié —</option>"; 
                   option_selecteur("0", $types_contrats);
                   echo "<option value=\"plus_contrat_type\" >Nouveau type de contrat :</option>";
               echo "</select><br/>";

                    /* ########### + type contrat ########### */
                    echo "\n\n\n";
                    echo "<fieldset id=\"plus_contrat_type\" class=\"subfield\" style=\"display: none;\"><legend class=\"subfield\">Nouveau Type de contrat</legend>";
                        echo "<label for=\"plus_contrat_type_nom\">Type :</label>\n";
                        echo "<input value=\"\" name=\"plus_contrat_type_nom\" type=\"text\">\n";
                    echo "</fieldset>";
                    echo "\n\n\n";

        echo "</fieldset>";
        echo "\n\n\n";

        /* ########### tutelle ########### */
        echo "<label for=\"tutelle\">Tutelle : </label>\n";
        echo "<select name=\"tutelle\" onchange=\"display(this,'plus_tutelle','plus_tutelle');\" id=\"tutelle\">";
        echo "<option value=\"0\" "; if ($data["tutelle"]=="") echo "selected"; echo ">— Aucune tutelle spécifiée —</option>"; 
        option_selecteur($data["tutelle"], $tutelles);
        echo "<option value=\"plus_tutelle\" "; if ($data["tutelle"]=="plus_tutelle") echo "selected"; echo ">Nouvelle tutelle :</option>";
        echo "</select><br/>";
        
            /* ########### + tutelle ########### */
            echo "\n\n\n";
            echo "<fieldset id=\"plus_tutelle\" class=\"subfield\" style=\"display: none;\"><legend class=\"subfield\">Nouvelle tutelle</legend>";
                echo "<label for=\"plus_tutelle\">Tutelle :</label>\n";
                echo "<input value=\"\" name=\"plus_tutelle\" type=\"text\">\n";
            echo "</fieldset>";
            echo "\n\n\n";
        

        /* ########### num_inventaire ########### */
        echo "<label for=\"num_inventaire\">Numéro d’inventaire : </label>\n";
        echo "<input value=\"".$data["num_inventaire"]."\" name=\"num_inventaire\" type=\"text\" id=\"num_inventaire\">";
        echo "<br/>";

        /* ########### responsable_achat ########### */
        echo "<label for=\"responsable_achat\">Acheteur ";

        echo "<a href=\"mailto:".$utilisateurs[$data["responsable_achat"]][3]."\" title=\"".$utilisateurs[$data["responsable_achat"]][3]."\"><strong>✉</strong></a> ";
echo "<abbr title=\"".phone_display("".$utilisateurs[$data["responsable_achat"]][4]."",".")."\"><strong>☏</strong></abbr>";
        
        echo ": </label>\n";
        echo "<select name=\"responsable_achat\" onchange=\"display(this,'plus_responsable_achat','plus_responsable_achat');\" id=\"responsable_achat\">";
        echo "<option value=\"0\" "; if ($data["responsable_achat"]=="0") echo "selected"; echo ">— Aucun responsable achat spécifié —</option>"; 
        option_selecteur($data["responsable_achat"], $utilisateurs, "2");
        echo "<option value=\"plus_responsable_achat\" "; if ($data["responsable_achat"]=="plus_responsable_achat") echo "selected"; echo ">Nouveau responsable achat :</option>";
        echo "</select>";


            /* ########### + responsable_achat ########### */
            echo "\n\n\n";
            echo "<fieldset id=\"plus_responsable_achat\" class=\"subfield\" style=\"display: none;\"><legend class=\"subfield\">Nouveau responsable achat</legend>";
                echo "<label for=\"plus_responsable_achat_prenom\">Prénom :</label>\n";
                echo "<input value=\"\" name=\"plus_responsable_achat_prenom\" type=\"text\">\n";

                echo "<label for=\"plus_responsable_achat_nom\">Nom :</label>\n";
                echo "<input value=\"\" name=\"plus_responsable_achat_nom\" type=\"text\">\n";
                
                echo "<label for=\"plus_responsable_achat_mail\">Mail :</label>\n";
                echo "<input value=\"\" name=\"plus_responsable_achat_mail\" type=\"text\">\n";
                
                echo "<label for=\"plus_responsable_achat_phone\">Téléphone :</label>\n";
                echo "<input value=\"\" name=\"plus_responsable_achat_phone\" type=\"text\">\n";

            echo "</fieldset>";
            echo "\n\n\n";

    echo "</fieldset>";

    echo "<fieldset><legend>Dates</legend>";
    
        /* ########### date_achat ########### */
        echo "<label for=\"achat\">Achat <abbr title=\"JJ/MM/AAAA\"><strong>ⓘ</strong></abbr> :</label>\n";
        echo "<input value=\"".dateformat($data["date_achat"],"fr")."\" name=\"date_achat\" type=\"date\" id=\"achat\"/>";
        echo "<br/>";

        /* ########### garantie ########### */
        echo "<label for=\"garantie\">Fin de garantie <abbr title=\"JJ/MM/AAAA\"><strong>ⓘ</strong></abbr> :</label>\n";
        echo "<input value=\"".dateformat($data["garantie"],"fr")."\" name=\"garantie\" type=\"date\" id=\"garantie\" /><br/>";
        
    
    echo "</fieldset>";

    echo "<p style=\"text-align:center;\"><input name='administratif_valid' value='Enregistrer' type='submit'></p>"; // TODO Ajouter un bouton réinitialiser

    echo "</form>";



echo "</div>";

/*
████████╗███████╗ ██████╗██╗  ██╗███╗   ██╗██╗ ██████╗ ██╗   ██╗███████╗
╚══██╔══╝██╔════╝██╔════╝██║  ██║████╗  ██║██║██╔═══██╗██║   ██║██╔════╝
   ██║   █████╗  ██║     ███████║██╔██╗ ██║██║██║   ██║██║   ██║█████╗  
   ██║   ██╔══╝  ██║     ██╔══██║██║╚██╗██║██║██║▄▄ ██║██║   ██║██╔══╝  
   ██║   ███████╗╚██████╗██║  ██║██║ ╚████║██║╚██████╔╝╚██████╔╝███████╗
   ╚═╝   ╚══════╝ ╚═════╝╚═╝  ╚═╝╚═╝  ╚═══╝╚═╝ ╚══▀▀═╝  ╚═════╝ ╚══════╝
*/


/* ########### Si des modifications dans la partie Technique ########### */
if ( isset($_POST["technique_valid"]) ) {

    $arr = array("categorie", "plus_categorie_nom", "plus_categorie_abbr", "marque", "plus_marque", "plus_marque_nom", "reference", "serial_number");
    foreach ($arr as &$value) {
        $$value= isset($_POST[$value]) ? htmlentities($_POST[$value]) : "" ;
    }


    /* ########### Ajout d’une nouvelle catégorie ########### */
    if ($categorie=="plus_categorie") {
        mysql_query ("INSERT INTO categorie (categorie_lettres, categorie_nom) VALUES (\"".$plus_categorie_abbr."\",\"".$plus_categorie_nom."\") ; ");
        
        /* TODO : prévoir le cas où le vendeur existe déjà */
        $query_table_categorienew = mysql_query ("SELECT categorie_index FROM categorie ORDER BY categorie_index DESC LIMIT 1 ;");
        while ($l = mysql_fetch_row($query_table_categorienew)) $categorie=$l[0];
        // on ajoute cette entrée dans le tableau des catégories (utilisé pour le select)
        $categories[$categorie]=array( $categorie,utf8_encode($plus_categorie_nom),utf8_encode($plus_categorie_abbr) );
        // TODO Attetion l’abréviation ne doit contenir que des lettres !
    }


    /* ########### Ajout d’une nouvelle marque ########### */
    if ($marque=="plus_marque") {
        mysql_query ("INSERT INTO marque (marque_nom) VALUES ('".$plus_marque_nom."') ; ");
        /* TODO : prévoir le cas où la marque existe déjà */
        $query_table_marquenew = mysql_query ("SELECT marque_index FROM marque ORDER BY marque_index DESC LIMIT 1 ;");
        while ($l = mysql_fetch_row($query_table_marquenew)) $marque=$l[0];
        // on ajoute cette entrée dans le tableau des types de contrats (utilisé pour le select)
        $marques[$marque]=array( $marque, utf8_encode($plus_marque_nom) );
    }


    // Si on change la catégorie, il est nécessaire de changer également le lab_id !
    if ($data["categorie"]!=$categorie) {
        // quelle est l’abbréviation de la catégorie ?
        $query_table_abbr = mysql_query ("SELECT categorie_lettres FROM categorie WHERE categorie_index='".$categorie."' ;");
        while ($l = mysql_fetch_row($query_table_abbr)) $abbr=$l[0];

        // recherche du labid max
        $allid=array();
        $query_table_labid = mysql_query ("SELECT lab_id FROM base WHERE categorie='".$categorie."' ORDER BY lab_id ASC ;");
        while ($lid = mysql_fetch_row($query_table_labid)) {
            // on supprime les lettres des lab_id, on met les chiffres dans un tableau
            array_push ( $allid, preg_replace('`[^0-9]`', '', $lid[0]) );
        }
        $newid=max($allid)+1;
        $data["lab_id"]="".$abbr."".$newid."";
        // TODO : Vérifier avant qu’aucune autre entrée ainsi nommée n’existe ! dans le cas d’un nommage manuel
    }

    $data["lab_id"] = ($categorie==0) ? "" : $data["lab_id"];

    mysql_query ("UPDATE base SET marque='".$marque."', reference='".$reference."', serial_number='".$serial_number."', categorie='".$categorie."', lab_id='".$data["lab_id"]."' WHERE base.base_index = $i;" );

    // Avant d’afficher on doit ajouter les nouvelles infos dans les array concernés…
    $data["marque"]=$marque;
    $data["serial_number"]=$serial_number;
    $data["reference"]=$reference;
    $data["categorie"]=$categorie;

}



echo "<div id=\"bloc\" style=\"background:#b4e287; vertical-align:top;\">";

    echo "<h1>Technique</h1>";
    
    echo "<form method=\"post\" action=\"?i=$i\">";

    echo "<fieldset><legend>Référence interne</legend>";

        /* ########### categorie ########### */
        echo "<label for=\"categorie\">Catégorie : </label>\n";
        echo "<select name=\"categorie\" onchange=\"display(this,'plus_categorie','plus_categorie');\" id=\"categorie\">";
        echo "<option value=\"0\" "; if ($data["categorie"]=="0") echo "selected"; echo ">— Aucune catégorie spécifiée —</option>"; 
        option_selecteur($data["categorie"], $categories, "2");
        echo "<option value=\"plus_categorie\" "; if ($data["categorie"]=="plus_categorie") echo "selected"; echo ">Nouvelle catégorie :</option>";
        echo "</select><br/>";
        
            /* ########### + categorie ########### */
            echo "\n\n\n";
            echo "<fieldset id=\"plus_categorie\" class=\"subfield\" style=\"display: none;\"><legend class=\"subfield\">Nouvelle Catégorie</legend>";
                echo "<label for=\"plus_categorie_nom\">Nom :</label>\n";
                echo "<input value=\"\" name=\"plus_categorie_nom\" type=\"text\">\n";
                echo "<label for=\"plus_categorie_abbr\">Abbréviation :</label>\n";
                echo "<input value=\"\" name=\"plus_categorie_abbr\" type=\"text\" maxlength=\"4\">\n";
            echo "</fieldset>";
            echo "\n\n\n";

        /* ########### lab_id ########### */
        echo "<label for=\"lab_id\">Identifiant labo : </label>\n";
        echo "<strong>".$data["lab_id"]."</strong>"; // TODO Ajouter un bouton pour choiser cette entrée manuellement (via tinybox ?)
        echo "<br/>";

    echo "</fieldset>";


    echo "<fieldset><legend>Références Constructeur</legend>";

        /* ########### marque ########### */
        echo "<label for=\"marque\">Marque : </label>\n";
        echo "<select name=\"marque\" onchange=\"display(this,'plus_marque','plus_marque');\" id=\"marque\">";
        echo "<option value=\"0\" "; if ($data["marque"]=="0") echo "selected"; echo ">— Aucune marque spécifiée —</option>"; 
        option_selecteur($data["marque"], $marques);
        echo "<option value=\"plus_marque\" "; if ($data["marque"]=="plus_marque") echo "selected"; echo ">Nouvelle marque :</option>";
        echo "</select><br/>";
        
            /* ########### + marque ########### */
            echo "\n\n\n";
            echo "<fieldset id=\"plus_marque\" class=\"subfield\" style=\"display: none;\"><legend class=\"subfield\">Nouvelle Marque</legend>";
                echo "<label for=\"plus_marque_nom\">Nom :</label>\n";
                echo "<input value=\"\" name=\"plus_marque_nom\" type=\"text\">\n";
            echo "</fieldset>";
            echo "\n\n\n";

        /* ########### reference ########### */
        echo "<label for=\"reference\">Référence : </label>\n";
        echo "<input value=\"".$data["reference"]."\" name=\"reference\" type=\"text\" id=\"reference\">";
        echo "<br/>";

        /* ########### serial_number ########### */
        echo "<label for=\"serial_number\">Numéro de série : </label>\n";
        echo "<input value=\"".$data["serial_number"]."\" name=\"serial_number\" type=\"text\" id=\"serial_number\"><br/>";

    echo "</fieldset>";


    echo "<fieldset><legend>Caractéristiques</legend>";

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


    echo "<fieldset><legend>Compatibilité</legend>";
        echo "<ul>";

    if (!$compatibilite) echo "Aucune compatibilité renseignée.<br/>";
    else {
        foreach ($compatibilite as $c) {
            echo "<li class=\"inline\">";
            echo "<input type=\"checkbox\" name=\"comp".$c[0]."\" value=\"".$c[0]."\" checked > ";
            echo "<a href=\"info.php?i=".$c[1]."\" target=\"_blank\">".$lab_ids[$c[1]][1]." ".$lab_ids[$c[1]][2]."</a>";
            echo "</li>";
            
            }
        echo "</ul><br/>";
    }
        echo "<a href=\"\">➕</a>";
        
    echo "</fieldset>";

    echo "<p style=\"text-align:center;\"><input name='technique_valid' value='Enregistrer' type='submit'></p>"; // TODO Ajouter un bouton réinitialiser

    echo "</form>";

echo "</div>";


/*
██████╗  ██████╗  ██████╗██╗   ██╗███╗   ███╗███████╗███╗   ██╗████████╗███████╗
██╔══██╗██╔═══██╗██╔════╝██║   ██║████╗ ████║██╔════╝████╗  ██║╚══██╔══╝██╔════╝
██║  ██║██║   ██║██║     ██║   ██║██╔████╔██║█████╗  ██╔██╗ ██║   ██║   ███████╗
██║  ██║██║   ██║██║     ██║   ██║██║╚██╔╝██║██╔══╝  ██║╚██╗██║   ██║   ╚════██║
██████╔╝╚██████╔╝╚██████╗╚██████╔╝██║ ╚═╝ ██║███████╗██║ ╚████║   ██║   ███████║
╚═════╝  ╚═════╝  ╚═════╝ ╚═════╝ ╚═╝     ╚═╝╚══════╝╚═╝  ╚═══╝   ╚═╝   ╚══════╝
*/

/* TODO : Ajouter la possibilité d’avoir des fichiers liés à fabricant-référence en plus de #n pour les data-sheet par exemple */

require_once("./config.php");

$max_size=file_upload_max_size();
/* ########### POST ########### */
$arr = array("del_f_confirm","f");
foreach ($arr as &$value) {
    $$value= isset($_POST[$value]) ? htmlentities($_POST[$value]) : "" ;
}

/* ########### Suppression d’un fichier ########### */
if ($del_f_confirm=="Confirmer la suppression") {
    // Si le dossier trash n’existe pas, on le crée
    if (!file_exists("$trash")) mkdir("$trash", 0775);
    // Si le dossier trash/$i n’existe pas, on le crée
    if (!file_exists("$trash$i")) mkdir("$trash$i", 0775);
    // unlink("/var/www/files/$i/$f"); // Supprimer un fichier ainsi est un peu violent, préférons le déplacer dans un dossier trash
    rename("$dossierdesfichiers$i/$f","$trash$i/$f");
}


echo "<div id=\"bloc\" style=\"background:rgb(245, 214, 197); vertical-align:top;\">";

    echo "<h1>Documents</h1>";

    echo "<fieldset><legend>Ajouter un fichier</legend>";
        echo "<form method=\"post\" action=\"?i=$i\" enctype=\"multipart/form-data\">";
        echo "<input value=\"".$data["base_index"]."\" name=\"i\" type=\"hidden\">\n";
        /* echo "<form action=\"$racine$dir\" class=\"dropzone\"></form>";*/
        echo "<p>Extensions autorisées : ";
        foreach ($extensions as $e) echo "$e ";
        echo "<br/>";
        echo "Taille maximum : ".formatBytes($max_size)."o.<br/>";
        
        echo "<input type=\"file\" name=\"fichier\" style=\"border:0px solid #cc0000;\"/>";
        echo "</p>";
        
        /* ########### Ajout d’un fichier ########### */
        if(isset($_FILES['fichier'])){
            $errors= array();
            $file_name = $_FILES['fichier']['name'];
            $file_size =$_FILES['fichier']['size'];
            $file_tmp =$_FILES['fichier']['tmp_name'];
            $file_type=$_FILES['fichier']['type'];
            $file_ext=strtolower(end(explode('.',$_FILES['fichier']['name'])));

            if(in_array($file_ext,$extensions)=== false){
                $errors[]="Extension non permise.";
            }

            if ( ($file_size > $max_size)|| ($file_size == 0) ) {
                $errors[]="La taille du fichier doit être au maximum de ".formatBytes($max_size)."o.";
            }

            if(empty($errors)==true) {
                move_uploaded_file($file_tmp,"/var/www/files/$i/".$file_name);
                echo "Fichier envoyé avec succès.<br/>";
            }
            else foreach ($errors as $e) echo "<p><strong>$e</strong></p>";
        }
        /* ########### END Ajout d’un fichier ########### */


        echo "<input name='Valider' value='Envoyer' type='submit'>";
        echo "</form>";

    echo "</fieldset>";


    echo "<fieldset><legend>Fichiers</legend>";
        displayDir("files/$i/", $del=TRUE);
    echo "</fieldset>";


    // Array references_similaires
    $query_table_reference_similaire = mysql_query ("SELECT base_index, lab_id FROM base WHERE reference=\"".$data["reference"]."\" AND marque=".$data["marque"]." AND categorie=".$data["categorie"]." AND base_index!=$i ORDER BY base_index ASC ;");
    $references_similaires = array();
    while ($l = mysql_fetch_row($query_table_reference_similaire)) {
        $references_similaires[$l[0]]=array($l[0], utf8_encode($l[1]));
    }

    echo "<fieldset><legend>Fichiers de référence similaire</legend>";
    
    if (!$references_similaires) echo "Aucune référence correspondante trouvée";
    else {
        foreach ($references_similaires as $rs) {
            echo "<a href=\"info.php?i=".$rs[0]."\" target=\"_blank\">#".$rs[0]." (".$rs[1].")</a>&nbsp;: ";
            displayDir("files/".$rs[0]."/");
        }
    }

    echo "</fieldset>";
    
    
echo "</div>";


/*
██╗   ██╗████████╗██╗██╗     ██╗███████╗ █████╗ ████████╗██╗ ██████╗ ███╗   ██╗
██║   ██║╚══██╔══╝██║██║     ██║██╔════╝██╔══██╗╚══██╔══╝██║██╔═══██╗████╗  ██║
██║   ██║   ██║   ██║██║     ██║███████╗███████║   ██║   ██║██║   ██║██╔██╗ ██║
██║   ██║   ██║   ██║██║     ██║╚════██║██╔══██║   ██║   ██║██║   ██║██║╚██╗██║
╚██████╔╝   ██║   ██║███████╗██║███████║██║  ██║   ██║   ██║╚██████╔╝██║ ╚████║
 ╚═════╝    ╚═╝   ╚═╝╚══════╝╚═╝╚══════╝╚═╝  ╚═╝   ╚═╝   ╚═╝ ╚═════╝ ╚═╝  ╚═══╝
*/





/* ########### Si des modifications dans la partie Utilisation ########### */
if ( isset($_POST["utilisation_valid"]) ) {

    $arr = array("utilisateur", "plus_utilisateur_prenom", "plus_utilisateur_nom", "plus_utilisateur_mail", "plus_utilisateur_phone", "localisation", "plus_localisation_bat", "plus_localisation_piece", "sortie", "raison_sortie", "plus_raison_sortie_nom", "integration");
    foreach ($arr as &$value) {
        $$value= isset($_POST[$value]) ? htmlentities($_POST[$value]) : "" ;
    }


    if ($utilisateur=="plus_utilisateur") {
        $plus_utilisateur_nom=mb_strtoupper($plus_utilisateur_nom);
        $plus_utilisateur_phone=phone_display("$plus_utilisateur_phone","");
        mysql_query ("INSERT INTO utilisateur (utilisateur_nom, utilisateur_prenom, utilisateur_mail, utilisateur_phone) VALUES ('".$plus_utilisateur_nom."', '".$plus_utilisateur_prenom."','".$plus_utilisateur_mail."','".$plus_utilisateur_phone."') ; ");
        /* TODO : prévoir le cas où le contrat existe déjà */
        $query_table_utilisateurnew = mysql_query ("SELECT utilisateur_index FROM utilisateur ORDER BY utilisateur_index DESC LIMIT 1 ;");
        while ($l = mysql_fetch_row($query_table_utilisateurnew)) $utilisateur=$l[0];
        // on ajoute cette entrée dans le tableau des utilisateurs (utilisé pour le select)
        $utilisateurs[$utilisateur]=array( $utilisateur, utf8_encode($plus_utilisateur_nom), utf8_encode($plus_utilisateur_prenom), utf8_encode($plus_utilisateur_mail), phone_display("$plus_utilisateur_phone",".") );
    }


    if ($localisation=="plus_localisation") {
        mysql_query ("INSERT INTO localisation (localisation_batiment, localisation_piece) VALUES ('".$plus_localisation_bat."', '".$plus_localisation_piece."' ); ");
        /* TODO : prévoir le cas où le contrat existe déjà */
        $query_table_localisationnew = mysql_query ("SELECT localisation_index FROM localisation ORDER BY localisation_index DESC LIMIT 1 ;");
        while ($l = mysql_fetch_row($query_table_localisationnew)) $localisation=$l[0];
        // on ajoute cette entrée dans le tableau des localisations (utilisé pour le select)
        $localisations[$localisation]=array( $localisation, utf8_encode($plus_localisation_bat), utf8_encode($plus_localisation_piece) );
    }


    if ($raison_sortie=="plus_raison_sortie") {
        mysql_query ("INSERT INTO raison_sortie (raison_sortie_nom) VALUES ('".$plus_raison_sortie_nom."'); ");
        /* TODO : prévoir le cas où le contrat existe déjà */
        $query_table_raisonnew = mysql_query ("SELECT raison_sortie_index FROM raison_sortie ORDER BY raison_sortie_index DESC LIMIT 1 ;");
        while ($l = mysql_fetch_row($query_table_raisonnew)) $raison_sortie=$l[0];
        // on ajoute cette entrée dans le tableau des raisons de sortie (utilisé pour le select)
        $raison_sorties[$raison_sortie]=array($raison_sortie,utf8_encode($plus_raison_sortie_nom));

    }

$raison_sortie = ($sortie==0) ? "0" : $raison_sortie ;


    mysql_query ("UPDATE base SET utilisateur='".$utilisateur."', localisation='".$localisation."', sortie='".$sortie."', integration='".$integration."', raison_sortie='".$raison_sortie."' WHERE base.base_index = $i;" );

    // Avant d’afficher on doit ajouter les nouvelles infos dans les array concernés…
    $data["utilisateur"]=$utilisateur;
    $data["localisation"]=$localisation;
    $data["sortie"]=$sortie;
    $data["raison_sortie"] = $raison_sortie ;
    $data["integration"]=$integration;

}








echo "<div id=\"bloc\" style=\"background:#a9bbcf; vertical-align:top;\">";

    echo "<h1>Utilisation</h1>";

    echo "<form method=\"post\" action=\"?i=$i\">";

    echo "<fieldset><legend>Utilisation</legend>";

        /* ########### utilisateur ########### */
        echo "<label for=\"utilisateur\">Utilisateur : </label>\n";
        echo "<select name=\"utilisateur\" onchange=\"display(this,'plus_utilisateur','plus_utilisateur');\" id=\"utilisateur\">";
        echo "<option value=\"0\" "; if ($data["utilisateur"]=="0") echo "selected"; echo ">— Aucun utilisateur spécifié —</option>"; 
        option_selecteur($data["utilisateur"], $utilisateurs, "2");
        echo "<option value=\"plus_utilisateur\" "; if ($data["utilisateur"]=="plus_utilisateur") echo "selected"; echo ">Nouvel utilisateur :</option>";
        echo "</select><br/>";
        
        
               
            /* ########### + utilisateur ########### */
            echo "\n\n\n";
            echo "<fieldset id=\"plus_utilisateur\" class=\"subfield\" style=\"display: none;\"><legend class=\"subfield\">Nouvel utilisateur</legend>";
                echo "<label for=\"plus_utilisateur_prenom\">Prénom :</label>\n";
                echo "<input value=\"\" name=\"plus_utilisateur_prenom\" type=\"text\">\n";

                echo "<label for=\"plus_utilisateur_nom\">Nom :</label>\n";
                echo "<input value=\"\" name=\"plus_utilisateur_nom\" type=\"text\">\n";
                
                echo "<label for=\"plus_utilisateur_mail\">Mail :</label>\n";
                echo "<input value=\"\" name=\"plus_utilisateur_mail\" type=\"text\">\n";
                
                echo "<label for=\"plus_utilisateur_phone\">Téléphone :</label>\n";
                echo "<input value=\"\" name=\"plus_utilisateur_phone\" type=\"text\">\n";

            echo "</fieldset>";
            echo "\n\n\n";
            

        /* ########### localisation ########### */
        echo "<label for=\"localisation\">Localisation : </label>\n";
        echo "<select name=\"localisation\" onchange=\"display(this,'plus_localisation','plus_localisation');\" > id=\"localisation\"";
        echo "<option value=\"0\" "; if ($data["localisation"]=="0") echo "selected"; echo ">— Aucune localisation spécifiée —</option>"; 
        option_selecteur($data["localisation"], $localisations, "2");
        echo "<option value=\"plus_localisation\" "; if ($data["localisation"]=="plus_localisation") echo "selected"; echo ">Nouvelle localisation :</option>";
        echo "</select>";

        echo " <abbr title=\"le ".dateformat($data["date_localisation"],"fr")."\"><strong>ⓘ</strong></abbr>";


            /* ########### + localisation ########### */
            echo "\n\n\n";
            echo "<fieldset id=\"plus_localisation\" class=\"subfield\" style=\"display: none;\"><legend class=\"subfield\">Nouvelle localisation</legend>";
                echo "<label for=\"plus_localisation_bat\">Bâtiment :</label>\n";
                echo "<input value=\"\" name=\"plus_localisation_bat\" type=\"text\">\n";
                echo "<label for=\"plus_localisation_piece\">Pièce :</label>\n";
                echo "<input value=\"\" name=\"plus_localisation_piece\" type=\"text\">\n";
            echo "</fieldset>";
            echo "\n\n\n";


    echo "</fieldset>";


    echo "<fieldset><legend>Inventaire</legend>";

        /* ########### sortie ########### */
        echo "<label for=\"sortie\">État : </label>\n";
        echo "<select name=\"sortie\" id=\"etat\" onchange=\"hide(this,'0','0');\">";
            echo "<option value=\"0\" "; if ($data["sortie"]=="") echo "selected"; echo ">Inventorié</option>";
            echo "<option value=\"1\" "; if ($data["sortie"]=="1") echo "selected"; echo ">Sortie définitive d’inventaire</option>";
            echo "<option value=\"2\" "; if ($data["sortie"]=="2") echo "selected"; echo ">Sortie temporaire d’inventaire</option>";
        echo "</select>";
        
        if ($data["sortie"]!="0") echo " <abbr title=\"le ".dateformat($data["date_sortie"],"fr")."\"><strong>ⓘ</strong></abbr>"; /* seulement si sortie… !!! */


        /* ########### raison_sortie ########### */
        
        $disp= ($data["sortie"]=="0") ? "none" : "block";
        
        echo "<span id=\"0\" style=\"display:$disp;\">";
        echo "<label for=\"raison_sortie\">Raison de sortie : </label>\n"; /* seulement si sortie… !!! */
        echo "<select name=\"raison_sortie\" onchange=\"display(this,'plus_raison_sortie','plus_raison_sortie');\" id=\"raison_sortie\">";
        echo "<option value=\"0\" "; if ($data["raison_sortie"]=="0") echo "selected"; echo ">— Aucune raison spécifiée —</option>"; 
        option_selecteur($data["raison_sortie"], $raison_sorties);
        echo "<option value=\"plus_raison_sortie\" "; if ($data["raison_sortie"]=="plus_raison_sortie") echo "selected"; echo ">Nouvelle raison :</option>";
        echo "</select>";   
        echo "</span>";



                    /* ########### + raison_sortie ########### */
                    echo "\n\n\n";
                    echo "<fieldset id=\"plus_raison_sortie\" class=\"subfield\" style=\"display: none;\"><legend class=\"subfield\">Nouvellle raison de sortie</legend>";
                        echo "<label for=\"plus_raison_sortie_nom\">Raison :</label>\n";
                        echo "<input value=\"\" name=\"plus_raison_sortie_nom\" type=\"text\">\n";
                    echo "</fieldset>";
                    echo "\n\n\n";
                 
    echo "</fieldset>";   



    echo "<fieldset><legend>Intégration (si le composant est intégré à un autre)</legend>";

        echo "<label for=\"integration\">Intégré dans :</label>\n";

        echo "<select name=\"integration\" id=\"integration\" >";
        echo "<option value=\"0\" "; if ($data["integration"]=="0") echo "selected"; echo ">— Aucune intégration spécifiée —</option>"; 
        option_selecteur($data["integration"], $lab_ids, "2");
        echo "</select>";

        if ($data["integration"]!="0") echo " <a href=\"info.php?i=".$data["integration"]."\"><strong>↗</strong></a>";

    echo "</fieldset>";

    echo "<p style=\"text-align:center;\"><input name='utilisation_valid' value='Enregistrer' type='submit'></p>"; // TODO Ajouter un bouton réinitialiser

    echo "</form>";

echo "</div>";


/*
     ██╗ ██████╗ ██╗   ██╗██████╗ ███╗   ██╗ █████╗ ██╗     
     ██║██╔═══██╗██║   ██║██╔══██╗████╗  ██║██╔══██╗██║     
     ██║██║   ██║██║   ██║██████╔╝██╔██╗ ██║███████║██║     
██   ██║██║   ██║██║   ██║██╔══██╗██║╚██╗██║██╔══██║██║     
╚█████╔╝╚██████╔╝╚██████╔╝██║  ██║██║ ╚████║██║  ██║███████╗
 ╚════╝  ╚═════╝  ╚═════╝ ╚═╝  ╚═╝╚═╝  ╚═══╝╚═╝  ╚═╝╚══════╝
*/

/* ########### POST ########### */
$arr = array("add_historique","del_h_confirm","h");
foreach ($arr as &$value) {
    $$value= isset($_POST[$value]) ? htmlentities($_POST[$value]) : "" ;
}


/* ########### Modifications SQL ########### */

// ajout d’une entrée dans l’historique
if ($add_historique=="Ajouter") {
    $arr = array("date_info", "histo");
    foreach ($arr as &$value) {
        $$value= isset($_POST[$value]) ? htmlentities($_POST[$value]) : "" ;
    }
    $query_do_i_insert_histo = mysql_query ("SELECT historique_index FROM historique WHERE historique_date=\"".dateformat($date_info,"en")."\" AND historique_texte=\"".$histo."\" AND historique_id=\"".$i."\";");
    if (!isset(mysql_fetch_row($query_do_i_insert_histo)[0]) ) {
        mysql_query ("INSERT INTO historique (historique_index, historique_date, historique_texte, historique_id) VALUES (NULL, \"".dateformat($date_info,"en")."\", \"".$histo."\", \"".$i."\"); ");
    }
}

// Suppression d’une entrée dans l’historique
if ($del_h_confirm=="Confirmer la suppression") {
    mysql_query ("DELETE FROM historique WHERE historique_index=$h AND historique_id=$i;");
    // TODO ajouter l’information effacée dans trash ? avec l’ip et l’heure ?
}


// Array historique
$query_table_historique = mysql_query ("SELECT * FROM historique WHERE historique_id=$i ORDER BY historique_date DESC, historique_index DESC ;");
$historique = array();
while ($l = mysql_fetch_row($query_table_historique)) {
    $historique[$l[0]]=array($l[0],$l[1],utf8_encode($l[2]));
}


echo "<div id=\"bloc\" style=\"background:#ad7fa8; vertical-align:top;\">";

    echo "<h1>Journal</h1>";
    
    echo "<form method=\"post\" action=\"?i=$i\">";

    echo "<fieldset><legend>Nouvelle information</legend>";

        echo "<label for=\"date_info\">Date <abbr title=\"JJ/MM/AAAA\"><strong>ⓘ</strong></abbr>:</label>\n";
        echo "<input value=\"".date("d/m/Y")."\" name=\"date_info\" type=\"date\" id=\"date_info\"/>";
        
        echo "<label for=\"histo\" style=\"vertical-align: top;\"> Information :</label>\n";
        echo "<textarea name=\"histo\" rows=\"4\" cols=\"33\"></textarea><br/>";
        echo "<input name='add_historique' value='Ajouter' type='submit'>";

    echo "</fieldset>";


    echo "<fieldset><legend>Historique</legend>";

    echo "<table style=\"border:none;\">";
    foreach ($historique as $h) {
        echo "<tr>";
        echo "<td style=\"padding-right: 10px; vertical-align: top;\"><strong>".dateformat($h[1],"fr")."</strong></td>";
        echo "<td>".$h[2]."</td>";
        echo "<td style=\"text-align:right;\"><span id=\"linkbox\" onclick=\"TINY.box.show({url:'del_confirm.php?i=$i&h=".$h[0]."',width:280,height:110})\" title=\"supprimer cette entrée (".$h[0].") du journal\">×<span></td>";
        echo "</tr>";
    }
    echo "</table>";
    
    
    echo "</fieldset>";

    echo "</form>";

echo "</div>";


/*
████████╗ █████╗  ██████╗ ███████╗
╚══██╔══╝██╔══██╗██╔════╝ ██╔════╝
   ██║   ███████║██║  ███╗███████╗
   ██║   ██╔══██║██║   ██║╚════██║
   ██║   ██║  ██║╚██████╔╝███████║
   ╚═╝   ╚═╝  ╚═╝ ╚═════╝ ╚══════╝
*/

/* ########### POST ########### */
$arr = array("plus_tags","tags_save");
foreach ($arr as &$value) {
    $$value= isset($_POST[$value]) ? htmlentities($_POST[$value]) : "" ;
}


/* ########### Array ########### */
// $tags_list
$query_table_tags_list = mysql_query ("SELECT * FROM tags_list WHERE tags_list_index!=0 ORDER BY tags_list_nom ASC ;");
$tags = array();
while ($l = mysql_fetch_row($query_table_tags_list)) {
    $tags[$l[0]]=array($l[0],utf8_encode($l[1]));
}
// tags_i les tags de $i
$query_table_tag_i = mysql_query ("SELECT * FROM tags WHERE tags_id=$i ;");
$tags_i = array();
while ($l = mysql_fetch_row($query_table_tag_i)) {
    $tags_i[$l[0]]=array($l[0],$l[1]);
}



/* ########### Modifications SQL ########### */
if ($tags_save=="Enregistrer les modifications de tags") {
    // Supprimer tous les tags de cette entrée pour réinitialiser
    mysql_query ("DELETE FROM tags WHERE tags_id=$i;");


    // Cases cochées
    if ($tags_save=="Enregistrer les modifications de tags") {
        // ajouter tous les tags cochés de cette entrée
        $alltags="";
        foreach ($tags as &$t) {
            $alltags.= (isset($_POST["tag".$t[0].""])) ? htmlentities("(".$t[0].",$i),") : "" ;
        }
        $alltags=substr($alltags, 0, -1); // suppression du dernier caractère
        mysql_query ("INSERT INTO tags (tags_index, tags_id) VALUES $alltags ; ");
    }



    if ($plus_tags!="") {
    // TODO : une page administration permettant de supprimer des tags ou d’en fusionner,…
        
        // Nouveaux tags dans tags_list
        $new_tag = explode(',',$plus_tags);
        $allnewtags=""; $allnewtagscomma="";

        foreach ($new_tag as &$nt) {
            $nt= ($nt[0]!=" ") ? $nt : substr($nt,1) ; // suppression du premier caractère si c’est un espace
            
            if ( in_array_r($nt,$tags) ) { // Recherche si le tag est déjà dans $tags
                $allnewtagscomma.= "'$nt',";
            }
            else { // si le tag n’existe pas déjà, on l’ajoute dans la liste des tags à créer
                $allnewtags.= "(NULL,'$nt'),";
                $allnewtagscomma.= "'$nt',";
            }
        }
        
        $allnewtags=substr($allnewtags, 0, -1); // suppression du dernier caractère
        $allnewtagscomma=substr($allnewtagscomma, 0, -1); // suppression du dernier caractère
        
        if ($allnewtagscomma!="") {
            mysql_query("INSERT INTO tags_list (tags_list_index, tags_list_nom) VALUES $allnewtags ;");
            
            // Nouveaux tags dans tags de $i
            $allnewtags_index="";
            // tagnew_i les tags de $i
            $query_table_tagnew_i = mysql_query ("SELECT tags_list_index FROM tags_list WHERE tags_list_nom IN ($allnewtagscomma) ;");
            
            while ($nti = mysql_fetch_row($query_table_tagnew_i)) {
                $allnewtags_index.= "('".$nti[0]."','$i')," ;
            }
            $allnewtags_index=substr($allnewtags_index, 0, -1); // suppression du dernier caractère
            mysql_query ("INSERT INTO tags (tags_index, tags_id) VALUES $allnewtags_index ; ");
        }

    }


    /* ########### Avant d’afficher les cases on refait la requête sql car il y a peut-être eu des modifs… ########### */
    
    // TODO : plutot que refaire les array il suffit de rajouter les nouveaux dedans avec array_push
    
    /* ########### Array ########### */
    // $tags_list
    $query_table_tags_list = mysql_query ("SELECT * FROM tags_list WHERE tags_list_index!=0 ORDER BY tags_list_nom ASC ;");
    $tags = array();
    while ($l = mysql_fetch_row($query_table_tags_list)) {
        $tags[$l[0]]=array($l[0],utf8_encode($l[1]));
    }
    // tags_i les tags de $i
    $query_table_tag_i = mysql_query ("SELECT * FROM tags WHERE tags_id=$i ;");
    $tags_i = array();
    while ($l = mysql_fetch_row($query_table_tag_i)) {
        $tags_i[$l[0]]=array($l[0],$l[1]);
    }


}

echo "<div id=\"bloc\" style=\"background:#e9b96e; vertical-align:top;\">";

    echo "<h1>Tags</h1>";
    
    echo "<form method=\"post\" action=\"?i=$i\">";
    
    echo "<fieldset id=\"tags\"><legend>Tags</legend>";

    echo "<ul>";
        foreach ($tags as $v) {
            echo "<li class=\"inline\">";
            echo "<input type=\"checkbox\" name=\"tag".$v[0]."\" value=\"1\"";
            if (isset($tags_i[$v[0]])) echo " checked ";
            echo "> ".$v[1]."";
            echo "</li>";
        }
    echo "</ul>";

    echo "</fieldset>";
    /* ########### + tags ########### */
    echo "\n\n\n";
    echo "<fieldset id=\"plus_tags\"><legend>Tags supplémentaires</legend>";
        echo "<label for=\"plus_tags\">Nouveaux tags <abbr title=\"séparés d’une virgule\"><strong>ⓘ</strong></abbr> :</label>\n";
        echo "<input value=\"\" name=\"plus_tags\" type=\"text\">\n";
    echo "</fieldset>";
    echo "\n\n\n";

    echo "<p style=\"text-align:center;\"><input name='tags_save' value='Enregistrer les modifications de tags' type='submit'></p>"; // TODO Ajouter un bouton réinitialiser

    echo "</form>";

echo "</div>";


// end container
echo "</div>";

?>


</body>
</html>
