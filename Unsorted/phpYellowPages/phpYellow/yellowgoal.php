<?php require_once("util.php");?>
<?php 
function documentStart($goal){
	include("documentStart.php");
}
/* attain the goal - add, edit, delete, search, password lookup
NO inserts or updates are made here -
the insert and update forms are only retrieved and displayed,
NOT applied to the database ...
See yellowresult.php for the ONLY database insert and update code.
*/

// START OF capture or intialize variables
// test to see what version is used. If newer then capture or intialize variables
$currentPHPversion = phpversion();
$currentPHPversion = str_replace  ( ".", "", $currentPHPversion); // remove period characters example 4.1.2
$currentPHPversion = substr($currentPHPversion, 0, 2); // get the first 2 characters
// do the same data handling again for the benchmark php version of PHP 4.1.2
$yardstick = 41; // actually 4.1.2 but we chop the 2 anyway 
if($currentPHPversion >= $yardstick) { // is current php version greater or less than the minimum needed 4.1.2?
	$goal = !isset($goal)?NULL:$_REQUEST['goal'];
	$yps = !isset($yps)?NULL:$_REQUEST['yps'];
}
// END OF capture or intialize variables

switch ($goal) {

		case "Comment":
			// this case sets up the comment form. The visitor fills it in and submits it to the Send Email module below
			documentStart($goal); // write the top of the page
			$goal = "Send a Comment";
			include("commentForm.php");
			break;



		case "Send a Comment":
			documentStart($goal); // write the top of the page	 
			// is the visitor email address valid?
			if ( empty($userEmail)) {
				echo"<b style=\"color:red;\">No email address was given. Please provide a valid email address!</b><br>";
				include("commentForm.php");
			break;
		}
		//validateEmail - from the php developer's cookbook but never 100% foolproof
		if (!eregi ("^([a-z0-9_]|\\-|\\.)+@(([a-z0-9_]|\\-)+\\.)+[a-z]{2,4}$", $userEmail)) {
			echo"<b style=\"color:red;\">Invalid email address. Please provide a valid email address.</b><br>";
			include("commentForm.php");
			break;
		}
			// formulate the query
			$query = "SELECT yemail FROM " . DBTABLE . " WHERE yps = '$yps'";
			// pconnect, select and query
			if ($link_identifier = mysql_pconnect(DBSERVERHOST, DBUSERNAME, DBPASSWORD)) {
				if ( mysql_select_db(DBNAME, $link_identifier)) {
					// run the query
		   		$queryResultHandle = mysql_query($query, $link_identifier) or die( mysql_error() );
					$rows = mysql_num_rows($queryResultHandle); // get # of rows
					if ( $rows < 1 ) {  // if no rows are found
		    			echo "Sorry, no email address was found.<br><br>\n";
					}else{ //if ( $rows < 1 )
						// if there are rows then get the results
						$data = mysql_fetch_array($queryResultHandle);
						$recipientEmail = stripslashes($data["yemail"]);
						// send the email note from the visitor to the lister
						$firstLineOfEmail = $userFirstName . " at " . $userEmail . " wrote: \n";
						$messageBody = $firstLineOfEmail . $messageBody;
						$systemEmail = SYSTEMEMAIL;
						$from = "From: $systemEmail\n"; /* used as the 4th mail() argument */
						$replyTo = "Reply-To: $userEmail\n";
						$recipient = $recipientEmail;
						$xMailer = "X-Mailer: PHP/" . phpversion();
						$optionalHeaders = $from . $replyTo . $xMailer;
						$recipientLength = strlen( $recipient );
						if ( $recipientLength < 6 ) {
							echo"<p>You MUST enter a VALID email address.</p>\n";
							include("commentForm.php");
						}else{
							if ( mail( $recipient, $subject, $messageBody, $optionalHeaders )) {
								echo"<h3>eMail Sent</h3>";
								echo"Thank You for using our Send a Comment service!<br>";
							}else{
								echo"<h3>Something Went Wrong!</h3>";
								echo"Please check that you have entered all necessary information.<br><br>";
								include("commentForm.php");
							} // mail
						} // recipientLength
					} // rows
				}else{ // select
					echo mysql_error();
				}
			}else{ //pconnect
				echo mysql_error();
			}
			break;




     case "Password Lookup": 
		documentStart($goal); // write the top of the page	 
		// formulate the query
		$query = "SELECT yemail, ypassword FROM " . DBTABLE . " WHERE yemail = '$loginemail'";
		// pconnect, select and query
		if ($link_identifier = mysql_pconnect(DBSERVERHOST, DBUSERNAME, DBPASSWORD)) {
			if ( mysql_select_db(DBNAME, $link_identifier)) {
				// run the query
		   		$queryResultHandle = mysql_query($query, $link_identifier) or die( mysql_error() );
				$rows = mysql_num_rows($queryResultHandle); // get # of rows
				if ( $rows < 1 ) {  // if no rows are found
		    		echo "Sorry, no password was found for <a href='mailto:$loginemail'>$loginemail</a>.";
				}else{ //if ( $rows < 1 )

					// if there are rows then show the table headings <th> and get the results
					echo("<table>\n");
					echo("<tr><th>Email</th><th>Password</th></tr>\n");
					for ($i = 0; $i < $rows; $i++) {
						$data = mysql_fetch_array($queryResultHandle);
					    $emaildata = stripslashes($data["yemail"]);
					    $password = stripslashes($data["ypassword"]);    
			    		$passworddata = "Found it!"; // for security reasons we won't display the password on the screen
					    echo("<tr><td>$emaildata</td><td>$passworddata</td></tr>\n");
					}
					echo("</table>\n");

					// then email it to the user
					// convert CONSTANTS to variables for use in email note
					$installpath = INSTALLPATH; // to embed this data in the email note
					$addEditDeletePage = "login.php";
					$loginPage = $installpath . $addEditDeletePage;
					$company = COMPANY;
					$webmaster = WEBMASTER;
					$productname = PRODUCTNAME;
					$yourphpyellowname = YOURPHPYELLOWNAME;
					$recipient = $emaildata;
					$from = "From: " . WEBMASTER . "\n"; /* used as the 4th mail() argument */
					$replyTo = "Reply-To: " . WEBMASTER . "\n";
					$xMailer = "X-Mailer: PHP/" . phpversion();
					$optionalHeaders = $from . $replyTo . $xMailer;
					$subject = "You Requested your Password on " . YOURPHPYELLOWNAME;
					$messagebody = "Hello $emaildata,
You are receiving this note because your email address was entered in our 
$yourphpyellowname Password Lookup.

Your password is: $password
Your email is: $emaildata

You may login to add, edit or delete your $yourphpyellowname listing here:
$loginPage

Regards,

Technical Support
$webmaster
$company";
					if ( @mail( $recipient, $subject, $messagebody, $optionalHeaders )) {
						echo "<p>Your password has been emailed and you will receive it shortly.</p>";
					}else{
						echo"<br>Email failed.";
					}
					// show the yellow page search form 
					$category = "*";
					if (file_exists("premiumSearchForm.php")) {
						$goal="Advanced Search";
						include("premiumSearchForm.php");
					}else{
						include("searchForm.php");
					}
					// end of email data
				} //if ( $rows < 1 )
			}else{ // select
				echo mysql_error();
			}
		}else{ //pconnect
			echo mysql_error();
		}
        break; 





     case "Add": // offer the blank insertion form template where a DBUSERNAME may add a new listing
			documentStart($goal); // write the top of the page	
			// capture values for email and password 
			$yemail = !isset($yemail)?NULL:$HTTP_POST_VARS['yemail'];
			$ypassword = !isset($ypassword)?NULL:$HTTP_POST_VARS['ypassword'];
			// initialize addForm.php variables
	      $ycompany = NULL;
         $yfirstname = NULL;
			$ylastname = NULL;
		   $yaddress = NULL;
     		$ycity = NULL;
	      $ystateprov = NULL;
        	$ycountry = NULL;
        	$ypostalcode = NULL;
			$yareacode = NULL;
        	$yphone = NULL;
     		$yfax = NULL;
        	$ycell = NULL;
        	$yurl = NULL;
			$ylogo = NULL;
       	include("addForm.php");
			break;




     case "Edit": 
			/* 
				retrieve the database record and 
				populate an edit form for possible modification 
			*/
			documentStart($goal); // write the top of the page	
			// retrieve listings for edit
			$query = "SELECT * FROM " . DBTABLE . " WHERE yemail='$yemail' AND ypassword='$ypassword'";
			// pconnect, select and query
			if ($link_identifier = mysql_pconnect(DBSERVERHOST, DBUSERNAME, DBPASSWORD)) {
				if ( mysql_select_db(DBNAME, $link_identifier)) {
					// run the query
		   		$queryResultHandle = mysql_query($query, $link_identifier) or die( mysql_error() );
					$rows = mysql_num_rows($queryResultHandle); // get # of rows
					if ( $rows > 0 ) { // if there are any records ...
						for ($i = 0; $i < $rows; $i++) { // loop through the rows and fetch data from each record
						$data = mysql_fetch_array($queryResultHandle);
/* yps ypassword yemail ycompany yfirstname ylastname yaddress ycity ystateprov ycountry  
ypostalcode yareacode yphone ycell yfax yurl ylogo lastupdate */
						$yps = stripslashes($data["yps"]);  // populate the variables with the array element values
	            	$ypassword = stripslashes($data["ypassword"]); // stripslashes reverses addslashes
	            	$yemail = stripslashes($data["yemail"]);
	    		      $ycompany = stripslashes($data["ycompany"]);
			         $yfirstname = stripslashes($data["yfirstname"]);
						$ylastname = stripslashes($data["ylastname"]);
    	  			   $yaddress = stripslashes($data["yaddress"]);
		        		$ycity = stripslashes($data["ycity"]);
	    		      $ystateprov = stripslashes($data["ystateprov"]);
	            	$ycountry = stripslashes($data["ycountry"]); 
	            	$ypostalcode = stripslashes($data["ypostalcode"]);
						$yareacode = stripslashes($data["yareacode"]);
	            	$yphone = stripslashes($data["yphone"]);
    	        		$yfax = stripslashes($data["yfax"]);
	            	$ycell = stripslashes($data["ycell"]);
	            	$yurl = stripslashes($data["yurl"]);
						$ylogo = stripslashes($data["ylogo"]);
						$ylastupdate = $data["lastupdate"];
	            	include("addForm.php");
					}
                	echo("</td></tr></table>\n");
		        }else{
    		        print "NO matches found! <a href='password.php'>Need your password?</a>";
        		}
			}else{ // select
				echo mysql_error();
			}
		}else{ //pconnect
			echo mysql_error();
		}
        break;



     case "Delete":
			documentStart($goal); // write the top of the page		 
			// retrieve listings for review - just like edit - except we'll delete them instead of update
			$query = "SELECT * FROM " . DBTABLE . " WHERE yemail='$yemail' AND ypassword='$ypassword'";
			// pconnect, select and query
			if ($link_identifier = mysql_pconnect(DBSERVERHOST, DBUSERNAME, DBPASSWORD)) {
				if ( mysql_select_db(DBNAME, $link_identifier)) {
				// run the query
		   	$queryResultHandle = mysql_query($query, $link_identifier) or die( mysql_error() );
				$rows = mysql_num_rows($queryResultHandle); // get # of rows
		      if ($rows > 0 ) { // if rows are greater than zero, get the results
					for ($i = 0; $i < $rows; $i++) { // loop through the rows and fetch data from each record
						$data = mysql_fetch_array($queryResultHandle);
/* yps ypassword yemail ycompany yfirstname ylastname yaddress ycity ystateprov ycountry  
ypostalcode yareacode yphone ycell yfax yurl ylogo lastupdate */
		            $yps = stripslashes($data["yps"]);  // populate the variables with the array element values
	            	$ypassword = stripslashes($data["ypassword"]); // stripslashes reverses addslashes
	            	$yemail = stripslashes($data["yemail"]);
	    		      $ycompany = stripslashes($data["ycompany"]);
			         $yfirstname = stripslashes($data["yfirstname"]);
						$ylastname = stripslashes($data["ylastname"]);
    	  			   $yaddress = stripslashes($data["yaddress"]);
		        		$ycity = stripslashes($data["ycity"]);
	    		      $ystateprov = stripslashes($data["ystateprov"]);
	            	$ycountry = stripslashes($data["ycountry"]); 
	            	$ypostalcode = stripslashes($data["ypostalcode"]);
						$yareacode = stripslashes($data["yareacode"]);
	            	$yphone = stripslashes($data["yphone"]);
    	        		$yfax = stripslashes($data["yfax"]);
	            	$ycell = stripslashes($data["ycell"]);
	            	$yurl = stripslashes($data["yurl"]);
						$ylogo = stripslashes($data["ylogo"]);
						$ylastupdate = stripslashes($data["lastupdate"]);
	            	include("addForm.php");
					} // for
	                echo("</td></tr></table>\n");					
	            }else{ // if ($rows > 0 )
		            echo "NO matches found! <a href='password.php'>Need your password?</a>";
				} // if ($rows > 0 )
			}else{ // select
				echo mysql_error();
			}
		}else{ //pconnect
			echo mysql_error();
		}
        break;



     case "Oops!":
			documentStart($goal); // write the top of the page		 
			print "<p>You need to choose a goal.</p>";
			include("loginForm.php");
			break;             




     default:
			documentStart($goal); // write the top of the page		 
			print "Please contact technical support at " . WEBMASTER;        
  }
?>



<?php include("footer.php");?>
