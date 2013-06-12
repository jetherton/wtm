<?php defined('SYSPATH') or die('No direct script access.');
/***********************************************************
* defaultcategoriesevents.php - Event handler for DefaultCategories Plugin
* This software is copy righted by WatchTheMed 2013
* Writen by Dylan Gillespie, Etherton Technologies <http://ethertontech.com>
* Started on 2013-07-06
* This plugin is to allow default categories for maps.
*************************************************************/


class addreportlayersevents {

	public function __construct()
	{
		Event::add('system.pre_controller', array($this, 'add'));
	}

	public function add()
	{
		$url = url::current();
		if($url == 'reports/submit'){
			Event::add('ushahidi_action.header_scripts', array($this, 'render_javascript'));
		}
	
	}
	
	public function render_javascript(){
		$view = new View('addreportlayers/addreportlayers_js');
		
		$layers = ORM::factory('layer')->
		where('layer_visible', true)->
		find_all();
		// Pagination
		// Pagination
		$pagination = new Pagination(array(
			'query_string' => 'page',
			'total_items'	 => ORM::factory('feed')->count_all()
		));
		
		
		$view->total_items = count($layers);
		$view->layers = $layers;
		$view->pagination = $pagination;
		echo $view;
	}
	
}
new addreportlayersevents;