<?
	include "includes/config.inc.php";
	
	if($action && $username && password)
	{
		$query = "select id from PHPAUCTION_users where nick='$username' and password='".md5($MD5_PREFIX.$password)."' and suspended=0";
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