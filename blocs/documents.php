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
$arr = array("del_f_confirm","f","filetoref","mv_f_confirm");
foreach ($arr as &$value) {
    $$value= isset($_POST[$value]) ? htmlentities($_POST[$value]) : "" ;
}

$arr = array("move","filename");
foreach ($arr as &$value) {
    $$value= isset($_GET[$value]) ? htmlentities($_GET[$value]) : "" ;
}


/* ########### Renommer un fichier ########### */
if ($mv_f_confirm=="Renommer") {
    $arr = array("newname","oldname","dir");
    foreach ($arr as &$value) {
        $$value= isset($_POST[$value]) ? htmlentities($_POST[$value]) : "" ;
    }
    rename("".html_entity_decode($dir)."/".html_entity_decode($oldname)."", "".html_entity_decode($dir)."/".html_entity_decode($newname)."");
    
}





/* ########### Suppression d’un fichier ########### */
if ($del_f_confirm=="Confirmer la suppression") {
    // Si le dossier trash n’existe pas, on le crée
    if (!file_exists("$trash")) { $umask_bak=umask(0); mkdir("$trash", 0775, true); umask($umask_bak); }
    $nomdel=date("Ymdhms")."-".str_replace('/', "_", $f);
    rename("$f","$trash/$nomdel");
}

if ($move!="") {
    $movefrom=str_replace("$dossierdesfichiers$database/", "", $move);
    if ($movefrom=="$i/") {
        $keys = array_keys(array_column($marques, 'marque_index'), $data[0]["marque"]);
        if ( ($data[0]["reference"]!="")&&($data[0]["marque"]!="0") ) {

            $m=$marques[$keys[0]]["marque_nom"];
            $r=$data[0]["reference"];

            $m=str_replace('/', "_", $m);
            $m=str_replace("&", "amp", $m);
            $m=str_replace(";", "semicolon", $m);

            $r=str_replace('/', "_", $r);
            $r=str_replace("&", "amp", $r);
            $r=str_replace(";", "semicolon", $r);
        }
        $moveto="$m-$r/";
    }
    else $moveto="$i/";
    rename("$dossierdesfichiers$database/$movefrom$filename", "$dossierdesfichiers$database/$moveto$filename");

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

        echo "<input type=\"file\" name=\"fichier[]\" multiple style=\"border:0px solid #cc0000;\"/><br/>";

        if ( ($data[0]["reference"]!="")&&($data[0]["marque"]!="0") ) {
            echo "<br/><input type=\"checkbox\" name=\"filetoref\" value=\"1\"> Fichier global lié à la référence fabricant.<br/>";
            $mv=TRUE;
        }
        else {
            echo "<br/><em>Vous pouvez uniquement envoyer un document lié à cette entrée. Pour envoyer un fichier global lié à la référence constructeur il est nécessaire de renseigner la marque et la référence fabricant.</em><br/>";
            $mv=FALSE;
        }
        echo "</p>";

	/* ########### Ajout de fichiers ########### */
	if(isset($_FILES['fichier'])){
		$errors = array();
		// Vérifie que le répertoire de destination est défini (selon ton code existant)
		if ($filetoref != "") {
		    $keys = array_keys(array_column($marques, 'marque_index'), $data[0]["marque"]);
		    $m = str_replace('/', "_", $marques[$keys[0]]["marque_nom"]);
		    $r = str_replace('/', "_", $data[0]["reference"]);
		    $dossier = $dossierdesfichiers . $database . "/" . $m . "-" . $r;
		} else {
		    $dossier = $dossierdesfichiers . $database . "/" . $i;
		}
		
		// Traitement de chaque fichier
		foreach($_FILES['fichier']['name'] as $key => $file_name){
		    // Récupération des infos de chaque fichier
		    $file_size = $_FILES['fichier']['size'][$key];
		    $file_tmp  = $_FILES['fichier']['tmp_name'][$key];
		    $file_type = $_FILES['fichier']['type'][$key];
		    
		    $parts = explode('.', $file_name);
		    $file_ext = mb_strtolower(end($parts));
		    
		    // Vérification des extensions et taille
		    if(in_array($file_ext, $extensions) == false){
		        $errors[] = "Extension non permise pour le fichier $file_name.";
		        continue; // Passe au fichier suivant
		    }
		    if(($file_size > $max_size) || ($file_size == 0)){
		        $errors[] = "La taille du fichier $file_name doit être au maximum de " . formatBytes($max_size) . "o et non vide";
		        continue; // Passe au fichier suivant
		    }
		    
		    // Préparation du dossier (éventuellement remplacement de certains caractères)
		    $dossier_modifie = str_replace("&", "amp", $dossier);
		    $dossier_modifie = str_replace(";", "semicolon", $dossier_modifie);
		    
		    // Déplacer le fichier
		    if(move_uploaded_file($file_tmp, "$dossier_modifie/" . $file_name)){
		        echo "<p class=\"success_message disappear_delay\" id=\"disappear_delay_".$file_name."\">Fichier $file_name envoyé avec succès.</p>";
		    } else {
		        $errors[] = "Erreur lors de l'envoi du fichier $file_name.";
		    }
		}
		
		// Affichage des erreurs s'il y en a
		if(!empty($errors)){
		    foreach ($errors as $e) {
		        echo "<p class=\"error_message disappear_delay\" id=\"disappear_delay_".$e."\"><strong>$e</strong></p>";
		    }
		}
}
/* ########### END Ajout de fichiers ########### */


        if ($write) echo "<p style=\"text-align:center;\"><input name=\"Valider\" value=\"Envoyer\" type=\"submit\" class=\"little_button\" /></p>";
        if ($write) echo "</form>";

    echo "</fieldset>";

/*  ╔═╗╦╔═╗╦ ╦╦╔═╗╦═╗╔═╗
    ╠╣ ║║  ╠═╣║║╣ ╠╦╝╚═╗
    ╚  ╩╚═╝╩ ╩╩╚═╝╩╚═╚═╝  */
    echo "<fieldset><legend>Fichiers de cette entrée</legend>";
        displayDir($database, $i, "$dossierdesfichiers$database/$i/", $del=$write, $allowmv=$mv);
    echo "</fieldset>";

    $keys = array_keys(array_column($marques, 'marque_index'), $data[0]["marque"]);
    echo "<fieldset><legend>Fichiers globaux liés à la référence constructeur</legend>";
    if ( ($data[0]["reference"]!="")&&($data[0]["marque"]!="0") ) {
       	$m=str_replace('/', "_", $marques[$keys[0]]["marque_nom"]);
	    $r=str_replace('/', "_", $data[0]["reference"]);
        displayDir($database, $i, "".$dossierdesfichiers."".$database."/".$m."-".$r."/", $del=$write, $allowmv=$mv);
    }
    else echo "Vous devez renseigner « Marque » et « Référence fabriquant » pour activer cette fonction.";
    echo "</fieldset>";


/*  ╦═╗╔═╗╔═╗╔═╗╦═╗╔═╗╔╗╔╔═╗╔═╗╔═╗  ╔═╗╦╔╦╗╦ ╦  ╔═╗╦╦═╗╔═╗╔═╗
    ╠╦╝║╣ ╠╣ ║╣ ╠╦╝║╣ ║║║║  ║╣ ╚═╗  ╚═╗║║║║║ ║  ╠═╣║╠╦╝║╣ ╚═╗
    ╩╚═╚═╝╚  ╚═╝╩╚═╚═╝╝╚╝╚═╝╚═╝╚═╝  ╚═╝╩╩ ╩╩ ╩═╝╩ ╩╩╩╚═╚═╝╚═╝ */
    echo "<fieldset><legend>Fichiers de référence similaire</legend>";

    // Array references_similaires
	$sql = "SELECT base_index, lab_id 
		    FROM base 
		    WHERE reference = :reference 
		      AND marque = :marque 
		      AND categorie = :categorie 
		      AND base_index != :i 
		    ORDER BY base_index ASC";
	$sth = $dbh->prepare($sql);
	$sth->execute([
		':reference' => $data[0]["reference"],
		':marque'    => $data[0]["marque"],
		':categorie' => $data[0]["categorie"],
		':i'         => $i
	]);
	$references_similaires = $sth->fetchAll(PDO::FETCH_ASSOC);
	$sth->closeCursor();

	if (!$references_similaires || $data[0]["reference"] == "" || $data[0]["marque"] == "0" || $data[0]["categorie"] == "0") {
		echo "Aucune référence correspondante trouvée";
	}
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
