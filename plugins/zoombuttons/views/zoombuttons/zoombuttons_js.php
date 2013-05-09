<?php defined('SYSPATH') or die('No direct script access.');
/***********************************************************
* zoombuttons_js.php - Javascript for Zoom Buttons Plugin
* This software is copy righted by WatchTheMed 2013
* Writen by Dylan Gillespie, Etherton Technologies <http://ethertontech.com>
* Started on 2013-09-05
* This plugin changes Ushahidi zoom buttons to the specifications of WTM.
*************************************************************/
?>

<script type="text/javascript">		
	var path_info = '<?php 
		if ((strpos(url::current(), 'reports/view')) !== false){ echo 'reports/view';}
		elseif ((strpos(url::current(), 'admin/reports/edit')) !== false){ echo 'admin/reports/edit';}
		else {echo url::current(); }	?>';
	var map_div = '';
	var my_map = null;
	var map_expand = false;
	var buttons_exists = false;
	console.log('buttons!');

	$(document).ready(function(){
		$('a.map').click(function(){
			if(!map_expand){
				if(!buttons_exists){
					zoomButtons();
				}
				else{
					$('#rulerControl').show();
				}
				map_expand = true;
			}
		});
		$('a.list').click(function(){
			if(map_expand){
				map_expand = false;
				$('#rulerControl').hide();
			}
		});
	});

	function zoomButtons(){
		buttons_exists = true;
		switch(path_info){
		case 'main':
			map_div = 'map';
			my_map = map._olMap;
			//stops map from moving when this is active
			//$('.filters').css({"margin":"0"});
			break;
		case 'reports/submit':
			map_div = 'divMap';
			my_map = map;
			//$('.report_left').css({"margin":"0"});
			break;
		case 'reports':
			map_div = 'rb_map-view';
			my_map = map;
			//$('.rb_list-and-map-box').wrap('<div class="rulerOffSet" style="position:relative; top:-19px"/>');
			//$('.rulerOffSet').next().css({"position":"relative", "top":"-19px"});
			
			break;
		case 'reports/view':
			map_div = 'map';
			my_map = myMap;
			//console.log(myMap);
			break;
		case 'admin/reports/edit':
			map_div = 'divMap';
			my_map = myMap;
			break;
			
		}    
		$('#'+map_div).before(
			'<div id="zoomControls"><img class="zoomIn" src="<?php echo URL::base();?>plugins/zoombuttons/media/img/img_trans.gif" width="40" height="40"/>\
			<div id="zoomIn"></div></div>\
			');
		var zoom_in = new OpenLayers.Control.ZoomIn({
			div: document.getElementById('zoomIn') });
		my_map.addControl(zoom_in);
	}
	jQuery(window).load(function() {
		zoomButtons();
	});
</script>
