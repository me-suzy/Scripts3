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
	include "includes/countries_.inc.php";

if ($REQUEST_METHOD=="GET") 
{
	if (!empty($YA_SESSIONID_COOKIE)) 
	{
		$sql="SELECT * FROM ".$dbfix."_sessions WHERE id=\"$YA_SESSIONID_COOKIE\"";
		$res=mysql_query ($sql);
		if (mysql_num_rows($res)>0) 
		{
			$arr=mysql_fetch_array ($res);
			$sql="SELECT * FROM ".$dbfix."_users WHERE id=$arr[userid]";
			$res=mysql_query ($sql);
			if ($res) 
			{
				if ($arr=mysql_fetch_array ($res))
				{
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
					$TPL_rate=round($arr[rate_sum]/$arr[rate_num]);
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
			header ("Location: user_login.php");
			exit;
		}
	}
	else 
	{
		header ("Location: user_login.php");
		exit;
	}
}

if ($REQUEST_METHOD=="POST") 
{
	if ($TPL_name && $TPL_nick && $TPL_password && $TPL_repeat_password && $TPL_email && $TPL_address && $TPL_city && $TPL_country && $TPL_zip && $TPL_phone) 
	{
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
		else if (strlen($TPL_zip)<6) //Primitive zip check
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
			$sql="SELECT * FROM ".$dbfix."_sessions WHERE id=\"$YA_SESSIONID_COOKIE\"";
			$res=mysql_query ($sql);
			if (mysql_num_rows($res)>0)
			{
				$arr=mysql_fetch_array ($res);
				$sql="UPDATE ".$dbfix."_users SET name=\"$TPL_name\", nick=\"$TPL_nick\", password=\"$TPL_password\", email=\"$TPL_email\", address=\"$TPL_address\", city=\"$TPL_city\", prov=\"$TPL_prov\", country=\"$TPL_country\", zip=\"$TPL_zip\", phone=\"$TPL_phone\" WHERE id=$arr[userid]";
				$res=mysql_query ($sql);
				if ($res) 
				{
					$sql="DELETE FROM ".$dbfix."_sessions WHERE id=\"$YA_SESSIONID_COOKIE\"";
					mysql_query ($sql);
					setcookie ("YA_SESSIONID_COOKIE","");
					header ("Location: index.php");
					exit;
				}
			}
			else 
			{
				header ("Location: user_login.php");
				exit;
			}
		}
	}
	else 
	{
		$TPL_err=1;
		$TPL_errmsg=$ERR_112;
	}
}	

	include "header.php";
	include "templates/change_details_php3.html";
	include "footer.php";
	$TPL_err=0;
?>
