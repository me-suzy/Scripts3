<?#//v.1.0.0
#///////////////////////////////////////////////////////
#//  COPYRIGHT 2002 PHPAuction.org ALL RIGHTS RESERVED//
#//  For Source code for the GPL version go to        //  
#//  http://phpauction.org and download               //
#//  Supplied by CyKuH [WTN]                          //
#///////////////////////////////////////////////////////

	include "./includes/messages.inc.php";
	include "./includes/config.inc.php";
	include "./includes/countries.inc.php";

if ($REQUEST_METHOD=="POST" && $action=="ok") 
{
	if ($TPL_username) 
	{
		$sql="SELECT email FROM PHPAUCTIONPROPLUS_users WHERE nick=\"". AddSlashes($TPL_username)."\"";
		
		$res=mysql_query ($sql);
		if ($res) 
		{
			if (mysql_num_rows($res)>0) 
			{
					//-- Generate a new random password and mail it to the user
					$EMAIL = mysql_result($res,0,"email");
					
					$NEWPASSWD = substr(uniqid(md5(time())),0,6);
					include "includes/newpasswd.inc.php";
					mail($to,$subject,$message,$from);
					
					//-- Update database
					$query = "update PHPAUCTIONPROPLUS_users set password='".md5($MD5_PREFIX.$NEWPASSWD)."' WHERE nick=\"".AddSlashes($TPL_username)."\";";
					$res = mysql_query($query);
					if(!$res)
					{
						print "An error occured while accessing the database: $query<BR>".mysql_error();
						exit;
					}
					
					include "header.php";
					include "templates/template_passwd_sent_php.html";
					include "footer.php";
					exit;
			}
			else
			{
				$TPL_err=1;
				$TPL_errmsg=$ERR_100;
			}
		}
		else 
		{
			MySQLError($query);
			exit;
		}
	}
	else 
	{
		$TPL_err=1;
		$TPL_errmsg=$ERR_112;
	}
}

if(!$action || ($action && $TPL_errmsg))
{
	include "header.php";
	include "templates/template_forgotpasswd_php.html";
}


	include "footer.php";
	$TPL_err=0;
	$TPL_errmsg="";
?>
