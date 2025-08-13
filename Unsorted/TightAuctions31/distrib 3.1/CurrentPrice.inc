<?
	//
	// Returns the current price.  
	//
	// Pre: Needs a db link to be established, the AdID, StartPrice, ReservePrice,
	//      and BidIncrements.inc to be included.
	//
	function GetCurrentPrice( &$WinningBidder )
	{	
		global $link, $AdID, $StartPrice, $ReservePrice;

		$WinningBidder = -1;

		$query = "SELECT UserAcctID, MaxBid FROM Bids WHERE AdID=$AdID ORDER BY BidDate ASC";
		$result = mysql_query( $query, $link );			

		$NumBids = mysql_num_rows( $result );

		if ( $NumBids == 0 )		
		{
			return $StartPrice;
		}
		else
		{
			while ( $row = mysql_fetch_row( $result ) )
			{
				$BidderID = $row[0];

				// Select only the highest bid from each individual bidder, so
				// that an individual bidder won't be unnecessarily raising the
				// price accidently by bidding against himself as the second 
				// highest and highest bidder. 

				$FoundBidder = false;
				for ( $i = 0; $i < count($UserAcctIDBids); $i++ )
				{
					if ( $UserAcctIDBids[$i] == $BidderID )
					{					
						$FoundBidder = true;				

						// Update the bidder's bid if it's higher
						if ( $row[1] > $Bids[$i] )
						{
							$Bids[$i] = $row[1];				
						}

						break;
					}
				}

				if ( !$FoundBidder )
				{
					$UserAcctIDBids[] = $BidderID;
					$Bids[] = $row[1];
				}
			}	

			$NumBids = count( $Bids );		// reupdate the number of bids for unique bids
		
			// Figure out who has the highest bid
						
			$HighestBidAmt = 0;
			for ($BidIdx = 0; $BidIdx < $NumBids; $BidIdx++)
			{
				$ID = $UserAcctIDBids[$BidIdx];
				$BidAmt = $Bids[$BidIdx];

				if ( $Bids[ $BidIdx ] > $HighestBidAmt )
				{			
					$HighestBidAmt = $Bids[ $BidIdx ];
					$WinningBidder = $UserAcctIDBids[ $BidIdx ];
					$HighestBidIdx = $BidIdx;
				}	
			}
						
			if ( $NumBids == 1 )
			{
				if ( $HighestBidAmt < $ReservePrice )
				{
					return $HighestBidAmt;
				}
				else
				{
					return $ReservePrice;
				}
			}
			else
			{				
				// Select the second highest bidder.  				

				$SecHighestBidAmt = 0;
				for ($BidIdx = 0; $BidIdx < $NumBids; $BidIdx++)
				{
					if ( ($Bids[ $BidIdx ] > $SecHighestBidAmt) && 
						 ($BidIdx != $HighestBidIdx) &&
						 ($UserAcctIDBids[ $BidIdx ] != $WinningBidder) )
					{						
						$SecHighestBidAmt = $Bids[ $BidIdx ];
					}	
				}	

				if ( ($SecHighestBidAmt < $ReservePrice) &&
					 ($HighestBidAmt <= $ReservePrice) )
				{
					// both are less than the reserve price

					//return $HighestBidAmt;					
					
					$CurrentPrice = $SecHighestBidAmt + GetBidIncrement( $SecHighestBidAmt );
		
					// Adding the bid increment may have put the price over the highest bid amout
	
					if ( $CurrentPrice > $HighestBidAmt )
						$CurrentPrice = $HighestBidAmt;

					return $CurrentPrice;					
				}
				else if ( ($SecHighestBidAmt < $ReservePrice) &&
	 					  ($HighestBidAmt >= $ReservePrice) )
				{
					// Second highest is less than the reserve price and the highest
					// is greater than the reserve price.  Update the bid increment on
					// the reserve price and clamp.

					$CurrentPrice = $ReservePrice + GetBidIncrement( $ReservePrice );			

					// Adding the bid increment may have put the price over the highest bid amout
	
					if ( $CurrentPrice > $HighestBidAmt )
						$CurrentPrice = $HighestBidAmt;

					return $CurrentPrice;
				}
				else 	
				{	
					// both are greater than the reserve price

					$CurrentPrice = $SecHighestBidAmt + GetBidIncrement( $SecHighestBidAmt );
		
					// Adding the bid increment may have put the price over the highest bid amout
	
					if ( $CurrentPrice > $HighestBidAmt )
						$CurrentPrice = $HighestBidAmt;

					return $CurrentPrice;
				}

				if ( $CurrentPrice < $ReservePrice )
					return $HighestBidAmt;
				else
					return $CurrentPrice;
			}					
					
		}  // end if ($NumBids != 0)		
	}

?>