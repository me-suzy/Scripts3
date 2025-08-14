<?php

if (!eregi("modules.php", $PHP_SELF)) {
    die ("You can't access this file directly...");
}
if (!isset($mainfile)) { include("mainfile.php"); }
$index=1;
global  $Pmenu,$breadcrumb;
$Pmenu="";
$breadcrumb="Active topics";
$templatesused ='P_activetopicbits,P_activetopics';
 include("header.php");
$num_active=25;
// Display activetopics
global $DB_site,$bgcolor3,$bgcolor4,$dateformat,$timeformat;

	if ($excatforums == "" or $excatforums <= "0") {
		$whereatsql = "WHERE visible";
	} else {
             $whereatsql = "WHERE thread.open!='10'";
		$excatfid = explode(",",$excatforums); $ia = 0; $vbp_at = count($excatfid);
		while ($ia < $vbp_at) {
		$whereatsql .= "AND forumid!='$excatfid[$ia]'";
       	++$ia;
       	}
        $whereatsql .= "AND visible";
    }
	$result = $DB_site->query("select * from thread $whereatsql order by lastpost desc limit $num_active");
	while ($latest_array = $DB_site->fetch_array($result)) {

	// Get Forum Infomation
	$result_forum = $DB_site->query("select * from forum where forumid='$latest_array[forumid]'");
	$forum_info_array = $DB_site->fetch_array($result_forum);
	$result_thread_text = $DB_site->query("select * from post where threadid='$latest_array[threadid]' order by dateline desc limit 1");
	$result_thread_array = $DB_site->fetch_array($result_thread_text);
	$title1 = substr($forum_info_array["title"],0,$ftitle1en);
    $title1 =substr ( $title1, 0, strrpos($title1," "));
    $title2 = substr($latest_array["title"],0,$ttitle1en);
    if (strlen($title2) > $ttitle1en) {
	    $title2 =substr ( $title2, 0, strrpos($title2," "));
    }
    $aticonid = $result_thread_array["iconid"];
    $startedby = $latest_array["postusername"];
    $lastposter = $latest_array["lastposter"];
    $mpostid= $result_thread_array["postid"];
     $atpostdate=vbdate($dateformat,$result_thread_array[dateline]);
     $atposttime=vbdate($timeformat,$result_thread_array[dateline]);
     if ($showtopicdesc){
        $atpagetext = $result_thread_array[pagetext];
        if (strlen($atpagetext) > $atpagetext) {
		   $atpagetext = substr($atpagetext,0,$num_topicchars);
           $atpagetext =substr ( $atpagetext, 0, strrpos($atpagetext," "));
		   $atpagetext .= "...";
		}
         $atpagetext=bbcodeparse($atpagetext,$forumid,1);
    }else{
		$atpagetext="";
	}

      $gothreadid = $latest_array["threadid"];
	if (($counter++ % 2) != 0) {
		$vbp_atbc=$vbp_atbc1;
	} else {
		$vbp_atbc=$vbp_atbc2;
	}

	 eval("\$P_activetopic_centerboxbit .= \"".gettemplate('P_activetopic_centerboxbit')."\";");
   	}

eval("dooutput(\"".gettemplate('P_activetopic_centerbox')."\");");
unset($num_active);
include("footer.php");
  

?>