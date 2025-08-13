<?
/*
   Copyright (c), 1999, 2000 - phpauction.org                  
   
   This program is free software; you can redistribute it and/or modify 
   it under the terms of the GNU General Public License as published by 
   the Free Software Foundation (version 2 or later).                                  
                                                                        
   This program is distributed in the hope that it will be useful,      
   but WITHOUT ANY WARRANTY; without even the implied warranty of       
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the        
   GNU General Public License for more details.                         
                                                                        
   You should have received a copy of the GNU General Public License    
   along with this program; if not, write to the Free Software          
   Foundation, Inc., 675 Mass Ave, Cambridge, MA 02139, USA.
   
*/

	include "./includes/messages.inc.php";
	include "./includes/config.inc.php";
	include "./includes/countries.inc.php";

if ($REQUEST_METHOD=="POST" && $action=="ok") 
{
	if ($TPL_username) 
	{
		$sql="SELECT email FROM PHPAUCTION_users WHERE nick=\"". AddSlashes($TPL_username)."\"";
		
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
					$query = "update PHPAUCTION_users set password='".md5($MD5_PREFIX.$NEWPASSWD)."' WHERE nick=\"".AddSlashes($TPL_username)."\";";
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
	include "templates/template_forgotpasswd_php.html";
}


	include "footer.php";
	$TPL_err=0;
	$TPL_errmsg="";
?>
