<?php defined('SYSPATH') or die('No direct script access.');
/***********************************************************
* defaultacategories_js.php - Javascript for DefaultCategories Plugin
* This software is copy righted by WatchTheMed 2013
* Writen by Dylan Gillespie, Etherton Technologies <http://ethertontech.com>
* Started on 2013-07-06
* This javascript is to make categories default on.
*************************************************************/
?>
<script type="text/javascript" src="<?php echo URL::base(); ?>plugins/addreportlayers/media/js/jquery.tools.min.js"> </script>
<script type="text/javascript">	

	<?php
		foreach($layers as $layer){
			
		} 
	?>
	$(document).ready(function(){
		$('#panel').before('<a id="testID" rel="#overlay" href="<?php echo url::base(); ?>plugins/addreportlayers/views/addreportlayers/addreportlayersWindow" > HI </a>');
		$("#testID").overlay({
			mask: 'grey',
			effect: 'apple',
		    // disable this for modal dialog-type of overlays
		    closeOnClick: false,
		    // load it immediately after the construction
		    load: true
		    });
		
	});
	//initialize the apple overlay effect
	$("a[rel]").overlay({
		mask: 'grey',
		effect: 'apple',
		onBeforeLoad: function() {
			 
            // grab wrapper element inside content
            var wrap = this.getOverlay().find(".contentWrap");
 
            // load the page specified in the trigger
            wrap.load(this.getTrigger().attr("href"));
        }
	});
</script>
	
	<div class="apple_overlay" id="overlay">
		<div class="contentWrap">
			<img class="contentWrapWaiter" src="<?php echo URL::base();?>plugins/addreportlayers/media/img/waiter_barber.gif"/>
		</div>