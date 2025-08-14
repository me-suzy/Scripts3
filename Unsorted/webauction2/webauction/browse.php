<?php
/*
   Copyright (c), 1999, 2000 - phpauction.org
   Copyright (c), 2001, 2002 - webauction.de.vu

   Lizenz siehe lizenz.txt & license

   Die neueste Version kostenfrei zum Download unter:
   http://webauction.de.vu


                browse.php

                browse category/subcategory by given ID
                parameters:
                        id - category number from database
                                if ==0 then: display all categories

                template variables:
                        $TPL_categories_string
                        $TPL_main_value

        */
        require('includes/messages.inc.php');
        require('includes/config.inc.php');

	$id2 = $id;
        $id = (int)$category;
        if ($id==0) {
	  $id = (int)$id2;
	}

        if ($id==0)
        {
                /*
                        display full list of categories of level 0
                */
                $result = mysql_query ( "SELECT * FROM ".$dbfix."_categories WHERE parent_id='0' ORDER BY cat_name" );
                if (!$result)
                {
                        /* output error message and exit */
                        print "database error";
                        exit;
                }
                else
                {
                        /* query succeeded - display list of categories */
                                $need_to_continue = 1;
                                $cycle = 1;

                        $TPL_main_value = "";
                        $TPL_categories_string = "Alle";
                                while ($row=mysql_fetch_array($result))
                                {
                                        if ($cycle==1 ) { $TPL_main_value.="<TR WIDTH=100% ALIGN=LEFT>\n"; }

                                        $sub_counter = (int)$row[sub_counter];
                                        $cat_counter = (int)$row[counter];
                                        if ($sub_counter!=0)
                                                $count_string = "(".$sub_counter.")";
                                        else
                                        {
                                                $count_string = "";
                                        /*
                                                if ($cat_counter!=0)
                                                {
                                                        $count_string = "(".$cat_counter.")";
                                                }
                                                else
                                                        $count_string = "";
                                        */
                                        }

                                        $TPL_main_value .= "        <TD WIDTH=\"33%\">$std_font<A HREF=\"browse.php?SESSION_ID=$sessionIDU&id=".$row[cat_id]."\">".$row[cat_name]."</A>".$count_string."</FONT></TD>\n";

                                        ++$cycle;
                                        if ($cycle==4) { $cycle=1; $TPL_main_value.="</TR>\n"; }
                                }

                        if ( $cycle>=2 && $cycle<=3 )
                        {
                                while ( $cycle<4 )
                                {
                                        $TPL_main_value .= "        <TD WIDTH=\"33%\">&nbsp;</TD>\n";
                                        ++$cycle;
                                }
                                $TPL_main_value .= "</TR>\n";
                        }

                        include "header.php";
                        include "templates/browse_header_php3.html";
                        include "templates/browse_php3.html";
                        include "footer.php";
                        exit;
                }
        }
        else
        {
                /*
                        specified category number

                        look into table - and if we don't have such category - redirect to full list
                */
                $result = mysql_query ( "SELECT * FROM ".$dbfix."_categories WHERE cat_id='$id'" );
                if ($result)
                        $category = mysql_fetch_array($result);
                else
                        $category = false;

                if (!$category)
                {
                        /* redirect to global categories list */
                        header ( "Location: browse.php?id=0" );
                        exit;
                }
                else
                {
                        /*
                                such category exists

                                retrieve it's subcategories and its auctions
                        */

                        /* recursively get "path" to this category */

                        $TPL_categories_string = "".$category["cat_name"];
                        $par_id = (int)$category[parent_id];

                        while ( $par_id!=0 )
                        {
                                // get next parent
                                $res = mysql_query ( "SELECT * FROM ".$dbfix."_categories WHERE cat_id='$par_id'");
                                if ($res)
                                {
                                        $rw = mysql_fetch_array($res);
                                        if ($rw)
                                                $par_id = (int)$rw[parent_id];
                                        else
                                                $par_id = 0;
                                }
                                else
                                        $par_id = 0;

                                $TPL_categories_string = "<A HREF=\"browse.php?SESSION_ID=$sessionIDU&id=".$rw["cat_id"]."\">".$rw["cat_name"]."</A> &gt; ".$TPL_categories_string;
                        }

                        /* get list of subcategories of this category */
                                        $subcat_count = 0;
                                        $result = mysql_query ( "SELECT * FROM ".$dbfix."_categories WHERE parent_id='$id' ORDER BY cat_name" );
                                        if (!$result)
                                        {
                                                /* output error message and exit */
                                        }
                                        else
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

											                                                                //Create Where Statment for Articel listing
											//Create Where Statment for Articel listing
                                                                	$where .= " or category='".$row[cat_id]."'";

                                                                $TPL_main_value .= "        <TD WIDTH=\"33%\">$std_font<A HREF=\"browse.php?SESSION_ID=$sessionIDU&id=".$row[cat_id]."\">".$row[cat_name]."</A>".$count_string."</FONT></TD>\n";

                                                                ++$cycle;
                                                                if ($cycle==4) { $cycle=1; $TPL_main_value.="</TR>\n"; }
                                                        }

                                                if ( $cycle>=2 && $cycle<=3 )
                                                {
                                                        while ( $cycle<4 )
                                                        {
                                                                $TPL_main_value .= "        <TD WIDTH=\"33%\">&nbsp;</TD>\n";
                                                                ++$cycle;
                                                        }
                                                        $TPL_main_value .= "</TR>\n";
                                                }

                                        }

                        /* get list of auctions of this category */
                                        $auctions_count = 0;

                                                /* retrieve records corresponding to passed page number */
                                                $page = (int)$page;
                                                if ($page==0)        $page = 1;
                                                $lines = (int)$lines;
                                                if ($lines==0)        $lines = 50;

                                                        /* determine limits for SQL query */
                                                        $left_limit = ($page-1)*$lines;

                                                        /* get total number of records */
                                                        $rsl = mysql_query ( "SELECT count(*) FROM ".$dbfix."_auctions WHERE closed='0' and (category='$id' $where)" );
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

                                        $result = mysql_query ( "SELECT * FROM ".$dbfix."_auctions WHERE closed='0' and (category='$id' $where) ORDER BY ends DESC LIMIT $left_limit,$lines" );

//										$pict_url = mysql_result($result,"id","pict_url");

                                        if ($result)
                                        {
                                                $tplv = "";
                                                $bgColor = "#EBEBEB";
                                                while ($row=mysql_fetch_array($result))
                                                {
                                                                                /* prepare some data */
                                                                                $date = strval($row["date"]);
                                                                                $y =        substr ($date, 0, 4);
                                                                                $m =        substr ($date, 4, 2);
                                                                                $d =        substr ($date, 6, 2);
                                                                                $h =        substr ($date, 8, 2);
                                                                                $min =        substr ($date, 10, 2);
                                                                                $sec =        substr ($date, 12, 2);

                                                                                $ends_date = strval($row["ends"]);
                                                                                $ends_y =        substr ($ends_date, 0, 4);
                                                                                $ends_m =        substr ($ends_date, 4, 2);
                                                                                $ends_d =        substr ($ends_date, 6, 2);
                                                                                $ends_h =        substr ($ends_date, 8, 2);
                                                                                $ends_min =        substr ($ends_date, 10, 2);
                                                                                $ends_sec =        substr ($ends_date, 12, 2);

                                                                                if($bgColor == "#EBEBEB"){
                                                                                        $bgColor = "#FFFFFF";
                                                                                }else{
                                                                                        $bgColor = "#EBEBEB";
                                                                                }

                                                                                $is_dutch = (intval($row["auction_type"])==2)?true:false;

                                                        $tplv .= "<TR ALIGN=CENTER VALIGN=MIDDLE BGCOLOR=\"$bgColor\">";

                                                                /* image icon */

                                                                        $tplv .= "<TD>";

		/* hier bildvorschau anfang*/

                                                                        if ( strlen($row[pict_url])>0 ) {
                                                                                $row[pict_url] = "uploaded/".$row[pict_url];
                                                                                $tplv .= "<img src=\"".$row[pict_url]."\" WIDTH=\"38\" HEIGHT=\"32\" BORDER=\"0\">";
                                                                        }
                                                                        else{
																				$row[pict_url] = "uploaded/".$row[pict_url];
                                                                                $tplv .= "<img src=\"".$row[pict_url]."\" WIDTH=\"38\" HEIGHT=\"32\" BORDER=\"0\">";
                                                                        }

		/* hier bildvorschau anfang*/


                                                                        $tplv .= "</TD>";
	/*													$tplv .= "<TD>";				
														$tplv .= "<a href=\"./item.php?id=$id&SESSION_ID=$sessionIDU\"><img src=\"./uploaded/".$pict_url."\" WIDTH=\"38\" HEIGHT=\"32\" BORDER=\"0\"></a>";
														$tplv .= "</td>";*/

                                /* this subastas title and link to details */
                                        $s_difference = time()-mktime($h,$min,$sec,$m,$d,$y);
                                        $difference = mktime($ends_h,$ends_min,$ends_sec,$ends_m,$ends_d,$ends_y)-time();


                                // Neue Auktion
                                        if ( intval($s_difference/3600)<24 )
                                                $n_str = "<IMG SRC=\"images/new.gif\">";
                                        else
                                                $n_str = "";

                                // letzten 3 stunden
                                        if ( intval($difference/3600)<3 )
                                                $e_str = "<IMG SRC=\"images/going.gif\">";
                                        else
                                                $e_str = "";

                                // endende auktionen
                                         if ( intval($difference/3600)<24 )
                                                 $ee_str = "<IMG SRC=\"images/ending.gif\">";
                                         else
                                                 $ee_str = "";

                                 // buyit

                                        if ($row[sofortkauf] >0)

                                                $buy_str = "<IMG SRC=\"images/sofort.gif\">";

                                        else

                                                $buy_str = "";

                                // hot Auktion
                                   $tmp_res = mysql_query ( "SELECT bid FROM ".$dbfix."_bids WHERE auction='".$row[id]."'" );
                                                if ( $tmp_res )
                                                        $num_bids = mysql_num_rows($tmp_res);
                                         if ($num_bids >5)

                                                 $h_str = "<IMG SRC=\"images/hot1.gif\">";
                                         else
                                                 $h_str = "";

                                // is this auction dutch?
                                        if ( $is_dutch )
                                                $d_string = " (D) ";
                                        else
                                                $d_string = "";

                                       $tplv .=
                                                "<TD ALIGN=LEFT><A HREF=\"item.php?SESSION_ID=$sessionIDU&id=".$row[id]."\">$std_font".
                                                htmlspecialchars($row[title]).
                                                "</FONT></A>$n_str $e_str $ee_str $h_str $buy_str $d_string</TD>";

                                                                /* current bid of this subastas */
                                                                                $bid = (string)$row[current_bid];
                                                                                ereg ( "(.+)\.(.+)", $bid, $regs );
                                                               /* original zeile:  $regs[2] = substr($regs[2],0,2);/*
/* Abfrage für current_bid = 0 sonst  */
 if ($row[current_bid] >1)
 $regs[2] = substr($regs[2],0,2);
    else
                                                                   /* current bid of this subastas */
                                                                                $bid = (string)$row[minimum_bid];
                                                                                ereg ( "(.+)\.(.+)", $bid, $regs );
                                                                                $regs[2] = substr($regs[2],0,2);

 /* Abfrage für current_bid ENDE  */
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

                                                        $tplv .= "</TR>";
                                                        ++$auctions_count;
                                                }
                                                $TPL_auctions_list_value = $tplv;
                                        }
                                        else
                                                $auctions_count = 0;

                                        $TPL_auctions_list_value .= "<TR ALIGN=CENTER><TD COLSPAN=5>$std_font".
                                                "<BR>".
                                                "$MSG_290 $total<BR>".
                                                "$MSG_289 $pages ($lines $MSG_291)<BR>".
                                                "Pages: ";

                                        for ($i=1; $i<=$pages; ++$i)
                                        {
                                                $TPL_auctions_list_value .=
                                                        ($page==$i)        ?
                                                                " $i "        :
                                                                " <A HREF=\"browse.php?SESSION_ID=$sessionIDU&id=$id&page=$i\">$i</A> ";
                                        }

                                        $TPL_auctions_list_value .=
                                                "</FONT></TD></TR>";

                                        if ($auctions_count==0)
                                        {
                                                $TPL_auctions_list_value = "        <TR ALIGN=CENTER><TD COLSPAN=5>$std_font"."$ERR_114</FONT></TD></TR>";
                                        }
                                }

                        include "header.php";
                        include "templates/browse_header_php3.html";
                        if ( $subcat_count>0 )
                        {
                                include "templates/browse_php3.html";
                        }
                        if ($subcat_count==0)
                        {
                                include "templates/auctions_no_cat.html";
                        }
                        include "footer.php";
                        exit;
                }
?>
