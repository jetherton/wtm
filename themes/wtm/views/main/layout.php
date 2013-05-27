<script type="text/javascript">
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

</script>
<!-- main body -->
<div id="main" class="clearingfix">
	<div id="mainmiddle">

		<!-- right column -->
		<div id="report-map-filter-box" class="clearingfix">
			<a class="btn toggle" id="filter-menu-toggle" class="" href="#the-filters"><?php echo Kohana::lang('ui_main.filter_reports_by'); ?><span class="btn-icon ic-right">&raquo;</span></a>
			
			<!-- filters box -->
			<div id="the-filters" class="map-menu-box">
			
				<?php
				// Action::main_sidebar_pre_filters - Add Items to the Entry Page before filters
				Event::run('ushahidi_action.main_sidebar_pre_filters');
				?>
                            
                                <!-- report category filters -->
				<?php 
                                $view = new View('enhancedmap/categories_filter');
                                $view->categories = $categories;
                                $view->categories_view_id = "category_switch";
                                echo $view;
                                ?>
				<!-- / report category filters -->
				
				
				
				
				<!-- report type filters -->
				<div id="report-type-filter" class="filters">
					<h3><?php echo Kohana::lang('ui_main.type'); ?></h3>
						<ul>
							<li><a id="media_0" class="active" href="#"><span><?php echo Kohana::lang('ui_main.reports'); ?></span></a></li>
							<li><a id="media_4" href="#"><span><?php echo Kohana::lang('ui_main.news'); ?></span></a></li>
							<li><a id="media_1" href="#"><span><?php echo Kohana::lang('ui_main.pictures'); ?></span></a></li>
							<li><a id="media_2" href="#"><span><?php echo Kohana::lang('ui_main.video'); ?></span></a></li>
							<li><a id="media_0" href="#"><span><?php echo Kohana::lang('ui_main.all'); ?></span></a></li>
						</ul>
						<div class="floatbox">
								<?php
								// Action::main_filters - Add items to the main_filters
								Event::run('ushahidi_action.map_main_filters');
								?>
							</div>
							<!-- / report type filters -->
				</div>
			
				<?php
				// Action::main_sidebar_post_filters - Add Items to the Entry Page after filters
				Event::run('ushahidi_action.main_sidebar_post_filters');
				?>
						
			</div>
			<!-- / filters box -->
			
			<?php
			if ($layers)
			{
				?>
				<div id="layers-box">
					<a class="btn toggle" id="layers-menu-toggle" class="" href="#kml_switch"><?php echo Kohana::lang('ui_main.layers');?> <span class="btn-icon ic-right">&raquo;</span></a>
					<!-- Layers (KML/KMZ) -->
					<ul id="kml_switch" class="category-filters map-menu-box">
						<?php
						foreach ($layers[0] as $layer_id => $layer)
						{
							$layer_name = $layer->layer_name;
							$layer_color = $layer->layer_color;
							$layer_url = $layer->layer_url;
							$layer_file = $layer->layer_file;
							$layer_meta_data = $layer->meta_data;
							$layer_link = (!$layer_url) ?
								url::base().Kohana::config('upload.relative_directory').'/'.$layer_file :
								$layer_url;
							echo '<li><a href="#" id="layer_'. $layer .'" meta_data="<strong>'.htmlentities($layer_name).':</strong><br/><br/>'.htmlentities($layer_meta_data).'">
							<span class="swatch" style="background-color:#'.$layer_color.'"></span>
							<span class="layer-name">'.$layer_name.'</span></a></li>';
							
							render_child_layers($layer, $layers);
						}
						?>
						<li id="layer_meta_window"></li>
					</ul>
				</div>
				<!-- /Layers -->
				<?php
			}
			?>
			
			
			<!-- additional content -->
			<?php
			if (Kohana::config('settings.allow_reports'))
			{
				?>
				<a class="btn toggle" id="how-to-report-menu-toggle" class="" href="#how-to-report-box"><?php echo Kohana::lang('ui_main.how_to_report'); ?> <span class="btn-icon ic-question">&raquo;</span></a>
				<div id="how-to-report-box" class="map-menu-box">
					
					<div class="how-to-report-methods">

						<!-- Phone -->
						<?php if (!empty($phone_array)) { ?>
						<div>
							<strong><?php echo Kohana::lang('ui_main.report_option_1'); ?></strong>
							<?php foreach ($phone_array as $phone) { ?>
								<?php echo $phone; ?><br/>
							<?php } ?>
						</div>
						<?php } ?>
						
						<!-- External Apps -->
						<?php if (count($external_apps) > 0) { ?>
						<div>
							<strong><?php echo Kohana::lang('ui_main.report_option_external_apps'); ?>:</strong><br/>
							<?php foreach ($external_apps as $app) { ?>
								<a href="<?php echo $app->url; ?>"><?php echo $app->name; ?></a><br/>
							<?php } ?>
						</div>
						<?php } ?>

						<!-- Email -->
						<?php if (!empty($report_email)) { ?>
						<div>
							<strong><?php echo Kohana::lang('ui_main.report_option_2'); ?>:</strong><br/>
							<a href="mailto:<?php echo $report_email?>"><?php echo $report_email?></a>
						</div>
						<?php } ?>

						<!-- Twitter -->
						<?php if (!empty($twitter_hashtag_array)) { ?>
						<div>
							<strong><?php echo Kohana::lang('ui_main.report_option_3'); ?>:</strong><br/>
							<?php foreach ($twitter_hashtag_array as $twitter_hashtag) { ?>
								<span>#<?php echo $twitter_hashtag; ?></span>
								<?php if ($twitter_hashtag != end($twitter_hashtag_array)) { ?>
									<br />
								<?php } ?>
							<?php } ?>
						</div>
						<?php } ?>

						<!-- Web Form -->
						<div>
							<a href="<?php echo url::site() . 'reports/submit/'; ?>"><?php echo Kohana::lang('ui_main.report_option_4'); ?></a>
						</div>

					</div>

				</div>
			<?php } ?>
			<!-- / additional content -->
		</div>
		<!-- / right column -->

		<!-- content column -->
		<div id="content" class="clearingfix">
				<?php
				// Map and Timeline Blocks
				echo $div_map;
				echo $div_timeline;
				?>
			</div>
		</div>
		<!-- / content column -->

	</div>
</div>
<!-- / main body -->

<!-- content -->
<div class="content-container">

	<!-- content blocks -->
	<div class="content-blocks clearingfix">
		<ul class="content-column">
			<?php blocks::render(); ?>
		</ul>
	</div>
	<!-- /content blocks -->

</div>
<!-- content -->







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
	echo '<ul style="margin-left:20px;">';
	
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
