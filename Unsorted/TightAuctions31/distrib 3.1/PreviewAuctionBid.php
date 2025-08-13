<?
    include( "config.php" );
	include("usersession.inc");			
	UpdateUserSession();
    include( "dblink.inc" );	
	include( "BidIncrements.inc" );
	include( "CurrentPrice.inc" );

	$TimeNow = time();
	$UserAcctID = GetSessionUserID();		

	$query = "SELECT Title, StartPrice, ReservePrice, Quantity, BeginDate, EndDate, Description, UserAcctID FROM AuctionAds WHERE AdID=$AdID";
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

	  $AuctionClosed = false;
	  $TimeLeft = $AdRow[5] - time();
	  if ( $TimeLeft <= 0 )
	  {
		$TimeLeft = "Auction has ended.";
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
	}

	$query = "SELECT MemberID, City, State FROM UserAccounts, AuctionAds WHERE UserAccounts.UserAccountID=$UserAdAcctID";
	$result = mysql_query( $query, $link );		
		
	if ( $MemberRow = mysql_fetch_row( $result ) )
	{
		$Seller = "<a href=\"javascript:viewMember('$MemberRow[0]')\">$MemberRow[0]</a>";
		$City = $MemberRow[1];
		$State = $MemberRow[2];
	}	
	
	// Get the updated number of bids - perhaps optimize by doing this initially 
	// at the top and then adding one for the current new bid if one was submitted
	// so that we don't have to hit the SQL server three times.

	$query = "SELECT UserAcctID, MaxBid FROM Bids WHERE AdID=$AdID";
	$result = mysql_query( $query, $link );			
	$NumBids = mysql_num_rows( $result );

	$CurrentBidWinner = -1;
	$Price = GetCurrentPrice( $CurrentBidWinner );	
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
<title><? echo $AdTitle; ?> -- Auction Ad -- <? echo $SiteName; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
<META HTTP-EQUIV="Expires" CONTENT="-1">
</head>
<SCRIPT LANGUAGE="JavaScript">
<!-- 
function viewMember(AdMemberID)
{
	var newWin = window.open("../viewmember.php?ToMemberID=" + AdMemberID, "viewmember", "width=500,height=450, scrollbars=1, resizable=yes");	
	newWin.focus();
}

function isNumber(theElement, theElementName)
{
  s = theElement.value;
  if ( (s == "") || (isNaN(Math.abs(s)) && (s.charAt(0) != '#')))
  {
	alert( theElementName +  " must be a number." );
    theElement.focus(); 
    return false;
  }
  return true;
}

function Validate() 
{
if ( document.ChangeBid.MaxBid.value=="" ) {
	alert( "Please enter in a bid value to be changed to." );
	document.ChangeBid.MaxBid.focus();
	return false;
}
if ( !isNumber(document.ChangeBid.MaxBid, "Change Bid" ) ) {
	return false;
}
return true;
}

function SubmitChangeBid()
{
	if ( !Validate() )
		return false;

	document.ChangeBid.submit();
	return true;
}

//-->
</SCRIPT>
<body bgcolor="#FFFFFF">
<?	
	// Include the standard header

	include( "header.inc" );

	function getCategoryPath( $CatID  )
	{
		global $link;

		$query = "SELECT ParentCatID, Name FROM Categories WHERE CategoryID=$CatID";
		$result = mysql_query( $query, $link );		
	
		if ( $row = mysql_fetch_row( $result ) )		
		{
			$subpath = getCategoryPath( $row[0] );
			$subpath .= " :: ";

			$query = "SELECT Name FROM Categories WHERE CategoryID=$CatID";
			$result = mysql_query( $query, $link );					

			if ( $row = mysql_fetch_row( $result ) )		
				$subpath .= "<a href=\"viewcat.php?CatID=$CatID\">$row[0]</a>";

			return $subpath;
		}
		else
		{
			return "<a href=\"viewcat.php?CatID=-1\">Categories</a>";
		}
	}

	$query = "SELECT CatID FROM Ads WHERE AdID=$AdID AND EndDate > $TimeNow";
	$result = mysql_query( $query, $link );		
	
	if ( $row = mysql_fetch_row( $result ) )	
	{
		$CatPath = getCategoryPath($row[0]);
	}
?>
<table width="100%" border="0" cellspacing="0" cellpadding=4">
  <tr>
    <td width="*"><font face="Arial, Helvetica, sans-serif" size="3"><b><? print( "$CatPath" ); ?></b></font></td>
  <tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="4">
  <tr>
    <td>        
      <table width="100%" border="0" cellspacing="0" cellpadding="4">
        <tr bgcolor="#FFCC00"> 
          <td> 
            <div align="center"><b><font size="4"><? print( "$AdTitle" ); ?></font></b></div>
            </td>
          </tr>
        </table>        
    </td>
  </tr>
  <tr>
    <td>
      <table width="100%" border="0" cellspacing="0" cellpadding="4">
        <tr> 
          <td width="85"><font face="Arial, Helvetica, sans-serif" size="2" color="#3366cc"><b>Price</b></font></td>
          <td><b><font face="Arial, Helvetica, sans-serif" size="3" color="#336600"><? if (isset($Price)) printf( "$ %.2f", $Price ); ?> 
            </font><font size="1" face="Arial, Helvetica, sans-serif"><? if ( $Price < $ReservePrice ) print( "(Reserve not yet met.)" ); ?></font><font face="Arial, Helvetica, sans-serif" size="3" color="#336600"> 
            </font></b></td>
          <td width="120"><font face="Arial, Helvetica, sans-serif" size="2" color="#3366cc"><b>Date Range</b></font></td>
          <td> 
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr> 
                <td width="75">Time Left: </td>
                <td><b><font face="Arial, Helvetica, sans-serif" size="3" color="#336600"><? if (isset($TimeLeft)) print( "$TimeLeft" ); ?></font></b></td>
              </tr>
              <tr> 
                <td><font size="2">Begin Date: </font></td>
                <td><b><font face="Arial, Helvetica, sans-serif" size="1"><? if (isset($BeginDate)) print( "$BeginDate CST" ); ?></font></b></td>
              </tr>
              <tr>
                <td><font size="2">End Date:</font> </td>
                <td><b><font face="Arial, Helvetica, sans-serif" size="1"><? if (isset($EndDate)) print( "$EndDate CST" ); ?></font></b></td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td><font face="Arial, Helvetica, sans-serif" size="2" color="#3366cc"><b>Quantity</b></font></td>
          <td width="41%"><? if (isset($Quantity)) print( "$Quantity" ); ?></td>
          <td><font face="Arial, Helvetica, sans-serif" size="2" color="#3366cc"><b>Location</b></font></td>
          <td width="42%"><b><font face="Arial, Helvetica, sans-serif" size="2"><? if (isset($City) && isset($State)) print( "$City, $State" ); ?></font></b></td>
        </tr>
        <tr> 
          <td><font face="Arial, Helvetica, sans-serif" size="2" color="#3366cc"><b>Bids</b></font></td>
          <td width="41%"><? if (isset($NumBids)) print( "$NumBids" ); ?> &nbsp;<font face="Arial, Helvetica, sans-serif" size="2"><? if ( $CurrentBidWinner != -1 ) print( "( Highest Bidder: <b>$CurrentBidWinner</b> )"); ?></font></td>
          <td><font face="Arial, Helvetica, sans-serif" size="2" color="#3366cc"><b>Seller</b></font></td>
          <td width="42%"><b><? print( "$Seller" ); ?><br>
            <font size="1" face="Arial, Helvetica, sans-serif">(Click on the Member 
            ID of the seller to send a message.)</font> </b></td>
        </tr>
      </table>
      <table width="100%" border="0" cellspacing="0" cellpadding="4">
        <tr> 
          <td bgcolor="#FFCC00"><font face="Arial, Helvetica, sans-serif" size="2"><b>Preview 
            Bid <font size="1" face="Arial, Helvetica, sans-serif">(<a href="ViewAuctionAd.php?AdID=<? echo $AdID; ?>">Click 
            here to go back and view the description of the item.</a>)</font> 
            </b></font> </td>
        </tr>
        <tr>
          <td> <br>
<center>
              <a name="SubmitMsg"></a> <?
	print( "<p><font color=\"#FF0000\"><b>$BidErrorMsg</b></font></p>\n" );
	print( "<p><b>$BidMsg</b></p>\n" );
?> 
            </center>
<?
if ( !$AuctionClosed )
{
?>  
          <table width="400" border="1" cellspacing="0" cellpadding="2" align="center">
<?
	if ( $UserAcctID == -1 ) 
	{
?> 
              <tr> 
                <td><font face="Arial, Helvetica, sans-serif" size="2">To place 
                  a bid please <a href="signin.php" target="_blank">login</a>, 
                  or <a href="register.php" target="_blank">register</a> for an 
                  account first. Then click on the refresh button on your browser 
                  for this page, or click <a href="ViewAuctionAd.php?AdID=<? echo $AdID; ?>">here</a> 
                  to refresh the page.</font></td>
              </tr>
<?
	} 

	if ( $UserAcctID == $UserAdAcctID ) {
?> 
              <tr> 
                <td><font face="Arial, Helvetica, sans-serif" size="2">Sellers may not bid on their own ads.</font></td>
              </tr>
<?
	}
?>
              <tr> 
                <td> 
                  <table border="0" cellspacing="0" cellpadding="4" width="100%">
                    <form method="post" action="SubmitAuctionBid.php?AdID=<? echo $AdID; ?>" name="SubmitBid">
                      <tr> 
                        <td width="150"><font face="Arial, Helvetica, sans-serif" size="2"><b><font color="#3366cc">Current 
                          Bid </font></b></font></td>
                        <td width="*">$ <? if (isset($Price)) printf( "%.2f", $Price ); ?></td>
                      </tr>
                      <tr> 
                        <td><font face="Arial, Helvetica, sans-serif" size="2"><b><font color="#3366cc">Bid 
                          Increment </font></b></font></td>
                        <td>$ <? if (isset($BidIncrement)) printf( "%.2f", "$BidIncrement" ); ?></td>
                      </tr>
                      <tr> 
                        <td><font face="Arial, Helvetica, sans-serif" size="2"><b><font color="#3366cc">Minimum 
                          Bid </font></b></font></td>
                        <td>$ <? if (isset($Price) && isset($BidIncrement)) 
							   	    { 
										$MinBid = $Price + $BidIncrement;
										printf( "%.2f", $MinBid ); 
							   		} ?></td>
                      </tr>
<?
	if ( ($UserAcctID != -1) && ($UserAcctID != $UserAdAcctID ) ) 
	{
?> 
                      <tr> 
                        <input type="hidden" name="MaxBid" value="<? printf( "%.2f", $MaxBid ); ?>">
                        <td><font face="Arial, Helvetica, sans-serif" size="2"><b><font color="#3366cc">Your 
                          Maximum Bid</font></b></font></td>
                        <td><b>$ <? if (isset($MaxBid)) { printf( "%.2f", $MaxBid ); }?></b></td>
                      </tr>
                      <tr> 
                        <td><font face="Arial, Helvetica, sans-serif" size="2"><b></b></font></td>
                        <td> 
                          <input type="submit" name="Submit" value="Submit Bid">
                        </td>
                      </tr>
                    </form>
<?
	}
?> 
                  </table>
                </td>
              </tr>
<?
	if ( ($UserAcctID != -1) && ($UserAcctID != $UserAdAcctID ) ) 
	{
?> 
              <tr>
                <td>
                  <table width="100%" border="0" cellspacing="0" cellpadding="4">
                    <form method="post" action="PreviewAuctionBid.php?AdID=<? echo $AdID; ?>" name="ChangeBid">
                    <tr>
                      <td width="150"><font face="Arial, Helvetica, sans-serif" size="2"><b><font color="#3366cc">Change 
                        Maximum Bid</font></b></font></td>
                      <td>
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr> 
                            <td> 
                              <input type="text" name="MaxBid" maxlength="30" size="12">
                            </td>
                            <td> 
                              <input type="submit" name="Submit" value="Change Bid" onclick='return SubmitChangeBid();'>
                            </td>
                          </tr>
                        </table>
                      </td>
                    </tr>
                      </form>
                  </table>
                </td>
              </tr>
<?
	}
?> 
              <tr> 
                <td> 
                  <p><font face="Arial, Helvetica, sans-serif" size="2">+ Do not 
                    enter a dollar sign with your bid. Only enter in numbers and 
                    a decimal point to separate the dollar amount from cents.</font></p>
                  <p><font face="Arial, Helvetica, sans-serif" size="2">+ Place 
                    a bid only if you're serious about buying the item. If you 
                    are the winning bidder, you will enter into a legally binding 
                    contract to purchase the item from the seller. </font></p>
                </td>
              </tr>
            </table>
<?
}	// end if not auction closed
?>
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
