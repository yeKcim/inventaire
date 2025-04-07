<?php
$BASE = isset($_GET["BASE"]) ? htmlentities($_GET["BASE"]) : "" ;

echo "<form method=\"get\" action=\"?\">";

$nb_base = 0;
$list_bases = array();

try {
    // Exécution de la requête pour récupérer les bases de données
    $dbs = $dblist->query('SHOW DATABASES');

    // Vérification si la requête a retourné des bases
    while ($db = $dbs->fetchColumn(0)) {
        if (strpos($db, $prefix) !== false) {
            $list_bases[] = "<option value=\"" . str_replace($prefix, "", $db) . "\">" . strtoupper(str_replace($prefix, "", $db)) . "</option>";
            $first_base = ($nb_base == 0) ? str_replace($prefix, "", $db) : $first_base;
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

    // Affichage des options de bases de données
    foreach ($list_bases as $d) {
        echo str_replace("value=\"" . str_replace($prefix, "", $database) . "\">", "value=\"" . str_replace($prefix, "", $database) . "\" selected>", $d);
    }

    echo "</select> ";
    echo "<script>
    \$j(document).ready(function() {
        \$j('#selectbase').select2({
            placeholder: \"Sélectionnez une base\"
        });
    });
    </script>";

    // Si un paramètre 'i' existe, l'ajouter en tant que champ caché
    if (isset($i)) {
        if ($i != "") echo "<input id=\"i\" name=\"i\" type=\"hidden\" value=\"$i\">";
    }

    // Bouton pour ajouter une nouvelle base d'inventaire
    echo "<span id=\"linkbox\" onclick=\"TINY.box.show({iframe:'database_add.php',width:200,height:200,closejs:function(){location.reload()}})\" title=\"Ajouter une nouvelle base d’inventaire\">+</span>";

    echo "</p>";
    echo "</form>";

    // Si aucune base n'est sélectionnée, quitter le script
    if ($database == "") exit();

} catch (Exception $e) {
    echo 'Erreur : ' . $e->getMessage();
}
?>

