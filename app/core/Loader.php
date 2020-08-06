<?php

class Loader {

	// PROPERTIES
	protected $_registry = NULL;



	public function __construct($Registry) {
		$this->_registry = $Registry;
	}



	/**
	*
	* @param String $handler
	* @param Array $data
	* @return mixed
	*
	*/
	public function controller($controller, $data = array()) {
		if ( isset($controller) AND !empty($controller) AND $controller != NULL ):
			
			$controller = explode("/", $controller);

			// FILE EXISTS
			if ( file_exists(ABS_PATH.'/app/controllers/'.$controller[0].'/'.ucfirst($controller[1].'.php')) ):

				// INCLUDE CONTROLLER
				include_once ABS_PATH.'/app/controllers/'.$controller[0].'/'.ucfirst($controller[1]).'.php';

				// CREATE CONTROLLER INSTANCE
				$controller = ucfirst($controller[0]).ucfirst($controller[1]);
				$controller = new $controller($this->_registry);

				// RETURN CONTROLLER OUTPUT
				return $controller->index($data);

			endif;

		endif;
	}



	/**
	*
	* @param String $param
	* @return void
	*
	*/
	public function model($param) {
		if ( !$this->_registry->has( 'model_'.str_replace("/", "_", $param) ) ):
			if ( isset($param) AND !empty($param) AND !is_numeric($param) AND $param != NULL ):

				// SANITIZE PARAM
				$param = filter_var($param, FILTER_SANITIZE_STRING);

				if ( file_exists(ABS_PATH.'/app/models/'.$param.".php") ):

					// LOAD MODEL
					include_once ABS_PATH.'/app/models/'.$param.".php";

					// MODEL CLASS
					preg_match("/([^\/]+$)/m", $param, $model);
					$model = ucfirst($model[0]);

					// INIT MODEL & UPDATE REGISTRY
					$this->_registry->set('model_'.str_replace("/", "_", $param), new $model);

				endif;

			endif;
		endif;
	}



	/**
	*
	* @param String $view
	* @param Array $param
	* @return String HTML-DOM
	*
	*/
	public function view($view, $param = array()) {
		if ( isset($view) AND !empty($view) AND $view != NULL AND is_array($param) ):
			if ( file_exists(ABS_PATH.'/themes/'.$this->_registry->get('config')->get('app_theme').'/'.$view.".html") ):

				// DOCUMENT ( $_styles )
				( !is_array(isset($param['styles'])) ) ? $param['styles'] = array() : null;
				foreach ( $this->_registry->get('document')->get_styles() AS $style ):
					array_push( $param['styles'], $style );
				endforeach;

				// DOCUMENT ( $_scripts )
				( !is_array(isset($param['scripts'])) ) ? $param['scripts'] = array() : null;
				foreach ( $this->_registry->get('document')->get_scripts() AS $script ):
					array_push( $param['scripts'], $script );
				endforeach;

				echo $this->_registry->get('twig')->render( $view.'.html', $param );
			endif;
		endif;
	}
}

?>