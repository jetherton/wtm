<?php defined('SYSPATH') or die('No direct script access.');
/***********************************************************
* defaultsubmit.php - Submit handler for DefaultCategories Plugin
* This software is copy righted by WatchTheMed 2013
* Writen by Dylan Gillespie, Etherton Technologies <http://ethertontech.com>
* Started on 2013-13-06
* This plugin is to allow default categories for maps.
*************************************************************/


if($post){
	foreach($categories as $key=>$category){
		
		$cat = str_replace(' ', '_', $category[0]);

		if(isset($post[$cat])){
			$category = ORM::factory('category', $key);
				//$val = $category->category_default;
				$category->category_default = !$category->category_default;
				$category->save();
		}
	}
}