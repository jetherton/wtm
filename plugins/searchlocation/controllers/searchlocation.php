<?php defined('SYSPATH') or die('No direct script access.');

class Searchlocation_Controller extends Controller{
	
	function index(){
		if($_POST){
			print_r($_POST);
		}
	}
	
	//function for using the Helper_map::geocode function 
	function geocodeAddress(){
		$address = $_POST['loc'];
		$results = map::geocode($address);
		
		
		$latlon = '[{"lat":"'.$results['latitude'].'"},{"lon":"'.$results['longitude'].'"}]';
		echo json_encode($latlon);
	}
		
} // End Searchlocation

?>