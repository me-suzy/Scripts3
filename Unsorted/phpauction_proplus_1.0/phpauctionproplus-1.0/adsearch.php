<?#//v.1.0.0

#///////////////////////////////////////////////////////
#//  COPYRIGHT 2002 PHPAuction.org ALL RIGHTS RESERVED//
#//  For Source code for the GPL version go to        //  
#//  http://phpauction.org and download               //
#//  Supplied by CyKuH [WTN]                          //
#///////////////////////////////////////////////////////


        require('./includes/messages.inc.php');
        require('./includes/config.inc.php');
        require('./includes/countries.inc.php');
        include "header.php";




 if (($HTTP_POST_VARS[action] != "search") && ($title == ""))
	{ $wher .=""; }
 else 
	{ $wher .= "AND (title like '%$title%') "; }

 if ($seller)
 {
 	$query = "select id from PHPAUCTIONPROPLUS_users where nick ='$seller'";
    $res = mysql_query($query);
    if(!$res)
    {
    	print "Error: $query<BR>".mysql_error();
    	exit;
    }
    $SELLER_ID = mysql_result($res,0,"id");
    
    $wher .= "(user like '%$SELLER_ID%') AND ";
  }
 if ($category) $wher .= "AND (category='$category') ";
 if ($maxprice) $wher .= "AND (minimum_bid<='$maxprice'  ) ";
 if ($minprice) $wher .= "AND (minimum_bid>='$minprice') ";
 if ($type) $wher .= "AND (auction_type='$type') ";
 if ($ending) {
 $data=date('YmdHms',time()+($ending*86400));
 $wher .="(ends<=$data) AND";
 }
 if ($country) $wher .= "(location='$country') AND ";
 if ($payment) {
 reset($payment);
 while(list($key,$val) = each($payment))
                {
                        if (!$pri) $ora .= "AND ((payment like '%".$payment[$key]."%')";
                        else $ora .= " or (payment like '%".$payment[$key]."%')";
                        $pri='true';
                }
             $ora .= ") ";
        }
if ($desc=='y') $ora .= "or (description like '%$title%') ";
if ($SortProperty=='starts'){$by='starts DESC';}
else if ($SortProperty=='min_bid'){$by='minimum_bid';}
else if ($SortProperty=='max_bid'){$by='minimum_bid DESC';}
else {$by='ends DESC';}
	$query = "SELECT * FROM PHPAUCTIONPROPLUS_auctions WHERE (closed <> 1) $wher $ora ORDER BY $by";
 if (!empty($wher) || !empty($ora))
 {
      $sql = mysql_query("SELECT * FROM PHPAUCTIONPROPLUS_auctions WHERE (closed <> 1) $wher $ora AND (closed=0) ORDER BY $by");

		if (mysql_num_rows($sql) == 0)
		{ $TPL_search_value.="<tr ALIGN=CENTER VALIGN=MIDDLE><td></td><td></td><td></td><td></td><td></td></tr></table><table width=100%><tr BGCOLOR=#EBEBEB><td width=100%><center>$std_font $MSG_198<br><br></td></tr></table><table>"; }
		else 
		{
      while ($myrow=mysql_fetch_array($sql))
      {
            $ends=$myrow['ends'];
            $id=$myrow['id'];
            $prov=mktime($myrow['ends']-$data);

             /* number of bids for this subastas */
             $bidsql = mysql_query ( "SELECT bid FROM PHPAUCTIONPROPLUS_bids WHERE auction='$id'" );
                if ( $bidsql )
                     $num_bids = mysql_num_rows($bidsql);
                else
                     $num_bids = 0;
            $t = strval($ends);
            $difference = mktime (
                                substr($t,8,2),//hours
                                substr($t,10,2),// mins
                                substr($t,12,2),// secs
                                substr($t,4,2),// month
                                substr($t,6,2),// day
                                substr($t,0,4)// year
                                ) - time();

            if ($difference > 0)
            {
                                $days_difference = intval($difference / 86400);
                                $difference = $difference - ($days_difference * 86400);

                                $hours_difference = intval($difference / 3600);
                                if(strlen($hours_difference) == 1)
                                {
                                        $hours_difference = "0".$hours_difference;
                                }

                                $difference = $difference - ($hours_difference * 3600);
                                $minutes_difference = intval($difference / 60);
                                if(strlen($minutes_difference) == 1)
                                {
                                        $minutes_difference = "0".$minutes_difference;
                                }

                                $difference = $difference - ($minutes_difference * 60);
                                $seconds_difference = $difference;
                                if(strlen($seconds_difference) == 1)
                                {
                                        $seconds_difference = "0".$seconds_difference;
                                }

                                $tplv = "$std_font $days_difference $MSG_126 <BR>$hours_difference:$minutes_difference:$seconds_difference";

                        }
                        else
                        {
                                $tplv = "$err_font$MSG_911</FONT>";
                        }
           				if ( strlen($myrow['pict_url'])>0 )
                        {
                                if ( intval($myrow['photo_uploaded'])!=0 )

                                $image1 = "$uploaded_path".$myrow['pict_url'];
                                $image = "<IMG SRC=\"$image1\" WIDTH=18 HEIGHT=16 BORDER=0>";
                        }
                        else
                        {
                                $image = "<IMG SRC=images/nopicture.gif WIDTH=18 HEIGHT=16 BORDER=0>";
                        }
           				if($bgColor == "#EBEBEB")
                        {
                                $bgColor = "#FFFFFF";
                        }
                        else
                        {
                                $bgColor = "#EBEBEB";
                        }
           			$TPL_search_value.="<tr ALIGN=CENTER VALIGN=MIDDLE BGCOLOR=\"$bgColor\"><td>$image</td><td><a href=item.php?id=$id>".stripslashes($myrow['title'])."</a></td><td>$std_font".print_money($myrow['current_bid'])."</td><td>$std_font $num_bids</td><td>$tplv</td></tr>";
		}
           }
      include "templates/template_advanced_search_result.html";
    }
 else {
        // -------------------------------------- payment

        //--
        $qurey = "select * from PHPAUCTIONPROPLUS_payments";
        $res_payment = mysql_query($qurey);
        if(!$res_payment)
             {
              MySQLError($query);
              exit;
             }
        $num_payments = mysql_num_rows($res_payment);
        $T="";
        $i = 0;
        while($i < $num_payments)
                 {
                  $payment_descr = mysql_result($res_payment,$i,"description");
                  $T.="<INPUT TYPE=CHECKBOX NAME=\"payment[]\" VALUE=\"$payment_descr\"";
                  if($payment_descr == $payment[$i])
                                {
                                        $T .= " CHECKED";
                                }
                   $T .= "> $std_font $payment_descr</FONT><BR>";
                   $i++;
                  }
        $TPL_payments_list = $T;
        // -------------------------------------- category
        $T= "<SELECT NAME=\"category\"><option></option>\n";
        $result = mysql_query("SELECT * FROM PHPAUCTIONPROPLUS_categories_plain");
        if($result):
        while($row=mysql_fetch_array($result)){
                   $T.="        <OPTION VALUE=\"".$row[cat_id]."\" ".(($row[cat_id]==$category)?"SELECTED":"").">".$row[cat_name]."</OPTION>\n";
                 }
        endif;
        $T.="</SELECT>\n";
        $TPL_categories_list = $T;
        // -------------------------------------- country
                        $T=        "<SELECT NAME=\"country\">\n";
                        reset($countries); while(list($key,$val)=each($countries)){
                                $T.=
                                        "        <OPTION VALUE=\"".
                                        $key.
                                        "\" ".
                                        (($key==$country)?"SELECTED":"")
                                        .">".$val."</OPTION>\n";
                        }
                        $T.="</SELECT>\n";
                        $TPL_countries_list = $T;
        include "templates/template_advanced_search.html";
      }
        include "footer.php";


?>