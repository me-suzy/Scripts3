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



$page = (int)$page;
                                                if ($page==0)        $page = 1;
                                                $lines = (int)$lines;
                                                if ($lines==0)        $lines = 25;
                        $left_limit = ($page-1)*$lines;



$rsl = mysql_query ( "SELECT count(*) FROM ".$dbfix."_auctions WHERE user='".addslashes($user_id)."' AND closed='1' ORDER BY ends");
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

        /* get active auctions for this user */
                        $result = mysql_query ( "SELECT * FROM ".$dbfix."_auctions WHERE user='".addslashes($user_id)."' AND closed='1' ORDER BY ends LIMIT $left_limit,$lines" );

                        if ($result)
                        {
                                $tplv = "";
                                $bgColor = "#B1CCE4";
                                while ($row=mysql_fetch_array($result))
                                {
                                                                /* prepare some data */
                                                                $date = $row["date"];
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
                                                                                        $font_color="#004488";
                                                                                }else{
                                                                                        $bgColor = "#EBEBEB";
                                            $font_color="#004488";
                                                                                }

                                        $tplv .= "<TR ALIGN=CENTER VALIGN=MIDDLE BGCOLOR=\"$bgColor\">";




                                                /* image icon */
                                                        $tplv .= "<TD WIDTH=\"35\">";
                                                        if ( strlen($row[pict_url])>0 ) {
                                                                $tplv .= "<IMG SRC=\"images/picture.gif\" BORDER=0>";
                                                        }
                                                        else{
                                                                $tplv .= "&nbsp;";
                                                        }
                                                        $tplv .= "</TD>";

                                                /* this subastas title and link to details */
                                                        $difference = time()-mktime($h,$min,$sec,$m,$d,$y);
                                                        if ( intval($difference/3600)<12 )
                                                                $n_str = "<IMG SRC=\"images/nueva.gif\">";
                                                        else
                                                                $n_str = "";

                                                        $tplv .=
                                                                "<TD ALIGN=LEFT><A HREF=\"item.php?id=".$row[id]."\"><FONT FACE=\"Arial\" color=\"$font_color\" SIZE=2>".
                                                                htmlspecialchars($row[title]).
                                                                "</FONT></A>".$n_str."</TD>";

                                                /* current bid of this subastas */
           $bid = (string)$row[current_bid];
                                                                ereg ( "(.+)\.(.+)", $bid, $regs );
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
                                                                "<FONT FACE=\"Verdana,Helvetica,Arial\" SIZE=2>".
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
                                                        $tplv .= "<TD><FONT color=\"$font_color\" FACE=\"Arial\" SIZE=2>".$reserved_price.$num_bids." Gebote</TD>";

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

                                                        $tplv .= "<TD><FONT color=\"$font_color\" FACE=\"Arial\" SIZE=2>Beendet</TD>";
                                                        $tplv .= "<TD><form action=del.php method=POST>".
                                                        "<input type=hidden name=id value=".$row[id].">".
                                                        "<input type=\"HIDDEN\" name=\"user_id\" value=\"$user_id\">".
                                                        "<INPUT TYPE=HIDDEN NAME=\"SESSION_ID\" VALUE=\"$sessionID\">".
                                                        "<INPUT TYPE=HIDDEN NAME=\"TPL_nick\" VALUE=\"$TPL_nick\">".
                                                        "<INPUT TYPE=HIDDEN NAME=\"page\" VALUE=\"$page\">".
                            "<INPUT TYPE=HIDDEN NAME=\"TPL_password\" VALUE=\"$TPL_password\">".
                                                    "<INPUT TYPE=HIDDEN NAME=\"name\" VALUE=\"$name\">".
                                                        "<FONT SIZE=1><br><input type=submit name=del value=Löschen></form></TD>";

                                        $tplv .= "</TR>";
                                        ++$auctions_count;
                                }
                                                $TPL_auctions_list_value = $tplv;
                                        }
                                        else
                                                $auctions_count = 0;

                                        $TPL_auctions_list_value .= "<TR ALIGN=CENTER><TD COLSPAN=5>".
                                                "<BR>".
                                                "<font size=\"2\" face=\"arial\" color=\"#000000\">Es wurden $total Auktionen in $city gefunden! Es werden $lines Auktionen pro Seite angezeigt.<BR>".
                                                "<BR>".
                                                "<font size=\"2\" face=\"arial\" color=\"#000000\">Ergebnisseiten: ";

                                        for ($i=1; $i<=$pages; ++$i)
                                        {
                                                $TPL_auctions_list_value .=
                                                        ($page==$i)        ?
                                                                " $i "        :
                                                                " <A HREF=\"my_closed_auctions.php?SESSION_ID=$SESSION_ID&user_id=$user_id&TPL_nick=$TPL_nick&TPL_password=$TPL_password&name=$name&page=$i&action=0\"><font size=\"2\" face=\"arial\" color=\"#000000\">$i</A> ";
                                        }

                                        $TPL_auctions_list_value .=
                                                "</FONT></TD></TR>";

                                        if ($auctions_count==0)
                                        {
                                                $TPL_auctions_list_value = "<table width=\"745\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><TR ALIGN=CENTER><TD COLSPAN=5><br><font size=\"2\" face=\"arial\" color=\"#000000\"> Es sind keine geschlossenen  Auktionen vorhanden.</FONT>";
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
                        include "templates/users_auctions_header_closed_php3.html";
                        include "templates/auctions_close.html";
                        include "footer.php";

                        exit;
?>
