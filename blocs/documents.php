<?php
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



/*
███████╗ ██████╗ ██████╗ ███╗   ███╗██╗   ██╗██╗      █████╗ ██╗██████╗ ███████╗
██╔════╝██╔═══██╗██╔══██╗████╗ ████║██║   ██║██║     ██╔══██╗██║██╔══██╗██╔════╝
█████╗  ██║   ██║██████╔╝██╔████╔██║██║   ██║██║     ███████║██║██████╔╝█████╗  
██╔══╝  ██║   ██║██╔══██╗██║╚██╔╝██║██║   ██║██║     ██╔══██║██║██╔══██╗██╔══╝  
██║     ╚██████╔╝██║  ██║██║ ╚═╝ ██║╚██████╔╝███████╗██║  ██║██║██║  ██║███████╗
╚═╝      ╚═════╝ ╚═╝  ╚═╝╚═╝     ╚═╝ ╚═════╝ ╚══════╝╚═╝  ╚═╝╚═╝╚═╝  ╚═╝╚══════╝
*/
echo "<div id=\"bloc\" style=\"background:rgb(245, 214, 197); vertical-align:top;\">";

    echo "<h1>Documents</h1>";

/*  ╔═╗ ╦╔═╗╦ ╦╔╦╗╔═╗╦═╗  ╦ ╦╔╗╔  ╔═╗╦╔═╗╦ ╦╦╔═╗╦═╗
    ╠═╣ ║║ ║║ ║ ║ ║╣ ╠╦╝  ║ ║║║║  ╠╣ ║║  ╠═╣║║╣ ╠╦╝
    ╩ ╩╚╝╚═╝╚═╝ ╩ ╚═╝╩╚═  ╚═╝╝╚╝  ╚  ╩╚═╝╩ ╩╩╚═╝╩╚═ */
    echo "<fieldset><legend>Ajouter un fichier</legend>";
        if ($write) echo "<form method=\"post\" action=\"?i=$i\" enctype=\"multipart/form-data\">";
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

            if(in_array($file_ext,$extensions)=== false) $errors[]="Extension non permise.";
            if ( ($file_size > $max_size)||($file_size == 0) ) $errors[]="La taille du fichier doit être au maximum de ".formatBytes($max_size)."o.";

            if(empty($errors)==true) {
                move_uploaded_file($file_tmp,"/var/www/files/$i/".$file_name);
                echo "Fichier envoyé avec succès.<br/>";
            }
            else foreach ($errors as $e) echo "<p><strong>$e</strong></p>";
        }
        /* ########### END Ajout d’un fichier ########### */

        if ($write) echo "<input name=\"Valider\" value=\"Envoyer\" type=\"submit\" class=\"little_button\" />";
        if ($write) echo "</form>";

    echo "</fieldset>";

/*  ╔═╗╦╔═╗╦ ╦╦╔═╗╦═╗╔═╗
    ╠╣ ║║  ╠═╣║║╣ ╠╦╝╚═╗
    ╚  ╩╚═╝╩ ╩╩╚═╝╩╚═╚═╝  */
    echo "<fieldset><legend>Fichiers</legend>";
        displayDir("files/$i/", $del=$write);
    echo "</fieldset>";


/*  ╦═╗╔═╗╔═╗╔═╗╦═╗╔═╗╔╗╔╔═╗╔═╗╔═╗  ╔═╗╦╔╦╗╦ ╦  ╔═╗╦╦═╗╔═╗╔═╗
    ╠╦╝║╣ ╠╣ ║╣ ╠╦╝║╣ ║║║║  ║╣ ╚═╗  ╚═╗║║║║║ ║  ╠═╣║╠╦╝║╣ ╚═╗
    ╩╚═╚═╝╚  ╚═╝╩╚═╚═╝╝╚╝╚═╝╚═╝╚═╝  ╚═╝╩╩ ╩╩ ╩═╝╩ ╩╩╩╚═╚═╝╚═╝ */
    echo "<fieldset><legend>Fichiers de référence similaire</legend>";

    // Array references_similaires
    $query_table_reference_similaire = mysql_query ("SELECT base_index, lab_id FROM base WHERE reference=\"".$data["reference"]."\" AND marque=".$data["marque"]." AND categorie=".$data["categorie"]." AND base_index!=$i ORDER BY base_index ASC ;");
    $references_similaires = array();
    while ($l = mysql_fetch_row($query_table_reference_similaire)) {
        $references_similaires[$l[0]]=array($l[0], utf8_encode($l[1]));
    }


    if ( (!$references_similaires) || ($data["reference"]=="") || ($data["marque"]=="0") || ($data["categorie"]=="0") )echo "Aucune référence correspondante trouvée";
    else {
        foreach ($references_similaires as $rs) {
            echo "<a href=\"info.php?i=".$rs[0]."\" target=\"_blank\">#".$rs[0]." (".$rs[1].")</a>&nbsp;: ";
            displayDir("files/".$rs[0]."/");
        }
    }

    echo "</fieldset>";
    
echo "</div>";

?>
