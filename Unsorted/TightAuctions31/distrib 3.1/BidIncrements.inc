<?

//	Min, Max, Increment

$BidIncrementTable = array(
	0.01, 0.99, 0.05,
	1.00, 4.99, 0.25,
	5.00, 24.99, 0.50,
	25.00, 99.99, 1.00,
	100.00, 249.99, 2.50,
	250.00, 499.99, 5.00,
	500.00, 999.99, 10.00,
	1000.00, 2499.99, 25.00,
	2500.00, 4999.99, 50.00,
	5000.00, -1, 100.00 );

function GetBidIncrement( $Price )
{
	global $BidIncrementTable;

	$NumRanges = count($BidIncrementTable) / 3;

	for ( $i = 0; $i < $NumRanges; $i++ )
	{
		$Min = $BidIncrementTable[$i*3];
		$Max = $BidIncrementTable[$i*3+1];

		if ( ($Min <= $Price) && ($Price <= $Max) )
		{
			return $BidIncrementTable[$i*3+2];
		}
	}

	$LastRange = $NumRanges-1;
	if ( $Price > $BidIncrementTable[$LastRange] )
		return $BidIncrementTable[$LastRange+2];
	else
		return $BidIncrementTable[2];		// return the first range 							
}

?>