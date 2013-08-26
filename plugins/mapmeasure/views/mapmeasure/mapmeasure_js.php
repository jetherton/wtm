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
	var zoomBox;
	//conversion for nautical miles
	var nautMile = 0.539957;
	var order = 1;
	var nautChecked = false;
	var kiloChecked = false;
	var measureDeactivateAll = null;
	var measureControls = null;

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
    function deactivateAll(runRemotely){
	
		//hide the Find Location stuff
		$('#searchButtons').hide();
		
		clickOut.deactivate();
		clickIn.deactivate();
		zoomBox.deactivate();
		$('#'+map_div).css({
			'cursor': "default"
		});
		$('#output').hide();
		
		$('#distanceOptionsDiv').hide();
		for(key in measureControls) {
		    var control = measureControls[key];
		    control.deactivate();
		    my_map.removeControl(control);
	        }
		//incase we are on the edit or submit page
		if( typeof turnOffControls != "undefined" && turnOffControls != null && typeof runRemotely == "undefined"){
		    turnOffControls(true);
		}
		$('#clickOut').removeClass("active");
		$('#clickIn').removeClass("active");
		$('#lineDraw').removeClass("active");
		$('#areaDraw').removeClass("active");
		$('#noDraw').removeClass("active");
		
    }
    
    measureDeactivateAll = deactivateAll;

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
						<div value="None" title="<?php echo Kohana::lang('mapmeasure.noMeasure')?>" id="noDraw" class="active">\
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
		$('#nautMile').click(function(){
			if(!nautChecked){
				nautChecked = true;
				kiloChecked = false;
				var dis = parseFloat($('#output').text().substring(10));
				dis *= nautMile;
				var out = '';
				if(order == 1) {
		            out += "Distance: " + dis + " NM";
		        } else {
		        	 out += "Distance: " + dis + " NM" + "<sup>2</sup>";
		        }
				document.getElementById('output').innerHTML = out;
			}
		});	
		$('#kilometers').click(function(){
			if(!kiloChecked){
				kiloChecked = true;
				nautChecked = false;
				var dis = parseFloat($('#output').text().substring(10));
				dis /= nautMile;
				var out = '';
				if(order == 1) {
		            out += "Distance: " + dis + " km";
		        } else {
		        	 out += "Distance: " + dis + " km" + "<sup>2</sup>";
		        }
				document.getElementById('output').innerHTML = out;
			}
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
        order = event.order;
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
		    <div id="ZoomBoxHolder" class="olControlEditingToolbar"></div>'
	    );
	
	    if(path_info == "main"){
		$('#noDraw').after(	
		    '<div id="fullScreen" title="FullScreen"><img style="border-top-right-radius: 3px;border-bottom-right-radius: 3px;" class="fullScreen" src="<?php echo URL::base();?>plugins/mapmeasure/media/img/img_trans.gif" width="1" height="1"/></div>'
		);
		$('#noDraw img').css('border-top-right-radius', '0px');
		$('#noDraw img').css('border-bottom-right-radius', '0px');
		$("#toolbarControl").css("width","250px");
	    }
	    
	    zoomBox =  new OpenLayers.Control.ZoomBox();	
	    
	    var panelControls = [ zoomBox ];	
	    var container = document.getElementById("ZoomBoxHolder");
	    var panel2 = new OpenLayers.Control.Panel({
			       div:container,
			       displayClass: 'olControlEditingToolbar'
			    });
	    panel2.addControls(panelControls);	
	    my_map.addControl(panel2);
	    
	    $("#ZoomBoxHolder div").click(function (){
		deactivateAll();
		zoomBox.activate();
		console.log("hey");
		$('#'+map_div).css({
		    'cursor': "crosshair"
		});
		
	    });
	    

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
	    $(this).addClass("active");
	    $('#'+map_div).css({
		    'cursor': "url('<?php echo URL::base()?>plugins/mapmeasure/media/img/ZoomOut.png'), -moz-zoom-out"
	    });
		
	});	
	$('#clickIn').click(function(){
	    deactivateAll();
	    clickIn.activate();
	    $(this).addClass("active");
	    $('#'+map_div).css({
		    'cursor': "url('<?php echo URL::base()?>plugins/mapmeasure/media/img/ZoomIn.png'), -moz-zoom-in"
	    });
	});	
	
	$('#fullScreen').click(function(){
	    if($("#fullScreen").hasClass("active")){
		$("#fullScreen").removeClass("active")
		$("#mainmenu").show();
		$(".slider-holder").show();
		$("#graphWrapper").show();
		$(".content-container").show();
		$("#footer").show();
		$("#clearBoth").show();
		$("div.wrapper").css("width","960px");
		
		$("#page").css("height", "auto");
		$("div.wrapper").css("height", "auto");
		$("#page").css("height", "auto");
		$("#middle").css("height", "auto");
		$("div.background").css("height", "auto");
		$("#main").css("height", "auto");
		$("#mainmiddle").css("height", "auto");
		$("#content").css("height", "auto");
		$("#map").css("height", "592px");
		
		$("div#report-type-filter").css("position","relative");
		$("div#report-type-filter").css("top","-75px");
		$("div#report-type-filter").css("bottom","auto");

		$("#mapStatus").css("position","relative");
		$("#mapStatus").css("top","-40px");
		$("#mapStatus").css("bottom","auto");
		
		$("#report-map-filter-box").css("float","none");
		$("#report-map-filter-box").css("left","730px");
		
		$("#toolbarControl").css("top", "10px");
		$("#searchControl").css("top", "10px");
		
	    } else {
		$("#fullScreen").addClass("active")
		
		$("#mainmenu").hide();
		$(".slider-holder").hide();
		$("#graphWrapper").hide();
		$(".content-container").hide();
		$("#footer").hide();
		$("#clearBoth").hide();
		$("div.wrapper").css("width","100%");
		
		$("#page").css("height", "100%");
		$("div.wrapper").css("height", "100%");
		$("#page").css("height", "100%");
		$("#middle").css("height", "100%");
		$("div.background").css("height", "100%");
		$("#main").css("height", "100%");
		$("#mainmiddle").css("height", "100%");
		$("#content").css("height", "100%");
		$("#map").css("height", "100%");
		
		$("div#report-type-filter").css("position","absolute");
		$("div#report-type-filter").css("top", "auto");
		$("div#report-type-filter").css("bottom", "-55px");

		$("#mapStatus").css("position","absolute");
		$("#mapStatus").css("top","auto");
		$("#mapStatus").css("bottom", "-50px");
		
		$("#report-map-filter-box").css("float","right");
		$("#report-map-filter-box").css("left","-10px");

		$("#toolbarControl").css("top", "70px");
		
		$("#searchControl").css("top", "70px");
		
	    }
	    
	    my_map.updateSize();
	});
	
	$('#lineDraw').click(function() {
	    deactivateAll();
	    measureControls.line.activate();
	    $(this).addClass("active");
	    $('#'+map_div).css({
		    'cursor': "url('<?php echo URL::base()?>plugins/mapmeasure/media/img/rulerLine.png'), -moz-zoom-in"
	    });
	});
	
	$('#areaDraw').click(function() {
	    deactivateAll();
	    measureControls.polygon.activate();
	    $(this).addClass("active");
	    $('#'+map_div).css({
		    'cursor': "url('<?php echo URL::base()?>plugins/mapmeasure/media/img/rulerPoly.png'), -moz-zoom-in"
	    });
	});
	
	$('#ZoomBoxHolder').click(function() {
	    deactivateAll();
	    zoomBox.activate();
	    $('#'+map_div).css({
		    'cursor': "url('<?php echo URL::base()?>plugins/mapmeasure/media/img/ZoomToBox.png'), -moz-zoom-in"
	    });
	});
	
	$('#noDraw').click(function() {
	    deactivateAll();
	    $(this).addClass("active");
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


