<?php defined('SYSPATH') or die('No direct script access.');
/***********************************************************
* searchlocationevents.php - Event handler for SearchLocations Plugin
* This software is copy righted by WatchTheMed 2013
* Writen by Dylan Gillespie, Etherton Technologies <http://ethertontech.com>
* Started on 2013-27-05
* This plugin is to add a search fucntion to the maps.
*************************************************************/


class searchlocationevents {

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
				$url == 'reports' OR 
				$url == 'reports/submit' OR 
				//$url == 'alerts' OR 
				$url == ''){
			Event::add('ushahidi_action.header_scripts', array($this, 'render_javascript'));
		}
		elseif(strpos($url, 'admin/reports/edit') !== false){
			Event::add('ushahidi_action.header_scripts_admin', array($this, 'render_javascript'));
		}		
	}
	
	public function render_javascript(){
		$view = new View('searchlocation/searchlocation_js');
		echo $view;
	}
	
}
new searchlocationevents;