<?php

class ParseFiles_Controller extends Controller{
	public function submitFiles(){
		echo '{"success" : true}';
	}
	
	public function parseWindow(){
		$view = new View('uploadlayers/uploadwindow');
		$js = new View('uploadlayers/uploadwindow_js');
		$view->js = $js;
		echo $view;
	}
}
?>