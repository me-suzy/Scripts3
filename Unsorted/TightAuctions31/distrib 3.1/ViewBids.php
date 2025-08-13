<?
    include( "config.php" );
	include("usersession.inc");			
	UpdateUserSession();
    include( "dblink.inc" );	
	include( "BidIncrements.inc" );
	include( "CurrentPrice.inc" );
		
	$query = "SELECT AdID, Title, StartPrice, ReservePrice, EndDate FROM AuctionAds WHERE AdID=$AdID";
	$result = mysql_query( $query, $link );		

	if ( $row = mysql_fetch_row( $result ) )
	{
		$AdID = $row[0];
		$Title = $row[1];
		$StartPrice = $row[2];
		$ReservePrice = $row[3];		
		$EndDate = date( "D, M j, Y  g:i A", $row[4] );

		$AuctionClosed = false;		
		$TimeLeft = $row[4]-time();
		if ( $TimeLeft <= 0 )
		{
			$TimeLeft = "Auction closed.";
			$AuctionClosed = true;
		}
		else
		{
			$Days = floor($TimeLeft / (60 * 60 * 24));
			$TimeLeft = $TimeLeft - $Days * (60 * 60 * 24);

			$Hours = floor($TimeLeft / (60 * 60));
			$TimeLeft = $TimeLeft - $Hours * (60 * 60);
			$Minutes = floor($TimeLeft / 60);

			$Seconds = $TimeLeft - $Minutes * 60;
			$TimeLeft = sprintf( "%d days, %dhrs %dmin %dsec", $Days, $Hours, $Minutes, $Seconds );	
		}

		$query = "SELECT UserAcctID, MaxBid, BidDate FROM Bids WHERE AdId=$AdID ORDER BY MaxBid DESC";
		$result = mysql_query( $query, $link );		

		$NumBids = mysql_num_rows($result);

		$BidWinner = -1;
		$Price = GetCurrentPrice($BidWinner);

		print( "<html><head><title>$Title - View Bids</title></head>\n<body bgcolor=\"#FFFFFF\">\n" );	
	}
	else
	{
		// Invalid ad -- the Ad ID doesn't exist for the user or at all

$BeginHeader = <<<BEGINHEADER
<html>
<head>
<title>Invalid Ad</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
BEGINHEADER;

	print( "$BeginHeader\n" );

	ValidateLoginRedirect();

$EndHeader = <<<ENDHEADER
</head>
<body bgcolor="#FFFFFF">
<p>&nbsp;</p>
<p><font color="#FF0033">The ad is invalid.</font></p>
</body>
</html>
ENDHEADER;

		print( "$EndHeader\n" );
		exit;				
	}

	include( "header.inc" );
?>
<SCRIPT LANGUAGE="JavaScript">
<!-- 
function viewMember(AdMemberID)
{
	var newWin = window.open("viewmember.php?ToMemberID=" + AdMemberID, "viewmember", "width=500,height=450, scrollbars=1, resizable=yes");	
	newWin.focus();
}
//-->
</SCRIPT>
<table width="100%" border="0" cellspacing="0" cellpadding="10">
  <tr valign="top"> 
    <td> 
      <p><font face="Arial, Helvetica, sans-serif" size="3"><b><font size="4" color="#003399">View 
        Bids </font></b></font></p>
      <table width="100%" border="0" cellspacing="0" cellpadding="2">
        <tr> 
          <td width="150"><font face="Arial, Helvetica, sans-serif" size="2">Item: 
            </font></td>
          <td width="*"><font face="Arial, Helvetica, sans-serif" size="2"><a href="ViewAuctionAd.php?AdID=<? echo $AdID; ?>"><b><? echo $Title; ?></b></a> 
            (# <a href="ViewAuctionAd.php?AdID=<? echo $AdID; ?>"><? echo $AdID; ?></a>)</font></td>
        </tr>
        <tr>
          <td><font face="Arial, Helvetica, sans-serif" size="2">Time Left / End 
            Time:</font></td>
          <td><font face="Arial, Helvetica, sans-serif" size="2"><b><? echo $TimeLeft; ?></b> 
            &nbsp; &nbsp;( Ends: <? echo $EndDate; ?> CST )</font></td>
        </tr>
        <tr> 
          <td>&nbsp;</td>
          <td><font face="Arial, Helvetica, sans-serif" size="2"></font></td>
        </tr>
        <tr> 
          <td><font face="Arial, Helvetica, sans-serif" size="2">Current Price:</font></td>
          <td><font face="Arial, Helvetica, sans-serif" size="2"><b><? printf( "$ %.2f", $Price ); ?></b></font></td>
        </tr>
        <tr> 
          <td><font face="Arial, Helvetica, sans-serif" size="2">Num of Bids:</font></td>
          <td><font face="Arial, Helvetica, sans-serif" size="2"><b><? echo $NumBids; ?></b></font></td>
        </tr>
      </table>
	  <br>     
      <table width="100%" border="0" cellspacing="0" cellpadding="4">
          <tr>             
            
          <td width="300" bgcolor="FFCC00"> <font face="Arial, Helvetica, sans-serif" size="2"><b>Member 
            ID</b></font> </td>
          <td width="125" bgcolor="FFCC00"> 
            <div align="center"><font face="Arial, Helvetica, sans-serif" size="2"><b>Bid 
              Amount</b></font></div>
		    </td>
            
          <td width="*" bgcolor="FFCC00"> 
            <div align="center"><font face="Arial, Helvetica, sans-serif" size="2"><b>Bid 
              Date</b></font></div>
            </td>
          </tr>
<?			
		$UserAcctID = GetSessionUserID();

		$NumRows = 0;		
		while ( $row = mysql_fetch_row( $result ) )
		{
			$NumRows++;

			$UserBidID = $row[0];
			$MaxBid = sprintf( "$ %.2f", $row[1] );
			$BidDate = date( "D, M j, Y  g:i A", $row[2] );

			$query = "SELECT MemberID FROM UserAccounts WHERE UserAccountID=$UserBidID";
			$MemberResult = mysql_query( $query, $link );		
			if ( $MemberRow = mysql_fetch_row($MemberResult) )
				$Bidder = "<a href=\"javascript:viewMember('$MemberRow[0]')\">$MemberRow[0]</a> ( <a href=\"ViewFeedback.php?MemberID=$MemberRow[0]\" target=\"_blank\">View Feedback</a> )";
	
			if ( ($NumRows % 2) != 0 )	
				print( "<tr>\n" );			
			else
				print( "<tr bgcolor=\"#CCCCFF\">\n" );	

			print( "  <td width=\"300\">$Bidder</td>\n" );
			
			if ( $AuctionClosed )
			{
				print( "  <td width=\"125\"><div align=\"center\">$MaxBid</div></td>\n" );
			}
			else
			{
				if ( $UserBidID == $UserAcctID )
					print( "  <td width=\"125\"><div align=\"center\">$MaxBid</div></td>\n" );
				else
					print( "  <td width=\"125\"><div align=\"center\">&nbsp;</div></td>\n" );
			}

			print( "  <td width=\"*\"><div align=\"center\">$BidDate CST</div></td>\n" );
			print( "</tr>\n" );
		}
?> 
        </table>

<br><br>
      <font face="Arial, Helvetica, sans-serif" size="2"><b>Note: You can only 
      view your own bids while the auction is currently open. When the auction 
      is closed, everyone can view all of the bids.</b></font> </td>
  </tr>
</table>
<?
	include( "footer.inc" );
?>
</body>
</html>
