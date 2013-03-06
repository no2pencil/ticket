<?php
  if(isset($_GET['new'])) {
    if($_GET['new']=="process") {
      $content = '<h2>Statuses</h2>';
    }
    if(isset($_GET['typename'])) {
      $content .= '<div id="typename"">'.$_GET['typename'].'</div>';
    }
    if(isset($_GET['status'])) {
      $content .= '<div>'.$_GET['status'].'</div>';
    }
  }
?>
