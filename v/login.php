<?php
if(isset($_POST['username'], $_POST['password'])){
	// TODO: An actual login script
	if($_POST['username'] == "admin" && $_POST['password'] = "pass"){
		$_SESSION['logged_in'] = true;
		header('location: index.php');
		die();
	}
}
?>
<!DOCTYPE HTML>
<html>
	<head>
		<title>Ticket System</title>
		<meta charset="UTF-8" />
		<meta name="Designer" content="PremiumPixels.com">
		<meta name="Author" content="$hekh@r d-Ziner, CSSJUNTION.com">
		<link rel="stylesheet" type="text/css" href="v/css/login-reset.css">
		<link rel="stylesheet" type="text/css" href="v/css/login-structure.css">
	</head>

	<body>
		<p align=center>
			<img src="http://www.imagine-net-tech.com/images/network.jpg" width="50%">
		</p>

		<form class="box login" action="index.php" method="POST">
			<fieldset class="boxBody">
			  <label>Username</label>
			  <input type="text" name="username" tabindex="1" placeholder="" required>
			  <label><a href="#" class="rLink" tabindex="5">Forget your password?</a>Password</label>
			  <input type="password" name="password" tabindex="2" required>
			</fieldset>
			<footer>
			  <label><input type="checkbox" tabindex="3">Keep me logged in</label>
			  <input type="submit" class="btnLogin" value="Login" tabindex="4">
			</footer>
		</form>
		<footer id="main">
		  <a href="http://wwww.cssjunction.com">Simple Login Form (HTML5/CSS3 Coded) by CSS Junction</a> | <a href="http://www.premiumpixels.com">PSD by Premium Pixels</a>
		</footer>
	</body>
</html>

