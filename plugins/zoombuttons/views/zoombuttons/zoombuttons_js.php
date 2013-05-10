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

	$(document).ready(function(){
		$('a.map').click(function(){
			if(!map_expand){
				if(!buttons_exists){
					zoomButtons();
				}
				map_expand = true;
			}
		});
	});

	function zoomButtons(){
		buttons_exists = true;
		var zoomIn = $('.olControlZoomIn');
		var zoomOut = $('.olControlZoomOut');
		var path = '<?php echo URL::base()?>/plugins/zoombuttons/media/img/';
		zoomIn.removeAttr('style');
		zoomOut.removeAttr('style');
		zoomIn.removeClass('olButton');
		zoomOut.removeClass('olButton');
		zoomIn.text('');
		zoomOut.text('');
		var inPath = 'url("' + path + 'Zoomin.png") -1px 0px';
		zoomIn.css({"src":"<?php echo URL::base();?>plugins/zoombuttons/media/img/img_trans.gif",
			 "width" :"30",
			 "height":"20",
			 "background": inPath
		});
		zoomOut.css({"src":"<?php echo URL::base();?>plugins/zoombuttons/media/img/img_trans.gif",
			 "width" :"30",
			 "height":"20",
			 "background": inPath
		});
		
	}

	
	jQuery(window).load(function() {
		zoomButtons();
	});
</script>
