<?


################################################################################################
#
#  project           	: phpListPro
#  filename          	: in.php
#  last modified by  	: Erich Fuchs
#  supplied by          : CyKuH [WTN]
#  nullified by      	: CyKuH [WTN]          
#  purpose           	: Count IN Links
#
#################################################################################################





#  Include Configs & Variables


#################################################################################################


require ("config.php");





if (getenv(HTTP_CLIENT_IP)){


    $ipaddr=getenv(HTTP_CLIENT_IP);


} else {


    $ipaddr=getenv(REMOTE_ADDR);


}





mysql_connect($server, $db_user, $db_pass) or die ("Database Connect Error");





if ($site) {


  $sql = "SELECT * FROM sites WHERE id='$site' ";


  $query = mysql_db_query($database, $sql) or die(geterrdesc($sql));


  $result = mysql_fetch_array($query);


  if ($result[cat]>0) {


    $catlink="&cat=$result[cat]";


  }





  if ($result && empty($vote) && $gateway) {





    if ($cookievotesonly) { 	//set testcookie


	$cookietime=time()+360;


	setcookie("CheckCookie_$site", "1", $cookietime, "$cookiepath");  //must vote within 360 seconds


    }





    include($header);





    echo "<div class=\"maincenter\">";


    echo "$lang[in_msg1]";


    echo " <a href=\"$result[site_addr]\" target=_blank>$result[site_name]</a><br>\n";


    echo "$lang[in_msg2]<p>\n";





    if ($show_rating) {


        echo "\n\n<script Language=\"JavaScript\"><!--\n";


	echo "function Form_Validator(theForm) {\n";


        echo "    exit=false;\n";


        echo "    if (theForm.rating.selectedIndex == 0) {\n";


        echo "      alert(\"$lang[in_chose]\");\n";


	echo "      theForm.rating.focus();\n";


        echo "      return (false);\n";


	echo "    }\n";


        echo "    return (true);\n";


	echo "} //--></script>\n\n";


        echo "<form action=\"in.php\" method=\"post\" onsubmit=\"return Form_Validator(this)\" name=\"Form\">\n";


	echo "<select name=rating size=1>\n";


        echo "<option selected value=0>Rating</option>\n";


	echo "<option value=1>1 (worst)</option>\n";


        echo "<option value=2>2</option>\n";


	echo "<option value=3>3</option>\n";


        echo "<option value=4>4</option>\n";


	echo "<option value=5>5</option>\n";


        echo "<option value=6>6</option>\n";


	echo "<option value=7>7</option>\n";


        echo "<option value=8>8</option>\n";


	echo "<option value=9>9</option>\n";


        echo "<option value=10>10 (best)</option>\n";


        echo "</select>&nbsp;&nbsp;\n";


    } else {


	echo "<form action=\"in.php\" method=\"post\" name=\"Form\">\n";


    }


    echo "<input type=submit name=vote value=\"$lang[in_vote]\">\n";


    echo "<input type=hidden name=site value=\"$site\">\n";


    echo "<input type=hidden name=referer value=\"$HTTP_REFERER\">\n";





    echo "</form>\n";





    echo "<br><br><a href=$list_url/list.php?status=NOV$catlink onclick=\"exit=false\">$lang[in_msg3]</a></div>\n";





    include($infooter);





  } elseif(!empty($vote) && !eregi("$list_url/in.php",$HTTP_REFERER) && $gateway) {





    header("location: $list_url/list.php?status=NOR$catlink");





  } elseif($result) {





    $status="OK";





    if ($bad_ips) {


	if(check_bad_ips($ipaddr) || $HTTP_COOKIE_VARS["BanCookie"]) {


    	    $status="BAN";


	    $cookietime=time()+($banip_cookietime*24*3600);


	    setcookie("BanCookie", "1", $cookietime, "$cookiepath");


	}


    }





    if ($cookievotesonly) { 	// check testcookie


        if($HTTP_COOKIE_VARS["CheckCookie_$site"] != 1){


    	    $status="NOC";


	} else {


	    setcookie("CheckCookie_$site", "", 0, "$cookiepath");  //delete checkcookie


	}


    }





    if ($anti_cheating && $status=="OK") {	// anti-cheating


	$votetimestamp=time()-($vote_timeout*3600);


	$sql = "SELECT * FROM votes WHERE id='$site' AND timestamp>'$votetimestamp' AND ip='$ipaddr' AND status='OK'";


	$query = mysql_db_query($database, $sql) or die(geterrdesc($sql));


	$votesresult = mysql_fetch_array($query);





	if ($votesresult) {


	    $status="NOK";


	}





        if($HTTP_COOKIE_VARS["phpListPro_$site"] != 1){


	    $cookietime=time()+($vote_timeout*3600);


	    setcookie("phpListPro_$site", "1", $cookietime, "$cookiepath");


	} else {


    	    $status="NOK";


	}








    }





    if ($tilt_insp) {		// check cheating





	$tilttimestamp=time()-($tilt_insp_timeframe*60);





	$sql = "SELECT id FROM votes WHERE id='$site' AND timestamp>'$tilttimestamp' AND status='OK'";


	$query = mysql_db_query($database, $sql) or die(geterrdesc($sql));


	$tiltresultok = mysql_num_rows($query);





	if ($tiltresultok>=$tilt_insp_maxvotesok) {


	    $status="TILT";


	}





	$sql = "SELECT id FROM votes WHERE id='$site' AND timestamp>'$tilttimestamp' AND status='NOK'";


	$query = mysql_db_query($database, $sql) or die(geterrdesc($sql));


	$tiltresultnok = mysql_num_rows($query);





	if ($tiltresultnok>=$tilt_insp_maxvotesnok) {


	    $status="TILT";


	}





	if ($status=="TILT") {			// ban this site for $tilt_insp_timeout





	    $sql = "SELECT * FROM sites WHERE id='$site' ";


	    $query = mysql_db_query($database, $sql) or die(geterrdesc($sql));


	    $result = mysql_fetch_array($query);





	    $tilt_time=time()+($tilt_insp_timeout*60);


	    $sql = "UPDATE sites SET tilt_time='$tilt_time' WHERE id='$site'";


    	    mysql_db_query($database, $sql) or die(geterrdesc($sql));





	    if ($result[tilt_time]<($tilt_time-(2*$tilt_insp_timeout*60))) { // check if this is a new tilt


		if ($tilt_insp_adminnotify) {	// send notification to admin


		    mail_tiltuser($result);


		}


		if ($tilt_insp_sitenotify) {	// send notification to site-admin


		    mail_tiltadmin($result);


		}


	    }








	}





    }








    if ($show_rating && $gateway && $vote && $rating && $rating<=10) {


	if ($status=="OK") {


	    $newrating=round((($result[votes]*$result[rating])+$rating)/($result[votes]+1)*10)/10;


	    $sql = "UPDATE sites SET votes=votes+1,rating='$newrating' WHERE id='$site'";


    	    mysql_db_query($database, $sql) or die(geterrdesc($sql));


	}


	$sql = "INSERT INTO votes VALUES ('$site','$ipaddr','".time()."','$referer','$status')";


	mysql_db_query($database, $sql) or die(geterrdesc($sql));


    } elseif ($status=="OK") {


	if ($status=="OK") {


	    $sql = "UPDATE sites SET votes=votes+1 WHERE id='$site'";


    	    mysql_db_query($database, $sql) or die(geterrdesc($sql));


	}


	$sql = "INSERT INTO votes VALUES ('$site','$ipaddr','".time()."','$HTTP_REFERER','$status')";


	mysql_db_query($database, $sql) or die(geterrdesc($sql));


    }





    if ($votelog_timeout>0) {  		// delete old votelogs


	if ($votelog_timeout<$vote_timeout) {$votelog_timeout=$vote_timeout;}


	$deletetimestamp=time()-($votelog_timeout*3600);


        $sql = "DELETE FROM votes WHERE timestamp<'$deletetimestamp'";


	mysql_db_query($database, $sql) or die(geterrdesc($sql));


    }





    header("location: $list_url/list.php?status=$status$catlink");





  } else {	// ERROR - Site not found


    header("location: $list_url/list.php?status=NF$catlink");


  }


} else { 	// ERROR - NO SiteId


    header("location: $list_url/list.php?status=NF$catlink");


}





mysql_close();





?>
