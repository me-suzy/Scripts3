<?php
session_start();
ob_start();
include("includes/header.php");
include("includes/messages.php");
include("includes/validate_member.php");
ini_set("include_path", "./");

if($site != "")
{
	$siteCode = $site;
}

if($siteCode == "")
{
	header("Location:selectSite.php");
}

if($siteCode != "")
{
	$site = $siteCode;
}
if (!isset($period)) { $period = 30; }

if (isset($site) && isset($period))
{
// Count the total number of hits to the site

$total_hits = 0;

$st = "SELECT COUNT(ip) as total_hits FROM stats WHERE site = $site";
$res = mysql_query($st) or die(mysql_error());
$row = mysql_fetch_array($res);

$total_hits = $row[total_hits];

// Count the number of unique IP addresses to visit the site

$total_visitors = 0;

$res = mysql_query("SELECT COUNT(DISTINCT ip) as total_visitors FROM stats WHERE site = $site");
$row = mysql_fetch_array($res);

$total_visitors = $row[total_visitors];

// Calculate average number of visitors per day

$total = 0;
$count = 0;

$res = mysql_query("SELECT date, COUNT(DISTINCT ip) as ip_count FROM stats WHERE site = $site GROUP BY date");

while (($row = mysql_fetch_array($res)) != NULL) {
  $total += $row[ip_count];
  $count++;
}

if ($count > 0)
  $average_per_date = $total/$count;
else
  $average_per_date = 0;

// Calculate total visitors for time period

$total_hits_period = 0;

$res = mysql_query("SELECT COUNT(ip) as total_hits FROM stats WHERE site = $site AND date >= '" . date("Ymd") . "' - $period + 1");
$row = mysql_fetch_array($res);

$total_hits_period = $row[total_hits];
}
?>
<script>
function jumpto(x){

if (document.form1.jumpmenu.value != "null") {
	document.location.href = x
	}
}
</script>

</head>

<body>
<span class="error"><b>Site Statistics</b></span>

<p>The following statistics provides a general overview of your sites performance.</p>

<? if (isset($site)) { ?>

<form name="form1">
<select name="jumpmenu" onChange="jumpto(document.form1.jumpmenu.options[document.form1.jumpmenu.options.selectedIndex].value)">
  <option>Select Time Period</option>
  <option value=?site=<?=$site?>&period=30>30 Days</option>
  <option value=?site=<?=$site?>&period=60>60 Days</option>
  <option value=?site=<?=$site?>&period=90>90 Days</option>
  <option value=?site=<?=$site?>&period=180>180 Days</option>
  <option value=?site=<?=$site?>&period=365>365 Days</option>
</select>
</form>
<br>
<p>
<table width=200 cellspacing=0 cellpadding=4 class="table">
<thead>
<th colspan=2>Summary</th>
</thead>
<tr class=text2>
<td>Total Hits:</td>
<td><?=$total_hits?></td>
</tr>
<tr class=text2>
<td>Total Visitors:</td>
<td><?=$total_visitors?></td>
</tr>
<tr class=text2>
<td nowrap >Average Visitors Per Day:</td>
<td><?=$average_per_date?></td>
</tr>
<tr class=text2>
<td nowrap >Total Hits For Last <?=$period?> Days:</td>
<td><?=$total_hits_period?></td>
</tr>

<?
}
?>

</table>
</p>

<p>This list details the total number of hits to your site for each of the last <?=$period?> days.</p>
<p>
<table width=550 cellspacing=0 cellpadding=4 class="table">
<thead>
<th>Date</th>
<!--<th>Percentage (%)</th>-->
<th>Visitors</th>
</thead>

<?

$res = mysql_query("SELECT COUNT(DISTINCT ip) as ip_count FROM stats WHERE site = $site AND date >= '" . date("Ymd") . "' - $period + 1");
$row = mysql_fetch_array($res);

$total_count = $row[ip_count];

$res2 = mysql_query("SELECT date, COUNT(DISTINCT ip) as ip_count FROM stats WHERE site = $site AND date >= '" . date("Ymd") . "' - $period + 1 GROUP BY date ORDER BY date DESC");
$max = 0;
while (($row = mysql_fetch_array($res2)) != NULL) {
if ($row[ip_count] > $max)
$max = $row[ip_count];
}
$st = "SELECT date, COUNT(DISTINCT ip) as ip_count FROM stats WHERE site = $site AND date >= '" . date("Ymd") . "' - $period + 1  GROUP BY date ORDER BY date DESC";
$res = mysql_query($st) or die(mysql_error());
while (($row = mysql_fetch_array($res)) != NULL) {
?>
<tr class=text2>
<td>
<?for($i=0;$i<10;$i++)echo $row[date][$i];?>&nbsp;&nbsp;&nbsp;
</td>
<!--
<td>
<img src="logger_bar.php?width=380&height=12&maximum=<?=$max?>&position=<?=$row[ip_count]?>">
</td>-->
<td>
<?=$row['ip_count']?>
</td>
</tr>

<?
}
?>

</table>
</p>

<p>
<table width=550 cellspacing=0 cellpadding=4 class="table">
<thead>
<th>Date</th>
<!--
<th>Percentage (%)</th>
-->
<th nowrap>Total Hits</th>
</thead>

<?

$res = mysql_query("SELECT COUNT(ip) as ip_count FROM stats WHERE site = $site AND date >= '" . date("Ymd") . "' - $period + 1");
$row = mysql_fetch_array($res);

$total_count = $row[ip_count];

$res2 = mysql_query("SELECT date, COUNT(ip) as ip_count FROM stats WHERE site = $site GROUP BY date ORDER BY date DESC");

$max = 0;

while (($row = mysql_fetch_array($res2)) != NULL) {
if ($row[ip_count] > $max)
$max = $row[ip_count];
}
$st = "SELECT date, COUNT(ip) as ip_count FROM stats WHERE site = $site GROUP BY date ORDER BY date DESC";
$res = mysql_query($st);
while (($row = mysql_fetch_array($res)) != NULL) {
?>
<tr class=text2>
<td nowrap>
<?for($i=0;$i<10;$i++)echo $row[date][$i];?>&nbsp;&nbsp;&nbsp;
</td>
<!--
<td>
<img src="logger_bar.php?width=380&height=12&maximum=<?=$max?>&position=<?=$row[ip_count]?>">
</td>
-->
<td>
<?=$row[ip_count]?>
</td>
</tr>

<?
}
?>

</p>

<?php
include("includes/footer.php");
?>