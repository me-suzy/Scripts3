<?#//v.1.0.0

#///////////////////////////////////////////////////////
#//  COPYRIGHT 2002 PHPAuction.org ALL RIGHTS RESERVED//
#//  For Source code for the GPL version go to        //  
#//  http://phpauction.org and download               //
#//  Supplied by CyKuH [WTN]                          //
#///////////////////////////////////////////////////////


	include "./includes/messages.inc.php";
	include "./includes/config.inc.php";
	
//getSessionVars();
	$auction_id = $sessionVars["CURRENT_ITEM"];
	
if (empty($pg)) 
{
	$pg=1;
}

if ($REQUEST_METHOD=="POST") 
{
if($TPL_rater_nick && $TPL_password && $TPL_rate && $TPL_feedback) 
	{


	$sql="SELECT winner, seller FROM PHPAUCTIONPROPLUS_winners WHERE auction ='$auction_id'";
			$resids=mysql_query ($sql);
	if (mysql_num_rows($resids)>0)

{
						$seller = AddSlashes(mysql_result($resids,0,"seller"));
						$winner = AddSlashes(mysql_result($resids,0,"winner"));

	$sql="SELECT id, nick FROM PHPAUCTIONPROPLUS_users WHERE nick=\"" .AddSlashes($TPL_nick_hidden)."\"";
			$res=mysql_query ($sql);
		if($res)
	
			$secid = AddSlashes($id);
						
    
	
		if ($TPL_rater_nick!=$TPL_nick_hidden) 
		{
	$sql="SELECT id, nick, password FROM PHPAUCTIONPROPLUS_users WHERE nick=\"" .AddSlashes($TPL_rater_nick)."\"";
			$resrater=mysql_query ($sql);
		
	if (mysql_num_rows($resrater)  > 0)	
	{
        	$arr=mysql_fetch_array ($resrater);
	
		

	if(	$arr[id]== $winner || 	$arr[id]==$seller )	
		{
			if ($arr[password]  == md5($MD5_PREFIX.$TPL_password)) 
				{
				
					$sql="SELECT rate_sum, rate_num FROM PHPAUCTIONPROPLUS_users WHERE id='$secid'";
					$res2=mysql_query ($sql);
					if ($res2) 
					{
						$arr=mysql_fetch_array ($res2);
						$arr[rate_sum] += intval($TPL_rate);
						$arr[rate_num]++;
						$secratesum = AddSlashes($arr[rate_sum]);
						$secratenum = AddSlashes($arr[rate_num]);
						$secTPL_rater_nick = AddSlashes ($TPL_rater_nick);
						$secTPL_feedback = AddSlashes (ereg_replace("\n","<BR>",$TPL_feedback));
						$secTPL_rate = AddSlashes ($TPL_rate);
						$sql="UPDATE PHPAUCTIONPROPLUS_users SET rate_sum='$secratesum', rate_num='$secratenum' WHERE id='$secid'";
						mysql_query ($sql);
						$sql="INSERT INTO PHPAUCTIONPROPLUS_feedbacks (rated_user_id, rater_user_nick, feedback, rate, feedbackdate) VALUES (
							'$secid',
												'$secTPL_rater_nick', 
												'$secTPL_feedback', 
												'$secTPL_rate', NULL)";
						mysql_query ($sql);
						$url = "Location:  profile.php?id=$id";
						header ($url);
						exit;
					}

				}
				else 
				{
					$TPL_err=1;
					$TPL_errmsg=$err_font."$ERR_101";
				}
		}
		else 
				{
					$TPL_err=1;
					$TPL_errmsg=$err_font."$ERR_703";
				}
		}
		else 
				{
					$TPL_err=1;
					$TPL_errmsg=$err_font."$ERR_102";
				}
		}
		else 
				{
					$TPL_err=1;
					$TPL_errmsg=$err_font."$ERR_103";
				}
	}
		else 
				{
					$TPL_err=1;
					$TPL_errmsg=$err_font."$ERR_704";
				}			
	}
	else 
	{
		$TPL_err=1;
		$TPL_errmsg=$err_font."$ERR_104</FONT>";
	}

}
if (($REQUEST_METHOD=="GET" || $TPL_err) ) 
{
	$secid = AddSlashes($id);
	$sql="SELECT nick, rate_sum, rate_num FROM PHPAUCTIONPROPLUS_users WHERE id='$secid'";
	$res=mysql_query ($sql);
	if ($res) 
	{
		if (mysql_num_rows($res)>0) 
		{
			$arr=mysql_fetch_array ($res);
			$sql="SELECT * FROM PHPAUCTIONPROPLUS_feedbacks WHERE rated_user_id='$secid' ORDER by feedbackdate DESC";

			$res=mysql_query ($sql);
			$i=0;
			while ($arrfeed=mysql_fetch_array($res)) 
			{
				$arr_feedback[$i]["username"]=$arrfeed[rater_user_nick];
				//$arr_feedback[$i]["title"]=htmlentities(substr($arrfeed[feedback], 0, 50));
				$arr_feedback[$i]["feedback"]=nl2br($arrfeed[feedback]);
				$arr_feedback[$i]["rate"]=$arrfeed[rate];
				
				$tmp_year = substr($arrfeed[feedbackdate],0,4);
				$tmp_month = substr($arrfeed[feedbackdate],4,2);
				$tmp_day = substr($arrfeed[feedbackdate],6,2);
				$arr_feedback[$i]["feedbackdate"] = "$tmp_day/$tmp_month/$tmp_year";
				$i++;
			}
			$echofeed="";
			$bgcolor = "#FFFFFF";
			for ($ind=($pg-1)*5; $ind+1<=$pg*5 && $ind<=$i-1; $ind++) 
			{
				if($bgcolor == "#FFFFFF"){
					$bgcolor = "#EEEEEE";
				}else{
					$bgcolor = "#FFFFFF";
				}
				
				$echofeed .="<tr bgcolor=$bgcolor><td valign=\"top\"><FONT FACE=\"Verdana,Arial,Helvetica\" SIZE=2>";
				$echofeed .="<B>".$arr_feedback[$ind][username]."</B>&nbsp;&nbsp;$sml_font($MSG_506".$arr_feedback[$ind][feedbackdate].")</FONT>";
				$echofeed .="<BR>";
				//$echofeed .=$arr_feedback[$ind][title];
				$echofeed .="<IMG src=\"./images/estrella_".$arr_feedback[$ind][rate].".gif\"><BR>";
				$echofeed .="<FONT FACE=\"Verdana,Arial,Helvetica\" SIZE=2>".$arr_feedback[$ind][feedback];
				$echofeed .="<BR><BR></td></tr>";
				
				$echofeed .= "<TR";
				if($bgcolor == "#FFFFFF"){
					$echofeed .= "  BGCOLOR=#EEEEEE";
				}else{
					$echofeed .= "  BGCOLOR=#FFFFFF";
				}				
				$echofeed .= "><TD ALIGN=right>";
			}
			if (round(($i/5-floor($i/5))*10)) 
			{
				$num_pages=floor($i/5);
			}
			else 
			{
				$num_pages=floor($i/5)-1;
			}
			
			for ($ind2=1; $ind2<=$num_pages+1; $ind2++) 
			{
				if ($pg!=$ind2) 
				{
					$echofeed .="<FONT FACE=\"Verdana,Arial,Helvetica\" SIZE=2>
					             <a href=\"feedback.php?id=$id&pg=$ind2&faction=show\">
					             $ind2</a>";
					if($ind2 != $num_pages+1){
						$echofeed .= " | ";
					}

				}
				else 
				{
					$echofeed .="<FONT FACE=\"Verdana,Arial,Helvetica\" SIZE=2 COLOR=\"#777777\">
					             $ind2";
					if($ind2 != $num_pages+1){
						$echofeed .= " | ";
					}
				}
			}
			$echofeed .= "</td></tr>";
			$TPL_feedbacks_num=$i;
			$TPL_nick=$arr[nick];
			if ($arr[rate_num]) 
			{
				$TPL_total_ratio=round($arr[rate_sum]/$arr[rate_num]);
			}
			else 
			{
				$TPL_total_ratio=0;
			}

			if ($arr[rate_num]) 
			{
				$rate_ratio=round($arr[rate_sum]/$arr[rate_num]);
			}
			else 
			{
				$rate_ratio=0;
			}
	      $TPL_rate_ratio_value	="<IMG src=\"./images/estrella_".$rate_ratio.".gif\">";			
		}
		else 
		{
			$TPL_err=1;
			$TPL_errmsg=$err_font."$ERR_105";
		}
	}
	else 
	{
		$TPL_err=1;
		$TPL_errmsg=$err_font."$ERR_106";
	}

}

if ($REQUEST_METHOD=="GET" && $faction=="show") 
{
 	$secid = AddSlashes($id);	
	$sql="SELECT * FROM PHPAUCTIONPROPLUS_feedbacks WHERE rated_user_id='$secid' ORDER by feedbackdate DESC";
	$res=mysql_query ($sql);
	$i=0;
	while ($arrfeed=mysql_fetch_array($res)) 
	{
		$arr_feedback[$i]["username"]=$arrfeed[rater_user_nick];
		$arr_feedback[$i]["title"]=substr($arrfeed[feedback], 0, 50);
		$arr_feedback[$i]["feedback"]=htmlentities(nl2br($arrfeed[feedback]));
		$arr_feedback[$i]["rate"]=$arrfeed[rate];
		$i++;
	}
	$nick=$arr_feedback[intval($f_id)][username];
	$feedback=$arr_feedback[intval($f_id)][feedback];
	$rate=$arr_feedback[intval($f_id)][rate];

}



// Calls the appropriate templates/templates

if (($REQUEST_METHOD=="GET" || $TPL_err) && !$faction){
	include "header.php";	
	include "templates/template_feedback_php.html";
	include "footer.php";
}

if ($REQUEST_METHOD=="GET" && $faction=="show"){
	include "header.php";	
	include "templates/template_show_feedback.html";
	include "footer.php";
}


	$TPL_err=0;
	$TPL_errmsg="";
?>
