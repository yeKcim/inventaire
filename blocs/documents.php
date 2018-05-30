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

/* TODO : Vérifications envoi de fichiers semble défectueux */

$max_size=file_upload_max_size();
/* HOW-TO:
To modify $max_size edit/add /etc/php/7.1/apache2/conf.d/00-user.ini:
	upload_max_filesize = 15M
	post_max_size = 15M
sudo service apache2 restart
*/

/* ########### POST ########### */
$arr = array("del_f_confirm","f","filetoref");
foreach ($arr as &$value) {
    $$value= isset($_POST[$value]) ? htmlentities($_POST[$value]) : "" ;
}

/* ########### Suppression d’un fichier ########### */
if ($del_f_confirm=="Confirmer la suppression") {
    // Si le dossier trash n’existe pas, on le crée
    if (!file_exists("$trash")) mkdir("$trash", 0775);
    $nomdel=date("Ymdhms")."-".str_replace('/', "_", $f);
    rename("$f","$trash/$nomdel");
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

        /* ########### Form ########### */
        $quick= ( isset($_GET["quick_page"]) ) ? "&quick_page=".$_GET["quick_page"]."&quick_name=".$_GET["quick_name"]."" : "";
        if ($write) echo "<form method=\"post\" action=\"?BASE=$database&i=".$i."".$quick."\" enctype=\"multipart/form-data\">";

        echo "<input value=\"".$data[0]["base_index"]."\" name=\"i\" type=\"hidden\">\n";
        /* echo "<form action=\"$racine$dir\" class=\"dropzone\"></form>";*/
        echo "<p>Extensions autorisées : ";
        foreach ($extensions as $e) echo "$e ";
        echo "<br/>";
        echo "Taille maximum : ".formatBytes($max_size)."o.<br/>";

        echo "<input type=\"file\" name=\"fichier\" style=\"border:0px solid #cc0000;\"/><br/>";

        if ( ($data[0]["reference"]!="")&&($data[0]["marque"]!="0") )
            echo "<br/><input type=\"checkbox\" name=\"filetoref\" value=\"1\"> Fichier global lié à la référence fabricant.<br/>";
        else echo "<br/><em>Vous pouvez uniquement envoyer un document lié à cette entrée. Pour envoyer un fichier global lié à la référence constructeur il est nécessaire de renseigner la marque et la référence fabricant.</em><br/>";
        echo "</p>";

        /* ########### Ajout d’un fichier ########### */
        if(isset($_FILES['fichier'])){
            $errors= array();
            $file_name = $_FILES['fichier']['name'];
            $file_size =$_FILES['fichier']['size'];
            $file_tmp =$_FILES['fichier']['tmp_name'];
            $file_type=$_FILES['fichier']['type'];
            $file_ext=strtolower(end(explode('.',$_FILES['fichier']['name'])));

            if(in_array($file_ext,$extensions)== false) $errors[]="Extension non permise.";
            if ( ($file_size > $max_size)||($file_size == 0) ) $errors[]="La taille du fichier doit être au maximum de ".formatBytes($max_size)."o.";

            if(empty($errors)==true) {

                if ($filetoref!="" ) {
					$keys = array_keys(array_column($marques, 'marque_index'), $data[0]["marque"]);
					$m=str_replace('/', "_", $marques[$keys[0]]["marque_nom"]);
		            $r=str_replace('/', "_", $data[0]["reference"]);
					$dossier=$dossierdesfichiers.$database."/".$m."-".$r;
		}
                else $dossier=$dossierdesfichiers.$database."/".$i;

                $dossier=str_replace("&", "&amp;", $dossier);
                move_uploaded_file($file_tmp,"$dossier/".$file_name);
                //echo "Fichier envoyé avec succès.<br/>";
                echo "<p class=\"success_message\" id=\"disappear_delay\">Fichier envoyé avec succès.</p>";
            }
            else foreach ($errors as $e) echo "<p class=\"error_message\" id=\"disappear_delay\"><strong>$e</strong></p>";
        }
        /* ########### END Ajout d’un fichier ########### */

        if ($write) echo "<p style=\"text-align:center;\"><input name=\"Valider\" value=\"Envoyer\" type=\"submit\" class=\"little_button\" /></p>";
        if ($write) echo "</form>";

    echo "</fieldset>";

/*  ╔═╗╦╔═╗╦ ╦╦╔═╗╦═╗╔═╗
    ╠╣ ║║  ╠═╣║║╣ ╠╦╝╚═╗
    ╚  ╩╚═╝╩ ╩╩╚═╝╩╚═╚═╝  */
    echo "<fieldset><legend>Fichiers de cette entrée</legend>";
        displayDir($database, $i, "$dossierdesfichiers$database/$i/", $del=$write);
    echo "</fieldset>";

    $keys = array_keys(array_column($marques, 'marque_index'), $data[0]["marque"]);
    echo "<fieldset><legend>Fichiers globaux liés à la référence constructeur</legend>";
    if ( ($data[0]["reference"]!="")&&($data[0]["marque"]!="0") ) {
       	$m=str_replace('/', "_", $marques[$keys[0]]["marque_nom"]);
	    $r=str_replace('/', "_", $data[0]["reference"]);
        displayDir($database, $i, "".$dossierdesfichiers."".$database."/".$m."-".$r."/", $del=$write);
    }
    else echo "Vous devez renseigner « Marque » et « Référence fabriquant » pour activer cette fonction.";
    echo "</fieldset>";


/*  ╦═╗╔═╗╔═╗╔═╗╦═╗╔═╗╔╗╔╔═╗╔═╗╔═╗  ╔═╗╦╔╦╗╦ ╦  ╔═╗╦╦═╗╔═╗╔═╗
    ╠╦╝║╣ ╠╣ ║╣ ╠╦╝║╣ ║║║║  ║╣ ╚═╗  ╚═╗║║║║║ ║  ╠═╣║╠╦╝║╣ ╚═╗
    ╩╚═╚═╝╚  ╚═╝╩╚═╚═╝╝╚╝╚═╝╚═╝╚═╝  ╚═╝╩╩ ╩╩ ╩═╝╩ ╩╩╩╚═╚═╝╚═╝ */
    echo "<fieldset><legend>Fichiers de référence similaire</legend>";

    // Array references_similaires
    $sth = $dbh->query("SELECT base_index, lab_id FROM base WHERE reference=\"".$data[0]["reference"]."\" AND marque=".$data[0]["marque"]." AND categorie=".$data[0]["categorie"]." AND base_index!=$i ORDER BY base_index ASC ;");
    $references_similaires = ($sth) ? $sth->fetchAll(PDO::FETCH_ASSOC) : FALSE ;
    if ( (!$references_similaires) || ($data[0]["reference"]=="") || ($data[0]["marque"]=="0") || ($data[0]["categorie"]=="0") )echo "Aucune référence correspondante trouvée";
    else {
        echo "<table id=\"simreffiles\">";
        echo "<thead><tr><th style=\"text-align:left\">Ref</th><th style=\"text-align:left\">Fichiers</th></tr></thead>";
        foreach ($references_similaires as $rs) {
            echo "<tr>";
            echo "<td width=\"20%\"><a href=\"info.php?i=".$rs["base_index"]."\" target=\"_blank\">";
            if ($rs["lab_id"]=="") echo "#".$rs["base_index"].""; else echo "<span title=\"#".$rs["base_index"]."\">".$rs["lab_id"]."</span>";
            echo "</a></td><td>";
            $ddir=display_dir_compact("$dossierdesfichiers$database/".$rs["base_index"]."/");
            if ($ddir) echo display_dir_compact("$dossierdesfichiers$database/".$rs["base_index"]."/"); else echo "Aucun fichier";
            echo "</td></tr>";
        }
        echo "</table>";
        echodatatables("simreffiles","5");
    }
    echo "</fieldset>";

echo "</div>";

?>
