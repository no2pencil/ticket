<?php
// TODO: Actual logout code
// While you're at it, do the whole members system lol
if(!isset($_GET['confirmLogout'])){
	$content = '<h3>Are you sure you want to logout?</h3><a href="logout.php?confirmLogout=true" class="btn">Yes</a> | <a href="index.php" class="btn">No</a>';
} else {
	$_SESSION['logged_in'] = false;
	header('location: index.php');
}
?>
