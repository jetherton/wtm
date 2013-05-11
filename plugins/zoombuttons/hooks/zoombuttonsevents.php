<?php defined('SYSPATH') or die('No direct script access.');
/***********************************************************
* zoombuttonsevents.php - Event handler for Zoom Buttons Plugin
* This software is copy righted by WatchTheMed 2013
* Writen by Dylan Gillespie, Etherton Technologies <http://ethertontech.com>
* Started on 2013-09-05
* This plugin changes the current Ushahidi zoom buttons to match the WatchTheMed toolbar.
*************************************************************/


class zoombuttonsevents {

	public function __construct()
	{
		Event::add('system.pre_controller', array($this, 'add'));
	}

	/**
	 * This function sets whether the plugin should plug in to the events or not
	 */
	public function add()
	{
		$url = url::current();

		//Only add the plugin to pages with a map
		//The last blank case happens when the webpage first loads, so the main page
		if($url == 'main' OR 
				$url == 'reports' OR 
				$url == 'reports/submit' OR 
				$url == 'alerts' OR 
				$url == ''){
			Event::add('ushahidi_action.header_scripts', array($this, 'render_javascript'));
		}
		elseif(strpos($url, 'reports/view') !== false){
			Event::add('ushahidi_action.header_scripts', array($this, 'render_javascript'));
		}
		elseif(strpos($url, 'admin/reports/edit') !== false){
			Event::add('ushahidi_action.header_scripts_admin', array($this, 'render_javascript'));
		}		
	}
	
	/**
	 * Renders the Javascript to make the map wide
	 */
	public function render_javascript(){
		$view = new View('zoombuttons/zoombuttons_js');
		$view->url = url::current();
		echo $view;
	}
	
}
new zoombuttonsevents;