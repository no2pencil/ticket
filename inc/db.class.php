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

	/*
	 * status()
	 * Prints out the information of this class, including any open connects to databases
	 * For dev use only
	*/
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
	 * Returns array. Will be empty on no result.
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
		
		if(count($rows) > 1){
			return $rows; // Multiple rows
		} else if(count($rows) == 1){
			return $rows[0]; // One row
		} else {
			return array(); // Zero rows
		}
	}

	/*
	 * search(string $table, string[] $columns, string $searchValue, string $exclude=null, boolean $exactMatch=false)
	 * Searches $table for $searchValue inside of $columns. If $exclude is not null, $columns containing $exclude will not be included.
	 * If $exactMatch is true, direction comparison will be used. If false, %LIKE% will be used.
	 * Returns an array of rows, empty array on no results
	 * Returns false on error
	 *
	 * WARNING: REQUIRES THE MYSQLND DRIVER. If mysqlnd driver is not being used, false will be returned.
	*/
	public function search($table, $columns, $searchValue, $exclude=null, $exactMatch=false){
		if(strpos(mysqli_get_client_info(), 'mysqlnd ') === false){
			return false;
		}

		$bind = array();
		$result = array();
		$table = self::mysqli()->real_escape_string($table); // We can't prepare tables, so this will have to do
		$sql = "SELECT * FROM $table WHERE";
		foreach($columns as $key => $col){
			if($key == (count($columns)-1)){
				$sql .= ' ' . $col . ' LIKE ? AND ' . $col . ' NOT LIKE ?';
			} else {
				$sql .= ' ' . $col . ' LIKE ? AND ' . $col . ' NOT LIKE ? OR';
			}
			$bind[0] = (isset($bind[0])) ? $bind[0] . "ss" : 'ss';
			$bind[] = "%" . $searchValue . "%";
			if($exclude == null){
				$bind[] = "";
			} else {
				$bind[] = "%" . $exclude . "%";
			}
		}
		
		foreach($bind as $key => $value){
			$bind[$key] = &$bind[$key]; // Makes them references for bind_param
		}
		
		if($stmt = $this->mysqli()->prepare($sql)){
			call_user_func_array(array($stmt, "bind_param"), $bind); // See what I did there? Dynamic bind_param. Nifty
			try {
				$stmt->execute();
			} catch(Exception $e){
				return false;
			}
			
			$results = $stmt->get_result();
			while($row = $results->fetch_array(MYSQLI_ASSOC)){
				$result[] = $row;
			}
			return $result;
		}
		return false;
	}
}
?>
