<div class="slider-holder">
    <a href="#" id="timelineSliderToggle">&nbsp;</a>
    <div id="graphSwitch" onclick="toggleGraph();">Histogram</div>
	<?php echo form::open(NULL, array('method' => 'get')); ?>
		<input type="hidden" value="0" name="currentCat" id="currentCat"/>
		<fieldset>
			<label for="startDate"><?php echo Kohana::lang('ui_main.from'); ?>:</label>
			<select name="startDate" id="startDate"><?php echo $startDate; ?></select>
			<label for="endDate"><?php echo Kohana::lang('ui_main.to'); ?>:</label>
			<select name="endDate" id="endDate"><?php echo $endDate; ?></select>
		</fieldset>
	<?php echo form::close(); ?>
</div>
<?php if (Kohana::config('settings.enable_timeline')): ?>

<script type="text/javascript">
    function toggleGraph(){
	if($(".graph-holder").is(":visible")){
	    $(".graph-holder").hide(400);
	    $("#graphSwitch").removeClass("active");
	} else {
	    $("#graphSwitch").addClass("active");
	    $(".graph-holder").show(400);
	    window.setTimeout(function(){reDrawGraph();},500);
	    
	}
    }
</script>
<div id="graphWrapper">    

    <div id="graph" class="graph-holder"></div>
</div>
<?php endif; ?>