<?php 
$content = "PDF test";
$framework->get('pdf')->invoice(1);
var_dump($framework->get('pdf'));
?>