<?
   include( "../config.php" );
   include( "../usersession.inc" );
   UpdateUserSession();
   include( "../dblink.inc" );

	$TimeNow = time();
	$UserAcctID = GetSessionUserID();		

	$query = "SELECT MemberID, City, State FROM UserAccounts WHERE UserAccountID=$UserAcctID";
	$result = mysql_query( $query, $link );		
		
	if ( $MemberRow = mysql_fetch_row( $result ) )
	{
		$Seller = "<a href=\"javascript:viewMember('$MemberRow[0]')\">$MemberRow[0]</a>";
		$SellerFeedback = "( <a href=\"../ViewFeedback.php?MemberID=$MemberRow[0]\" target=\"_blank\">View Feedback</a> )";
		$City = $MemberRow[1];
		$State = $MemberRow[2];
	}

	$BeginDate = date( "D, M j, Y  g:i A", time() );
    $EndDate   = date( "D, M j, Y  g:i A", time() + $AuctionAdExpiration );

	$TimeLeft = $AuctionAdExpiration;
	$Days = floor($TimeLeft / (60 * 60 * 24));
	$TimeLeft = $TimeLeft - $Days * (60 * 60 * 24);

	$Hours = floor($TimeLeft / (60 * 60));
	$TimeLeft = $TimeLeft - $Hours * (60 * 60);

	$Minutes = floor($TimeLeft / 60);

	$Seconds = $TimeLeft - $Minutes * 60;
	$TimeLeft = sprintf( "%d days, %dhrs %dmin %dsec", $Days, $Hours, $Minutes, $Seconds );	

?>
<html>
<head>
<title><? echo $Title; ?> -- Auction Ad</title>
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
//-->
</SCRIPT>
<body bgcolor="#FFFFFF">
<?	
	// Include the standard header

	include( "../header.inc" );

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
				$subpath .= "<a href=\"../viewcat.php?CatID=$CatID&ViewAuctions=true\">$row[0]</a>";

			return $subpath;
		}
		else
		{
			return "<a href=\"../viewcat.php?CatID=-1&ViewAuctions=true\">Categories</a>";
		}
	}

	$CatPath = getCategoryPath($Category);
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
            <div align="center"><b><font size="4"><? print( "$Title" ); ?></font></b></div>
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
          <td><b><font face="Arial, Helvetica, sans-serif" size="3" color="#336600"><? if (isset($StartPrice)) printf( "$ %.2f", $StartPrice ); ?></font></b> <font size="1" face="Arial, Helvetica, sans-serif"><? if ( $StartPrice < $ReservePrice ) print( "(Reserve not yet met.)" ); ?></font></td>
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
          <td width="41%">0<font face="Arial, Helvetica, sans-serif" size="2"></font></td>
          <td><font face="Arial, Helvetica, sans-serif" size="2" color="#3366cc"><b>Seller</b></font></td>
          <td width="42%"><b><? print( "$Seller $SellerFeedback" ); ?><br>
            <font size="1" face="Arial, Helvetica, sans-serif">(Click on the Member 
            ID of the seller to send a message.)</font> </b></td>
        </tr>
      </table>
      <table width="100%" border="0" cellspacing="0" cellpadding="4">
        <tr bgcolor="#FFCC00"> 
          <td> 
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr> 
                <td width="50%"><font face="Arial, Helvetica, sans-serif" size="2"><b>Description</b></font> 
                </td>
                <td align="right" width="50%"><font face="Arial, Helvetica, sans-serif" size="2"><b>Item 
                  # </b></font></td>
              </tr>
            </table>
            
          </td>
        </tr>
		<tr>
		  <td>
<?
	echo $Description;
?>
		  <p>&nbsp;</p>
          </td>
		</tr>
      </table>
      <table width="100%" border="0" cellspacing="0" cellpadding="4">
        <tr> 
          <td bgcolor="#FFCC00"><font face="Arial, Helvetica, sans-serif" size="2"><b>Bidding</b></font> 
          </td>
        </tr>
        <tr>
          <td> 
            <div align="center"><br>
              [ Bidding Form ]<font face="Arial, Helvetica, sans-serif" size="2"></font> 
            </div>
          </td>
        </tr>
      </table>      
    </td>
  </tr>
</table>
<?
	include( "../footer.inc" );
?> 
</body>
</html>
