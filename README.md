#Wordpress Less Compiler 1.3

##About

This plugin let's you develop using less and switch between using less.js to create the styles and a compiled CSS file.

The plugin hasa  built in less compiler that will use less.js to write and minify the compiled less to a static CSS file.  It also handles the output of both less and the css, so there's no need to enqueue any files yourself.

##Environment Overrides
As of version 1.2 you have the abliity to use an environment variable to override the databse output settings.  This also allows for slightly more granular control.  Example:

```php
$_ENV["less-compiler"] = array(
  "public" => "css",
  "admin"  => "less",
);
```
This will ensure that on this server, non admin users will always see the CSS file instead of LESS, and admin users will always see the LESS file.  This is useful if you are migrating a database between dev and production servers and want to make sure you aren't accidentally serving LESS.

##About the Compiler
In an effort to ensure that there are no discrepancies between the LESS data as converted by the LESS javascript and the compiled CSS, the compiler now uses the LESS Javascript to compile.  Previoulsy it used a LESS PHP library, but this did not allow for custom versions of LESS, and often fell behind the latest version, as well as presented inconsistencies in parsing.

The tradeoff of the latest version is that the compiler will occassionally compile from cached LESS files.  Chrome is particularly prone to storing LESS data in window.localstorage.  running desrtoy_less ( less_url ); from the console and doing a hard refresh is usually enough to get the newest LESS data.

##Uninstalling
When uninstalled the plugin will remove fields from the wp_options table that it uses to store your settings.  It will NOT delete your less files or your compiled CSS file.  You will however have to manually enqueue the files after uninstalling.

