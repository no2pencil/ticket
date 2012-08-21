<?php
class customers extends framework {
	//public function add($name, $email, $primaryPhone, $secondaryPhone, $address, $referral){
	public function add($post_array) {
		date_default_timezone_set("EST");
		$sql = 'INSERT INTO customers(name, primaryPhone, secondaryPhone, email, address, referral, createDate) VALUES(';
		foreach($post_array as $post) {
			if($post!="create_user") $sql .= '\''.$post.'\',';
		}
		$sql .= '\''.date("Y-m-d").'\')';
		//$sql = "INSERT INTO customers(name, email, primaryPhone, secondaryPhone, address, referral, createDate) VALUES(?, ?, ?, ?, ?, ?, ?)";
		echo $sql;
		if($stmt = parent::get("db")->mysqli()->prepare($sql)){
			//$stmt->bind_param('sssssss', $post_array[0], $post_array[3], $post_array[1], $post_array[2], $post_array[4], $post_array, date("Y-m-d"));
			$createDate = parent::get('utils')->timestamp();
			$stmt->execute();
			$stmt->store_result();
			if($stmt->affected_rows > 0){
				return parent::get('db')->mysqli()->insert_id;
			}
		}
		return false;
	}

	/*
	 * reff()
	 * Returns references & their id
	*/
	public function reff() {
		$sql = "SELECT id, reff from reff"; 
                $result = parent::get('db')->mysqli()->query($sql);
                $fresult = array();
                while($row = $result->fetch_array()){
			$fresult[$row[id]]=array(
				"id" => $row[id],
				"reff" => $row[reff]
			);
                }
                return $fresult;
	}
	
	/*
	 * search(string $query)
	 * Searches for a customer using LIKE and the query
	 *
	 * TODO: Refine to make more accurate
	*/
	public function search($query){
		$sql = "SELECT id FROM customers WHERE customers.name LIKE ? OR customers.primaryPhone LIKE ? OR customers.email LIKE ?";
		if($stmt = parent::get("db")->mysqli()->prepare($sql)){
			$query = '%' . $query . '%';
			$stmt->bind_param('sss', $query, $query, $query);
			$stmt->execute();
			$stmt->bind_result($id);
			$stmt->store_result();
			if($stmt->num_rows == 0){
				return array();
			}
			$result = array();
			while($stmt->fetch()){
				$result[] = $id;
			}
			foreach($result as $key => $id){
				$result[$key] = $this->getInfoById($id);
			}
			return $result;
		}
		return false;
	}
	
	/*
	 * getInfoById(int $id)
	 * Returns an array with customer information based on the ID given
	*/
	public function getInfoById($id){
		$sql = "SELECT name, email, primaryPhone, secondaryPhone, address, referral FROM customers WHERE id=?;";
		if($stmt = parent::get("db")->mysqli()->prepare($sql)){
			$stmt->bind_param('i', $id);		
			$stmt->execute();
			$stmt->bind_result($name, $email, $primaryPhone, $secondaryPhone, $address, $referral);
			$stmt->store_result();
			if($stmt->num_rows > 0){
				$stmt->fetch();
				return array(
							"id" => $id,
							"name" => $name,
							"email" => $email,
							"primaryPhone_raw" => $primaryPhone,
							"secondaryPhone_raw" => $secondaryPhone,
							"primaryPhone" => parent::get('utils')->formatPhone($primaryPhone),
							"secondaryPhone" => parent::get('utils')->formatPhone($secondaryPhone),
							"primaryPhoneSearch" => parent::get('utils')->formatSearchPhone($primaryPhone),
							"address" => $address,
							"referral" => $referral,
							"totalTickets" => $this->getTicketCountForId($id),
							"openTickets" => $this->getTicketCountForId($id, true)
							);
			}
		} else {
			echo parent::get("db")->mysqli()->error . "<br>";
		}
		return false;
	}

	public function getTicketIdsForId($id, $onlyopen=false) {
		$sql = "SELECT invoice from tickets where customer=?";
		if($onlyopen) $sql .= " AND status > 0";
		if($stmt = parent::get('db')->mysqli()->prepare($sql)) {
			$stmt->bind_param('i', $id);
			$stmt->execute();
                        $stmt->bind_result($invoice);
			$result=array();
			while ($stmt->fetch()) {
                                $result[] = $invoice;
			}
                        $stmt->close();
                        return $result;
		}
		return false;
	}
	
	public function getTicketCountForId($id, $onlyopen=false){
		$sql = "SELECT * FROM tickets WHERE customer=?";
		if($onlyopen){
			$sql .= " AND status != 0";
		}
		if($stmt = parent::get('db')->mysqli()->prepare($sql)){
			$stmt->bind_param('i', $id);
			$stmt->execute();
			$stmt->store_result();
			return $stmt->num_rows;
		}
		return false;
	}
	
	/*
	 * get_bulk(int $count, int $page)
	 * Gets bulk information of customers
	 * Returns their information in an array
	 * Limited to $count, 0 for unlimited
	 * Use $page if you have $count set for multiple pages
	*/
	public function get_bulk($count, $page){
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
	
	public function getAll(){
		//return $this->getBulk(10, 0);
		return true;
	}
}
?>
