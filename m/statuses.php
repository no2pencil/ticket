<?php
  if(isset($_GET['new'])) {
    if($_GET['new']=="process") {
      /* TODO Scrub the GET array */
      if($framework->get('status')->addStatus($_GET['text'], $_GET['icon'], $_GET['color'])) {
        $content = '<h2>Adding Status</h2>';
        $content .= '<span class="badge badge-'.$_GET['color'].'">';
        $content .= '<i class="'.$_GET['icon'].'"> ';
        $content .= $_GET['text'].'</i></span>';
      } else {
        $alert['status'] = 'error';
        $alert['msg'] = "Something Jacked up with the Status Creation!";
        $content = '<h2>Error Adding Status</h2>';
      }
    }
    if(isset($_GET['typename'])) {
      $content .= '<div id="typename"">Type : '.$_GET['typename'].'</div>';
    }
    if(isset($_GET['status'])) {
      $content .= '<div>Status : '.$_GET['status'].'</div>';
    }
  }
?>
