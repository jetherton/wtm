<?php defined('SYSPATH') or die('No direct script access.');
/***********************************************************
* defaultcategoriesevents.php - Event handler for DefaultCategories Plugin
* This software is copy righted by WatchTheMed 2013
* Writen by Dylan Gillespie, Etherton Technologies <http://ethertontech.com>
* Started on 2013-07-06
* This plugin is to allow default categories for maps.
*************************************************************/


class privatefieldsevents {

	public function __construct()
	{
		Event::add('system.pre_controller', array($this, 'add'));
	}

	public function add()
	{
		$url = url::current();

		if(strpos($url, 'admin/reports/edit') !== false){
			Event::add('ushahidi_action.header_scripts_admin', array($this, 'render_admin_viewjs'));
		}
		elseif(strpos($url, 'reports/view/') !== false){
			Event::add('ushahidi_action.header_scripts', array($this, 'render_javascript'));
		}
		Event::add('ushahidi_action.report_submit_admin', array($this, 'parseSubmit'));

	}
	
	public function render_javascript(){
		$view = new View('privatefields/privatefields_js');
		
		$url = url::current();
		$len = strlen('reports/view/');
		$results = array();
		$names = array();
		
		$incidentID = substr($url, $len);
		
		$incident = ORM::factory('incident', $incidentID);
		
		$person = ORM::factory('incident_person')->
		where('incident_id', $incidentID)->
		find();
		
		if($incident->incident_private == 1){
			
		}
		
		if(count($names) > 0){
			$view->names = $names;
		}

		echo $view;
	}
	
	public function render_admin_viewjs(){
		$view = new View('privatefields/privateadminsettings_js');
		echo $view;
	}
	
	public function parseSubmit(){
		$view = new View('privatefields/privatesubmit');
		$view->post = $_POST;
		echo $view;
	}

}
new privatefieldsevents;