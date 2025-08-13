<?#//v.1.0.0

#///////////////////////////////////////////////////////
#//  COPYRIGHT 2002 PHPAuction.org ALL RIGHTS RESERVED//
#//  For Source code for the GPL version go to        //  
#//  http://phpauction.org and download               //
#//  Supplied by CyKuH [WTN]                          //
#///////////////////////////////////////////////////////

	include "includes/config.inc.php";
	
	if($action && $username && password)
	{
		$query = "select id from PHPAUCTIONPROPLUS_users where nick='$username' and password='".md5($MD5_PREFIX.$password)."' and suspended=0";
		$res = mysql_query($query);
		//print $query;;
		if(mysql_num_rows($res) > 0)
		{
			$PHPAUCTION_LOGGED_IN = mysql_result($res,0,"id");
			$PHPAUCTION_LOGGED_IN_USERNAME = $HTTP_POST_VARS[username];
			session_name($SESSION_NAME);
			session_register("PHPAUCTION_LOGGED_IN","PHPAUCTION_LOGGED_IN_USERNAME");
		}
	}

	Header("Location: $HTTP_REFERER");
	exit;
?>