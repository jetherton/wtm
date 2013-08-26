<div id="layers-box">
<a class="btn toggle" id="layers-menu-toggle" class="toggle" href="#kml_switch"><?php echo Kohana::lang('ui_main.layers');?> <span class="btn-icon ic-right">&raquo;</span></a>
					<!-- Layers (KML/KMZ) -->
					<ul id="kml_switch" class="category-filters map-menu-box" style="z-index:10000;">
					   
						<?php						
						foreach ($layers[0] as $layer_id => $layer)
						{
							$layer_name = $layer->layer_name;
							$layer_color = $layer->layer_color;
							$layer_icon = $layer->icon;
							$layer_url = $layer->layer_url;
							$layer_file = $layer->layer_file;
							$layer_meta_data = $layer->meta_data;
							$layer_link = (!$layer_url) ?
								url::base().Kohana::config('upload.relative_directory').'/'.$layer_file :
								$layer_url;
							echo '<li><a href="#" class="toggleLayer" id="layer_'. $layer .'" >';
							echo '<div class="color_swatch" style="background-color:#'.$layer_color.';"></div>';
							if($layer_icon != null OR $layer_icon != ""){
							    echo '<span class="swatch" >';
							    echo '<image src="'.url::base().'media/uploads/'.$layer_icon. '"/>';
							} else {
							    echo '<span class="swatch" style="background-color:#'.$layer_color.'">';
							}
							echo '</span>';
							
							echo '<span class="layer-name">'.$layer_name.'</span>';														
							echo '</a>';
							echo '<a href="#" class="layer_meta_clicker" id="meta_layer_click_'.$layer.'">&nbsp;</a>';
							echo '<div id="layerMetaInfo_'.$layer.'" class="layerMeta">';
							echo $layer_meta_data;
							echo '</div>';
							echo '</li>';
							
							//render_child_layers($layer, $layers);
						}
						?>
					    <!--
					     <li>
						<a href="#" id="layer_bath" class="toggleLayer">
						    <div class="color_swatch" style="background-color:#ea00ff;">
						    </div>						    
						    <span class="swatch"><img src="<?php echo url::base();?>themes/wtm/images/bathmetric.png"/></span>
						    <span class="layer-name">Bathymetry</span>						    
						</a>
						<a href="#" class="layer_meta_clicker" id="meta_layer_click_bath">&nbsp;</a>
						<div class="layerMeta" id="layerMetaInfo_bath">
						      <p>
							  <span style="font-family: Arial, Helvetica, sans; font-size: 11px; line-height: 14px; text-align: justify;">
							      Bathymetry is the study of underwater depth of lake or ocean floors. In other words, bathymetry is the underwater equivalent to hypsometry or topography. The name comes from Greek βαθύς (bathus), "deep",[1] and μέτρον (metron), "measure".[2] Bathymetric (or hydrographic) charts are typically produced to support safety of surface or sub-surface navigation, and usually show seafloor relief or terrain as contour lines (called depth contours or isobaths) and selected depths (soundings), and typically also provide surface navigational information. Bathymetric maps (a more general term where navigational safety is not a concern) may also use a Digital Terrain Model and artificial illumination techniques to illustrate the depths being portrayed. Paleobathymetry is the study of past underwater depths.
							  </span>
							  <br>
						      </p>
						</div>
					    </li>
					    -->
					    
					</ul>
				</div>
				
				
				
<?php 

/**
 * Used to render child layers.
 * @param unknown_type $layer_id
 */
function render_child_layers($layer, $layers)	{
	
	if(!isset($layers[$layer->id])){
		return;
	}
	
	$child_layers = $layers[$layer->id];
	echo '<ul >';
	
	foreach($child_layers as $child_layer){
		
		$layer = $child_layer->id;
		$layer_name = $child_layer->layer_name;
		$layer_color = $child_layer->layer_color;
		$layer_url = $child_layer->layer_url;
		$layer_file = $child_layer->layer_file;
		$layer_meta_data = $child_layer->meta_data;
		$layer_link = (!$layer_url) ?
			url::base().Kohana::config('upload.relative_directory').'/'.$layer_file :
			$layer_url;
		
		
		echo '<li><a href="#" id="layer_'. $layer .'" meta_data="<strong>'.htmlentities($layer_name).':</strong><br/><br/>'.htmlentities($layer_meta_data).'">
		<span class="swatch" style="background-color:#'.$layer_color.'"></span>
							<span class="layer-name">'.$layer_name.'</span></a></li>';
							
		render_child_layers($child_layer, $layers);
	}
	
	echo '</ul>';
	
	}

	?>