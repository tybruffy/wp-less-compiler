<?php



class SettingsStyle extends PluginObject {

	public $css;
	public $less;
	public $admin_view;
	public $public_view;
	public $view_type;

	function __construct( $style ) {
		if ( is_object($style) ) {
			//I think this is never true, at this point
			$info = $this->_create_from_object( $style );
		} else {
			$info = $this->_create_from_array( $style );
		}

		$this->css  = new Asset( $info["css"]["url"], $info["css"]["path"], $info["css"]["hash"] );
		$this->less = new Asset( $info["less"]["url"], $info["less"]["path"] );
	}




	public function output( $message ) {
		$view_type = ($this->public_view == $this->admin_view) ? $this->public_view : "variable";

		$this->html = new SettingsHtml( $this->css, $this->less, $view_type, $message );
		$this->html->output_page_styles();
		$this->html->display();
	}

	public function become_array() {

		$css["url"]   = isset($this->css->url) ? $this->css->url : null;
		$css["path"]  = isset($this->css->path) ? $this->css->relative_path() : null;
		$css["hash"]  = isset($this->css->hash) ? $this->css->hash : null;
	
		$less["url"]  = isset($this->less->url) ? $this->less->url : null;
		$less["path"] = isset($this->less->path) ? $this->less->relative_path() : null;

		return array( "css" => $css, "less" => $less );
	}


	private function _create_from_object( $object ) {
		$css["url"]   = isset($asset->css->url) ? $asset->css->url : null;
		$css["path"]  = isset($asset->css->path) ? $asset->css->path : null;
		$css["hash"]  = isset($asset->css->hash) ? $asset->css->hash : null;
	
		$less["url"]  = isset($asset->less->url) ? $asset->less->url : null;
		$less["path"] = isset($asset->less->path) ? $asset->less->path : null;

		return array( "css" => $css, "less" => $less );
	}

	private function _create_from_array( $array ) {

		$css["url"]   = isset($array["css"]["url"]) ? $array["css"]["url"] : null;
		$css["path"]  = isset($array["css"]["path"]) ? $array["css"]["path"] : null;
		$css["hash"]  = isset($array["css"]["hash"]) ? $array["css"]["hash"] : null;
	
		$less["url"]  = isset($array["less"]["url"]) ? $array["less"]["url"] : null;
		$less["path"] = isset($array["less"]["path"]) ? $array["less"]["path"] : null;

		return array( "css" => $css, "less" => $less );
	}

}





?>