<?php

/*
   Copyright (c), 1999, 2000 - phpauction.org
   Copyright (c), 2001, 2002 - webauction.de.vu

   Lizenz siehe lizenz.txt & license

   Die neueste Version kostenfrei zum Download unter:
   http://webauction.de.vu

*/


 // Include messages file
	require('includes/messages.inc.php');

// Connect to sql server & inizialize configuration variables
	require('includes/config.inc.php');


	/* get active auctions for this user */
			$result = mysql_query ( "SELECT * FROM ".$dbfix."_auctions WHERE user='".addslashes($user_id)."' AND closed='1' ORDER BY date DESC" );

			if ($result)
			{
				$tplv = "";
				$bgColor = "#EBEBEB";
				while ($row=mysql_fetch_array($result))
				{
								/* prepare some data */
								$date = $row["date"];
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

					$tplv .= "<TR ALIGN=CENTER VALIGN=MIDDLE BGCOLOR=\"$bgColor\">";

						/* image icon */
							$tplv .= "<TD>";
							if ( strlen($row[pict_url])>0 ) {
								$tplv .= "<IMG SRC=\"images/picture.gif\" WIDTH=18 HEIGHT=16 BORDER=0>";
							}
							else{
								$tplv .= "&nbsp;";
							}
							$tplv .= "</TD>";

						/* this subastas title and link to details */
							$difference = time()-mktime($h,$min,$sec,$m,$d,$y);

							$tplv .= 
								"<TD ALIGN=LEFT><A HREF=\"item2.php?id=".$row[id]."\"><FONT FACE=\"Verdana,Helvetica,Arial\" SIZE=2>".
								htmlspecialchars($row[title]).
								"</FONT></A></TD>";

						/* current bid of this subastas */
								if ($bid == 0)
								{
									$bid = $starting_price;
								}
								$bid = number_format($bid,0,","," ")." ".$currency;


							$tplv .= 
								"<TD>".
								"<TABLE CELLSPACING=0 CELLPADDING=0 BORDER=0 WIDTH=\"100%\">".
								"<TR VALIGN=TOP><TD ALIGN=LEFT>".
								"</TD><TD ALIGN=RIGHT>".
								"<FONT FACE=\"Verdana,Helvetica,Arial\" SIZE=2>".
								$bid.
								"</TD></TR></TABLE>".
								"</TD>";

						/* number of bids for this subastas */
								$tmp_res = mysql_query ( "SELECT bid FROM ".$dbfix."_bids WHERE auction='".$row[id]."'" );
								if ( $tmp_res )
									$num_bids = mysql_num_rows($tmp_res);
								else
									$num_bids = 0;

								$rpr = (int)$row[reserved_price];
								if ($rpr!=0)
									$reserved_price = " <IMG SRC=\"images/r.gif\"> ";
								else
									$reserved_price = "";
							$tplv .= "<TD><FONT FACE=\"Verdana,Helvetica,Arial\" SIZE=2>".$reserved_price.$num_bids."</TD>";

						/* time left till the end of this subastas */
							$difference = mktime($ends_h,$ends_min,$ends_sec,$ends_m,$ends_d,$ends_y)-time();
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

							$tplv .= "<TD><FONT FACE=\"Verdana,Helvetica,Arial\" SIZE=2>$days_difference $MSG_097<BR>$hours_difference:$minutes_difference:$seconds_difference</TD>";

					$tplv .= "</TR>";
					++$auctions_count;
				}
				$TPL_auctions_list_value = $tplv;
			}
			else
				$auctions_count = 0;

			if ($auctions_count==0)
			{
				$TPL_auctions_list_value = "	<TR ALIGN=CENTER><TD COLSPAN=5>No existe ninguna subasta que contenga esta clave de búsqueda</TD></TR>";
			}
	
	/* get this user's nick */
			$query = "SELECT * FROM ".$dbfix."_users WHERE id=".htmlspecialchars($user_id);
			$result = mysql_query ( $query );
			if ($result)
			{
				if (mysql_num_rows($result)>0)
					$TPL_user_nick = mysql_result ($result,0,"nick");
				else
					$TPL_user_nick = "";
			}
			else
				$TPL_user_nick = "";

			include "header.php";
			include "templates/users_closed_auctions_header_php3.html";
			include "templates/auctions_closed.html";
			include "footer.php";
			exit;
?>
