<?

#################################################################################################
#
#  project           	: phpBazar
#  filename          	: guestbook_show.php
#  last modified by  	: Erich Fuchs
#  supplied by          : Reseting
#  nullified by	        : CyKuH [WTN]
#  purpose           	: Show the guestbook entry's
#
#################################################################################################





#  Connect to the DB

#################################################################################################



mysql_connect($server, $db_user, $db_pass);





echo "<table align=\"center\"  cellspacing=\"0\" cellpadding=\"3\" width=\"100%\" border=\"0\">\n";

echo "<tr><td><div class=\"maincatnav\">\n";

echo "&nbsp<br>\n";

echo "</div></td>\n";





#  Calculate Page-Numbers

#################################################################################################

if (empty($perpage)) $perpage = 5;

if (empty($pperpage)) $pperpage = 9;	//!!! ONLY 5,7,9,11,13 !!!!

if (empty($sort)) $sort = "desc";

if (empty($offset)) $offset = 0;

if (empty($poffset)) $poffset = 0;





$amount = mysql_db_query($database, "SELECT count(*) FROM guestbook");

$amount_array = mysql_fetch_array($amount);

$pages = ceil($amount_array["0"] / $perpage);

$actpage = ($offset+$perpage)/$perpage;

$maxpoffset = $pages-$pperpage;

$middlepage=($pperpage-1)/2;

if ($maxpoffset<0) {$maxpoffset=0;}





echo "<td><div class=\"mainpages\">\n";





if ($pages) {                                       // print only when pages > 0

    echo "$ad_pages\n";



    if ($offset) {

        $noffset=$offset-$perpage;

        $npoffset = $noffset/$perpage-$middlepage;

        if ($npoffset<0) {$npoffset=0;}

        if ($npoffset>$maxpoffset) {$npoffset = $maxpoffset;}

	echo "[<a href=\"guestbook.php?offset=$noffset&poffset=$npoffset\"

            onmouseover=\"window.status='$nav_prev'; return true;\"

    	    onmouseout=\"window.status=''; return true;\"><</a>] ";

	}



    for($i = $poffset; $i< $poffset+$pperpage && $i < $pages; $i++) {

    	$noffset = $i * $perpage;

        $npoffset = $noffset/$perpage-$middlepage;

        if ($npoffset<0) {$npoffset = 0;}

        if ($npoffset>$maxpoffset) {$npoffset = $maxpoffset;}

	$actual = $i + 1;

        if ($actual==$actpage) {

 	    echo "(<a href=\"guestbook.php?offset=$noffset&poffset=$npoffset\"

            onmouseover=\"window.status='$nav_actpage'; return true;\"

    	    onmouseout=\"window.status=''; return true;\">$actual</a>) ";

            } else {

 	    echo "[<a href=\"guestbook.php?offset=$noffset&poffset=$npoffset\"

            onmouseover=\"window.status='$nav_gopage'; return true;\"

    	    onmouseout=\"window.status=''; return true;\">$actual</a>] ";

	    }

	}



    if ($offset+$perpage<$amount_array["0"]) {

        $noffset=$offset+$perpage;

        $npoffset = $noffset/$perpage-$middlepage;

        if ($npoffset<0) {$npoffset=0;}

        if ($npoffset>$maxpoffset) {$npoffset = $maxpoffset;}

	echo "[<a href=\"guestbook.php?offset=$noffset&poffset=$npoffset\"

            onmouseover=\"window.status='$nav_next'; return true;\"

    	    onmouseout=\"window.status=''; return true;\">></a>] ";

        }

    }



echo "</div></td></tr>\n";

echo "</table>\n";









#  Start the Page

#################################################################################################







echo "<table align=\"center\"  cellspacing=\"1\" cellpadding=\"3\" width=\"100%\" border=\"0\">\n";

echo "   <tr>\n";

echo "     <td class=\"gbheader\">$gb_name</td>\n";

echo "     <td class=\"gbheader\">$gb_comments</td>\n";

echo "   </tr>\n";





#  Get Entrys for current page

#################################################################################################



$result = mysql_db_query($database, "SELECT * FROM guestbook ORDER by id $sort LIMIT $offset, $perpage");



while ($db = mysql_fetch_array($result)) {

    $when = date($dateformat, $db["timestamp"]);



    if ($db[email]   != "none") {

	$email = "<a href=\"mailto: $db[email]\"><img src=\"$image_dir/icons/email.gif\" alt=\" Send E-Mail\" border=\"0\" align=\"right\"></a>";

	} else {

	$email = "";

	}

    if ($db[icq]     != 0)      {

	$icq = "<a href=\"http://wwp.icq.com/scripts/contact.dll?msgto=$db[icq]\"><img src=\"http://wwp.icq.com/scripts/online.dll?icq=" . $db[icq] . "&img=5\" alt=\"Send ICQ Message\" border=\"0\" align=\"right\" height=\"17\"></a>";

	} else {

	$icq = "";

	}

    if ($db[http]    != "none") {

	$http = "<a href=\"http://$db[http]\" target=\"_blank\"><img src=\"$image_dir/icons/home.gif\" alt=\"View Web Page\" border=\"0\" align=\"right\"></a>";

	} else {

	$http = "";

	}

    if ($db[ip]      != "none") {

	$ips = "<img src=\"$image_dir/icons/ip.gif\" alt=\"IP logged\" align=\"left\">";

	} else {

	$ips = "";

	}

    if ($db[location]!= "none") {

	$location = "$gb_location<br>$db[location]<br>";

	} else {

	$location = "<br><br>";

	}

    if ($db[browser]      != "") {

	$browser = "<img src=\"$image_dir/icons/browser.gif\" alt=\"$db[browser]\" align=\"left\">";

	} else {

	$browser = "";

	}



    if ($login_check) {$mod=$login_check[2];}

    echo "  <tr>\n";

    echo "     <td class=\"gbtable1\">\n";

    echo "        <em id=\"red\">".badwords($db[name],$mod)."</em><br>\n";

    echo "        <div class=\"smallleft\">$location<br></div>\n";

    echo "        <br>$icq $http $email $ips $browser\n";

    echo "     </td>\n";

    echo "        <td class=\"gbtable2\"><div class=\"smallleft\">\n";

    if ($mod) {

	echo "<a href=\"guestbook_submit.php?delid=$db[id]\"><img src=\"$image_dir/icons/trash.gif\" alt=\"MODERATOR Delete Entry\" border=\"0\" align=\"right\"></a>";

	echo "<div class=\"spaceleft\">&nbsp;</div>\n";

	}

    echo "        $gb_posted $when</div><hr>".badwords($db[message],$mod)."</td>\n";

    echo "  </tr>\n";

    }



# End of Page reached

#################################################################################################

echo "</table>\n";

#echo "<br>\n";

#echo "</div>";





#  Disconnect DB

#################################################################################################

mysql_close();

?>