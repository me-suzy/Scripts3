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

if (!$sql_mode) {
require "vars.php";
require "cron.php";
}

$snow = $show;
if ($snow!="") {
echo '<table border="' . $tableborder . '" cellspacing="' . $tablecellsp . '" cellpadding="' . $tablecellpa . '" width="100%">
<tr>
<td bgcolor="' . $tablecolorh . '"><small><b><font color=' . $tablecolort . '>' . $freelancer . '</td>
<td bgcolor="' . $tablecolorh . '"><small><b><font color=' . $tablecolort . '>Rating</td>
<td bgcolor="' . $tablecolorh . '"><small><b><font color=' . $tablecolort . '>Reviews</td>
</tr>';
$i=0;
$result0 = SQLact("query", "SELECT username,rating,AVG(rating) as average,count(rating) as rating_count FROM freelancers_ratings WHERE type='freelancer' AND status='rated' GROUP BY username ORDER BY rating_count DESC, average DESC LIMIT 0,$snow");
while ($row=SQLact("fetch_array", $result0)) {
if (SQLact("num_rows", $result0)==0) {} else {
$i++;
echo '<tr>
<td bgcolor="' . $tablecolor1 . '"><a href="' . $siteurl . '/freelancers.php?viewprofile=' . $row[username] . '">' . $row[username] . '</a>';
$dddres = SQLact("query", "SELECT * FROM freelancers_programmers WHERE username='" . $row[username] . "' AND special='user'");
if (SQLact("num_rows", $dddres)==0) {} else {
echo ' <a href="' . $siteurl . '/aboutspecial.html"><img src="' . $siteurl . '/special.gif" alt="Special User" border=0></a>';
}
echo '</td>
<td bgcolor="' . $tablecolor1 . '"><a href="' . $siteurl . '/feedback.php?f=' . $row[username] . '">';
$avgratin = round($row[average], 2);
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
echo '</a></td>';
echo '<td bgcolor="' . $tablecolor1 . '"><a href="' . $siteurl . '/feedback.php?f=' . $row[username] . '"</a>' . $row[rating_count] . '</a></td>
</tr>';
}
}
if ($i=0) {
echo '<tr>
<td bgcolor="' . $tablecolor1 . '" colspan=3><small>No ' . $freelancers . ' with ratings could be found.</td>
</tr>';
} else {}
echo '</table>';
} else {
echo '<table border="' . $tableborder . '" cellspacing="' . $tablecellsp . '" cellpadding="' . $tablecellpa . '" width="100%">
<tr>
<td bgcolor="' . $tablecolorh . '"><small><b><font color=' . $tablecolort . '>' . $freelancer . '</td>
<td bgcolor="' . $tablecolorh . '"><small><b><font color=' . $tablecolort . '>Rating</td>
<td bgcolor="' . $tablecolorh . '"><small><b><font color=' . $tablecolort . '>Reviews</td>
</tr>';
$i=0;
$result0 = SQLact("query", "SELECT username,AVG(rating) as average,count(rating) as rating_count FROM freelancers_ratings WHERE type='freelancer' AND status='rated' GROUP BY username ORDER BY rating_count DESC, average DESC LIMIT 0,5");
while ($row=SQLact("fetch_array", $result0)) {
if (SQLact("num_rows", $result0)==0) {} else {
$i++;
echo '<tr>
<td bgcolor="' . $tablecolor1 . '"><a href="' . $siteurl . '/freelancers.php?viewprofile=' . $row[username] . '">' . $row[username] . '</a>';
$dddres = SQLact("query", "SELECT * FROM freelancers_programmers WHERE username='" . $row[username] . "' AND special='user'");
if (SQLact("num_rows", $dddres)==0) {} else {
echo ' <a href="' . $siteurl . '/aboutspecial.html"><img src="' . $siteurl . '/special.gif" alt="Special User" border=0></a>';
}
echo '</td>
<td bgcolor="' . $tablecolor1 . '"><a href="' . $siteurl . '/feedback.php?f=' . $row[username] . '">';
$avgratin = round($row[average], 2);
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
echo '</a></td>';
echo '<td bgcolor="' . $tablecolor1 . '"><a href="' . $siteurl . '/feedback.php?f=' . $row[username] . '"</a>' . $row[rating_count] . '</a></td>
</tr>';
}
}
echo '</table>';
if ($i=0) {
echo '<tr>
<td bgcolor="' . $tablecolor1 . '" colspan=3><small>No ' . $freelancers . ' with ratings could be found.</td>
</tr>';
} else {}
}
?>