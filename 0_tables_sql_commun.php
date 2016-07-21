<?php

// $categories
$table_categories = "SELECT * FROM categorie WHERE categorie_index!=0 ORDER BY categorie_nom ASC ;";
$query_table_categories = mysql_query ($table_categories);
$categories = array();
while ($l = mysql_fetch_row($query_table_categories)) {
    $categories[$l[0]]=array($l[0],"".utf8_encode($l[2])."","(".utf8_encode($l[1]).")");
}


// $types_contrats
$table_type_contrat = "SELECT * FROM contrat_type WHERE contrat_type_index!=0 ORDER BY contrat_type.contrat_type_cat ASC ;";
$query_table_type_contrat = mysql_query ($table_type_contrat);
$types_contrats = array();
while ($l = mysql_fetch_row($query_table_type_contrat)) {
    $types_contrats[$l[0]]=array($l[0],utf8_encode($l[1]));
}


// utilisateurs
$query_table_utilisateur = mysql_query ("SELECT DISTINCT(utilisateur_index) utilisateur_index, utilisateur_nom, utilisateur_prenom, utilisateur_mail, utilisateur_phone FROM utilisateur WHERE utilisateur_index!=0 ORDER BY utilisateur_nom ASC ;");
$utilisateurs = array();
while ($utilisateur = mysql_fetch_row($query_table_utilisateur)) {
    $utilisateurs[$utilisateur[0]]=array(   $utilisateur[0],                    utf8_encode($utilisateur[1]),
                                            utf8_encode($utilisateur[2]),       $utilisateur[3],
                                            $utilisateur[4]
                                        );
}



?>
