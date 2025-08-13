<?
#################################################################################################
#
#  project           	: phpBazar
#  filename          	: classified_results.php
#  last modified by  	: Erich Fuchs
#  supplied by          : Reseting
#  nullified by	        : CyKuH [WTN]
#  purpose           	: Show the classified ads search results
#
#################################################################################################



#  Connect to the DB

#################################################################################################

mysql_connect($server, $db_user, $db_pass);





#  Ads Overview

#################################################################################################

if ($login_check) {$mod=$login_check[2];}

if ($login_check) {$userid=$login_check[1];}

if ($adapproval && !$mod) {$approval="AND publicview='1'";}

if (empty($ad_sort)) $ad_sort = "addate desc";





if (!$adid && $sqlquery) {



    if ($sqlquery2 && $sqlquery2!="*") {

	$keywords = ereg_replace(" ",",",$sqlquery2);

	$text = explode(",",$keywords);



	for ($i=0;$i<count($text);$i++) {

	  if ($text[$i]) {

	    $sql2=" AND (header LIKE '%".$text[$i]."%' OR text LIKE '%".$text[$i]."%' OR sfield LIKE '%"

	    .$text[$i]."%' OR field1 LIKE '%".$text[$i]."%' OR field2 LIKE '%".$text[$i]."%' OR field3 LIKE '%"

	    .$text[$i]."%' OR field4 LIKE '%".$text[$i]."%' OR field5 LIKE '%".$text[$i]."%' OR field6 LIKE '%"

	    .$text[$i]."%' OR field7 LIKE '%".$text[$i]."%' OR field8 LIKE '%".$text[$i]."%' OR field9 LIKE '%"

	    .$text[$i]."%' OR field10 LIKE '%".$text[$i]."%' OR field11 LIKE '%".$text[$i]."%' OR field12 LIKE '%"

	    .$text[$i]."%' OR field13 LIKE '%".$text[$i]."%' OR field14 LIKE '%".$text[$i]."%' OR field15 LIKE '%"

	    .$text[$i]."%' OR field16 LIKE '%".$text[$i]."%' OR field17 LIKE '%".$text[$i]."%' OR field18 LIKE '%"

	    .$text[$i]."%' OR field19 LIKE '%".$text[$i]."%' OR field20 LIKE '%".$text[$i]."%')";

	  }

	}

    }



    $nsqlquery=rawurlencode(stripslashes($sqlquery));

    $nsqlquery2=rawurlencode(stripslashes($sqlquery2));

    $nsqlquery3=rawurlencode(stripslashes($sqlquery3));





    # Calculate Page-Numbers

    #################################################################################################

    if (empty($perpage)) $perpage = 5;

    if (empty($pperpage)) $pperpage = 5;    // !!! ONLY 3,5,7,9,11,13 !!!

    if (empty($offset)) $offset = 0;

    if (empty($poffset)) $poffset = 0;



    $sql = "SELECT count(*) FROM ads".stripslashes($sqlquery.$sql2.$sqlquery3);

    $amount = mysql_db_query($database, "$sql") or died("SQL Error: $sqlquery");

    $amount_array = mysql_fetch_array($amount);

    $pages = ceil($amount_array["0"] / $perpage);

    $actpage = ($offset+$perpage)/$perpage;

    $maxpoffset = $pages-$pperpage;

    $middlepage=($pperpage-1)/2;

    if ($maxpoffset<0) {$maxpoffset=0;}



    $from_result=$offset+1;

    $to_result=$offset+$perpage;

    if ($to_result>$amount_array[0]) {$to_result=$amount_array[0];}



    echo "<table align=\"center\"  cellspacing=\"0\" cellpadding=\"3\" width=\"100%\" border=\"0\">\n";

    echo "<tr><td><div class=\"maincatnav\">\n";

    echo "<a href=\"classified.php\" onmouseover=\"window.status='$ad_home'; return true;\"

	onmouseout=\"window.status=''; return true;\">$ad_home</a> / $adseek_result $from_result-$to_result ($amount_array[0]) <br>\n";

    echo "</div></td>\n";



    echo "<td><div class=\"mainpages\">\n";



    if ($pages) {                                       // print only when pages > 0

      echo "$ad_pages\n";



      if ($offset) {

	$noffset=$offset-$perpage;

	$npoffset = $noffset/$perpage-$middlepage;

	if ($npoffset<0) {$npoffset = 0;}

	if ($npoffset>$maxpoffset) {$npoffset = $maxpoffset;}

        echo "[<a href=\"classified.php?sqlquery=$nsqlquery&sqlquery2=$nsqlquery2&sqlquery3=$nsqlquery3&offset=$noffset&poffset=$npoffset\"

	     onmouseover=\"window.status='$nav_prev'; return true;\"

	     onmouseout=\"window.status=''; return true;\"><</a>]\n";

      }

      for($i = $poffset; $i< $poffset+$pperpage && $i < $pages; $i++) {

        $noffset = $i * $perpage;

	$npoffset = $noffset/$perpage-$middlepage;

	if ($npoffset<0) {$npoffset = 0;}

	if ($npoffset>$maxpoffset) {$npoffset = $maxpoffset;}

	$actual = $i + 1;

        if ($actual==$actpage) {

	    echo "(<a href=\"classified.php?sqlquery=$nsqlquery&sqlquery2=$nsqlquery2&sqlquery3=$nsqlquery3&offset=$noffset&poffset=$npoffset\"

	     onmouseover=\"window.status='$nav_actpage'; return true;\"

	     onmouseout=\"window.status=''; return true;\">$actual</a>)\n";

	    } else {

	    echo "[<a href=\"classified.php?sqlquery=$nsqlquery&sqlquery2=$nsqlquery2&sqlquery3=$nsqlquery3&offset=$noffset&poffset=$npoffset\"

	     onmouseover=\"window.status='$nav_gopage'; return true;\"

	     onmouseout=\"window.status=''; return true;\">$actual</a>]\n";

	    }

      }



      if ($offset+$perpage<$amount_array["0"]) {

        $noffset=$offset+$perpage;

	$npoffset = $noffset/$perpage-$middlepage;

	if ($npoffset<0) {$npoffset = 0;}

	if ($npoffset>$maxpoffset) {$npoffset = $maxpoffset;}

        echo "[<a href=\"classified.php?sqlquery=$nsqlquery&sqlquery2=$nsqlquery2&sqlquery3=$nsqlquery3&offset=$noffset&poffset=$npoffset\"

	     onmouseover=\"window.status='$nav_next'; return true;\"

	     onmouseout=\"window.status=''; return true;\">></a>]\n";

      }

    }



    echo "</div></td></tr>\n";

    echo "</table>\n";



    $sqlquery = "SELECT * FROM ads".stripslashes($sqlquery.$sql2.$sqlquery3)." LIMIT $offset, $perpage";

    $result = mysql_db_query($database, "$sqlquery") or died("SQL Error: $sqlquery");



    echo "<table align=\"center\" cellspacing=\"1\" cellpadding=\"3\" width=\"100%\" border=\"0\">\n";



    while ($db = mysql_fetch_array($result)) {



	$result2 = mysql_db_query($database, "SELECT * FROM userdata WHERE id=$db[userid]") or died("Record NOT Found");

	$dbu = mysql_fetch_array($result2);

	$result3 = mysql_db_query($database, "SELECT * FROM adcat WHERE id=$db[catid]") or died("Record NOT Found");

	$dbc = mysql_fetch_array($result3);



	if ($sales_option && $sqlquery && !sales_checkaccess(1,$userid,$db[catid])) { // check access for user and cat

	    // if no acces to cat, do nothing :-)

	} else {

	    include ("classified_ads.inc.php");

	}

    } //End while

    echo "</table>\n";







}

# End of Page reached

#################################################################################################





#  Disconnect DB

#################################################################################################

mysql_close();

?>