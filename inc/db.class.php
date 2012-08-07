<?php
class db {
	public function mysqli(){
		include "inc/db_creds.php";
		if(!isset($this->mysqli)){
			if(!$this->mysqli = new mysqli($db_server, $db_user, $db_pass, $db_db)) {
				die(":(");
			}
		}
		return $this->mysqli;
	}

	function status() {
		return print_r($this,true);
	}

	function reffs() {
	}
}
?>
