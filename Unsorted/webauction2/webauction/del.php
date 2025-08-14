<?php

/*
   Copyright (c), 1999, 2000 - phpauction.org
   Copyright (c), 2001, 2002 - webauction.de.vu

   Lizenz siehe lizenz.txt & license

   Die neueste Version kostenfrei zum Download unter:
   http://webauction.de.vu

*/



	require('includes/messages.inc.php');
	require('includes/config.inc.php');





            $query = "SELECT date,ends FROM ".$dbfix."_auctions WHERE id='$id'";
            $result = mysql_query($query);
			$date_old = mysql_result ( $result, 0, "date");
            $ends_old = mysql_result($result,0,"ends");

		    $query = "UPDATE ".$dbfix."_auctions SET date='$date_old', ends='$ends_old', closed='del' where id='$id'";

		
		if (!mysql_query($query))
			print $ERR_001.mysql_error()."$query";
		else
		{
			
			
			
			header ("location:my_closed_auctions.php?SESSION_ID=$SESSION_ID&user_id=$user_id&TPL_nick=$TPL_nick&TPL_password=$TPL_password&name=$name&page=$page");
		}




?>
