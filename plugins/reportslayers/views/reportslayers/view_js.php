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


<?php 
/***********************************************
 * Note this requires the following code in the /themes/active_theme/views/reports/view_js.php file
 * Also needs to go in /themes/active_theme/views/reports/submit_edit_js.php
 */

/*
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
        var title = $(this, "strong").text();
	//remove the layer if it was clicked again
	if(isCurrentLayer){
		map.trigger("deletelayer", title);
			$(this).removeClass("active");
	}
	
	// Was a different layer selected?
	if (!isCurrentLayer) {            
		// Set the currently selected layer as the active one
		$(this).addClass("active");
		map.addLayer(Ushahidi.KML, {
			name: title,
			url: "json/layer/" + layerId
		});
	}

	return false;
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