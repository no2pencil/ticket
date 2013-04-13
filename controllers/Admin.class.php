<?php
class Admin extends BaseController {
	public function users(){
		$users = new UserModel();
		if($this->gets['id'] == "add"){ // The add user form was submitted
			if(!empty($_POST)){
				$valid = $users->isValidInfo($this->posts); // Check for validity
				if($valid === true){ // We're valid, insert it.
					$result = $users->addUser($this->posts['email'], $this->posts['password'], $this->posts['name']);
					if($result === true){
						$this->view->success_msg = 'User has been added'; // Insert worked
					} else {
						$this->view->error_msg = $result; // Insert failed (probably email taken)
					}
				} else {
					$this->view->error_msg = $valid; // Invalid, display that error
				}
			}
			$this->displayView(false, "users_add"); // display the add users page
		} else if($this->gets['id'] == "delete"){
			$result = $users->deleteById($this->gets['user']);
			if($result){
				$this->view->success_msg = 'User has been deleted';
			} else {
				$this->view->error_msg = 'There was an error deleting the user: ' . $result;
			}
		}

		/* No actions taken or anything, display the main admin/users page */
		$this->view->users = $users->getAll(); // For the all users tab
		$this->displayView(false, "users_all");
	}
}