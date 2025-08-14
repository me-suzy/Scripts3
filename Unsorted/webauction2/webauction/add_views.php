

<?php

/*
   Copyright (c), 1999, 2000 - phpauction.org
   Copyright (c), 2001, 2002 - webauction.de.vu

   Lizenz siehe lizenz.txt & license

   Die neueste Version kostenfrei zum Download unter:
   http://webauction.de.vu

*/


        require('./includes/messages.inc.php');
        require('./includes/config.inc.php');
	require("./header.php"); 

//-- get Auction Data


   $query = "select * from ".$dbfix."_users where nick=\"$TPL_nick\" AND password=\"$TPL_password\"";
   $result = mysql_query($query);
   if ( !$result )
   {
                print $ERR_101;
        exit;
   }
        
   $user_id  = mysql_result ( $result, 0, "id" );
   



//-- Make new id function
        
        function generate_id()
        {
                global $title, $description, $dbfix;
                $continue = true;
                $new_id = uniqid(""); 
                
                while($continue)
                {
                        mt_srand((double)microtime()*1000000); 
            $new_id= intval(mt_rand()+$SELL_title);
                        

                        $res = mysql_num_rows(mysql_query("SELECT * FROM ".$dbfix."_views WHERE id=\"$new_id\""));
            
                        if ($res>0)
                                $continue = true;
                        else
                                $continue = false;
                }
                
                
                return $new_id;
        }

                

//-- Make date

            $now_date = date("YmdHis");

//--  check viewlist


        
                
                        
                        $existe = mySQL_Fetch_Array(mySQL_Query("SELECT * FROM ".$dbfix."_views WHERE user='$user_id' AND auction ='$auction_id'"));
                        if($existe)
                                {
                                print "


<html>

<head>
<meta http-equiv=\"Content-Type\"
content=\"text/html; charset=iso-8859-1\">
<meta name=\"GENERATOR\" content=\"Microsoft FrontPage Express 2.0\">
<title></title>
</head>

<body bgcolor=\"#FFFFFF\" link=\"#000000\" vlink=\"#000000\"
alink=\"#000000\">
<div align=\"left\"> </div>

<p><br>
</p>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font size=\"3\" face=\"Arial\" color=\"#FF0000\"><b>Diese
Auktion wurde schon zu Ihrer Beobachtungsliste hinzugefügt! &nbsp;&nbsp;<font size=\"3\" face=\"Arial\" color=\"#000000\"><A HREF=item.php?id=$auction_id&action=$action>Zurück</A></b>



<p>&nbsp;</p>

</body>
</html>

                        
                        
                        ";

}
        //--  insert viewlist   
        
$queryz = "select * from ".$dbfix."_auctions where id='$auction_id'";
$resultz = mysql_query($queryz);
if (!$resultz)
                errorLogSQL();
        else
        {

  $ends_old  = mysql_result ( $resultz, 0, "ends" );
  $date_auc  = mysql_result ( $resultz, 0, "date" );
  $desc_auc  = mysql_result ( $resultz, 0, "description" );
  $pict_auc  = mysql_result ( $resultz, 0, "pict_url" );
  $cate_auc  = mysql_result ( $resultz, 0, "category" );
  $mbid_auc  = mysql_result ( $resultz, 0, "minimum_bid" );
  $resv_auc  = mysql_result ( $resultz, 0, "reserve_price" );
  $type_auc  = mysql_result ( $resultz, 0, "auction_type" );
  $dura_auc  = mysql_result ( $resultz, 0, "duration" );
  $loca_auc  = mysql_result ( $resultz, 0, "location" );
  $lzip_auc  = mysql_result ( $resultz, 0, "location_zip" );
  $ship_auc  = mysql_result ( $resultz, 0, "shipping" );
  $paym_auc  = mysql_result ( $resultz, 0, "payment" );
  $inte_auc  = mysql_result ( $resultz, 0, "international" );
  $ends_auc  = mysql_result ( $resultz, 0, "ends" );
  $cbid_auc  = mysql_result ( $resultz, 0, "current_bid" );
  $clos_auc  = mysql_result ( $resultz, 0, "closed" );
  $phot_auc  = mysql_result ( $resultz, 0, "photo_uploaded" );
  $quan_auc  = mysql_result ( $resultz, 0, "quantity" );
  $susp_auc  = mysql_result ( $resultz, 0, "suspended" );

  $ends_old = mktime( 
  substr($ends_old,8,2),     // Stunden
  substr($ends_old,10,2),    // Minuten
  substr($ends_old,12,2),    // Sekunden
  substr($ends_old,4,2),     // Monat
  substr($ends_old,6,2),     // Tag
  substr($ends_old,0,4)      // Jahr
  );

  $time_to_mail_is = $ends_old - $time_to_mail*24*60*60;  
  $time_to_mail_is = date("YmdHis", $time_to_mail_is);



$query2 = "select * from ".$dbfix."_users where id='$user_id'";
$result2 = mysql_query($query2);
if (!$result2)
                errorLogSQL();
        else
        {
$user_mail  = mysql_result ($result2,0,"email");
        
            if (!$existe){
            $views_id = generate_id();
        $query = "INSERT INTO ".$dbfix."_views VALUES ('$views_id' , '$auction_id','$user_id','$titel' ,'$date_auc' ,'$desc_auc' ,'$pict_auc' , '$cate_auc','$mbid_auc', '$resv_auc' ,'$type_auc' ,'$dura_auc','$loca_auc','$lzip_auc','$ship_auc','$paym_auc','$inte_auc','$ends_auc','$cbid_auc','$clos_auc','$phot_auc','$quan_auc','$susp_auc', '0', '$time_to_mail_is','$user_mail', '$bids_mail')";
      //$query = "INSERT INTO views VALUES ('$views_id', '$user_id', '$auction_id', '0', '$time_to_mail_is', '$bids_mail','$user_mail','$titel')";
                  //     $query = "INSERT INTO views VALUES ('$views_id', '$user_id', '$auction_id', '0', '$time_to_mail_is', '$bids_mail','$user_mail','$titel')";

                
  if (!mysql_query($query))
                        print $ERR_001.mysql_error()."$query";
                else
                {
                        
                        
                        
                        print "
                        
                        
   <html>

<head>
<meta http-equiv=\"Content-Type\"
content=\"text/html; charset=iso-8859-1\">
<meta name=\"GENERATOR\" content=\"Microsoft FrontPage Express 2.0\">
<title></title>
</head>

<body bgcolor=\"#FFFFFF\" link=\"#000000\" vlink=\"#000000\"
alink=\"#000000\">


&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font size=\"2\" face=\"Arial\" color=\"#008000\"><b>Diese Auktion wurde zu Ihrer Beobachtungsliste hinzugefügt! &nbsp;&nbsp;<font size=\"2\" face=\"Arial\" color=\"#000000\"><A HREF=\"item.php?id=$auction_id&action=$action\">Zurück</A></b>


<p><br>
</p>

</body>
</html>

                        
                        
                        ";
                 }
      }
   }
}

require('./footer.php');


?>
