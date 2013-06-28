<?php defined('SYSPATH') or die('No direct script access.');
/***********************************************************
 * upload_helper.php - Helper to check for old layers and delete them if they don't have a report.
* This software is copy righted by WatchTheMed 2013
* Writen by Dylan Gillespie, Etherton Technologies <http://ethertontech.com>
* Started on 2013-27-06
* Helper to check for old layers and delete them if they don't have a report.
*************************************************************/



class upload_helper {
	
	//Will remove any uploads that are just sitting in the database if they weren't assigned to a report, specifically from uploading a layer onto a report.
	public static function check_old_uploads(){
		$reportslayers = ORM::factory('reportslayers')->
		where('report_id', '0')->
		find_all();
		
		foreach($reportslayers as $layer_id){
			$layer = ORM::factory('layer', $layer_id->layer_id);
			$oldSeconds = strtotime($layer->date_uploaded);
			$currentSeconds = time();

			$dayLength = 24 * 60 * 60;
			
			if($currentSeconds - $oldSeconds >= $dayLength){
				$layer->delete();
				$layer_id->delete();
			}
		}
	}
}