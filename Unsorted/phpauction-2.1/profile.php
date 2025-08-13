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



   // Include messages file & Connect to sql server & inizialize configuration variables
	include "./includes/messages.inc.php";
	include "./includes/config.inc.php";
	include "header.php";
	
	//-- 
	
	//getSessionVars();
	$auction_id = $sessionVars["CURRENT_ITEM"];


if ( empty($user_id) )
	$user_id = $id;


if (!empty($user)) 
{
	$sql="SELECT id FROM PHPAUCTION_users WHERE nick=\"".AddSlashes($user)."\"";
	$res=mysql_query ($sql);
	$arr=mysql_fetch_array ($res);
	$TPL_user_id=$arr[id];
}
if (!empty($user_id)) 
{
	$TPL_user_id=$user_id;
}

	$sql="SELECT * FROM PHPAUCTION_users WHERE id='".AddSlashes($TPL_user_id)."'";
	
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
            $TPL_ADC_value = ArrangeDateMesCompleto('',$month,$year,'','');
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




	include "./templates/template_profile_php.html";
	include "./footer.php";


 
?>

