<?php

/*
   Copyright (c), 1999, 2000 - phpauction.org
   Copyright (c), 2001, 2002 - webauction.de.vu

   Lizenz siehe lizenz.txt & license

   Die neueste Version kostenfrei zum Download unter:
   http://webauction.de.vu

*/



	require('./includes/messages.inc.php');
	require('./includes/config.inc.php');
        require('header.php');




//Update Database



            $query = "SELECT * FROM ".$dbfix."_auctions WHERE id='$id'";
            $result = mysql_query($query);
            $description_old = mysql_result ( $result, 0, "description");
			$ends_old = mysql_result ( $result, 0, "ends");
			$date_old = mysql_result ( $result, 0, "date");
	    	$now_date = date ("d.m.Y");  
		    $now_time = date ("H:i");
			
		    $query = 
			"UPDATE ".$dbfix."_auctions SET date='$date_old', ends='$ends_old', description='$description_old<br><br><b>Ergänzung vom $now_date um $now_time Uhr:</b><br><br>$description' WHERE id='$id'";



		
		if (!mysql_query($query))
			print $ERR_001.mysql_error()."<BR>$query";
		else
		{
			
			
			include "templates/sell_update_result_php3.html";
		}

		include "footer.php";


?>
