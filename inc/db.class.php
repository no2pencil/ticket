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
	
	/*
	 * fetchArray(mysqli_result $result)
	 * Fetches an associative array of $result. Array keys are prepended with their respective table names in the format of: (table).(column)
	*/
	public function fetchArray($result){
		$rows = array();
		$fieldcount = $result->field_count;
		$i = 0;
		while($row = $result->fetch_row()){
			for($n = 0; $n < $fieldcount; $n++){
				$meta = $result->fetch_field_direct($n);
				$rows[$i][$meta->table . '.' .  $meta->name] = $row[$n];
			}
			$i++;
		}
		if(count($rows) > 0){
			return $rows; // Multiple rows
		} else {
			return $rows[0]; // One row, just return that
		}
	}
}
?>
