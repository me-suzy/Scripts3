<?php $goal = !isset($_GET["goal"])?$_POST["goal"]:$_GET["goal"]; // initialize or capture ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
	<HEAD>
		<?php require_once("configure.php");?>
      <script language="Javascript" src="phpcheckout.js"></script>
      <script language="Javascript">loadCSSFile();</script>
		<title>Newsletter - <?php echo IMPLEMENTATIONNAME;?> - <?php echo BENEFIT;?> - phpcheckoutTM - php checkout software from DreamRiver.com - Contact Page - Profit from your own digital downloads with PHPCHECKOUT. - easily create your own php checkout for any digital product - Profit from your digital downloads - software, audio, video, images, books ... phpcheckout is a front-to-back solution which lets you market, sell and deliver digital products on your website. Easy to use point and click controls ... and much more ... php software mysql database - download - web database - dreamriver - freeware - sql - file downloader - internet application development - hot scripts - good value - database software - custom code - php e-mail software - ultimate web apps software - phpyellow Pages - EasySQL - phpTellAFriend - mysql php download phpyellow.com dreamriver.com phpcheckout.com</title>
		<meta name="Generator" content="Custom Handmade">
		<meta name="Author" content="DreamRiver.com, Richard Creech, http://www.dreamriver.com">
		<meta name="Keywords" content="PHPCHECKOUT, checkout, php, check, out, php checkout, contact, shop, shopping, shopper, sales, catalog, online, retail, retailer, download, downloading, make, money, sell, product, products">
		<meta name="Description" content="Contact DreamRiver.com about PHPCHECKOUT by visiting this page. Select one of many communication methods including phone, stealth email or snail mail options. Profit from your own digital downloads with PHPCHECKOUT.">
</HEAD> 

<body>
<!-- START of body -->


<?php include("header.php");?>


<?php if(FPSTATUS == 'Online' ):?>
<table width="100%">
	<!-- start of primary content FOR PAGE -->
	<tr>



		<!-- start MAIN COLUMN -->
		<td valign="top">
		<!-- PUT CONTENT RIGHT HERE !!! -->
		

		<blockquote>	

<?php
// initialize or capture variables
$goal = !isset($goal)?"Subscribe":$_POST['goal'];
$email = !isset($email)?$_REQUEST['email']:$_POST['email'];
?>


			<?php if(OFFERNEWSLETTER == "yes"):?>



<?php 
// this script handles all newsletter subscribes and unsubscribes

 echo"<h1>$goal Result</h1>";


// see if the email is any good, if not then throw it out
if (empty($email)) {
	echo"<b style=\"color:red;\">Please provide a valid email address!</b><br>";
	include("newsForm.php");
	exit;
}
//validateEmail - from the php developer's cookbook but never 100% foolproof
if (!eregi ("^([a-z0-9_]|\\-|\\.)+@(([a-z0-9_]|\\-)+\\.)+[a-z]{2,4}$", $email)) {
	echo"<b style=\"color:red;\">Please provide a valid email address</b><br>";
	include("newsForm.php");
	exit;
}

switch($goal) {
	case"Subscribe":
		/* this module subscribes an email address to the newsletter
			by writing a value for the 'news' column in the TABLECUSTOMER table
		*/
		// does the email address exist in the database?
		$query = "SELECT email FROM " . TABLECUSTOMER . " WHERE email = '$email'";		
		// pconnect, select and query
		if ($link_identifier = mysql_pconnect(DBSERVERHOST, DBUSERNAME, DBPASSWORD)) {
			if ( mysql_select_db(DBNAME, $link_identifier)) {
				// run the query
   			$queryResultHandle = mysql_query($query, $link_identifier) or die( mysql_error() );
				$rows = mysql_num_rows($queryResultHandle);
				switch( $rows ) {
					case 0:
						// do a db insert if no email exists
						$query = "INSERT INTO " . TABLECUSTOMER . "(email, news)VALUES('$email', 'yes')";
   					$queryResultHandleInsert = mysql_query($query, $link_identifier) or die( mysql_error() );
						$insertedRows = mysql_affected_rows();						
						if($insertedRows == 1 ) {
							echo"<div style=\"font-size:14px;\">Subscribe SUCCESSFUL for <b>$email</b>\n";
							echo"<h3>Thank you for your interest in " . ORGANIZATION . ".</h3>\n";
						}
						break;
					case 1:
					default:
						// if an email address exists in the db then do a db update
						$query = "UPDATE " . TABLECUSTOMER . " SET news='yes' WHERE email = '$email'";
   					$queryResultHandleUpdate = mysql_query($query, $link_identifier) or die( mysql_error() );
						$updatedRows = mysql_affected_rows();						
						if($updatedRows == 1 ) {
							echo"<p>Subscribe SUCCESSFUL for <b>$email</b>\n";
							echo"<h3>Thank you for your interest in " . ORGANIZATION . "</h3>\n";
						}else{
							echo"<p><b>$email</b> is already SUBSCRIBED.</p>\n";
							include("newsForm.php");
						}
				}

				// send them the subscription bonus of an Article
				$recipient = $email;
				$from = "From: " . SYSTEMEMAIL . "\n"; /* used as the 4th mail() argument */
				$replyTo = "Reply-To: " . SYSTEMEMAIL . "\n";
				$xMailer = "X-Mailer: PHP/" . phpversion();
				$optionalHeaders = $from . $replyTo . $xMailer;
				$subject = "Bonus Subscription Article";
				$messagebody = "Don't let your web site embarrass you
by Tim Hicks, TRH Communications 

Robert Browning wrote: 
' ...a man's reach should exceed his grasp 
Or what's a heaven for?'

I'll bet he would have made a lousy painter or window-washer - or Web site builder. 

A Web site is like a puppy. It starts with enthusiasm, but if you don't give it regular care and attention it will embarrass you later. 

Your site's size and complexity should be constrained by your ability to maintain its accuracy, timeliness and freshness. 

Some short-lived features may be beyond your ability to replace them frequently. So don't build them in the first place. It's better to have fewer pages than to have stale ones. 

If you have a good programmer on your Web team, you can put in some simple automated content management tools. 

Put a hidden comment in each page that indicates when it should be updated next, then regularly run a program that scans your pages looking for those that have reached their 'best-before' date. Change that best-before date every time you update its page. Different pages will have different 'shelf lives.' 

The grocery store analogy is appropriate. Some of what's on a grocery store's shelves will keep indefinitely, but a month-old newspaper or cabbage won't do much for the ambience. So appoint a stockperson today to make sure everything is fresh every day. 

© TRH Communications, Victoria, B.C. http://www.trh.bc.ca
";
				if (@mail( $recipient, $subject, $messagebody, $optionalHeaders )) {
					echo"<h3>Bonus Subscription Article sent to $recipient</h3>";
				}


			}else{ // select
				echo mysql_error();
			}
		}else{ //pconnect
			echo mysql_error();
		}
		break;

	
	
	
	case"Unsubscribe":
		/*
		This module unsubscribes the email address entered by the user.
		The user could also click on this embedded url:
			http://www.yourdomain.com/phpcheckout/unsubscribe.php?email=value
		For example, you could send out an email and embed the customer email in this unsubscribe link
		by way of a database loop. 
		The code below handles these two possible POST and GET unsubscribe requests
		*/
		$query = "UPDATE " . TABLECUSTOMER . " SET news='no' WHERE email = '$email'";
		// pconnect, select and query
		if ($link_identifier = mysql_pconnect(DBSERVERHOST, DBUSERNAME, DBPASSWORD)) {
			if ( mysql_select_db(DBNAME, $link_identifier)) {
				// run the query
   			$queryResultHandle = mysql_query($query, $link_identifier) or die( mysql_error() );
				$rows = mysql_affected_rows($link_identifier);
				// report the outcome to the user by displaying a page		
				switch( $rows ) {
					case 0:
						//echo"Zero ( 0 ) rows were affected by your unsubscribe request for <b>$email</b>\n";
						echo"<p>Your email address of $email is either: <ol><li>already UNSUBSCRIBED or <li>was never subscribed</ul></p>\n";
						include("newsForm.php");
						break;
					case 1:
					default:
						echo"<p>Unsubscribe SUCCESSFUL for <b>$email</b> \n"; // the note uses $email as the recipient
						echo"... but we're sorry to see you go! <br>Please <a href=\"contact.php\">contact us</a> if there is something we can help with.</p>";
						include("newsForm.php");
				}
			}else{ // select
				echo mysql_error();
			}
		}else{ //pconnect
			echo mysql_error();
		}		       
		break;

	default:
		echo"No goal exists.";
}?>





			<?php else:?>
			
				<P>The newsletter is not offered at this time.</P>

			<?php endif;?>

		
		<!-- END OF CORE CONTENT !!! -->
		</td>
	</tr>
</table>
<?php else:include('offline.php');endif; // on or offline ?>

<p><br></p>



</blockquote>	

</body>
</html>