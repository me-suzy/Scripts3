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
	
	getSessionVars();
	$auction_id = $sessionVars["CURRENT_ITEM"];
	
if (empty($pg)) 
{
	$pg=1;
}

if ($REQUEST_METHOD=="POST") 
{
	if ($TPL_rater_nick && $TPL_password && $TPL_rate && $TPL_feedback) 
	{
		if ($TPL_rater_nick!=$TPL_nick_hidden) 
		{
			$sql="SELECT nick, password FROM ".$dbfix."_users WHERE id=\"" .AddSlashes($user_id)."\"";
			$res=mysql_query ($sql);
			if (mysql_num_rows($res)>0) 
			{
				$arr=mysql_fetch_array ($res);
				if ($arr[password]==$TPL_password) 
				{
					$sql="SELECT rate_sum, rate_num FROM ".$dbfix."_users WHERE id=". AddSlashes($id);
					$res=mysql_query ($sql);
					if ($res) 
					{
						$arr=mysql_fetch_array ($res);
						$arr[rate_sum] += intval($TPL_rate);
						$arr[rate_num]++;
						$sql="UPDATE users SET rate_sum=". AddSlashes($arr[rate_sum]) .", rate_num=". AddSlashes($arr[rate_num])." WHERE id=". AddSlashes ($id);
						mysql_query ($sql);
						$sql="INSERT INTO ".$dbfix."_feedbacks (rated_user_id, rater_user_nick, feedback, rate, date) VALUES ("
												. AddSlashes ($id).", \""
												. AddSlashes ($TPL_rater_nick)."\", \""
												. AddSlashes (ereg_replace("\n","<BR>",$TPL_feedback))."\", \""
												. AddSlashes ($TPL_rate)."\", NULL)";
						mysql_query ($sql);
						$url = "Location: profile.php?id=$id&SESSION_ID=".urlencode($sessionIDU);
						header ($url);
						exit;
					}
					else 
					{
						$TPL_err=1;
						$TPL_errmsg=$err_font."$ERR_100";
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
		$TPL_errmsg=$err_font."$ERR_104</FONT>";
	}
}

if (($REQUEST_METHOD=="GET" || $TPL_err) ) 
{
	$sql="SELECT nick, rate_sum, rate_num FROM ".$dbfix."_users WHERE id=". AddSlashes($id);
	$res=mysql_query ($sql);
	if ($res) 
	{
		if (mysql_num_rows($res)>0) 
		{
			$arr=mysql_fetch_array ($res);
			$sql="SELECT * FROM ".$dbfix."_feedbacks WHERE rated_user_id=". AddSlashes($id)." ORDER by date DESC";

			$res=mysql_query ($sql);
			$i=0;
			while ($arrfeed=mysql_fetch_array($res)) 
			{
				$arr_feedback[$i]["username"]=$arrfeed[rater_user_nick];
				//$arr_feedback[$i]["title"]=htmlentities(substr($arrfeed[feedback], 0, 50));
				$arr_feedback[$i]["feedback"]=nl2br($arrfeed[feedback]);
				$arr_feedback[$i]["rate"]=$arrfeed[rate];
				
				$tmp_year = substr($arrfeed[date],0,4);
				$tmp_month = substr($arrfeed[date],4,2);
				$tmp_day = substr($arrfeed[date],6,2);
				$arr_feedback[$i]["date"] = "$tmp_day/$tmp_month/$tmp_year";
				$i++;
			}
			$echofeed="";
			$bgcolor = "#EBEBEB";
			for ($ind=($pg-1)*5; $ind+1<=$pg*5 && $ind<=$i-1; $ind++) 
			{
				if($bgColor == "#EBEBEB"){
											$bgColor = "#FFFFFF";
											$font_color="#004488";
										}else{
											$bgColor = "#EBEBEB";
                                            $font_color="#004488";
										}
				
				$echofeed .="<tr bgcolor=$bgcolor><td valign=\"top\"><FONT FACE=\"Arial\" SIZE=2>";
				$echofeed .="<B>".$arr_feedback[$ind][username]."</B>&nbsp;&nbsp;$sml_font($MSG_506".$arr_feedback[$ind][date].")</FONT>";
				$echofeed .="<BR>";
				//$echofeed .=$arr_feedback[$ind][title];
				$echofeed .="<IMG src=\"./images/estrella_".$arr_feedback[$ind][rate].".gif\"><BR>";
				$echofeed .="<FONT FACE=\"Arial\" SIZE=2>".$arr_feedback[$ind][feedback];
				$echofeed .="<BR><BR></td></tr>";
				
				$echofeed .= "<TR";
				if($bgcolor == "#EBEBEB"){
					$cehofeed .= "  BGCOLOR=#FFFFFF";
				}else{
					$echofeed .= "  BGCOLOR=#EBEBEB";
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
					$echofeed .="<FONT FACE=\"Arial\" SIZE=2>
					             <a href=\"feedback.php?SESSION_ID=".urlencode($sessionIDU)."&id=$id&pg=$ind2&faction=show\">
					             $ind2</a>";
					if($ind2 != $num_pages+1){
						$echofeed .= " | ";
					}

				}
				else 
				{
					$echofeed .="<FONT FACE=\"Arial\" SIZE=2 COLOR=\"#FFFFFF\">
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
	$sql="SELECT * FROM ".$dbfix."_feedbacks WHERE rated_user_id=". AddSlashes($id). " ORDER by date DESC";
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



// Calls the appropriate templates

if (($REQUEST_METHOD=="GET" || $TPL_err) && !$faction){
	include "header.php";	
	include "templates/feedback_php3.html";
	include "footer.php";
}

if ($REQUEST_METHOD=="GET" && $faction=="show"){
	include "header.php";
	include "templates/show_feedback_header.html";
	include "templates/show_my_feedback.html";
	include "footer.php";
}


	$TPL_err=0;
	$TPL_errmsg="";
?>
