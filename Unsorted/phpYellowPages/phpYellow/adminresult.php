<?php require_once("adminOnly.php");?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD>

	<script language="Javascript" src="yellow.js"></script>
	<script language="Javascript">loadCSSFile();</script>
   <script language="JavaScript">
<!--
/*
This function launches a temporary popup window which displays the administrative results. 
The window is a reporting tool. The messages it shows are temporary. Therefore, the popup 
has a timeout which you may reset in util.php to force the popup to close. No point in having 
all these windows open. Choose to close them all after POPUPTIMEOUT milliseconds in util.php
*/
function popup() {
	var popuptimeout = <?php echo POPUPTIMEOUT;?>;
    newWindow = window.open('','popup','width=420,height=360');
    setTimeout('closeWin(newWindow)', popuptimeout ); // delay POPUPTIMEOUT seconds before closing (set POPUPTIMEOUT in util.php)
}
function closeWin(newWindow) {
	newWindow.close(); // close popup
}	
//-->
</script>
	
    <?php echo "<TITLE>phpYellow - " . YOURPHPYELLOWNAME . " - Administration - " . COMPANY . "</TITLE>";?>

	<META NAME="keywords" CONTENT="phpYellow, php, Yellow, Search, for, add, change, or, delete, your, product, or, service, listing(s), on, our, Yellow, Pages, It's, fast, free.">
	<META NAME="description" CONTENT="Search for, add, change or delete your product or service listing(s) on our Yellow Pages. It's fast and free.">
	<META NAME="Author" CONTENT="Richard Creech, Web: http://www.dreamriver.com Email: richardc@dreamriver.com">
	<META NAME="GENERATOR" CONTENT="Blood, Sweat & Tears">
	<META HTTP-EQUIV="Expires" CONTENT="Mon, 24 April 2000 01:23:45 GMT">
	<meta name="robots" content="noindex,nofollow">
	</HEAD>
<?php 
// DATE FUNCTIONS FOR THE EXPIRY MODULE
// Get the current date 
$todaysDate = date("Y-m-d");  // eg .2001-06-01  year month day
// calculate 6 months from today for the 6 month listing purchase
$sixMonthExpiry = mktime (0,0,0,date("m")+6,date("d"),date("Y"));
$biannualExpiry =  date ("Y-m-d", $sixMonthExpiry);
// calculate 1 year from today for the 12 month listing purchase
$oneYearExpiry = mktime (0,0,0,date("m"),date("d"),date("Y")+1);
$annualExpiry =  date ("Y-m-d", $oneYearExpiry);
// calculate an expiry yesterday 
$oneDayAgoExpiry = mktime (0,0,0,date("m"),date("d") - 1 ,date("Y"));
$yesterdayExpiry =  date ("Y-m-d", $oneDayAgoExpiry);
?>

<BODY>

<?php
/* 
This page performs these operations:
Find Record Like . . ., Update, Delete, Set-Rank, Search, Oops!,
Preview Listings by Status, Update Status, Manage Listings, Set CKEY Parameters, Delete Single Elist

No user listings are inserted nor updated to the database on this page
To avoid duplication, inserts and updates are handled with the standard 
user functions See yellowresult.php for insert and update functions.
*/




# process the goal and display the result
echo"<h1>Admin Result<br>$goal</h1>";


# attain the goal
 switch ($goal) {
	case "Confirm Delete":
		if($recordsToDelete == "One" ) {
			$tableName = DBTABLE2;
			$primaryKey  = "ckey";
			$recordToDestroy = $ckey;
		}else{
			$tableName = DBTABLE;
			$primaryKey  = "yps";
			$recordToDestroy = $yps;					
		}
		$query = "DELETE FROM $tableName WHERE $primaryKey='$recordToDestroy'";
		// pconnect, select and query
		if ($link_identifier = mysql_pconnect(DBSERVERHOST, DBUSERNAME, DBPASSWORD)) {
			if ( mysql_select_db(DBNAME, $link_identifier)) {
				// run the query
		   		$queryResultHandle = mysql_query($query, $link_identifier) or die( mysql_error() );
				$rows = mysql_affected_rows($link_identifier); // get # of rows
				if ( $rows == 1 ) {  
		    		echo "<br>1 record $primaryKey#$recordToDestroy deleted from the $tableName table.";
				}elseif($rows == 0 ) {
					echo"<br>No data deleted.";
				}elseif( $rows > 1 ) {
					echo"<br>$rows rows deleted from the $tableName table.";
				}
				if( $primaryKey == "yps" ) {
					// destroy every related category record too
					$query = "DELETE FROM category WHERE ypsid='$yps'";
			   		$queryResultHandle = mysql_query($query, $link_identifier) or die( mysql_error() );
					$rows = mysql_affected_rows($link_identifier); // get # of rows
					if ( $rows == 1 ) {  
		    			echo "<br>1 record deleted from the category table.";
					}elseif($rows == 0 ) {
						echo"<br>No data deleted from the category table.";
					}elseif( $rows > 1 ) {
						echo"<br>$rows rows deleted from the category table.";
					}					
				}
			}else{ // select
				echo mysql_error();
			}
		}else{ //pconnect
			echo mysql_error();
		}
		break;
	
	
	
	
     case "Delete": // finds dynamically chosen data 
		$goal = "Confirm Delete";
 		include("adminConfirmDeleteForm.php");
 		break;



 
     case "Manage Listings": // finds dynamically chosen data
			$totalmatches = !isset($totalmatches)?NULL:$HTTP_POST_VARS['totalmatches'];
			$offset = !isset($offset)?NULL:$HTTP_POST_VARS['offset'];
			$primaryField = !isset($primaryField)?NULL:$HTTP_POST_VARS['primaryField'];
		/* this is identical to the free search except:
			1. administration controls are added
			2. including manageForm.php replaces searchForm.php
			3. output is displayed using oneAdminRecord.php
			4. table width is increased
			5. the order by clause is changed to ckey, not rank
		*/
		// pconnect, select and query
		if ($link_identifier = mysql_pconnect(DBSERVERHOST, DBUSERNAME, DBPASSWORD)) {
			if ($select = mysql_select_db(DBNAME, $link_identifier)) {
		        if ($totalmatches < 1 ) { // do not reset offset unless a new search    
		            if ($offset == 0 ) {  
		        	    $offset = '0'; // The default offset is zero
		            }
		        }

		        // ASSEMBLE THE SQL QUERY
		        //make the select
		        $select = "SELECT *";

		       // add the tablenames to the query
		       $fromtable = " FROM " . DBTABLE . "," . DBTABLE2;

		       // BUILD THE WHERE CLAUSE
		       $where = " WHERE yps=ypsid AND status='approved' AND expires >'$todaysDate'";
     
 			    // determine the category
	            // if the user has chosen a category include it
				if (($category != '') && ($category != '*')): 
			    	$primaryFieldSQL = " AND category = '$category' ";
			    else:
			    	$primaryFieldSQL = "";
			    	$pageheading = "All Categories "; // show heading on page
			    endif;		

				// show dynamic table heading
				switch($primaryField) {
					case "category":
						if ($category == "*") {
							$pageheading = "by Category: All";					
						}else{
							$pageheading = "by Category: $category";
						}
						break;
				}	

		
			    // DETERMINE what "Narrow results with" field to use
				// only one of these will be used
				// citytofind then ystateprov then ycountry else none
				if ($citytofind){
					$narrowResultsWith = " AND ycity='$citytofind'";
					$ycountry="";
					$ystateprov="";
				}elseif ($ystateprov) {
					$narrowResultsWith = " AND ystateprov='$ystateprov'";
					$ycountry="";
				}elseif ($ycountry) {
					$narrowResultsWith = " AND ycountry='$ycountry'";		
				}else{
					$narrowResultsWith = "";			
				}

				// set the groupby
				$groupby = " GROUP BY ckey";

				// set the search results order
			    $orderby = " ORDER BY ckey DESC, yps DESC";

			    // concatenate the limit for the mySQL select
			    $comma = ",";
			    $limitq = " LIMIT " . $offset . $comma . RECORDSPERPAGE;
			    // this is the final concatenation of the user query
			    $concatquery = $select . $fromtable . $where . $primaryFieldSQL . $narrowResultsWith . $groupby . $orderby . $limitq; // concatenate the user sql query


			    // show the pageheading in the table header
echo "<table class='yellow' width=640><tr><th>$goal $pageheading</th></tr><tr><td>\n";

			    // if the offset is 0 then find out how many records will be returned 
			    if ( $offset == '0' ) {
					$querymatches = "select count(ckey) " . $fromtable . $where . $primaryFieldSQL . $narrowResultsWith;

					if ( SHOWSQL != "no" ) {
						echo"<p style=\"font-size:x-small;color:red;font-weight:bold;\">Query: $querymatches</p>";
						echo mysql_error();
					}
					// get the count of found records
					$queryResultHandle = mysql_query($querymatches, $link_identifier) or die (mysql_error());
					$totalmatches = mysql_result($queryResultHandle,0); // how many rows?
            		if ( $totalmatches == 0 ) {      
    	            	echo "No Matches. Click here to <a href='login.php'>add your business listing free of charge.</a>";
   	    	        	include("manageForm.php");
       	    	    	break;
					}
				} // if ( $offset == '0' ) {


			    // this is where the showing x results of x total found is built
			    $resultmax = $offset + RECORDSPERPAGE; // assign the outer result range number
			    if ( $resultmax > $totalmatches ) {
		          	$resultmax = $totalmatches;
				}	 
			    $firstrowonpage = $offset + 1;
        		print("Total Matches: $totalmatches &nbsp;&nbsp;&nbsp;Showing Results $firstrowonpage to $resultmax<br>");

				if ( SHOWSQL != "no" ) { // for debugging - shows the query
					echo"<br><hr><p style=\"font-size:x-small;color:red;font-weight:bold;\">Query: $concatquery</p><hr><br>";
					echo mysql_error();
				}
					
				// RUN THE QUERY TO RETREIVE EACH FOUND RECORD
				$queryResultHandle = mysql_query($concatquery, $link_identifier) or die ( mysql_error()); 
				// make sure that we recieved some data from our query 
				$rows = mysql_num_rows ($queryResultHandle);
				if ( $rows > 0 ) {
					while ($data = mysql_fetch_array ($queryResultHandle)) {
						// retreive the results
			         $yfps = stripslashes($data["yps"]);  // populate the variables with the array element values
		           	$yfpassword = stripslashes($data["ypassword"]); // stripslashes reverses addslashes
	    	       	$yfemail = stripslashes($data["yemail"]);
	    		      $yfcompany = stripslashes($data["ycompany"]);
			         $yffirstname = stripslashes($data["yfirstname"]);
						$yflastname = stripslashes($data["ylastname"]);
	    	  		   $yfaddress = stripslashes($data["yaddress"]);
			       	$yfcity = stripslashes($data["ycity"]);
	    		      $yfstateprov = stripslashes($data["ystateprov"]);
	        	   	$yfcountry = stripslashes($data["ycountry"]); 
	            	$yfpostalcode = stripslashes($data["ypostalcode"]);
						$yfareacode = stripslashes($data["yareacode"]);
		           	$yfphone = stripslashes($data["yphone"]);
    		       	$yffax = stripslashes($data["yfax"]);
	    	       	$yfcell = stripslashes($data["ycell"]);
	        	   	$yfurl = stripslashes($data["yurl"]);
						$yflogo = stripslashes($data["ylogo"]);
						$yflastupdate = $data["lastupdate"];
						// and also get the category fields as a result of the join
						$ckey = stripslashes($data["ckey"]);
                  // category wasn't working in the manage multipage so we rename it
						$yfcategory = stripslashes($data["category"]);
						$description = stripslashes($data["description"]);
						$rank = stripslashes($data["rank"]);
						$paymentrequired = stripslashes($data["paymentrequired"]);
						$status = stripslashes($data["status"]);
						$expires = stripslashes($data["expires"]);
						$lastupdate = stripslashes($data["lastupdate"]);
                  // convert some variables for use in checkout.php
                  $x_first_name=$yffirstname;
                  $x_last_name=$yflastname;
                  include("adminOneRecord.php");  // display the row values in a template
					}
					mysql_free_result ($queryResultHandle);
				}else{
					echo"<br>Process anomaly. Please try again.<br>";
					echo $query;
					exit;
				}			

		        // do we need to present more results for the user query on another page?
		        $pagestoshow = $totalmatches - RECORDSPERPAGE;
		        if ( $offset < $pagestoshow ) { // MULTIPAGE - if present offset is less than the number of the returned rows then do multipage
					$displayNextButton = "true";
        			$offset = ($offset + RECORDSPERPAGE);  // here the offset is incremented to point to the next set of results desired
					include("backNextControlsForm.php");
       			}else{
           			echo "<h3>End of Results</h3>";
				}
   	   			include("manageForm.php"); // offer another search form
				echo"</td></tr></table>";
			}else{ // select
	   			echo mysql_error();
			}
		}else{ //pconnect
			echo mysql_error();
		} 	
        break;
		
		
		
		
		 
 	case "Update Record Status": // commit administration status decisions
		// only status is affected here - nothing else
		// any status except for 'approved' will NOT show in search results
		$status = addslashes($status);
		$query = "UPDATE " . DBTABLE2 . " SET status='$status' WHERE ckey='$ckey'";
		// pconnect, select and query
		if ($link_identifier = mysql_pconnect(DBSERVERHOST, DBUSERNAME, DBPASSWORD)) {
			if ( mysql_select_db(DBNAME, $link_identifier)) {
				// run the query
		   		$queryResultHandle = mysql_query($query, $link_identifier) or die( mysql_error() );
				$rows = mysql_affected_rows($link_identifier);
				switch($rows) {
					case 1:
						echo"One record status field updated. ckey#$ckey modified to &quot;$status&quot;<br>";
						break;
					case 0:
						echo"No status change or record already updated.";
						break;
					default:
						echo"Too many rows updated, or No status set for Update Record Status in adminresult.php";
				}				
			}else{ // select
				echo mysql_error();
			}
		}else{ //pconnect
			echo mysql_error();
		}        
	    break;
 
 
 
     case "Preview Listings by Status":
	 	echo"<h2>Status: $status</h2>";
		$query = "SELECT * FROM " . DBTABLE . "," . DBTABLE2 . " WHERE yps=ypsid AND status='$status' ORDER BY ckey DESC LIMIT 100";
		// pconnect, select and query
		if ($link_identifier = mysql_pconnect(DBSERVERHOST, DBUSERNAME, DBPASSWORD)) {
			if ( mysql_select_db(DBNAME, $link_identifier)) {
				// run the query
		   		$queryResultHandle = mysql_query($query, $link_identifier) or die( mysql_error() );
				// make sure that we recieved some data from our query 
				$rows = mysql_num_rows ($queryResultHandle);
				if ( $rows > 0 ) {
					echo"<table class=\"yellow\" width=650><tr><td>";
					while ($data = mysql_fetch_array ($queryResultHandle)) {
						// retreive the results
		                $yfps = stripslashes($data["yps"]);  // populate the variables with the array element values
	            		$yfpassword = stripslashes($data["ypassword"]); // stripslashes reverses addslashes
    	        		$yfemail = stripslashes($data["yemail"]);
    			        $yfcompany = stripslashes($data["ycompany"]);
		    	        $yffirstname = stripslashes($data["yfirstname"]);
						$yflastname = stripslashes($data["ylastname"]);
    	  			    $yfaddress = stripslashes($data["yaddress"]);
		        		$yfcity = stripslashes($data["ycity"]);
    			        $yfstateprov = stripslashes($data["ystateprov"]);
        	    		$yfcountry = stripslashes($data["ycountry"]); 
            			$yfpostalcode = stripslashes($data["ypostalcode"]);
						$yfareacode = stripslashes($data["yareacode"]);
	            		$yfphone = stripslashes($data["yphone"]);
   		        		$yffax = stripslashes($data["yfax"]);
    	        		$yfcell = stripslashes($data["ycell"]);
        	    		$yfurl = stripslashes($data["yurl"]);
						$yflogo = stripslashes($data["ylogo"]);
						$yflastupdate = $data["lastupdate"];
						// and also get the category fields as a result of the join
// ckey ypsid category description rank paymentrequired status expires lastupdate
						$ckey = stripslashes($data["ckey"]);
						$yfypsid = stripslashes($data["ypsid"]);
						$yfcategory = stripslashes($data["category"]);
						$yfdescription = stripslashes($data["description"]);
						$yfrank = stripslashes($data["rank"]);
						$yfpaymentrequired = stripslashes($data["paymentrequired"]);
						$yfstatus = stripslashes($data["status"]);
						$yfexpires = stripslashes($data["expires"]);
						$yflastupdate = stripslashes($data["lastupdate"]);
						
						// format the results
						include("adminStatusRecord.php");
 					} // while ($data = mysql_fetch_array
					echo"</td></tr></table>";
					mysql_free_result ($queryResultHandle);
				}else{ // if ( $rows > 0
					echo"<span style=\"color:Coral;font-weight:bold;font-size:18px;\">No matches found.</span>";
				}
			}else{ // select
				echo mysql_error();
			}
		}else{ //pconnect
			echo mysql_error();
		}						
		break;		




    case "Find Record Like . . .": // find records like searchstring, admin only
	   $dbtableToUse = DBTABLE . "," . DBTABLE2;	
		$primarykey = "yps"; // for order by in sql clause
		$query = "SELECT * FROM $dbtableToUse WHERE yps=ypsid AND $searchfield LIKE '%$stringtofind%' ORDER BY $primarykey DESC LIMIT 100";
		// pconnect, select and query
		if ($link_identifier = mysql_pconnect(DBSERVERHOST, DBUSERNAME, DBPASSWORD)) {
			if ( mysql_select_db(DBNAME, $link_identifier)) {
				// run the query
		   	$queryResultHandle = mysql_query($query, $link_identifier) or die( mysql_error() );
				// make sure that we recieved some data from our query 
				$rows = mysql_num_rows ($queryResultHandle);
				if ( $rows > 0 ) {
					echo"<table class=\"yellow\"><tr><th>$query</th></tr>\n";
					while ($data = mysql_fetch_array ($queryResultHandle)) {
						// retreive the results
						echo"<tr><td>\n";
			            $yfps = stripslashes($data["yps"]);  // populate the variables with the array element values
		            	$yfpassword = stripslashes($data["ypassword"]); // stripslashes reverses addslashes
    		        		$yfemail = stripslashes($data["yemail"]);
    				      $yfcompany = stripslashes($data["ycompany"]);
		    		      $yffirstname = stripslashes($data["yfirstname"]);
							$yflastname = stripslashes($data["ylastname"]);
    	  			    	$yfaddress = stripslashes($data["yaddress"]);
			        		$yfcity = stripslashes($data["ycity"]);
    				      $yfstateprov = stripslashes($data["ystateprov"]);
        		    		$yfcountry = stripslashes($data["ycountry"]); 
            			$yfpostalcode = stripslashes($data["ypostalcode"]);
							$yfareacode = stripslashes($data["yareacode"]);
	            		$yfphone = stripslashes($data["yphone"]);
	   		        	$yffax = stripslashes($data["yfax"]);
    		        		$yfcell = stripslashes($data["ycell"]);
        		    		$yfurl = stripslashes($data["yurl"]);
							$yflogo = stripslashes($data["ylogo"]);
							$yflastupdate = $data["lastupdate"];
							$ckey = stripslashes($data["ckey"]);
							$ypsid = stripslashes($data["ypsid"]);
							$category = stripslashes($data["category"]);
							$description = stripslashes($data["description"]);
							$rank = stripslashes($data["rank"]);
							$paymentrequired = stripslashes($data["paymentrequired"]);
							$status = stripslashes($data["status"]);
							$expires = stripslashes($data["expires"]);
							$lastupdate = stripslashes($data["lastupdate"]);
                     // convert some variables for use in checkout.php
                     $x_first_name=$yffirstname;
                     $x_last_name=$yflastname;
//$yfcategory = $category;
                     include("adminOneRecord.php");  // display the row values in a template
						echo"</td></tr>";
 					} // while ($data = mysql_fetch_array
					echo"</table>";
					include("findRecordLikeForm.php");
					mysql_free_result ($queryResultHandle);
				}else{ // if ( $rows > 0
					echo"<span style=\"color:Coral;font-weight:bold;font-size:18px;\">No matches found.</span>";
				}
			}else{ // select
				echo mysql_error();
			}
		}else{ //pconnect
			echo mysql_error();
		}						
		break;     



	case "Instant Destroy":
		$query = "SELECT ypsid FROM category WHERE ckey = $ckey LIMIT 1"; // get the yps
		// pconnect, select and query
		if ($link_identifier = mysql_pconnect(DBSERVERHOST, DBUSERNAME, DBPASSWORD)) {
			if ( mysql_select_db(DBNAME, $link_identifier)) {
				// run the query
		   	$queryResultHandle = mysql_query($query, $link_identifier) or die( mysql_error() );
				// make sure that we recieved some data from our query 
				$rows = mysql_num_rows ($queryResultHandle);
				if ( $rows > 0 ) {
					$ypsid = mysql_result($queryResultHandle,0);
					mysql_free_result ($queryResultHandle);
					$query = "DELETE FROM " . DBTABLE2 . " WHERE ypsid = $ypsid";
					// run the query
		   		$queryResultHandle = mysql_query($query, $link_identifier) or die( mysql_error() );
					$rows = 0;
					$rows = mysql_affected_rows($link_identifier); // get # of rows
					switch($rows) {
						case 0:
							echo"No data affected in request to delete " . DBTABLE2 . " listings.";
							break;
						case 1:
							echo"<h4>One (1) " . DBTABLE2 . " listing ypsid # $ypsid destroyed</h4>";
							break;
						default:
							echo"<h4>Total of $rows " . DBTABLE2 . " listings with ypsid # $ypsid destroyed</h4>";
					} // switch rows
					$query = "DELETE FROM " . DBTABLE . " WHERE yps = $ypsid";
					// run the query
		   		$queryResultHandle = mysql_query($query, $link_identifier) or die( mysql_error() );
					$rows = 0;
					$rows = mysql_affected_rows($link_identifier); // get # of rows
					switch($rows) {
						case 0:
							echo"No data affected in request to delete " . DBTABLE . " listing.";
							break;
						case 1:
							echo"<h4>" . DBTABLE . " listing yps # $ypsid Destroyed</h4>";
							break;
						default:
							echo"Process anomaly in adminresult.php Instant Destroy";
					} // switch rows
				}else{ // if ( $rows > 0
					echo"<h3>No matches found</h3>";
				}
			}else{ // select
				echo mysql_error();
			}
		}else{ //pconnect
			echo mysql_error();
		}						
		break;




     default:
         echo "<h1>Oops!</h1>";
         print("Please contact " . WEBMASTER); 
         break;       
  }

echo "<h3>End of Admin Result</h3>";
?>

</body>
</html>
