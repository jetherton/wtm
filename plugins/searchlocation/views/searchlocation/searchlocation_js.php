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
							<input type="text" id="coordinates" name="coordinates"/><div id="searchBtn">Search</div>\
							<input type="radio" name="search" id="Address" value="Address"/>Address</br>\
							<input type="radio" name="search" id="LatLong" value="LatLong"/>Longitude and Latitude</br>\
							<input type="radio" name="search" id="DMS" value="DMS"/>Degrees, minutes, seconds</br>\
							<input type="radio" name="search" id="Minutes" value="Minutes"/>Degrees, decimal minutes</br>\
						</div>\
				</div>\
				');

		$('.searchIcon').click(function(){
			$('#searchButtons').toggle();
		});
		$('#searchBtn').click(function(){
			searchLocation();
		});
		map_search = true;
	}



	function searchLocation(){
		var searchType = '';
		var location = $('#coordinates').val();
		//transform variables for coordinates
		var proj4326 = new OpenLayers.Projection("EPSG:4326");
		var projmerc = new OpenLayers.Projection("EPSG:900913");
		
		var searchArray = new Array();
			searchArray[0] = $('#Address');
			searchArray[1] = $('#LatLong');
			searchArray[2] = $('#DMS');
			searchArray[3] = $('#Minutes');

		for(var i = 0; i < 4; i++){
			if(searchArray[i].is(':checked')){
				searchType = searchArray[i].val();
				break;
			}
		}
		if(searchType == ''){
			alert('Please select a location type.');
		}
		else{
			switch(searchType){
				case 'Address':
					if(location != ''){
						$.post("<?php echo url::base(); ?>searchlocation/geocodeAddress", { 'loc': location },
								function(data) {
									var m = jQuery.parseJSON(data);
									//have to transform the coordinates to match the map I guess
									var lonlat = new OpenLayers.LonLat(m[1]['lon'], m[0]['lat']);
									lonlat.transform(proj4326, projmerc);
									my_map.setCenter(lonlat, my_map.zoom);
								}, "json");
					}
					break;
				case 'LatLong':
					var result = latLong(location);
					var lonlat = new OpenLayers.LonLat(result['lon'],result['lat']);
					lonlat.transform(proj4326, projmerc);
					my_map.setCenter(lonlat, my_map.zoom);
					break;
				case 'DMS':
					var result = DMS(location);
					var lonlat = new OpenLayers.LonLat(result['lon'],result['lat']);
					lonlat.transform(proj4326, projmerc);
					my_map.setCenter(lonlat, my_map.zoom);
					break;
				case 'Minutes':
					var result = DMM(location);
					var lonlat = new OpenLayers.LonLat(result['lon'],result['lat']);
					lonlat.transform(proj4326, projmerc);
					my_map.setCenter(lonlat, my_map.zoom);
					break;
			}
			
		}
	}

	//parse the string for lat long coordinates
	function latLong(loc){
		var lat, lon;
		if(loc.indexOf(',') !== false){
			lon = parseFloat(loc.substring(0, loc.indexOf(',')));
			lat = parseFloat(loc.substring(loc.indexOf(',')+1));
		}
		else{
			lon = parseFloat(loc.substring(0, loc.indexOf(' ')));
			lat = parseFloat(loc.substring(loc.indexOf(' ')+1));
		}
		var results = new Array();
		results['lat'] = lat;
		results['lon'] = lon;

		return results;
	}
	
	//parse the items and convert to lat lon
	function DMS(loc){
		var values;
		
		if(loc.indexOf(',') !== false){
			values = loc.split(',');
		}
		else{
			values = loc.split(",");
		}

		console.log('values', values);
		var Londeg = (values[0].toLowerCase().indexOf('n') !== false || (values[0].toLowerCase().indexOf('s') !== false)) ? values[0] : parseInt(values[0]);
		var Lonmins = values[1];
		var Lonsecs = (loc.toLowerCase().indexOf('n') !== false || (loc.toLowerCase().indexOf('s') !== false)) ? values[2] : parseInt(values[2]);
		var south = false;
		var west = false;
		var Latdeg, Latmins, Latsecs;

		if(loc.toLowerCase().indexOf('and') !== false){
			Latdeg = (loc.toLowerCase().indexOf('w') !== false || (loc.toLowerCase().indexOf('e') !== false)) ? values[4] : parseInt(values[4]);
			Latmins = parseInt(values[5]);
			Latsecs = (loc.toLowerCase().indexOf('w') !== false || (loc.toLowerCase().indexOf('e') !== false)) ? values[6] : parseInt(values[6]);
		}
		else{
			Latdeg = (loc.toLowerCase().indexOf('w') !== false || (loc.toLowerCase().indexOf('e') !== false)) ? values[3] : parseInt(values[3]);
			Latmins = parseInt(values[4]);
			Latsecs = (loc.toLowerCase().indexOf('w') !== false || (loc.toLowerCase().indexOf('e') !== false)) ? values[5] : parseInt(values[5]);
		}

		console.log('Londeg:', Londeg);
		console.log('Lonmins:', Lonmins);
		console.log('Lonsecs:', Lonsecs);
		console.log('Latdeg:', Latdeg);
		console.log('Latmins:', Latmins);
		console.log('Latsecs:', Latsecs);
		
		if(Londeg == null || Lonmins == null || Lonsecs == null || Latdeg == null || Latmins == null || Latsecs == null){
			alert('Improper formatting. Example of proper formatting is: 41 25 01N and 120 58 57W or N41 25 01 W120 58 57.');
		}

		//Find and pull the Letter coordinates out of the variables
		if(typeof Londeg == 'string'){
			if(Londeg.toLowerCase().indexOf('s') !== false){
				Londeg = parseInt(Londeg.substring(1));
				south = true;
			}
			else{
				Londeg = parseInt(Londeg.substring(1));
			}
		}
		if(typeof Lonsecs == 'string'){
			if(Lonsecs.toLowerCase().indexOf('s') !== false){
				Lonsecs = parseInt(Lonsecs.substring(0, Lonsecs.toLowerCase().indexOf('s')));
				south = true;
			}
			else{
				Lonsecs = parseInt(Lonsecs.substring(0, Lonsecs.toLowerCase().indexOf('s')));
			}
		}
		if(typeof Latdeg == 'string'){
			if(Latdeg.toLowerCase().indexOf('w') !== false){
				Latdeg = parseInt(Latdeg.substring(1));
				west = true;
			}
			else{
				Latdeg = parseInt(Latdeg.substring(1));
			}
		}
		if(typeof Latsecs == 'string'){
			if(Latsecs.toLowerCase().indexOf('w') !== false){
				Latsecs = parseInt(Latsecs.substring(0, Latsecs.toLowerCase().indexOf('w')));
				west = true;
			}
			else{
				Latsecs = parseInt(Latsecs.substring(0, Latsecs.toLowerCase().indexOf('w')));
			}
		}


		var lon = Londeg + (Lonmins/60) + (Lonsecs/3600);
		lon = south ? -(lon) : lon;

		var lat = Latdeg + (Latmins/60) + (Latsecs/3600);
		lat = west ? -(lat) : lat;

		var results = new Array();
		results['lat'] = lat;
		results['lon'] = lon;

		return results;
		
	}
	
	//parse the items and convert to lat lon
	function DMM(loc){

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




