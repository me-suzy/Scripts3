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
                        $result = mysql_query ( "SELECT * FROM ".$dbfix."_auctions WHERE user='".addslashes($user_id)."' AND closed='1'  ORDER BY ends LIMIT $left_limit,$lines" );

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


                    $minimum_bid = $row[minimum_bid];




                                                /* image icon */
                                                        $tplv .= "<TD WIDTH=\"35\" bgcolor=ffffff>";
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
                                                                $n_str = "";
                                                        else
                                                                $n_str = "";

if($row["current_bid"]>0){

$status = "<font color=#FF0000 size=1 face=arial>[Dieser Artikel wurde schon Verkauft]</font>";

}
else
{
$status = "<font color=#008000 size=1 face=arial>[Dieser Artikel wurde noch nicht Verkauft]</font>";
}
                                                        $tplv .=
                                                                "<TD ALIGN=LEFT bgcolor=ffffff><A HREF=\"item2.php?id=".$row[id]."\"><FONT FACE=\"Arial\" color=#004488 SIZE=2><p align=\"center\">".
                                                                htmlspecialchars($row[title]).
                                                                "</FONT></A> <br>$status</p>".$n_str."</TD>";

                                                /* current bid of this subastas */
                                                                $bid = (string)$row[current_bid];
                                                                ereg ( "(.+)\.(.+)", $bid, $regs );
                                                                $regs[2] = substr($regs[2],0,2);

                                                                $rp = (int)$row[reserve_price];
                                                                if ($rp>0)
                                                                        $rp_ins = "";
                                                                else
                                                                        $rp_ins = "&nbsp;";

if($row[current_bid]>0){

   $tplv .=
                                "<TD VALIGN=TOP bgcolor=ffffff>".
                                "<form action=\"auction_restart.php\" method=\"POST\">".
                                "<p align=\"center\"><font size=\"1\"><FONT FACE=\"Arial\" color=\"#004488\" SIZE=2><INPUT TYPE=text size=\"5\" NAME=\"minimum_bid\" VALUE=\"$minimum_bid\"> $currency</td>".
                                                                "<TD bgcolor=ffffff>".
                                                                "<TABLE CELLSPACING=0 CELLPADDING=0 BORDER=0 WIDTH=\"100%\" bgcolor=ffffff>".
                                                                "<TR VALIGN=TOP><TD ALIGN=LEFT>".
                                "<input type=\"HIDDEN\" name=\"auction_id\" value=\"$row[id]\">".
                                "<input type=\"HIDDEN\" name=\"date_old\" value=\"$row[date]\">".
                                "<input type=\"HIDDEN\" name=\"ends_old\" value=\"$row[ends]\">".
                                                                "<input type=\"HIDDEN\" name=\"user_id\" value=\"$user_id\">".
                                                                "<INPUT TYPE=HIDDEN NAME=\"SESSION_ID\" VALUE=\"$sessionIDU;\">".
                                                                "<INPUT TYPE=HIDDEN NAME=\"TPL_nick\" VALUE=\"$TPL_nick\">".
                                "<INPUT TYPE=HIDDEN NAME=\"TPL_password\" VALUE=\"$TPL_password\">".
                                                                "<INPUT TYPE=HIDDEN NAME=\"name\" VALUE=\"$name\">".
                                                                "<INPUT TYPE=HIDDEN NAME=\"page\" VALUE=\"$page\">".
                                                                "<INPUT TYPE=HIDDEN NAME=\"nick\" VALUE=\"$TPL_nick\">".
                                "<INPUT TYPE=HIDDEN NAME=\"password\" VALUE=\"$TPL_password\">".
                                                                "<font size=\"1\"><SELECT NAME=\"duration\">".
                                    "<OPTION VALUE=\"3\">3 Tage</OPTION>".
                                    "<OPTION VALUE=\"5\">5 Tage</OPTION>".
                                    "<OPTION VALUE=\"7\">7 Tage</OPTION>".
                                    "<OPTION VALUE=\"10\">10 Tage</OPTION>".
                                    "<OPTION VALUE=\"14\">14 Tage</OPTION>".
                                "</SELECT>".
                                "</p>".
                                                           
                                                                "</TD><TD ALIGN=center>".


                                                                "<font color=#FF0000 size=3 face=arial> [ Verkauft ]</font>".


                                                                "</form></TD></TR></TABLE>";



}
                      else


{
   $tplv .=
                                "<TD VALIGN=TOP bgcolor=ffffff>".
                                "<form action=\"auction_restart.php\" method=\"POST\">".
                                "<p align=\"center\"><font size=\"1\"><FONT FACE=\"Arial\" color=\"#004488\" SIZE=2><INPUT TYPE=text size=\"5\" NAME=\"minimum_bid\" VALUE=\"$minimum_bid\"> $currency</td>".
                                                                "<TD bgcolor=ffffff>".
                                                                "<TABLE CELLSPACING=0 CELLPADDING=0 BORDER=0 WIDTH=\"100%\" bgcolor=ffffff>".
                                                                "<TR VALIGN=TOP><TD ALIGN=LEFT>".
                                "<input type=\"HIDDEN\" name=\"auction_id\" value=\"$row[id]\">".
                                "<input type=\"HIDDEN\" name=\"date_old\" value=\"$row[date]\">".
                                "<input type=\"HIDDEN\" name=\"ends_old\" value=\"$row[ends]\">".
                                                                "<input type=\"HIDDEN\" name=\"user_id\" value=\"$user_id\">".
                                                                "<INPUT TYPE=HIDDEN NAME=\"SESSION_ID\" VALUE=\"$sessionIDU;\">".
                                                                "<INPUT TYPE=HIDDEN NAME=\"TPL_nick\" VALUE=\"$TPL_nick\">".
                                "<INPUT TYPE=HIDDEN NAME=\"TPL_password\" VALUE=\"$TPL_password\">".
                                                                "<INPUT TYPE=HIDDEN NAME=\"name\" VALUE=\"$name\">".
                                                                "<INPUT TYPE=HIDDEN NAME=\"page\" VALUE=\"$page\">".
                                                                "<INPUT TYPE=HIDDEN NAME=\"nick\" VALUE=\"$TPL_nick\">".
                                "<INPUT TYPE=HIDDEN NAME=\"password\" VALUE=\"$TPL_password\">".
                                                                "<font size=\"1\"><SELECT NAME=\"duration\">".
                                    "<OPTION VALUE=\"3\">3 Tage</OPTION>".
                                    "<OPTION VALUE=\"5\">5 Tage</OPTION>".
                                    "<OPTION VALUE=\"7\">7 Tage</OPTION>".
                                    "<OPTION VALUE=\"10\">10 Tage</OPTION>".
                                    "<OPTION VALUE=\"14\">14 Tage</OPTION>".
                                "</SELECT>".
                                "</p>".
                                                                "</TD><TD ALIGN=RIGHT bgcolor=ffffff>".
                                                                "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font size=\"1\"><input type=\"submit\" name=\"\" value=\"Neu starten\">&nbsp;&nbsp;<FONT FACE=\"Arial\" SIZE=2>".
                                                                "</form></TD></TR></TABLE>";



}
                                        $tplv .= "";
                                        ++$auctions_count;
                        }
                                                $TPL_auctions_list_value = $tplv;
                                        }
                                        else
                                                $auctions_count = 0;

                                        $TPL_auctions_list_value .= "<TR ALIGN=CENTER><TD COLSPAN=5 bgcolor=ffffff>".
                                                "<BR>".
                                                "<font size=\"2\" face=\"arial\" color=\"#000000\">Es wurden $total Auktionen in $city gefunden! Es werden $lines Auktionen pro Seite angezeigt.<BR>".
                                                "<BR>".
                                                "<font size=\"2\" face=\"arial\" color=\"#000000\">Ergebnisseiten: ";

                                        for ($i=1; $i<=$pages; ++$i)
                                        {
                                                $TPL_auctions_list_value .=
                                                        ($page==$i)        ?
                                                                " $i "        :
                                                                " <A HREF=\"my_active_auctions_del.php?SESSION_ID=$SESSION_ID&user_id=$user_id&TPL_nick=$TPL_nick&TPL_password=$TPL_password&name=$name&page=$i&action=0\"><font size=\"2\" face=\"arial\" color=\"#000000\">$i</A> ";
                                        }

                                        $TPL_auctions_list_value .=
                                                "</FONT></TD></TR>";

                                        if ($auctions_count==0)
                                        {
                                                $TPL_auctions_list_value = "<table width=100% border=0 cellpadding=0
                                        cellspacing=0 bgcolor=#ffffff><TR ALIGN=CENTER><TD COLSPAN=5><br><font size=2
                                        face=arial color=#000000> Keine Auktionen zum Neustarten  
                                        vorhanden.</FONT>"; 
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
                        include "templates/users_auctions_header_closed_restart_php3.html";
                        include "templates/auctions_closed_restart.html";
                        include "footer.php";
                        exit;
?>
