<?php defined('SYSPATH') or die('No direct script access.');
/**
 * @author     John Etherton <john@ethertontech.com>
 * @package    Enhanced Map, Ushahidi Plugin - https://github.com/jetherton/enhancedmap
 * @license	   GNU Lesser GPL (LGPL) Rights pursuant to Version 3, June 2007
 * @copyright  2012 Etherton Technologies Ltd. <http://ethertontech.com>
 * @Date	   2012-06-08
 * Purpose:	   View for the categories filter
 *             This file is adapted from the file Ushahidi_Web/themes/default/views/main.php
 *             Originally written by the Ushahidi Team
 * Inputs:     $categories_view_id - HTML element id of this whole view. Great for a $("#<category_view_id>") type function
 *             $categories - An array of categories that will be shown to the user
 * Outputs:    HTML
 *
 * The Enhanced Map, Ushahidi Plugin is free software: you can redistribute
 * it and/or modify it under the terms of the GNU Lesser General Public License
 * as published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * The Enhanced Map, Ushahidi Plugin is distributed in the hope that it will
 * be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with the Enhanced Map, Ushahidi Plugin.  If not, see
 * <http://www.gnu.org/licenses/>.
 *
 * Changelog:
 * 2012-06-08:  Etherton - Initial release
 *
 * Developed by Etherton Technologies Ltd.
 */
?>


<!-- category filters -->
<div id="report-category-filter">
			<ul id="<?php echo $categories_view_id;?>" class="category-filters cat_switch">
				
				<li><a class="active" id="cat_0" href="#"><div class="swatch" style="background-color:none;"></div><div class="category-title"><?php echo Kohana::lang('ui_main.all');?></div></a></li>
				<?php
					foreach ($categories as $category => $category_info)
					{
						$category_title = $category_info[0];
						$category_color = $category_info[1];
						$category_image = '';

						$color_css = 'class="swatch" style="background-color:#'.$category_color.'"';
						if($category_info[2] != NULL ) {
							$category_image = html::image(array(
								'src'=>$category_info[2],
								'style'=>'float:left;width:20px;height:20px;',
								'class'=>'cat_icon'
								));
							$color_css = '';
						}
						//check if this category has kids
						if(count($category_info[3]) > 0)
						{
							echo '<li>';
							echo '<a style="display:block; float:right; text-align:center; width:15px; padding:6px 0px 7px 0px; position:relative;" href="#" id="drop_cat_'.$category.'">+</a>';
							echo '<a  style="width:205px;" href="#" id="cat_'. $category .'">';
							echo '<div class="color_swatch" style="background: #'.$category_color.'"></div>';
							echo '<div '.$color_css.'>'.$category_image.'</div><div class="category-title">'.$category_title.'</div></a>';
							
							
						}
						else
						{
							echo '<li><a href="#" id="cat_'. $category .'">';
							echo '<div class="color_swatch" style="background: #'.$category_color.'"></div>';
							echo '<div '.$color_css.'>'.$category_image.'</div><div class="category-title">'.$category_title.'</div></a>';
						}
						// Get Children
						echo '<div class="hide" id="child_'. $category .'"><ul>';
						foreach ($category_info[3] as $child => $child_info)
						{
							$child_title = $child_info[0];
							$child_color = $child_info[1];
							$child_image = '';
							$color_css = 'class="swatch" style="background-color:#'.$child_color.'"';
							if($child_info[2] != NULL && file_exists(Kohana::config('upload.relative_directory').'/'.$child_info[2])) {
								$child_image = html::image(array(
									'src'=>Kohana::config('upload.relative_directory').'/'.$child_info[2],
									'style'=>'float:left;padding-right:5px;',
									'class'=>'cat_icon'
									));
								$color_css = '';
							}
							echo '<li ><a href="#" id="cat_'. $child .'" cat_parent="'.$category.'">';
							echo '<div class="color_swatch" style="background: #'.$child_color.'"></div>';
							echo '<div '.$color_css.'>'.$child_image.'</div><div class="category-title">'.$child_title.'</div></a></li>';
						}
						echo '</ul></div></li>';
					}
				?>
			</ul>
</div>
			<!-- / category filters -->
