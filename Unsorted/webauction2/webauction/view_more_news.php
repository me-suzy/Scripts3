<?
/*
   Copyright (c), 1999, 2000 - phpauction.org
   Copyright (c), 2001, 2002 - webauction.de.vu

   Lizenz siehe lizenz.txt & license

   Die neueste Version kostenfrei zum Download unter:
   http://webauction.de.vu

*/


        include "includes/config.inc.php";
        include "includes/messages.inc.php";

        $datumswert = time() - 86400;
        $datumswert2 = date("Y-m-d H:i:s", $datumswert);

        $result = mysql_query ( "SELECT * FROM ".$dbfix."_auctions WHERE closed='0' and date >= '$datumswert2' ORDER BY date " );

        $auctions_count = 0;

                                                /* retrieve records corresponding to passed page number */
                                                $page = (int)$page;
                                                if ($page==0)        $page = 1;
                                                $lines = (int)$lines;
                                                if ($lines==0)        $lines = 25;

                                                        /* determine limits for SQL query */
                                                        $left_limit = ($page-1)*$lines;

                                                        /* get total number of records */
                                                        $rsl = mysql_query ("SELECT count(*) from ".$dbfix."_auctions WHERE closed='0' and date >= '$datumswert2' "  );
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
                                        $tmp_res = mysql_query ( "SELECT bid FROM ".$dbfix."_bids WHERE auction='".$row[id]."'" );
                                                                                if ( $tmp_res )
                                                                                        $num_bids = mysql_num_rows($tmp_res);
                                                                                else
                                                                                        $num_bids = 0;

                                                                                $rpr = (int)$row[reserved_price];

                                                                                        $reserved_price = "";
                                                                                if  ( $num_bids > 3 )
                                                                                $h_str = "<IMG SRC=\"images/hot.gif\">";
                                                                                else
                                                                                $h_str = "";

                                        $tplv .= "<TD align=left>";
                                        if ( strlen($row[pict_url])>0 ) {
                                                if (intval($row["photo_uploaded"])!=0)
                                                        $row[pict_url] = "uploaded/".$row[pict_url];
                                                $tplv .= "<IMG SRC=\"images/picture.gif\" WIDTH=18 HEIGHT=16 BORDER=0>".$h_str."";
                                        }
                                        else{
                                                $tplv .= "&nbsp;";
                                        }
                                        $tplv .= "</TD>";

                                /* this subastas title and link to details */
                                        $s_difference = time()-mktime($h,$min,$sec,$m,$d,$y);
                                        $difference = mktime($ends_h,$ends_min,$ends_sec,$ends_m,$ends_d,$ends_y)-time();
                                        if ( intval($s_difference/3600)<24 )
                                                                        $n_str = "<IMG SRC=\"images/new.gif\">";
                                                                        else
                                                                        $n_str = "";
                                                                        if ( intval($difference/3600)<24 )
                                                                        $e_str = "<IMG SRC=\"images/ending.gif\">";
                                                                        else
                                                                        $e_str = "";
                                        $tplv .=
                                                "<TD ALIGN=LEFT><A HREF=\"item.php?SESSION_ID=$sessionIDU&id=".$row[id]."\">$std_font".
                                                htmlspecialchars($row[title]).
                                                "</FONT></A> ".$n_str." ".$e_str." </TD>";

                                /* current bid of this subastas */
                                          if  ( $num_bids > 0)
                                                                                {
                                                                                $bid = (string)$row[current_bid];
                                                                                ereg ( "(.+)\.(.+)", $bid, $regs );
                                                                                $regs[2] = substr($regs[2],0,2);
                                                                                $akt_gebot_dm = $regs[1];
                                                                                $akt_gebot_pf = $regs[2];
                                                                                }
                                                                                else
                                                                                {
                                                                                $bid = (string)$row[minimum_bid];
                                                                                ereg ( "(.+)\.(.+)", $bid, $regs );
                                                                                $regs[2] = substr($regs[2],0,2);
                                                                                $akt_gebot_dm = $regs[1];
                                                                                $akt_gebot_pf = $regs[2];
                                                                                }


                                        $tplv .=
                                                "<TD>".
                                                "<TABLE CELLSPACING=0 CELLPADDING=0 BORDER=0 WIDTH=\"100%\">".
                                                "<TR VALIGN=TOP><TD ALIGN=LEFT>".
                                                "</TD><TD ALIGN=RIGHT>".
                                                "$std_font".
                                                 $akt_gebot_dm.",".$akt_gebot_pf.
                                                "</TD></TR></TABLE>".
                                                "</TD>";

                                /* number of bids for this subastas */

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

                                        $tplv .= "<TD align=center>$std_font $days_difference $MSG_097 $hours_difference:$minutes_difference:$seconds_difference</TD>";

                        $tplv .= "</TR>\n";
                        $counter++;
                }

                $TPL_auctions_list_value = $tplv;
        }
        else
                                                $auctions_count = 0;

                                        $TPL_auctions_list_value .= "<TR ALIGN=CENTER><TD COLSPAN=5>$std_font".
                                                "<BR>".
                                                "$MSG_290 $total<BR>".
                                                "$MSG_289 $pages ($lines $MSG_291)<BR>".
                                                "Seiten: ";

                                        for ($i=1; $i<=$pages; ++$i)
                                        {
                                                $TPL_auctions_list_value .=
                                                        ($page==$i)        ?
                                                                " $i "        :
                                                                " <A HREF=\"view_more_news.php?page=$i\">$i</A> ";
                                        }

                                        $TPL_auctions_list_value .=
                                                "</FONT></TD></TR>";

        include "header.php";
        include "templates/view_news_php3.html";
        include "footer.php";
