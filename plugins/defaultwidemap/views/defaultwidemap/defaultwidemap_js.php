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
			mainMapWide();
			break;
		case 'reports/submit':
			reportSubmitWide();
			break;
		case 'reports/view':
			$('a.wider-map').click();
			break;
		}    
		
	}

	function reportSubmitWide(){
		$('.report-find-location').prependTo($('.report_left'));
		$('#divMap').prependTo($('.report_left'));

		$('#divMap').width(900);
		$('.report-find-location').width(883);
		$('.report-find-location').height(50);
		$('#location_find').css({"float": "right","top": "-38px","position": "relative"});
		$('#button').css({"float": "right","top": "-38px","position": "relative"});
		$('#find_text').css({"position":"relative", "top":"-32px"});
		$('.report_right').css({"position":"relative", "top":"432px"});
		$('.report_optional').prependTo($('.btn_submit').parent());
		$('.big-block').height(1050);
		
		map.updateSize();
	}


	function mainMapWide(){
		$('.map').css({"height":"350px", "width": "900px"});
		$('#main').css({"height":"500px"});
		$('#mapStatus').css({"width":"900px"});
		$('.slider-holder').css({"width":"885px"});
		$('#content').css({"height":"500px", "width": "1200px"});
		$('#right').prependTo($('.content-container'));
		map._olMap.updateSize();
	}

	//Reports/ page not accepting listeners elsewhere
	$(document).ready(function(){
		$('a.map').click(function(){
			$('#reports-box').width(900);
			$('#rb_map-view').width(897);
			$('#filters-box').css({"float":"right"});
			map.updateSize();
		});
		$('a.list').click(function(){
			$('#reports-box').width(600);
			$('#filters-box').css({"float":"left"});
		});
	});

</script>

<body onload='loadWide()'>