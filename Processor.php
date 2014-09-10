<?php

class Processor extends PluginObject {

	public $type;
	public $style;
	public $updated_style;
	public $message = array();

	private $request_array;

	function __construct( $style ) {

		$this->request_array = $_POST;
		$this->style         = $style;
		$this->type          = isset( $this->request_array['request'] ) ? $this->request_array['request'] : null;

		if ( isset( $this->type ) ) {
			$this->message["placement"] = $this->type;
			$this->{"_init_".$this->type}();
			$this->updated_style = true;
		}
	}


	private function _init_compile() {
		require_once( "Compiler.php" );

		$compress = isset($this->request_array["compress"]) ? $this->request_array["compress"] : false;

		$this->compiler = new Compiler( $this->style, $this->request_array["css_text"], $compress);
		try {
			$this->compiler->compile();
		} catch (Exception $e) {
			$this->message["text"] = "<strong>Error:</strong> Compile failed: ".$e->getMessage();
			$this->message["type"] = "error";
		}

		if ( $this->compiler->is_compiled() ) {
			$this->message["text"] = "CSS compiled successfully.";
			$this->message["type"] = "updated";
			$this->_save_css_version();
			$this->_save_new_style_hash();
		}
	}

	private function _save_css_version() {
		self::_set_wp_option("css-version", time());
	}

	private function _save_new_style_hash() {
		$style = self::_get_wp_option("asset");
		$this->style->css->hash = $this->style->css->generate_hash();
		$style["css"]["hash"]   = $this->style->css->hash;
		self::_set_wp_option( "asset", $style );
	}

	private function _init_save() {
		$this->style = new SettingsStyle( $this->request_array );
		$this->_set_frontend_views( $this->request_array['view'] );
		$this->_save();
	}

	private function _save() {
		self::_set_wp_option( "asset", $this->style->become_array() );
		self::_set_wp_option( "public-view", $this->public_view );
		self::_set_wp_option( "admin-view", $this->admin_view );

		$this->message["text"] = "Settings saved successfully.";
		$this->message["type"] = "updated";
	}

	private function _set_frontend_views( $view_type ) {
		switch ( $view_type ) {
			case "css":
			case "less":
				$this->admin_view  = $view_type;
				$this->public_view = $view_type;
				break;
			case "variable":
				$this->admin_view  = "less";
				$this->public_view = "css";
				break;
			default:
				$this->admin_view  = "css";
				$this->public_view = "css";
		}
	}

}

?>