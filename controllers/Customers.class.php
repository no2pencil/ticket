<?php
class Customers extends BaseController {
	public function index(){

	}

	public function view(){		
		$customer = new CustomerModel();
		if(isset($this->gets['id'])){
			$info = $customer->get($this->gets['id']);
			$this->view->name = $info['name'];
			$this->view->address = $info['address'];
			$this->view->email = $info['email'];
			$this->view->phone = $info['phone'];
			$this->displayView();
		}
	}

	public function add(){
		$customer = new CustomerModel();
		if(!empty($this->posts)){
			$valid = $customer->isValidInfo($this->posts);
			if($valid !== true){
				$this->view->error_msg = $valid;
			} else {
				$result = $customer->addCustomer($this->posts['name'], $this->posts['email'], $this->posts['phone1'] . ' ' . $this->posts['phone2'] . ' ' . $this->posts['phone3'], $this->posts['address']);
				if($result){
					$this->view->success_msg = "User has been saved.";
				} else {
					$this->view->error_msg = "There was an error";
				}
			}
		}
		$this->displayView();
	}
}
?>