<?php
class page {
	public function __construct(){
		try {
			require_once('inc/startup.php');
		
			if(isset($_GET['pageofprogram__'])){
				$page = preg_replace("[^A-Za-z0-9]", "", $_GET['pageofprogram__']);
			} else {
				$page = "default";
			}
			
			$framework = new framework();
			
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
