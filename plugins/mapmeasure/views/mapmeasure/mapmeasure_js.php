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
	//variables to hold map zooming listeners
	var clickOut;
	var clickIn;
	var nautMile = 0.539957;

	$(document).ready(function(){
		$('a.map').click(function(){
			if(!map_expand){
				if(!ruler_exists){
					Ruler();
					zoomButtons();
				}
				else{
					$('#toolbarControl').show();
				}
				map_expand = true;
			}
		});
		$('a.list').click(function(){
			if(map_expand){
				map_expand = false;
				$('#toolbarControl').hide();
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

    //turn off all listeners
    function deactivateAll(){
		clickOut.deactivate();
		clickIn.deactivate();
		$('#'+map_div).css({
			'cursor': "default"
		});
		$('#output').hide();
		$('#distanceOptionsDiv').hide();
		for(key in measureControls) {
            var control = measureControls[key];
            control.deactivate();
            my_map.removeControl(control);
            //control.destroy();
        }
    }

	function createRuler(){
		//create the ruler buttons
		$('#'+map_div).before(
				'<div style="position:absolute;">\
				<div id="toolbarControl">\
						<div title="<?php echo Kohana::lang('mapmeasure.lineMeasure')?>" id="lineDraw" onclick="toggleControl(this)">\
							<img class="rulerIcon" src="<?php echo URL::base();?>plugins/mapmeasure/media/img/img_trans.gif" width="1" height="1"/>\
						</div>\
						<div title="<?php echo Kohana::lang('mapmeasure.areaMeasure')?>" id="areaDraw" onclick="toggleControl(this)">\
							<img class="areaIcon" src="<?php echo URL::base();?>plugins/mapmeasure/media/img/img_trans.gif" width="1" height="1"/>\
						</div>\
						<div value="None" title="<?php echo Kohana::lang('mapmeasure.noMeasure')?>" id="noDraw">\
							<img class="dragIcon" src="<?php echo URL::base();?>plugins/mapmeasure/media/img/img_trans.gif" width="1" height="1"/>\
						</div>\
						</br><div id="distanceOptionsDiv">\
								<input type="radio" id="kilometers" name="distanceOptions" checked> <?php echo Kohana::lang('mapmeasure.kilometers')?>\
								<input type="radio" name="distanceOptions" id="nautMile"> <?php echo Kohana::lang('mapmeasure.nautMile')?>\
							</div>\
						</br><div id = "output"></div>\
				</div>\
				');
		$('#lineDraw').val('line');
		$('#areaDraw').val('polygon');
		$('#noDraw').click(function(){
			deactivateAll();
		});		
	}

    var measureControls;
    function Ruler(){        
        ruler_exists = true;
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
            my_map = map;
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

           my_map.addControl(control);
        }
        for(key in measureControls) {
            var control = measureControls[key];
            control.setImmediate(true);
        }

    }
    
    function handleMeasurements(event) {
        var units = ($('#kilometers').is(':checked')) ? event.units : 'NM';
        var order = event.order;
        var measure = event.measure;
        var element = document.getElementById('output');
        var out = "";
        var kind = ($('#kilometers').is(':checked')) ? 1 : nautMile;
        if(order == 1) {
            out += "Distance: " + measure.toFixed(3)*kind + " " + units;
        } else {
            out += "Distance: " + measure.toFixed(3)*kind + " " + units + "<sup>2</sup>";
        }
        element.innerHTML = out;
        $('#output').show();
        $('#distanceOptionsDiv').show();
        
    }

    function toggleControl(element) {
        clickIn.deactivate();
        clickOut.deactivate();
        
        $('#'+map_div).css({
			'cursor': "default"
		});
        for(key in measureControls) {
            var control = measureControls[key];
            if(element.value == key) {
                control.activate();
            } else {
                control.deactivate();
            }
        }
        //console.log(my_map);
    }
 


  //create the ZoomButtons
	function zoomButtons(){
		$('.olControlZoom').hide();

		$('#lineDraw').before(
			'<div id="clickIn" title="Zoom in"><img class="zoomInIcon" src="<?php echo URL::base();?>plugins/mapmeasure/media/img/img_trans.gif" width="1" height="1"/></div>\
			<div id="clickOut" title="Zoom out"><img class="zoomOutIcon" src="<?php echo URL::base();?>plugins/mapmeasure/media/img/img_trans.gif" width="1" height="1"/></div>\
			'
  		);

        OpenLayers.Control.ClickOut = OpenLayers.Class(OpenLayers.Control, {                
            defaultHandlerOptions: {
                'single': true,
                'double': false,
                'pixelTolerance': 0,
                'stopSingle': false,
                'stopDouble': true
            },
            initialize: function(options) {
                this.handlerOptions = OpenLayers.Util.extend(
                    {}, this.defaultHandlerOptions
                );
                OpenLayers.Control.prototype.initialize.apply(
                    this, arguments
                ); 
                this.handler = new OpenLayers.Handler.Click(
                    this, {
                        'click': this.trigger
                    }, this.handlerOptions
                );
            }, 
            trigger: function(e) {
            	var lonlat = my_map.getLonLatFromPixel(e.xy);
            	my_map.setCenter(lonlat, my_map.getZoom() - 1);
            }
        });
        
        OpenLayers.Control.ClickIn = OpenLayers.Class(OpenLayers.Control, {                
            defaultHandlerOptions: {
                'single': true,
                'double': false,
                'pixelTolerance': 0,
                'stopSingle': false,
                'stopDouble': true
            },

            initialize: function(options) {
                this.handlerOptions = OpenLayers.Util.extend(
                    {}, this.defaultHandlerOptions
                );
                OpenLayers.Control.prototype.initialize.apply(
                    this, arguments
                ); 
                this.handler = new OpenLayers.Handler.Click(
                    this, {
                        'click': this.trigger
                    }, this.handlerOptions
                );
            }, 

            trigger: function(e) {
            	var lonlat = my_map.getLonLatFromPixel(e.xy);
            	my_map.setCenter(lonlat, my_map.getZoom() + 1);
            }

        });

        clickOut = new OpenLayers.Control.ClickOut();
        clickIn = new OpenLayers.Control.ClickIn();

        my_map.addControl(clickOut);
        my_map.addControl(clickIn);
        $('#clickOut').click(function(){
        	deactivateAll();
			clickOut.activate();
			$('#'+map_div).css({
				'cursor': "url('<?php echo URL::base()?>plugins/mapmeasure/media/img/mouseZoomOut.png'), -moz-zoom-out"
			});
			//console.log(my_map);
		});	
		$('#clickIn').click(function(){
			deactivateAll();
			clickIn.activate();
			$('#'+map_div).css({
				'cursor': "url('<?php echo URL::base()?>plugins/mapmeasure/media/img/mouseZoomIn.png'), -moz-zoom-in"
			});
			
		});	
  	}
    

jQuery(window).load(function() {
	if(path_info != 'reports'){
		Ruler();
		zoomButtons();
		
	}
});
	
	
</script>



<link rel="stylesheet" type="text/css" href="<?php echo url::base(); ?>plugins/mapmeasure/media/css/measureCSS.css"/>


