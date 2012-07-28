<?php
class db {
	public function mysqli(){
		require_once("include.db");
		if(!isset($this->mysqli)){
			$this->mysqli = new mysqli($db_server, $db_user, $db_pass, $db_db);
		}
		return $this->mysqli;
	}

	function status() {
        	return print_r($this,true);
    	}
}
?>
