<?php defined('SYSPATH') or die('No direct script access.');
/***********************************************************
* importantreports_js.php - Javascript for Important Reports plugin
* This software is copy righted by WatchTheMed 2013
* Writen by Dylan Gillespie, Etherton Technologies <http://ethertontech.com>
* Started on 2013-26-06
* This javascript makes important reports noted on main screen.
*************************************************************/
?>

<script type="text/javascript">	
$(document).ready(function(){
	var reportsLayer = map._olMap.getLayersByName("Reports");
	console.log(reportsLayer);
});
	
</script>
