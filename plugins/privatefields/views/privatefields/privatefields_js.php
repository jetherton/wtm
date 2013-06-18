<?php defined('SYSPATH') or die('No direct script access.');
/***********************************************************
* privatefields_js.php - Javascript for PrivateFields Plugin
* This software is copy righted by WatchTheMed 2013
* Writen by Dylan Gillespie, Etherton Technologies <http://ethertontech.com>
* Started on 2013-14-06
* This javascript is to make fields that are labeled as private to not not appear.
*************************************************************/
?>

<script type="text/javascript">	

	var divArray = Array();
		divArray['description'] = '.report-description-text';
		divArray['date'] = '.r_date';
		divArray['title'] = '.report-title';
		divArray['loc'] = '.r_location';
		//divArray['first'] = 'incident_firstname';
		//divArray['last'] = 'incident_lastname';
		//divArray['email'] = 'incident_email';

	var privateArray = <?php echo $private?>;

	$(document).ready(function(){
		console.log(privateArray);
		for(var i in privateArray){
			switch(i){
				case 'description' : $('.left-col').children('.report-description-text').text(''); break;
				case 'date' : $('.left-col').children('.report-when-where').children('.r_date').text(''); break;
				case 'title' : $('.left-col').children('.report-title').text(''); break;
				case 'loc' : $('.left-col').children('.report-when-where').children('.r_location').text(''); break;
				case 'first' : $('.left-col').children('.report-description-text').text(''); break;
				case 'last' : $('.left-col').children('.report-description-text').text(''); break;
				case 'email' : $('.left-col').children('.report-description-text').text(''); break;
			}
		}

		if(<?php echo count($names)?> > 0){
			console.log('<?php echo $names['first'].$names['last']?>');
		}
	});
	
</script>