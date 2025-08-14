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



if (empty($action)) 
{
	$action="login";
}


if ($REQUEST_METHOD=="GET" && $action=="login") 
{
	include "header.php";	
	include "templates/user_login_my_account_php3.html";
}

if ($REQUEST_METHOD=="POST" && $action=="login") 
{
	if ($TPL_nick && $TPL_password) 
	{
		$sql="SELECT * FROM ".$dbfix."_users WHERE nick=\"". AddSlashes($TPL_nick)."\"";
		
		$res=mysql_query ($sql);
		if ($res) 
		{
			if (mysql_num_rows($res)>0) 
			{
				$arr=mysql_fetch_array ($res);
				if ($TPL_password==$arr[password]) 
				{
					$TPL_id_hidden=$arr[id];
					$TPL_name=$arr[name];
					$TPL_nick=$arr[nick];
					$TPL_password=$arr[password];
					$TPL_repeat_password=$arr[password];
					$TPL_email=$arr[email];
					$TPL_address=$arr[address];
					$TPL_city=$arr[city];
					$TPL_prov=$arr[prov];
					$TPL_country=$arr[country];
					$TPL_zip=$arr[zip];
					$TPL_phone=$arr[phone];
					if ($arr[rate_num]) 
					{
						$TPL_rate=round($arr[rate_sum]/$arr[rate_num]);
					}
					else 
					{
						$TPL_rate=0;
					}
					$country="";
					while (list ($code, $name) = each ($countries))
					{
						$country .= "<option value=\"$code\"";
						if ($code==$TPL_country)
						{
						$country .= " selected";
						}
						$country .= ">$name</option>\n";
					};
  	   		        $expires = time()+(60*60*34*265*1); 			// Cookie expires in 1 years.
	                SetCookie("login","",$expires,"","$TPL_nick","$TPL_password");
					include "header.php";
					include "templates/my_account.php.html";
				}
				else 
				{
					$TPL_err=1;
					$TPL_errmsg=$ERR_101;
				}
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

if ($REQUEST_METHOD=="POST" && $action=="update") 
{
	if ($TPL_name && $TPL_nick && $TPL_password && $TPL_repeat_password && $TPL_email && $TPL_address && $TPL_city && $TPL_country && $TPL_zip && $TPL_phone) 
	{
		if (strlen($TPL_nick)<6) 
		{
			$TPL_err=1;
			$TPL_errmsg=$ERR_010;
		}
		else if (strlen ($TPL_password)<6) 
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
			$sql="UPDATE ".$dbfix."_users SET name=\"".			AddSlashes($TPL_name)
							 ."\", nick=\"".			AddSlashes($TPL_nick)
							 ."\", password=\"".		AddSlashes($TPL_password)
							 ."\", email=\"".			AddSlashes($TPL_email)
							 ."\", address=\"".			AddSlashes($TPL_address)
							 ."\", city=\"".			AddSlashes($TPL_city)
							 ."\", prov=\"".			AddSlashes($TPL_prov)
							 ."\", country=\"".			AddSlashes($TPL_country)
							 ."\", zip=\"".				AddSlashes($TPL_zip)
							 ."\", phone=\"".			AddSlashes($TPL_phone)
							 ."\" WHERE id=".			AddSlashes($TPL_id_hidden);
			$res=mysql_query ($sql);
			include "header.php";	
			include "templates/updated.html";

		}
	}
	else 
	{
		$TPL_err=1;
		$TPL_errmsg=$ERR_112;
	}
}


if ($REQUEST_METHOD=="POST" && $action == "update" && $TPL_err) 
{
	include "header.php";	
    $expires = time()+(60*60*34*265*1); 			// Cookie expires in 1 years.
	SetCookie("login","",$expires,"","$TPL_nick","$TPL_password");


	//-- If an error occures re-built countries <SELECT>
	$country="";
	while (list ($code, $name) = each ($countries))
	{
		$country .= "<option value=\"$code\"";
		if ($code==$TPL_country)
		{
			$country .= " selected";
		}
		$country .= ">$name</option>\n";
	};
	
	

	include "templates/my_account.php.html";
}


if ($REQUEST_METHOD=="POST" && $action == "login" && $TPL_err) 
{
	include "header.php";	
	include "templates/user_login_my_account_php3.html";
}



	include "footer.php";
	$TPL_err=0;
	$TPL_errmsg="";

################################################

if ($action=="profil") 
{
	if ($TPL_nick && $TPL_password) 
	{
		$sql="SELECT * FROM ".$dbfix."_users WHERE nick=\"". AddSlashes($TPL_nick)."\"";
		
		$res=mysql_query ($sql);
		if ($res) 
		{
			if (mysql_num_rows($res)>0) 
			{
				$arr=mysql_fetch_array ($res);
				if ($TPL_password==$arr[password]) 
				{
					$TPL_id_hidden=$arr[id];
					$TPL_name=$arr[name];
					$TPL_nick=$arr[nick];
					$TPL_password=$arr[password];
					$TPL_repeat_password=$arr[password];
					$TPL_email=$arr[email];
					$TPL_address=$arr[address];
					$TPL_city=$arr[city];
					$TPL_prov=$arr[prov];
					$TPL_country=$arr[country];
					$TPL_zip=$arr[zip];
					$TPL_phone=$arr[phone];
					if ($arr[rate_num]) 
					{
						$TPL_rate=round($arr[rate_sum]/$arr[rate_num]);
					}
					else 
					{
						$TPL_rate=0;
					}
					$country="";
					while (list ($code, $name) = each ($countries))
					{
						$country .= "<option value=\"$code\"";
						if ($code==$TPL_country)
						{
						$country .= " selected";
						}
						$country .= ">$name</option>\n";
					};
  	   		   $expires = time()+(60*60*34*265*10); 			// Cookie expires in 10 years.
              
					include "header.php";
					include "templates/change_details_php3.html";
				}
				else 
				{
					$TPL_err=1;
					$TPL_errmsg=$ERR_101;
				}
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

if ($REQUEST_METHOD=="POST" && $action=="update") 
{
	if ($TPL_name && $TPL_nick && $TPL_password && $TPL_repeat_password && $TPL_email && $TPL_address && $TPL_city && $TPL_country && $TPL_zip && $TPL_phone) 
	{
		if (strlen($TPL_nick)<6) 
		{
			$TPL_err=1;
			$TPL_errmsg=$ERR_010;
		}
		else if (strlen ($TPL_password)<6) 
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
			$sql="UPDATE ".$dbfix."_users SET name=\"".			AddSlashes($TPL_name)
							 ."\", nick=\"".			AddSlashes($TPL_nick)
							 ."\", password=\"".		AddSlashes($TPL_password)
							 ."\", email=\"".			AddSlashes($TPL_email)
							 ."\", address=\"".			AddSlashes($TPL_address)
							 ."\", city=\"".			AddSlashes($TPL_city)
							 ."\", prov=\"".			AddSlashes($TPL_prov)
							 ."\", country=\"".			AddSlashes($TPL_country)
							 ."\", zip=\"".				AddSlashes($TPL_zip)
							 ."\", phone=\"".			AddSlashes($TPL_phone)
							 ."\" WHERE id=".			AddSlashes($TPL_id_hidden);
			$res=mysql_query ($sql);
			include "header.php";	
			include "templates/updated.html";

		}
	}
	else 
	{
		$TPL_err=1;
		$TPL_errmsg=$ERR_112;
	}
}


if ($REQUEST_METHOD=="POST" && $action == "update" && $TPL_err) 
{
	include "header.php";	

	//-- If an error occures re-built countries <SELECT>
	$country="";
	while (list ($code, $name) = each ($countries))
	{
		$country .= "<option value=\"$code\"";
		if ($code==$TPL_country)
		{
			$country .= " selected";
		}
		$country .= ">$name</option>\n";
	};		
		
	
	include "templates/my_account.php.html";
}


if ($REQUEST_METHOD=="POST" && $action == "login" && $TPL_err) 
{
	include "header.php";	
	include "templates/user_login_my_account_php3.html";
}



	//include "footer.php";
	$TPL_err=0;
	$TPL_errmsg="";


?>
