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
if ($showstatus == "open") {
?>
<b><big>Project Search Results</big></b><br><small>
Status: open<br>
</small>
<p>
<table width="100%" border="<? echo $tableborder; ?>" cellspacing="<? echo $tablecellsp; ?>" cellpadding="<? echo $tablecellpa; ?>">
<tr>
<td bgcolor="<? echo $tablecolorh; ?>" width=150><b><font color=<? echo $tablecolort; ?>>Project Name</td>
<td bgcolor="<? echo $tablecolorh; ?>"><b><font color=<? echo $tablecolort; ?>>Job Type</td>
<td bgcolor="<? echo $tablecolorh; ?>"><b><font color=<? echo $tablecolort; ?>>Bids</td>
<td bgcolor="<? echo $tablecolorh; ?>"><b><font color=<? echo $tablecolort; ?>>Status</td>
<td bgcolor="<? echo $tablecolorh; ?>"><b><font color=<? echo $tablecolort; ?>>Start Date</td>
</tr>
<?php
$result = SQLact("query", "SELECT * FROM freelancers_projects WHERE status='open' ORDER BY date2 DESC");
$rows = SQLact("num_rows", $result);
if ($rows==0) {
echo '<tr>
<td bgcolor="' . $tablecolor1 . '" colspan="5" align="center">(No open projects found.)</td>
</tr>';
} else {
while ($row=SQLact("fetch_array", $result)) {
?>
<tr>
<td bgcolor="<? echo $tablecolor1; ?>"><a href="<? echo $siteurl; ?>/project.php?id=<? echo $row[id]; ?>"><? echo $row[project]; ?></a><?
if ($row[special] == "featured") {
echo ' <a href="' . $siteurl . '/featured.html"><img src="' . $siteurl . '/featured.gif" alt="Featured Project!" border=0></a>';
} else {}
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
?></td>
<td bgcolor="<? echo $tablecolor1; ?>"><?php
$archway = $row[categories];
$len=strlen($archway);
$boink=substr("$archway", 0, ($len-2));
echo $boink;
?></td>
<td bgcolor="<? echo $tablecolor1; ?>"><?
$result2 = SQLact("query", "SELECT * FROM freelancers_bids WHERE id='" . $row[id] . "'");
$rows2 = SQLact("num_rows", $result2);
echo $rows2;
?></td>
<td bgcolor="<? echo $tablecolor1; ?>"><font color=green><b>open</td>
<td bgcolor="<? echo $tablecolor1; ?>"><? echo $row[creation]; ?></td>
</tr>
<?php
}
}
?>
</table>
<small><? echo $rows; ?> project<?
if ($rows!==1) {
echo 's';
} else {}
?> found.</small><p>
<small><a href="<? echo $siteurl; ?>/search.php">Perform Another Search...</a></small><p>
<?php
} else {
if (!$submit) {
?>
<center>
<big><b>Search Projects</b></big>
<p>
<form method="POST" action="search.php">
<div align="center"><center>
<b>Status:</b>
<select name="status" size="1">
<option selected value="">Any</option>
<option value="open">Open</option>
<option value="frozen">Frozen</option>
<option value="closed">Closed</option>
</select>
<input type="submit" value="Search" name="submit">
<p>
<b>Keywords:</b> <input type="text" name="keywords" value="" size="20">
<p>
<table>
<tr><td>
<b>Job Type:</b><br>
<?php
$selectcats = SQLact("query", "SELECT * FROM freelancers_cats");
$i=0;
while ($row=SQLact("fetch_array", $selectcats)) {
$i++;
?>
<input type="checkbox" name="category[]" value="<? echo $row[categories]; ?>"> <? echo $row[categories]; ?><br>
<?php
}
?>
</td></tr>
</table>
  </center></div>
</form>
<?php
} else {
if (is_array($category)) {
$query = "SELECT * FROM freelancers_projects WHERE categories LIKE '";
foreach ($category as $key=>$val) {
$query .= "%" . $val . "%";
}
$query .= "' AND ";
} else {
$query = "SELECT * FROM freelancers_projects WHERE ";
}
if ($status == "") {
$query .= "project LIKE '%$keywords%' ORDER BY date2 DESC";
} else {
$query .= "status LIKE '$status' AND project LIKE '%$keywords%' ORDER BY date2 DESC";
}
$lolres = SQLact("query", $query);
$rows = SQLact("num_rows", $lolres);
?>
<b><big>Project Search Results</big></b><br><small>
Status: <?
if ($status == "") {
echo "all";
} else {
echo $status;
}
?><br>
</small>
<p>
<table width="100%" border="<? echo $tableborder; ?>" cellspacing="<? echo $tablecellsp; ?>" cellpadding="<? echo $tablecellpa; ?>">
<tr>
<td bgcolor="<? echo $tablecolorh; ?>" width=150><b><font color=<? echo $tablecolort; ?>>Project Name</td>
<td bgcolor="<? echo $tablecolorh; ?>"><b><font color=<? echo $tablecolort; ?>>Job Type</td>
<td bgcolor="<? echo $tablecolorh; ?>"><b><font color=<? echo $tablecolort; ?>>Bids</td>
<td bgcolor="<? echo $tablecolorh; ?>"><b><font color=<? echo $tablecolort; ?>>Status</td>
<td bgcolor="<? echo $tablecolorh; ?>"><b><font color=<? echo $tablecolort; ?>>Start Date</td>
</tr>
<?php
if ($rows!==0) {
while ($row=SQLact("fetch_array", $lolres)) {
?>
<tr>
<td bgcolor="<? echo $tablecolor1; ?>"><a href="<? echo $siteurl; ?>/project.php?id=<? echo $row[id]; ?>"><? echo $row[project]; ?></a><?
if ($row[special] == "featured") {
echo ' <a href="' . $siteurl . '/featured.html"><img src="' . $siteurl . '/featured.gif" alt="Featured Project!" border=0>';
} else {}
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
?></a></td>
<td bgcolor="<? echo $tablecolor1; ?>"><?php
$archway = $row[categories];
$len=strlen($archway);
$boink=substr("$archway", 0, ($len-2));
echo $boink;
?></td>
<td bgcolor="<? echo $tablecolor1; ?>"><?
$y4res = SQLact("query", "SELECT * FROM freelancers_bids WHERE id='" . $row[id] . "'");
echo SQLact("num_rows", $y4res);
?></td>
<td bgcolor="<? echo $tablecolor1; ?>"><?
if ($row[status] == "open") {
echo '<font color="green"><b>' . $row[status] . '</font>';
} else if ($row[status] == "frozen") {
echo '<font color="blue"><b>' . $row[status] . '</font>';
} else {
echo '<font color="red"><b>closed</font>';
}?></td>
<td bgcolor="<? echo $tablecolor1; ?>"><? echo $row[creation]; ?></td>
</tr>
<?php
}
} else {
echo '<tr>
<td bgcolor="' . $tablecolor1 . '" colspan="5" align="center">(No projects found.)</td>
</tr>';
}
?>
</table>
<small><? echo $rows; ?> project<?
if ($rows!==1) {
echo 's';
} else {}
?> found.</small><p>
<small><a href="<? echo $siteurl; ?>/search.php">Perform Another Search...</a></small><p>
<?php
}
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