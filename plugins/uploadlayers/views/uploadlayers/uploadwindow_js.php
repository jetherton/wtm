<?php defined('SYSPATH') or die('No direct script access.');
/***********************************************************
 * uploadwindow_js.php - Pop up window for uploads
* This software is copy righted by WatchTheMed 2013
* Writen by Dylan Gillespie, Etherton Technologies <http://ethertontech.com>
* Started on 2013-20-06
* Creates the pop up window for uploads, javascript side.
*************************************************************/
?>

<!-- Javascript files for the uploader -->
<script src="<?php echo URL::base();?>plugins/uploadlayers/media/js/fine-uploader/client/js/header.js"></script>
<script src="<?php echo URL::base();?>plugins/uploadlayers/media/js/fine-uploader/client/js/util.js"></script>
<script src="<?php echo URL::base();?>plugins/uploadlayers/media/js/fine-uploader/client/js/version.js"></script>
<script src="<?php echo URL::base();?>plugins/uploadlayers/media/js/fine-uploader/client/js/features.js"></script>
<script src="<?php echo URL::base();?>plugins/uploadlayers/media/js/fine-uploader/client/js/promise.js"></script>
<script src="<?php echo URL::base();?>plugins/uploadlayers/media/js/fine-uploader/client/js/button.js"></script>
<script src="<?php echo URL::base();?>plugins/uploadlayers/media/js/fine-uploader/client/js/upload-data.js"></script>
<script src="<?php echo URL::base();?>plugins/uploadlayers/media/js/fine-uploader/client/js/ajax.requester.js"></script>
<script src="<?php echo URL::base();?>plugins/uploadlayers/media/js/fine-uploader/client/js/deletefile.ajax.requester.js"></script>
<script src="<?php echo URL::base();?>plugins/uploadlayers/media/js/fine-uploader/client/js/handler.base.js"></script>
<script src="<?php echo URL::base();?>plugins/uploadlayers/media/js/fine-uploader/client/js/handler.base.js"></script>
<script src="<?php echo URL::base();?>plugins/uploadlayers/media/js/fine-uploader/client/js/window.receive.message.js"></script>
<script src="<?php echo URL::base();?>plugins/uploadlayers/media/js/fine-uploader/client/js/handler.form.js"></script>
<script src="<?php echo URL::base();?>plugins/uploadlayers/media/js/fine-uploader/client/js/handler.xhr.js"></script>
<script src="<?php echo URL::base();?>plugins/uploadlayers/media/js/fine-uploader/client/js/paste.js"></script>
<script src="<?php echo URL::base();?>plugins/uploadlayers/media/js/fine-uploader/client/js/dnd.js"></script>
<script src="<?php echo URL::base();?>plugins/uploadlayers/media/js/fine-uploader/client/js/uploader.basic.js"></script>
<script src="<?php echo URL::base();?>plugins/uploadlayers/media/js/fine-uploader/client/js/uploader.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo URL::base();?>/plugins/uploadlayers/media/js/fine-uploader/client/fineuploader.css"/>



<script type="text/javascript">	

$(document).ready(function(){  
	
	var errorHandler = function(event, id, fileName, reason) {
        qq.log("id: " + id + ", fileName: " + fileName + ", reason: " + reason);
    };
    
	$(document).ready(function() {
	    var manualuploader = new qq.FineUploader({
	      element: $('#manual-fine-uploader')[0],
	      request: {
	    	  endpoint: "<?php echo url::base();?>parseFiles/submitFiles",
	    	  
	      },
	      autoUpload: false,
	      text: {
	        uploadButton: '<i class="icon-plus icon-white"></i> Select Files'
	      }
	    });
	 
	    $('#triggerUpload').click(function() {
		  manualuploader.setParams({
				 layer_url : $('#layer_url').val(),
				 layer_name : $('#layer_name').val(),
				 layer_color : $('#layer_color').val(),
				 meta_data : $('#meta_data').val()
	    	  });
	      manualuploader.uploadStoredFiles();
	    });
	  });
});
	
</script>