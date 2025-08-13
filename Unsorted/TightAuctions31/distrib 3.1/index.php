<?
    include( "config.php" );
	include("usersession.inc");			
	UpdateUserSession();
    include( "dblink.inc" );
?>
<html>
<head>
<title>Home Page</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
<style type="text/css">

<!--
.featured { font-family: Arial, Helvetica, sans-serif; font-size: 9pt; font-weight: 100}

.inputbox
{
    BORDER-RIGHT: #FFCC00 1px solid;
    BORDER-TOP: #FFCC00 1px solid;
    BORDER-LEFT: #FFCC00 1px solid;
    BORDER-BOTTOM: #FFCC00 1px solid;
    FONT-WEIGHT: normal;
    FONT-SIZE: 14px;
    COLOR: #333333;
    BACKGROUND-COLOR: #FCE9B3
}

.button {
   font-family: arial;
   font-size:11px;
   font-weight: normal;
   color: 222222;
   background-color:#DDDDDD;
   border-color: CCCCCC;
   border-style: groove;
}

-->

</style>
</head>
<body bgcolor="#FFFFFF">
<?
	include( "header.inc" );
?>
<table width="100%" border="0" cellspacing="0" cellpadding="3">
  <tr> 
    <td bgcolor="#FFCC00" valign="top" width="*"> 
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td width="80" valign="top"><font face="Arial, Helvetica, sans-serif" size="3"><b>Search 
            </b></font></td>
          <td width="*" align="right" valign="top"> 
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <form method="get" action="search.php" name="Search">
                <tr align="left"> 
                  <td> 
                    <input class="inputbox" type="text" name="search" size="20">
                    <input class="button" type="submit" name="Submit" value="  Find  ">
                  </td>
                </tr>
              </form>
            </table>
          </td>
        </tr>
      </table>
    </td>
    <td width="1">&nbsp;</td>
    <td bgcolor="#FFCC00" valign="top" width="400"><font face="Arial, Helvetica, sans-serif" size="3"><b>My 
      Account</b></font> </td>
  </tr>
  <tr> 
    <td valign="top"> <br>
      <table width="100%" border="0" cellspacing="0" cellpadding="3">
        <tr bgcolor="#3366CC"> 
          <td><font color="#FFFFFF" face="Arial, Helvetica, sans-serif" size="3"><b>Browse 
            Auction Categories</b></font><font face="Arial, Helvetica, sans-serif" size="3"></font></td>
        </tr>
      </table>
      <?

	include( "auction_browse_cat.inc" );

?>
      <br>
      <table width="100%" border="0" cellspacing="0" cellpadding="3">
        <tr bgcolor="#FFEE00"> 
          <td><font face="Arial, Helvetica, sans-serif" size="3"><b>Featured Auction 
            Items</b></font></td>
        </tr>
        <tr> 
          <td> 
<?
	$NeedUpdate = false;
	$FeaturedItemsFileName = "featured_auction_items.inc";

	if ( file_exists( $FeaturedItemsFileName ) )
	{
		$LastChanged = filectime( $FeaturedItemsFileName );

		// Update every 15 min.  

		if ( (time() - $LastChanged) > 900 )
		{
			$NeedUpdate = true;
		}
	}
	else
	{
		$NeedUpdate = true;
	}

	if ( $NeedUpdate )
	{
		$myFile = fopen( $FeaturedItemsFileName, "w" );
		if ( $myFile )
		{
			$TimeNow = time();

			// Grab auctions

			$query = "SELECT AdID, Title FROM AuctionAds WHERE EndDate > $TimeNow ORDER BY EndDate";
			$result = mysql_query( $query, $link );		

			$NumAds = 10;
			while ( ($row = mysql_fetch_row( $result )) && ($NumAds > 0) )
			{
				$NumAds--;
				fputs( $myFile, "<tr><td>\n" );	
				print( "<tr><td>\n" );	
				fputs( $myFile, "<img src=\"square_bullet.gif\" width=\"5\" height=\"5\" border=\"0\" align=\"bottom\">&nbsp;<a href=\"ViewAuctionAd.php?AdID=$row[0]\"><span class=\"featured\">$row[1]</span></a>\n" );
				print( "<img src=\"square_bullet.gif\" width=\"5\" height=\"5\" border=\"0\" align=\"bottom\">&nbsp;<a href=\"ViewAuctionAd.php?AdID=$row[0]\"><span class=\"featured\">$row[1]</span></a>\n" );
				fputs( $myFile, "</td></tr>\n" );
				print( "</td></tr>\n" );
			}

			fclose( $myFile );
		}
	}
	else
	{
		// Just read from the file

		$myFile = file( $FeaturedItemsFileName );
		for ( $index = 0; $index < count( $myFile ); $index++ )
		{
			print( $myFile[$index] );
		}
	}
?>
          </td>
        </tr>
      </table>
      <hr noshade>
      <table width="100%" border="0" cellspacing="0" cellpadding="3">
        <tr bgcolor="#3366CC"> 
          <td><font color="#FFFFFF" face="Arial, Helvetica, sans-serif" size="3"><b>Browse 
            Classified </b></font> <font color="#FFFFFF" face="Arial, Helvetica, sans-serif" size="3"><b>Categories</b></font><font face="Arial, Helvetica, sans-serif" size="3"></font></td>
        </tr>
      </table>
<?
	include( "classified_browse_cat.inc" );
?>
      <br>
      <table width="100%" border="0" cellspacing="0" cellpadding="3">
        <tr bgcolor="#FFEE00"> 
          <td><font face="Arial, Helvetica, sans-serif" size="3"><b>Featured Classified 
            Items</b></font></td>
        </tr>
        <tr> 
          <td> 
<?
	$NeedUpdate = false;
	$FeaturedItemsFileName = "featured_classified_items.inc";

	if ( file_exists( $FeaturedItemsFileName ) )
	{
		$LastChanged = filectime( $FeaturedItemsFileName );

		// Update every 15 min.  

		if ( (time() - $LastChanged) > 900 )
		{
			$NeedUpdate = true;
		}
	}
	else
	{
		$NeedUpdate = true;
	}

	if ( $NeedUpdate )
	{
		$myFile = fopen( $FeaturedItemsFileName, "w" );
		if ( $myFile )
		{
			$TimeNow = time();

			// Grab classifieds

			$query = "SELECT AdID, Title FROM Ads WHERE EndDate > $TimeNow ORDER BY EndDate DESC";
			$result = mysql_query( $query, $link );		

			$MaxAds = 100;			

			while ( ($row = mysql_fetch_row( $result )) && ($MaxAds > 0) )
			{
				$MaxAds--;
				$FeaturedAdIDs[] = $row[0];
				$FeaturedTitles[] = $row[1];
			}		

			if ( isset($FeaturedAdIDs) )
			{
				$NumAds = 0;
				$MaxFeaturedAds = count($FeaturedAdIDs);
				$SelectedAdList[] = -1;

				while ( ($NumAds < 10) && ($NumAds != $MaxFeaturedAds) )
				{
					$NumAds++;		
					$UniqueFound = false;

					while ( !$UniqueFound )
					{
						// Protect rand() from ranging 0..0 which generates an error

						if ( $MaxFeaturedAds == 1 )
							$SelectedAd = 0;
						else
							$SelectedAd = rand( 0, $MaxFeaturedAds-1 );						

						$UniqueFound = true;
						for ( $i = 0; $i < count( $SelectedAdList ); $i++ )
						{
							if ( $SelectedAd == $SelectedAdList[$i] )
								$UniqueFound = false;
						}
					}

					$SelectedAdList[] = $SelectedAd;
					$SelectedAdID = $FeaturedAdIDs[ $SelectedAd ];
					$SelectedAdTitle = $FeaturedTitles[ $SelectedAd ];

					fputs( $myFile, "<tr><td>\n" );	
					print( "<tr><td>" );	

					fputs( $myFile, "<img src=\"square_bullet.gif\" width=\"5\" height=\"5\" border=\"0\" align=\"bottom\">&nbsp;<a href=\"ViewClassifiedAd.php?AdID=$SelectedAdID\"><span class=\"featured\">$SelectedAdTitle</span></a>" );
					print( "<img src=\"square_bullet.gif\" width=\"5\" height=\"5\" border=\"0\" align=\"bottom\">&nbsp;<a href=\"ViewClassifiedAd.php?AdID=$SelectedAdID\"><span class=\"featured\">$SelectedAdTitle</span></a>" );

					fputs( $myFile, "</td></tr>\n" );
					print( "</td></tr>\n" );
				}
			}

			fclose( $myFile );
		}
	}
	else
	{
		// Just read from the file

		$myFile = file( $FeaturedItemsFileName );
		for ( $index = 0; $index < count( $myFile ); $index++ )
		{
			print( $myFile[$index] );
		}
	}
?>
          </td>
        </tr>
      </table>
    </td>
    <td>&nbsp;</td>
    <td valign="top"> 
      <ul>
        <li><a href="register.php"><font face="Georgia, Times New Roman, Times, serif" size="4">Register</font></a></li>
        <li><a href="signin.php"><font face="Georgia, Times New Roman, Times, serif" size="4">Login</font></a></li>
        <li><a href="MyAccount/NewAd.php"><font face="Georgia, Times New Roman, Times, serif" size="4">Post 
          a New Ad</font></a></li>
        <li><a href="MyAccount/ViewMyAds.php"><font face="Georgia, Times New Roman, Times, serif" size="4">View 
          My Ads</font></a></li>
      </ul>
</table>
<hr noshade>
<br>
<?
	include( "footer.inc" );
?>
</body>
</html>