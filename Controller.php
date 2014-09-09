<?php

Class Controller extends PluginObject {

	private $style;

	function __construct() {
		require_once( "SettingsStyle.php" );
		require_once( "CssStyle.php" );
		require_once( "LessStyle.php" );
		require_once( "Asset.php" );
		require_once( "Processor.php" );
		require_once( "SettingsHtml.php" );

		add_action( "wp_enqueue_scripts", array($this, "frontend_init") );
		add_action( "admin_menu", array($this, "backend_init") );
	}




	public function frontend_init() {
		global $current_user;
		$view_type = $this->_get_view_type();
		
		if ($view_type && self::_get_wp_option("asset")) {
			$this->_initalize_plugin( $view_type );
		}
	}

	public function backend_init() {
		add_options_page( 'Less Compiler Options', 'Less Compiler', 'manage_options', 'less-compiler-menu', array($this, 'backend_render') );
	}


	public function backend_render() {
		$this->_set_style( "settings" );
		$this->processor = new Processor( $this->style );

		if ( $this->processor->updated_style ) {
			$this->style = $this->processor->style;
		}

		$this->style->public_view = self::_get_wp_option( "public-view" );
		$this->style->admin_view  = self::_get_wp_option( "admin-view" );
		$this->style->output( $this->processor->message );
	}

	public function frontend_render() {
		$this->style->output();
	}


	private function _get_view_type() {
		$user_type = ( current_user_can("manage_options") ) ? "admin" : "public";
		if (isset($_ENV["less-compiler"][$user_type])) {
			return $_ENV["less-compiler"][$user_type];
		} else {
			return self::_get_wp_option( $user_type."-view" );
		}
	}

	private function _initalize_plugin( $view_type ) {
		$this->_set_style( $view_type );
		$this->frontend_render();
	}

	private function _set_style( $view_type ) {
		$asset = self::_get_wp_option( "asset" );
		$style_class = ucfirst($view_type."Style");
		$this->style = new $style_class( $asset );
	}


}

?>