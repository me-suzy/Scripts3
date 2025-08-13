<?php

/*

   Copyright (c), 1999, 2000 - phpauction.org                  
   
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
   
*/



	// Include messages file	
	require('./includes/messages.inc.php');

	// Connect to sql server & inizialize configuration variables
	require('./includes/config.inc.php');

	//-- Function definition section


	/* get active bids for this user */
	

	$result = mysql_query("select a.id, a.title, a.ends, b.bid FROM PHPAUCTION_auctions a, PHPAUCTION_bids b WHERE a.id=b.auction AND a.closed='0' AND b.bidder='$HTTP_SESSION_VARS[PHPAUCTION_LOGGED_IN]' order by a.ends asc, b.bidwhen desc");
	
			$idcheck= "";

  			if ($result)
			{
				$tplv = "";
				$bgColor = "#EBEBEB";
				while ($row=mysql_fetch_array($result))
				{
				
				 	 $rowid = $row[id];
						

				     if ($idcheck != $rowid)
					 {
								$bid = $row[bid];
								
								/* prepare some data */


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

					$tplv .= "<TR VALIGN=MIDDLE BGCOLOR=\"$bgColor\">";



						/* this subastas title and link to details */
							$difference = time()-mktime($h,$min,$sec,$m,$d,$y);

							$tplv .= 
								"<TD ALIGN=LEFT><A HREF=\"item.php?id=".$rowid."\"><FONT FACE=\"Verdana,Helvetica,Arial\" SIZE=2>".
								stripslashes(htmlspecialchars($row[title])).
								"</FONT></A></TD>";

						/* current bid of this subastas */
							if($bid == 0)
							{
								$bid = $starting_price;
							}
							$bid = print_money($bid);

							$tplv .= 
								"<TD>".
								"<TABLE CELLSPACING=0 CELLPADDING=0 BORDER=0 WIDTH=\"100%\">".
								"<TR VALIGN=TOP><TD ALIGN=LEFT>".
								"</TD><TD>".
								$std_font.
								$bid.
								"</FONT></TD></TR></TABLE>".
								"</TD>";


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

							$tplv .= "<TD>$std_font$days_difference $MSG_126 <BR>$hours_difference:$minutes_difference:$seconds_difference</FONT></TD>";

					$tplv .= "</TR>";
					++$auctions_count;
					
					$idcheck = $rowid;
				}
				$TPL_auctions_list_value = $tplv;

				}
			}
			else
				$auctions_count = 0;

			if ($auctions_count==0)
			{
				$TPL_auctions_list_value = "	<TR ALIGN=CENTER><TD COLSPAN=5>$MSG_910</TD></TR>";
			}
	

  
  
  
  include "header.php";
   
?>



<TABLE WIDTH="100%" CELLSPACING="1" CELLPADDING="5" BORDER="0" ALIGN="CENTER" bgcolor="#FFFFFF">
<TR>
	<TD COLSPAN=5>
		<?
			print $std_font;
		?>
 			<?=$std_font?><?=$MSG_638?></FONT>
		<BR><IMG SRC="images/linea.gif" WIDTH="745" HEIGHT="6">
	</TD>
</TR>
</TABLE>


<!-- TABLE WITH AUCTIONS -->
<TABLE WIDTH="100%" BORDER=0 CELLPADDING=3 CELLSPACING=0 bgcolor="#FFFFFF">
<TR BGCOLOR="<?=$FONTCOLOR[$SETTINGS[bordercolor]]?>">
	<TD WIDTH="45%"><?=$nav_font?><? print $MSG_168; ?></FONT></TD>
	<TD WIDTH="15%"><?=$nav_font?><?=$MSG_639?></FONT></TD>
	<TD WIDTH="15%"><?=$nav_font?><? print $MSG_171; ?></FONT></TD>
</TR>
<? print $TPL_auctions_list_value; ?>
</TABLE>
<?

			include "footer.php";
			
			exit;
?>
