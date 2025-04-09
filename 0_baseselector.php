<?php


/**
 * Détermine la couleur du texte en fonction de la couleur de fond.
 * Si le fond est sombre, retourne blanc (#FFFFFF), sinon noir (#000000).
 *
 * @param string $bgHex La couleur de fond au format hexadécimal (#RRGGBB ou #RGB).
 * @return string Couleur du texte (#FFFFFF ou #000000).
 */
function getTextColorForBackground($bgHex) {
    // Suppression du caractère #
    $hex = ltrim($bgHex, '#');
    // Si en format court (#RGB), convertir en #RRGGBB
    if (strlen($hex) == 3) {
        $hex = $hex[0] . $hex[0]
             . $hex[1] . $hex[1]
             . $hex[2] . $hex[2];
    }
    // Extraction des composantes RGB
    $r = hexdec(substr($hex, 0, 2));
    $g = hexdec(substr($hex, 2, 2));
    $b = hexdec(substr($hex, 4, 2));
    // Calcul de la luminosité en utilisant la formule de luminance relative
    // brightness = (R*299 + G*587 + B*114) / 1000
    $brightness = (($r * 299) + ($g * 587) + ($b * 114)) / 1000;
    // Si la luminosité est faible (< 128), on choisit le blanc, sinon le noir.
    return ($brightness < 128) ? "#FFFFFF" : "#000000";
}



$BASE = isset($_GET["BASE"]) ? htmlentities($_GET["BASE"]) : "";

echo "<form method=\"get\" action=\"?\">";

$nb_base = 0;
$list_bases = array();

try {
    // Exécution de la requête pour récupérer les bases de données
    $dbs = $dblist->query('SHOW DATABASES');

    // Vérifier si la requête a retourné des bases
    while ($db = $dbs->fetchColumn(0)) {
        if (strpos($db, $prefix) !== false) {
            // Récupération du nom abrégé de la base
            $shortName = str_replace($prefix, "", $db);

            // Connexion temporaire à la base pour récupérer la couleur dans SETTINGS
            try {
                $dbh_temp = new PDO("mysql:host=$connecthost;dbname=$db", $connectlogin, $connectpasse);
                $dbh_temp->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $stmt = $dbh_temp->query("SELECT color FROM SETTINGS WHERE i = 1");
                $color = $stmt->fetchColumn();
                if (!$color) {
                    $color = "#ffffff"; // Couleur par défaut si non trouvée
                }
            } catch (PDOException $e) {
                $color = "#ffffff"; // Valeur par défaut en cas d'erreur
            }

            // Stocker les informations dans un tableau associatif
            $list_bases[] = array(
                'short_name' => $shortName,
                'display' => strtoupper($shortName),
                'color' => $color
            );
            if ($nb_base == 0) {
                $first_base = $shortName;
            }
            $nb_base++;
        }
    }
    
    // Gestion des erreurs si aucune base n'a été trouvée
    if ($nb_base == 0) {
        echo "<p style=\"text-align:center;\">Aucun inventaire détecté !</p>";
        echo "<p style=\"text-align:center;\"><span id=\"linkbox\" onclick=\"TINY.box.show({iframe:'database_add.php',width:200,height:200,closejs:function(){location.reload()}})\" title=\"Ajouter une nouvelle base d’inventaire\">Créer la première base</span></p>";
        exit();
    } elseif ($nb_base == 1) {
        $database = ($BASE == "") ? $first_base : $BASE;
    }

    echo "<p style=\"text-align:center;\">";
    echo "<select name=\"BASE\" onchange=\"submit();\" class=\"select2\" tabindex=\"0\" id=\"selectbase\">";
    echo "<option value=\"\">− Sélectionnez une base −</option>";

	$border_color = null;
    // Pour l'affichage dans le select, on génère les <option> à partir de $list_bases
    foreach ($list_bases as $base) {

        if ($base['short_name'] == $BASE) {
       		$selected = " selected";
       		$border_color = htmlspecialchars($base['color']);
        } else {
        	$selected = "";
        	
        }
     
        echo "<option value=\"" . $base['short_name'] . "\"" . $selected . ">" . $base['display'] . "</option>";
    }
    echo "</select> ";
    
    echo "</select> ";
    echo "<script>
    \$j(document).ready(function() {
        \$j('#selectbase').select2();
    });
    </script>";
    
    
    // Optionnel : champ caché pour 'i'
    if (isset($i) && $i != "") {
        echo "<input id=\"i\" name=\"i\" type=\"hidden\" value=\"$i\">";
    }
    // Bouton pour ajouter une nouvelle base d'inventaire
    echo "<span id=\"linkbox\" onclick=\"TINY.box.show({iframe:'database_add.php',width:400,height:400,closejs:function(){location.reload()}})\" title=\"Ajouter une nouvelle base d’inventaire\">+</span>";
    echo "</p>";
    echo "</form>";
    

	// ajout d’une ligne de la couleur de la base
        echo (isset($border_color)) ? "<hr style=\"color:{$border_color}; border-style: solid; margin-top:-0.5em; border-width:1px;\" />" : "" ;
    
    
    
    

    // Si aucune base n'est sélectionnée, afficher toutes les bases sous forme de <li> avec background color
    if (($database == "") && ($nb_base >= 2)) {

        echo "<ul style=\"text-align:center; list-style:none;\">";
        foreach ($list_bases as $base) {
        	// Récupération de la couleur de fond et calcul de la couleur du texte
        	$bgColor = htmlspecialchars($base['color']);
			$textColor = getTextColorForBackground($bgColor);
			// Définition du background via inline style avec la couleur récupérée
            echo "<li style=\"border:solid 1px #aaaaaa; width:30%; margin-left: 1em; margin-bottom: 1em; margin-right: auto; float:left; background-color:{$bgColor}; font-size:1.2em;\">";
            echo "<a style=\"display: block; padding:2em; color:{$textColor}; text-decoration:none;\" href=\"?BASE=" . $base['short_name'] . "\">" . $base['display'] . "</a>";
            echo "</li>";
        }
        echo "</ul>";
        
        exit();
    }

} catch (Exception $e) {
    echo 'Erreur : ' . $e->getMessage();
}
?>

