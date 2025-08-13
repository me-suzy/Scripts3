<?php
/*
   Copyright (c), 1999, 2000 - phpauction.org                  
   
   This program is free software; you can redistribute it and/or modify 
   it under the terms of the GNU General Public License as published by 
   the Free Software Foundation (version 2 or later).                                  
                                                                        
   This program is distributed in the hope that it will be useful,      
   but WITHOUT ANY WARRANTY; without even the implied warranty of       
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the        
   GNU General Public License for more details.                         
                                                                        
   You should have received a copy of the GNU General Public License    
   along with this program; if not, write to the Free Software          
   Foundation, Inc., 675 Mass Ave, Cambridge, MA 02139, USA.
*/

        include("./includes/config.inc.php");
        include("./includes/messages.inc.php");
        include("./includes/auction_types.inc.php");
        include("./includes/autobid.inc.php");

        /* first check if valid auction ID passed */
        $result = mysql_query("SELECT * FROM PHPAUCTION_auctions WHERE id='$id'");
                // SQL error
        if (!$result)
        {
                include("header.php");
				$TPL_errmsg = $ERR_001;					
              	include("templates/template_bid_php.html");
				
                include("footer.php");
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
	$result = mysql_query("SELECT * FROM PHPAUCTION_users WHERE id='".$Data["user"]."'");
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
	
	$query = "SELECT MAX(bid) AS maxbid FROM PHPAUCTION_bids WHERE auction='$id' GROUP BY auction";
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
		$query = "SELECT increment FROM PHPAUCTION_increments WHERE".
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

        unset($display_bid_form);
        if (empty($action) )
        {
                // no "action" specified
                $display_bid_form = true;
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
			$query = "SELECT * FROM PHPAUCTION_users WHERE nick='".addslashes($nick)."'";

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
			$query2 = "SELECT suspended FROM PHPAUCTION_auctions WHERE id='$id'";
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
			$query = "SELECT * from PHPAUCTION_bids WHERE bidder='$bidder_id' and auction='$id'";
			
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

		// check if this user isn't winning now
		if ( !isset($ERR) )
		{
			$result = mysql_query("SELECT * FROM PHPAUCTION_bids WHERE auction='$id' ORDER BY bid DESC");
			$auctionBIDS = $result;
			$n = 0;
			if ($result)
			{
				$n = mysql_num_rows($result);
				if ($n>0  && $atype!=2)
				{
					$bidder = mysql_result($result,0,"bidder");
					if ($bidder == $bidderID)
							$ERR = "613";
				}

          
			 }
			else
				$ERR = "001";
		}

		// perform final actions
		if ( isset($ERR) )
		{
				$display_bid_form = true;
				$TPL_errmsg = ${"ERR_".$ERR};
		}
		else
		{
		unset($ERR);






		$send_email = 0;

		// Send e-mail to the old winner if necessary

		// Check if there's a previous winner and get his/her data

		$query = "select bidder,bid from PHPAUCTION_bids where auction=\"$id\" order by bid desc";
		$result = mysql_query($query);
		if(!$query)
		{				
			print $ERR_001."<BR>$query<BR>".mysql_error();
			exit;
		}

		if(mysql_num_rows($result) > 0)
		{
		if($atype == 2){
			$send_email= 0;
}
else
			$send_email = 1;

			$OldWinner_id = mysql_result($result,0,"bidder");
			$OldWinner_bid = mysql_result($result,0,"bid");
			$OldWinner_bid = print_money($OldWinner_bid); 

			$query = "select * from PHPAUCTION_users where id=\"$OldWinner_id\"";
			$result_old_winner = mysql_query($query);
			if(!$result_old_winner){
				print $ERR_001."<BR>$query<BR>".mysql_error();
				exit;
			}

			$OldWinner_nick = mysql_result($result_old_winner,0,"nick");
			$OldWinner_name = mysql_result($result_old_winner,0,"name");
			$OldWinner_email = mysql_result($result_old_winner,0,"email");
		}

		// Update auctions table with the new bid

		$bid = doubleval($bid);
		$query = "update PHPAUCTION_auctions set current_bid=$bid,starts=$auctiondate,ends=$auctionends where id=\"$id\"";
		//$query = "update auctions set current_bid=$bid where id=\"$id\"";
		if(!mysql_query($query)){				
			print $ERR_001."<BR>$query<BR>".mysql_error();
			exit;
		}


		// Update bids table with the new bid

		$query = "insert into PHPAUCTION_bids values(\"$id\",\"$bidder_id\",$bid,NULL,".intval($qty).")";
		if(!mysql_query($query)){				
			print $ERR_001."<BR>$query<BR>".mysql_error();
			exit;
		}


		if($send_email){
        	$year    = substr($auctionends,0,4);
        	$month   = substr($auctionends,4,2);
        	$day     = substr($auctionends,6,2);
        	$hours   = substr($auctionends,8,2);
        	$minutes = substr($auctionends,10,2);
        	$ends_string   = $month . " " . $day . " " . $year . "  " . $hours . ":" . $minutes;
			$new_bid = print_money($bid);
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
				else
				{
					$TPL_id = $id;
					include "header.php";
					include "templates/template_bid_result_php.html";
					include "footer.php";
					exit;
				}
			}
        }

        if($display_bid_form)
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
