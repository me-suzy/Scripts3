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

	if(empty($action))
	{
		$action = "login";
	}

	if ($HTTP_POST_VARS[action] != "login") 
	{
		include "header.php";	
		include "templates/template_user_login_php.html";
	}


	if ($HTTP_POST_VARS[action] == "login") 
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
			Header("Location: user_menu.php");
			exit;
		}
		else
		{
			$TPL_err=1;
			$TPL_errmsg = $ERR_038;
			include "header.php";	
			include "templates/template_user_login_php.html";
		}
	}

	if ($REQUEST_METHOD=="POST" && $action=="update") 
	{
		if ($TPL_email && $TPL_address && $TPL_city && $TPL_country && $TPL_zip && $TPL_phone && TPL_nletter) 
		{

		 if (strlen($TPL_password)<6 && strlen($TPL_password) > 0) 
			{
				$TPL_err=1;
				$TPL_errmsg=$ERR_011;
			}
			else if ($TPL_password!=$TPL_repeat_password) 
			{
				$TPL_err=1;
				$TPL_errmsg=$ERR_109;
			}
			else if (strlen($TPL_email)<5)		//Primitive mail check 
			{
				$TPL_err=1;
				$TPL_errmsg=$ERR_110;
			}
			else if (strlen($TPL_zip)<5) //Primitive zip check
			{
				$TPL_err=1;
				$TPL_errmsg=$ERR_616;
			}
			else if (strlen($TPL_phone)<3) //Primitive phone check
			{
				$TPL_err=1;
				$TPL_errmsg=$ERR_617;
			}
			else 
			{
				$TPL_birthdate = substr($TPL_birthdate,6,4).
									  substr($TPL_birthdate,0,2).
									  substr($TPL_birthdate,3,2);

				$sql="UPDATE PHPAUCTION_users SET email=\"".			AddSlashes($TPL_email)

								 ."\", birthdate=\"".	AddSlashes($TPL_birthdate)
								 ."\", address=\"".		AddSlashes($TPL_address)
								 ."\", city=\"".			AddSlashes($TPL_city)
								 ."\", prov=\"".			AddSlashes($TPL_prov)
								 ."\", country=\"".		AddSlashes($TPL_country)
								 ."\", zip=\"".			AddSlashes($TPL_zip)
								 ."\", phone=\"".			AddSlashes($TPL_phone)

								  ."\", nletter=\"".			AddSlashes($TPL_nletter);

				if(strlen($TPL_password) > 0)
				{	
					$sql .= 	"\", password=\"".		md5($MD5_PREFIX.AddSlashes($TPL_password));
				}

				$sql .= "\" WHERE id='".			AddSlashes($TPL_id_hidden)."'";
				$res=mysql_query ($sql);

				include "header.php";	
				include "templates/template_updated.html";

			}
		}
		else 
		{
			$TPL_err=1;
			$TPL_errmsg=$ERR_112;
		}
	}




	include "footer.php";
	$TPL_err=0;
	$TPL_errmsg="";
?>
