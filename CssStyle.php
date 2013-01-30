<?php



class CssStyle extends PluginObject {

	function __construct( $asset ) {
		$this->css = new Asset( $asset['css']['url'], $asset['css']['path'], $asset['css']['hash'] );
	}

	public function output() {
		wp_enqueue_style( "compiled-less", $this->css->url, false, null, "all" );
	}


}






?>