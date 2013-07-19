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
	<a id="submitAReport" href="<?php echo url::base();?>reports/submit">
	    <img src="<?php echo url::base(); ?>themes/wtm/images/submitAReport.png"/>
	</a>	
	<div id="mainmiddle">

		<!-- right column -->
		<div id="report-map-filter-box" class="clearingfix">
			<a class="btn toggle" id="filter-menu-toggle" class="" href="#the-filters"><?php echo Kohana::lang('ui_main.categories'); ?><span id="show_cat_btn" class="btn-icon ic-right">&raquo;</span></a>
			
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
				
				
				
				
							
				<?php
				// Action::main_sidebar_post_filters - Add Items to the Entry Page after filters
				Event::run('ushahidi_action.main_sidebar_post_filters');
				?>

			</div>
			<br/>
			<!-- / filters box -->
			
			<?php
			if ($layers)
			{
				?>
				<div id="layers-box">
					<a class="btn toggle" id="layers-menu-toggle" class="" href="#kml_switch"><?php echo Kohana::lang('ui_main.layers');?> <span id="show_layers_btn" class="btn-icon ic-right">&raquo;</span></a>
					<!-- Layers (KML/KMZ) -->
					<ul id="kml_switch" class="category-filters map-menu-box">
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
							echo '<li><a href="#" id="layer_'. $layer .'" >';
							echo '<div class="color_swatch" style="background-color:#'.$layer_color.';"></div>';
							if($layer_icon != null OR $layer_icon != ""){
							    echo '<span class="swatch" >';
							    echo '<image src="'.url::base().'media/uploads/'.$layer_icon. '"/>';
							} else {
							    echo '<span class="swatch" style="background-color:#'.$layer_color.'">';
							}
							echo '</span>';
							
							echo '<span class="layer-name">'.$layer_name.'</span>';
							echo '<div class="layerMeta">';
							echo $layer_meta_data;
							echo '</div>';
							echo '</a></li>';
							
							//render_child_layers($layer, $layers);
						}
						?>
					</ul>
				</div>
				<!-- /Layers -->
				<?php
			}
			?>
			
			
			<!-- additional content -->
			
			<!-- / additional content -->
		</div>
		<!-- / right column -->

		<!-- content column -->
		<div id="content" class="clearingfix">
				<?php
				// Map and Timeline Blocks
				echo $div_map;
				?>
				<!-- report type filters -->
				<div id="report-type-filter" class="filters">

										<ul>
											<li><div>filter</div></li>
											<li><a id="media_0" href="#"><span><?php echo Kohana::lang('ui_main.all'); ?></span></a></li>
											<li><a id="media_4" href="#"></span></a></li>
											<li><a id="media_1" href="#"></span></a></li>
											<li><a id="media_2" href="#"></a></li>
											
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
				echo $div_timeline;
				?>
			</div>
		</div>
		<!-- / content column -->

	</div>
</div>
<!-- / main body -->

<!-- content -->
<div class="content-container" style="position: relative;top: -130px;">

	<!-- content blocks -->
	<div class="content-blocks clearingfix">
		<ul class="content-column">
		    <li id="front-page-news" class="wtm_head_up" style="width:612px;">
			<h1>News</h1>
			<table style="width:100%;">
			    <?php
				foreach($news as $news_item){
				    ?>
			    <tr>
				<td>
					<?php 
					$media = ORM::Factory('media')->where('incident_id', $news_item->id)->find_all();
					if ($media->count())
					{
						foreach ($media as $photo)
						{
							if ($photo->media_thumb)
							{ // Get the first thumb
								$incident_thumb = url::convert_uploaded_to_abs($photo->media_thumb);
								echo '<img class="teaser_img" src="'.$incident_thumb.'"/>';
								break;
							}
						}
					}
					?>
				    <div class="front_date_cat">
					<?php 
					    $t = strtotime($news_item->incident_date);
					    echo date('Y-m-d',$t).' / ';
					    echo Kohana::lang('ui_main.category').': '.$news_item->category[0]->category_title;
					    echo ' /';
					?>
				    </div>
				    <h1><a href="<?php echo url::base().'/reports/view/'.$news_item->id;?>"><?php echo $news_item->incident_title; ?></a></h1>
				    <div class="front_teaser">
					<?php
					
					
					
					
					$content = $news_item->incident_description;
					$content = substr($content, 0, strpos($content, "\n"));
					$content = html::clean($content);
					
					echo $content.' <a style="text-decoration:underline;" href="'.url::base().'reports/view/'.$news_item->id.'">more&gt;</a>';
					?>
				    </div>
				</td>				
			    </tr>
			    <?php
				}
			    ?>
			</table>
		    </li>
		    <li id="front-col-small"  style="width:310px; padding:0px;">
			<div id="front_all_about" class="wtm_head_up">
			    <div id="front_about">
				<h1>About</h1>
				<p> 
				    WatchTheMed is a network and a tool in order to document violations of 
				    migrants' rights at sea to establish responsiblity for the violations which
				    are structural products of the EU's policy of closure.
				</p>
			    </div>
			    <div id="front_flyer">			
				<p> 
				    Read the Flyer: <a href="/">english</a>, <a href="/">french</a>, <a href="/">arabic</a>
				</p>
			    </div>

			    <div id="how_to_report_box">
				<h2><?php echo Kohana::lang("ui_main.how_to_report");?></h2>
				<!-- Phone -->
				<?php if (!empty($phone_array)) { ?>
				<p>
					<?php echo Kohana::lang('ui_main.report_option_1'); ?>
					<?php foreach ($phone_array as $phone) { ?>
						<?php echo $phone; ?><br/>
					<?php } ?>
				</p>
				<?php } ?>

				<!-- External Apps -->
				<?php if (count($external_apps) > 0) { ?>
				<p>
					<?php echo Kohana::lang('ui_main.report_option_external_apps'); ?>:<br/>
					<?php 
					    $i = 0;
					    foreach ($external_apps as $app) { 
						$i++;
						if($i>1){echo ', ';}?><a href="<?php echo $app->url; ?>"><?php echo $app->name; ?></a><?php } ?>
				</p>
				<?php } ?>

				<!-- Email -->
				<?php if (!empty($report_email)) { ?>
				<p>
					<?php echo Kohana::lang('ui_main.report_option_2'); ?>:<br/>
					<a href="mailto:<?php echo $report_email?>"><?php echo $report_email?></a>
				</p>
				<?php } ?>

				<!-- Twitter -->
				<?php if (!empty($twitter_hashtag_array)) { ?>
				<p>
					<?php echo Kohana::lang('ui_main.report_option_3'); ?>:<br/>
					<?php foreach ($twitter_hashtag_array as $twitter_hashtag) { ?>
						<span>#<?php echo $twitter_hashtag; ?></span>
						<?php if ($twitter_hashtag != end($twitter_hashtag_array)) { ?>
							<br />
						<?php } ?>
					<?php } ?>
				</p>
				<?php } ?>

				<!-- Web Form -->
				<p>
					<a href="<?php echo url::site() . 'reports/submit/'; ?>">
					    <?php echo Kohana::lang('ui_main.report_option_4'); ?>
					    <img style="margin-top:10px;" src="<?php echo url::base();?>themes/wtm/images/submit_a_report.png"/>
					</a>
				</p>



			    </div>
			</div>
			
			<div id="front_social" class="wtm_head_up">
			    <a class="social" href="http://facebook.com"><div id="social_facebook"></div></a>
			    <a class="social" href="http://twitter.com"><div id="social_twitter"></div></a>
			    <a class="social" href="<?php echo url::base();?>rss"><div id="social_rss"></div></a>
			</div>
			
			<div id="tag cloud" class="wtm_head_up">
			    <h1>Not sure what goes here</h1>
			</div>

		    </li>
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
	echo '<ul >';
	
	foreach($child_layers as $child_layer){
		
		$layer = $child_layer->id;
		$layer_name = $child_layer->layer_name;
		$layer_color = $child_layer->layer_color;
		$layer_icon = $child_layer->icon;
		$layer_url = $child_layer->layer_url;
		$layer_file = $child_layer->layer_file;
		$layer_meta_data = $child_layer->meta_data;
		$layer_link = (!$layer_url) ?
			url::base().Kohana::config('upload.relative_directory').'/'.$layer_file :
			$layer_url;
		
		
		echo '<li><a href="#" id="layer_'. $layer .'" meta_data="<strong>'.htmlentities($layer_name).':</strong><br/><br/>'.htmlentities($layer_meta_data).'">';
		if($layer_icon != null OR $layer_icon != ""){
		    echo '<span class="swatch" >';
		    echo '<image src="'.url::base().'media/uploads/'.$layer_icon. '"/>';
		} else {
		    echo '<span class="swatch" style="background-color:#'.$layer_color.'">';
		}
		echo '</span>';
		echo '<span class="layer-name">'.$layer_name.'</span></a></li>';
		
		
							
		render_child_layers($child_layer, $layers);
	}
	
	echo '</ul>';
	
	}

	?>
