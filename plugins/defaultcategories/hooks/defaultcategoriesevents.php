<?php defined('SYSPATH') or die('No direct script access.');
/***********************************************************
* defaultcategoriesevents.php - Event handler for DefaultCategories Plugin
* This software is copy righted by WatchTheMed 2013
* Writen by Dylan Gillespie, Etherton Technologies <http://ethertontech.com>
* Started on 2013-07-06
* This plugin is to allow default categories for maps.
*************************************************************/


class defaultcategoriesevents {

	public function __construct()
	{
		Event::add('system.pre_controller', array($this, 'add'));
	}

	public function add()
	{
		$url = url::current();

		//Event::run('ushahidi_action.map_settings_modified');
		//Only add the plugin to pages with a map
		//The last blank case happens when the webpage first loads, so the main page
		if($url == 'main' OR 
				//$url == 'reports' OR 
				//$url == 'reports/submit' OR 
				//$url == 'alerts' OR 
				$url == ''){
			Event::add('ushahidi_action.header_scripts', array($this, 'render_javascript'));
		}
		//elseif(strpos($url, 'admin/reports/edit') !== false){
		//	Event::add('ushahidi_action.header_scripts_admin', array($this, 'render_javascript'));
		//}	
		elseif(strpos($url, 'admin/settings') !== false){
			Event::add('ushahidi_action.header_scripts_admin', array($this, 'render_admin_viewjs'));
		}
		
		Event::add('ushahidi_action.map_settings_modified', array($this, 'parseSubmit'));
	}
	
	public function render_javascript(){
		$view = new View('defaultcategories/defaultcategories_js');
		$view->categories = json_encode($this->makeCategories());
		echo $view;
	}
	
	public function render_admin_viewjs(){
		$view = new View('defaultcategories/defaultadminsettings_js');
		$view->categoryDiv = $this->retrieveCategories();
		$view->categories = $this->makeCategories();
		echo $view;
	}
	
	public function parseSubmit(){
		$view = new View('defaultcategories/defaultsubmit');
		$view->categories = json_decode($this->makeCategories());
		$view->post = $_POST;
		echo $view;
	}
	
	
	//function for using the Helper_map::geocode function
	private function retrieveCategories(){
			$default_map_all = Kohana::config('settings.default_map_all');
			$categories = json_decode($this->makeCategories());
			$category_filter = Kohana::lang('defaultcategories.categories');
			$main_hide = Kohana::lang('ui_main.hide');
			$all_categories = Kohana::lang('ui_main.all_categories');
			$hovertext = Kohana::lang('defaultcategories.hover');
		
			// Get default icon
			$default_map_all_icon = '';
			if (Kohana::config('settings.default_map_all_icon_id'))
			{
				$icon_object = ORM::factory('media')->find(Kohana::config('settings.default_map_all_icon_id'));
				$default_map_all_icon = Kohana::config('upload.relative_directory')."/".$icon_object->media_medium;
			}
		
			$color_css = 'class="category-icon swatch" style="background-color:#'.$default_map_all.'"';
			$all_cat_image = '';
			if ($default_map_all_icon != NULL)
			{
				$all_cat_image = html::image(array(
						'src'=>$default_map_all_icon
				));
				$color_css = 'class="category-icon"';
			}
		
			$all = str_replace(' ', '_', $all_categories);
			$string = "<!-- category filters -->
			<h4><a href=\"#\" class=\"tooltip\" title=\"$hovertext\"> $category_filter</a></h4>
			</div>
		
			<table id=\"category_switch\" class=\"category_table\">
				
			<tr><td>
			<span $color_css>$all_cat_image</span></td>
			<td><span class=\"category-title\">$all_categories</span></td>
			<td><input type='checkbox' name=\"$all\" id=\"$all\"/>
			</td></tr>";
			foreach ($categories as $category => $category_info)
			{
			$category_title = html::escape($category_info[0]);
			$category_color = $category_info[1];
			$category_image = ($category_info[2] != NULL)
			? url::convert_uploaded_to_abs($category_info[2])
			: NULL;
			$category_description = html::escape(Category_Lang_Model::category_description($category));
		
			$color_css = 'class="category-icon swatch" style="background-color:#'.$category_color.'"';
					if ($category_info[2] != NULL)
			{
			$category_image = html::image(array(
				'src'=>$category_image,
			));
			$color_css = 'class="category-icon"';
			}
			$string .= '<tr><td>'
									    . '<span '.$color_css.'>'.$category_image.'</span></td>'
										    		. '<td><span class="category-title">'.$category_title.'</span></td>';
			if(sizeof($category_info[3]) == 0){
				$checkbox = "<td><input type='checkbox' name='".str_replace(' ', '_', $category_title)."' id='".str_replace(' ', '_', $category_title)."' value='$category_title'";
				if($category_info[4] == 1){
					$checkbox .= ' checked';
				}
				$checkbox .= '/></td></tr>';
				$string .= $checkbox;
			}
			else{
				$string .= '<td><a class="show" id="show_'.$category.'"> + </a></td></tr>';
			}
									

		$string .= '<div  id="child_'. $category .'">';
		if (sizeof($category_info[3]) != 0)
			{
				foreach ($category_info[3] as $child => $child_info)
				{
											$child_title = html::escape($child_info[0]);
												$child_color = $child_info[1];
					$child_image = ($child_info[2] != NULL)
					? url::convert_uploaded_to_abs($child_info[2])
					: NULL;
					$child_description = html::escape(Category_Lang_Model::category_description($child));
		
					$color_css = 'class="category-icon swatch" style="background-color:#'.$child_color.'"';
					if ($child_info[2] != NULL)
					{
					$child_image = html::image(array(
							'src' => $child_image
					));
		
					$color_css = 'class="category-icon"';
											}
											$child_name = str_replace(' ' , '_', $child_title);
												$string .= '<tr style="display:none" class="child_'.$category.'"><td></td>'
												. '<td><span '.$color_css.'>'.$child_image.'</span>       '
												. '        <span class="category-title">'.$child_title.'</span></td>';
												$checkbox = "<td><input type='checkbox' name='$child_name' id='$child_name' value='$child_name'";
											if($child_info[3] == 1){
												$checkbox .= ' checked';
					}
														$checkbox .= '/></td></tr>';
														$string .= $checkbox;
					}
					}
					$string .= '</div></div>';
		}
		$string .= "</table>
		<!-- / category filters -->";
			
		return json_encode($string);
	}
	
	//Helper function to find parent and child categories
	private function makeCategories(){
	// Get locale
			$l = Kohana::config('locale.language.0');
			// Get all active top level categories
			$parent_categories = array();
		$all_parents = ORM::factory('category')
			->where('category_visible', '1')
			->where('parent_id', '0')
			->find_all();
	
											foreach ($all_parents as $category)
											{
												// Get The Children
			$children = array();
											foreach ($category->children as $child)
											{
											$child_visible = $child->category_visible;
											if ($child_visible)
											{
					// Check for localization of child category
						$display_title = Category_Lang_Model::category_title($child->id,$l);
	
						$ca_img = ($child->category_image != NULL)
						? url::convert_uploaded_to_abs($child->category_image)
						: NULL;
	
						$children[$child->id] = array(
							$display_title,
							$child->category_color,
							$ca_img,
							$child->category_default
		);
						}
						}
	
						// Check for localization of parent category
						$display_title = Category_Lang_Model::category_title($category->id,$l);
	
						// Put it all together
						$ca_img = ($category->category_image != NULL)
						? url::convert_uploaded_to_abs($category->category_image)
						: NULL;
	
						$parent_categories[$category->id] = array(
						$display_title,
						$category->category_color,
						$ca_img,
						$children,
						$category->category_default
						);
											}
		return json_encode($parent_categories, true);
											}
	
}
new defaultcategoriesevents;