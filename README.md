#Wordpress Less Compiler

####Version 1.1

##About

This plugin let's you develop using less and switch between using less.js to create the styles and a compiled CSS file.

The plugin hasa  built in less compiler that will use less.js to write and minify the compiled less to a static CSS file.  It also handles the output of both less and the css, so there's no need to enqueue any files yourself.

##Uninstalling
When uninstalled the plugin will remove fields from the wp_options table that it uses to store your settings.  It will NOT delete your less files or your compiled CSS file.  You will however have to manually enqueue the files after uninstalling.

