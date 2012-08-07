<?php
$content = '<h2>Customers</h2>';

// Chosen form
$content .= '
	<form action="customers.php" class="form-search">
        <select data-placeholder="Customers..." class="chzn-select" style="width:350px;" tabindex="2">
			<option value=""></option>';
$results = $framework->get('customers')->search("");;
foreach($results as $row){
	$content .= '<option value="'.$row['name'].'">'.$row['name'].' '.$row['primaryPhone'].'</option>'; 
}
$content .= '</select>';

// View all and new customer
$content .= ' | <a href="customers.php?viewall=true" class="btn">View all</a>';
$content .= ' | <a href="customers.php?new=true" class="btn">New customer</a>';
$content .= '<div style="margin-bottom: 15px;"></div></form>';

if(isset($_GET['search'])){
	$content .= '<div class="well">';
	$content .= '<legend>Search</legend>';
        
	if(empty($results)){
		$content .= '<h3>No results</h3>';
	} else {
		$search_results = '';

		foreach($results as $row){
			$search_results .= '<tr><td>' . $row['name'] . '</td><td>';
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
		</div>';
} else if(isset($_GET['new'])){
	$content .= 'New Customer Form...';
	
}
?>
