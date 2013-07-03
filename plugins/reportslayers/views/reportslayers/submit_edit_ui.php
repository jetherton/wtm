<?php
/***********************************************************
* submit_edit_ui.php - view for the Reports Layers plugin
* This software is copy righted by WatchTheMed 2013
* Writen by John Etherton, Etherton Technologies <http://ethertontech.com>
* Started on 2013-06-02
* This plugin lets users access layers on reports
*************************************************************/
?>


<div class="report_row" id="reportslayers_ui">
	<h4><?php echo Kohana::lang('ui_main.layers'); ?> </h4>
	<div class="report_category" id="submit_layers">
	<?php		
	if(isset($layers[0])){
	    $top_level_layers = sizeof($layers[0]);
	    $start = 0;
	    $stop = intval($top_level_layers/2);
	    $column_count = 1;
	    for($j = 0; $j < 2; $j++){
		echo '<ul class="category-column category-column-'.$column_count.'" id="category-column-'.$column_count.'">';
		for($i = $start; $i < $stop; $i++)
		{
		    $layer = $layers[0][$i];
		    $layer_id = $layer->id;
		    $layer_name = $layer->layer_name;
		    $layer_color = $layer->layer_color;
		    $layer_meta_data = $layer->meta_data;
		    echo '<li title="'.htmlentities($layer_meta_data).'">';
		    echo '<label>';
		    echo form::checkbox('reportslayers[]', $layer_id, in_array($layer_id, $selections), ' class="check-box layer_switcher" id="layer_'.$layer_id.'"');
		    echo '<span class="swatch" style="background-color:#'.$layer_color.'"></span>';
		    echo '<span class="layer-name">'.$layer_name.'</span>';
		    echo '</label>';

		    render_child_layers_edit_submit($layer, $layers);
		    echo '</li>';
		}
		echo '</ul>';
		$start =  $stop;
		$stop =  $top_level_layers;
		$column_count++;
	    }
	}
	?>
	

	</div>
</div>


<?php if(sizeof($selections)>0){?>
<script type="text/javascript">
    jQuery(window).load(function() {
	<?php
	    foreach($selections as $selection){
		echo 'jQuery("#layer_'.$selection.'").change();'."\n";
	    }
	?>
    });
</script>
<?php } ?>




				
				
<?php 

/**
 * Used to render child layers.
 * @param unknown_type $layer_id
 */
function render_child_layers_edit_submit($layer, $layers){
	
	if(!isset($layers[$layer->id])){
		return;
	}
	
	$child_layers = $layers[$layer->id];
	echo '<ul>';
	
	foreach($child_layers as $child_layer){
		
		$layer_id = $child_layer->id;
		$layer_name = $child_layer->layer_name;
		$layer_color = $child_layer->layer_color;
		$layer_meta_data = $child_layer->meta_data;
		echo '<li title="'.htmlentities($layer_meta_data).'">';
		echo '<label>';
		echo form::checkbox('reportslayers[]', $layer_id, false, ' class="check-box layer_switcher" id="layer_'.$layer_id.'"');
		echo '<span class="swatch" style="background-color:#'.$layer_color.'"></span>';
		echo '<span class="layer-name">'.$layer_name.'</span>';
		echo '</label>';
							
		render_child_layers_edit_submit($child_layer, $layer_id);
		echo '</li>';
	}
	
	echo '</ul>';
	
	}

	?>