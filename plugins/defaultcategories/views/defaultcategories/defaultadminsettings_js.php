<?php defined('SYSPATH') or die('No direct script access.');
/***********************************************************
* defaultadminsettings_js.php - Javascript for DefaultCategories Plugin
* This software is copy righted by WatchTheMed 2013
* Writen by Dylan Gillespie, Etherton Technologies <http://ethertontech.com>
* Started on 2013-07-06
* This javascript is to add default options to the settings page.
*************************************************************/
?>


<script type="text/javascript">	
	
	var path_info = '<?php echo url::current();?>';
	var parents = $('.has_border_first').next().next().next();
	

jQuery(window).load(function() {
	$.post("<?php echo url::base(); ?>defaultcategories/retrieveCategories",
			function(data) {
				var m = jQuery.parseJSON(data);
				console.log(m);
			}, "json");
});
	
//<link rel="stylesheet" type="text/css" href="<?php echo url::base(); ?>plugins/searchlocation/media/css/searchLocationCSS.css"/>
</script>






