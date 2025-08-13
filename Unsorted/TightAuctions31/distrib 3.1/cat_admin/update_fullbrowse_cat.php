<?
    include( "../config.php" );
    include( "../dblink.inc" );

	$auctionFile = fopen( "../auction_fullbrowse_cat.inc", "w" );
        if (!$auctionFile)
	{
		print( "Error:  Category file could not be opened for writing.\n" );
		exit;
	}

	$classifiedFile = fopen( "../classified_fullbrowse_cat.inc", "w" );
        if (!$classifiedFile)
	{
		print( "Error:  Category file could not be opened for writing.\n" );
		exit;
	}
			
	$query = "SELECT CategoryID, Name FROM Categories WHERE Level=0 ORDER BY Name";
	$result = mysql_query( $query, $link );		
	
	// Count the number of rows of top level categories and subcategories
	
	$NumTopLevelCats = mysql_num_rows( $result );
	$NumSubLevelCats = 0;
	while ( $row = mysql_fetch_row( $result ) )
	{
		$query = "SELECT CategoryID FROM Categories WHERE Level=1 AND ParentCatID=$row[0] ORDER BY Name";
		$ChildrenResult = mysql_query( $query, $link );		
		$NumSubLevelCats += mysql_num_rows( $ChildrenResult );
	}
  
	$NumCols = 4;
	$NumRows = ($NumTopLevelCats * 2) + $NumSubLevelCats;		// NumTopLevelCats x 2 for the extra <br> at the end of each top level cat
	$CategoriesPerColumn = ceil($NumRows / $NumCols);	                

	$query = "SELECT CategoryID, Name FROM Categories WHERE Level=0 ORDER BY Name";
	$result = mysql_query( $query, $link );		

	PrintM( "<tr>\n" );
	PrintM( "<td valign=\"top\"><font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"2\">\n" );

	$NumWrittenCols = 0;
	$NumWrittenRows = 0;
	while ( $row = mysql_fetch_row( $result ) )
	{
		$query = "SELECT CategoryID, Name FROM Categories WHERE Level=1 AND ParentCatID=$row[0] ORDER BY Name";
		$ChildrenResult = mysql_query( $query, $link );

		$NumChildren = mysql_num_rows( $ChildrenResult );
		
		//
		// Will this category and subcategory be more than the max num categories per col?
		// If so, then just start on the next column.  If it's on the last column, then
		// don't start a new column.
		//
		
		if ( ((1 + $NumChildren + $NumWrittenRows) > $CategoriesPerColumn) &&
		     ($NumWrittenCols < ($NumCols-1)) )
		{
			PrintM( "</font></td><td valign=\"top\"><font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"2\">\n" );						
			$NumWrittenRows = 0;
			$NumWrittenCols++;
		}
		
		fputs( $auctionFile, "<a href=\"viewcat.php?CatID=$row[0]&ViewAuctions=true\"><b>$row[1]</b></a><br>\n" );
		fputs( $classifiedFile, "<a href=\"viewcat.php?CatID=$row[0]\"><b>$row[1]</b></a><br>\n" );
		$NumWrittenRows++;
		
		$ChildNum = 0;
		while ( $childrow = mysql_fetch_row( $ChildrenResult ) )
		{
			$ChildNum++;
			
			if ( $ChildNum == $NumChildren )
			{
				fputs( $auctionFile, "&nbsp; &nbsp;<a href=\"viewcat.php?CatID=$childrow[0]&ViewAuctions=true\">$childrow[1]</a><br>\n" );
				fputs( $classifiedFile, "&nbsp; &nbsp;<a href=\"viewcat.php?CatID=$childrow[0]\">$childrow[1]</a><br>\n" );
			}
			else
			{
				fputs( $auctionFile, "&nbsp; &nbsp;<a href=\"viewcat.php?CatID=$childrow[0]&ViewAuctions=true\">$childrow[1],</a><br>\n" );
				fputs( $classifiedFile, "&nbsp; &nbsp;<a href=\"viewcat.php?CatID=$childrow[0]\">$childrow[1],</a><br>\n" );
			}
						
			$NumWrittenRows++;
		}	
		
		PrintM( "<br>\n" );
	}
	
	PrintM( "</font></td>\n" );
	PrintM( "</tr>\n" );
      
	fclose( $auctionFile );
	fclose( $classifiedFile );
?>  


