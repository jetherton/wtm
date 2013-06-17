<?php defined('SYSPATH') or die('No direct script access.');
/**
 * @author     John Etherton <john@ethertontech.com>
 * @package    Private Fields, Ushahidi Plugin - https://github.com/jetherton/wtm
 *
 * Developed by Etherton Technologies Ltd.
 */


class Enhancedmap_Install {

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
	
		// Create the database tables.
		// Also include table_prefix in name		
		$this->db->query('CREATE TABLE IF NOT EXISTS `'.Kohana::config('database.default.table_prefix').'incident_private`
		(
				`incident_title` tinyint(1) NOT NULL DEFAULT 0,
				`incident_description` tinyint(1) NOT NULL DEFAULT 0,
				`incident_date` tinyint(1) NOT NULL DEFAULT 0,
				`incident_time` tinyint(1) NOT NULL DEFAULT 0,
				`incident_firstname` tinyint(1) NOT NULL DEFAULT 1,
				`incident_lastname` tinyint(1) NOT NULL DEFAULT 1,
				`incident_email` tinyint(1) NOT NULL DEFAULT 1
		) ENGINE=InnoDB DEFAULT CHARSET=latin1;');
		
		
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