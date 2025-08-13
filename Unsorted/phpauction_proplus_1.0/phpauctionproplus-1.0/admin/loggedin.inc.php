<?#//v.1.0.0
#///////////////////////////////////////////////////////
#//  COPYRIGHT 2002 PHPAuction.org ALL RIGHTS RESERVED//
#//  For Source code for the GPL version go to        //  
#//  http://phpauction.org and download               //
#//  Supplied by CyKuH [WTN]                          //
#///////////////////////////////////////////////////////

	#// Check if admin user is logged in
	session_name("PHPAUCTIONADMIN");
	session_start();
	
	if(empty($HTTP_SESSION_VARS[PHPAUCTION_ADMIN_LOGIN]))
	{
		Header("Location: login.php");
		exit;
	}
?>