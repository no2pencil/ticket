<?php
class tickets extends framework {

	/*
	 * add(string $customer, int $type, string $priority, string $dueDate, int $status, string $specialFields)
	 * Adds a new ticket to the system with the provided information.
	 * Returns insert ID on success, false on failure
	*/
	public function add($customer, $invoice, $type, $priority, $dueDate, $status, $specialFields, $date){
		$sql = "INSERT INTO tickets(customer, invoice, type, priority, dueDate, status, specialFields, createDate) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
		if($stmt = parent::get('db')->mysqli()->prepare($sql)){
			//$timestamp = parent::get('utils')->timestamp();
			$stmt->bind_param('isississ', $customer, $invoice, $type, $priority, $dueDate, $status, $specialFields, $date);
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
	 * searchTicketById(int $id)
	 * Searches ticket ids by partial name given from ticket name
	 * Returns id, & that is passed to getTicketById 
	 * Returns false on failure
	*/
	public function searchTicketById($invoice) {
		$sql = "SELECT id from tickets where invoice like '%$invoice%'";
		$result = parent::get('db')->mysqli()->query($sql);
		if($result) {
			$id=$result->fetch_array(MYSQLI_ASSOC);
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
		//"LEFT JOIN repair AS repair ON ticket.repair = repair.id ".
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

		return $result; 
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
			//$fresult[$row['id']]['specialFields'] = $this->generateSpecialFields($row['specialFields']);
		}
		return $fresult;
	}

	/*
	 * setStatusByID(int $status_id, int $invoice, string $last_updated)
	 */

	public function setStatusByID($invoice_id, $status_id) {
		$sql = "UPDATE tickets set status=? WHERE id=?";
		if($stmt = parent::get('db')->mysqli()->prepare($sql)){
			$stmt->bind_param('ii', $status_id, $invoice_id);
			$stmt->execute();
			$stmt->store_result();
			if($stmt->affected_rows > 0){
				return true;
			}
		}
		return false;
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
		while($row = $result->fetch_array()) {
			$fresult[$row['id']] = array("status" => $row['status']); 
		}
		return $fresult;
	}

	/*
	 * getRepairs()
	 * Returns all Repairs & Repair Descriptions
	*/
	public function getRepairs() {
		$sql = "SELECT id, repair, description FROM repairs";
		$result = parent::get('db')->mysqli()->query($sql);
		$fresult = array();
		while($row = $result->fetch_array()) {
			$fresult[$row['id']] = array(
				"id" =>$row['id'],
				"repair" =>$row['repair'],
				"description" =>$row['description']
			);
		}
		return $fresult;
	}

	/*
	 * setStatusByID(int $status_id, int $invoice, string $last_updated)
	*/

	public function setRepairByID($invoice_id, $repair_id) {
		$sql = "UPDATE tickets set repair=? WHERE id=?";
		if($stmt = parent::get('db')->mysqli()->prepare($sql)){
			$stmt->bind_param('ii', $repair_id, $invoice_id);
			$stmt->execute();
			$stmt->store_result();
			if($stmt->affected_rows > 0){
				return true;
			}
		}
		return false;
	}

	/*
	 * getComments(int $invoice_id)
	 * Returns all comments for the ticket  with the specified invoice id
	 * Uses LIKE for invoice number, see $sql below for more info.
	*/
	public function getComments($invoice_id) {
		$sql = "SELECT comment, dateadded FROM comments where invoice_id = '$invoice_id'";
		$result = parent::get('db')->mysqli()->query($sql);
		$comments = array();
		while($row = $result->fetch_array()) {
			$comments[] = $row;
		}
		return $comments;
	}

	/*
	 * getBulkOpen(int $limit, int $page)
	 * Same as getBulk, except just open
	 */
	public function getBulkOpen($limit, $page) {
		$offset = $page * $limit;
		$sql = "SELECT * FROM tickets as ticket ".
			"LEFT JOIN statuses AS status ON ticket.status = status.id " .
			"LEFT JOIN customers AS customer ON ticket.customer = customer.id " .
			"LEFT JOIN users AS user ON ticket.creator = user.id " .
			"where status.status != 'Closed' and status.status != 'Cancelled' " .
			"and status.status != 'eBay item sold' and status.status != 'Canceled' " .
			"ORDER BY ticket.id DESC ".
			"LIMIT " . (int)$limit . " OFFSET " . (int)$offset;

		$result = parent::get('db')->mysqli()->query($sql);
		return parent::get('db')->fetchArray($result);
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
					<tr>
						<th>Status</th>
						<th>Repair</th>
						<th>Invoice</th>
						<th>Customer</th>
						<th>Call</th>
					</tr>
				</thead>
				<tbody>';
		foreach($tickets as $key => $ticket) {
			switch ($ticket['status.status']) {
				case "Pending Payment":
					$btn_atr='badge-success';
					$btn_char=' icon-money">';
				break;
				case "Call Customer Admin":
				case "Call Customer Tech":
					$btn_atr='badge-warning';
					$btn_char=' icon-phone">';
				break;
				case "In Progress":
					$btn_atr='';
					$btn_char=' icon-stethoscope">';
				break;
				case "Parts need to be ordered":
					$btn_atr='badge-info';
					$btn_char=' icon-shopping-cart">';
				break;
				case "Post Payment":
					$btn_atr='badge-important';
					$btn_char=' icon-fire">';
				break;
				case "Waiting for Parts":
					$btn_atr='badge-info';
					$btn_char=' icon-time">';
				break;
				case "Closed":
					$btn_atr='badge-inverse';
					$btn_char=' icon-lock">';
				break;
				default:
					$btn_atr='';
					$btn_char=' icon-hand-right">';
				break;
			}
			$result .= '<tr><td><a href="#" rel="tooltip" placement="left" title="';
			$result .= $ticket['status.status'];
			$result .= '"><span class="badge '.$btn_atr.'">';
			$result .= '<em class="icon-white';
			$result .= $btn_char.'</em></span></a></td>';

			$result .= '<td><a href="#" rel="tooltip" placement="left" title="';
			$result .= $ticket['status.status'];
			$result .= '"><span class="badge badge-inverse">';
			$result .= '<em class="icon-';
			switch($ticket['ticket.repair']) {
				case 1:
					$result .= 'desktop">&nbsp;Desktop/PC';
					break;
				case 2:
					$result .= 'laptop">&nbsp;Laptop';
					break;
				case 3:
					$result .= 'tablet">&nbsp;iPad';
					break;
				case 4:
					$result .= 'mobile-phone">&nbsp;iPhone';
					break;
				case 5:
					$result .= 'keyboard">&nbsp;Coding';
					break;
				case 6:
					$result .= 'sitemap">&nbsp;Networking';
					break;
				case 7:
					$result .= 'gamepad">&nbsp;Nintendo DS/DSi/3DS/XL';
					break;
				case 8:
					$result .= 'cloud-upload">&nbsp;Web Hosting';
					break;
				case 9:
					$result .= 'folder-open-alt">&nbsp;Data Recovery';
					break;
				default:
					$result .= 'desktop">&nbsp;Desktop/PC';
					break;
			}
			$result .= '</em></span></a></td>';

			$result .= '<td><a href="tickets.php?view=' . $ticket['ticket.id'] . '">' .  $ticket['ticket.invoice'] . '</a></td>';
			$result .= '<td><a href="customers.php?view=' . $ticket['customer.id'] . '" class="btn">' . $ticket['customer.name'] . '</a></td><td>';
			if(!empty($ticket['customer.primaryPhone'])){
				$PrimaryPhone = parent::get('utils')->formatPhone($ticket['customer.primaryPhone']);
				$ringurl = parent::get('ring_central')->make_url($ticket['customer.primaryPhone']);
				if($ringurl){
					$result .= '<a href="#RingUrlModal" data-toggle="modal" rel="tooltip" title="Call '.$PrimaryPhone.'">&nbsp;<span class="badge badge-warning"><i class="icon-comment icon-white"></i></span></a>';
				} else {
					//$result .= $framework->get('utils')->formatPhone($ticket['customer.primaryPhone']); // User does not have ring central setup
				}
			} else {
				$result .= 'No phone on file';
			}
			$result .= '</td>';
/*
<td><div class="btn-group">';
			$result .= '<button class="btn">'.$ticket['status.status'].'</button>';
			$result .= '<button class="btn dropdown-toggle" data-toggle="dropdown">';
			$result .= '<span class="caret"></span></button>';
			$result .= '<ul class="dropdown-menu">
				<li><a href="#">View</a></li>
				<li><a href="#">Edit</a></li>
				<li class="divider"></li>
				<li><a href="#">Close</a></li>';
			$result .= '</ul></div></td>';
*/
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
