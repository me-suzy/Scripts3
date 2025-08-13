<?php
session_start();
ob_start();
include("includes/header.php");
include("includes/messages.php");
include("includes/validate_member.php");
ini_set("include_path", "./");

if($siteCode == "")
{
	header("Location:selectSite.php");
}

if($siteCode != "")
{
	$site = $siteCode;
}

if (isset($site))
  {
    // Count the number of visits each month

    $max = 0;

    $res = mysql_query("SELECT COUNT(DISTINCT ip) AS month_count FROM stats WHERE month = 1 AND site = $site");
    $row = mysql_fetch_array($res);

    if (($janurary = $row[month_count]) > $max) $max = $row[month_count];

    $res = mysql_query("SELECT COUNT(DISTINCT ip) AS month_count FROM stats WHERE month = 2 AND site = $site");
    $row = mysql_fetch_array($res);

    if (($february = $row[month_count]) > $max) $max = $row[month_count];

    $res = mysql_query("SELECT COUNT(DISTINCT ip) AS month_count FROM stats WHERE month = 3 AND site = $site");
    $row = mysql_fetch_array($res);

    if (($march = $row[month_count]) > $max) $max = $row[month_count];

    $res = mysql_query("SELECT COUNT(DISTINCT ip) AS month_count FROM stats WHERE month = 4 AND site = $site");
    $row = mysql_fetch_array($res);

    if (($april = $row[month_count]) > $max) $max = $row[month_count];

    $res = mysql_query("SELECT COUNT(DISTINCT ip) AS month_count FROM stats WHERE month = 5 AND site = $site");
    $row = mysql_fetch_array($res);

    if (($may = $row[month_count]) > $max) $max = $row[month_count];

    $res = mysql_query("SELECT COUNT(DISTINCT ip) AS month_count FROM stats WHERE month = 6 AND site = $site");
    $row = mysql_fetch_array($res);

    if (($june = $row[month_count]) > $max) $max = $row[month_count];

    $res = mysql_query("SELECT COUNT(DISTINCT ip) AS month_count FROM stats WHERE month = 7 AND site = $site");
    $row = mysql_fetch_array($res);

    if (($july = $row[month_count]) > $max) $max = $row[month_count];

    $res = mysql_query("SELECT COUNT(DISTINCT ip) AS month_count FROM stats WHERE month = 8 AND site = $site");
    $row = mysql_fetch_array($res);

    if (($august = $row[month_count]) > $max) $max = $row[month_count];

    $res = mysql_query("SELECT COUNT(DISTINCT ip) AS month_count FROM stats WHERE month = 9 AND site = $site");
    $row = mysql_fetch_array($res);

    if (($september = $row[month_count]) > $max) $max = $row[month_count];

    $res = mysql_query("SELECT COUNT(DISTINCT ip) AS month_count FROM stats WHERE month = 10 AND site = $site");
    $row = mysql_fetch_array($res);

    if (($october = $row[month_count]) > $max) $max = $row[month_count];

    $res = mysql_query("SELECT COUNT(DISTINCT ip) AS month_count FROM stats WHERE month = 11 AND site = $site");
    $row = mysql_fetch_array($res);

    if (($november = $row[month_count]) > $max) $max = $row[month_count];

    $res = mysql_query("SELECT COUNT(DISTINCT ip) AS month_count FROM stats WHERE month = 12 AND site = $site");
    $row = mysql_fetch_array($res);

    if (($december = $row[month_count]) > $max) $max = $row[month_count];

    $total=$janurary+$february+$march+$april+$may+$june+$july+$august+$september+$october+$november+$december;
  }
?>
<span class="error"><b>Site Statistics</b></span>
		<p>The following statistic provides a summary of the total number of hits to the site
	in the time since tracking commenced.</p>
	<p>

  	<table width=550 cellspacing=0 cellpadding=4 class="table">
  	<thead>
  	<th>Month</th>
  	<!--
  	<th>Percentage (%)</th>-->
  	<th>Visitors</th>
	</thead>
    <tr><td>January</td><!--<td><img src="logger_bar.php?width=350&height=12&maximum=<?=$max?>&maximum=<?=$max?>&position=<?=$janurary?>"></td>--><td><?=$janurary?></td></tr>
    <tr><td>February</td><!--<td><img src="logger_bar.php?width=350&height=12&maximum=<?=$max?>&position=<?=$janurary?>"></td>--><td><?=$february?></td></tr>
    <tr><td>March</td><!--<td><img src="logger_bar.php?width=350&height=12&maximum=<?=$max?>&position=<?=$march?>"></td>--><td><?=$march?></td></tr>
    <tr><td>April</td><!--<td><img src="logger_bar.php?width=350&height=12&maximum=<?=$max?>&position=<?=$april?>"></td>--><td><?=$april?></td></tr>
    <tr><td>May</td><!--<td><img src="logger_bar.php?width=350&height=12&maximum=<?=$max?>&position=<?=$may?>"></td>--><td><?=$may?></td></tr>
    <tr><td>June</td><!--<td><img src="logger_bar.php?width=350&height=12&maximum=<?=$max?>&position=<?=$june?>"></td>--><td><?=$june?></td></tr>
    <tr><td>July</td><!--<td><img src="logger_bar.php?width=350&height=12&maximum=<?=$max?>&position=<?=$july?>"></td>--><td><?=$july?></td></tr>
    <tr><td>August</td><!--<td><img src="logger_bar.php?width=350&height=12&maximum=<?=$max?>&position=<?=$august?>"></td>--><td><?=$august?></td></tr>
    <tr><td>September</td><!--<td><img src="logger_bar.php?width=350&height=12&maximum=<?=$max?>&position=<?=$september?>"></td>--><td><?=$september?></td></tr>
    <tr><td>October</td><!--<td><img src="logger_bar.php?width=350&height=12&maximum=<?=$max?>&position=<?=$october?>"></td>--><td><?=$october?></td></tr>
    <tr><td>November</td><!--<td><img src="logger_bar.php?width=350&height=12&maximum=<?=$max?>&position=<?=$november?>"></td>--><td><?=$november?></td></tr>
    <tr><td>December</td><!--<td><img src="logger_bar.php?width=350&height=12&maximum=<?=$max?>&position=<?=$december?>"></td>--><td><?=$december?></td></tr>


    </table>
       </p>





<?
  if (isset($site))
  {
    // Count the number of visits each month

    $total = 0;
    $max = 0;

    $res = mysql_query("SELECT COUNT(month) AS month_count FROM stats WHERE month = 1 AND site = $site");
    $row = mysql_fetch_array($res);

    if (($janurary = $row[month_count]) > $max) $max = $row[month_count];

    $res = mysql_query("SELECT COUNT(month) AS month_count FROM stats WHERE month = 2 AND site = $site");
    $row = mysql_fetch_array($res);

    if (($february = $row[month_count]) > $max) $max = $row[month_count];

    $res = mysql_query("SELECT COUNT(month) AS month_count FROM stats WHERE month = 3 AND site = $site");
    $row = mysql_fetch_array($res);

    if (($march = $row[month_count]) > $max) $max = $row[month_count];

    $res = mysql_query("SELECT COUNT(month) AS month_count FROM stats WHERE month = 4 AND site = $site");
    $row = mysql_fetch_array($res);

    if (($april = $row[month_count]) > $max) $max = $row[month_count];

    $res = mysql_query("SELECT COUNT(month) AS month_count FROM stats WHERE month = 5 AND site = $site");
    $row = mysql_fetch_array($res);

    if (($may = $row[month_count]) > $max) $max = $row[month_count];

    $res = mysql_query("SELECT COUNT(month) AS month_count FROM stats WHERE month = 6 AND site = $site");
    $row = mysql_fetch_array($res);

    if (($june = $row[month_count]) > $max) $max = $row[month_count];

    $res = mysql_query("SELECT COUNT(month) AS month_count FROM stats WHERE month = 7 AND site = $site");
    $row = mysql_fetch_array($res);

    if (($july = $row[month_count]) > $max) $max = $row[month_count];

    $res = mysql_query("SELECT COUNT(month) AS month_count FROM stats WHERE month = 8 AND site = $site");
    $row = mysql_fetch_array($res);

    if (($august = $row[month_count]) > $max) $max = $row[month_count];

    $res = mysql_query("SELECT COUNT(month) AS month_count FROM stats WHERE month = 9 AND site = $site");
    $row = mysql_fetch_array($res);

    if (($september = $row[month_count]) > $max) $max = $row[month_count];

    $res = mysql_query("SELECT COUNT(month) AS month_count FROM stats WHERE month = 10 AND site = $site");
    $row = mysql_fetch_array($res);

    if (($october = $row[month_count]) > $max) $max = $row[month_count];

    $res = mysql_query("SELECT COUNT(month) AS month_count FROM stats WHERE month = 11 AND site = $site");
    $row = mysql_fetch_array($res);

    if (($november = $row[month_count]) > $max) $max = $row[month_count];

    $res = mysql_query("SELECT COUNT(month) AS month_count FROM stats WHERE month = 12 AND site = $site");
    $row = mysql_fetch_array($res);

    if (($december = $row[month_count]) > $max) $max = $row[month_count];

    $total=$janurary+$february+$march+$april+$may+$june+$july+$august+$september+$october+$november+$december;
  }

?>

	<p>

  	<table width=550 cellspacing=0 cellpadding=4 class="table">
  	<thead>
  	<th>Month</th>
  	<!--
  	<th>Percentage (%)</th>-->
  	<th>Total Hits</th>
	</thead>
    <tr><td>January</td><!--<td><img src="logger_bar.php?width=350&height=12&maximum=<?=$max?>&maximum=<?=$max?>&position=<?=$janurary?>"></td>--><td><?=$janurary?></td></tr>
    <tr><td>February</td><!--<td><img src="logger_bar.php?width=350&height=12&maximum=<?=$max?>&position=<?=$janurary?>"></td>--><td><?=$february?></td></tr>
    <tr><td>March</td><!--<td><img src="logger_bar.php?width=350&height=12&maximum=<?=$max?>&position=<?=$march?>"></td>--><td><?=$march?></td></tr>
    <tr><td>April</td><!--<td><img src="logger_bar.php?width=350&height=12&maximum=<?=$max?>&position=<?=$april?>"></td>--><td><?=$april?></td></tr>
    <tr><td>May</td><!--<td><img src="logger_bar.php?width=350&height=12&maximum=<?=$max?>&position=<?=$may?>"></td>--><td><?=$may?></td></tr>
    <tr><td>June</td><!--<td><img src="logger_bar.php?width=350&height=12&maximum=<?=$max?>&position=<?=$june?>"></td>--><td><?=$june?></td></tr>
    <tr><td>July</td><!--<td><img src="logger_bar.php?width=350&height=12&maximum=<?=$max?>&position=<?=$july?>"></td>--><td><?=$july?></td></tr>
    <tr><td>August</td><!--<td><img src="logger_bar.php?width=350&height=12&maximum=<?=$max?>&position=<?=$august?>"></td>--><td><?=$august?></td></tr>
    <tr><td>September</td><!--<td><img src="logger_bar.php?width=350&height=12&maximum=<?=$max?>&position=<?=$september?>"></td>--><td><?=$september?></td></tr>
    <tr><td>October</td><!--<td><img src="logger_bar.php?width=350&height=12&maximum=<?=$max?>&position=<?=$october?>"></td>--><td><?=$october?></td></tr>
    <tr><td>November</td><!--<td><img src="logger_bar.php?width=350&height=12&maximum=<?=$max?>&position=<?=$november?>"></td>--><td><?=$november?></td></tr>
    <tr><td>December</td><!--<td><img src="logger_bar.php?width=350&height=12&maximum=<?=$max?>&position=<?=$december?>"></td>--><td><?=$december?></td></tr>
</p>

<?php
include("includes/footer.php");
?>