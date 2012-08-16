<?php
if($_POST[action]=="create_user") {
  echo "<pre>";
  print_r($_POST);
  echo "</pre>";
}
else echo $_POST['action'];

$content = '<h2>Customers</h2>';

if($_GET[viewall] || !$_GET) {
// Chosen form
$content .= '
	<form action="customers.php" class="form-search">
        <select id="customers" name="customers" data-placeholder="Customers..." class="chzn-select" style="width:350px;" tabindex="2">
			<option value=""></option>';
$results = $framework->get('customers')->search("");
foreach($results as $row){
	$content .= '<option name="'.$row['name'].'" value="'.$row['name'].'">'.$row['name'].' '.$row['primaryPhoneSearch'].'</option>'; 
}
$content .= '</select>';
}

// View all and new customer
$content .= '
        <div class="btn-group" style="margin: 9px 0;">
          <a href="customers.php?viewall=true" class="btn">View All</a>
          <a href="customers.php?new=true" class="btn">New Customer</a>
          <a id="newticket" href="tickets.php?new=true" data-toggle="button" class="btn">New Ticket</a>
        </div>';
/*
$content .= '<a href="customers.php?viewall=true" class="btn">View all</a>';
$content .= ' | <a href="customers.php?new=true" class="btn">New customer</a>';
$content .= ' | <a href="tickets.php?new=true" class=" <a class="btn btn-primary btn-large">New Ticket</a>';
*/
$content .= '<div style="margin-bottom: 15px;"></div></form>';

if(isset($_GET['view'])) {
	$results = $framework->get('customers')->getInfoById($_GET[view]);
	$tickets = $framework->get('customers')->getTicketIdsForId($_GET[view]);

	$content .= '<div class="well">';
	$content .= '<legend>Customer</legend>';

	if(empty($results)) $content .= '<h3>No results</h3>';
	else {
                $index=0;
                while($index<$results[totalTickets]) {
                        // Get Ticket Number
			//$tickets[$index]="Ticket Number";
                        $results[$index+1]='<a href="/tickets.php?search=';
			$results[$index+1].=substr($tickets[$index],-4,4);
			$results[$index+1].='">'.$tickets[$index].'</a>';
                        $index++;
                }

		$content .= $framework->get('html')->buildTable($results, array(""));
	}
}

if(isset($_GET['search'])){
	$results = $framework->get('customers')->search($_GET[search]);
	$content .= '<div class="well">';
	$content .= '<legend>Search</legend>';
        
	if(empty($results)){
		$content .= '<h3>No results</h3>';
	} else {
		$search_results = '';

		foreach($results as $row){
			$search_results .= '<tr><td>';
			$search_results .= '<a href="customers.php?view='.$row[id].'">';
			$search_results .= $row['name'];
			$search_results .= '</a></td><td>';
			$search_results .= $row['email'] . '</td><td>';
			if($row['primaryPhone']) {
				$search_results .= $row['primaryPhone'];
			}
			else {
				$search_results .= '$nbsp;';
			}
			$search_results .= '</td></tr>';
		}
		
		$content .= '
			<table class="table table-striped">
				<thead>
					<tr>
						<th>Name</th>
						<th>Email</th>
						<th>Primary phone</th>
					</tr>
				</thead>
				<tbody>
					' . $search_results . '
				</tbody>
			</table>';
	}
	$content .= '</div>';
} else if(isset($_GET['viewall'])){
	// View all customers
	$page = (!isset($_GET['page'])) ? 0 : (int)$_GET['page'];
	
	if($page<1){
		$previousBtn = '<li class="disabled">
		<a href="#">Previous</a>
		</li>';
	} else {
		$previousBtn = '<li>
		<a href="customers.php?viewall=true&page=' . ($page-1) . '">Previous</a>
		</li>';
	}
	
	$results = $framework->get('customers')->get_bulk(10, $page);
	
	$viewall_results = ' ';
	foreach($results as $row) {
		$viewall_results .= '<tr><td>' . $row['name'] . '</td><td>';
		$viewall_results .= $row['email'] . '</td>';
		
		$ring_central_callout = $framework->get('ring_central')->make_url($row['primaryPhone_raw']);
		if(!empty($ring_central_callout)){
			$viewall_results .= '<td><a href="' . $ring_central_callout . '" target="_blank">' . $row['primaryPhone'] . '</a></td>';
		} else {
			$viewall_results .= '<td>' . $row['primaryPhone'] . '</td>';
		}
		
		$viewall_results .= '</tr>';
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
		$nextBtn = '<li>
						<a href="customers.php?viewall=true&page=' . ($page+1) . '">Next</a>
					</li>';
	}
	
	$content .= '
		<div class="well">
			<legend>Viewing all users</legend>
			<table class=\'table table-striped\'>
				<thead>
					<tr>
						<th>Name</th>
						<th>Email</th>
						<th>Primary Phone</th>
					</tr>
				</thead>
				<tbody>
					' . $viewall_results . '
				</tbody>
			</table>
			<ul class="pager">
				' . $previousBtn . $nextBtn . '
			</ul>
		</div>
<script type="text/javascript"> 
  $(document).ready(function() {
    $(".chzn-select").chosen().change(function (event) {
      alert("Customer Change Detected");
      $("#newticket").attr("disabled", false);
    });
  });
</script> 
';

} else if(isset($_GET['new'])){
	$content .= 'New Customer Form...';
 	$content .= '<form id="create_user" method="POST"><table>';
	$content .= '<thead><tr>';
	$content .= '<th class="left">Name:&nbsp;</th>';
	$content .= '<td><input type="text" id="uname" name="uname"></td>';
	$content .= '</tr></thead><tbody><tr>';
        $content .= '<th>Primary Phone:</th>';
        $content .= '<td><input type="text" id="prime_phone" name="prime_phone"></td>';
	$content .= '</tr><tr>';
        $content .= '<th>Secondary Phone:</th>';
        $content .= '<td><input type="text" id="secondary_phone" name="secondary_phone"></td>';
	$content .= '</tr><tr>';
	$content .= '<th>Email:&nbsp;</th>';
	$content .= '<td><input type="text" id="email" name="email"></td>';
	$content .= '</tr><tr>';
	$content .= '<th>Zip Code:&nbsp;</th>';
	$content .= '<td><input type="text" id="email" name="zip"></td>';
	$content .= '</tr><tr>';
	$content .= '<th>Reference:&nbsp;</th>';
	$content .= '<td>';
	$content .= '<input type="hidden" id="action" name="action" value="create_user">';
        $content .= '<select id="customers" name="customers" data-placeholder="Customers..." class="chzn-select" style="width:350px;">';
	$content .= '<option value=""></option>';
	$results = $framework->get('customers')->reff();
	foreach($results as $row){
        	$content .= '<option name="'.$row.'" value="'.$row.'">'.$row.'</option>';
	} 
	$content .= '</select>';
	$content .= '</td>';
	$content .= '</tr><tr>';
	$content .= '<th>Action</th><td><a id="create_button" href="#" class="btn btn-success"><i class="icon-white icon-off"></i>&nbsp;Create</a></td>';
	$content .= '</tr></tbody></table></form>';
	$content .= '<script>';
	$content .= '$("a").click(function(e) {
	e.preventDefault();
	$("#create_user").submit();
	});';
	$content .= '</script>';
}
?>
