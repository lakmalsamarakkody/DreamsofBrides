<?php

class Document {


	// REGISTRY
	private $_registry = NULL;


	// PROPERTIES
	private $_styles = array();
	private $_scripts = array();



	// CONSTRUCT
	public function __construct($Registry) {
		$this->_registry = $Registry;
	}



	/**
	 *
	 * @param String $file
	 *
	 */
	final public function add_style($file = '') {
		if ( isset($file) AND !empty($file) ):
			if ( file_exists( ABS_PATH.'/themes/'.$this->_registry->get('config')->get('app_theme').'/assets/css/'.$file ) ):
				( !is_array(isset($this->_styles)) ) ? $this->_styles = array() : null;
				array_push($this->_styles, $this->_registry->get('config')->get('base_url').'/themes/'.$this->_registry->get('config')->get('app_theme').'/assets/css/'.$file);
			endif;
		endif;
	}



	/**
	 *
	 * @param String $file
	 *
	 */
	final public function add_script($file = '') {
		if ( isset($file) AND !empty($file) ):
			if ( file_exists( ABS_PATH.'/themes/'.$this->_registry->get('config')->get('app_theme').'/assets/js/'.$file ) ):
				( !is_array(isset($this->_scripts)) ) ? $this->_scripts = array() : null;
				array_push($this->_scripts, $this->_registry->get('config')->get('base_url').'/themes/'.$this->_registry->get('config')->get('app_theme').'/assets/js/'.$file);
			endif;
		endif;
	}



	/**
	 *
	 * @return array()
	 *
	 */
	final public function get_styles() {
		return $this->_styles;
	}



	/**
	 *
	 * @return array()
	 *
	 */
	final public function get_scripts() {
		return $this->_scripts;
	}
}

?>