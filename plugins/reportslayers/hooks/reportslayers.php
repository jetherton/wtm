<?php defined('SYSPATH') or die('No direct script access.');
/***********************************************************
* reportslayers.php - Event handler for reports layers
* This software is copy righted by WatchTheMed 2013
* Writen by John Etherton, Etherton Technologies <http://ethertontech.com>
* Started on 2013-05-29
* This plugin lets users access layers on reports
*************************************************************/


class reportslayers {

	public function __construct()
	{	
		Event::add('system.pre_controller', array($this, 'add'));
	}

	public function add()
	{
		if(Router::$controller == "reports" AND Router::$method == "view")
		{
                    Event::add('ushahidi_action.report_view_sidebar', array($this, '_add_view_ui'));
                    Event::add('ushahidi_action.header_scripts', array($this, '_add_view_js'));                
                    plugin::add_stylesheet("reportslayers/media/css/reportslayers");
                }
                
                if(Router::$controller == "reports" AND Router::$method == "submit")
		{
                    Event::add('ushahidi_action.report_form_frontend_after_time', array($this, '_add_view_ui'));
                    Event::add('ushahidi_action.header_scripts', array($this, '_add_view_js'));                
                    plugin::add_stylesheet("reportslayers/media/css/reportslayers_submit");
                }
                
                   if(Router::$controller == "reports" AND Router::$method == "edit")
		{
                    Event::add('ushahidi_action.report_pre_form_admin', array($this, '_add_view_ui'));
                    Event::add('ushahidi_action.header_scripts_admin', array($this, '_add_view_js'));                
                    plugin::add_stylesheet("reportslayers/media/css/reportslayers_edit");
                }


	}
	
	
	/**
	 * This adds the JS for users when viewing reports on the front end
	 */
	public function _add_view_js(){
		$view = new View('reportslayers/view_js');
		echo $view;
	}
	
	/**
	 * Pulls the list of layers so that it can be rendered to the user when the
	 * user is viewing a report on the front end
	 */
	public function _add_view_ui(){
		// Get all active Layers (KMZ/KML)
		$layers = array();
		$config_layers = Kohana::config('map.layers'); 
		if ($config_layers == $layers) {
			foreach (ORM::factory('layer')
					->where('layer_visible', 1)
					->orderby('layer_name', 'asc')
					->find_all() as $layer)
			{
				if(!isset($layers[$layer->parent_id])){
					$layers[$layer->parent_id] = array();
				}
		
				$layers[$layer->parent_id][$layer->id] = $layer;
			}
		}
		else
		{
			$layers = $config_layers;
		}
		$view = new View('reportslayers/view_ui');
		$view->layers = $layers;
		
		
		echo $view;
	}
	
}
new reportslayers;