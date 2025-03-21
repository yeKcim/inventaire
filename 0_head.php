<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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

<!--╔═╗╦ ╦╔═╗╔═╗╔═╗╔═╗╔╗╔     ╔╦╗╦ ╦╦ ╔╦╗╦  ╔═╗╔═╗╦  ╔═╗╔═╗╔╦╗
    ║  ╠═╣║ ║║ ║╚═╗║╣ ║║║  o  ║║║║ ║║  ║ ║  ╚═╗║╣ ║  ║╣ ║   ║
    ╚═╝╩ ╩╚═╝╚═╝╚═╝╚═╝╝╚╝  o  ╩ ╩╚═╝╩═╝╩ ╩  ╚═╝╚═╝╩═╝╚═╝╚═╝ ╩   -->
    <script src="chosen/chosen.jquery.js" type="text/javascript"></script>
    <link rel="stylesheet" href="chosen/chosen.css">

<!--╔╦╗╦╔═╗╔═╗╔═╗╔═╗╔═╗╔═╗╦═╗  ╔╦╗╔═╗╦  ╔═╗╦ ╦
     ║║║╚═╗╠═╣╠═╝╠═╝║╣ ╠═╣╠╦╝   ║║║╣ ║  ╠═╣╚╦╝
    ═╩╝╩╚═╝╩ ╩╩  ╩  ╚═╝╩ ╩╩╚═  ═╩╝╚═╝╩═╝╩ ╩ ╩   -->
    <script type="text/javascript">
      $(document).ready( function() {
        $('#disappear_delay').delay(2000).fadeOut();
      });
    </script>

<!--╔╦╗╔═╗╔╦╗╔═╗╔╦╗╔═╗╔╗ ╦  ╔═╗╔═╗
     ║║╠═╣ ║ ╠═╣ ║ ╠═╣╠╩╗║  ║╣ ╚═╗
    ═╩╝╩ ╩ ╩ ╩ ╩ ╩ ╩ ╩╚═╝╩═╝╚═╝╚═╝  -->
	<link rel="stylesheet" type="text/css" href="datatables/jquery.dataTables.css">
	<style>
	  .dataTables_length label 		{width:auto;}
	  .dataTables_filter label 		{width:auto;}
	  table.dataTable tr.odd   		{background-color: #dedede;}
	  table.dataTable tr.odd td.sorting_1 	{background-color: #D7DFE3;}
	  table.dataTable tr.even td.sorting_1 	{background-color: #F5FBFF;}
	</style>
	
<!--╔═╗╔╦╗╔═╗╔═╗╔═╗╦ ╦╔═╗  ╔═╗╔═╗╦╔═╗╦╔═╗  ╔═╗╔═╗╦═╗╔╦╗╔═╗╦╔╗╔╔═╗  ╔═╗╔═╗╦═╗╔═╗╔═╗╔╦╗╔═╗╦═╗╔═╗╔═╗
	║╣ ║║║╠═╝║╣ ║  ╠═╣║╣   ╚═╗╠═╣║╚═╗║║╣   ║  ║╣ ╠╦╝ ║ ╠═╣║║║║╚═╗  ║  ╠═╣╠╦╝╠═╣║   ║ ║╣ ╠╦╝║╣ ╚═╗
	╚═╝╩ ╩╩  ╚═╝╚═╝╩ ╩╚═╝  ╚═╝╩ ╩╩╚═╝╩╚═╝  ╚═╝╚═╝╩╚═ ╩ ╩ ╩╩╝╚╝╚═╝  ╚═╝╩ ╩╩╚═╩ ╩╚═╝ ╩ ╚═╝╩╚═╚═╝╚═╝ -->
	<script type="text/javascript">
		$(document).ready(function() {
		  $(".restricted-input").on("input", function() {
			var originalValue = $(this).val();
		    var filteredValue = originalValue.replace(/[^\p{L}0-9_-]/gu, '');
			if (originalValue !== filteredValue) {
			  $(this).val(filteredValue);
			}
		  });
		});
	</script>

</head>


