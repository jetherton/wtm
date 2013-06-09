<?php defined('SYSPATH') or die('No direct script access.');
/***********************************************************
* defaultcategories_js.php - Javascript for DefaultCategories Plugin
* This software is copy righted by WatchTheMed 2013
* Writen by Dylan Gillespie, Etherton Technologies <http://ethertontech.com>
* Started on 2013-07-06
* This plugin is to allow default categories on maps.
*************************************************************/


class Defaultcategories_Controller extends Controller{

	//function for using the Helper_map::geocode function
	function retrieveCategories(){
		$default_map_all = Kohana::config('settings.default_map_all');
		$categories = $this->makeCategories();
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
		
		$string = "<!-- category filters -->
		<h4><a href=\"#\" class=\"tooltip\" title=\"$hovertext\"> $category_filter</a></h4>
					</div>
		
					<ul id=\"category_switch\" class=\"category-filters\">
											
						<ul>
								<span $color_css>$all_cat_image</span>
								<span class=\"category-title\">$all_categories</span>
									<input type='checkbox' name=\"$all_categories\"/>
						</ul>";
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
		
								$string .= '<ul>'
								    . '<span '.$color_css.'>'.$category_image.'</span>'
								    . '<span class="category-title">'.$category_title.'</span>';
								$checkbox = "<input type='checkbox' id='".str_replace(' ', '_', $category_title)."' value='$category_title'";
								if($category_info[4] == 1){
									$checkbox .= ' checked';
								}
								$checkbox .= '/>';
								$string .= $checkbox;
		
								// Get Children
								$string .= '<div class="hide" id="child_'. $category .'">';
								if (sizeof($category_info[3]) != 0)
								{
									$string .= '<ul>';
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
		
										$string .= '<ul>'
										    . '<span '.$color_css.'>'.$child_image.'</span>'
										    . '<span class="category-title">'.$child_title.'</span>';
										$checkbox = "<input type='checkbox' id='$child_title' value='$child_title'";
										if($child_info[3] == 1){
											$checkbox .= ' checked';
										}
										$checkbox .= '</ul>';
										$string .= $checkbox;
									}
									$string .= '</ul>';
								}
								$string .= '</div></ul>';
							}
					$string .= "</ul>
					<!-- / category filters -->";
			
		echo json_encode($string);
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
		return $parent_categories;
	}
	
	
	public function changeDefault(){
		$changed = json_decode($_POST['cha']);
		//print_r($cha);
		//print_r($_GET);
		foreach($changed as $key=>$value){		
			$category = ORM::factory('category')->
			where('category_title', '=', $key)->
			find();
			
			//if($category->loaded()){
				$category->category_default = $value;
				$category->save();
			//}
		}
		//exit;
	}
	
	public function getCategories(){
		echo json_encode($this->makeCategories());
	}

} // End Defaultcategories

?>