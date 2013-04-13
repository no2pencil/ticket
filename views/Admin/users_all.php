<h1>Administrator / Users</h1>

<ul class="nav nav-tabs">
	<li class="active">
		<a href="#">All users</a>
	</li>
	<li>
		<a href="<?php echo _SITE_ROOT; ?>/Admin/users/add">Add user</a>
	</li>
</ul>

<?php
if(isset($view->success_msg)){
	?>
	<div class="alert alert-success">
		<?php echo $view->success_msg; ?>
	</div>
	<?php
}
?>

<?php if(isset($view->error_msg)){ ?>
	<div class="alert alert-error">
		<?php echo $view->error_msg; ?>
	</div>
<?php } ?>

<div class="tab-pane active" id="allUsers">
	<table class="table">
		<thead>
			<tr><th>ID</th><th>Name</th><th>Email</th><th>Last logged in from IP</th><th>Actions</th></tr>
		</thead>
		<tbody>
			<?php
			if(empty($view->users)){
			?>
			<tr>
				<td colspan="4">
					<div class="alert alert-error">
						No users were found.
					</div>
				</td>
			</tr>
			<?php
			} else {
				foreach($view->users as $user){
					?>
						<tr>
							<td><?php echo $user['id']; ?></td>
							<td><?php echo $user['name']; ?></td>
							<td><?php echo $user['email']; ?></td>
							<td><?php echo $user['last_login_ip']; ?></td>
							<td>
								<div class="btn-group">
									<button class="btn btn-mini">Edit</button>
									<button class="btn btn-mini btn-danger" data-toggle="popover" title="" data-placement="bottom" data-html="true" data-content='<div class="btn-group"><a href="<?php echo _SITE_ROOT; ?>/Admin/users/delete?user=<?php echo $user['id']; ?>" class="btn btn-danger span1">Delete user</a></div>' data-original-title="Are you sure?">Delete</button>
								</div>
							</td>
						</tr>
					<?php
				}
			}
			?>
		</tbody>
	</table>
</div>