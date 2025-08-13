<?php

	error_reporting(E_WARNING);
	require_once("admin/config.php");
	
	if($template == "")
		$template = "template1";
		
	// Simply include the required template file
	include("templates/$template/top.php");
	
?>
