<?php
 /*
   Copyright (c), 1999, 2000 - phpauction.org
   Copyright (c), 2001, 2002 - webauction.de.vu

   Lizenz siehe lizenz.txt & license

   Die neueste Version kostenfrei zum Download unter:
   http://webauction.de.vu

*/

	/*	specification for autobid robot
		for specified auction:
		1) look if there are bidders for this auction
		2) determine the bidder with largest autobid amount
		  a) if he's the one who bids - make bid the smallest one
		  b) if there are "concurents" - make the bid a bit larger than nearest concurent's amount
	*/

	function autobid($id)
	{
		global $sessionVars;

		// check if valid auction ID passed
		$result = mysql_query("SELECT * FROM auctions WHERE id=$id");
		if ($result) {
			if (mysql_num_rows($result)==0)
				return false;
		} else 
			return false;

		// get info about this auction
		$Auction = mysql_fetch_array($result);
		$aMinimumBid = doubleval($Auction["minimum_bid"]);

		// see if there are bidders for this auction
		$result = mysql_query("SELECT * FROM autobid WHERE auction=$id");
		if (!$result) // database error
			return false;

		$nBidders = mysql_num_rows($result);
		if ($nBidders==0)
			return true;

		// see if there is only one bidder
		if($nBidders==1)
		{
			$Bidder = mysql_fetch_array($result);
			$result = mysql_query("SELECT bid FROM bids WHERE auction=$id ORDER BY bid DESC");
			if (!$result)
				return false;
			$numBids = mysql_num_rows($result);

			// there are no bids for this auction
			if ($numBids==0)
			{
				// if there are no bids -- create a new bid and update tables
				$next = $aMinimumBid; // + doubleval(getIncrement($aMinimumBid));

				// check this user's limit
				/*
				print "Next: $next<BR>";
				print "aMinimumBid: $aMinimumBid<BR>";
				print "Limit: ".$Bidder["amount"]."<BR>";
				*/
				if ( $next<=doubleval($Bidder["amount"]) )
				{
					// update tables
					if (!mysql_query("UPDATE auctions SET current_bid='$next' WHERE id=$id"))
						return false;

					if (!mysql_query(
							"INSERT INTO bids VALUES ('$id', '".
							$Bidder["user"].
							"', '".
							$next."', NULL, '".
							$Bidder["quantity"]."')"))
						return false;

					return true;
				}
				else
					return false;
			}
			else
				return false; // this situation to be handled (probably?)
		}
		// there are 2 or more bidders
		else
		{
			// determine the next bid for this auction
			$bidsResult = mysql_query("SELECT * FROM bids WHERE auction=$id ORDER BY bid DESC");
			if (!$bidsResult)
				return false;
			$numBids = mysql_num_rows($bidsResult);
			unset($max_bid_exists); // a flag for further use
			if ($numBids==0)
			{
				$next_bid = doubleval($aMinimumBid); // + doubleval(getIncrement($aMinimumBid)));
			}
			else
			{
				$max_bid = doubleval(mysql_result($bidsResult,0,"bid"));
				$max_bid_exists = true;
				$next_bid = doubleval($max_bid + doubleval(getIncrement($max_bid)));
			}

				// save these bids
				$Bids = array(); $indexBids = 0;
				if ( $numBids!=0 )
					mysql_data_seek($bidsResult,0);
				while ( $row=mysql_fetch_array($bidsResult) ){
					$Bids[$indexBids] = $row;
					++$indexBids;
				}

			// get the list of bidders for this auction
			// also: calculate the maximum amount for this auction
				$max_amount = doubleval(0);
				if ( !($result = mysql_query("SELECT * FROM autobid WHERE auction=$id ORDER BY amount ASC")) )
					return false;
				$Bidders = array(); $BiddersByID = array();
				$indexBidders = 0;
				while($row=mysql_fetch_array($result,MYSQL_ASSOC)) 
				{
					// add this bidder to hash
					$Bidders[$indexBidders] = $row; ++$index;
					$BiddersByID[$row["user"]] = $row;
					++$indexBidders;

					// maximum amount
					if ( doubleval($row["amount"])>$max_amount )
						$max_amount = doubleval($row["amount"]);
				}

			// get old bids for this auction

			// prepare data for updating "bids" table
				$updateData = array(); 
				$indexUpdate = 0;
				$go2update = false;
				while( ($next_bid <= $max_amount) && ($go2update==false) )
				{
					// calculate the next bid for this auction
					$next2bid = doubleval($next_bid) + doubleval(getIncrement($next_bid));

					// find users that will bid for this amount
					/******************
					$res = mysql_query("SELECT * FROM autobid WHERE auction=$id AND amount>='".$next_bid."' ORDER BY amount ASC LIMIT 0,2");
					if (!$res)
						return false;
					// check if there are more than 1 bidders for this bid -- else make final bid and exit
					$numBidders = mysql_num_rows($res);
					******************/

							// this list will be in ascending order (sorted by amount)
						$Bidders1 = array(); $numBidders = 0;
						reset($Bidders); while(list($key,$val)=each($Bidders)){
							if ($Bidders[$key]["amount"]>=$next_bid)
							{
								$Bidders1[$numBidders] = $val;
								++$numBidders;
							}
						}

					if ( $numBidders==1 )
					{
						// check if this bidder isn't winner
						/************************
						$rs = mysql_query("SELECT * FROM bids WHERE auction=$id ORDER BY bid DESC LIMIT 0,1");
						if (!$rs)
							return false;
						if(mysql_num_rows($rs)>0)
						{
							if (mysql_result($rs,0,"bidder")==mysql_result($res,0,"user"))
								return true;
						}
						*************************/

							/*
							print "Before check: <BR>";
							print "indexUpdate=$indexUpdate <BR>";
							print "indexBids=$indexBids <BR>";
							print "Winner: ".$updateData[$indexUpdate-1]["user"]."<BR>";
							print "Pretender: ".$Bidders1[0]["user"];
							*/

							if ( $indexUpdate!=0 )
							{
								if ( $updateData[$indexUpdate-1]["user"]==$Bidders1[0]["user"] )
									$go2update = true;;
							}
							elseif ( $indexBids!=0 )
							{
								if ( $Bids[0]["bidder"]==$Bidders1[0]["user"] )
									$go2update = true;;

							}

						// make final bid and return TRUE
						/* **********************************
						if (!mysql_query("UPDATE auctions SET current_bid='$next_bid' WHERE id=$id"))
							return false;

						if (!mysql_query(
								"INSERT INTO bids VALUES ($id, ".
								mysql_result($res,0,"user").
								", '".$next_bid."', NULL, ".
								mysql_result($res,0,"quantity").
								")")
							)
							return false;
						********* */

							if ( $go2update == false )
							{
								$updateData[$indexUpdate] = array (
									"user"	=> $Bidders1[0]["user"],
									"amount"	=> $Bidders1[0]["amount"],
									"quantity"=> $Bidders1[0]["quantity"],
									"bid"=> $next_bid,
									"users"	=> 1
								);
								++$indexUpdate;
								$go2update = true;
							}
					}
					else
					{
						// there are 2 or more bidders - get them
//							mysql_data_seek($res,0); 
//							$Bidder[false] = mysql_fetch_array($res);
							$Bidder[false] = $Bidders1[0];

//							mysql_data_seek($res,1); 
//							$Bidder[true] = mysql_fetch_array($res);
							$Bidder[true] = $Bidders1[1];

							$BidderID1 = $Bidder[false]["user"];
							$BidderID2 = $Bidder[true]["user"];

						// determine the last bidder (if any) and select another bidder
						/******************************
						$r_est = mysql_query("SELECT bidder FROM bids WHERE auction=$id ORDER BY bid DESC LIMIT 0,1");
						if (!$r_est)
							return false;
						if ( mysql_num_rows($r_est)==0 )
							$bidder = false;
						else
						{
							$bidderID = mysql_result($r_est,0,"bidder");
							if ( $bidderID==$Bidder[false]["user"] )
								$bidder = true;
							else
								$bidder = false;
						}
						******************************/

							$bidder = false;
							if ( $indexUpdate!=0 )
							{
//								print "<FONT COLOR=RED>Winner is: ".$updateData[$indexUpdate-1]["user"]."</FONT><BR>";
								if ( $updateData[$indexUpdate-1]["user"]==$Bidder[false]["user"] )
									$bidder = true;
							}
							elseif ( $indexBids!=0 )
							{
//								print "<FONT COLOR=RED>Winner is: ".$Bids[0]["bidder"]."</FONT><BR>";
								if ( $Bids[0]["bidder"]==$Bidder[false]["user"] )
									$bidder = true;
							}

/*							print "Candidats: $BidderID1 and $BidderID2 <BR>";
							print "Chosen: ".$Bidder[$bidder]["user"]." <BR>"; */

						// update database
						/* **********************************
						if (!mysql_query("UPDATE auctions SET current_bid='$next_bid' WHERE id=$id"))
							return false;

						if (!mysql_query(
								"INSERT INTO bids VALUES ($id, ".
								$Bidder[$bidder]["user"].
								", '".$next_bid."', NULL, ".
								$Bidder[$bidder]["quantity"].
								")")
							)
							return false;
						********* */

						$updateData[$indexUpdate] = array (
							"user"	=> $Bidder[$bidder]["user"],
							"quantity"=> $Bidder[$bidder]["quantity"],
							"bid"=> $next_bid,
							"users"	=> $numBidders
						);
						++$indexUpdate;
					}

					if ( $go2update==false )
						$next_bid = $next2bid;
				}

				// *********** sew out the things **************
				if ($indexBids>0)
				{
					$lastPhase = $updateData[$indexUpdate-1]["users"];
					$lastPhaseWinner = $Bids[0]["bidder"];
					$winnerSet = true;
				}
				else
					$winnerSet = false;

				$ucounter = 0;
				for ($i=0; $i<$indexUpdate; $i++)
				{
					// get this step
					$row = $updateData[$i];

					// if a new phase is starting - process the old phase
						// to process a phase means:
						// 1) to find the phase's end
						// 2) to delete unvaluable bids

					if ( ($i==0) || ($updateData[$i-1]["users"]!=$updateData[$i]["users"]) )
					{
						/*	a new phase just began
							see if a previous phase winner has been set - if not - leave 2 last bids from this phase
						*/

						/* firstly find the end of this phase and determine its length */
						$k = $i; $length = 0;
						while ( ($updateData[$k]["users"]==$updateData[$i]["users"]) && ($k<$indexUpdate) )
						{
							$length++;
							$k++;
						}
						--$k;

						//print "<BR>Phase began: $length, users: ".$row["users"];

						if ( !$winnerSet )
						{
							/* leave 2 last bids from this phase */
							if ( $length>2 )
							{
								for($l=$k-2;$l>=$i;$l--)
								{
									unset($updateData[$l]);
									++$ucounter;
								}
							}
						}
						else
						{
							/* make it to fit the last winner */
							if ( $length>1 )
							{
								$l = 1;
								while ( ($updateData[$k-$l]["user"]==$lastPhaseWinner) && ($k-$l>=$i) )
								{
									$l++;
								}
								$l++;
								for ($j=$k-$l; $j>=$i; $j--)
								{
									unset($updateData[$j]);
									++$ucounter;
								}
							}
						}
						$i = $k;
					}


					// see if a phase is ending - and set its winner if needed
					if ( ($i!=$indexUpdate-1) && ($updateData[$i+1]["users"]!=$row["users"]) )
					{
						$winnerSet = true;
						$lastPhase = $row["users"];
						$lastPhaseWinner = $row["user"];
					}
				}

					//print "<BR> Unset: $ucounter <BR>";

				// ********** output the old bids ***************
/*				print "<BR><BR><BR>";
				for ($i=$indexBids-1; $i>=0; $i--) {
					$val = $Bids[$i];
					$r = mysql_query("SELECT * FROM users WHERE id=".$val["bidder"]);
					$nick = mysql_result ($r,0,"nick");
					print $val["bid"]." : <B>".$nick."(".$val["bidder"].")</B> (old bid)<BR>";
				}
				print "<BR><BR><BR>";

				// ********** output the new bids ***************
				print "<BR><BR><BR>";
				reset($updateData); while(list($key,$val)=each($updateData)) {
					$r = mysql_query("SELECT * FROM users WHERE id=".$val["user"]);
					$nick = mysql_result ($r,0,"nick");
					print $val["bid"]." : <B>".$nick."(".$val["user"].")</B> (bidders: ".$val["users"].")<BR>";
				}
				print "<BR><BR><BR>"; 
				exit;*/

				// ********** update database ******************
				reset($updateData);
				while ( list($i,$row)=each($updateData) )
				{
					$query = "INSERT INTO bids	VALUES ( $id, ".
						$row["user"].
						", '".$row["bid"]."', NULL, ".
						intval($row["quantity"]).
						" )";
					if ( !mysql_query($query) )
					{
						//print "<BR>$query<BR>";
						return false;
					}
				}

				/* see if bids are made within last 10 minutes (configurable) - delay auction for 30 minutes */
				$c = $Auction["ends"];
				$auction_ends = mktime (
					substr($c,8,2),
					substr($c,10,2),
					substr($c,12,2),
					substr($c,4,2),
					substr($c,6,2),
					substr($c,0,4)
				);

				global $auctionDelayPeriod, $auctionDelayTime;

				if ( $auction_ends-time()<=$auctionDelayPeriod )
				{
					//print "auction delayed";
					// delay this auction
					$auction_ends += $auctionDelayTime;
				}

				$ends = date ( "Y-m-d H:i:s", $auction_ends );

				$query = "UPDATE auctions SET current_bid='".$row["bid"]."', ends='$ends' WHERE id=$id";
				if ( !mysql_query($query) )
				{
					//print "<BR>$query<BR>";
					return false;
				}
		}

		return true;
	}
?>