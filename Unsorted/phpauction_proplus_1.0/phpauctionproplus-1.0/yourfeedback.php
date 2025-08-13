<?#//v.1.0.0

#///////////////////////////////////////////////////////
#//  COPYRIGHT 2002 PHPAuction.org ALL RIGHTS RESERVED//
#//  For Source code for the GPL version go to        //  
#//  http://phpauction.org and download               //
#//  Supplied by CyKuH [WTN]                          //
#///////////////////////////////////////////////////////

	//-- Set offset and limit for pagination
	$limit = 10;
	if(!$offset) $offset = 0;

	include "./includes/messages.inc.php";
	include "./includes/config.inc.php";
	
 	$query = "SELECT id, nick, rate_num, rate_sum from PHPAUCTIONPROPLUS_users where nick='$HTTP_SESSION_VARS[PHPAUCTION_LOGGED_IN_USERNAME]' ";
		$result = @mysql_query("$query"); 
			if(!$result)
		        {
			MySQLError($query);
			exit;
				}
		$ratedid = mysql_result ($result,0,"id");
		$rate_num = mysql_result ($result,0,"rate_num");
		$ratednick = mysql_result ($result,0,"nick");
		$total_rate = mysql_result ($result,0,"rate_sum");
		
	include "header.php";
	
?>

<TABLE WIDTH="100%" BGCOLOR="#FFFFFF" BORDER=0 CELLPADDING="0" CELLSPACING="0">

<TR>

    <TD align="center"> <BR>
      <BR>
    <B> </B> <BR>
      <table width="70%" border="0" cellspacing="0" cellpadding="0" align="center" height="14">
        <tr>
          <td align="left"><b> 
            <? print $tlt_font.$MSG_222; ?>
            <? echo $err_font.$TPL_errmsg;?>
            <br>
            </b></td>
      </tr>
    </table>
      <TABLE width="70%" CELLSPACING="0" CELLPADDING="0" BGCOLOR="#EEEEEE" align="center" border="0">
        <TR>

<TD>

	        <TABLE width="100%" CELLSPACING="1" CELLPADDING="4" align="center">
              <tr  BGCOLOR="#FFFFFF">

		<td ALIGN=right>

		  <? 
		  if ( $rate_num > 0 )
		{
			$rate_ratio = round( $total_rate / $rate_num );
	   }
		else
		{
			$rate_ratio = 0;
	   }

		$rateratio = "<IMG SRC=\"./images/estrella_".$rate_ratio.".gif\" ALIGN=TOP>";
			
		  echo "<B>$tlt_font$ratednick</FONT></B>

		           $std_font($rate_num)
				   <BR>$rateratio<BR><BR>";
 
		           
		  ?>

		</td>
	</tr>

	</TABLE>

</TD>

</TR>

</TABLE>
      
    
      
		
      <TABLE width="70%" align="center" cellpadding="8" border="1" cellspacing="0" bordercolor="<?=$FONTCOLOR[$SETTINGS[bordercolor]]?>">
        <? 
	

  	$sql="SELECT * FROM PHPAUCTIONPROPLUS_feedbacks WHERE rated_user_id  = '$HTTP_SESSION_VARS[PHPAUCTION_LOGGED_IN]' ORDER by feedbackdate DESC limit $offset, $limit";
	$res=mysql_query ($sql);
	if(!$res)
		    {
			MySQLError($query);
			exit;
			}
	if($res)
	{
	$num_feedback = mysql_num_rows($res);
	}
			$i = 0;
			$bgcolor = "#FFFFFF";
	while($feed = mysql_fetch_array($res))
	{
	
	$NICK[] = $feed[rater_user_nick];
	$FEEDBACK[] = stripslashes($feed[feedback]);
	$RATE[] = $feed[rate];
    $FEEDBACKDATE[] = substr($feed[feedbackdate],4,2)."/".substr($feed[feedbackdate],6,2)."/".substr($feed[feedbackdate],0,4)." ".substr($feed[feedbackdate],8,2).":".substr($feed[feedbackdate],10,2);       	
				
		    	if($bgcolor == "#FFFFFF")
                {
					$bgcolor = "#EEEEEE";
				}else{
					$bgcolor = "#FFFFFF";
					
				}
	if(is_array($feed))
	{
		while(list($k,$v) = each($NICK))
		{
				
?>
        <TR bgcolor="<?=$bgcolor; ?>"> 
          <TD CLASS=white height="64" valign="top"> 
            <p><FONT COLOR="#000000"> 
              <?=$std_font;?>
              <b> 
              <?=$NICK[$k]?>
              </b> </FONT><font color="#000000"> 
              <?=$std_font;?>
              <?=$MSG_506?>
              <?=$FEEDBACKDATE[$k]?>
              </font><br>
              <font color="#000000"> 
              <?=$std_font;?>
              <b>
              <?=$MSG_476?>
              </b> <IMG SRC="./images/estrella_<?=$RATE[$k]?>.gif" ALIGN=TOP> 
              </font><br>
              <font color="#000000"> 
              <?=$std_font;?>
              <b>
              <?=$MSG_504?>
              </b><br>
              <?=$FEEDBACK[$k]?>
              </font></p>
            
        <?
 		}
		$i++;
	}
}
		 ?>
			</TD>
	
        </TR>
      </TABLE><br>
      <table width="80" border="0" cellspacing="0" cellpadding="0" bgcolor="#CCCCCC">
        <tr>
          <td align="center" > 
<?
	//-- Build navigation line

		print "<b><SPAN CLASS=\"navigation\">";
		$num_pages = ceil($num_feedback / $limit);
		$i=0;
		while($i < $num_pages )
           {
		 $of = ($i * $limit);
			if($of != $offset){
				print "<A HREF=\"yourfeedback.php?offset=$of\" CLASS=\"navigation\">".($i + 1)."</A>";
				if($i != $num_pages) print " | ";
			}
			else
            {
				print  $i + 1;
				if($i != $num_pages) print " | ";
			}
        		$i++;
	     	}
		print "</SPAN></b>";
?>
          </td>
        </tr>
      </table>
      &nbsp;&nbsp; <br>
      <br></TD>
  </TR></TABLE>
<? include "footer.php"; ?>