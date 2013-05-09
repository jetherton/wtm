<?php defined('SYSPATH') or die('No direct script access.');
/***********************************************************
* mapmeasure_js.php - Javascript for Map Measure Plugin
* This software is copy righted by WatchTheMed 2013
* Writen by Dylan Gillespie, Etherton Technologies <http://ethertontech.com>
* Started on 2013-30-04
* This plugin is to add a ruler tool to the maps.
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
	var ruler_exists = false;

	$(document).ready(function(){
		$('a.map').click(function(){
			if(!map_expand){
				if(!ruler_exists){
					Ruler();
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
	// style the sketch fancy
    var sketchSymbolizers = {
        "Point": {
            pointRadius: 4,
            graphicName: "square",
            fillColor: "white",
            fillOpacity: 1,
            strokeWidth: 1,
            strokeOpacity: 1,
            strokeColor: "#333333"
        },
        "Line": {
            strokeWidth: 3,
            strokeOpacity: 1,
            strokeColor: "#666666",
            strokeDashstyle: "dash"
        },
        "Polygon": {
            strokeWidth: 2,
            strokeOpacity: 1,
            strokeColor: "#666666",
            fillColor: "white",
            fillOpacity: 0.3
        }
    };
    var style = new OpenLayers.Style();
    
    style.addRules([new OpenLayers.Rule({symbolizer: sketchSymbolizers})]);
    var styleMap = new OpenLayers.StyleMap({"default": style});
    
    // allow testing of specific renderers via "?renderer=Canvas", etc
    var renderer = OpenLayers.Util.getParameters(window.location.href).renderer;
    renderer = (renderer) ? [renderer] : OpenLayers.Layer.Vector.prototype.renderers;

    measureControls = {
        line: new OpenLayers.Control.Measure(
            OpenLayers.Handler.Path, {
                persist: true,
                handlerOptions: {
                    layerOptions: {
                        renderers: renderer,
                        styleMap: styleMap
                    }
                }
            }
        ),
        polygon: new OpenLayers.Control.Measure(
            OpenLayers.Handler.Polygon, {
                persist: true,
                handlerOptions: {
                    layerOptions: {
                        renderers: renderer,
                        styleMap: styleMap
                    }
                }
            }
        )
    };

	function createRuler(){
		//create the ruler buttons
		//console.log(map_div);
		$('#'+map_div).before(
				'<div id="rulerControl"><img class="rulerIcon" src="<?php echo URL::base();?>plugins/mapmeasure/media/img/img_trans.gif" width="1" height="1"/>\
				<div id="rulerDiv" style="display:none">\
					<input type="radio" value="line" name="ruler" title="Measure in a series of lines." id="lineDraw" onclick="toggleControl(this)"> Line</br>\
					<input type="radio" value="polygon" name="ruler" title="Measure within an area." id="areaDraw" onclick="toggleControl(this)"> Area</br>\
					<input type="radio" value="None" name="ruler" title="Turn off measuring." id="noDraw" onclick="toggleControl(this)"> None\
				</div>\
				<div id = "output"></div></div>\
				');
		//open the ruler buttons when clicked on
		$('#rulerControl').mouseenter(function(){
			$('#rulerDiv').show();
			$('#output').hide();
		});
		$('#rulerControl').mouseleave(function(){
			$('#rulerDiv').hide();
			//$('#output').show();
		});
		
	}


    var measureControls;
    function Ruler(){        
        ruler_exists = true;
    	switch(path_info){
		case 'main':
			map_div = 'map';
			my_map = map._olMap;
			//stops map from moving when this is active
			$('.filters').css({"margin":"0"});
			break;
		case 'reports/submit':
			map_div = 'divMap';
			my_map = map;
			$('.report_left').css({"margin":"0"});
			break;
		case 'reports':
			map_div = 'rb_map-view';
			my_map = map;
			$('.rb_list-and-map-box').wrap('<div class="rulerOffSet" style="position:relative; top:-19px"/>');
			$('.rulerOffSet').next().css({"position":"relative", "top":"-19px"});
			
			break;
		case 'reports/view':
			map_div = 'map';
			my_map = myMap;
			break;
		case 'admin/reports/edit':
			map_div = 'divMap';
			my_map = myMap;
			break;
			
		}    
        createRuler();
        var control;
        for(var key in measureControls) {
            control = measureControls[key];
            control.events.on({
                "measure": handleMeasurements,
                "measurepartial": handleMeasurements
            });
            //(path_info == 'reports/view') ? my_map.addEventListener(control) : my_map.addControl(control);
           my_map.addControl(control);
        }
        for(key in measureControls) {
            var control = measureControls[key];
            control.setImmediate(true);
        }

    }
    
    function handleMeasurements(event) {
        var units = event.units;
        var order = event.order;
        var measure = event.measure;
        var element = document.getElementById('output');
        var out = "";
        if(order == 1) {
            out += "Distance: " + measure.toFixed(3) + " " + units;
        } else {
            out += "Distance: " + measure.toFixed(3) + " " + units + "<sup>2</" + "sup>";
        }
        element.innerHTML = out;
    }

    function toggleControl(element) {
       	$('#rulerDiv').toggle();
       	if(element.id == 'noDraw'){
			$('#output').hide();
        }
       	else{
			$('#output').show();
       	}
        for(key in measureControls) {
            var control = measureControls[key];
            if(element.value == key && element.checked) {
                control.activate();
            } else {
                control.deactivate();
            }
        }
        
    }
    
    function toggleImmediate(element) {
        for(key in measureControls) {
            var control = measureControls[key];
            control.setImmediate(element.checked);
        }
    }

jQuery(window).load(function() {
	if(path_info != 'reports'){
		Ruler();
	}
});
	
	
</script>



<link rel="stylesheet" type="text/css" href="<?php echo url::base(); ?>plugins/mapmeasure/media/css/measureCSS.css"/>


