<?php
if(!isset($_GET['confirmLogout'])){
	$content .= '<h3>Are you sure you want to logout?</h3>';
	$content .= '<a href="logout.php?confirmLogout=true" class="btn">Yes</a>';
	$content .= ' | ';
	$content .= '<a href="index.php" class="btn">No</a>';
	$content .= ' ';
} else {
	$_SESSION['logged_in'] = false;
	header('location: index.php');
}
?>
