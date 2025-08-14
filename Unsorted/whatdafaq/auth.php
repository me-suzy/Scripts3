<?php
session_start();
if ( ISSET ( $logout ) ) {
   session_unset("userid");
   session_unset("password");
}      

// Set Admin userid and password here;
$adminuserid = "admin";
$adminpassword = "whatdafaq";

if ($userid == "") {
	include ( "loginform.php" );
	exit;
	
 } elseif ( ISSET ( $userid ) ) {
	if ( ( $userid == $adminuserid ) AND ( $password == $adminpassword )) {
		session_register("userid");
		session_register("password");
		$loggedin = "yes";
	} else {
		include ( "loginform.php" );
		exit;
	}
}
?>
