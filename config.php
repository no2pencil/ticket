<?php
	/*
	 * This file holds all of the base configurations used throughout the site
	 *
	 */

	global $DEVURL;
	global $PRODURL;
	global $DS;
	global $db_env;

	$DEVURL  = "ticdev.akroncdnr.com";
	$PRODURL = "xrms.akroncdnr.com";
	$DS = "/";
	$db_env = "TEST";

	date_default_timezone_set("EST");
?>
