<?php 
/**
 * Reports edit view page.
 *
 * PHP version 5
 * LICENSE: This source file is subject to LGPL license 
 * that is available through the world-wide-web at the following URI:
 * http://www.gnu.org/copyleft/lesser.html
 * @author     Ushahidi Team <team@ushahidi.com> 
 * @package    Ushahidi - http://source.ushahididev.com
 * @module     API Controller
 * @copyright  Ushahidi - http://www.ushahidi.com
 * @license    http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL) 
 */
?>
			<div class="bg">
				<h2>
					<?php admin::reports_subtabs("edit"); ?>
				</h2>
				<?php print form::open(NULL, array('enctype' => 'multipart/form-data', 'id' => 'reportForm', 'name' => 'reportForm')); ?>
					<input type="hidden" name="save" id="save" value="">
					<input type="hidden" name="location_id" id="location_id" value="<?php print $form['location_id']; ?>">
					<input type="hidden" name="incident_zoom" id="incident_zoom" value="<?php print $form['incident_zoom']; ?>">
					<input type="hidden" name="country_name" id="country_name" value="<?php echo $form['country_name'];?>" />
					<!-- report-form -->
					<div class="report-form">
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
								<h3><?php echo Kohana::lang('ui_main.report_saved');?></h3>
							</div>
						<?php
						}
						?>
						<div class="head">
							<h3><?php echo $id ? Kohana::lang('ui_main.edit_report') : Kohana::lang('ui_main.new_report'); ?></h3>
							<div class="btns" style="float:right;">
								<ul>
									<li><a href="#" class="btn_save"><?php echo utf8::strtoupper(Kohana::lang('ui_main.save_report'));?></a></li>
									<li><a href="#" class="btn_save_close"><?php echo utf8::strtoupper(Kohana::lang('ui_main.save_close'));?></a></li>
									<li><a href="#" class="btn_save_add_new"><?php echo utf8::strtoupper(Kohana::lang('ui_main.save_add_new'));?></a></li>
									<li><a href="<?php echo url::base().'admin/reports/';?>" class="btns_red"><?php echo utf8::strtoupper(Kohana::lang('ui_main.cancel'));?></a>&nbsp;&nbsp;&nbsp;</li>
									<?php if ($id) {?>
									<li><a href="<?php echo $previous_url;?>" class="btns_gray">&laquo; <?php echo utf8::strtoupper(Kohana::lang('ui_main.previous'));?></a></li>
									<li><a href="<?php echo $next_url;?>" class="btns_gray"><?php echo utf8::strtoupper(Kohana::lang('ui_main.next'));?> &raquo;</a></li>
									<?php } ?>
								</ul>
							</div>
						</div>
						<!-- f-col -->
						<div class="f-col">
							<?php
							// Action::report_pre_form_admin - Runs right before report form is rendered
							Event::run('ushahidi_action.report_pre_form_admin', $id);
							?>
							<?php if ($show_messages) { ?>
							<div class="row">
								<h4 style="margin:0;padding:0;"><a href="#" id="messages_toggle" class="show-messages"><?php echo Kohana::lang('ui_main.show_messages');?></a>&nbsp;</h4>
								<!--messages table goes here-->
			                    <div id="show_messages">
									<?php
									foreach ($all_messages as $message) {
										echo "<div class=\"message\">";
										echo "<strong><u>" . $message->message_from . "</u></strong> - ";
										echo $message->message;
										echo "</div>";
									}
									?>
								</div>
							</div>
							<?php } ?>
							<div class="row">
								<h4><?php echo Kohana::lang('ui_main.form');?> <span>(<?php echo Kohana::lang('ui_main.select_form_type');?>)</span></h4>
								<span class="sel-holder">
									<?php print form::dropdown('form_id', $forms, $form['form_id'],
										' onchange="formSwitch(this.options[this.selectedIndex].value, \''.$id.'\')"') ?>
								</span>
								<div id="form_loader" style="float:left;"></div>
							</div>
							<div class="row">
								<h4><?php echo Kohana::lang('ui_main.title');?> <span class="required">*</span></h4>
								<?php print form::input('incident_title', $form['incident_title'], ' class="text title"'); ?>
							</div>
							<div class="row">
								<h4><?php echo Kohana::lang('ui_main.description');?> <span><?php echo Kohana::lang('ui_main.include_detail');?>.</span> <span class="required">*</span></h4>
								<span class="allowed-html"><?php echo html::allowed_html(); ?></span>
								<?php print form::textarea('incident_description', $form['incident_description'], ' rows="12" cols="40"') ?>
							</div>

							<?php
							// Action::report_form_admin - Runs just after the report description
							Event::run('ushahidi_action.report_form_admin', $id);
							?>

							<?php
							if (!($id))
							{ // Use default date for new report
								?>
								<div class="row" id="datetime_default">
									<h4><a href="#" id="date_toggle" class="new-cat"><?php echo Kohana::lang('ui_main.modify_date');?></a><?php echo Kohana::lang('ui_main.modify_date');?>: 
									<?php echo Kohana::lang('ui_main.today_at').' '.$form['incident_hour']
										.":".$form['incident_minute']." ".$form['incident_ampm']; ?></h4>
								</div>
								<?php
							}
							?>
							<div class="row <?php
								if (!($id))
								{ // Hide date editor for new report
									echo "hide";
								}?> " id="datetime_edit">
								<div class="date-box">
									<h4><?php echo Kohana::lang('ui_main.date');?> <span><?php echo Kohana::lang('ui_main.date_format');?></span></h4>
									<?php print form::input('incident_date', $form['incident_date'], ' class="text"'); ?>								
									<?php print $date_picker_js; ?>				    
								</div>
								<div class="time">
									<h4><?php echo Kohana::lang('ui_main.time');?> <span>(<?php echo Kohana::lang('ui_main.approximate');?>)</span></h4>
									<?php
									print '<span class="sel-holder">' .
								    form::dropdown('incident_hour', $hour_array,
									$form['incident_hour']) . '</span>';
									
									print '<span class="dots">:</span>';
									
									print '<span class="sel-holder">' .
									form::dropdown('incident_minute',
									$minute_array, $form['incident_minute']) .
									'</span>';
									print '<span class="dots">:</span>';
									
									print '<span class="sel-holder">' .
									form::dropdown('incident_ampm', $ampm_array,
									$form['incident_ampm']) . '</span>';
									?>
								</div>
							</div>
							<div class="row">
							<?php Event::run('ushahidi_action.report_form_admin_after_time', $id); ?>
							</div>
							<div class="row">
								<h4><a href="#" id="category_toggle" class="new-cat"><?php echo Kohana::lang('ui_main.new_category');?></a><?php echo Kohana::lang('ui_main.categories');?> 
								<span><?php echo Kohana::lang('ui_main.select_multiple');?>.</span>  <span class="required">*</span></h4>
								<?php print $new_category_toggle_js; ?>
								<!--category_add form goes here-->
			                    <div id="category_add" class="category_add">
			                        <?php
			                        print '<p>'.Kohana::lang('ui_main.add_new_category').'<hr/></p>';
                                    print form::label(array("id"=>"category_name_label", "for"=>"category_name"), Kohana::lang('ui_main.name'));
                                    print '<br/>';
                                    print form::input('category_name', $new_categories_form['category_name'], 'class=""');
                                    print '<br/>';
                                    print form::label(array("id"=>"description_label", "for"=>"description"), Kohana::lang('ui_main.description'));
                                    print '<br/>';
                                    print form::input('category_description', $new_categories_form['category_description'], 'class=""');
                                    print '<br/>';
                                    print form::label(array("id"=>"color_label", "for"=>"color"), Kohana::lang('ui_main.color'));
                                    print '<br/>';
                                    print form::input('category_color', $new_categories_form['category_color'], 'class=""');
                                    print $color_picker_js;
                                    print '<br/>';
                                    print '<span>';
                                    print '<a href="#" id="add_new_category">'.Kohana::lang('ui_main.add').'</a>';
                                    print '</span>';
                                    ?> 
                                </div>

			                    <div class="report_category">
                        	    <?php
									$selected_categories = array();
									if (!empty($form['incident_category']) && is_array($form['incident_category'])) {
										$selected_categories = $form['incident_category'];
									}
									$columns = 2;
									echo category::form_tree('incident_category', $selected_categories, $columns, FALSE, TRUE);
								?>
           						</div>
							</div>
						    
						<?php
						// Action::report_form - Runs right after the report categories
						Event::run('ushahidi_action.admin_report_form', $id);
						?>

						<?php echo $custom_forms; ?>

						</div>
						<!-- f-col-1 -->
						<div class="f-col-1">
							<div class="incident-location">
								<h4><?php echo Kohana::lang('ui_main.incident_location');?></h4>
								<div class="location-info">
									<span><?php echo Kohana::lang('ui_main.latitude');?>:</span>
									<?php print form::input('latitude', $form['latitude'], ' class="text"'); ?>
									<span><?php echo Kohana::lang('ui_main.longitude');?>:</span>
									<?php print form::input('longitude', $form['longitude'], ' class="text"'); ?>
								</div>
								<ul class="map-toggles">
									<li><a href="#" class="smaller-map"><?php echo Kohana::lang('ui_main.smaller_map'); ?></a></li>
									<li style="display:block;"><a href="#" class="wider-map"><?php echo Kohana::lang('ui_main.wider_map'); ?></a></li>
									<li><a href="#" class="taller-map"><?php echo Kohana::lang('ui_main.taller_map'); ?></a></li>
									<li><a href="#" class="shorter-map"><?php echo Kohana::lang('ui_main.shorter_map'); ?></a></li>
								</ul>
								<div id="divMap" class="map_holder_reports">
									<div id="geometryLabelerHolder" class="olControlNoSelect">						    
							<div id="geometryLabeler">
								<div id="geometryLabelComment">
									<span id="geometryLabel">
										<label><?php echo Kohana::lang('ui_main.geometry_label');?>:</label> 
										<?php print form::textarea('geometry_label', '', ' class="lbl_text" style="width:200px;"'); ?>
									</span>
									<span id="geometryShowLabel">
									    <label>Show Label:</label>
									    <?php print form::checkbox('geometry_showlabel', 'show', false, 'style="margin-right:20px;"');?>
									    
									</span>
									<span id="geometryComment">
										<label><?php echo Kohana::lang('ui_main.geometry_comments');?>:</label> 
										<?php print form::textarea('geometry_comment', '', ' class="lbl_text2" style="width:200px;"'); ?>
									</span>
									<span id="geometryIcon">
										    <?php //create the icons array
											$icons = array('incident_circle.png'=>'w',
											    'location_square.png'=>'r',
											    'wreck_cross.png'=>'b',
											    'helicopter.png'=>'g',
											    'aircraft.png'=>'g',
											    'boatpatrol.png'=>'d');
										    ?>
										    <label><?php echo Kohana::lang('wtm.Icon');?>:</label> 
										    <?php 
											print '<select name="geometry_icon" id="geometry_icon" class="lbl_text">';
											foreach($icons as $file=>$name){
											    $path = url::base().'media/img/openlayers/'.$file;
											    print '<option value="'.$path.'" data-image="'.$path.'" > </option>';
											}
											print '</select>';
										    ?>
										    <script language="javascript">
											$(document).ready(function(e) {
											    $("#geometry_icon").msDropDown();  
											    $("#geometry_icon_msdd").css('width','62px');
											});
										    </script>
									</span>
								</div>
								<div>
									
								</div>
								<div>
									
								</div>
								<div>
								    <?php 
									    $opacityArray = array();
									    for($i = 0; $i <= 100; $i++){
										$opacityArray[$i] = $i;
									    }
									    
									    $lineStyle = array(
										'solid'=>'Solid',
										'dot'=>'Dot',
										'dash'=>'Dash',
										'dashdot'=>'Dash Dot',
										'longdash'=>'Long Dash',
										'longdashdot'=>'Long Dash Dot',										
									    );
									?>
									<span id="geometryColor">
										<label><?php echo Kohana::lang('ui_main.geometry_color');?>:</label> 
										<?php print form::input('geometry_color', '', ' class="lbl_text"'); ?>
									</span>
								        <span id="geometryStrokeColor">
										<label>Line <?php echo Kohana::lang('ui_main.geometry_color');?>:</label> 
										<?php print form::input('geometry_strokeColor', '', ' class="lbl_text"'); ?>
									</span>
									<span id="geometryOpacity">
										<label>Fill Opacity:</label> 
										<?php print form::dropdown('geometry_opacity', $opacityArray,70); ?>
									</span>
									<span id="geometryStrokeOpacity">
										<label>Stroke Opacity:</label> 
										<?php print form::dropdown('geometry_strokeOpacity', $opacityArray,100); ?>
									</span>
									<span id="geometryStrokewidth">
										<label><?php echo Kohana::lang('ui_main.geometry_strokewidth');?>:</label> 
										<?php print form::dropdown('geometry_strokewidth', $stroke_width_array, ''); ?>
									</span>
									<span id="geometryLineStyle">
										<br/>
										<label>Line Style:</label> 
										<?php print form::dropdown('geometry_lineStyle', $lineStyle, 'solid'); ?>
									</span>
									<span id="geometryEditPoints">										
										<input type="button" id="geometry_editpoints" value="Edit Points"/>
									</span>
									<span id="geometryLat">
										<label><?php echo Kohana::lang('ui_main.latitude');?>(DD.DD):</label> 
										<?php print form::input('geometry_lat', '', ' class="lbl_text"'); ?>
									</span>
									<span id="geometryLon">
										<label><?php echo Kohana::lang('ui_main.longitude');?>(DD.DD):</label> 
										<?php print form::input('geometry_lon', '', ' class="lbl_text"'); ?>
									</span>
									<span style="display:none;" id="moveFront" title="<?php echo Kohana::lang('wtm.moveFront');?>">
										<label> <?php echo Kohana::lang('wtm.front')?></label>
									</span>
									<span style="display:none;" id="moveBack" title="<?php echo Kohana::lang('wtm.moveBack');?>">
										<label><?php echo Kohana::lang('wtm.back')?></label>
									</span>
									<div id="hoursMinsSeconds">
									    <span id="geometryLatDegrees">
										    <label><?php echo Kohana::lang('ui_main.latitude');?>(DD MM SS):</label> 
										    <?php print form::input('geometry_lat_degrees', '', ' class="lbl_text short_input"'); ?> 
										    <?php print form::input('geometry_lat_minutes', '', ' class="lbl_text short_input"'); ?> 
										    <?php print form::input('geometry_lat_seconds', '', ' class="lbl_text short_input"'); ?>
									    </span>
									    <span id="geometryLonDegrees">
										    <label><?php echo Kohana::lang('ui_main.longitude');?>(DD MM SS):</label> 
										    <?php print form::input('geometry_lon_degrees', '', ' class="lbl_text short_input"'); ?>
										    <?php print form::input('geometry_lon_minutes', '', ' class="lbl_text short_input"'); ?>
										    <?php print form::input('geometry_lon_seconds', '', ' class="lbl_text short_input"'); ?>
									    </span>
									</div>
								</div>
								<div>
									<span id="fontSize">
										<label><?php echo Kohana::lang('wtm.font_size')?>:</label>
										<?php print form::dropdown('font_size', $font_size_array, '12');?>
									</span>
									<span id="fontColor">
										<label><?php echo Kohana::lang('wtm.font_color')?>:</label>
										<?php print form::input('font_color', '', ' class="lbl_text"'); ?>
									</span>
									<span id="outlineWidth">
										<label><?php echo Kohana::lang('wtm.outline_width')?>:</label>
										<?php print form::dropdown('outline_width', $outline_size_array, '2');?>
									</span>
									<span id="outlineColor">
										<label><?php echo Kohana::lang('wtm.outline_color')?>:</label>
										<?php print form::input('outline_color', '', ' class="lbl_text"'); ?>
									</span>
									<span id="zUp">
									    <input style="padding: 0px 5px;" type="button" value="Move to Top" id="zIndexUpBtn"/>
									</span>
									&nbsp;&nbsp;&nbsp;
									<span id="zDown">
									    <input style="padding: 0px 5px;" type="button" value="Move to Bottom" id="zIndexDownBtn"/>
									</span>
								</div>
							</div>
							<div id="geometryLabelerClose"></div>
						</div>
                                                <div id="geometryPointsHolder" class="olControlNoSelect">	
						    <table>
							<tr>
							    <td >
								<select name="editVertex" id="pointsListHolder" style="height:100px; width:300px;" multiple>
								    
								</select>
							    </td>
							    <td id="pointsEditHolder">
								<table>
								    <tr>
									<td>
									    <label><?php echo Kohana::lang('ui_main.latitude');?>(DD.DD):</label> 
									</td>
									<td>
									    <?php print form::input('point_geometry_lat', '', ' class="lbl_text"'); ?>
									</td>
									<td>
									    <label><?php echo Kohana::lang('ui_main.longitude');?>(DD.DD):</label> 
									</td>
									<td>
									<?php print form::input('point_geometry_lon', '', ' class="lbl_text"'); ?>
									</td>
								    </tr>
								
								    <tr>
									<td>
									    <label><?php echo Kohana::lang('ui_main.latitude');?>(DD MM SS):</label> 
									</td>
									<td>
									    <?php print form::input('pointGeometry_lat_degrees', '', ' class="lbl_text short_input"'); ?> 
									    <?php print form::input('pointGeometry_lat_minutes', '', ' class="lbl_text short_input"'); ?> 
									    <?php print form::input('pointGeometry_lat_seconds', '', ' class="lbl_text short_input"'); ?>
									</td>
									<td></td><td></td>
								    </tr>
								    <tr>
									<td>
									    <label><?php echo Kohana::lang('ui_main.longitude');?>(DD MM SS):</label> 
									</td>
									<td>
									    <?php print form::input('pointGeometry_lon_degrees', '', ' class="lbl_text short_input"'); ?>
									    <?php print form::input('pointGeometry_lon_minutes', '', ' class="lbl_text short_input"'); ?>
									    <?php print form::input('pointGeometry_lon_seconds', '', ' class="lbl_text short_input"'); ?>
									</td>
									<td>
									    <input type="button" id="removePointGeometry" value="Remove Point"/>
									</td>
									<td>
									    <input type="button" id="addPointGeometry" value="Add Point"/>
									</td>
								    </tr>
								</table>
							    </td>
							</tr>
						    </table>
						    <div id="geometryPointsClose"></div>
						</div>
								</div>
							</div>
							<div class="incident-find-location">
								<div id="pointPanel" class="olControlEditingToolbar" style="margin-right:0px;">
								    <div id="pointCoords" title="Add a Point With Coordinates"class="olControlDrawFeatureCoordPointItemInactive olButton"></div>
								</div>
								<div id="panel" class="olControlEditingToolbar"></div>
								<div id="deletePanel" class="btns" style="float:left;">
										<a href="#" title="Delete last item added." class="btn_del_last">&nbsp;</a>
										<a title="Re-add last deleted item." href="#" class="btn_undel_last">&nbsp;</a>
										<a href="#" title="Delete selected item."class="btn_del_sel">&nbsp;</a>
								</div>
								<div id="followAspectRatioDiv">
								    Keep Aspect Ratio 
								    <input type="checkbox" checked="checked" name="keepAspectRatio" id="keepAspectRatio" value="yes" onchange="setKeepAspectRatio();"/>
								</div>
								<div style="clear:both;"></div>
								<!--
								<?php print form::input('location_find', '', ' title="'.Kohana::lang('ui_main.location_example').'" class="findtext"'); ?>
								<div class="btns"  style="float:left;">
									<ul>
										<li><a href="#" class="btn_find"><?php echo utf8::strtoupper(Kohana::lang('ui_main.find_location'));?></a></li>
									</ul>
								</div>
								<div id="find_loading" class="incident-find-loading"></div>
								<div style="clear:both;"><?php echo Kohana::lang('ui_main.pinpoint_location');?>.</div>
								-->
							</div>
							<?php Event::run('ushahidi_action.report_form_admin_location', $id); ?>
							<div class="row">
								<div class="town">
									<h4><?php echo Kohana::lang('ui_main.reports_location_name');?>  <span class="required">*</span><br /><span><?php echo Kohana::lang('ui_main.detailed_location_example');?></span></h4>
									<?php print form::input('location_name', $form['location_name'], ' class="text long"'); ?>
								</div>
							</div>
				
				
							<!-- News Fields -->
							<div class="row link-row">
								<h4><?php echo Kohana::lang('ui_main.reports_news');?></h4>
							</div>
							<div id="divNews">
								<?php
								$this_div = "divNews";
								$this_field = "incident_news";
								$this_startid = "news_id";
								$this_field_type = "text";
					
								if (empty($form[$this_field]))
								{
									$i = 1;
									print "<div class=\"row link-row\">";
									print form::input($this_field . '[]', '', ' class="text long"');
									print "<a href=\"#\" class=\"add\" onClick=\"addFormField('$this_div','$this_field','$this_startid','$this_field_type'); return false;\">add</a>";
									print "</div>";
								}
								else
								{
									$i = 0;
									foreach ($form[$this_field] as $value) {									
										print "<div ";
										if ($i != 0) {
											print "class=\"row link-row second\" id=\"" . $this_field . "_" . $i . "\">\n";
										}
										else
										{
											print "class=\"row link-row\" id=\"$i\">\n";
										}
										print form::input($this_field . '[]', $value, ' class="text long"');
										print "<a href=\"#\" class=\"add\" onClick=\"addFormField('$this_div','$this_field','$this_startid','$this_field_type'); return false;\">add</a>";
										if ($i != 0)
										{
											print "<a href=\"#\" class=\"rem\"  onClick='removeFormField(\"#" . $this_field . "_" . $i . "\"); return false;'>remove</a>";
										}
										print "</div>\n";
										$i++;
									}
								}
								print "<input type=\"hidden\" name=\"$this_startid\" value=\"$i\" id=\"$this_startid\">";
								?>
							</div>


							<!-- Video Fields -->
							<div class="row link-row">
								<h4><?php echo Kohana::lang('ui_main.external_video_link');?></h4>
							</div>
							<div id="divVideo">
								<?php
								$this_div = "divVideo";
								$this_field = "incident_video";
								$this_startid = "video_id";
								$this_field_type = "text";
					
								if (empty($form[$this_field]))
								{
									$i = 1;
									print "<div class=\"row link-row\">";
									print form::input($this_field . '[]', '', ' class="text long"');
									print "<a href=\"#\" class=\"add\" onClick=\"addFormField('$this_div','$this_field','$this_startid','$this_field_type'); return false;\">add</a>";
									print "</div>";
								}
								else
								{
									$i = 0;
									foreach ($form[$this_field] as $value) {									
										print "<div ";
										if ($i != 0) {
											print "class=\"row link-row second\" id=\"" . $this_field . "_" . $i . "\">\n";
										}
										else
										{
											print "class=\"row link-row\" id=\"$i\">\n";
										}
										print form::input($this_field . '[]', $value, ' class="text long"');
										print "<a href=\"#\" class=\"add\" onClick=\"addFormField('$this_div','$this_field','$this_startid','$this_field_type'); return false;\">add</a>";
										if ($i != 0)
										{
											print "<a href=\"#\" class=\"rem\"  onClick='removeFormField(\"#" . $this_field . "_" . $i . "\"); return false;'>remove</a>";
										}
										print "</div>\n";
										$i++;
									}
								}
								print "<input type=\"hidden\" name=\"$this_startid\" value=\"$i\" id=\"$this_startid\">";
								?>
							</div>
							
							<?php Event::run('ushahidi_action.report_form_admin_after_video_link', $id); ?>

							<!-- Photo Fields -->
							<div class="row link-row">
								<h4><?php echo Kohana::lang('ui_main.reports_photos');?></h4>
								<?php								
    								if ($incident_media)
                        			{
                        				// Retrieve Media
                        				foreach($incident_media as $photo) 
                        				{
                        					if ($photo->media_type == 1)
                        					{
                        						$thumb = url::convert_uploaded_to_abs($photo->media_thumb);
                        						$large_photo = url::convert_uploaded_to_abs($photo->media_link);
                        						?>
                        						<div class="report_thumbs" id="photo_<?php echo $photo->id; ?>">
	                        						<a class="photothumb" rel="lightbox-group1" href="<?php echo $large_photo; ?>">
	                        						<img src="<?php echo $thumb; ?>" />
	                        						</a>
													&nbsp;&nbsp;
													<a href="#" onClick="deletePhoto('<?php echo $photo->id; ?>', 'photo_<?php echo $photo->id; ?>'); return false;" ><?php echo Kohana::lang('ui_main.delete'); ?></a>
                        						</div>
                        						<?php
                        					}
                        				}
                        			}
			                    ?>
							</div>
							<div id="divPhoto">
								<?php
								$this_div = "divPhoto";
								$this_field = "incident_photo";
								$this_startid = "photo_id";
								$this_field_type = "file";
					
								if (empty($form[$this_field]['name'][0]))
								{
									$i = 1;
									print "<div class=\"row link-row\">";
									print form::upload($this_field . '[]', '', ' class="text long"');
									print "<a href=\"#\" class=\"add\" onClick=\"addFormField('$this_div','$this_field','$this_startid','$this_field_type'); return false;\">add</a>";
									print "</div>";
								}
								else
								{
									$i = 0;
									foreach ($form[$this_field]['name'] as $value) 
									{
										print "<div ";
										if ($i != 0) {
											print "class=\"row link-row second\" id=\"" . $this_field . "_" . $i . "\">\n";
										}
										else
										{
											print "class=\"row link-row\" id=\"$i\">\n";
										}
										// print "\"<strong>" . $value . "</strong>\"" . "<BR />";
										print form::upload($this_field . '[]', $value, ' class="text long"');
										print "<a href=\"#\" class=\"add\" onClick=\"addFormField('$this_div','$this_field','$this_startid','$this_field_type'); return false;\">add</a>";
										if ($i != 0)
										{
											print "<a href=\"#\" class=\"rem\"  onClick='removeFormField(\"#".$this_field."_".$i."\"); return false;'>remove</a>";
										}
										print "</div>\n";
										$i++;
									}
								}
								print "<input type=\"hidden\" name=\"$this_startid\" value=\"$i\" id=\"$this_startid\">";
								?>
							</div>
						</div>
						<!-- f-col-bottom -->
						<div class="f-col-bottom-container">
							<div class="f-col-bottom">
								<div class="row">
									<h4><?php echo Kohana::lang('ui_main.personal_information');?></span></h4>
									<label>
										<span><?php echo Kohana::lang('ui_main.first_name');?></span>
										<?php print form::input('person_first', $form['person_first'], ' class="text"'); ?>
									</label>
									<label>
										<span><?php echo Kohana::lang('ui_main.last_name');?></span>
										<?php print form::input('person_last', $form['person_last'], ' class="text"'); ?>
									</label>
								</div>
								<div class="row">
									<label>
										<span><?php echo Kohana::lang('ui_main.email_address');?></span>
										<?php print form::input('person_email', $form['person_email'], ' class="text"'); ?>
									</label>
									<label>
										<span><?php echo Kohana::lang('wtm.phone');?></span>
										<?php print form::input('person_phone', $form['person_phone'], ' class="text" placeholder="'.Kohana::lang('wtm.phoneNum').'"'); ?>
									</label>
								</div>
								<div class="row">
									<label>
										<span><?php echo Kohana::lang('wtm.facebook');?></span>
										<?php print form::input('person_facebook', $form['person_facebook'], ' class="text"'); ?>
									</label>
									</div>
							</div>
							<!-- f-col-bottom-1 -->
							<div class="f-col-bottom-1">
								<h4><?php echo Kohana::lang('ui_main.information_evaluation');?></h4>
								<div class="row">
									<div class="f-col-bottom-1-col"><?php echo Kohana::lang('ui_main.approve_this_report');?>?</div>
									<?php if (Auth::instance()->has_permission('reports_approve')): ?>
									<input type="radio" name="incident_active" value="1"
									<?php if ($form['incident_active'] == 1)
									{
										echo " checked=\"checked\" ";
									}?>> <?php echo Kohana::lang('ui_main.yes');?>
									<input type="radio" name="incident_active" value="0"
									<?php if ($form['incident_active'] == 0)
									{
										echo " checked=\"checked\" ";
									}?>> <?php echo Kohana::lang('ui_main.no');?>
									<?php else: ?>
										<?php echo $form['incident_active'] ? Kohana::lang('ui_main.yes') : Kohana::lang('ui_main.no');?>
									<?php endif; ?>
								</div>
								<div class="row">
									<div class="f-col-bottom-1-col"><?php echo Kohana::lang('ui_main.verify_this_report');?>?</div>
									<?php if (Auth::instance()->has_permission('reports_verify')): ?>
									<input type="radio" name="incident_verified" value="1"
									<?php if ($form['incident_verified'] == 1)
									{
										echo " checked=\"checked\" ";
									}?>> <?php echo Kohana::lang('ui_main.yes');?>
									<input type="radio" name="incident_verified" value="0"
									<?php if ($form['incident_verified'] == 0)
									{
										echo " checked=\"checked\" ";
									}?>> <?php echo Kohana::lang('ui_main.no');?>									
									<?php else: ?>
										<?php echo $form['incident_verified'] ? Kohana::lang('ui_main.yes') : Kohana::lang('ui_main.no');?>
									<?php endif; ?>
								</div>
							</div>
							<div style="clear:both;"></div>
						</div>
						<div class="btns">
							<ul>
								<li><a href="#" class="btn_save"><?php echo utf8::strtoupper(Kohana::lang('ui_main.save_report'));?></a></li>
								<li><a href="#" class="btn_save_close"><?php echo utf8::strtoupper(Kohana::lang('ui_main.save_close'));?></a></li>
									<li><a href="#" class="btn_save_add_new"><?php echo utf8::strtoupper(Kohana::lang('ui_main.save_add_new'));?></a></li>
								<?php 
								if($id)
								{
									echo "<li><a href=\"#\" class=\"btn_delete btns_red\">".utf8::strtoupper(Kohana::lang('ui_main.delete_report'))."</a></li>";
								}
								?>
								<li><a href="<?php echo url::site().'admin/reports/';?>" class="btns_red"><?php echo utf8::strtoupper(Kohana::lang('ui_main.cancel'));?></a></li>
							</ul>
						</div>						
					</div>
				<?php print form::close(); ?>
				<?php
				if($id)
				{
					// Hidden Form to Perform the Delete function
					print form::open(url::site().'admin/reports/', array('id' => 'reportMain', 'name' => 'reportMain'));
					$array=array('action'=>'d','incident_id[]'=>$id);
					print form::hidden($array);
					print form::close();
				}
				?>
			</div>
