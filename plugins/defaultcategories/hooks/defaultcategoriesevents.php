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
	}
	
	public function render_javascript(){
		$view = new View('defaultcategories/defaultcategories_js');
		echo $view;
	}
	
	public function render_admin_viewjs(){
		$view = new View('defaultcategories/defaultadminsettings_js');
		echo $view;
	}
	
}
new defaultcategoriesevents;