jQuery(document).ready(function($) {
	// Customized version found at http://www.webmaster-source.com/2013/02/06/using-the-wordpress-3-5-media-uploader-in-your-plugin-or-theme/ 
	var bgnp_custom_uploader;
	$('.bgnp_upload_image_button').click(function(e) {
 
        	e.preventDefault();
 
        	//If the uploader object has already been created, reopen the dialog
        	if (bgnp_custom_uploader) {
            	bgnp_custom_uploader.open();
            		return;
        	}
 
        	//Extend the wp.media object
        	bgnp_custom_uploader = wp.media.frames.file_frame = wp.media({
	            	title: 'Choose Image',
	            	button: {
	                	text: 'Choose Image'
	            	},
            		multiple: false
        	});
 
        	//When a file is selected, grab the URL and set value
        	bgnp_custom_uploader.on('select', function() {
        		attachment = bgnp_custom_uploader.state().get('selection').first().toJSON();
            		$('#upload_image').val(attachment.url);
        	});
 
        	//Open the uploader dialog
        	bgnp_custom_uploader.open();
    	});
});