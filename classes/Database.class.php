<?php
class Database {
	/* Connection info */
	private $host     = "localhost";
	private $username = "root";
	private $password = "";
	private $database = "ticket_mvc";
	
	// Our PDO link
	private $PDO;

	public function __construct(){
		try{
			$this->PDO = new PDO("mysql:host=$this->host;dbname=$this->database", $this->username, $this->password);
			$this->PDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} catch(PDOException $e){
			new Error($e->getMessage());
		}
	}

	/*
	 * insert(string $table, array $data)
	 * Inserts a row into $table. Keys for $data are the columns and their values are the values to be inserted.
	 * Returns true on success, false on failure.
	*/
	public function insert($table, $data){
		try {
			$values = $this->convertArrayToSQL(',', $data);
			$sql = 'INSERT INTO `' . $table . '` SET ' . $values[0];
			$sth = $this->PDO->prepare($sql);
			$sth->execute($values[1]);
			if($sth->rowCount() > 0){
				return true;
			}
			return false;
		} catch(PDOException $e){
			new Error('PDOException: ' . $e->getMessage());
		}
	}

	/* 
	 * select(string $table, array $where)
	 * Selects rows from $table based on $where
	 * $where should be "column" => "value". All column/value pairs are put together using AND, however you can insert an "OR" to do or
	 * IE: $where = array("username" => "admin", "OR", "email" => "admin@mail.com"); // will return row where username is admin OR email is admin@mail.com
	 * IE: $where = array("username" => "admin", "email" => "admin@mail.com"); // Will return row where username is admin AND email is admin@mail.com
	 * Returns array
	*/
	public function select($table, $where){
		try {
			$sqlInfo = $this->convertArrayToSQL('AND', $where);
			if(empty($sqlInfo[0])) $sqlInfo[0] = '1=1';
			$sql = 'SELECT * FROM `' . $table . '` WHERE ' . $sqlInfo[0];
			$sth = $this->PDO->prepare($sql);
			$sth->execute($sqlInfo[1]);
			$result = $sth->fetchAll();
			return $result;
		} catch(PDOException $e){
			new Error('PDOException: ' . $e->getMessage());
		}
	}

	/*
	 * update(string $table, array $set, array $where=array())
	 * Updates table with the new values (an associative array of column => new value). If $where is defined, the only rows that will be affected are those that match $where.
	 * Where isn't required. If where is not provided, all rows will be updated.
	 * Returns int with number of rows changed
	 * WARNING: 0 rows changed would be a successful query, but will still match false. Make sure you use === to test!
	*/
	public function update($table, $set, $where=array()){
		try {
			if(empty($where)) $where = array("1" => "1");
			$data = $this->convertArrayToSQL('AND', $set, array("WHERE"), $where); // ;_; this design pattern sucks
			$sql = 'UPDATE `' . $table . '` SET ' . $data[0];
			$sth = $this->PDO->prepare($sql);
			$sth->execute($data[1]);
			return $sth->rowCount();
		} catch (PDOException $e){
			new Error('PDOException: ' . $e->getMessage());
		}
	}

	/*
	 * delete(string $table, array $where)
	 * Deletes rows from $table that match $where
	 * If $where is empty, all rows will be deleted
	 * Returns number of rows affected.
	 * WARNING: 0 rows changed would be a successful query, but will still match false. Make sure your use the === comparitive operator.
	*/
	public function delete($table, $where){
		try {
			$sql = 'DELETE FROM `' . $table . '` WHERE ';
			$prepare_data = array();
			if(empty($where)){
				$sql .= '1=1';
			} else {
				$data = $this->convertArrayToSQL('AND', $where);
				$sql .= $data[0];
				$prepare_data = $data[1];
			}
			$sth = $this->PDO->prepare($sql);
			$sth->execute($prepare_data);
			return $sth->rowCount();
		} catch(PDOException $e){
			new Error('PDOException: ' . $e->getMessage());
		}
	}

	/*
	 * convertArrayToSQL(string $seperator, array $data, array $data2, array $data3, ...)
	 * Turns the given arrays into a prepared-statement ready SQL query, and returns both the SQL query AND the proper array for executing the query
	 * You can use an unlimited amount of arguments by the way. This is to allow duplicate keys. 
	 * $seperator is what is put between each dataset. IE "and" would make something like "`column` = :column0 AND `hola` = :hola0"
	 * Returns array([0] => The SQL query, [1] => The prepared statement data)
	*/
	private function convertArrayToSQL($seperator, $data){ // Requires at least two params
		$sql = ''; // SQL query to return
		$data = array(); // Prepared statement data to return
		$arguments = func_get_args();
		$dont_put_and = false; // If true next value will not have "and" before it. We put this out here because multiple arguments go into the same query.

		for($argument_index=1; $argument_index<func_num_args(); $argument_index++){ // Loop through each argument. Skip 1 because that's our seperator
			$argument_iteration = 0;
			foreach($arguments[$argument_index] as $key => $value){ // Loop through each key/value
				if($value == "OR" || $value == "WHERE"){
					$sql .= ' ' . $value . ' ';
					$dont_put_and = true; // This is a hack because I hate my life
					continue;
				} else {
					if(($argument_iteration > 0 || $argument_index > 1) && !$dont_put_and){
						$sql .= ' ' . $seperator . ' ';
					}
				}
				$dont_put_and = false; // Well value must have been data, so we should probably put and after it :)

				/* Now we need to put our keys in the SQL statement in the form of "`table_key` = :prepared_statement_key" */
				/* We also have to check for duplicates */
				$dup_key_count = 0;
				while(strpos($sql, ':' . $key . $dup_key_count) == true){
					$dup_key_count++;
				}
				$sql .= '`' . $key . '` = '; // Set column key...
				if($value == "CURRENT_TIMESTAMP"){
					$sql .= 'CURRENT_TIMESTAMP';
				} else {
					$sql .= '?';
					$data[] = $value;
				}
				$argument_iteration++;
			}
		}
		return array($sql, $data);
	}
}