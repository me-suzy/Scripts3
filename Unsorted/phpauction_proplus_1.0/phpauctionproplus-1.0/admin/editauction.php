<?#//v.1.0.0

#///////////////////////////////////////////////////////
#//  COPYRIGHT 2002 PHPAuction.org ALL RIGHTS RESERVED//
#//  For Source code for the GPL version go to        //  
#//  http://phpauction.org and download               //
#//  Supplied by CyKuH [WTN]                          //
#///////////////////////////////////////////////////////

        include "loggedin.inc.php";

        include "../includes/config.inc.php";
        include "../includes/messages.inc.php";
        include "../includes/countries.inc.php";

        $username = $name;


        //-- Data check
        if(!$id){
                header("Location: listauctions.php");
                exit;
        }

        /*
         *        If script is called to actually make modifications
         *        (ie on first invocation this script just displays some HTML
         *         on the second it tries to modify the database).
         */
        if($action)
        {


                // Check that all the fields are not NULL
                if ($id && $title && $nick && $date && $duration && $category &&
                        $description && $current_bid && $quantity &&
                        $min_bid && $reserve_price && $buy_now && $country)
                {

                        $DATE = explode("/",$date);
                        $tmp_day                 = $DATE[0];
                        $tmp_month         = $DATE[1];
                        $tmp_year         = $DATE[2];

                        /*
                         *         Check the input values for validity.
                         */


                        if(strlen($tmp_year) == 2){
                                $tmp_year = "19".$tmp_year;
                        }


                        if (strlen($nick)<6)
                        {
                                $ERR = "ERR_010";
                        }
                        else if (!ereg("^[0-9]{2}/[0-9]{2}/[0-9]{4}$",$date) &&
                                         !ereg("^[0-9]{2}/[0-9]{2}/[0-9]{2}$",$date)) //date check
                        {
                                $ERR = "ERR_700";
                        }
                        else if ($quantity < 1) // 1 or more items being sold
                        {
                                $ERR = "ERR_701";
                        }
                        else if ($current_bid < $min_bid && $current_bid != 0) // bid > min_bid
                        {
                                $ERR = "ERR_702";
                        }
                        else
                        {

                                $date = "$tmp_year"."$tmp_month"."$tmp_day";

                                $sql="UPDATE PHPAUCTIONPROPLUS_auctions SET title=\"".AddSlashes($title)."\",
								user=\"".AddSlashes($nick)."\",
                                                              	starts=\"".AddSlashes($date)."\",
                                                                duration=\"".AddSlashes($duration)."\",
                                                                category=\"".AddSlashes($category)."\",
                                                                description=\"".AddSlashes($description)."\",
                                                                current_bid=\"".AddSlashes($current_bid)."\",
                                                                location=\"".AddSlashes($country)."\",
                                                                quantity=\"".AddSlashes($quantity)."\",
                                                                minimum_bid=\"".AddSlashes($min_bid)."\",
                                                                buy_now=\"".AddSlashes($buy_now)."\",
                                                                reserve_price=\"". AddSlashes($reserve_price)."\"
								WHERE id='".AddSlashes($id)."'";
                                $res=mysql_query ($sql);

                                if (!$res)
                                {
                                         print "Database error on update: " . mysql_error();
                                         exit;
                                }
                                else
                                {
                                        $updated = 1;
                                }


                        }
                }
                else
                {
                // COUNTRIES

                $country_list="";
                while (list ($code, $descr) = each ($countries))
                {
                        $country_list .= "<option value=\"$code\"";
                        if ($code == $country)
                        {
                                $country_list .= " selected";
                        }
                        $country_list .= ">$descr</option>\n";
                }
                // NICKS (usernames)

                $nick_list = ""; // empty string to begin HTML list
                $nick_query = "select id, nick from PHPAUCTIONPROPLUS_users";
                $res_q = mysql_query($nick_query);
                if(!$res_q){
                        print "Database access error: abnormal termination".mysql_error();
                        exit;
                }
                while($row = mysql_fetch_array($res_q))
                {
                        $nick_list .= "<option value=\"$row[id]\"";
                        if ($row[id] == $nick)
                        {
                                $nick_list .= " selected ";
                        }
                        $nick_list .= ">$row[1]</option>\n";
                };


                // DURATIONS

                $dur_list = ""; // empty string to begin HTML list
                $dur_query = "select days, description from PHPAUCTIONPROPLUS_durations";
                $res_d = mysql_query($dur_query);
                if(!$res_d){
                        print "Database access error: abnormal termination".mysql_error();
                        exit;
                }
                for ($i = 0; $i < mysql_num_rows($res_d); $i++)
                {
                        $row = mysql_fetch_row($res_d);
                        // 0 is days, 1 is description
                        // Append to the list
                        $dur_list .= "<option value=\"$row[0]\"";
                        // If this Durations # of days coresponds to the duration of this
                        // auction, select it
                        if ($row[0] == $duration)
                        {
                                $dur_list .= " selected ";
                        }
                        $dur_list .= ">$row[1]</option>\n";
                }


                        $ERR = "ERR_112";
                }

        }


        if(!$action || ($action && $updated))
        {

                /*
                 *        Make a large SQL query getting values from the "auctions"
                 *        table and corresponding values that are indexed in other tables
                 *         and displaying them both (and allowing the admin to change
                 *        only the proper indexed values.
                 */
                $query = "select a.id, a.user as nick , u.nick as nick_description,
                a.title, a.starts, a.description,
                a.category, c.cat_name, a.duration as duration, d.description as
                dur_description, a.suspended, a.current_bid,
                a.quantity, a.reserve_price, a.buy_now, a.location, a.minimum_bid from PHPAUCTIONPROPLUS_auctions
                a, PHPAUCTIONPROPLUS_users u, PHPAUCTIONPROPLUS_categories c, PHPAUCTIONPROPLUS_durations d where u.id = a.user and
                c.cat_id = a.category and d.days = a.duration and a.id=\"$id\"";
                $result = mysql_query($query);
                if(!$result){
                        print "Database access error: abnormal termination".mysql_error();
                        exit;
                }



                $id = mysql_result($result,0,"id");
                $title = mysql_result($result,0,"title");
                $nick = mysql_result($result,0,"nick");
                $tmp_date = mysql_result($result,0,"starts");
                $duration = mysql_result($result,0,"duration");
                $category = mysql_result($result, 0, "category");
                $cat_description = mysql_result($result,0,"cat_name");
                $description = mysql_result($result,0,"description");
                $suspended = mysql_result($result,0,"suspended");
                $current_bid = mysql_result($result,0,"current_bid");
                $min_bid = mysql_result($result,0,"minimum_bid");
                $quantity = mysql_result($result,0,"quantity");
                $reserve_price = mysql_result($result,0,"reserve_price");
                $buy_now = mysql_result($result,0,"buy_now");
                $country = mysql_result($result, 0, "location");




                /*
                 *         For all list-like items we create drop-down
                 *        lists and select the index listed in the auction table.
                 *        for this auction.
                 */


                // NICKS (usernames)

                $nick_list = ""; // empty string to begin HTML list
                $nick_query = "select id, nick from PHPAUCTIONPROPLUS_users";
                $res_q = mysql_query($nick_query);
                if(!$res_q){
                        print "Database access error: abnormal termination".mysql_error();
                        exit;
                }
                while($row = mysql_fetch_array($res_q))
                {
                        $nick_list .= "<option value=\"$row[id]\"";
                        if ($row[id] == $nick)
                        {
                                $nick_list .= " selected ";
                        }
                        $nick_list .= ">$row[1]</option>\n";
                };


                // DURATIONS

                $dur_list = ""; // empty string to begin HTML list
                $dur_query = "select days, description from PHPAUCTIONPROPLUS_durations";
                $res_d = mysql_query($dur_query);
                if(!$res_d){
                        print "Database access error: abnormal termination".mysql_error();
                        exit;
                }
                for ($i = 0; $i < mysql_num_rows($res_d); $i++)
                {
                        $row = mysql_fetch_row($res_d);
                        // 0 is days, 1 is description
                        // Append to the list
                        $dur_list .= "<option value=\"$row[0]\"";
                        // If this Durations # of days coresponds to the duration of this
                        // auction, select it
                        if ($row[0] == $duration)
                        {
                                $dur_list .= " selected ";
                        }
                        $dur_list .= ">$row[1]</option>\n";
                };



                // CATEGORIES

                        $T=        "<SELECT NAME=\"category\">\n";
                        $categ = mysql_query("SELECT * FROM PHPAUCTIONPROPLUS_categories_plain");
                        if($categ):
                                while($categ_result=mysql_fetch_array($categ)){
                                        $T.=
                                                "        <OPTION VALUE=\"".
                                                $categ_result[cat_id].
                                                "\" ".
                                                (($categ_result[cat_id]==$category)?"SELECTED":"")
                                                .">".$categ_result[cat_name]."</OPTION>\n";
                                }
                        endif;
                        $T.="</SELECT>\n";
                        $TPL_categories_list = $T;



                // COUNTRIES

                $country_list="";
                while (list ($code, $descr) = each ($countries))
                {
                        $country_list .= "<option value=\"$code\"";
                        if ($code == $country)
                        {
                                $country_list .= " selected";
                        }
                        $country_list .= ">$descr</option>\n";
                };

                $date = mysql_result($result,0,"starts");
                $tmp_day = substr($date,6,2);
                $tmp_month = substr($date,4,2);
                $tmp_year = substr($date,0,4);
                $date = "$tmp_day/$tmp_month/$tmp_year";

        }


        require "./header.php";
        require('../includes/styles.inc.php');
?> <BR>
<TABLE WIDTH="650" BORDER="0" CELLSPACING="0" CELLPADDING="1" BGCOLOR="#296FAB" ALIGN="CENTER">
        <TR>
                <TD ALIGN=CENTER><FONT COLOR=#FFFFFF FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="4"><B>
                        <? print $MSG_512; ?>
                        </B></FONT></TD>
        </TR>
        <TR>
                <TD>
                        <TABLE WIDTH="100%" BORDER="0" CELLPADDING="5" BGCOLOR="#FFFFFF">
                                <TR>
                                        <TD>
                                                <TABLE WIDTH=100% CELPADDING=0 CELLSPACING=0 BORDER=0 BGCOLOR="#FFFFFF">
                                                        <TR>
                                                                <TD ALIGN=CENTER COLSPAN=5> <BR>
                                                                        <FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="4">
                                                                        <B> </B> </FONT> <BR>
                                                                </TD>
                                                        </TR>
                                                        <TABLE WIDTH="100%" BORDER="0" CELLPADDING="5">
                                                                <?
                                                                        if($ERR || $updated){
                                                                                                print "<TR>
                                                                                                                <TD>
                                                                                                                </TD>
                                                                                                                <TD WIDTH=486>
                                                                                                                <FONT FACE=\"Verdana,Arial, Helvetica\" SIZE=2 COLOR=red>";
                                                                                                                if($$ERR) print $$ERR;
                                                                                                                if($updated) print "Auction data updated";
                                                                                                                print "</TD>
                                                                                                                </TR>";
                                                                        }
                                                                        ?>
                                                                <FORM NAME=details ACTION="<? print basename($PHP_SELF); ?>" METHOD="POST">
                                                                        <TR>
                                                                                <TD WIDTH="204" VALIGN="top" ALIGN="right">

                                                                                        <? print "$MSG_312 *"; ?>
                                                                                </TD>
                                                                                <TD WIDTH="486">
                                                                                        <INPUT TYPE=text NAME=title SIZE=40 MAXLENGTH=255 VALUE="<? print $title; ?>">
                                                                                </TD>
                                                                        </TR>
                                                                        <TR>
                                                                                <TD WIDTH="204" VALIGN="top" ALIGN="right">

                                                                                        <? print "$MSG_313 *"; ?>
                                                                                </TD>
                                                                                <TD WIDTH="486">
                                                                                        <SELECT NAME=nick>
                                                                                                <OPTION VALUE=""> </OPTION>
                                                                                                <?  echo $nick_list; ?>
                                                                                        </SELECT>
                                                                                </TD>
                                                                        </TR>
                                                                        <TR>
                                                                                <TD WIDTH="204" VALIGN="top" ALIGN="right">

                                                                                        <? print " $MSG_314 *"; ?>
                                                                                </TD>
                                                                                <TD WIDTH="486">
                                                                                        <INPUT TYPE=text NAME=date SIZE=20 MAXLENGTH=20 VALUE="<? echo $date; ?>">
                                                                                </TD>
                                                                        </TR>
                                                                        <TR>
                                                                                <TD WIDTH="204" VALIGN="top" ALIGN="right">

                                                                                        <? print "$MSG_315 *"; ?>
                                                                                </TD>
                                                                                <TD WIDTH="486">
                                                                                        <SELECT NAME=duration>
                                                                                                <OPTION VALUE=""> </OPTION>
                                                                                                <?  echo $dur_list; ?>
                                                                                        </SELECT>
                                                                                </TD>
                                                                        </TR>
                                                                        <TR>
                                                                                <TD WIDTH="204"  VALIGN="top" ALIGN="right">

                                                                                        <? print "$MSG_316 *"; ?>
                                                                                </TD>
                                                                                <TD WIDTH="486">
                                                                                        <? print $TPL_categories_list; ?>
                                                                                </TD>
                                                                        </TR>
                                                                        <TR>
                                                                                <TD WIDTH="204" VALIGN="top" ALIGN="right">

                                                                                        <? print "$MSG_317 *"; ?>
                                                                                </TD>
                                                                                <TD WIDTH="486">
                                                                                        <INPUT TYPE=text NAME=description SIZE=40 MAXLENGTH=255 VALUE="<? echo $description; ?>">
                                                                                </TD>
                                                                        </TR>
                                                                        <TR>
                                                                                <TD WIDTH="204" VALIGN="top" ALIGN="right">

                                                                                        <? print "$MSG_014 *"; ?>
                                                                                </TD>
                                                                                <TD WIDTH="486">
                                                                                        <SELECT NAME=country>
                                                                                                <OPTION VALUE=""> </OPTION>
                                                                                                <?  echo $country_list; ?>
                                                                                        </SELECT>
                                                                                </TD>
                                                                        </TR>
                                                                        <TR>
                                                                                <TD WIDTH="204" VALIGN="top" ALIGN="right">

                                                                                        <? print "$MSG_318 *"; ?>
                                                                                </TD>
                                                                                <TD WIDTH="486">
                                                                                        <INPUT TYPE=text NAME="current_bid" SIZE=15 MAXLENGTH=15 VALUE="<? echo $current_bid; ?>">
                                                                                </TD>
                                                                        </TR>
                                                                        <TR>
                                                                                <TD WIDTH="204" VALIGN="top" ALIGN="right">

                                                                                        <? print "$MSG_327 *"; ?>
                                                                                </TD>
                                                                                <TD WIDTH="486">
                                                                                        <INPUT TYPE=text NAME="min_bid" SIZE=40 MAXLENGTH=40 VALUE="<? echo
                $min_bid; ?>">
                                                                                </TD>
                                                                        </TR>
                                                                        <TR>
                                                                                <TD WIDTH="204" VALIGN="top" ALIGN="right">

                                                                                        <? print "$MSG_319 *"; ?>
                                                                                </TD>
                                                                                <TD WIDTH="486">
                                                                                        <INPUT TYPE=text NAME="quantity" SIZE=40 MAXLENGTH=40 VALUE="<? echo
                $quantity; ?>">
                                                                                </TD>
                                                                        </TR>
                                                                        <TR>
                                                                                <TD WIDTH="204" VALIGN="top" ALIGN="right">

                                                                                        <? print "$MSG_320 *"; ?>
                                                                                </TD>
                                                                                <TD WIDTH="486">
                                                                                        <INPUT TYPE=text NAME="reserve_price" SIZE=40 MAXLENGTH=40 VALUE="<? echo
                $reserve_price; ?>">
                                                                                </TD>
                                                                        </TR>
                                                                        <TR>
                                                                                <TD WIDTH="204" VALIGN="top" ALIGN="right">

                                                                                        <? print "$MSG_497 *"; ?>
                                                                                </TD>
                                                                                <TD WIDTH="486">
                                                                                        <INPUT TYPE=text NAME="buy_now" SIZE=40 MAXLENGTH=40 VALUE="<? echo
                $buy_now; ?>">
                                                                                </TD>
                                                                        </TR>
                                                                        <TR>
                                                                                <TD WIDTH="204" VALIGN="top" ALIGN="right">

                                                                                        <? print "$MSG_300"; ?>
                                                                                </TD>
                                                                                <TD WIDTH="486">
                                                                                        <?
                                                                                                if($suspended == 0)
                                                                                                        print "$MSG_029";
                                                                                                else
                                                                                                        print "$MSG_030";

                                                                                        ?>
                                                                                </TD>
                                                                        </TR>
                                                                        <TR>
                                                                                <TD WIDTH="204">&nbsp;</TD>
                                                                                <TD WIDTH="486"> <BR>
                                                                                        <BR>
                                                                                        <INPUT TYPE=submit NAME=act VALUE="<? print $MSG_089; ?>">
                                                                                </TD>
                                                                        </TR>

                                                        </TABLE>
                                                        <INPUT TYPE="hidden" NAME="id" VALUE="<? echo $id; ?>">
                                                        <INPUT TYPE="hidden" NAME="offset" VALUE="<? echo $offset; ?>">
                                                        <INPUT TYPE="hidden" NAME="action" VALUE="update">
                                                        <BR>
                                                        <BR>  </FORM>
                                                        <CENTER>
                                                                <FONT FACE="Tahoma, Arial" SIZE="2"><A HREF="admin.php" CLASS="navigation">Admin
                                                                home</A> | <FONT FACE="Tahoma, Arial" SIZE="2"><A
                  HREF="listauctions.php?offset=<? print $offset; ?>
                  "CLASS="navigation">Auctions list</A></FONT> </FONT>
                                                        </CENTER>
                                                </TABLE>
                                        </TD>
                                </TR>
                        </TABLE>




</TD>
        </TR>
</TABLE>
<!-- Closing external table (header.php) -->
<? include "./footer.php"; ?>