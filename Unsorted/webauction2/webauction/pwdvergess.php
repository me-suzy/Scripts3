<SCRIPT Language=PHP>

/*
   Copyright (c), 1999, 2000 - phpauction.org
   Copyright (c), 2001, 2002 - webauction.de.vu

   Lizenz siehe lizenz.txt & license

   Die neueste Version kostenfrei zum Download unter:
   http://webauction.de.vu

*/
	include "./includes/messages.inc.php";
	include "./includes/config.inc.php";
	include "./includes/countries.inc.php";

if ($REQUEST_METHOD=="POST" && $action=="ok") 
{
	if ($TPL_username) 
	{
		$sql="SELECT email FROM a_users WHERE nick=\"". AddSlashes($TPL_username)."\"";
		
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
					$query = "update a_users set password='".$NEWPASSWD."' WHERE nick=\"".AddSlashes($TPL_username)."\";";
					$res = mysql_query($query);
					if(!$res)
					{
						print "An error occured while accessing the database: $query<BR>".mysql_error();
						exit;
					}
					
					require "header.php";
					include "templates/passwd_sent_php.html";
					require "footer.php";
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
			$TPL_err=1;
			$TPL_errmsg=$ERR_001;
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
	include "templates/forgotpasswd_php.html";
}


	include "footer.php";
	$TPL_err=0;
	$TPL_errmsg="";
?>
