<?php
	/*
	 * Handle all statuses through the admin panel with these functions
	 */
class status extends framework {
	public function getStatuses(){
		$sql = "SELECT id, status, description FROM statuses";
		$query = parent::get('db')->mysqli()->query($sql);
		$result = array();
		while($row = $query->fetch_array()){
			$result[] = $row;
		}
		return $result;
	}

	public function getTypes() {
		$sql = "SELECT id, name, description FROM tickettypes";
		$query = parent::get('db')->mysqli()->query($sql);
		$result = array();
		while($row = $query->fetch_array()) {
			$result[] = $row;
		}
		return $result;
	}

	public function addStatus($TypeName, $TypeDescription) {
		$sql = "INSERT INTO tickettypes(name, description) VALUES('".$TypeName."', '".$TypeDescription."')";
		$query = parent::get('db')->mysqli()->query($sql);
		if($query) {
			return 0;
		} else {
			return 1;
		}
	}

	public function addType() {
	}
}
?>
