<?php
	/*
	 * Handle all statuses through the admin panel with these functions
	 */
class status extends framework {
	public function getStatuses(){
		//$sql = "SELECT id, status, description FROM statuses";
		$sql = "SELECT id, text, icon, color from statuses_d";
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

	public function addStatus($Text, $Icon, $Color) {
	//public function addStatus($TypeName, $TypeDescription) {
		//$sql = "INSERT INTO tickettypes(name, description) VALUES('".$TypeName."', '".$TypeDescription."')";
		$sql = "INSERT INTO statuses_d(text, icon, color) VALUES('".$Text."', '".$Icon."', '".$Color."')";
		$query = parent::get('db')->mysqli()->query($sql);
		if($query) {
			return true;
		}
		return false;
	}

	public function addType() {
	}
}
?>
