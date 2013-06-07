<?php defined('SYSPATH') or die('No direct script access.');
/***********************************************************
* defaultcategories_js.php - Javascript for DefaultCategories Plugin
* This software is copy righted by WatchTheMed 2013
* Writen by Dylan Gillespie, Etherton Technologies <http://ethertontech.com>
* Started on 2013-07-06
* This plugin is to allow default categories on maps.
*************************************************************/
?>


<script type="text/javascript">	
	
	var path_info = '<?php 
		if ((strpos(url::current(), 'admin/reports/edit')) !== false){ echo 'admin/reports/edit';}
		else {echo url::current(); }	?>';
	var map_div = '';
	var my_map = null;
	var map_cat = false;
	var cat_exists = false;
    

	$(document).ready(function(){
		$('a.map').click(function(){
			if(!map_cat){
				if(!cat_exists){
					createSearchbar();
				}
				else{
					$('#searchControl').show();
				}
				map_cat = true;
			}
		});
		$('a.list').click(function(){
			if(map_cat){
				map_cat = false;
				$('#searchControl').hide();
			}
		});
	});
	

jQuery(window).load(function() {
	switch(path_info){
	case 'main':
		map_div = 'map';
		my_map = map._olMap;
		break;
	case 'reports/submit':
		map_div = 'divMap';
		my_map = map;
		break;
	case 'reports':
		map_div = 'rb_map-view';
		my_map = map;
		break;
	case 'admin/reports/edit':
		map_div = 'divMap';
		my_map = myMap;
		break;
	 case 'alerts':
        map_div = 'divMap';
        my_map = map._olMap;
        break;
	}
	if(path_info != 'reports'){
		createSearchbar();
	}
});
	
//<link rel="stylesheet" type="text/css" href="<?php echo url::base(); ?>plugins/searchlocation/media/css/searchLocationCSS.css"/>
</script>






