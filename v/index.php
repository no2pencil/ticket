<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
  <title>Ticketing system</title>
  <link rel="stylesheet" href="v/css/style.css" type="text/css">
  <link type="text/css" href="v/css/jquery-ui/jquery-ui-1.8.21.custom.css" rel="stylesheet" />
  <script type="text/javascript" src="v/js/jquery-1.7.2.min.js"></script>
  <script type="text/javascript" src="v/js/jquery-ui-1.8.21.custom.min.js"></script>
  <script src="v/css/chosen/chosen/chosen.jquery.js" type="text/javascript"></script> 
  <script type="text/javascript" src="v/js/scripts.js"></script>
  <?php
  // Include model scripts
  if(file_exists("v/js/model_scripts/" . $page . ".js")){
      echo '<script type="text/javascript" src="v/js/model_scripts/' . $page . '.js"></script>';
  }
  ?>
  <script type="text/javascript" src="v/css/bootstrap/js/bootstrap.js"></script>
  <link href="v/css/bootstrap/css/bootstrap.css" rel="stylesheet"> 
  <link href="v/css/chosen/chosen/chosen.css" rel="stylesheet"> 

<!-- Google Charts API -->
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['', 'Current Ticket Progress'],
<?php
$status = array(
	"new" => 0,
	"open" => 0,
	"progress" => 0,
	"pendp" => 0,
	"admin" => 0,
	"tech" => 0,
	"postp" => 0,
	"parts" => 0
);
$data = $framework->get('tickets')->getAllOpen();
foreach($data as $row) {
        //echo "<!-- Status :".$row[status]." Value:".$row[status]."<br> -->";
	switch($row[status]) {
		case 17:
		case 51: 
		case 56:
			$status['new']++;
			break;
		case 18: // Open
			$status['open']++;
			break;
		case 19:
			$status['progress']++;
			break;
		case 68:
			$status['tech']++;
			break;
		case 70:
			$status['parts']++;
			break;
	}
}
$i=0;
foreach($status as $row) {
	//echo $row[status].":".$data[status]."<br>";
	switch($i) {
		case 0:
			$status_word = "New";
			break;
		case 1:
			$status_word = "Open";
			break;
		case 2:
			$status_word = "In Progress";
			break;
		case 3:
			$status_word = "Pending Payment";
			break;
		case 4:
			$status_word = "Call-back from Admin";
			break;
		case 5:
			$status_word = "Call-back from Tech";
			break;
		case 6:
			$status_word = "Post Payment";
			break;
		case 7:
			$status_word = "Waiting for Parts";
			break;
	}
        printf("          [\"%s\",      %s],\n",$status_word,$row);
        $i++;
}
?>
       ]);

        var options = {
          title: 'Current Ticket Progress'
        };

        var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
        chart.draw(data, options);
      }
    </script>
<!-- END Google Charts -->
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
<script type="text/javascript"> 

</script> 

	</body>
</html>
