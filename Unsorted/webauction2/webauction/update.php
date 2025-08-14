<?
/*
   Copyright (c), 1999, 2000 - phpauction.org
   Copyright (c), 2001, 2002 - webauction.de.vu

   Lizenz siehe lizenz.txt & license

   Die neueste Version kostenfrei zum Download unter:
   http://webauction.de.vu

*/
	
	include("includes/config.inc.php");
	
	$result = mysql_query("SELECT * FROM ".$dbfix."_auctions");
		if ($result) {
			if (mysql_num_rows($result)==0)
				return false;
		} else 
			return false;

		// get info about this auction
		$Auction = mysql_fetch_array($result);
		$start = doubleval($Auction["date"]);
        $end = doubleval($Auction["ends"]);

$sql= "update ".$dbfix."_auctions set date='$start' and ends='$end' and art='privat'";

	?>
