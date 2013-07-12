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
	var searchLayer = null;
    

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
	

	function createSearchbar(){
		//create the ruler buttons
		$('#'+map_div).before(
				'<div style="position:absolute;">\
				<div id="searchControl">\
					<img class="searchIcon" src="<?php echo URL::base();?>plugins/searchlocation/media/img/img_trans.gif" width="1" height="1"/>\
						<div id="searchButtons" style="display:none;padding:5px;">\
								<input type="text" id="coordinates" placeholder="1234 S Main St, New York, New York" name="coordinates"/>\
									<input type="button" value="search" onclick="searchLocation();"/> <input type="button" value="clear" onclick="clearSearch();"/></br>\
								<input type="radio" checked name="search" id="Address" title="Search by address." value="Address"/>Address</br>\
								<input type="radio" name="search" id="LatLong" title="Seach by Latitude and Longitude." value="LatLong"/>Longitude and Latitude</br>\
								<input type="radio" name="search" id="DMS" title="Search by DMS" value="DMS"/>Degrees, minutes, seconds</br>\
								<input type="radio" name="search" id="Minutes" title="Search for DM." value="Minutes"/>Degrees, decimal minutes</br>\
						</div>\
				</div>\
				');

		$('.searchIcon').click(function(){
			$('#searchButtons').toggle();
		});
	
		$('#Address').click(function(){
			$('#coordinates').attr('placeholder', '1234 S Main St, New York, New York...');
		});
		$('#LatLong').click(function(){
			$('#coordinates').attr('placeholder', 'DD.DDDD, DD.DDDD');
		});
		$('#DMS').click(function(){
			$('#coordinates').attr('placeholder', 'DD MM SS, DD MM SS');
		});
		$('#Minutes').click(function(){
			$('#coordinates').attr('placeholder', 'DD.ddd MM.mmm, DD.ddd MM.mmm');
		});
		map_search = true;
		
		var template = {
		    pointRadius: "10", // using context.getSize(feature)
		    fillColor: "#333333", // using context.getColor(feature)
		    strokeWidth:"2",
		    strokeColor:"#ffffff"
		};
		var style = new OpenLayers.Style(template);
		
		searchLayer = new OpenLayers.Layer.Vector( "Search Layer", {
		    styleMap: new OpenLayers.StyleMap(style)
		});
				

		my_map.addLayer(searchLayer);
	}


	function clearSearch(){
	    searchLayer.removeAllFeatures();
	}

	function searchLocation(){
		//transform variables for coordinates
		var proj4326 = new OpenLayers.Projection("EPSG:4326");
		var projmerc = new OpenLayers.Projection("EPSG:900913");
		var orgCenter = my_map.center;
		var location = $("#coordinates").val();
		var searchType = $('input:radio[name=search]:checked').val();		
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
									var point = new OpenLayers.Feature.Vector(										
									    new OpenLayers.Geometry.Point(
										lonlat.lon, lonlat.lat)
									);
									searchLayer.addFeatures([point]);
									
									var newCenter = my_map.center;
									//error check if the center was changed, if not then the location was not parsed.
									if(newCenter == orgCenter){
										alert('Sorry, can\'t find ' + location + '.');
									}
								}, "json");
					}
					break;
				case 'LatLong':
					var result = latLong(location);
					var lonlat = new OpenLayers.LonLat(result['lon'],result['lat']);
					lonlat.transform(proj4326, projmerc);
					my_map.setCenter(lonlat, my_map.zoom);
					var point = new OpenLayers.Feature.Vector(										
					    new OpenLayers.Geometry.Point(
						lonlat.lon, lonlat.lat)
					);
					searchLayer.addFeatures([point]);
					var newCenter = my_map.center;
					//error check if the center was changed, if not then the location was not parsed.
					if(newCenter == orgCenter){
						alert('Sorry, can\'t find ' + location + '.');
					}
					break;
				case 'DMS':				    
					var result = DMS(location);
					if(result !== false){
						var lonlat = new OpenLayers.LonLat(result['lon'],result['lat']);
						lonlat.transform(proj4326, projmerc);
						my_map.setCenter(lonlat, my_map.zoom);
						var point = new OpenLayers.Feature.Vector(										
						    new OpenLayers.Geometry.Point(
							lonlat.lon, lonlat.lat)
						);
						searchLayer.addFeatures([point]);
					}
					break;
				case 'Minutes':
					var result = DMS(location);
					if(result !== false){
						var lonlat = new OpenLayers.LonLat(result['lon'],result['lat']);
						lonlat.transform(proj4326, projmerc);
						my_map.setCenter(lonlat, my_map.zoom);
						var point = new OpenLayers.Feature.Vector(										
						    new OpenLayers.Geometry.Point(
							lonlat.lon, lonlat.lat)
						);
						searchLayer.addFeatures([point]);
					}
					break;
			}
			
		}
	}

	//parse the string for lat long coordinates
	function latLong(loc){
		var lat, lon;
		if(loc.indexOf(',') != -1){
			lat = parseFloat(loc.substring(0, loc.indexOf(',')));
			lon = parseFloat(loc.substring(loc.indexOf(',')+1));
		}
		else{
			lat = parseFloat(loc.substring(0, loc.indexOf(' ')));
			lon = parseFloat(loc.substring(loc.indexOf(' ')+1));
		}
		var results = new Array();
		results['lat'] = lat;
		results['lon'] = lon;
		
		return results;
	}
	
	//parse the items and convert to lat lon
	function DMS(loc){
		var values;
		
		if(loc.indexOf(' ') != -1){
			values = loc.split(' ');
		}
		else{
			//just putting ' ' didn't work for spaces
			alert("Sorry, I don't recognize your formatting");
			return false;
		}
		if(values.length != 6){
		    	alert("Sorry, I don't recognize your formatting");
			return false;
		}
		console.log(values);

		if(values.length > 1){
			var Latdeg = (values[0].toLowerCase().indexOf('n') != -1 || (values[0].toLowerCase().indexOf('s') != -1)) ? values[0] : parseInt(values[0]);
			var Latmins = parseInt(values[1]);
			var Latsecs = (values[2].toLowerCase().indexOf('n') != -1 || (values[2].toLowerCase().indexOf('s') != -1)) ? values[2] : parseInt(values[2]);
			var south = false;
			var west = false;
			var Londeg, Lonmins, Lonsecs;
	
			if(loc.toLowerCase().indexOf('and') != -1){
				Londeg = (values[4].toLowerCase().indexOf('w') != -1 || (locvalues[4].toLowerCase().indexOf('e') != -1)) ? values[4] : parseInt(values[4]);
				Lonmins = parseInt(values[5]);
				Lonsecs = (values[6].toLowerCase().indexOf('w') != -1 || (values[6].toLowerCase().indexOf('e') != -1)) ? values[6] : parseInt(values[6]);
			}
			else{
				Londeg = (values[3].toLowerCase().indexOf('w') != -1 || (values[3].toLowerCase().indexOf('e') != -1)) ? values[3] : parseInt(values[3]);
				Lonmins = parseInt(values[4]);
				Lonsecs = (values[5].toLowerCase().indexOf('w') != -1 || (values[5].toLowerCase().indexOf('e') != -1)) ? values[5] : parseInt(values[5]);
			}
	
			
			if(Londeg == null || Lonmins == null || Lonsecs == null || Latdeg == null || Latmins == null || Latsecs == null){
				alert('Improper formatting. Example of proper formatting is: 41 25 01N and 120 58 57W or N41 25 01 W120 58 57.');
			}
	
			//Find and pull the Letter coordinates out of the variables
			if(typeof Latdeg == 'string'){
				if(Latdeg.toLowerCase().indexOf('s') != -1){
					Latdeg = parseInt(Latdeg.substring(1));
					south = true;
				}
				else{
					Latdeg = parseInt(Latdeg.substring(1));
				}
			}
			if(typeof Latsecs == 'string'){
				if(Latsecs.toLowerCase().indexOf('s') != -1){
					Latsecs = parseInt(Latsecs.substring(0, Latsecs.toLowerCase().indexOf('s')));
					south = true;
				}
				else{
					Latsecs = parseInt(Latsecs.substring(0, Latsecs.toLowerCase().indexOf('n')));
				}
			}
			if(typeof Londeg == 'string'){
				if(Londeg.toLowerCase().indexOf('w') != -1){
					Londeg = parseInt(Londeg.substring(1));
					west = true;
				}
				else{
					Londeg = parseInt(Londeg.substring(1));
				}
			}
			if(typeof Lonsecs == 'string'){
				if(Lonsecs.toLowerCase().indexOf('w') != -1){
					Lonsecs = parseInt(Lonsecs.substring(0, Lonsecs.toLowerCase().indexOf('w')));
					west = true;
				}
				else{
					Lonsecs = parseInt(Lonsecs.substring(0, Lonsecs.toLowerCase().indexOf('e')));
				}
			}
	
			var lon = Londeg + (Lonmins/60) + (Lonsecs/3600);
			lon = west ? -(lon) : lon;
	
			var lat = Latdeg + (Latmins/60) + (Latsecs/3600);
			lat = south ? -(lat) : lat;
	
			var results = new Array();
			results['lat'] = lat;
			results['lon'] = lon;
	
			return results;
		}
		else{
			alert('Sorry, can\'t find ' + loc + '.');
			return false;
		}
		
	}
	
	//parse the items and convert to lat lon
	function DMM(loc){
		var values;
		
		if(loc.indexOf('%2C') != -1){
			values = loc.split('%2C');
		}
		else{
			//just putting ' ' didn't work for spaces
			values = loc.split('+');
		}

		if(values.length > 1){
			var Latdeg = (values[0].toLowerCase().indexOf('n') != -1 || (values[0].toLowerCase().indexOf('s') != -1)) ? values[0] : parseInt(values[0]);
			var Latmins = (values[1].toLowerCase().indexOf('n') != -1 || (values[1].toLowerCase().indexOf('s') != -1)) ? values[1] : parseInt(values[1]);
			var south = false;
			var west = false;
			var Londeg, Lonmins;
	
			if(loc.toLowerCase().indexOf('and') != -1){
				Londeg = (values[3].toLowerCase().indexOf('w') != -1 || (locvalues[3].toLowerCase().indexOf('e') != -1)) ? values[3] : parseInt(values[3]);
				Lonmins = (values[4].toLowerCase().indexOf('w') != -1 || (values[4].toLowerCase().indexOf('e') != -1)) ? values[4] : parseInt(values[4]);
			}
			else{
				Londeg = (values[2].toLowerCase().indexOf('w') != -1 || (values[2].toLowerCase().indexOf('e') != -1)) ? values[2] : parseInt(values[2]);
				Lonmins = (values[3].toLowerCase().indexOf('w') != -1 || (values[3].toLowerCase().indexOf('e') != -1)) ? values[3] : parseInt(values[3]);
			}
	
			
			if(Londeg == null || Lonmins == null || Latdeg == null || Latmins == null){
				alert('Improper formatting. Example of proper formatting is: 41 25 01N and 120 58 57W or N41 25 01 W120 58 57.');
			}
	
			//Find and pull the Letter coordinates out of the variables
			if(typeof Latdeg == 'string'){
				if(Latdeg.toLowerCase().indexOf('s') != -1){
					Latdeg = parseInt(Latdeg.substring(1));
					south = true;
				}
				else{
					Latdeg = parseInt(Latdeg.substring(1));
				}
			}
			if(typeof Latmins == 'string'){
				if(Latmins.toLowerCase().indexOf('s') != -1){
					Latmins = parseInt(Latmins.substring(0, Latmins.toLowerCase().indexOf('s')));
					south = true;
				}
				else{
					Latmins = parseInt(Latmins.substring(0, Latmins.toLowerCase().indexOf('n')));
				}
			}
			if(typeof Londeg == 'string'){
				if(Londeg.toLowerCase().indexOf('w') != -1){
					Londeg = parseInt(Londeg.substring(1));
					west = true;
				}
				else{
					Londeg = parseInt(Londeg.substring(1));
				}
			}
			if(typeof Lonmins == 'string'){
				if(Lonmins.toLowerCase().indexOf('w') != -1){
					Lonmins = parseInt(Lonmins.substring(0, Lonmins.toLowerCase().indexOf('w')));
					west = true;
				}
				else{
					Lonmins = parseInt(Lonmins.substring(0, Lonmins.toLowerCase().indexOf('e')));
				}
			}
	
			var lon = Londeg + (Lonmins/60);
			lon = west ? -(lon) : lon;
	
			var lat = Latdeg + (Latmins/60);
			lat = south ? -(lat) : lat;
	
			var results = new Array();
			results['lat'] = lat;
			results['lon'] = lon;
	
			return results;
		}
		else{
			alert('Sorry, can\'t find ' + loc + '.');
			return false;
		}
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

	<?php
			if(isset($_GET['coordinates'])){
		?>
			if(path_info == 'reports'){
				$('a.map').click();
			}
			searchLocation('<?php echo $_GET['coordinates']?>', '<?php echo $_GET['search']?>');
		<?php }?>
});
	
//
</script>

<link rel="stylesheet" type="text/css" href="<?php echo url::base(); ?>plugins/searchlocation/media/css/searchLocationCSS.css"/>




