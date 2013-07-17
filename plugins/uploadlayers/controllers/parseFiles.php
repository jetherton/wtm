<?php defined('SYSPATH') or die('No direct script access.');
/***********************************************************
* parseFiles_Controller.php - Handles post and opening of popup
* This software is copy righted by WatchTheMed 2013
* Writen by Dylan Gillespie, Etherton Technologies <http://ethertontech.com>
* Started on 2013-20-06
*************************************************************/


class ParseFiles_Controller extends Controller{
	
	//handle incoming report layers
	public function submitFiles(){

			// Fetch the submitted data
			$post_data = array_merge($_POST, $_FILES);
			$layer_date = date('Y-m-d');
		
		
			// Layer instance for the actions
			$layer = (isset($post_data['layer_id']) AND Layer_Model::is_valid_layer($post_data['layer_id']))
			? new Layer_Model($post_data['layer_id'])
			: new Layer_Model();
		
				// Manually extract the primary layer data
				$layer_data = arr::extract($post_data, 'layer_name', 'layer_color', 'layer_url');
		
				// Grab the layer file to be uploaded
				$layer_data['qqfile'] = isset($post_data['qqfile']['name'])? $post_data['qqfile']['name'] : NULL;
		
				// Extract the layer file for upload validation
				$other_data = arr::extract($post_data, 'qqfile');
		
				// Set up validation for the layer file
				$post = Validation::factory($other_data)
				->pre_filter('trim', TRUE)
				->add_rules('qqfile', 'upload::valid','upload::type[kml,kmz]');
		
					// Success! SAVE
					$layer->save();
		
					$path_info = upload::save("qqfile");
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
						$layer->layer_name = isset($post_data['layer_name']) ? $post_data['layer_name'] : null;
						$layer->layer_color = isset($post_data['layer_color']) ? $post_data['layer_color'] : null;
						$layer->layer_url = isset($post_data['layer_url']) ? $post_data['layer_url'] : null;
						$layer->date_uploaded = $layer_date;
						$layer->meta_data = isset($post_data['meta_data']) ? $post_data['meta_data'] : null;
						
						$layer->save();
						
						//get rid of old uploads before we set this one into the database
						upload_helper::check_old_uploads();
						
						$reportlayers = ORM::factory('reportslayers');
						$reportlayers->layer_id = $layer->id;
						$reportlayers->report_id = 0;
						$reportlayers->save();
						
						echo '{"success": "true", "newUuid" : "'.$layer->id.'"}';
					}
		
		
	}
	
	//submit the icon seperatly as it's a different upload bar
	public function submitIcon(){
		// Upload Image/Icon
			$filename = $_FILES['qqfile']['tmp_name'];
			$layer = ORM::factory('layer')->
			where('id', $_POST['layer_id'])->
			find();
			
			$new_filename = "layer_".$layer->id."_".time();
		
			// Name the files for the DB
			$layer_img_file = $new_filename.".png";
			$layer_img_thumb_file = $new_filename."_16x16.png";
		
			// Resize Image to 32px if greater
			Image::factory($filename)->resize(32,32,Image::HEIGHT)
			->save(Kohana::config('upload.directory', TRUE) . $layer_img_file);
			// Create a 16x16 version too
			Image::factory($filename)->resize(16,16,Image::HEIGHT)
			->save(Kohana::config('upload.directory', TRUE) . $layer_img_thumb_file);
		
			// Okay, now we have these three different files on the server, now check to see
			//   if we should be dropping them on the CDN
		
			if (Kohana::config("cdn.cdn_store_dynamic_content"))
			{
				$layer_img_file = cdn::upload($layer_img_file);
				$layer_img_thumb_file = cdn::upload($layer_img_thumb_file);
					
				// We no longer need the files we created on the server. Remove them.
				$local_directory = rtrim(Kohana::config('upload.directory', TRUE), '/').'/';
				unlink($local_directory.$new_filename.".png");
				unlink($local_directory.$new_filename."_16x16.png");
			}
		
			// Remove the temporary file
			unlink($filename);
		
			// Delete Old Image
			$layer_old_image = $layer->icon;
			if ( ! empty($layer_old_image))
			{
				if (file_exists(Kohana::config('upload.directory', TRUE).$layer_old_image))
				{
					unlink(Kohana::config('upload.directory', TRUE).$layer_old_image);
				}
				elseif (Kohana::config("cdn.cdn_store_dynamic_content") AND valid::url($layer_old_image))
				{
					cdn::delete($layer_old_image);
				}
			}
		
			// Save
			$layer->icon = $layer_img_file;
			$layer->layer_icon_thumb = $layer_img_thumb_file;
			$layer->save();
			
			echo '{"success": "true"}';
	}
	
	//create the pop up window
	public function parseWindow(){
		$view = new View('uploadlayers/uploadwindow');
		$js = new View('uploadlayers/uploadwindow_js');
		$view->js = $js;
	
		// Javascript Header
		$view->colorpicker_enabled = TRUE;

		echo $view;
	}
	
	//get the details of the new layer to add to the report
	public function getLayerDetails(){
		$layer = ORM::factory('layer')->
		where('id', $_POST['layer'])->
		find();
		
		echo '{';
		echo  "\"label\": \"$layer->layer_name\",";
		echo "\"color\" : \"$layer->layer_color\"";
		if($layer->icon != null){
			echo ",\"icon\" : \"$layer->layer_icon_thumb\"";
		}
		echo '}';
	}
}
?>