<?php
	// PRODUCTION: Remove testing environment variables
	require_once("config.php");

	if(gethostname()==$PRODURL) {
		$db_env="PROD";
	}
	require_once("v/head.php");
	require_once("c/index.php");
	new page();
?>
