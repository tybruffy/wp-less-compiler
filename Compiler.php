<?php
//	TODO: Performance audit RE: concatenation of CSS in Compiler#compile.


/**
 * Create a new Compiler.  Requires a style object on construct. 
 * Then call compile.  Returns true if successfully written
 * to a file, false if there is an unknown error.  Throws an exception
 * if a known error occurred.
 */

class Compiler extends PluginObject {

	private $compiled;
	private $style;
	private $css_text;
	private $less;
	private $css;
	private $css_comment;

	function __construct( $style, $css_text ) {
		$this->style        = $style;
		$this->css_text     = stripslashes($css_text);
		$this->less         = $this->style->less;
		$this->css          = $this->style->css;
		$this->css_comment  = "/*\r\nCSS compiled from the file: ".$this->less->url. "\r\n*/\r\n"; 
	}




	public function is_compiled() {
		return $this->compiled;
	}


	public function compile() {
		$css_string = $this->_minify_css( $this->css_text );
		$css_string = $this->_add_css_comment( $this->css_comment, $css_string );
		return $this->_write_to_file( $css_string, $this->css->path );
	}




	private function _minify_css( $css_string ) {
		require_once( "lib/cssmin/cssmin-v3.0.1.php" );
		return CssMin::minify($css_string);
	}


	private function _write_to_file( $text, $file_name ) {
		$file = fopen( $file_name , "w" );
		if ( ! fwrite( $file, $text ) ) {
			throw new Exception("Could not write to CSS file.");
		}
		fclose( $file );
		$this->compiled = true;
	}


	private function _add_css_comment( $comment, $css ) {
		return $comment.$css;
	}

}

?>