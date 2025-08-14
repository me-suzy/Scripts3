<?

/*
   Copyright (c), 1999, 2000 - phpauction.org
   Copyright (c), 2001, 2002 - webauction.de.vu

   Lizenz siehe lizenz.txt & license

   Die neueste Version kostenfrei zum Download unter:
   http://webauction.de.vu

*/

   // Include messages file & Connect to sql server & inizialize configuration variables
	include "includes/messages.inc.php";
	include "includes/config.inc.php";
	include "header.php";
	
	//-- 
	
	getSessionVars();
	$auction_id = $sessionVars["CURRENT_ITEM"];


if ( empty($user_id) )
	$user_id = $id;


if (!empty($user)) 
{
	$sql="SELECT id FROM ".$dbfix."_users WHERE nick=\"".AddSlashes($user)."\"";
	$res=mysql_query ($sql);
	$arr=mysql_fetch_array ($res);
	$TPL_user_id=$arr[id];
}
if (!empty($user_id)) 
{
	$TPL_user_id=$user_id;
}

	$sql="SELECT * FROM ".$dbfix."_users WHERE id='".AddSlashes($TPL_user_id)."'";
	
	$res=mysql_query($sql);
	if ($res) 
	{
		if ($arr=mysql_fetch_array($res)) 
		{
			$TPL_num_feedbacks		=$arr[rate_num];
			$TPL_user_value			=$arr[nick];
			if ($arr[rate_num]) 
			{
				$rate_ratio=round($arr[rate_sum]/$arr[rate_num]);
			}
			else 
			{
				$rate_ratio=0;
			}
			$TPL_rate_ratio_value	="<IMG src=\"./images/estrella_".$rate_ratio.".gif\">";
			$reg_date				=$arr[reg_date];
            		$year = substr($reg_date,0,4);
			$month = substr($reg_date,4,2);
                        $day = substr($reg_date,6,2);
            $TPL_ADC_value = ArrangeDateMesCompleto($day,$month,$year,'','');			
		}
		else 
		{
			$TPL_err=1;
			$TPL_errmsg="Such users wasn't found in database";
		}
	}
	else 
	{
		$TPL_err=1;
		$TPL_errmsg="Error quering database";
	}




	include "./templates/profile_php3.html";
	include "./footer.php";


 
?>

