<?#//v.1.0.0

#///////////////////////////////////////////////////////
#//  COPYRIGHT 2002 PHPAuction.org ALL RIGHTS RESERVED//
#//  For Source code for the GPL version go to        //  
#//  http://phpauction.org and download               //
#//  Supplied by CyKuH [WTN]                          //
#///////////////////////////////////////////////////////

	include "./includes/messages.inc.php";
	include "./includes/config.inc.php";

	#// If user is not logged in redirect to login page
	if(!isset($HTTP_SESSION_VARS["PHPAUCTION_LOGGED_IN"]))
	{
		Header("Location: user_login.php");
		exit;
	}



	if($HTTP_POST_VARS[action] == "sell")
	{
		#// Delete auction
		if(is_array($HTTP_POST_VARS[delete]))
		{
			while(list($k,$v) = each($HTTP_POST_VARS[delete]))
			{
				#// Retrieve auction's category
				$query = "DELETE FROM PHPAUCTIONPROPLUS_auctions WHERE id='$v'";
				$res = @mysql_query($query);
				if(!$res)
				{
					MySQLError($query);
					exit;
				}
			}
		}				
	}

	#// relist selected auction (if any)
	if($HTTP_POST_VARS[action] == "update")
	{
		#// Delete auction
		if(is_array($HTTP_POST_VARS[delete]))
		{
			while(list($k,$v) = each($HTTP_POST_VARS[delete]))
			{
				#// Retrieve auction's category
				$query = "SELECT category FROM PHPAUCTIONPROPLUS_auctions WHERE id='$v'";
				$res = @mysql_query($query);
				if(!$res)
				{
					MySQLError($query);
					exit;
				}
				$CATEGORY = mysql_result($res,0,"category");
				
				#//
				$query = "UPDATE PHPAUCTIONPROPLUS_counters SET closedauctions=(closedauctions-1)";
				$res = @mysql_query($query);
				if(!$res)
				{
					MySQLError($query);
					exit;
				}

				#//
				$query = "DELETE FROM PHPAUCTIONPROPLUS_auctions WHERE id='$v'";
				$res = @mysql_query($query);
				if(!res)
				{
					MySQLError($query);
					exit;
				}
				
				#// Bids
				$decremsql = mysql_query("select * FROM PHPAUCTIONPROPLUS_bids WHERE auction='$v'");
				$decrem=mysql_num_rows($decremsql);
				$query = "DELETE FROM PHPAUCTIONPROPLUS_bids WHERE auction='$v'";
				$res = @mysql_query($query);
				if(!$res)
				{
					MySQLError($query);
					exit;
				}
				
				#// Winners
				$query = "DELETE FROM PHPAUCTIONPROPLUS_winners WHERE auction='$v'";
				$res = @mysql_query($query);
				if(!$res)
				{
					MySQLError($query);
					exit;
				}
				
				#// Categories
				// update "categories" table - for counters
				$cat_id = $CATEGORY;
				$root_cat = $cat_id;
				do 
				{
					// update counter for this category
					$query = "SELECT * FROM PHPAUCTIONPROPLUS_categories WHERE cat_id='$cat_id'";
					$result = mysql_query($query);
					if ( $result )
					{
						if ( mysql_num_rows($result)>0 )
						{
							$R_parent_id = mysql_result($result,0,"parent_id");
							$R_cat_id = mysql_result($result,0,"cat_id");
							$R_counter = intval(mysql_result($result,0,"counter"));
							$R_sub_counter = intval(mysql_result($result,0,"sub_counter"));

							$R_sub_counter--;
							if ( $cat_id == $root_cat )
								--$R_counter;
							
							if($R_counter < 0) $R_counter = 0;
							if($R_sub_counter < 0) $R_sub_counter = 0;
							
							$query = "UPDATE PHPAUCTIONPROPLUS_categories SET counter='$R_counter', sub_counter='$R_sub_counter' WHERE cat_id=\"$cat_id\"";
							if (!mysql_query($query) )
							{	
								MySQLError($query);
								exit;
							}

							$cat_id = $R_parent_id;
						}
					}
				} 
				while ($cat_id!=0);				

			}
		}
		
		

		#// Re-list auctions
		if(is_array($HTTP_POST_VARS[relist]))
		{
			while(list($k,$v) = each($relist))
			{
				#// 
				$TODAY = date("Y-m-d H:i:s");
				// auction ends
				$WILLEND = time() + $duration[$k] * 24 * 60 * 60;
				$WILLEND = date("Y-m-d H:i:s", $WILLEND);
				
				$query = "update PHPAUCTIONPROPLUS_auctions set starts='$TODAY',
											  ends='$WILLEND',
											  duration=$duration[$k],
											  closed=0
											  where id='$k'";
				$res = mysql_query($query);
				//print $query;
				if(!$res)
				{
					MySQLError($query);
					exit;
				}
				
				#// Unset EDITED_AUCTIONS array (set in edit_auction.php)
				session_name($SESSION_NAME);
				session_unregister("EDITED_AUCTIONS");
				
				//-- Update COUNTERS table

				$query = "update PHPAUCTIONPROPLUS_counters set auctions=(auctions+1),closedauctions=(closedauctions-1) ";
				$RR = mysql_query($query);
				if(!$RR)
				{
					print "Error: $query<BR>".mysql_error();
					exit;
				}
				
				#// Get category
				$query = "select category from PHPAUCTIONPROPLUS_auctions where id='$k'";
				$RRR = mysql_query($query);
				$CATEGORY = mysql_result($RRR,0,"category");
				
				#// and increase category counters
				$ct = $CATEGORY;
				$row = mysql_fetch_array(mysql_query("SELECT * FROM PHPAUCTIONPROPLUS_categories WHERE cat_id=$ct"));
				$counter = $row[counter]+1;
				$subcoun = $row[sub_counter]+1;
				$parent_id = $row[parent_id];
				mysql_query("UPDATE PHPAUCTIONPROPLUS_categories SET counter=$counter, sub_counter=$subcoun WHERE cat_id=$ct");

				// update recursive categories
				while ( $parent_id!=0 )
				{
					// update this parent's subcounter
						$rw = mysql_fetch_array(mysql_query("SELECT * FROM PHPAUCTIONPROPLUS_categories WHERE cat_id=$parent_id"));
						$subcoun = $rw[sub_counter]+1;
						mysql_query("UPDATE PHPAUCTIONPROPLUS_categories SET sub_counter=$subcoun WHERE cat_id=$parent_id");
					// get next parent
						$parent_id = intval($rw[parent_id]);
				}				

			}
		}
	}
	
	
		
	#// Retrieve active auctions from the database
	$query = "select id,title,current_bid,starts,ends,minimum_bid,duration from PHPAUCTIONPROPLUS_auctions where user='$HTTP_SESSION_VARS[PHPAUCTION_LOGGED_IN]' and closed=0 order by title";
	//print $query;
	$res = mysql_query($query);
	if(!$res)
	{	
		print "Error: $query<BR>".mysql_error();
		exit;
	}
	//print $query;
	#//Built array
	while($item = mysql_fetch_array($res))
	{
		$IDS[] = $item[id];
		$TITLE[] = stripslashes($item[title]);
		$DURATION[] = $item[duration];
		
		$ends = substr($item[ends],6,2)."/".substr($item[ends],4,2)."/".substr($item[ends],0,4);
		$ENDS[] = $ends;

		$starts = substr($item[starts],6,2)."/".substr($item[starts],4,2)."/".substr($item[starts],0,4);
		$STARTS[] = $starts;
		$STARTINGBID[] = $item[minimum_bid];
		$BID[] = $item[current_bid];
		
		#//
		$query = "select count(bid) as count from PHPAUCTIONPROPLUS_bids where auction='$item[id]'";
		$res_ = mysql_query($query);
		if(!$res_)
		{
			print "Error: $query<BR>".mysql_error();
			exit;
		}
		$BIDS[] = mysql_result($res_,0,"count");
	}
		
		
	#// Retrieve data from the database
	$query = "select id,title,current_bid,starts,ends,minimum_bid,duration,reserve_price from PHPAUCTIONPROPLUS_auctions where user='$HTTP_SESSION_VARS[PHPAUCTION_LOGGED_IN]'  and closed=1 and suspended<>8 order by title";
	$res = mysql_query($query);
	if(!$res)
	{	
		print "Error: $query<BR>".mysql_error();
		exit;
	}
	//print $query;
	#//Built array
	while($item = mysql_fetch_array($res))
	{
		$C_IDS[] = $item[id];
		$C_TITLE[] = stripslashes($item[title]);
		$C_DURATION[] = $item[duration];
		
		$ends = substr($item[ends],6,2)."/".substr($item[ends],4,2)."/".substr($item[ends],0,4);
		$C_ENDS[] = $ends;

		$starts = substr($item[starts],6,2)."/".substr($item[starts],4,2)."/".substr($item[starts],0,4);
		$C_STARTS[] = $starts;
		$C_STARTINGBID[] = $item[minimum_bid];
		$C_BID[] = $item[current_bid];

		$current = $item[current_bid];
		$reserve = $item[reserve_price];

		if(($reserve-$current) >= 0)
		{
		$C_OK[] = "1";
		}
		else
		{
		$C_OK[] = "0";
		}
		
		#//
		$query = "select count(bid) as count from PHPAUCTIONPROPLUS_bids where auction='$item[id]'";
		$res_ = mysql_query($query);
		if(!$res_)
		{
			print "Error: $query<BR>".mysql_error();
			exit;
		}
		$C_BIDS[] = mysql_result($res_,0,"count");
	}		
	
	

	#// Retrieve data from the database about bulk uploaded auctions
	$query = "select id,description,title,minimum_bid,category from PHPAUCTIONPROPLUS_auctions where user='$HTTP_SESSION_VARS[PHPAUCTION_LOGGED_IN]'  and suspended=8 order by title";
	$res = mysql_query($query);
	if(!$res)
	{	
		print "Error: $query<BR>".mysql_error();
		exit;
	}
	//print $query;
	#//Built array
	while($item = mysql_fetch_array($res))
	{
		$B_IDS[] = $item[id];
		$B_TITLE[] = stripslashes($item[title]);
		$B_DESC[] = $item[description];
		$B_PRICE[] = $item[minimum_bid];
		
		#//
		$query = "select cat_name from PHPAUCTIONPROPLUS_categories where cat_id='$item[category]'";
		$res_ = mysql_query($query);
		if(!$res_)
		{
			print "Error: $query<BR>".mysql_error();
			exit;
		}
		$B_CAT[] = mysql_result($res_,0,"cat_name");
	}		


	include "header.php";
	include "./templates/template_yourauctions_php.html";
	include "footer.php";
	
?>