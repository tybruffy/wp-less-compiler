<?php

class SettingsHtml extends PluginObject {
	public $compiler;
	
	private $style;
	private $plugin_info;
	private $less_error;
	private $form_data;
	private $request_type;
	
	private $modifed_msg = "<strong>Warning:</strong> CSS file has been modified since it was last compiled.";
	private $override_msg = "";
	private $compile_msg = "";
	private $save_msg    = "";




	function __construct( $css, $less, $view_type, $message ) {
		self::plugin_info();

		$this->css       = $css;
		$this->less      = $less;
		$this->view_type = $view_type;

		$this->_set_messages( $message );
	}




	public function output_page_styles() {
		wp_enqueue_style( 'wlc_styles', self::$plugin_url . "/assets/css/base.css" );
		wp_enqueue_script( 'destroy-less', self::$plugin_url . "/assets/js/destroy-less.js", array("jquery") );
		wp_enqueue_script( 'plugin_js', self::$plugin_url . "/assets/js/plugin.js", array("jquery") );
		wp_enqueue_script( 'fb_js', self::$plugin_url . "/lib/ajax-file-browser/browser.js", array("jquery") );
		wp_enqueue_style( 'fb_css', self::$plugin_url . "/lib/ajax-file-browser/file-browser.css" );
		echo sprintf('<script type="text/javascript">var plugin_url = "%s"; less_ver = "%s"</script>', self::$plugin_url, self::LESS_JS_VER );
	}

	public function display() {
		?>
		<div id="icon-options-general" class="icon32"><br></div>
		<h2 class="nav-tab-wrapper">
			LESS Compiler
		</h2>

		<div class="wrap" id="tjg-admin-theme">
			
			<form method="post" enctype="multipart/form-data" class="padded-form bordered-form options-form">
				<h1>Options</h1>
				<input type="hidden" name="request" value="save">
			
				<div class="form-row">
					<div class="form12">
						<label for="less-path">Less Input File</label>
						<input type="text" name="less[url]" id="less_url" value="<?php echo $this->less->url; ?>" readonly="readonly" />
						<input type="hidden" name="less[path]" id="less_file" value="<?php echo $this->less->path; ?>">
					</div>
					<div class="form4 no-label">
						<a class="file-browser button centered-label" href="#" data-path="<?php echo self::$template_path; ?>" data-url="<?php echo self::$template_url; ?>" data-linked-url-field="#less_url" data-linked-path-field="#less_file">Browse for File</a>
					</div>
				</div>

				<div class="form-row">
					<div class="form12">
						<label for="css-path">CSS Output File</label>
						<input type="text" name="css[url]" id="css_url" value="<?php echo $this->css->url; ?>" readonly="readonly" />
						<input type="hidden" name="css[path]" id="css_file" value="<?php echo $this->css->path; ?>">
					</div>
					<div class="form4 no-label">		
						<a class="file-browser button centered-label" href="#" data-path="<?php echo self::$template_path; ?>" data-url="<?php echo self::$template_url; ?>" data-linked-url-field="#css_url" data-linked-path-field="#css_file">Browse for File</a>
					</div>
				</div>

				<div class="form-row">
					<div class="form4">
						<label for="view-css" class="checkbox-label centered-label stacked">
							<input type="radio" name="view" value="css" id="view-css" <?php echo $this->_input_check($this->view_type, "css", "checked");?>>
							Disable Less
						</label>
						<label for="view-less" class="checkbox-label centered-label stacked">
							<input type="radio" name="view" value="less" id="view-less" <?php echo $this->_input_check($this->view_type, "less", "checked");?>>
							Enable Less
						</label>
						<label for="view-variable" class="checkbox-label centered-label stacked">
							<input type="radio" name="view" value="variable" id="view-variable" <?php echo $this->_input_check($this->view_type, "variable", "checked");?>>
							Enable Less for Administrators
						</label>
					</div>
					<?php echo $this->override_msg; ?>
				</div>

				<?php echo $this->save_msg; ?>

				<div class="form-row">
					<div class="form12">
						<input type="submit" value="Save Options" />
					</div>
				</div>
			</form>

			<form method="post" enctype="multipart/form-data" class="padded-form bordered-form compiler-form">
				<h1>Compile Less</h1>
							
				<?php echo $this->compile_msg; ?>
				<div class="form-row">
					<div class="form4">
						<label for="compress" class="checkbox-label centered-label stacked">
							<input type="checkbox" name="compress" value="1" id="compress">
							Compress CSS
						</label>
					</div>
					<?php echo $this->override_msg; ?>
				</div>
				<div class="form-row">
					<input type="hidden" name="request" value="compile">
					<input type="submit" value="Compile Less File" />
				</div>
				<div class="form-row">
					<small><em><strong>Please Note:</strong> This plugin uses the less.js browser version to convert the less to css before submitting this form.  After clicking Compile, the form may hang for a few seconds while this conversion takes place.</em></small>
				</div>				
			</form>
		</div>
		<?php
	}




	private	function _set_messages( $message ) {
		if ( $this->css->file_has_changed() ) {
			$this->compile_msg = $this->_get_msg_html( "error", $this->modifed_msg );
		}

		if ( !empty($message) ) {
			$this->{$message["placement"].'_msg'} = $this->_get_msg_html( $message["type"], $message["text"]);
		}

		if ( isset( $_ENV["less-compiler"]["public"] ) || isset( $_ENV["less-compiler"]["admin"] ) ) {
			$this->override_msg = $this->_get_msg_html( "", sprintf(
				'<div class="form8 update-nag">
					<strong>Environment variable override enabled. Changes will not take effect.</strong><br /><br />
					<strong>Public:</strong> %s<br />
					<strong>Admin:</strong> %s
				</div>
				',
				$_ENV["less-compiler"]["public"] ? strtoupper($_ENV["less-compiler"]["public"]) : "Not Set",
				$_ENV["less-compiler"]["admin"] ? strtoupper($_ENV["less-compiler"]["admin"]) : "Not Set"
			));
		}
	}


	private function _get_msg_html( $class, $message ) {
		return sprintf('<div class="%s"><p>%s</p></div>', $class, $message );
	}


	private function _input_check( $var, $value, $type ) {
		if ( $var == $value ) { 
			return $type.'="'.$type.'"'; 
		}
	}

}

?>