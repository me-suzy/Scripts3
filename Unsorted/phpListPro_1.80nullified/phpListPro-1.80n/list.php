<?
################################################################################################
#
#  project           	: phpListPro
#  filename          	: list.php
#  last modified by  	: Erich Fuchs
#  supplied by          : CyKuH [WTN]
#  nullified by      	: CyKuH [WTN]          
#  purpose           	: List the entry's
#
#################################################################################################




#  Include Configs & Variables & Functions

#################################################################################################

require ("config.php");



if ($HTTP_COOKIE_VARS["checkviewed"] != "1") {

    $cookietime=time()+(24*3600*365);

    setcookie("checkviewed", "1", "$cookietime", "$cookiepath");

    $firsttimeuser=true;

}





#header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");    // Date in the past

#header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");

                                                      // always modified

#header ("Cache-Control: no-cache, must-revalidate");  // HTTP/1.1

#header ("Pragma: no-cache");                          // HTTP/1.0





include($header);





function starttable($fromsite,$tosite,$list_name="") {

    global $lang,$show_rating,$show_randomlink,$catlink2;



    echo "<table class=\"list\" align=\"center\" cellspacing=\"1\">";

    if ($list_name) {

	echo"<tr><td class=\"listtitle\" colspan=5>";

	if ($show_randomlink) {

	    echo"<a href=\"out.php?site=random$catlink2\" target=\"_blank\" onmouseover=\"window.status='$lang[random]'; return true;\" onmouseout=\"window.status=''; return true;\">

		    <img src=\"images/random.gif\" border=\"0\" align=\"right\" alt=\"$lang[random]\"></a>";

	}

	echo"<div class=\"listtitle\">$list_name</div></td></tr>";

    }



    echo"<tr>";

    echo"<td class=\"listhead\"><div class=\"listhead\">$lang[rank]<div></td>";

    echo"<td class=\"listhead\"><div class=\"listhead\">$lang[site] [$fromsite - $tosite]<div></td>";

    echo"<td class=\"listhead\"><div class=\"listhead\">$lang[votes]<div></td>";

    echo"<td class=\"listhead\"><div class=\"listhead\">$lang[hits]<div></td>";

    if ($show_rating) {

	echo"<td class=\"listhead\"><div class=\"listhead\">$lang[rate]<div></td>";

    }

    echo"</tr>\n";

}



#  Status

#################################################################################################



if($status == "NOK") {

    echo $lang[anti_cheat_msg];

} elseif($status == "OK") {

    echo $lang[vote_log_msg];

} elseif($status == "NF") {

    echo $lang[not_found_msg];

} elseif($status == "TILT") {

    echo $lang[tilt_msg];

} elseif($status == "NOC") {

    echo $lang[nocookie_msg];

} elseif($status == "BAN") {

    echo $lang[ban_msg];

}





#  Start mySQL

#################################################################################################

mysql_connect($server, $db_user, $db_pass) or die ("Database Connect Error");





$timestamp=time();

if (empty($perpage) && $breaks_perpage>0) {$perpage = $breaks_perpage*$break_time;} else {$perpage = $max_listsize;}

if (empty($offset)) {$offset = 0;}



$sql = "SELECT id FROM sites WHERE 1=1";

if ($cat) {$sql.=" AND cat='$cat'";}

if ($search) {$sql.=" AND (site_name LIKE '%".$search."%' OR site_desc LIKE '%".$search."%')";}

$query = mysql_db_query($database, $sql) or die(geterrdesc($sql));

$listcountall=mysql_num_rows($query);



$sql = "SELECT * FROM sites WHERE inactive='0'";

if ($webmasterapproval) {$sql.=" AND approved='1'";}

if ($emailapproval) {$sql.=" AND emailapproved='1'";}

if ($tilt_insp) {$sql.=" AND tilt_time<'$timestamp'";}

if ($min_votes_require>0) {$sql.=" AND votes>='$min_votes_require'";}

if ($cat) {$sql.=" AND cat='$cat'";}

if ($search) {$sql.=" AND (site_name LIKE '%".$search."%' OR site_desc LIKE '%".$search."%')";}

$psql = $sql." ORDER BY $listsorting DESC, $listsorting2 DESC LIMIT 0, $max_listsize";

$sql .= " ORDER BY $listsorting DESC, $listsorting2 DESC LIMIT $offset, $perpage";

$pquery = mysql_db_query($database, $psql) or die(geterrdesc($psql));

$query = mysql_db_query($database, $sql) or die(geterrdesc($sql));

$pagecount=mysql_num_rows($pquery);

$listcount=mysql_num_rows($query);





#  PageNumbers

#################################################################################################



$pagesstr.="<table class=\"menu\" align=\"center\"><tr>\n";

if ($show_categories) {

    $pagesstr.="<td align=\"left\" width=\"1\">";

    echo"

	<SCRIPT LANGUAGE=\"JavaScript\"><!--

	function changecat(newcat) {

	    exit=false;

	    site = \"list.php?cat=\"+(newcat);

	    if (newcat!=0) {

		top.location.href=site;

	    } else {

		top.location.href=\"list.php\";

	    }

	}

	//--></SCRIPT>";



    $pagesstr.="<form action=\"$PHP_SELF\" name=\"cats\" method=\"POST\">\n";

    $pagesstr.="<select name=\"cats\" onchange=\"changecat(this.options[this.selectedIndex].value)\">";

    $selected[$cat]="SELECTED";

    for($i = 0; $i<count($category); $i++) {

	$pagesstr.= "<option value=\"$i\" $selected[$i]>$category[$i]</option>\n";

    }



    $pagesstr.="</select></form></td>";



}

if ($show_languages) {

    $pagesstr.="<td align=\"left\" width=\"1\">\n";

    $raw_list_url=rawurlencode($list_url);

    echo"

	<SCRIPT LANGUAGE=\"JavaScript\"><!--

	function changelang(newlang) {

	    exit=false;

	    site = \"lang.php?language=\"+(newlang)+\"&url=$raw_list_url\";

	    if (newlang!=0) {

		top.location.href=site;

	    } else {

		top.location.href=\"list.php\";

	    }

	}

	//--></SCRIPT>";



    $pagesstr.="<form action=\"$PHP_SELF\" name=\"lang\" method=\"POST\">\n";

    $pagesstr.="<select name=\"lang\" onchange=\"changelang(this.options[this.selectedIndex].value)\">";

    for($i = 0; $i<count($language); $i++) {

	if ($language[$i]==$default_language) {$selected="SELECTED";} else {$selected="";}

    	$pagesstr.= "<option value=\"$language[$i]\" $selected>$language[$i]</option>\n";

    }



    $pagesstr.="</select></form></td>\n";



}



if ($show_search) {

    $pagesstr.="<td align=\"left\" height=\"1\"><form action=\"$PHP_SELF\" name=\"searching\" method=\"POST\">\n";

    $pagesstr.="&nbsp;<input type=\"text\" name=\"search\" size=\"15\" maxlength=\"20\" value=\"$search\">\n";

    $pagesstr.="<input type=\"hidden\" name=\"cat\" value=\"$cat\">\n";

    $pagesstr.="<input type=\"submit\" value=\"$lang[search]\" name=\"submit_form\"></form></td>";

}

$pagesstr.="<td align=\"right\">\n";



$pages = ceil($pagecount / $perpage);$actpage = ($offset+$perpage)/$perpage;if ($pages>1) {                   // print only when pages > 0

$pagesstr.="<div class=\"smallright\">$lang[pages]\n";

    for($i = 0; $i < $pages; $i++) {

    	$noffset = $i * $perpage;

	$actual = $i + 1;

        if ($actual==$actpage) {

             $pagesstr.="(<b>$actual</b>) ";

        } else {

     	     $pagesstr.="[<a href=\"list.php?offset=$noffset$catlink2$searchlink2\" onclick=\"exit=false\">$actual</a>] ";

        }

    }

}

$pagesstr.="</div></td></tr></table>\n";



#  The List-Section

#################################################################################################



include($menu);

echo $pagesstr;



if (is_file("break_0.inc")) { include("break_0.inc");}



if ($break_afterbanners && $max_banners>0 && $max_banners<$break_time && $offset==0) {

    $tosite=$max_banners;

} else {

    $tosite=$break_time+$offset;

}



starttable($offset+1,$tosite,$list_name);



$break_timecount=$break_time+$offset;

$breakcount=1;

$i=$offset+1;

while (($list = mysql_fetch_array($query)) && ($i <= $max_listsize)) {

    if(!empty($list[banner_height]) && !empty($list[banner_width]) && !empty($list[banner_addr]) && ($max_banners >= $i)){

	$show_banner = "<a href=\"$list_url/out.php?site=$list[id]\" target=_blank onmouseover=\"window.status='$list[site_addr]'; return true;\" onmouseout=\"window.status=''; return true;\">\n";

	$show_banner .= "<img src=\"$list[banner_addr]\" border=0 width=\"$list[banner_width]\" height=\"$list[banner_height]\"></a><br>\n";

    } else {

	$show_banner = "";

    }



    if ($show_newicon && $list[id]>($timestamp-24*3600*$show_newicon)) {

	$show_newiconstr = "<br><img src=\"images/new.gif\" border=\"0\">";

    } else {

	$show_newiconstr = "";

    }



    echo "<tr><td class=\"listrowrank\"><div class=\"listrowrank\">".($i)."$show_newiconstr</div></td>\n";

    echo "<td class=\"listrowsite\">$show_banner\n";

    echo "<a href=\"$list_url/out.php?site=$list[id]\" target=_blank onmouseover=\"window.status='$list[site_addr]'; return true;\" onmouseout=\"window.status=''; return true;\">".stripslashes($list[site_name])."</a><br>\n";

    echo "<div class=\"listrowsite\">".stripslashes($list[site_desc])."</div></td>\n";

    echo "<td class=\"listrowvotes\"><div class=\"listrowvotes\">$list[votes]</div></td>\n";

    echo "<td class=\"listrowhits\"><div class=\"listrowhits\">$list[hits]</div></td>\n";

    if ($show_rating) {

	echo "<td class=\"listrowrate\"><div class=\"listrowrate\">".round($list[rating]*10)/10;



	if($show_ratingbar) {

	    $bar_in_width = $list[rating]*10;

	    $bar_in_height = $ratingbar_height-1;

	    if($bar_in_width >= $ratingbar_high) {

	        $ratingbarin="ratingbarinhigh";

	    } else {

	        $ratingbarin="ratingbarin";

	    }

	    echo "<table align=center border=0 cellspacing=0 cellpadding=1 width=\"$ratingbar_width\" height=\"$bar_height\">\n";

	    echo " <tr>\n";

	    echo "  <td class=\"ratingbarout\">\n";

	    echo "   <table align=left border=0 cellspacing=0 cellpadding=0 width=\"$bar_in_width%\" height=\"$bar_in_height\">\n";

	    echo "    <tr>\n";

	    echo "     <td class=\"$ratingbarin\">\n";

	    echo "        <div class=\"ratingbarspace\">&nbsp;<div>\n";                        // Netscape Patch !!! shit upgrade to IE

	    echo "     </td>\n";

	    echo "    </tr>\n";

	    echo "   </table>\n";

	    echo "  </td>\n";

	    echo " </tr>\n";

	    echo "</table>\n";

	}



	echo "</div></td></tr>\n";

    }



    // Break's



    if($break_afterbanners && $max_banners == $i && $listcount+$offset!=$i) {

	echo "</table>\n";

	if (is_file("break_".$breakcount.".inc")) { include("break_".$breakcount.".inc");} else {echo("<p>");}

	if ($max_banners>=$break_time) {

	    $break_timecount+=$break_time;

	    $breakcount++;

	    if ($i<$max_listsize) { starttable($max_banners+1,$break_timecount);}

	} else {

	    $breakcount++;

    	    if ($i<$max_listsize) { starttable($max_banners+1,$break_time);}

	}

    } elseif($break_timecount == $i && $listcount+$offset!=$i) {

	echo "</table>\n";

	if (is_file("break_".$breakcount.".inc")) { include("break_".$breakcount.".inc");} else {echo("<p>");}

	$break_timecount+=$break_time;

	$breakcount++;

	if ($i<$max_listsize) {

    	    starttable($i+1,$break_timecount);

	}

    }



    // End Break's



    $i++;



} //end while



echo "</table></div>";



#  The Footer-Section

#################################################################################################

echo $pagesstr;

include($menu);





// Variables Routine

$sql = "SELECT * FROM variables";

$query = mysql_db_query($database, $sql) or die(geterrdesc($sql));

$listvar = mysql_fetch_array($query);



$listvar[counter]++;

$sql = "UPDATE variables SET counter='$listvar[counter]'";

mysql_db_query($database, $sql) or die(geterrdesc($sql));



if ($listvar[resettimestamp]<$timestamp && $days_to_reset) { //time to reset the list ?

    if ($setinactive_on_reset) {

        $sql = "UPDATE sites SET inactive='1' WHERE votes=0";

	mysql_db_query($database, $sql) or die(geterrdesc($sql));

    }

    if ($setactive_on_reset) {

        $sql = "UPDATE sites SET inactive='0' WHERE votes>0";

	mysql_db_query($database, $sql) or die(geterrdesc($sql));

    }

    // reset list

    $sql = "UPDATE sites SET votes='0',hits='0',rating='5.0'";

    mysql_db_query($database, $sql) or die(geterrdesc($sql));

    // set new resettimestamp

    $newtimestamp=$timestamp+($days_to_reset*24*3600);

    $sql = "UPDATE variables SET resettimestamp='$newtimestamp'";

    mysql_db_query($database, $sql) or die(geterrdesc($sql));

}

// End Variables



echo "<div class=\"footer\">\n";

if ($show_useronline) {

    $timeout=time()-300;  // value in seconds

    $result=mysql_db_query($database, "INSERT INTO useronline VALUES ('$timestamp','$REMOTE_ADDR')");

    $result=mysql_db_query($database, "DELETE FROM useronline WHERE timestamp<$timeout");

    $result=mysql_db_query($database, "SELECT DISTINCT ip FROM useronline") or die("Useronline Error");

    $user  =mysql_num_rows($result);

    if ($user==1) {

	$useronline=", $user $lang[useronline]";

    } else {

	$useronline=", $user $lang[usersonline]";

    }

}

if ($show_counter) {

    $usercounter=", $listvar[counter] $lang[userssatisfied]";

}

echo" $listcountall $lang[sitesindb]$useronline$usercounter<br>";

if ($days_to_reset) {

    echo "$lang[inoutresets] $days_to_reset $lang[days],";

    echo "$lang[next]: ".date("M/j/Y h:i a T", $listvar[resettimestamp])."<br><br>\n";

}

echo "PHPListPro Ver. $list_ver &copy;2001-".date("Y")." <!--CyKuH [WTN]-->SmartISoft<br>\n";

echo "</div>\n";



if ($firsttimeuser && $show_favwinfirsttime) {

    echo "<script language=javascript>window.external.AddFavorite(\"$list_url\",\"$list_name\")</script>";

}



include($footer);



#  End mySQL

#################################################################################################

mysql_close();



?>
