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
	 * Creates a ticket type.
	 * Returns true on success, false on failure
	*/
	public function createType() {
		$sql = "INSERT INTO tickettypes(name, description, specialFields) VALUES (?, ?, ?)";
		if($stmt = parent::get('db')->mysqli()->prepare($sql)) {
			$stmt->bind_param('sss', $Typename, $Typedescription, $Typespecial);
			$stmt->execute();
			$stmt->store_result();
			if($stmt->affected_rows > 0) {
				return true;
			}
		}
		return false;
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
	 * search(string $value, string $exclude, string $columns=array('id', 'customer'))
	 * Returns IDs for rows that match search query. Returns false on error.
	 * $columns are the columns to search in. Search is done using LIKE.
	 * $exclude excludes results using NOT LIKE
	*/
	public function search($value, $exclude, $columns=array('id', 'customer')){
		$bind = array();
		$result = array();
		$sql = "SELECT id FROM tickets WHERE";
		foreach($columns as $key => $col){
			if($key == (count($columns)-1)){
				$sql .= ' ' . $col . ' LIKE ? AND ' . $col . ' NOT LIKE ?';
			} else {
				$sql .= ' ' . $col . ' LIKE ? AND ' . $col . ' NOT LIKE ? OR';
			}
			$bind[0] = (empty($bind[0])) ? 'ss' : $bind[0] . 'ss';
			$bind[] = "%" . $value . "%";
			$bind[] = "%" . $exclude . "%";
		}
		
		foreach($bind as $key => $value){
			$bind[$key] = &$bind[$key]; // Makes them references for bind_param
		}
		
		if($stmt = parent::get('db')->mysqli()->prepare($sql)){
			call_user_func_array(array($stmt, "bind_param"), $bind); // See what I did there? Dynamic bind_param. Nifty
			$stmt->execute();
			$stmt->bind_result($id);
			while($stmt->fetch()){
				$result[] = array('id' => $id);
			}
			
			$rows = array();
			foreach($result as $row){
				$row = $this->getTicketById($row['id']);
				$rows[] = $row;
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
					"LEFT JOIN users AS creator ON ticket.creator = creator.id " .
						"WHERE ticket.id=" . (int)$id . " LIMIT 1";
		$result = parent::get('db')->mysqli()->query($sql);
		$result = parent::get('db')->fetchArray($result);
		// This part is for turning NULL into empty strings.
		foreach($result as $key => $value){
			if(gettype($value == 'array')){
				foreach($value as $key2 => $value2){
					if(gettype($value2) == 'NULL'){
						$result[$key][$key2] = '';
					}
				}
			} else if(gettype($value) == 'NULL'){
				$result[$key] = '';
			}
		}
		return $result[0]; 
	}
	
	/*
	 * getTypeByID(int $id)
	 * Returns the type with the $id
	*/
	public function getTypeById($id){
		$types = $this->getTypes(); // laziness ftw
		return $types[$id];
	}
	
	/*
	 * getTypes()
	 * Returns all ticket types
	*/
	public function getTypes(){
		$sql = "SELECT id, name FROM tickettypes";
		$result = parent::get('db')->mysqli()->query($sql);
		$fresult = array();
		while($row = $result->fetch_array()){
			$fresult[$row['id']] = array("name" => $row['name'], "description" => $row['description']);
			$fresult[$row['id']]['specialFields'] = $this->generateSpecialFields($row['specialFields']);
		}
		return $fresult;
	}

	/*
	 * getStatusById(int $id)
	 * Returns the status with the given ID
	*/
    public function getStatusById($id) {
            $statuses = $this->getStatuses(); // TODO: Benchmark test this vs querying the db
            return $statuses[$id];
    }
	
	/*
	 * getStatuses()
	 * Returns all of the statuses
	*/
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
	
	/*
	 * getComments(int $id)
	 * Returns all comments for the ticket with the specified invoice
	 * Uses LIKE for invoice number, see $sql below for more info.
	*/
	public function getComments($id) {
		$sql = "SELECT comment, dateadded FROM comments where invoice like '%$id'";
		$result = parent::get('db')->mysqli()->query($sql);
		$comments = array();
		while($row = $result->fetch_array()) {
			$comments[] = $row;
		}
		return $comments;
	}

	/*
	 * getBulk(int $limit, int $page)
	 * Returns an array full of tickets
	*/
	public function getBulk($limit, $page){
		$offset = $page * $limit;
		$sql = "SELECT * FROM tickets AS ticket " .
					"LEFT JOIN statuses AS status ON ticket.status = status.id " .
					"LEFT JOIN customers AS customer ON ticket.customer = customer.id " .
					"LEFT JOIN users AS user ON ticket.creator = user.id " .
						"LIMIT " . (int)$limit . " OFFSET " . (int)$offset;
		
		$result = parent::get('db')->mysqli()->query($sql);
		return parent::get('db')->fetchArray($result);
	}
	
	/*
	* generateListDisplay(array $tickets)
	* Generates a table with all of the tickets in $tickets.
	*/
	public function generateListDisplay($tickets){
		$result = '
			<table class="table">
				<thead>
					<tr><th>Invoice</th><th>Customer</th><th>Call</th><th>Status</th></tr>
				</thead>
				<tbody>';
		foreach($tickets as $key => $ticket){
			$result .= '<tr>';
			$result .= '<td><a href="tickets.php?view=' . $ticket['ticket.id'] . '">' .  $ticket['ticket.invoice'] . '</a></td>';
			$result .= '<td><a href="customers.php?view=' . $ticket['customer.id'] . '" class="btn">' . $ticket['customer.name'] . '</a></td>';
			$result .= '<td>';
			if(!empty($ticket['customer.primaryPhone'])){
				$ringurl = parent::get('ring_central')->make_url($ticket['customer.primaryPhone']);
				if($ringurl){
					$result .= '
						<a href="' . $ringurl . '" target="_blank"><span class="badge badge-warning"><i class="icon-comment icon-white"></i></span></a>';
				} else {
					$result .= $framework->get('utils')->formatPhone($ticket['customer.primaryPhone']); // User does not have ring central setup
				}
			} else {
				$result .= 'No phone on file';
			}
			$result .= '</td>';
			$result .= '<td><div class="btn-group">';
			$result .= '<button class="btn dropdown-toggle">' . $ticket['status.status'];
			$result .= '<button class="btn dropdown-toggle" data-toggle="dropdown">';
			$result .= '
                                    <ul class="dropdown-menu">
                                            <li><a href="#">View</a></li>
                                            <li><a href="#">Edit</a></li>
                                            <li class="divider"></li>
                                            <li><a href="#">Close</a></li>
                                    </ul>';
			$result .= '</div></td>';
			$result .= '</tr>';
		}
		$result .= '</tbody></table>';
		return $result;
	}
	
	/*
	 * getAll()
	 * Returns all tickets
	*/
	public function getAll(){
		$stillmore = true;
		$result = array();
		$page = 0;
		while($stillmore){
			$tmp = $this->getBulk(1, $page);
			if(empty($tmp)){
				return $result;
			}
			$result[] = $tmp;
			$page++;
		}
	}
	
}
?>
