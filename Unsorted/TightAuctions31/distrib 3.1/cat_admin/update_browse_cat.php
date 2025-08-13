<?
   include( "../config.php" );
   include( "../dblink.inc" );

	$auctionFile = fopen( "../auction_browse_cat.inc", "w" );
        if (!$auctionFile)
	{
		print( "Error:  Category file could not be opened for writing.\n" );
		exit;
	}

	$classifiedFile = fopen( "../classified_browse_cat.inc", "w" );
        if (!$classifiedFile)
	{
		print( "Error:  Category file could not be opened for writing.\n" );
		exit;
	}

	function PrintM( $str )
	{
		global $auctionFile, $classifiedFile;

		fputs( $auctionFile, $str );
		fputs( $classifiedFile, $str );
	}
	
	$query = "SELECT CategoryID, Name FROM Categories WHERE Level=0 ORDER BY Name";
	$result = mysql_query( $query, $link );		

	$NumTopLevelCats = mysql_num_rows( $result );

	$NumCols = 2;
	$CategoriesPerColumn = ceil($NumTopLevelCats / $NumCols);	                

        PrintM( "<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"2\">\n" );

	PrintM( "<tr>\n" );
	PrintM( "<td valign=\"top\"><font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"2\">\n" );

	$NumWrittenCols = 0;
	$NumWrittenRows = 0;
	while ( $row = mysql_fetch_row( $result ) )
	{
		if ( ($NumWrittenRows > $CategoriesPerColumn) &&
		     ($NumWrittenCols < ($NumCols-1)) )
		{
			PrintM( "</font></td><td valign=\"top\"><font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"2\">\n" );						
			$NumWrittenRows = 0;
			$NumWrittenCols++;
		}
		
		fputs( $auctionFile, "| <a href=\"viewcat.php?CatID=$row[0]&ViewAuctions=true\"><b>$row[1]</b></a> |<br>\n" );
		fputs( $classifiedFile, "| <a href=\"viewcat.php?CatID=$row[0]\"><b>$row[1]</b></a> |<br>\n" );
		$NumWrittenRows++;				
	}	

	PrintM( "</font></td>\n" );
	PrintM( "</tr>\n" );

/*
	fputs( $myFile, "<tr><td align=\"center\"><br>\n" );
	fputs( $myFile, "<font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"2\"><a href=\"viewcat.php?CatID=-1\"><b>View Category Map</b></a>\n" );
	fputs( $myFile, "</td></tr>\n" );
*/
	PrintM( "</table>\n" );

	PrintM( "<br><center><font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"2\"><a href=\"viewcat.php?CatID=-1\"><b>View Category Map</b></a></center>\n" );

/*
	while ( $row = mysql_fetch_row( $result ) )
	{
		fputs( $myFile, "<tr>\n" );
		fputs( $myFile, "  <td><font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"2\"><a href=\"viewcat.php?CatID=$row[0]\"><b>$row[1]</b></a>\n" );

		// Grab the first three children of this category

		$query = "SELECT CategoryID, Name FROM Categories WHERE Level=1 AND ParentCatID=$row[0] ORDER BY Name";
		$ChildrenResult = mysql_query( $query, $link );

		$NumChildren = 0;
		while ( ($NumChildren < 0) && ($childrow = mysql_fetch_row( $ChildrenResult )) )
		{
			if ( $NumChildren == 0 )
				fputs( $myFile, "  <br>\n" );

			fputs( $myFile, "  <a href=\"viewcat.php?CatID=$childrow[0]\">$childrow[1],</a> \n" );
			$NumChildren++;
		}

		if ( $NumChildren != 0 )
			fputs( $myFile, "...\n" );

      		fputs( $myFile, "  </font></td>\n" );
		fputs( $myFile, "</tr>\n" );
	}

	fputs( $myFile, "<tr>\n" );
	fputs( $myFile, "  <td>\n" );
	fputs( $myFile, "    <font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"2\"><a href=\"viewcat.php?CatID=-1\"><b>> More Categories <</b></a>\n" );
	fputs( $myFile, "  </td>\n" );
	fputs( $myFile, "</tr>\n" );
*/

	fclose( $auctionFile );
	fclose( $classifiedFile );
?>  


