<?php
if($_GET['customer_id']) $customer_id=$_GET['customer_id'];
if($_POST['customers_select']) $customer_id=$_POST['customers_select'];
$content = '<h2>Customers</h2>';
$content .= '
        <div class="btn-group" style="margin: 9px 0;">
          <a href="customers.php?viewall=true" class="btn">View All</a>
          <a href="customers.php?new=true" class="btn">New Customer</a>
';
if($customer_id) {
          $content .= '<a href="customers.php?edit=true&customer_id='.$customer_id.'" class="btn">Edit Customer</a>';
}
          $content .= '<a id="newticket" href="tickets.php?new=true" data-toggle="button" class="btn">New Ticket</a>
        </div>';
$content .= '<div style="margin-bottom: 15px;"></div></form>';


if(isset($_GET['viewall'])){
	$page = (isset($_GET['page'])) ? (int)$_GET['page'] : 0;
	$data = $framework->get('customers')->getBulk(25, $page);
	$content .= '<h3>Viewing all customers</h3>';
	if(empty($data)){
		$content .= '
				<div class="alert alert-error">
					<strong>There are no more customers</strong> | <a href="javascript:history.go(-1)">Go back</a>
				</div>';
	} else {
		$content .= $framework->get('customers')->generateListDisplay($data);
		$content .= '
				<ul class="pager">
					<li class="previous">
						<a href="customers.php?viewall=true&page=' . (($page==0) ? 0 : $page-1) . '">&larr; Previous</a>
					</li>
					<li class="next">
						<a href="customers.php?viewall=true&page=' . ($page+1) . '">Next &rarr;</a>
					</li>
				</ul>';
	}
}
if(isset($_POST['savenew'])){
	$result = $framework->get('customers')->add($_POST['name'], $_POST['email'], $_POST['primaryPhone'], $_POST['secondaryPhone'], $_POST['address'], $_POST['referral']);
	if($result){
		$content .= '
			<div class="alert alert-success">
				<strong>Customer has been created</strong> | <a href="customers.php?new=true">Add another</a>
			</div>';
	} else {
		$content .= '
			<div class="alert alert-error">
				<strong>Error: </strong>Something went wrong. | <a href="#" onclick="history.go(-2)">Back</a>
			</div>';
	}
}
if(isset($_GET['new'])){
	$content .= '
		<form action="customers.php" method="post" class="form-horizontal">
			<input type="hidden" name="savenew" value="true">
			<legend>New customer</legend>
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
			<div class="form-actions">
				<button type="submit" class="btn btn-primary">Save</button>
			</div>
		</form>';
}

if($_POST['customers_select']) {
  $data = $framework->get('customers')->getInfoById($_POST['customers_select']);
        $phone = $framework->get('utils')->formatPhone($data['customer.primaryPhone']);
        $content .= '
                        <legend>Customer ID : '.$data['customer.id'].'</legend>
                        <div class="control-group">
                                <label class="control-label">Name : '.$data['customer.name'].'
                        </div>
	';
}

if($_POST['update']=='true') {
        $result = $framework->get('customers')->update($_POST['customer_id'], $_POST['name'], $_POST['email'], $_POST['primaryPhone'], $_POST['secondaryPhone'], $_POST['address'], $_POST['referral']);
        if($result){
                $content .= '
                        <div class="alert alert-success">
                                <strong>Customer has been updated</strong> | <a href="customers.php?new=true">Add another</a>
                        </div>';
        } else {
                $content .= '
                        <div class="alert alert-error">
                                <strong>Error: </strong>Something went wrong. | <a href="#" onclick="history.go(-2)">Back</a>
                        </div>';
        }

}

if($_GET['edit']=='true') {
  $data = $framework->get('customers')->getInfoById($_GET['customer_id']);
        $phone = $framework->get('utils')->formatPhone($data['customer.primaryPhone']);
        $content .= '
                <form action="customers.php" method="post" class="form-horizontal">
                        <input type="hidden" name="update" value="true">
			<input type="hidden" name="customer_id" value="'.$data['customer.id'].'">
                        <legend>Customer ID : '.$data['customer.id'].'</legend>
                        <div class="control-group">
                                <label class="control-label">Name</label>
                                <div class="controls">
                                        <input type="text" name="name" value="'.$data['customer.name'].'">
                                </div>
                        </div>
                        <div class="control-group">
                                <label class="control-label">Email</label>
                                <div class="controls">
                                        <input type="text" name="email" value="'.$data['customer.email'].'">
                                </div>
                        </div>
                        <div class="control-group">
                                <label class="control-label">Primary phone</label>
                                <div class="controls">
                                        <input type="text" name="primaryPhone" value="'.$phone.'">
                                </div>
                        </div>
                        <div class="control-group">
                                <label class="control-label">Secondary phone</label>
                                <div class="controls">
                                        <input type="text" name="secondaryPhone" value="'.$data['seondaryPhone'].'">
                                </div>
                       </div>
                        <div class="control-group">
                                <label class="control-label">Address</label>
                                <div class="controls">
                                        <input type="text" name="address" value="'.$data['address'].'">
                                </div>
                        </div>
                        <div class="control-group">
                                <label class="control-label">Referral</label>
                                <div class="controls">
                                        <input type="text" name="referral" value="'.$data['referral'].'">
                                </div>
                        </div>
                        <div class="form-actions">
                                <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                </form>';
}
?>
