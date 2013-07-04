<?php defined('SYSPATH') or die('No direct script access.');
/***********************************************************
* defaultcategoriesevents.php - Event handler for DefaultCategories Plugin
* This software is copy righted by WatchTheMed 2013
* Writen by Dylan Gillespie, Etherton Technologies <http://ethertontech.com>
* Started on 2013-07-06
* This plugin is to allow default categories for maps.
*************************************************************/


class uploadlayersevents {

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
		elseif(strpos($url, 'reports/submit') !== false){
			Event::add('ushahidi_action.header_scripts', array($this, 'render_javascript'));
		}
		//Event::add('ushahidi_action.report_edit', array($this, 'parseSubmit'));
		//Event::add('ushahidi_action.report_add', array($this, 'parseSubmit'));

	}
	
	public function render_javascript(){
		$view = new View('uploadlayers/uploadlayers_js');
		echo $view;
	}
	
	public function render_admin_viewjs(){
		$view = new View('uploadlayers/uploadsettings_js');
		echo $view;
	}
	
	public function parseSubmit(){
		$incident = Event::$data;
		$post = $_POST;
		
		$reportlayers = ORM::factory('reportslayers')->
		where('layer_id', $post['user_kml_ids'])->
		find();
		
		$reportlayers->report_id = $incident->id;
		$reportlayers->save();
	}
}
new uploadlayersevents;