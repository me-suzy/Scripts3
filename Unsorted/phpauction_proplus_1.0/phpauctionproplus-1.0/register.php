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
	include "./includes/checkage.inc.php";

	//--
	$auction_id = $sessionVars["CURRENT_ITEM"];

if (empty($action)) 
{
	$action="first";
}

if ($REQUEST_METHOD=="POST" && $action=="first") 
{
if ($TPL_name && $TPL_nick && $TPL_password && $TPL_repeat_password && $TPL_email && 
	$TPL_address && $TPL_city && $TPL_prov && $TPL_country && $TPL_zip && $TPL_phone ) 
	{
		
		//-- Explode birthdate into DAY MONTH YEAR
		
		$DATE = explode("/",$TPL_birthdate);
		$birth_day 		= $DATE[1];
		$birth_month 	= $DATE[0];
		$birth_year 	= $DATE[2];
		$DATE = "$birth_year$birth_month$birth_day";
		
		
		
		if (strlen($TPL_nick)<6) 
		{
			$TPL_err=1;
			$TPL_errmsg=$ERR_107;
		}
		else if (strlen ($TPL_password)<6) 
		{
			$TPL_err=1;
			$TPL_errmsg=$ERR_108;
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
		else if (!ereg("^[0-9]{2}/[0-9]{2}/[0-9]{4}$",$TPL_birthdate)) //Birthdate check
		{
			$TPL_err = 1;
			$TPL_errmsg = $ERR_043;
		}
		elseif(!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+([\.][a-z0-9-]+)+$",$TPL_email))
		{
			$TPL_err = 1;
			$TPL_errmsg = $ERR_008;
		}
		else if(!CheckAge($birth_day,$birth_month, $birth_year))
		{
			$TPL_err = 1;
			$TPL_errmsg = $ERR_113;
		}
		else
		{
			$sql="SELECT nick FROM PHPAUCTIONPROPLUS_users WHERE nick=\"". AddSlashes ($TPL_nick)."\"";
			$res=mysql_query ($sql);
			if (mysql_num_rows($res)==0) 
			{

				
				$id = md5(uniqid(rand()));
				$id = eregi_replace("[a-f]","",$id);
				
		
				$TPL_id_hidden=$id;
				$TPL_nick_hidden=$TPL_nick;
				$TPL_password_hidden=$TPL_password;
				$TPL_name_hidden=$TPL_name;
				$TPL_email_hidden=$TPL_email;
			}
			else 
			{
				$TPL_err=1;
				$TPL_errmsg=$ERR_111; // Selected user already exists
			}

		
			$sql="SELECT email FROM PHPAUCTIONPROPLUS_users WHERE email=\"". AddSlashes ($TPL_email)."\"";
                        $res=mysql_query ($sql);
                        if (mysql_num_rows($res)==0)
                        {

								$id = md5(uniqid(rand()));
								//$id = eregi_replace("[a-f]","",$id);                                

                                $TPL_id_hidden=$id;
                                $TPL_nick_hidden=$TPL_nick;
                                $TPL_password_hidden=$TPL_password;
                                $TPL_name_hidden=$TPL_name;
                                $TPL_email_hidden=$TPL_email;
                        }
                        else
                        {
                                $TPL_err=1;
                                $TPL_errmsg=$ERR_115; // Selected user already exists
                        }
	
			if($TPL_err == 0)
			{
				$TODAY = date("Ymd");
				if($SETTINGS[signupfee] == 1)
				{
					$SUSPENDED = 9;
				}
				else
				{
					$SUSPENDED = 1;
				}
				$sql="INSERT INTO PHPAUCTIONPROPLUS_users (id, nick, password, name, address, city, prov, country, zip, phone, nletter,email, reg_date, rate_sum,  rate_num, birthdate,suspended)
				      VALUES (\"$TPL_id_hidden\", \"". Addslashes ($TPL_nick_hidden)."\", \""
												 . md5($MD5_PREFIX.Addslashes ($TPL_password_hidden))."\", \""
												 . Addslashes ($TPL_name_hidden)."\", \""
												 . AddSlashes ($TPL_address)."\", \""
												 . AddSlashes ($TPL_city)."\", \""
												 . AddSlashes ($TPL_prov)."\", \""
												 . AddSlashes ($TPL_country)."\", \""
												 . AddSlashes ($TPL_zip)."\", \""
												 . AddSlashes ($TPL_phone)."\", \""
												 . AddSlashes ($TPL_nletter)."\", \"" 
												 .AddSlashes ($TPL_email_hidden)."\", '$TODAY', 0,0,$DATE,$SUSPENDED)";
				$res=mysql_query ($sql);
				if ($res==0) 
				{
					$TPL_err=1;
					$TPL_errmsg=mysql_error ();//"Error updating users data";
				}
				else 
				{
					#// Automatically login user
					/*
					$PHPAUCTION_LOGGED_IN = $TPL_id_hidden;
					$PHPAUCTION_LOGGED_IN_USERNAME = $TPL_nick_hidden;
					session_name($SESSION_NAME);
					session_register("PHPAUCTION_LOGGED_IN","PHPAUCTION_LOGGED_IN_USERNAME");
					*/
				
					/* Update column users in table PHPAUCTIONPROPLUS_counters */
					$counteruser = mysql_query("UPDATE PHPAUCTIONPROPLUS_counters SET inactiveusers=(inactiveusers+1)");
					if(!$counteruser)
					{
						MySQLError($counteruser);
						exit;
					}

							
					/*					
					$buffer = file("./includes/usermail.inc.php");
					$i = 0;
					$j = 0;
						while($i < count($buffer))
					{
						if(!ereg("^#(.)*$",$buffer[$i]))
						{
							$skipped_buffer[$j] = $buffer[$i];
							$j++;
						}
					$i++;
					}
	
					$message = implode($skipped_buffer,"");
					*/
					
					
					#==================================================================================
					#// The following peace of code is now in the user_confirmation_inc.php file
					#==================================================================================
					/*==================================================================================
	
					$message = ereg_replace("<#c_id#>",AddSlashes($TPL_id_hidden),$message);
					$message = ereg_replace("<#c_name#>",AddSlashes($TPL_name_hidden),$message);
					$message = ereg_replace("<#c_nick#>",AddSlashes($TPL_nick_hidden),$message);
					$message = ereg_replace("<#c_address#>",AddSlashes($TPL_address),$message);
					$message = ereg_replace("<#c_city#>",AddSlashes($TPL_city),$message);
                 
					$message = ereg_replace("<#c_prov#>",AddSlashes($TPL_prov),$message);
					$message = ereg_replace("<#c_zip#>",AddSlashes($TPL_zip),$message);
					$message = ereg_replace("<#c_country#>",AddSlashes($countries[$TPL_country]),$message);
					$message = ereg_replace("<#c_phone#>",AddSlashes($TPL_phone),$message);
					$message = ereg_replace("<#c_email#>",AddSlashes($TPL_email_hidden),$message);
					$message = ereg_replace("<#c_password#>",AddSlashes($TPL_password_hidden),$message);
                    
					$message = ereg_replace("<#c_sitename#>",$SETTINGS[sitename],$message);
                    
					$message = ereg_replace("<#c_siteurl#>",$SETTINGS[siteurl],$message);
					$message = ereg_replace("<#c_adminemail#>",$SETTINGS[adminmail],$message);                    	
					$message = ereg_replace("<#c_confirmation_page#>",$SETTINGS[siteurl]."confirm.php?id=$TPL_id_hidden",$message);                    	
					
					mail($TPL_email_hidden,"$MSG_098",$message,"From:$SETTINGS[sitename] <$SETTINGS[adminmail]>\nReplyTo:$SETTINGS[adminmail]");
					=====================================================================================*/
					
					#// Send confirmation e-mail message depending on the peyment settings
						include "includes/user_confirmation.inc.php";
				}
			} // if($TPL_err == 0)
		}
	}
	else 
	{
		$TPL_err=1;
		$TPL_errmsg="$ERR_112"; // Data missing
	}
}



	include "header.php";

if (($REQUEST_METHOD=="GET" && $action=="first") || ($REQUEST_METHOD=="POST" && $action=="first" && $TPL_err)) 
{
	$country="";
	while (list ($code, $name)=each ($countries))
	{
		$country .="<option value=\"$code\"";
		if ($code==$TPL_country)
		{
			$country .= " selected";
		}
		$country .=">$name</option>\n";
	}
	include "templates/template_register_php.html";
}


if ($REQUEST_METHOD=="POST" && $action=="first" && !$TPL_err) 
{
	#//======================================================
	#  Original PHPAUCTION (GPL) command
	#//======================================================
	//include "templates/template_registered_php.html";

	#//======================================================
	#  PHPAUCTION PRO code
	#//======================================================
	if($SETTINGS[signupfee] == 1)
	{
		$PHPAUCTION_LOGGED_IN = $TPL_id_hidden;
					$PHPAUCTION_LOGGED_IN_USERNAME = $TPL_nick_hidden;
					session_name($SESSION_NAME);
					session_register("PHPAUCTION_LOGGED_IN","PHPAUCTION_LOGGED_IN_USERNAME");

		#// Depending on the fee type selected ("pay", "prepay")
		#// include the necessary lib file
		if($SETTINGS[feetype] == "prepay")
		{
			include "lib/signuppayment.php";
		}
		else
		{
			include "lib/signuppayment_pay.php";
		}
	}
	else
	{
		include "templates/template_registered_php.html";
	}
}
	include "footer.php";

	$TPL_err=0;
	$TPL_errmsg="";
?>
