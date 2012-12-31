<?php
	$Statuses = $framework->get('status')->getStatuses();
	$StatusTypes = $framework->get('status')->getTypes();
	if(!isset($alert)) {
		$alert = "This project is still under heavy construciton. Work your wget magic!";
	}
?>
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
            <i class="icon-tags"></i> Tickets
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
            <i class="icon-user"></i> Customers
            <b class="caret"></b>
          </a>
          <ul class="dropdown-menu">
            <li><a href="#NewCustomerModal" data-toggle="modal">New Customer</a></li>
            <li><a href="customers.php?viewall=true">All customers</a></li>
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
            <i class="icon-comment"></i> Users
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
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <i class="icon-cog"></i> Administration
            <b class="caret"></b>
          </a>
          <ul class="dropdown-menu">
            <li><a href="#NewUserModal" data-toggle="modal">New User</a></li>
            <li class="divider"></li>
            <li><a href="#StatusesModal" data-toggle="modal">Statuses</a></li>
            <li><a href="#ReferralsModal" data-toggle="modal">Referrals</a></li>
          </ul>
        </li>

	<!--
        <li><a href="admin.php">
          <i class="icon-cog"></i> Administration
        </a></li>
	-->
        <li><a href="logout.php">
          <i class="icon-off"></i> Logout
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
    <h3 id="NewTicketModalLabel">New Ticket</h3>
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
    <h3 id="NewUserModalLabel">New User</h3>
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

<?php /*
       * Modals for Status management
       */ ?>
<div id="StatusesModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="StatusesModalLabel" aria-hidden="false">
  <form action="" method="post" class="form-horizontal">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
    <h3 id="StatusesModalLabel">Status Management</h3>
  </div>
  <div class="modal-body">
    <input type="hidden" name="new" value="process">
    <fieldset>

    <legend>Status Type</legend>
    <div class="control-group">
      <ul class="nav nav-pills">
        <?php 
		foreach($StatusTypes as $Type) {
        ?>
        <li class="dropdown">
          <a class="dropdown-toggle" data-toggle="dropdown" href="#">
		<?php echo $Type['name']; ?>
          <b class="caret"></b></a>
          <ul class="dropdown-menu">
		<?php 
			foreach($Statuses as $Status) { 
				if(isset($Status)) {
					if($Type['id'] == $Status['description']) {
            					echo '<li><a href="#">';
						echo $Status['status']; 
						echo '</a></li>';
					}
				}
			} 
		?>
          </ul>
        </li>
	<?php } ?>
      </ul>
    </div>

    <legend>New Status</legend>
    <div class="control-group">
      <label class="control-label" for="typename">Status Type</label>
      <div class="controls">
        <input type="text" class="input-xlarge" id="typename" name="typename">
      </div>
    </div>
    <div class="control-group">
      <label class="control-label" for="status">Status Name</label>
      <div class="controls">
        <input type="text" class="input-xlarge" id="status" name="status">
      </div>
    </div>
  </div>
  <div class="modal-footer">
    <button class="btn btn-primary">Save</button>
    <button class="btn btn-warning" data-dismiss="modal" aria-hidden="true">Cancel</button>
  </div>
  </form>
</div>

<?php /*
       * Modals for Status management
       */ ?>
<div id="ReferralsModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="ReferralsModalLabel" aria-hidden="false">
  <form action="" method="post" class="form-horizontal">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
    <h3 id="ReferralsModalLabel">Referrals Management</h3>
  </div>
  <div class="modal-body">
    <input type="hidden" name="new" value="process">
    <fieldset>
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
  <strong>Warning!</strong> <?php echo $alert; ?>
</div>

		<div class="wrapper">
			<div class="content">
				<?php
				if(isset($content)) echo $content;
				else echo 'Internal error: No content was set...';
				?>
			</div>
		</div>
	<?php require_once("scripts.php"); ?>
</body>
</html>
