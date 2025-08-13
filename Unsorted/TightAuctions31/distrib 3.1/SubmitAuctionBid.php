<?
    include( "config.php" );
	include("usersession.inc");			
	UpdateUserSession();
    include( "dblink.inc" );	
	include( "BidIncrements.inc" );	
	include( "CurrentPrice.inc" );
	
	$TimeNow = time();
	$UserAcctID = GetSessionUserID();		

	if ( $UserAcctID == -1 )
	{
		exit;
	}

	$query = "SELECT Title, StartPrice, ReservePrice, Quantity, BeginDate, EndDate, Description, UserAcctID FROM AuctionAds WHERE AdID=$AdID AND EndDate>$TimeNow";
	$result = mysql_query( $query, $link );		

	if ( $AdRow = mysql_fetch_row( $result ) )
	{	
	  $AdTitle = $AdRow[0];
	  $StartPrice = $AdRow[1];
	  $ReservePrice = $AdRow[2];
	  $Quantity  = $AdRow[3];
	  $BeginDate = date( "D, M j, Y  g:i A", $AdRow[4] );
	  $EndDate   = date( "D, M j, Y  g:i A", $AdRow[5] );				  
	  $Description = $AdRow[6];
	  $UserAdAcctID = $AdRow[7];
	  
	  $TimeLeft = $AdRow[5] - time();
	  if ( $TimeLeft <= 0 )
	  {
		$TimeLeft = "Auction has ended.";
	  }
	  else
	  {
		$Days = date( "j", $TimeLeft );
		$HMS  = date( "g:i:s", $TimeLeft );
		$TimeLeft = "$Days days, $HMS";
	  }
	}
	else
	{
		print( "<html><head><title>Auction Ended Redirect</title></head><body>\n" );
		print( "<meta http-equiv=\"refresh\" content=\"0; url=ViewAuctionAd.php?AdID=$AdID\">Auction Ended... Redirecting back to auction.<br><br>\n" );
	    print( "Click <a href=\"ViewAuctionAd.php?AdID=$AdID\">here</a> if the page did not redirect you back to the ad.</body></html>\n" );
		exit;
	}

	$query = "SELECT MemberID, City, State FROM UserAccounts, AuctionAds WHERE UserAccounts.UserAccountID=$UserAdAcctID";
	$result = mysql_query( $query, $link );		
		
	if ( $MemberRow = mysql_fetch_row( $result ) )
	{
		$Seller = "<a href=\"javascript:viewMember('$MemberRow[0]')\">$MemberRow[0]</a>";
		$City = $MemberRow[1];
		$State = $MemberRow[2];
	}	
	
	$CurrentBidWinner = -1;
	$Price = GetCurrentPrice( $CurrentBidWinner );
	
	$BidErrorMsg = "";

	if ( isset($Submit) )
	{
		// Calculate the new winning bidder if any, notify the previous winning bidder that
		// they have been outbid if any, and set the error messages to be displayed if any.

		// Make sure that the user ID is set and the that logged in user is not the same as
		// the user that is selling the item.

		if ( ($UserAcctID != -1) && ($UserAcctID != $UserAdAcctID) )
		{
			// Make sure that the bid is greater than the current price + bid increment
			
			if ( $MaxBid < ($Price + GetBidIncrement($Price)) )
			{
				$BidErrorMsg  = "Max bid must be greater than or equal to the minimum bid amount.";    
			}
			else
			{
				$TimeNow = time();
				$IP = getenv("REMOTE_ADDR"); 
			
				$query = "INSERT INTO Bids (AdID, UserAcctID, MaxBid, BidDate, CreatedIPAddress) ";
				$query .= "VALUES ($AdID, $UserAcctID, $MaxBid, $TimeNow, '$IP')";
				$result = mysql_query( $query, $link );					

				if ( !$result )
				{
					$BidErrorMsg = "Error submitting bid.";    
					echo mysql_error();
				}
				else
				{
					$BidMsg = "Bid Accepted.  You are currently not the highest bidder for this auction though.";
					
					$NewBidWinner = -1;    
					$Price = GetCurrentPrice( $NewBidWinner );
					
					// Send a message to the previous winner stating that they've been outbid.
					// Only send the message if the price meets the reserve price and there was a previous winner.

					if ( ($CurrentBidWinner != $NewBidWinner) && 
						 ($CurrentBidWiner != -1) && 
						 ($Price >= $ReservePrice) )
					{	
						// Notify the old bidding winner that they have been outbid
						
						$MessageSubject = "Outbid message for \"$AdTitle\"";

						$MessageBody = "<html><head><title>$MessageSubject</title></head><body>\n"; 
						$MessageBody .= "<p>A higher bid has been placed for \"$AdTitle\".\n";
						$MessageBody .= "<p>Click on the following link, or copy and paste into your browser to view the auction:</p><br>\n";
						$MessageBody .= "<p>&nbsp; &nbsp; &nbsp; &nbsp;<a href=\"http://$DomainName/ViewAuctionAd.php?AdID=$AdID\">http://$DomainName/ViewAuctionAd.php?AdID=$AdID</a></p>\n";
						$MessageBody .= "</body></html>";
						
						$query = "SELECT Email FROM UserAccounts WHERE UserAccountID='$CurrentBidWinner'";
						$result = mysql_query( $query, $link );		
						if ( $row = mysql_fetch_row( $result ) )
						{
						    $ToEmail = $row[0];
						    $Success = mail( $ToEmail, $MessageSubject, $MessageBody, "From: bidmessenger@$EmailDomain\n" ); 
						
						    if ( !$Success )
						    {						
								// TODO: Log this error and place e-mail into spool for resending later
						    }						    
						}
						else
						{
						     // TODO: log this error 

						}																		

						// Update the current bid winner

						$CurrentBidWinner = $NewBidWinner;
					}
					
					if ( $NewBidWinner == $UserAcctID )
					{
						$BidMsg = "Bid Accepted.  You are currently the highest bidder!";
					}

					if ( $Price < $ReservePrice )
						$BidMsg = "Bid Accepted.  The reserve price has not yet been met.";
				}
			}
		}
		else
		{
			$BidErrorMsg = "User ID not set or invalid user ID.";
		}
	}

	// Get the updated number of bids - perhaps optimize by doing this initially 
	// at the top and then adding one for the current new bid if one was submitted
	// so that we don't have to hit the SQL server three times.

	$query = "SELECT UserAcctID, MaxBid FROM Bids WHERE AdID=$AdID";
	$result = mysql_query( $query, $link );			
	$NumBids = mysql_num_rows( $result );

	$BidIncrement = GetBidIncrement( $Price );

	if ( $CurrentBidWinner != -1 )
	{
		$query = "SELECT MemberID FROM UserAccounts WHERE UserAccountID=$CurrentBidWinner";
		$result = mysql_query( $query, $link );		
		
		if ( $MemberRow = mysql_fetch_row( $result ) )
		{
			$CurrentBidWinner = "<a href=\"javascript:viewMember('$MemberRow[0]')\">$MemberRow[0]</a>";
		}
	}		
?>
<html>
<head>
<title><? echo $AdTitle; ?> -- Bidding Confirmation</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
<META HTTP-EQUIV="Expires" CONTENT="-1">
</head>
<body bgcolor="#FFFFFF">
<?	
	// Include the standard header

	include( "header.inc" );
?>
<table width="100%" border="0" cellspacing="0" cellpadding=4">
  <tr> 
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="4">
  <tr> 
    <td> 
      <table width="100%" border="1" cellspacing="0" cellpadding="4">
        <tr bgcolor="#FFCC00"> 
          <td> 
            <div align="center"><b><font face="Arial, Helvetica, sans-serif" size="2">Auction 
              Bid Confirmation <? print( "(Item # $AdID)" ); ?></font></b><b><font face="Arial, Helvetica, sans-serif" size="2"><b> 
              </b></font></b></div>
          </td>
        </tr>
        <tr bgcolor="#CCCCFF"> 
          <td> 
            <div align="center"><b><font size="4"><? print( "$AdTitle" ); ?> </font></b></div>
          </td>
        </tr>
        <tr> 
          <td> 
            <p>&nbsp;</p>
            <p><br>
            </p>
            <center>
<p>
<?
	print( "<p><font color=\"#FF0000\"><b>$BidErrorMsg</b></font></p>\n" );
	print( "<p><b>$BidMsg</b></p>\n" );
?> 
</p>
              <p>&nbsp;</p>
              <p><b><font face="Arial, Helvetica, sans-serif" size="2"><b>( <a href="PreviewAuctionBid.php?AdID=<? echo $AdID; ?>&MaxBid=<? echo $MaxBid; ?>">Click 
                here to go back and bid on the item.</a> ) </b></font></b></p>
              <p><font face="Arial, Helvetica, sans-serif" size="2">- Or -</font></p>
              <p><b><font face="Arial, Helvetica, sans-serif" size="2"><b>( <a href="ViewAuctionAd.php?AdID=<? echo $AdID; ?>">Click 
                here to go back and view the description of the item.</a> ) </b></font></b></p>
              <p><b><font face="Arial, Helvetica, sans-serif" size="2"></font></b> 
              </p>
            </center>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
<?
	include( "footer.inc" );
?> 
</body>
</html>
