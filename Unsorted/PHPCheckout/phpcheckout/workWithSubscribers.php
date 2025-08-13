<?php /* this page is not available. It is under construction. */?>
<?php require_once("adminOnly.php");?>
<?php require_once("functions.php");?>
<script language="JavaScript">
<!--
/*
This function launches a temporary popup window which displays the administrative results. 
The window is a reporting tool. The messages it shows are temporary. Therefore, the popup 
has a timeout which you may reset in configure.php to force the popup to close. No point in having 
all these windows open. Choose to close them all after POPUPTIMEOUT milliseconds in configure.php
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
<?php 
// count the number of newsletter subscribers
$query = "SELECT COUNT(customerid) AS 'SUBSCRIBERS' FROM " . TABLECUSTOMER . " WHERE news='yes'";
		if ($link_identifier = mysql_pconnect(DBSERVERHOST, DBUSERNAME, DBPASSWORD)) {
			if ( mysql_select_db(DBNAME, $link_identifier)) {
				// run the query
		   	$queryResultHandle = mysql_query($query, $link_identifier) or die( mysql_error() );
				$data = mysql_fetch_array($queryResultHandle);
				$SUBSCRIBERS = $data["SUBSCRIBERS"];

				// get a count for new subscribers
				$query = "SELECT COUNT(lastupdate) AS 'newSubscribers' FROM " . TABLECUSTOMER . " WHERE TO_DAYS(NOW()) - TO_DAYS(lastupdate) <= 1 AND news = 'yes'";
				$queryResultHandle = mysql_query($query, $link_identifier) or die( mysql_error() );
				$data = mysql_fetch_array($queryResultHandle);
				$newSubscribers = $data["newSubscribers"];
			}else{ // select
				echo mysql_error();
			}
		}else{ //pconnect
			echo mysql_error();
		}
?>


<?php drawHeader();?>


<blockquote>

<h1>Work with <?php echo "$SUBSCRIBERS Newsletter Subscribers";?></h1>
<h2><?php echo $newSubscribers;?> New Subscribers</h2>
<p>There have been <?php echo $newSubscribers . " new subscribers in the last 24 hours.</p>";?>
<?php $task = $_POST['task'];?>
<?php include("navSubscriber.php");?>

<?php 
// get a count for new subscribers
// calculate an expiry yesterday 
$oneDayAgoExpiry = mktime (0,0,0,date("m"),date("d") - 1 ,date("Y"));
$query = "SELECT COUNT(news) AS 'newSubscribers' FROM " . TABLECUSTOMER . " WHERE news='yes' AND lastupdate > $oneDayAgoExpiry";
?>

	<p><br></p>

	
	
	<?php 
	switch($task) {
		case "Subscribe":
		case "Unsubscribe":
			include("unsubscribeForm.php");
			break;



		case "Launch Newsletter Mailer":
		   echo "<h2>$task</h2>";
			/* this script is a mailer - it sends an email notification to the selected recipients
				this mailer is launched from a form. The form contains a subject line and text of the note.  
				The note will also contain an unsubscribe link. When the form is submitted the script below runs...
			*/

			// run this block only if in live mode
			if ( $mode == "Live" ) {
   // first we build the list of recipients
$query = "SELECT email FROM " . TABLECUSTOMER . " WHERE news='yes' ORDER BY email ASC";
// pconnect, select and query
if ($link_identifier = mysql_pconnect(DBSERVERHOST, DBUSERNAME, DBPASSWORD)) {
	if ( mysql_select_db(DBNAME, $link_identifier)) {
		// run the query
  		$queryResultHandle = mysql_query($query, $link_identifier) or die( mysql_error() );
		$rows = mysql_num_rows($queryResultHandle); // get # of rows
		if ( $rows > 0 ) {  // if rows are found
			while( $data = mysql_fetch_array($queryResultHandle)) {
				// this presumes you have added slashes on insert or update, will run anyway...
   		    $email = stripslashes($data["email"]);
				// actually send an email note
				$recipient = $email;
				$from = "From: " . TECHNICALSUPPORT . "\n"; /* used as the 4th mail() argument */
				$replyTo = "Reply-To: " . TECHNICALSUPPORT . "\n";
				$xMailer = "X-Mailer: PHP/" . phpversion();
				$optionalHeaders = $from . $replyTo . $xMailer;
				// $subject is passed from the start form
				// $comments are passed from the start form

				// build the $unsubscribeLink
				// either use constant INSTALLPATH from configure.php or add your own string
				$myInstallPath = INSTALLPATH;
				$unsubscribeLink = "\n\nTo unsubscribe click here:\n" . $myInstallPath . "unsubscribe.php?email=$recipient";
            // join comments with unsubscribe
				$messagebody = $comments . $unsubscribeLink;

				if( mail( $recipient, $subject, $messagebody, $optionalHeaders )) {
					//put your logic here
					echo"Note sent to $recipient<br>";
				}
			} //while
		}else{
			echo"No rows were found.";
		} //if ( $rows == 1 )
	}else{ // select
		echo mysql_error();
	}
}else{ //pconnect
	echo mysql_error();
}

} // close of the if ( $mode == "Live" ) block

// send a note to technical support

	// actually send an email note
	$recipient = $extraRecipient;
	$from = "From: " . TECHNICALSUPPORT . "\n"; /* used as the 4th mail() argument */
	$replyTo = "Reply-To: " . TECHNICALSUPPORT . "\n";
	$xMailer = "X-Mailer: PHP/" . phpversion();
	$optionalHeaders = $from . $replyTo . $xMailer;
	// $subject is passed from the start form
	// $comments are passed from the start form
	// build the $unsubscribeLink
	// either use constant INSTALLPATH from util.php or add your own string
	$myInstallPath = INSTALLPATH;
	$unsubscribeLink = "\n\nTo unsubscribe click here:\n" . $myInstallPath . "unsubscribe.php?email=$recipient";
   // join comments with unsubscribe
	$messagebody = $comments . $unsubscribeLink;
	if( mail( $recipient, $subject, $messagebody, $optionalHeaders )) {
		//put your logic here
		echo"Note sent to $recipient<br>";
	}else{
		echo"<span style=\"color:red;font-weight:bold;\">A note was NOT sent to $recipient</span><br>";   
   }
// end of send to technical support



      echo"<br><br>SCRIPT FINISHED - END OF DOCUMENT";
      break;



	default:
		echo"<h3>Welcome to the Subscribers Module</h3>";
} // end switch $task
?>


</blockquote>



</body>
</html>