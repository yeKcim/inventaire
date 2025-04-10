<?php

try {
    $db_tmp = new PDO("mysql:host=$connecthost;dbname=$prefix$database", $connectlogin, $connectpasse);
    $db_tmp->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Requête pour récupérer color et blocs
    $stmt = $db_tmp->query("SELECT color, blocs FROM SETTINGS WHERE i = 1");
    $settings = $stmt->fetch(PDO::FETCH_ASSOC);
    // Attribution avec valeurs par défaut si null
    $SETTINGS_color = isset($settings['color']) ? $settings['color'] : "#ffffff";
    $blocs = isset($settings['blocs']) ? $settings['blocs'] : "";
} catch (PDOException $e) {
    $SETTINGS_color = "#ffffff";
    $blocs = "";
}

// ajout d’une ligne de la couleur de la base
echo ($SETTINGS_color != "#ffffff") ? "<hr style=\"color:{$SETTINGS_color}; border-style: solid; margin-top:-0.5em; border-width:1px;\" />" : "" ;

// déterminer la couleur de texte en fonction de la couleur
$SETTINGS_textColor = getTextColorForBackground($SETTINGS_color);

// tableau modules à partir de la liste blocs
$SETTINGS_modules=explode(",", $settings['blocs']);

?>

