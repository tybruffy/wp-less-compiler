jQuery(document).ready(function($) { 

	$(".file-browser").click(function() {
		$.serverBrowser({
			ajax_url     : plugin_url+"/lib/ajax-file-browser/browser.class.php",
			restrict_to  : $(this).data("path"),
			root_path    : $(this).data("path"),
			root_url     : $(this).data("url"),
			return_ids   : {
				path : $(this).data("linked-path-field"),
				url  : $(this).data("linked-url-field")
			}
		});
	});	

	$(".compiler-form").submit(function() {
		var $wrap = $('<div class="test"></div>')
			,	$less = $("#less_url")
			,	$file = $("#less_file")
			,	$css  = $("#css_file")

		destroyLessCache($less.val());

		$link = $("<link>")
			.attr("rel", "stylesheet/less")
			.attr("type", "text/css")
			.attr("href", $less.val())

		$script = $("<script></script>")
			.attr("type", "text/javascript")
			.attr("src", plugin_url+"/assets/js/less-"+less_ver+".js")

		$wrap.append($link)
		$wrap.appendTo("body")
		$wrap.append($script)

		$style = $('style[id*="less"]')
		css = $style.html()
		$style.remove();

		$input = $("<input>")
			.attr("type", "hidden")
			.attr("name", "css_text")
			.val( css );

		$(this).append($input);

		return true;
	});

});