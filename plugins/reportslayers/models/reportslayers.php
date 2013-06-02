<?php defined('SYSPATH') or die('No direct script access.');
/**
 * @author     John Etherton <john@ethertontech.com>
 * @package    Reports Layers, Ushahidi Plugin - https://github.com/jetherton/wtm
 * @license    GNU Lesser GPL (LGPL) Rights pursuant to Version 3, June 2007
 * @copyright  2013 Etherton Technologies Ltd. <http://ethertontech.com>
 * @Date       2013-06-02
 * Purpose:    Model for storing information about which reports need which layers
 * Inputs:     Internal calls from modules
 * Outputs:    Access to the Enhanced map settings
 * Changelog:
 * 2013-06-02:  Etherton - Initial release
 *
 * Developed by Etherton Technologies Ltd.
 */



class Reportslayers_Model extends ORM_Tree
{	
	protected $table_name = 'reportslayers';


}