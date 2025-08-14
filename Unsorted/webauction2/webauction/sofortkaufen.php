<?
        require('./includes/messages.inc.php');
        require('./includes/config.inc.php');




//-- get Auction  Data

   $query = "select * from  ".$dbfix."_auctions where id=\"$id\"";
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
   $sofortkauf  = mysql_result ( $result, 0, "sofortkauf" );



   $now_date = date("YmdHis");

   $query1 = "select * from  ".$dbfix."_users where nick=\"$nick\"";

   $result1 = mysql_query($query1);
   if ( !$result1 )
   {
                print $ERR_001;
        exit;
   }

   $userid        = mysql_result ( $result1, 0, "id" );

   $query = "INSERT INTO  ".$dbfix."_bids VALUES ('$id','$userid','$sofortkauf','$now_date','0' )";

   $sql_update = "update  ".$dbfix."_auctions set ends='$now_date',current_bid='$sofortkauf' where id='$id'";
                        if (!mysql_query($sql_update)){
                        print $ERR_001.mysql_error()."$query";
              }

//-- Counter Update

            $sql = "select  ".$dbfix."_auctions from counters";
                        $result_counters = mysql_query($sql);
                        if($result_counters){
                    $auction_counter = mysql_result($result_counters,0,"auctions") - 1;
                        $sql_update = "update  ".$dbfix."_counters set auctions = $auction_counter";
                        $result = mysql_query($sql_update);
                        }

// and increase category counters

                $ct = $category ;

                $row = mysql_fetch_array(mysql_query("SELECT * FROM  ".$dbfix."_categories WHERE cat_id=$ct"));

                $counter = $row[counter]-1;

                $subcoun = $row[sub_counter]-1;

                $parent_id = $row[parent_id];

                mysql_query("UPDATE  ".$dbfix."_categories SET counter=$counter, sub_counter=$subcoun WHERE cat_id=$ct");



                        // update recursive categories

                while ( $parent_id!=0 )

                {

                        // update this parent's subcounter

                                $rw = mysql_fetch_array(mysql_query("SELECT * FROM  ".$dbfix."_categories WHERE cat_id=$parent_id"));

                                $subcoun = $rw[sub_counter]-1;

                                mysql_query("UPDATE  ".$dbfix."_categories SET sub_counter=$subcoun WHERE cat_id=$parent_id");

                        // get next parent

                                $parent_id = intval($rw[parent_id]);

                }

                        if (!mysql_query($query))
                        print $ERR_001.mysql_error()."$query";
                else
  {






                        header ("location:item.php?SESSION_ID=$SESSION_ID&user_id=$user_id&TPL_nick=$TPL_nick&TPL_password=$TPL_password&name=$name&page=$page");
                }




?>