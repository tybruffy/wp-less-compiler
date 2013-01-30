<?php

require_once( "path.class.php" );

class File_Browser {
	private $restrict_to = null;
	private $top_level;
	private $current_dir;
	private $web_root;
	private $list;
	private $filtered_list;
	private $errors;


	function __construct() {
		$path               = $this->_get_start_path( $_REQUEST );
		$this->web_root     = $this->_get_web_root( $_REQUEST );
		$this->restrict_to  = $this->_restrict_to( $_REQUEST );
		$this->current_dir  = new Path( $path, $this->web_root );

		if ( $this->_can_read_directory( $this->current_dir ) ) {
			$this->_create_list();
			$this->filtered_list = $this->_filter_list( $this->list );
			$this->json = $this->_get_json( $this->filtered_list );
		}

		$this->return_data();
	}


	public function return_data() {
		if ( empty($this->errors) ) {
			header('Content-Type: application/json');
			echo $this->json;
		} else {
			header('HTTP/1.1 500 Internal Server Error');
			header('Content-Type: application/json');
			die( json_encode( $this->errors ) );
		}
	}


	private function _filter_list( $list ) {
		foreach( $list as $index => $path ) {

			if ( $path->is_hidden_file() || $this->_is_restricted( $path ) ) {
				unset( $list[$index] );
			}
		}
		return $list;
	}

	private function _get_start_path( $array ) {
		return ( array_key_exists("path", $array) ) ? $array["path"] : dirname( __FILE__ );
	}

	private function _get_web_root( $array ) {
		$current_url =  $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		$root_url =  str_replace( basename( $_SERVER['PHP_SELF'] ), "", $current_url );
		return ( array_key_exists("web_root", $array) ) ? $array["web_root"] : $root_url;
	}

	private function _restrict_to( $array ) {
		return ( !empty($array["restrict_to"]) ) ? realpath($array["restrict_to"]) : null;
	}

	private function _is_restricted( $path ) {
		return ( !empty($this->restrict_to) && !is_numeric(strpos($path->path, $this->restrict_to)) ) ? true : false;
	}

	

	private function _can_read_directory( $path ) {
		if ( ! $path->is_directory() ) {
			$this->errors[] = "Directory is not readable.";
		} elseif ( !$path->is_readable() ) {
			$this->errors[] = "Specified path is not a directory.";
		}
		return (empty( $this->errors ));
	}

	private function _create_list() {
		chdir( $this->current_dir->path );
		if ( $handle = opendir('.') ) {
			while (( $item = readdir( $handle ) ) !== false) {
				$this->list[] = new Path( $item, $this->web_root, $this->current_dir->path );;
			}
			closedir( $handle );
		}
	}

	private function _get_json( $var ) {

		$json = json_encode( $var );
		if ( !$json ) {
			$this->errors[] = "JSON could not be encoded.";
		}
		return $json;
	}

}

new File_Browser();

?>