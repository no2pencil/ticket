<?php
class User extends BaseController {
	public function login(){
		if($_SESSION['logged_in'] == true){
			header('location: ' . _SITE_ROOT . '/Home/');
			die();
		}
		if(!empty($this->posts)){
			$user = new UserModel();
			$result = $user->login($this->posts['email'], $this->posts['password']);
			if(empty($result)){
				$this->view->error_msg = "Invalid email/password";
			} else {
				$_SESSION['logged_in'] = true;
				$_SESSION['email'] = $this->posts['email'];
				$_SESSION['name'] = $result['name'];
				$_SESSION['user_id'] = $result['id'];
				header('location: ' . _SITE_ROOT . '/Home/');
				die();
			}
		}
		$this->displayView(true); // Display only the login page and not the template
	}

	public function logout(){
		$_SESSION['logged_in'] = false;
		unset($_SESSION['name']);
		unset($_SESSION['email']);
		unset($_SESSION['user_id']);
		$this->view->success_msg = "Goodbye";
		$this->displayView(true, "login");
	}
}