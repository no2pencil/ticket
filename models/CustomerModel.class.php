<?php
class CustomerModel extends BaseModel {

	/*
	 * deleteById(int $id)
	 * Deletes the row with the ID of $id
	 * Returns true on success, false on failure (ID not found?)
	*/
	public function deleteById($id){
		$result = $this->database->delete('customers', array('id' => $id));
		if($result > 0){
			return true;
		}
		return false;
	}

	/*
	 * get(int $id)
	 * returns user with id of $id
	*/
	public function get($id){
		$customer = $this->database->select('customers', array('id' => $id));
		return $customer[0]; // We're only getting one customer, so just return that one
	}

	/*
	 * getAll()
	 * Returns all users
	*/
	public function getAll(){
		$result = $this->database->select('customers', array());
		return $result;
	}
	
	/*
	 * isValidInfo(array $info)
	 * Looks through $info for "name", "username", "email", and "password". If these keys are found, their values will be tested to see if they are valid. If password is found, password_confirm will be checked for and if found and does not match password, will return an error message.
	 * Returns true on all valid, returns string (error message) on error.
	*/
	public function isValidInfo($info){
		if(isset($info['name'])){
			if(strlen($info['name']) == 0) return "You must enter a name";
		}
		return true;
	}

	/*
	 * addCustomer(string $name, string $email, string $phone, string $address)
	 * Adds a new customer to the database.
	 * Validity of values is not checked.
	 * $phone must be 3 numbers, space, 3 numbers, space, 4 numbers (for example, "111 222 3333"). If it does not match this pattern, it will default to empty.
	 * Returns true on success, false on failure
	*/
	public function addCustomer($name, $email, $phone, $address){
		return $this->database->insert("customers", array("name" => $name, 'email' => $email, 'phone' => $phone, 'address' => $address, "creation_timestamp" => "CURRENT_TIMESTAMP"));
	}
}