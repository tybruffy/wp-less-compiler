jQuery(document).ready(function($) { 

	$(".file-browser")
		.click(function() {

			$.serverBrowser({
				ajax_url     : fb_url,
				restrict_to  : $(this).data("path"),
				root_path    : $(this).data("path"),
				root_url     : $(this).data("url"),
				return_ids   : {
					path : $(this).data("linked-path-field"),
					url  : $(this).data("linked-url-field")
				}
			});

		});	
});