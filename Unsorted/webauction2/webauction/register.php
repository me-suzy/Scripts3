<?

/*
   Copyright (c), 1999, 2000 - phpauction.org
   Copyright (c), 2001, 2002 - webauction.de.vu

   Lizenz siehe lizenz.txt & license

   Die neueste Version kostenfrei zum Download unter:
   http://webauction.de.vu

*/







	include "includes/messages.inc.php";

	include "includes/config.inc.php";

	include "includes/countries.inc.php";

	include "includes/classes.inc.php";



	//--

	getSessionVars();

	$auction_id = $sessionVars["CURRENT_ITEM"];



if (empty($action)) 

{

	$action="first";

}



if ($REQUEST_METHOD=="POST" && $action=="first") 

{

	if ($TPL_name && $TPL_nick && $TPL_password && $TPL_repeat_password && $TPL_email && 

	   $TPL_address && $TPL_city && $TPL_prov && $TPL_country && $TPL_zip && $TPL_phone) 

	{

		
		$datum = new datum;
		$datum->setRawdate($TPL_birthdate);
		$DATE=$datum->db_date;

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

	                else if (!(ereg("^[0-9a-zA-Z]([-_.]?[0-9a-zA-Z])*@[0-9a-zA-Z]([-.]?[0-9a-zA-Z])*\\.[a-zA-Z]{2,3}$",$TPL_email)))          //Besserer mail check 
		{

			$TPL_err=1;

			$TPL_errmsg=$ERR_110;

		}

		else if (strlen($TPL_zip)<4) //Primitive zip check

		{

			$TPL_err=1;

			$TPL_errmsg=$ERR_606;

		}

		else if (strlen($TPL_phone)<3) //Primitive phone check

		{

			$TPL_err=1;

			$TPL_errmsg=$ERR_607;

		}
	
		
		else if ($datum->error) //Birthdate check 
		{

			$TPL_err = 1;

			$TPL_errmsg = $ERR_043;

		}

		else if(!$datum->checkage())
		{
			$TPL_err = 1;

			$TPL_errmsg = $ERR_113;

		}

		else

		{

			$sql="SELECT nick FROM ".$dbfix."_users WHERE nick=\"". AddSlashes ($TPL_nick)."\"";

			$res=mysql_query ($sql);

			if (mysql_num_rows($res)==0) 

			{


				
				$id = uniqid("");
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


		
			$sql="SELECT email FROM ".$dbfix."_users WHERE email=\"". AddSlashes ($TPL_email)."\"";

                        $res=mysql_query ($sql);

                        if (mysql_num_rows($res)==0)

                        {


											$id = uniqid("");
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

                                $TPL_errmsg=$ERR_115; // Selected user already exists

                        }
	

			if($TPL_err == 0)

			{

				$sql="INSERT INTO ".$dbfix."_users (id, nick, password, name, address, city, prov, country, zip, phone, email, reg_date, rate_sum,  rate_num, birthdate)

				      VALUES (\"$TPL_id_hidden\", \"". Addslashes ($TPL_nick_hidden)."\", \""

												 . Addslashes ($TPL_password_hidden)."\", \""

												 . Addslashes ($TPL_name_hidden)."\", \""

												 . AddSlashes ($TPL_address)."\", \""

												 . AddSlashes ($TPL_city)."\", \""

												 . AddSlashes ($TPL_prov)."\", \""

												 . AddSlashes ($TPL_country)."\", \""

												 . AddSlashes ($TPL_zip)."\", \""

												 . AddSlashes ($TPL_phone)."\", \""

												 . AddSlashes ($TPL_email_hidden)."\", NULL, 0,0,$DATE)";

				$res=mysql_query ($sql);

				if ($res==0) 

				{

					$TPL_err=1;

					$TPL_errmsg=mysql_error ();//"Error updating users data";

				}

				else 

				{

				

					//-- Get actual users and auctions counters

					$query = "select users from ".$dbfix."_counters";

					$result_counters = mysql_query($query);

					if(!$result_counters){

						$TPL_errmsg = $ERR_001;

					}else{

						$users_counter = mysql_result($result_counters,0,"users") + 1;

						

						//-- Update counters table

						

						$query = "update ".$dbfix."_counters set users = $users_counter";

					

						$result_update_counters = mysql_query($query);

						if(!$result_update_counters){

							$TPL_errmsg = $ERR_001;

						}

					}

							

					 // - bestätigungsmail verschicken

					$buffer = file("includes/usermail.inc.php");

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

					//--Reteve message

	

					$message = implode($skipped_buffer,"");

	

					//--Change TAGS with variables content

	

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
                    
					$message = ereg_replace("<#c_sitename#>",$SITE_NAME,$message);
                    
					$message = ereg_replace("<#c_siteurl#>",$SITE_URL,$message);

					$message = ereg_replace("<#c_adminemail#>",$adminEmail,$message);                    	

					mail($TPL_email_hidden,"$MSG_098",$message,"From:$SITE_NAME <$adminEmail>\nReplyTo:$adminEmail");

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

	include "templates/register_php3.html";

}





if ($REQUEST_METHOD=="POST" && $action=="first" && !$TPL_err) 

{

	include "templates/registered_php3.html";

}

	include "footer.php";



	$TPL_err=0;

	$TPL_errmsg="";

?>

