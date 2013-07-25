<?php



class CssStyle extends PluginObject {

	function __construct( $asset ) {
		$this->css = new Asset( $asset['css']['url'], $asset['css']['path'], $asset['css']['hash'] );
	}

	public function output() {
		$version = self::_get_wp_option( "css-version" );
		wp_enqueue_style( "compiled-less", $this->css->url, false, $version, "all" );
	}


}






?>