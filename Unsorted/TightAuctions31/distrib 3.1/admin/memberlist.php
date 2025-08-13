<html>
<head>
<title>Member List</title>
</head>
<body>
<?
   include( "../config.php" );
   include( "../dblink.inc" );
	
	$query  = "SELECT DateRegistered, Email, MemberID, Password, First, Middle, Last, CreatedIPAddress, ModifiedIPAddress FROM UserAccounts";
	$result = mysql_query( $query, $link );		

	echo mysql_error();

	while ( $row = mysql_fetch_row( $result ) )
	{
		$DateReg = date( "D, M j, Y  h:i A", $row[0] );

		print( "$DateReg, $row[1], $row[2], $row[3], $row[4], $row[5], $row[6], $row[7], $row[8]<br>\n" );
	}		

?>
</body>
</html>
