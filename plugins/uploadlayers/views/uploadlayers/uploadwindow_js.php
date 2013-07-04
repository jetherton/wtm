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
<link rel="stylesheet" type="text/css" href="<?php echo URL::base();?>plugins/uploadlayers/media/css/fineuploader.css"/>



<script type="text/javascript">	

var manualuploader = new qq.FineUploader({
    element: $('#manual-fine-uploader')[0],
    request: {
  	  endpoint: "<?php echo url::base();?>parseFiles/submitFiles",
    },
    multiple: false,
    autoUpload: false,
    text: {
      uploadButton: '<i class="icon-plus icon-white"></i> Select File'
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
	    	if(!$('.qq-upload-list').children().length){
				alert('<?php echo Kohana::lang('uploadlayers.listAlert')?>');
			}
	    	else if($('#layer_name').val() == null || $('#layer_name').val() == ''){
		    	alert('<?php echo Kohana::lang('uploadlayers.nameAlert')?>');
		    }
		    else{
				manualuploader.setParams({
					 layer_name : $('#layer_name').val(),
					 layer_color : $('#layer_color').val(),
					 meta_data : $('#meta_data').val()
		    	  });
		      manualuploader.uploadStoredFiles();
		    }
		   
	    });
	  });

	  function setLayerId(){
		  //submit and edit pages have different set up of the divs
		  var url = '<?php echo strpos(url::current(), 'admin/reports/edit') !== false ? 'edit' : 'submit'?>';
		  var id = manualuploader.getUploads()[0].uuid;
		  $('#user_kml_ids').val(id);
		  console.log(url);
		  
		  $.post('<?php echo url::base();?>/parseFiles/getLayerDetails', {'layer' : id}, 
				  function(data){
					  if(url == 'edit'){
						  $('#submit_layers').children('.category-column-2').append('<li\
							        title="'+data.label+'" class="last"><label><input\
							        type="checkbox" name="reportslayers[]" value="'+id+'"\
							        class="check-box layer_switcher" id="layer_'+id+'"><span\
							        class="swatch"\
							        style="background-color:#' + data.color + '"></span><span\
									class="layer-name">'+data.label+'</span></label></li>');
					  }
					  else{
						  $('#custom_forms').append('<ul class="category-column category-column-1 treeview" id="category-column-1"><li\
							        title="'+data.label+'" class="last"><label><input\
							        type="checkbox" name="reportslayers[]" value="'+id+'"\
							        class="check-box layer_switcher" id="layer_'+id+'"><span\
							        class="swatch"\
							        style="background-color:#' + data.color + '"></span><span\
									class="layer-name">'+data.label+'</span></label></li></ul>');
					  }
						  $('#layer_'+id).click();
		  }, 'json');
		  
		  alert('<?php echo Kohana::lang('uploadlayers.success') ?>');
		  $("a[rel]").overlay().close();
	  }

	
</script>