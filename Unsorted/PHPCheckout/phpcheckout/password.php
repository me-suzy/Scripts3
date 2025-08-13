<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
	<HEAD>
		<?php include_once("configure.php");?>
      <script language="Javascript" src="phpcheckout.js"></script>
      <script language="Javascript">loadCSSFile();</script>
		<TITLE>Password -  <?php echo IMPLEMENTATIONNAME;?> - <?php echo BENEFIT;?> -  <?php echo ORGANIZATION;?> - Powered by phpcheckoutTM from DreamRiver http://wwww.dreamriver.com - Software Powers the Net - a DreamRiver Internet Software Product - php software mysql database - download - web database - dreamriver - freeware - sql - file downloader - internet application development - hot scripts - good value - database software - custom code - php e-mail software - ultimate web apps software - phpYellow Pages - EasySQL - phpTellAFriend - mysql php download - Easily create complete internet programs with php, apache, mysql and windows</TITLE>
	</HEAD> 

<body>
<!-- START of body -->


<?php include("header.php");?>



<BODY>

<blockquote>


<h1>Look Up Password</h1>


<?php if(empty($loginemail)):?>
	<p>Forgot your password? Fill in your email address and your password will be emailed to you.
	<br><br>
	<!-- Start Password Form -->
	<br>
	<form method="post" name="passwordForm" action="password.php">
		<input type="hidden" name="goal" value="Look Up Password">
		<table class="favcolor">
			<tr>
				<th colspan=2>Look Up Password</th>
			</tr>
			<tr>
				<td>Your Email Address</td>
				<td><input type="text" name="loginemail" size="25" maxlength="35"></td>
			</tr>
			<tr>
				<td colspan=2>
					<input type="submit" name="submit" value="Email My Password To Me" class="submit">
				</td>
			</tr>
</table>
</form>
</p>
<!-- End LogIn Form-->


			

<?php else:
// formulate the query
$query = "SELECT email, password FROM " . TABLECUSTOMER . " WHERE email = '$loginemail'";
// pconnect, select and query
if ($link_identifier = mysql_pconnect(DBSERVERHOST, DBUSERNAME, DBPASSWORD)) {
	if ( mysql_select_db(DBNAME, $link_identifier)) {
		// run the query
   	$queryResultHandle = mysql_query($query, $link_identifier) or die( mysql_error() );
		$rows = mysql_num_rows($queryResultHandle); // get # of rows
		if ( $rows < 1 ) {  // if no rows are found
    		echo "<br><br>Sorry, no password was found for <a href='mailto:$loginemail'>$loginemail</a>.";
		}else{ //if ( $rows < 1 )
			// if there are rows then show the table headings <th> and get the results
			echo("<p><table>\n");
			echo("<tr><th>Email</th><th>Password</th></tr>\n");
			for ($i = 0; $i < $rows; $i++) {
				$data = mysql_fetch_array($queryResultHandle);
				$emaildata = stripslashes($data["email"]);
			   $password = stripslashes($data["password"]);    
	    		$passworddata = "Found it!"; // for security reasons we won't display the password on the screen
			   echo("<tr><td>$emaildata</td><td>$passworddata</td></tr>\n");
			}
			echo("</table></p>\n");


			// email the password to the user
			if( DBSERVERHOST != "localhost" ) { // do not send if running on localhost, send only from a domain
				// convert CONSTANTS to variables for use in email note
				$installpath = INSTALLPATH; // to embed this data in the email note
				$loginPage = "login.php";
				$absoluteUrlLoginPage = $installpath . $loginPage;
				$company = COMPANY;
				$technicalSupport = TECHNICALSUPPORT;
				$implementationName = IMPLEMENTATIONNAME;
				$recipient = $emaildata;
				$from = "From: " . SYSTEMEMAIL . "\n"; /* used as the 4th mail() argument */
				$replyTo = "Reply-To: " . TECHNICALSUPPORT . "\n";
				$xMailer = "X-Mailer: PHP/" . phpversion();
				$optionalHeaders = $from . $replyTo . $xMailer;
				$subject = "You Requested your Password on " . IMPLEMENTATIONNAME;
				$messagebody = "Hello $emaildata,
You are receiving this note because your email address was entered in our 
$implementationName Password Lookup.

Your password is: $password
Your email is: $emaildata

You may login to $implementationName here:
$absoluteUrlLoginPage

Regards,

Technical Support
$technicalSupport
$company";
				if ( mail( $recipient, $subject, $messagebody, $optionalHeaders )) {
					echo "<p>Your password has been emailed and you will receive it shortly.</p>";
				}else{
					echo"<br>Email failed.";
				}
			} // if( DBSERVERHOST != "localhost"
		} //if ( $rows < 1 )
	}else{ // select
		echo mysql_error();
	} // select
}else{ //pconnect
	echo mysql_error();
} // pconnect
endif;?>


<p><br></p>



</blockquote>



</body>
</html>
