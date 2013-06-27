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
<script src="<?php echo URL::base();?>plugins/uploadlayers/media/js/header.js"></script>
<script src="<?php echo URL::base();?>plugins/uploadlayers/media/js/util.js"></script>
<script src="<?php echo URL::base();?>plugins/uploadlayers/media/js/version.js"></script>
<script src="<?php echo URL::base();?>plugins/uploadlayers/media/js/features.js"></script>
<script src="<?php echo URL::base();?>plugins/uploadlayers/media/js/promise.js"></script>
<script src="<?php echo URL::base();?>plugins/uploadlayers/media/js/button.js"></script>
<script src="<?php echo URL::base();?>plugins/uploadlayers/media/js/upload-data.js"></script>
<script src="<?php echo URL::base();?>plugins/uploadlayers/media/js/ajax.requester.js"></script>
<script src="<?php echo URL::base();?>plugins/uploadlayers/media/js/deletefile.ajax.requester.js"></script>
<script src="<?php echo URL::base();?>plugins/uploadlayers/media/js/handler.base.js"></script>
<script src="<?php echo URL::base();?>plugins/uploadlayers/media/js/handler.base.js"></script>
<script src="<?php echo URL::base();?>plugins/uploadlayers/media/js/window.receive.message.js"></script>
<script src="<?php echo URL::base();?>plugins/uploadlayers/media/js/handler.form.js"></script>
<script src="<?php echo URL::base();?>plugins/uploadlayers/media/js/handler.xhr.js"></script>
<script src="<?php echo URL::base();?>plugins/uploadlayers/media/js/paste.js"></script>
<script src="<?php echo URL::base();?>plugins/uploadlayers/media/js/dnd.js"></script>
<script src="<?php echo URL::base();?>plugins/uploadlayers/media/js/uploader.basic.js"></script>
<script src="<?php echo URL::base();?>plugins/uploadlayers/media/js/uploader.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo URL::base();?>/plugins/uploadlayers/media/css/fineuploader.css"/>



<script type="text/javascript">	

var manualuploader = new qq.FineUploader({
    element: $('#manual-fine-uploader')[0],
    request: {
  	  endpoint: "<?php echo url::base();?>parseFiles/submitFiles",
    },
    multiple: false,
    autoUpload: false,
    text: {
      uploadButton: '<i class="icon-plus icon-white"></i> Select Files'
    },
    validation: {
			allowedExtensions : ['kml', 'kmz']
    },
    callbacks : {
			onComplete: function(){
				setLayerId();
			}
    }
  });
  
	$(document).ready(function() {
	 
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

	  function setLayerId(){
		  var id = manualuploader.getUploads()[0].uuid;
		  $('#user_kml_ids').val(id);
		  alert('<?php echo Kohana::lang('uploadlayers.success') ?>');
		  $("a[rel]").overlay().close();	  
	  }

	
</script>