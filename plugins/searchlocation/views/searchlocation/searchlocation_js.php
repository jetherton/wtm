<?php defined('SYSPATH') or die('No direct script access.');
/***********************************************************
* searchlocation_js.php - Javascript for Search Location Plugin
* This software is copy righted by WatchTheMed 2013
* Writen by Dylan Gillespie, Etherton Technologies <http://ethertontech.com>
* Started on 2013-27-05
* This plugin is to add a search options for the maps
*************************************************************/
?>

<script type="text/javascript">	
	var path_info = '<?php 
		if ((strpos(url::current(), 'admin/reports/edit')) !== false){ echo 'admin/reports/edit';}
		else {echo url::current(); }	?>';
	var map_div = '';
	var my_map = null;
	var map_search = false;
	var search_exists = false;
    

	$(document).ready(function(){
		$('a.map').click(function(){
			if(!map_search){
				if(!search_exists){
					createSearchbar();
				}
				else{
					$('#searchControl').show();
				}
				map_search = true;
			}
		});
		$('a.list').click(function(){
			if(map_search){
				map_search = false;
				$('#searchControl').hide();
			}
		});
	});
	


    //turn off all listeners
    function deactivateAll(){
		clickOut.deactivate();
		clickIn.deactivate();
		$('#'+map_div).css({
			'cursor': "default"
		});
		$('#output').hide();
		for(key in measureControls) {
            var control = measureControls[key];
            control.deactivate();
            my_map.removeControl(control);
            //control.destroy();
        }
    }

	function createSearchbar(){
		//create the ruler buttons
		$('#'+map_div).before(
				'<div style="position:absolute;">\
				<div id="searchControl">\
					<img class="searchIcon" src="<?php echo URL::base();?>plugins/searchlocation/media/img/img_trans.gif" width="1" height="1"/>\
						<div id="searchButtons" style="display:none">\
							<input type="text" name="coordinates"/><div id="searchBtn">Search</div></br>\
							<input type="radio" name="search" value="Address"/>Address</br>\
							<input type="radio" name="search" value="LatLong"/>Latitude and Longitude</br>\
							<input type="radio" name="search" value="DHM"/>Degrees, hours, minutes</br>\
							<input type="radio" name="search" value="Minutes"/>Degrees, decimal minutes</br>\
						</div>\
				</div>\
				');

		$('.searchIcon').click(function(){
			$('#searchButtons').toggle();
		});
		map_search = true;
	}

    
   

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
	
//
</script>

<link rel="stylesheet" type="text/css" href="<?php echo url::base(); ?>plugins/searchlocation/media/css/searchLocationCSS.css"/>




