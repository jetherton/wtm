<?php
/**
 * Handles javascript stuff related to report creation and editing
 *
 * PHP version 5
 * LICENSE: This source file is subject to LGPL license 
 * that is available through the world-wide-web at the following URI:
 * http://www.gnu.org/copyleft/lesser.html
 *
 * @author     Ushahidi Team <team@ushahidi.com> 
 * @package    Ushahidi - http://source.ushahididev.com
 * @subpackage Reports
 * @copyright  Ushahidi - http://www.ushahidi.com
 * @license    http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL) 
 */
?>
		var map;
		var myMap = null;
		var thisLayer;
		var proj_4326 = new OpenLayers.Projection('EPSG:4326');
		var proj_900913 = new OpenLayers.Projection('EPSG:900913');
		var vlayer;
		var highlightCtrl;
		var selectCtrl;
		var selectedFeatures = [];
		var controls = null;
		var featureStyle = null;
		var polygonEditPoint = null;
		
		
		// jQuery Textbox Hints Plugin
		// Will move to separate file later or attach to forms plugin
		jQuery.fn.hint = function (blurClass) {
		  if (!blurClass) { 
		    blurClass = 'texthint';
		  }

		  return this.each(function () {
		    // Get jQuery version of 'this'
		    var $input = jQuery(this),

		    // Capture the rest of the variable to allow for reuse
		      title = $input.attr('title'),
		      $form = jQuery(this.form),
		      $win = jQuery(window);

		    function remove() {
		      if ($input.val() === title && $input.hasClass(blurClass)) {
		        $input.val('').removeClass(blurClass);
		      }
		    }

		    // Only apply logic if the element has the attribute
		    if (title) { 
			
		      // On blur, set value to title attr if text is blank
		      $input.blur(function () {
		        if (this.value === '') {
		          $input.val(title).addClass(blurClass);
		        }
		      }).focus(remove).blur(); // now change all inputs to title

		      // Clear the pre-defined text when form is submitted
		      $form.submit(remove);
		      $win.unload(remove); // handles Firefox's autocomplete
			  $(".btn_find").click(remove);
		    }
		  });
		};

		jQuery(window).load(function() {
			// Map options
			var options = {
				units: "dd",
				numZoomLevels: 18, 
				controls:[],
				theme: false,
				projection: proj_900913,
				'displayProjection': proj_4326,
				eventListeners: {
					"zoomend": incidentZoom
				},
				maxExtent: new OpenLayers.Bounds(-20037508.34, -20037508.34, 20037508.34, 20037508.34),
				maxResolution: 156543.0339
			};
			
			// Now initialise the map
			map = new OpenLayers.Map('divMap', options);
			myMap = map;
			<?php echo map::layers_js(FALSE); ?>
			map.addLayers(<?php echo map::layers_array(FALSE); ?>);
			map.addControl(new OpenLayers.Control.Navigation());
			map.addControl(new OpenLayers.Control.Zoom());
			map.addControl(new OpenLayers.Control.MousePosition());
			map.addControl(new OpenLayers.Control.ScaleLine());
			map.addControl(new OpenLayers.Control.Scale('mapScale'));
			map.addControl(new OpenLayers.Control.LayerSwitcher());
			map.addControl(new OpenLayers.Control.ZoomBox());
			
			// Vector/Drawing Layer Styles
			
			// Vector/Drawing Layer Styles
			style1 = new OpenLayers.Style({
				pointRadius: "8",
				fillColor: "${fillColor}",
				fillOpacity: "${fillOpacity}",
				strokeColor: "${strokeColor}",
				strokeWidth: "${strokeWidth}",
				strokeOpacity: "${strokeOpacity}",
				strokeDashstyle: "${strokeDashstyle}",
				graphicZIndex: 1,
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
				labelOutlineWidth: '${labelOutlineWidth}'
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
						} else {
						    return 21;
						}	
					},
				    iconHeight: function(feature) {
						if(typeof feature.attributes.icon == "undefined"){
						    return 20;
						} else {
						    return 25;
						}	
					},
				    iconOffsetX: function(feature) {
						if(typeof feature.attributes.icon == "undefined"){
						    return -10;
						} else if(feature.attributes.icon == "<?php echo url::base();?>media/img/openlayers/marker.png") {
						    return -10;
						} else {
						   return -14;
						}
					},
				    iconOffsetY: function(feature) {
						if(typeof feature.attributes.icon == "undefined"){
						    return -10;
						} else if(feature.attributes.icon == "<?php echo url::base();?>media/img/openlayers/marker.png") {
						    return -15;
						} else {
						    return -27;
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
					}
				}
			});
			
			
			
			
			style2 = new OpenLayers.Style({
				pointRadius: "8",
				//fillColor: "#30E900",
				//fillOpacity: "0.7",
				//strokeColor: "#197700",
				//strokeWidth: 2.5,
				graphicZIndex: 1,
				graphicOpacity: 0.5,
				graphicWidth: 26,
				graphicHeight: 31,
				graphicXOffset: -16,
				graphicYOffset: -30
				
			});
			
			vertexStyle = new OpenLayers.Style({
				pointRadius: "8",
				fillColor: "#30E900",
				fillOpacity: "0.7",
				strokeColor: "#197700",
				strokeWidth: 2.5,
				externalGraphic: null,
				label: null
				
			});
			
			style3 = new OpenLayers.Style({
				pointRadius: "8",
				fillColor: "#30E900",
				fillOpacity: "0.7",
				strokeColor: "#197700",
				strokeWidth: 2.5,
				graphicZIndex: 1
			});
			
			var vlayerStyles = new OpenLayers.StyleMap({
				"default": style1,
				"select": style2,
				"temporary": style3,
				"vertex": vertexStyle
			});
			
			// Create Vector/Drawing layer
			vlayer = new OpenLayers.Layer.Vector( "Editable", {
				styleMap: vlayerStyles,
				rendererOptions: {zIndexing: true}
			});
			map.addLayer(vlayer);
			
			
			 // configure the snapping agent
			snap = new OpenLayers.Control.Snapping({
			    layer: vlayer,
			    targets: [vlayer],
			    greedy: false
			});
			snap.activate();
			
			// Drag Control
			var drag = new OpenLayers.Control.DragFeature(vlayer, {
				onStart: startDrag,
				onDrag: doDrag,
				onComplete: endDrag
			});
			map.addControl(drag);                        
                        

                        
                          $("input.layer_switcher").change(function(e) {
                            // Get the layer id
                            var layerId = this.id.substring(6);
			    
			    

                            var isCurrentLayer = false;
                            var context = this;

                            // Remove all actively selected layers
                            $("input.layer_switcher").each(function(i) {
                                    if (!$(this).is(':checked')) {
                                            if (this.id == context.id) {
                                                    isCurrentLayer = true;
                                            }
                                    }
                            });
    
                            var title = "sl_"+layerId;
                            //remove the layer if it was clicked again
                            if(isCurrentLayer){
                                    var kmlLayer = map.getLayersByName(title);
                                    map.removeLayer(kmlLayer[0]);                                    
                                    $(this).removeClass("active");
                            }

                            // Was a different layer selected?
                            if (!isCurrentLayer) {            
                                    // Set the currently selected layer as the active one
                                    
                                    var kmlLayer = new OpenLayers.Layer.Vector(title, {
                                        projection: new OpenLayers.Projection("EPSG:4326"),
                                        strategies: [new OpenLayers.Strategy.Fixed()],
                                        protocol: new OpenLayers.Protocol.HTTP({
                                            url: "<?php echo url::base();?>json/layer/" + layerId,
                                            format: new OpenLayers.Format.KML({
                                                extractStyles: true, 
                                                extractAttributes: true,
                                                maxDepth: 5
                                            })
                                        })
                                    });
                                    
                                     
                                    map.addLayer(kmlLayer);
                            }

                            return true;
                    });
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
			
			// Vector Layer Events
			vlayer.events.on({
				beforefeaturesadded: function(event) {
					//for(i=0; i < vlayer.features.length; i++) {
					//	if (vlayer.features[i].geometry.CLASS_NAME == "OpenLayers.Geometry.Point") {
					//		vlayer.removeFeatures(vlayer.features);
					//	}
					//}
					
					// Disable this to add multiple points
					// vlayer.removeFeatures(vlayer.features);
				},
				featuresadded: function(event) {
					//set the label to blank
					for(i in event.features){
					    var feature = event.features[i];
					    feature.style = featureStyle;
					    if(typeof feature.attributes.label == "undefined"){
						feature.attributes.label = "";
						vlayer.drawFeature(feature);
					    }						    
					}
					refreshFeatures(event);
					
				},
				featuremodified: function(event) {
					refreshFeatures(event);
				},
				featuresremoved: function(event) {
					refreshFeatures(event);
				}
			});
			
			// Vector Layer Highlight Features
			highlightCtrl = new OpenLayers.Control.SelectFeature(vlayer, {
			    hover: true,
			    highlightOnly: true,
			    renderIntent: "temporary"
			});
			selectCtrl = new OpenLayers.Control.SelectFeature(vlayer, {
				clickout: true, toggle: false,
				multiple: false, hover: false,
				renderIntent: "select",
				onSelect: addSelected,
				onUnselect: clearSelected
			});
			map.addControl(highlightCtrl);
			map.addControl(selectCtrl);
			
			// Insert Saved Geometries
			wkt = new OpenLayers.Format.WKT();
			<?php
			if ( ! count($geometries))
			{
				?>
				// Default Point
				point = new OpenLayers.Geometry.Point(<?php echo $longitude; ?>, <?php echo $latitude; ?>);
				OpenLayers.Projection.transform(point, proj_4326, map.getProjectionObject());
				var origFeature = new OpenLayers.Feature.Vector(point);
				origFeature.attributes.label = "";
				origFeature.attributes.icon = "<?php echo url::file_loc('img').'media/img/openlayers/marker.png' ;?>";
				vlayer.addFeatures(origFeature);
				<?php
			}
			else
			{
				foreach ($geometries as $geometry)
				{
					$geometry = json_decode($geometry);
					echo "wktFeature = wkt.read('$geometry->geometry');\n";
					echo "wktFeature.geometry.transform(proj_4326,proj_900913);\n";
					echo "wktFeature.label = ".json_encode($geometry->label).";\n";
					echo "wktFeature.showLabel = ". ($geometry->showLabel ? 'true' : 'false'). ";\n";
					if($geometry->showLabel){
					    echo "wktFeature.attributes.label = ".json_encode($geometry->label).";\n";
					} else {
					    echo "wktFeature.attributes.label = \"\";\n";
					}
					echo "wktFeature.comment = ".json_encode($geometry->comment).";\n";
					echo "wktFeature.color = '$geometry->color';\n";
					echo "wktFeature.attributes.fillColor  = '#$geometry->color';\n";
					echo "wktFeature.strokewidth = '$geometry->strokewidth';\n";
					echo "wktFeature.attributes.strokeWidth = '$geometry->strokewidth';\n";
					echo "wktFeature.icon = ".json_encode(url::file_loc('img').'media/img/openlayers/'.$geometry->icon).";\n";
					echo "wktFeature.attributes.icon = ".json_encode(url::file_loc('img').'media/img/openlayers/'.$geometry->icon).";\n";
					echo "wktFeature.attributes.fontSize = ".$geometry->fontSize.";\n";
					echo "wktFeature.attributes.fontColor = '#".$geometry->fontColor."';\n";
					echo "wktFeature.attributes.labelOutlineWidth = ".$geometry->labelOutlineWidth.";\n";
					echo "wktFeature.attributes.labelOutlineColor = '#".$geometry->labelOutlineColor."';\n";
					
					echo "wktFeature.attributes.strokeColor = '#".$geometry->strokeColor."';\n";
					echo "wktFeature.attributes.fillOpacity = ".$geometry->fillOpacity.";\n";
					echo "wktFeature.attributes.strokeOpacity = ".$geometry->strokeOpacity.";\n";
					echo "wktFeature.attributes.strokeDashstyle = '".$geometry->strokeDashstyle."';\n";
					
					echo "vlayer.addFeatures(wktFeature);\n";
				}
			}
			?>
			
			
			// Create a lat/lon object
			var startPoint = new OpenLayers.LonLat(<?php echo $longitude; ?>, <?php echo $latitude; ?>);
			startPoint.transform(proj_4326, map.getProjectionObject());
			
			// Display the map centered on a latitude and longitude (Google zoom levels)
			map.setCenter(startPoint, <?php echo ($incident_zoom) ? $incident_zoom : $default_zoom; ?>);
			
			// Create the Editing Toolbar
			var container = document.getElementById("panel");
						
			
			var circleControl = new OpenLayers.Control.DrawFeature(vlayer,
			    OpenLayers.Handler.RegularPolygon,
			    {handlerOptions: {sides:40, irregular: true},
			    'displayClass': 'elipse'});
			    
			var textControl = new OpenLayers.Control.DrawFeature(vlayer,
			     OpenLayers.Handler.Point,
			     {'displayClass': 'olControlDrawTextPoint',
			      'featureAdded': function(e){
				e.renderIntent = "styleText";
				e.attributes.icon = "<?php echo url::file_loc('img').'media/img/openlayers/clear_rect32x14.png' ;?>";
				e.attributes.label = "text";
				e.showLabel = true;
				vlayer.drawFeature(e);
			      }
			      });
			var editVerticiesControl = new OpenLayers.Control.ModifyFeature(vlayer,
			    {
				'displayClass': 'olControlModifyVert',
				vertexRenderIntent: "vertex",
				createVertices: true,
				virtualStyle:
				{
				    pointRadius: "8",
				    fillColor: "#0030E9",
				    fillOpacity: "0.7",
				    strokeColor: "#001977",
				    strokeWidth: 2.5,
				    externalGraphic: null,
				    label: null
				}
			    }
			);
			editVerticiesControl.mode = OpenLayers.Control.ModifyFeature.RESHAPE;
			
			var rotateControl = new OpenLayers.Control.ModifyFeature(vlayer,
			    {
				'displayClass': 'olControlModifyRotate',
				vertexRenderIntent: "vertex",
				createVertices: true,
				virtualStyle:
				{
				    pointRadius: "8",
				    fillColor: "#0030E9",
				    fillOpacity: "0.7",
				    strokeColor: "#001977",
				    strokeWidth: 2.5,
				    externalGraphic: null,
				    label: null
				}
			    }
			);
			rotateControl.mode = OpenLayers.Control.ModifyFeature.ROTATE;
			
			
			var resizeControl = new OpenLayers.Control.ModifyFeature(vlayer,
			    {
				'displayClass': 'olControlModifyResize',
				vertexRenderIntent: "vertex",
				createVertices: true,
				virtualStyle:
				{
				    pointRadius: "8",
				    fillColor: "#0030E9",
				    fillOpacity: "0.7",
				    strokeColor: "#001977",
				    strokeWidth: 2.5,
				    externalGraphic: null,
				    label: null
				}
			    }
			);
			resizeControl.mode = OpenLayers.Control.ModifyFeature.RESIZE;
			
			controls = { text : textControl,
			     drag : new OpenLayers.Control.DragPan(),
			     editVerticies: editVerticiesControl,
			     rotate:rotateControl,
			     resize:resizeControl,
			     circle : circleControl,
			     polygon : new OpenLayers.Control.DrawFeature(vlayer,
				 OpenLayers.Handler.Polygon,
				 {'displayClass': 'olControlDrawFeaturePolygon'}),
			     line : new OpenLayers.Control.DrawFeature(vlayer,
				 OpenLayers.Handler.Path,
				 {'displayClass': 'olControlDrawFeaturePath'}),
			     point : new OpenLayers.Control.DrawFeature(vlayer,
				 OpenLayers.Handler.Point,
				 {'displayClass': 'olControlDrawFeaturePoint',
				  'featureAdded':function(e){
				    e.attributes.icon = "<?php echo url::file_loc('img').'media/img/openlayers/marker.png' ;?>";
				    vlayer.drawFeature(e);
			     }})
			};
			
			var panelControls = [
			 controls.text,	
			 controls.editVerticies,
			 controls.rotate,
			 controls.resize,
			 controls.drag,
			 controls.circle,
			 controls.polygon,
			 controls.line,
			 controls.point
			];
			
			
			turnOffControls = function(){
			    
			    for(i in controls){
				controls[i].deactivate();
			    }
			    selectCtrl.deactivate();
			    drag.deactivate();			    
			}
			
			var panel = new OpenLayers.Control.Panel({
			   div:container,
			   displayClass: 'olControlEditingToolbar'
			});
			panel.addControls(panelControls);
			map.addControl(panel);
			
			
			
			
			drag.activate();
			highlightCtrl.activate();
			selectCtrl.activate();
			
			/** turn off other controls when drawing lines**/
			controls.line.myActivate = controls.line.activate;
			controls.line.activate = function(){
			    turnOffControls();
			    controls.line.myActivate();			    
			}
			
			/** turn off other controls when polygons lines**/
			controls.polygon.myActivate = controls.polygon.activate;
			controls.polygon.activate = function(){
			    turnOffControls();
			    controls.polygon.myActivate();			    
			}
			
			/** turn off other controls when circles lines**/
			controls.circle.myActivate = controls.circle.activate;
			controls.circle.activate = function(){
			    turnOffControls();
			    controls.circle.myActivate();			    
			}
			
			
			/** turn off other controls when editing verticies**/
			controls.editVerticies.editActivate = controls.editVerticies.activate;
			controls.editVerticies.activate = function(){
			    turnOffControls();
			    controls.editVerticies.editActivate();			    
			}
			
			/** turn off other controls when rotating**/
			controls.rotate.myActivate = controls.rotate.activate;
			controls.rotate.activate = function(){
			    turnOffControls();
			    controls.rotate.myActivate();			    
			}
			
			/** turn off other controls when resizing**/
			controls.resize.myActivate = controls.resize.activate;
			controls.resize.activate = function(){
			    turnOffControls();
			    controls.resize.myActivate();			    
			}
			
			/**
			 * Hack to make sure selectControl always works 
			 *
			 * Override navigation activate/deactive to also activate/deactive
			 * the selectCtrl. Previously selectCtrl was not being re-activated
			 * after new features were added.
			 */
			 
			navigationCtrl = controls.drag;
			navigationCtrl.navActivate = controls.drag.activate;
			navigationCtrl.navDeactivate = controls.drag.deactivate;
			navigationCtrl.activate = function () {
				turnOffControls();
				drag.activate();
				this.navActivate();
				selectCtrl.activate();
				
				
			};
			navigationCtrl.deactivate = function () {
				this.navDeactivate();
				selectCtrl.deactivate();
			};
			map.events.register("click", map, function(e){
				//selectCtrl.deactivate();
				//selectCtrl.activate();
			});
			
			
			//click on the coord button puts a dot in the center
			//of the view window selects it, and then opens up the
			//dialog to set the lat,lon
			$('#pointCoords').on('click', function(){
			     var mapCenter = map.getCenter();
			     var newPoint = new OpenLayers.Geometry.Point(mapCenter.lon, mapCenter.lat);
			     var newFeature = new OpenLayers.Feature.Vector(newPoint);
			     newFeature.attributes = { label: "",
			     icon:"<?php echo url::file_loc('img').'media/img/openlayers/marker.png' ;?>"};
			     vlayer.addFeatures([newFeature]);
			     refreshFeatures();
			     //set this feature as what's active
			     clearSelected();
			     addSelected(newFeature);
			     
			});
			
			// Undo Action Removes Most Recent Marker
			$('.btn_del_last').on('click', function () {
				if (vlayer.features.length > 0) {
					x = vlayer.features.length - 1;
					vlayer.removeFeatures(vlayer.features[x]);
				}
				$('#geometry_color').ColorPickerHide();
				$('#font_color').ColorPickerHide();
				$('#outline_color').ColorPickerHide();
				$('#geometry_strokeColor').ColorPickerHide();
				$('#geometryLabelerHolder').hide(400);
				selectCtrl.activate();
				return false;
			});
			
			// Delete Selected Features
			$('.btn_del_sel').on('click', function () {
				for(var y=0; y < selectedFeatures.length; y++) {
					vlayer.removeFeatures(selectedFeatures);
				}
				selectedFeatures = [];
				$('#geometry_color').ColorPickerHide();
				$('#font_color').ColorPickerHide();
				$('#geometry_strokeColor').ColorPickerHide();
				$('#outline_color').ColorPickerHide();
				$('#geometryLabelerHolder').hide(400);
				selectCtrl.activate();
				return false;
			});
			
			// Clear Map
			$('.btn_clear').on('click', function () {
				vlayer.removeFeatures(vlayer.features);
				$('input[name="geometry[]"]').remove();
				$("#latitude").val("");
				$("#longitude").val("");
				$('#geometry_label').val("");
				$('#geometry_comment').val("");
				$('#geometry_color').val("");
				$('#geometry_lat').val("");
				$('#geometry_lon').val("");
				$('#geometry_color').ColorPickerHide();
				$('#geometry_strokeColor').ColorPickerHide();
				$('#font_color').ColorPickerHide();
				$('#outline_color').ColorPickerHide();
				$('#font_color').val('');
				$('#outline_color').val('');
				$('#geometryLabelerHolder').hide(400);
				selectCtrl.activate();
				return false;
			});
			
			// GeoCode
			$('.btn_find').on('click', function () {
				geoCode();
			});
			$('#location_find').bind('keypress', function(e) {
				var code = (e.keyCode ? e.keyCode : e.which);
				if(code == 13) { //Enter keycode
					geoCode();
					return false;
				}
			});
			
			// Event on Latitude/Longitude Typing Change
			$('#latitude, #longitude').bind("blur", function() {
				var newlat = $("#latitude").val();
				var newlon = $("#longitude").val();
				// Do nothing if either field is empty.
				if (newlat == '' || newlon == '') return;
				if (!isNaN(newlat) && !isNaN(newlon))
				{
					// Clear the map first
					vlayer.removeFeatures(vlayer.features);
					$('input[name="geometry[]"]').remove();
					
					point = new OpenLayers.Geometry.Point(newlon, newlat);
					OpenLayers.Projection.transform(point, proj_4326,proj_900913);
					
					f = new OpenLayers.Feature.Vector(point);
					vlayer.addFeatures(f);
					
					// create a new lat/lon object
					myPoint = new OpenLayers.LonLat(newlon, newlat);
					myPoint.transform(proj_4326, map.getProjectionObject());

					// display the map centered on a latitude and longitude
					map.panTo(myPoint);
				}
				else
				{
					// Commenting this out as its horribly annoying
					//alert('Invalid value!');
				}
			});
			
			/* Form Actions */
			// Action on Save Only
			$('.btn_save').on('click', function () {
				$("#save").attr("value", "dontclose");
				$(this).parents("form").submit();
				return false;
			});
			
			$('.btn_save_close').on('click', function () {
				$(this).parents("form").submit();
				return false;
			});

			$('.btn_save_add_new').on('click', function () {
				$("#save").attr("value", "addnew");
				$(this).parents("form").submit();
				return false;
			});
			
			// Delete Action
			$('.btn_delete').on('click', function () {
				var agree=confirm("<?php echo Kohana::lang('ui_admin.are_you_sure_you_want_to'); ?> <?php echo Kohana::lang('ui_admin.delete_action'); ?>?");
				if (agree){
					$('#reportMain').submit();
				}
				return false;
			});
			
			// Toggle Date Editor
			$('a#date_toggle').click(function() {
		    	$('#datetime_edit').show(400);
				$('#datetime_default').hide();
		    	return false;
			});
			
			// Show Messages Box
		    $('a#messages_toggle').click(function() {
		    	$('#show_messages').toggle(400);
		    	return false;
			});
			
			// Textbox Hints
			$("#location_find").hint();
			
			/* Dynamic categories */
			<?php if ($edit_mode): ?>
			$('#category_add').hide();
		    $('#add_new_category').click(function() { 
		        var category_name = $("input#category_name").val();
		        var category_description = $("input#category_description").val();
		        var category_color = $("input#category_color").val();

				//trim the form fields
				//Removed ".toUpperCase()" from name and desc for Ticket #38
		        category_name = category_name.replace(/^\s+|\s+$/g, '');
		        category_description = category_description.replace(/^\s+|\s+$/g,'');
		        category_color = category_color.replace(/^\s+|\s+$/g, '').toUpperCase();
        
		        if (!category_name || !category_description || !category_color) {
		            alert("Please fill in all the fields");
		            return false;
		        }
        
		        //category_color = category_color.toUpperCase();

		        re = new RegExp("[^ABCDEF0123456789]"); //Color values are in hex
		        if (re.test(category_color) || category_color.length != 6) {
		            alert("Please use the Color picker to help you choose a color");
		            return false;
		        }
		
				$.post("<?php echo url::base() . 'admin/reports/save_category/' ?>", 
					{ category_title: category_name, category_description: category_description, category_color: category_color },
					function(data){
						if ( data.status == 'saved')
						{
							// alert(category_name+" "+category_description+" "+category_color);
					        $('#user_categories').append("<li><label><input type=\"checkbox\"name=\"incident_category[]\" value=\""+data.id+"\" class=\"check-box\" checked />"+category_name+"</label></li>");
							$('#category_add').hide();
						}
						else
						{
							alert("Your submission had errors!!");
						}
					}, "json");
		        return false; 
		    });
			<?php endif; ?>
                    
                    
                    
                     
                    
                    
                    
                    
                    
                    
                    
		
			// Category treeview
			$(".category-column").treeview({
			  persist: "location",
			  collapsed: true,
			  unique: false
			});
			
			// Date Picker JS
			$("#incident_date").datepicker({ 
			    showOn: "both", 
			    buttonImage: "<?php echo url::file_loc('img') ?>media/img/icon-calendar.gif", 
			    buttonImageOnly: true 
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
						$('.incident-location').insertBefore($('.f-col'));
						$('.map_holder_reports').css({"height":"350px", "width": "935px"});
						$('.incident-location h4').css({"margin-left":"10px"});
						$('.location-info').css({"margin-right":"14px"});
						$('a[href=#report-map]').parent().hide();
						$('a.taller-map').parent().show();
						$('a.smaller-map').parent().show();
						break;
					case "taller-map":
						$('.map_holder_reports').css("height","600px");
						$('a.shorter-map').parent().show();
						$('a.smaller-map').parent().show();
						break;
					case "shorter-map":
						$('.map_holder_reports').css("height","350px");
						$('a.taller-map').parent().show();
						$('a.smaller-map').parent().show();
						break;
					case "smaller-map":
						$('.incident-location').hide().prependTo($('.f-col-1'));
						$('.map_holder_reports').css({"height":"350px", "width": "494px"});
						$('a.wider-map').parent().show();
						$('.incident-location').show();
						$('.incident-location h4').css({"margin-left":"0"});
						$('.location-info').css({"margin-right":"0"});
						break;
				};
				
				map.updateSize();
				map.pan(0,1);
				
				return false;
			});
			
			
			// Prevent Map Effects in the Geometry Labeler
			/*
			$('#geometryLabelerHolder').click(function(evt) {
				var e = evt ? evt : window.event; 
				OpenLayers.Event.stop(e);
				return true;
			});
			*/
			
			$('#geometry_showlabel').change(function(){			    
			    var isChecked = $(this).is(':checked');
			    for (f in selectedFeatures) {
					selectedFeatures[f].attributes.label = isChecked ? selectedFeatures[f].label : "";
					selectedFeatures[f].showLabel = isChecked;
					selectedFeatures[f].style = null;
					vlayer.drawFeature(selectedFeatures[f]);
				}
				refreshFeatures();
			});
			
			// Geometry Label Text Boxes
			$('#geometry_label').click(function() {
				$('#geometry_label').focus();
				$('#geometry_color').ColorPickerHide();
				$('#font_color').ColorPickerHide();
				$('#outline_color').ColorPickerHide();
				$('#geometry_strokeColor').ColorPickerHide();
			}).bind("change keyup blur", function(){
				for (f in selectedFeatures) {
					selectedFeatures[f].label = this.value;
					if(selectedFeatures[f].showLabel){
					    selectedFeatures[f].attributes.label = $(this).val();
					}
					selectedFeatures[f].style = null;
					vlayer.drawFeature(selectedFeatures[f]);
				}
				refreshFeatures();
			});
			
			$('#geometry_comment').click(function() {
				$('#geometry_comment').focus();
				$('#geometry_color').ColorPickerHide();
				$('#font_color').ColorPickerHide();
				$('#outline_color').ColorPickerHide();
				$('#geometry_strokeColor').ColorPickerHide();
			}).bind("change keyup blur", function(){
				for (f in selectedFeatures) {
					selectedFeatures[f].comment = this.value;
			    }
				refreshFeatures();
			});
			
			$('#geometry_lat').click(function() {
				$('#geometry_lat').focus();
				$('#geometry_color').ColorPickerHide();
				$('#font_color').ColorPickerHide();
				$('#outline_color').ColorPickerHide();
				$('#geometry_strokeColor').ColorPickerHide();
			}).bind("change keyup blur", function(){
				//update the hours minutes seconds
				$("#geometry_lat_degrees").val(decimalLatToHours(parseFloat(this.value)));
				$("#geometry_lat_minutes").val(decimalLatToMinutes(parseFloat(this.value)));
				$("#geometry_lat_seconds").val(decimalLatToSeconds(parseFloat(this.value)));
				for (f in selectedFeatures) {
					selectedFeatures[f].lat = this.value;
			    }
				refreshFeatures();
			});
			
			$('#geometry_lon').click(function() {
				$('#geometry_lon').focus();
				$('#geometry_color').ColorPickerHide();
				$('#font_color').ColorPickerHide();
				$('#outline_color').ColorPickerHide();
				$('#geometry_strokeColor').ColorPickerHide();
			}).bind("change keyup blur", function(){
				$("#geometry_lon_degrees").val(decimalLonToHours(parseFloat(this.value)));
				$("#geometry_lon_minutes").val(decimalLonToMinutes(parseFloat(this.value)));
				$("#geometry_lon_seconds").val(decimalLonToSeconds(parseFloat(this.value)));
				for (f in selectedFeatures) {
					selectedFeatures[f].lon = this.value;
			    }
				refreshFeatures();
			});
			
			//bind changes in hh mm ss to decimal degress
			$('#geometry_lat_degrees, #geometry_lat_minutes, #geometry_lat_seconds').bind("change keyup blur", function(){
			
			    var newlat = hmsToDecimal(
				parseFloat($("#geometry_lat_degrees").val()),
				parseFloat($("#geometry_lat_minutes").val()),
				parseFloat($("#geometry_lat_seconds").val()));
			    $('#geometry_lat').val(newlat);
				
				var newlon = $("#geometry_lon").val();
				if (!isNaN(newlat) && !isNaN(newlon))
				{
					var lonlat = new OpenLayers.LonLat(newlon, newlat);
					lonlat.transform(proj_4326,proj_900913);
					for (f in selectedFeatures) {
						selectedFeatures[f].geometry.x = lonlat.lon;
						selectedFeatures[f].geometry.y = lonlat.lat;
						selectedFeatures[f].lon = newlat;
						selectedFeatures[f].lat = newlon;
						vlayer.drawFeature(selectedFeatures[f]);
				    }
				}
				else
				{
					alert('Invalid value!')
				}
			});
			
			$('#geometry_lon_degrees, #geometry_lon_minutes, #geometry_lon_seconds').bind("change keyup blur", function(){
			
			    var newlon = hmsToDecimal(
				parseFloat($("#geometry_lon_degrees").val()),
				parseFloat($("#geometry_lon_minutes").val()),
				parseFloat($("#geometry_lon_seconds").val()));
			    $('#geometry_lon').val(newlon);
				
				var newlat = $("#geometry_lat").val();
				if (!isNaN(newlat) && !isNaN(newlon))
				{
					var lonlat = new OpenLayers.LonLat(newlon, newlat);
					lonlat.transform(proj_4326,proj_900913);
					for (f in selectedFeatures) {
						selectedFeatures[f].geometry.x = lonlat.lon;
						selectedFeatures[f].geometry.y = lonlat.lat;
						selectedFeatures[f].lon = newlat;
						selectedFeatures[f].lat = newlon;
						vlayer.drawFeature(selectedFeatures[f]);
				    }
				}
				else
				{
					alert('Invalid value!')
				}
				
			});
			
			
			// Event on Latitude/Longitude Typing Change
			$('#geometry_lat, #geometry_lon').bind("change keyup", function() {
				var newlat = $("#geometry_lat").val();
				var newlon = $("#geometry_lon").val();
				if (!isNaN(newlat) && !isNaN(newlon))
				{
					var lonlat = new OpenLayers.LonLat(newlon, newlat);
					lonlat.transform(proj_4326,proj_900913);
					for (f in selectedFeatures) {
						selectedFeatures[f].geometry.x = lonlat.lon;
						selectedFeatures[f].geometry.y = lonlat.lat;
						selectedFeatures[f].lon = newlat;
						selectedFeatures[f].lat = newlon;
						vlayer.drawFeature(selectedFeatures[f]);
				    }
				}
				else
				{
					alert('Invalid value!')
				}
			});
			
			
			/***********************************
			 * When the user clicks edit points
			 * This populates the edit points UI
			 ************************************/
			$('#geometryEditPoints').bind("click",function(){
			    $('#geometryPointsHolder').show();
			    //remove all the options
			    $("#pointsListHolder option").each(function() {
				$(this).remove();
			    });
			    //clear out the text fields
			    $('#point_geometry_lat').val('');
			    $('#point_geometry_lon').val('');
			    $('#pointGeometry_lat_degrees').val('');
			    $('#pointGeometry_lat_minutes').val('');
			    $('#pointGeometry_lat_seconds').val('');
			    $('#pointGeometry_lon_degrees').val('');
			    $('#pointGeometry_lon_minutes').val('');
			    $('#pointGeometry_lon_seconds').val('');
			    
			    
			    for (f in selectedFeatures) {
				if(selectedFeatures[f].geometry.componentTypes[0] == "OpenLayers.Geometry.LinearRing"){
				    for(i in selectedFeatures[f].geometry.components[0].components){
					if(i == 0){continue;}
					var component = selectedFeatures[f].geometry.components[0].components[i];
					var id = component.id;
					var x = component.x;
					var y = component.y;
					var lonLat = new OpenLayers.LonLat(x, y);
					var point2 =  lonLat.transform(proj_900913, proj_4326);
					x = point2.lat;
					y = point2.lon;
					 $('#pointsListHolder')
					    .append($("<option></option>")
					    .attr("value",id)
					    .text(x + ", " + y)); 
				    }
				    break;
				}				
			    }
			});
			
			$('#geometryPointsClose').bind("click",function(){
			    $('#geometryPointsHolder').hide();
			});
			
			
			/*******************************************
			 * Updates the point input boxes when
			 * a new point is selected
			 ******************************************/
			$('#pointsListHolder').bind("change",function(){
			    var coords = $('#pointsListHolder option:selected').text();
			    coords = coords.split(", ");
			    var lat = parseFloat(coords[0]);
			    var lon = parseFloat(coords[1]);
			    $("#point_geometry_lat").val(lat);
			    $("#point_geometry_lon").val(lon);
			    
			    pointDecimalToHMS();
			});
			
			//update a point when the user changes the lat
			$("#point_geometry_lat").bind("change keyup blur",function(){			 
			    pointDecimalToHMS();
			    updateEditPoint();
			});
			
			//update a point when the user changes the lon
			$("#point_geometry_lon").bind("change keyup blur",function(){			 
			    pointDecimalToHMS();
			    updateEditPoint();
			});
			
			
			function pointHMStoDecimal(){
			    var lat_d = $("#pointGeometry_lat_degrees").val();
			    var lat_m = $("#pointGeometry_lat_minutes").val();
			    var lat_s = $("#pointGeometry_lat_seconds").val();
			    if(lat_d == '' || lat_m == '' || lat_s == ''){return;}
			    lat_d = parseFloat(lat_d);
			    lat_m = parseFloat(lat_m);
			    lat_s = parseFloat(lat_s);
			    if(isNaN(lat_d) || Math.abs(lat_d) > 90){alert("Invalid latitude degree"); return;}
			    if(isNaN(lat_m)){alert("Invalid latitude minute"); return;}
			    if(isNaN(lat_s)){alert("Invalid latitude second"); return;}
			    
			    var lon_d = $("#pointGeometry_lon_degrees").val();
			    var lon_m = $("#pointGeometry_lon_minutes").val();
			    var lon_s = $("#pointGeometry_lon_seconds").val();
			    lon_d = parseFloat(lon_d);
			    lon_m = parseFloat(lon_m);
			    lon_s = parseFloat(lon_s);
			    if(lon_d == '' || lon_m == '' || lon_s == ''){return;}
			    if(isNaN(lon_d)){alert("Invalid longitude degree"); return;}
			    if(isNaN(lon_m)){alert("Invalid longitude minute"); return;}
			    if(isNaN(lon_s)){alert("Invalid longitude second"); return;}
			    
			    $("#point_geometry_lat").val(hmsToDecimal(lat_d, lat_m, lat_s));
			    $("#point_geometry_lon").val(hmsToDecimal(lon_d, lon_m, lon_s));
			    
			}
			
			$('#pointGeometry_lon_degrees, #pointGeometry_lon_minutes, #pointGeometry_lon_seconds, #pointGeometry_lat_degrees, #pointGeometry_lat_minutes, #pointGeometry_lat_seconds').bind("change keyup", function(){
			    pointHMStoDecimal();
			    updateEditPoint();
			});
			
			function pointDecimalToHMS(){
			    var lat = parseFloat($("#point_geometry_lat").val());
			    if(isNaN(lat) || Math.abs(lat) > 90){
				return;
			    }			
			    var lon = parseFloat($("#point_geometry_lon").val());
			    if(isNaN(lon)){
				return;
			    }
			    
			    $("#pointGeometry_lat_degrees").val(decimalLatToHours(lat));
			    $("#pointGeometry_lat_minutes").val(decimalLatToMinutes(lat));
			    $("#pointGeometry_lat_seconds").val(decimalLatToSeconds(lat));
			    
			    $("#pointGeometry_lon_degrees").val(decimalLonToHours(lon));
			    $("#pointGeometry_lon_minutes").val(decimalLonToMinutes(lon));
			    $("#pointGeometry_lon_seconds").val(decimalLonToSeconds(lon));
			}
			
			/*******************************************
			 * Update an individual point in a polygon
			 ********************************************/
			function updateEditPoint(){
			    var lat = $("#point_geometry_lat").val();
			    var lon = $("#point_geometry_lon").val();
			    
			    if(lat == '' || lon == ''){ return;}
			    lat = parseFloat(lat);
			    lon = parseFloat(lon);
			    if(isNaN(lat) || Math.abs(lat)>90){
				alert("Invalid Latitude");
				return;
			    }			
			    
			    if(isNaN(lon)){
				alert("Invalid Longitude");
				return;
			    }
			    
			    $('#pointsListHolder option:selected').text(lat + ", " + lon);
			    
			    var currentPoint = $("#pointsListHolder").val();
			    //loop over points and find the current one
			     for (f in selectedFeatures) {
				if(selectedFeatures[f].geometry.componentTypes[0] == "OpenLayers.Geometry.LinearRing"){
				    for(i in selectedFeatures[f].geometry.components[0].components){
					var component = selectedFeatures[f].geometry.components[0].components[i];
					var id = component.id;
					if( id == currentPoint){					    
					    var lonLat = new OpenLayers.LonLat(lon, lat);
					    var point2 =  lonLat.transform( proj_4326,proj_900913);
					    component.y = point2.lat;
					    component.x = point2.lon;
					    break;
					}
				    }
				    vlayer.drawFeature(selectedFeatures[f]);
				    refreshFeatures();
				    break;
				}				
			    }
			}
			
			/*******************************
			 * Add a new point to a polygon
			 ********************************/
			$('#addPointGeometry').bind("click",function(){
			    for (f in selectedFeatures) {
				if(selectedFeatures[f].geometry.componentTypes[0] == "OpenLayers.Geometry.LinearRing"){
				    
				    var point = new OpenLayers.Geometry.Point(0,0);	
				    var id = point.id;
				    selectedFeatures[f].geometry.components[0].components.splice(1,0,point);
				    $('#pointsListHolder option').removeAttr("selected");
				    $('#pointsListHolder option:first')
					    .after($("<option></option>")
					    .attr("value",id)
					    .attr("selected","selected")
					    .text("0, 0")); 
				    $('#pointsListHolder').change();
				    
				    vlayer.drawFeature(selectedFeatures[f]);
				    refreshFeatures(); 
				    break;
				}				
			    }
			});
			
			
			/*******************************
			 * Remove a point from a polygon
			 ********************************/
			$('#removePointGeometry').bind("click",function(){
			
			    var currentPoint = $("#pointsListHolder").val();
			    if(currentPoint == null){
				alert("No point selected");
				return;
			    }
			    
			     for (f in selectedFeatures) {
				if(selectedFeatures[f].geometry.componentTypes[0] == "OpenLayers.Geometry.LinearRing"){
				    for(i in selectedFeatures[f].geometry.components[0].components){
					var component = selectedFeatures[f].geometry.components[0].components[i];
					var id = component.id;
					if( id == currentPoint){	
					    if(selectedFeatures[f].geometry.components[0].components.length == 2){
						alert("A polygon must have at least 2 points");
						return;
					    }
					    console.log("i: " + i + " length: "+ selectedFeatures[f].geometry.components[0].components.length);
					    selectedFeatures[f].geometry.components[0].components.splice(i,1);					    
					}
				    }
				    vlayer.drawFeature(selectedFeatures[f]);
				    refreshFeatures();
				    break;
				}				
			    }
			    
			    $('#pointsListHolder option:selected').remove();
			    
			});
			
			// Event on Icon Change
			$('#geometry_icon').bind("change",function(){
			    var iconPath = $(this).val();
			    for (f in selectedFeatures) {
				selectedFeatures[f].attributes.icon = iconPath;
				selectedFeatures[f].style = null;
				vlayer.drawFeature(selectedFeatures[f]);
			    }
			    refreshFeatures();
			});
							
			// Event on Color Change
			$('#geometry_color').ColorPicker({
				onSubmit: function(hsb, hex, rgb) {
					$('#geometry_color').val(hex);
					for (f in selectedFeatures) {
						selectedFeatures[f].color = hex;
						selectedFeatures[f].attributes.fillColor = "#"+hex;
						vlayer.drawFeature(selectedFeatures[f]);
				    }
					refreshFeatures();
				},
				onChange: function(hsb, hex, rgb) {
					$('#geometry_color').val(hex);
					for (f in selectedFeatures) {
						selectedFeatures[f].color = hex;
						selectedFeatures[f].attributes.fillColor = "#"+hex;
						vlayer.drawFeature(selectedFeatures[f]);
				    }
					refreshFeatures();
				},
				onBeforeShow: function () {
					$(this).ColorPickerSetColor(this.value);
					for (f in selectedFeatures) {
						selectedFeatures[f].color = this.value;
						selectedFeatures[f].attributes.fillColor = "#"+this.value;
						vlayer.drawFeature(selectedFeatures[f]);
				    }
					refreshFeatures();
				}
			}).bind('keyup', function(){
				$(this).ColorPickerSetColor(this.value);
				for (f in selectedFeatures) {
					selectedFeatures[f].color = this.value;
					selectedFeatures[f].attributes.fillColor = "#"+this.value;
					vlayer.drawFeature(selectedFeatures[f]);
			    }
				refreshFeatures();
			});
			
			
			
			// Event on Color Change
			$('#geometry_strokeColor').ColorPicker({

				onChange: function(hsb, hex, rgb) {
					$('#geometry_strokeColor').val(hex);
					for (f in selectedFeatures) {
						selectedFeatures[f].attributes.strokeColor = "#"+hex;
						vlayer.drawFeature(selectedFeatures[f]);
				    }
					refreshFeatures();
				}
			});
			
			/************************************
			 * Change the style of the line
			 ***********************************/
			$('#geometry_lineStyle').bind("change", function(){
				for (f in selectedFeatures) {
					selectedFeatures[f].attributes.strokeDashstyle = this.value;
					vlayer.drawFeature(selectedFeatures[f]);
				}
				refreshFeatures();
			});
			
			
			
			
			
			
			
			
			
			// Event on Color Change
			$('#font_color').ColorPicker({				
			    onChange: function(hsb, hex, rgb) {
				$('#font_color').val(hex);
				    for (f in selectedFeatures) {
					selectedFeatures[f].attributes.fontColor = '#'+hex;
					vlayer.drawFeature(selectedFeatures[f]);
				    }
				refreshFeatures();
			    }
			});
			
			// Event on Color Change
			$('#outline_color').ColorPicker({
				onChange: function(hsb, hex, rgb) {
					$('#outline_color').val(hex);
					for (f in selectedFeatures) {
						selectedFeatures[f].attributes.labelOutlineColor = '#'+hex;
						vlayer.drawFeature(selectedFeatures[f]);
				    }
					refreshFeatures();
				}
			});
			
			// Event on StrokeWidth Change
			$('#geometry_strokewidth').bind("change keyup", function() {
				if (parseFloat(this.value) && parseFloat(this.value) <= 8) {
					for (f in selectedFeatures) {
						selectedFeatures[f].strokewidth = this.value;
						selectedFeatures[f].attributes.strokeWidth = this.value;
						vlayer.drawFeature(selectedFeatures[f]);
					}
					refreshFeatures();
				}
			});
			
			$('#geometry_opacity').bind("change", function(){
			    var opacity = parseFloat($(this).val())/100;
			    for (f in selectedFeatures) {
				    selectedFeatures[f].attributes.fillOpacity = opacity;
				    vlayer.drawFeature(selectedFeatures[f]);
				    
			    }
			    refreshFeatures();
			});
			
			
			/**********************************
			 * Change the opacity of the stroke
			 **********************************/
			$('#geometry_strokeOpacity').bind("change", function(){
			    var opacity = parseFloat($(this).val())/100;
			    for (f in selectedFeatures) {
				    console.log(opacity);
				    selectedFeatures[f].attributes.strokeOpacity = opacity;
				    vlayer.drawFeature(selectedFeatures[f]);
				    
			    }
			    refreshFeatures();
			});
			
			// Event on Fontsize Change
			$('#font_size').bind("change keyup", function() {
				if (parseFloat(this.value) && parseFloat(this.value) <= 40) {
					for (f in selectedFeatures) {
						selectedFeatures[f].attributes.fontSize = this.value;
						vlayer.drawFeature(selectedFeatures[f]);
					}
					refreshFeatures();
				}
			});
			
			// Event on Outlinewidth Change
			$('#outline_width').bind("change keyup", function() {
				if (parseFloat(this.value) && parseFloat(this.value) <= 10) {
					for (f in selectedFeatures) {
						selectedFeatures[f].attributes.labelOutlineWidth = this.value;
						vlayer.drawFeature(selectedFeatures[f]);

			console.log('outline');

					}
					refreshFeatures();
				}
			});
			
			// Close Labeler
			$('#geometryLabelerClose').click(function() {
				$('#geometryLabelerHolder').hide(400);
				for (f in selectedFeatures) {
					selectCtrl.unselect(selectedFeatures[f]);
				}
				selectCtrl.activate();
			});

			// Detect Dropdown Select
			$("#select_city").change(function() {
				var lonlat = $(this).val().split(",");
				if ( lonlat[0] && lonlat[1] )
				{
					// Clear the map first
					vlayer.removeFeatures(vlayer.features);
					$('input[name="geometry[]"]').remove();

					point = new OpenLayers.Geometry.Point(lonlat[0], lonlat[1]);
					OpenLayers.Projection.transform(point, proj_4326,proj_900913);

					f = new OpenLayers.Feature.Vector(point);
					vlayer.addFeatures(f);

					// create a new lat/lon object
					myPoint = new OpenLayers.LonLat(lonlat[0], lonlat[1]);
					myPoint.transform(proj_4326, map.getProjectionObject());

					// display the map centered on a latitude and longitude
					map.panTo(myPoint);

					// Update form values (jQuery)
					$("#location_name").attr("value", $('#select_city :selected').text());

					$("#latitude").attr("value", lonlat[1]);
					$("#longitude").attr("value", lonlat[0]);
				}
			});

		});
		
		function addFormField(div, field, hidden_id, field_type) {
			var id = document.getElementById(hidden_id).value;
			
			// HTML for the form field to be added
			var formFieldHTML = "<div class=\"row link-row second\" id=\"" + field + "_" + id + "\">" +
			    "<input type=\"" + field_type + "\" name=\"" + field + "[]\" class=\"" + field_type + " long2\" />" +
			    "<a href=\"#\" class=\"add\" "+
			    "    onClick=\"addFormField('" + div + "','" + field + "','" + hidden_id + "','" + field_type + "'); return false;\">"+
			    "    add</a>" +
			    "<a href=\"#\" class=\"rem\"  onClick='removeFormField(\"#" + field + "_" + id + "\"); return false;'>remove</a></div>";

			$("#" + div).append(formFieldHTML);

			$("#" + field + "_" + id).effect("highlight", {}, 800);

			id = (id - 1) + 2;
			document.getElementById(hidden_id).value = id;
		}

		function removeFormField(id) {
			var answer = confirm("<?php echo Kohana::lang('ui_admin.are_you_sure_you_want_to_delete_this_item'); ?>?");
		    if (answer){
				$(id).remove();
		    }
			else{
				return false;
		    }
		}
		
		function deletePhoto (id, div)
		{
			var answer = confirm("<?php echo Kohana::lang('ui_admin.are_you_sure_you_want_to_delete_this_photo'); ?>?");
		    if (answer){
				$("#" + div).effect("highlight", {}, 800);
				$.get("<?php echo url::base() . 'admin/reports/deletePhoto/' ?>" + id);
				$("#" + div).remove();
		    }
			else{
				return false;
		    }
		}
		
		/**
		 * Google GeoCoder
		 */
		function geoCode()
		{
			$('#find_loading').html('<img src="<?php echo url::file_loc('img')."media/img/loading_g.gif"; ?>">');
			address = $("#location_find").val();
			$.post("<?php echo url::site() . 'reports/geocode/' ?>", { address: address },
				function(data){
					if (data.status == 'success'){
						// Clear the map first
						vlayer.removeFeatures(vlayer.features);
						$('input[name="geometry[]"]').remove();
						
						point = new OpenLayers.Geometry.Point(data.longitude, data.latitude);
						OpenLayers.Projection.transform(point, proj_4326,proj_900913);
						
						f = new OpenLayers.Feature.Vector(point);
						vlayer.addFeatures(f);
						
						// create a new lat/lon object
						myPoint = new OpenLayers.LonLat(data.longitude, data.latitude);
						myPoint.transform(proj_4326, map.getProjectionObject());

						// display the map centered on a latitude and longitude
						map.panTo(myPoint);
												
						// Update form values
						$("#country_name").val(data.country);
						$("#latitude").val(data.latitude);
						$("#longitude").val(data.longitude);
						$("#location_name").val(data.location_name);
					} else {
						// Alert message to be displayed
						var alertMessage = address + " not found!\n\n***************************\n" + 
						    "Enter more details like city, town, country\nor find a city or town " +
						    "close by and zoom in\nto find your precise location";

						alert(alertMessage)
					}
					$('div#find_loading').html('');
				}, "json");
			return false;
		}
		
		function formSwitch(form_id, incident_id)
		{
			var answer = confirm('<?php echo Kohana::lang('ui_admin.are_you_sure_you_want_to_switch_forms'); ?>?');
			if (answer){
				$('#form_loader').html('<img src="<?php echo url::file_loc('img')."media/img/loading_g.gif"; ?>">');
				$.post("<?php echo url::site().'reports/switch_form'; ?>", { form_id: form_id, incident_id: incident_id },
					function(data){
						if (data.status == 'success'){
							$('#custom_forms').html('');
							$('#custom_forms').html(data.response);
							$('#form_loader').html('');
						}
				  	}, "json");
			}
		}
		
		/* Keep track of the selected features */
		function addSelected(feature) {
			selectedFeatures.push(feature);
			selectCtrl.activate();
			if (vlayer.features.length == 1 && feature.geometry.CLASS_NAME == "OpenLayers.Geometry.Point") {
				// This is a single point, no need for geometry metadata
			} else {
				$('#geometryLabelerHolder').show(400);
				if (feature.geometry.CLASS_NAME == "OpenLayers.Geometry.Point") {
					$('#geometryLat').show();
					$('#geometryLon').show();
					$('#hoursMinsSeconds').show();
					$('#geometryColor').hide();
					$('#geometryStrokewidth').hide();
					$('#geometryEditPoints').hide();
					thisPoint = feature.clone();
					thisPoint.geometry.transform(proj_900913,proj_4326);
					$('#geometry_lat').val(thisPoint.geometry.y);
					$('#geometry_lon').val(thisPoint.geometry.x);
					
					$("#geometry_lat_degrees").val(decimalLatToHours(thisPoint.geometry.y));
					$("#geometry_lat_minutes").val(decimalLatToMinutes(thisPoint.geometry.y));
					$("#geometry_lat_seconds").val(decimalLatToSeconds(thisPoint.geometry.y));
					$("#geometry_lon_degrees").val(decimalLonToHours(thisPoint.geometry.x));
					$("#geometry_lon_minutes").val(decimalLonToMinutes(thisPoint.geometry.x));
					$("#geometry_lon_seconds").val(decimalLonToSeconds(thisPoint.geometry.x));
					
					$('#fontColor').show();
					$('#fontSize').show();
					$('#outlineWidth').show();
					$('#outlineColor').show();
					
					$('#geometryStrokeColor').hide();
					$('#geometryOpacity').hide();
					$('#geometryStrokeOpacity').hide();
					$('#geometryLineStyle').hide();
					
				} else {
					$('#geometryLat').hide();
					$('#geometryLon').hide();
					$('#hoursMinsSeconds').hide();
					$('#geometryColor').show();
					$('#geometryStrokewidth').show();
					$('#geometryEditPoints').show();
					$('#fontColor').show();
					$('#fontSize').show();
					$('#outlineWidth').show();
					$('#outlineColor').show();
					
					$('#geometryStrokeColor').show();
					$('#geometryOpacity').show();
					$('#geometryStrokeOpacity').show();
					$('#geometryLineStyle').show();
					
					$('#geometry_strokeColor').val('cc0000');
					$('#geometry_opacity').val('70');
					$('#geometry_strokeOpacity').val(100);
					$('#geometry_lineStyle').val('solid');

				}
				if ( typeof(feature.label) != 'undefined') {
					$('#geometry_label').val(feature.label);
				}
				if(typeof(feature.attributes.icon) != 'undefined'){
				    if(feature.attributes.icon.indexOf("clear_rect32x14.png") == -1 && feature.geometry.CLASS_NAME == "OpenLayers.Geometry.Point"){
					$('#geometry_icon').val(feature.attributes.icon);
					$('#geometry_icon').change();
					$('#geometryIcon').show();
				    } else {
					$('#geometryIcon').hide();
				    }				    
				} else {
				    $('#geometryIcon').hide();
				}				
				if(typeof(feature.showLabel) != 'undefined'){				
				    if(feature.showLabel){
					$('#geometry_showlabel').attr('checked','checked');
				    } else {
					$('#geometry_showlabel').removeAttr('checked');
				    }					
				} else {
				    $('#geometry_showlabel').removeAttr('checked');
				}
				if ( typeof(feature.comment) != 'undefined') {
					$('#geometry_comment').val(feature.comment);
				}
				if ( typeof(feature.lon) != 'undefined') {
					$('#geometry_lon').val(feature.lon);
				}
				if ( typeof(feature.lat) != 'undefined') {
					$('#geometry_lat').val(feature.lat);
				}
				if ( typeof(feature.color) != 'undefined') {
					$('#geometry_color').val(feature.color);
				} else {
				    $('#geometry_color').val('ffcc66'); 
				}
				if ( typeof(feature.strokewidth) != 'undefined' && feature.strokewidth != '') {
					$('#geometry_strokewidth').val(feature.strokewidth);
				} else {
					$('#geometry_strokewidth').val("2.5");
				}

				if(typeof(feature.attributes) != 'undefined'){
				    console.log(feature.attributes);
				    
				    if(typeof(feature.attributes.fontColor) != 'undefined'){
				    	$('#font_color').val(feature.attributes.fontColor);
				    }
				    
				    if(typeof(feature.attributes.fontSize) != 'undefined'){
					$('#font_size').val(feature.attributes.fontSize);
				    }
				    if(typeof(feature.attributes.labelOutlineWidth) != 'undefined'){
					    $('#outline_width').val(feature.attributes.labelOutlineWidth);
				    }
				    if(typeof(feature.attributes.labelOutlineColor) != 'undefined'){
					    $('#outline_color').val(feature.attributes.labelOutlineColor);
				    }

				    if(typeof(feature.attributes.strokeColor) != 'undefined'){
					    $('#geometry_strokeColor').val(feature.attributes.strokeColor.substring(1));
				    }
				    if(typeof(feature.attributes.fillOpacity) != 'undefined'){
					    $('#geometry_opacity').val(feature.attributes.fillOpacity*100);
				    }
				    if(typeof(feature.attributes.strokeOpacity) != 'undefined'){
					    $('#geometry_strokeOpacity').val(feature.attributes.strokeOpacity*100);
				    }
				    if(typeof(feature.attributes.strokeDashstyle) != 'undefined'){
					    $('#geometry_lineStyle').val(feature.attributes.strokeDashstyle);
				    }
				}
			}
		}

		/* Clear the list of selected features */
		function clearSelected(feature) {
		    selectedFeatures = [];
			$('#geometryLabelerHolder').hide(400);
			$('#geometry_label').val("");
			$('#geometry_comment').val("");
			$('#geometry_color').val("");
			$('#geometry_lat').val("");
			$('#geometry_lon').val("");
			$('#geometry_showlabel').removeAttr('checked');
			selectCtrl.deactivate();
			selectCtrl.activate();
			$('#geometry_color').ColorPickerHide();
			$('#font_color').ColorPickerHide();
			$('#outline_color').ColorPickerHide();
			$('#geometry_strokeColor').ColorPickerHide();
		}

		/* Feature starting to move */
		function startDrag(feature, pixel) {
		    lastPixel = pixel;
		}

		/* Feature moving */
		function doDrag(feature, pixel) {
		    for (f in selectedFeatures) {
		        if (feature != selectedFeatures[f]) {
		            var res = map.getResolution();
		            selectedFeatures[f].geometry.move(res * (pixel.x - lastPixel.x), res * (lastPixel.y - pixel.y));
		            vlayer.drawFeature(selectedFeatures[f]);
		        }
		    }
		    lastPixel = pixel;
		}

		/* Featrue stopped moving */
		function endDrag(feature, pixel) {
		    for (f in selectedFeatures) {
		        f.state = OpenLayers.State.UPDATE;
		    }
			refreshFeatures();
			
			// Fetching Lat Lon Values
		  	var latitude = parseFloat($("#latitude").val());
			var longitude = parseFloat($("#longitude").val());
			
			// Looking up country name using reverse geocoding
			reverseGeocode(latitude, longitude);
		}
		
		
		/*****************************************************
		 * This function is used to store all the features as
		 * JSON in an hidden input field
		 ****************************************************/
		function refreshFeatures(event) {
			var geoCollection = new OpenLayers.Geometry.Collection;
			$('input[name="geometry[]"]').remove();
			for(i=0; i < vlayer.features.length; i++) {
				newFeature = vlayer.features[i].clone();
				newFeature.geometry.transform(proj_900913,proj_4326);
				geoCollection.addComponents(newFeature.geometry);
				if (vlayer.features.length == 1 && vlayer.features[i].geometry.CLASS_NAME == "OpenLayers.Geometry.Point") {
					// If feature is a Single Point - save as lat/lon
				} else {
					// Otherwise, save geometry values
					// Convert to Well Known Text
					var format = new OpenLayers.Format.WKT();
					var geometry = format.write(newFeature);
					var label = '';
					var showLabel = false;
					var comment = '';
					var lon = '';
					var lat = '';
					var color = 'ffcc66';
					var strokewidth = '2';
					var icon = '';
					var fontSize = 12;
					var fontColor = 'ffffff';
					var labelOutlineColor = '000000';
					var labelOutlineWidth = '2';
					var strokeColor = 'cc0000';
					var fillOpacity = 0.7;
					var strokeOpacity = 1;
					var strokeDashstyle = 'solid';
					
					if (typeof(vlayer.features[i].attributes.icon) != 'undefined'){
					    icon = vlayer.features[i].attributes.icon;
					    icon = icon.substr(icon.lastIndexOf('/')+1);
					}
					
					if ( typeof(vlayer.features[i].attributes.strokeDashstyle) != 'undefined') {
						strokeDashstyle = vlayer.features[i].attributes.strokeDashstyle;
					}
					
					if ( typeof(vlayer.features[i].attributes.fillOpacity) != 'undefined') {
						fillOpacity = parseFloat(vlayer.features[i].attributes.fillOpacity);
					}
					
					if ( typeof(vlayer.features[i].attributes.strokeOpacity) != 'undefined') {
						strokeOpacity = parseFloat(vlayer.features[i].attributes.strokeOpacity);
					}
					
					
					if ( typeof(vlayer.features[i].attributes.strokeColor) != 'undefined') {
						strokeColor = vlayer.features[i].attributes.strokeColor.substring(1);
					}
					
					if ( typeof(vlayer.features[i].attributes.labelOutlineColor) != 'undefined') {
						labelOutlineColor = vlayer.features[i].attributes.labelOutlineColor.substring(1);
					}
					
					if ( typeof(vlayer.features[i].attributes.labelOutlineWidth) != 'undefined') {
						labelOutlineWidth = vlayer.features[i].attributes.labelOutlineWidth;
					}
					
					if ( typeof(vlayer.features[i].attributes.fontColor) != 'undefined') {
						fontColor = vlayer.features[i].attributes.fontColor.substring(1);
					}
					
					if ( typeof(vlayer.features[i].attributes.fontSize) != 'undefined') {
						fontSize = vlayer.features[i].attributes.fontSize;
					}
					
					if ( typeof(vlayer.features[i].label) != 'undefined') {
						label = vlayer.features[i].label;
					}
					if(typeof(vlayer.features[i].showLabel) != 'undefined') {
					    showLabel = vlayer.features[i].showLabel;
					}
					if ( typeof(vlayer.features[i].comment) != 'undefined') {
						comment = vlayer.features[i].comment;
					}
					if ( typeof(vlayer.features[i].lon) != 'undefined') {
						lon = vlayer.features[i].lon;
					}
					if ( typeof(vlayer.features[i].lat) != 'undefined') {
						lat = vlayer.features[i].lat;
					}
					if ( typeof(vlayer.features[i].color) != 'undefined') {
						color = vlayer.features[i].color;
					}
					if ( typeof(vlayer.features[i].strokewidth) != 'undefined') {
						strokewidth = vlayer.features[i].strokewidth;
					}
					geometryAttributes = JSON.stringify({ 
					    icon:icon, 
					    geometry: geometry, 
					    label: label, 
					    showLabel: showLabel, 
					    comment: comment,
					    lat: lat, 
					    lon: lon, 
					    color: color, 
					    strokewidth: strokewidth,
					    fontSize: fontSize,
					    fontColor: fontColor,
					    labelOutlineWidth: labelOutlineWidth,
					    labelOutlineColor: labelOutlineColor,
					    strokeColor: strokeColor,
					    fillOpacity: fillOpacity,
					    strokeOpacity: strokeOpacity,
					    strokeDashstyle: strokeDashstyle
					    });
					    console.log(geometryAttributes);
					$('#reportForm').append($('<input></input>').attr('name','geometry[]').attr('type','hidden').attr('value',geometryAttributes));
				}
			}
			
			// Centroid of location will constitute the Location
			// if its not a point
			centroid = geoCollection.getCentroid(true);
			$("#latitude").val(centroid.y);
			$("#longitude").val(centroid.x);
		}
		
		function incidentZoom(event) {
			$("#incident_zoom").val(map.getZoom());
		}
		
		
		
		// Reverse GeoCoder
		function reverseGeocode(latitude, longitude) {		
			var latlng = new google.maps.LatLng(latitude, longitude);
			var geocoder = new google.maps.Geocoder();
			geocoder.geocode({'latLng': latlng}, function(results, status){
				if (status == google.maps.GeocoderStatus.OK) {
					var country = results[results.length - 1].formatted_address;
					$("#country_name").val(country);
				} else {
					console.log("Geocoder failed due to: " + status);
				}
			});
		}
		
		
		function decimalLatToHours(lat){
		    var signlat = 1;
		    if(lat < 0)  { signlat = -1; }
		    var latAbs = Math.abs( Math.round(lat * 1000000.));
		    var hours = Math.floor(latAbs / 1000000) * signlat;
		    return hours;
		}
		
		function decimalLatToMinutes(lat){
		    var signlat = 1;
		    if(lat < 0)  { signlat = -1; }
		    var latAbs = Math.abs( Math.round(lat * 1000000.));
		    var minutes = Math.floor(  ((latAbs/1000000) - Math.floor(latAbs/1000000)) * 60) ;
		    return minutes;
		}
		
		function decimalLatToSeconds(lat){
		    var signlat = 1;
		    if(lat < 0)  { signlat = -1; }
		    var latAbs = Math.abs( Math.round(lat * 1000000.));
		    var seconds = ( Math.floor(((((latAbs/1000000) - Math.floor(latAbs/1000000)) * 60) - Math.floor(((latAbs/1000000) - Math.floor(latAbs/1000000)) * 60)) * 100000) *60/100000 );
		    return seconds;
		}
		
		
		
		function decimalLonToHours(lon){
		    var signlon = 1;
		    if(lon < 0)  { signlon = -1; }
		    var lonAbs = Math.abs(Math.round(lon * 1000000.));
		    var hours = Math.floor(lonAbs / 1000000) * signlon;
		    return hours;
		}
		
		function decimalLonToMinutes(lon){
		    var signlon = 1;
		    if(lon < 0)  { signlon = -1; }
		    var lonAbs = Math.abs(Math.round(lon * 1000000.));
		    var minutes = Math.floor(  ((lonAbs/1000000) - Math.floor(lonAbs/1000000)) * 60);
		    return minutes;
		}
		
		function decimalLonToSeconds(lon){
		    var signlon = 1;
		    if(lon < 0)  { signlon = -1; }
		    var lonAbs = Math.abs(Math.round(lon * 1000000.));
		    var seconds = ( Math.floor(((((lonAbs/1000000) - Math.floor(lonAbs/1000000)) * 60) - Math.floor(((lonAbs/1000000) - Math.floor(lonAbs/1000000)) * 60)) * 100000) *60/100000 );
		    return seconds;
		}
		
		function hmsToDecimal(hour, minute, second){
		    latsign = 1;
		    if(hour < 0)  { latsign = -1; }
		    var absdlat = Math.abs( Math.round(hour * 1000000.));
		    minute = Math.abs(Math.round(minute * 1000000.)/1000000);  //integer
		    var absmlat = Math.abs(Math.round(minute * 1000000.));  //integer
		    second = Math.abs(Math.round(second * 1000000.)/1000000);
		    var absslat = Math.abs(Math.round(second * 1000000.)); 
		    
		    var decimal = Math.round(absdlat + (absmlat/60.) + (absslat/3600.) ) * latsign/1000000;
			
		    return decimal;
		}
