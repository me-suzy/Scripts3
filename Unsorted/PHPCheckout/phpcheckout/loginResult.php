<?php require_once("configure.php");?>
<?php 
// initialize or capture IMPORTANT variables
$task = !isset($_POST['task'])?NULL:$_POST['task'];
$customerid = !isset($_POST['customerid'])? NULL: $_POST['customerid'];
?>
<?php
// initialize or capture user form variables
$task = !isset($task)?"Register":$_POST['task'];
$customerid = !isset($_POST['customerid'])? NULL: $_POST['customerid'];
$email = !isset($_POST['email'])? NULL: $_POST['email'];
$password = !isset($_POST['password'])? NULL: $_POST['password'];
$confirmPassword = !isset($_POST['confirmPassword'])? NULL: $_POST['password'];
$privacy = !isset($_POST['privacy'])? "high": $_POST['privacy'];
$news = !isset($_POST['news'])? "no": $_POST['news'];
$firstname = !isset($_POST['firstname'])? NULL: $_POST['firstname'];
$lastname = !isset($_POST['lastname'])? NULL: $_POST['lastname'];
$role = !isset($_POST['role'])? NULL: $_POST['role'];
$organization = !isset($_POST['organization'])? NULL: $_POST['organization'];
$address = !isset($_POST['address'])? NULL: $_POST['address'];
$city = !isset($_POST['city'])? NULL: $_POST['city'];
$stateprov = !isset($_POST['stateprov'])? NULL: $_POST['stateprov'];
$country = !isset($_POST['country'])? NULL: $_POST['country'];
$postalcode = !isset($_POST['postalcode'])? NULL: $_POST['postalcode'];
$areacode = !isset($_POST['areacode'])? NULL: $_POST['areacode'];
$phone = !isset($_POST['phone'])? NULL: $_POST['phone'];
$fax = !isset($_POST['fax'])? NULL: $_POST['fax'];
$cellphone = !isset($_POST['cellphone'])? NULL: $_POST['cellphone'];
$website = !isset($_POST['website'])? NULL: $_POST['website'];
$remember_me = !isset($_POST['remember_me'])? NULL: $_POST['remember_me'];
$customersince = !isset($_POST['customersince'])? NULL: $_POST['customersince'];
$visits = !isset($_POST['visits'])? NULL: $_POST['visits'];
$lastupdate = !isset($_POST['lastupdate'])? NULL: $_POST['lastupdate'];


?>
<?php $userComments = !isset($_POST['userComments'])?NULL:$_POST['userComments']; // from Send Feedback module ?>
<?php $messageFromResult = !isset($_POST['messageFromResult'])?NULL:$_POST['messageFromResult']; // a reporting string ?>
<?php $cookieWasSet = !isset($_POST['cookieWasSet'])?NULL:$_POST['cookieWasSet']; // state of customerid cookie?>
<?php $formToInclude = NULL; // initialize ?>
<?php
//
////////////////////////////////////// START OF DYNAMIC PROCESSES
/*
The user may select tasks from a list on the left of the loginResults.php page. One task at a time 
is sent to loginResult.php. This page processes the task selected and displays 
any results.
*/
switch( $task ) {
	case "Verify Customer Record": // fetches one record
		$query = "SELECT * FROM " . TABLECUSTOMER . " WHERE email = '$email' AND password = '$password'";
		// pconnect, select and query
		if ($link_identifier = mysql_pconnect(DBSERVERHOST, DBUSERNAME, DBPASSWORD)) {
			if ( mysql_select_db(DBNAME, $link_identifier)) {
				// run the query
	   		$queryResultHandle = mysql_query($query, $link_identifier) or die( mysql_error() );
				$rows = mysql_num_rows($queryResultHandle); // get # of rows
				if ( $rows > 0 ) {
					$data = mysql_fetch_array($queryResultHandle);
					// grab the data and make it retreivable
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
					$messageFromResult = "<h3>Customer Record Verified for ID#$customerid</h3>";
					$messageFromResult .= "<h2>Welcome $firstname</h2>";
					$messageFromResult .= "<p>Pick a button on the left.</p>";
					// setup the customerid variable for the cookie
					$_POST['customerid'] = $customerid;
					///////////////////////////// START OF COOKIE CODE
					include("cookieTheCustomerID.php");
					///////////////////////////// END OF COOKIE CODE
				}else{
					$messageFromResult = "<h3>No data found for customer</h3>";
					$formToInclude = "loginForm.php";
				}
			}else{ // select
				echo mysql_error();
			}
		}else{ //pconnect
			echo mysql_error();
		}			
		break;





	case"Send the Feedback":
		// send email, perhaps cc the user
		$technicalSupport = TECHNICALSUPPORT;
		$recipient = $technicalSupport;
		$from = "From: " . TECHNICALSUPPORT . "\n"; /* used as the 4th mail() argument */
		$replyTo = "Reply-To: " . TECHNICALSUPPORT . "\n";
		$xMailer = "X-Mailer: PHP/" . phpversion();
		$optionalHeaders = $from . $replyTo . $xMailer;
		$subject = IMPLEMENTATIONNAME;
		$messagebody = "Sent by $firstname $lastname on $todaysTextDate:
Customer Comments		
$userComments 


Customer Personal Details
First name: $firstname
Last name: $lastname
Organization: $organization
Role: $role


Address Details
Address: $address
City: $city
State: $stateprov
Country: $country
Postal code: $postalcode


E Details
Email: $email
Website: $website


Login Details
Customerid: $customerid
Password: $password
Customer since: $customersince
Visits: $visits
Privacy: $privacy
News: $news


Phone Details
Area code: $areacode
Phone: $phone
Fax: $fax
Cell phone: $cellphone


Last update: $lastupdate
 - - - - - - - - - END OF EMAIL - - - - - - - - -
";
		if (@mail( $recipient, $subject, $messagebody, $optionalHeaders )) {
			// build the text response string to insert into the right place later
			$messageFromResult .= "<h2>Email sent</h2>\n";
			$messageFromResult .= "<ul><li>The email was sent</li>\n";
			$messageFromResult .= "<li>THANK YOU for your feedback</li>\n";
			$messageFromResult .= "<li>Our policy is to respond within 24 hours or one business day</li>\n";
			$messageFromResult .= "<li>If you do not hear from us within this timeframe then <a href=\"contact.php\">please contact us</a></li></ul>\n";
		}else{
			$messageFromResult .= "<h2>Email NOT sent</h2>\n";
			$messageFromResult .= "<p>Email NOT sent. Please try again later.</p>\n";
		}
		$cc = $_POST['cc'];
		if( $cc == "on" ) { // if the cc checkbox was selected
			// send a copy to the original author
			$recipient = $email;
			@mail( $recipient, $subject, $messagebody, $optionalHeaders );
		}
		break;



	case"Send Feedback":
		$messageFromResult = "<h1>Send Feedback</h1>";
		$formToInclude = "sendFeedbackForm.php";
		break;





	case"View My Licences":
		// for this case only print all output immediately. This was necessitated by the possible many retrieve forms to be generated from multiple purchases. It was ugly any other way.
		require_once("functions.php");
		drawHeader();
		include("header.php");
		echo"<blockquote>";
		echo"<h1>View My Licenses</h1>";
		// display licenses held to the user
		$query = "SELECT item, purchasedate, pnum FROM " . TABLEPURCHASE . " WHERE customerid = '$customerid' ORDER BY transaction DESC";
		// pconnect, select and query
		if ($link_identifier = mysql_pconnect(DBSERVERHOST, DBUSERNAME, DBPASSWORD)) {
			if ( mysql_select_db(DBNAME, $link_identifier)) {        
				// run the query
				$queryResultHandle = mysql_query($query, $link_identifier) or die (mysql_error());
				$rows = mysql_num_rows($queryResultHandle);
				if ($rows >= 1){ // if there are rows then show them
   				echo"<TABLE class=\"favcolor2\" BORDER=0><TR>\n";
					$numberOfFields = mysql_num_fields($queryResultHandle);
	     			for ($i = 0; $i < $numberOfFields; $i++) {
						if( mysql_field_name($queryResultHandle,$i)=='pnum' ) {
        					echo"<TH>&nbsp;</TH>";
						}else{
         				echo"<TH>" . mysql_field_name($queryResultHandle,$i) . "</TH>";
	  					}
					}
					echo"</TR>\n";
        			for ($i = 0; $i < $rows; $i++) {
		     			echo"<TR>\n";
    	     			$row_array = mysql_fetch_row($queryResultHandle);
      				for ($j = 0; $j < $numberOfFields; $j++) {
							if( mysql_field_name($queryResultHandle,$j)=='pnum' ) {
								$productnumber = $row_array[$j];
								// get the value for the download method, a variable called 'via' in the product form
								$query = "SELECT via FROM " . TABLEITEMS . " WHERE productnumber = '$productnumber'";
								// run the query
								$queryResultHandle = mysql_query($query, $link_identifier) or die (mysql_error());
								if($queryResultHandle = mysql_result($queryResultHandle,0)) {
	         					echo"<TD>";
									include("loginRetrieveItemForm.php");
	         					echo"</TD>\n";
								}else{
									echo"Data retrieval failure.";
									exit;
								}
							}else{
	         				echo"<TD>" . $row_array[$j] . "</TD>\n";
		  					}
						}
     	  				echo"</TR>\n";
      			}
					echo"</TABLE>";
				}else{
					echo"<h3>No paid licences were found for $email</h3>";
					include("loadLoginModuleForm.php");
				}
			}else{ // select
				echo mysql_error();
			}
		}else{ //pconnect
			echo mysql_error();
		}
		echo"</blockquote></body></html>";
		exit;
		break;




	case "Help":
		$formToInclude = "loginHelp.php";
		break;



	


	case"Retrieve Contact Info":
		// include a form so the user can update their data
		$_POST['task'] = "Update Contact Info";
		$messageFromResult = "<h1>Retrieve Contact Info</h1>";
		$formToInclude = "registerForm.php";
		break;




	case"Update Contact Info":
		// check to see if the password matches the password confirm
		$password = $_REQUEST['password'];
		$confirmPassword = $_REQUEST['confirmPassword'];
		if($confirmPassword != $password) {
			include_once("header.php");
			$password = "";
			$messageFromResult =  "<p>Password and confirming password do not match. Please enter them again.</p>";
			$formToInclude = "registerForm.php";
			$_REQUEST['task'] = "Update Contact Info";
			echo $messageFromResult;
			include("$formToInclude");
			exit;
		}

		// commit all data to the database
		// addslashes
		//$customerid = addslashes($customerid);
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
		//$visits = addslashes($visits); // this is set only on file download
		$role = addslashes($role);
		$privacy = addslashes($privacy);
		$news = addslashes($news);
		//$lastupdate = addslashes($lastupdate); // automatically updated by mysql database
		$query = "UPDATE " . TABLECUSTOMER . " SET password='$password',email='$email',organization='$organization',firstname='$firstname',lastname='$lastname',address='$address',city='$city',stateprov='$stateprov',country='$country',postalcode='$postalcode',areacode='$areacode',phone='$phone',fax='$fax',cellphone='$cellphone',website='$website',role='$role',privacy='$privacy',news='$news' WHERE customerid = $customerid LIMIT 1";
		if ($link_identifier = mysql_pconnect(DBSERVERHOST, DBUSERNAME, DBPASSWORD)) {
			if ($select = mysql_select_db(DBNAME, $link_identifier)) {
			   $queryResultHandle = mysql_query($query, $link_identifier) or die (mysql_error());
				$rows = mysql_affected_rows (); 
				$messageFromResult = "<h1>Update Contact Info</h1><ul>";
				switch( $rows ) {
					case 0:
						$messageFromResult .= "<li>no change to data\n";
						$messageFromResult .= "<li>result is: zero (0) records affected\n";
						$messageFromResult .= "<li>you have already updated this record, or you did not change anything, or the update was unsuccessful";
						break;
					case 1:
						$messageFromResult .= "<li>update successful\n";
						$messageFromResult .= "<li>thank you for keeping your data current\n";
						break;
					default:
						$messageFromResult .= "<li>out of range rows affected in login while $task.\n";
				}
				echo"</ul>\n";
			}else{ // select
	   			echo mysql_error();
			} // select
		}else{ //pconnect
			echo mysql_error();
		} //pconnect
		break;

 

		

	default:
		echo"<blockquote><h1>Not Authorized</h1></blockquote>";
} // switch task
////////////////////////////////////// END OF DYNAMIC PROCESSES
?>



<!-- The following HTML displays what the user sees as results -->
<html>
	<head>
		<script language="Javascript" src="phpcheckout.js"></script>
		<script language="Javascript">loadCSSFile();</script>
		<TITLE>Login Result -  <?php echo IMPLEMENTATIONNAME;?> - <?php echo BENEFIT;?> -  <?php echo ORGANIZATION;?> - Powered by phpcheckoutTM from DreamRiver http://wwww.dreamriver.com - Software Powers the Net - Easily create complete internet programs with php, apache, mysql and windows</TITLE>
		<meta name="Generator" content="Custom Handmade">
		<meta name="Author" content="DreamRiver.com, Richard Creech, http://www.dreamriver.com">
		<meta name="Keywords" content="PHPCHECKOUT, checkout, php, check, out, php checkout, login, shop, shopping, shopper, sales, catalog, online, retail, retailer, download, downloading, make, money, sell, product, products">
		<meta name="Description" content="PHPCHECKOUT lets you create your own php checkout. Ideal for digitally downloaded products or services. Profit from your digital downloads - software, audio, video, images, books ... phpcheckout is a front-to-back solution which lets you market, sell and deliver digital products on your website. Easy to use point and click controls ... and much more ... ">
	</head> 
<body>
<!-- START of body -->
<?php include_once("header.php");?>
<?php if(FPSTATUS == 'Online' ):?>
<!-- start of primary content FOR PAGE -->
<table width="100%">
<tr>
	<?php if(!empty($customerid)): // conditionally show the left loginChooseGoal.php forms ?>
	<td width="28%" valign="top" class="dotted" bordercolor="silver">
		<?php include("loginChooseGoal.php"); // give the logged in user some options of things to do ?>
		<img src="appimage/pixel.gif" width="1" height="400" border=0 alt="">
	</td>
	<?php endif;?>


	<?php if(!empty($customerid)): // conditionally show the results ?>
	<td valign="top">
		<p>
			<?php echo $messageFromResult;?>
		</p>

			<?php if($cookieWasSet == "true"){include("cookiesAbout.php");}?>


			<?php if(!empty($formToInclude)){include("$formToInclude");}?>

	</td>
	<?php else:?>
		<?php 
		echo"<blockquote><h1>Unauthorized</h1>";
		echo"You must register and login to access these pages.</blockquote>";
		exit;
		?>
	<?php endif;?>
</tr>
</table>
		
		<!-- END OF CORE CONTENT !!! -->
		</td>
	</tr>
</table>
<?php else:include('offline.php');endif; // on or offline ?>


</body>
</html>

