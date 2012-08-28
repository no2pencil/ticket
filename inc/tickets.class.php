<?php
class tickets extends framework {
	
	/*
	 * add(string $customer, int $type, string $priority, string $dueDate, int $status, string $specialFields)
	 * Adds a new ticket to the system with the provided information.
	 * Returns insert ID on success, false on failure
	*/
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
	| id            | int(255)      | NO   | PRI | NULL    | AUTO_INCREMENT |
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
	 * searchTicketById(int $id)
	 * Searches ticket ids by partial name given from ticket name
	 * Returns id, & that is passed to getTicketById 
	 * Returns false on failure
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
	 * Returns IDs for rows that match search query. Returns false on error.
	 * $columns are the columns to search in. Search is done using LIKE.
	*/
	public function search($value, $columns=array('id', 'customer')){
		$bind = array();
		$result = array();
		$sql = "SELECT id FROM tickets WHERE";
		foreach($columns as $key => $col){
			if($key == (count($columns)-1)){
				$sql .= ' ' . $col . ' LIKE ?';
			} else {
				$sql .= ' ' . $col . ' LIKE ? OR';
			}
			$bind[0] = (empty($bind[0])) ? 's' : $bind[0] . 's';
			$bind[] = "%" . $value . "%";
		}
		
		foreach($bind as $key => $value){
			$bimp[$key] = &$bind[$key]; // Makes them references for bind_param
		}
		
		if($stmt = parent::get('db')->mysqli()->prepare($sql)){
			call_user_func_array(array($stmt, "bind_param"), $bind); // See what I did there? Dynamic bind_param.
			$stmt->execute();
			$stmt->bind_result($id);
			while($stmt->fetch()){
				$result[] = array('id' => $id);
			}
			
			$rows = array();
			foreach($result as $row){
				$rows[] = $this->getTicketById($row['id']);
			}
			return $rows;
		}
		return false;
	}

	/*
	 * getTicketById(int $id)
	 * Returns the ticket information based on $id. Also joins statuses, users, and customers into the query.
	*/
	public function getTicketById($id){
		$sql = "SELECT * FROM tickets AS ticket " .
					"LEFT JOIN statuses AS status ON ticket.status = status.id " .
					"LEFT JOIN customers AS customer ON ticket.customer = customer.id " .
					"LEFT JOIN users AS user ON ticket.creator = user.id " .
						"WHERE ticket.id=" . (int)$id . " LIMIT 1";
		$result = parent::get('db')->mysqli()->query($sql);
		$result = parent::get('db')->fetchArray($result);
		return $result;
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
		$sql = "SELECT * FROM tickets AS ticket JOIN customers AS customer ON ticket.customer = customer.id LIMIT " . (int)$limit . " OFFSET " . (int)$offset;
		$result = parent::get('db')->mysqli()->query($sql);
		return parent::get('db')->fetchArray($result);
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
