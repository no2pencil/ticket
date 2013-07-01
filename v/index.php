<?php
$host = $_SERVER['HTTP_HOST'];
$self = $_SERVER['PHP_SELF'];
$query = !empty($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : null;
$url = !empty($query) ? "http://$host$self?$query" : "http://$host$self";

	$Statuses = $framework->get('status')->getStatuses();
	$StatusTypes = $framework->get('status')->getTypes();
        $Repairs = $framework->get('tickets')->getRepairs();
	if(!isset($alert)) {
		$alert = array();
		$alert['status'] = 'warning';
		$alert['msg'] = "This project is still under heavy construciton. Work your wget magic!";
	}
?>
<body>
<div class="navbar">
  <div class="navbar-inner">
    <div class="container">
      <ul class="nav">
	<?php 
/* Replace XRMS-Mini with variable that can be set by admin panel */
	?>
        <li><a class="brand" href="#">XRMS-Mini</a></li>
        <li class="active">
          <a href="index.php"><i class="icon-white icon-home"></i> Home</a>
        </li>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <i class="icon-tags"></i> Tickets
            <b class="caret"></b>
          </a>
          <ul class="dropdown-menu">
<?php
              // This still needs some work & is not 100%
              // The idea is to only load the new ticket modal option if we
              // can assure the user is viewing a customer
              if(strpos($url,"customer")>0) {
                $CustomerData = $framework->get('customers')->getInfoById($_GET['view']);
?>
            <li><a href="#NewTicketModal" data-toggle="modal">New Ticket</a></li>
            <li class="divider"></li>
<?php } ?>
            <li>
              <form action="tickets.php" method="post" name="searchform" style="margin: 0;">
                <input type="hidden" name="search" value="<?php echo $_SESSION['user_name']; ?>">
                <input type="hidden" name="searchcols[]" value="creator">
              </form>
              <a href="#" onclick="searchform.submit();">My Tickets</a>
            </li>
            <li><a href="tickets.php?viewall=true">All Tickets</a></li>
            <li class="divider"></li>
            <li>
              <form action="tickets.php" method="post" class="form-search" style="padding: 3px 15px; margin: 0;">
                <input type="text" name="search" placeholder="Quick search" class="search-query span4">
              </form>
            </li>
            <li><a href="tickets.php?advancedsearch=true">Advanced Search</a></li>
          </ul>
        </li>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <i class="icon-user"></i> Customers
            <b class="caret"></b>
          </a>
          <ul class="dropdown-menu">
<?php if(isset($CustomerData)) { ?>
            <li><a href="#EditCustomerModal" data-toggle="modal">Edit <?php echo $CustomerData['customer.name']; ?></a></li>
            <li class="divider"></li>
<?php } ?>
            <li><a href="#NewCustomerModal" data-toggle="modal">New Customer</a></li>
            <li><a href="customers.php?viewall=true">All customers</a></li>
            <li class="divider"></li>
            <li><form id="customers_select_form" action="customers.php" method="post" class="form-search" style="padding: 3px 15px; margin: 0;">
<select id="customers_select" name="customers_select" data-placeholder="Customer Data" class="chzn-select searh-query span4"> 
              <option value=""></option> 
              <?php
                $data = $framework->get('customers')->getAll();
                foreach($data as $id => $customer) {
			$phone = $framework->get('utils')->formatSearchPhone($customer['customer.primaryPhone']);
			printf("<option value=\"%s\">%s %s</option>\n",$customer['customer.id'],$customer['customer.name'],$phone);
		}
              ?>
            </select><input type="submit" id="hiddenbutton" value=""></form></li>
            <li><a href="customers.php?advancedsearch=true">Advanced Search</a></li>
          </ul>
        </li>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <i class="icon-bar-chart"></i> Reports
            <b class="caret"></b>
          </a>
          <ul class="dropdown-menu">
            <li><a href="google.php">Customer Referrals</a></li>
            <li><a href="repairtypes.php">Repair Types</a></li>
          </ul>
        </li>
      </ul>
      <ul class="nav pull-right">
        <!-- if user is admin -->
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <i class="icon-fixed-width icon-cogs"></i> Administration
            <b class="caret"></b>
          </a>
          <ul class="dropdown-menu">
            <li><a href="#NewUserModal" data-toggle="modal"><i class="icon-user"></i> New User</a></li>
            <li><a href="users.php?viewall=true"><i class="icon-group"></i> All Users</a></li>
            <li><a href="#"><i class="icon-search"></i> Search Users</a></li>
            <li class="divider"></li>
            <li><a href="#StatusesModal" data-toggle="modal"><i class="icon-th-list"></i> Statuses</a></li>
            <li><a href="#ReferralsModal" data-toggle="modal"><i class="icon-comments"></i> Referrals</a></li>
          </ul>
        </li>
        <li><a href="logout.php">
          <i class="icon-off"></i> Logout
        </a></li>
      </ul>
    </div>
  </div>
</div>

<?php 
	/*
	 * Modals to load an overlay with the new customer, ticket, & user forms
	 */

	require_once("v/js/NewCustomerModal.php");
	require_once("v/js/NewTicketModal.php");
	require_once("v/js/NewUserModal.php");
	require_once("v/js/EditCustomerModal.php");

	require_once("v/js/ReferralsModal.php");
	require_once("v/js/StatusesModal.php");

	require_once("v/js/RingUrlModal.php");
	if($alert['msg']) {
?>
<div class="alert alert-block alert-<?php echo $alert['status']; ?>">
  <button type="button" class="close" data-dismiss="alert">&times;</button>
    <strong><?php echo $alert['msg']; ?></strong> 
</div>
<?php } ?>

		<div class="wrapper">
			<div class="content">
				<?php
				if(isset($content)) echo $content;
				else echo 'Internal error: No content was set...';
				?>
			</div>
		</div>
	<?php require_once("v/js/scripts.php"); ?>
</body>
</html>
