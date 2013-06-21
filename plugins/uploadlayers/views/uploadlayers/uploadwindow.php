<?php defined('SYSPATH') or die('No direct script access.');
/***********************************************************
 * uploadwindow.php - Pop up window for uploads
* This software is copy righted by WatchTheMed 2013
* Writen by Dylan Gillespie, Etherton Technologies <http://ethertontech.com>
* Started on 2013-20-06
* Creates the pop up window for uploads.
*************************************************************/
?>
<div id="uploadWindow" style="color:black">
	
	<h3 style="text-color:#000"><?php echo Kohana::lang('uploadlayers.upload');?></h3>
	
	<div class="report_row">
		<div id="manual-fine-uploader"></div>
			
	</div>
<<<<<<< HEAD



				<!-- tabs -->
				<div class="tabs">					
					<!-- tab -->
					<div class="tab">
=======
	
	</form>
</div>

<div class="bg">
				<h2>
					<?php admin::manage_subtabs("layers"); ?>
				</h2>
				<?php
				if ($form_error) {
				?>
					<!-- red-box -->
					<div class="red-box">
						<h3><?php echo Kohana::lang('ui_main.error');?></h3>
						<ul>
						<?php
						foreach ($errors as $error_item => $error_description)
						{
							// print "<li>" . $error_description . "</li>";
							print (!$error_description) ? '' : "<li>" . $error_description . "</li>";
						}
						?>
						</ul>
					</div>
				<?php
				}

				if ($form_saved) {
				?>
					<!-- green-box -->
					<div class="green-box">
						<h3><?php echo Kohana::lang('ui_main.layer_has_been');?> <?php echo $form_action; ?>!</h3>
					</div>
				<?php
				}
				?>
				<!-- report-table -->
				<div class="report-form">
					<?php print form::open(NULL,array('id' => 'layerListing',
					 	'name' => 'layerListing')); ?>
						<input type="hidden" name="action" id="action" value="">
						<input type="hidden" name="layer_id" id="layer_id_action" value="">
						<div class="table-holder">
							<table class="table">
								<thead>
									<tr>
										<th class="col-1">&nbsp;</th>
										<th class="col-2"><?php echo Kohana::lang('ui_main.layers');?></th>
										<th class="col-3"><?php echo Kohana::lang('ui_main.color');?></th>
										<th class="col-4"><?php echo Kohana::lang('ui_main.actions');?></th>
									</tr>
								</thead>
								<tfoot>
									<tr class="foot">
										<td colspan="4">
											<?php echo $pagination; ?>
										</td>
									</tr>
								</tfoot>
								<tbody>
									<?php
									if ($total_items == 0)
									{
									?>
										<tr>
											<td colspan="4" class="col">
												<h3><?php echo Kohana::lang('ui_main.no_results');?></h3>
											</td>
										</tr>
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
										<tr>
											<td class="col-1">&nbsp;</td>
											<td class="col-2">
												<div class="post">
													<h4><?php echo $layer_name; ?></h4>
												</div>
												<ul class="info">
													<?php
													if($layer_file)
													{
														?><li class="none-separator"><?php echo Kohana::lang('ui_main.kml_kmz_file');?>: <p><strong><?php echo $layer_file; ?></strong></p>
														&nbsp;[<a href="javascript:layerAction('i','DELETE FILE','<?php echo rawurlencode($layer_id);?>')">Delete</a>]</li>
														<?php
													}
													?>
												</ul>
												<ul class="links">
													<?php
													if($layer_url)
													{
														?><li class="none-separator"><?php echo Kohana::lang('ui_main.kml_url');?>: <p><strong><?php echo text::auto_link($layer_url); ?></strong></p></li><?php
													}
													?>
												</ul>
											</td>
											<td class="col-3">
											<?php echo "<img src=\"".url::base()."swatch/?c=".$layer_color."&w=30&h=30\">"; ?>
											</td>
											<td class="col-4">
												<ul>
													<li class="none-separator"><a href="#add" onClick="fillFields('<?php echo(rawurlencode($layer_id)); ?>','<?php echo(rawurlencode($layer_name)); ?>','<?php echo(rawurlencode($layer_url)); ?>','<?php echo(rawurlencode($layer_color)); ?>','<?php echo(rawurlencode($layer_file)); ?>')"><?php echo Kohana::lang('ui_main.edit');?></a></li>
													<li class="none-separator"><a class="status_yes" href="javascript:layerAction('v','SHOW/HIDE','<?php echo(rawurlencode($layer_id)); ?>')"><?php if ($layer_visible) { echo Kohana::lang('ui_main.visible'); } else { echo Kohana::lang('ui_main.hidden'); }?></a></li>
<li><a href="javascript:layerAction('d','DELETE','<?php echo(rawurlencode($layer_id)); ?>')" class="del"><?php echo Kohana::lang('ui_main.delete');?></a></li>
												</ul>
											</td>
										</tr>
										<?php									
									}
									?>
								</tbody>
							</table>
						</div>
					<?php print form::close(); ?>
				</div>
				
				<!-- tabs -->
				<div class="tabs">
					<!-- tabset -->
					<a name="add"></a>
					<ul class="tabset">
						<li><a href="#" class="active"><?php echo Kohana::lang('ui_main.add_edit');?></a></li>
					</ul>
					<!-- tab -->
					<div class="tab">
						<?php print form::open(NULL,array('enctype' => 'multipart/form-data', 
							'id' => 'layerMain', 'name' => 'layerMain')); ?>
>>>>>>> a6ff7ad6488e2d8e2d9f67af91bfcd73642304ff
						<input type="hidden" id="layer_id" 
							name="layer_id" value="" />
						<input type="hidden" name="action" 
							id="action" value="a"/>
						<input type="hidden" name="layer_file_old" 
							id="layer_file_old" value=""/>
						<div class="tab_form_item">
							<strong><?php echo Kohana::lang('ui_main.layer_name');?>:</strong><br />
<<<<<<< HEAD
							<?php print form::input('layer_name', '', ' class="text" id="layer_name"'); ?>
						</div>
						<div class="tab_form_item">
							<strong><?php echo Kohana::lang('ui_main.layer_url');?>:</strong><br />
							<?php print form::input('layer_url', '', ' class="text long" id="layer_url"'); ?>
						</div>
						<div class="tab_form_item">
							<strong><?php echo Kohana::lang('ui_main.color');?>:</strong><br />
							<?php print form::input('layer_color', '', ' class="text" id="layer_color"'); ?>
=======
							<?php print form::input('layer_name', '', ' class="text"'); ?>
						</div>
						<div class="tab_form_item">
							<strong><?php echo Kohana::lang('ui_main.layer_url');?>:</strong><br />
							<?php print form::input('layer_url', '', ' class="text long"'); ?>
						</div>
						<div class="tab_form_item">
							<strong><?php echo Kohana::lang('ui_main.color');?>:</strong><br />
							<?php print form::input('layer_color', '', ' class="text"'); ?>
>>>>>>> a6ff7ad6488e2d8e2d9f67af91bfcd73642304ff
							<script type="text/javascript" charset="utf-8">
								$(document).ready(function() {
									$('#layer_color').ColorPicker({
										onSubmit: function(hsb, hex, rgb) {
											$('#layer_color').val(hex);
										},
										onChange: function(hsb, hex, rgb) {
											$('#layer_color').val(hex);
										},
										onBeforeShow: function () {
											$(this).ColorPickerSetColor(this.value);
										}
									})
									.bind('keyup', function(){
										$(this).ColorPickerSetColor(this.value);
									});
								});
							</script>
						</div>
<<<<<<< HEAD
						<div class="tab_form_item">
							<strong><?php echo Kohana::lang('wtm.Layer Meta-Data');?>:</strong><br />
							<?php print form::textarea('meta_data','', 'style="width:500px;height:380x;" id="meta_data"');?>
						</div>
						<div style="clear:both"></div>
											</div>		
					</div>
					<div id="triggerUpload" class="btn btn-primary" style="margin-top: 10px;">
  						<i class="icon-upload icon-white"></i> Upload now
					</div>
</div>
<?php echo $js;?>
=======
						<div style="clear:both"></div>
						<div class="tab_form_item">
							<strong><?php echo Kohana::lang('ui_main.kml_kmz_upload');?>:</strong><br />
							<?php print form::upload('layer_file', '', ''); ?>
						</div>
						<div style="clear:both"></div>
						<div class="tab_form_item">
							<input type="submit" class="save-rep-btn" value="<?php echo Kohana::lang('ui_main.save');?>" />
						</div>
						<?php print form::close(); ?>			
					</div>
				</div>
			</div>
>>>>>>> a6ff7ad6488e2d8e2d9f67af91bfcd73642304ff
