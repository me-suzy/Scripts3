<?php
/************************************************************************/
/* vbPortal: CMS mod for vBulletin                                      */
/* vBulletin is Copyright ©2000, 2001, Jelsoft Enterprises Limited.     */
/* ===========================                                          */
/* vbPortal by wajones                                                  */
/* Copyright (c) 2001 by William A. Jones                               */
/* http://www.phpportals.com                                            */
/* ===========================                                          */
/* A modified version of kevin (tubedogg's) polls                       */
/* anywhere hack, used by permission ;-)                                */
/************************************************************************/
// A modified version of tubedogg's polls anywhere hack, used by permission ;-)
if(!isset($mainfile)) { include("mainfile.php"); }
global  $nukurl,$bburl,$Pmenu,$breadcrumb;
$Pmenu="";
$breadcrumb="More Polls";
function listpolls() {
    global $sitename, $prefix,$index,$bburl,$nukurl;
    $index=1;
    include ('header.php');
    OpenTable();

if (isset($more)) {
	$forum = mysql_query("SELECT title FROM forum WHERE forumid='$forumid'");
	$forumtitle = mysql_result($forum,0,0);
	$poll = mysql_query("SELECT threadid,question,forumid FROM poll,thread WHERE thread.pollid>0 AND thread.forumid='$forumid' AND thread.pollid=poll.pollid ORDER BY thread.dateline DESC");
	echo("Forum: <a href=\"$bburl/forumdisplay.php?forumid=$forumid\">$forumtitle</a><br><ul>");
	while ($thread = mysql_fetch_array($poll)) {
		echo("<li><a href=\"$bburl/showthread.php?threadid=$thread[threadid]\">$thread[question]</a>");
	}
	echo("</ul><br><a href=\"$PHP_SELF\">go back</a>");
} else {
	$listforums=explode(",",$listforums);
	foreach($listforums as $forumid) {
		$poll = mysql_query("SELECT threadid,question,forumid FROM poll,thread WHERE thread.pollid>0 AND thread.forumid='$forumid' AND thread.pollid=poll.pollid ORDER BY thread.dateline DESC");
		if (mysql_num_rows($poll) != "0") {
			$forum = mysql_query("SELECT title FROM forum WHERE forumid='$forumid'");
			$forumtitle = mysql_result($forum,0,0);
			echo("Forum: <a href=\"$bburl/forumdisplay.php?forumid=$forumid\">$forumtitle</a><br><ul>");
			if (mysql_num_rows($poll) <= $listmaxresults) {
				while ($thread = mysql_fetch_array($poll)) {
					echo("<li><a href=\"$bburl/showthread.php?threadid=$thread[threadid]\">$thread[question]</a>");
				}
				echo("</ul>");
			} else {
				$i=1;
				while (($thread = mysql_fetch_array($poll)) && ($i <= $listmaxresults)) {
					++$i;
					echo("<li><a href=\"$bburl/showthread.php?threadid=$thread[threadid]\">$thread[question]</a>");
				}
				echo("<li><b><a href=\"$PHP_SELF?more=1&forumid=$forumid\">more polls in $forumtitle</a></b>");
			}
		}
	}
}
    CloseTable();
    include ('footer.php');
}
    listpolls();
?>