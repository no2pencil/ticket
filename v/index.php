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
  <script type="text/javascript" src="v/css/bootstrap/docs/assets/js/bootstrap-dropdown.js"></script>
  <link href="v/css/bootstrap/docs/assets/css/bootstrap.css" rel="stylesheet"> 
  <link href="v/css/bootstrap/docs/assets/css/bootstrap-responsive.css" rel="stylesheet">
  <link href="v/css/bootstrap/docs/assets/js/google-code-prettify/prettify.css" rel="stylesheet"> 
</head>
<body>
<div class="navbar">
  <div class="navbar-inner">
    <div class="container">
      <ul class="nav">
        <li><a class="brand" href="#">XRMS-Mini</a></li>
        <li class="active">
          <a href="index.php"><i class="icon-white icon-home"></i> Home</a>
        </li>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <i class="icon-white icon-tags"></i> Tickets
            <b class="caret"></b>
          </a>
          <ul class="dropdown-menu">
            <li><a href="tickets.php">My Tickets</a></li>
            <li><a href="tickets.php?new=true">New Ticket</a></li>
            <li><a href="#">Search </a></li>
          </ul>
        </li>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <i class="icon-white icon-user"></i> Customers
            <b class="caret"></b>
          </a>
          <ul class="dropdown-menu">
            <li><a href="customers.php?new=true">New Customer</a></li>
            <li><a href="customers.php?viewall=true">View Customers</a></li>
            <li><a href="#">Search Customers</a></li>
          </ul>
        </li>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <i class="icon-white icon-comment"></i> Users
            <b class="caret"></b>
          </a>
          <ul class="dropdown-menu">
            <li><a href="users.php?new=true">New User</a></li>
            <li><a href="users.php?viewall=true">All Users</a></li>
            <li><a href="#">Search Customers</a></li>
          </ul>
        </li>
      </ul>
      <ul class="nav pull-right">
        <li><a href="logout.php">
          <i class="icon-white icon-off"></i> Logout
        </a></li>
      </ul>
    </div>
  </div>
</div>

<div class="alert">
  <button class="close" data-dismiss="alert">x</button>
  <strong>Warning!</strong> this project is still under heavy construciton. Tread lightly!
</div>

		<div class="wrapper">
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
