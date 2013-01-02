<?php
class customers extends framework {
	/*
	 * add(string $name, string $email, string $primaryPhone, string $secondaryPhone, string $address, string $referral)
	 * Adds a customer with the given information to the database
	 * Returns true on success, false on failure
	*/
	public function add($name, $email, $primaryPhone, $secondaryPhone, $address, $referral){
		$sql = 'INSERT INTO customers(name, email, primaryPhone, secondaryPhone, address, referral, createDate, creator) VALUES(?, ?, ?, ?, ?, ?, ?, ?)';
		if($stmt = parent::get('db')->mysqli()->prepare($sql)){
			$stmt->bind_param('ssssssss', $name, $email, $primaryPhone, $secondaryPhone, $address, $referral, $createDate, $creator);
			$createDate = parent::get('utils')->timestamp();
			$creator = $_SESSION['user_id'];
			$stmt->execute();
			$stmt->store_result();
			if($stmt->affected_rows == 1){
				return true;
			}
		}
		return false;
	}

	/*
	 * update(string $customer_id, string $name, string $email, string $primaryPhone, string $secondaryPhone, string $address, string $referral)
	 * Updates a customer in the database, based on the provided customer id, & form data
	 * Returns true on success, false on failure
	*/
	public function update($customer_id, $name, $email, $primaryPhone, $secondaryPhone, $address, $referral){
		$sql = "UPDATE customers SET name = ?, email = ?, primaryPhone = ?, secondaryPhone = ?, address = ?, referral = ? ";
		$sql .= "WHERE id=? LIMIT 1";
		if($stmt = parent::get('db')->mysqli()->prepare($sql)){
			$stmt->bind_param('sssssss', $name, $email, $primaryPhone, $secondaryPhone, $address, $referral, $customer_id);
			$stmt->execute();
			$stmt->store_result();
			if($stmt->affected_rows == 1) return true;
		}
		return false;
	}
	
	/*
	 * search(string $value, string $exclude, string $columns=array('name'))
	 * Returns data for rows that match search query. Returns false on error.
	 * $columns are the columns to search in. Search is done using LIKE.
	 * $exclude excludes results using NOT LIKE
	*/
	public function search($value, $exclude, $columns=array('name')){
		$bind = array();
		$result = array();
		$sql = "SELECT id FROM customers WHERE";
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
				$row = $this->getInfoById($row['id']);
				$rows[] = $row;
			}
			return $rows;
		}
		return false;
	}

	/*
	 * getReferrals()
	 * Returns an array of all referrals
	 */
	public function getReferrals() {
		$sql = "SELECT id, reff from reff";
		$result = parent::get('db')->mysqli()->query($sql);
		if($result) {
			return parent::get('db')->fetchArray($result);
		} 
		return false;
	}

	/* 
	 * get ReferralByID(int id)
	 *
	 */
	public function getReferralByID($id) {
		$sql = "SELECT reff from reff where id='".(int)$id."' LIMIT 1";
		$result = parent::get('db')->mysqli()->query($sql);
		if($result) {
			return parent::get('db')->fetchArray($result);
		}
		return false;
	}
	
	/*
	 * getInfoById(int $id)
	 * Returns an array with customer information based on the ID given
	*/
	public function getInfoById($id){
		$sql = "SELECT * FROM customers AS customer
					LEFT JOIN users as creator ON customer.creator = creator.id
						WHERE customer.id='" . (int)$id . "' LIMIT 1";
		$result = parent::get('db')->mysqli()->query($sql);
		if($result) {
			return parent::get('db')->fetchArray($result);
		}
		return false;
	}

	/*
	 * getCustomerTickets(int $id)
	 * Returns all tickets for customer matching $id
	 * Returns false on error
	*/
	public function getCustomerTickets($id){
		$tickets = array();
		$sql = "SELECT id FROM tickets WHERE customer='" . (int)$id . "'";
		$result = parent::get('db')->mysqli()->query($sql);
		if(!$result){
			return false;
		}
		while($row = $result->fetch_array()){
			$tickets[] = parent::get('tickets')->getTicketById($row['id']);
		}
		return $tickets;
	}
	
	/*
	 * generateListDisplay(array $data)
	 * Generates a list with the given customer $data
	*/
	public function generateListDisplay($data){
		$result = '
			<table class="table">
				<thead>
					<tr><th>Name</th><th>Email</th><th>Call</th><th>Open/Closed tickets</th><th>Actions</th></tr>
				</thead>
				<tbody>';
		foreach($data as $key => $value){
			if(!empty($value['customer.primaryPhone'])){
				$ringurl = parent::get('ring_central')->make_url($value['customer.primaryPhone']);
				if($ringurl){
					$call = '
						<a href="' . $ringurl . '" target="_blank"><span class="badge badge-warning"><i class="icon-comment icon-white"></i></span></a>';
				} else {
					$call = $framework->get('utils')->formatPhone($value['customer.primaryPhone']); // User does not have ring central setup
				}
			} else {
				$call = 'No phone on file';
			}
			$ticketcount = '3/10'; // Do this later
			$result .= '<tr><td>' . $value['customer.name'] . '</td><td>' . $value['customer.email'] . '</td><td>' . $call . '</td><td>' . $ticketcount . '</td>';
			$result .= '
				<td><div class="btn-group">
				  <button class="btn" onclick="window.location.href=\'customers.php?view=' . $value['customer.id'] . '\'">View</button>
				  <button class="btn dropdown-toggle" data-toggle="dropdown">
					<span class="caret"></span>
				  </button>
				  <ul class="dropdown-menu">
					<li><a href="#">New Ticket</a></li>
					<li><a href="customers.php?edit=true&customer_id='.$value['customer.id'].'">Edit</a></li>
				  </ul>
				</div></td>';
			$result .= '</tr>';
		}
		$result .= '</tbody></table>';
		return $result;
	}
	
	/*
	 * getBulk(int $count, int $page)
	 * Gets bulk information of customers
	 * Returns their information in an array
	 * Limited to $count, 0 for unlimited
	 * Use $page if you have $count set for multiple pages
	*/
	public function getBulk($count, $page){
		$result = array();
		$offset = $page * $count;
	
		$sql = "SELECT id FROM customers LIMIT ? OFFSET ?;";
		if($stmt = parent::get("db")->mysqli()->prepare($sql)){
			$stmt->bind_param('ii', $count, $offset);
			$stmt->execute();
			$stmt->bind_result($id);
			while($stmt->fetch()){
				$result[] = $id;
			}
			$stmt->close();
			foreach($result as $key => $id){
				$result[$key] = $this->getInfoById($id);
			}
			return $result;
		}
		return false;
	}
	
	/*
	 * getAll()
	 * Returns all customers
	 * Returns false on error
	*/
	public function getAll(){
		$sql = "SELECT * FROM customers AS customer LEFT JOIN users AS creator ON customer.creator = creator.id;";
		$result = parent::get('db')->mysqli()->query($sql);
		if(!$result){
			return false;
		}
		$rows = parent::get('db')->fetchArray($result);
		return $rows;

		/*$stillmore = true;
		$result = array();
		$page = 0;
		while($stillmore){
			$tmp = $this->getBulk(1, $page);
			if(empty($tmp)){
				return $result;
			}
			$result[] = $tmp;
			$page++;
		}*/

	}
}
?>
