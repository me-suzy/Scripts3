<?
/*
   Copyright (c), 1999, 2000 - phpauction.org
   Copyright (c), 2001, 2002 - webauction.de.vu

   Lizenz siehe lizenz.txt & license

   Die neueste Version kostenfrei zum Download unter:
   http://webauction.de.vu

*/

       /* Include messages file & Connect to sql server & inizialize configuration variables */
	require('includes/messages.inc.php');
	require('includes/config.inc.php');
	require('includes/auction_types.inc.php');

	if ( !empty($id) )
	{
		$sessionVars["CURRENT_ITEM"] = $id;
		putSessionVars();
	}
	elseif ( empty($id) && !empty($sessionVars["CURRENT_ITEM"]) )
	{
		$id = $sessionVars["CURRENT_ITEM"];
	}
	elseif ( empty($id) && empty($sessionVars["CURRENT_ITEM"]) )
	{
		// error message
		include "header.php";
		print "<CENTER>$std_font $ERR_605 </FONT></CENTER>";
		include "footer.php";
		exit;
	}

	require("header.php");

 // now check if requested auction exists and is/isn't closed
	$AuctionIsClosed = false;
	$query = "SELECT * FROM ".$dbfix."_auctions WHERE id='$id'";
	$result = mysql_query ($query);
	if ( $result )
	{
		// look if such auction exists
		if ( mysql_num_rows($result)>0 )
		{
			$closed = intval(mysql_result($result,0,"closed"));

			// look if auction has a "closed" flag
			if ( $closed==0 )
			{
				print "<CENTER>".$std_font." $ERR_614 </FONT>";
				include "footer.php";
				exit;
			}
			else
			{
				// look if auction isn't closed
				$c = mysql_result($result,0,"ends");
				$c = mktime ( 
					substr($c,8,2),// hour
					substr($c,10,2),// min
					substr($c,12,2),// sec
					substr($c,4,2),// month
					substr($c,6,2),// day
					substr($c,0,4)// year
				);
				if ( $c<=time() )
				{
					// auction is closed - set flag
					$AuctionIsClosed = true;
				}
			}
		}
		else
		{
			print "<CENTER>".$std_font." $ERR_606 </FONT></CENTER>";
			include "footer.php";
			exit;
		}
	}
	else
	{
		print "<CENTER>".$std_font." $ERR_001 </FONT></CENTER>";
		include "footer.php";
		exit;
	}

	$sessionVars["CURRENT_ITEM"] = $id;
	putSessionVars();


	$query = "select bid from ".$dbfix."_bids where auction=\"$id\"";
	$result = mysql_query($query);
	if ( mysql_num_rows($result ) > 0)
	{
		$TPL_BIDS_value = "<A HREF=\"bidhistory.php?SESSION_ID=".urlencode($sessionID)."&id=$id\">$MSG_105</A> | ";
	}

	/* get auction data  */
	$query = "select * from ".$dbfix."_auctions where id='$id'";
	$result = mysql_query($query);
	if ( !$result )
	{
		print $ERR_001;
	   exit;
	}

	$user           = stripslashes(mysql_result ( $result, 0, "user" ));
	$title          = stripslashes(mysql_result ( $result, 0, "title" ));
	$date           = mysql_result ( $result, 0, "date" );
	$description    = stripslashes(mysql_result ( $result, 0, "description" ));
	$pict_url       = mysql_result ( $result, 0, "pict_url" );
	$category       = mysql_result ( $result, 0, "category" );
	$minimum_bid    = mysql_result ( $result, 0, "minimum_bid" );
	$reserve_price  = mysql_result ( $result, 0, "reserve_price" );
	$auction_type   = mysql_result ( $result, 0, "auction_type" );
	$location       = mysql_result ( $result, 0, "location" );
	$location_zip   = mysql_result ( $result, 0, "location_zip" );
	$shipping       = mysql_result ( $result, 0, "shipping" );
	$payment        = mysql_result ( $result, 0, "payment" );
	$international  = mysql_result ( $result, 0, "international" );
	$ends           = mysql_result ( $result, 0, "ends" );
	$current_bid    = mysql_result ( $result, 0, "current_bid" );
	$phu = intval(mysql_result( $result,0,"photo_uploaded"));
	$atype = intval(mysql_result($result,0,"auction_type"));
	$iquantity = mysql_result ($result,0,"quantity");

	$TPL_auction_type = $auction_types[$atype];
/*	if ($atype==2)
	{ */
		$TPL_auction_quantity = $iquantity;
/*	} */

	
	
	
	
	//-- Get RESERVE PRICE information
	
	if ($reserve_price > 0)
	{
		$TPL_reserve_price = "$MSG_030 ";
		
		//-- Has someone reached the reserve price?
	

                   if($current_bid < $reserve_price)
		{
			$TPL_reserve_price .= " ($MSG_514)";
		}
		else
		{
			$TPL_reserve_price .= " ($MSG_515) </FONT>";
		}
	}
	else
	{
		$TPL_reserve_price = "$MSG_029";
	}
	
	
	
	
	
	
	$TPL_id_value           = $id;
	$TPL_title_value        = $title;
	$TPL_description_value  = $description;
	//print $phu;

	// see if it's an uploaded picture
	if ( $phu!=0 )
		$pict_url = $uploaded_path.$pict_url;

	/* get user's nick */
	$query      = "SELECT id,nick FROM ".$dbfix."_users WHERE id='$user'";
	$result_usr = mysql_query ( $query );
	if ( !$result_usr )
	{
		print $MSG_001;
		exit;
	}
	$user_nick			= mysql_result ( $result_usr, 0, "nick");
	$user_id				= mysql_result ( $result_usr, 0, "id");
	$TPL_user_nick_value = $user_nick;


	/* get bids for current item */
	$query = "SELECT MAX(bid) AS maxbid FROM ".$dbfix."_bids WHERE auction='$id' GROUP BY auction";
	$result_bids = mysql_query ( $query) ;
	if ( !$result_bids )
	{
		print $MSG_001;
	   exit;
	}

	if ( mysql_num_rows($result_bids ) > 0)
	{
	   $high_bid       = mysql_result ( $result_bids, 0, "maxbid" );
	   $query          = "select bidder from ".$dbfix."_bids where bid=$high_bid and auction='$id'";
	   $result_bidder  = mysql_query ( $query );
	   $high_bidder_id = mysql_result ( $result_bidder, 0, "bidder" );
	}

	if ( !mysql_num_rows($result_bids) )
	{
	   $num_bids = 0;
	   $high_bid = 0;
	}
	else
	{
		/* Get number of bids  */
		$query          = "select bid from ".$dbfix."_bids where auction='$id'";
	   $result_numbids = mysql_query ( $query );
	   if ( !$result_numbids )
		{
				print $ERR_001;
			 exit;
	   }
	   $num_bids = mysql_num_rows ( $result_numbids );

	   /* Get bidder nickname */
	   $query         = "select id,nick from ".$dbfix."_users where id='$high_bidder_id'";
	   $result_bidder = mysql_query($query);
	   if ( !$result_bidder )
	   {
		  print $ERR_001;
		  exit;
	   }
	   $high_bidder_id   = mysql_result ( $result_bidder, 0, "id" );
	   $high_bidder_nick = mysql_result ( $result_bidder, 0, "nick" );

	   }

		/* Get bid increment for current bis and calculate minimum bid */
		$query = "SELECT increment FROM ".$dbfix."_increments WHERE".
			"((low<=$high_bid AND high>=$high_bid) OR".
			"(low<$high_bid AND high<$high_bid)) ORDER BY increment DESC";

	$result_incr = mysql_query  ( $query );
	if(mysql_num_rows($result_incr) != 0) {
		$increment   = mysql_result ( $result_incr, 0, "increment" );
        }
        

	if ($high_bid == 0)
	{
	$query = "SELECT increment FROM ".$dbfix."_increments WHERE".
		  "((low<=$minimum_bid AND high>=$minimum_bid) OR".
		  "(low<$minimum_bid AND high<$minimum_bid)) ORDER BY increment DESC";
		  	
		  $result_incr = mysql_query  ( $query );
		  		
	  $increment   = mysql_result ( $result_incr, 0, "increment" );  
	  
	  $next_bid = $minimum_bid;
	}
	else
	{
	  $next_bid = $high_bid + $increment;
	}

   if ( $pict_url )
		{
			$TPL_pict_url_value  = "<A HREF=\"#image\"><IMG SRC=\"./images/picture.gif\" BORDER=0></A>"
							."<A HREF=\"#image\">$MSG_108</A>"
							."<IMG SRC=\"images/transparent.gif\" WIDTH=15>";
	   }


	   /* Get current number of feedbacks */
	   $query          = "SELECT rated_user_id FROM ".$dbfix."_feedbacks WHERE rated_user_id='$user_id'";
	   $result         = mysql_query   ( $query );
	   $num_feedbacks  = mysql_num_rows ( $result );

	   /* Get current total rate value for user */
	   $query         = "SELECT rate_sum FROM ".$dbfix."_users WHERE nick='$user_nick'";
	   $result        = mysql_query  ( $query );
	   $total_rate    = mysql_result ( $result, 0, "rate_sum" );           
           $TPL_all = " <A HREF=\"alluser.php?SESSION_ID=".urlencode($sessionIDU)."&user_id=".$user_id."\">$MSG_1002</A>";
	   $TPL_vendetor_value = " <A HREF=\"profile.php?SESSION_ID=".urlencode($sessionIDU)."&user_id=".$user_id."\">".$TPL_user_nick_value."</A>";

	   if ( $num_feedbacks > 0 )
		{
			$rate_ratio = round( $total_rate / $num_feedbacks );
	   }
		else
		{
			$rate_ratio = 0;
	   }

		$TPL_rate_radio = "<IMG SRC=\"./images/estrella_".$rate_ratio.".gif\" ALIGN=TOP>";

	   /* High bidder  */
	  if ( $high_bidder_id ){
		$TPL_hight_bidder_id  = "<TR><TD WIDTH=\"30%\"><FONT FACE=\"Verdana,Arial, Helvetica, sans-serif\" SIZE=\"2\"><B>"
							.$MSG_117.":</B></FONT></TD><TD><FONT FACE=\"Verdana,Arial, Helvetica, sans-serif\" SIZE=\"2\">"
							."<A HREF=\"profile.php?SESSION_ID=".urlencode($sessionIDU)."&user=$high_bidder_nick\">$high_bidder_nick</A></FONT>";





	   /* Get current number of feedbacks */
	   $query		   = "select rated_user_id from ".$dbfix."_feedbacks where rated_user_id=\"$high_bidder_id\"";
	   $result		   = mysql_query ( $query );
	   $num_feedbacks = mysql_num_rows($result);

	   /* Get current total rate value for user */
	   $query = "select rate_sum,nick,id from ".$dbfix."_users where nick=\"$high_bidder_nick\"";
	   $result = mysql_query($query);

	   $total_rate = mysql_result($result,0,"rate_sum");           
		$nickname = mysql_result($result,0,"nick");
		$userid = mysql_result($result,0,"id");

	   if ( $num_feedbacks > 0 )
		{
			$rate_ratio = round($total_rate / $num_feedbacks);
	   }
		else
		{
		  $rate_ratio = 0;
	   }

		$TPL_hight_bidder_id = 
		"<TR>".
	   "<TD WIDTH=\"30%\">".
	   "<FONT FACE=\"Verdana,Arial, Helvetica, sans-serif\" SIZE=\"2\">".
	   "<B>$MSG_117:</B>".
	   "</FONT> </TD> <TD>".
	   "<FONT FACE=\"Verdana,Arial, Helvetica, sans-serif\" SIZE=\"2\">".
	   "<A HREF=\"profile.php?SESSION_ID=".urlencode($sessionIDU)."&id=$userid\">$high_bidder_nick</A> <IMG SRC=\"./images/estrella_".$rate_ratio.".gif\" ALIGN=TOP>".
	   "</FONT>".
	   "</TD> </TR>";
	}

	$year          = intval ( date("Y"));
	$month         = intval ( date("m"));
	$day           = intval ( date("d"));
	$hours         = intval ( date("H"));
	$minutes       = intval ( date("i"));
	$seconds       = intval ( date("s"));
	$ends_year     = substr ( $ends, 0, 4 );
	$ends_month    = substr ( $ends, 4, 2 );
	$ends_day      = substr ( $ends, 6, 2 );
	$ends_hours    = substr ( $ends, 8, 2 );
	$ends_minutes  = substr ( $ends, 10, 2 );
	$ends_seconds  = substr ( $ends, 12, 2 );

	#   $difference = intval( mktime( $ends_hours,$ends_minutes,$ends_seconds,$ends_month,$ends_day,$ends_year)) - intval(mktime($hours,$minutes,$seconds,$month,$day,$year));
	$difference = intval( mktime( $ends_hours,$ends_minutes,$ends_seconds,$ends_month,$ends_day,$ends_year)) - time();
    if ($difference > 0) {
    	$TPL_days_difference_value = intval($difference / 86400).$MSG_126;
    	$difference = $difference - ($TPL_days_difference_value * 86400);

    	$hours_difference = intval($difference / 3600);
    	if(strlen($hours_difference) == 1)
    	{
    		$hours_difference = "0".$hours_difference;
    	}
    	$TPL_hours_difference_value = $hours_difference.":";

    	$difference = $difference - ($hours_difference * 3600);
    	$minutes_difference = intval($difference / 60);
    	if (strlen($minutes_difference) == 1)
    	{
    		$minutes_difference = "0".$minutes_difference;
    	}
    	$TPL_minutes_difference_value  = $minutes_difference.":";

    	$difference = $difference - ($minutes_difference * 60);
    	$seconds_difference = $difference;
    	if (strlen($seconds_difference) == 1)
    	{
    		$seconds_difference = "0".$seconds_difference;
    	}
    	$TPL_seconds_difference_value = $seconds_difference;
    } else {
        $TPL_days_difference_value = "$err_font $MSG_911 </FONT>";
        $TPL_hours_difference_value = "";
        $TPL_minutes_difference_value = "";
        $TPL_seconds_difference_value = "";
    }
    
	$TPL_num_bids_value  = $num_bids;
	$TPL_currency_value1 = $currency." ".number_format($minimum_bid,0,",",".");
	$TPL_currency_value2 = $currency." ".number_format($high_bid,0,",",".");
	$TPL_currency_value3 = $currency." ".number_format($increment,0,",",".");
	$TPL_next_bid = $currency." ".number_format($next_bid,0,",",".");

	/* Bidding table */
	$TPL_next_bid_value   = $next_bid;
	$TPL_user_id_value    = $user_id;
	$TPL_title_value      = $title;
	$TPL_category_value   = $category;
	$TPL_id_value         = $id;

	/* Description & Image table */
	$TPL_description_value = nl2br($description);

	if ( $pict_url )
	{
		$TPL_pict_url = "<IMG SRC=\"$pict_url\" BORDER=0>";
	}
	else
	{
		$TPL_pict_url = "<B>$MSG_114</B>";
	}


	/* Get location description */
	include ("includes/countries.inc.php");
	while ( list($key, $val) = each ($countries) )
	{
			if ( $val = $location )
			{
				$location_name = $countries[$val];
		  }
	}
	$TPL_location_name_value = $location_name;
	$TPL_location_zip_value  = "(".$location_zip.")";

	if ( $shipping == '1' )
	{
		$TPL_shipping_value = $MSG_038;
	}
	else
	{
		$TPL_shipping_value = $MSG_032;
	}

	if ( $international )
	{
		$TPL_international_value = ", $MSG_033";
	}
	else
	{
		$TPL_international_value = ", $MSG_043";
	}

	/* Get Payment methods */
	$payment_methods = explode("\n",$payment);
	$i = 0;
	$c = count($payment_methods);
	$began = false;
	while ($i<$c)
	{
		if (strlen($payment_methods[$i])!=0 )
		{
			if ($began)
				$TPL_payment_value .= ", ";
			else
				$began = true;

			$TPL_payment_value .= trim($payment_methods[$i]);
		}
		$i++;
	}

	$year     = substr($date,0,4);
	$month    = substr($date,4,2);
	$day      = substr($date,6,2);
	$hours    = substr($date,8,2);
	$minutes  = substr($date,10,2);
	$seconds  = substr($date,12,2);

	$date_string   = ArrangeDate($day,$month,$year,$hours,$minutes);
	$TPL_date_string1 = $date_string;

	$year    = substr($ends,0,4);
	$month   = substr($ends,4,2);
	$day     = substr($ends,6,2);
	$hours   = substr($ends,8,2);
	$minutes = substr($ends,10,2);

	$date_string   = ArrangeDate($day,$month,$year,$hours,$minutes);
	$TPL_date_string2 = $date_string;

	$c_name[] = array(); $c_id[] = array();
	$TPL_cat_value = "";

	$query = "SELECT cat_id,parent_id,cat_name FROM ".$dbfix."_categories WHERE cat_id='$category'";
	$result = mysql_query($query);
	if ( !$result )
	{
		print $ERR_001;
	   exit;
	}
	$result     = mysql_fetch_array ( $result );
	$parent_id  = $result[parent_id];
	$cat_id     = $categories;

	$j = $category; 
	$i = 0;
	do {
				$query = "SELECT cat_id,parent_id,cat_name FROM ".$dbfix."_categories WHERE cat_id='$j'";
			 $result = mysql_query($query);
			 if ( !$result )
				{
					print $ERR_001;
					exit;
			 }
			$result = mysql_fetch_array ( $result );
			if ( $result )
			  {
					$parent_id  = $result[parent_id];
					$c_name[$i] = $result[cat_name];
					$c_id[$i]   = $result[cat_id];
					$i++;
					$j = $parent_id;
			} else {
				// error message
				print "<CENTER>$err_font $ERR_620 </FONT></CENTER>";
				include "footer.php";
				exit;
			}
	} while ( $parent_id != 0 );

	for ($j=$i-1; $j>=0; $j--)
	{
		if ( $j == 0)
		{
		 $TPL_cat_value .= "<A href=\"browse.php?SESSION_ID=$sessionIDU&id=".$c_id[$j]."\">".$c_name[$j]."</A>";
	   }
		else
		{
		 $TPL_cat_value .= "<A href=\"browse.php?SESSION_ID=$sessionIDU&id=".$c_id[$j]."\">".$c_name[$j]."</A> / ";
	   }
	}


	include ("./templates/item_php3.html");
	require("footer.php");
?>
