<?php
class tickets extends framework {
	public function add($customer, $type, $priority, $dueDate, $status, $specialFields){
		$sql = "INSERT INTO tickets(customer, type, priority, dueDate, status, specialFields, createDate) VALUES (?, ?, ?, ?, ?, ?, ?)";
		if($stmt = parent::get('db')->mysqli()->prepare($sql)){
			$timestamp = parent::get('utils')->timestamp();
			$stmt->bind_param('iisssss', $customer, $type, $priority, $dueDate, $status, $specialFields, $timestamp);
			$stmt->execute();
			$stmt->store_result();
			if($stmt->affected_rows > 0){
				return parent::get('db')->mysqli()->insert_id;
			}
		}
		return false;
	}

	/* 
	 * createType();
	 * Creates a ticket type
	 * TODO: Finish function
	*/
	public function createType() {
		$sql = "INSERT INTO tickettypes(name, description, specialFields) VALUES (?, ?, ?)";
		if($stmt = parent::get('db')->mysqli()->prepare($sql)) {
			$stmt->bind_param('sss', $Typename, $Typedescription, $Typespecial);
			$stmt->execute();
			$stmt->store_result();
			if($stmt->affected_rows > 0) {
			}
		}
	}
	
/*
| createDate    | varchar(30)   | NO   |     | NULL    |                |
| creator       | int(5)        | NO   |     | NULL    |                |
| type          | int(255)      | NO   |     | NULL    |                |
| priority      | varchar(255)  | NO   |     | NULL    |                |
| dueDate       | varchar(10)   | NO   |     | NULL    |                |
| status        | int(255)      | NO   |     | NULL    |                |
| customer      | int(255)      | NO   |     | NULL    |                |
| specialFields | varchar(1000) | NO   |     | NULL    |                |
| invoice       | varchar(12)   | YES  |     | NULL    |                |
*/

	/*
	 * Searches ticket ids by partial name given from ticket name
	 * Returns id, & that is passed to getTicketById
	*/
	public function searchTicketById($id) {
		$sql = "SELECT id from tickets where invoice like '%$id%'";
		$result = parent::get('db')->mysqli()->query($sql);
		if($result) {
			$id= $result->fetch_array(MYSQLI_ASSOC);
			if($id) {
				return $id[id];
			}
			return false;
		}
		return false;
	}
	
	/*
	 * search(string $value, string $columns=array('id', 'customer'))
	 * Returns all rows in table "tickets" matching the criteria. Empty array on no results. False on error.
	 * $columns are the columns to search in. Search is done using LIKE
	 * unfin.
	*/
	public function search($value, $columns=array('id', 'customer')){
		$cols = implode(', ', $columns); // Thank god for prepared statements
		$sql = "SELECT id, createDate, creator, type, priority, dueDate, status, customer, specialFields FROM tickets WHERE";
		if($cols) {
		foreach($cols as $col){
			$sql .= ' ' . $col . ' LIKE %' . $value . '%'; // aka any $columns that contain $value
		} }
	}

	/*
	 * Pulls ticket information from the database
	 * based on the ticket id passed
	*/
	public function getTicketById($id){
		$sql = "SELECT t.id AS ID, t.createDate AS 'Created on', u.name AS Creator, c.name as Customer, s.status AS Status, s.description AS 'status_description', t.priority AS Priority, t.dueDate AS 'Due date' FROM tickets AS t " .
					"JOIN statuses AS s ON t.status = s.id " .
					"JOIN users AS u ON t.creator = u.id " .
					"JOIN customers AS c ON t.customer = c.id " . 
						"WHERE t.id=" . (int)$id . " LIMIT 1";
		$sql = "SELECT createDate, creator, type, priority, dueDate, status, customer, invoice from tickets where id=$id";
		$result = parent::get('db')->mysqli()->query($sql);
		$row = $result->fetch_array(MYSQLI_ASSOC);
		return $row;
	}
	
	public function getTypeById($id){
		$types = $this->getTypes(); // laziness ftw
		return $types[$id];
	}

	public function getTypes(){
		$sql = "SELECT id, name FROM tickettypes";
		//$result = parent::get('db')->mysqli()->query($sql);
		$result = parent::get('db')->mysqli()->query($sql);
		$fresult = array();
		while($row = $result->fetch_array()){
			$fresult[$row['id']] = array("name" => $row['name'], "description" => $row['description']);
			$fresult[$row['id']]['specialFields'] = $this->generateSpecialFields($row['specialFields']);
		}
		return $fresult;
	}

        public function getStatusById($id) {
                $statuses = $this->getStatuses();
                return $statuses[$id];
        }
	
	public function getStatuses() {
		$sql = "SELECT id, status FROM statuses";
                $result = parent::get('db')->mysqli()->query($sql);
                $fresult = array();
                while($row = $result->fetch_array()){
                        $fresult[$row['id']] = array("status" => $row['status']); 
                }
                return $fresult;
	}

	public function getAllOpen() {
		//$sql = "SELECT * from tickets as t JOIN customers AS c ON t.customer = c.id where";
		$sql = "SELECT id, status, customer, invoice from tickets where ";
		$sql .= " status != 11";
		$sql .= " and status != 20";
		$sql .= " and status != 23";
		$sql .= " and status != 26";
		$sql .= " and status != 29";
		$sql .= " and status != 39";
		$sql .= " and status != 41";
		$sql .= " and status != 46";
		$sql .= " and status != 47";
		$sql .= " and status != 54";
		$sql .= " and status != 55";
		$sql .= " and status != 61";
		$sql .= " and status != 62";
		$sql .= " and status != 63";
		$sql .= " order by id DESC";
		$result = parent::get('db')->mysqli()->query($sql);
		if($result !== false){
			$fresult= array();
			while($row = $result->fetch_array()) {
				$fresult[$row['id']] = array(
					"id" => $row['id'],
					"status" => $row['status'],
					"customer" => $row['customer'],
					"invoice" => $row['invoice']
				);
			}
		} else {
			return false;
		}
		
		return $fresult;
	}

	public function getComments($id) {
		$sql = "SELECT comment, dateadded FROM comments where invoice like '%$id'";
		$result = parent::get('db')->mysqli()->query($sql);
		$comments = array();
		while($row = $result->fetch_array()) {
			$comments[] = $row;
		}
		return $comments;
	}

	public function getBulk($limit, $offset){
		$sql = "SELECT * FROM tickets AS t JOIN customers AS c ON t.customer = c.id LIMIT " . (int)$limit . " OFFSET " . (int)$offset;
		$result = parent::get('db')->mysqli()->query($sql);
		$tickets = array();
		while($row = $result->fetch_array()){
			$tickets[] = $row;
		}
		return $tickets;
	}
	
	public function generateSpecialFields($special){
		if(!empty($special)){
			$result = array();
			$fields = explode(' ;;; ', $special);
			foreach($fields as $id => $field){
				$stuff = explode(' ^^^ ', $field);
				$type = $stuff[0];
				$name = $stuff[1];
				$default = $stuff[2];
				$result[$id]['name'] = $name;
				$result[$id]['default'] = $default;
				$result[$id]['type'] = $type;
				if($type == "textarea"){
					$result[$id]['html'] = '<textarea name="special_' . $type . '_' . $name . '">' . $default . '</textarea>';
				} else {
					$result[$id]['html'] = '<input type="' . $type . '" name="special_' . $type . '_' . $name . '" value="' . $default . '">';
				}
			}
			return $result;
		}
		return false;
	}
}
?>
