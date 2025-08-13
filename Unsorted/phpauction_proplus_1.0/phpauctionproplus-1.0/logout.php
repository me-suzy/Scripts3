<?#//v.1.0.0

#///////////////////////////////////////////////////////
#//  COPYRIGHT 2002 PHPAuction.org ALL RIGHTS RESERVED//
#//  For Source code for the GPL version go to        //  
#//  http://phpauction.org and download               //
#//  Supplied by CyKuH [WTN]                          //
#///////////////////////////////////////////////////////


	include "includes/config.inc.php";
	
	session_name($SESSION_NAME);
	session_unregister("PHPAUCTION_LOGGED_IN");
	session_unregister("PHPAUCTION_LOGGED_IN_USERNAME");
	
	Header("Location: index.php");
	exit;

?>