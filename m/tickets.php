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
} else if(isset($_GET['advancedsearch'])){
	/* display search form */
	
	$content .= '
		<form action="tickets.php" method="post">
			<legend>Advanced Search</legend>
			<input type="text" name="search" placeholder="Search value">
			<label>Select columns to search:</label>';
	$cols = $framework->get('db')->getCols('tickets');
	foreach($cols as $col){
		$content .= '
			<label class="checkbox">
				<input type="checkbox" name="searchcols[]" value="' . $col . '"> ' . $col . '
			</label>';
	}
	$content .= '
			<input type="submit" class="btn" value="Search">
		</form>';
	
} 

if(isset($_POST['search'])){
	/* search logic */
	$content .= '<legend>Search results</legend>';
	$cols = (isset($_POST['searchcols'])) ? $_POST['searchcols'] : array('id', 'customer'); // TODO: Create settings & put in default search params
	$results = $framework->get('tickets')->search($_POST['search'], $cols);
	if(empty($results)){
		$content .= '<div class="alert alert-error"><strong>No results found</strong> <a href="tickets.php?advancedsearch=true">Redefine search</a></div>';
	} else {
		$content .= '
			<table class="table">
				<thead>
					<tr><th>ID</th><th>Customer</th><th>Priority</th><th>Due date</th><th>Status</th></tr>
				</thead>
				<tbody>';
		foreach($results as $row){
			$content .= '<tr><td>' . $row['ticket.id'] . 
						'</td><td>' . $row['customer.name'] . 
						'</td><td>' . $row['ticket.priority'] .
						'</td><td>' . $row['ticket.dueDate'] . 
						'</td><td>' . $row['status.status'] . ' (<a href="#" rel="tooltip" title="' . $row['status.description'] . '">?</a>)' .
						'</td>';
		}
		$content .= '
				</tbody>
			</table>';
	}
} 
if(isset($_GET['viewall'])){
	$page = (isset($_GET['page'])) ? (int)$_GET['page'] : 0;
	$data = $framework->get('tickets')->getBulk(10, $page * 10);
	$content .= '<table class="table">
					<thead>
						<tr>
						<th>Invoice</th>
						<th>Customer</th>
						<th>Priority</th>
						<th>Date</th>	
						<th>Status</th>
						</tr>
					</thead>
					<tbody>';
					
	$viewall_results = '';
	foreach($data as $ticket){
		$viewall_results .= '<tr><td>' . $ticket['ticket.invoice'] . 
							'</td><td>' . $ticket['customer.name'] . 
							'</td><td>' . $ticket['ticket.priority'] . 
							'</td><td>' . $ticket['ticket.dueDate'] . 
							'</td><td>' . $ticket['ticket.status'] . 
							'</td></tr>';
	}	
	
	if(empty($viewall_results)){
        $viewall_results .= '
                <tr><td colspan="3"><div class=\'alert alert-error\'>
                        <strong>No more customers found</strong>
                </div></td></tr>';
        $nextBtn = '<li class="disabled">
                                        <a href="#">Next</a>
                                </li>';
    } else {
		$content .= $viewall_results;
        $nextBtn = '<li>
        <a href="customers.php?viewall=true&page=' . ($page+1) . '">Next</a>
        </li>';
	}

	$content .= '
					</tbody>
				</table>';
}
?>
