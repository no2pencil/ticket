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
            <li>
				<form action="tickets.php" method="post" name="searchform" style="margin: 0;">
					<input type="hidden" name="search" value="<?php echo $_SESSION['user_name']; ?>">
					<input type="hidden" name="searchcols[]" value="creator">
				</form>
				<a href="#" onclick="searchform.submit();">My Tickets</a>
			</li>
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
            <li><a href="customers.php?viewall=true">All customers</a></li>
            <li><a href="#NewCustomerModal" data-toggle="modal">New Customer</a></li>
            <li class="divider"></li>
            <li><form id="customers_select_form" action="customers.php" method="post" class="form-search" style="padding: 3px 15px; margin: 0;">
<select id="customers_select" name="customers_select" data-placeholder="Customer Data" class="chzn-select searh-query span2"> 
              <option value=""></option> 
              <?php
                $data = $framework->get('customers')->getAll();
                foreach($data as $id => $customer) {

			$phone = $framework->get('utils')->formatPhone($customer['customer.primaryPhone']);
			printf("<option value=\"%s\">%s %s</option>\n",$customer['customer.id'],$customer['customer.name'],$phone);
		}
              ?>
            </select><input type="submit" id="hiddenbutton" value=""></form></li>
            <li><a href="customers.php?advancedsearch=true">Advanced Search</a></li>
          </ul>
        </li>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <i class="icon-white icon-comment"></i> Users
            <b class="caret"></b>
          </a>
          <ul class="dropdown-menu">
            <li><a href="#NewUserModal" data-toggle="modal">New User</a></li>
            <li><a href="users.php?viewall=true">All Users</a></li>
            <li class="divider"></li>
            <li><a href="#">Search Users</a></li>
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

<?php /*
       * Modals to load an overlay with the new customer, ticket, & user forms
       */ ?>

<div id="NewCustomerModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="NewCustomerModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="NewCustomerModalLabel">New Customer</h3>
  </div>
  <div class="modal-body">
    <form action="customers.php" method="post" class="form-horizontal">
      <input type="hidden" name="savenew" value="true">
      <div class="control-group">
        <label class="control-label">Name</label>
        <div class="controls">
          <input type="text" name="name">
        </div>
      </div>
      <div class="control-group">
        <label class="control-label">Email</label>
        <div class="controls">
          <input type="text" name="email">
        </div>
      </div>
      <div class="control-group">
        <label class="control-label">Primary phone</label>
        <div class="controls">
          <input type="text" name="primaryPhone">
        </div>
      </div>
      <div class="control-group">
        <label class="control-label">Secondary phone</label>
        <div class="controls">
          <input type="text" name="secondaryPhone">
        </div>
      </div>
      <div class="control-group">
        <label class="control-label">Address</label>
        <div class="controls">
          <input type="text" name="address">
        </div>
      </div>
      <div class="control-group">
        <label class="control-label">Referral</label>
        <div class="controls">
          <input type="text" name="referral">
        </div>
      </div>
  </div>
  <div class="modal-footer">
    <button class="btn btn-primary">Save</button>
    <button class="btn btn-warning" data-dismiss="modal" aria-hidden="true">Cancel</button>
  </div>
  </form>
</div>

<div id="NewTicketModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="NewTicketModalLabel" aria-hidden="true">
  <form>
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
    <h3 id="NewCustomerModalLabel">New Ticket</h3>
  </div>
  <div class="modal-body">
  </div>
  <div class="modal-footer">
    <button class="btn btn-primary">Save</button>
    <button class="btn btn-warning" data-dismiss="modal" aria-hidden="true">Cancel</button>
  </div>
  </form>
</div>

<div id="NewUserModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="NewUserModalLabel" aria-hidden="false">
  <form action="users.php" method="post" class="form-horizontal">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
    <h3 id="NewCustomerModalLabel">New User</h3>
  </div>
  <div class="modal-body">
    <input type="hidden" name="new" value="process">
    <fieldset>
    <legend>New user</legend>
    <div class="control-group">
      <label class="control-label" for="name">Name</label>
      <div class="controls">
        <input type="text" class="input-xlarge" id="name" name="name">
      </div>
    </div>
    <div class="control-group">
      <label class="control-label" for="username">Username</label>
      <div class="controls">
        <input type="text" class="input-xlarge" id="username" name="username">
      </div>
    </div>
    <div class="control-group">
      <label class="control-label" for="password">Password</label>
      <div class="controls">
        <input type="password" class="input-xlarge" id="password" name="password">
      </div>
    </div>
    <div class="control-group">
      <label class="control-label" for="password2">Retype password</label>
      <div class="controls">
        <input type="password" class="input-xlarge" id="password2" name="password2">
      </div>
    </div>
    </fieldset>
  </div>
  <div class="modal-footer">
    <button class="btn btn-primary">Save</button>
    <button class="btn btn-warning" data-dismiss="modal" aria-hidden="true">Cancel</button>
  </div>
  </form>
</div>



<div class="alert">
  <button class="close" data-dismiss="alert">x</button>
  <strong>Warning!</strong> this project is still under heavy construciton. Work your wget magic!
</div>

		<div class="wrapper">
			<div class="content">
				<?php
				if(isset($content)) echo $content;
				else echo 'Internal error: No content was set...';
				?>
			</div>
		</div>
	<script> 
          var selects = $('.chzn-select');
          selects.chosen().change(function() {
            var selected = [];
            selects.find("option").each(function() {
              if (this.selected) {
                selected[this.value] = this;
              }
              $('#customers_select_form').submit();
            });
          });
          $('#NewCustomerModal').on('shown', function() {
            $('#NewCustomerModal').click();
          });
          $('#NewUserModal').on('shown', function () {
            //$('#NewUserModal').modal('show');
            $('#NewUserModal').click();
          });
	</script>
	</body>
</html>
