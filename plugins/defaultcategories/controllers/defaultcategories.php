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
		$category_filter = Kohana::lang('ui_main.category_filter');
		$main_hide = Kohana::lang('ui_main.hide');
		$all_categories = Kohana::lang('ui_main.all_categories');
		
		// Get default icon
		$default_map_all_icon = '';
		if (Kohana::config('settings.default_map_all_icon_id'))
		{
			$icon_object = ORM::factory('media')->find(Kohana::config('settings.default_map_all_icon_id'));
			$default_map_all_icon = Kohana::config('upload.relative_directory')."/".$icon_object->media_medium;
		}
		
		$color_css = 'class=\"category-icon swatch\" style=\"background-color:#'.$default_map_all.'\"';
		$all_cat_image = '';
		if ($default_map_all_icon != NULL)
		{
			$all_cat_image = html::image(array(
					'src'=>$default_map_all_icon
			));
			$color_css = 'class=\"category-icon\"';
		}
		
		$string = "<!-- category filters -->
		<div class=\"cat-filters clearingfix\">
		<strong>
			$category_filter
							<span>
								[<a href=\"javascript:toggleLayer('category_switch_link', 'category_switch')\" id=\"category_switch_link\">
									$main_hide
								</a>]
							</span>
						</strong>
					</div>
		
					<ul id=\"category_switch\" class=\"category-filters\">
											
						<li>
							<a class=\"active\" id=\"cat_0\" href=\"#\">
								<span $color_css>$all_cat_image</span>
								<span class=\"category-title\">$all_categories</span>
							</a>
						</li>";
		foreach ($categories as $category => $category_info)
			{
				$category_title = html::escape($category_info[0]);
				$category_color = $category_info[1];
				$category_image = ($category_info[2] != NULL)
								? url::convert_uploaded_to_abs($category_info[2])
								: NULL;
				$category_description = html::escape(Category_Lang_Model::category_description($category));
		
				$color_css = 'class=\"category-icon swatch\" style=\"background-color:#'.$category_color.'\"';
				if ($category_info[2] != NULL)
					{
						$category_image = html::image(array(
								'src'=>$category_image,
								));
						$color_css = 'class=\"category-icon\"';
					}
		
								$string .= '<li>'
								    . '<a href=\"#\" id=\"cat_'. $category .'\" title=\"'.$category_description.'\">'
								    . '<span '.$color_css.'>'.$category_image.'</span>'
								    . '<span class=\"category-title\">'.$category_title.'</span>'
								    . '</a>';
		
								// Get Children
								$string .= '<div class=\"hide\" id=\"child_'. $category .'\">';
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
										
										$color_css = 'class=\"category-icon swatch\" style=\"background-color:#'.$child_color.'\"';
										if ($child_info[2] != NULL)
										{
											$child_image = html::image(array(
												'src' => $child_image
											));
		
											$color_css = 'class=\"category-icon\"';
										}
		
										$string .= '<li>'
										    . '<a href=\"#\" id=\"cat_'. $child .'\" title=\"'.$child_description.'\">'
										    . '<span '.$color_css.'>'.$child_image.'</span>'
										    . '<span class=\"category-title\">'.$child_title.'</span>'
										    . '</a>'
										    . '</li>';
									}
									$string .= '</ul>';
								}
								$string .= '</div></li>';
							}
					$string .= "</ul>
					<!-- / category filters -->";
			
		echo json_encode($string);
	}
	
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
							$ca_img
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
					$children
			);
		}
		return $parent_categories;
	}

} // End Defaultcategories


?>