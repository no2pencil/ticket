<h1>Administrator / Settings</h1>

<form action="<?php echo _SITE_ROOT; ?>/Admin/settings/save" method="post" class="form-horizontal">
	<?php
	if(isset($view->success_msg)){
		?>
		<div class="alert alert-success">
			<?php echo $view->success_msg; ?>
		</div>
	<?php
	}
	?>
	<fieldset>
		<?php
		/* Group all categories together so we can display them appropriately */
		foreach($view->settings as $setting){
			if(empty($settings['category'])){
				$settings['category'] = 'Other';
			}
			if(!isset($categories[$settings['category']])){
				$categories[$settings['category']] = array();
			}
			$categories[$settings['category']][] = $setting;
		}

		/* Display these settings */
		foreach($categories as $category => $values){
			?><legend><?php echo $category; ?></legend>
			<?php
			foreach($values as $setting){ 
			?>
				<div class="control-group">
					<label class="control-label"><?php echo $setting['keyname']; ?></label>
					<div class="controls">
						<input class="span6" type="text" name="setting_<?php echo str_replace(' ', '_', $setting['keyname']); // When a name with spaces posts, the spaces are converted to underscores. Let's just do that here instead for consistency reasons. ?>" value="<?php echo $setting['keyvalue']; ?>"/><?php if(!empty($setting['helptext'])){ ?><span class="help-inline"><?php echo $setting['helptext']; ?></span><?php } ?>
					</div>
				</div>
		<?php
			}
		}
		?>
		<div class="form-actions">
			<button type="submit" class="btn btn-primary">Save changes</button>
		</div>
	</fieldset>
</form>