<h1>Administrator / Users</h1>

<ul class="nav nav-tabs">
	<li>
		<a href="<?php echo _SITE_ROOT; ?>/Admin/users/all">All users</a>
	</li>
	<li class="active">
		<a href="#">Add user</a>
	</li>
</ul>




<form action="<?php echo _SITE_ROOT; ?>/Admin/users/add" method="post" class="form-horizontal">
	<fieldset>
		<legend>Add User</legend>
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

		<div class="control-group">
			<label class="control-label">Name</label>
			<div class="controls">
				<input type="text" name="name" placeholder="Name"/>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label">Email</label>
			<div class="controls">
				<input type="text" name="email" placeholder="Email"/>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label">Password</label>
			<div class="controls">
				<input type="password" name="password" placeholder="Password"/>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label">Password (confirm)</label>
			<div class="controls">
				<input type="password" name="password_confirm" placeholder="Password (confirm)"/>
			</div>
		</div>
		<div class="form-actions">
			<button type="submit" class="btn btn-primary">Add user</button>
		</div>
	</fieldset>
</form>
