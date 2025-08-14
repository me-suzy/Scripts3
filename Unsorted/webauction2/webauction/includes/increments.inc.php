<?php
/*
   Copyright (c), 1999, 2000 - phpauction.org
   Copyright (c), 2001, 2002 - webauction.de.vu

   Lizenz siehe lizenz.txt & license

   Die neueste Version kostenfrei zum Download unter:
   http://webauction.de.vu

*/


	$increments_hash = array (
	    1 => 1,
	    2 => 5,
	    3 => 8,
	    4 => 10,
	    5 => 25,
		6 => 50,
		7 => 100,
		8 => 200
	);



	
	function getincrement($value)
	{
		global $increments_hash, $inc1, $von1, $bis1;

		if ( ($value>0) && ($value<=9.99) )
			return $increments_hash[1];
		elseif ( ($value>9.99) && ($value<=99.99 ) )
			return $increments_hash[2];
		elseif ( ($value>99.99) && ($value<=299.99 ) )
			return $increments_hash[3];
		elseif ( ($value>299.99) && ($value<=599.99) )
			return $increments_hash[4];
		elseif ( ($value>599.99) && ($value<=999.99) )
			return $increments_hash[5];
		elseif ( ($value>999.99) && ($value<=4999.99) )
			return $increments_hash[6];
		elseif ( ($value>4999.99) && ($value<=29999.99) )
			return $increments_hash[7];
		elseif ( ($value>30000) )
			return $increments_hash[8];
	}
?>