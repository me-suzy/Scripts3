<?
/*
   Copyright (c), 1999, 2000 - phpauction.org
   Copyright (c), 2001, 2002 - webauction.de.vu

   Lizenz siehe lizenz.txt & license

   Die neueste Version kostenfrei zum Download unter:
   http://webauction.de.vu

*/

	include "includes/config.inc.php";
	include "includes/messages.inc.php";
	include "includes/countries.inc.php";
	include "includes/classes.inc.php";



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
					$TPL_birthdate=$arr[birthdate ];
					$TPL_address=$arr[address];
					$TPL_city=$arr[city];
					$TPL_prov=$arr[prov];
					$TPL_country=$arr[country];
					$TPL_zip=$arr[zip];
					$TPL_phone=$arr[phone];
					$TPL_handy=$arr[handy];
					if ($arr[rate_num]) 
					{
						$TPL_rate=round($arr[rate_sum]/$arr[rate_num]);
					}
					else 
					{
						$TPL_rate=0;
					}
					//Geburtsdatum
					$datum = new datum;
					$datum->setDBdate ($TPL_birthdate);
					$TPL_birthdate = $datum->de_date;
					
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
                    include "templates/change_details_header_php3.html";
					include "templates/change_details_php3.html";
					include "footer.php";
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


?>
