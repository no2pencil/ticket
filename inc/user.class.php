<?php
/*
 * user.class.php
 * Manages user functions, such as login, logout, new user, edit user, etc.
*/
class user extends framework {

	/*
	 * add(string $name, string $username, string $password, int $type)
	 * Adds a new user to the system with the given info
	 * Returns false on error
	 * Returns string on error w/ message
	*/
	public function add($name, $username, $password, $type){
		if(!$this->get_user_info($username)){
			$sql = "INSERT INTO users(username, name, password) VALUES(?, ?, ?)";
			if($stmt = parent::get("db")->mysqli()->prepare($sql)){
				$password = hash("sha512", "8iur9wurei" . $password . "jd3w8j8sl");
				$stmt->bind_param('sss', $username, $name, $password);
				$stmt->execute();
				$stmt->store_result();
				if($stmt->affected_rows > 0){
					return true;
				}
			}
			return false;
		} else {
			return "Username taken";
		}
	}
	
	/*
	 * login(string $username, string $password)
	 * Checks to see if creds are right, if so change $_SESSION['logged_in'] to true
	*/
	public function login($username, $password){
		$sql = "SELECT id FROM users WHERE username=? AND password=?";
		if($stmt = parent::get("db")->mysqli()->prepare($sql)){
			$password = hash("sha512", "8iur9wurei" . $password . "jd3w8j8sl");
			$stmt->bind_param('ss', $username, $password);
			$stmt->execute();
			$stmt->bind_result($id);
			$stmt->store_result();
			if($stmt->num_rows > 0){
				$stmt->fetch();
				$_SESSION['logged_in'] = true;
				$_SESSION['user_name'] = $username;
				$_SESSION['user_id'] = $id;
				return true;
			}
		}
		return false;
	}
	
	/*
	 * get_user_info(string $username)
	 * Returns the user's info belonging to the username
	 * Returns false if username not found
	*/
	public function get_user_info($username){
		$sql = "SELECT id, name, password, type FROM users WHERE username=?";
		if($stmt = parent::get("db")->mysqli()->prepare($sql)){
			$stmt->bind_param('s', $username);
			$stmt->execute();
			$stmt->store_result();
			if($stmt->num_rows > 0){
				$stmt->bind_result($id, $name, $password, $type);
				$stmt->fetch();
				return array("id" => $id, "name" => $name, "username" => $username, "password" => $password, "type" => $type);
			}
		}
		return false;
	}
	
	/*
	 * get_bulk(int $count, int $page)
	 * Returns an array of users info.
	*/
	public function get_bulk($count, $page){
		$result = array();
		$offset = $page * $count;
		$sql = "SELECT id, name, username, password, type FROM users LIMIT " . $count . " OFFSET " . $offset;
		if($stmt = parent::get("db")->mysqli()->prepare($sql)){
			$stmt->execute();
			$stmt->store_result();
			if($stmt->num_rows > 0){
				$stmt->bind_result($id, $name, $username, $password, $type);
				while($stmt->fetch()){
					$result[] = array("id" => $id, "name" => $name, "username" => $username, "password" => $password, "type" => $type);
				}
				return $result;
			}
			return array(); // I guess we've reached the end?
		}
		return false;
	}
	
	/*
	 * validate_password(string $password)
	 * Returns true if password matches criteria, a string if it does not.
	*/
	public function validate_password($password){
		if(strlen($password < 5)){
			return "Your password must be at least 5 characters";
		}
		return true;
	}
}
?>
