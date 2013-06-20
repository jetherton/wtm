<?php defined('SYSPATH') or die('No direct script access.');
/***********************************************************
* privatefields_js.php - Javascript for PrivateFields Plugin
* This software is copy righted by WatchTheMed 2013
* Writen by Dylan Gillespie, Etherton Technologies <http://ethertontech.com>
* Started on 2013-14-06
* This javascript is to make fields that are labeled as private to not not appear.
*************************************************************/
?>

<script src="<?php echo URL::base();?>/plugins/uploadlayers/media/js/fine-uploader/client/js/header.js"></script>
<script src="<?php echo URL::base();?>/plugins/uploadlayers/media/js/fine-uploader/client/js/util.js"></script>
<script src="<?php echo URL::base();?>/plugins/uploadlayers/media/js/fine-uploader/client/js/version.js"></script>
<script src="<?php echo URL::base();?>/plugins/uploadlayers/media/js/fine-uploader/client/js/features.js"></script>
<script src="<?php echo URL::base();?>/plugins/uploadlayers/media/js/fine-uploader/client/js/promise.js"></script>
<script src="<?php echo URL::base();?>/plugins/uploadlayers/media/js/fine-uploader/client/js/button.js"></script>
<script src="<?php echo URL::base();?>/plugins/uploadlayers/media/js/fine-uploader/client/js/upload-data.js"></script>
<script src="<?php echo URL::base();?>/plugins/uploadlayers/media/js/fine-uploader/client/js/ajax.requester.js"></script>
<script src="<?php echo URL::base();?>/plugins/uploadlayers/media/js/fine-uploader/client/js/deletefile.ajax.requester.js"></script>
<script src="<?php echo URL::base();?>/plugins/uploadlayers/media/js/fine-uploader/client/js/handler.base.js"></script>
<script src="<?php echo URL::base();?>/plugins/uploadlayers/media/js/fine-uploader/client/js/handler.base.js"></script>
<script src="<?php echo URL::base();?>/plugins/uploadlayers/media/js/fine-uploader/client/js/window.receive.message.js"></script>
<script src="<?php echo URL::base();?>/plugins/uploadlayers/media/js/fine-uploader/client/js/handler.form.js"></script>
<script src="<?php echo URL::base();?>/plugins/uploadlayers/media/js/fine-uploader/client/js/handler.xhr.js"></script>
<script src="<?php echo URL::base();?>/plugins/uploadlayers/media/js/fine-uploader/client/js/paste.js"></script>
<script src="<?php echo URL::base();?>/plugins/uploadlayers/media/js/fine-uploader/client/js/dnd.js"></script>
<script src="<?php echo URL::base();?>/plugins/uploadlayers/media/js/fine-uploader/client/js/uploader.basic.js"></script>
<script src="<?php echo URL::base();?>/plugins/uploadlayers/media/js/fine-uploader/client/js/uploader.js"></script>

<link rel="stylesheet" type="text/css" href="<?php echo URL::base();?>/plugins/uploadlayers/media/js/fine-uploader/client/fineuploader.css"/>

<script type="text/javascript">	

$(document).ready(function(){  
	$('.report_left').append('<div class="report_row">\
								<h4>Upload a layer</h4>\
								<div id="upload_button" class="qq-uploader"></div>\
								<div id="upload_trigger">Trigger</div>\
							</div>');

	var errorHandler = function(event, id, fileName, reason) {
        qq.log("id: " + id + ", fileName: " + fileName + ", reason: " + reason);
    };
	var uploader = new qq.FineUploader({
        element: $('#upload_button')[0],
        autoUpload: false,
        uploadButtonText: "Select Files",
        request: {
            endpoint: "<?php echo url::base();?>uploadlayers/parseFiles"
        },
        callbacks: {
            onError: errorHandler
        }
    });
	$('#upload_trigger').click(function() {
        uploader.uploadStoredFiles();
    });
});
	
</script>