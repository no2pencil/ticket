<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<title>Ticketing system</title>
		<link rel="stylesheet" href="v/css/style.css" type="text/css">
		<link type="text/css" href="v/css/jquery-ui/jquery-ui-1.8.21.custom.css" rel="stylesheet" />
		<script type="text/javascript" src="v/js/jquery-1.7.2.min.js"></script>
		<script type="text/javascript" src="v/js/jquery-ui-1.8.21.custom.min.js"></script>
		<script type="text/javascript" src="v/js/scripts.js"></script>
    		<link href="v/css/bootstrap/bootstrap.css" rel="stylesheet"> 
		<link href="v/css/bootstrap/bootstrap-responsive.css" rel="stylesheet"> 
		<link href="v/css/bootstrap/docs.css" rel="stylesheet"> 
	</head>
	<body>
		<div class="wrapper">
			<div class="leftMenu">
				<form> 
				<a class="btn" href="index.php">Home</a> 
				<a class="btn" href="customers.php">Customers</a>
				<a class="btn" href="tickets.php">Tickets</a>
				<a class="btn" href="about.php">About</a>
<a class="btn" href="">Link</a> 
<button class="btn" type="submit">Button</button> 
<input class="btn" type="button" value="Input"> 
<input class="btn" type="submit" value="Submit"> 
				</form>
			</div>
			<div class="content">
				<?php
				if(isset($content)){
					echo $content;
				} else {
					echo 'Internal error: No content was set...';
				}
				?>
			</div>
		</div>
	</body>
</html>
