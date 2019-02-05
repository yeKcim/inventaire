<?php
require_once("./0_connect.php");
if ($database=="") require_once("./0_baseselector.php");
require_once("./0_connect_db.php");
require_once("./0_tables_sql_commun.php");

/* ########### GET ########### */
$arr = array("i", "f", "dir", "BASE");
foreach ($arr as &$value) {
    $$value= isset($_GET[$value]) ? htmlentities($_GET[$value]) : "" ;
}

$quick= ( isset($_GET["quick_page"]) ) ? "quick_page=".$_GET["quick_page"]."&quick_name=".$_GET["quick_name"]."&" : "";


/*
███████╗██╗ ██████╗██╗  ██╗██╗███████╗██████╗
██╔════╝██║██╔════╝██║  ██║██║██╔════╝██╔══██╗
█████╗  ██║██║     ███████║██║█████╗  ██████╔╝
██╔══╝  ██║██║     ██╔══██║██║██╔══╝  ██╔══██╗
██║     ██║╚██████╗██║  ██║██║███████╗██║  ██║
╚═╝     ╚═╝ ╚═════╝╚═╝  ╚═╝╚═╝╚══════╝╚═╝  ╚═╝
*/
if ($f!="") {
    echo "<form method=\"post\" action=\"?".$quick."i=".$i."&BASE=".$BASE."\">";
        echo "<p style=\"text-align:center\"><strong>Nouveau nom :</strong></p>";
        echo "<p style=\"text-align:center\"><input name='newname' value=\"".$f."\" type='text'\" size=\"32\" pattern=\"^[A-Za-z0-9_-.]{1,32}$\"/> </p>";
        echo "<input name='oldname' value=\"".$f."\" type='hidden'\">";
        echo "<input name='dir' value=\"".$dir."\" type='hidden'\">";
        echo "<p style=\"text-align:center\"><input name='mv_f_confirm' value='Renommer' type='submit'\"> </p>";
    echo "</form>";
}



?>
