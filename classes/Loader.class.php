<?php
/*
 * Loader.class.php
 * The loader class is used to create webpages
*/
class Loader {
	private $controller;
	private $action;
	private $urlValues;
	private $postValues;

	/* 
	 * Store our URL request and identify controller and action.
	 * If controller is not defined, it will be home.
	 * If action is not defined, it will be index.
	*/
	public function __construct($request, $posts){
		// Set up autoloading
		spl_autoload_register("self::autoload");
		
		// Setup post/get scripts. This is stored in a variable so our scripts are still useable on a server... or something. it's just good design.
		$this->urlValues = $request;
		$this->postValues = $posts;
		
		// Properly setup our controller/action
		if(empty($this->urlValues['controller'])){ // Gonna assume the user is trying to go to the homepage
			$this->controller = "Home";
			$this->action = "index";
		} else {
			$this->controller = $this->urlValues['controller'];
		}
		$this->action = !empty($this->urlValues['action']) ? $this->urlValues['action'] : "index";

		// Create our page!
		$controller = $this->createController();
		$controller->executeAction();
	}

	/*
	 * Create and return the controller used for the request.
	*/
	public function createController(){
		if(file_exists(_ROOT . '/controllers/' . $this->controller . '.class.php')){ // Does the controller exist?
			require _ROOT . '/controllers/' . $this->controller . '.class.php'; // Include it
			if(class_exists($this->controller)){ // Does the correct class exist?
				$parents = class_parents($this->controller);
				if(in_array("BaseController", $parents)){ // Does it extend the correct class?
					if(method_exists($this->controller, $this->action) || method_exists($this->controller, '__call')){ // does the controller have the requested action? Maybe a _call for dynamic actions?
						return new $this->controller($this->action, $this->urlValues, $this->postValues); // Yes! Make the controller and pass on the action, GET variables, and POST variables
					}
				}
			}
		}
		var_dump($this->urlValues);
		return new Error("badUrl", $this->urlValues);
	}


	/*
	 * public autoload($name)
	 *
	 * Params:
	 	string $name: autoload is called when an undefined class is attempted to be created. $name is that class name.
	 *
	 * Warning:
	 	This does not autoload controllers, as for controllers should be manually loaded through the Loader class.
	 *
	 * Returns:
	 	Class $name if $name is found
	*/
	public function autoload($name){
		$files = array(
			_ROOT . '/classes/' . $name . '.class.php',
			_ROOT . '/models/' . $name . '.class.php'
		);

		foreach($files as $file){
			if(file_exists($file)){
				include $file;
			}
		}
	}
}