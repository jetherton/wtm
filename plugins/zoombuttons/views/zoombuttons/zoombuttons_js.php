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
	var map_expandButtons = false;
	var buttons_exists = false;

	//listen for the reports page map creation button
	$(document).ready(function(){
		$('a.map').click(function(){
			if(!map_expandButtons){
				if(!buttons_exists){
					zoomButtons();
					}
				}
				map_expandButtons = true;
			
		});
	});

	function zoomButtons(){
		buttons_exists = true;
		var zoomIn = $('.olControlZoomIn');
		var zoomOut = $('.olControlZoomOut');
		var zoomControl = $('.olControlZoom');
		var path = '<?php echo URL::base()?>/plugins/zoombuttons/media/img/';

		//remove any previous css that could get in the way
		zoomControl.css({'padding':'0', 'width' : '58px'});
		zoomIn.removeAttr('style');
		zoomOut.removeAttr('style');
		//remove the + and - text
		zoomIn.text('');
		zoomOut.text('');
		var inPath = 'url("' + path + 'Zoomin.png") -1px 0px';
		var outPath = 'url("' + path + 'Zoomout.png") -1px -1px';
		zoomIn.css({
			 "src":"<?php echo URL::base();?>plugins/zoombuttons/media/img/img_trans.gif",
			 "width" :"27",
			 "height":"18",
			 "background": inPath,
			 "position" : "relative",
			 "float" : "left"
		});
		zoomOut.css({
			 "src":"<?php echo URL::base();?>plugins/zoombuttons/media/img/img_trans.gif",
			 "width" :"27",
			 "height":"18",
			 "background": outPath,
			 "position" : "relative",
			 "float" : "right",
			 "left" : "-2px"
		});
		
	}

	
	jQuery(window).load(function() {
		if(path_info != 'reports'){zoomButtons();}
	});
</script>
