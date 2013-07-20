<?php defined('SYSPATH') or die('No direct script access.');
/***********************************************************
* reportslayers.php - Event handler for reports layers
* This software is copy righted by WatchTheMed 2013
* Writen by John Etherton, Etherton Technologies <http://ethertontech.com>
* Started on 2013-05-29
* This plugin lets users access layers on reports
*************************************************************/


class reportslayers {

	private $post = null;
	
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
		    Event::add('ushahidi_action.report_form', array($this, '_add_submit_edit_ui'));
		    Event::add('ushahidi_action.report_posted_frontend', array($this, '_grab_post'));
		    Event::add('ushahidi_action.report_add', array($this, '_save_layers'));		    
		    
                }
                
                   if(Router::$controller == "reports" AND Router::$method == "edit")
		{
		       
		    Event::add('ushahidi_action.admin_report_form', array($this, '_add_submit_edit_ui'));	
		    Event::add('ushahidi_action.report_submit_admin', array($this, '_grab_post'));	
		    Event::add('ushahidi_action.report_edit', array($this, '_save_layers'));		    
                }


	}
	
	/**
	 * Called when a controller has saved a report and we have an id to tie to the report
	 */
	public function _save_layers(){	    	    
	    $report = Event::$data;
	    $post = $this->post;
	    //clear out current layer/report mapping
	    ORM::factory('reportslayers')->where('report_id', $report->id)
		    ->delete_all();
	    if(isset($post['reportslayers'])){
		foreach($post['reportslayers'] as $layer_id){
		    $model = ORM::factory('reportslayers');
		    $model->layer_id = $layer_id;
		    $model->report_id = $report->id;
		    $model->save();
		}
	    }
	}
	
	
	/**
	 * Called when the controller processing the saving of a report has compiled the post variables
	 */
	public function _grab_post(){
	    $this->post = Event::$data;
	}
	
	
	/**
	 * This adds the JS for users when viewing reports on the front end
	 */
	public function _add_view_js(){
		if(isset(Router::$arguments)){
		    $id = Router::$arguments[0];
		    $view = new View('reportslayers/view_js');
		     //next grab the selections
		    $selections_orm = ORM::factory('reportslayers')
			->where('report_id',$id)
			->find_all();
		    $selections = array();
		    foreach($selections_orm as $selection){
			$selections[] = $selection->layer_id;
		    }
		    $view->selections = $selections;
		    echo $view;
		}
	}
	
	/**
	 * Pulls the list of layers so that it can be rendered to the user when the
	 * user is viewing a report on the front end
	 */
	public function _add_view_ui(){
		$id = Event::$data;
		// Get all active Layers (KMZ/KML)
		$layers = array();
		$config_layers = Kohana::config('map.layers'); 
		if ($config_layers == $layers) {
		    $standard_layers_orm = ORM::factory('layer')
					->where('layer_visible', 1)
					->where('date_uploaded', '0000-00-00')
					->orderby('layer_name', 'asc')
					->find_all();
		    $standard_layers = array();
		    foreach($standard_layers_orm as $orm){
			$standard_layers[] = $orm;
		    }
		    $special_layers_orm = ORM::factory('layer')
			    ->join('reportslayers', 'reportslayers.layer_id', 'layer.id','INNER')
			    ->where('reportslayers.report_id',$id)
			    ->where('date_uploaded <> \'0000-00-00\'')
			    ->orderby('layer_name', 'asc')
			    ->find_all();
		    $special_layers = array();
		    foreach($special_layers_orm as $orm){
			$special_layers[] = $orm;
		    }
		    $all_layers = array_merge($special_layers, $standard_layers);
			foreach ( $all_layers as $layer)
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
	
	
	/**
	 * Used to add the layers on by default UI to an event 
	 * submit / edit page
	 */
	public function _add_submit_edit_ui(){
	    $id = Event::$data;
	    
	    // Get all active Layers (KMZ/KML)
	    $layers = array();
	    $config_layers = Kohana::config('map.layers'); 
	    if ($config_layers == $layers) {
		
		  $standard_layers_orm = ORM::factory('layer')
					->where('layer_visible', 1)
					->where('date_uploaded', '0000-00-00')
					->orderby('layer_name', 'asc')
					->find_all();
		    $standard_layers = array();
		    foreach($standard_layers_orm as $orm){
			$standard_layers[] = $orm;
		    }
		    
		    $special_layers = array();

		    if($id){
			$special_layers_orm = ORM::factory('layer')
				->join('reportslayers', 'reportslayers.layer_id', 'layer.id','INNER')
				->where('reportslayers.report_id',$id)
				->where('date_uploaded <> \'0000-00-00\'')
				->orderby('layer_name', 'asc')
				->find_all();		    
			foreach($special_layers_orm as $orm){
			    $special_layers[] = $orm;
			}
		    }
		    $all_layers = array_merge($special_layers, $standard_layers);
		
		
		foreach ($all_layers as $layer){
		    if(!isset($layers[$layer->parent_id])){ 
			$layers[$layer->parent_id] = array();
		    }

		    $layers[$layer->parent_id][] = $layer;
		}
	    } else {
		$layers = $config_layers;
	    }
	    $view = new View('reportslayers/submit_edit_ui');
	    $view->layers = $layers;
	    //next grab the selections
	    $selections_orm = ORM::factory('reportslayers')
		    ->where('report_id',$id)
		    ->find_all();
	    $selections = array();
	    foreach($selections_orm as $selection){
		$selections[] = $selection->layer_id;
	    }
	    $view->selections = $selections;

	    echo $view;

	}
	
}
new reportslayers;