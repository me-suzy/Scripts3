<?
   include( "../config.php" );
   include( "../usersession.inc" );
   UpdateUserSession();
   include( "../dblink.inc" );
   include( "../AdExpiration.inc" );

	// Check and make sure that the ad is valid for the user

	// Make sure that there has been no bids for the ad

	$query = "SELECT BidID FROM Bids WHERE AdID=$AdID";
	$NumBidsResult = mysql_query( $query, $link );		
	if ( mysql_num_rows($NumBidsResult) != 0 )	
	{
$BeginHeader = <<<BEGINHEADER
<html>
<head>
<title>Invalid Ad</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
BEGINHEADER;

	print( "$BeginHeader\n" );

	ValidateLoginRedirect();

$EndHeader = <<<ENDHEADER
</head>
<body bgcolor="#FFFFFF">
<p>&nbsp;</p>
<p><font color="#FF0033">The ad has bids and cannot be modified.</font></p>
</body>
</html>
ENDHEADER;

		print( "$EndHeader\n" );
		exit;				
	}
	
	$UserAcctID = GetSessionUserID();		

	$query = "SELECT AdID FROM Ads WHERE UserAcctID=$UserAcctID AND AdID=$AdID";
	$result = mysql_query( $query, $link );		

	if ( !mysql_fetch_row( $result ) )
	{	
		// Invalid ad -- the Ad ID doesn't exist for the user or at all

$ErrorMsg = <<<ENDERROR
<html>
<head>
<title>Invalid Ad</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<?
	ValidateLoginRedirect();
?>
</head>
<body bgcolor="#FFFFFF">
<p>&nbsp;</p>
<p><font color="#FF0033">The ad to modify is invalid. Go back to <a href="MyAccount.php">My 
  Account</a> and select the ad to modify from there.</font></p>
</body>
</html>
ENDERROR;

		print( "$ErrorMsg\n" );
		exit;				
	}

?>

<html>
<head>
<title>Modify Auction Ad</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
<META HTTP-EQUIV="Expires" CONTENT="-1">
<?
	ValidateLoginRedirect();
?>
</head>
<body bgcolor="#FFFFFF">
<?
	include( "../header.inc" );
?>
<p>&nbsp;</p>
<?
  if ($action == "disable")
	{
			// Set the EndDate to the time right now

      			$TimeNow = time();

			$IP = getenv("REMOTE_ADDR"); 

			$query = "UPDATE AuctionAds SET EndDate=$TimeNow, ModifiedIPAddress='$IP' WHERE AdID=$AdID";
			$result = mysql_query( $query, $link );		
		
			if ( !$result )
			{
				print("<h3><font color=\"#FF0033\">Error executing disable ad query.</font></h3></body></html>\n");    
				echo "<br>\n";
				echo mysql_error();		
				exit;
			}

$Confirmation=<<<HTML_CONFIRMATION
<h2>Ad Disable Confirmation</h2>
<p><font face="Arial, Helvetica, sans-serif" size="3" color="#006600"><b>Your 
  ad has been successfully disabled.</b></font></p>
<p><font face="Arial, Helvetica, sans-serif" size="3"><a href="ViewMyAds.php">Click 
  here to go back to the &quot;View My Ads&quot; section.</a></font></p>
HTML_CONFIRMATION;

			echo $Confirmation;
	}
	else if ($action == "delete")
	{
			// TODO: Currently no way to log an IPAddress ad deletion since the ad entry is deleted.
			// Need to create a separate logging table.

			$query = "DELETE FROM AuctionAds WHERE AdID=$AdID";
			$result = mysql_query( $query, $link );		
		
			if ( !$result )
			{
				print("<h3><font color=\"#FF0033\">Error executing delete ad query.</font></h3></body></html>\n");    
				echo "<br>\n";
				echo mysql_error();		
				exit;
			}

			// Delete the corresponding bids also

			$query = "DELETE FROM Bids WHERE AdID=$AdID";
			$result = mysql_query( $query, $link );		

			if ( !$result )
			{
				print("<h3><font color=\"#FF0033\">Error executing delete bids for ad query.</font></h3></body></html>\n");    
				echo "<br>\n";
				echo mysql_error();		
				exit;
			}

$Confirmation=<<<HTML_CONFIRMATION
<h2>Ad Deletion Confirmation</h2>
<p><font face="Arial, Helvetica, sans-serif" size="3" color="#006600"><b>Your 
  ad has been successfully deleted.</b></font></p>
<p><font face="Arial, Helvetica, sans-serif" size="3"><a href="ViewMyAds.php">Click 
  here to go back to the &quot;View My Ads&quot; section.</a></font></p>
HTML_CONFIRMATION;

			echo $Confirmation;
	}
	else if ($action == "renew")
	{
	        $TimeNow = time();
			$EndTime = $TimeNow + $AuctionAdExpiration;
		
			$IP = getenv("REMOTE_ADDR"); 

			$query = "UPDATE AuctionAds SET BeginDate=$TimeNow, EndDate=$EndTime, ModifiedIPAddress='$IP' WHERE AdID=$AdID";
			$result = mysql_query( $query, $link );		
		
			if ( !$result )
			{
				print("<h3><font color=\"#FF0033\">Error executing renew ad query.</font></h3></body></html>\n");    
				echo "<br>\n";
				echo mysql_error();		
				exit;
			}

$Confirmation=<<<HTML_CONFIRMATION
<h2>Ad Renew Confirmation</h2>
<p><font face="Arial, Helvetica, sans-serif" size="3" color="#006600"><b>Your 
  ad has been successfully renewed.</b></font></p>
<p><font face="Arial, Helvetica, sans-serif" size="3"><a href="ViewMyAds.php">Click 
  here to go back to the &quot;View My Ads&quot; section.</a></font></p>
HTML_CONFIRMATION;

			echo $Confirmation;

	}


?> 

<?
	include( "../footer.inc" );
?>
</body>
</html>
