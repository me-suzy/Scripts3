<?php
/*
   Copyright (c), 1999, 2000 - phpauction.org
   Copyright (c), 2001, 2002 - webauction.de.vu

   Lizenz siehe lizenz.txt & license

   Die neueste Version kostenfrei zum Download unter:
   http://webauction.de.vu

*/

	require('includes/messages.inc.php');
	require('includes/config.inc.php');

//-- get Auction  Data


   $query = "select * from ".$dbfix."_auctions where id=\"$auction_id\"";
   $result = mysql_query($query);
   if ( !$result )
   {
		print $ERR_001;
        exit;
   }
        
   $user_id        = mysql_result ( $result, 0, "user" );
   $title          = mysql_result ( $result, 0, "title" );
   $description    = mysql_result ( $result, 0, "description" );
   $pict_url       = mysql_result ( $result, 0, "pict_url" );
   $category       = mysql_result ( $result, 0, "category" );
   $reserve_price  = mysql_result ( $result, 0, "reserve_price" );
   $auction_type   = mysql_result ( $result, 0, "auction_type" );
   $location       = mysql_result ( $result, 0, "location" );
   $location_zip   = mysql_result ( $result, 0, "location_zip" );
   $shipping       = mysql_result ( $result, 0, "shipping" );
   $payment        = mysql_result ( $result, 0, "payment" );
   $photo_uploaded = mysql_result ( $result, 0, "photo_uploaded" );
   $international  = mysql_result ( $result, 0, "international" );
   $reserve_price  = mysql_result ( $result, 0, "reserve_price" );



//-- Make new id
	
	function generate_id()
	{
		global $title, $description, $dbfix;
		$continue = true;
		$new_id = uniqid("");
		
		while($continue)
		{
			mt_srand((double)microtime()*1000000); 
            $new_id= intval(mt_rand()+$SELL_title);
			

			$res = mysql_num_rows(mysql_query("SELECT * FROM ".$dbfix."_auctions WHERE id=\"$new_id\""));

			if ($res>0)
				$continue = true;
			else
				$continue = false;
		}
		
		
		return $new_id;
	}

		
//-- Make new ends

		    $now = date("YmdHis");

            $now = mktime( 
					    substr($now,8,2),     // Stunden
					    substr($now,10,2),    // Minuten
					    substr($now,12,2),    // Sekunden
					    substr($now,4,2),     // Monat
					    substr($now,6,2),     // Tag
					    substr($now,0,4)      // Jahr
				        );

			$new_ends = $now+$duration*24*60*60; 
	        $new_ends = date("YmdHis", $new_ends);

//-- Make new date

            $now_date = date("YmdHis");

//--  Make new Auction
            //$new_auction_id = generate_id();
		    //$query = "INSERT INTO ".$dbfix."_auctions VALUES ('$new_auction_id', '$user_id', '$title', '$now_date', '$description', '$pict_url', '$category', '$minimum_bid', '$reserve_price', '$auction_type', '$duration', '$location', '$location_zip', '$shipping', '$payment', '$international', '$new_ends', '0.0000', '0', '$photo_uploaded', '1', '0')";

            $sql_update = "update  ".$dbfix."_auctions set date='$now_date', ends='$new_ends', closed='0' where id='$auction_id'";
			if (!mysql_query($sql_update)){
			print $ERR_001.mysql_error()."$query";
              }

//-- Counter Update

            $sql = "select auctions from ".$dbfix."_counters";
			$result_counters = mysql_query($sql);
			if($result_counters){
		    $auction_counter = mysql_result($result_counters,0,"auctions") + 1;
			$sql_update = "update ".$dbfix."_counters set auctions = $auction_counter";
			$result = mysql_query($sql_update);
			}

// and increase category counters

		$ct = $category ;

		$row = mysql_fetch_array(mysql_query("SELECT * FROM ".$dbfix."_categories WHERE cat_id=$ct"));

		$counter = $row[counter]+1;

		$subcoun = $row[sub_counter]+1;

		$parent_id = $row[parent_id];

		mysql_query("UPDATE ".$dbfix."_categories SET counter=$counter, sub_counter=$subcoun WHERE cat_id=$ct");



			// update recursive categories

		while ( $parent_id!=0 )

		{

			// update this parent's subcounter

				$rw = mysql_fetch_array(mysql_query("SELECT * FROM ".$dbfix."_categories WHERE cat_id=$parent_id"));

				$subcoun = $rw[sub_counter]+1;

				mysql_query("UPDATE ".$dbfix."_categories SET sub_counter=$subcoun WHERE cat_id=$parent_id");

			// get next parent

				$parent_id = intval($rw[parent_id]);

		}

                        if (!mysql_query($query))
			print $ERR_001.mysql_error()."$query";
		else
  {
   




			
			header ("location:my_closed_auctions_restart.php?SESSION_ID=$SESSION_ID&user_id=$user_id&TPL_nick=$TPL_nick&TPL_password=$TPL_password&name=$name&page=$page");
		}




?>
