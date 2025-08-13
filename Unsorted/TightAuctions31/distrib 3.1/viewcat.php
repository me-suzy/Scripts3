<?
    include( "config.php" );
	include( "usersession.inc");			
    include( "dblink.inc" );
	include( "CurrentPrice.inc" );

	if ( isset( $NumAds ) )
	{
		SaveNumAdsToDisplay( $NumAds );
	    $NumAdsToDisplay = $NumAds;
	}
	else
	{
	    $NumAdsToDisplay = GetNumAdsToDisplay();
	}

    $AdDisplayOptions = GetAdDisplayOptions();

	UpdateUserSession();

	if ( !isset($ViewAuctions) )
		$ViewAuctions = false;

   if ( $CatID != -1 )
   {
		$query = "SELECT Name FROM Categories WHERE CategoryID=$CatID";
		$result = mysql_query( $query, $link );		
	
		if ( $row = mysql_fetch_row( $result ) )
			$CatName = $row[0];
	}
	else
	{
		$CatName = "Categories";
	}

?>
<html>
<head>
<title><? echo $CatName; ?> -- Browsing Categories</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
<META HTTP-EQUIV="Expires" CONTENT="-1">
<meta name="description" content="Get updated with the latest Internet news!  Auctions, classified ads, advertising, auctions, and have fun searching for computers, electronics, hardware, software, CDs, antiques, and more.">
<meta name="keywords" content="auction, classified, free, FFA, advertising, listings, ad, cool, fun, website, news, Internet, computer, electronics, hardware, software, CD, shopping, news, forum">
<script>
<!--
function reload( Menu, CatID, ViewAuctions, PageNum )
{
	if ( ViewAuctions )
		window.location = "viewcat.php?CatID=" + CatID + "&ViewAuctions=true" + "&page=" + PageNum + "&NumAds=" + 
						  Menu.NumAdsSelect.options[ Menu.NumAdsSelect.selectedIndex ].value;
	else
		window.location = "viewcat.php?CatID=" + CatID + "&page=" + PageNum + "&NumAds=" + 
						  Menu.NumAdsSelect.options[ Menu.NumAdsSelect.selectedIndex ].value;
}

//-->
</script>
<style type="text/css">
<!--
.smalltext
{
	font-size:8pt; 
}
-->
</style>
</head>
<body bgcolor="#FFFFFF">
<?
	include( "header.inc" );

	function getCategoryPath( $CatID  )
	{
		global $link, $ViewAuctions;

		$query = "SELECT ParentCatID, Name FROM Categories WHERE CategoryID=$CatID";
		$result = mysql_query( $query, $link );		
	
		if ( $row = mysql_fetch_row( $result ) )		
		{
			$subpath = getCategoryPath( $row[0] );
			$subpath .= " :: ";

			$query = "SELECT Name FROM Categories WHERE CategoryID=$CatID";
			$result = mysql_query( $query, $link );					

			if ( $row = mysql_fetch_row( $result ) )	
			{
				if ( $ViewAuctions )	
					$subpath .= "<a href=\"viewcat.php?CatID=$CatID&ViewAuctions=true\">$row[0]</a>";
				else
					$subpath .= "<a href=\"viewcat.php?CatID=$CatID\">$row[0]</a>";
			}

			return $subpath;
		}
		else
		{
			if ( $ViewAuctions )	
				return "<a href=\"viewcat.php?CatID=-1&ViewAuctions=true\">Categories</a>";
			else
				return "<a href=\"viewcat.php?CatID=-1\">Categories</a>";
		}
	}

$CatPathTablePt1 = <<<CATPATHTABLE1
<table width="100%" border="0" cellspacing="0" cellpadding=4">
  <tr>
    <td width="*"><font face="Arial, Helvetica, sans-serif" size="3"><b>
CATPATHTABLE1;

$CatPathTablePt2 = <<<CATPATHTABLE2
</b></font></td>
  <tr>
</table>
CATPATHTABLE2;

	if ( $CatID != -1 )
	{
		$CatPath = getCategoryPath($CatID);

		$CatPathTable  = $CatPathTablePt1;
		$CatPathTable .= $CatPath;
		$CatPathTable .= $CatPathTablePt2;

		print( "$CatPathTable\n" ); 
	}
?>
<table width="100%" border="0" cellspacing="0" cellpadding="3">
  <tr bgcolor="#FFCC00"> 
    <td width="47%"><font face="Arial, Helvetica, sans-serif" size="3"><b><? print("$CatName"); ?></b></font></td>
  </tr>
  <tr> 
    <td width="47%" valign="top"> 
      <table width="100%" border="0" cellspacing="0" cellpadding="2" align="center">
<?
	if ( $CatID == -1 )
	{
		if ( $ViewAuctions )
			include( "auction_fullbrowse_cat.inc" );
		else
			include( "classified_fullbrowse_cat.inc" );
	}
	else
	{	
		$query = "SELECT CategoryID, Name FROM Categories WHERE ParentCatID=$CatID ORDER BY Name";
		$result = mysql_query( $query, $link );		

		$NumCols = 4;
		$NumRows = mysql_num_rows( $result );
		$CategoriesPerColumn = ceil($NumRows / $NumCols);		

		class Category
		{
			var $id;
			var $name;

			function Category( $id, $name )
			{
				$this->id   = $id;
				$this->name = $name;
			}
		}
		
		while ( $row = mysql_fetch_row( $result ) )
		{
			$CatRows[] = new Category( $row[0], $row[1] );
		}
	
		for ( $CatRow = 0; $CatRow < $CategoriesPerColumn; $CatRow++ )
		{
			print( "<tr>\n" );

			for ( $CatCol = 0; $CatCol < $NumCols; $CatCol++ )
			{
				$CatIdx = $CatRow + $CatCol * $CategoriesPerColumn;

				if ( $CatIdx < $NumRows )
				{
					$catcls = $CatRows[$CatIdx];
					$id   = $catcls->id;
					$name = $catcls->name;

					if ( $ViewAuctions )	
						print( "  <td><font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"2\"><a href=\"viewcat.php?CatID=$id&ViewAuctions=true\"><b>$name</b></a></font></td>\n" );
					else
						print( "  <td><font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"2\"><a href=\"viewcat.php?CatID=$id\"><b>$name</b></a></font></td>\n" );
				}
				else
				{
					print( "  <td></td>\n" );
				}
			}

			print( "</tr>" );
		} 
	}
?> 
      </table>
    </td>
  </tr>
</table>

<?
	if ( $CatID != -1 )
	{
		if ( !isset($page) || ($page == "") )
			$page = 1;

		$RowStart = $NumAdsToDisplay * ($page-1);

		// Generate the page number links

		$TimeNow = time();

		if ( $ViewAuctions )	
			$query = "SELECT * FROM AuctionAds WHERE CatID=$CatID AND EndDate > $TimeNow";
		else
			$query = "SELECT * FROM Ads WHERE CatID=$CatID AND EndDate > $TimeNow";			

		$result = mysql_query( $query, $link );		

		$NumAds = mysql_numrows( $result );

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

				if ( $ViewAuctions )	
					$PageLinks .= "<a href=\"viewcat.php?CatID=$CatID&ViewAuctions=true&page=$PreviousPage\">Previous</a> ";
				else
					$PageLinks .= "<a href=\"viewcat.php?CatID=$CatID&page=$PreviousPage\">Previous</a> ";
			}

			for ( $i = $StartPage; $i <= $EndPage; $i++ )
			{
				if ( $i == $page )
				{
					$PageLinks .= "<font color=\"#005522\" size=\"3\"><b>$i</b></font>, ";
				}
				else if ( $i == $EndPage )
				{
					if ( $ViewAuctions )	
						$PageLinks .= "<a href=\"viewcat.php?CatID=$CatID&ViewAuctions=true&page=$i\">$i</a> ";
					else
						$PageLinks .= "<a href=\"viewcat.php?CatID=$CatID&page=$i\">$i</a> ";
				}
				else
				{
					if ( $ViewAuctions )	
						$PageLinks .= "<a href=\"viewcat.php?CatID=$CatID&ViewAuctions=true&page=$i\">$i</a>, ";
					else
						$PageLinks .= "<a href=\"viewcat.php?CatID=$CatID&page=$i\">$i</a>, ";
				}
			}		

			if ( $page != $NumPages )
			{
				$NextPage = $page + 1;

				if ( isset($ViewAuctions) && $ViewAuctions )	
					$PageLinks .= "<a href=\"viewcat.php?CatID=$CatID&ViewAuctions=true&page=$NextPage\">Next</a> ";
				else
					$PageLinks .= "<a href=\"viewcat.php?CatID=$CatID&page=$NextPage\">Next</a> ";
			}

$PageLinksHTML = <<<PAGELINKSHTML
<table border=0 bgcolor='#FFCC00' width='100%' cellpadding=0 cellspacing=0>
	<tr><td width='100%'>
    <table border=0 bgcolor='#FFCC00' width='100%' cellpadding=0 cellspacing=1>
		<form><tr><td>                   
	    <table width=100% bgcolor=white border=0 cellspacing=0 cellpadding=3>
		  <tr bgcolor='#FFCC00'><td width='*'>
				<div align="right"><font face="Arial, Helvetica, sans-serif" size="2">Ads </font>
PAGELINKSHTML;

			$PageLinksHTML .= "\n<select size=1 name=\"NumAdsSelect\" class=\"smalltext\" onChange=\"reload(this.form, $CatID, $ViewAuctions, $page)\">";

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
		}

	if ( ($NumPages != 0) && ($StartPage != $EndPage) )
		print ( "$PageLinksHTML\n" );
	}
?> 

<?
	if ( $CatID != -1 )
	{
		print( "<hr noshade>\n" );
		
		print( "<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">\n" );
		print( "  <tr>\n" );
		print( "    <td width=\"*\" bgcolor=\"#FFCC00\"><font face=\"Arial, Helvetica, sans-serif\" size=\"2\"><b>Title</b></font></td>\n" );

		if ( $ViewAuctions )		
			print( "    <td align=\"center\" width=\"40\" bgcolor=\"#FFCC00\"><font face=\"Arial, Helvetica, sans-serif\" size=\"2\"><b>Bids</b></font></td>\n" );

		print( "    <td align=\"center\" width=\"80\" bgcolor=\"#FFCC00\"><font face=\"Arial, Helvetica, sans-serif\" size=\"2\"><b>Price</b></font></td>\n" );
		print( "    <td width=\"250\" bgcolor=\"#FFCC00\"><font face=\"Arial, Helvetica, sans-serif\" size=\"2\"><b>End Date</b></font></td>\n" );
		print( "  </tr>\n" );

		$TimeNow = time();

		if ( $ViewAuctions )
			$query = "SELECT AdID, Title, StartPrice, ReservePrice, EndDate FROM AuctionAds WHERE CatID=$CatID AND EndDate > $TimeNow ORDER BY EndDate LIMIT $RowStart, $NumAdsToDisplay";
		else
			$query = "SELECT AdID, Title, Price, EndDate FROM Ads WHERE CatID=$CatID AND EndDate > $TimeNow ORDER BY EndDate LIMIT $RowStart, $NumAdsToDisplay";

		$result = mysql_query( $query, $link );		
		
		$NumRows = 0;
		while ( $row = mysql_fetch_row( $result ) )
		{
			$NumRows++;

			if ( $ViewAuctions )
			{
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
			
				print( "  <td><a href=\"ViewAuctionAd.php?AdID=$AdID\">$Title</a></td>\n" );
				print( "  <td align=\"center\" width=\"40\">$NumBids</td>\n" );
				print( "  <td align=\"center\" width=\"80\">\$$Price</td>\n" );
				print( "  <td width=\"250\">$EndDate CST</td>\n" );
				print( "</tr>\n" );
			}
			else
			{
				$EndDate = date( "D, M j, Y  h:i A", $row[3] );			

				if ( ($NumRows % 2) != 0 )	
					print( "<tr>\n" );			
				else
					print( "<tr bgcolor=\"#FFFF99\">\n" );			
			
				print( "  <td><a href=\"ViewClassifiedAd.php?AdID=$row[0]\">$row[1]</a></td>\n" );
				print( "  <td align=\"center\" width=\"80\">\$$row[2]</td>\n" );
				print( "  <td width=\"250\">$EndDate CST</td>\n" );
				print( "</tr>\n" );
			}
		}

		print( "</table>\n" );

		if ( ($NumPages != 0) && ($StartPage != $EndPage) )
			print ( "$PageLinksHTML\n" );
	}
?>
<br><br>
<?
	include( "footer.inc" );
?>
</body>
</html>
