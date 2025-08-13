<?


################################################################################################


#


#  project           	: phpListPro


#  filename          	: newlist.php


#  last modified by  	: Erich Fuchs


#  e-mail            	: office@smartisoft.com


#  purpose           	: List new entry's as a textlist (for implementation in any site)


#


#################################################################################################





$maxnewlist=10; 		//shows max n entries


$maxchars=10;			//shows max n chars





#  Include Configs & Variables & Functions


#################################################################################################


require ("config.php");








#  Start mySQL


#################################################################################################


mysql_connect($server, $db_user, $db_pass) or die ("Database Connect Error");





$timestamp=time();


$newtimestamp=$timestamp-24*3600*$show_newicon;





$sql = "SELECT * FROM sites WHERE inactive='0'";


if ($webmasterapproval) {$sql.=" AND approved='1'";}


if ($emailapproval) {$sql.=" AND emailapproved='1'";}


if ($tilt_insp) {$sql.=" AND tilt_time<'$timestamp'";}


if ($min_votes_require>0) {$sql.=" AND votes>='$min_votes_require'";}


if ($cat) {$sql.=" AND cat='$cat'";}


$sql .= " AND id>$newtimestamp ORDER BY id DESC LIMIT 0, $maxnewlist";


$query = mysql_db_query($database, $sql) or die(geterrdesc($sql));


$listcount=mysql_num_rows($query);





$i=1;


echo "<table>";


while (($list = mysql_fetch_array($query)) && ($i <= $max_listsize)) {





    echo "<tr><td class=\"standard\"><div class=\"listrowrank\">".($i)."</div></td>\n";


    echo "<td class=\"standard\"><a href=\"$list_url/out.php?site=$list[id]\" target=_blank onmouseover=\"window.status='$list[site_addr]'; return true;\" onmouseout=\"window.status=''; return true;\">".substr(stripslashes($list[site_name]),0,$maxchars)."</a><br>\n";


    echo "</td></tr>";





#    // Break's


#


#    if($break_afterbanners && $max_banners == $i && $listcount+$offset!=$i) {


#	echo "</table>\n";


#	if (is_file("break_".$breakcount.".inc")) { include("break_".$breakcount.".inc");} else {echo("<p>");}


#	if ($max_banners>=$break_time) {


#	    $break_timecount+=$break_time;


#	    $breakcount++;


#	    if ($i<$max_listsize) { starttable($max_banners+1,$break_timecount);}


#	} else {


#	    $breakcount++;


#    	    if ($i<$max_listsize) { starttable($max_banners+1,$break_time);}


#	}


#    } elseif($break_timecount == $i && $listcount+$offset!=$i) {


#	echo "</table>\n";


#	if (is_file("break_".$breakcount.".inc")) { include("break_".$breakcount.".inc");} else {echo("<p>");}


#	$break_timecount+=$break_time;


#	$breakcount++;


#	if ($i<$max_listsize) {


#    	    starttable($i+1,$break_timecount);


#	}


#    }


#


#    // End Break's





    $i++;





} //end while


echo "</table>";





#  End mySQL


#################################################################################################


mysql_close();





?>
