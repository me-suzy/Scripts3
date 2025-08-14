<?
/*
   Copyright (c), 1999, 2000 - phpauction.org
   Copyright (c), 2001, 2002 - webauction.de.vu

   Lizenz siehe lizenz.txt & license

   Die neueste Version kostenfrei zum Download unter:
   http://webauction.de.vu

*/
require('includes/config.inc.php');


	require('includes/messages.inc.php');



			$TPL_maximum_bids = "";



			$query = "select auction,max(bid) as max_bid from ".$dbfix."_bids where bidder='$user_id' group by auction order by max_bid desc";


			$result = mysql_query($query);





			if ($result)


				$num_auction = mysql_num_rows($result);


			else


				$num_auction = 0;





			$i = 0;


			$bgcolor = "#EBEBEB";


			while($i < $num_auction && $i < 200){


				if($bgcolor == "#EBEBEB")


				{


					$bgcolor = "#FFFFFF";


				}


				else


				{


					$bgcolor = "#EBEBEB";


				}


				$max_bid  	 = mysql_result($result,$i,"max_bid");


				$auction  = mysql_result($result,$i,"auction");






/* get auction data  */
   $query5 = "select * from ".$dbfix."_auctions where id=\"$auction\"";
   $result5 = mysql_query($query5);
   if ( !$result5 )
   {
		print $ERR_001;
        exit;
   }

   $minimum_bid    = mysql_result ( $result5, 0, "minimum_bid" );
   $current_bid    = mysql_result ( $result5, 0, "current_bid" );

//--Next bid

$query = "select increment from ".$dbfix."_increments where
                                 ((low<=$current_bid AND high>=$current_bid) OR
                                  (low<$current_bid AND high<$current_bid)) order by increment desc";
        $result_incr = mysql_query  ( $query );

	   $increment = getIncrement($current_bid);
        if ($max_bid == 0)
		{
			$next_bid = $minimum_bid + $increment;
        }
		else
		{
            $next_bid = $current_bid + $increment;
        }


//-- Overbid check


  $query = "select auction,max(bid) as max_bid from ".$dbfix."_bids where auction='$auction' group by auction order by max_bid desc";
  $result2 = mysql_query($query);
  $red = mysql_result ($result2,0,"max_bid");


  if ($red > $max_bid)
  {
  $not_win = "1";
  $font_color = "";
  $input_string_bid = "".
  $currency.
  "";
  $br="";
  $br2 = "";
  }
else
  {
  $not_win = "0";
$font_color = "#004488";
$input_string_bid = "<form action=\"my_bid_update.php\" method=\"POST\"><font size=1><br><font face=arial color=#008000 size=2><b>Zuschlag erhalten!</b><br></form>";
$current_bid_page = "";
$br = "";
$br2 = "<font size=1><br><font face=arial color=$font_color size=2>";
  }







				$query = "select user,title,closed,id from ".$dbfix."_auctions where id=\"$auction\"";




				$result_bid = mysql_query($query);





				$title = mysql_result($result_bid,0,"title");


				$closed = mysql_result($result_bid,0,"closed");


				$auc_id = mysql_result($result_bid,0,"id");

				$seller_id = mysql_result($result_bid,0,"user");


/*				$sql = "select name,email from ".$dbfix."_users where id='$seller_id'";
                $result_mail = mysql_query($sql);
                $seller_mail = mysql_result($result_mail,0,"email");
                $seller_name = mysql_result($result_mail,0,"name");
*/
                                    $euro_fak = 1.95583;
                                    $price        = sprintf ("%4.2f", $max_bid);
                                    $euro_cal   = $price / $euro_fak;
                                    $euro_price     = sprintf ("%4.2f",$euro_cal);



				if($closed == "1" && $not_win=="0"){




					$TPL_maximum_bids .=


					"<TR BGCOLOR=\"$bgcolor\">".


					"<TD WIDTH=\"140\" VALIGN=top ALIGN=LEFT>".



                    "$br$br2&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font face=arial color=$font_color size=2>".

					number_format($max_bid,0,",",".").


                    "&nbsp;&nbsp;<font face=arial size=2>".

					$currency.


					$current_bid_page.


					"</TD>".





					"<TD ALIGN=LEFT WIDTH=\"600\">".



					"<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" WIDTH=\"600\"><tr><td WIDTH=\"336\"><A HREF=\"./item2.php?id=".$auc_id."&SESSION_ID=$sessionIDU\"><font face=arial color=#004488 size=2>$br".stripslashes($title)."$br$br</A></td><td WIDTH=\"134\"><A HREF=\"mailto:$seller_mail?subject=$title ($auc_id)&body=Hallo $seller_name, es geht um die Auktion '".$title."' mit der Nummer: $auc_id, die ich gewonnen habe.\"><font face=arial color=#004488 size=2>Verkäufermail</A></td><td WIDTH=\"124\">".$input_string_bid."</td></table></form>".



					"</TR>";


				}


				$i++;





			}



        include "header.php";
	include "templates/my_win_php3.html";
	include "footer.php";


?>