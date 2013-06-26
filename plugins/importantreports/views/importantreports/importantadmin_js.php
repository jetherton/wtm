<?php defined('SYSPATH') or die('No direct script access.');
/***********************************************************
<<<<<<< HEAD
* importantadmin_js.php - Javascript for Important Reports plugin
=======
 * importantadmin_js.php - Javascript for Important Reports plugin
>>>>>>> 2feb1f37c18c9aaaeff9a8db9d433eeffed94ac8
* This software is copy righted by WatchTheMed 2013
* Writen by Dylan Gillespie, Etherton Technologies <http://ethertontech.com>
* Started on 2013-26-06
* This javascript gives option for important report.
*************************************************************/
?>

<script type="text/javascript">	

	$(document).ready(function(){
		var imp = '';
		imp += '<div class="row"><div class="f-col-bottom-1-col"><?php echo Kohana::lang('importantreports.make_important')?></div>';
		imp += '<input type="radio" name="incident_important" id="important" value="1"> Yes';
		imp += '<input type="radio" name="incident_important" id="notImportant" value="0"> No		</div>';
		$('.f-col-bottom-1').append(imp);

		var important = <?php echo $important?>;
		if(important){
			$('#important')[0].checked = true;
		}
		else{
			$('#notImportant')[0].checked = true;
		}
	});


	
</script>