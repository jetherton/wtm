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
	var globalData;

	$.post("<?php echo url::base(); ?>defaultcategories/retrieveCategories",
			function(data) {
				$('.has_border_first').next().next().next().append(data);
			}, "json");
	$.post("<?php echo url::base(); ?>defaultcategories/getCategories", 
			function(data) {
				globalData = data;
			}, "json");

	function test(){
		var changed = new Array();
		for(var i in globalData){
			var name = globalData[i][0].replace(' ', '_');
			var checked = $('#' + name).is(':checked');
			if(globalData[i][4] != checked){
				changed[name.replace('_', ' ')] = checked;
				//console.log(name);
			}
		}
		console.log(changed);
		console.log(changed == null);
		
		//if(changed.length != 0){
			$.post("<?php echo url::base(); ?>defaultcategories/changeDefault", { 'cha' : JSON.stringify(changed) });
		//}
		
	}
	$(document).ready(function(){
		
		$('.cancel-btn').click(function(){
		});
	});

</script>

<link rel="stylesheet" type="text/css" href="<?php echo url::base(); ?>plugins/defaultcategories/media/css/defaultcategoriesCSS.css"/>




