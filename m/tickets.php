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

if(isset($_GET['search'])) {
	$id = (int)$_GET['search'];
	$searchResults = $framework->get('tickets')->searchTicketById($id);
	if($searchResults) {
		$info = $framework->get('tickets')->getTicketById($searchResults);	
		$customer = $framework->get('customers')->getInfoById($info[customer]);
		$type = $framework->get('tickets')->getTypeById($info[type]);
		$status = $framework->get('tickets')->getStatusById($info[status]);
	}
	else die("No results found :(");
        if(!empty($info)){
                if(!empty($customer)) $info[customer]=$customer[name].' '.$customer[primaryPhone];
		if(!empty($type)) $info[type]=$type[name];
		if(!empty($status)) {
			switch($info[status]) {
				case 19:
				case 23:
				case 62:
					$info[status]='<a href="#" class="btn btn-mini">'.$status[status].'</a>'; 
					break;
				case 20:
				case 55:
					$info[status]='<a href="#" class="btn btn-mini btn-info">'.$status[status].'</a>';
					break;
				case 55:
					$info[status]='<a href="#" class="btn btn-mini btn-success">'.$status[status].'</a>';
					break;
				case 70:
					$info[status]='<a href="#" class="btn btn-mini btn-primary">'.$status[status].'</a>';
					break;
				default :
					//$info[status]="Status!";
					break;
			}
		}
                $info['<hr>'] = '<hr>';
                $info['Actions'] = '<a href="tickets.php?edit=' . $id . '">Edit</a> | <a href="#" id="ticket_comment">Comment</a>';
                $content .= $framework->get('html')->buildTable($info, array("status_description"));
                $content .= '</div>';
        } else {
                $content .= '<h3>There is no ticket with id '.$_GET[view];
        }
}

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
} else if(isset($_GET['advancedSearch'])){
	/* display search form */
	
} else if(isset($_GET['search'])){
	/* search logic */
	
} else if(isset($_GET['viewall'])){
	$page = (isset($_GET['page'])) ? (int)$_GET['page'] : 0;
	$data = $framework->get('tickets')->getBulk(10, $page * 10);
	$content .= '<table class="table">
					<thead>
						<tr><th>ID</th><th>
					</thead>
					<tbody>';
	foreach($data as $key => $ticket){
		$content .= '<tr><td>' . $ticket['id'] . '</td></tr>';
	}
	$content .= '
					</tbody>
				</table>';
}
?>
