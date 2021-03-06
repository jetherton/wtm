<div id="content">
	<div class="content-bg">

		<?php if ($site_submit_report_message != ''): ?>
			<div class="green-box">
				<h3><?php echo $site_submit_report_message; ?></h3>
			</div>
		<?php endif; ?>

		<!-- start report form block -->
		<?php print form::open(NULL, array('enctype' => 'multipart/form-data', 'id' => 'reportForm', 'name' => 'reportForm', 'class' => 'gen_forms')); ?>
		<input type="hidden" name="latitude" id="latitude" value="<?php echo $form['latitude']; ?>">
		<input type="hidden" name="longitude" id="longitude" value="<?php echo $form['longitude']; ?>">
		<input type="hidden" name="country_name" id="country_name" value="<?php echo $form['country_name']; ?>" />
		<input type="hidden" name="incident_zoom" id="incident_zoom" value="<?php echo $form['incident_zoom']; ?>" />
		<div class="big-block">
			
			<?php if ($form_error): ?>
			<!-- red-box -->
			<div class="red-box">
				<h3>Error!</h3>
				<ul>
					<?php
						foreach ($errors as $error_item => $error_description)
						{
							print (!$error_description) ? '' : "<li>" . $error_description . "</li>";
						}
					?>
				</ul>
			</div>
			<?php endif; ?>
			<div class="row">
				<input type="hidden" name="form_id" id="form_id" value="<?php echo $id?>">
			</div>
			<div class="report_left">
			    <div id="submit_real_left">
			    
				<div class="report_row">
					<?php if(count($forms) > 1): ?>
					<div class="row">
						<h4><span><?php echo Kohana::lang('ui_main.select_form_type');?></span>
						<span class="sel-holder">
							<?php print form::dropdown('form_id', $forms, $form['form_id'],
						' onchange="formSwitch(this.options[this.selectedIndex].value, \''.$id.'\')"') ?>
						</span>
						<div id="form_loader"></div>
						</h4>
					</div>
					<?php endif; ?>
				    <h1>Submit a Report</h1>
					<h4><?php echo Kohana::lang('ui_main.reports_title'); ?> <span class="required">*</span> </h4>
					<?php print form::input('incident_title', $form['incident_title'], ' class="text long"'); ?>
				</div>
				<div class="report_row">
					<h4><?php echo Kohana::lang('ui_main.reports_description'); ?> <span class="required">*</span> </h4>
					<span class="allowed-html"><?php echo html::allowed_html(); ?></span>
					<?php print form::textarea('incident_description', $form['incident_description'], ' rows="10" class="textarea long" style="margin-top:10px;"') ?>
				</div>
				<div class="report_row" id="datetime_default">
					<h4>
						<a href="#" id="date_toggle" class="show-more"><?php echo Kohana::lang('ui_main.modify_date'); ?></a>
						<?php echo Kohana::lang('ui_main.date_time'); ?>: 
						<?php 
						    echo Kohana::lang('ui_main.today_at')." "."<span id='current_time'>".$form['incident_hour']
							.":".$form['incident_minute']." ".$form['incident_ampm']."(".$form['incident_timeZone'].")</span>"; 
						?>
						<?php if($site_timezone): ?>
							<small>(<?php echo $site_timezone; ?>)</small>
						<?php endif; ?>
					</h4>
				</div>
				<div class="report_row hide" id="datetime_edit">
					<div class="date-box">
						<h4><?php echo Kohana::lang('ui_main.reports_date'); ?></h4>
						<?php print form::input('incident_date', $form['incident_date'], ' class="text short"'); ?>
						<script type="text/javascript">
							$().ready(function() {
								$("#incident_date").datepicker({ 
									showOn: "both", 
									buttonImage: "<?php echo url::file_loc('img'); ?>media/img/icon-calendar.gif", 
									buttonImageOnly: true 
								});
							});
						</script>
					</div>
					<div class="time">
						<h4><?php echo Kohana::lang('ui_main.reports_time'); ?></h4>
						<?php
							for ($i=1; $i <= 12 ; $i++)
							{
								// Add Leading Zero
								$hour_array[sprintf("%02d", $i)] = sprintf("%02d", $i);
							}
							for ($j=0; $j <= 59 ; $j++)
							{
								// Add Leading Zero
								$minute_array[sprintf("%02d", $j)] = sprintf("%02d", $j);
							}
							$ampm_array = array('pm'=>'pm','am'=>'am');
							print form::dropdown('incident_hour',$hour_array,$form['incident_hour']);
							print '<span class="dots">:</span>';
							print form::dropdown('incident_minute',$minute_array,$form['incident_minute']);
							print '<span class="dots">:</span>';
							print form::dropdown('incident_ampm',$ampm_array,$form['incident_ampm']);
						?>
						<?php if ($site_timezone != NULL): ?>
							<small>(<?php echo $site_timezone; ?>)</small>
						<?php endif; ?>
					</div>
					<div style="clear:both; display:block;" id="incident_date_time"></div>
				</div>
				<div class="report_row">
					<!-- Adding event for endtime plugin to hook into -->
				<?php Event::run('ushahidi_action.report_form_frontend_after_time'); ?>
				</div>
				<div class="report_row">
					<h4><?php echo Kohana::lang('ui_main.reports_categories'); ?> <span class="required">*</span></h4>
					<div class="report_category" id="categories">
					<?php
						$selected_categories = (!empty($form['incident_category']) AND is_array($form['incident_category']))
							? $selected_categories = $form['incident_category']
							: array();
							
						
						echo category::form_tree('incident_category', $selected_categories, 2);
						?>
					</div>
				</div>


			    <?php
			    // Action::report_form - Runs right after the report categories
			    Event::run('ushahidi_action.report_form');
			    ?>

				<?php echo $custom_forms ?>

				<div class="report_optional">
					<h3><?php echo Kohana::lang('ui_main.reports_optional'); ?></h3>
					<div class="report_row">
						<h4><?php echo Kohana::lang('ui_main.reports_first'); ?></h4>
						<?php print form::input('person_first', $form['person_first'], ' class="text long"'); ?>
					</div>
					<div class="report_row">
						<h4><?php echo Kohana::lang('ui_main.reports_last'); ?></h4>
						<?php print form::input('person_last', $form['person_last'], ' class="text long"'); ?>
					</div>
					<div class="report_row">
						<h4><?php echo Kohana::lang('ui_main.reports_email'); ?></h4>
						<?php print form::input('person_email', $form['person_email'], ' class="text long"'); ?>
					</div>
					<div class="report_row">
						<h4><?php echo Kohana::lang('wtm.phone'); ?></h4>
						<?php print form::input('person_phone', $form['person_phone'], ' class="text long" placeholder="'.Kohana::lang('wtm.phoneNum').'"'); ?>
					</div>
					<div class="report_row">
						<h4><?php echo Kohana::lang('wtm.facebook'); ?></h4>
						<?php print form::input('person_facebook', $form['person_facebook'], ' class="text long"'); ?>
					</div>
					<?php
					// Action::report_form_optional - Runs in the optional information of the report form
					Event::run('ushahidi_action.report_form_optional');
					?>
				</div>
			    </div>
			</div>
			<div class="report_right">
			    
				<?php if (count($cities) > 1): ?>
				<div class="report_row">
					<h4><?php echo Kohana::lang('ui_main.reports_find_location'); ?></h4>
					<?php print form::dropdown('select_city',$cities,'', ' class="select" '); ?>
				</div>
				<?php endif; ?>
				<div class="report_row" style="width:960px;">
					<div id="divMap" class="report_map" style="width:960px;">
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
								<div style="display:none;">
									<span id="moveFront" title="<?php echo Kohana::lang('wtm.moveFront');?>">
										<label> <?php echo Kohana::lang('wtm.front')?></label>
									</span>
									<span id="moveBack" title="<?php echo Kohana::lang('wtm.moveBack');?>">
										<label><?php echo Kohana::lang('wtm.back')?></label>
									</span>
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
										<label>Fill <?php echo Kohana::lang('ui_main.geometry_color');?>:</label> 
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
					    <div id="helpText" style="display:none;">
						<br/>
						Help
						<br/>
						<br/>
						Indicate as precisely as possible the chain of events of the ongoing or past incident, using the different sections and tools in this page.
						<br/>
						<br/>
						<ul>
						    <li>Describe the events in detail in the “description” section.</li>
						    <li>Fill in the different relevant fields concerning sources.</li>
						    <li>Select the date and time corresponding to the time of the incident</li>
						    <li>Upload additional media such as audio or image files if they exist</li>
						    <li>Select one or several categories the report should belong to</li>
						    <li>Choose which layers are relevant to the incident and you would like WTM users to see when they will view your report.</li>
						    <li>Use the different drawing tools on the map to draw the chain of events from the moment of the boat’s departure to the end of its journey (or its current position). Indicate with points the different key locations in the chain of events (the successive positions of the migrants’ boat as well as that of other actors involved). If only one point in the chain of events is known, indicate that one. By editing the points, you may label them and add information as to what happened at each point. Indicate with a full line trajectories that are known (by linking up known points), with a dashed line trajectories that are estimated. You may view a few examples such as the <strong><a href="<?php echo url::base();?>reports/view/16">"Left-to-die boat" case</a></strong>.</li>
						</ul>
						<br/>
						<br/>
						View the "<strong><a href="<?php echo url::base();?>page/index/4" >How to report</a></strong>" page for additional instructions or <strong><a href="mailto:chazheller@yahoo.com">write to us</a></strong> if you have any further questions.
					    <div id="helpTextClose">X</div>
					    </div>
					    
					</div>
					<div class="report-find-location" style="width:960px;">
						<div id="pointPanel" class="olControlEditingToolbar" style="margin-right:0px;">
						    <div id="pointCoords" title="Add a Point With Coordinates"class="olControlDrawFeatureCoordPointItemInactive olButton"></div>
						</div>						
						<div id="panel" class="olControlEditingToolbar"></div>						
						<div class="btns" id="secondPanel">
							<a title="Delete last item added." href="#" class="btn_del_last">&nbsp;</a>
							<a title="Re-add last deleted item." href="#" class="btn_undel_last">&nbsp;</a>
							<a title="Delete selected item." href="#" class="btn_del_sel">&nbsp;</a>
							<a title="Help" href="#" id="helpButton" class="btn_help">HELP</a>
						</div>
						<div id="followAspectRatioDiv">
						    Keep Aspect Ratio 
						    <input type="checkbox" checked="checked" name="keepAspectRatio" id="keepAspectRatio" value="yes" onchange="setKeepAspectRatio();"/>
						</div>
						<div style="clear:both;"></div>
						<!--
						    <?php print form::input('location_find', '', ' title="'.Kohana::lang('ui_main.location_example').'" class="findtext"'); ?>
						    <input type="button" name="button" id="button" value="<?php echo Kohana::lang('ui_main.find_location'); ?>" class="btn_find" />
						    <div id="find_loading" class="report-find-loading"></div>
						    <div style="clear:both;" id="find_text"><?php echo Kohana::lang('ui_main.pinpoint_location'); ?>.</div>
						-->
					</div>
				</div>
			    <div id="right_top_half">
				<?php Event::run('ushahidi_action.report_form_location', $id); ?>
				<div class="report_row">
					<h4>
						<?php echo Kohana::lang('ui_main.reports_location_name'); ?> 
						<span class="required">*</span><br />
						<span class="example"><?php //echo Kohana::lang('ui_main.detailed_location_example'); ?></span>
					</h4>
					<?php print form::input('location_name', $form['location_name'], ' class="text long"'); ?>
				</div>

				<!-- News Fields -->
				<div id="divNews" class="report_row">
					<h4><?php echo Kohana::lang('ui_main.reports_news'); ?></h4>
					
					<?php 
						// Initialize the counter
						$i = (empty($form['incident_news'])) ? 1 : 0;
					?>

					<?php if (empty($form['incident_news'])): ?>
						<div class="report_row">
							<?php print form::input('incident_news[]', '', ' class="text long2"'); ?>
							<a href="#" class="add" onClick="addFormField('divNews','incident_news','news_id','text'); return false;">add</a>
						</div>
					<?php else: ?>
						<?php foreach ($form['incident_news'] as $value): ?>
						<div class="report_row" id="<?php echo $i; ?>">
							<?php echo form::input('incident_news[]', $value, ' class="text long2"'); ?>
							<a href="#" class="add" onClick="addFormField('divNews','incident_news','news_id','text'); return false;">add</a>

							<?php if ($i != 0): ?>
								<?php $css_id = "#incident_news_".$i; ?>
								<a href="#" class="rem"	onClick="removeFormField('<?php echo $css_id; ?>'); return false;">remove</a>
							<?php endif; ?>

						</div>
						<?php $i++; ?>

						<?php endforeach; ?>
					<?php endif; ?>

					<?php print form::input(array('name'=>'news_id', 'type'=>'hidden', 'id'=>'news_id'), $i); ?>
				</div>


				<!-- Video Fields -->
				<div id="divVideo" class="report_row">
					<h4><?php print Kohana::lang('ui_main.external_video_link'); ?></h4>
					<?php 
						// Initialize the counter
						$i = (empty($form['incident_video'])) ? 1 : 0;
					?>

					<?php if (empty($form['incident_video'])): ?>
						<div class="report_row">
							<?php print form::input('incident_video[]', '', ' class="text long2"'); ?>
							<a href="#" class="add" onClick="addFormField('divVideo','incident_video','video_id','text'); return false;">add</a>
						</div>
					<?php else: ?>
						<?php foreach ($form['incident_video'] as $value): ?>
							<div class="report_row" id="<?php  echo $i; ?>">

							<?php print form::input('incident_video[]', $value, ' class="text long2"'); ?>
							<a href="#" class="add" onClick="addFormField('divVideo','incident_video','video_id','text'); return false;">add</a>

							<?php if ($i != 0): ?>
								<?php $css_id = "#incident_video_".$i; ?>
								<a href="#" class="rem"	onClick="removeFormField('<?php echo $css_id; ?>'); return false;">remove</a>
							<?php endif; ?>

							</div>
							<?php $i++; ?>
						
						<?php endforeach; ?>
					<?php endif; ?>

					<?php print form::input(array('name'=>'video_id','type'=>'hidden','id'=>'video_id'), $i); ?>
				</div>
				
				<?php Event::run('ushahidi_action.report_form_after_video_link'); ?>

				<!-- Photo Fields -->
				<div id="divPhoto" class="report_row">
					<h4><?php echo Kohana::lang('ui_main.reports_photos'); ?></h4>
					<?php 
						// Initialize the counter
						$i = (empty($form['incident_photo']['name'][0])) ? 1 : 0;
					?>

					<?php if (empty($form['incident_photo']['name'][0])): ?>
					<div class="report_row">
						<?php print form::upload('incident_photo[]', '', ' class="file long2"'); ?>
						<a href="#" class="add" onClick="addFormField('divPhoto', 'incident_photo','photo_id','file'); return false;">add</a>
					</div>
					<?php else: ?>
						<?php foreach ($form['incident_photo']['name'] as $value): ?>

							<div class="report_row" id="<?php echo $i; ?>">
								<?php print form::upload('incident_photo[]', $value, ' class="file long2"'); ?>
								<a href="#" class="add" onClick="addFormField('divPhoto','incident_photo','photo_id','file'); return false;">add</a>

								<?php if ($i != 0): ?>
									<?php $css_id = "#incident_photo_".$i; ?>
									<a href="#" class="rem"	onClick="removeFormField('<?php echo $css_id; ?>'); return false;">remove</a>
								<?php endif; ?>

							</div>

							<?php $i++; ?>

						<?php endforeach; ?>
					<?php endif; ?>

					<?php print form::input(array('name'=>'photo_id','type'=>'hidden','id'=>'photo_id'), $i); ?>
				</div>
				<div style="clear:both;">&nbsp;<br/></div>
				<?php Event::run('ushahidi_action.file_upload'); ?>
				<div style="clear:both;">&nbsp;<br/></div>
			</div>		
				
				
				<div class="report_row">
					<input name="submit" type="submit" value="<?php echo Kohana::lang('ui_main.reports_btn_submit'); ?> Report" class="btn_submit" /> 
				</div>
			</div>
		</div>
		<?php print form::close(); ?>
		<!-- end report form block -->
	</div>
</div>
<div style="clear:both"></div>
