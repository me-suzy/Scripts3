<?#//v.1.0.0
	include "loggedin.inc.php";

#///////////////////////////////////////////////////////
#//  COPYRIGHT 2002 PHPAuction.org ALL RIGHTS RESERVED//
#//  For Source code for the GPL version go to        //  
#//  http://phpauction.org and download               //
#//  Supplied by CyKuH [WTN]                          //
#///////////////////////////////////////////////////////

		session_name("PHPAUCTIONADMIN");
		session_start();
		session_unregister("PHPAUCTION_ADMIN_LOGIN");
		session_unregister("PHPAUCTION_ADMIN_USER");
   		Header("Location: login.php");
?>