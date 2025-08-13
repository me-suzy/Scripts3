<!-- START downloadSwitchVia.php -->
<?php 
// initialize or capture variables
$recipient = !isset($_POST['recipient'])?NULL:$_POST['recipient']; 
$productnumber = !isset($_POST['productnumber'])?NULL:$_POST['productnumber'];
$companyUrl = constant("HOMEPAGE");
// retrieve the item download details and increment the total hit counter for the item			
// pconnect, select and query
if ($link_identifier = mysql_pconnect(DBSERVERHOST, DBUSERNAME, DBPASSWORD)) {
	if ( mysql_select_db(DBNAME, $link_identifier)) {
		// get the current total and anything else we need
		/*
		This file is used with: 
		   a) regular downloads
			b) paid downloads
			c) customer login to retrieve newest version
		In the case of a) and b) the query gets the requested data.
		In the case of c) to be used is the 'Retrieve Newest Version' query 
		because we must also check the purchase table
		*/
		$task = !isset($_POST['task'])?NULL:$_POST['task'];
		$customerid = !isset($_POST['customerid'])?NULL:$_POST['customerid'];
		if($task == "Retrieve Newest Version") {
			$query = "SELECT productname, shortname, url, hits, via, special FROM " . TABLEPURCHASE . "," . TABLEITEMS . " WHERE " . TABLEPURCHASE . ".pnum = " . TABLEITEMS . ".productnumber AND " . TABLEPURCHASE . ".customerid = '$customerid' AND " . TABLEITEMS . ".productnumber=$productnumber LIMIT 1";
		}else{
			$query = "SELECT productname, shortname, url, hits, via, special FROM " . TABLEITEMS . " WHERE productnumber='$productnumber'";
		}
		$queryResultHandle = mysql_query($query, $link_identifier) or die( mysql_error() );
		$rows = mysql_num_rows($queryResultHandle);
		switch($rows) {
			case 1:
				// gather the item data
				$data = mysql_fetch_array($queryResultHandle);
				$productname = stripslashes($data["productname"]);
				$shortname = stripslashes($data["shortname"]); // for temp use in a download filename prefix
				$url = stripslashes($data["url"]);
				$hits = stripslashes($data["hits"]);
				$via = stripslashes($data["via"]);
				$special = stripslashes($data["special"]);
						
				++$hits; // increment the hit counter
				$query = "UPDATE " . TABLEITEMS . " SET hits = $hits WHERE productnumber='$productnumber' ";
		   	$queryResultHandle = mysql_query($query, $link_identifier) or die( mysql_error() );
				break;
			case 0:
				echo"No rows found.";
				exit;
				break;
			default:
				echo"Out of range increment of hit counter.";
		}
	}else{ // select
		echo mysql_error();
	}
}else{ //pconnect
	echo mysql_error();
}
// conditionally copy the source file to a temporary download folder
if($via == "HTTP" || $via == "SMTP Attachment") { // if via == HTTP
	// copy the file to a temp folder
	$sourceFilePath = DOWNLOADFOLDER . "/" . $url;
	$destinationFilePath = "temp" . "/" . $url;
	// make folder if it doesn't exist
	if (!is_dir("temp")) { 
		if ( mkdir ("temp", 0775)) {
			//echo"<br>Made a directory called &quot;temp&quot; and set permissions to 0775 for this folder.";
		}
	}
	if (!copy($sourceFilePath, $destinationFilePath)) {
		echo ("Failed to xfer file for download.<br>Please contact the administrator.<br>\n");
		exit;
	}else{
		chdir("./temp"); // go to the temp folder
		$dotExtension = stristr ( $url, "."); // get the extension
		// rename the file
		$prefix = strtoupper ($shortname);
		$customeridTracker = trim($customerid);
		$tmpName = $prefix .  "[" . $customeridTracker . "]" . md5(uniqid(time())) . $dotExtension;
		rename ( $url, $tmpName);
		$downloadPathFile = "temp/" . $tmpName;
		chdir("../"); // return back to the start folder
		// garbage collection is now automated if you set it up
	}
} // if($via == "HTTP")
?>

<?php echo"<h2>Download by $via</h2>";?>

<?php switch($via) {
/* This offers the item by the method chosen */
	case "SMTP Body":
		$webmaster = TECHNICALSUPPORT;
		$from = "From: " . TECHNICALSUPPORT . "\n"; /* used as the 4th mail() argument */
		$replyTo = "Reply-To: " . TECHNICALSUPPORT . "\n";
		$xMailer = "X-Mailer: PHP/" . phpversion();
		$optionalHeaders = $from . $replyTo . $xMailer;
		$subject = ORGANIZATION . " Delivery: " . $productname;
		$messagebody = "Hello $recipient,
		The item you have requested - $productname - is included in the body of this note, below:

		<!-- Start cut here -->

		$special
				
		<!-- End cut here -->
		
		We invite you to return and try out our other products and services. 
		You may visit us here: 

		$companyUrl

		Yours Sincerely,
		$webmaster";
		if (@mail( $recipient, $subject, $messagebody, $optionalHeaders )) {
			echo"<p>Email sent to $recipient.</p>";
		}else{
			echo"<p>Email NOT sent. Please try again later.</p>";
		}
		break;
		



	case "SMTP Attachment": // for future use
		include_once("mailClass.php");
		chdir("./temp"); // go to the temp folder
		$attachment = fread(fopen($tmpName, "r"), filesize($tmpName)); 
		$mail = new mime_mail(); 
		$mail->from = SYSTEMEMAIL; 
		$mail->headers = "Errors-To: error@dreamriver.com"; 
		$mail->to = $recipient; 
		$mail->subject = "Delivery: $productname"; 
		$mail->body = ""; 
		$mail->add_attachment("$attachment", $tmpName, "txt"); 
		$mail->send();
		chdir("../"); // return back to the phpcheckout folder
		break;

	
	
	case "HTTP":
		echo"<div align='center' style='font-size:32;'><a href=\"" . $downloadPathFile . "\">Click Here to Download<br>' <i>$productname</i> '</a></div><p><br></p>";
		echo"<p><br></p>";
		break;
	case "CRT":
	case "Special":
		echo"<div align='center'><form><textarea cols=60 rows=12 name=specialMessage wrap=physical>$special</textarea></form></div>";
		break;
	default:
		echo"No switch declared.";
}?>
<!-- end downloadSwitchVia.php -->