<?php require_once("configure.php");?>
<?php
// initialize or capture variables
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
?>
<?php 
switch( $task ) {
	case "Register": // insert or update the database
		/*
			This page is called on submit of the RegisterForm.php where a _presumably_
		   new customer completes their contact data. This script inserts that data 
		   into the db. 

		   First this script checks to see if a match exists for the email and password,
			since we don't want any duplicates, and if a match exists then we make an update 
			to the database with the new information, instead of making an insert. 

			This lets an unwitting customer keep their original ID and still let them 
			make changes to the database even in 'Register' mode.

		*/
		// check to see if the password matches the password confirm
		$password = $_REQUEST['password'];
		$confirmPassword = $_REQUEST['confirmPassword'];
		if($confirmPassword != $password) {
			include_once("header.php");
			$password = "";
			echo "<p>Password and confirming password do not match. Please enter them again.</p>";
			include("register.php");
			exit;
		}

	   // check to see if a match for email and password exists
  		$query = "SELECT customerid, visits FROM " . TABLECUSTOMER . " WHERE email='$email' AND password='$password'";
   	// pconnect, select and query
	   if ($link_identifier = mysql_pconnect(DBSERVERHOST, DBUSERNAME, DBPASSWORD)) {
		   if ( mysql_select_db(DBNAME, $link_identifier)) {
			   // run the query
   		   $queryResultHandle = mysql_query($query, $link_identifier) or die( mysql_error() );
			   $rows = mysql_num_rows($queryResultHandle);
				// if a match exists then $rows will be 1 or greater
            switch( $rows ) {
               case 0: // a new registrant! Make the data operation an insert
                  // addslashes for improved data security
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
   		         // $lastupdate is auto set by the mysql database

						// set some useful parameters
		            $customersince = $todaysDate;
		            $visits = 1;
         		   $query = "INSERT INTO " . TABLECUSTOMER . " (password,email,organization,firstname,lastname,address,city,stateprov,country,postalcode,areacode,phone,fax,cellphone,website,customersince,visits,role,privacy,news) VALUES('$password','$email','$organization','$firstname','$lastname','$address','$city','$stateprov','$country','$postalcode','$areacode','$phone','$fax','$cellphone','$website','$customersince','$visits','$role','$privacy','$news')";
                  // run the query
	      			$queryResultHandle = mysql_query($query, $link_identifier) or die( mysql_error() );
		   			$aRows = mysql_affected_rows(); // how many rows affected?
			   		if ($aRows != 1) { // if there was a problem, for example NO insert
				   		echo"Insert failure. Please try again later.";
							exit;
					   }else{
      					// get the insert id
		      			$query = "SELECT LAST_INSERT_ID()";
		   	   	   $queryResultHandle = mysql_query($query, $link_identifier) or die( mysql_error() );
					      $customerid = mysql_result($queryResultHandle,0);
						} // if ($aRows != 1
                  break;

               case 1: // its a returning customer so make an update query instead of an insert
               default: // under testing duplicates have arisen, this switch default handles multiple identical email and passwords
   					// retreive the data
	   				$data = mysql_fetch_array($queryResultHandle);
		   			$customerid = $data["customerid"];
			   		$totalVisits = $data["visits"];
				   	$visits = $totalVisits + 1; // increment the visits counter
                  // addslashes to the data we're about to put into the database
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
   		         $visits = addslashes($visits);
	   	         $role = addslashes($role);
		            $privacy = addslashes($privacy);
		            $news = addslashes($news);
         	   	//lastupdate field value = automatically updated by mysql database
                  if( isset( $customeridCookie )) {$customerid = $customeridCookie;}
		            $query = "UPDATE " . TABLECUSTOMER . " SET password='$password',email='$email',organization='$organization',firstname='$firstname',lastname='$lastname',address='$address',city='$city',stateprov='$stateprov',country='$country',postalcode='$postalcode',areacode='$areacode',phone='$phone',fax='$fax',cellphone='$cellphone',website='$website',role='$role',privacy='$privacy',news='$news' WHERE customerid = $customerid LIMIT 1";
	   			   $queryResultHandle = mysql_query($query, $link_identifier) or die( mysql_error() );
            } // switch ( $rows	
         }else{ // select
			   echo mysql_error();
			}	
		}else{ // pconnect
		   echo mysql_error();
		}
      // so now we've either inserted or updated the basic data for this customer.
							///////////////////////////// START OF COOKIE CODE
                     // conditionally make a new cookie with a cookie lifetime of one (1) year
							if($remember_me == "on") { // if the user selected to be remembered
								// calculate 1 year from today
								$nextYear = mktime (0,0,0,date("m"),date("d"),date("Y")+1);
								$customeridCookie = $customerid; // change the name for clarity
								if ( setcookie("customeridCookie", "$customeridCookie", $nextYear)) {
									$cookieWasSet = "true";
									//echo"<br>new cookie set. customeridCookie is: $customeridCookie<br>";
								} // if the cookie was set
							} // if the user selected to be remembered
							///////////////////////////// END OF COOKIE CODE
// fire off an email with the registration details - good for PR
			
			$messageFromResult = "<h3>Registration Complete</h3>\n";


		break;
	default:
		echo"No task given in register result.";
}?>
<!DOCTYPE html public "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD>

<script language="Javascript" type="text/javascript" src="phpcheckout.js"></script>
<script language="Javascript" type="text/javascript">loadCSSFile();</script>

<!-- unique page title -->
<!-- the page name, function or purpose | IMPLEMENTATIONNAME | BENEFIT | ORGANIZATION | MYKEYWORDS | Powered by phpcheckoutTM from DreamRiver http://wwww.dreamriver.com - Software Powers the Net - Easily create complete internet programs with php, apache, mysql and windows -->

<TITLE>Register Result -  <?php echo IMPLEMENTATIONNAME;?> - <?php echo BENEFIT;?> -  <?php echo ORGANIZATION;?> - Powered by phpcheckoutTM from DreamRiver http://wwww.dreamriver.com - Software Powers the Net - Easily create complete internet programs with php, apache, mysql and windows</TITLE>

<META NAME="keywords" CONTENT="shopping cart, checkout, download, digital, products, php, php3, php4, software, freeware, download, code, source, freeware, downlaod, yellow page, directory, php, lookup, whois">
<META NAME="description" CONTENT="Enter your description here.">
<META NAME="Author" CONTENT="Richard Creech, Web: http://www.dreamriver.com">
<META NAME="GENERATOR" CONTENT="Hard work and years of programming">
<META name="ROBOTS" content="INDEX,FOLLOW">
</HEAD>
<BODY>
<!-- START of Page -->
<TABLE width="100%" align="center">
	<TR>
		<TD>
			<?php include("header.php");?>
			<?php if(FPSTATUS == 'Online' ):?>

			<h1>Register Result</h1>

			<?php echo "<p>$messageFromResult</p>";?>

			<?php include("loadLoginModuleForm.php");?>



		<!-- END OF CORE CONTENT !!! -->
		</td>
	</tr>
</table>
<?php else:include('offline.php');endif; // on or offline ?>


</body>
</html>
