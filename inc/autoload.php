<?php
function __autoload($class){
	if(file_exists('inc/' . $class . '.class.php')){
		if(!(include('inc/' . $class . '.class.php')) == 'OK'){
			throw new Exception("Could not load class " + $class);
		}
	} else {
		throw new Exception("Could not find class " + $class);
	}
}
?>
