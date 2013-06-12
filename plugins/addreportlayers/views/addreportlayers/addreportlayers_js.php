<?php defined('SYSPATH') or die('No direct script access.');
/***********************************************************
* defaultacategories_js.php - Javascript for DefaultCategories Plugin
* This software is copy righted by WatchTheMed 2013
* Writen by Dylan Gillespie, Etherton Technologies <http://ethertontech.com>
* Started on 2013-07-06
* This javascript is to make categories default on.
*************************************************************/
?>

<link rel="stylesheet" type="text/css" href="<?php echo url::base(); ?>plugins/addreportlayers/media/css/addreportlayersCSS.css"/>
<script type="text/javascript">	

	$(document).ready(function(){
		var div = "<a id='testID' > HI </a>\
			<div style='position:absolute'>\
			<div id='addreportlayersWindow'>\
				<div class='table-holder'>\
		<table class='table'>\
			<thead>\
				<tr>\
					<th class='col-1'>&nbsp;</th>\
					<th class='col-2'><?php echo Kohana::lang('ui_main.layers');?></th>\
					<th class='col-3'><?php echo Kohana::lang('ui_main.color');?></th>\
					<th class='col-4'><?php echo Kohana::lang('ui_main.actions');?></th>\
				</tr>\
			</thead>\
			<tfoot>\
				<tr class='foot'>\
					<td colspan='4'>\
						<?php echo $pagination; ?>\
					</td>\
				</tr>\
			</tfoot>\
			<tbody>\
				<?php
				if ($total_items == 0)
				{
				?>
					<tr>\
						<td colspan='4' class='col'>\
							<h3><?php echo Kohana::lang('ui_main.no_results');?></h3>\
						</td>\
					</tr>\
				<?php	
				}
				foreach ($layers as $layer)
				{
					$layer_id = $layer->id;
					$layer_name = $layer->layer_name;
					$layer_color = $layer->layer_color;
					$layer_url = $layer->layer_url;
					$layer_file = $layer->layer_file;
					$layer_visible = $layer->layer_visible;
					?>
					<tr>\
						<td class='col-1'>&nbsp;</td>\
						<td class='col-2'>\
							<div class='post'>\
								<h4><?php echo $layer_name; ?></h4>\
							</div>\
							<ul class='info'>\
								<?php
								if($layer_file)
								{
									?><li class='none-separator'><?php echo Kohana::lang('ui_main.kml_kmz_file');?>: <p><strong><?php echo $layer_file; ?></strong></p>\
									&nbsp;[<a href="javascript:layerAction('i','DELETE FILE','<?php echo rawurlencode($layer_id);?>')">Delete</a>]</li>\
									<?php
								}
								?>
							</ul>\
							<ul class='links'>\
								<?php
								if($layer_url)
								{
									?><li class='none-separator'><?php echo Kohana::lang('ui_main.kml_url');?>: <p><strong><?php echo text::auto_link($layer_url); ?></strong></p></li>\<?php
								}
								?>
							</ul>\
						</td>\
						<td class='col-3'>\
						<?php echo '<img src='.url::base().'swatch/?c='.$layer_color.'&w=30&h=30'>'; ?>\
						</td>\
						<td class='col-4'>\
							<ul>\
								<li class='none-separator'><a href='#add' onClick='fillFields('<?php echo(rawurlencode($layer_id)); ?>','<?php echo(rawurlencode($layer_name)); ?>','<?php echo(rawurlencode($layer_url)); ?>','<?php echo(rawurlencode($layer_color)); ?>','<?php echo(rawurlencode($layer_file)); ?>')'><?php echo Kohana::lang('ui_main.edit');?></a></li>\
								<li class='none-separator'><a class='status_yes' href='javascript:layerAction('v','SHOW/HIDE','<?php echo(rawurlencode($layer_id)); ?>')'><?php if ($layer_visible) { echo Kohana::lang('ui_main.visible'); } else { echo Kohana::lang('ui_main.hidden'); }?></a></li>\
<li><a href='javascript:layerAction('d','DELETE','<?php echo(rawurlencode($layer_id)); ?>')' class='del'><?php echo Kohana::lang('ui_main.delete');?></a></li>\
							</ul>\
						</td>\
					</tr>\
					<?php									
				}
				?>\
			</tbody>\
		</table>\
	</div>\
			</div>\
		</div>\
";
		//$('#panel').before(div);
		$('#testID').click(function(){
			$('#addreportlayersWindow').toggle();
		});
	});
	
	
</script>
	