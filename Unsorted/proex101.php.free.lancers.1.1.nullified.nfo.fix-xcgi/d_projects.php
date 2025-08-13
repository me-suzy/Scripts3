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
$result = SQLact("query", "SELECT * FROM freelancers_projects WHERE status='open' ORDER BY date2 DESC LIMIT 0,$show");
echo '<table border="' . $tableborder . '" cellspacing="' . $tablecellsp . '" cellpadding="' . $tablecellpa . '" width="100%">
<tr>
<td bgcolor="' . $tablecolorh . '"><small><b><font color=' . $tablecolort . '>Project Name</td>
<td bgcolor="' . $tablecolorh . '" width=30><small><b><font color=' . $tablecolort . '>Bids</td>
<td bgcolor="' . $tablecolorh . '"><small><b><font color=' . $tablecolort . '>' . $catname . '</td>
<td bgcolor="' . $tablecolorh . '" width=50><small><b><font color=' . $tablecolort . '>Started</td>
<td bgcolor="' . $tablecolorh . '" width=50><small><b><font color=' . $tablecolort . '>Ends</td>
</tr>
';
while ($row=SQLact("fetch_array", $result)) {
echo '<tr>
<td bgcolor="' . $tablecolor1 . '" valign=center><small><a href="' . $siteurl . '/project.php?id=' . $row[id] . '">' . $row[project] . '</a>';
if ($row[special] == "featured") {
echo ' <a href="' . $siteurl . '/featured.html"><img src="' . $siteurl . '/featured.gif" alt="Featured Project!" border=0></a>';
}
$secondsPerDay9 = ((24 * 60) * 60);
$timeStamp9 = time();
$daysUntilExpiry9 = $row[expires];
$getdat9 = $timeStamp9 + ($daysUntilExpiry9 * $secondsPerDay9);
$thedat9 = $getdat9-$timeStamp9;
$realdat9 = round($thedat9/((24 * 60) * 60));
$explod9 = explode('-', $projectudays);
for ($i9=0;$i9<count($explod9);$i9++) {
if ($realdat9==$explod9[$i9]) {
echo ' <img src="' . $siteurl . '/urgent.gif" alt="Urgent!" border=0>';
}
}
echo '</td>
<td bgcolor="' . $tablecolor1 . '"><small>';
$result2 = SQLact("query", "SELECT * FROM freelancers_bids WHERE project='" . $row[project] . "' AND status='open'");
$rows = SQLact("num_rows", $result2);
echo $rows;
echo '</td>
<td bgcolor="' . $tablecolor1 . '"><small>';
$archway = $row[categories];
$len=strlen($archway);
$boink=substr("$archway", 0, ($len-2));
echo $boink;
echo '</td>
<td bgcolor="' . $tablecolor1 . '"><small>' . $row[creation] . '</td>
<td bgcolor="' . $tablecolor1 . '"><small>';
$secondsPerDay = ((24 * 60) * 60);
$timeStamp = time();
$daysUntilExpiry = $row[expires];
$expiry = $timeStamp + ($daysUntilExpiry * $secondsPerDay);
$date  = getdate($expiry);
$month = $date["mon"];
$day   = $date["mday"];
$year  = $date["year"];
$expiryDate = $month . '/' . $day . '/' . $year;
echo $expiryDate;
echo '</td>
</tr>
';
}
echo '</table>';
} else {
$result = SQLact("query", "SELECT * FROM freelancers_projects WHERE status='open' ORDER BY date2 DESC LIMIT 0,10");
echo '<table border="' . $tableborder . '" cellspacing="' . $tablecellsp . '" cellpadding="' . $tablecellpa . '" width="100%">
<tr>
<td bgcolor="' . $tablecolorh . '"><small><b><font color=' . $tablecolort . '>Project Name</td>
<td bgcolor="' . $tablecolorh . '" width=30><small><b><font color=' . $tablecolort . '>Bids</td>
<td bgcolor="' . $tablecolorh . '"><small><b><font color=' . $tablecolort . '>' . $catname . '</td>
<td bgcolor="' . $tablecolorh . '" width=50><small><b><font color=' . $tablecolort . '>Started</td>
<td bgcolor="' . $tablecolorh . '" width=50><small><b><font color=' . $tablecolort . '>Ends</td>
</tr>
';
while ($row=SQLact("fetch_array", $result)) {
echo '<tr>
<td bgcolor="' . $tablecolor1 . '" valign=center><small><a href="' . $siteurl . '/project.php?id=' . $row[id] . '">' . $row[project] . '</a>';
if ($row[special] == "featured") {
echo ' <a href="' . $siteurl . '/featured.html"><img src="' . $siteurl . '/featured.gif" alt="Featured Project!" border=0></a>';
}
$secondsPerDay9 = ((24 * 60) * 60);
$timeStamp9 = time();
$daysUntilExpiry9 = $row[expires];
$getdat9 = $timeStamp9 + ($daysUntilExpiry9 * $secondsPerDay9);
$thedat9 = $getdat9-$timeStamp9;
$realdat9 = round($thedat9/((24 * 60) * 60));
$explod9 = explode('-', $projectudays);
for ($i9=0;$i9<count($explod9);$i9++) {
if ($realdat9==$explod9[$i9]) {
echo ' <img src="' . $siteurl . '/urgent.gif" alt="Urgent!" border=0>';
}
}
echo '</td>
<td bgcolor="' . $tablecolor1 . '"><small>';
$result2 = SQLact("query", "SELECT * FROM freelancers_bids WHERE project='" . $row[project] . "' AND status='open'");
$rows = SQLact("num_rows", $result2);
echo $rows;
echo '</td>
<td bgcolor="' . $tablecolor1 . '"><small>';
$archway = $row[categories];
$len=strlen($archway);
$boink=substr("$archway", 0, ($len-2));
echo $boink;
echo '</td>
<td bgcolor="' . $tablecolor1 . '"><small>' . $row[creation] . '</td>
<td bgcolor="' . $tablecolor1 . '"><small>';
$secondsPerDay = ((24 * 60) * 60);
$timeStamp = time();
$daysUntilExpiry = $row[expires];
$expiry = $timeStamp + ($daysUntilExpiry * $secondsPerDay);
$date  = getdate($expiry);
$month = $date["mon"];
$day   = $date["mday"];
$year  = $date["year"];
$expiryDate = $month . '/' . $day . '/' . $year;
echo $expiryDate;
echo '</td>
</tr>
';
}
echo '</table>';
}
?>