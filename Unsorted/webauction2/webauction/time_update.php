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


		    $ends_old = mktime( 
					    substr($ends_old,8,2),     // Stunden
					    substr($ends_old,10,2),    // Minuten
					    substr($ends_old,12,2),    // Sekunden
					    substr($ends_old,4,2),     // Monat
					    substr($ends_old,6,2),     // Tag
					    substr($ends_old,0,4)      // Jahr
				        );


			$new_ends = $ends_old+$duration*24*60*60; 
	        $new_ends = date("YmdHis", $new_ends);
	        



		    $query = "UPDATE ".$dbfix."_auctions SET date='$date_old', ends='$new_ends' where id='$id'";

		
		if (!mysql_query($query))
			print $ERR_001.mysql_error()."$query";
		else
		{
			
			
			
			header ("location:my_active_auctions_newtime.php?SESSION_ID=$SESSION_ID&user_id=$user_id&TPL_nick=$TPL_nick&TPL_password=$TPL_password&name=$name&page=$page");
		}




?>
