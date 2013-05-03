<?php defined('SYSPATH') or die('No direct script access.');
/***********************************************************
* mapmeasure_js.php - Javascript for Map Measure Plugin
* This software is copy righted by WatchTheMed 2013
* Writen by Dylan Gillespie, Etherton Technologies <http://ethertontech.com>
* Started on 2013-04-30
* This plugin is to add a ruler tool to the maps.
*************************************************************/
?>

<script type="text/javascript">	
	var path_info = '<?php echo ((strpos(url::current(), 'reports/view')) !== false) ? substr(url::current(), 0, strlen('reports/view')) : url::current(); 	?>';

	function loadWide(){
		switch(path_info){
		case 'main':
			map_div = 'map';
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
			$('a.wider-map').click();
			break;
		}    
		
	}

</script>

<body onload='loadWide()'>