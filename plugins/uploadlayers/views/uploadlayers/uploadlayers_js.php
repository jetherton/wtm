<?php defined('SYSPATH') or die('No direct script access.');
/***********************************************************
* privatefields_js.php - Javascript for PrivateFields Plugin
* This software is copy righted by WatchTheMed 2013
* Writen by Dylan Gillespie, Etherton Technologies <http://ethertontech.com>
* Started on 2013-14-06
* This javascript is to make fields that are labeled as private to not not appear.
*************************************************************/
?>
<!-- Javascript files for popup window -->
<script type="text/javascript" src="<?php echo URL::base(); ?>plugins/uploadlayers/media/js/jquery.tools.min.js"> </script>
<script type="text/javascript" src="<?php echo URL::base(); ?>plugins/uploadlayers/media/js/jquery-ui.min.js"> </script>
<link rel="stylesheet" href="<?php echo URL::base(); ?>plugins/uploadlayers/media/css/jquery-ui.css" />
<link rel="stylesheet" href="<?php echo URL::base(); ?>plugins/uploadlayers/media/css/uploadlayersCSS.css" />


<script type="text/javascript">	

$(document).ready(function(){  
	$('.report_left').append('<div class="report_row">\
								<h4><a id="uploadLayer" rel="#overlay" href="<?php echo url::base(); ?>parseFiles/parseWindow" >\
									Upload a Layer\
								</a></h4>\
							</div>');
	$('#uploadLayer').after('<div class="apple_overlay" id="overlay" style="display:none">\
							<div class="contentWrap">\
								<img class="contentWrapWaiter" src="<?php echo URL::base();?>plugins/uploadlayers/media/img/waiter_barber.gif"/>\
							</div>\
						</div>');
	$('#uploadLayer').after('<input type="hidden" id="user_kml_ids" name="user_kml_ids"/>');
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
});
	
</script>