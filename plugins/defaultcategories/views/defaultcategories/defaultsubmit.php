<?php defined('SYSPATH') or die('No direct script access.');
/***********************************************************
* defaultsubmit.php - Submit handler for DefaultCategories Plugin
* This software is copy righted by WatchTheMed 2013
* Writen by Dylan Gillespie, Etherton Technologies <http://ethertontech.com>
* Started on 2013-13-06
* This plugin is to allow default categories for maps.
*************************************************************/



if(isset($post['defaultCats'])){
    
	//first turn off category_default for all categories that have it on
	$allCats = ORM::factory('category')->where('category_default',1)->find_all();
	foreach($allCats as $cat){
	    $cat->category_default = 0;
	    $cat->save();
	}
	
	//now turn it back on for those that are in the POST
	foreach($post['defaultCats'] as $dbId=>$cat_item){
	    $cat = ORM::factory('category')->where('id',$dbId)->find();
	    $cat->category_default = 1;
	    $cat->save();
	}
    
}