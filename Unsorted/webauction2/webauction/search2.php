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



	$q = trim($q);

	$query = "".$q;
	$query = "".$query; // set $query variable if it's not set yet
	$searchQuery = $query;
	$qquery = ereg_replace("%","\\%",$query);
	$qquery = ereg_replace("_","\\_",$qquery);


if ($query_search==1){ //-- User
$search_art ="Suche nach Auktionen des Users <b>$qquery</b>!";
$br = "";
}

if ($query_search==2){ //-- PLZ
$search_art ="Suche nach Auktionen mit der Postleitzahl <b>$qquery</b>!<br><b>Tipp:</b> Wenn Sie weniger Zahlen der Postleitzahl nehmen, vergrößern Sie das Suchgebiet.";
$br = "<br>";
}

$querya = "select * from ".$dbfix."_auctions where closed='0'";
$resulta	= mysql_query ( $querya );
$num_auctions_all = mysql_num_rows($resulta);

if ($query_search==3){ //-- Auktion
$search_art ="Suche nach dem Begriff $qquery in den Titeln und den Beschreibungen aller $num_auctions_all aktiven Auktionen!";
$br = "";
}

$queryx = "select * from ".$dbfix."_categories";
$resultx	= mysql_query ( $queryx );
$num_categories_all = mysql_num_rows($resultx);

if ($query_search==4){ //-- Kategorien
$search_art ="Suche nach innerhalb der $num_categories_all Kategorien nach dem Begriff $qquery!";
$br = "";
}

	if ( strlen($query)==0)
	{
		include "header.php";
		?>
		<? print $tlt_font; ?>
		<?

		

		include "templates/empty_search.html";
		include "footer.php";
		exit;
	}

	/* generate query syntax for searching in auction */
	$search_words = explode (" ", $qquery);

		/* query part 1 */
	$qp1 = "";
	$qp = "";


if ($query_search==1){ //-- User

        $query1 = "select * from ".$dbfix."_users where nick=\"".$qquery."\"";
        $result1 = mysql_query ( $query1 );
        $user = mysql_result ( $result1,0,"id" );


$qp1 .= 
		" (user LIKE '%".
		addslashes($user).
		"%')";
}

if ($query_search==2){ //-- PLZ

$qp1 .= 
		"(location_zip LIKE '%".
		addslashes($qquery).
		"%')";
}

if ($query_search==3){ //-- Auktion

$qp1 .= 
		" (title LIKE '%".
		addslashes($qquery).
		"%') or (description LIKE '%".
		addslashes($qquery)."%') ";
}

$sql_count = "SELECT count(*) FROM ".$dbfix."_auctions WHERE ( $qp1 ) AND ( closed='0') ORDER BY ends";
$sql = "SELECT * FROM auctions WHERE ( $qp1 ) AND ( closed='0') ORDER BY ends";

if ($query_search==4){ //-- Kategorie

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

		$qp .= "";
	}


//	die($qp1);
//	print $qp."<BR>";

	$sql_count = "SELECT count(*) FROM ".$dbfix."_auctions WHERE ( $qp1 ) AND ( closed='0') ORDER BY ends";
	$sql = "SELECT * FROM ".$dbfix."_auctions WHERE ( $qp1 ) AND ( closed='0') ORDER BY ends";

	$sql_count_cat = "SELECT count(*) FROM ".$dbfix."_categories WHERE (cat_name LIKE '%".addslashes($qquery)."%' and parent_id>='2000') ORDER BY cat_name ASC";
	$sql_cat = "SELECT * FROM ".$dbfix."_categories WHERE (cat_name LIKE '%".addslashes($qquery)."%' and parent_id>='2000') ORDER BY cat_name ASC";
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
											$count_string = "(".$sub_counter.")";
										else
										{
											if ($cat_counter!=0)
											{
												$count_string = "(".$cat_counter.")";
											}
											else
												$count_string = "";
										}

									$TPL_main_value .= "	<TD WIDTH=\"33%\">$std_font<A HREF=\"kategorie.php?SESSION_ID=$sessionIDU&id=".$row[cat_id]."\"><font face=\"arial\" size=\"2\" color=\"#000000\">".$row[cat_name]."</A><font face=\"arial\" size=\"1\" color=\"#FF0000\"> ".$count_string."</FONT></TD>\n";
									
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
						}
						else
							print mysql_error(); 
}


			/* get list of auctions of this category */
					$auctions_count = 0;

						/* retrieve records corresponding to passed page number */
						$page = (int)$page;
						if ($page==0)	$page = 1;
						$lines = (int)$lines;
						if ($lines==0)	$lines = 25;

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
								$total = 0;

							/* get number of pages */
							$pages = (int)($total/$lines);
							if (($total % $lines)>0)
								++$pages;

					$result = mysql_query ( $sql." LIMIT $left_limit,$lines" );

					if ($result)
					{
						$tplv = "";
						$bgColor = "#FFFFFF";
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

								
			if($bgColor == "#EBEBEB"){
											$bgColor = "#FFFFFF";
											$font_color="#004488";
										}else{
											$bgColor = "#EBEBEB";
                                            $font_color="#004488";
										}



										$is_dutch = (intval($row["auction_type"])==2)?true:false;

							$tplv .= "<TR ALIGN=CENTER VALIGN=MIDDLE BGCOLOR=\"$bgColor\">";

								/* image icon */
									$tplv .= "<TD>";
									if ( strlen($row[pict_url])>0 ) {
										if ( intval($row[photo_uploaded])!=0 )
											$row[pict_url] = "uploaded/".$row[pict_url];
										$tplv .= "<IMG SRC=\"images/picture.gif\" BORDER=0>";
									}
									else{
										$tplv .= "&nbsp;";
									}
									$tplv .= "</TD>";

								/* this subastas title and link to details */
									$difference = time()-mktime($h,$min,$sec,$m,$d,$y);

										// is this auction new?
									if ( intval($difference/3600)<12 )
										$n_str = "<IMG SRC=\"images/nueva.gif\">";
									else
										$n_str = "";

										// is this auction dutch?
									if ( $is_dutch )
										$d_string = "";
									else
										$d_string = "";

									$tplv .= 
										"<TD ALIGN=LEFT><A HREF=\"item.php?SESSION_ID=$sessionIDU&id=".$row[id]."\"><font face=arial size=2 color=$font_color>".
										htmlspecialchars($row[title]).
										"</FONT></A>".$n_str.$d_string."</TD>";
         $query       = "select max(bid) as maxbid from ".$dbfix."_bids where auction=\"".$row[id]."\" group by auction";
   $result_bids = mysql_query ( $query) ;
   if ( !$result_bids )
   {
		print $MSG_001;
        exit;
   }
 
   if ( mysql_num_rows($result_bids ) > 0)
   {
		$high_bid       = mysql_result ( $result_bids, 0, "maxbid" );
        $query          = "select bidder from ".$dbfix."_bids where bid=$high_bid";
        $result_bidder  = mysql_query ( $query );
   }
        
   if ( !mysql_num_rows($result_bids) )
   {
		$num_bids = 0;
        $high_bid = 0;
   }



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
					$regs[1].",".$regs[2].
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
									$tplv .= "<TD><font face=arial size=2 color=$font_color>".$reserved_price.$num_bids." Gebote</TD>";

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

									$tplv .= "<TD><font face=arial size=2 color=$font_color>$days_difference Tage <BR>$hours_difference:$minutes_difference:$seconds_difference h</TD>";

							$tplv .= "</TR>";
							++$auctions_count;
						}
						$TPL_auctions_list_value = $tplv;
					}
					else
						$auctions_count = 0;

					$TPL_auctions_list_value .= "<TR ALIGN=CENTER><TD COLSPAN=5>".
						"<BR>".
						"<font face=\"arial\" size=\"2\" color=\"#000000\">Es wurden $total Auktionen mit dem Begriff \"$qquery\" gefunden!<BR>".
						"($lines Auktionen pro Seite)<BR>".
						"<font face=\"arial\" size=\"2\" color=\"#000000\">Ergebnisseiten: ";

					for ($i=1; $i<=$pages; ++$i)
					{
						$TPL_auctions_list_value .=
							($page==$i)	?
								" $i "	:
								" <A HREF=\"search.php?SESSION_ID=$sessionIDU&id=$id&page=$i&q=".urlencode($searchQuery)."&query_search=$query_search\"><font face=\"arial\" size=\"2\" color=\"#000000\">$i</A> ";
					}

					$TPL_auctions_list_value .=
						"</FONT>";

					if ($auctions_count==0)
					{
						$TPL_auctions_list_value = "<CENTER> $std_font $MSG_198 <BR><BR></FONT>";
					}

			include "header.php";
			include "templates/search_header_php3.html";
			if ( $subcat_count>0)
			{
//					print "cats: ".$subcat_count."<BR>";

		    
			include "templates/browse_php3.html";
					
			}
			if ($query_search !=4){
			include "templates/auctions_no_cat.html";
			}
			
			include "footer.php";
			exit;

//	include "footer.php";

?>
