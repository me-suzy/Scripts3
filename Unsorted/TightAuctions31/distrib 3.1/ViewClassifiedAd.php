<?
    include( "config.php" );
	include("usersession.inc");			
	UpdateUserSession();
    include( "dblink.inc" );

	$TimeNow = time();
	$UserAcctID = GetSessionUserID();	

	if ( $mode == "preview" )
		$query = "SELECT Title, Price, Quantity, BeginDate, EndDate, Description, UserAcctID  FROM Ads WHERE AdID=$AdID AND UserAcctID=$UserAcctID";
	else
		$query = "SELECT Title, Price, Quantity, BeginDate, EndDate, Description, UserAcctID FROM Ads WHERE AdID=$AdID AND EndDate>$TimeNow";

	$result = mysql_query( $query, $link );		

	if ( $AdRow = mysql_fetch_row( $result ) )
	{
	  $AdTitle = $AdRow[0];
	  $AdPrice = $AdRow[1];
	  $Quantity  = $AdRow[2];
	  $BeginDate = date( "D, M j, Y  h:i A", $AdRow[3] );
	  $EndDate   = date( "D, M j, Y  h:i A", $AdRow[4] );				  
	  $Description = $AdRow[5];
	  $UserAdAcctID = $AdRow[6];

	  $TimeLeft = $AdRow[4] - time();
	  if ( $TimeLeft <= 0 )
	  {
		$TimeLeft = "Ad has ended.";
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

	$query = "SELECT MemberID, City, State FROM UserAccounts, Ads WHERE UserAccounts.UserAccountID=$UserAdAcctID";
	$result = mysql_query( $query, $link );		
		
	if ( $MemberRow = mysql_fetch_row( $result ) )
	{
		$Seller = "<a href=\"javascript:viewMember('$MemberRow[0]')\">$MemberRow[0]</a>";
		$SellerFeedback = "( <a href=\"ViewFeedback.php?MemberID=$MemberRow[0]\" target=\"_blank\">View Feedback</a> )";
		$City = $MemberRow[1];
		$State = $MemberRow[2];
	}

?>
<html>
<head>
<title><? echo $AdTitle; ?> -- Classified Ad</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
<META HTTP-EQUIV="Expires" CONTENT="-1">
<META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">
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
	if ( $mode == "preview" )
	{
		print( "<p align=\"center\"><font face=\"Arial, Helvetica, sans-serif\" size=\"2\"><a href=\"viewad.php?AdID=$AdID&mode=preview\">Click here to refresh your ad view.</a></font></p>\n" );
	}
?> 
<?
	include( "header.inc" );
?>
<?

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
          <td width="85"><font face="Arial, Helvetica, sans-serif" size="2"><b><font color="#3366cc">Price</font></b></font></td>
          <td><b><font face="Arial, Helvetica, sans-serif" size="2"><? if (isset($AdPrice)) print( "$ $AdPrice" ); ?></font></b></td>
          <td width="120"><font face="Arial, Helvetica, sans-serif" size="2"><b><font color="#3366cc">Date 
            Range</font></b></font></td>
          <td> 
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="75">Time Left: </td>
                <td><b><font face="Arial, Helvetica, sans-serif" size="3" color="#336600"><? if (isset($TimeLeft)) print( "$TimeLeft" ); ?></font></b></td>
              </tr>
              <tr> 
                <td width="75"><font size="2">Begin Date: </font></td>
                <td><b><font face="Arial, Helvetica, sans-serif" size="2"><? if (isset($BeginDate)) print( "$BeginDate CST" ); ?></font></b></td>
              </tr>
              <tr> 
                <td><font size="2">End Date: </font></td>
                <td><b><font face="Arial, Helvetica, sans-serif" size="2"><? if (isset($EndDate)) print( "$EndDate CST" ); ?></font></b></td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td><font face="Arial, Helvetica, sans-serif" size="2"><b><font color="#3366cc">Quantity</font></b></font></td>
          <td width="41%"><? if (isset($Quantity)) print( "$Quantity" ); ?></td>
          <td><font face="Arial, Helvetica, sans-serif" size="2"><b><font color="#3366cc">Location</font></b></font></td>
          <td width="42%"><b><font face="Arial, Helvetica, sans-serif" size="2"><? if (isset($City) && isset($State)) print( "$City, $State" ); ?></font></b></td>
        </tr>
        <tr> 
          <td><font face="Arial, Helvetica, sans-serif" size="2"><b></b></font></td>
          <td width="41%">&nbsp;</td>
          <td><font face="Arial, Helvetica, sans-serif" size="2"><b><font color="#3366cc">Seller</font></b></font></td>
          <td width="42%"><b><? print( "$Seller $SellerFeedback" ); ?> &nbsp; &nbsp; <font size="1" face="Arial, Helvetica, sans-serif">(Click 
            on the Member ID of the seller to send a message.)</font> </b></td>
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
                  # <? echo $AdID; ?></b></font> </td>
              </tr>
            </table>
          </td>
        </tr>
		<tr>
		  <td>
<?
		if ( $AdRow )
		{
			print( "$Description\n" );
		}
		else
		{
			print( "<br><p><font color=\"#FF0033\">Ad has expired or is invalid.</font></p></td>\n");
		}		
?>
		  </td>
		</tr>
      </table>
    </td>
  </tr>
</table>
<br>
<?
	include( "footer.inc" );
?>
</body>
</html>
