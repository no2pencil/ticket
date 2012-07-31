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
		<link href="v/css/bootstrap/docs/assets/css/bootstrap.css" rel="stylesheet"> 
		<link href="v/css/bootstrap/docs/assets/css/bootstrap-responsive.css" rel="stylesheet">
		<link href="v/css/bootstrap/docs/assets/js/google-code-prettify/prettify.css" rel="stylesheet"> 
	</head>
	<body>
		<div class="wrapper">
			<div class="leftMenu">
				<a class="btn" style="text-align: left;" href="index.php">Home</a> 
				<a class="btn" style="text-align: left;" href="customers.php">Customers</a>
				<a class="btn" style="text-align: left;" href="tickets.php">Tickets</a>
				<a class="btn" style="text-align: left;" href="about.php">About</a>
				<a class="btn btn-danger" style="text-align: left;" href="logout.php">Logout</a>
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
