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
      $j(document).ready( function() {
        $j('#disappear_delay').delay(5000).fadeOut();
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


