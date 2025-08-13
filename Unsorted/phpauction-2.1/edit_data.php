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
	
	#// If user is not logged in redirect to login page
	if(!isset($HTTP_SESSION_VARS["PHPAUCTION_LOGGED_IN"]))
	{
		Header("Location: user_login.php");
		exit;
	}

	if($HTTP_POST_VARS[action] == "update")
	{
		#// Check data
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

				$sql="UPDATE PHPAUCTION_users SET email=\"".	AddSlashes($TPL_email)
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

				$sql .= "\" WHERE nick='".$HTTP_SESSION_VARS[PHPAUCTION_LOGGED_IN_USERNAME]."'";
				$res=mysql_query ($sql);

				#// Redirect user to his/her admin page
				$TMP_MSG = $MSG_183;
				session_name($SESSION_NAME);
				session_register("TMP_MSG");
				
				Header("Location: user_menu.php");
				exit;

				/*
				include "header.php";	
				include "templates/template_updated.html";
				*/
			}
		}
		else 
		{
			$TPL_err=1;
			$TPL_errmsg=$ERR_112;
		}
	}
	elseif($HTTP_POST_VARS[action] != "update" || !empty($TPL_errmsg)) 
	{
		#// Retrieve user's data
		$query = "select * from PHPAUCTION_users where nick='$HTTP_SESSION_VARS[PHPAUCTION_LOGGED_IN_USERNAME]'";
		$result = @mysql_query($query);
		if(!$result)
		{
			print $ERR_001." $query<BR>".mysql_error();
			exit;
		}
		else
		{
			$USER = mysql_fetch_array($result);
			$TPL_nick 		= $USER[nick];
			$TPL_name 		= $USER[name];
			$TPL_zip 		= $USER[zip];
			$TPL_email 		= $USER[email];
			$TPL_address 	= $USER[address];
			$TPL_country 	= $USER[country];
			$TPL_city 		= $USER[city];
			$TPL_prov 		= $USER[prov];
			$TPL_phone 		= $USER[phone];
			$TPL_nletter     = $USER[nletter];
			$TPL_birthdate	=  substr($USER[birthdate],4,2)."/".
							   substr($USER[birthdate],6,2)."/".
							   substr($USER[birthdate],0,4);
		}
		include "header.php";	

		//-- If an error occures re-built countries <SELECT>
		$country="";
		while (list ($code, $name) = each ($countries))
		{
			$country .= "<option value=\"$code\"";
			if ($code == $TPL_country)
			{
				$country .= " selected";
			}
			$country .= ">$name</option>\n";
		};		


		include "templates/template_change_details_php.html";
	}


	include "footer.php";
	$TPL_err=0;
	$TPL_errmsg="";
?>
