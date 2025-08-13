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

if ($id && $id !== "") {
echo $toplayout;
$result = SQLact("query", "SELECT * FROM freelancers_projects WHERE id='$id'");
if (SQLact("num_rows", $result)==0) {
echo 'No project was found with that ID number.';
} else {
?>
<b><big>Project: <? echo SQLact("result", $result,0,"project"); ?></big></b><?
if (SQLact("result", $result,0,"special") == "featured") {
echo ' <a href="' . $siteurl . '/featured.html"><img src="' . $siteurl . '/featured.gif" alt="Featured Project!" border=0></a>';
}
$secondsPerDay9 = ((24 * 60) * 60);
$timeStamp9 = time();
$daysUntilExpiry9 = SQLact("result", $result,0,"expires");
$getdat9 = $timeStamp9 + ($daysUntilExpiry9 * $secondsPerDay9);
$thedat9 = $getdat9-$timeStamp9;
$realdat9 = round($thedat9/((24 * 60) * 60));
$explod9 = explode('-', $projectudays);
for ($i9=0;$i9<count($explod9);$i9++) {
if ($realdat9==$explod9[$i9]) {
echo ' <img src="' . $siteurl . '/urgent.gif" alt="Urgent!" border=0>';
}
}
?><br><b>ID:</b> <? echo $id; ?><p>
<?php
if (SQLact("result", $result,0,"status") == "cancelled") {
echo '(Cancelled Project)<p>';
} else if (SQLact("result", $result,0,"status") == "closed") {
echo '<b>Chosen ' . $freelancer . ':</b> <a href="' . $siteurl . '/freelancers.php?viewprofile=' . SQLact("result", $result,0,"chosen") . '">' . SQLact("result", $result,0,"chosen") . '</a>';
$result2 = SQLact("query", "SELECT * FROM freelancers_programmers WHERE username='" . SQLact("result", $result,0,"chosen") . "'");
if (SQLact("result", $result2,0,"special") == "user") {
echo ' <a href="' . $siteurl . '/aboutspecial.html"><img src="' . $siteurl . '/special.gif" alt="Special User" border=0></a>';
}
}
?>

<table border="<? echo $tableborder; ?>" width="100%" cellpadding="<? echo $tablecellpa; ?>" cellspacing="<? echo $tablecellsp; ?>">
<tr>
<td width="20%" bgcolor=<? echo $tablecolor1; ?> valign=top width=150><b>Status:</td>
<td width="80%" bgcolor=<? echo $tablecolor1; ?> valign=top>
<?php
echo SQLact("result", $result,0,"status");
if (SQLact("result", $result,0,"status") == "cancelled" || SQLact("result", $result,0,"status") == "closed") {
echo ' (No bidding.)';
} else if (SQLact("result", $result,0,"status") == "frozen") {
echo ' (No bidding.  Waiting for action from project owner.)';
}
$secondsPerDay29 = ((24 * 60) * 60);
$timeStamp29 = time();
$daysUntilExpiry29 = SQLact("result", $result,0,"expires");
$getdat29 = $timeStamp29 + ($daysUntilExpiry29 * $secondsPerDay29);
$thedat29 = $getdat29-$timeStamp29;
$realdat29 = round($thedat29/((24 * 60) * 60));
$explod29 = explode('-', $projectudays);
for ($i29=0;$i29<count($explod29);$i29++) {
if ($realdat29==$explod29[$i29]) {
echo ' <img src="' . $siteurl . '/urgent.gif" alt="Urgent!" border=0>';
}
}
?>
</td>
</tr>

<tr>
<td bgcolor=<? echo $tablecolor2; ?> valign=top><b>Budget:</td>
<td bgcolor=<? echo $tablecolor2; ?> valign=top>
<?php
if (SQLact("result", $result,0,"budgetmin") == "" && SQLact("result", $result,0,"budgetmax") !== "") {
echo 'Max. ' . $currencytype . '' . $currency . '' . SQLact("result", $result,0,"budgetmax");
} else if (SQLact("result", $result,0,"budgetmin") !== "" && SQLact("result", $result,0,"budgetmax") == "") {
echo 'Min. ' . $currencytype . '' . $currency . '' . SQLact("result", $result,0,"budgetmin");
} else if (SQLact("result", $result,0,"budgetmin") !== "" && SQLact("result", $result,0,"budgetmax") !== "") {
echo $currencytype . '' . $currency . '' . SQLact("result", $result,0,"budgetmin") . ' - ' . $currencytype . '' . $currency . '' . SQLact("result", $result,0,"budgetmax");
}
?>
</td>
</tr>

<tr>
<td bgcolor=<? echo $tablecolor1; ?> valign=top><b>Created:</td>
<td bgcolor=<? echo $tablecolor1; ?> valign=top>
<? echo SQLact("result", $result,0,"creation") . ' ' . SQLact("result", $result,0,"ctime"); ?>
</td>
</tr>

<tr>
<td bgcolor=<? echo $tablecolor2; ?> valign=top><b>Delivery Date:</td>
<td bgcolor=<? echo $tablecolor2; ?> valign=top>
<?php
$secondsPerDay = ((24 * 60) * 60);

$timeStamp = time();

$daysUntilExpiry = SQLact("result", $result,0,"expires");
$expiry = $timeStamp + ($daysUntilExpiry * $secondsPerDay);


$date  = getdate($expiry);
$month = $date["mon"];
$day   = $date["mday"];
$year  = $date["year"];

$expiryDate = $month . '/' . $day . '/' . $year;
echo $expiryDate . ' ' . SQLact("result", $result,0,"ctime");

if ($expiry==0) {
echo ' (less than a day left)';
} else if ($expiry >= 1) {
echo ' (' . ( $expiry - $timeStamp ) / $secondsPerDay . ' day';
if ($expiry==1) {} else {
echo 's';
}

echo ' left)';
} else {
echo ' (expired)';
}
?>
</td>
</tr>

<tr>
<td bgcolor=<? echo $tablecolor1; ?> valign=top><b>Project Creator:</td>
<td bgcolor=<? echo $tablecolor1; ?> valign=top><? echo SQLact("result", $result,0,"creator"); ?><?
$check = SQLact("query", "SELECT * FROM freelancers_webmasters WHERE username='" . SQLact("result", $result,0,"creator") . "' AND special='user'");
if (SQLact("num_rows", $check)==0) {} else {
echo ' <a href="' . $siteurl . '/aboutspecial.html"><img src="' . $siteurl . '/special.gif" alt="Special User" border=0></a>';
}
?><br><small>Rating:
<?php
$result8 = SQLact("query", "SELECT AVG(rating) as average2 FROM freelancers_ratings WHERE username='" . SQLact("result", $result,0,"creator") . "' AND type='buyer' AND status='rated'");
if (SQLact("num_rows", SQLact("query", "SELECT * FROM freelancers_ratings WHERE username='" . SQLact("result", $result,0,"creator") . "' AND type='buyer' AND status='rated'"))==0) {
echo '<small>(No Feedback Yet)';
} else {
echo '<a href="' . $siteurl . '/feedback.php?b=' . SQLact("result", $result,0,"creator") . '">';
$avgratin8 = round(SQLact("result", $result8,0,"average2"), 2);
$avgrating8 = explode(".", $avgratin8);
for ($t28=0;$t28<$avgrating8[0];$t28++) {
echo '<img src="' . $siteurl . '/star.gif" border=0 alt="' . $avgratin8 . '/10">';
}
$numeric28 = 10-$avgrating8[0];
if ($numeric28==0) {} else {
for ($b28=0;$b28<$numeric28;$b28++) {
echo '<img src="' . $siteurl . '/star2.gif" border=0 alt="' . $avgratin8 . '/10">';
}
}
if (SQLact("num_rows", SQLact("query", "SELECT * FROM freelancers_ratings WHERE username='" . SQLact("result", $result,0,"creator") . "' AND type='buyer' AND status='rated'"))==1) {
echo ' (<b>1</b> review)';
} else {
echo ' (<b>' . SQLact("num_rows", SQLact("query", "SELECT * FROM freelancers_ratings WHERE username='" . SQLact("result", $result,0,"creator") . "' AND type='buyer' AND status='rated'")) . '</b> reviews)';
}
echo '</a>';
}
?>
</td>
</tr>

<tr>
<td bgcolor=<? echo $tablecolor2; ?> valign=top><b>Description:</td>
<td bgcolor=<? echo $tablecolor2; ?> valign=top><small>
<?php
$desc = SQLact("result", $result,0,"description");
$desc = str_replace("  ", " &nbsp;", $desc);
$desc = str_replace("
", "<br>
", $desc);
echo $desc;
?>
</td>
</tr>
<tr>
<td bgcolor=<? echo $tablecolor1; ?> valign=top><b><? echo $catname; ?>:</td>
<td bgcolor=<? echo $tablecolor1; ?> valign=top>
<?php
$archway = SQLact("result", $result,0,"categories");
$len=strlen($archway);
$boink=substr("$archway", 0, ($len-2));
echo $boink;
?>
</td>
</tr>
<?php
$p1 = $tablecolor2;
$p2 = $tablecolor1;
$p3 = $tablecolor2;
if ($attachmode == "enabled") {
unset ($p1, $p2, $p3);
$p1 = $tablecolor1;
$p2 = $tablecolor2;
$p3 = $tablecolor1;
?>
<tr>
<td bgcolor=<? echo $tablecolor2; ?> valign=top><b>Attachment:</td>
<td bgcolor=<? echo $tablecolor2; ?> valign=top><?
if ($mode == "demo") {
echo 'Not available in demo!';
} else {
if (SQLact("result", $result,0,"attachment") !== "") {
echo '<a href="' . $attachurl . '/' . SQLact("result", $result,0,"attachment") . '" target="_blank">' . SQLact("result", $result,0,"attachment") . '</a>';
} else {
echo 'No Attachment Uploaded.';
}
}
?></td>
</tr>
<?php
}
if ($pcat1 == "") {} else {
?>
<tr>
<td bgcolor=<? echo $p1; ?> valign=top><b><? echo $pcat1; ?>:</td>
<td bgcolor=<? echo $p1; ?> valign=top>
<?php
$boink1 = SQLact("result", $result,0,"pcat1val");
echo $boink1;
?>
</td>
</tr>
<?php
}
if ($pcat2 == "") {} else {
?>
<tr>
<td bgcolor=<? echo $p2; ?> valign=top><b><? echo $pcat2; ?>:</td>
<td bgcolor=<? echo $p2; ?> valign=top>
<?php
$boink2 = SQLact("result", $result,0,"pcat2val");
echo $boink2;
?>
</td>
</tr>
<?php
}
if ($pcat3 == "") {} else {
?>
<tr>
<td bgcolor=<? echo $p3; ?> valign=top><b><? echo $pcat3; ?>:</td>
<td bgcolor=<? echo $p3; ?> valign=top>
<?php
$boink3 = SQLact("result", $result,0,"pcat3val");
echo $boink3;
?>
</td>
</tr>
<?php
}
?>
</table>
<p>
<a href="<? echo $siteurl; ?>/forum.php?viewproj=<? echo $id; ?>"><img src="<? echo $siteurl; ?>/viewboard.gif" border=0 alt="View Message Board for this Project"></a>
<br>
Messages Posted: <b><? echo SQLact("num_rows", SQLact("query", "SELECT * FROM freelancers_forum WHERE pid='" . $id . "'")); ?></b>
<p>
<?php
if (SQLact("result", $result,0,"status") == "closed" || SQLact("result", $result,0,"status") == "cancelled" || SQLact("result", $result,0,"status") == "frozen") {} else {
?>
<a href="<? echo $siteurl; ?>/project.php?bid=<? echo $id; ?>"><img src="<? echo $siteurl; ?>/placebid.gif" border=0 alt="Place Bid"></a>
<p>
<?php
}
?>
<table width="100%" border="<? echo $tableborder; ?>" cellspacing="<? echo $tablecellsp; ?>" cellpadding="<? echo $tablecellpa; ?>">
<tr>
<td bgcolor=<? echo $tablecolorh; ?>><small><b><font color=<? echo $tablecolort; ?>><? echo $freelancer; ?></td>
<td bgcolor=<? echo $tablecolorh; ?>><small><b><font color=<? echo $tablecolort; ?>>Bid</td>
<td bgcolor=<? echo $tablecolorh; ?>><small><b><font color=<? echo $tablecolort; ?>>Delivery Within</td>
<td bgcolor=<? echo $tablecolorh; ?>><small><b><font color=<? echo $tablecolort; ?>>Time of Bid</td>
<td bgcolor=<? echo $tablecolorh; ?>><small><b><font color=<? echo $tablecolort; ?>><center>Rating</td>
</tr>
<?php
$bees = SQLact("query", "SELECT * FROM freelancers_bids WHERE id='" . $id . "' ORDER BY amount ASC, date2 DESC");
if (SQLact("num_rows", $bees)==0) {
echo '<tr>
<td bgcolor="' . $tablecolor1 . '" align="center" colspan=5>(No bids have been placed yet.)</td>
</tr>';
} else {
while ($row=SQLact("fetch_array", $bees)) {
?>
<tr>
<td bgcolor="<? echo $tablecolor1; ?>"><a href="<? echo $siteurl; ?>/freelancers.php?viewprofile=<? echo $row[username]; ?>"><? echo $row[username]; ?></a></td>
<td bgcolor="<? echo $tablecolor1; ?>"><? echo $currencytype . '' . $currency; ?><? echo $row[amount]; ?></td>
<td bgcolor="<? echo $tablecolor1; ?>"><? echo $row[delivery]; ?> day(s)</td>
<td bgcolor="<? echo $tablecolor1; ?>"><? echo $row[date]; ?></td>
<td bgcolor="<? echo $tablecolor1; ?>"><center>
<small><?
$chick = SQLact("query", "SELECT AVG(rating) as average FROM freelancers_ratings WHERE username='" . $row[username] . "' AND type='freelancer' AND status='rated'");
if (SQLact("num_rows", SQLact("query", "SELECT * FROM freelancers_ratings WHERE username='" . $row[username] . "' AND type='freelancer' AND status='rated'"))==0) {
echo '<small>(No Feedback Yet)';
} else {
echo '<a href="' . $siteurl . '/feedback.php?f=' . $row[username] . '">';
$avgratin = round(SQLact("result", $chick,0,"average"), 2);
$avgrating = explode(".", $avgratin);
for ($t2=0;$t2<$avgrating[0];$t2++) {
echo '<img src="' . $siteurl . '/star.gif" border=0 alt="' . $avgratin . '/10">';
}
$numeric2 = 10-$avgrating[0];
if ($numeric2==0) {} else {
for ($b2=0;$b2<$numeric2;$b2++) {
echo '<img src="' . $siteurl . '/star2.gif" border=0 alt="' . $avgratin . '/10">';
}
}
if (SQLact("num_rows", SQLact("query", "SELECT * FROM freelancers_ratings WHERE username='" . $row[username] . "' AND type='freelancer' AND status='rated'"))==1) {
echo ' (<b>1</b> review)';
} else {
echo ' (<b>' . SQLact("num_rows", SQLact("query", "SELECT * FROM freelancers_ratings WHERE username='" . $row[username] . "' AND type='freelancer' AND status='rated'")) . '</b> reviews)';
}
echo '</a>';
}
?></small>
</td>
</tr>
<tr>
<td bgcolor="<? echo $tablecolor1; ?>" colspan=5><small><? echo $row[details]; ?></td>
</tr>
<?php
}
}
?>
</table>
<p>
<?php
if (SQLact("result", $result,0,"status") == "closed" || SQLact("result", $result,0,"status") == "cancelled" || SQLact("result", $result,0,"status") == "frozen") {} else {
?>
<a href="<? echo $siteurl; ?>/project.php?bid=<? echo $id; ?>"><img src="<? echo $siteurl; ?>/placebid.gif" border=0 alt="Place Bid"></a>
<p>
<?php
}
}
} else if ($invite && $invite !== "") {
$realun = SQLact("query", "SELECT * FROM freelancers_programmers WHERE username='$invite'");
if (SQLact("num_rows", $realun)==0) {
echo $toplayout;
echo 'No ' . $freelancer . ' account was found with the username <b>' . $invite . '</b><br>
<a href="javascript:history.go(-1);">Go Back...</a>';
} else {
if (!$submit) {
$username = $HTTP_COOKIE_VARS["busername"];
$password = $HTTP_COOKIE_VARS["bpassword"];
echo $toplayout;
?>
<b>Please login first</b>
<p>
<form method="POST" action="project.php">
<input type="hidden" name="invite" value="<? echo $invite; ?>">

<strong>Username:</strong><br>
<small>(<a href="buyers.php?forgot=find">Click here</a> if you forgot it.)</small>
<br>
<input type="text" name="username" value="<? echo $username; ?>" size="15">
<br>
<strong>Password:</strong><br>
<small>(<a href="buyers.php?forgot=find">Click here</a> if you forgot it.)</small>
<br>
<input type="password" name="password" value="<? echo $password; ?>" size="15">
<br>
<input type="submit" value="Login" name="submit">
</form>
<?php
} else {
$bozzyres = SQLact("query", "SELECT * FROM freelancers_webmasters WHERE username='$username'");
$bozzyrows = SQLact("num_rows", $bozzyres);
if ($bozzyrows==0) {
echo $toplayout;
echo 'No ' . $buyer . ' account was found with the username <b>' . $username . '</b>. <br>
<A HREF="javascript:history.go(-1);">Go Back</A>';
} else {
$byzzyres = SQLact("query", "SELECT * FROM freelancers_webmasters WHERE username='$username' AND password='$password'");
$byzzyrows = SQLact("num_rows", $byzzyres);
if ($byzzyrows==0) {
echo $toplayout;
echo 'The password you provided is incorrect. <br>
<A HREF="javascript:history.go(-1);">Go Back</A>';
} else {
setcookie ("busername", $username,time()+999999);
setcookie ("bpassword", $password,time()+999999);
echo $toplayout;
$result = SQLact("query", "SELECT * FROM freelancers_projects WHERE creator='$username' AND status='open'");
if (SQLact("num_rows", $result)==0) {
echo 'You do not have any open projects right now, sorry.<br>
<a href="' . $siteurl . '/buyers.php?manage=2">Manage Account...</a>';
} else {
if (!$confirm) {
echo '<form method="POST" action="project.php">
<input type="hidden" name="invite" value="' . $invite . '">
<input type="hidden" name="username" value="' . $username . '">
<input type="hidden" name="password" value="' . $password . '">
<input type="hidden" name="submit" value="Login">
<strong>Which project would you like to invite <a href="' . $siteurl . '/freelancers.php?viewprofile=' . $invite . '">' . $invite . '</a> to bid on?</strong>
<select name="project" size="1">
';
while ($row=SQLact("fetch_array", $result)) {
echo '<option value="' . $row[id] . '">' . $row[project] . '</option>
';
}
echo '</select>
<input type="submit" value="Invite" name="confirm">
</form>';
} else {
$emaddr = SQLact("result", $realun,0,"email");
$mymess = $emailheader . '
----------
' . $username . ' has just invited you to place a bid on their project. The name of the project is "' . SQLact("result", $result,0,"project") . '" and you can view it at the following URL: ' . $siteurl . '/project.php?id=' . SQLact("result", $result,0,"id") . '
----------
' . $emailfooter;
mail($emaddr,$companyname . " Project Invitation",$mymess,"From: $emailaddress");
echo 'Done. Your invitation has been successfully sent to <b>' . $invite . '</b>.';
}
}
}
}
}
}
} else if ($bid && $bid !== "") {
$hugeres2 = SQLact("query", "SELECT * FROM freelancers_suspends WHERE ip='" . $REMOTE_ADDR . "'");
$hugerows2 = SQLact("num_rows", $hugeres2);
if ($hugerows2==0) {
$result = SQLact("query", "SELECT * FROM freelancers_projects WHERE id='$bid' AND status='open'");
if (SQLact("num_rows", $result)==0) {
echo $toplayout;
echo 'ERROR: No project was found with that ID number or the project found is not currently open for bidding.<br>
<a href="javascript:history.go(-1);">Go Back...</a>';
} else {
if (!$submit) {
$username = $HTTP_COOKIE_VARS["fusername"];
$password = $HTTP_COOKIE_VARS["fpassword"];
echo $toplayout;
echo '<form method="POST" action="project.php">
<input type="hidden" name="bid" value="' . $bid . '">

<big><b>Bid on Project: ' . SQLact("result", $result,0,"project") . '</b></big>
<p>
<p>
<strong>Username:</strong><br>
<small>(<a href="' . $siteurl . '/freelancers.php?new=user">Click here to signup</a>)</small>
<br>
<input type="text" name="username" value="' . $username . '" size="15">
<p>
<strong>Password:</strong><br>
<input type="password" name="password" value="' . $password . '" size="15">
<p>
<strong>Your bid for the total project:</strong><br>';
if (SQLact("result", $result,0,"budgetmin") == "" && SQLact("result", $result,0,"budgetmax") !== "") {
echo '<small>Project budget maximum is ' . $currencytype . '' . $currency . '' . SQLact("result", $result,0,"budgetmax") . '</small><br>';
} else if (SQLact("result", $result,0,"budgetmin") !== "" && SQLact("result", $result,0,"budgetmax") == "") {
echo '<small>Project budget minimum is ' . $currencytype . '' . $currency . '' . SQLact("result", $result,0,"budgetmin") . '</small><br>';
} else if (SQLact("result", $result,0,"budgetmin") !== "" && SQLact("result", $result,0,"budgetmax") !== "") {
echo '<small>Project budget is ' . $currencytype . '' . $currency . '' . SQLact("result", $result,0,"budgetmin") . ' - ' . $currencytype . '' . $currency . '' . SQLact("result", $result,0,"budgetmax") . '</small><br>';
}
echo $currencytype . '' . $currency . '<input type="text" name="bidamount" maxlength="6" size="6">
<p>
<strong>In how many days can you deliver a completed project?</strong><br>
<small>';
$secondsPerDay = ((24 * 60) * 60);
$timeStamp = time();
$daysUntilExpiry = SQLact("result", $result,0,"expires");
$expiry = $timeStamp + ($daysUntilExpiry * $secondsPerDay);
$date  = getdate($expiry);
$month = $date["mon"];
$day   = $date["mday"];
$year  = $date["year"];
$expiryDate = $month . '/' . $day . '/' . $year;
echo '(The user wants the project completed by ' . $expiryDate. ', which is ';
if ($expiry==0) {
echo 'in less than one day.)';
} else if ($expiry>=1) {
echo 'in ' . ( $expiry - $timeStamp ) / $secondsPerDay . ' day';
if ($expiry==1) {} else {
echo 's';
}
echo '.)';
} else {
echo 'expired.)';
}
echo '</small>
<br>
<input type="text" name="days" maxlength="3" value="' . ( $expiry - $timeStamp ) / $secondsPerDay . '" size="5"> Day(s)

<p>
<strong>Provide the details of your bid (optional):</strong><br>
<textarea rows="8" name="details" cols="52"></textarea>
<p>
<input type="checkbox" name="outbid" value="y"> Notify me by e-mail if someone bids lower than me on this project.
<p>
<input type="submit" value="Place Bid" name="submit">
</form>';
} else {
if ($username == "" || $password == "" || $bidamount == "" || $days == "") {
echo $toplayout;
echo 'ERROR: You have left 1 or more required fields blank.<br>
<a href="javascript:history.go(-1);">Go Back...</a>';
} else {
$bozzyres = SQLact("query", "SELECT * FROM freelancers_programmers WHERE username='$username'");
$bozzyrows = SQLact("num_rows", $bozzyres);
if ($bozzyrows==0) {
echo $toplayout;
echo 'No ' . $freelancer . ' account was found with the username <b>' . $username . '</b>. <br>
<A HREF="javascript:history.go(-1);">Go Back</A>';
} else {
$byzzyres = SQLact("query", "SELECT * FROM freelancers_programmers WHERE username='$username' AND password='$password'");
$byzzyrows = SQLact("num_rows", $byzzyres);
if ($byzzyrows==0) {
echo $toplayout;
echo 'The password you provided is incorrect. <br>
<A HREF="javascript:history.go(-1);">Go Back</A>';
} else {
setcookie ("fusername", $username,time()+999999);
setcookie ("fpassword", $password,time()+999999);
echo $toplayout;
$today = getdate();
$month = $today['mon'];
$day = $today['mday'];
$year = $today['year'];
$hours = $today['hours'];
$minutes = $today['minutes'];
$bidremind = SQLact("query", "SELECT * FROM freelancers_bids WHERE id='$bid' AND amount>$bidamount AND outbid='y' AND username!='$username'");
while ($row=SQLact("fetch_array", $bidremind)) {
$emails = SQLact("query", "SELECT * FROM freelancers_programmers WHERE username='" . $row[username] . "'");
$mymess = $emailheader . '
----------
' . $username . ' has just outbid you on a project. You can view the project and place a new bid at ' . $siteurl . '/project.php?id=' . $bid . '
----------
' . $emailfooter;
mail(SQLact("result", $emails,0,"email"),$companyname . " Project Outbid Notice",$mymess,"From: $emailaddress");
}
$bidnotify = SQLact("query", "SELECT * FROM freelancers_webmasters WHERE username='" . SQLact("result", $result,0,"creator") . "'");
if (SQLact("result", $bidnotify,0,"bidnotify") !== "") {
$mymess2 = $emailheader . '
----------
' . $username . ' has just bid ' . $currencytype . '' . $currency . '' . $bidamount . ' on your project. You can view the details at ' . $siteurl . '/project.php?id=' . $bid . '
----------
' . $emailfooter;
mail(SQLact("result", $bidnotify,0,"email"),$companyname . " Project Bid Notice",$mymess2,"From: $emailaddress");
}
$prevbid = SQLact("query", "SELECT * FROM freelancers_bids WHERE id='$bid' AND username='$username'");
if (SQLact("num_rows", $prevbid)==0) {
SQLact("query", "INSERT INTO freelancers_bids (username, status, id, project, special, amount, delivery, date, details, date2, chosen, outbid) VALUES ('$username', 'open', '$bid', '" . SQLact("result", $result,0,"project") . "', '" . SQLact("result", $result,0,"special") . "', '$bidamount', '$days', '$month/$day/$year @ $hours:$minutes', '$details', '" . time() . "', '', '$outbid')");
echo '<br><center><b>Your bid has been recorded successfully. If you decide to place a new bid on this project, your old one will be erased.<br>
You will be notified by e-mail when this project ends.</b><br>
<a href="' . $siteurl . '/project.php?id=' . $bid . '">Go back...</a>
<META HTTP-EQUIV=REFRESH CONTENT="30; URL=' . $siteurl . '/project.php?id=' . $bid . '">';
} else {
SQLact("query", "UPDATE freelancers_bids SET username='$username', status='open', id='$bid', project='" . SQLact("result", $result,0,"project") . "', special='" . SQLact("result", $result,0,"special") . "', amount='$bidamount', delivery='$days', date='$month/$day/$year @ $hours:$minutes', details='$details', date2='" . time() . "', chosen='', outbid='$outbid' WHERE id='$bid' AND username='$username'");
echo '<br><center><b>Your bid has been recorded successfully. If you decide to place a new bid on this project, your old one will be erased.<br>
(NOTE: Your old bid was replaced with this one.)<br>
You will be notified by e-mail when this project ends.</b><br>
<a href="' . $siteurl . '/project.php?id=' . $bid . '">Go back...</a>
<META HTTP-EQUIV=REFRESH CONTENT="30; URL=' . $siteurl . '/project.php?id=' . $bid . '">';
}
}
}
}
}
}
} else {
echo 'You have been suspended from using ' . $companyname . '!<br>
You do not have access to bid on projects used in this section of the website!<br>
The reason for your suspension is:<br>
<b><i>';
$humanres2 = SQLact("query", "SELECT * FROM freelancers_suspends WHERE ip='" . $REMOTE_ADDR . "'");
$rrreason2 = SQLact("result", $humanres2,0,"reason");
if ($rrreason2 == "") {
echo 'No reason was given for your suspension.';
} else {
echo $rrreason2;
}
echo '</i></b><br><br>
If you think there has been a mistake, contact ' . $emailaddress;
}
} else {
echo 'No project was found with that ID number.';
}
echo $bottomlayout;
?>