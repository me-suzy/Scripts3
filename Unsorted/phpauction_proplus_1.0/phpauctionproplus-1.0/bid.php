<?#//v.1.0.0

#///////////////////////////////////////////////////////
#//  COPYRIGHT 2002 PHPAuction.org ALL RIGHTS RESERVED//
#//  For Source code for the GPL version go to        //  
#//  http://phpauction.org and download               //
#//  Supplied by CyKuH [WTN]                          //
#///////////////////////////////////////////////////////


	include("./includes/config.inc.php");
	include("./includes/messages.inc.php");
	include("./includes/auction_types.inc.php");

	/* first check if valid auction ID passed */
	$result = mysql_query("SELECT * FROM PHPAUCTIONPROPLUS_auctions WHERE id='$id'");
			// SQL error
	if (!$result)
	{
		MySQLError($query);
		exit;
	}
	$n = mysql_num_rows($result);

			// such auction does not exist
	if ($n==0)
	{
			include("header.php");
			$TPL_errmsg = $ERR_606;
			include("templates/template_bid_php.html");

			include("footer.php");
			exit;
	}

	// extract info about this auction into an hash
	$Data = mysql_fetch_array($result);
	$auctiondate = $Data[starts];
	$auctionends = $Data[ends];
	$item_title = $Data["title"];
	$increment= $Data[increment];
	$item_description = $Data["description"];
	$aquantity = $Data[quantity];
	$minimum_bid=$Data[minimum_bid];
	$current_bid=$Data[current_bid];
	// check if auction isn't closed
	$AuctionIsClosed = false;
	$closed = intval($Data["closed"]);
	$c = $Data["ends"];

	if ( mktime(substr($c,8,2),
				substr($c,10,2),
				substr($c,12,2),
				substr($c,4,2),
				substr($c,6,2),
				substr($c,0,4)
			) <= time() )
			$AuctionIsClosed = true;

	if ( ($closed==1) || ($AuctionIsClosed) )
	{
			include("header.php");
			$TPL_errmsg = $ERR_614;					
			include("templates/template_bid_php.html");

			include("footer.php");
			exit;
	}

	// fetch info about seller
	$result = mysql_query("SELECT * FROM PHPAUCTIONPROPLUS_users WHERE id='".$Data["user"]."'");
	$n = 0;
	if ($result)
			$n = mysql_num_rows($result);
	if ($n>0)
			$Seller = mysql_fetch_array($result);
	else
			$Seller = array();


	$atype = intval($Data[auction_type]);

	// calculate: increment and mimimum bid value
	// determine max bid for this auction
	
	$query = "SELECT MAX(bid) AS maxbid FROM PHPAUCTIONPROPLUS_bids WHERE auction='$id' GROUP BY auction";
	$result_bids = mysql_query ( $query) ;
	if ( !$result_bids )
	{
		print $MSG_001;
	   exit;
	}

	if ( mysql_num_rows($result_bids ) > 0)
	{
	   $high_bid       = mysql_result ( $result_bids, 0, "maxbid" );
	   
	}

		
	/* Get bid increment for current bis and calculate minimum bid */
	$customincrement=$Data[increment];
	$minimum_bid=$Data[minimum_bid];
	$query = "SELECT increment FROM PHPAUCTIONPROPLUS_increments WHERE".
		"((low<=$high_bid AND high>=$high_bid) OR".
		"(low<$high_bid AND high<$high_bid)) ORDER BY increment DESC";

	$result_incr = mysql_query  ( $query );
	if($result_incr != 0)
	{
		$increment   = mysql_result ( $result_incr, 0, "increment" );
    }
    
    if($atype == 2)
	{
		$increment = 0; 
	}
    if($customincrement > 0)
    {
        $increment   = $customincrement;
    }

	if ($high_bid == 0 || $atype ==2)
	{
		$next_bid = $minimum_bid;
	
	}
   
	else
	{
		$next_bid = $high_bid + $increment;
	
	}


        /*      else: such auction does exist.
                if called from item.php - then transfer passed data
                if called - check data/username/password and then execute autobid
        */

        unset($display_bid_form1);
        if (empty($action) )
        {
                // no "action" specified
                $display_bid_form1 = true;
        }
        else
        {
		// an action specified: check for data and perform corresponding actions

		unset($ERR);
		
		$bid = input_money($bid);

		// check if bid value is OK


		if($bid < $next_bid)
		{
			$ERR = "607";
		}

		// check if number of items is OK
		if ( ($atype==2) && (!isset($ERR)) )
		{
			if ( (intval($qty)==0) || (intval($qty)>intval($Data["quantity"])) )
			{
				$ERR = "608";
			}
		}

		// check if nickname and password entered
		if ( !isset($ERR) )
		{
			if ( strlen($nick)==0 || strlen($password)==0 )
					$ERR = "610";
		}

		// check if nick is valid
		if ( !isset($ERR) )
		{
			$query = "SELECT * FROM PHPAUCTIONPROPLUS_users WHERE nick='".addslashes($nick)."'";

			$result = mysql_query($query);
		
			$n = 0;
			if ($result)
					$n = mysql_num_rows($result);
			else
					$ERR = "001";

			if ( !isset($ERR) )
			{
					if ($n==0)
							$ERR = "609";
			}
			if($n > 0)
		{
				$bidder_id = mysql_result($result,0,"id");
                  #// Automatically login user
                $PHPAUCTION_LOGGED_IN = $bidder_id;
                $PHPAUCTION_LOGGED_IN_USERNAME = mysql_result($result,0,"nick");
                session_name($SESSION_NAME);
                session_register("PHPAUCTION_LOGGED_IN","PHPAUCTION_LOGGED_IN_USERNAME");

		}
	}
		// check if password is correct
		if ( !isset($ERR) )
		{
				$pwd = mysql_result($result,0,"password");
				if ($pwd != md5($MD5_PREFIX.$password))
				{
						$ERR = "611";
				}
				else
				{
					if(mysql_result($result,0,"suspended") > 0)
					{
						$ERR = "618";
					}
				}
		}

		// Check if Auction is suspended
		if ( !isset($ERR) )
		{
			$query2 = "SELECT suspended FROM PHPAUCTIONPROPLUS_auctions WHERE id='$id'";
			$result2 = mysql_query($query2);
			
			if (mysql_result($result2, 0, "suspended") > 0)
			{
				$ERR = "619";
			}
		}


		#// ------------------------------------------------------------
		#// ADDED BY GIANLUCA Jan. 9, 2002
		#// ------------------------------------------------------------
		#// If dutch auction, check if the bidding user already 
		#// placed a bid and, if yes, the current bid cannot be minor
		#// than the previous placed bid.
		if($Data[auction_type] == 2)
		{
			#//
			$CURRENT = $bid * $qty;
			
			#// Search for bids of this user 
			$query = "SELECT * from PHPAUCTIONPROPLUS_bids WHERE bidder='$bidder_id' and auction='$id'";
			
			$res_ = @mysql_query($query);
			if($res_ && mysql_num_rows($res_) > 0)
			{
				while($BID = mysql_fetch_array($res_))
				{
					if($CURRENT < ($BID[quantity] * $BID[bid]))
					{
						$ERR = "059";
					}
				}
			}
		}
		#// ------------------------------------------------------------
		#// >>>>>>ENDS - ADDED BY GIANLUCA Jan. 9, 2002<<<<<<<<
		#// ------------------------------------------------------------
		
		
		



		// check if bidder is not the seller
		if ( !isset($ERR) )
		{
				$bidderID = mysql_result($result,0,"id");
				if ( $bidderID == $Seller["id"] )
						$ERR = "612";
		}

	

		// perform final actions
		if ( isset($ERR) )
		{
				$display_bid_form1 = true;
				$TPL_errmsg = ${"ERR_".$ERR};
		}
		else
		{
		unset($ERR);


	    // ################################
	    //          PROXY BIDDING
	    // ################################
		$bidP = print_money($bid);
	    $query = "SELECT * FROM PHPAUCTIONPROPLUS_users WHERE nick='".addslashes($nick)."'";
		$result = mysql_query($query);

		$n = 0;
		if ($result)
				$n = mysql_num_rows($result);
		else
				$ERR = "001";

		if ( !isset($ERR) )
		{
				if ($n==0)
						$ERR = "609";
		}
		if($n > 0)
			$bidder_id = mysql_result($result,0,"id");


	    $result = mysql_query("SELECT * FROM PHPAUCTIONPROPLUS_proxybid WHERE itemid='$id'");
        #print "RESULT -> $result";
        if (mysql_num_rows($result) == 0)
        {
		    if ($next_bid < $bid)
			{
		        $query = "INSERT INTO PHPAUCTIONPROPLUS_proxybid VALUES ('$id','$bidder_id',$bid)";
		        if(!mysql_query($query))
				{
			        MySQLError($query);
			        exit;
		        }
		        $TPL_proxy = "$MSG_699 $bidP $MSG_700";
		        $next_bid=$next_bid;

	#// Update bids table
				$query = "INSERT INTO PHPAUCTIONPROPLUS_bids VALUES(NULL, \"$id\",\"$bidder_id\",".input_money($next_bid).",NULL,".intval($qty).")";
				$rr_ =@mysql_query($query);
				if(!$rr_)
				{
			        MySQLError($query);
			        exit;
		        }

$query = "update PHPAUCTIONPROPLUS_auctions set current_bid=$next_bid where id=\"$id\"";
		if(!mysql_query($query))
		{				
			MySQLError($query);
			exit;
		}
		    }
        }
        else
        {
			$proxy_bidder_id = mysql_result($result,0,"userid");
			$proxy_max_bid = mysql_result($result,0,"bid");

			if ($proxy_max_bid < $bid)
			{
				$next_bid=$proxy_max_bid+ $increment;
				$TPL_new_proxy = "$MSG_699 $bidP $MSG_700";

				$query = "UPDATE PHPAUCTIONPROPLUS_proxybid SET userid='$bidder_id',bid=$bid where itemid=\"$id\"";
				$proxy=1;
				if(!mysql_query($query))
				{
					MySQLError($query);
						exit;
				}

				$next_bid=$next_bid;

	#// Update bids table
				$query = "INSERT INTO PHPAUCTIONPROPLUS_bids VALUES(NULL, \"$id\",\"$bidder_id\",".input_money($next_bid).",NULL,".intval($qty).")";
				$rr_ =@mysql_query($query);
				if(!$rr_)
				{
			        MySQLError($query);
			        exit;
		        }

$query = "update PHPAUCTIONPROPLUS_auctions set current_bid=$next_bid where id=\"$id\"";
		if(!mysql_query($query))
		{				
			MySQLError($query);
			exit;
		}


            }
            elseif ($proxy_max_bid == $bid)
		    {
                $proxywinner=1;
				$proxy=true;
		        $next_bid=$proxy_max_bid;
		        $next_bid=$next_bid;
		        $bidder_id=$proxy_bidder_id;
		        $TPL_new_proxy2 = "$MSG_701";
	#// Update bids table
				$query = "INSERT INTO PHPAUCTIONPROPLUS_bids VALUES(NULL, \"$id\",\"$bidder_id\",".input_money($next_bid).",NULL,".intval($qty).")";
				$rr_ =@mysql_query($query);
				if(!$rr_)
				{
			        MySQLError($query);
			        exit;
		        }

$query = "update PHPAUCTIONPROPLUS_auctions set current_bid=$next_bid where id=\"$id\"";
		if(!mysql_query($query))
		{				
			MySQLError($query);
			exit;
		}
	            $display_bid_form = true;

                if($display_bid_form)
	            {
					// prepare some data for displaying in the form
					$nickH = htmlspecialchars($nick);
					$bidP = htmlspecialchars($bid);
					$qtyH = htmlspecialchars($qty);
					$TPL_title = htmlspecialchars($Data[title]);
					$TPL_next_bid = print_money($next_bid+$increment);

					$TPL_proposed_bid = print_money($bid);
					$TPL_cancel_bid_link = "<A HREF=item.php?>$MSG_332</A>";


					// output the form
					
					include("header.php");
					include("templates/template_bid_php.html");
					include("footer.php");
					exit;
	            }

				$query = "DELETE FROM PHPAUCTIONPROPLUS_proxybid where itemid=\"$id\"";
				if(!mysql_query($query))
				{
					MySQLError($query);
					exit;
				}



            }


            elseif ($proxy_max_bid > $bid)
	        {
	#// Update bids table
				$query = "INSERT INTO PHPAUCTIONPROPLUS_bids VALUES(NULL, \"$id\",\"$bidder_id\",".input_money($bid).",NULL,".intval($qty).")";
				$rr_ =@mysql_query($query);
				if(!$rr_)
				{
			        MySQLError($query);
			        exit;
		        }

				$proxywinner=1;
				$proxy=true;
				$next_bid=$bid+$increment;
				$next_bid=$next_bid;
				$bidder_id2=$proxy_bidder_id;
				$TPL_new_proxy3 = "$MSG_701";

	#// Update bids table
				$query = "INSERT INTO PHPAUCTIONPROPLUS_bids VALUES(NULL, \"$id\",\"$bidder_id2\",".input_money($next_bid).",NULL,".intval($qty).")";
				$rr_ =@mysql_query($query);
				if(!$rr_)
				{
			        MySQLError($query);
			        exit;
		        }
$query = "update PHPAUCTIONPROPLUS_auctions set current_bid=$next_bid where id=\"$id\"";
		if(!mysql_query($query))
		{				
			MySQLError($query);
			exit;
		}
				$display_bid_form = true;

				if($display_bid_form)
				{
					// prepare some data for displaying in the form
					$nickH = htmlspecialchars($nick);
					$bidP = htmlspecialchars($bid);
					$qtyH = htmlspecialchars($qty);
					$TPL_title = htmlspecialchars($Data[title]);
					$TPL_next_bid = print_money($next_bid+$increment);

					$TPL_proposed_bid = print_money($bid);
					$TPL_cancel_bid_link = "<A HREF=item.php?>$MSG_332</A>";


					// output the form
				
					include("header.php");
					include("templates/template_bid_php.html");
					include("footer.php");
			exit;	

				}
 

	

            }
        }

	    // ################################
	    //         END OF PROXY BIDDING
	    // ################################

	#// Update bids table
				$query = "INSERT INTO PHPAUCTIONPROPLUS_bids VALUES(NULL, \"$id\",\"$bidder_id\",".input_money($next_bid).",NULL,".intval($qty).")";
				$rr_ =@mysql_query($query);
				if(!$rr_)
				{
			        MySQLError($query);
			        exit;
		        }

		$send_email = 0;

		// Send e-mail to the old winner if necessary
		// Check if there's a previous winner and get his/her data

		$query = "select bidder,bid from PHPAUCTIONPROPLUS_bids where auction='$id' order by bid desc";
		$result = mysql_query($query);
		if(!$result)
		{				
			MySQLError($query);
			exit;
		}

		if(mysql_num_rows($result) > 0)
		{
	    if($atype == 2)
		{
			$send_email= 0;
		}
		else	
			$send_email = 1;

			$OldWinner_id = mysql_result($result,0,"bidder");
			$new_bid =$next_bid;
			$OldWinner_bid =  print_money($new_bid-$increment);
 
			$query = "select * from PHPAUCTIONPROPLUS_users where id=\"$OldWinner_id\"";
			$result_old_winner = mysql_query($query);
			if(!$result_old_winner)
			{
				MySQLError($query);
				exit;
			}

			$OldWinner_nick = mysql_result($result_old_winner,0,"nick");
			$OldWinner_name = mysql_result($result_old_winner,0,"name");
			$OldWinner_email = mysql_result($result_old_winner,0,"email");
		}

		// Update auctions table with the new bid

  
		$query = "update PHPAUCTIONPROPLUS_auctions set starts=$auctiondate,ends=$auctionends where id=\"$id\"";
		//$query = "update PHPAUCTIONPROPLUS_auctions set current_bid=$bid where id=\"$id\"";
		if(!mysql_query($query))
		{				
			MySQLError($query);
			exit;
		}
		#// ------------------------------------------------------------
		#// ADDED BY SIMOKAS
		#// ------------------------------------------------------------
		// Send notification if users keyword matches (Item Watch)

    		$result = mysql_query("SELECT * FROM PHPAUCTIONPROPLUS_users");
			$num_users = mysql_num_rows($result);
			$i = 0;
			while($i < $num_users) {
				$nickname = mysql_result ($result,$i,"nick");
				$e_mail = mysql_result ($result,$i,"email");
				$items = mysql_result ($result,$i,"item_watch");

				$match = strstr($items,$id);

			// If keyword matches with opened auction title or/and desc send user a mail
			if ($match) 
				{ 

			// Get data about the auction
			$res = mysql_query("SELECT * FROM PHPAUCTIONPROPLUS_auctions where id='$id' ");
				$auc_title = stripslashes(mysql_result ($res,0,"title"));
				$current_bid = mysql_result ($res,0,"current_bid");
				$sitename = $SETTINGS[sitename];
				$auction_url = $SETTINGS[siteurl] . "item.php?mode=1&id=$id";
				$new_bid2 = print_money($current_bid);
			// Mail body and mail() functsion
			include ("./templates/template_item_watch_bidmail_php.html");
				}
				else { } 


			$i++;
			}

		// End of Item watch

		
		/* Update column bids in table PHPAUCTIONPROPLUS_counters */
		$counteruser = mysql_query("UPDATE PHPAUCTIONPROPLUS_counters SET bids=(bids+1)");

		if($send_email){
        	$year    = substr($auctionends,0,4);
        	$month   = substr($auctionends,4,2);
        	$day     = substr($auctionends,6,2);
        	$hours   = substr($auctionends,8,2);
        	$minutes = substr($auctionends,10,2);
        	$ends_string   = $month . " " . $day . " " . $year . "  " . $hours . ":" . $minutes;
			$new_bid = print_money($next_bid);
        	//-- Send e-mail message
           	include('./includes/no_longer_winner.inc.php');
		}

		// 3) perform output
				if ( isset($ERR) )
				{
					$ERR = ${"ERR_".$ERR};
					include "header.php";
					print "<CENTER> $std_font $ERR </CENTER>";
					print mysql_error();
					include "footer.php";
					exit;
			    }
				elseif(!$display_bid_form)
				{
					$TPL_id = $id;
					include "header.php";
					include "templates/template_bid_result_php.html";
					include "footer.php";
					exit;
				}
			}
        }

        if($display_bid_form1)
        {
			// prepare some data for displaying in the form
			$nickH = htmlspecialchars($nick);
			$bidH = htmlspecialchars($bid);
			$qtyH = htmlspecialchars($qty);
			$TPL_title = htmlspecialchars($Data[title]);
			$TPL_next_bid = print_money($next_bid);

			$TPL_proposed_bid = print_money($bid);
			$TPL_cancel_bid_link = "<A HREF=item.php?>$MSG_332</A>";


			// output the form
			include("header.php");
			include("templates/template_bid_php.html");
			include("footer.php");
			exit;
        }
	elseif(!$display_bid_form1)
				{
					$TPL_id = $id;
					include "header.php";
					include "templates/template_bid_result_php.html";
					include "footer.php";
					exit;
				}
?>