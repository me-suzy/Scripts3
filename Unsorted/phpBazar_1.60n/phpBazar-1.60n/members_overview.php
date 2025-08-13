<?
#################################################################################################
#
#  project              : phpBazar
#  filename             : members_overview.php
#  last modified by     : Erich Fuchs
#  supplied by          : Reseting
#  nullified by		: CyKuH [WTN]
#  purpose              : show Members Overview
#
#################################################################################################



mysql_connect($server, $db_user, $db_pass);

if (function_exists("sales_checkuser")) {$is_sales_user=sales_checkuser($userid);}



if ($show_members_overview && (!($sales_option && $sales_members>1) || $is_sales_user)) {



    #  Calculate Page-Numbers

    #################################################################################################

    $perpage = 50;

    $pperpage = 9;				//!!! ONLY 5,7,9,11,13 !!!!

    if (empty($sort)) $sort = "asc";

    if (empty($offset)) $offset = 0;

    if (empty($poffset)) $poffset = 0;

    if (empty($orders)) $orders = "id";



    if ($action == "membersearch" && $value) {

	$sqlsearch="WHERE username LIKE '%$value%'";

    }



    $amount = mysql_db_query($database, "SELECT count(*) FROM userdata $sqlsearch");

    $amount_array = mysql_fetch_array($amount);

    $pages = ceil($amount_array["0"] / $perpage);

    $actpage = ($offset+$perpage)/$perpage;

    $maxpoffset = $pages-$pperpage;

    $middlepage=($pperpage-1)/2;

    if ($maxpoffset<0) {$maxpoffset=0;}



    echo "<table align=\"center\"  cellspacing=\"0\" cellpadding=\"3\" width=\"100%\" border=\"0\">\n";

    echo "<tr><td><div class=\"maincatnav\">\n";

    echo "<form action=\"$PHP_SELF\" method=\"GET\" style=\"display:inline;\">\n";

    echo "<input type=\"hidden\" name=\"action\" value=\"membersearch\">\n";

    echo "<input type=text name=\"value\" size=\"20\" maxlength=\"20\" value=\"$value\">\n";

    echo "<input type=submit value=$members_link1>\n";

    echo "</form>\n";

    echo "</td><td>\n";

    echo "<div class=\"mainpages\">\n";



    if ($pages) {                                       // print only when pages > 0

        echo "$ad_pages\n";





        if ($offset) {

	    $noffset=$offset-$perpage;

    	    $npoffset = $noffset/$perpage-$middlepage;

            if ($npoffset<0) {$npoffset=0;}

	    if ($npoffset>$maxpoffset) {$npoffset = $maxpoffset;}

	    echo "[<a href=\"$PHP_SELF?action=$action&value=$value&orders=$orders&sort=$sort&offset=$noffset&poffset=$npoffset\"><</a>] ";

	}



        for($i = $poffset; $i< $poffset+$pperpage && $i < $pages; $i++) {

	    $noffset = $i * $perpage;

    	    $npoffset = $noffset/$perpage-$middlepage;

    	    if ($npoffset<0) {$npoffset = 0;}

    	    if ($npoffset>$maxpoffset) {$npoffset = $maxpoffset;}

	    $actual = $i + 1;

    	    if ($actual==$actpage) {

 		echo "(<a href=\"$PHP_SELF?action=$action&value=$value&orders=$orders&sort=$sort&offset=$noffset&poffset=$npoffset\">$actual</a>) ";

    	    } else {

 		echo "[<a href=\"$PHP_SELF?action=$action&value=$value&orders=$orders&sort=$sort&offset=$noffset&poffset=$npoffset\">$actual</a>] ";

	    }

	}



	if ($offset+$perpage<$amount_array["0"]) {

    	    $noffset=$offset+$perpage;

    	    $npoffset = $noffset/$perpage-$middlepage;

    	    if ($npoffset<0) {$npoffset=0;}

    	    if ($npoffset>$maxpoffset) {$npoffset = $maxpoffset;}

	    echo "[<a href=\"$PHP_SELF?action=$action&value=$value&orders=$orders&sort=$sort&offset=$noffset&poffset=$npoffset\">></a>] ";

        }

    }



    echo "</div>\n";

    echo "</td></tr></table>\n";



    $result = mysql_db_query($database, "select * FROM userdata $sqlsearch ORDER by $orders $sort LIMIT $offset, $perpage") or die("Database Query Error");

    $result2 = mysql_db_query($database, "select * FROM login ") or die("Database Query Error");



    echo " <table align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\" width=\"100%\">\n";

    echo "   <tr>\n";

    echo "    <td class=\"gbheader\" align=\"left\">\n";

    echo "      <div class=\"smallleft\"><a href=\"$PHP_SELF?action=$action&value=$value&orders=username&sort=asc&offset=$offset&poffset=$poffset\">$memb_detuser</a></div>";

    echo "    </td>\n";

    echo "    <td class=\"gbheader\" width=\"30\">\n";

    echo "      <div class=\"smallcenter\"><a href=\"$PHP_SELF?action=$action&value=$value&orders=ads&sort=desc&offset=$offset&poffset=$poffset\">$memb_detads</a></div>";

    echo "    </td>\n";

    echo "    <td class=\"gbheader\" width=\"30\">\n";

    echo "      <div class=\"smallcenter\"><a href=\"$PHP_SELF?action=$action&value=$value&orders=votes&sort=desc&offset=$offset&poffset=$poffset\">$memb_detvot</a></div>";

    echo "    </td>\n";

    echo "    <td class=\"gbheader\" width=\"30\">\n";

    echo "      <div class=\"smallcenter\"><a href=\"$PHP_SELF?action=$action&value=$value&orders=email&sort=asc&offset=$offset&poffset=$poffset\">$memb_detmail</a></div>";

    echo "    </td>\n";

    echo "    <td class=\"gbheader\" width=\"30\">\n";

    echo "      <div class=\"smallcenter\"><a href=\"$PHP_SELF?action=$action&value=$value&orders=icq&sort=desc&offset=$offset&poffset=$poffset\">$memb_deticq</a></div>";

    echo "    </td>\n";

    echo "    <td class=\"gbheader\" width=\"30\">\n";

    echo "      <div class=\"smallcenter\"><a href=\"$PHP_SELF?action=$action&value=$value&orders=homepage&sort=desc&offset=$offset&poffset=$poffset\">$memb_deturl</a></div>";

    echo "    </td>\n";

    echo "    <td class=\"gbtable2\" align=\"right\" width=\"90\">\n";

    echo "	<div class=\"smallright\">per page: $perpage <br>sort: $orders</div>\n";

    echo "    </td>\n";

    echo "  </tr>\n";

    echo "</table>\n";



    while ($db = mysql_fetch_array($result)) {

	$result2 = mysql_db_query($database, "select * from login WHERE id=$db[id]") or die("Database Query Error");

	$db2 = mysql_fetch_array($result2);



	echo " <table align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\" width=\"100%\">\n";

	echo "   <tr>\n";

        echo "    <td class=\"gbtable2\" width=\"\">\n";

	echo "     <div class=\"smallleft\">$db[username]</div>";

        echo "    </td>\n";

        echo "    <td class=\"gbtable2\" width=\"30\">\n";

	echo "     <div class=\"smallcenter\">$db[ads]</div>";

        echo "    </td>\n";

        echo "    <td class=\"gbtable2\" width=\"30\">\n";

	echo "     <div class=\"smallcenter\">$db[votes]</div>";

        echo "    </td>\n";

        echo "    <td class=\"gbtable2\" width=\"30\">\n";

	echo "     <div class=\"smallcenter\">";



	    if ($db[email]) {

    		if ($sales_option && $sales_members && !$is_sales_user) { // check access for user

		    ico_email("","center");

		} else {

		    ico_email("username=$db[username]","center");

		}

	    }
 else {

		echo"&nbsp;";

	    }

        echo "	   </div>\n";

        echo "    </td>\n";

        echo "    <td class=\"gbtable2\" width=\"30\">\n";

	echo "     <div class=\"smallcenter\">";

	    if ($db[icq]) {

    		if ($sales_option && $sales_members && !$is_sales_user) { // check access for user

		    ico_icq("","center");

		} else {

		    ico_icq("$db[icq]","center");

		}

	    }
 else {

		echo"&nbsp;";

	    }

        echo "	   </div>\n";

        echo "    </td>\n";

        echo "    <td class=\"gbtable2\" width=\"30\">\n";

	echo "     <div class=\"smallcenter\">";

            if ($show_url && $db[homepage]) {

    		if ($sales_option && $sales_members && !$is_sales_user) { // check access for user

		    ico_url("","center");

		} else {

		    ico_url("$db[homepage]","center");

		}

	    }
 else {

		echo"&nbsp;";

            }

        echo "	   </div>\n";

        echo "    </td>\n";

        echo "    <td class=\"gbtable2\" align=\"center\" width=\"90\">\n";

	if (substr($db2[password],0,8) == "deleted_") {

	    echo "	   <div class=\"smallcenter\">$memb_detdeleted</div>\n";

	} else {

	    if ($show_members_details) {

    		if ($sales_option && $sales_members && !$is_sales_user) { // check access for user

	            echo "<center><img src=\"$image_dir/icons/signno.gif\" border=\"0\" alt=\"$sales_lang_noaccess\" align=\"center\" vspace=\"2\"

		        onmouseover=\"window.status='$sales_lang_noaccess'; return true;\"

		        onmouseout=\"window.status=''; return true;\">\n";

		} else {

		    echo "<div class=\"smallcenter\"><a href=\"$PHP_SELF?choice=details&uid=$db[id]&uname=$db[username]\">$memb_detdetails</a>\n";

		}

	    }

	}

        echo "    </td>\n";

	echo "  </tr>\n";

        echo "</table>\n";

	$count++;

    }

} else {

    echo "$memb_notenabled";

#    include ("member.php");

}

mysql_close();



?>