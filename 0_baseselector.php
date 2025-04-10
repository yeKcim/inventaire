<?php






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
        echo "<p style=\"text-align:center;\"><span id=\"linkbox\" onclick=\"TINY.box.show({iframe:'database_add.php',width:400,height:400,closejs:function(){location.reload()}})\" title=\"Ajouter une nouvelle base d’inventaire\">Créer la première base</span></p>";
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

