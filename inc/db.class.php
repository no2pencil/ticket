<?php
class db {
	public function mysqli(){
		if(!isset($this->mysqli)){
			$this->mysqli = new mysqli('localhost', 'root', '', 'ticket');
		}
		return $this->mysqli;
	}
}
?>