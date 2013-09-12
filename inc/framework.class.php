<?php
/*
 * framework.class.php
 * Not exactly a framework, per se, but used to access other classes in a dynamic fashion.
*/
class framework {
	public function get($class){
		if(!isset($this->$class)){
			try {
				$this->$class = new $class();
			} catch(Exception $e){
				/* TODO: ERROR HANDLING */
				die("Fatal error: " + $e->getMessage());
			}
		}
		return $this->$class;
	}
}
?>
