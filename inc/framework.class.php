<?php
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