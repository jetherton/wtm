<?php

class ParseFiles_Controller extends Controller{
	public function submitFiles(){
		$fp = fopen('file.txt', 'w');
		fwrite($fp, print_r($_POST, TRUE));
		fclose($fp);
		echo '{"success" : true}';
	}
	
	public function parseWindow(){
		$view = new View('uploadlayers/uploadwindow');
		$js = new View('uploadlayers/uploadwindow_js');
		$view->js = $js;

		// Setup and initialize form field names
		$form = array(
				'action' => '',
				'layer_id' => '',
				'layer_name' => '',
				'layer_url'	=> '',
				'layer_file' => '',
				'layer_color' => ''
		);
		
		// Copy the form as errors, so the errors will be stored with keys corresponding to the form field names
		$errors = $form;
		$form_error = FALSE;
		$form_saved = FALSE;
		$form_action = "";
		$parents_array = array();
		
		// Check, has the form been submitted, if so, setup validation
		if ($_POST)
		{
			// Fetch the submitted data
			$post_data = array_merge($_POST, $_FILES);
				
			// Layer instance for the actions
			$layer = (isset($post_data['layer_id']) AND Layer_Model::is_valid_layer($post_data['layer_id']))
			? new Layer_Model($post_data['layer_id'])
			: new Layer_Model();
		
			// Check for action
			if ($post_data['action'] == 'a')
			{
				// Manually extract the primary layer data
				$layer_data = arr::extract($post_data, 'layer_name', 'layer_color', 'layer_url', 'layer_file_old');
		
				// Grab the layer file to be uploaded
				$layer_data['layer_file'] = isset($post_data['layer_file']['name'])? $post_data['layer_file']['name'] : NULL;
		
				// Extract the layer file for upload validation
				$other_data = arr::extract($post_data, 'layer_file');
		
				// Set up validation for the layer file
				$post = Validation::factory($other_data)
				->pre_filter('trim', TRUE)
				->add_rules('layer_file', 'upload::valid','upload::type[kml,kmz]');
		
				// Test to see if validation has passed
				if ($layer->validate($layer_data) AND $post->validate(FALSE))
				{
					// Success! SAVE
					$layer->save();
						
					$path_info = upload::save("layer_file");
					if ($path_info)
					{
						$path_parts = pathinfo($path_info);
						$file_name = $path_parts['filename'];
						$file_ext = $path_parts['extension'];
						$layer_file = $file_name.".".$file_ext;
						$layer_url = '';
		
						if (strtolower($file_ext) == "kmz")
						{
							// This is a KMZ Zip Archive, so extract
							$archive = new Pclzip($path_info);
							if (TRUE == ($archive_files = $archive->extract(PCLZIP_OPT_EXTRACT_AS_STRING)))
							{
								foreach ($archive_files as $file)
								{
									$ext_file_name = $file['filename'];
									$archive_file_parts = pathinfo($ext_file_name);
									//because there can be more than one file in a KMZ
									if
									(
									$archive_file_parts['extension'] == 'kml' AND
									$ext_file_name AND
									$archive->extract(PCLZIP_OPT_PATH, Kohana::config('upload.directory')) == TRUE
									)
									{
										// Okay, so we have an extracted KML - Rename it and delete KMZ file
										rename($path_parts['dirname']."/".$ext_file_name,
										$path_parts['dirname']."/".$file_name.".kml");
											
										$file_ext = "kml";
										unlink($path_info);
										$layer_file = $file_name.".".$file_ext;
									}
										
								}
							}
		
								
						}
		
		
						// Upload the KML to the CDN server if configured
						if (Kohana::config("cdn.cdn_store_dynamic_content"))
						{
							// Upload the file to the CDN
							$layer_url = cdn::upload($layer_file);
		
							// We no longer need the files we created on the server. Remove them.
							$local_directory = rtrim(Kohana::config('upload.directory', TRUE), '/').'/';
							unlink($local_directory.$layer_file);
		
							// We no longer need to store the file name for the local file since it's gone
							$layer_file = '';
						}
		
						// Set the final variables for the DB
						$layer->layer_url = $layer_url;
						$layer->layer_file = $layer_file;
						$layer->save();
					}
						
					$form_saved = TRUE;
					array_fill_keys($form, '');
					$form_action = utf8::strtoupper(Kohana::lang('ui_admin.added_edited'));
				}
				else
				{
					// Validation failed
		
					// Repopulate the form fields
					$form = arr::overwrite($form, array_merge($layer_data->as_array(), $post->as_array()));
		
					// Ropulate the error fields, if any
					$errors = arr::overwrite($errors, array_merge($layer_data->errors('layer'), $post->errors('layer')));
					$form_error = TRUE;
				}
		
			}
			elseif ($post_data['action'] == 'd')
			{
				// Delete action
				if ($layer->loaded)
				{
					// Delete KMZ file if any
					$layer_file = $layer->layer_file;
					if ( ! empty($layer_file) AND file_exists(Kohana::config('upload.directory', TRUE).$layer_file))
					{
						unlink(Kohana::config('upload.directory', TRUE) . $layer_file);
					}
		
					$layer->delete();
					$form_saved = TRUE;
					$form_action = utf8::strtoupper(Kohana::lang('ui_admin.deleted'));
				}
			}
			elseif ($post_data['action'] == 'v')
			{
				// Show/Hide Action
				if ($layer->loaded == TRUE)
				{
					$layer->layer_visible =  ($layer->layer_visible == 1)? 0 : 1;
					$layer->save();
						
					$form_saved = TRUE;
					$form_action = utf8::strtoupper(Kohana::lang('ui_admin.modified'));
				}
			}
			elseif ($post_data['action'] == 'i')
			{
				// Delete KML/KMZ action
				if ($layer->loaded == TRUE)
				{
					$layer_file = $layer->layer_file;
					if ( ! empty($layer_file) AND file_exists(Kohana::config('upload.directory', TRUE).$layer_file))
					{
						unlink(Kohana::config('upload.directory', TRUE) . $layer_file);
					}
		
					$layer->layer_file = null;
					$layer->save();
						
					$form_saved = TRUE;
					$form_action = utf8::strtoupper(Kohana::lang('ui_admin.modified'));
				}
			}
		}
		
		// Pagination
		$pagination = new Pagination(array(
				'query_string' => 'page',
				'items_per_page' => 0,
				'total_items' => ORM::factory('layer')->count_all()
		));
		
		$layers = ORM::factory('layer')
		->orderby('layer_name', 'asc')
		->find_all(0, $pagination->sql_offset);
		
		$view->errors = $errors;
		$view->form_error = $form_error;
		$view->form_saved = $form_saved;
		$view->form_action = $form_action;
		$view->pagination = $pagination;
		$view->total_items = $pagination->total_items;
		$view->layers = $layers;
		
		// Javascript Header
		$view->colorpicker_enabled = TRUE;
		$view->js = new View('admin/manage/layers/layers_js');
		echo $view;
	}
}
?>