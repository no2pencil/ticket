<?php
if(isset($_POST['username'], $_POST['password'])){
	if($framework->get('user')->login($_POST['username'], $_POST['password'])){
		header('location: index.php');
		die();
	} else {
		header('location: login.php?msg=Error: Wrong username or password');
		die();
	}
}
?>