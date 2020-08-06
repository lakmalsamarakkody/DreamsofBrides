<?php


class App {

	// APP PROPERTIES
	protected $_registry = NULL;
	protected $_route = NULL;
	protected $_controller = array( 'FILE' => 'Home.php', 'INSTANCE' => 'Home' );
	protected $_method = 'index';
	protected $_params = [];



	function __construct($Registry) {

		// REGISTRY
		$this->_registry = $Registry;

		// PARSE ROUTE
		$this->parseRoute();

		// CONFIGURE CONTROLLER ( ORDER : 1 )
		self::configureController();

		// CONFIGURE METHOD ( ORDER : 2 )
		self::configureMethod();

		// CONFIGURE PARAMS ( ORDER : 3 )
		self::configureParams();

		// CALL TO THE METHOD OF THE CONTROLLER
		call_user_func_array( [$this->_controller, $this->_method], $this->_params );

	}



	// PARSE ROUTE URL
	private function parseRoute() {

		// CHECK IF THE ROUTE IS SET
		if ( isset($_GET['route']) ):

			// EXPLODE URL
			$this->_route = explode('/', filter_var(trim($_GET['route'], '/'), FILTER_SANITIZE_URL));

			// IF ( - ) PRESENT IN URL
			if ( strpos($this->_route[0], "-") !== false ):
				$this->_route[0] = str_replace("-", " ", $this->_route[0]);
				$this->_route[0] = ucwords($this->_route[0]);
				$this->_route[0] = str_replace(" ", "", $this->_route[0]);
			else:
				$this->_route[0] = ucfirst($this->_route[0]);
			endif;

		endif;

	}



	private function configureController() {

		// DETECT FILE ( $this->_controller )
		if ( isset($this->_route[0]) ):

			if ( is_dir( ABS_PATH.'/app/controllers/'.strtolower($this->_route[0]) ) ):
				if ( isset($this->_route[1]) ):
					if ( file_exists( ABS_PATH.'/app/controllers/'.strtolower($this->_route[0]).'/'.ucfirst(strtolower($this->_route[1])).'.php' ) ):

						// SET CONTROLLER ( FILE / INSTANCE )
						$this->_controller['FILE'] = strtolower($this->_route[0]).'/'.ucfirst(strtolower($this->_route[1])).'.php';
						$this->_controller['INSTANCE'] = $this->_route[0].'_'.ucfirst(strtolower($this->_route[1]));

						// UNSET KEYS ( $this->_route )
						unset($this->_route[0]);
						unset($this->_route[1]);

					endif;
				endif;
			elseif ( file_exists( ABS_PATH.'/app/controllers/'.$this->_route[0].'.php' ) ):

				// SET CONTROLLER ( FILE / INSTANCE )
				$this->_controller['FILE'] = $this->_route[0].'.php';
				$this->_controller['INSTANCE'] = $this->_route[0];

				// UNSET KEYS ( $this->_route )
				unset($this->_route[0]);

			endif;

		endif;

		// LOAD CONTROLLER
		require_once ABS_PATH.'/app/controllers/'.$this->_controller['FILE'];

		// CONTROLLER INSTANCE
		$this->_controller = new $this->_controller['INSTANCE']($this->_registry);

		// RESET KEYS ( $this->_route )
		$this->_route = $this->_route ? array_values($this->_route) : [];

	}



	private function configureMethod() {

		// IF METHOD DEFINED
		if ( isset($this->_route[0]) ):
			if ( method_exists($this->_controller, $this->_route[0]) ):
				$this->_method = $this->_route[0];
				unset($this->_route[0]);
			endif;
		endif;

		// RESET KEYS ( $this->_route )
		$this->_route = $this->_route ? array_values($this->_route) : [];

	}



	private function configureParams() {

		// IF PARAMS DEFINED
		if ( isset($this->_route[0]) ):
			$this->_params = $this->_route;
		endif;

	}
}


?>