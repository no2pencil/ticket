<?php
$content = '<h2>Site Settings</h2>';
$content .= '<div style="margin-bottom: 15px;"></div>';


$content .= '
	<div class="tabbable">
		<ul class="nav nav-tabs">
			<li class="active"><a href="#tab1" data-toggle="tab">Appearance</a></li>
			<li><a href="#tab2" data-toggle="tab">Statuses</a></li>
		</ul>
		<div class="tab-content">
			<div class="tab-pane active" id="tab1">
				<form action="settings.php" method="post">
					
					<input type="submit" name="appearance" value="Save">
				</form>
			</div>
			<div class="tab-pane" id="tab2">
				<p>Howdy, I\'m in Section 2.</p>
			</div>
		</div>
	</div>
	';
?>
