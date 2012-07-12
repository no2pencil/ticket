<?php
$content = '<h2>Customers</h2>
<form action="customers.php"><input type="text" name="search" placeholder="Name, phone, ticket number, or email" style="width: 225px;"><input type="submit" value="Search"></form> | <a href="customers.php?viewall=true">View all</a> | <a href="customers.php?newcustomer=true">New customer</a><br><br>';

if(isset($_GET['search']) && !empty($_GET['search'])){
	$results = $framework->get('customers')->search($_GET['search']);
	if(empty($results)){
		$content .= '<h3>No results</h3>';
	} else {
		$content .= '<table><tr><td>Name</td><td>Email</td><td>Primary Phone</td></tr>';
		foreach($results as $row){
			$content .= '<tr><td>' . $row['name'] . '</td><td>' . $row['email'] . '</td><td>' . $row['primaryPhone'] . '</td></tr>';
		}
		$content .= '</table><br><br>';
		$content .= $framework->get('html')->buildTable($results);
	}
} else if(isset($_GET['viewall'])){
	$page = (!isset($_GET['page'])) ? 0 : (int)$_GET['page'];
	$offset = $page * 10;
	$result = $framework->get('customers')->getBulk(10, $offset);
	if(empty($result)){
		$content .= '<h3>No results</h3>';
	} else {
		$content .= '<table class="zebra"><tr><th>Name</th><th>Email</th><th>Primary Phone</th></tr>';
		foreach($result as $row){
			$content .= '<tr><td><a href="customers.php?view=' . $row['id'] . '">' . $row['name'] . '</a></td><td>' . $row['email'] . '</td><td>' . $row['primaryPhone'] . '</td></tr>';
		}
		$content .= '</table><br><br>';
	}
	
	$content .= '<center>';
	if($page > 0){
		$content .= '<a href="customers.php?viewall=true&page=' . ($page-1) . '">Previous</a>';
	} else {
		$content .= 'Previous';
	}
	$content .= ' | <form action="customers.php"><input type="hidden" name="viewall" value="true"><input type="text" name="page" value=' . $page . ' style="width: 20px;"></form> | <a href="customers.php?viewall=true&page=' . ($page+1) . '">Next</a></center>';
} else if(isset($_GET['view'])){
	$id = (int)$_GET['view'];
	$info = $framework->get('customers')->getInfoById($id);
	$content .= '<h3>' . $info['name'] . '</h3>';
	$types = $framework->get('tickets')->getTypes();
	$newticket = '<form action="tickets.php"><input type="hidden" name="customer" value="' . $id . '"><input type="hidden" name="newticket" value="true"><select name="ticketType">';
	foreach($types as $id => $type){
		$newticket .= '<option value="' . $id . '">' . $type['name'] . '</option>';
	}
	$newticket .= '</select><input type="submit" value="New ticket"></form>';
	$content .= '<table>
					<tr><td>Email</td><td>' . $info['email'] . '</td></tr>
					<tr><td>Primary Phone</td><td>' . $info['primaryPhone'] . '</td></tr>
					<tr><td>Secondary Phone</td><td>' . $info['secondaryPhone'] . '</td></tr>
					<tr><td>Address</td><td>' . $info['address'] . '</td></tr>
					<tr><td>Referral</td><td>' . $info['referral'] . '</td></tr>
					<tr><td>Tickets:</td><td>' . $info['openTickets'] . ' open, ' . $info['totalTickets'] . ' total</td></tr>
					<tr><td>Actions</td><td>' . $newticket . ' | <a href="customers.php?edit=' . $id . '">Edit</a> | <a href="customers.php?delete=' . $id . '">Delete</a></td></tr></table>';
} else if(isset($_GET['delete'])){
	$id = (int)$_GET['delete'];
	if(isset($_GET['continue'])){
		$result = $framework->get('customers')->delete($id);
		if($result){
			$content .= '<h3>Customer has been deleted</h3>';
		} else {
			$content .= '<h3>There was an error deleting the customer</h3>';
		}
	} else {
		$info = $framework->get('customers')->getInfoById($id);
		$content .= '<h3 style="color: red;">You are about to delete ' . $info['name'] . '</h3><h4>' . $info['name'] . ' has ' . $info['openTickets'] . ' open tickets and ' . $info['totalTickets'] . ' total tickets. These will be marked as closed with the appended note of "customer was deleted".</h4>';
		$content .= 'Are you sure you want to continue? <a href="customers.php?delete=' . $id . '&continue=true">Yes</a> | <a href="customers.php?view=' . $id . '">No</a>';
	}
} else if(isset($_GET['newcustomer'])){
	$content .= '<h3>New customer</h3>';
	$content .= '<form action="customers.php" method="post">
					<input type="hidden" name="createCustomer" value="true"> 
						<table>';
	if(isset($_GET['error'])){
		$content .= '<tr><td style="font-weight: bold; color: red;">' . $_GET['error'] . '</td><td></td></tr>';
	}
	$content .= '
							<tr><td>Name</td><td><input type="text" name="name"></td></tr>
							<tr><td>Email</td><td><input type="text" name="email"></td></tr>
							<tr><td>Primary Phone</td><td><input type="text" name="primaryPhone"></td></tr>
							<tr><td>Secondary Phone</td><td><input type="text" name="secondaryPhone"></td></tr>
							<tr><td>Address</td><td><input type="text" name="address"></td></tr>
							<tr><td>Referral</td><td><input type="text" name="referral"></td></tr>
							<tr><td></td><td><input type="submit" value="Create" style="width: 100%;"></td></tr>
						</table>
				  </form>';
} else if(isset($_POST['createCustomer'])){
	if(!empty($_POST['name'])){
		$result = $framework->get('customers')->add($_POST['name'], $_POST['email'], $_POST['primaryPhone'], $_POST['secondaryPhone'], $_POST['address'], $_POST['referral']);
		if($result === false){
			$content .= '<h3>There was an error</h3>';
		} else {
			$content .= '<h3>User has been successfully created</h3><a href="customers.php?view=' . $result . '">View customer</a> | <a href="tickets.php?new=true&customer=' . $result . '">Create ticket for customer</a>';
		}
	} else {
		header('location: customers.php?newcustomer=true&error=The name field is required');
	}
}
?>