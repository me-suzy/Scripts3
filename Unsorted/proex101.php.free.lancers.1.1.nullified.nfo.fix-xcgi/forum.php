<?php
/*
                                                   PHP Freelancers version 1.1
                                                   -----------------------
                                                   A script by ProEx101 Web Services
                                                   (http://www.ProEx101.com/)

    "PHP Freelancers" is not a free script. If you got this from someplace
    other than SmartCGIs.com or ProEx101 Web Services, please contact us,
    we do offer rewards for that type of information. Visit our site for up
    to date versions. Most PHP scripts are over $300, sometimes more than
    $700, this script is much less. We can keep this script cheap, as well
    as free scripts on our site, if people don't steal it.
          Also, no return links are required, but we appreciate it if you
          do find a spot for us.
          Thanks!

          Special Notice to Resellers
          ===========================
          Reselling this script without prior permission
          from ProEx101 Web Services is illegal and
          violators will be prosecuted to the fullest
          extent of the law.  To apply to be a legal
          reseller, please visit:
          http://www.ProEx101.com/freelancers/resell.php

       (c) copyright 2001 ProEx101 Web Services, SmartCGIs.com, and R3N3 Internet Services */

require "vars.php";
require "cron.php";

$adminforum = $HTTP_COOKIE_VARS["adminforum"];
if ($viewproj && $viewproj !== "") {
$result = SQLact("query", "SELECT * FROM freelancers_projects WHERE id='$viewproj'");
$subject2 = SQLact("query", "SELECT * FROM freelancers_subjects WHERE pid='$viewproj'");
if (SQLact("num_rows", $result)==0 && SQLact("num_rows", $subject2)!==0) {
header("Location: $siteurl/forum.php?viewsub=$viewproj");
} else if (SQLact("num_rows", $result)==0 && SQLact("num_rows", $subject2)==0) {
echo $toplayout;
echo 'Couldn\'t Find Project: No project was found with that ID number, <a href="' . $siteurl . '/buyers.php?new=project">click here</a> to create a new project now.<br>
<a href="javascript:history.go(-1);">Go Back...</a>';
} else {
echo $toplayout;
$subject = SQLact("query", "SELECT * FROM freelancers_forum WHERE pid='$viewproj' ORDER BY date2 ASC");
echo '<big>Subject: <b>
<a href="' . $siteurl . '/project.php?id=' . $viewproj . '">' . SQLact("result", $result,0,"project") . '</a></b></big>
<p>
<a href="' . $siteurl . '/forum.php?reply=' . $viewproj . '"><img src="' . $siteurl . '/postreply.gif" border=0 alt="Post a Reply"></a>
<p>
<table width="100%" border="' . $tableborder . '" cellspacing="' . $tablecellsp . '" cellpadding="' . $tablecellpa . '">
<tr>
<td bgcolor="' . $tablecolorh . '"><b><font color=' . $tablecolort . '>Author</td>
<td bgcolor="' . $tablecolorh . '"><b><font color=' . $tablecolort . '>Message</td>
</tr>';
if (SQLact("num_rows", $subject)==0) {
echo '<tr><td bgcolor=' . $tablecolor1 . ' colspan=2><center>(No messages have been posted. <a href="' . $siteurl . '/forum.php?reply=' . $viewproj . '">Click here</a> to post the first one.)<td></tr>';
} else {
$i=0;
while ($row=SQLact("fetch_array", $subject)) {
$i++;
if ($i%2) {
$ctu = '' . $tablecolor1 . '';
} else {
$ctu = '' . $tablecolor2 . '';
}
echo '<tr>
<td width=160 bgcolor="' . $ctu . '" valign="top">
Username:
' . $row[froom];
if ($row[acctype] == "freelancer") {
$guarenteed = SQLact("query", "SELECT * FROM freelancers_programmers WHERE username='" . $row[froom] . "'");
$acctype = $freelancer;
} else {
$guarenteed = SQLact("query", "SELECT * FROM freelancers_webmasters WHERE username='" . $row[froom] . "'");
$acctype = $buyer;
}
if (SQLact("result", $guarenteed,0,"special") == "user") {
echo ' <a href="' . $siteurl . '/aboutspecial.html"><img src="' . $siteurl . '/special.gif" alt="Special User" border=0></a>';
}
echo '<p><small>Account type: ' . $acctype . '<br>
Date posted: ' . $row[date];
if ($adminforum == $adminpass) {
echo '<br>
<a href="' . $siteurl . '/forum.php?delmessage=' . $row[mid] . '">Delete</a>';
}
echo '</td>
<td bgcolor="' . $ctu . '" valign="top">
';
if ($row[private] == "") {
$msg = str_replace("
","<br>
",$row[message]);
echo $msg;
} else {
echo '<i>Private message for ' . $row[private] . '. <a href="' . $siteurl . '/forum.php?login=' . $row[private] . '&type=viewproj&fid=' . $row[pid] . '">click to view...</a></i><br><br>';
$username = $HTTP_COOKIE_VARS["fusername"];
$tttype = "freelancer";
if ($username == "") {
$username = $HTTP_COOKIE_VARS["busername"];
$tttype = "buyer";
}
if ($row[froom] == $username && $row[acctype] == $tttype || $row[private] == $username && $username !== "" && $row[privatetype] == $tttype || $adminforum == $adminpass) {
$msg = str_replace("
","<br>
",$row[message]);
echo $msg;
}
}
echo '</td>
</tr>';
}
}
echo '</table>
<p>
<a href="' . $siteurl . '/forum.php?reply=' . $viewproj . '"><img src="' . $siteurl . '/postreply.gif" border=0 alt="Post a Reply"></a>
<p>
<a href="' . $siteurl . '/forum.php">Main Message Board</a>';
}
} else if ($viewsub && $viewsub !== "") {
$projtran = SQLact("query", "SELECT * FROM freelancers_projects WHERE id='$viewsub'");
if (SQLact("num_rows", $projtran)!==0) {
header("Location: $siteurl/forum.php?viewproj=$viewsub");
}
echo $toplayout;
$result = SQLact("query", "SELECT * FROM freelancers_subjects WHERE pid='$viewsub'");
if (SQLact("num_rows", $result)==0) {
echo 'Couldn\'t Find Forum: No subject was found with that ID number, <a href="' . $siteurl . '/forum.php?new=subject">click here</a> to create a new subject now.<br>
<a href="javascript:history.go(-1);">Go Back...</a>';
} else {
$subject = SQLact("query", "SELECT * FROM freelancers_forum WHERE pid='$viewsub' ORDER BY date2 ASC");
$subject2 = SQLact("query", "SELECT * FROM freelancers_subjects WHERE pid='$viewsub'");
echo '<big>Subject: <b>
' . SQLact("result", $subject2,0,"subject") . '</b></big>
<p>
<a href="' . $siteurl . '/forum.php?reply=' . $viewsub . '"><img src="' . $siteurl . '/postreply.gif" border=0 alt="Post a Reply"></a>
<p>
<table width="100%" border="' . $tableborder . '" cellspacing="' . $tablecellsp . '" cellpadding="' . $tablecellpa . '">
<tr>
<td bgcolor="' . $tablecolorh . '"><b><font color=' . $tablecolort . '>Author</td>
<td bgcolor="' . $tablecolorh . '"><b><font color=' . $tablecolort . '>Message</td>
</tr>';
if (SQLact("num_rows", $subject)==0) {
echo '<tr><td bgcolor=' . $tablecolor1 . ' colspan=2><center>(No messages have been posted. <a href="' . $siteurl . '/forum.php?reply=' . $viewsub . '">Click here</a> to post the first one.)<td></tr>';
} else {
$i=0;
while ($row=SQLact("fetch_array", $subject)) {
$i++;
if ($i%2) {
$ctu = '' . $tablecolor1 . '';
} else {
$ctu = '' . $tablecolor2 . '';
}
echo '<tr>
<td width=160 bgcolor="' . $ctu . '" valign="top">
Username:
' . $row[froom];
if ($row[acctype] == "freelancer") {
$guarenteed = SQLact("query", "SELECT * FROM freelancers_programmers WHERE username='" . $row[froom] . "'");
$acctype = $freelancer;
} else {
$guarenteed = SQLact("query", "SELECT * FROM freelancers_webmasters WHERE username='" . $row[froom] . "'");
$acctype = $buyer;
}
if (SQLact("result", $guarenteed,0,"special") == "user") {
echo ' <a href="' . $siteurl . '/aboutspecial.html"><img src="' . $siteurl . '/special.gif" alt="Special User" border=0></a>';
}
echo '<p><small>Account type: ' . $acctype . '<br>
Date posted: ' . $row[date];
if ($adminforum == $adminpass) {
echo '<br>
<a href="' . $siteurl . '/forum.php?delmessage=' . $row[mid] . '">Delete</a>';
}
echo '
</td>
<td bgcolor="' . $ctu . '" valign="top">
';
if ($row[private] == "") {
$msg = str_replace("
","<br>
",$row[message]);
echo $msg;
} else {
echo '<i>Private message for ' . $row[private] . '. <a href="' . $siteurl . '/forum.php?login=' . $row[private] . '&type=viewsub&fid=' . $row[pid] . '">click to view...</a></i><br><br>';
$username = $HTTP_COOKIE_VARS["fusername"];
$tttype = "freelancer";
if ($username == "") {
$username = $HTTP_COOKIE_VARS["busername"];
$tttype = "buyer";
}
if ($row[private] == $username && $username !== "" && $row[privatetype] == $tttype || $adminforum == $adminpass) {
$msg = str_replace("
","<br>
",$row[message]);
echo $msg;
}
}
echo '</td>
</tr>';
}
}
echo '</table>
<p>
<a href="' . $siteurl . '/forum.php?reply=' . $viewsub . '"><img src="' . $siteurl . '/postreply.gif" border=0 alt="Post a Reply"></a>
<p>
<a href="' . $siteurl . '/forum.php">Main Message Board</a>';
}
} else if ($new == "subject") {
if (!$submit) {
echo $toplayout;
$username = $HTTP_COOKIE_VARS["fusername"];
$password = $HTTP_COOKIE_VARS["fpassword"];
$atype1 = 'SELECTED';
if ($password == "") {
$username = $HTTP_COOKIE_VARS["busername"];
$password = $HTTP_COOKIE_VARS["bpassword"];
$atype1 = '';
$atype2 = 'SELECTED';
}
echo '<big><b>Create New Forum Subject</b></big>
<p>

<form method="POST" action="forum.php">
<input type="hidden" name="new" value="subject">

<strong>Username:</strong><br>
<input type="text" name="username" value="' . $username . '" size="15">
<br>
<strong>Password:</strong><br>
<input type="password" name="password" value="' . $password . '" size="15">
<br>
(Your Account Type):<br>
<select name="atype">
<option ' . $atype1 . ' value="freelancer"> ' . $freelancer . '</option>
<option ' . $atype2 . ' value="buyer"> ' . $buyer . '</option>
</select>
<p>
<strong>Subject Name:</strong><br>
<input type="text" name="subject" maxlength="50" size="25">
<p>
<strong>Message:</strong>
<br>
<textarea rows="8" name="message" cols="52"></textarea>
<p>

<strong>Private Post (optional):</strong><br>
<small>If you want the message you\'re posting to only be viewed by a specific person, enter their username below. They will be notified of your post. If you leave this blank everyone will be able to view your message.</small>
<br>
<input type="text" name="private" maxlength="50" size="15">
<br>
(Their Account Type):<br>
<select name="ptype">
<option value="buyer"> ' . $buyer . '</option>
<option value="freelancer"> ' . $freelancer . '</option>
</select>

<p>
<input type="submit" value="Submit" name="submit">
</form>';
} else {
if ($subject == "" || $message == "") {
echo $toplayout;
echo 'You left a field blank.<br>
<a href="javascript:history.go(-1);">Go Back...</a>';
} else {
if ($atype == "buyer") {
$bozzyres = SQLact("query", "SELECT * FROM freelancers_webmasters WHERE username='$username'");
$bozzyrows = SQLact("num_rows", $bozzyres);
if ($bozzyrows==0) {
echo $toplayout;
echo 'No ' . $buyer . ' account was found with the username <b>' . $username . '</b>. <br><A HREF="javascript:history.back()">Go Back</A>';
} else {
$byzzyres = SQLact("query", "SELECT * FROM freelancers_webmasters WHERE username='$username' AND password='$password'");
$byzzyrows = SQLact("num_rows", $byzzyres);
if ($byzzyrows==0) {
echo $toplayout;
echo 'The password you provided is incorrect. <br><A HREF="javascript:history.back()">Go Back</A>';
} else {
setcookie ("busername", $username,time()+999999);
setcookie ("bpassword", $password,time()+999999);
if ($private !== "" && $ptype == "buyer") {
$bozzyres = SQLact("query", "SELECT * FROM freelancers_webmasters WHERE username='$private'");
$bozzyrows = SQLact("num_rows", $bozzyres);
if ($bozzyrows==0) {
echo $toplayout;
echo 'No ' . $buyer . ' account was found with the username <b>' . $private . '</b>. <br><A HREF="javascript:history.back()">Go Back</A>';
} else {
$ttoy = time();
$tttoy = date("n/j/Y @ G:i");
SQLact("query", "INSERT INTO freelancers_subjects (pid, subject, date, date2) VALUES ('$ttoy', '$subject', '$tttoy', '$ttoy')");
SQLact("query", "INSERT INTO freelancers_forum (pid, froom, acctype, date, date2, private, privatetype, message, mid) VALUES ('$ttoy', '$username', '$atype', '$tttoy', '$ttoy', '$private', '$ptype', '$message', '$ttoy')");
$mymsg = $username . ' has just posted the following private message for you on the message boards. You can reply to the message at ' . $siteurl . '/forum.php?viewsub=' . $ttoy . '
-----------------------------------------
' . $message;
mail(SQLact("result", $bozzyres,0,"email"),$companyname . " Message Board Post Notice",$mymsg,"From: $username <$emailaddress>");
echo $toplayout;
echo '<META HTTP-EQUIV=REFRESH CONTENT="2; URL=' . $siteurl . '/forum.php?viewsub=' . $ttoy . '">
Your message has been posted into your new forum subject. <a href="' . $siteurl . '/forum.php?viewsub=' . $ttoy . '">Click here</a> to go there now.';
}
} else if ($private !== "" && $ptype == "freelancer") {
$bozzyres = SQLact("query", "SELECT * FROM freelancers_programmers WHERE username='$private'");
$bozzyrows = SQLact("num_rows", $bozzyres);
if ($bozzyrows==0) {
echo $toplayout;
echo 'No ' . $freelancer . ' account was found with the username <b>' . $private . '</b>. <br><A HREF="javascript:history.back()">Go Back</A>';
} else {
$ttoy = time();
$tttoy = date("n/j/Y @ G:i");
SQLact("query", "INSERT INTO freelancers_subjects (pid, subject, date, date2) VALUES ('$ttoy', '$subject', '$tttoy', '$ttoy')");
SQLact("query", "INSERT INTO freelancers_forum (pid, froom, acctype, date, date2, private, privatetype, message, mid) VALUES ('$ttoy', '$username', '$atype', '$tttoy', '$ttoy', '$private', '$ptype', '$message', '$ttoy')");
$mymsg = $username . ' has just posted the following private message for you on the message boards. You can reply to the message at ' . $siteurl . '/forum.php?viewsub=' . $ttoy . '
-----------------------------------------
' . $message;
mail(SQLact("result", $bozzyres,0,"email"),$companyname . " Message Board Post Notice",$mymsg,"From: $username <$emailaddress>");
echo $toplayout;
echo '<META HTTP-EQUIV=REFRESH CONTENT="2; URL=' . $siteurl . '/forum.php?viewsub=' . $ttoy . '">
Your message has been posted into your new forum subject. <a href="' . $siteurl . '/forum.php?viewsub=' . $ttoy . '">Click here</a> to go there now.';
}
} else {
$ttoy = time();
$tttoy = date("n/j/Y @ G:i");
SQLact("query", "INSERT INTO freelancers_subjects (pid, subject, date, date2) VALUES ('$ttoy', '$subject', '$tttoy', '$ttoy')");
SQLact("query", "INSERT INTO freelancers_forum (pid, froom, acctype, date, date2, private, privatetype, message, mid) VALUES ('$ttoy', '$username', '$atype', '$tttoy', '$ttoy', '$private', '$ptype', '$message', '$ttoy')");
echo $toplayout;
echo '<META HTTP-EQUIV=REFRESH CONTENT="2; URL=' . $siteurl . '/forum.php?viewsub=' . $ttoy . '">
Your message has been posted into your new forum subject. <a href="' . $siteurl . '/forum.php?viewsub=' . $ttoy . '">Click here</a> to go there now.';
}
}
}
} else {
$bozzyres = SQLact("query", "SELECT * FROM freelancers_programmers WHERE username='$username'");
$bozzyrows = SQLact("num_rows", $bozzyres);
if ($bozzyrows==0) {
echo $toplayout;
echo 'No ' . $freelancer . ' account was found with the username <b>' . $username . '</b>. <br><A HREF="javascript:history.back()">Go Back</A>';
} else {
$byzzyres = SQLact("query", "SELECT * FROM freelancers_programmers WHERE username='$username' AND password='$password'");
$byzzyrows = SQLact("num_rows", $byzzyres);
if ($byzzyrows==0) {
echo $toplayout;
echo 'The password you provided is incorrect. <br><A HREF="javascript:history.back()">Go Back</A>';
} else {
setcookie ("fusername", $username,time()+999999);
setcookie ("fpassword", $password,time()+999999);
if ($private !== "" && $ptype == "buyer") {
$bozzyres = SQLact("query", "SELECT * FROM freelancers_webmasters WHERE username='$private'");
$bozzyrows = SQLact("num_rows", $bozzyres);
if ($bozzyrows==0) {
echo $toplayout;
echo 'No ' . $buyer . ' account was found with the username <b>' . $private . '</b>. <br><A HREF="javascript:history.back()">Go Back</A>';
} else {
$ttoy = time();
$tttoy = date("n/j/Y @ G:i");
SQLact("query", "INSERT INTO freelancers_subjects (pid, subject, date, date2) VALUES ('$ttoy', '$subject', '$tttoy', '$ttoy')");
SQLact("query", "INSERT INTO freelancers_forum (pid, froom, acctype, date, date2, private, privatetype, message, mid) VALUES ('$ttoy', '$username', '$atype', '$tttoy', '$ttoy', '$private', '$ptype', '$message', '$ttoy')");
$mymsg = $username . ' has just posted the following private message for you on the message boards. You can reply to the message at ' . $siteurl . '/forum.php?viewsub=' . $ttoy . '
-----------------------------------------
' . $message;
mail(SQLact("result", $bozzyres,0,"email"),$companyname . " Message Board Post Notice",$mymsg,"From: $username <$emailaddress>");
echo $toplayout;
echo '<META HTTP-EQUIV=REFRESH CONTENT="2; URL=' . $siteurl . '/forum.php?viewsub=' . $ttoy . '">
Your message has been posted into your new forum subject. <a href="' . $siteurl . '/forum.php?viewsub=' . $ttoy . '">Click here</a> to go there now.';
}
} else if ($private !== "" && $ptype == "freelancer") {
$bozzyres = SQLact("query", "SELECT * FROM freelancers_programmers WHERE username='$private'");
$bozzyrows = SQLact("num_rows", $bozzyres);
if ($bozzyrows==0) {
echo $toplayout;
echo 'No ' . $freelancer . ' account was found with the username <b>' . $private . '</b>. <br><A HREF="javascript:history.back()">Go Back</A>';
} else {
$ttoy = time();
$tttoy = date("n/j/Y @ G:i");
SQLact("query", "INSERT INTO freelancers_subjects (pid, subject, date, date2) VALUES ('$ttoy', '$subject', '$tttoy', '$ttoy')");
SQLact("query", "INSERT INTO freelancers_forum (pid, froom, acctype, date, date2, private, privatetype, message, mid) VALUES ('$ttoy', '$username', '$atype', '$tttoy', '$ttoy', '$private', '$ptype', '$message', '$ttoy')");
$mymsg = $username . ' has just posted the following private message for you on the message boards. You can reply to the message at ' . $siteurl . '/forum.php?viewsub=' . $ttoy . '
-----------------------------------------
' . $message;
mail(SQLact("result", $bozzyres,0,"email"),$companyname . " Message Board Post Notice",$mymsg,"From: $username <$emailaddress>");
echo $toplayout;
echo '<META HTTP-EQUIV=REFRESH CONTENT="2; URL=' . $siteurl . '/forum.php?viewsub=' . $ttoy . '">
Your message has been posted into your new forum subject. <a href="' . $siteurl . '/forum.php?viewsub=' . $ttoy . '">Click here</a> to go there now.';
}
} else {
$ttoy = time();
$tttoy = date("n/j/Y @ G:i");
SQLact("query", "INSERT INTO freelancers_subjects (pid, subject, date, date2) VALUES ('$ttoy', '$subject', '$tttoy', '$ttoy')");
SQLact("query", "INSERT INTO freelancers_forum (pid, froom, acctype, date, date2, private, privatetype, message, mid) VALUES ('$ttoy', '$username', '$atype', '$tttoy', '$ttoy', '$private', '$ptype', '$message', '$ttoy')");
echo $toplayout;
echo '<META HTTP-EQUIV=REFRESH CONTENT="2; URL=' . $siteurl . '/forum.php?viewsub=' . $ttoy . '">
Your message has been posted into your new forum subject. <a href="' . $siteurl . '/forum.php?viewsub=' . $ttoy . '">Click here</a> to go there now.';
}
}
}
}
}
}
} else if ($login && $login !== "") {
if (!$submit) {
echo $toplayout;
$username = $HTTP_COOKIE_VARS["fusername"];
$password = $HTTP_COOKIE_VARS["fpassword"];
$atype1 = 'SELECTED';
if ($password == "") {
$username = $HTTP_COOKIE_VARS["busername"];
$password = $HTTP_COOKIE_VARS["bpassword"];
$atype1 = '';
$atype2 = 'SELECTED';
}
echo '<big><b>Login & View Private Messages</b></big>
<p>

<form method="POST" action="forum.php">
<input type="hidden" name="login" value="' . $login . '">
<input type="hidden" name="type" value="' . $type . '">
<input type="hidden" name="fid" value="' . $fid . '">

<strong>Account Type:</strong><br>
<select name="atype">
<option ' . $atype1 . ' value="freelancer">' . $freelancer . '</option>
<option ' . $atype2 . ' value="buyer">' . $buyer . '</option>
</select>
<br>
<strong>Username:</strong><br>
<input type="text" name="username" value="' . $username . '" size="15">
<br>
<strong>Password:</strong><br>
<input type="password" name="password" value="' . $password . '" size="15">
<p>
<input type="submit" value="Login" name="submit">
</form>';
} else {
if ($atype == "buyer") {
$bozzyres = SQLact("query", "SELECT * FROM freelancers_webmasters WHERE username='$username'");
$bozzyrows = SQLact("num_rows", $bozzyres);
if ($bozzyrows==0) {
echo $toplayout;
echo 'No ' . $buyer . ' account was found with the username <b>' . $username . '</b>. <br><A HREF="javascript:history.back()">Go Back</A>';
} else {
$byzzyres = SQLact("query", "SELECT * FROM freelancers_webmasters WHERE username='$username' AND password='$password'");
$byzzyrows = SQLact("num_rows", $byzzyres);
if ($byzzyrows==0) {
echo $toplayout;
echo 'The password you provided is incorrect. <br><A HREF="javascript:history.back()">Go Back</A>';
} else {
setcookie ("busername", $username,time()+999999);
setcookie ("bpassword", $password,time()+999999);
header("Location: $siteurl/forum.php?$type=$fid");
}
}
} else {
$bozzyres = SQLact("query", "SELECT * FROM freelancers_programmers WHERE username='$username'");
$bozzyrows = SQLact("num_rows", $bozzyres);
if ($bozzyrows==0) {
echo $toplayout;
echo 'No ' . $freelancer . ' account was found with the username <b>' . $username . '</b>. <br><A HREF="javascript:history.back()">Go Back</A>';
} else {
$byzzyres = SQLact("query", "SELECT * FROM freelancers_programmers WHERE username='$username' AND password='$password'");
$byzzyrows = SQLact("num_rows", $byzzyres);
if ($byzzyrows==0) {
echo $toplayout;
echo 'The password you provided is incorrect. <br><A HREF="javascript:history.back()">Go Back</A>';
} else {
setcookie ("fusername", $username,time()+999999);
setcookie ("fpassword", $password,time()+999999);
header("Location: $siteurl/forum.php?$type=$fid");
}
}
}
}
} else if ($reply && $reply !== "") {
$result = SQLact("query", "SELECT * FROM freelancers_subjects WHERE pid='$reply'");
$ttre = SQLact("result", $result,0,"subject");
if (SQLact("num_rows", $result)==0) {
$result = SQLact("query", "SELECT * FROM freelancers_projects WHERE id='$reply'");
$ttre = '<a href="' . $siteurl . '/project.php?id=' . $reply . '">' . SQLact("result", $result,0,"project") . '</a>';
}
if (!$submit) {
echo $toplayout;
$username = $HTTP_COOKIE_VARS["fusername"];
$password = $HTTP_COOKIE_VARS["fpassword"];
$atype1 = 'SELECTED';
if ($password == "") {
$username = $HTTP_COOKIE_VARS["busername"];
$password = $HTTP_COOKIE_VARS["bpassword"];
$atype1 = '';
$atype2 = 'SELECTED';
}
echo '<big><b>Post A Reply</b></big>
<p>

<form method="POST" action="forum.php">
<input type="hidden" name="reply" value="' . $reply . '">

<strong>Username:</strong><br>
<input type="text" name="username" value="' . $username . '" size="15">
<br>
<strong>Password:</strong><br>
<input type="password" name="password" value="' . $password . '" size="15">
<br>
(Your Account Type):<br>
<select name="atype">
<option ' . $atype1 . ' value="freelancer"> ' . $freelancer . '</option>
<option ' . $atype2 . ' value="buyer"> ' . $buyer . '</option>
</select>
<p>
<strong>Subject Name: ' . $ttre . '</strong>
<p>
<strong>Message:</strong>
<br>
<textarea rows="8" name="message" cols="52"></textarea>
<p>

<strong>Private Post (optional):</strong><br>
<small>If you want the message you\'re posting to only be viewed by a specific person, enter their username below. They will be notified of your post. If you leave this blank everyone will be able to view your message.</small>
<br>
<input type="text" name="private" maxlength="50" size="15">
<br>
(Their Account Type):<br>
<select name="ptype">
<option value="buyer"> ' . $buyer . '</option>
<option value="freelancer"> ' . $freelancer . '</option>
</select>

<p>
<input type="submit" value="Submit" name="submit">
</form>';
} else {
if ($message == "") {
echo $toplayout;
echo 'You left a field blank.<br>
<a href="javascript:history.go(-1);">Go Back...</a>';
} else {
if ($atype == "buyer") {
$bozzyres = SQLact("query", "SELECT * FROM freelancers_webmasters WHERE username='$username'");
$bozzyrows = SQLact("num_rows", $bozzyres);
if ($bozzyrows==0) {
echo $toplayout;
echo 'No ' . $buyer . ' account was found with the username <b>' . $username . '</b>. <br><A HREF="javascript:history.back()">Go Back</A>';
} else {
$byzzyres = SQLact("query", "SELECT * FROM freelancers_webmasters WHERE username='$username' AND password='$password'");
$byzzyrows = SQLact("num_rows", $byzzyres);
if ($byzzyrows==0) {
echo $toplayout;
echo 'The password you provided is incorrect. <br><A HREF="javascript:history.back()">Go Back</A>';
} else {
setcookie ("busername", $username,time()+999999);
setcookie ("bpassword", $password,time()+999999);
if ($private !== "" && $ptype == "buyer") {
$bozzyres = SQLact("query", "SELECT * FROM freelancers_webmasters WHERE username='$private'");
$bozzyrows = SQLact("num_rows", $bozzyres);
if ($bozzyrows==0) {
echo $toplayout;
echo 'No ' . $buyer . ' account was found with the username <b>' . $private . '</b>. <br><A HREF="javascript:history.back()">Go Back</A>';
} else {
$ttoy = time();
$tttoy = date("n/j/Y @ G:i");
if (SQLact("num_rows", $result)==0) {
SQLact("query", "INSERT INTO freelancers_subjects (pid, subject, date, date2) VALUES ('$ttoy', '$subject', '$tttoy', '$ttoy')");
SQLact("query", "INSERT INTO freelancers_forum (pid, froom, acctype, date, date2, private, privatetype, message, mid) VALUES ('$ttoy', '$username', '$atype', '$tttoy', '$ttoy', '$private', '$ptype', '$message', '$ttoy')");
$mymsg = $username . ' has just posted the following private message for you on the message boards. You can reply to the message at ' . $siteurl . '/forum.php?viewsub=' . $ttoy . '
-----------------------------------------
' . $message;
mail(SQLact("result", $bozzyres,0,"email"),$companyname . " Message Board Post Notice",$mymsg,"From: $username <$emailaddress>");
echo $toplayout;
echo '<META HTTP-EQUIV=REFRESH CONTENT="2; URL=' . $siteurl . '/forum.php?viewsub=' . $ttoy . '">
Your message has been posted into your new forum subject. <a href="' . $siteurl . '/forum.php?viewsub=' . $ttoy . '">Click here</a> to go there now.';
} else {
SQLact("query", "INSERT INTO freelancers_forum (pid, froom, acctype, date, date2, private, privatetype, message, mid) VALUES ('$reply', '$username', '$atype', '$tttoy', '$ttoy', '$private', '$ptype', '$message', '$ttoy')");
$mymsg = $username . ' has just posted the following private message for you on the message boards. You can reply to the message at ' . $siteurl . '/forum.php?viewsub=' . $reply . '
-----------------------------------------
' . $message;
mail(SQLact("result", $bozzyres,0,"email"),$companyname . " Message Board Post Notice",$mymsg,"From: $username <$emailaddress>");
echo $toplayout;
echo '<META HTTP-EQUIV=REFRESH CONTENT="2; URL=' . $siteurl . '/forum.php?viewsub=' . $reply . '">
Your message has been posted. <a href="' . $siteurl . '/forum.php?viewsub=' . $reply . '">Click here</a> to go there now.';
}
}
} else if ($private !== "" && $ptype == "freelancer"){
$bozzyres = SQLact("query", "SELECT * FROM freelancers_programmers WHERE username='$private'");
$bozzyrows = SQLact("num_rows", $bozzyres);
if ($bozzyrows==0) {
echo $toplayout;
echo 'No ' . $freelancer . ' account was found with the username <b>' . $private . '</b>. <br><A HREF="javascript:history.back()">Go Back</A>';
} else {
$ttoy = time();
$tttoy = date("n/j/Y @ G:i");
if (SQLact("num_rows", $result)==0) {
SQLact("query", "INSERT INTO freelancers_subjects (pid, subject, date, date2) VALUES ('$ttoy', '$subject', '$tttoy', '$ttoy')");
SQLact("query", "INSERT INTO freelancers_forum (pid, froom, acctype, date, date2, private, privatetype, message, mid) VALUES ('$ttoy', '$username', '$atype', '$tttoy', '$ttoy', '$private', '$ptype', '$message', '$ttoy')");
$mymsg = $username . ' has just posted the following private message for you on the message boards. You can reply to the message at ' . $siteurl . '/forum.php?viewsub=' . $ttoy . '
-----------------------------------------
' . $message;
mail(SQLact("result", $bozzyres,0,"email"),$companyname . " Message Board Post Notice",$mymsg,"From: $username <$emailaddress>");
echo $toplayout;
echo '<META HTTP-EQUIV=REFRESH CONTENT="2; URL=' . $siteurl . '/forum.php?viewsub=' . $ttoy . '">
Your message has been posted into your new forum subject. <a href="' . $siteurl . '/forum.php?viewsub=' . $ttoy . '">Click here</a> to go there now.';
} else {
SQLact("query", "INSERT INTO freelancers_forum (pid, froom, acctype, date, date2, private, privatetype, message, mid) VALUES ('$reply', '$username', '$atype', '$tttoy', '$ttoy', '$private', '$ptype', '$message', '$ttoy')");
$mymsg = $username . ' has just posted the following private message for you on the message boards. You can reply to the message at ' . $siteurl . '/forum.php?viewsub=' . $reply . '
-----------------------------------------
' . $message;
mail(SQLact("result", $bozzyres,0,"email"),$companyname . " Message Board Post Notice",$mymsg,"From: $username <$emailaddress>");
echo $toplayout;
echo '<META HTTP-EQUIV=REFRESH CONTENT="2; URL=' . $siteurl . '/forum.php?viewsub=' . $reply . '">
Your message has been posted. <a href="' . $siteurl . '/forum.php?viewsub=' . $reply . '">Click here</a> to go there now.';
}
}
} else {
$ttoy = time();
$tttoy = date("n/j/Y @ G:i");
if (SQLact("num_rows", $result)==0) {
SQLact("query", "INSERT INTO freelancers_subjects (pid, subject, date, date2) VALUES ('$ttoy', '$subject', '$tttoy', '$ttoy')");
SQLact("query", "INSERT INTO freelancers_forum (pid, froom, acctype, date, date2, private, privatetype, message, mid) VALUES ('$ttoy', '$username', '$atype', '$tttoy', '$ttoy', '$private', '$ptype', '$message', '$ttoy')");
echo $toplayout;
echo '<META HTTP-EQUIV=REFRESH CONTENT="2; URL=' . $siteurl . '/forum.php?viewsub=' . $ttoy . '">
Your message has been posted into your new forum subject. <a href="' . $siteurl . '/forum.php?viewsub=' . $ttoy . '">Click here</a> to go there now.';
} else {
SQLact("query", "INSERT INTO freelancers_forum (pid, froom, acctype, date, date2, private, privatetype, message, mid) VALUES ('$reply', '$username', '$atype', '$tttoy', '$ttoy', '$private', '$ptype', '$message', '$ttoy')");
if ($atype == "buyer") {
$projnotify = SQLact("query", "SELECT * FROM freelancers_projects WHERE id='$reply' AND creator='$username'");
if (SQLact("num_rows", $projnotify)!==0) {
$mymsg = $emailheader . '
----------
' . $username . ' has just posted on your project\'s message board (Project name: ' . SQLact("result", $projnotify,0,"project") . '). It could be a question that requires a response. You can view the message at '  .$siteurl . '/forum.php?viewsub=' . $reply . '
----------
' . $emailfooter;
mail(SQLact("result", $bozzyres,0,"email"),$companyname . " Message Board Post Notice",$mymsg,"From: $username <$emailaddress>");
}
}
echo $toplayout;
echo '<META HTTP-EQUIV=REFRESH CONTENT="2; URL=' . $siteurl . '/forum.php?viewsub=' . $reply . '">
Your message has been posted. <a href="' . $siteurl . '/forum.php?viewsub=' . $reply . '">Click here</a> to go there now.';
}
}
}
}
} else {
$bozzyres = SQLact("query", "SELECT * FROM freelancers_programmers WHERE username='$username'");
$bozzyrows = SQLact("num_rows", $bozzyres);
if ($bozzyrows==0) {
echo $toplayout;
echo 'No ' . $freelancer . ' account was found with the username <b>' . $username . '</b>. <br><A HREF="javascript:history.back()">Go Back</A>';
} else {
$byzzyres = SQLact("query", "SELECT * FROM freelancers_programmers WHERE username='$username' AND password='$password'");
$byzzyrows = SQLact("num_rows", $byzzyres);
if ($byzzyrows==0) {
echo $toplayout;
echo 'The password you provided is incorrect. <br><A HREF="javascript:history.back()">Go Back</A>';
} else {
setcookie ("fusername", $username,time()+999999);
setcookie ("fpassword", $password,time()+999999);
if ($private !== "" && $ptype == "buyer") {
$bozzyres = SQLact("query", "SELECT * FROM freelancers_webmasters WHERE username='$private'");
$bozzyrows = SQLact("num_rows", $bozzyres);
if ($bozzyrows==0) {
echo $toplayout;
echo 'No ' . $buyer . ' account was found with the username <b>' . $private . '</b>. <br><A HREF="javascript:history.back()">Go Back</A>';
} else {
$ttoy = time();
$tttoy = date("n/j/Y @ G:i");
if (SQLact("num_rows", $result)==0) {
SQLact("query", "INSERT INTO freelancers_subjects (pid, subject, date, date2) VALUES ('$ttoy', '$subject', '$tttoy', '$ttoy')");
SQLact("query", "INSERT INTO freelancers_forum (pid, froom, acctype, date, date2, private, privatetype, message, mid) VALUES ('$ttoy', '$username', '$atype', '$tttoy', '$ttoy', '$private', '$ptype', '$message', '$ttoy')");
$mymsg = $username . ' has just posted the following private message for you on the message boards. You can reply to the message at ' . $siteurl . '/forum.php?viewsub=' . $ttoy . '
-----------------------------------------
' . $message;
mail(SQLact("result", $bozzyres,0,"email"),$companyname . " Message Board Post Notice",$mymsg,"From: $username <$emailaddress>");
echo $toplayout;
echo '<META HTTP-EQUIV=REFRESH CONTENT="2; URL=' . $siteurl . '/forum.php?viewsub=' . $ttoy . '">
Your message has been posted into your new forum subject. <a href="' . $siteurl . '/forum.php?viewsub=' . $ttoy . '">Click here</a> to go there now.';
} else {
SQLact("query", "INSERT INTO freelancers_forum (pid, froom, acctype, date, date2, private, privatetype, message, mid) VALUES ('$reply', '$username', '$atype', '$tttoy', '$ttoy', '$private', '$ptype', '$message', '$ttoy')");
$mymsg = $username . ' has just posted the following private message for you on the message boards. You can reply to the message at ' . $siteurl . '/forum.php?viewsub=' . $reply . '
-----------------------------------------
' . $message;
mail(SQLact("result", $bozzyres,0,"email"),$companyname . " Message Board Post Notice",$mymsg,"From: $username <$emailaddress>");
echo $toplayout;
echo '<META HTTP-EQUIV=REFRESH CONTENT="2; URL=' . $siteurl . '/forum.php?viewsub=' . $reply . '">
Your message has been posted. <a href="' . $siteurl . '/forum.php?viewsub=' . $reply . '">Click here</a> to go there now.';
}
}
} else if ($private !== "" && $ptype == "freelancer") {
$bozzyres = SQLact("query", "SELECT * FROM freelancers_programmers WHERE username='$private'");
$bozzyrows = SQLact("num_rows", $bozzyres);
if ($bozzyrows==0) {
echo $toplayout;
echo 'No ' . $freelancer . ' account was found with the username <b>' . $private . '</b>. <br><A HREF="javascript:history.back()">Go Back</A>';
} else {
$ttoy = time();
$tttoy = date("n/j/Y @ G:i");
if (SQLact("num_rows", $result)==0) {
SQLact("query", "INSERT INTO freelancers_subjects (pid, subject, date, date2) VALUES ('$ttoy', '$subject', '$tttoy', '$ttoy')");
SQLact("query", "INSERT INTO freelancers_forum (pid, froom, acctype, date, date2, private, privatetype, message, mid) VALUES ('$ttoy', '$username', '$atype', '$tttoy', '$ttoy', '$private', '$ptype', '$message', '$ttoy')");
$mymsg = $username . ' has just posted the following private message for you on the message boards. You can reply to the message at ' . $siteurl . '/forum.php?viewsub=' . $ttoy . '
-----------------------------------------
' . $message;
mail(SQLact("result", $bozzyres,0,"email"),$companyname . " Message Board Post Notice",$mymsg,"From: $username <$emailaddress>");
echo $toplayout;
echo '<META HTTP-EQUIV=REFRESH CONTENT="2; URL=' . $siteurl . '/forum.php?viewsub=' . $ttoy . '">
Your message has been posted into your new forum subject. <a href="' . $siteurl . '/forum.php?viewsub=' . $ttoy . '">Click here</a> to go there now.';
} else {
SQLact("query", "INSERT INTO freelancers_forum (pid, froom, acctype, date, date2, private, privatetype, message, mid) VALUES ('$reply', '$username', '$atype', '$tttoy', '$ttoy', '$private', '$ptype', '$message', '$ttoy')");
$mymsg = $username . ' has just posted the following private message for you on the message boards. You can reply to the message at ' . $siteurl . '/forum.php?viewsub=' . $reply . '
-----------------------------------------
' . $message;
mail(SQLact("result", $bozzyres,0,"email"),$companyname . " Message Board Post Notice",$mymsg,"From: $username <$emailaddress>");
echo $toplayout;
echo '<META HTTP-EQUIV=REFRESH CONTENT="2; URL=' . $siteurl . '/forum.php?viewsub=' . $reply . '">
Your message has been posted. <a href="' . $siteurl . '/forum.php?viewsub=' . $reply . '">Click here</a> to go there now.';
}
}
} else {
$ttoy = time();
$tttoy = date("n/j/Y @ G:i");
if (SQLact("num_rows", $result)==0) {
SQLact("query", "INSERT INTO freelancers_subjects (pid, subject, date, date2) VALUES ('$ttoy', '$subject', '$tttoy', '$ttoy')");
SQLact("query", "INSERT INTO freelancers_forum (pid, froom, acctype, date, date2, private, privatetype, message, mid) VALUES ('$ttoy', '$username', '$atype', '$tttoy', '$ttoy', '$private', '$ptype', '$message', '$ttoy')");
echo $toplayout;
echo '<META HTTP-EQUIV=REFRESH CONTENT="2; URL=' . $siteurl . '/forum.php?viewsub=' . $ttoy . '">
Your message has been posted into your new forum subject. <a href="' . $siteurl . '/forum.php?viewsub=' . $ttoy . '">Click here</a> to go there now.';
} else {
SQLact("query", "INSERT INTO freelancers_forum (pid, froom, acctype, date, date2, private, privatetype, message, mid) VALUES ('$reply', '$username', '$atype', '$tttoy', '$ttoy', '$private', '$ptype', '$message', '$ttoy')");
if ($atype == "buyer") {
$projnotify = SQLact("query", "SELECT * FROM freelancers_projects WHERE id='$reply' AND creator='$username'");
if (SQLact("num_rows", $projnotify)!==0) {
$mymsg = $emailheader . '
----------
' . $username . ' has just posted on your project\'s message board (Project name: ' . SQLact("result", $projnotify,0,"project") . '). It could be a question that requires a response. You can view the message at '  .$siteurl . '/forum.php?viewsub=' . $reply . '
----------
' . $emailfooter;
mail(SQLact("result", $bozzyres,0,"email"),$companyname . " Message Board Post Notice",$mymsg,"From: $username <$emailaddress>");
}
}
echo $toplayout;
echo '<META HTTP-EQUIV=REFRESH CONTENT="2; URL=' . $siteurl . '/forum.php?viewsub=' . $reply . '">
Your message has been posted. <a href="' . $siteurl . '/forum.php?viewsub=' . $reply . '">Click here</a> to go there now.';
}
}
}
}
}
}
}
} else if ($delmessage && $delmessage !== "") {
echo $toplayout;
if ($adminforum == $adminpass) {
SQLact("query", "DELETE FROM freelancers_forum WHERE mid='$delmessage'");
echo 'The forum message you selected was successfully deleted!<br>
<a href="' . $siteurl . '/forum.php">Go Back...</a>';
} else {
echo 'You aren\'t authorized to perform this action!<br>
<a href="' . $siteurl . '/forum.php">Go Back...</a>';
}
} else if ($delete && $delete !== "") {
echo $toplayout;
if ($adminforum == $adminpass) {
SQLact("query", "DELETE FROM freelancers_subjects WHERE pid='$delete'");
echo 'The forum subject you selected was successfully deleted!<br>
<a href="' . $siteurl . '/forum.php">Go Back...</a>';
} else {
echo 'You aren\'t authorized to perform this action!<br>
<a href="' . $siteurl . '/forum.php">Go Back...</a>';
}
} else {
echo $toplayout;
if (!$next) {
$result = SQLact("query", "SELECT pid, subject, date, date2 FROM freelancers_subjects ORDER BY date2 DESC LIMIT 0,$forumnextnum");
$next = 1;
$displaynum = $forumnextnum;
} else {
$olddispnum = ($forumnextnum * $next) - $forumnextnum;
$displaynum = $forumnextnum * $next;
$result = SQLact("query", "SELECT pid, subject, date, date2 FROM freelancers_subjects ORDER BY date2 DESC LIMIT $olddispnum,$displaynum");
}
echo '<big><b>' . $companyname . ' Message Boards</b></big><p>
<a href="' . $siteurl . '/forum.php?new=subject"><img src="' . $siteurl . '/createsubject.gif" border=0 alt="Create New Subject"></a>
<p>
<table border="' . $tableborder . '" width="100%" cellspacing="' . $tablecellsp . '" cellpadding="' . $tablecellpa . '">
<tr>
<td bgcolor="' . $tablecolorh . '"><b><font color=' . $tablecolort . '>Subject</td>
<td bgcolor="' . $tablecolorh . '"><b><font color=' . $tablecolort . '>Posts</td>
<td bgcolor="' . $tablecolorh . '"><b><font color=' . $tablecolort . '>Date Created</td>
';
if ($adminforum == $adminpass) {
$cs=4;
echo '<td bgcolor="' . $tablecolorh . '"><b><font color=' . $tablecolort . '>Delete</td>
';
} else {
$cs=3;
}
echo '</tr>';
if (SQLact("num_rows", $result)==0) {
echo '<tr><td bgcolor=' . $tablecolor1 . ' colspan=' . $cs . '><center>(No subjects have been posted. <a href="' . $siteurl . '/forum.php?new=subject">Click here</a> to post the first one.)<td></tr>';
} else {
while ($row=SQLact("fetch_array", $result)) {
$subject = SQLact("query", "SELECT * FROM freelancers_forum WHERE pid='" . $row[pid] . "'");
echo '<tr>
<td bgcolor="' . $tablecolor1 . '"><a href="' . $siteurl . '/forum.php?viewsub=' . $row[pid] . '">' . $row[subject] . '</a></td>
<td bgcolor="' . $tablecolor1 . '">' . SQLact("num_rows", $subject) . '</td>
<td bgcolor="' . $tablecolor1 . '">' . $row[date] . '</td>';
if ($adminforum == $adminpass) {
echo '<td bgcolor="' . $tablecolor1 . '"><b><font color=' . $tablecolort . '><a href="' . $siteurl . '/forum.php?delete=' . $row[pid] . '">Delete</a></td>
';
}
echo '</tr>';
}
}
echo '</table>
';
if (SQLact("num_rows", SQLact("query", "SELECT * FROM freelancers_subjects")) > SQLact("num_rows", SQLact("query", "SELECT * FROM freelancers_subjects LIMIT 0,$displaynum"))) {
$next = $next + 1;
echo '<br><small><a href="' . $siteurl . '/forum.php?next=' . $next . '">Display Next ' . $forumnextnum . ' >></a></small>';
}
echo '<p>
<a href="' . $siteurl . '/forum.php?new=subject"><img src="' . $siteurl . '/createsubject.gif" border=0 alt="Create New Subject"></a>';
}
if ($adminforum == $adminpass) {
echo '<br><br><a href="' . $siteurl . '/admin.php?pass=' . $adminpass . '">Back to Admin...</a>';
}
echo $bottomlayout;
?>