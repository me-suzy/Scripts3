<!-- start of indexDynamicListings.php -->
<?php
require_once("util.php");
/* this module retreives all categories, counts the listings in each, and nicely formats them
   To test this page run indexDynamicListings.php with util.php available in /phpYellow
   1. optionally set $columnsWanted        on line 48
	2. optionally set $estimatedScreenWidth on line 49 
*/
$recordDisplay = !isset($recordDisplay)?"complete":"oneLine";
// pconnect, select and query
if ($link_identifier = mysql_pconnect(DBSERVERHOST, DBUSERNAME, DBPASSWORD)) {
	if ( mysql_select_db(DBNAME, $link_identifier)) {
		// run the query
		// get the total categories populated
		$query = "SELECT COUNT(category) FROM category GROUP BY category";
   	$queryResultHandle = mysql_query($query, $link_identifier) or die( mysql_error() );
		$totalCategories = mysql_num_rows($queryResultHandle);
		if ($totalCategories == 0 ) {
			echo "<p style=\"font-weight:bold;\">Total Populated Categories: None Found!</p>";
		}else{
			// get the total number of listings (there may be more than 1 listing per category) and display it as an integer on one line
			$query = "SELECT COUNT(ckey) AS 'TOTALLISTINGS' FROM " . DBTABLE2 . " WHERE status='approved' AND expires >'$todaysDate'";
		  	$queryResultHandle = mysql_query($query, $link_identifier) or die( mysql_error() );
			$rows = mysql_num_rows($queryResultHandle);
			if ( $rows == 1 ) {
				// retrieve the totallisting number
				$totalListings = mysql_result($queryResultHandle, 0 );
				echo "<h2>Choose from $totalListings Top Listings Or Use the Search Form</h2>\n";
			}else{
				echo "<p style=\"font-weight:bold;\">Total Listings: None Found!</p>\n";
			}	
			// query to find out how many listings in each category
			$query="SELECT category, COUNT(category) AS 'LISTINGCOUNT' FROM category WHERE status='approved' AND expires >'$todaysDate' GROUP BY category HAVING LISTINGCOUNT ORDER BY category ASC";
			$queryResultHandle = mysql_query($query, $link_identifier) or die( mysql_error() );
			$rows = mysql_num_rows($queryResultHandle); // to make sure we still have results
			if ( $rows > 0 ) {
				// advanced or simple search?
				if(defined("SETRANK")) {
					if (file_exists("premiumSearchForm.php")) {
						$goal = "Advanced Search";
						$searchType = "Advanced+Search";
					}
				}else{
					$goal = "Search";
					$searchType = "Search";
				}					
				// retreive the results
				// set by you - how many columns of results do you wish?
				$columnsWanted = 4; 
				$estimatedScreenWidth = 800; // pixels wide
				$totalRows = $totalCategories; // is determined above by the database query
				$avgColumnWidth = round($estimatedScreenWidth / $columnsWanted);
				$rowsPerColumn = round($totalRows / $columnsWanted ); // returns the nearest integer value
				$displayedCategories = 0;
				echo"\n\n\n<table><tr>\n";
				$columnCount = 0;
				while( $columnCount < $columnsWanted ) {
					echo"<td width=\"$avgColumnWidth\" align=\"left\" valign=\"top\">\n";
					for( $rowsThisCell=0; $rowsThisCell < $rowsPerColumn; $rowsThisCell++ ) {
						//echo $rowsThisCell . "<br>\n";
						if($grabARecord = mysql_fetch_array ($queryResultHandle)){
							++$displayedCategories;
							$category = stripslashes($grabARecord["category"]);
							$listingsInACategory = $grabARecord["LISTINGCOUNT"];
							// convert the possibly space filled text into a continuous string
							$encodedCategory = urlencode($category);
							
							// should results go on one line or with complete detail reporting?
							if(defined("RESULTS_ON_ONE_LINE")) { // show results one line per record?
								if(RESULTS_ON_ONE_LINE == "yes"){
									if(empty($recordDisplay)) {
										$recordDisplay="oneLine";
									}
								} // if the customer wants to use this feature
							} // // if a premium Edition 

							// generate the hyperlink and the correct search type
							echo "<a href=\"" . INSTALLPATH . "yellowresult.php?goal=$searchType&category=$encodedCategory&recordDisplay=$recordDisplay\">$category" . " (" . $listingsInACategory . ")</a><br>";
						}
					} // for
					// if there is an "odd" number category still not included in the loop then include it
					if($displayedCategories == ($totalRows - 1 ) ) {
						if($grabARecord = mysql_fetch_array ($queryResultHandle)) { // if we can get a last record
							$category = stripslashes($grabARecord["category"]);
							$listingsInACategory = $grabARecord["LISTINGCOUNT"];
							// convert the possibly space filled text into a continuous string
							$encodedCategory = urlencode($category);
							// should results go on one line or with complete detail reporting?
							if(defined("RESULTS_ON_ONE_LINE")) { // show results one line per record?
								if(RESULTS_ON_ONE_LINE == "yes"){
									$recordDisplay = "oneLine";
            				}else{
									$recordDisplay = "complete";
								} // if the customer wants to use this feature
							} // // if a premium Edition 
							// generate the hyperlink and the correct search type
							echo "<a href=\"" . INSTALLPATH . "yellowresult.php?goal=$searchType&category=$encodedCategory\">$category" . " (" . $listingsInACategory . ")</a><br>";
						}
					}
					echo"</td>\n";
					++$columnCount; // increment the column counter
				} // while
				echo "</tr></table>\n";
			}else{ //if ( $rows > 0
				echo "<p style=\"color:red;font-weight:bold;\">No data found. Click to add a listing.</p>";
			}
		} // if ($totalCategories == 0 ) {
	}else{ // select
		echo mysql_error();
	}
}else{ //pconnect
	echo mysql_error();
}?>
<!-- end of indexDynamicListings.php -->