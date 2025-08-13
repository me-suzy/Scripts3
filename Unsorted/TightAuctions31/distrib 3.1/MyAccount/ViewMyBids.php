<?
   include( "../config.php" );
   include( "../usersession.inc" );
   UpdateUserSession();
   include( "../dblink.inc" );
?>
<html>
<head>
<title>View Bids</title>
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
<META HTTP-EQUIV="Expires" CONTENT="-1">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<?
	ValidateLoginRedirect();
?>
</head>
<body bgcolor="#FFFFFF">
<?
	include( "../header.inc" );
?>
<table width="100%" border="0" cellspacing="0" cellpadding="10">
  <tr valign="top"> 
    <td> 
      <p><font face="Arial, Helvetica, sans-serif" size="3"><b><font size="4" color="#003399">View 
        My Bids </font></b></font></p>
        <table width="70%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td><font face="Arial, Helvetica, sans-serif" size="2">Click on the 
            title of the ad that you wish to view. </font></td>
        </tr>
      </table>
      <h3>Auctions</h3>
        
      <table width="100%" border="0" cellspacing="0" cellpadding="4">
        <tr> 
            
          <td width="50" bgcolor="#FFCC00"> 
            <div align="center"><font face="Arial, Helvetica, sans-serif" size="2"><b>Active</b></font></div>
          </td>                        
            
          <td width="*" bgcolor="#FFCC00"><font face="Arial, Helvetica, sans-serif" size="2"><b>Title 
            ( # of bids)</b></font></td>                        
            
          <td width="250" bgcolor="#FFCC00"><font face="Arial, Helvetica, sans-serif" size="2"><b>End 
            Date </b></font></td>
          </tr>
<?
		$UserAcctID = GetSessionUserID();			
			
		$query = "SELECT DISTINCT AuctionAds.AdID, AuctionAds.Title, AuctionAds.BeginDate, AuctionAds.EndDate FROM AuctionAds, Bids WHERE Bids.AdID=AuctionAds.AdID AND Bids.UserAcctID='$UserAcctID'";
		$result = mysql_query( $query, $link );		

		$NumRows = 0;		
		while ( $row = mysql_fetch_row( $result ) )
		{
			$query = "SELECT BidID FROM Bids WHERE AdID=$row[0]";
			$NumBidsResult = mysql_query( $query, $link );		

			if ( $NumBidsResult )
				$NumBids = mysql_num_rows($NumBidsResult);
			else
				$NumBids = "Error retrieving bids";

			$EndDate   = date( "D, M j, Y  g:i A", $row[3] );			

			if ( $row[3] > time() )
				$Active = true;
			else
				$Active = false;

			$NumRows++;
	
			if ( ($NumRows % 2) != 0 )	
				print( "<tr>\n" );			
			else
				print( "<tr bgcolor=\"#CCCCFF\">\n" );	

			if ( $Active )
				print( "  <td width=\"50\"><div align=\"center\"><font color=\"#339900\"><b>Y</b></font></div></td>\n" );
			else
				print( "  <td width=\"50\"><div align=\"center\"><font color=\"#CC3300\"><b>N</b></font></div></td>\n" );			

			print( "  <td width=\"*\"><a href=\"../ViewAuctionAd.php?AdID=$row[0]\">$row[1]</a> ( <a href=\"../ViewBids.php?AdID=$row[0]\">$NumBids bids</a> )</td>\n" );
			print( "  <td width=\"250\"><b>$EndDate CST</b></td>\n" );

			print( "</tr>\n" );
		}
?> 
        </table>
<br><br>
    </td>
  </tr>
</table>
<?
	include( "../footer.inc" );
?>
</body>
</html>
