<?php


/**
 * Creates an asset to attach to a style object.  Checks
 * if that file differs from when it was originally saved
 * in the database.
 */

class Asset extends PluginObject {

	public $url;
	public $input_path;
	public $path;
	public $hash;

	public static $hash_type = "md5";
	

	function __construct( $url, $path, $hash = null ) {
		self::plugin_info();

		$this->url  = $url;
		$this->input_path = $path;
		$this->_set_path();
		$this->hash = ( empty($hash) ) ? $this->generate_hash() : $hash;
	}




	public function file_has_changed() {
		return ( $this->hash !== $this->generate_hash() && ! is_null($this->hash) ) ? true : false;
	}

	public function generate_hash() {
		if ( !empty($this->path) ) {
			return hash_file( self::$hash_type, $this->path );
		}
	}
	
	public function relative_path() {
		$sanitized = str_replace("\\\\", "\\", $this->input_path);
		$sanitized = $this->_fix_slashes( $sanitized );
		$template  = $this->_get_template_dir();
		return str_replace( $template, "", $sanitized );
	}




	private function _set_path() {
		$relative   = $this->relative_path();
		$template   = $this->_get_template_dir();
		$this->path = $template.$relative;
	}

	private function _get_template_dir() {
		return $this->_fix_slashes( self::$template_path );
	}

	private function _fix_slashes( $string ) {
		return preg_replace('/[\\\\\/]/', DS,  $string);
	}

}

