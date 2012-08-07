<?php
$content = '<h2>Users</h2>';
// Search form
$content .= '<form action="users.php" class="form-search"><input type="text" name="search" class="input-medium search-query" placeholder="Name, username, or type"><input type="submit" value="Search" class="btn"></form>';
// Other elements
$content .= ' | <a href="users.php?viewall=true" class="btn">View all</a>';
$content .= ' | <a href="users.php?new=true" class="btn">New user</a>';
// Seperates content from this navbar thingy
$content .= '<div style="margin-bottom: 15px;"></div>';

// Page content for processing and stuff
if(isset($_GET['new'])){
	if($_GET['new'] == 'process'){
		// The form has been submitted, process data and return result
		$result = $framework->get('user')->add($_GET['name'], $_GET['username'], $_GET['password'], 0); // TODO: User permissions and such
		if($result === true){
			$content .= '
				<div class="alert alert-success">
					<strong>User account has been added</strong>
				</div>';
		} else if($result === false){
			$content .= '
				<div class="alert alert-error">
					<strong>There was an error adding the user</strong>
				</div>';
		} else {
			header('location: users.php?new=true&err=' + $result);
			die();
		}
	} else {
		// Form has not been submitted, show the form
		$content .= '
		<div class="well">
			<form class="form-horizontal">
				<input type="hidden" name="new" value="process">
				
				<fieldset>
					<legend>New user</legend>
					
					';
		if(isset($_GET['err'])){
			$content .= '
					<div class="alert alert-error" >
						<strong>Error: </strong>' . $_GET['err'] . '
					</div>';
		}
		$content .= '
					<div class="control-group">
						<label class="control-label" for="name">Name</label>
						<div class="controls">
							<input type="text" class="input-xlarge" id="name" name="name">
						</div>
					</div>
					
					<div class="control-group">
						<label class="control-label" for="username">Username</label>
						<div class="controls">
							<input type="text" class="input-xlarge" id="username" name="username">
						</div>
					</div>
					
					<div class="control-group">
						<label class="control-label" for="password">Password</label>
						<div class="controls">
							<input type="password" class="input-xlarge" id="password" name="password">
						</div>
					</div>
					
					<div class="control-group">
						<label class="control-label" for="password2">Retype password</label>
						<div class="controls">
							<input type="password" class="input-xlarge" id="password2" name="password2">
						</div>
					</div>
					
					<div class="form-actions">
						<button type="submit" class="btn btn-primary">Add new user</button>
						<a href="users.php" class="btn">Cancel</a>
					</div>
				</fieldset>
			</form>
		</div>';
	}
} else if(isset($_GET['viewall'])){
	// View all users
	$page = (isset($_GET['page'])) ? $_GET['page'] : 0;
	
	if($page<1){
		$previousBtn = '<li class="disabled">
		<a href="#">Previous</a>
		</li>';
	} else {
		$previousBtn = '<li>
		<a href="users.php?viewall=true&page=' . ($page-1) . '">Previous</a>
		</li>';
	}
	
	$results = $framework->get('user')->get_bulk(10, $page);
	//$results = $frameork->get('user')->search($_GET['search']);
	
	$viewall_results = '';
	foreach($results as $user){
		$viewall_results .= '<tr><td>' . $user['name'] . '</td><td>' . $user['username'] . '</td></tr>';
	}
	
	if(empty($viewall_results)){
		$viewall_results .= '
			<tr><td colspan="2"><div class="alert alert-error">
				<strong>No more users found</strong>
			</div></td></tr>';
		$nextBtn = '<li class="disabled">
						<a href="#">Next</a>
					</li>';
	} else {
		$nextBtn = '<li>
						<a href="users.php?viewall=true&page=' . ($page+1) . '">Next</a>
					</li>';
	}
	
	$content .= '
		<div class="well">
			<legend>Viewing all users</legend>
			<table class="table table-striped">
				<thead>
					<tr>
						<th>Name</th>
						<th>Username</th>
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
}
?>
