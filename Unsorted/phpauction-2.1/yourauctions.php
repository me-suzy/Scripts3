<?
	include "./includes/messages.inc.php";
	include "./includes/config.inc.php";

	#// If user is not logged in redirect to login page
	if(!isset($HTTP_SESSION_VARS["PHPAUCTION_LOGGED_IN"]))
	{
		Header("Location: user_login.php");
		exit;
	}

	#// relist selected auction (if any)
	if($HTTP_POST_VARS[action] == "update")
	{
		if(is_array($HTTP_POST_VARS[relist]))
		{
			while(list($k,$v) = each($relist))
			{
				#// 
				$TODAY = date("Y-m-d H:i:s");
				// auction ends
				$WILLEND = time() + $duration[$k] * 24 * 60 * 60;
				$WILLEND = date("Y-m-d H:i:s", $WILLEND);
				
				$query = "update PHPAUCTION_auctions set starts='$TODAY',
											  ends='$WILLEND',
											  duration=$duration[$k],
											  closed=0
											  where id='$k'";
				$res = mysql_query($query);
				//print $query;
				if(!$res)
				{
					print "Error: $query<BR>".mysql_error();
					exit;
				}
				
				#// Unset EDITED_AUCTIONS array (set in edit_auction.php)
				session_name($SESSION_NAME);
				session_unregister("EDITED_AUCTIONS");
				
				//-- Update COUNTERS table

				$query = "update PHPAUCTION_counters set auctions=auctions + 1";
				$RR = mysql_query($query);
				if(!$RR)
				{
					print "Error: $query<BR>".mysql_error();
					exit;
				}
				
				#// Get category
				$query = "select category from PHPAUCTION_auctions where id='$k'";
				$RRR = mysql_query($query);
				$CATEGORY = mysql_result($RRR,0,"category");
				
				#// and increase category counters
				$ct = $CATEGORY;
				$row = mysql_fetch_array(mysql_query("SELECT * FROM PHPAUCTION_categories WHERE cat_id=$ct"));
				$counter = $row[counter]+1;
				$subcoun = $row[sub_counter]+1;
				$parent_id = $row[parent_id];
				mysql_query("UPDATE PHPAUCTION_categories SET counter=$counter, sub_counter=$subcoun WHERE cat_id=$ct");

				// update recursive categories
				while ( $parent_id!=0 )
				{
					// update this parent's subcounter
						$rw = mysql_fetch_array(mysql_query("SELECT * FROM PHPAUCTION_categories WHERE cat_id=$parent_id"));
						$subcoun = $rw[sub_counter]+1;
						mysql_query("UPDATE PHPAUCTION_categories SET sub_counter=$subcoun WHERE cat_id=$parent_id");
					// get next parent
						$parent_id = intval($rw[parent_id]);
				}				

			}
		}
	}
	
	
		
	#// Retrieve active auctions from the database
	$query = "select id,title,current_bid,starts,ends,minimum_bid,duration from PHPAUCTION_auctions where user='$HTTP_SESSION_VARS[PHPAUCTION_LOGGED_IN]' and closed=0 order by title";
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
		$query = "select count(bid) as count from PHPAUCTION_bids where auction='$item[id]'";
		$res_ = mysql_query($query);
		if(!$res_)
		{
			print "Error: $query<BR>".mysql_error();
			exit;
		}
		$BIDS[] = mysql_result($res_,0,"count");
	}
		
		
	#// Retrieve data from the database
	$query = "select id,title,current_bid,starts,ends,minimum_bid,duration from PHPAUCTION_auctions where user='$HTTP_SESSION_VARS[PHPAUCTION_LOGGED_IN]'  and closed=1 order by title";
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
		
		#//
		$query = "select count(bid) as count from PHPAUCTION_bids where auction='$item[id]'";
		$res_ = mysql_query($query);
		if(!$res_)
		{
			print "Error: $query<BR>".mysql_error();
			exit;
		}
		$C_BIDS[] = mysql_result($res_,0,"count");
	}		
	
	
	#// Build durations array 
	$query = "select * from PHPAUCTION_durations order by days";
	$rd = mysql_query($query);
	while($row = mysql_fetch_array($rd))
	{
		$DURATIONS[$row[days]] = $row[description];
	}

	include "header.php";
	include "./templates/template_yourauctions_php.html";
	include "footer.php";
	
?>