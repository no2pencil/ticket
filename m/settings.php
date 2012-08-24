<?php
$content = '<h2>Site Settings</h2>';
$content .= '<div style="margin-bottom: 15px;"></div>';
// TODO: Include JS files and such that makes this work.
$content .= '
<div class="tabbable"> <!-- Only required for left/right tabs -->
  <ul class="nav nav-tabs">
    <li class="active"><a href="#tab1" data-toggle="tab">Section 1</a></li>
    <li><a href="#tab2" data-toggle="tab">Section 2</a></li>
  </ul>
  <div class="tab-content">
    <div class="tab-pane active" id="tab1">
      <p>I\'m in Section 1.</p>
    </div>
    <div class="tab-pane" id="tab2">
      <p>Howdy, I\'m in Section 2.</p>
    </div>
  </div>
</div>
	';
?>