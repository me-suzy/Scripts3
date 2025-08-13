<html>
<head>
<title>Ad List</title>
</head>
<body>
<?
   include( "../config.php" );
   include( "../dblink.inc" );
			
	echo "<h3>Classified Ads</h3>\n";

	$query  = "SELECT AdID, Title, Price FROM Ads ORDER BY AdID";
	$result = mysql_query( $query, $link );		

	echo mysql_error();

	while ( $row = mysql_fetch_row( $result ) )
	{
		print( "$row[0], $row[1], $row[2]<br>\n" );
	}		

	echo "<h3>Auction Ads</h3>\n";

	$query  = "SELECT AdID, Title, StartPrice, ReservePrice FROM AuctionAds ORDER BY AdID";
	$result = mysql_query( $query, $link );		

	echo mysql_error();

	while ( $row = mysql_fetch_row( $result ) )
	{
		print( "$row[0], $row[1], $row[2]<br>\n" );
	}		

?>
</body>
</html>
