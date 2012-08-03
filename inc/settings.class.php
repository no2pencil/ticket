<?php
/*
 * settings.class.php
 * Used for retrieving and editing site settings
*/
class settings extends framework {
	/*
	 * get(string $name)
	 * Returns the setting's value with the name of $name
	 * Returns false on failure, or $name not existing in settings table
	*/
	public function get($name){
		$sql = 'SELECT value FROM settings WHERE name=?';
		if($stmt = parent::get('db')->mysqli()->prepare($sql)){
			$stmt->bind_param('s', $name);
			$stmt->execute();
			$stmt->store_result();
			$stmt->bind_result($value);
			if($stmt->num_rows > 0){
				$stmt->fetch();
				return $value;
			}
		}
		return false;
	}
	
	/*
	 * set(string $name, string $value)
	 * Sets $name to $value. If name does not exist, create it.
	 * returns true on success, false on failure
	*/
	public function set($name, $value){
		$sql = "SELECT * FROM settings WHERE name=?";
		if($stmt = parent::get('db')->mysqli()->prepare($sql)){
			$stmt->bind_param('s', $name);
			$stmt->execute();
			$stmt->store_result();
			if($stmt->num_rows > 0){
				$sql = "UPDATE settings SET value=? WHERE name=?";
			} else {
				$sql = "INSERT INTO settings(value, name) VALUES(?, ?)";
			}
		}
		
		if(isset($sql)){
			if($stmt = parent::get('db')->mysqli()->prepare($sql)){
				$stmt->bind_param('ss', $value, $name);
				$stmt->execute();
				$stmt->store_result();
				if($stmt->affected_rows > 0){
					return true;
				}
			}
		}
		
		return false;
	}
}
?>