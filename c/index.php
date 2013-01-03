<?php
/*
 * page.class.php
 * Used for creating pages, including startup scripts, and creating our $framework object.
 * Also checks to see if the user is logged in. If not, only show the login page.
*/
class page {
	public function __construct(){
		try {
			require_once('inc/startup.php');
			
			$framework = new framework();
			
			$content = ''; // Predefined to prevent Undefined Variable notices
			
			if(!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] == false){
				require "m/login.php";
				require "v/login.php";
				die();
			}
		
			if(isset($_GET['pageofprogram__'])){
				$page = preg_replace("[^A-Za-z0-9]", "", $_GET['pageofprogram__']);
			} else {
				$page = "default";
			}
			
			if(file_exists('m/' . $page . '.php')){
				include('m/' . $page . '.php');
			} else {
				include('m/404.php');
			}
			
			include('v/index.php');
			
		} catch (Exception $e){
			echo "There was a problem with the program.<br>Check the exceptions log for more info.";
			file_put_contents("data/errors/exceptions.log", date("Y-m-d H:i:s e") . "\r\n" . print_r($e, true) . "\r\n\r\n", FILE_APPEND);
		}
	}
}
?>
