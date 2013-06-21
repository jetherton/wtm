<?php defined('SYSPATH') or die('No direct script access.');
/***********************************************************
 * uploadwindow.php - Pop up window for uploads
* This software is copy righted by WatchTheMed 2013
* Writen by Dylan Gillespie, Etherton Technologies <http://ethertontech.com>
* Started on 2013-20-06
* Creates the pop up window for uploads.
*************************************************************/
?>

<div id="uploadWindow">
	<?php echo $js;?>
	<form id="uploadForm">
	<h3 style="text-color:#000"><?php echo Kohana::lang('uploadlayers.upload');?></h3>
	
	<div class="report_row">
		<div id="manual-fine-uploader"></div>
			<div id="triggerUpload" class="btn btn-primary" style="margin-top: 10px;">
  			<i class="icon-upload icon-white"></i> Upload now
		</div>
	</div>
	
	</form>
</div>