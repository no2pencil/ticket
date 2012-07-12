<?php
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
}
?>