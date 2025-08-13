<html>
<head>
<title>Expired List</title>
</head>
<body>
<?
   include( "../config.php" );
   include( "../dblink.inc" );
			
	$TimeNow = getdate( time() );

	$BeginToday = mktime( 0, 0, 0, $TimeNow["mon"], $TimeNow["mday"], $TimeNow["year"] );
	$EndToday   = mktime( 23, 59, 59, $TimeNow["mon"], $TimeNow["mday"], $TimeNow["year"] );

	$query  = "SELECT AdID, Title, Email, MemberID, First, Middle, Last FROM Ads, UserAccounts ";
	$query .= "WHERE ($BeginToday <= EndDate) AND (EndDate <= $EndToday) AND Ads.UserAcctID=UserAccounts.UserAccountID";
	$result = mysql_query( $query, $link );		

	echo mysql_error();

	while ( $row = mysql_fetch_row( $result ) )
	{
		print( "$row[0], $row[1], $row[2], $row[3], $row[4], $row[5], $row[6]<br>\n" );
	}		

?>
</body>
</html>
