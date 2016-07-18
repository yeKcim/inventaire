<?php
require_once("./connect.php");
require_once("./tables_sql_commun.php");

/* ########### GET ########### */
$arr = array("i", "h", "f", "e");
foreach ($arr as &$value) {
    $$value= isset($_GET[$value]) ? htmlentities($_GET[$value]) : "" ;
}

/*
██╗  ██╗██╗███████╗████████╗ ██████╗ ██████╗ ██╗ ██████╗ ██╗   ██╗███████╗
██║  ██║██║██╔════╝╚══██╔══╝██╔═══██╗██╔══██╗██║██╔═══██╗██║   ██║██╔════╝
███████║██║███████╗   ██║   ██║   ██║██████╔╝██║██║   ██║██║   ██║█████╗  
██╔══██║██║╚════██║   ██║   ██║   ██║██╔══██╗██║██║▄▄ ██║██║   ██║██╔══╝  
██║  ██║██║███████║   ██║   ╚██████╔╝██║  ██║██║╚██████╔╝╚██████╔╝███████╗
╚═╝  ╚═╝╚═╝╚══════╝   ╚═╝    ╚═════╝ ╚═╝  ╚═╝╚═╝ ╚══▀▀═╝  ╚═════╝ ╚══════╝
*/
if ($h!="") {
    echo "<form method=\"post\" action=\"?i=$i\">";
        echo "<p style=\"text-align:center\"><strong>Vous êtes sur le point de supprimer une entrée du journal. Attention, une fois confirmée, la suppression est définitive.</strong></p>";
        echo "<p style=\"text-align:center\"><input name='del_h_confirm' value='Confirmer la suppression' type='submit'\"> <input name='del_h_confirm' value='Annuler' type='submit'\"></p>";
        echo "<input value=\"".$h."\" name=\"h\" type=\"hidden\">\n";
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
    echo "<form method=\"post\" action=\"?i=$i\">";
        echo "<p style=\"text-align:center\"><strong>Vous êtes sur le point de supprimer un fichier. Attention, une fois confirmée, la suppression est définitive.</strong></p>";
        echo "<p style=\"text-align:center\"><input name='del_f_confirm' value='Confirmer la suppression' type='submit'\"> <input name='del_f_confirm' value='Annuler' type='submit'\"></p>";
        echo "<input value=\"".$f."\" name=\"f\" type=\"hidden\">\n";
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
    echo "<form method=\"post\" action=\"?i=$i\">";
        echo "<p style=\"text-align:center\"><strong>Vous êtes sur le point de supprimer un entretien. Attention, une fois confirmée, la suppression est définitive.</strong></p>";
        echo "<p style=\"text-align:center\"><input name='del_e_confirm' value='Confirmer la suppression' type='submit'\"> <input name='del_e_confirm' value='Annuler' type='submit'\"></p>";
        echo "<input value=\"".$e."\" name=\"e_del\" type=\"hidden\">\n";
    echo "</form>";
}

?>
