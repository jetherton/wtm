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
				<!-- tabs -->
				<div class="t">					
					<!-- tab -->
					<div class="a">
						<div class="tab_form">

							<strong><?php echo Kohana::lang('ui_main.layer_name');?>:</strong><br />
							<?php print form::input('layer_name', '', ' class="text" id="layer_name"'); ?>
						</div>
						<div class="tab_form">
							<strong><?php echo Kohana::lang('ui_main.color');?>:</strong><br />
							<?php print form::input('layer_color', '', ' class="text" id="layer_color"'); ?>
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
						<div class="tab_form">
							<strong><?php echo Kohana::lang('wtm.Layer Meta-Data');?>:</strong><br />
							<?php print form::textarea('meta_data','', 'style="width:500px;height:380x;" id="meta_data"');?>
						</div>
						<div class="tab_form">
							<strong><?php echo Kohana::lang('uploadlayers.icon')?>:</strong></br>
							<div id="layer_image"></div>
						</div>
						<div style="clear:both"></div>
												
					</div>
							
		</div>
		<div id="triggerUpload" class="btn btn-primary" style="margin-top: 10px;">
		  						<i class="icon-upload icon-white"></i> Upload now
							</div>
</div>
<?php echo $js;?>