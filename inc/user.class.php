<?php
/*
 * user.class.php
 * Manages user functions, such as login, logout, new user, edit user, etc.
*/
class user extends framework {

	/*
	 * add(string $name, string $username, string $password, int $type)
	 * Adds a new user to the system with the given info
	*/
	public function add($name, $username, $password, $type){
		$sql = "INSERT INTO users(username, name, password) VALUES(?, ?, ?)";
		if($stmt = $framework->get("db")->mysqli()->prepare($sql)){
			$password = hash("sha512", "8iur9wurei" . $password . "jd3w8j8sl");
			$stmt->bind_param('sss', $username, $name, $password);
			$stmt->execute();
			$stmt->store_result();
			if($stmt->affected_rows > 0){
				return true;
			}
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