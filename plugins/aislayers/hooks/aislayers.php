<?php defined('SYSPATH') or die('No direct script access.');
/***********************************************************
* zoombuttonsevents.php - Event handler for Zoom Buttons Plugin
* This software is copy righted by WatchTheMed 2013
* Writen by Dylan Gillespie, Etherton Technologies <http://ethertontech.com>
* Started on 2013-09-05
* This plugin changes the current Ushahidi zoom buttons to match the WatchTheMed toolbar.
*************************************************************/


class aislayers {

	public function __construct()
	{
		Event::add('system.pre_controller', array($this, 'add'));
	}

	/**
	 * This function sets whether the plugin should plug in to the events or not
	 */
	public function add()
	{
                if(Router::$controller == "main"){                        
                        Event::add('ushahidi_action.header_scripts', array($this, 'add_layer'));
                }
	}
	
	/**
	 * Renders the Javascript to make the map wide
	 */
	public function add_layer(){
                $view = new View('aislayers/add_layers_js');
                echo $view;
	}
	
}
new aislayers;