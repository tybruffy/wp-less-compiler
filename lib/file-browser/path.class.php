<?php

class Path {
	public $path_root;
	public $path;
	public $type;
	public $name;
	public $url;

	function __construct( $path, $web_root, $path_root = null ) {
		$path_root = ( !empty( $path_root ) ) ? $path_root : $path;

		$this->path_root = $path_root;
		$this->path      = realpath( $path );
		$this->type      = ($this->is_directory( $path )) ? "directory" : "file";
		$this->name      = $path;
		$this->url       = $this->_get_url( $path, $path_root, $web_root );
	}

	public function is_directory() {
		return is_dir( $this->path );
	}

	public function is_readable() {
		return (is_readable( $this->path ));
	}

	public function is_hidden_file() {
		return ( preg_match("/^\./", $this->name) && $this->name != ".." ) ? true : false; 
	}

	private function _get_url( $path, $path_root, $web_root ) {
		return $web_root.'/'.$this->name;
	}



}

?>