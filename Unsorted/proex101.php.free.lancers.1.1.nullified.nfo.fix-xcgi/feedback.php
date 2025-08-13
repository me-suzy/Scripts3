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

$hugeres = SQLact("query", "SELECT * FROM freelancers_bans WHERE ip='" . $REMOTE_ADDR . "'");
$hugerows = SQLact("num_rows", $hugeres);
if ($hugerows==0) {
echo $toplayout;
if ($f && $f !== "") {
$result = SQLact("query", "SELECT AVG(rating) as average FROM freelancers_ratings WHERE username='$f' AND type='freelancer' AND status='rated'");
if (SQLact("num_rows", SQLact("query", "SELECT * FROM freelancers_ratings WHERE username='$f' AND type='freelancer' AND status='rated'"))==0) {
echo 'No feedback has been provided about this user yet.';
} else {
?>
<big><b>Feedback Provided by <? echo $buyers; ?></b></big><br>
Username: <? echo $f;
$dddres = SQLact("query", "SELECT * FROM freelancers_programmers WHERE username='$f' AND special='user'");
if (SQLact("num_rows", $dddres)==0) {} else {
echo ' <a href="' . $siteurl . '/aboutspecial.html"><img src="' . $siteurl . '/special.gif" alt="Special User" border=0></a>';
}
?><br>
Account Type: <? echo $freelancer; ?>
<p>
<table>
<tr>
<td>Total Reviews:</td> <td><b><? echo SQLact("num_rows", SQLact("query", "SELECT * FROM freelancers_ratings WHERE username='$f' AND type='freelancer' AND status='rated'")); ?></b></td>
</tr>
<tr>
<td>Average Rating:</td> <td><b><?
echo round(SQLact("result", $result,0,"average"), 2);
?></b> out of 10</td>
</tr>
</table>
<p>

<table width="100%" border="<? echo $tableborder; ?>" cellspacing="<? echo $tablecellsp; ?>" cellpadding="<? echo $tablecellpa; ?>">
<tr>
<td bgcolor="<? echo $tablecolorh; ?>" width=150><small><b><font color=<? echo $tablecolort; ?>><? echo $buyer; ?></td>
<td bgcolor="<? echo $tablecolorh; ?>"><small><b><font color=<? echo $tablecolort; ?>>Project Name</td>
<td bgcolor="<? echo $tablecolorh; ?>"><small><b><font color=<? echo $tablecolort; ?>>Project Date</td>
<td bgcolor="<? echo $tablecolorh; ?>"><small><b><font color=<? echo $tablecolort; ?>>Rating</td>
</tr>
<?php
$result4 = SQLact("query", "SELECT * FROM freelancers_ratings WHERE username='$f' AND type='freelancer' AND status='rated'");
while ($row2=SQLact("fetch_array", $result4)) {
?>
<tr>
<td bgcolor="<? echo $tablecolor2; ?>"><a href="<? echo $siteurl; ?>/feedback.php?b=<? echo $row2[ratedby]; ?>"><? echo $row2[ratedby]; ?></a></td>
<td bgcolor="<? echo $tablecolor2; ?>"><a href="<? echo $siteurl; ?>/project.php?id=<? echo $row2[projid]; ?>"><? echo $row2[projname]; ?></a></td>
<td bgcolor="<? echo $tablecolor2; ?>"><? echo $row2[projdate]; ?></td>
<td bgcolor="<? echo $tablecolor2; ?>"><? echo $row2[rating]; ?></td>
</tr>
<tr>
<td bgcolor="<? echo $tablecolor2; ?>" colspan=4><small><b>Comments:</b> <? echo $row2[comments]; ?></td>
</tr>
<?php
}
?>
</table>
<?php
}
} else if ($b && $b !== "") {
$result = SQLact("query", "SELECT AVG(rating) as average FROM freelancers_ratings WHERE username='$b' AND type='buyer' AND status='rated'");
if (SQLact("num_rows", SQLact("query", "SELECT * FROM freelancers_ratings WHERE username='$b' AND type='buyer' AND status='rated'"))==0) {
echo 'No feedback has been provided about this user yet.';
} else {
?>
<big><b>Feedback Provided by <? echo $freelancers; ?></b></big><br>
Username: <? echo $b;
$dddres = SQLact("query", "SELECT * FROM freelancers_webmasters WHERE username='$b' AND special='user'");
if (SQLact("num_rows", $dddres)==0) {} else {
echo ' <a href="' . $siteurl . '/aboutspecial.html"><img src="' . $siteurl . '/special.gif" alt="Special User" border=0></a>';
}
?><br>
Account Type: <? echo $buyer; ?>
<p>
<table>
<tr>
<td>Total Reviews:</td> <td><b><? echo SQLact("num_rows", SQLact("query", "SELECT * FROM freelancers_ratings WHERE username='$b' AND type='buyer' AND status='rated'")); ?></b></td>
</tr>
<tr>
<td>Average Rating:</td> <td><b><?
echo round(SQLact("result", $result,0,"average"), 2);
?></b> out of 10</td>
</tr>
</table>
<p>

<table width="100%" border="<? echo $tableborder; ?>" cellspacing="<? echo $tablecellsp; ?>" cellpadding="<? echo $tablecellpa; ?>">
<tr>
<td bgcolor="<? echo $tablecolorh; ?>" width=150><small><b><font color=<? echo $tablecolort; ?>><? echo $freelancer; ?></td>
<td bgcolor="<? echo $tablecolorh; ?>"><small><b><font color=<? echo $tablecolort; ?>>Project Name</td>
<td bgcolor="<? echo $tablecolorh; ?>"><small><b><font color=<? echo $tablecolort; ?>>Project Date</td>
<td bgcolor="<? echo $tablecolorh; ?>"><small><b><font color=<? echo $tablecolort; ?>>Rating</td>
</tr>
<?php
$result4 = SQLact("query", "SELECT * FROM freelancers_ratings WHERE username='$b' AND type='buyer' AND status='rated'");
while ($row2=SQLact("fetch_array", $result4)) {
?>
<tr>
<td bgcolor="<? echo $tablecolor2; ?>"><a href="<? echo $siteurl; ?>/freelancers.php?viewprofile=<? echo $row2[ratedby]; ?>"><? echo $row2[ratedby]; ?></a></td>
<td bgcolor="<? echo $tablecolor2; ?>"><a href="<? echo $siteurl; ?>/project.php?id=<? echo $row2[projid]; ?>"><? echo $row2[projname]; ?></a></td>
<td bgcolor="<? echo $tablecolor2; ?>"><? echo $row2[projdate]; ?></td>
<td bgcolor="<? echo $tablecolor2; ?>"><? echo $row2[rating]; ?></td>
</tr>
<tr>
<td bgcolor="<? echo $tablecolor2; ?>" colspan=4><small><b>Comments:</b> <? echo $row2[comments]; ?></td>
</tr>
<?php
}
?>
</table>
<?php
}
} else {
echo 'No feedback has been provided about this user yet.';
}
echo $bottomlayout;
} else {
echo 'You have been banned from using ' . $companyname . '!<br>
You do not have access to any services used in this section of the website!<br>
The reason for your ban is:<br>
<b><i>';
$humanres = SQLact("query", "SELECT * FROM freelancers_bans WHERE ip='" . $REMOTE_ADDR . "'");
$rrreason = SQLact("result", $humanres,0,"reason");
if ($rrreason == "") {
echo 'No reason was given for your ban.';
} else {
echo $rrreason;
}
echo '</i></b><br><br>
If you think there has been a mistake, contact ' . $emailaddress;
}
?>
