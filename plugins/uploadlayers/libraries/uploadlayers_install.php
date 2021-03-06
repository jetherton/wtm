<?php defined('SYSPATH') or die('No direct script access.');
/**
 * @author     Dylan Gillespie <dylan@ethertontech.com>
 * @package    Upload Layers, Ushahidi Plugin - https://github.com/jetherton/wtm
 *
 * Developed by Etherton Technologies Ltd.
 */


class Uploadlayers_Install {

    /**
	 * Function: __construct
	 * 	 
	 * Description: A default constructor that initializes instance variables.
	 *
	 * Views:
	 *
	 * Results: Instance variables are set
	 */
	public function __construct()
	{
		$this->db = Database::instance();
	}

	
	
	
	
	
	/**
	 * Function: run_install
	 *
	 * Description: Creates the required database tables for the actionable plugin
	 *
	 * Views:
	 *
	 * Results: Database is initialized
	 */
	public function run_install()
	{
	
		//check to see if the column already exists if the plugin is activated more than once	
		$sql = true;
		try{
			$sql = $this->db->query(
					'SELECT `date_uploaded` FROM `layer`');
		}
		catch(Exception $e){
			if($e){
				$this->db->query('ALTER TABLE `layer` ADD  `date_uploaded` DATE NOT NULL;
								  ALTER TABLE  `layer` ADD  `layer_icon_thumb` CHAR( 255 ) NOT NULL');
			}
		}
	
		
	}


	
	/**
	 * Function: uninstall
	 *
	 * Description: Should uninstall the settings from the DB, but I hate the idea of careless admin
	 * not knowing that DB deletes are permanent, so right now this does nothing.
	 *
	 * Views:
	 *
	 * Results: Nothing
	 */
	public function uninstall()
	{
		
	
	}
}