=== WP Less Compiler ===
Contributors: The Jake Group
Donate link: http://www.thejakegroup.com/
Tags: Less, CSS, Less Compiler, CSS Preprocessor
Requires at least: 3.5.0
Tested up to: 3.9.2
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Compile LESS to CSS on the fly, or generate a dedicated CSS file from LESS to serve to your visitors.

== Description ==

This plugin has everything you need to run LESS in development and convert it to CSS for production.

Have an issue? This plugin is a work in progress, and we'd love your feedback.  The official plugin repository is hosted on [GitHub](https://github.com/the-jake-group/wp-less-compiler).  Look here for the most up to date development versions.  You can also use the [issue tracker](https://github.com/the-jake-group/wp-less-compiler/issues) to let us know if you have any problems or suggestions.

== Features ==

* Display CSS to visitors while showing LESS to administrators
* Compile and serve a CSS file generated from LESS
* Develop in LESS right in the browser

== Installation ==

1. Download the plugin and unzip it.
1. Upload `wp-less-compiler` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Go to Settings > Less Compiler and add a LESS File and a CSS File, save your settings.

== Frequently Asked Questions ==

= Can I use a custom version of LESS =

Currently there is no way to do this that would not be overwritten by future versions of the plugin,
but expect it in a future release.  

If you MUST do this, here's how:

1. Download your preferred version of Less.js and save it as less-{version-suffix}.js.  For example the minified version of 1.7.0 would be named less-1.7.0.min.js
2. Upload that file to plugins/wp-less-compiler/assets/js/
3. Open plugins/wp-less-compiler/wp-less-compiler.php and change the `LESS_JS_VER` constant to the version-suffix you used earlier.

Remember that this will be overwritten when the plugin updates, but a persistent way to do this is coming soon.

== Changelog ==

= 1.3.0 =
* Added CSS Compression as an option instead of as the default.

= 1.2.1 =
* Prevented errors on the front end if you activate the plugin but don't configure it.

= 1.2.0 =
* Less version updated.
* Added ability to override database output settings with an environment variable.

= 1.1.0 =
* Bug fixes and Less version update.

= 1.0.0 =
* First release.
