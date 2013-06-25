<?php defined('SYSPATH') or die('No direct script access.');
/***********************************************************
* defaultadminsettings_js.php - Javascript for DefaultCategories Plugin
* This software is copy righted by WatchTheMed 2013
* Writen by Dylan Gillespie, Etherton Technologies <http://ethertontech.com>
* Started on 2013-07-06
* This javascript is to add default options to the settings page.
*************************************************************/
?>


<script type="text/javascript">	
	
var path_info = '<?php echo url::current();?>';	
	var globalData = jQuery.parseJSON(<?php echo $categories ?>);


	$(document).ready(function(){var item = $('.has_border_first').next().next().next().append(<?php echo $categoryDiv ?>);				    
				     $('#category_switch').addClass('defaultCats');
				 });


	function change(){
		var changed = new Array();
		for(var i in globalData){
			var name = globalData[i][0].replace(' ', '_');
			var checked = $('#' + name).is(':checked');
			if(globalData[i][4] != checked){
				changed[i] = checked;
				//$.post("<?php echo url::base();?>defaultcategories/changeDefault", {catid: i, change : checked});
			}
			for(var j in globalData[i][3]){
				var name = globalData[i][0].replace(' ', '_');
				var checked = $('#' + name).is(':checked');
				if(globalData[i][3][j][3] != checked){
					changed[j] = checked;
					//$.post("<?php echo url::base();?>defaultcategories/changeDefault", {catid: j, change :checked});
				}
			}
		}
		$.post("<?php echo url::base()?>defaultcategories/changeDefault", {chan : changed});
	}
	$(document).on('click', '.cancel-btn', function(){
		change();
	});
	
	$(document).on('click','.show',function() {
		var val = this.id.substring(5);
		$('.child_' + val).toggle();
		if(this.text == '+'){
			$('#' + this.id).text('-');
		}
		else if(this.text == '-'){
			$('#' + this.id).text('+');
		}
	 });
	 $(document).on('click', '#All_Categories', function(){
		 var checkedStatus = this.checked;
		 $('table :checkbox').each(function () {
		     $(this).prop('checked', checkedStatus);
		 });
	 });

</script>

<link rel="stylesheet" type="text/css" href="<?php echo url::base(); ?>plugins/defaultcategories/media/css/defaultcategoriesCSS.css"/>




