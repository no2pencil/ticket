<?php
  GLOBAL $db_env;
  $db_env="TEST";
  if(gethostname()=="xrms.akroncdnr.com") {
    $db_env="PROD";
  }
  require_once("c/index.php");
  new page();
?>
