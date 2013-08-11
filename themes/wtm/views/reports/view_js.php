<?php
/**
 * Reports view js file.
 *
 * Handles javascript stuff related to reports view function.
 *
 * PHP version 5
 * LICENSE: This source file is subject to LGPL license 
 * that is available through the world-wide-web at the following URI:
 * http://www.gnu.org/copyleft/lesser.html
 * @author     Ushahidi Team <team@ushahidi.com> 
 * @package    Ushahidi - http://source.ushahididev.com
 * @module     Reports Controller
 * @copyright  Ushahidi - http://www.ushahidi.com
 * @license    http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL) 
 */
?>

var myMap = null;
var bathymetry = null;

// Set the base url
Ushahidi.baseURL = "<?php echo url::site(); ?>";

jQuery(window).load(function() {

	<?php echo map::layers_js(FALSE); ?>

	// Configuration for the map
	var mapConfig = {

		// Zoom level
		zoom: <?php echo ($incident_zoom) ? $incident_zoom : intval(Kohana::config('settings.default_zoom')); ?>,

		// Map center
		center: {
			latitude: <?php echo $latitude; ?>,
			longitude: <?php echo $longitude; ?>
		},

		// Map controls
		mapControls: [
			new OpenLayers.Control.Navigation({ dragPanOptions: { enableKinetic: true } }),
			new OpenLayers.Control.Zoom(),
			new OpenLayers.Control.MousePosition(),
			new OpenLayers.Control.ScaleLine(),
			new OpenLayers.Control.Scale('mapScale'),
			new OpenLayers.Control.LayerSwitcher(),
			new OpenLayers.Control.Attribution()
		],

		// Base layers
		baseLayers: <?php echo map::layers_array(FALSE); ?>

	};

	// Set Feature Styles
	var style1 = new OpenLayers.Style({
				pointRadius: "8",
				fillColor: "${fillColor}",
				fillOpacity: "${fillOpacity}",
				strokeColor: "${strokeColor}",
				strokeWidth: "${strokeWidth}",
				strokeOpacity: "${strokeOpacity}",
				strokeDashstyle: "${strokeDashstyle}",
				graphicZIndex: '${graphicZIndex}',
				externalGraphic: "${icon}",
				graphicOpacity: 1,
				graphicWidth: "${iconWidth}",
				graphicHeight: "${iconHeight}",
				graphicXOffset: "${iconOffsetX}",
				graphicYOffset: "${iconOffsetY}",
				
				label : '${label}',                    
				
				fontSize: '${fontSize}',
				fontColor: '${fontColor}',
				fontFamily: "Arial, Helvetica, sans-serif",
				fontWeight: "bold",
				labelOutlineColor: '${labelOutlineColor}',
				labelOutlineWidth: '${labelOutlineWidth}',
				labelYOffset: '${labelYOffset}'
			},{
				context: {
				    label: function(feature) {
						if(typeof feature.attributes.label == "undefined"){
						    return "";
						} else {
						    return feature.attributes.label;
						}
						
					},
				    icon: function(feature) {
						if(typeof feature.attributes.icon == "undefined"){
						    return "<?php echo url::base();?>themes/wtm/images/greendot.png";
						} else {
						    return feature.attributes.icon;
						}	
					},
				    iconWidth: function(feature) {
						if(typeof feature.attributes.icon == "undefined"){
						    return 20;
						} else if(feature.attributes.icon.indexOf("incident_circle") != -1 ||
						    feature.attributes.icon.indexOf("location_square") != -1 ||
						    feature.attributes.icon.indexOf("marker.png") != -1) {
						    return 10;
						} else if(feature.attributes.icon.indexOf("wreck_cross.png") != -1){
						    return 20;
						} else if(feature.attributes.icon.indexOf("clear_rect32x14.png") != -1) {
						    return 42;
						} else {
						    return 40;
						}	
					},
				    iconHeight: function(feature) {
						if(typeof feature.attributes.icon == "undefined"){						
						    return 11;
						} else if(feature.attributes.icon.indexOf("clear_rect32x14.png") != -1) {
						    return 17;
						} else if(feature.attributes.icon.indexOf("incident_circle") != -1 ||
						    feature.attributes.icon.indexOf("location_square") != -1 ||
						    feature.attributes.icon.indexOf("marker.png") != -1) {
						    return 10;
						} else if(feature.attributes.icon.indexOf("wreck_cross.png") != -1){
						    return 20;
						}else {
						    return 30;
						}	
					},
				    iconOffsetX: function(feature) {
						if(typeof feature.attributes.icon == "undefined"){
						    return -5;
						} else if(feature.attributes.icon == "<?php echo url::base();?>media/img/openlayers/incident_circle.png" ||
						    feature.attributes.icon == "<?php echo url::base();?>media/img/openlayers/location_square.png") {
						    return -5;
						} else if( feature.attributes.icon == "<?php echo url::base();?>media/img/openlayers/wreck_cross.png"){
						    return -10;
						} else if(feature.attributes.icon.indexOf("clear_rect32x14.png") != -1) {
						    return -20;
						} else {
						   return -20;
						}
					},
				    iconOffsetY: function(feature) {
						if(typeof feature.attributes.icon == "undefined"){
						    return -5;
						} else if(feature.attributes.icon == "<?php echo url::base();?>media/img/openlayers/incident_circle.png" ||
						    feature.attributes.icon == "<?php echo url::base();?>media/img/openlayers/location_square.png" ) {
						    return -5;
						} else if( feature.attributes.icon == "<?php echo url::base();?>media/img/openlayers/wreck_cross.png"){
						    return -10;
						} else if(feature.attributes.icon.indexOf("clear_rect32x14.png") != -1) {
						    return -10;
						} else {
						    return -15;
						}	
					},
				    labelYOffset: function(feature) {
						if(typeof feature.attributes.icon == "undefined"){
						    return -5;
						} else if(feature.attributes.icon == "<?php echo url::base();?>media/img/openlayers/incident_circle.png" ||
						    feature.attributes.icon == "<?php echo url::base();?>media/img/openlayers/location_square.png" ||
						    feature.attributes.icon == "<?php echo url::base();?>media/img/openlayers/wreck_cross.png") {
						    return -20;
						} else if(feature.attributes.icon.indexOf("clear_rect32x14.png") != -1) {
						    return 0;
						} else {
						    return -25;
						}	
					},
				    fontSize: function(feature){
					    if(typeof feature.attributes.fontSize == "undefined"){
						return 12;
					    } else {
						return feature.attributes.fontSize;
					    }
					},
				    fontColor: function(feature){
					    if(typeof feature.attributes.fontColor == "undefined"){
						return '#ffffff';
					    } else {
						return feature.attributes.fontColor;
					    }
					},
				labelOutlineColor : function(feature){
					    if(typeof feature.attributes.labelOutlineColor == "undefined"){
						return '#000000';
					    } else {
						return feature.attributes.labelOutlineColor;
					    }
					},
				labelOutlineWidth: function(feature){
					    if(typeof feature.attributes.labelOutlineWidth == "undefined"){
						return '2';
					    } else {
						return feature.attributes.labelOutlineWidth;
					    }
					},
				strokeColor : function(feature){
					    if(typeof feature.attributes.strokeColor == "undefined"){
						return '#CC0000';
					    } else {
						return feature.attributes.strokeColor;
					    }
					},
				fillOpacity : function(feature){
					    if(typeof feature.attributes.fillOpacity == "undefined"){
						return '0.7';
					    } else {
						return feature.attributes.fillOpacity;
					    }
					},
				strokeOpacity : function(feature){
					    if(typeof feature.attributes.strokeOpacity == "undefined"){
						return '1';
					    } else {
						return feature.attributes.strokeOpacity;
					    }
					},
				fillColor : function(feature){
					    if(typeof feature.attributes.fillColor == "undefined"){
						return '#ffcc66';
					    } else {
						return feature.attributes.fillColor;
					    }
					},
				strokeWidth : function(feature){
					    if(typeof feature.attributes.strokeWidth == "undefined"){
						return 2.5;
					    } else {
						return feature.attributes.strokeWidth;
					    }
					},
				strokeDashstyle : function(feature){
					    if(typeof feature.attributes.strokeDashstyle == "undefined"){
						return 'solid';
					    } else {
						return feature.attributes.strokeDashstyle;
					    }
					},
				graphicZIndex : function(feature){
					if(typeof feature.attributes.graphicZIndex == "undefined"){
							return 1;
						}
						else{
						    //console.log(feature.attributes.graphicZIndex + " "+ feature.attributes.label);
							return feature.attributes.graphicZIndex;
						}
					}
				}
			});

	var style2 = new OpenLayers.Style({
		pointRadius: "8",
		fillColor: "#30E900",
		fillOpacity: "0.7",
		strokeColor: "#197700",
		strokeWidth: 3,
		//graphicZIndex: 1
	});


	// Styles to use for rendering the markers
	var styleMap = new OpenLayers.StyleMap({
		'default': style1,
		'select': style1,
		'temporary': style2
	});


	// Initialize the map
	var map = new Ushahidi.Map('map', mapConfig);
	map.addLayer(Ushahidi.GEOJSON, {
		name: "Single Report",
		url: "<?php echo 'json/single/'.$incident_id; ?>",
		styleMap: styleMap,
		rendererOptions: {zIndexing: true}
	});
	 myMap = map._olMap;
	 
	 
	 
	 
	 
	 
	 //bathymetry layer	
	var proj_4326 = new OpenLayers.Projection('EPSG:4326');
	var topLeft = new OpenLayers.Geometry.Point(0, 45);
	OpenLayers.Projection.transform(topLeft, proj_4326, myMap.getProjectionObject());
	var bottomRight = new OpenLayers.Geometry.Point(32, 30);
	OpenLayers.Projection.transform(bottomRight, proj_4326, myMap.getProjectionObject());

	bathymetry = new OpenLayers.Layer.Image(
	    'Bathymetry',
	    '<?php echo url::base();?>media/img/openlayers/bathymetry.jpg',
	    new OpenLayers.Bounds(topLeft.x, bottomRight.y, bottomRight.x, topLeft.y),
	    new OpenLayers.Size(1280, 850),
	     {'isBaseLayer': false, 'alwaysInRange': true}
	);
		

	myMap.addLayer(bathymetry);
	bathymetry.setVisibility(false);
         
         
         
         
         
         
         
        // Layer selection
	$("ul#kml_switch li > a.toggleLayer").click(function(e) {
		// Get the layer id
		var layerId = this.id.substring(6);
		

		var isCurrentLayer = false;
		var context = this;

		// Remove all actively selected layers
		$("#kml_switch a").each(function(i) {
			if ($(this).hasClass("active")) {
				if (this.id == context.id) {
					isCurrentLayer = true;
				}
			}
		});
		//remove the layer if it was clicked again
		if(isCurrentLayer && layerId != "bath"){
			map.trigger("deletelayer", $(".layer-name", this).html());
				$(this).removeClass("active");
				$("#meta_layer_click_"+layerId).removeClass("layerActive");
		}
		
		// Was a different layer selected?
		if (!isCurrentLayer && layerId != "bath") {
			// Set the currently selected layer as the active one
			$(this).addClass("active");
			$("#meta_layer_click_"+layerId).addClass("layerActive");
			map.addLayer(Ushahidi.KML, {
				name: $(".layer-name", this).html(),
				url: "json/layer/" + layerId
			});
		}
		console.log(layerId);
		if(layerId == "bath"){
		    
		    if(bathymetry.visibility){
			$(this).removeClass("active");
			$("#meta_layer_click_"+layerId).removeClass("layerActive");
			bathymetry.setVisibility(false);
		    } else {
			$(this).addClass("active");
			$("#meta_layer_click_"+layerId).addClass("layerActive");
			bathymetry.setVisibility(true);
		    }
		}

		return false;
	});
	
	$("ul#kml_switch li > a.layer_meta_clicker").click(function(e) {
	    
	    var layerId = this.id.substring(17);
	    if($(this).hasClass("active")){
		$(this).removeClass("active");
		$("#layerMetaInfo_"+layerId).hide();
	    } else {
		$(this).addClass("active");
		$("#layerMetaInfo_"+layerId).show();
	    }
	});
	
	
	// Ajax Validation for the comments
	$("#commentForm").validate({
		rules: {
			comment_author: {
				required: true,
				minlength: 3
			},
			comment_email: {
				required: true,
				email: true
			},
			comment_description: {
				required: true,
				minlength: 3
			},
			captcha: {
				required: true
			}
		},
		messages: {
			comment_author: {
				required: "Please enter your Name",
				minlength: "Your Name must consist of at least 3 characters"
			},
			comment_email: {
				required: "Please enter an Email Address",
				email: "Please enter a valid Email Address"
			},
			comment_description: {
				required: "Please enter a Comment",
				minlength: "Your Comment must be at least 3 characters long"
			},
			captcha: {
				required: "Please enter the Security Code"
			}
		}
	});
	
	// Handles the functionality for changing the size of the map
	// TODO: make the CSS widths dynamic... instead of hardcoding, grab the width's
	// from the appropriate parent divs
	$('.map-toggles a').click(function() {
		var action = $(this).attr("class");
		$('ul.map-toggles li').hide();
		switch(action)
		{
			case "wider-map":
				$('.report-map').insertBefore($('.left-col'));
				$('.map-holder').css({"height":"310px", "width": "960px"});
				$('a[href=#report-map]').parent().hide();
				$('a.taller-map').parent().show();
				$('a.smaller-map').parent().show();
				$('body.page-reports-view div#layers-box').show();
				break;
			case "taller-map":
				$('.map-holder').css("height","600px");
				$('a.shorter-map').parent().show();
				$('a.smaller-map').parent().show();
				$('body.page-reports-view div#layers-box').show();
				break;
			case "shorter-map":
				$('.map-holder').css("height","350px");
				$('a.taller-map').parent().show();
				$('a.smaller-map').parent().show();
				$('body.page-reports-view div#layers-box').show();
				break;
			case "smaller-map":
				$('.report-map').hide().prependTo($('.report-media-box-content'));
				$('.map-holder').css({"height":"350px", "width": "310px"});
				$('a.wider-map').parent().show();
				$('.report-map').show();
				$('body.page-reports-view div#layers-box').hide();
				break;
		};
		
		map.trigger("resize");
		return false;
	});

}); // END jQuery(window).load();	











function rating(id,action,type,loader) {
	$('#' + loader).html('<img src="<?php echo url::file_loc('img')."media/img/loading_g.gif"; ?>">');
	$.post("<?php echo url::site().'reports/rating/' ?>" + id, { action: action, type: type },
		function(data){
			if (data.status == 'saved'){
				if (type == 'original') {
					$('#oup_' + id).attr("src","<?php echo url::file_loc('img').'media/img/'; ?>gray_up.png");
					$('#odown_' + id).attr("src","<?php echo url::file_loc('img').'media/img/'; ?>gray_down.png");
					$('#orating_' + id).html(data.rating);
				}
				else if (type == 'comment')
				{
					$('#cup_' + id).attr("src","<?php echo url::file_loc('img').'media/img/'; ?>gray_up.png");
					$('#cdown_' + id).attr("src","<?php echo url::file_loc('img').'media/img/'; ?>gray_down.png");
					$('#crating_' + id).html(data.rating);
				}
			} else {
				if(typeof(data.message) != 'undefined') {
					alert(data.message);
				}
			}
			$('#' + loader).html('');
	  	}, "json");
}
		


