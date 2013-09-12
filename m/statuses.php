<?php
  if(isset($_GET['new'])) {
    if($_GET['new']=="process") {
      $content = '<h2>Adding Repair Type</h2>';
    }
    if(isset($_GET['typename'])) {
      $content .= '<div id="typename"">Type : '.$_GET['typename'].'</div>';
    }
    if(isset($_GET['status'])) {
      $content .= '<div>Status : '.$_GET['status'].'</div>';
    }
  }
?>
