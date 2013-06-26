<?php defined('SYSPATH') or die('No direct script access.');
/***********************************************************
* importantreportsevents.php - Event handler for Important Reports
* This software is copy righted by WatchTheMed 2013
* Writen by Dylan Gillespie, Etherton Technologies <http://ethertontech.com>
* Started on 2013-26-06
* This plugin is to add a ruler tool to the maps.
*************************************************************/


class importantreportsevents {

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
		if($url == 'main'){
			Event::add('ushahidi_action.header_scripts', array($this, 'render_javascript'));
		}
		elseif(strpos($url, 'admin/reports/edit') !== false){
			Event::add('ushahidi_action.header_scripts_admin', array($this, 'render_admin_javascript'));
		}
		Event::add('ushahidi_action.report_submit_admin', array($this, 'parseSubmitAdmin'));
		
	}
	
	/**
	 * Renders the Javascript to make the map wide
	 */
	public function render_javascript(){
		$view = new View('importantreports/importantreports_js');
		
		$importants = ORM::factory('incident')->
		where('incident_important', 1)->
		find_all();
		
		$view->importants = $importants;
		echo $view;
	}
	/**
	 * Pulls the javascript for the admin pages
	 */
	public function render_admin_javascript(){
		$view = new View('importantreports/importantadmin_js');
		$report = intval(substr(url::current(),19));
		$import = ORM::factory('incident', $report);
		
		$view->important = $import->incident_important;
		echo $view;
	}
	
	public function parseSubmitAdmin(){
		$report = intval(substr(url::current(),19));
		$import = ORM::factory('incident', $report);
		
		$import->incident_important = $_POST['incident_important'];
		$import->save();
	}
}
new importantreportsevents;