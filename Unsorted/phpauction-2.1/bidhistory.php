<?
/*

   Copyright (c), 1999, 2000, 2002 - phpauction.org                  
   
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
   

   Include messages file & Connect to sql server & inizialize configuration variables */
   require('./includes/messages.inc.php');
   require('./includes/config.inc.php');
   require('./includes/auction_types.inc.php');

$filename = "counter/".$id.".txt";
if (!file_exists($filename)) 
{ $newfile = @fopen($filename, "w+");
$tfail = fopen("$filename", "w");
$faili = "1";
fwrite($tfail, "$faili", 500000);
fclose($tfail);
}

// Check filesize, if 0 then insert 1

$filesize=filesize($filename);
if ($filesize=="0")
{ $newfile = @fopen($filename, "w+") or die("Couldn't create file.");
$tfail = fopen("$filename", "w");
$faili = "1";
fwrite($tfail, "$faili", 500000);
fclose($tfail);
}

// Read file into string

$whattoread = @fopen($filename, "r") or die("Couldn't open file");
$file_contents = fread($whattoread, filesize($filename));
$new_file_contents=$file_contents+1;

// Save new number into the file

$tfail = fopen("$filename", "w");
fwrite($tfail, "$new_file_contents", 500000);
fclose($tfail);

$TPL_nr="$new_file_contents";


	if ( !empty($id) )
	{
		$sessionVars["CURRENT_ITEM"] = $id;
	
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
	$query = "SELECT * FROM PHPAUCTION_auctions WHERE id='$id'";
	$result = mysql_query ($query);
	if ( $result )
	{
		// look if such auction exists
		if ( mysql_num_rows($result)>0 )
		{
			$closed = intval(mysql_result($result,0,"closed"));

			// look if auction has a "closed" flag
			if ( $closed==1 )
			{
				print "<CENTER>".$std_font." $ERR_614 </FONT>";
			
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
			print "<CENTER>".$std_font." $ERR_606 </FONT>";
			include "footer.php";
			exit;
		}
	}
	else
	{
		print "<CENTER>".$std_font." $ERR_001 </FONT>";
		include "footer.php";
		exit;
	}

	$sessionVars["CURRENT_ITEM"] = $id;


   // --------------------------------------------------------------------
   $auction_id = $id;

   $TPL_BIDS_value = "<A HREF=\"item.php?SESSION_ID=$sessionIDU&id=$auction_id\">$MSG_507</A> | ";
   
/* get auction data  */
   $query = "select * from PHPAUCTION_auctions where id=\"$auction_id\"";
   $result = mysql_query($query);
   if ( !$result )
   {
		print $ERR_001;
        exit;
   }
        
   $user           = stripslashes(mysql_result ( $result, 0, "user" ));
   $title          = stripslashes(mysql_result ( $result, 0, "title" ));
   $date           = mysql_result ( $result, 0, "starts" );
   $description    = stripslashes(mysql_result ( $result, 0, "description" ));
   $pict_url       = mysql_result ( $result, 0, "pict_url" );
   $category       = mysql_result ( $result, 0, "category" );
   $minimum_bid    = mysql_result ( $result, 0, "minimum_bid" );
   $reserve_price  = mysql_result ( $result, 0, "reserve_price" );
   $auction_type   = mysql_result ( $result, 0, "auction_type" );
   $location       = mysql_result ( $result, 0, "location" );
   $customincrement = mysql_result ( $result, 0, "increment" );
   $location_zip   = mysql_result ( $result, 0, "location_zip" );
   $shipping       = mysql_result ( $result, 0, "shipping" );
   $payment        = mysql_result ( $result, 0, "payment" );
   $international  = mysql_result ( $result, 0, "international" );
   $ends           = mysql_result ( $result, 0, "ends" );
   $current_bid    = mysql_result ( $result, 0, "current_bid" );
$phu = intval(mysql_result( $result,0,"photo_uploaded"));

if ( $phu!=0 )
	$pict_url = "uploaded/".$pict_url;

   $TPL_id_value           = $auction_idid;
   $TPL_title_value        = $title;
   $TPL_description_value  = $description;
   $TPL_pict_url_value     = $pict_url;

$atype = intval(mysql_result($result,0,"auction_type"));
$iquantity = mysql_result ($result,0,"quantity");

$TPL_auction_type = $auction_types[$atype];
/*if ($atype==2)
{*/
	$TPL_auction_quantity = $iquantity;
/*}*/


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
	
	
	
	
	
	

/* get user's nick */
   $query      = "select id,nick from PHPAUCTION_users where id=\"$user\"";
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
   $query       = "select max(bid) as maxbid from PHPAUCTION_bids where auction=\"$auction_id\" group by auction";
   $result_bids = mysql_query ( $query) ;
   if ( !$result_bids )
   {
		print $MSG_001;
        exit;
   }
 
   if ( mysql_num_rows($result_bids ) > 0)
   {
		$high_bid       = mysql_result ( $result_bids, 0, "maxbid" );
        $query          = "select bidder from PHPAUCTION_bids where bid=$high_bid and auction='$id'";
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
		$query          = "select * from PHPAUCTION_bids where auction=\"$auction_id\"ORDER BY bidwhen DESC";
        $result_numbids = mysql_query ( $query );
        if ( !$result_numbids )
		{
				print $ERR_001;
                exit;
        }
        $num_bids = mysql_num_rows ( $result_numbids );

        /* Get bidder nickname */
        $query         = "select id,nick from PHPAUCTION_users where id=\"$high_bidder_id\"";
        $result_bidder = mysql_query($query);
        if ( !$result_bidder )
		{
            print $ERR_001;
            exit;
        }
        $high_bidder_id   = mysql_result ( $result_bidder, 0, "id" );
        $high_bidder_nick = mysql_result ( $result_bidder, 0, "nick" );
                
        }
                
	    /* Get bid increment for current bid and calculate minimum bid */
        $query = "select increment from PHPAUCTION_increments where
                                 ((low<=$high_bid AND high>=$high_bid) OR
                                  (low<$high_bid AND high<$high_bid)) order by increment desc";
	  	$result_incr = mysql_query  ( $query );
	if(mysql_num_rows($result_incr) != 0)
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

              
        if ( $pict_url )
		{
			$TPL_pict_url_value  = "<A HREF=\"#image\"><IMG SRC=\"./images/picture.gif\" BORDER=0></A>"
                                  ."<A HREF=\"#image\">$MSG_108</A>"
                                  ."<IMG SRC=\"images/transparent.gif\" WIDTH=15>";
        }

        /* Get current number of feedbacks */
        $query          = "select rated_user_id from PHPAUCTION_feedbacks where rated_user_id=\"$user_id\"";
        $result         = mysql_query   ( $query );
        $num_feedbacks  = mysql_num_rows ( $result );

        /* Get current total rate value for user */
        $query         = "select rate_sum from PHPAUCTION_users where nick=\"$user_nick\"";
        $result        = mysql_query  ( $query );
        $total_rate    = mysql_result ( $result, 0, "rate_sum" );           

        $TPL_vendetor_value = " <A HREF=\"profile.php?user_id=".$user_id."\"><b>".$TPL_user_nick_value."</b></A>";

        if ( $num_feedbacks > 0 )
		{
			$rate_ratio = round( $total_rate / $num_feedbacks );
        }
		else
		{
			$rate_ratio = 0;
        }

		$TPL_rate_radio = "<IMG SRC=\"./images/estrella_".$rate_ratio.".gif\" ALIGN=TOP>";
	$TPL_num_feedbacks="<B>($num_feedbacks)</B>";

        /* High bidder  */
       if ( $high_bidder_id ){
          $TPL_hight_bidder_id  = "<TR><TD WIDTH=\"30%\"><B>$std_font $MSG_117:</B>
                               
   </FONT></TD><TD><FONT FACE=\"Verdana,Arial, Helvetica, sans-serif\" SIZE=\"2\">"
                                  ."<A HREF=\"profile.php?auction_id=$auction_idid&user=$high_bidder_nick\">$high_bidder_nick</A></FONT>";
                
        /* Get current number of feedbacks */
        $query		   = "select rated_user_id from PHPAUCTION_feedbacks where rated_user_id=\"$high_bidder_id\"";
        $result		   = mysql_query ( $query );
        $num_feedbacks = mysql_num_rows($result);

    	/* Get current total rate value for user */
    	$query = "select rate_sum,nick,id from PHPAUCTION_users where nick=\"$high_bidder_nick\"";
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
   							
   								"<B> $std_font $MSG_117:</B>".
   								"</FONT> </TD> <TD>".
   						 "<A HREF=\"profile.php?id=$userid\"><B>$std_font $high_bidder_nick</A> ($num_feedbacks) </B><IMG SRC=\"./images/estrella_".$rate_ratio.".gif\" ALIGN=TOP>".
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

   $difference = intval( mktime( $ends_hours,$ends_minutes,$ends_seconds,$ends_month,$ends_day,$ends_year)) -     intval(mktime($hours,$minutes,$seconds,$month,$day,$year) );
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

   $TPL_num_bids_value  = $num_bids;
   $TPL_currency_value1 = print_money($minimum_bid);
   $TPL_currency_value2 = print_money($high_bid);
   $TPL_currency_value3 = print_money($increment);
   $TPL_currency_value4 = print_money($next_bid);

/* Bidding table */
   $TPL_next_bid_value   = $next_bid;
   $TPL_user_id_value    = $user_id;
   $TPL_title_value      = $title;
   $TPL_category_value   = $category;
   $TPL_id_value         = $auction_id;

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
   include ("./includes/countries.inc.php");
   while ( list($key, $val) = each ($countries) )
   {
			if ( $val = $location )
			{
				$location_name = $val;
            }
   }
   $TPL_location_name_value = $countries[$location_name];
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

   $query = "select cat_id,parent_id,cat_name from PHPAUCTION_categories where cat_id='$category'";
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
				$query = "select cat_id,parent_id,cat_name from PHPAUCTION_categories where cat_id='$j'";
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
		print "<CENTER>$std_font $ERR_605 </FONT></CENTER>";
		include "footer.php";
		exit;
	     }
  
   } while ( $parent_id != 0 );

   for ($j=$i-1; $j>=0; $j--)
   {
		if ( $j == 0)
		{
           $TPL_cat_value .= "<A href=\"browse.php?id=".$c_id[$j]."&SESSION_ID=$sessionIDU\">".$c_name[$j]."</A>";
        }
		else
		{
           $TPL_cat_value .= "<A href=\"browse.php?id=".$c_id[$j]."&SESSION_ID=$sessionIDU\">".$c_name[$j]."</A> / ";
        }
   }

   include ("./templates/template_item_php.html");

//-- Display bids history               

					include ("./templates/template_bidhistory_php.html");
       
                $BgColor = "#EBEBEB";
                $TPL_info_value = "";
                $i = 0;
               	while  ( $i < $num_bids )
				{
					$bidder         = mysql_result( $result_numbids, $i, "bidder");
					$bid            = mysql_result( $result_numbids, $i, "bid");
					$when           = mysql_result( $result_numbids, $i, "bidwhen");
                        
                    //--Get bidder's nickname
                    $query = "select id,nick from PHPAUCTION_users where id=\"$bidder\"";
                    $result_bidder = mysql_query($query);
                    if(!$result)
					{
						print $ERR_001;
						exit;
                    }
                       
	 			    $bidder_name = mysql_result ( $result_bidder,0,"nick" );
                    $bidder_id   = mysql_result ( $result_bidder,0,"id" );

				$quantity_bid = intval(mysql_result($result_numbids, $i, "quantity"));
                        
                    //-- Format bid date
                    $year    = substr($when,0,4);
                    $month   = substr($when,4,2);
                    $day     = substr($when,6,2);
                    $hours   = substr($when,8,2);
                    $minutes = substr($when,10,2);
                    $seconds = substr($when,12,2);

                    if($BgColor == "#EBEBEB")
					{
 						$BgColor = "#FFFFFF";
                    }
					else
					{
						$BgColor = "#EBEBEB";
                    }

                    $TPL_info_value  .= "<TR VALIGN=\"top\" BGCOLOR=\"$BgColor\">"
                                     ."<TD WIDTH=\"33%\">"
                                     ."<FONT FACE=\"Verdana,Helvetica,Arial\" SIZE=2>"
                                     ."<A HREF=\"./profile.php?id=$bidder_id&SESSION_ID=$sessionIDU\">$bidder_name"
                                     ."</TD>"
                                     ."<TD WIDTH=\"33%\" ALIGN=\"center\">$std_font ".print_money($bid)."
                                     </TD>"
                                     ."<TD WIDTH=\"33%\" ALIGN=\"center\">
                  $std_font ".$month."-".$day."-".$year.", ".$hours.":".$minutes.":".$seconds
                                     ."</TD>"
							  .(
								($atype==2)?(
									"<TD ALIGN=CENTER>$std_font ".$quantity_bid." </FONT></TD>"
								):""
							  )
                                     ."</TR>";
                    $i++;
                }
                $TPL_info_value .= "</TABLE></CENTER>";
                
			    print $TPL_info_value;
    print "</TD></TR></TABLE></TD></TR></TABLE>";
  
    require("footer.php");
?>