<?php
$content = '<h2>Tickets</h2>';

/*
<form action="tickets.php"><input type="text" name="view" placeholder="Ticket number" style="width: 100px;"><input type="submit" value="Open"></form> | <a href="tickets.php?viewall=true">View all</a> | ';
$content .= '<form action="tickets.php"><input type="hidden" name="newticket" value="true"><select name="ticketType">';
$types = $framework->get('tickets')->getTypes();
foreach($types as $id => $type){
	$content .= '<option value="' . $id . '">' . $type['name'] . '</option>';
}
$content .= '</select><input type="submit" value="New ticket"></form><br><br>';
*/

$content .= '
        <div class="btn-group" style="margin: 9px 0;">
	  <a href="tickets.php" class="btn">My Tickets</a>
          <a href="tickets.php?viewall=true" class="btn">View All</a>
          <a id="newticket" href="tickets.php?new=true" class="btn disabled">New Ticket</a>
	  <a href="tickets.php" class="btn">Search Tickets</a> 
        </div>
';

if(isset($_GET['view'])){
	$id = (int)$_GET['view'];
	$info = $framework->get('tickets')->getTicketById($id);
	$customer = $framework->get('customers')->getInfoById($info[customer]);
	if(!empty($info)){
		if(!empty($customer)) $info[customer]=$customer[name].' '.$customer[primaryPhone];
		$info['<hr>'] = '<hr>';
		$info['Actions'] = '<a href="tickets.php?edit=' . $id . '">Edit</a> | <a href="#" id="ticket_delete_link">Delete</a>';
		$content .= $framework->get('html')->buildTable($info, array("status_description"));
		// Ticket delete dialog:
		$content .= '<div id="ticket_delete" title="Are you sure you want to delete this ticket?">
			<p>Deleted tickets cannot be recovered.</p>
		</div>';
	} else {
		$content .= '<h3>There is no ticket with id '.$_GET[view];
	}
} else if(isset($_GET['delete'])){
	$id = (int)$_GET['delete'];
	if(isset($_GET['dodelete'])){
		$result = $framework->get('tickets')->delete($id);
		if($result){
			$content .= '<h3>The ticket has been deleted</h3>';
		} else {
			$content .= '<h3>There was an error deleting the ticket</h3>';
		}
	} else {
		$content .= '<h3>Are you sure you want to delete ticket ' . $id . '?</h3>Deleted tickets cannot be recovered. The ticket will be gone forever.<br>'; // I lied here, it'll be gone 5evr.
		$content .= '<a href="tickets.php?delete=' . $id . '&dodelete=yes">Yes</a> | <a href="tickets.php?view=' . $id . '">No</a>';
	}
} else if(isset($_GET['viewall'])){
	$page = (isset($_GET['page'])) ? (int)$_GET['page'] : 0;
	$data = $framework->get('tickets')->getBulk(10, $page * 10);
	foreach($data as $key => $ticket){
		$content .= '<tr><td>';
	}
}
?>
