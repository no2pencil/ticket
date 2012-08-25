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
  <?php
  // Include model scripts
  if(file_exists("v/js/model_scripts/" . $page . ".js")){
      echo '<script type="text/javascript" src="v/js/model_scripts/' . $pag . '.js"></script>';
  }
  ?>
  <script type="text/javascript" src="v/css/bootstrap/js/bootstrap.js"></script>
  <link href="v/css/bootstrap/css/bootstrap.css" rel="stylesheet"> 
  <link href="v/css/chosen/chosen/chosen.css" rel="stylesheet"> 
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
            <li><a href="tickets.php?viewall=true">My Tickets</a></li>
            <li><a href="tickets.php?viewall=true">All Tickets</a></li>
            <li><a href="tickets.php?new=true">New Ticket</a></li>
            <li class="divider"></li>
            <li><form action="tickets.php" method="post" class="form-search" style="padding: 3px 15px; margin: 0;"><input type="text" name="search" placeholder="Quick search" class="search-query span2"></form></li>
            <li><a href="tickets.php?advancedsearch=true">Advanced Search</a></li>
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
            <li class="divider"></li>
            <li><form action="customers.php" class="form-search" style="padding: 3px 15px; margin: 0;"><input type="text" name="search" placeholder="Quick search" class="search-query span2"></form></li>
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
            <li class="divider"></li>
            <li><a href="#">Search Customers</a></li>
          </ul>
        </li>
      </ul>
      <ul class="nav pull-right">
        <!-- if user is admin -->
        <li><a href="admin.php">
          <i class="icon-white icon-cog"></i> Administration
        </a></li>
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
				if(isset($content)) echo $content;
				else echo 'Internal error: No content was set...';
				?>
			</div>
		</div>
<script src="v/css/chosen/chosen/chosen.jquery.js" type="text/javascript"></script> 
<script type="text/javascript"> 
  $(document).ready(function() {
    $('#').click(function() {
        return false;
    });
    $(".chzn-select").chosen();
    $(".chzn-select-deselect").chosen({allow_single_deselect:true});
  });
</script> 

	</body>
</html>
