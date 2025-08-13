<?
	include( "usersession.inc" );
	include( "BidIncrements.inc" );
	include( "CurrentPrice.inc" );
	include( "config.php" );
	include( "dblink.inc" );

	if ( isset($NumAds) )
	{
		$NumAdsToDisplay = $NumAds;
		SaveNumAdsToDisplay( $NumAdsToDisplay );
	}
	else
	{
	
		$NumAdsToDisplay = GetNumAdsToDisplay();
	}

    $AdDisplayOptions = GetAdDisplayOptions();	

	UpdateUserSession();

	if ( !isset($search) )
		$search = "";

	$search = trim($search);
?>
<html>
<head>
<title>Search</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
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

.smalltext
{
	font-size:8pt; 
}

-->
</style>

<script>
<!--
function reload( Menu, PageNum )
{
<?
	$SearchEntities = htmlentities($search, ENT_QUOTES);

	$trans = get_html_translation_table(HTML_ENTITIES); 
	$trans[" "] = "&nbsp"; 
	$trans["\\"] = "%5C"; 
	$SearchEntities = strtr($SearchEntities, $trans);

	print( "\tvar Search = '$SearchEntities';" );
?>

	window.location = "search.php?search=" + Search + "&page=" + PageNum + "&NumAds=" + 
					   Menu.NumAdsSelect.options[ Menu.NumAdsSelect.selectedIndex ].value;
}
//-->
</script>

</head>
<body bgcolor="#FFFFFF">
<?
	include( "header.inc" );
?>
<form method="get" action="search.php" name="Search">
  <font face="Arial, Helvetica, sans-serif" size="3"><b>Search:</b></font> 
  <input type="text" class="inputbox" name="search" size="30" <? $SearchEnt = htmlentities($search, ENT_COMPAT); print( "value=\"$SearchEnt\""); ?> >
  <input type="submit" class="button" name="Submit" value="Find">
</form>

<?
	if ( $search != "" )
	{
		if ( !isset($page) || ($page == "") )
			$page = 1;

		$RowStart = $NumAdsToDisplay * ($page-1);

		// Generate the page number links

		$TimeNow = time();

		$searchSQL = addslashes($search);

		$query = "SELECT AdID FROM Ads ";
		$query .= "WHERE (EndDate > $TimeNow) AND (Title LIKE \"%$searchSQL%\" OR Price LIKE \"%$searchSQL%\" OR Description LIKE \"%$searchSQL%\")";

		$result = mysql_query( $query, $link );		
		$NumClassifiedAds = mysql_numrows( $result );

		$query = "SELECT AdID FROM AuctionAds ";
		$query .= "WHERE (EndDate > $TimeNow) AND (Title LIKE \"%$searchSQL%\" OR Description LIKE \"%$searchSQL%\")";

		$result = mysql_query( $query, $link );		
		$NumAuctionAds = mysql_numrows( $result );

		$NumAds = $NumClassifiedAds + $NumAuctionAds;

		$TempTableName = "tmpAdsTable";

		// Auction ads have a 0 in the last column and classified ads have a 1 in the last column.

		$query = "CREATE TEMPORARY TABLE ";
		$query .= $TempTableName;
		$query .= " SELECT AdID, Title, StartPrice, ReservePrice, EndDate, 0 FROM AuctionAds WHERE (EndDate > $TimeNow) AND (Title LIKE \"%$searchSQL%\" OR Description LIKE \"%$searchSQL%\") LIMIT $RowStart, $NumAdsToDisplay";

		$result = mysql_query( $query, $link );

		$query = "INSERT INTO ";
		$query .= $TempTableName;
		$query .= " SELECT AdID, Title, Price, -1, EndDate, 1 FROM Ads ";
		$query .= "WHERE (EndDate > $TimeNow) AND (Title LIKE \"%$searchSQL%\" OR Price LIKE \"%$searchSQL%\" OR Description LIKE \"%$searchSQL%\") LIMIT $RowStart, $NumAdsToDisplay";

		$result = mysql_query( $query, $link );

		$query = "SELECT * From ";
		$query .= $TempTableName;

		$result = mysql_query( $query, $link );

		$NumPages = ceil( $NumAds / $NumAdsToDisplay );

		$NumDisplayedPageLinks = 10;

		$StartPage = $page - round( $NumDisplayedPageLinks / 2 );
		if ($StartPage < 1)
		{
			$AddToEnd = 1 - $StartPage;
			$StartPage = 1;
		}

		$EndPage = $page + round( $NumDisplayedPageLinks / 2 );
		if ($EndPage > $NumPages)
		{
			$AddToBegin = $EndPage - $NumPages;
			$EndPage = $NumPages;
		}

		$StartPage -= $AddToBegin;
		$EndPage   += $AddToEnd;

		if ($StartPage < 1)
			$StartPage = 1;

		if ($EndPage > $NumPages)
			$EndPage = $NumPages;

		$PageLinks = "";

		if ( $StartPage != $EndPage )
		{
			if ( $page != 1 )
			{
				$PreviousPage = $page - 1;
				
				$PageLinks .= "<a href=\"search.php?search=$search&page=$PreviousPage\">Previous</a> ";
			}

			for ( $i = $StartPage; $i <= $EndPage; $i++ )
			{
				if ( $i == $page )
				{
					if ( $i == $EndPage )
						$PageLinks .= "<font color=\"#005522\" size=\"3\"><b>$i</b></font> ";
					else
						$PageLinks .= "<font color=\"#005522\" size=\"3\"><b>$i</b></font>, ";
				}
				else if ( $i == $EndPage )
				{
					$PageLinks .= "<a href=\"search.php?search=$search&page=$i\">$i</a> ";
				}
				else
				{
					
					$PageLinks .= "<a href=\"search.php?search=$search&page=$i\">$i</a>, ";
				}
			}		

			if ( $page != $NumPages )
			{
				$NextPage = $page + 1;

				$PageLinks .= "<a href=\"search.php?search=$search&page=$NextPage\">Next</a> ";
			}

$PageLinksHTML = <<<PAGELINKSHTML
<table border=0 bgcolor='#FFCC00' width='100%' cellpadding=0 cellspacing=0>
	<tr><td width='100%'>
    <table border=0 bgcolor='#FFCC00' width='100%' cellpadding=0 cellspacing=1>
		<form>
		<tr><td>                   
	    <table width=100% bgcolor=white border=0 cellspacing=0 cellpadding=3>
		  <tr bgcolor='#FFCC00'>
		     <td width='*'>
				<div align="right"><font face="Arial, Helvetica, sans-serif" size="2">Ads </font>
PAGELINKSHTML;

			$PageLinksHTML .= "\n<select size=1 name=\"NumAdsSelect\" class=\"smalltext\" onChange=\"reload(this.form, $page)\">";

			for ( $i = 0; $i < count($AdDisplayOptions); $i++ )
			{
				if ( $AdDisplayOptions[$i] == $NumAdsToDisplay )
					$PageLinksHTML .= "  <option selected value=\"$AdDisplayOptions[$i]\">$AdDisplayOptions[$i]</option>\n";
				else
					$PageLinksHTML .= "  <option value=\"$AdDisplayOptions[$i]\">$AdDisplayOptions[$i]</option>\n";
			}

			$PageLinksHTML .= "</select>\n";
			$PageLinksHTML .= "<font face=\"Arial, Helvetica, sans-serif\" size=\"2\">$PageLinks</font></div></td>\n";

			$PageLinksHTML .= "</tr></table></td></tr></form></table></td></tr></table>\n";

		}	// end if ( $StartPage != $EndPage )

	  if ( ($search != "") && ($NumPages != 0) && ($StartPage != $EndPage) )
		print ( "$PageLinksHTML\n" );

	}
?> 
<hr noshade>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr valign="top"> 
    <td> 
<?
	  print( "<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">\n" );
	  print( "  <tr>\n" );
	  print( "    <td width=\"*\" bgcolor=\"#FFCC00\"><font face=\"Arial, Helvetica, sans-serif\" size=\"2\"><b>Title</b></font></td>\n" );
	 
	  print( "    <td align=\"center\" width=\"40\" bgcolor=\"#FFCC00\"><font face=\"Arial, Helvetica, sans-serif\" size=\"2\"><b>Bids</b></font></td>\n" );

	  print( "    <td align=\"center\" width=\"80\" bgcolor=\"#FFCC00\"><font face=\"Arial, Helvetica, sans-serif\" size=\"2\"><b>Price</b></font></td>\n" );
	  print( "    <td width=\"250\" bgcolor=\"#FFCC00\"><font face=\"Arial, Helvetica, sans-serif\" size=\"2\"><b>End Date</b></font></td>\n" );
	  print( "  </tr>\n" );

	  if ($search != "")
	  {		
		$NumRows = 0;
		while ( $row = mysql_fetch_row( $result ) )
		{			
			$NumRows++;

			// Check the last column to see what type of ad it is

			if ( $row[5] == 1 )
			{
				// Classified ad

				$EndDate = date( "D, M j, Y  h:i A", $row[4] );
	
				if ( ($NumRows % 2) != 0 )	
					print( "<tr>\n" );			
				else
					print( "<tr bgcolor=\"#FFFF99\">\n" );	

				print( "  <td width=\"*\"><a href=\"ViewClassifiedAd.php?AdID=$row[0]\">$row[1]</a></td>\n" );
				print( "  <td align=\"center\" width=\"40\">CA</td>\n" );
				print( "  <td align=\"center\" width=\"80\">\$$row[2]</td>\n" );
				print( "  <td width=\"250\">$EndDate CST</td>\n" );
				print( "</tr>\n" );
			}
			else
			{
				// Auction ad

				$AdID = $row[0];
				$Title = $row[1];
				$StartPrice = $row[2];
				$ReservePrice = $row[3];
				$EndDate = date( "D, M j, Y  h:i A", $row[4] );

				$query = "SELECT BidID FROM Bids WHERE AdID=$AdID";
				$NumBidsResult = mysql_query( $query, $link );			
				$NumBids = mysql_num_rows( $NumBidsResult );

				if ( ($NumRows % 2) != 0 )	
					print( "<tr>\n" );			
				else
					print( "<tr bgcolor=\"#FFFF99\">\n" );			

				$BidWinner = -1;
				$Price = GetCurrentPrice( $BidWinner );
		
				print( "  <td width=\"*\"><a href=\"ViewAuctionAd.php?AdID=$row[0]\">$row[1]</a></td>\n" );
				print( "  <td align=\"center\" width=\"40\">$NumBids</td>\n" );
				print( "  <td align=\"center\" width=\"80\">\$$Price</td>\n" );
				print( "  <td width=\"250\">$EndDate CST</td>\n" );
				print( "</tr>\n" );
			}
		}

		$query = "DROP TABLE " + $TempTableName;
		$result = mysql_query( $query, $link );
	  }

	print( "        </table>\n" );
?> 
    </td>
  </tr>
</table>
<hr noshade>
<?
	if ( ($search != "") && ($NumPages != 0) && ($StartPage != $EndPage) )
		print ( "$PageLinksHTML\n" );
?>
<?
	include( "footer.inc" );
?>
</body>
</html>
