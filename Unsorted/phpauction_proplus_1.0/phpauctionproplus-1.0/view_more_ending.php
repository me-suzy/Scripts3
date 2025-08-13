<?#//v.1.0.0
#///////////////////////////////////////////////////////
#//  COPYRIGHT 2002 PHPAuction.org ALL RIGHTS RESERVED//
#//  For Source code for the GPL version go to        //  
#//  http://phpauction.org and download               //
#//  Supplied by CyKuH [WTN]                          //
#///////////////////////////////////////////////////////

	include "./includes/config.inc.php";

	include "./includes/messages.inc.php";

	

	//-- Set offset and limit for pagination
	
	$limit = 20;
	if(!$offset) $offset = 0;



	$result = mysql_query ( "SELECT * FROM PHPAUCTIONPROPLUS_auctions WHERE closed='0' ORDER BY ends LIMIT $offset,$limit" );



	if ($result)

	{

		$tplv = "";

		$bgColor = "#EBEBEB";

		while ($row=mysql_fetch_array($result))

		{

						/* prepare some data */

						$date = strval($row["date"]);

						$y =	substr ($date, 0, 4);

						$m =	substr ($date, 4, 2);

						$d =	substr ($date, 6, 2);

						$h =	substr ($date, 8, 2);

						$min =	substr ($date, 10, 2);

						$sec =	substr ($date, 12, 2);



						$ends_date = strval($row["ends"]);

						$ends_y =	substr ($ends_date, 0, 4);

						$ends_m =	substr ($ends_date, 4, 2);

						$ends_d =	substr ($ends_date, 6, 2);

						$ends_h =	substr ($ends_date, 8, 2);

						$ends_min =	substr ($ends_date, 10, 2);

						$ends_sec =	substr ($ends_date, 12, 2);

					



						if($bgColor == "#EBEBEB"){

							$bgColor = "#FFFFFF";

						}else{

							$bgColor = "#EBEBEB";

						}



						$is_dutch = (intval($row["auction_type"])==2)?true:false;



				$tplv .= "<TR ALIGN=CENTER VALIGN=MIDDLE BGCOLOR=\"$bgColor\">";



				/* image icon */

					$tplv .= "<TD>";

					if ( strlen($row[pict_url])>0 ) {

						if (intval($row["photo_uploaded"])!=0)

							$row[pict_url] = "uploaded/".$row[pict_url];

						$tplv .= "<IMG SRC=\"images/picture.gif\" WIDTH=18 HEIGHT=16 BORDER=0>";

					}

					else{

						$tplv .= "&nbsp;";

					}

					$tplv .= "</TD>";



				/* this subastas title and link to details */

					$s_difference = time()-mktime($h,$min,$sec,$m,$d,$y);

					$difference = mktime($ends_h,$ends_min,$ends_sec,$ends_m,$ends_d,$ends_y)-time();



					$tplv .= 

						"<TD ALIGN=LEFT><A HREF=\"item.php?id=".$row[id]."\">$std_font".

						stripslashes(htmlspecialchars($row[title])).

						"</FONT></A></TD>";



				/* current bid of this subastas */

						$bid = (string)$row[current_bid];

						ereg ( "(.+)\.(.+)", $bid, $regs );

						$regs[2] = substr($regs[2],0,2);





					$tplv .= 

						"<TD>".

						"<TABLE CELLSPACING=0 CELLPADDING=0 BORDER=0 WIDTH=\"100%\">".

						"<TR VALIGN=TOP><TD ALIGN=LEFT>".

						"</TD><TD ALIGN=RIGHT>".

						"$std_font".

						$regs[1].".".$regs[2].

						"</TD></TR></TABLE>".

						"</TD>";



				/* number of bids for this subastas */

						$tmp_res = mysql_query ( "SELECT bid FROM PHPAUCTIONPROPLUS_bids WHERE auction='".$row[id]."'" );

						if ( $tmp_res )

							$num_bids = mysql_num_rows($tmp_res);

						else

							$num_bids = 0;



						$rpr = (int)$row[reserved_price];

						if ($rpr!=0)

							$reserved_price = " <IMG SRC=\"images/r.gif\"> ";

						else

							$reserved_price = "";

					$tplv .= "<TD>$std_font".$reserved_price.$num_bids."</TD>";



				/* time left till the end of this subastas */

					$days_difference = intval($difference / 86400);

					$difference = $difference - ($days_difference * 86400);



					$hours_difference = intval($difference / 3600);

					if(strlen($hours_difference) == 1){

						$hours_difference = "0".$hours_difference;

					}



					$difference = $difference - ($hours_difference * 3600);

					$minutes_difference = intval($difference / 60);

					if(strlen($minutes_difference) == 1){

						$minutes_difference = "0".$minutes_difference;

					}



					$difference = $difference - ($minutes_difference * 60);

					$seconds_difference = $difference;

					if(strlen($seconds_difference) == 1){

						$seconds_difference = "0".$seconds_difference;

					}



					$tplv .= "<TD>$std_font $days_difference $MSG_097 <BR>$hours_difference:$minutes_difference:$seconds_difference</TD>";



			$tplv .= "</TR>\n";

			$counter++;

		}

		

		$TPL_auctions_list_value = $tplv;

	}





	include "header.php";

	include "templates/template_view_ending_php.html";

	include "footer.php";

