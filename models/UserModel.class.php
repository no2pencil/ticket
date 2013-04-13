<?php
class UserModel extends BaseModel {

	/*
	 * deleteById(int $id)
	 * Deletes the row with the ID of $id
	 * Returns true on success, false on failure (ID not found?) 
	*/
	public function deleteById($id){
		$result = $this->database->delete('users', array('id' => $id));
		if($result > 0){
			return true;
		}
		return false;
	}

	/*
	 * getAll()
	 * Returns all users
	*/
	public function getAll(){
		$result = $this->database->select('users', array());
		foreach($result as $key => $user){
			$result[$key]['last_login_ip'] = long2ip($result[$key]['last_login_ip']); // Convert all IPs into something more user-friendly
		}
		return $result;
	}

	/*
	 * getUserByUsername($username)
	 * Selects a user by their username and returns it.
	*/
	public function getUserByUsername($username){
		$result = $this->database->select('users', array("username" => $username));
		if(!empty($result)){
			$result = $result[0];
			$result['last_login_ip'] = long2ip($result['last_login_ip']);	
		} 
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

		if(isset($info['email'])){
			if(!strpos($info['email'], "@")) return "Invalid email";
		}

		if(isset($info['password'])){
			if(strlen($info['password']) <= 4) return "Password must be longer than 4 characters";
			if(isset($info['password_confirm'])){
				if($info['password'] != $info['password_confirm']) return "Passwords do not match";
			}
		}

		return true;
	}

	/*
	 * addUser(string $email, string $password, $name)
	 * Adds a user to the database. Validity is not checked.
	 * Whether email already exists IS checked though for key consisity reasons.
	 * Returns true on success, string if username is taken.
	*/
	public function addUser($email, $password, $name){
		$checkEmail = $this->database->select('users', array("email" => $email));
		if(!empty($checkEmail)){
			return "Email is taken";
		}
		return $this->database->insert("users", array("name" => $name, "email" => $email, "password" => $this->hash($password), "creation_timestamp" => "CURRENT_TIMESTAMP"));
	}

	/*
	 * login(string $email, string $password)
	 * The result of a query will be returned. So empty array if user creds are wrong. Array with user info if correct.
	 * No session information is stored/further action is taken
	*/
	public function login($email, $password){
		$password = $this->hash($password);
		$result = $this->database->select("users", array("email" => $email, "password" => $password));
		if(!empty($result)){
			$result = $result[0]; // Basically limit our result to one row
			$result['last_login_ip'] = long2ip($result['last_login_ip']);
			$this->database->update('users', array('last_login_ip' => ip2long($_SERVER['REMOTE_ADDR'])), array('username' => $username));
		}
		return $result;
	}

	/*
	 * hash($string)
	 * Returns hash of $string 128 characters in length
	*/
	public function hash($string){
		return hash("sha512", 'dH`.}2nzr$}DGl_i)^TVtq`SQ1|i7~wpg[w~6kk_+hY@s2w+qznH%!sXM]e}$2?N' . $string . '!((E`;3DHhP75RtZ};/$a9teQN4ix~+d +^AzI=Dsy0UKtg^:%,~#w:*>,8=+b|t');
	}
}