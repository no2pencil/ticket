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
	
	/*
	 * getCols(string $table)
	 * Returns the columns in $table
	 * Returns false on failure
	*/
	public function getCols($table){
		$cols = array();
		$sql = 'SELECT * FROM ' . $table . ' LIMIT 1';
		$result = $this->mysqli()->query($sql);
		if($result){
			$row = $result->fetch_array(MYSQLI_ASSOC);
			foreach($row as $key => $value){
				$cols[] = $key;
			}
			return $cols;
		}
		return false;
	}
}
?>
