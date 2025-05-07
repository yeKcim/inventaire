<!doctype html>
<html lang="fr" dir="ltr" xml:lang="fr" xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="robots" content="noindex,nofollow" />
    <title>
        <?php
        $titre=isset($titre) ? $titre : "Inventaire" ;
        echo $titre; ?>
    </title>
    <link rel="stylesheet" type="text/css" href="./style.css">

    <!-- ascii : http://patorjk.com/software/taag/#p=display&h=2&v=0&f=ANSI%20Shadow&t= -->

<!-- ╦╔═╗ ╦ ╦╔═╗╦═╗╦ ╦
     ║║═╬╗║ ║║╣ ╠╦╝╚╦╝
    ╚╝╚═╝╚╚═╝╚═╝╩╚═ ╩   -->
    <script src="jquery.min.js" type="text/javascript"></script>
	<script>window.$j = jQuery.noConflict(); /* Pour select2 */</script>
	<script>window.$d = jQuery.noConflict(); /* Pour datatables */</script>
    <script src="jquery.inputmask.min.js"></script>

<!--╔╦╗╦╔╗╔╦ ╦╔╗ ╔═╗═╗ ╦
     ║ ║║║║╚╦╝╠╩╗║ ║╔╩╦╝
     ╩ ╩╝╚╝ ╩ ╚═╝╚═╝╩ ╚═
     tinybox pour les popup layout -->
    <script type="text/javascript" src="tinybox/tinybox.js"></script>
    <link rel="stylesheet" href="tinybox/tinybox.css" />

<!--╔╦╗╦═╗╔═╗╔═╗╔═╗╔═╗╔╗╔╔═╗
     ║║╠╦╝║ ║╠═╝╔═╝║ ║║║║║╣
    ═╩╝╩╚═╚═╝╩  ╚═╝╚═╝╝╚╝╚═╝
    dropzone pour drag&drop uploader    -->
    <script src="dropzone/dropzone.js"></script>
    <link rel="stylesheet" href="dropzone/dropzone.css">

<!--╔╦╗╦╔═╗╔═╗╦  ╔═╗╦ ╦   ╦ ╦╦╔╦╗╔═╗
     ║║║╚═╗╠═╝║  ╠═╣╚╦╝ █ ╠═╣║ ║║║╣
    ═╩╝╩╚═╝╩  ╩═╝╩ ╩ ╩    ╩ ╩╩═╩╝╚═╝    -->
    <script type="text/javascript">
        // <![CDATA[
        function display(obj,id1,id2) {
            txt = obj.options[obj.selectedIndex].value;
            document.getElementById(id1).style.display = 'none';
            document.getElementById(id2).style.display = 'none';
            if ( txt.match(id1) ) { document.getElementById(id1).style.display = 'block'; }
            if ( txt.match(id2) ) { document.getElementById(id2).style.display = 'block'; }
        }
        // ]]>
    </script>

    <script type="text/javascript">
        // <![CDATA[
        function hide(obj,id1,id2) {
            txt = obj.options[obj.selectedIndex].value;
            document.getElementById(id1).style.display = 'block';
            document.getElementById(id2).style.display = 'block';
            if ( txt.match(id1) ) { document.getElementById(id1).style.display = 'none'; }
            if ( txt.match(id2) ) { document.getElementById(id2).style.display = 'none'; }
        }
        // ]]>
    </script>

<!--╔╦╗╦╔═╗╔═╗╔═╗╔═╗╔═╗╔═╗╦═╗  ╔╦╗╔═╗╦  ╔═╗╦ ╦
     ║║║╚═╗╠═╣╠═╝╠═╝║╣ ╠═╣╠╦╝   ║║║╣ ║  ╠═╣╚╦╝
    ═╩╝╩╚═╝╩ ╩╩  ╩  ╚═╝╩ ╩╩╚═  ═╩╝╚═╝╩═╝╩ ╩ ╩   -->
	<script type="text/javascript">
	  $j(document).ready(function() {
		$j('#disappear_delay, .disappear_delay').delay(5000).fadeOut();
	  });
	</script>

<!--╔╦╗╔═╗╔╦╗╔═╗╔╦╗╔═╗╔╗ ╦  ╔═╗╔═╗
     ║║╠═╣ ║ ╠═╣ ║ ╠═╣╠╩╗║  ║╣ ╚═╗
    ═╩╝╩ ╩ ╩ ╩ ╩ ╩ ╩ ╩╚═╝╩═╝╚═╝╚═╝  -->
    
    

        
        
	<script src="datatables/datatables.js" type="text/javascript"></script>
	<link rel="stylesheet" type="text/css" href="datatables/datatables.css">
	
	<link rel="stylesheet" type="text/css" href="datatables/fixedHeader.dataTables.min.css">
	<script src="datatables/dataTables.fixedHeader.min.js"></script>


	<style>
		#listing input {
	  		width: 100%; /* Occupe toute la largeur du <td> */
			box-sizing: border-box;
			margin-top:10px;
			margin-bottom:10px;
		}
		#listing input::placeholder {
			font-size: 0.7em;
			text-align: center;
		}
		
		/* Appliquer sticky aux cellules de l'en-tête */
		#listing thead th {
			position: sticky;
			top: 0;
			/*background-color: #fff; /* Pour masquer le contenu qui défile derrière */
			z-index: 2;
		}
	</style>
	<script>
	  $j(document).ready(function () {
		new DataTable('#listing', {
		  pageLength: 50,
		  stateSave: true,
		  language: {
				"emptyTable": "Aucune donnée disponible dans le tableau",
				"loadingRecords": "Chargement...",
				"processing": "Traitement...",
				"select": {
					"rows": {
						"_": "%d lignes sélectionnées",
						"1": "1 ligne sélectionnée"
					},
					"cells": {
						"1": "1 cellule sélectionnée",
						"_": "%d cellules sélectionnées"
					},
					"columns": {
						"1": "1 colonne sélectionnée",
						"_": "%d colonnes sélectionnées"
					}
				},
				"autoFill": {
					"cancel": "Annuler",
					"fill": "Remplir toutes les cellules avec <i>%d<\/i>",
					"fillHorizontal": "Remplir les cellules horizontalement",
					"fillVertical": "Remplir les cellules verticalement"
				},
				"searchBuilder": {
					"conditions": {
						"date": {
							"after": "Après le",
							"before": "Avant le",
							"between": "Entre",
							"empty": "Vide",
							"not": "Différent de",
							"notBetween": "Pas entre",
							"notEmpty": "Non vide",
							"equals": "Égal à"
						},
						"number": {
							"between": "Entre",
							"empty": "Vide",
							"gt": "Supérieur à",
							"gte": "Supérieur ou égal à",
							"lt": "Inférieur à",
							"lte": "Inférieur ou égal à",
							"not": "Différent de",
							"notBetween": "Pas entre",
							"notEmpty": "Non vide",
							"equals": "Égal à"
						},
						"string": {
							"contains": "Contient",
							"empty": "Vide",
							"endsWith": "Se termine par",
							"not": "Différent de",
							"notEmpty": "Non vide",
							"startsWith": "Commence par",
							"equals": "Égal à",
							"notContains": "Ne contient pas",
							"notEndsWith": "Ne termine pas par",
							"notStartsWith": "Ne commence pas par"
						},
						"array": {
							"empty": "Vide",
							"contains": "Contient",
							"not": "Différent de",
							"notEmpty": "Non vide",
							"without": "Sans",
							"equals": "Égal à"
						}
					},
					"add": "Ajouter une condition",
					"button": {
						"0": "Recherche avancée",
						"_": "Recherche avancée (%d)"
					},
					"clearAll": "Effacer tout",
					"condition": "Condition",
					"data": "Donnée",
					"deleteTitle": "Supprimer la règle de filtrage",
					"logicAnd": "Et",
					"logicOr": "Ou",
					"title": {
						"0": "Recherche avancée",
						"_": "Recherche avancée (%d)"
					},
					"value": "Valeur",
					"leftTitle": "Désindenter le critère",
					"rightTitle": "Indenter le critère"
				},
				"searchPanes": {
					"clearMessage": "Effacer tout",
					"count": "{total}",
					"title": "Filtres actifs - %d",
					"collapse": {
						"0": "Volet de recherche",
						"_": "Volet de recherche (%d)"
					},
					"countFiltered": "{shown} ({total})",
					"emptyPanes": "Pas de volet de recherche",
					"loadMessage": "Chargement du volet de recherche...",
					"collapseMessage": "Réduire tout",
					"showMessage": "Montrer tout"
				},
				"buttons": {
					"collection": "Collection",
					"colvis": "Visibilité colonnes",
					"colvisRestore": "Rétablir visibilité",
					"copy": "Copier",
					"copySuccess": {
						"1": "1 ligne copiée dans le presse-papier",
						"_": "%d lignes copiées dans le presse-papier"
					},
					"copyTitle": "Copier dans le presse-papier",
					"csv": "CSV",
					"excel": "Excel",
					"pageLength": {
						"-1": "Afficher toutes les lignes",
						"_": "Afficher %d lignes",
						"1": "Afficher 1 ligne"
					},
					"pdf": "PDF",
					"print": "Imprimer",
					"copyKeys": "Appuyez sur ctrl ou u2318 + C pour copier les données du tableau dans votre presse-papier.",
					"createState": "Créer un état",
					"removeAllStates": "Supprimer tous les états",
					"removeState": "Supprimer",
					"renameState": "Renommer",
					"savedStates": "États sauvegardés",
					"stateRestore": "État %d",
					"updateState": "Mettre à jour"
				},
				"decimal": ",",
				"datetime": {
					"previous": "Précédent",
					"next": "Suivant",
					"hours": "Heures",
					"minutes": "Minutes",
					"seconds": "Secondes",
					"unknown": "-",
					"amPm": [
						"am",
						"pm"
					],
					"months": {
						"0": "Janvier",
						"2": "Mars",
						"3": "Avril",
						"4": "Mai",
						"5": "Juin",
						"6": "Juillet",
						"8": "Septembre",
						"9": "Octobre",
						"10": "Novembre",
						"1": "Février",
						"11": "Décembre",
						"7": "Août"
					},
					"weekdays": [
						"Dim",
						"Lun",
						"Mar",
						"Mer",
						"Jeu",
						"Ven",
						"Sam"
					]
				},
				"editor": {
					"close": "Fermer",
					"create": {
						"title": "Créer une nouvelle entrée",
						"button": "Nouveau",
						"submit": "Créer"
					},
					"edit": {
						"button": "Editer",
						"title": "Editer Entrée",
						"submit": "Mettre à jour"
					},
					"remove": {
						"button": "Supprimer",
						"title": "Supprimer",
						"submit": "Supprimer",
						"confirm": {
							"_": "Êtes-vous sûr de vouloir supprimer %d lignes ?",
							"1": "Êtes-vous sûr de vouloir supprimer 1 ligne ?"
						}
					},
					"multi": {
						"title": "Valeurs multiples",
						"info": "Les éléments sélectionnés contiennent différentes valeurs pour cette entrée. Pour modifier et définir tous les éléments de cette entrée à la même valeur, cliquez ou tapez ici, sinon ils conserveront leurs valeurs individuelles.",
						"restore": "Annuler les modifications",
						"noMulti": "Ce champ peut être modifié individuellement, mais ne fait pas partie d'un groupe. "
					},
					"error": {
						"system": "Une erreur système s'est produite (<a target=\"\\\" rel=\"nofollow\" href=\"\\\">Plus d'information<\/a>)."
					}
				},
				"stateRestore": {
					"removeSubmit": "Supprimer",
					"creationModal": {
						"button": "Créer",
						"order": "Tri",
						"paging": "Pagination",
						"scroller": "Position du défilement",
						"search": "Recherche",
						"select": "Sélection",
						"columns": {
							"search": "Recherche par colonne",
							"visible": "Visibilité des colonnes"
						},
						"name": "Nom :",
						"searchBuilder": "Recherche avancée",
						"title": "Créer un nouvel état",
						"toggleLabel": "Inclus :"
					},
					"renameButton": "Renommer",
					"duplicateError": "Il existe déjà un état avec ce nom.",
					"emptyError": "Le nom ne peut pas être vide.",
					"emptyStates": "Aucun état sauvegardé",
					"removeConfirm": "Voulez vous vraiment supprimer %s ?",
					"removeError": "Échec de la suppression de l'état.",
					"removeJoiner": "et",
					"removeTitle": "Supprimer l'état",
					"renameLabel": "Nouveau nom pour %s :",
					"renameTitle": "Renommer l'état"
				},
				"info": "Affichage de _START_ à _END_ sur _TOTAL_ entrées",
				"infoEmpty": "Affichage de 0 à 0 sur 0 entrées",
				"infoFiltered": "(filtrées depuis un total de _MAX_ entrées)",
				"lengthMenu": "Afficher _MENU_ entrées",
				"paginate": {
					"first": "Première",
					"last": "Dernière",
					"next": "Suivante",
					"previous": "Précédente"
				},
				"zeroRecords": "Aucune entrée correspondante trouvée",
				"aria": {
					"sortAscending": " : activer pour trier la colonne par ordre croissant",
					"sortDescending": " : activer pour trier la colonne par ordre décroissant"
				},
				"infoThousands": " ",
				"search": "Rechercher :",
				"thousands": " "
		  },
		  initComplete: function () {
		    this.api()
		      .columns()
		      .every(function () {
		        let column = this;
		        // Supprime les espaces en début et fin de chaîne
		        let title = column.footer().textContent.trim();

		        // Créer un champ input dans le footer
		        let input = document.createElement('input');
		        input.placeholder = title;
		        // Facultatif : forcer l'alignement du texte à gauche
		        input.style.textAlign = "left";
		        column.footer().replaceChildren(input);

		        // Event listener pour la recherche
		        input.addEventListener('keyup', () => {
		          if (column.search() !== input.value) {
		            column.search(input.value).draw();
		          }
		        });
		      });
		  }
		});
	  });
	</script>
	<script>$d.fn.DataTable = $.fn.DataTable; /* Associer DataTables à $d */</script>


<!--╔═╗╔═╗╦  ╔═╗╔═╗╔╦╗ ╔╦╗╦ ╦╔═╗     ╔╦╗╦ ╦╦ ╔╦╗╦╔═╗╔═╗╦  ╔═╗╔═╗╔╦╗
	╚═╗║╣ ║  ║╣ ║   ║───║ ║║║║ ║  o  ║║║║ ║║  ║ ║╚═╗║╣ ║  ║╣ ║   ║ 
	╚═╝╚═╝╩═╝╚═╝╚═╝ ╩   ╩ ╚╩╝╚═╝  o  ╩ ╩╚═╝╩═╝╩ ╩╚═╝╚═╝╩═╝╚═╝╚═╝ ╩   -->
  	<link href="select2/select2.min.css" rel="stylesheet" />
    <script src="select2/select2.min.js"></script>
	<script>$j.fn.select2 = $.fn.select2; /* Réassocie Select2 à $j une fois pour toutes */</script>
    	
<!--╔═╗╔╦╗╔═╗╔═╗╔═╗╦ ╦╔═╗  ╔═╗╔═╗╦╔═╗╦╔═╗  ╔═╗╔═╗╦═╗╔╦╗╔═╗╦╔╗╔╔═╗  ╔═╗╔═╗╦═╗╔═╗╔═╗╔╦╗╔═╗╦═╗╔═╗╔═╗
	║╣ ║║║╠═╝║╣ ║  ╠═╣║╣   ╚═╗╠═╣║╚═╗║║╣   ║  ║╣ ╠╦╝ ║ ╠═╣║║║║╚═╗  ║  ╠═╣╠╦╝╠═╣║   ║ ║╣ ╠╦╝║╣ ╚═╗
	╚═╝╩ ╩╩  ╚═╝╚═╝╩ ╩╚═╝  ╚═╝╩ ╩╩╚═╝╩╚═╝  ╚═╝╚═╝╩╚═ ╩ ╩ ╩╩╝╚╝╚═╝  ╚═╝╩ ╩╩╚═╩ ╩╚═╝ ╩ ╚═╝╩╚═╚═╝╚═╝ -->
	<script type="text/javascript">
	  $j(document).ready(function() {
		$j(".restricted-input").on("input", function() {
		  var originalValue = $j(this).val();
		  var filteredValue = originalValue.replace(/[^A-Za-z0-9_-]/g, '');
		  if (originalValue !== filteredValue) {
		    $j(this).val(filteredValue);
		  }
		});
	  });
	</script>

</head>


