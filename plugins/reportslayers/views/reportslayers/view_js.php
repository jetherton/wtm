<script type="text/javascript">

var layersToLoad = [
<?php if(sizeof($selections) > 0){ 
    $i = 0;
    foreach($selections as $selection){
	$i++;
	if($i > 1){echo ",";}
	echo $selection;
    }
} ?>
];



jQuery(window).load(function() {
	setTimeout(iterativelyLoadLayers, 250);
	
});

function iterativelyLoadLayers(){
    if(layersToLoad.length > 0){
	var layerId = layersToLoad.pop();
	$("#layer_"+layerId).click();
	setTimeout(iterativelyLoadLayers, 250);
	var d = new Date();
	if(typeof(console) == 'object'){console.log('chainging selection on layer_'+layerId + ' at: '+d.getTime());}
    }
}
	  


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
	
	




<?php 
/***********************************************
 * Note this requires the following code in the /themes/active_theme/views/reports/view_js.php file
 * Also needs to go in /themes/active_theme/views/reports/submit_edit_js.php
 */

/*
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
 */

?>






$(function(){
	
	// show/hide report filters and layers boxes on home page map
	$("a.toggle").toggle(
		function() { 
			$($(this).attr("href")).show();
			$(this).addClass("active-toggle");
		},
		function() { 
			$($(this).attr("href")).hide();
			$(this).removeClass("active-toggle");
		}
	);
	
});


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
</script>