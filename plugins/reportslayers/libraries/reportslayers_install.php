<?php defined('SYSPATH') or die('No direct script access.');
/**
 * @author     John Etherton <john@ethertontech.com>
 * @package    reports Layers - https://github.com/jetherton/wtm
 * @license    GNU Lesser GPL (LGPL) Rights pursuant to Version 3, June 2007
 * @copyright  2013 Etherton Technologies Ltd. <http://ethertontech.com>
 * @Date       2013-06-02
 * Purpose:    Installation script for the Reports Layers plugin
 * Inputs:     Internal calls from modules
 * Outputs:    Adds or removes the database elements that Enhanced Map needs
 * 
 *
 * Changelog:
 * 2013-06-02:  Etherton - Initial release
 *
 * Developed by Etherton Technologies Ltd.
 */


class Reportslayers_Install {

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
		$this->db->query('CREATE TABLE IF NOT EXISTS `'.Kohana::config('database.default.table_prefix').'reportslayers` (
		    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
		    `report_id` bigint(20) unsigned NOT NULL,
		    `layer_id` int(11) NOT NULL,
		    PRIMARY KEY (`id`),
		    KEY `report_id` (`report_id`,`layer_id`)
		    ) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;');
		
		
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