<?php
class View {
	public function __set($key, $val){
		$this->$key = $val;
	}

	public function __get($key){
		return $this->$key;
	}
}