<?
	include "includes/config.inc.php";
	
	session_name($SESSION_NAME);
	session_unregister("PHPAUCTION_LOGGED_IN");
	session_unregister("PHPAUCTION_LOGGED_IN_USERNAME");
	
	Header("Location: index.php");
	exit;

?>