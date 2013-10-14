<?php

class LessStyle extends PluginObject {

	function __construct( $asset ) {
		self::plugin_info();
		$this->less = new Asset( $asset['less']['url'], $asset['less']['path'] );
	}

	public function output() {
		echo sprintf('<script type="text/javascript"> var less_url = "%s";</script>', $this->less->url);
		echo sprintf('<link rel="stylesheet/less" type="text/css" href="%s" />', $this->less->url );
		echo '<script type="text/javascript">less = { env: "development", dumpLineNumbers: "mediaquery" };</script>';
		wp_enqueue_script( "destroy-less", self::$plugin_url."/assets/js/destroy-less.js", false, null, false );
		wp_enqueue_script( "less", self::$plugin_url."/assets/js/less-".self::LESS_JS_VER.".js", false, null, false );
	}

}
