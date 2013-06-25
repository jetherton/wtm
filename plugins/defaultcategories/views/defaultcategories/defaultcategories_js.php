<?php defined('SYSPATH') or die('No direct script access.');
/***********************************************************
* defaultacategories_js.php - Javascript for DefaultCategories Plugin
* This software is copy righted by WatchTheMed 2013
* Writen by Dylan Gillespie, Etherton Technologies <http://ethertontech.com>
* Started on 2013-07-06
* This javascript is to make categories default on.
*************************************************************/
?>

<script type="text/javascript">	

	

	function startDefaultCategories(){
			var data = <?php echo $categories?>;
			console.log(data);
			for(var i in data){
			    var catId = data[i];
			    $("#cat_"+catId).click();
			}

	}



	

jQuery(window).load(function() {
	
	startDefaultCategories();
	
});

</script>