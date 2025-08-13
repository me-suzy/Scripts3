<?
   include( "../config.php" );
   include( "../usersession.inc" );
   UpdateUserSession();
   include( "../dblink.inc" );
?>
<html>
<head>
<title>View Ads</title>
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
        Ads </font></b></font></p>
        <table width="70%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td>
            <p><font face="Arial, Helvetica, sans-serif" size="2">Click on the 
              title of the ad that you wish to view, or click on &quot;[edit]&quot; 
              to update, disable, delete, or renew the ad. Only classifieds may 
              be updated, disabled, renewed, or deleted. </font></p>
            <p><font face="Arial, Helvetica, sans-serif" size="2">Auctions ads 
              cannot be modified or deleted once a bid has been placed. If you 
              have an exceptional reason for modifying your auction ad, then you 
              may contact us at <a href="mailto:support@$EmailDomain">support@<? echo $EmailDomain; ?></a>. 
              </font></p>
          </td>
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
			
		$query = "SELECT AdID, Title, BeginDate, EndDate FROM AuctionAds WHERE UserAcctID='$UserAcctID'";
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

			// Allow an ad to be edited only if the auction has no bids

			$EditLink = "";

			$query = "SELECT BidID FROM Bids WHERE AdID=$row[0]";
			$NumBidsResult = mysql_query( $query, $link );		

			if ( mysql_num_rows($NumBidsResult) == 0 )
			{
				$EditLink = "[<a href=\"ModifyAuctionAd.php?AdID=$row[0]\">edit</a>]";
			}				

			print( "  <td width=\"*\"><a href=\"../ViewAuctionAd.php?AdID=$row[0]\">$row[1]</a> ( <a href=\"../ViewBids.php?AdID=$row[0]\">$NumBids bids</a> ) $EditLink</td>\n" );
			print( "  <td width=\"250\"><b>$EndDate CST</b></td>\n" );

			print( "</tr>\n" );
		}
?> 
        </table>

<br><br><hr noshade>

        <h3>Classifieds</h3>
        
      <table width="100%" border="0" cellspacing="0" cellpadding="4">
        <tr> 
            
          <td width="50" bgcolor="#FFCC00"> 
            <div align="center"><font face="Arial, Helvetica, sans-serif" size="2"><b>Active</b></font></div>
            </td>          
            
          <td width="*" bgcolor="#FFCC00"><font face="Arial, Helvetica, sans-serif" size="2"><b>Title</b></font></td>            
            
          <td width="250" bgcolor="#FFCC00"><font face="Arial, Helvetica, sans-serif" size="2"><b>End 
            Date </b></font></td>
          </tr>
<?	
		$query = "SELECT AdID, Title, BeginDate, EndDate FROM Ads WHERE UserAcctID='$UserAcctID'";
		$result = mysql_query( $query, $link );		
		
		$NumRows = 0;
		while ( $row = mysql_fetch_row( $result ) )
		{
			$EndDate = date( "D, M j, Y  g:i A", $row[3] );			
		
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

			print( "  <td width=\"*\"><a href=\"../ViewClassifiedAd.php?AdID=$row[0]\">$row[1]</a> [<a href=\"ModifyClassifiedAd.php?AdID=$row[0]\">edit</a>]</td>\n" );
			print( "  <td width=\"250\"><b>$EndDate CST</b></td>\n" );

			print( "</tr>\n" );
		}
?> 
        </table>

    </td>
  </tr>
</table>
<?
	include( "../footer.inc" );
?>
</body>
</html>
