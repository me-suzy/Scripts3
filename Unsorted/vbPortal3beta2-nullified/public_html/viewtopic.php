<?php
/************************************************************************/
/* vbPortal: CMS mod for vBulletin                                      */
/* vBulletin is Copyright ©2000, 2001, Jelsoft Enterprises Limited.     */
/* ===========================                                          */
/* vbPortal by wajones                                                  */
/* Copyright (c) 2001 by William A. Jones                               */
/* http://www.phpportals.com                                            */
/* ===========================                                          */
/* Based on PHP-NUKE: Web Portal System                                 */
/* Copyright (c) 2001 by Francisco Burzi (fbc@mandrakesoft.com)         */
/* http://phpnuke.org                                                   */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/
/************************************************************************/
/* PHP-NUKE: Web Portal System                                          */
/* ===========================                                          */
/*                                                                      */
/* Copyright (c) 2001 by Francisco Burzi (fburzi@ncc.org.ve)            */
/* http://phpnuke.org                                                   */
/*                                                                      */
/* =========================                                            */
/* Part of phpBB integration                                            */
/* Copyright (c) 2001 by                                                */
/*    Richard Tirtadji AKA King Richard (rtirtadji@hotmail.com)         */
/*    Hutdik Hermawan AKA hotFix (hutdik76@hotmail.com)                 */
/* http://www.phpnuke.web.id                                            */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/

include('config.php');
include('functions.php');
include('mainfile.php');
include('auth.php');

$sql = "SELECT forum_name, forum_moderator FROM forums WHERE forum_id = '$forum'";
if(!$result = mysql_query($sql, $db))
	forumerror(0001);
$myrow = mysql_fetch_array($result);
$forum_name = $myrow[forum_name];
$mod = $myrow[forum_moderator];
$sql = "SELECT topic_title, topic_status FROM forumtopics WHERE topic_id = '$topic'";

$total = get_total_posts($topic, $db, "topic");
if($total > $posts_per_page) {
	$times = 0;
	for($x = 0; $x < $total; $x += $posts_per_page)
		$times++;
	$pages = $times;
}



if(!$result = mysql_query($sql, $db))
    forumerror(0001);
$myrow = mysql_fetch_array($result);
$topic_subject = stripslashes($myrow[topic_title]);
$lock_state = $myrow[topic_status];

include('header.php');
?>
<TABLE BORDER="0" WIDTH="100%"><TR><TD ALIGN=LEFT>
<FONT SIZE=1><b><?php echo translate("Moderated By: "); $moderator = get_moderator($mod, $db); ?><a href="user.php?op=userinfo&uname=<?php echo $moderator?>"><?php echo $moderator?></a></b><br>
<a href="forum.php"><?php echo "$sitename ".translate("Forum Index");?></a> <b>» »</b>
<a href="viewforum.php?forum=<?php echo $forum?>"><?php echo stripslashes($forum_name)?></a> <b>» »</b> <?php echo $topic_subject;?></FONT>
</TD><TD>
<a href="newtopic.php?forum=<?php echo $forum?>&mod=<?php echo $mod?>"><IMG SRC="images/forum/icons/new_topic-dark.jpg" BORDER="0"></a>&nbsp;&nbsp;
<?php
		if($lock_state != 1) {
?>
			<a href="reply.php?topic=<?php echo $topic ?>&forum=<?php echo $forum ?>&mod=<?php echo $mod ?>"><IMG SRC="images/forum/icons/reply-dark.jpg" BORDER="0"></a></TD>
<?php
		}
		else {
?>
			<IMG SRC="images/forum/icons/reply_locked-dark.jpg" BORDER="0"></TD>
<?php
		}
echo "</TR><TABLE>";
if($total > $posts_per_page) {
	echo "<TABLE BORDER=0 WIDTH=100% ALIGN=CENTER>";
	$times = 1;
	echo "<TR ALIGN=\"RIGHT\"><TD><font size=1><b>$pages</b> ".translate("pages")." ( ";
	for($x = 0; $x < $total; $x += $posts_per_page) {
		if($times != 1)
			echo " | ";
		echo "<a href=\"viewtopic.php?topic=$topic&forum=$forum&start=$x\">$times</a>";
		$times++;
	}
	echo " ) </TD></TR></TABLE>\n";
}
OpenTable();
?>

<center><a href="forum.php"><?php echo "$sitename ".translate("Forum Index");?></a></center>

<?php CloseTable(); ?>

<TABLE BORDER="0" CELLPADDING="1" CELLPADDING="0" ALIGN="CENTER" VALIGN="TOP" WIDTH="100%"><TR><TD>
<TABLE BORDER="0" CELLPADDING="3" CELLPADDING="1" WIDTH="100%">
<TR BGCOLOR="<?php echo $bgcolor2?>" ALIGN="LEFT">
	<TD WIDTH=20%><B><?php echo translate("Author");?></B></TD>
	<TD><B><?php echo $topic_subject?></B></TD>
</TR>
<?php
if(isset($start)) {
	$sql = "SELECT * FROM posts WHERE topic_id = '$topic' ORDER BY post_id LIMIT $start, $posts_per_page";
}
else {
	$sql = "SELECT * FROM posts WHERE topic_id = '$topic' ORDER BY post_id LIMIT $posts_per_page";
}
if(!$result = mysql_query($sql, $db))
    forumerror(0001);
$myrow = mysql_fetch_array($result);
$row_color = $color2;
$count = 0;

if ($user) {
	$user = base64_decode($user);
	$userdata = explode(":", $user);
}

if($userdata[0] == $mod) {
$viewip = 1;
}

do {
   if(!($count % 2))
     $row_color = $bgcolor3;
   else 
     $row_color = $bgcolor1;
   
   echo "<TR BGCOLOR=\"$row_color\" ALIGN=\"LEFT\">\n";
   if($myrow[poster_id] != 1) {
	   $posterdata = get_userdata_from_id($myrow[poster_id], $db);
	}
   else 
     $posterdata = array("uid" => 1, "uname" => "Anonymous", "posts" => "0", "rank" => -1);
   echo "<TD width=18% valign=top><b>$posterdata[uname]</b><br>\n";
   $posts = $posterdata[posts];
   if($posterdata[uid] != 1) {
	   if($posterdata[rank] != 0) 
	     $sql = "SELECT rank_title FROM ranks WHERE rank_id = '$posterdata[rank]'";
	   else
	     $sql = "SELECT rank_title FROM ranks WHERE rank_min <= " . $posterdata[posts] . " AND rank_max >= " . $posterdata[posts] . " AND rank_special = 0";
	   if(!$rank_result = mysql_query($sql, $db))
	    forumerror(0001);
	   list($rank) = mysql_fetch_array($rank_result);
	   echo "<font size=1>" . stripslashes($rank) . "</font>";
	   echo "<BR><font size=1>".translate("Joined: ")."$posterdata[user_regdate]</font>\n";
	   echo "<br><font size=1>".translate("Posts: ")."$posts<br>\n";
	   echo "".translate("From: ")."$posterdata[user_from]<br></FONT>\n";
	}
   else {
	   echo "<font size=1>".translate("Unregistered User")."</font><br>";
	}
   if ($posterdata[user_avatar] != '')
   echo "<img src=\"images/forum/avatar/$posterdata[user_avatar]\">";
   echo "</td><td>";
   if ($myrow[image] != "") echo "<img src=\"images/forum/subject/$myrow[image]\"><font size=1>";
   else echo "<img src=\"images/forum/icons/posticon.gif\"><font size=1>";
   echo "&nbsp;&nbsp;".translate("Posted: ")."$myrow[post_time]";
   echo "</font><br><br>\n";
   $message = stripslashes($myrow[post_text]);
   $message = eregi_replace("\[addsig]", "<BR><BR>-----------------<BR>" . bbencode(nl2br($posterdata[user_sig])), $message);
   echo "$message<BR><BR>";
   echo "<HR noshade size=1>\n";
   if($posterdata[uid] != 1)
   echo "&nbsp<a href=\"user.php?op=userinfo&uname=$posterdata[uname]\"><img src=\"images/forum/icons/profile.gif\" border=0></a><FONT SIZE=1>".translate("Profile")."</FONT>\n";
   if($posterdata["femail"] != '') 
     echo "&nbsp;<a href=\"mailto:$posterdata[femail]\"><IMG SRC=\"images/forum/icons/email.gif\" BORDER=0></a><FONT SIZE=1>".translate("Email")."</FONT>\n";
   if($posterdata["url"] != '') {
      if(strstr("http://", $posterdata["url"]))
	$posterdata["url"] = "http://" . $posterdata["url"];
      echo "&nbsp;<a href=\"$posterdata[url]\" TARGET=\"_blank\"><IMG SRC=\"images/forum/icons/www_icon.gif\" BORDER=0></a><FONT SIZE=1>www</FONT>\n";
   }
   if($posterdata["user_icq"] != '')
     echo "&nbsp;<a href=\"http://wwp.icq.com/$posterdata[user_icq]#pager\" target=\"_blank\"><img src=\"http://online.mirabilis.com/scripts/online.dll?icq=$posterdata[user_icq]&img=5\" border=\"0\"></a>&nbsp;<a href=\"http://wwp.icq.com/scripts/search.dll?to=$posterdata[user_icq]\"><img src=\"images/forum/icons/icq_add.gif\" border=\"0\"></a><FONT SIZE=1>".translate("Add")."</FONT>";
   
   if($posterdata["user_aim"] != '')
     echo "&nbsp;<a href=\"aim:goim?screenname=$posterdata[user_aim]&message=Hi+$posterdata[user_aim].+Are+you+there?\"><img src=\"images/forum/icons/aim.gif\" border=\"0\"></a><FONT SIZE=1>aim</FONT>";
   
   if($posterdata["user_yim"] != '')
     echo "&nbsp;<a href=\"http://edit.yahoo.com/config/send_webmesg?.target=$posterdata[user_yim]&.src=pg\"><img src=\"images/forum/icons/yim.gif\" border=\"0\"></a>";
   
   if($posterdata["user_msnm"] != '')
     echo "&nbsp;<a href=\"user.php?op=userinfo&uname=$posterdata[uname]\"><img src=\"images/forum/icons/msnm.gif\" border=\"0\"></a>";
   
//   echo "&nbsp;<IMG SRC=\"images/forum/icons/div.gif\">\n";
   if($posterdata[uid]==$userdata[0] || $mod==$userdata[0])
     echo "&nbsp;<a href=\"editpost.php?post_id=$myrow[post_id]&topic=$topic&forum=$forum\"><img src=\"images/forum/icons/edit.gif\" border=0></a><FONT SIZE=1>".translate("Edit")."</FONT>\n";
     echo "&nbsp;<a href=\"reply.php?topic=$topic&forum=$forum&post=$myrow[post_id]&quote=1&mod=$mod\"><IMG SRC=\"images/forum/icons/quote.gif\" BORDER=\"0\"></a><FONT SIZE=1>".translate("Quote")."</FONT>\n";
   if($viewip == 1) {
//      echo "&nbsp;<IMG SRC=\"images/forum/icons/div.gif\">\n";
      echo "&nbsp;<a href=\"topicadmin.php?mode=viewip&post=$myrow[post_id]&forum=$forum\"><IMG SRC=\"images/forum/icons/ip_logged.gif\" BORDER=0></a><FONT SIZE=1>ip</FONT>\n";
   }
   echo "</TD></TR>";
   $count++;
} while($myrow = mysql_fetch_array($result));
$sql = "UPDATE forumtopics SET topic_views = topic_views + 1 WHERE topic_id = '$topic'";
@mysql_query($sql, $db);
?>

</TABLE></TD></TR></TABLE>
<?php OpenTable(); ?>
<TABLE ALIGN="CENTER" BORDER="0" WIDTH="95%">
<?php
if($total > $posts_per_page) {
	$times = 1;
	echo "<TR ALIGN=\"RIGHT\"><TD COLSPAN=2><font size=1>".translate("Goto Page: ")."";
	for($x = 0; $x < $total; $x += $posts_per_page) {
		if($times != 1)
			echo " | ";
		echo "  <a href=\"viewtopic.php?topic=$topic&forum=$forum&start=$x\">$times</a>";
		$times++;
	}
	echo "</TD></TR>\n";
}
?>
<TR>
	<TD>
		<a href="newtopic.php?forum=<?php echo $forum?>&mod=<?php echo $mod?>"><IMG SRC="images/forum/icons/new_topic-dark.jpg" BORDER="0"></a>&nbsp;&nbsp;
<?php
		if($lock_state != 1) {
?>
			<a href="reply.php?topic=<?php echo $topic ?>&forum=<?php echo $forum ?>&mod=<?php echo $mod ?>"><IMG SRC="images/forum/icons/reply-dark.jpg" BORDER="0"></a></TD>
<?php
		}
		else {
?>
			<IMG SRC="images/forum/icons/reply_locked-dark.jpg" BORDER="0"></TD>
<?php
		}
?>
	</TD>
<TD ALIGN="RIGHT">
<FORM ACTION="viewforum.php" METHOD="GET">
<font size="2"><?php echo translate("Jump To: ");?></font> <SELECT NAME="forum"><OPTION VALUE="-1"><?php echo translate("Select a Forum");?></OPTION>
<?php
        $sql = "SELECT cat_id, cat_title FROM catagories ORDER BY cat_id";
        if($result = mysql_query($sql, $db)) {
                $myrow = mysql_fetch_array($result);   
                do {
                //        echo "<OPTION VALUE=\"-1\">&nbsp;</OPTION>\n";
                        echo "<OPTION VALUE=\"-1\">$myrow[cat_title]</OPTION>\n";
                        echo "<OPTION VALUE=\"-1\">----------------</OPTION>\n";
                        $sub_sql = "SELECT forum_id, forum_name FROM forums WHERE cat_id = '$myrow[cat_id]' ORDER BY forum_id";
                        if($res = mysql_query($sub_sql, $db)) {
                                if($row = mysql_fetch_array($res)) {   
                                        do {
                                                $name = stripslashes($row[forum_name]);
                                                echo "<OPTION VALUE=\"$row[forum_id]\">$name</OPTION>\n";
                                        } while($row = mysql_fetch_array($res));
                                }
                                else {
                                        echo "<OPTION VALUE=\"0\">".translate("No More Forums")."</OPTION>\n";
                                }
                        }
                        else {
                                echo "<OPTION VALUE=\"0\">Error Connecting to DB</OPTION>\n";
                        }
                } while($myrow = mysql_fetch_array($result));
        }
        else {
                echo "<OPTION VALUE=\"-1\">ERROR</OPTION>\n";
        }
?>
</SELECT>
<INPUT TYPE="SUBMIT" VALUE="Go">
</FORM>
</td></TR></TABLE>
<?php CloseTable(); ?>
<?php

if($userdata[0] == $mod) {
OpenTable();
echo "<CENTER>";
echo "<font size=2><b>".translate("Administration Tools")."</b></font><br>";
echo "-------------------------<br>";
if($lock_state != 1)
	echo "<a href=\"topicadmin.php?mode=lock&topic=$topic&forum=$forum\"><IMG SRC=\"images/forum/icons/lock_topic.gif\" ALT=\"".translate("Lock this Topic")."\" BORDER=0></a> ";
else
	echo "<a href=\"topicadmin.php?mode=unlock&topic=$topic&forum=$forum\"><IMG SRC=\"images/forum/icons/unlock_topic.gif\" ALT=\"".translate("Unlock this Topic")."\" BORDER=0></a> ";

echo "<a href=\"topicadmin.php?mode=move&topic=$topic&forum=$forum\"><IMG SRC=\"images/forum/icons/move_topic.gif\" ALT=\"".translate("Move this Topic")."\" BORDER=0></a> ";
echo "<a href=\"topicadmin.php?mode=del&topic=$topic&forum=$forum\"><IMG SRC=\"images/forum/icons/del_topic.gif\" ALT=\"".translate("Delete this Topic")."\" BORDER=0></a></CENTER>\n";
CloseTable();
}

include("footer.php");
/********************************************
CVS Log
$Log: viewtopic.php,v $
Revision 1.24  2000/10/22 07:42:23  thefinn
Dynamic signature hack

Revision 1.23  2000/10/22 03:10:08  thefinn
Dynamic Signatures

Revision 1.22  2000/10/14 04:02:57  thefinn
Small fixes, mostly cosmetic

Revision 1.21  2000/10/13 18:06:44  thefinn
Anonymous posting

Revision 1.20  2000/10/03 20:33:06  thefinn
User registration date shows up correctly on posts now

Revision 1.19  2000/09/18 20:40:25  thefinn
Got the ranking code in, needs input validity checking but other wise its done.

Revision 1.18  2000/09/14 18:27:57  thefinn
Added CVS Log tags to frequently changed files


*********************************************/
?>
