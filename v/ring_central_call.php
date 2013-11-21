<?php
  //$DEBUG=1;
  if(isset($_POST)) {
    $call_url = 'https://service.ringcentral.com/ringout.asp?';
    if(isset($DEBUG)) {
      foreach($_POST as $key => $post) {
        echo $key.' : '.$post.', ';
      }
    }
    if(isset($call_url)) {
      $ch = curl_init();
      //curl_setopt($ch, CURLOPT_GET, 1);
      curl_setopt($ch, CURLOPT_HTTPGET, 1);
      $call_url_post = http_build_query(array(
        'cmd' => 'call',
        'username' => $_POST['username'],
        'password' => $_POST['password'],
        'to'       => $_POST['to'],
        'from'     => $_POST['from'],
        'clid'     => $_POST['clid']
      ));

      //curl_setopt($ch, CURLOPT_GETFIELDS, $call_url_post);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $call_url_post);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
      curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
      curl_setopt($ch, CURLOPT_TIMEOUT, 30);
      curl_setopt($ch, CURLOPT_URL, $call_url.$call_url_post);

      $payload_output = curl_exec($ch);
      curl_close($ch);
      echo $payload_output;
    }
  } else {
    echo "No Data Passed";
  }
?>
