<?php defined('SYSPATH') or die('No direct script access.');
/***********************************************************
 * searchlocation.php - Javascript for SearchLocation Plugin
* This software is copy righted by WatchTheMed 2013
* Writen by Dylan Gillespie, Etherton Technologies <http://ethertontech.com>
* Started on 2013-28-05
* This plugin is to add searching for locations to map.
*************************************************************/

class Searchlocation_Controller extends Controller{
	
	//function for using the Helper_map::geocode function 
	function geocodeAddress(){
		$address = $_POST['loc'];
		$results = map::geocode($address);
		
		
		$latlon = '[{"lat":"'.$results['latitude'].'"},{"lon":"'.$results['longitude'].'"}]';
		echo json_encode($latlon);
	}
		
} // End Searchlocation

?>