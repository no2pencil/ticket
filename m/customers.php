<?php
$content = '<h2>Customers</h2>';

if(isset($_GET['search'])){
	$content .= '<div class="well">';
	$content .= '<legend>Search</legend>';
        
	if(empty($results)){
		$content .= '<h3>No results</h3>';
	} else {
		$search_results = '';

		foreach($results as $row){
			$search_results .= '<tr><td>' . $row[name] . '</td><td>';
			$search_results .= $row[email] . '</td><td>';
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
	$page = (isset($_GET['page'])) ? $_GET['page'] : 0;
	$rng=$framework->get("customers")->ring_cntrl(1);
	/* echo "<pre>".print_r($rng)."</pre>"; */
	$rng_url = "https://service.ringcentral.com/ringout.asp?cmd=call&username=";
	$rng_num = $rng['rng_num'];
	$rng_frm = $rng['rng_frm'];
	$rng_pss = $rng['rng_pss'];
	$rng_url .= $rng_num."&password=".$rng_pss."&to=";
	$rng_end = "&from=".$rng_frm."&clid=";
	$rng_end .= $rng_frm."&prompt=1";
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
	
	//$results = $framework->get('customers')->get_bulk(10, $page);
	
	$results = $framework->get('customers')->search($_GET['search']);;
	$vieall_results = ' ';
	$content .= '<form action="customers.php" class="form-search">
        <select data-placeholder="Customers..." class="chzn-select" style="width:350px;" tabindex="2">
	<option value=""></option>';
	foreach($results as $row){
		$content .= '<option value="'.$row['name'].'">'.$row['name'].' '.$row['primaryPhone'].'</option>'; 
	}
	$content .= '</select>';

	$results = $framework->get('customers')->get_bulk(10, $page);
	foreach($results as $row) {
		$viewall_results .= '<tr><td>';
		$viewall_results .= $row['name'];
		$viewall_results .= '</td><td>';
		$viewall_results .= $row['email'];
		$viewall_results .= '</td><td><a href="';
		$viewall_results .= $rng_url;
		$viewall_results .= $row['primaryPhone'];
		$viewall_results .= '" target="_blank">';
		$viewall_results .= $row['primaryPhone'];
		$viewall_results .= '</a></td></tr>';
	}
	$content .= '</select>';
	// View all and new customer
	$content .= ' | <a href="customers.php?viewall=true" class="btn">View all</a>';
	$content .= ' | <a href="customers.php?new=true" class="btn">New customer</a>';
	$content .= '<div style="margin-bottom: 15px;"></div></form>';
	
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
