;(function($){
	$.extend({
		serverBrowser: function( options ) {
			
			var defaults = {
				ajax_url    : '/browser.class.php',
				restrict_to : null,

				success: function( data ) {
					var lists     = methods.sort_data( data );
					var	list_html = methods._get_list_html( lists );

					$(".tb-file-browser").html( list_html );
				},
				error: function( data ) {
					 $.error( 'serverBrowser call returned error: readyState: ' + data.readyState + ', status: '+ data.status +', statusText: "'+ data.statusText +'".  Pass a custom "error" method to the serverBrowser object to override/troubleshoot this message.  Function should accept the AJAX data object as an argument.');
				}
			};

			var settings = $.extend( {}, defaults, options );

			var methods = {
				browse: function( ajax_url, path_root, web_root, restrict_to, success_cb, error_cb ) {
					 return $.ajax({
						url      : ajax_url,
						dataType : "json",
						data     : {
							path        : path_root,
							web_root    : web_root,
							restrict_to : restrict_to
						},

						success: function ( data ) {
							success_cb( data );
						},
						error: function (data) {
							error_cb( data );
						}
					});
				},

				sort_data: function( list ) {
					var obj = { directory: [], file: [] };
					$.each( list, function(index, file) {
						obj[file.type].push( file );
					});

					return { directories: obj["directory"], files: obj["file"] }
				},

				return_data: function ( return_obj, file ) {
					if ( $.isEmptyObject( return_obj ) ) {
						return file;
					} else {
						methods.set_values( return_obj, file );
						return true;
					}
				},

				set_values: function ( return_obj, file ) {
					for ( prop in return_obj ) {
						var field = $( return_obj[prop] );
						if ( field.is("input") ) {
							field.val( file[prop] );
						} else {
							field.text( file[prop] );
						}
					}
				},

				_bind_directory_handler: function( settings, dir_info ) {
					dir_info.path = methods._strip_special_dir( dir_info.path );
					dir_info.url = methods._strip_special_dir( dir_info.url );

					methods.browse( settings.ajax_url, dir_info.path, dir_info.url, settings.restrict_to, settings.success, settings.error );
				},

				_strip_special_dir: function( path ) {
					var regex = new RegExp("[^\/]+\/\.\.\/");
					return path.replace( regex, "" );
				},

				_bind_file_handler: function ( settings, file_info)  {
					methods.return_data( settings.return_ids, file_info );
					$(".tb-fb-overlay").remove();
					$(".tb-file-browser").remove();
				},

				_get_list_items_html: function ( file_obj ) {
					var link = $('<a href="#">')
						.addClass(file_obj.type+'-link')
						.data("file", file_obj)
						.html(file_obj.name)
						.click(function() {
							methods[ '_bind_' +file_obj.type+ '_handler']( settings, $(this).data("file") );
						});

					var list_item = $("<li>")
						.addClass(file_obj.type)
						.html( link )

					return list_item;
				},

				_get_list_html: function ( lists_obj ) {
					var html = $( "<ul>" ).addClass("directory-list");

					$.each( lists_obj.directories, function( index, dir ) {
						dir_html = methods._get_list_items_html( dir );
						dir_html.appendTo( html );
					});

					$.each( lists_obj.files, function( index, file ) {
						dir_html = methods._get_list_items_html( file );
						dir_html.appendTo( html );
					});

					return html;
				},

				init: function () {
					$('<div class="tb-file-browser">')
						.prependTo("body");
					
					$('<div class="tb-fb-overlay">')
						.prependTo("body")
						.click(function() {
							$(this).remove();
							$(".tb-file-browser").remove();			
						});

					methods.browse( settings.ajax_url, settings.root_path, settings.root_url, settings.restrict_to, settings.success, settings.error );
				},

			};

			methods.init();

			return this;

		}
		
	});

})(jQuery);










