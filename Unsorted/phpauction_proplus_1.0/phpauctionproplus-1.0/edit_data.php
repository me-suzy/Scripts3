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
			elseif(!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+([\.][a-z0-9-]+)+$",$TPL_email))
			{
				$TPL_err = 1;
				$TPL_errmsg = $ERR_008;
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

				$sql="UPDATE PHPAUCTIONPROPLUS_users SET email=\"".	AddSlashes($TPL_email)
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
	elseif(($HTTP_POST_VARS[action] != "update" || $TPL_errmsg !=1)) 
	{
		#// Retrieve user's data
		$query = "select * from PHPAUCTIONPROPLUS_users where nick='$HTTP_SESSION_VARS[PHPAUCTION_LOGGED_IN_USERNAME]'";
		$result = @mysql_query($query);
		if(!$result)
		{
			MySQLError($query);
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
			$TPL_nletter 	= $USER[nletter];
			$TPL_birthdate	=  substr($USER[birthdate],4,2)."/".
							   substr($USER[birthdate],6,2)."/".
							   substr($USER[birthdate],0,4);
							   
			#// Build countries <SELECT>
			$country="";
			while (list ($code, $name) = each ($countries))
			{
				$country .= "<option value=\"$code\"";
				if ($code == $TPL_country)
				{
					$country .= " selected";
				}
				$country .= ">$name</option>\n";
			}

							   
		}
		include "header.php";	
		include "templates/template_change_details_php.html";
	}
	
	if($TPL_err==1)
	{
		#// Build countries <SELECT>
		$country="";
		while (list ($code, $name) = each ($countries))
		{
			$country .= "<option value=\"$code\"";
			if ($code == $TPL_country)
			{
				$country .= " selected";
			}
			$country .= ">$name</option>\n";
		}
	
		include "header.php";
		include "templates/template_change_details_php.html";
	}
	include "footer.php";
	$TPL_err=0;
	$TPL_errmsg="";
?>
