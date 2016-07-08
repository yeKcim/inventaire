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
    
<!-- ╦╔═╗ ╦ ╦╔═╗╦═╗╦ ╦
     ║║═╬╗║ ║║╣ ╠╦╝╚╦╝
    ╚╝╚═╝╚╚═╝╚═╝╩╚═ ╩   -->
    <script src="jquery.min.js" type="text/javascript"></script>
    
<!--╔╦╗╦╔╗╔╦ ╦╔╗ ╔═╗═╗ ╦
     ║ ║║║║╚╦╝╠╩╗║ ║╔╩╦╝
     ╩ ╩╝╚╝ ╩ ╚═╝╚═╝╩ ╚═
     tinybox pour les popup layout -->
    <script type="text/javascript" src="tinybox/tinybox.js"></script>
    <link rel="stylesheet" href="tinybox/tinybox.css" />
    
<!--╔╦╗╦═╗╔═╗╔═╗╔═╗╔═╗╔╗╔╔═╗
     ║║╠╦╝║ ║╠═╝╔═╝║ ║║║║║╣ 
    ═╩╝╩╚═╚═╝╩  ╚═╝╚═╝╝╚╝╚═╝
    dropzone pour drag&drop uploader    -->
    <script src="dropzone/dropzone.js"></script>
    <link rel="stylesheet" href="dropzone/dropzone.css">
    

<!--╔╦╗╦╔═╗╔═╗╦  ╔═╗╦ ╦   ╦ ╦╦╔╦╗╔═╗
     ║║║╚═╗╠═╝║  ╠═╣╚╦╝ █ ╠═╣║ ║║║╣ 
    ═╩╝╩╚═╝╩  ╩═╝╩ ╩ ╩    ╩ ╩╩═╩╝╚═╝    -->
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
    
<!--╔═╗╦ ╦╔═╗╔═╗╔═╗╔═╗╔╗╔     ╔╦╗╦ ╦╦ ╔╦╗╦  ╔═╗╔═╗╦  ╔═╗╔═╗╔╦╗
    ║  ╠═╣║ ║║ ║╚═╗║╣ ║║║  o  ║║║║ ║║  ║ ║  ╚═╗║╣ ║  ║╣ ║   ║ 
    ╚═╝╩ ╩╚═╝╚═╝╚═╝╚═╝╝╚╝  o  ╩ ╩╚═╝╩═╝╩ ╩  ╚═╝╚═╝╩═╝╚═╝╚═╝ ╩   -->
    <script src="chosen/chosen.jquery.js" type="text/javascript"></script>
    <link rel="stylesheet" href="chosen/chosen.css">

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
    require_once("./blocs/administratif.php");
    require_once("./blocs/technique.php");
    require_once("./blocs/caracteristiques.php");
    require_once("./blocs/documents.php");
    require_once("./blocs/utilisation.php");
    require_once("./blocs/journal.php");
    //require_once("./blocs/entretien.php");
echo "</div>";

?>


</body>
</html>
