<?php
/**
 * Main cluster js file.
 * 
 * Server Side Map Clustering
 *
 * PHP version 5
 * LICENSE: This source file is subject to LGPL license 
 * that is available through the world-wide-web at the following URI:
 * http://www.gnu.org/copyleft/lesser.html
 * @author     Ushahidi Team <team@ushahidi.com> 
 * @package    Ushahidi - http://source.ushahididev.com
 * @module     API Controller
 * @copyright  Ushahidi - http://www.ushahidi.com
 * @license    http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL) 
 */
?>
</script>
<script type="text/javascript">

$(document).ready(function() {
  $("[id^='layer_']").mouseover(function(){
  	var metaData = $(this).attr('meta_data');
  	if(metaData != ''){
  		$("#layer_meta_window").show();
  		$("#layer_meta_window").html(metaData);
  	}
  });
    $("[id^='layer_']").mouseenter(function(){
  	var metaData = $(this).attr('meta_data');
  	if(metaData != '' && typeof metaData != 'undefined'){
  		var layerName = $(this).text();
  		$("#layer_meta_window").show();
  		$("#layer_meta_window").html(metaData);
  	}
  }).mouseleave(function(){
 		//$("#layer_meta_window").hide();
  });
});


		
// Initialize the Ushahidi namespace
Ushahidi.baseUrl = "<?php echo url::site(); ?>";
Ushahidi.markerRadius = <?php echo $marker_radius; ?>;
Ushahidi.markerOpacity = <?php echo $marker_opacity; ?>;
Ushahidi.markerStokeWidth = <?php echo $marker_stroke_width; ?>;
Ushahidi.markerStrokeOpacity = <?php echo $marker_stroke_opacity; ?>;

// Selected Category
var gCategoryId = ['0']; //default to all
//
// Default to most active month
var startTime = <?php echo $active_startDate ?>;

// Default to most active month
var endTime = <?php echo $active_endDate ?>;

// To hold the Ushahidi.Map reference
var map = null;



/******
* Removes the category ID from the string currentCat
*******/
function removeCategoryFilter(idToRemove)
{
        for(i = 0; i < gCategoryId.length; i++)
        {
                if (gCategoryId[i] == idToRemove)
                {
                        //deactivate
                        $("#cat_"+idToRemove).removeClass("active");
                        gCategoryId.splice(i,1);
                        break;
                }
        }
}//end removeCategoryFilter()


/**
 * Toggle Layer Switchers
 */
function toggleLayer(link, layer) {
	if ($("#"+link).text() == "<?php echo Kohana::lang('ui_main.show'); ?>")
	{
		$("#"+link).text("<?php echo Kohana::lang('ui_main.hide'); ?>");
	}
	else
	{
		$("#"+link).text("<?php echo Kohana::lang('ui_main.show'); ?>");
	}
	$('#'+layer).toggle(500);
}

/**
 * Create a function that calculates the smart columns
 */
function smartColumns() {
	//Reset column size to a 100% once view port has been adjusted
	$("ul.content-column").css({ 'width' : "100%"});

	//Get the width of row
	var colWrap = $("ul.content-column").width();

	// Find how many columns of 200px can fit per row / then round it down to a whole number
	var colNum = <?php echo $blocks_per_row; ?>;

	// Get the width of the row and divide it by the number of columns it 
	// can fit / then round it down to a whole number. This value will be
	// the exact width of the re-adjusted column
	var colFixed = Math.floor(colWrap / colNum);

	// Set exact width of row in pixels instead of using % - Prevents
	// cross-browser bugs that appear in certain view port resolutions.
	//$("ul.content-column").css({ 'width' : colWrap});

	// Set exact width of the re-adjusted column	
	//$("ul.content-column li").css({ 'width' : colFixed});
}

/**
 * Callback function for rendering the timeline
 */
function refreshTimeline(options) {

	<?php if (Kohana::config('settings.enable_timeline')) {?>

	// Use report filters if no options passed
	options = options || map.getReportFilters();
	// Copy options object to avoid accidental modifications to reportFilters
	options = jQuery.extend({}, options);

	var url = "<?php echo url::site().'bigmap_json/timeline'; ?>";

	var interval = (options.e - options.s) / (3600 * 24);

	if (interval <= 3) {
		options.i = "hour";
	} else if (interval <= (31 * 6)) {
		options.i = "day";
	} else {
		options.i = "month";
	}

	// Get the graph data
	$.ajax({
		url: url,
		data: options,
		success: function(response) {
			// Clear out the any existing plots
			$("#graph").html('');

			if (response != null && response[0].data.length < 2)
				return;

			var graphData = [];
			var raw = response[0].data;
			for (var i=0; i<raw.length; i++) {
				var date = new Date(raw[i][0]);

				var dateStr = date.getFullYear() + "-";
				dateStr += ('0' + (date.getMonth()+1)).slice(-2) + '-';
				dateStr += ('0' + date.getDate()).slice(-2);

				graphData.push([dateStr, parseInt(raw[i][1])]);
			}
			var timeline = $.jqplot('graph', [graphData], {
				seriesDefaults: {
					color: response[0].color,
					lineWidth: 1.6,
					markerOptions: {
						show: false
					}
				},
				axesDefaults: {
					pad: 1.23,
				},
				axes: {
					xaxis: {
						renderer: $.jqplot.DateAxisRenderer,
						tickOptions: {
							formatString: '%#d&nbsp;%b\n%Y'
						}
					},
					yaxis: {
						min: 0,
						tickOptions: {
							formatString: '%.0f'
						}
					}
				},
				cursor: {show: false}
			});
		},
		dataType: "json"
	});
	<?php }?>
}


jQuery(function() {
	//Etherton: commented this out and put in the enhanced map URL so that we can have the full power
        //of enahcned map
        //var reportsURL = "<?php echo Kohana::config('settings.allow_clustering') == 1 ? "json/cluster" : "json"; ?>";
        var reportsURL = "bigmap_json";

	// Render thee JavaScript for the base layers so that
	// they are accessible by Ushahidi.js
	<?php echo map::layers_js(FALSE); ?>
	
	// Map configuration
	var config = {

		// Zoom level at which to display the map
		zoom: <?php echo Kohana::config('settings.default_zoom'); ?>,

		// Redraw the layers when the zoom level changes
		redrawOnZoom: <?php echo Kohana::config('settings.allow_clustering') == 1 ? "true" : "false"; ?>,

		// Center of the map
		center: {
			latitude: <?php echo Kohana::config('settings.default_lat'); ?>,
			longitude: <?php echo Kohana::config('settings.default_lon'); ?>
		},

		// Map controls
		mapControls: [
			new OpenLayers.Control.Navigation({ dragPanOptions: { enableKinetic: true } }),
			new OpenLayers.Control.Attribution(),
			new OpenLayers.Control.Zoom(),
			new OpenLayers.Control.MousePosition({
				div: document.getElementById('mapMousePosition'),
				numdigits: 5
			}),
			new OpenLayers.Control.Scale('mapScale'),
			new OpenLayers.Control.ScaleLine(),
			new OpenLayers.Control.LayerSwitcher()
		],

		// Base layers
		baseLayers: <?php echo map::layers_array(FALSE); ?>,

		// Display the map projection
		showProjection: true,
		
		reportFilters: {
			s: startTime,
			e: endTime
		}

	};

	// Initialize the map
	map = new Ushahidi.Map('map', config);
	map.addLayer(Ushahidi.GEOJSON, {
		name: "<?php echo Kohana::lang('ui_main.reports'); ?>",
		url: reportsURL,
		transform: false
	}, true, true);


	// Register the referesh timeline function as a callback
	map.register("filterschanged", refreshTimeline);
	setTimeout(function() { refreshTimeline({
		s: startTime,
		e: endTime
	}); }, 800);
    
    
    
			//////////////////////////////////////////////////////////////////////////////////////
			// Parent Category opener
			$("a[id^='drop_cat_']").click(function()
			{
				//get the ID of the category we're dealing with
				var catID = this.id.substring(9);
				//if the kids aren't currenlty shown, show them
				if( !$("#child_"+catID).is(":visible"))
				{
					$("#child_"+catID).show();
					$(this).html("-");
					//since all we're doing is showing things we don't need to update the map
					// so just bounce
					
					$("a[id^='cat_']").addClass("forceRefresh"); //have to do this because IE sucks
					$("a[id^='cat_']").removeClass("forceRefresh"); //have to do this because IE sucks
					
					return false;
				}
				else //kids are shown, deactivate them.
				{
					var kids = $("#child_"+catID).find('a');
					kids.each(function(){
						if($(this).hasClass("active"))
						{
							//remove this category ID from the list of IDs to show
							var idNum = $(this).attr("id").substring(4);
							//removeCategoryFilter(idNum);
						}
					});
					$("#child_"+catID).hide();
					$(this).html("+");
					return false;
				}
			});



	
			//////////////////////////////////////////////////////////////////////////////////////
			// Category Switch Action
			$("a[id^='cat_']").click(function(e)
			{
				//the id of the category that just changed
				var catID = this.id.substring(4);
				
				//the list of categories we're currently showing
				numOfCategoriesSelected = gCategoryId.length;
				 
				 
				//First we check if the "All Categories" button was pressed. If so unselect everything else
				if( catID == 0)
				{
					if( !$("#cat_0").hasClass("active")) //it's being activated so unselect everything else
					{
						//unselect all other selected categories
						while (gCategoryId.length > 0)
						{
							removeCategoryFilter(gCategoryId[0]);
						}				
					}
				}
				else
				{ //we're dealing wtih single categories or parents
				
				
					//first check and see if we're dealing with a parent category
					if( $("#child_"+catID).find('a').length > 0)
					{
						//are we turning the parent on or off?
						var parentActive = $("#cat_"+catID).hasClass("active");
						
						//we want to turn on/off kid categories						
						var kids = $("#child_"+catID).find('a');
						kids.each(function(){
							
							var kidNum = $(this).attr("id").substring(4);
							if(!parentActive)
							{
								$(this).addClass("active");

							}
							else
							{
								removeCategoryFilter(kidNum);
								$(this).removeClass("active");
							}
						});
															
					}//end of if for dealing with parents
					
					//check if we're dealing with a child
					if($(this).attr("cat_parent"))
					{
						//get the parent ID
						parentID = $(this).attr("cat_parent");
						//if it's active deactivate it
						//first check and see if we're adding or removing this category
						if($("#cat_"+parentID).hasClass("active")) //it is active so make it unactive and remove this category from the list of categories we're looking at.
						{ 
							removeCategoryFilter(parentID);
							//also deactiveate any siblings
							var kids = $("#child_"+parentID).find('a');
							kids.each(function(){
								var kidNum = $(this).attr("id").substring(4);
								removeCategoryFilter(kidNum);
								$(this).removeClass("active");
							});
						}
						
					}//end of dealing with kids
					
					//first check and see if we're adding or removing this category
					if($("#cat_"+catID).hasClass("active")) //it is active so make it unactive and remove this category from the list of categories we're looking at.
					{ 
						removeCategoryFilter(catID);
					}
					else //it isn't active so make it active
					{ 
					
                                            $("#cat_"+catID).addClass("active");

                                            //make sure the "all categories" button isn't active
                                            removeCategoryFilter("0");

                                            gCategoryId.push(catID);                                            
					}
				}
				
				
				//check to make sure something is selected. If nothing is selected then select "all gategories"
				if( gCategoryId.length == 0)
				{
					$("#cat_0").addClass("active");
					gCategoryId.push("0");
				}

                                 // Update report filters
                                map.updateReportFilters({c: gCategoryId});

                                e.stopPropagation();

				
				return false;
			});
			

	// Layer selection
	$("ul#kml_switch li > a").click(function(e) {
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
		if(isCurrentLayer){
			map.trigger("deletelayer", $(".layer-name", this).html());
				$(this).removeClass("active");
		}
		
		// Was a different layer selected?
		if (!isCurrentLayer) {
			// Set the currently selected layer as the active one
			$(this).addClass("active");
			map.addLayer(Ushahidi.KML, {
				name: $(".layer-name", this).html(),
				url: "json/layer/" + layerId
			});
		}

		return false;
	});
		
	// Timeslider and date change actions
	$("select#startDate, select#endDate").selectToUISlider({
		labels: 4,
		labelSrc: 'text',
		sliderOptions: {
			change: function(e, ui) {
				var from = $("#startDate").val();
				var to = $("#endDate").val();

				if (to > from && (from != startTime || to != endTime)) {
					// Update the report filters
					startTime = from;
					endTime = to;
					map.updateReportFilters({s: from, e: to});
				}

				e.stopPropagation();
			}
		}
	});
	
	// Media Filter Action
	$('.filters a').click(function() {
		var mediaType = parseFloat(this.id.replace('media_', '')) || 0;
		
		$('.filters a.active').removeClass('active');
		$(this).addClass('active');

		// Update the report filters
		map.updateReportFilters({m: mediaType});
		
		return false;
	});
	
	//Execute the function when page loads
	smartColumns();

	// Are checkins enabled?
	<?php if (Kohana::config('settings.checkins')): ?>
	
	// URL for fetching the checkins
	var checkinsURL = "api/?task=checkin&action=get_ci&mapdata=1&sqllimit=1000&orderby=checkin.checkin_date&sort=ASC";

	// Styling for the checkins
	var ciStyle = new OpenLayers.Style({
		pointRadius: 5,
		fillColor: "${color}",
		strokeColor: "#FFFFFF",
		fillOpacity: "${fillOpacity}",
		strokeOpacity: 0.75,
		strokeWidth: 1.5
	});

	var checkinStyleMap = new OpenLayers.StyleMap({
		default: ciStyle
	});

	$.getJSON(checkinsURL, function(data)
	{
		var jsonFormat = new OpenLayers.Format.JSON();
		var jsonStr = jsonFormat.write(json2GeoJSON(data));
		var format = new OpenLayers.Format.GeoJSON();
		layerFeatures = format.read(jsonStr);
		if (layerFeatures.length > 0) {
			// Add the checkins layer
			map.addLayer(Ushahidi.GEOJSON, {
				url: checkinsURL,
				name: "Checkins",
				features: layerFeatures,
				styleMap: checkinStyleMap,
				transform: true,
			}, false, true);
		}
	});

	/**
	 * Callback function to convert the data -
	 * returned by the checkins API call - to GeoJSON
	 */
	function json2GeoJSON(json) {
		var GeoJSON = {
			type: "FeatureCollection",
			features: []
		};

		$.each(json["payload"]["checkins"], function(index, checkin){
			var checkinImage = (checkin.media !== undefined) ? checkin.media[0].medium : "";
			var checkinLink = (checkinImage !== "") ? checkin.media[0].link : "";
			var feature = {
				type: "Feature",
				properties: {
					name: checkin.msg,
					link: checkinLink,
					color: "#"+checkin.user.color,
					image: checkinImage,
					fillOpacity: checkin.opacity,
				},
				geometry: {
					type: "Point",
					coordinates: [checkin.lon, checkin.lat],
				}
			};
			GeoJSON.features.push(feature);
		});
		return GeoJSON;
	}
	<?php endif; ?>

});

$(window).resize(function () { 
	//Each time the viewport is adjusted/resized, execute the function
	smartColumns();
});


<?php if (Kohana::config('settings.checkins')): ?>
// EK <emmanuel(at)ushahidi.com
// TODO: Load the sidebar with the checkins - moving this to BackboneJS
<?php endif; ?>


</script>
<script type="text/javascript">