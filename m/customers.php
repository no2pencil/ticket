<?php
/* Gather customer id from post or get array */
if(isset($_POST['customers_select'])) {
	$customer_id=$_POST['customers_select'];
	$_GET['view']=$customer_id;
}

if(isset($_GET['customer_id'])) {
	$customer_id=$_GET['customer_id'];
}

if(isset($_POST['savenew'])){
        $cust_id = $framework->get('customers')->add($_POST['name'], $_POST['email'], $_POST['primaryPhone'], $_POST['secondaryPhone'], $_POST['address'], $_POST['referral']);
        if($cust_id > 0){
		$alert['status']='success';
		$alert['msg']='Customer '.$cust_id.' has been created'; 
		$_GET['view']=$cust_id;
	} else {
		$alert['status']='error';
		$alert['msg']='Somethign went wrong creating a new customer';
	}
}

if(isset($_POST['update'])) {
        if(!isset($_POST['customer_id'])) {
                $alert['status']='error';
                $alert['msg']='Customer ID is not set';
        } else {
                $_GET['view']=$_POST['customer_id'];
                $result = $framework->get('customers')->update($_POST['name'], $_POST['email'], trim($_POST['primaryPhone']), trim($_POST['secondaryPhone']), $_POST['address'], $_POST['referral'], $_POST['customer_id']);
                if($result){
                        $alert['status']='success';
                        $alert['msg']='Customer '.$_POST['customer_id'].' has been updated...';
                } else {
                        $alert['status']='error';
                        $alert['msg']='Somethign went wrong updating customer'.$_POST['customer_id'];
                }
        }
}


if(isset($_GET['view'])) {
	$customer_id=$_GET['view'];
	$_POST['customers_select']=$customer_id;
}
$content = '<h2>Customers</h2>';
$content .= '<div style="margin-bottom: 15px;"></div>';


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

/*
if(isset($_GET['new'])){
	$content .= '
		<form action="customers.php?view='.$customer_id.'" method="post" class="form-horizontal">
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
*/

if(isset($_GET['view'])) {
	$data = $framework->get('customers')->getInfoById($customer_id);
	$PrimaryPhone = $framework->get('utils')->formatPhone($data['customer.primaryPhone']);
	//$SecondaryPhone = $framework->get('utils')->formatPhone($data['customer.secondaryPhone']);
	$ticket_data = $framework->get('customers')->getCustomerTickets($data['customer.id']);
	$referral = $framework->get('customers')->getReferralByID($data['customer.referral']);
	if(isset($data['customer.primaryPhone'])) {
		$ringurl = $framework->get('ring_central')->make_url(trim($data['customer.primaryPhone']));
	}
        $content .= '
                        <h4>Customer ID : '.$data['customer.id'].'</h4>
                        <div class="control-group">
                                <label class="control-label">Name : '.$data['customer.name'].'</label>
                        </div>
                        <div class="control-group">
                                <label class="control-label">Email : '.$data['customer.email'].'</label>
                        </div>
                        <div class="control-group">
                                <label class="control-label">Primary phone : '.$PrimaryPhone;
	if(isset($ringurl)) $content.='<a href="'.$ringurl.'" rel="tooltip" title="Call '.$PrimaryPhone.'" target="_blank">';
	$content .= '&nbsp;<span class="badge badge-warning"><i class="icon-comment icon-white"></i></span></a></label>
			</div>
                        <div class="control-group">
                                <label class="control-label">Secondary phone : ';
			if(isset($SecondaryPhone)) echo $SecondaryPhone;
				$content .= '</label>
                       </div>';
	if(!empty($data['customer.address'])) {
		$content .='
                       <div class="control-group">
                                <label class="control-label">Address : <a href="http://maps.google.com/?q='.$data['customer.address'].'" target="_blank"><span class="badge badge-info"><i class="icon-road icon-white"></i></span>&nbsp;'.$data['customer.address'].'</a></label>
                        </div>';
	}
	$content .= '
                        <div class="control-group">
                                <label class="control-label">Referral : '.$referral['reff.reff'].'</label>
                        </div>

			<h4>Tickets </h4>
			<div class="control-group">';
				foreach($ticket_data as $ticket_data_element) {
		                        switch ($ticket_data_element['status.status']) {
                		                case "Pending Payment":
                                		        $btn_atr='btn-success';
                                        		$btn_char='">$';
                                		break;
                                		case "Call Customer Admin":
                                		case "Call Customer Tech":
                                        		$btn_atr='btn-warning';
                                        		$btn_char=' icon-warning-sign">';
                                		break;
                                		case "In Progress":
                                        		$btn_atr='';
                                        		$btn_char=' icon-wrench">';
                                		break;
                                		case "Parts need to be ordered":
                                        		$btn_atr='btn-info';
                                        		$btn_char=' icon-shopping-cart">';
                                		break;	
                                		case "Post Payment":
                                        		$btn_atr='btn-danger';
                                        		$btn_char=' icon-fire">';
                                		break;
                                		case "Waiting for Parts":
                                        		$btn_atr='btn-info';
                                        		$btn_char=' icon-time">';
                                		break;
                                		case "Closed":
                                        		$btn_atr='btn-inverse';
                                        		$btn_char=' icon-lock">';
						break;
                               			default:
                                        		$btn_atr='';
                                        		$btn_char='">';
                                		break;
                        		}

					$content .= '<label class="control-label">Ticket : ';

					$content .= '<a href="tickets.php?view='.$ticket_data_element['ticket.id'].'" class="btn ';
					$content .= $btn_atr.'" rel="tooltip" placement="left" title="';
                        		$content .= $ticket_data_element['status.status'];
					$content .= '">';
					$content .= $ticket_data_element['ticket.invoice'];
					$content .= '<i class="icon-white '.$btn_char.'</i></a></label>';
					$content .= '<label class="control-label">Status : '.$ticket_data_element['status.status'].'</label>';
				}
				$content .='<label><hr></label>
			</div>
	';
}
?>
