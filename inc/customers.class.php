<?php
class customers extends framework {
	public function add($name, $email, $primaryPhone, $secondaryPhone, $address, $referral){
		$sql = "INSERT INTO customers(name, email, primaryPhone, secondaryPhone, address, referral, createDate) VALUES(?, ?, ?, ?, ?, ?, ?)";
		if($stmt = parent::get("db")->mysqli()->prepare($sql)){
			$stmt->bind_param('ssiisss', $name, $email, $primaryPhone, $secondaryPhone, $address, $referral, $createDate);
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
"primaryPhoneDial" => $primaryPhone,
"secondaryPhoneDial" => $secondaryPhone,
"primaryPhone" => parent::get('utils')->formatPhone($primaryPhone),
"secondaryPhone" => parent::get('utils')->formatPhone($secondaryPhone),
"primaryPhoneSearch" => parent::get('utils')->formatSearchPhone($primaryPhone),
"address" => $address,
"referral" => $referral,
"totalTickets" => $this->getTicketCountForId($id),
"openTickets" => $this->getTicketCountForId($id, true));
			}
		} else {
			echo parent::get("db")->mysqli()->error . "<br>";
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
