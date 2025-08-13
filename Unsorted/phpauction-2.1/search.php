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

	require('./includes/messages.inc.php');
	require('./includes/config.inc.php');

	$q = trim($q);

	$query = "".$q;
	$query = "".$query; // set $query variable if it's not set yet
	$searchQuery = $query;
	$qquery = ereg_replace("%","\\%",$query);
	$qquery = ereg_replace("_","\\_",$qquery);

	if ( strlen($query)==0 )
	{
		include "header.php";
		?>
		<? print $tlt_font; ?>
		
		<?
		
		include "templates/template_empty_search.html";
		include "footer.php";
		exit;
	}

	/* generate query syntax for searching in auction */
	$search_words = explode (" ", $qquery);

		/* query part 1 */
	$qp1 = "";
	$qp = "";

	$qp1 .= 
		" (title LIKE '%".
		addslashes($qquery).
		"%') OR (description LIKE '%".
		addslashes($qquery)."%') ";

	$qp .= " (cat_name LIKE '%".addslashes($qquery)."%') ";
	
	$addOR = true;
	while ( list(,$val) = each($search_words) )
	{
		$val = ereg_replace("%","\\%",$val);
		$val = ereg_replace("_","\\_",$val);
		if ($addOR)
		{
			$qp1 .= " OR ";
			$qp .= " OR ";
		}
		$addOR = true;

		$qp1 .= 
			" (title LIKE '%".
			addslashes($val).
			"%') OR (description LIKE '%".
			addslashes($val)."%') ";

		$qp .= "(cat_name LIKE '%".addslashes($qquery)."%') ";
	}
//	die($qp1);
//	print $qp."<BR>";

	$sql_count = "SELECT count(*) FROM PHPAUCTION_auctions WHERE ( $qp1 ) AND ( closed='0') ORDER BY ends";
	$sql = "SELECT * FROM PHPAUCTION_auctions WHERE ( $qp1 ) AND ( closed='0') ORDER BY ends";

	$sql_count_cat = "SELECT count(*) FROM PHPAUCTION_categories WHERE ( $qpc1 ) ORDER BY cat_name ASC";
	$sql_cat = "SELECT * FROM PHPAUCTION_categories WHERE ".$qp." ORDER BY cat_name ASC";
//	print $sql_cat."<BR>";

	/* get categories whose names fit the search criteria */			

	$result = mysql_query($sql_cat);
	$subcat_count = 0;
	if ($result)
	{
		/* query succeeded - display list of categories */
		$need_to_continue = 1;
		$cycle = 1;

		$TPL_main_value = "";
		while ($row=mysql_fetch_array($result))
		{
			++$subcat_count;
			if ($cycle==1 ) { $TPL_main_value.="<TR ALIGN=LEFT>\n"; }
			$sub_counter = (int)$row[sub_counter];
			$cat_counter = (int)$row[counter];
			if ($sub_counter!=0)
			{
				$count_string = "(".$sub_counter.")";
			}
			else
			{
				if ($cat_counter!=0)
				{
					$count_string = "(".$cat_counter.")";
				}
				else
				{
					$count_string = "";
				}
			}

			$TPL_main_value .= "	<TD WIDTH=\"33%\">$std_font<A HREF=\"browse.php?id=".$row[cat_id]."\">".$row[cat_name]."</A>".$count_string."</FONT></TD>\n";
									
			++$cycle;
			if ($cycle==4) { $cycle=1; $TPL_main_value.="</TR>\n"; }
		}

		if ( $cycle>=2 && $cycle<=3 )
		{
			while ( $cycle<4 )
			{
				$TPL_main_value .= "	<TD WIDTH=\"33%\">&nbsp;</TD>\n";
				++$cycle;
			}
			$TPL_main_value .= "</TR>\n";
		}
	} // end if ($result)
	else
	{
		print mysql_error();
	} 

	/* get list of auctions of this category */
	$auctions_count = 0;

	/* retrieve records corresponding to passed page number */
	$page = (int)$page;
	if ($page==0)	$page = 1;
	$lines = (int)$lines;
	if ($lines==0)	$lines = 50;

	/* determine limits for SQL query */
	$left_limit = ($page-1)*$lines;

	/* get total number of records */
	$rsl = mysql_query ( $sql_count );
	if ($rsl)
	{
		$hash = mysql_fetch_array($rsl);
		$total = (int)$hash[0];
	}
	else
	{
		$total = 0;
	}
	
	/* get number of pages */
	$pages = (int)($total/$lines);
	if (($total % $lines)>0)
		++$pages;

	$result = mysql_query ( $sql." LIMIT $left_limit,$lines" );

	if ($result)
	{
		$tplv = "";
		$bgColor = "#EBEBEB";
		while ($row=mysql_fetch_array($result))
		{

			$bid = $row[current_bid];
			$starting_price = $row[minimum_bid];

			/* prepare some data */
			$date = $row["starts"];
			$y =	substr ($date, 0, 4);
			$m =	substr ($date, 4, 2);
			$d =	substr ($date, 6, 2);
			$h =	substr ($date, 8, 2);
			$min =	substr ($date, 10, 2);
			$sec =	substr ($date, 12, 2);

			if($bgColor == "#EBEBEB")
			{
				$bgColor = "#FFFFFF";
			}
			else
			{
				$bgColor = "#EBEBEB";
			}

			$is_dutch = (intval($row["auction_type"])==2)?true:false;

			$tplv .= "<TR ALIGN=CENTER VALIGN=MIDDLE BGCOLOR=\"$bgColor\">";

			/* image icon */
			$tplv .= "<TD>";
			if ( strlen($row[pict_url])>0 ) 
			{
				if ( intval($row[photo_uploaded])!=0 )
			
				$row[pict_url] = "uploaded/".$row[pict_url];
				$tplv .= "<IMG SRC=\"images/picture.gif\" WIDTH=18 HEIGHT=16 BORDER=0>";
			}
			else
			{
				$tplv .= "&nbsp;";
			}
			$tplv .= "</TD>";

			/* this subastas title and link to details */
			$difference = time()-mktime($h,$min,$sec,$m,$d,$y);

			$tplv .=  "<TD ALIGN=LEFT><A HREF=\"item.php?id=".$row[id]."\">$std_font".
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
					"</TD><TD ALIGN=RIGHT>".
					"$std_font".
					$bid.
					"</TD></TR></TABLE>".
					"</TD>";

			/* number of bids for this subastas */
			$tmp_res = mysql_query ( "SELECT bid FROM PHPAUCTION_bids WHERE auction='".$row[id]."'" );
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
			$t = strval($row[ends]);
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

				$tplv .= "<TD>$std_font $days_difference $MSG_126 				<BR>$hours_difference:$minutes_difference:$seconds_difference</TD>";

				$tplv .= "</TR>";
			}
			else
			{
				$tplv .= "<TD>$err_font$MSG_911</FONT></TD></TR>";
			}
			++$auctions_count;

		} // end of while ??
	
	
		$TPL_auctions_list_value = $tplv;
	}
	else
	{
		$auctions_count = 0;
	}


	$TPL_auctions_list_value .= "<TR ALIGN=CENTER><TD COLSPAN=5>" . 
			$std_font ."<BR>" . $MSG_290 . $total . "<BR>" . $MSG_289 . $pages .
			"(" . $lines . $MSG_291. ")<BR>"."Pages: ";



					for ($i=1; $i<=$pages; ++$i)
					{
						$TPL_auctions_list_value .=
							($page==$i)	?
								" $i "	:
								" <A HREF=\"search.php?id=$id&page=$i&q=".urlencode($searchQuery)."\">$i</A> ";
					}

					$TPL_auctions_list_value .=
						"</FONT></TD></TR>";

					if ($auctions_count==0)
					{
						$TPL_auctions_list_value = "	<TR ALIGN=CENTER><TD COLSPAN=5> $std_font $MSG_198 <BR><BR></FONT></TD></TR>";
					}

			include "header.php";
			if ( $subcat_count>0 )
			{
					include "templates/template_browse_php.html";
			}
			else
			{
			    include "templates/template_auctions_no_cat.html";
			}
			
			
			include "footer.php";
			exit;

//	include "footer.php";

?>