<?php
if(isset($_POST['comment'])) {
  date_default_timezone_set("EST");
  $return = $framework->get('comments')->setComment($_POST['invoice'], $_POST['comment'], date("Y-m-d"), 7);
  if(!$return) {
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";
  }
}

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
		$customer = $framework->get('customers')->getInfoById($info[0]['tickets.customer']);
		$type = $framework->get('tickets')->getTypeById($info[0]['tickets.type']);
		$status = $framework->get('tickets')->getStatusById($info[0]['tickets.status']);
	}
	else die("No results found :(");
        if(!empty($info)){
                if(!empty($customer)) $info[customer]='<a href="customers.php?view='.$customer[id].'">'.$customer[name].'</a> '.$customer[primaryPhone];
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
		// Currently not returning all items in the array... #2pencil
		$comments = array();
		$comments = $framework->get('comments')->getAllByTicket($id);
		if($comments) {
			foreach($comments as $comment) {
                		$info['<hr>'] .= '<hr>Updated'.$comment[dateadded].'<br>'.$comment[comment];
			}
		}
		$info['Actions'] = '
        <form method="POST" action="tickets.php" class="well form-search">
        <fieldset>  
          <div class="control-group">  
            <label class="control-label" for="textarea">Comment:</label>  
          <div class="controls">  
            <textarea class="input-xlarge" id="comment" name="comment" rows="6"></textarea>  
          </div></div> 
          <div class="form-actions">  
            <input type="hidden" name="invoice" value="'.$id.'">
            <input type="hidden" name="user_id" value="'.$id.'">
            <button type="submit" class="btn btn-primary">Save</button>  
            <button class="btn btn-danger">Cancel</button>  
          </div>  
        </fieldset></form> ';

		$content .= '
          <h4>Actions</h4>
          <div class="bs-docs-example">
            <ul class="nav nav-pills">
              <li class="active"><a href="#">Edit</a></li>
              <li><a href="#">Print</a></li>
              <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">Status<b class="caret"></b></a>
                <ul class="dropdown-menu">
                  <li><a href="#">New</a></li>
                  <li><a href="#">Open</a></li>
                  <li><a href="#">In Progres</a></li>
                  <li><a href="#">Pending Payment</a></li>
                  <li><a href="#">Post Payment</a></li>
                  <li><a href="#">Call Customer Tech</a></li>
                  <li><a href="#">Call Customer Admin</a></li>
                  <li><a href="#">Waiting For Parts</a></li>
                  <li><a href="#">Post Payment</a></li>

                  <li class="divider"></li>
                  <li><a href="#">Cancled</a></li>
                  <li><a href="#">Closed</a></li>
                </ul>
              </li>
            </ul>
          </div>';
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
} 

if(isset($_GET['advancedsearch'])){
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
		foreach($results as $result){
			$customer=array();
			$searchResults = $framework->get('tickets')->searchTicketById($result[id]);
			if($searchResults) {
				$info = $framework->get('tickets')->getTicketById($searchResults);
				$customer = $framework->get('customers')->getInfoById($info[customer]);
				$type = $framework->get('tickets')->getTypeById($info[type]);
				$status = $framework->get('tickets')->getStatusById($info[status]);
			}
			$content .= '<!-- '. print_r($result) .' -->';
			$content .= '<tr><td><a href="tickets.php?view='.$info .'">'. $info[invoice].'</a>';
			$content .= '</td><td><a href="customers.php?view='.$customer[id].'">' . $customer[name].'</a>';
			$content .= '</td><td>' . $info[priority];
			$content .= '</td><td>' . $info[dueDate];
			$content .= '</td><td>' . $status;
			$content .= '</td></tr>';
		}
		$content .= '
				</tbody>
			</table>';
	}
} 
if(isset($_GET['viewall'])){
	$page = (isset($_GET['page'])) ? (int)$_GET['page'] : 0;
	$data = $framework->get('tickets')->getBulk(25, $page * 25);
	$content .= '<h3>Viewing all tickets</h3>';
	if(empty($data)){
		$content .= '
				<div class="alert alert-error">
					<strong>There are no more tickets</strong> | <a href="javascript:history.go(-1)">Go back</a>
				</div>';
	} else {
		$content .= $framework->get('tickets')->generateListDisplay($data);
		$content .= '
				<ul class="pager">
					<li class="previous">
						<a href="tickets.php?viewall=true&page=' . (($page==0) ? 0 : $page-1) . '">&larr; Previous</a>
					</li>
					<li class="next">
						<a href="tickets.php?viewall=true&page=' . ($page+1) . '">Next &rarr;</a>
					</li>
				</ul>';
	}
}
?>
