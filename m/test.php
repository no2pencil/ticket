<?php 
$content = "PDF test";
var_dump($framework->get('db')->search('users', array('username'), 'adm'));
die();
$framework->get('pdf')->invoice(0);
?>