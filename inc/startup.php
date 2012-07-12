<?php
if(!isset($_SESSION)){
	session_start();
}
if(!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] == false){
	// Todo: login scripts
}
require("autoload.php");
?>