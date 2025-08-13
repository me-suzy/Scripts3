<?php require_once("util.php");
function documentStart($goal){
	include("documentStart.php");
	return $goal;
}?>
<?php 
// DATE FUNCTIONS FOR THE EXPIRY MODULE
// the current date is retrieved in util.php like this: $todaysDate = date("Y-m-d");  // eg .2001-06-01  year month day
// calculate 6 months from today for the 6 month listing purchase
$sixMonthExpiry = mktime (0,0,0,date("m")+6,date("d"),date("Y"));
$biannualExpiry =  date ("Y-m-d", $sixMonthExpiry);
// calculate 1 year from today for the 12 month listing purchase
$oneYearExpiry = mktime (0,0,0,date("m"),date("d"),date("Y")+1);
$annualExpiry =  date ("Y-m-d", $oneYearExpiry);
// calculate an expiry yesterday 
$oneDayAgoExpiry = mktime (0,0,0,date("m"),date("d") - 1 ,date("Y"));
$yesterdayExpiry =  date ("Y-m-d", $oneDayAgoExpiry);
// basic listing expiry
$basicExpirationFormula = mktime (0,0,0,date("m"),date("d")+ DAYSTOEXPIRY,date("Y")); // calculate DAYSTOEXPIRY days
$basicListingExpiry = date ("Y-m-d", $basicExpirationFormula);
?>
<?php // initialize or capture variables if phpversion 4.1.0 or higher is present
$phpVersion = phpversion();
if ($phpVersion >= "4.1.2") { // real mccoy
$totalmatches = !isset($_REQUEST['totalmatches'])?NULL:$_REQUEST['totalmatches'];
$offset = !isset($_REQUEST['offset'])?0:$_REQUEST['offset'];
$citytofind = !isset($citytofind)?NULL:$_REQUEST['citytofind'];
$yareacode = !isset($yareacode)?NULL:$_REQUEST['yareacode'];
$ystateprov = !isset($ystateprov)?NULL:$_REQUEST['ystateprov'];
$ycountry = !isset($ycountry)?NULL:$_REQUEST['ycountry'];
$goal = !isset($goal)?NULL:$_REQUEST['goal']; // capture or initialize the goal variable line 31
}?>
<?php
/* This page contains the critical insert, update, delete, 
authorize and search functions */
switch ($goal) { // attain the goal

	case "Add": // this inserts the contact data record
		documentStart($goal); // applies header formatting
		$query = "INSERT INTO " . DBTABLE . " ( ypassword, yemail, ycompany, yfirstname, ylastname, yaddress, ycity, ystateprov, ycountry, ypostalcode, yareacode, yphone, ycell, yfax, yurl ) VALUES('$ypassword', '$yemail', '$ycompany', '$yfirstname', '$ylastname', '$yaddress', '$ycity', '$ystateprov', '$ycountry', '$ypostalcode', '$yareacode', '$yphone', '$ycell', '$yfax', '$yurl')";
      // set some variables used in email confirmation
    	$webmaster = WEBMASTER;
    	$yourphpyellowname = YOURPHPYELLOWNAME;
    	$installpath = INSTALLPATH;
      $yourphpyellowname = YOURPHPYELLOWNAME; // end of CONSTANT to variable declarations
		/* start of ADDSLASHES BLOCK  - escape quote characters and backslashes */
   	$ypassword = addslashes($ypassword);
   	$yemail = addslashes($yemail);
      $ycompany = addslashes($ycompany);
      $yfirstname = addslashes($yfirstname);
		$ylastname = addslashes($ylastname);
	   $yaddress = addslashes($yaddress);
   	$ycity = addslashes($ycity);
      $ystateprov = addslashes($ystateprov);
   	$ycountry = addslashes($ycountry); 
   	$ypostalcode = addslashes($ypostalcode);
		$yareacode = addslashes($yareacode);
   	$yphone = addslashes($yphone);
   	$yfax = addslashes($yfax);
   	$ycell = addslashes($ycell);
   	$yurl = addslashes($yurl);
		// $ylogo = addslashes($ylogo); // handled separately below
		/* end of ADDSLASHES BLOCK */
		// pconnect, select and query
		if ($link_identifier = mysql_pconnect(DBSERVERHOST, DBUSERNAME, DBPASSWORD)) {
			if ( mysql_select_db(DBNAME, $link_identifier)) {
				// run the query
		   	$queryResultHandle = mysql_query($query, $link_identifier) or die( mysql_error() );
				$rows = mysql_affected_rows($link_identifier);
				// decide what to do with the results
				switch( $rows ) {
					case 0:
						echo"Count for rows inserted is zero. No data was changed.<br>\n";
						break;
					case 1:
						echo "First Step Completed - Contact details ADDED.\n";
						$andPosition = PAIDLISTINGS != "no"?" and position ": NULL; 
						echo "<br>You are NOT finished yet! Select a category $andPosition below.\n";
						$userLoginPage = INSTALLPATH . "login.php";
						$goPremiumUrl = INSTALLPATH . "goPremium.php";
				      if ( EMAILSURFERONADD == "yes" ) { /* send a listing copy to the surfer */
							$recipient = $yemail;
							$from = "From: " . WEBMASTER . "\n"; /* used as the 4th mail() argument */
							$replyTo = "Reply-To: " . WEBMASTER . "\n";
							$xMailer = "X-Mailer: PHP/" . phpversion();
							$optionalHeaders = $from . $replyTo . $xMailer;
							$subject = "Save for future reference: Welcome to $yourphpyellowname";
							$messagebody = "Welcome to $yourphpyellowname,
Thank you for adding your listing! This is the data you submitted:

password: $ypassword
email: $yemail
firm: $ycompany
contact: $yfirstname $ylastname
address: $yaddress
city: $ycity
stateprov: $ystateprov
country: $ycountry
postalcode: $ypostalcode
phone area code: $yareacode
phone: $yphone
fax: $yfax
cell: $ycell
url: $yurl

If you need to update or delete this listing you may do so by logging in here:

$userLoginPage

We also have an automated password lookup on our website which you may use if you forget your login data. Your basic listing expires on $basicListingExpiry. 

Click here $goPremiumUrl to see options for upgrading your listing. 

Please save this note for future reference, and THANK YOU for choosing $yourphpyellowname.

Yours Sincerely,
$webmaster";
							if (@mail( $recipient, $subject, $messagebody, $optionalHeaders )) {
								echo"<br>Confirmation sent to $recipient.";
							}
    					} //if ( EMAILSURFERONADD == "yes" ) 

					// send a copy to the webmaster?
			    	if ( NOTIFYONCHANGE == "yes" ) { // send a copy to webmaster
						$yourphpyellowname = YOURPHPYELLOWNAME;
						$adminHome = ADMINHOME;
						$installpath = INSTALLPATH;
						$adminLink = $installpath . $adminHome;					
						$recipient = WEBMASTER;
						$from = "From: " . WEBMASTER . "\n"; // used as the 4th mail() argument
						$replyTo = "Reply-To: " . WEBMASTER . "\n";
						$xMailer = "X-Mailer: PHP/" . phpversion();
						$optionalHeaders = $from . $replyTo . $xMailer;
						$subject = "New or Changed Listing for $yourphpyellowname";
						$messagebody = "Hello $recipient,
A record has been submitted to $yourphpyellowname as follows:

password: $ypassword
email: $yemail
firm: $ycompany
contact: $yfirstname $ylastname
address: $yaddress
city: $ycity
stateprov: $ystateprov
country: $ycountry
postalcode: $ypostalcode
phone area code: $yareacode
phone: $yphone
fax: $yfax
cell: $ycell
url: $yurl


Edit this Record? $adminLink

You are receiving this note because your current setting is to receive 
notification of new or changed listings. This optional feature may be 
changed in the file util.php, by toggling the constant NOTIFYONCHANGE to 
a value of 'no' from a value of 'yes'.

$recipient";
						@mail( $recipient, $subject, $messagebody, $optionalHeaders );
	    			}
					// END of conditionally notify the webmaster
					
						$query = "SELECT LAST_INSERT_ID()";
						$queryResultHandle = mysql_query($query, $link_identifier) or die (mysql_error());
						// fetch the single row data
						$yps = mysql_result($queryResultHandle, 0 );



// START - THIS CODE INSERTS OR UPDATES THE CUSTOMER IMAGE
					   if( USELOGOS == "yes" ) { 
							/* compare the PHP server version with the phpYellow Pages required version */
							$currentPHPversion = phpversion();
							$currentPHPversion = str_replace  ( ".", "", $currentPHPversion); // remove period characters example 4.1.2
							$currentPHPversion = substr($currentPHPversion, 0, 2); // get the first 2 characters
							$yardstick = 41; // actually 4.1.2 but we chop the 2 anyway 
							if($currentPHPversion >= $yardstick) { // is current php version greater or less than the minimum needed 4.1.2?
								// then use 4.1.2 compliant image file upload code
								include("premiumImageUpload412.php");
							}else{ // use the old image upload code
								include("premiumImageUpload404.php");
							}
						}
// END - THIS CODE INSERTS OR UPDATES THE CUSTOMER IMAGE



                  echo "<h2>Next Step - Choose Category $andPosition</h2>";
  						$goal = "Add Category";
   					include("categoryForm.php");
	   				break;

					default:
						echo"Process anomaly in $goal switch(rows) case authorize. Notify the webmaster.<br>\n";

				} // switch( $rows ) {
			}else{ // select
				echo mysql_error();
			}
		}else{ //pconnect
			echo mysql_error();
		}						
		break;




	case "Update Category": // this commits changes to the category record
		documentStart($goal); // applies header formatting
		/* start of ADDSLASHES BLOCK  - escape quote characters and backslashes */
		// ypsid,category,description,rank,paymentrequired,status,expires
   	// $ypsid = addslashes($ypsid); // not needed - does not change
   	$category = addslashes($category);
   	$description = addslashes($description);
		$rank = addslashes($rank);
		$paymentrequired = addslashes($paymentrequired);
		$status = addslashes($status);
		$expires = addslashes($expires);
		/* end of ADDSLASHES BLOCK */

		// optionally queue all category listings
		if(QUEUELISTINGS == "yes") {
			$status = "pending";
		}		
	
		// this only updates the category record for the fields: category and description		
		$query = "UPDATE " . DBTABLE2 . " SET category='$category',description='$description' WHERE ckey='$ckey' ";
		if (SHOWSQL != "no" ) {
			echo"<p>Query: $query</p>";
		}
		// pconnect, select and query
		if ($link_identifier = mysql_pconnect(DBSERVERHOST, DBUSERNAME, DBPASSWORD)) {
			if ( mysql_select_db(DBNAME, $link_identifier)) {
				// run the query
	   		$queryResultHandle = mysql_query($query, $link_identifier) or die( mysql_error() );
				$rows = mysql_affected_rows($link_identifier);
				if ( $rows == 1 ) {
		        echo "<p>One record updated. Thank You for keeping your information current.</p>";
					if (MULTICATEGORY == "yes") {
                  echo"<h2>Update a Category?</h2>";
						// now get the categories for update
						$goal = "Update Category";
						$query = "SELECT * FROM category WHERE ypsid=$yps ORDER BY lastupdate DESC,ckey DESC";
						// go get the category records
			   			$queryResultHandle = mysql_query($query, $link_identifier) or die( mysql_error() );
						$rows = mysql_num_rows($queryResultHandle);
						if ( $rows > 0 ) { // if there are category results ...					
							$rows = mysql_num_rows($queryResultHandle); // run the count select to get the number of rows, without the limit
					        for ($i = 0; $i < $rows; $i++) {  // loop through all the rows
								$data = mysql_fetch_array($queryResultHandle); // fetch the row data
								// ckey ypsid category description rank paymentrequired status expires lastupdate
								// START of stripslashes block
								$ckey = stripslashes($data["ckey"]);
								$ypsid = stripslashes($data["ypsid"]);
								$category = stripslashes($data["category"]);
								$description = stripslashes($data["description"]);
								$rank = stripslashes($data["rank"]);
								$paymentrequired = stripslashes($data["paymentrequired"]);
								$status = stripslashes($data["status"]);
								$expires = stripslashes($data["expires"]);
								$lastupdate = stripslashes($data["lastupdate"]);
								// end of stripslashes block
								$yps = $ypsid;
								$receiptEmail = $yemail; // convert for possible buy scenario
								include("categoryForm.php");
							}					
						}	
					}else{ // if (MULTICATEGORY == "yes")		
if(defined("SETRANK")) {
	if (file_exists("premiumSearchForm.php")) {
		include("premiumSearchForm.php");
	}
}else{
	include("searchForm.php");
}					
					} // if (MULTICATEGORY == "yes")
				}
			}else{ // select
				echo mysql_error();
			}
		}else{ //pconnect
			echo mysql_error();
		}        
	    break;
		
		



	case "Add Category": // this inserts the category record
		documentStart($goal); // applies header formatting
		/* start of ADDSLASHES BLOCK  - escape quote characters and backslashes */
		// ypsid,category,description,rank,paymentrequired,status,expires
   	$ypsid = addslashes($ypsid);
   	$category = addslashes($category);
   	$description = addslashes($description);
		$status = addslashes($status);
		$expires = addslashes($expires);
		/* end of ADDSLASHES BLOCK */
		// set the status and paymentrequired of the newly added category listing

		
//////////////////////////// Start of PAIDLISTING handling FOR "Add Category" and "Update Category"
		if(!isset($rank)) { $rank = 0; } // set a default rank

		switch(PAIDLISTINGS) {
			case "all":
				$status = "pending";
				$paymentrequired = "yes";				
				break;
			case "yes":
				/* here the status depends on what rank the user chooses, so we examine it */
				switch($rank) {
					case 0:
						$status = "approved"; // it is a free listing
						$paymentrequired = "no"; // it is a free listing
						break;
					case 1:
					case 2:
						$status = "pending"; // it is a paid listing
						$paymentrequired = "yes"; // it is a paid listing
						break;
					default:
						echo"Process anomaly in set rank switch for yellowresult.php add category";
				}
				break;
			case "no":
				$status = "approved";
				$paymentrequired = "no";	
				$rank = "0"; // sets rank to basic for all listings if PAIDLISTINGS = "no" 
				break;
			default:
				// for the Standard Edition, free
				$status = "approved";
				$paymentrequired = "no";
		}
     	// set the listing expiration date	
		$expires = $basicListingExpiry;
		// optionally queue all category listings
		if(QUEUELISTINGS == "yes") {
			$status = "pending";
		}
//////////////////////////// Start of PAIDLISTING handling FOR "Add Category" and "Update Category"



		$query = "INSERT INTO " . DBTABLE2 . " ( ypsid,category,description,rank,paymentrequired,status,expires ) VALUES('$ypsid','$category','$description','$rank','$paymentrequired','$status','$expires')";
		// pconnect, select and query
		if ($link_identifier = mysql_pconnect(DBSERVERHOST, DBUSERNAME, DBPASSWORD)) {
			if ( mysql_select_db(DBNAME, $link_identifier)) {
				// run the query
		   	$queryResultHandle = mysql_query($query, $link_identifier) or die( mysql_error() );
				$rows = mysql_affected_rows($link_identifier);
				// get the last insert id for ckey to optimize spontaneous buys
				$query = "SELECT LAST_INSERT_ID()";
				$queryResultHandle_ID = mysql_query($query, $link_identifier) or die (mysql_error());
				$ckey = mysql_result($queryResultHandle_ID, 0 ); // fetch the single row data
				// decide what to do with the results
				switch( $rows ) {
					case 0:
						echo"No data was added. Contact the webmaster.<br>\n";
						break;
					case 1: // the expected result: 1 record affected
						// if the listing requires payment then send the user to the checkout
						if($paymentrequired == "yes") {
							include("premiumCheckOut.php");
							exit;
						}
						if(QUEUELISTINGS == "yes") {
							echo"Your listing has been queued for review.<br>";
							echo"Your listing will be published online when approved.<br>";
						}else{
							echo"Your listing is now published online.<br>";
						}
						echo "<h2>Final Step Completed - your $category category ADDED</h2>\n";

						if( QUEUELISTINGS == "no" ) {
							echo"<ul><li>to see your new listing click on the &quot;New!&quot; link above</li><li>to learn about Enhanced Listings click on the &quot;Go Premium&quot; link above</li></ul>";
						}
						if ( MULTICATEGORY == "yes") {
							$goal = "Add Category";
							echo"<h2>Add Another Category?</h2>";
							$receiptEmail = $yemail; // convert for possible buy scenario
							include("categoryForm.php");
						}else{
if(defined("SETRANK")) {
	if (file_exists("premiumSearchForm.php")) {
		include("premiumSearchForm.php");
	}
}else{
	include("searchForm.php");
}
						}
						break;
						
					default:
						echo"Process anomaly in $goal switch(rows). Notify the webmaster.<br>\n";	
				} // switch( $rows ) {
			}else{ // select
				echo mysql_error();
			}
		}else{ //pconnect
			echo mysql_error();
		}						
		break;

	








     case "Edit": // updates the db for one contact record
			documentStart($goal); // applies header formatting
			/* start of ADDSLASHES BLOCK  - escape quote characters and backslashes */
			$ypassword = addslashes($ypassword);
   		$yemail = addslashes($yemail);
			$ycompany = addslashes($ycompany);
		   $yfirstname = addslashes($yfirstname);
			$ylastname = addslashes($ylastname);
			$yaddress = addslashes($yaddress);
   		$ycity = addslashes($ycity);
			$ystateprov = addslashes($ystateprov);
   		$ycountry = addslashes($ycountry); 
   		$ypostalcode = addslashes($ypostalcode);
			$yareacode = addslashes($yareacode);
   		$yphone = addslashes($yphone);
   		$yfax = addslashes($yfax);
   		$ycell = addslashes($ycell);
   		$yurl = addslashes($yurl);
			//$ylogo = ""; // handled separately - it does not need addslashes because the uploaded file name is system renamed
			/* end of ADDSLASHES BLOCK */

			$query = "UPDATE " . DBTABLE . " SET ypassword='$ypassword', yemail='$yemail', ycompany='$ycompany', yfirstname='$yfirstname', ylastname='$ylastname', yaddress='$yaddress', ycity='$ycity', ystateprov='$ystateprov', ycountry='$ycountry', ypostalcode='$ypostalcode', yareacode='$yareacode', yphone='$yphone', ycell='$ycell', yfax='$yfax', yurl='$yurl' WHERE yps='$yps' ";
		
			// pconnect, select and query
			if ($link_identifier = mysql_pconnect(DBSERVERHOST, DBUSERNAME, DBPASSWORD)) {
				if ( mysql_select_db(DBNAME, $link_identifier)) {
					// run the query
			   	$queryResultHandle = mysql_query($query, $link_identifier) or die( mysql_error() );
					$rows = mysql_affected_rows($link_identifier);


// START - THIS CODE INSERTS OR UPDATES THE CUSTOMER IMAGE
					   if( USELOGOS == "yes" ) { 
							/* compare the PHP server version with the phpYellow Pages required version */
							$currentPHPversion = phpversion();
							$currentPHPversion = str_replace  ( ".", "", $currentPHPversion); // remove period characters example 4.1.2
							$currentPHPversion = substr($currentPHPversion, 0, 2); // get the first 2 characters
							$yardstick = 41; // actually 4.1.2 but we chop the 2 anyway 
							if($currentPHPversion >= $yardstick) { // is current php version greater or less than the minimum needed 4.1.2?
								// then use 4.1.2 compliant image file upload code
								include("premiumImageUpload412.php");
							}else{ // use the old image upload code
								include("premiumImageUpload404.php");
							}
						}
// END - THIS CODE INSERTS OR UPDATES THE CUSTOMER IMAGE


					echo "<h2>Edit Successful.</h2>";
					echo "<p>Thank You for keeping your information current.<br>";
					echo"<span style=\"font-style:italic;font-size:x-small;\">Note: to change your category first delete your record and then add the category you wish.</span></p>\n";

					// send a copy to the webmaster?
			    	if ( NOTIFYONCHANGE == "yes" ) { // send a copy to webmaster
						$yourphpyellowname = YOURPHPYELLOWNAME;
						$adminHome = ADMINHOME;
						$installpath = INSTALLPATH;
						$adminLink = $installpath . $adminHome;					
						$recipient = WEBMASTER;
						$from = "From: " . WEBMASTER . "\n"; // used as the 4th mail() argument
						$replyTo = "Reply-To: " . WEBMASTER . "\n";
						$xMailer = "X-Mailer: PHP/" . phpversion();
						$optionalHeaders = $from . $replyTo . $xMailer;
						$subject = "New or Changed Listing for $yourphpyellowname";
						$messagebody = "Hello $recipient,
A record has been submitted to $yourphpyellowname as follows:

password: $ypassword
email: $yemail
firm: $ycompany
contact: $yfirstname $ylastname
address: $yaddress
city: $ycity
stateprov: $ystateprov
country: $ycountry
postalcode: $ypostalcode
phone area code: $yareacode
phone: $yphone
fax: $yfax
cell: $ycell
url: $yurl


Edit this Record? $adminLink

You are receiving this note because your current setting is to receive 
notification of new or changed listings. This optional feature may be 
changed in the file util.php, by toggling the constant NOTIFYONCHANGE to 
a value of 'no' from a value of 'yes'.

$recipient";
						@mail( $recipient, $subject, $messagebody, $optionalHeaders );
	    			}
					// END OF CONDITIONALLY notify the webmaster

					// now get the categories for update
					$query = "SELECT * FROM category WHERE ypsid=$yps ORDER BY ckey DESC";
					// get the category records
				   $queryResultHandle = mysql_query($query, $link_identifier) or die( mysql_error() );
					$rows = mysql_num_rows($queryResultHandle);
					if ( $rows > 0 ) { // if there are category results ...					
						 $rows = mysql_num_rows($queryResultHandle); // run the count select to get the number of rows, without the limit
					    for ($i = 0; $i < $rows; $i++) {  // loop through all the rows
							$data = mysql_fetch_array($queryResultHandle); // fetch the row data
							// ckey ypsid category description rank paymentrequired status expires lastupdate
							// START of stripslashes block
							$ckey = stripslashes($data["ckey"]);
							$ypsid = stripslashes($data["ypsid"]);
							$category = stripslashes($data["category"]);
							$description = stripslashes($data["description"]);
							$rank = stripslashes($data["rank"]);
							$paymentrequired = stripslashes($data["paymentrequired"]);
							$status = stripslashes($data["status"]);
							$expires = stripslashes($data["expires"]);
							$lastupdate = stripslashes($data["lastupdate"]);
							// end of stripslashes block
							$yps = $ypsid;
							$goal = "Update Category";
							include("categoryForm.php");
							echo"<br><br><a href=\"goPremium.php\" target=\"_blank\">More info about Paid Listings</a><br>";
						}					
					}else{
						echo"No category results found in yellowresult.php edit mode. yps is: $yps";
					}
				}else{ // select
					echo mysql_error();
				}
			}else{ //pconnect
				echo mysql_error();
			}        
			break;






	case "highlight":
		documentStart($goal); // applies header formatting
		// selects a specific record number using the form: yellowresult.php?goal=highlight&ckey=putYourCKEYnumberHere
		$fromtable = " FROM " . DBTABLE . "," . DBTABLE2;
		$query = "SELECT * $fromtable WHERE yps=ypsid AND status='approved' AND expires >'$todaysDate' AND ckey='$ckey'";
// echo"<br>TEST: the value for the HIGHLIGHT query is:<br> $query<br>";
		// pconnect, select and query
		if ($link_identifier = mysql_pconnect(DBSERVERHOST, DBUSERNAME, DBPASSWORD)) {
			if ( mysql_select_db(DBNAME, $link_identifier)) {
				// run the query
		   	$queryResultHandle = mysql_query($query, $link_identifier) or die( mysql_error() );
				$rows = mysql_num_rows($queryResultHandle);
				if ( $rows > 0 ) {
					$data = mysql_fetch_array ($queryResultHandle);
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
					$yfckey = stripslashes($data["ckey"]);
					$yfypsid = stripslashes($data["ypsid"]);
					$yfcategory = stripslashes($data["category"]);
					$yfdescription = stripslashes($data["description"]);
					$yfrank = stripslashes($data["rank"]);
					$yfpaymentrequired = stripslashes($data["paymentrequired"]);
					$yfstatus = stripslashes($data["status"]);
					$yfexpires = stripslashes($data["expires"]);
					$yflastupdate = stripslashes($data["lastupdate"]);
               include("highlightRecord.php");  // display the row values in a template
				}else{ // if ( $rows > 0
					echo"<p style=\"color:red;font-weight:bold;\">This module is called &quot;Highlight&quot;. <br>Zero ( 0 ) rows were found. <br>The most likely problem is that a valid CKEY number was not found - your database listing is non-existent or incomplete. Did you add a category?</p>";
				}
				$category = "*"; // to prepare the category in the search form
				if(defined("SETRANK")) {
					if (file_exists("premiumSearchForm.php")) {
						include("premiumSearchForm.php");
					}
				}else{
					include("searchForm.php");
				}
			}else{ // select
				echo mysql_error();
			}
		}else{ //pconnect
			echo mysql_error();
		}		         
      break;

      



		



     case "Delete": // permanently removes data from the db
		documentStart($goal); // applies header formatting
		$query = "DELETE FROM " . DBTABLE . " WHERE yps='$yps' ";
		// pconnect, select and query
		if ($link_identifier = mysql_pconnect(DBSERVERHOST, DBUSERNAME, DBPASSWORD)) {
			if ( mysql_select_db(DBNAME, $link_identifier)) {
				// run the query
		   		$queryResultHandle = mysql_query($query, $link_identifier) or die( mysql_error() );
				$rows = mysql_affected_rows($link_identifier);
				if ( $rows == 1 ) {
					// also delete all related categories
					$query = "DELETE FROM " . DBTABLE2 . " WHERE ypsid='$yps' ";
					// run the query
					$queryResultHandle = mysql_query($query, $link_identifier) or die( mysql_error() );
					$rows = mysql_affected_rows($link_identifier);
					if ( $rows > 0 ) {
						echo"<h1>$goal Successful</h1>";
						echo"One listing and $rows related category record/s deleted.";
						echo"Thank you for keeping your information current.";
					}else{
						echo"Process anomaly in yellowresult $query.<br>";
						echo"There was no " . DBTABLE2 . " table record.";
					}
				} //if ( $rows == 1 ) {
				$category = "*"; // to prepare the category in the search form
				if(defined("SETRANK")) {
					if (file_exists("premiumSearchForm.php")) {
						include("premiumSearchForm.php");
					}
				}else{
					include("searchForm.php");
				}
			}else{ // select
				echo mysql_error();
			}
		}else{ //pconnect
			echo mysql_error();
		}		         
        break;




     case "Search": // this is the free search, the Paid is much better
		documentStart($goal); // applies header formatting
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
	            // if the DBUSERNAME has chosen a category include it
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
			    $orderby = " ORDER BY rank DESC, yps DESC";

			    // concatenate the limit for the mySQL select
			    $comma = ",";
			    $limitq = " LIMIT " . $offset . $comma . RECORDSPERPAGE;
			    // this is the final concatenation of the DBUSERNAME query
			    $concatquery = $select . $fromtable . $where . $primaryFieldSQL . $narrowResultsWith . $groupby . $orderby . $limitq; // concatenate the DBUSERNAME sql query


			    // show the pageheading in the table header
				echo "<table class=\"yellow\" width=\"100%\"><tr><th>$goal $pageheading</th></tr><tr><td>\n";

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
    	            	echo "No Matches. Click here to <a href='login.php'>add your listing free of charge.</a>";
   	    	        	include("searchForm.php");
							include("indexDynamicListings.php"); // creates a dynamic index of listings
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
				echo"<span class=\"yellow\">";
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
						// ckey ypsid category description rank paymentrequired status expires lastupdate
						$yfckey = stripslashes($data["ckey"]);
						$yfypsid = stripslashes($data["ypsid"]);
						$yfcategory = stripslashes($data["category"]);
						$yfdescription = stripslashes($data["description"]);
						$yfrank = stripslashes($data["rank"]);
						$yfpaymentrequired = stripslashes($data["paymentrequired"]);
						$yfstatus = stripslashes($data["status"]);
						$yfexpires = stripslashes($data["expires"]);
						$yflastupdate = stripslashes($data["lastupdate"]);
           			include("showOneRecord.php");  // display the row values in a template
					}
					mysql_free_result ($queryResultHandle);
				}else{
					echo"<br>Process anomaly. Please try again.<br>";
					echo $query;
					exit;
				}			

		        // do we need to present more results for the DBUSERNAME query on another page?
		        $pagestoshow = $totalmatches - RECORDSPERPAGE;
		        if ( $offset < $pagestoshow ) { // MULTIPAGE - if present offset is less than the number of the returned rows then do multipage
					$displayNextButton = "true";
        			$offset = ($offset + RECORDSPERPAGE);  // here the offset is incremented to point to the next set of results desired
					include("backNextControlsForm.php");
       			}else{
           			echo "<h3>End of Results</h3>";
				}
				echo"</td></tr></table>";
				echo"</span>";				
   			include("searchForm.php"); // offer another search form
				include("indexDynamicListings.php"); // creates a dynamic index of listings
			}else{ // select
	   			echo mysql_error();
			}
		}else{ //pconnect
			echo mysql_error();
		} 	
        break;



     default: // this is what happens if no switch case applies
	 	echo"<a href=\"http://www.dreamriver.com/phpYellow/\">See a live demo of the Premium version of phpYellow Pages on Dreamriver.com.</a><br>";
	}
include("footer.php");
?>
