<?

/*
   Copyright (c), 1999, 2000 - phpauction.org
   Copyright (c), 2001, 2002 - webauction.de.vu

   Lizenz siehe lizenz.txt & license

   Die neueste Version kostenfrei zum Download unter:
   http://webauction.de.vu

*/
	include "includes/messages.inc";
	include "includes/config.inc";
	include "includes/countries.inc";
	include "includes/restarts.inc";
	include "includes/relations.inc";
    include "includes/auction_types.inc";
	include "includes/durations.inc";
	include "includes/payments.inc";
	include "includes/datacheck.inc";


    

	if ($id && $password && $nick) 
	{
		$sql="SELECT * FROM ".$dbfix."_auctions WHERE id=\"". AddSlashes($id)."\"";
		
		$res=mysql_query ($sql);
		if ($res) 
		{
			if (mysql_num_rows($res)>0) 
			{
				$arr=mysql_fetch_array ($res);
				if ($TPL_password==$arr[password]) 
				{
					$id=$arr[id];
					$title=$arr[title];
					$description=$arr[description];
					$pict_url=$arr[pict_url];
					$auction_type=$arr[auction_type];
					$quantity=$arr[quantity];
					$minimum_bid=$arr[minimum_bid];
					$reserve_price=$arr[reserve_price];
					$location_zip=$arr[location_zip];
					$category=$arr[category];
					if ($arr[rate_num]) 
					{
						$TPL_rate=round($arr[rate_sum]/$arr[rate_num]);
					}
					else 
					{
						$TPL_rate=0;
					}

           /*----------------------------------------------------------*/

					$T=	"<SELECT NAME=\"atype\">\n";
			reset($auction_types); while(list($key,$val)=each($auction_types)){
				$T.=
					"	<OPTION VALUE=\"".
					$key.
					"\" ".
					(($key==$atype)?"SELECTED":"")
					.">".$val."</OPTION>\n";
			}
			$T.="</SELECT>\n";
			$TPL_auction_type = $T;

  	   		/*----------------------------------------------------------*/ 
			
            $T=	"<SELECT NAME=\"duration\">\n";
			reset($durations); while(list($key,$val)=each($durations)){
				$T.=
					"	<OPTION VALUE=\"".
					$key.
					"\" ".
					(($key==$duration)?"SELECTED":"")
					.">".$val."</OPTION>\n";
			}
			$T.="</SELECT>\n";
			$TPL_durations_list = $T;

			/*----------------------------------------------------------*/

			$T=	"<SELECT NAME=\"wieoft\">\n";
			reset($restarts); while(list($key,$val)=each($restarts)){
				$T.=
					"	<OPTION VALUE=\"".
					$key.
					"\" ".
					(($key==$restart)?"SELECTED":"")
					.">".$val."</OPTION>\n";
			}
			$T.="</SELECT>\n";
			$TPL_restarts_list = $T;


         /*----------------------------------------------------------*/
			$T=	"<SELECT NAME=\"menge\">\n";
			reset($relations); while(list($key,$val)=each($relations)){
				$T.=
					"	<OPTION VALUE=\"".
					$key.
					"\" ".
					(($key==$relation)?"SELECTED":"")
					.">".$val."</OPTION>\n";
			}
			$T.="</SELECT>\n";
			$TPL_relations_list = $T;

          /*----------------------------------------------------------*/

           $T=	"<SELECT NAME=\"country\">\n";
			reset($countries); while(list($key,$val)=each($countries)){
				$T.=
					"	<OPTION VALUE=\"".
					$key.
					"\" ".
					(($key==$country)?"SELECTED":"")
					.">".$val."</OPTION>\n";
			}
			$T.="</SELECT>\n";
			$TPL_countries_list = $T;

          /*----------------------------------------------------------*/

		  $T=	"";
			reset($payments); while(list($key,$val)=each($payments)){
				$T.=
					"<INPUT TYPE=CHECKBOX NAME=\"payment".$key."\" ".
					( (!empty(${"payment".$key}))?"CHECKED":"")
					."> $std_font".$val."</FONT><BR>";
			}
			$TPL_payments_list = $T;

          /*----------------------------------------------------------*/
              
					include "header.php";
					include "templates/my_auction_reg.html";
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

##################################



?>
