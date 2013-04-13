<?php
class Error {
	public function __construct($error){
		//$callers = debug_backtrace();
		//var_dump($callers);
		//$msg = date('[m/d/y H:i:s] ') . $callers[0]['file'] . ' -> ' . $callers[0]['class'] . ' -> ' . $callers[0]['function'] . ' -> ' . $callers[0]['line'] . ': ' . $error;
		die($error);
	}
}