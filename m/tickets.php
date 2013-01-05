<?php
if(!isset($Statuses)) {
	$Statuses = $framework->get('status')->getStatuses();
	$StatusTypes = $framework->get('status')->getTypes();
}

if(!isset($_POST['new'])) {
	if(isset($_POST['status'])) {
		$result = $framework->get('tickets')->setStatusByID($_POST['invoice_id'], $_POST['status']);
		if(!$result) {
			$alert['status']='error';
			$alert['msg']='Something jacked up with the status update ';
		} else {
			$alert['status']='success';
			$alert['msg']='Update to status was saved';
		}
	}
	if(isset($_POST['comment'])) {
  		$return = $framework->get('comments')->setComment($_POST['invoice_id'], $_POST['comment'], date("Y-m-d"), $_SESSION['user_id']);
	}
}

$content = '<h2>Tickets</h2>';

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
			// All this needs to be fixed
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
		$comments = array();
		$comments = $framework->get('comments')->getAllByTicket($id);
		if($comments) {
			foreach($comments as $comment) {
                		$info['<hr>'] .= '<hr>Updated'.$comment[dateadded].'<br>'.$comment[comment];
			}
		}
		$info['Actions'] = '
        <!-- <form method="POST" action="tickets.php" class="well form-search"> -->
        <fieldset>  
          <div class="control-group">  
            <label class="control-label" for="textarea">Comment:</label>  
          <div class="controls">  
            <textarea class="input-xlarge" id="comment" name="comment" rows="6"></textarea>  
          </div></div> 
          <div class="form-actions">  
            <input type="hidden" name="invoice" value="'.$id.'">
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

if(isset($_POST['new'])) {
	if(isset($_GET['customer_id'])) {
		$customer_id=$_GET['customer_id'];
	}
	if(isset($_POST['customer_id'])) {
		$customer_id=$_POST['customer_id'];
	}
	if(!isset($customer_id)) {
		die("Customer ID was not set!");
	}
	$CustomerData = $framework->get('customers')->getInfoById($customer_id);

	if(isset($_POST['comment'])) {
		// Get previous human readable ticket based on current id
		$ticket_array = $framework->get('tickets')->getBulkOpen(1,1);
		$ticket_id =(int)substr($ticket_array['ticket.invoice'], -4);
		$ticket_id = date("Y").'-'.$ticket_id;
		// Incriment, & store
		while($framework->get('tickets')->searchTicketById($ticket_id)) {
			// First pass should always return true...
			$ticket_id_4=(int)substr($ticket_id, -4);
			$ticket_id_4++;
			$ticket_id = date("Y").'-'.$ticket_id_4;
		}

		$invoice_dbid = $framework->get('tickets')->add($customer_id, $ticket_id, 5, '', '', 56, '', date("Y-m-d"));
		if($invoice_dbid>0) {
			$alert['status']='success';
			$alert['msg']='Invoice '.$invoice_dbid.' created';
			if(!$framework->get('comments')->setComment($invoice_dbid, $_POST['comment'], date("Y-m-d"), $_SESSION['user_id'])) {
				$alert['status']='error';
				$alert['msg']='Unable to comment on new ticket, for whatever reason :(';
			} 
			$_GET['view']=$invoice_dbid;
		} else {
			$alert['status']='error';
			$alert['msg']='Unable to create an invoice';
		}
	} else {
		$content .= '<h3>Creating ticket for '.$CustomerData['customer.name'];
		$content .= '<table class="table">
		<tbody>
		<tr><td>
		<form method="POST" action="tickets.php?new=true" class="well form-search">
			<fieldset>
			<!-- Span6 has reported IE7 issues -->
			<div class="control-group">
				<label class="control-label" for="textarea">Comment:</label>
				<div class="controls">
					<textarea class="span6 input-xlarge" id="comment" name="comment" rows="6"></textarea>
				</div>
			</div>
			<div class="form-actions">
				<input type="hidden" name="invoice_id" value="'.$_GET['view'].'">
				<input type="hidden" name="customer_id" value="'.$customer_id.'">
				<button type="submit" class="btn btn-primary">Save</button>
				<button class="btn btn-danger">Cancel</button>
			</div>
			</fieldset>
		</form>
		</tr></td>
                </tbody>
                </table>';
	}
}

if(isset($_GET['view'])){
	$info = $framework->get('tickets')->getTicketById($_GET['view']);
	$ringurl = $framework->get('ring_central')->make_url(trim($info['customer.primaryPhone']));
	if($info) {
		$comments = $framework->get('comments')->getAllByTicket($info['ticket.id']);
		$content .= '<form action="tickets.php?view='.$_GET['view'].'" method="post">';
		$content .= '
				<h3>Viewing ticket ' . $info['ticket.invoice'] . '</h3>
				<table class="table">
					<tbody>
						<tr><th>Status</th><td colspan="2">';
		$content .= '<select name="status">';
		foreach($Statuses as $id => $Status) {
			if($Status['description']==$info['status.description']) {
				$content .= '<option value="'.$Status['id'].'"';
				if($Status['id']==$info['ticket.status']) {
					$content .= ' selected="selected"';
				}
				$content .= '>'.$Status['status'].'</option>';
			}
		}
		$content .= '</select>';
		$content .= '			</td></tr>';
		$content .= '<tr><th>Created on</th><td>'.$info['ticket.createDate'].'</td></tr>
						<tr><th>Last Updated</th><td>&nbsp;</td></tr>
                                                <tr><th>Customer</th>';
		$content .= '			<td><a href="customers.php?view='.$info['customer.id'].'">'.$info['customer.name'].'</a>&nbsp;('.$info['customer.id'].')&nbsp; ';

		if(isset($ringurl)) $content.='<a href="'.$ringurl.'" target="_blank">';
		$content .= '<span class="badge badge-warning"><i class="icon-comment icon-white"></i></span></a></td></tr>
						<tr><th>Created by</th><td>' . $info['creator.name'] . '</td></tr>
						<tr><th>Comments</th><td></td></tr>';
	foreach($comments as $comment) {
		$usersname = $framework->get('user')->get_user_info_by_id($comment['user_id']);
		$content .= '<tr><th>'.$comment['lastupdated'].'</th><td>'.$comment['comment'].'</td><td>'.$usersname['name'].'</td></tr>';
	} 
					$content .= '
					</tbody></table>
				<table class="table">
                                <tbody>
<tr><td>
        <fieldset>
          <!-- Span6 has reported IE7 issues -->
          <div class="control-group">
            <label class="control-label" for="textarea">Comment:</label>
          <div class="controls">
            <textarea class="span6 input-xlarge" id="comment" name="comment" rows="6"></textarea>
          </div></div>
          <div class="form-actions">
            <input type="hidden" name="invoice_id" value="'.$_GET['view'].'">
            <button type="submit" class="btn btn-primary">Save</button>
            <button class="btn btn-danger">Cancel</button>
          </div>
        </fieldset></form> 
</tr></td>
					</tbody>
				</table>'; 
	} else {
		$content .= 'Error: Ticket not found...';
	}

} 

if(isset($_GET['advancedsearch'])){
	/* display search form */
	$content .= '
		<form action="tickets.php" method="post">
			<legend>Advanced Search</legend>
			<input type="text" name="search" placeholder="Search value"><br>
			<input type="text" name="exclude" placeholder="Exclude from results">
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
	$searchCols = (isset($_POST['searchcols'])) ? $_POST['searchcols'] : array('id', 'customer', 'invoice');
	$exclude = (isset($_POST['exclude'])) ? $_POST['exclude'] : '';
	//$results = $framework->get('tickets')->search($_POST['search'], $exclude, $searchCols);
	$id = $framework->get('tickets')->searchTicketById($_POST['search']);
	$results = $framework->get('tickets')->getTicketById($id);
	if(empty($results)){
		$content .= '<div class="alert alert-error"><strong>No results found</strong> <a href="tickets.php?advancedsearch=true">Redefine search</a></div>';
	} else {
		$tickets = array();
		$tickets[0] = $results;
		$content .= $framework->get('tickets')->generateListDisplay($tickets);
	}
} 

if(isset($_GET['viewall'])){
	$page = (isset($_GET['page'])) ? (int)$_GET['page'] : 0;
	$data = $framework->get('tickets')->getBulk(25, $page);
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
