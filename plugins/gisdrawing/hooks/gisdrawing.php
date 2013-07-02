<?php defined('SYSPATH') or die('No direct script access.');
/***********************************************************
* importantreportsevents.php - Event handler for Important Reports
* This software is copy righted by WatchTheMed 2013
* Writen by Dylan Gillespie, Etherton Technologies <http://ethertontech.com>
* Started on 2013-26-06
* This plugin is to add a ruler tool to the maps.
*************************************************************/


class gisdrawing {

	public function __construct()
	{
		Event::add('system.pre_controller', array($this, 'add'));
	}

	/**
	 * This function sets whether the plugin should plug in to the events or not
	 */
	public function add()
	{
		$controller = Router::$controller;
		$method = Router::$method;
		if($controller == "reports"){
		    if($method == "submit"){
			Event::add('ushahidi_action.header_scripts',  array($this, 'add_stuff_in_header'));			
		    } elseif($method == "edit"){
			Event::add('ushahidi_action.header_scripts_admin',  array($this, 'add_stuff_in_header'));			
		    }
		}	
		
		
	}
	
	
	/**
	 * Throw in some JavaScript and CSS
	 */
	public function add_stuff_in_header(){
	    $path = url::base().'plugins/gisdrawing/media/ms-Dropdown/';
	    echo "\n".'<script src="'.$path.'js/msdropdown/jquery.dd.min.js" type="text/javascript"></script>'."\n";
	    echo '<link rel="stylesheet" type="text/css" href="'.$path.'css/msdropdown/dd.css" />'."\n";
	}
	
	
	
}
new gisdrawing;