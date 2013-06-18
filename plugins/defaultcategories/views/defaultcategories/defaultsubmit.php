<?php defined('SYSPATH') or die('No direct script access.');
/***********************************************************
* defaultsubmit.php - Submit handler for DefaultCategories Plugin
* This software is copy righted by WatchTheMed 2013
* Writen by Dylan Gillespie, Etherton Technologies <http://ethertontech.com>
* Started on 2013-13-06
* This plugin is to allow default categories for maps.
*************************************************************/


if($post){
	foreach($categories as $key=>$parent){
		
		$cat = str_replace(' ', '_', $parent[0]);
		$category = ORM::factory('category', $key);

		if(isset($post[$cat])){
				$category->category_default = 1;
				$category->save();
		}
		else{
			$category->category_default = 0;
			$category->save();
		}
		
		foreach($parent[3] as $val=>$child){
			$cat = str_replace(' ', '_', $child[0]);
			$category = ORM::factory('category', $val);
			
			if(isset($post[$cat])){
				$category->category_default = 1;
				$category->save();
			}
			else{
				$category->category_default = 0;
				$category->save();
			}
		}
		
		
	}
	//print_r($post);
	//exit;
}