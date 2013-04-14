<h1>Customers / Add</h1>

<form method="post" class="form-horizontal">
	<fieldset>
		<legend>Add new customer</legend>
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
			<label class="control-label" for="name">Name</label>
			<div class="controls">
				<input type="text" id="name" name="name"/>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="email">Email</label>
			<div class="controls">
				<input type="email" id="email" name="email"/>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="phone1">Phone</label>
			<div class="controls">
				<input type="text" name="phone1" id="phone1" class="span1 text-center" maxlength="3"/>
				<input type="text" name="phone2" class="span1 text-center" maxlength="3"/>
				<input type="text" name="phone3" class="span1 text-center" maxlength="4"/>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="address">Address</label>
			<div class="controls">
				<input type="text" id="address" name="address"/>
			</div>
		</div>
		<div class="form-actions">
			<button type="submit" class="btn btn-primary">Save</button>
			<button type="button" class="btn" onclick="history.go(-1);">Cancel</button>
		</div>
	</fieldset>
</form>