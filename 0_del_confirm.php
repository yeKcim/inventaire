<?php
require_once("./0_connect.php");
if ($database=="") require_once("./0_baseselector.php");
require_once("./0_connect_db.php");
require_once("./0_tables_sql_commun.php");

/* ########### GET ########### */
$arr = array("i", "h", "f", "e");
foreach ($arr as &$value) {
    $$value= isset($_GET[$value]) ? htmlentities($_GET[$value]) : "" ;
}

$quick= ( isset($_GET["quick_page"]) ) ? "quick_page=".$_GET["quick_page"]."&quick_name=".$_GET["quick_name"]."&" : "";

/*
██╗  ██╗██╗███████╗████████╗ ██████╗ ██████╗ ██╗ ██████╗ ██╗   ██╗███████╗
██║  ██║██║██╔════╝╚══██╔══╝██╔═══██╗██╔══██╗██║██╔═══██╗██║   ██║██╔════╝
███████║██║███████╗   ██║   ██║   ██║██████╔╝██║██║   ██║██║   ██║█████╗
██╔══██║██║╚════██║   ██║   ██║   ██║██╔══██╗██║██║▄▄ ██║██║   ██║██╔══╝
██║  ██║██║███████║   ██║   ╚██████╔╝██║  ██║██║╚██████╔╝╚██████╔╝███████╗
╚═╝  ╚═╝╚═╝╚══════╝   ╚═╝    ╚═════╝ ╚═╝  ╚═╝╚═╝ ╚══▀▀═╝  ╚═════╝ ╚══════╝
*/
if ($h!="") {
    echo "<form method=\"post\" action=\"?".$quick."i=".$i."\">";
        echo "<p style=\"text-align:center\"><strong>Vous êtes sur le point de supprimer une entrée du journal. Attention, une fois confirmée, la suppression est définitive.</strong></p>";
        echo "<p style=\"text-align:center\"><input name='del_h_confirm' value='Confirmer la suppression' type='submit'\"> <input name='del_h_confirm' value='Annuler' type='submit'\"></p>";
        echo "<input value=\"".$h."\" name=\"h\" type=\"hidden\">\n";
	echo "<input id=\"BASE\" name=\"BASE\" type=\"hidden\" value=\"$database\">";
    echo "</form>";
}


/*
███████╗██╗ ██████╗██╗  ██╗██╗███████╗██████╗
██╔════╝██║██╔════╝██║  ██║██║██╔════╝██╔══██╗
█████╗  ██║██║     ███████║██║█████╗  ██████╔╝
██╔══╝  ██║██║     ██╔══██║██║██╔══╝  ██╔══██╗
██║     ██║╚██████╗██║  ██║██║███████╗██║  ██║
╚═╝     ╚═╝ ╚═════╝╚═╝  ╚═╝╚═╝╚══════╝╚═╝  ╚═╝
*/
if ($f!="") {
    echo "<form method=\"post\" action=\"?".$quick."i=".$i."\">";
        echo "<p style=\"text-align:center\"><strong>Vous êtes sur le point de supprimer un fichier. Attention, une fois confirmée, la suppression est définitive.</strong></p>";
        echo "<p style=\"text-align:center\"><input name='del_f_confirm' value='Confirmer la suppression' type='submit'\"> <input name='del_f_confirm' value='Annuler' type='submit'\"></p>";
        echo "<input value=\"".$f."\" name=\"f\" type=\"hidden\">\n";
	echo "<input id=\"BASE\" name=\"BASE\" type=\"hidden\" value=\"$database\">";
    echo "</form>";
}


/*
███████╗███╗   ██╗████████╗██████╗ ███████╗████████╗██╗███████╗███╗   ██╗
██╔════╝████╗  ██║╚══██╔══╝██╔══██╗██╔════╝╚══██╔══╝██║██╔════╝████╗  ██║
█████╗  ██╔██╗ ██║   ██║   ██████╔╝█████╗     ██║   ██║█████╗  ██╔██╗ ██║
██╔══╝  ██║╚██╗██║   ██║   ██╔══██╗██╔══╝     ██║   ██║██╔══╝  ██║╚██╗██║
███████╗██║ ╚████║   ██║   ██║  ██║███████╗   ██║   ██║███████╗██║ ╚████║
╚══════╝╚═╝  ╚═══╝   ╚═╝   ╚═╝  ╚═╝╚══════╝   ╚═╝   ╚═╝╚══════╝╚═╝  ╚═══╝
*/
if ($e!="") {
    echo "<form method=\"post\" action=\"?".$quick."i=".$i."\">";
        echo "<p style=\"text-align:center\"><strong>Vous êtes sur le point de supprimer un entretien. Attention, une fois confirmée, la suppression est définitive.</strong></p>";
        echo "<p style=\"text-align:center\"><input name='del_e_confirm' value='Confirmer la suppression' type='submit'\"> <input name='del_e_confirm' value='Annuler' type='submit'\"></p>";
        echo "<input value=\"".$e."\" name=\"e_del\" type=\"hidden\">\n";
	echo "<input id=\"BASE\" name=\"BASE\" type=\"hidden\" value=\"$database\">";
    echo "</form>";
}

?>
