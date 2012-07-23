<?php
if(!isset($_SESSION)){
	session_start();
}

require_once("autoload.php"); // Autoload so $framework functions work

if(!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] == false){
	// Todo: login scripts
	require_once("v/login.php");
}
?>
