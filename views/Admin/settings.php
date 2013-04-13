<h1>Administrator / Settings</h1>

<form action="<?php echo _SITE_ROOT; ?>/Admin/settings/save" method="post" class="form-horizontal">
	<fieldset>
		<?php
		foreach($view->settings as $setting){
		?>
			<div class="control-group">
				<label class="control-label"><?php echo $setting['keyname']; ?></label>
				<div class="controls">
					<input class="input-block-level" type="text" name="<?php echo str_replace(' ', '_', $setting['keyname']); ?>" value="<?php echo $setting['keyvalue']; ?>"/><?php if(!empty($setting['helptext'])){ ?><span class="help-inline"><?php echo $setting['helptext']; ?></span><?php } ?>
				</div>
			</div>
		<?php
		}
		?>
		<div class="form-actions">
			<button type="submit" class="btn btn-primary">Save changes</button>
			<button type="button" class="btn">Cancel</button>
		</div>
	</fieldset>
</form>