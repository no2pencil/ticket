<?php
  $db_server = 'localhost';
  global $db_env;
  if($db_env=="PROD") {
    $db_server = '192.168.0.121';
  }
  $db_user = 'ticket';
  $db_pass = '';
  $db_db = 'ticket';
?>
