<?php require_once("adminOnly.php");?>
<?php require_once("functions.php");?>
<?php // initialize or capture
$systemMessage = NULL; // any message will be below this point and NOT posted
$formToInclude = NULL; // any form inclusion will be below this point and NOT posted
$merchant = !isset($_POST["merchant"])? NULL: $_POST["merchant"]; // future use only
$badData = NULL; // initialize flag for problems
$task = !isset($_POST["task"])? NULL: $_POST['task'];
$customerid = !isset($_POST["customerid"])? NULL: $_POST['customerid'];
$productnumber = !isset($_POST["productnumber"])? NULL: $_POST['productnumber'];
$baseprice = !isset($_POST["baseprice"])? NULL: $_POST['baseprice'];
$item = !isset($_POST["item"])? NULL: $_POST['item'];
$purchasedate = !isset($_POST["purchasedate"])? NULL: $_POST['purchasedate'];
 






/*
$ = !isset($_POST[""])? NULL: $_POST[""];
*/
?>


<script language="JavaScript">
<!-- hide 
/*
This function launches a temporary popup window which displays the administrative results. 
The window is a reporting tool. The messages it shows are temporary. Therefore, the popup 
has a timeout which you may reset in configure.php to force the popup to close. No point in having 
all these windows open. Choose to close them all after POPUPTIMEOUT milliseconds in configure.php
*/
function popup() {
	var popuptimeout = <?php ECHO POPUPTIMEOUT;?>;
   newWindow = window.open('','popup','width=420,height=360');
   setTimeout('closeWin(newWindow)', popuptimeout ); // delay POPUPTIMEOUT seconds before closing (set POPUPTIMEOUT in util.php)
}
function closeWin(newWindow) {
	newWindow.close(); // close popup
}	
//-->
</script>
<?php 
 // initialize
if(!empty($task)){
	switch( $task ) {
		case"Find":
			// handled by javascript event handler on the form
			break;





		case "Add A Customer":
			$systemMessage .= "<h2>$task</h2>";
			$systemMessage .= "<p><a href=\"#customerForm\">Enter the data to add.</a></p>";
			$customerid = NULL;
			$password = NULL;
			$email = NULL;
			$privacy = NULL;
			$news = "yes";
			$firstname = NULL;
			$lastname = NULL;
			$role = NULL;
			$organization = NULL;
			$address = NULL;
			$city = NULL;
			$stateprov = NULL;
			$country = NULL;
			$postalcode = NULL;
			$areacode = NULL;
			$phone = NULL;
			$fax = NULL;
			$cellphone = NULL;
			$website = NULL;
			$customersince = NULL;
			$visits = NULL;
			$lastupdate = NULL;
			$task = "Insert Customer Record";
			$formToInclude = "customerForm.php";
			break;



	case "Insert Customer Record":
		// accept only posted variables
		$customerid = $_POST['customerid'];
		$password = $_POST['password'];
		$email = $_POST['email'];
		$firstname = $_POST['firstname'];
		$lastname = $_POST['lastname'];
		$organization = $_POST['organization'];
		$address = $_POST['address'];
		$city = $_POST['city'];
		$stateprov = $_POST['stateprov'];
		$country = $_POST['country'];
		$postalcode = $_POST['postalcode'];
		$areacode = $_POST['areacode'];
		$phone = $_POST['phone'];
		$fax = $_POST['fax'];
		$cellphone = $_POST['cellphone'];
		$website = $_POST['website'];
		$customersince = $todaysDate; // is set only on insert
		// $visits = $_POST['visits']; // this is set only on actual customer visits, not admin
		// $lastupdate = $_POST['lastupdate']; // automatically updated by mysql database
		$role = $_POST['role'];
		$privacy = $_POST['privacy'];
		$news = $_REQUEST['news'];
	
		$systemMessage .= "<h2>$task</h2>";
      if ( empty($email)) {
			$systemMessage .= "<b style=\"color:red;\">&nbsp;&nbsp;&nbsp;No email address was given. Please provide a valid email address!</b><br>";
			$formToInclude = "customerForm.php";
			break;
		}
		//validateEmail - from the php developer's cookbook but never 100% foolproof
		if (!eregi ("^([a-z0-9_]|\\-|\\.)+@(([a-z0-9_]|\\-)+\\.)+[a-z]{2,4}$", $email)) {
			$systemMessage .= "<b style=\"color:red;\">&nbsp;&nbsp;&nbsp;Invalid email address. Please provide a valid email address</b><br>";
			$formToInclude = "customerForm.php";
			break;
		}
		if ( empty($password)) {
			$systemMessage .= "<b style=\"color:red;\">&nbsp;&nbsp;&nbsp;No password was given. Please enter a password. New? Make one up.</b><br>";
			$formToInclude = "customerForm.php";
			break;
		}
		if($news!='yes'){$news='no';} // set default receive newsletter value
		// addslashes
		//$customerid = addslashes($data["customerid"]);
		$password = addslashes($password);
		$email = addslashes($email);
		$organization = addslashes($organization);
		$firstname = addslashes($firstname);
		$lastname = addslashes($lastname);
		$address = addslashes($address);
		$city = addslashes($city);
		$stateprov = addslashes($stateprov);
		$country = addslashes($country);
		$postalcode = addslashes($postalcode);
		$areacode = addslashes($areacode);
		$phone = addslashes($phone);
		$fax = addslashes($fax);
		$cellphone = addslashes($cellphone);
		$website = addslashes($website);
		//$customersince = addslashes($customersince); // assigned below
		//$visits = addslashes($visits); // assigned below
		$role = addslashes($role);
		$privacy = addslashes($privacy);
		$news = addslashes($news);
		//$lastupdate = addslashes($lastupdate);
		
		// set some useful parameters for the customerForm.php
		$customersince = $todaysDate;
		$visits = 1;

		$query = "INSERT INTO " . TABLECUSTOMER . "(password,email,organization,firstname,lastname,address,city,stateprov,country,postalcode,areacode,phone,fax,cellphone,website,customersince,visits,role,privacy,news) VALUES('$password','$email','$organization','$firstname','$lastname','$address','$city','$stateprov','$country','$postalcode','$areacode','$phone','$fax','$cellphone','$website','$customersince','$visits','$role','$privacy','$news')";
		if ($link_identifier = mysql_pconnect(DBSERVERHOST, DBUSERNAME, DBPASSWORD)) {
			if ($select = mysql_select_db(DBNAME, $link_identifier)) {
			   	$queryResultHandle = mysql_query($query, $link_identifier) or die (mysql_error());
				$rows = mysql_affected_rows (); 
				if($rows > 0) {
					$query = "SELECT LAST_INSERT_ID()";
				   	$queryResultHandle2 = mysql_query($query, $link_identifier) or die (mysql_error());
					$tempCustomerid = mysql_result($queryResultHandle2,0);	
				}
				$systemMessage .= "<h1>$task</h1>";
				$systemMessage .= "<p><b>Result</b><br>$rows record affected<br>New record is customerid #$tempCustomerid</p>";
				//$systemMessage .=  "<p><b>Query:</b> $query</p>";
			}else{ // select
	   			$systemMessage .=  mysql_error();
			} // select
		}else{ //pconnect
			$systemMessage .=  mysql_error();
		} //pconnect
		break;




	case"Add Single Purchase":
		if(empty($customerid)){
			//$systemMessage .= "<img align=\"left\" src=\"images/exclamationMark.gif\" width=\"32\" height=\"32\" border=0 alt=\"System Report\">";
			$systemMessage .= "<h2>$task</h2>";
			$systemMessage .= "<br>No customerid was present. This module cannot proceed!";
			$systemMessage .= "<br>A purchase was NOT added.";
			$systemMessage .= "<h3>End of Result</h3>";
		}else{
			// insert a purchase record into the purchase table
			// rename some variables from ITEM TABLE for use in the PURCHASE table
			$pnum = $productnumber;
			$pricepaid = $baseprice;
			$query = "INSERT INTO  " . TABLEPURCHASE . "(customerid,item,pnum,pricepaid,purchasedate) VALUES('$customerid', '$item', '$pnum' , '$pricepaid', '$purchasedate')";
			// run the query
			if ($link_identifier = mysql_pconnect(DBSERVERHOST, DBUSERNAME, DBPASSWORD)) {
				if ($select = mysql_select_db(DBNAME, $link_identifier)) {
					$queryResultHandle = mysql_query($query, $link_identifier) or die (mysql_error());
					$rows = mysql_affected_rows (); 
					//$systemMessage .= "<img align=\"left\" src=\"images/exclamationMark.gif\" width=\"32\" height=\"32\" border=0 alt=\"System Report\">";
					$systemMessage .= "<h2>$task</h2>";
					$systemMessage .= "<p><b>Result</b><br>$rows record affected<p>";
					$systemMessage .=  "<p><b>Query:</b><br><code>$query</code></p>";
					$systemMessage .= "<h3>End of Result</h3>";
				}else{ // select
	   			$systemMessage .=  mysql_error();
				} // select
			}else{ //pconnect
				$systemMessage .=  mysql_error();
			} //pconnect
		} // empty($customerid
		break;



	case"+ Purchase":
		$task = "Add Single Purchase";
		$formToInclude = "proPurchaseForm.php";
		break;



	case "Destroy It":
		
		$systemMessage .= "<h2>$task</h2>";
		if(empty($customerid)){$customerid = $uniqueKey;}
		$query = "DELETE FROM " . TABLECUSTOMER . " WHERE customerid = $customerid LIMIT 1";
		if ($link_identifier = mysql_pconnect(DBSERVERHOST, DBUSERNAME, DBPASSWORD)) {
			if ($select = mysql_select_db(DBNAME, $link_identifier)) {
			   $queryResultHandle = mysql_query($query, $link_identifier) or die (mysql_error());
				$rows = mysql_affected_rows (); 
				$systemMessage .= "<p><b>Result</b><br>$rows record affected in TABLECUSTOMER table<br>";
				$query = "DELETE FROM " . TABLEPURCHASE . " WHERE customerid = $customerid LIMIT 1";
			   $queryResultHandle3 = mysql_query($query, $link_identifier) or die (mysql_error());
				$rows3 = mysql_affected_rows ();
				$systemMessage .= "<p><b>Result</b><br>$rows3 record affected in purchase table<br>";	
				$query = "DELETE FROM " . TABLESURVEY . " WHERE surveyid = $customerid LIMIT 1";
			   $queryResultHandle2 = mysql_query($query, $link_identifier) or die (mysql_error());
				$rows2 = mysql_affected_rows ();
				$systemMessage .= "<p><b>Result</b><br>$rows2 record affected in survey table<br>";
			}else{ // select
	   			$systemMessage .=  mysql_error();
			} // select
		}else{ //pconnect
			$systemMessage .=  mysql_error();
		} //pconnect
		$systemMessage .= "<br>";
		break;




	case "Destroy This ID":
		
		$systemMessage .= "<h2>$task</h2>";
		$task = "Destroy It";
		$systemMessage .= "<p>This action will permanently destroy customerid #$uniqueKey and other related records belonging to this customer. ";
		$systemMessage .= "To confirm a destroy click on &quot;Destroy customerid #$uniqueKey&quot;. ";
		$systemMessage .= "<B style=\"color: red;\">Destroy is Permanent</b>.";
		$systemMessage .= "<form method=\"post\" action=\"workWithCustomer.php\"><input type=\"hidden\" name=\"task\" value=\"$task\"><input class=\"input\" type=submit value=\"Destroy customerid #$uniqueKey\"></p>";
		$systemMessage .= "<input type=\"hidden\" name=\"uniqueKey\" value=\"$uniqueKey\">";
		$systemMessage .= "<input type=\"hidden\" name=\"formuser\" value=\"".ADMINUSER."\">";
		$systemMessage .= "<input type=\"hidden\" name=\"formpassword\" value=\"".ADMINPASSWORD."\">";
		$systemMessage .= "</form></p>";
		break;


/*
	case "Confirm Purge of Customers":
		$systemMessage .= "<h2>$task</h2>";
		$systemMessage .= "<p>This action will permanently remove all customer records where the customer does not want to receive email AND they have NOT subscribed to the newsletter. ";
		$systemMessage .= "This feature is desirable when you want to work with just the records where the customer will accept communication from you. ";
		$systemMessage .= "<p><B style=\"color: red;\">Note: Deleting customer records may create orphaned survey records</b>. Survey records without a matching customer record can not be traced to the customer.</p>";
		$systemMessage .= "<p>Click on &quot;Purge All Customers&quot; to permanently remove these customer records.";	
		$systemMessage .= "<form method=\"post\" action=\"workWithCustomer.php\"><input type=\"hidden\" name=\"task\" value=\"Purge All Customers\"><input class=\"input\" type=submit value=\"Purge All Customers\"></p></form>";
		break;




	case "Purge All Customers":
		$systemMessage .= "<h2>$task</h2>";
		$systemMessage .= "<p><B style=\"color: red;\">Note: this may create orphaned survey records</b>.</p>";
		$query = "DELETE FROM " . TABLECUSTOMER . " WHERE news='no' AND privacy='high'";
		if ($link_identifier = mysql_pconnect(DBSERVERHOST, DBUSERNAME, DBPASSWORD)) {
			if ($select = mysql_select_db(DBNAME, $link_identifier)) {
			   	$queryResultHandle = mysql_query($query, $link_identifier) or die (mysql_error());
				$rows = mysql_affected_rows (); 
				$systemMessage .= "<h1>$goal</h1>";
				$systemMessage .= "<p><b>Result</b><br>$rows record affected<p>";
				$systemMessage .=  "<p><b>Query:</b> $query</p>";
			}else{ // select
	   			$systemMessage .=  mysql_error();
			} // select
		}else{ //pconnect
			$systemMessage .=  mysql_error();
		} //pconnect
		break;
*/




	case "Delete Single Record":
		if(empty($customerid)){$customerid = $uniqueKey;}
		$query = "DELETE FROM " . TABLECUSTOMER . " WHERE customerid = $customerid LIMIT 1";
		if ($link_identifier = mysql_pconnect(DBSERVERHOST, DBUSERNAME, DBPASSWORD)) {
			if ($select = mysql_select_db(DBNAME, $link_identifier)) {
			   $queryResultHandle = mysql_query($query, $link_identifier) or die (mysql_error());
				$rows = mysql_affected_rows (); 
				
				$systemMessage .= "<h2>$task</h2>";
				$systemMessage .= "<p><b>Result</b><br>$rows record affected<p>";
				$systemMessage .=  "<p><b>Query:</b> <code>$query</code></p>";
			}else{ // select
	   			$systemMessage .=  mysql_error();
			} // select
		}else{ //pconnect
			$systemMessage .=  mysql_error();
		} //pconnect
		break;




	case "Delete A Customer":
		
		$systemMessage .= "<h2>$task</h2>";
		$systemMessage .= "<p>Enter the record number to delete. <B style=\"color: red;\">Delete is PERMANENT</b>.</p>";
		$task = "Delete Single Record";
		$formToInclude = "customerRetrieve.php";
		break;





	case "Update A Customer": // sends edited record to database
		// accept only posted variables
		$customerid = $_REQUEST['customerid']; // may arrive as get or post - Find Needle in Haystack, or customerForm
		$password = $_POST['password'];
		$email = $_POST['email'];
		$firstname = $_POST['firstname'];
		$lastname = $_POST['lastname'];
		$organization = $_POST['organization'];
		$address = $_POST['address'];
		$city = $_POST['city'];
		$stateprov = $_POST['stateprov'];
		$country = $_POST['country'];
		$postalcode = $_POST['postalcode'];
		$areacode = $_POST['areacode'];
		$phone = $_POST['phone'];
		$fax = $_POST['fax'];
		$cellphone = $_POST['cellphone'];
		$website = $_POST['website'];
		// $customersince = $_POST['customersince']; // this is set only on insert
		// $visits = $_POST['visits']; // this is set only on actual customer visits, not admin
		// $lastupdate = $_POST['lastupdate']; // automatically updated by mysql database
		$role = $_POST['role'];
		$privacy = $_POST['privacy'];
		$news = !isset($news)?"no":"yes";

		// addslashes
		$customerid = addslashes($customerid);
		$password = addslashes($password);
		$email = addslashes($email);
		$organization = addslashes($organization);
		$firstname = addslashes($firstname);
		$lastname = addslashes($lastname);
		$address = addslashes($address);
		$city = addslashes($city);
		$stateprov = addslashes($stateprov);
		$country = addslashes($country);
		$postalcode = addslashes($postalcode);
		$areacode = addslashes($areacode);
		$phone = addslashes($phone);
		$fax = addslashes($fax);
		$cellphone = addslashes($cellphone);
		$website = addslashes($website);
		// $customersince = addslashes($customersince); // this is set only on insert
		// $visits = addslashes($visits); // this is set only on file download
		$role = addslashes($role);
		$privacy = addslashes($privacy);
		$news = addslashes($news);
		//$lastupdate = addslashes($lastupdate); // automatically updated by mysql database
		$query = "UPDATE " . TABLECUSTOMER . " SET password='$password',email='$email',organization='$organization',firstname='$firstname',lastname='$lastname',address='$address',city='$city',stateprov='$stateprov',country='$country',postalcode='$postalcode',areacode='$areacode',phone='$phone',fax='$fax',cellphone='$cellphone',website='$website',role='$role',privacy='$privacy',news='$news' WHERE customerid = $customerid LIMIT 1";
		if ($link_identifier = mysql_pconnect(DBSERVERHOST, DBUSERNAME, DBPASSWORD)) {
			if ($select = mysql_select_db(DBNAME, $link_identifier)) {
			   $queryResultHandle = mysql_query($query, $link_identifier) or die (mysql_error());
				$rows = mysql_affected_rows (); 
				$systemMessage .= "<h1>$task</h1>";
				$systemMessage .= "<p><b>Result</b><br>$rows record affected<p>";
				$systemMessage .=  "<p><b>Query:</b> <code>$query</code></p>";
			}else{ // select
	   		$systemMessage .=  mysql_error();
			} // select
		}else{ //pconnect
			$systemMessage .=  mysql_error();
		} //pconnect
		break;


	case "Edit A Customer":
		$systemMessage .= "<h2>$task</h2>";
		$task = "Retrieve Customer Record";
		$systemMessage .= "<p>Enter the record number to edit.</p>";
		$formToInclude = "customerRetrieve.php";
		break;



	case "Retrieve Customer Record": // fetches one record
	case "Retrieve+Customer+Record": // if get induced
$uniqueKey = NULL;
	$customerid = !isset($customerid)?$uniqueKey:$_REQUEST['customerid'];
//	if(empty($customerid)){$customerid = $uniqueKey;}
	if(!empty($customerid)) {
		$query = "SELECT * FROM " . TABLECUSTOMER . " WHERE customerid = $customerid";
		// pconnect, select and query
		if ($link_identifier = mysql_pconnect(DBSERVERHOST, DBUSERNAME, DBPASSWORD)) {
			if ( mysql_select_db(DBNAME, $link_identifier)) {
				// run the query
		   		$queryResultHandle = mysql_query($query, $link_identifier) or die( mysql_error() );
				$rows = mysql_num_rows($queryResultHandle); // get # of rows
				if ( $rows > 0 ) { // if a customer record exists ...
					$data = mysql_fetch_array($queryResultHandle);
					// grab the data and stuff it into an editable form
					//$customerid = stripslashes($data["customerid"]);
					$password = $data["password"];
					$email = stripslashes($data["email"]);
					$organization = stripslashes($data["organization"]);
					$firstname = stripslashes($data["firstname"]);
					$lastname = stripslashes($data["lastname"]);
					$address = stripslashes($data["address"]);
					$city = stripslashes($data["city"]);
					$stateprov = stripslashes($data["stateprov"]);
					$country = stripslashes($data["country"]);
					$postalcode = stripslashes($data["postalcode"]);
					$areacode = stripslashes($data["areacode"]);
					$phone = stripslashes($data["phone"]);
					$fax = stripslashes($data["fax"]);
					$cellphone = stripslashes($data["cellphone"]);
					$website = stripslashes($data["website"]);
					$customersince = stripslashes($data["customersince"]);
					$visits = stripslashes($data["visits"]);
					$role = stripslashes($data["role"]);
					$privacy = stripslashes($data["privacy"]);
					$news = stripslashes($data["news"]);
					$lastupdate = stripslashes($data["lastupdate"]);
					// now point the task to the next step
					$task = "Update A Customer";
					$formToInclude = "customerForm.php";
				}else{
					$systemMessage .= "<p>No data found for customerid # $customerid.</p>";
					$formToInclude = "customerRetrieve.php"; // prompt for another record
				}
			}else{ // select
				$systemMessage .=  mysql_error();
			}
		}else{ //pconnect
			$systemMessage .=  mysql_error();
		}
	}else{
		$systemMessage .= "<h2>No customerid</h2>";
		$systemMessage .= "&nbsp;&nbsp;&nbsp;No customerid was present.";
		$systemMessage .= "<br>&nbsp;&nbsp;&nbsp;This module cannot proceed.<br><br>";
	} // if(!empty($customerid
		break;








	case "Manage All Customers":
		
		$systemMessage .= "<h2>$task</h2>";
		$recordsPerPage = 50;
		if(isset($offset)) {
			$offset = $offset + $recordsPerPage;		
		}else{
			$offset = 0;
		}
		$limit = "LIMIT $offset,$recordsPerPage";
		$query = "SELECT * FROM " . TABLECUSTOMER . " ORDER BY customerid DESC $limit";
		// pconnect, select and query
		if ($link_identifier = mysql_pconnect(DBSERVERHOST, DBUSERNAME, DBPASSWORD)) {
			if ( mysql_select_db(DBNAME, $link_identifier)) {
				// run the query
		   		$queryResultHandle = mysql_query($query, $link_identifier) or die( mysql_error() );
				$rows = mysql_num_rows($queryResultHandle); // get # of rows
$systemMessage .=  "<p><code>$query</code></p>";
				$systemMessage .= "<table align=\"center\" border=1>";
				if ( $rows > 0 ) {

					while ($data = mysql_fetch_array($queryResultHandle)) {
						// grab the data and display it
						$customerid = stripslashes($data["customerid"]);
						$password = $data["password"];
						$email = stripslashes($data["email"]);
						$organization = stripslashes($data["organization"]);
						$firstname = stripslashes($data["firstname"]);
						$lastname = stripslashes($data["lastname"]);
						$address = stripslashes($data["address"]);
						$city = stripslashes($data["city"]);
						$stateprov = stripslashes($data["stateprov"]);
						$country = stripslashes($data["country"]);
						$postalcode = stripslashes($data["postalcode"]);
						$areacode = stripslashes($data["areacode"]);
						$phone = stripslashes($data["phone"]);
						$fax = stripslashes($data["fax"]);
						$cellphone = stripslashes($data["cellphone"]);
						$website = stripslashes($data["website"]);
						$customersince = stripslashes($data["customersince"]);
						$visits = stripslashes($data["visits"]);
						$role = stripslashes($data["role"]);
						$privacy = stripslashes($data["privacy"]);
						$news = stripslashes($data["news"]);
						$lastupdate = stripslashes($data["lastupdate"]);

						// show one record of data
						$systemMessage .= "<tr bgcolor=\"white\"><td>\n";
						$formToInclude = "customerRecord.php";
						$systemMessage .= "</td><td valign=\"middle\">\n";
						$formToInclude .= "aedControl.php";
						$systemMessage .= "</td></tr>\n";
					} // while
					$systemMessage .= "<tr><td colspan=2>\n";
					$formToInclude .= "customerBackNextControl.php";
					$systemMessage .= "</td></tr>\n";
					$systemMessage .= "</table>\n";
				}else{
					$systemMessage .= "<p>No data found for customerid # $customerid.</p>";
				}
			}else{ // select
				$systemMessage .=  mysql_error();
			}
		}else{ //pconnect
			$systemMessage .=  mysql_error();
		}
		break;
		
				

		case"Clear":
		default:
	}// end switch $task
}else{ // if(!empty($task))
	//$systemMessage .= "No goal is present this program cannot continue.";
	//exit;
}?>



<!-- this component should be last on the page - the visual results -->
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
	<HEAD>
		<?php require_once("configure.php");?>
      <script language="Javascript" src="phpcheckout.js"></script>
      <script language="Javascript">loadCSSFile();</script>
		<TITLE>Work With Customer -  <?php echo IMPLEMENTATIONNAME;?> - <?php echo BENEFIT;?> -  <?php echo ORGANIZATION;?> - Powered by phpcheckoutTM from DreamRiver http://wwww.dreamriver.com - Software Powers the Net - a DreamRiver Internet Software Product - php software mysql database - download - web database - dreamriver - freeware - sql - file downloader - internet application development - hot scripts - good value - database software - custom code - php e-mail software - ultimate web apps software - phpYellow Pages - EasySQL - phpTellAFriend - mysql php download - Easily create complete internet programs with php, apache, mysql and windows</TITLE>
		<meta name="Generator" content="Custom Handmade">
		<meta name="Author" content="DreamRiver.com, Richard Creech, http://www.dreamriver.com">
		<meta name="Keywords" content="PHPCHECKOUT, checkout, php, check, out, php checkout, contact, shop, shopping, shopper, sales, catalog, online, retail, retailer, download, downloading, make, money, sell, product, products">
		<meta name="Description" content="Contact DreamRiver.com about PHPCHECKOUT by visiting this page. Select one of many communication methods including phone, stealth email or snail mail options. Profit from your own digital downloads with PHPCHECKOUT.">
</HEAD> 

<body>
<!-- START of body -->

<?php include("navAllAdmin.php");?>


<h1>Work With Customer</h1>


<table border=0>
<tr>
	<!-- Task Panel -->
	<td width="15%" valign="top" class="favcolor2"><h3>Task Panel</h3>
		<?php include("navCustomer.php");?>
	</td>
	
	<!-- Reporting Area -->
	<td width="600" valign="top"><h3>Reporting Area</h3>
	
		<?php echo $systemMessage;?>

		<?php if(!empty($formToInclude)) {include("$formToInclude");}?>

	</td>
</tr>
</table>