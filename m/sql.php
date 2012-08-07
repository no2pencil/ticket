<?php
// Load the db from prod to dev
$db_prod_user="root";
$db_prod_pass="";
$db_prod_sql ="";
$db_prod_db  ="xrms";
$db_prod_table="contacts";

$db_dev_user="ticket";
$db_dev_pass="";
$db_dev_db  ="ticket";
$db_dev_table="customers";

$sql="select contact_id, first_names, last_name, email, work_phone, cell_phone from contacts";

$con = mysql_connect($db_prod_sql,$db_prod_user,$db_prod_pass);
if (!$con) {
  die('Could not connect: ' . mysql_error());
}

mysql_select_db($db_prod_db, $con);

$result = mysql_query($sql);

if(!$result) die($sql);

$customer = array();

while($row = mysql_fetch_array($result)) {
	$array[id] = $row[contact_id];
	echo $array[id];
}

mysql_close($con);
?>
