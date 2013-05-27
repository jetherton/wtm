<?php defined('SYSPATH') or die('No direct script access.');
/***********************************************************
* allmaps_js.php - Javascript for All Maps Plugin
* This software is copy righted by WatchTheMed 2013
* Writen by Dylan Gillespie, Etherton Technologies <http://ethertontech.com>
* Started on 2013-30-04
* This plugin is to add a ruler tool to the maps.
*************************************************************/
?>

<script type="text/javascript" src="http://www.openstreetmap.org/openlayers/OpenStreetMap.js"></script>

<script type="text/javascript">	
	var path_info = '<?php 
		if ((strpos(url::current(), 'reports/view')) !== false){ echo 'reports/view';}
		elseif ((strpos(url::current(), 'admin/reports/edit')) !== false){ echo 'admin/reports/edit';}
		else {echo url::current(); }	?>';
	var map_div = '';
	var my_map = null;
	var all_maps = new Array();

	//listener for reports page
	$(document).ready(function(){
		$('a.map').click(function(){
				addAllLayers();
		});
	});

    function addAllLayers(){   
        //this pulls the layer variables     
        <?php echo map::layers_js(TRUE);?>
      
      	//all the types of layers
        all_maps[0] = esri_topo;
        all_maps[1] = esri_street;
        all_maps[2] = esri_imagery;
        all_maps[3] = esri_natgeo;
        all_maps[4] = google_satellite;
        all_maps[5] = google_hybrid;
        all_maps[6] = google_normal;
        all_maps[7] = google_physical;
        all_maps[8] = bing_road;
        all_maps[9] = bing_hybrid;
        all_maps[10] = bing_satellite;
        all_maps[11] = osm_mapnik;
        all_maps[12] = osm_cycle;
        all_maps[13] = osm_TransportMap;
        
        
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
		case 'reports/view':
			map_div = 'map';
			my_map = myMap;
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
		for(var m = 0; m < all_maps.length; m++){
			var preExistingLayers = my_map.getLayersByName(all_maps[m].name);
			if(preExistingLayers == 0){
				my_map.addLayer(all_maps[m]);
			}
		}
    }

    //load the google API
    if(typeof(google) == "undefined"){
	    window.google = window.google || {};
	    google.maps = google.maps || {};
	    (function() {
	      
	      function getScript(src) {
	        document.write('<' + 'script src="' + src + '"' +
	                       ' type="text/javascript"><' + '/script>');
	      }
	      
	      var modules = google.maps.modules = {};
	      google.maps.__gjsload__ = function(name, text) {
	        modules[name] = text;
	      };
	      
	      google.maps.Load = function(apiLoad) {
	        delete google.maps.Load;
	        apiLoad([0.009999999776482582,[[["https://mts0.googleapis.com/vt?lyrs=m@216000000\u0026src=api\u0026hl=en-US\u0026","https://mts1.googleapis.com/vt?lyrs=m@216000000\u0026src=api\u0026hl=en-US\u0026"],null,null,null,null,"m@216000000"],[["https://khms0.googleapis.com/kh?v=128\u0026hl=en-US\u0026","https://khms1.googleapis.com/kh?v=128\u0026hl=en-US\u0026"],null,null,null,1,"128"],[["https://mts0.googleapis.com/vt?lyrs=h@216000000\u0026src=api\u0026hl=en-US\u0026","https://mts1.googleapis.com/vt?lyrs=h@216000000\u0026src=api\u0026hl=en-US\u0026"],null,null,"imgtp=png32\u0026",null,"h@216000000"],[["https://mts0.googleapis.com/vt?lyrs=t@131,r@216000000\u0026src=api\u0026hl=en-US\u0026","https://mts1.googleapis.com/vt?lyrs=t@131,r@216000000\u0026src=api\u0026hl=en-US\u0026"],null,null,null,null,"t@131,r@216000000"],null,null,[["https://cbks0.googleapis.com/cbk?","https://cbks1.googleapis.com/cbk?"]],[["https://khms0.googleapis.com/kh?v=75\u0026hl=en-US\u0026","https://khms1.googleapis.com/kh?v=75\u0026hl=en-US\u0026"],null,null,null,null,"75"],[["https://mts0.googleapis.com/mapslt?hl=en-US\u0026","https://mts1.googleapis.com/mapslt?hl=en-US\u0026"]],[["https://mts0.googleapis.com/mapslt/ft?hl=en-US\u0026","https://mts1.googleapis.com/mapslt/ft?hl=en-US\u0026"]],[["https://mts0.googleapis.com/vt?hl=en-US\u0026","https://mts1.googleapis.com/vt?hl=en-US\u0026"]],[["https://mts0.googleapis.com/mapslt/loom?hl=en-US\u0026","https://mts1.googleapis.com/mapslt/loom?hl=en-US\u0026"]],[["https://mts0.googleapis.com/mapslt?hl=en-US\u0026","https://mts1.googleapis.com/mapslt?hl=en-US\u0026"]],[["https://mts0.googleapis.com/mapslt/ft?hl=en-US\u0026","https://mts1.googleapis.com/mapslt/ft?hl=en-US\u0026"]]],["en-US","US",null,0,null,null,"https://maps.gstatic.com/mapfiles/","https://csi.gstatic.com","https://maps.googleapis.com","https://maps.googleapis.com"],["https://maps.gstatic.com/intl/en_us/mapfiles/api-3/10/21","3.10.21"],[1126598928],1.0,null,null,null,null,0,"",null,null,1,"https://khms.googleapis.com/mz?v=128\u0026",null,"https://earthbuilder.googleapis.com","https://earthbuilder.googleapis.com",null,"https://mts.googleapis.com/vt/icon"], loadScriptTime);
	      };
	      //even though loadScriptTime doesn't look like it is used, it is
	      var loadScriptTime = (new Date).getTime();
	      getScript("https://maps.gstatic.com/intl/en_us/mapfiles/api-3/10/21/main.js");
	    })();
	    //end Google API
    }
    

jQuery(window).load(function() {
	if(path_info != 'reports'){
		addAllLayers();
	}
});
	
	
</script>

