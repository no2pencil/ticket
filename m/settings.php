<?php
$content = '<h2>Site Settings</h2>';
$content .= '<div style="margin-bottom: 15px;"></div>';
// TODO: Include JS files and such that makes this work.
$content .= '
	<div class="tabbable">
		<ul class="nav nav-table">
			<li class="active"><a href="#tab1" data-toggle="tab">Section 1</a></li>
			<li><a href="#tab2" data-toggle="tab">Section 2</a></li>
		</ul>
		<div class="tab-content">
			<div class="tab-pane active" id="tab1">
				<p>Tab one sucka!</p>
			</div>
			<div class="tab-pane" id="tab2">
				<p>Tab two, this is awesome!</p>
			</div>
		</div>
	</div>';
?>