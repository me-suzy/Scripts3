<?
   include( "../config.php" );
   include( "../usersession.inc" );
   UpdateUserSession();
   include( "../dblink.inc" );
   include( "../AdExpiration.inc" );
?>

<html>
<head>
<title>New Auction Ad</title>
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
<META HTTP-EQUIV="Expires" CONTENT="-1">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<?
	ValidateLoginRedirect();
?>
</head>

<body bgcolor="#FFFFFF">
<?
	include( "../header.inc" );
?>
<?
	if ( isset($Title) && ($Quantity != "") && 
		 isset($StartPrice) && isset($ReservePrice) &&
		 isset($Description) && (strcmp($Category, "None") != 0) )
	{
		if ( strlen($Title) > 255 )
		{
			print("<h3><font color=\"#FF0033\">The title is too long.  Go back and shorten the title, and then try again.</font></h3></body></html>\n");    
			exit;
		}

		$Title = addslashes($Title);
		$Quantity = addslashes($Quantity);
		$StartPrice = addslashes($StartPrice);
		$ReservePrice = addslashes($ReservePrice);
		$Description = addslashes($Description);
				
		$UserAcctID = GetSessionUserID();
		
		// check to make sure that the same ad isn't being submitted more than once

		$query = "SELECT * FROM AuctionAds WHERE UserAcctID=$UserAcctID AND CatID=$Category AND ";
		$query .= "Title='$Title' AND Description='$Description' AND Quantity='$Quantity' AND ";
		$query .= "StartPrice='$StartPrice' AND ReservePrice='$ReservePrice'";

		$result = mysql_query( $query, $link );		

		if ( mysql_numrows($result) != 0 )
		{
			print("<h3><font color=\"#FF0033\">Error: This ad has already been submitted.</font></h3></body></html>\n");    
			exit;
		}

		// insert the ad

		$TimeNow = time();
		$EndTime = time() + $AuctionAdExpiration;

		$IP = getenv("REMOTE_ADDR"); 

		$query = "INSERT INTO AuctionAds (UserAcctID, CatID, Title, Description, StartPrice, ReservePrice, Quantity, InitialAdDate, BeginDate, EndDate, CreatedIPAddress, ModifiedIPAddress) ";
		$query .= "VALUES ($UserAcctID, $Category, '$Title', '$Description', '$StartPrice', '$ReservePrice', '$Quantity', $TimeNow, $TimeNow, $EndTime, '$IP', '$IP')";
		$result = mysql_query( $query, $link );		
		
		if ( !$result )
		{
			print("<h3><font color=\"#FF0033\">Error executing insert ad query.</font></h3></body></html>\n");    
			exit;
		}
	}
?>

<blockquote>
<h2>Ad Submission Confirmation</h2>
  <p><font face="Arial, Helvetica, sans-serif" size="3" color="#006600"><b>Thank 
    you for your submission. Your ad has been receieved.</b></font></p>
  <p><font face="Arial, Helvetica, sans-serif" size="3"><a href="MyAccount.php">Click 
    here to go back to My Account.</a></font></p>
</blockquote>
<?
	include( "../footer.inc" );
?>
</body>
</html>
