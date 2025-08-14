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


		    $query = "DELETE FROM ".$dbfix."_views WHERE id='$id'";

		
		if (!mysql_query($query))
			print $ERR_001.mysql_error()."$query";
		else
		{
			

			
			header ("location:my_view_list.php?id=$id&SESSION_ID=$SESSION_ID&user_id=$user_id&TPL_nick=$TPL_nick&TPL_password=$TPL_password&name=$name");
		}




?>
