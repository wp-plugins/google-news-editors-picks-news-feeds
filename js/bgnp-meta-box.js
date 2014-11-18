jQuery(document).ready(function () {
	// qTip function for Premium Google News Plugin meta box help, including text instruction and links.
	jQuery(".bgnp_meta_help[alt]").qtip({
		content : {
        		attr: 'alt'
    		},
		position: {
			corner: {
				target : 'topMiddle',
				tooltip: 'bottomLeft'
			}
		},
		show    : {
			when: {
				event: 'mouseover'
			}
		},
		hide    : {
			fixed: true,
			when: {
				event: 'mouseout'
			}
		},
		style   : {
			tip: 'bottomLeft',
			name: 'cream'
		}
	});
	
	//Premium Google News Plugin custom RSS meta description counter 
	jQuery("#bgnp_rss_meta").after("<p style=\"text-align:left;\"><normal>Enter meta description for RSS feed, 156 character limit.</normal><input type=\"text\" value=\"156\" maxlength=\"156\" size=\"3\" id=\"bgnp_rss_meta_counter\" readonly=\"\"> <normal>character(s).</normal></p>");
    	jQuery("#bgnp_rss_meta_counter").val(jQuery("#bgnp_rss_meta").val().length);
    	jQuery("#bgnp_rss_meta").keyup( function() {
    	jQuery("#bgnp_rss_meta_counter").val(jQuery("#bgnp_rss_meta").val().length);
   	});
});