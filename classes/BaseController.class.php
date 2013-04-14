<?php
abstract class BaseController {
	protected $postValues;
	protected $urlValues;
	protected $action;
	protected $view;

	/*
	 * __construct(string $action, array $urlvalues, array $postvalues)
	 * This will setup the controller so it's easy to use/program
	 * Action will be the page in the controller we are accessing.
	 * urlvalues will be $_GET values.
	 * postvalues will be $_POST values.
	 * We use variables to store get/post values so our program can work on more server setups.
	 *
	 * Also this is where startup scripts go.
	*/
	public function __construct($action, $urlvalues, $postvalues){
		/* Variables and such for our page to use */
		$this->action = $action;
		$this->posts = $postvalues;
		$this->gets = $urlvalues;
		$this->view = new View();
		$this->view->javascript = ''; // So our controllers can inject javascript
		$this->settings = array(); // Sitewide settings available to all controllers.

		/* Basic startup scripts for the page */
		if(!isset($_SESSION)){
			session_start(); // Start our session if not done already
		}

		$settings = new SiteSettingsModel();
		$this->view->site_settings = $settings->getAll(true); // Load all settings into the view (so the template can also use it)
	}

	public function executeAction(){
		return $this->{$this->action}();
	}

	/*
	 * displayView(boolean $ajax, string $action_name)
	 * Displays the view. When $action_name is -1, the default view will be loaded (classname + actionname).
	 * When ajax is true, the theme is not included with the view - good for AJAX requests or pages inside of other pages
	 * WARNING: This scripts dies in the end. It is called when all controller and model actions are done, and it's time for the view to be displayed.
	*/
	protected function displayView($ajax=false, $action_name=-1){
		$action_name = ($action_name == -1) ? $this->action : $action_name;
		$viewloc = _ROOT . '/views/' . get_class($this) . '/' . $action_name . '.php';
		if(file_exists($viewloc)){

			ob_start();
			$view = $this->view; // Copy our view object locally, so the views can use it.
			require $viewloc;
			if(isset($this->view->javascript)){
				echo "<script>" . $this->view->javascript . "</script>"; // Insert our controller-defined javascript at the END of the page so dom elements can be accessed
			}
			$view->_contents = ob_get_clean();

			if($ajax){
				echo $view->_contents;
			} else {
				require _ROOT . '/views/template.php';
			}
			die();
		} else {
			return new Error('Error: View (' . $viewloc . ') is missing');
		}
	}
}
