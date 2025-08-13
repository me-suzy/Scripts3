<?
    include( "config.php" );
	include("usersession.inc");			
	UpdateUserSession();
    include( "dblink.inc" );

	$ValidMember = false;

	if ( isset($MemberID ) )
	{
		$MemberID2 = addslashes($MemberID);
		$query = "SELECT UserAccountID, DateRegistered FROM UserAccounts WHERE MemberID='$MemberID2'";
		$result = mysql_query( $query, $link );		

		if ( $row = mysql_fetch_row( $result ) )
		{
			$ValidMember = true;

			$MemberDateRegistered = date( "M j, Y", $row[1] );

			$query = "SELECT ByUserAcctID, Comment, Rating, ForUserRole, FeedbackDate FROM Feedback WHERE ForUserAcctID=$row[0] ORDER BY FeedbackDate DESC";
			$result = mysql_query( $query, $link );		

			while ( $row = mysql_fetch_row( $result ) )
			{				
				$ByUserAcctID[] = $row[0];
				$Comment[] = $row[1];
				$Rating[] = $row[2];
				$ForUserRole[] = $row[3];							// 0 = buyer, 1 = seller
				$FeedbackDate[] = $row[4];
			}
		}
	}

	if ( !$ValidMember )
	{
		print( "<html><head><title>Invalid Member</title></head><body>\n" );
		print( "<br><br><center><h3>The member account is invalid.</h3></center></body></html>\n" );
		exit;
	}

	$NumVotes = count($ByUserAcctID);

	$RatingTotal = 0;
	for ( $i = 0; $i < $NumVotes; $i++ )
	{
		$RatingTotal += $Rating[$i];		
	}

	if ( $NumVotes == 0 )
		$AvgRating = "0.00";
	else
		$AvgRating = sprintf( "%.2f", $RatingTotal / $NumVotes );

	$SellerRatingTotal = 0;
	$NumSellerRatings = 0;
	for ( $i = 0; $i < $NumVotes; $i++ )
	{
		if ( $ForUserRole[$i] == 0 )		
		{
			$SellerRatingTotal += $Rating[$i];		
			$NumSellerRatings++;
		}
	}
	
	if ( $NumSellerRatings == 0 )
		$SellerRating = "0.00";
	else
		$SellerRating = sprintf( "%.2f", $SellerRatingTotal / $NumSellerRatings );

	$BuyerRatingTotal = 0;
	$NumBuyerRatings = 0;
	for ( $i = 0; $i < $NumVotes; $i++ )
	{
		if ( $ForUserRole[$i] == 1 )		
		{
			$BuyerRatingTotal += $Rating[$i];		
			$NumBuyerRatings++;
		}
	}

	if ( $NumBuyerRatings == 0 )
		$BuyerRating = "0.00";
	else
		$BuyerRating = sprintf( "%.2f", $BuyerRatingTotal / $NumBuyerRatings );	

	$TimeNow = time();
	$LastWeek = $TimeNow - 60 * 60 * 24 * 7;
	$LastMonth = $TimeNow - (60 * 60 * 24 * 7) * 30;
	$LastSixMonths = $TimeNow - ((60 * 60 * 24 * 7) * 30) * 6;

	$LastWeekTotal = 0;
	$NumLastWeekRatings = 0;
	for ( $i = 0; $i < $NumVotes; $i++ )
	{
		if ( $FeedbackDate[$i] > $LastWeek )
		{
			$LastWeekTotal += $Rating[$i];
			$NumLastWeekRatings++;
		}
	}

	if ( $NumLastWeekRatings == 0 )
		$LastWeekRating = "0.00";
	else
		$LastWeekRating = sprintf( "%.2f", $LastWeekTotal / $NumLastWeekRatings );	

	$LastMonthTotal = 0;
	$NumLastMonthRatings = 0;
	for ( $i = 0; $i < $NumVotes; $i++ )
	{
		if ( $FeedbackDate[$i] > $LastMonth )
		{
			$LastMonthTotal += $Rating[$i];
			$NumLastMonthRatings++;
		}
	}

	if ( $NumLastMonthRatings == 0 )
		$LastMonthRating = "0.00";
	else
		$LastMonthRating = sprintf( "%.2f", $LastMonthTotal / $NumLastMonthRatings );	

	$LastSixMonthTotal = 0;
	$NumLastSixMonthRatings = 0;
	for ( $i = 0; $i < $NumVotes; $i++ )
	{
		if ( $FeedbackDate[$i] > $LastSixMonth )
		{
			$LastSixMonthTotal += $Rating[$i];
			$NumLastSixMonthRatings++;
		}
	}

	if ( $NumLastSixMonthRatings == 0 )
		$LastSixMonthRating = "0.00";
	else
		$LastSixMonthRating = sprintf( "%.2f", $LastSixMonthTotal / $NumLastSixMonthRatings );	
?>
<html>
<head>
<title>View Feedback</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
<META HTTP-EQUIV="Expires" CONTENT="-1">
<META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">
<meta name="description" content="Get updated with the latest Internet news!  Place classified ads, auctions, advertising, auctions, and have fun searching for computers, electronics, hardware, software, CDs, antiques, and other items.">
<meta name="keywords" content="free, classified, auction, free for all, advertising, listings, ad, cool, fun, website, news, Internet, computer, electronics, hardware, software, CD, shopping, news, forum">
</head>
<SCRIPT LANGUAGE="JavaScript">
<!-- 
function viewMember(ToMemberID)
{
	var newWin = window.open("viewmember.php?ToMemberID=" + ToMemberID, "viewmember", "width=500,height=450, scrollbars=1, resizable=yes");	
	newWin.focus();
}
//-->
</SCRIPT>
<body bgcolor="#FFFFFF">
<?
	include( "header.inc" );
?>
<br>
<p><font face="Arial, Helvetica, sans-serif" size="4" color="#003399"><b>View Feedback</b></font></p>
<p><b>Feedback Summary</b> </p>
<table width="450" border="2" cellspacing="0" cellpadding="2" bgcolor="#FFCC66" bordercolor="#336600">
  <tr> 
    <td width="125"><font face="Arial, Helvetica, sans-serif" size="3">Member:</font></td>
    <td width="325"><font face="Arial, Helvetica, sans-serif" size="3"><b>&nbsp;<a href="javascript:viewMember('<? echo $MemberID; ?>')"><? echo $MemberID; ?></a></b> (Member Since: <? echo $MemberDateRegistered; ?>)</font></td>
  </tr>
  <tr> 
    <td><font face="Arial, Helvetica, sans-serif" size="3">Average Rating:</font></td>
    <td><font face="Arial, Helvetica, sans-serif" size="3"><b>&nbsp;<? echo $AvgRating; ?> </b>(<? echo $NumVotes; ?> votes)</font></td>
  </tr>
  <tr> 
    <td><font face="Arial, Helvetica, sans-serif" size="3">Seller Rating:</font></td>
    <td><font face="Arial, Helvetica, sans-serif" size="3"><b>&nbsp;<? echo $SellerRating; ?> </b>(<? echo $NumSellerRatings; ?> votes)</font></td>
  </tr>
  <tr> 
    <td><font face="Arial, Helvetica, sans-serif" size="3">Buyer Rating:</font></td>
    <td><font face="Arial, Helvetica, sans-serif" size="3"><b>&nbsp;<? echo $BuyerRating; ?> </b>(<? echo $NumBuyerRatings; ?> votes)</font></td>
  </tr>
  <tr> 
    <td><font face="Arial, Helvetica, sans-serif" size="3">Last Week:</font></td>
    <td><font face="Arial, Helvetica, sans-serif" size="3"><b>&nbsp;<? echo $LastWeekRating; ?> </b>(<? echo $NumLastWeekRatings; ?> votes)</font></td>
  </tr>
  <tr> 
    <td><font face="Arial, Helvetica, sans-serif" size="3">Last Month:</font></td>
    <td><font face="Arial, Helvetica, sans-serif" size="3"><b>&nbsp;<? echo $LastMonthRating; ?> </b>(<? echo $NumLastMonthRatings; ?> votes)</font></td>
  </tr>
  <tr>
    <td><font face="Arial, Helvetica, sans-serif" size="3">Last Six Months:</font></td>
    <td><font face="Arial, Helvetica, sans-serif" size="3"><b>&nbsp;<? echo $LastSixMonthRating; ?> </b>(<? echo $NumLastSixMonthRatings; ?> votes)</font></td>
  </tr>
</table>
<br><br>
<p align="center"><font face="Arial, Helvetica, sans-serif" size="2"><b>&gt;
<?
	$UserAcctID = GetSessionUserID();			

	if ( $UserAcctID == -1 )
		print( " You must be <a href=\"signin.php\">signed in</a> to leave feedback. " );
	else
		print( " <a href=\"LeaveFeedback.php?MemberID=$MemberID\">Leave feedback for this member.</a> " );
?>
&lt;</b></font></p>

<p><b>Feedback Comments</b></p>
<table width="100%" border="2" cellspacing="0" cellpadding="4" bordercolor="#336600" bgcolor="#FFFFCC">
  <tr> 
    <td width="*"><font face="Arial, Helvetica, sans-serif" size="3"><b>Feedback 
      By</b></font></td>
    <td width="75"> 
      <div align="center"><font face="Arial, Helvetica, sans-serif" size="3"><b>Rating</b></font></div>
    </td>
    <td width="110">
      <div align="center"><font face="Arial, Helvetica, sans-serif" size="3"><b>Buyer 
        / Seller</b></font></div>
    </td>
    <td width="100">
      <div align="center"><font face="Arial, Helvetica, sans-serif" size="3"><b>Date</b></font></div>
    </td>
  </tr>
  <?
	for ( $i = 0; $i < $NumVotes; $i++ )
	{
		$FeedbackByMember = "";

		$query = "SELECT MemberID FROM UserAccounts WHERE UserAccountID=$ByUserAcctID[$i]";
		$result = mysql_query( $query, $link );				
		if ( $row = mysql_fetch_row( $result ) )
			$FeedbackByMember = "<a href=\"javascript:viewMember('$row[0]')\">$row[0]</a>";

		if ( $ForUserRole[$i] == 0 )
			$BuyerOrSeller = "Buyer";
		else
			$BuyerOrSeller = "Seller";

		$MemberComment = $Comment[$i];
		if ( $MemberComment == "" )
			$MemberComment = "No comment made.";

		$FeedbackDateFmt = date( "M j, Y", $FeedbackDate[$i] );		

		print( "<tr>\n" );
		print( "  <td><table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"1\">\n" );
		print( "    <tr><td><font face=\"Arial, Helvetica, sans-serif\" size=\"2\">$FeedbackByMember</font></td></tr>\n" );
		print( "    <tr><td><font face=\"Arial, Helvetica, sans-serif\" size=\"2\">$MemberComment&nbsp;</font></td></tr></table></td>\n" );
		print( "  <td><div align=\"center\"><font face=\"Arial, Helvetica, sans-serif\" size=\"2\">$Rating[$i]</font></div></td>\n" );
		print( "  <td><div align=\"center\"><font face=\"Arial, Helvetica, sans-serif\" size=\"2\">$BuyerOrSeller</font></div></td>\n" );
		print( "  <td><div align=\"center\"><font face=\"Arial, Helvetica, sans-serif\" size=\"2\">$FeedbackDateFmt</font></div></td>\n" );
		print( "</tr>\n" );
	}

	if ( $NumVotes == 0 )
	{
		print( "<tr>\n" );
		print( "  <td><font face=\"Arial, Helvetica, sans-serif\" size=\"2\">No feedback available.</td>\n" );
		print( "    <td>&nbsp;</td>\n" );
		print( "    <td>&nbsp;</td>\n" );
		print( "</tr>\n" );
	}
?> 
</table>
<br><br>
<?
	include( "footer.inc" );
?> 

