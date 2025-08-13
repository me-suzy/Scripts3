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

    $res = mysql_query("SELECT COUNT(DISTINCT ip) AS day_count FROM stats WHERE day = 0 AND site = $site");
    $row = mysql_fetch_array($res);

    if (($monday = $row[day_count]) > $max) $max = $row[day_count];

    $res = mysql_query("SELECT COUNT(DISTINCT ip) AS day_count FROM stats WHERE day = 1 AND site = $site");
    $row = mysql_fetch_array($res);

    if (($tuesday = $row[day_count]) > $max) $max = $row[day_count];

    $res = mysql_query("SELECT COUNT(DISTINCT ip) AS day_count FROM stats WHERE day = 2 AND site = $site");
    $row = mysql_fetch_array($res);

    if (($wednesday = $row[day_count]) > $max) $max = $row[day_count];

    $res = mysql_query("SELECT COUNT(DISTINCT ip) AS day_count FROM stats WHERE day = 3 AND site = $site");
    $row = mysql_fetch_array($res);

    if (($thursday = $row[day_count]) > $max) $max = $row[day_count];

    $res = mysql_query("SELECT COUNT(DISTINCT ip) AS day_count FROM stats WHERE day = 4 AND site = $site");
    $row = mysql_fetch_array($res);

    if (($friday = $row[day_count]) > $max) $max = $row[day_count];

    $res = mysql_query("SELECT COUNT(DISTINCT ip) AS day_count FROM stats WHERE day = 5 AND site = $site");
    $row = mysql_fetch_array($res);

    if (($saturday = $row[day_count]) > $max) $max = $row[day_count];

    $res = mysql_query("SELECT COUNT(DISTINCT ip) AS day_count FROM stats WHERE day = 6 AND site = $site");
    $row = mysql_fetch_array($res);

    if (($sunday = $row[day_count]) > $max) $max = $row[day_count];

    $total = $monday+$tuesday+$wednesday+$thursday+$friday+$saturday+$sunday;

  }

 ?>
 <span class="error"><b>Site Statistics</b></span>

 	<p>The following statistic provides a summary of the total number of hits to the site
 	in the time since tracking commenced.</p>

 	<p>

   	<table width=550 cellspacing=0 cellpadding=4 class="table">
 	<thead>
   	<th>Day</th>
   	<!--
   	<th>Percentage (%)</th>-->
   	<th>Visitors</th>
 	</thead>
     <tr><td>Sunday</td><!--<td><img src="logger_bar.php?width=350&maximum=<?=$max?>&height=12&position=<?=$sunday?>"></td>--><td><?=$sunday?></td></tr>
     <tr><td>Monday</td><!--<td><img src="logger_bar.php?width=350&maximum=<?=$max?>&height=12&position=<?=$monday?>"></td>--><td><?=$monday?></td></tr>
     <tr><td>Tuesday</td><!--<td><img src="logger_bar.php?width=350&maximum=<?=$max?>&height=12&position=<?=$tuesday?>"></td>--><td><?=$tuesday?></td></tr>
     <tr><td>Wednesday</td><!--<td><img src="logger_bar.php?width=350&maximum=<?=$max?>&height=12&position=<?=$wednesday?>"></td>--><td><?=$wednesday?></td></tr>
     <tr><td>Thursday</td><!--<td><img src="logger_bar.php?width=350&maximum=<?=$max?>&height=12&position=<?=$thursday?>"></td>--><td><?=$thursday?></td></tr>
     <tr><td>Friday</td><!--<td><img src="logger_bar.php?width=350&maximum=<?=$max?>&height=12&position=<?=$friday?>"></td>--><td><?=$friday?></td></tr>
     <tr><td>Saturday</td><!--<td><img src="logger_bar.php?width=350&maximum=<?=$max?>&height=12&position=<?=$saturday?>"></td>--><td><?=$saturday?></td></tr>


     </table>
        </p>



        <?
   if (isset($site))
   {
     // Count the number of visits each month

     $max = 0;

     $res = mysql_query("SELECT COUNT(day) AS day_count FROM stats WHERE day = 0 AND site = $site");
     $row = mysql_fetch_array($res);

     if (($monday = $row[day_count]) > $max) $max = $row[day_count];

     $res = mysql_query("SELECT COUNT(day) AS day_count FROM stats WHERE day = 1 AND site = $site");
     $row = mysql_fetch_array($res);

     if (($tuesday = $row[day_count]) > $max) $max = $row[day_count];

     $res = mysql_query("SELECT COUNT(day) AS day_count FROM stats WHERE day = 2 AND site = $site");
     $row = mysql_fetch_array($res);

     if (($wednesday = $row[day_count]) > $max) $max = $row[day_count];

     $res = mysql_query("SELECT COUNT(day) AS day_count FROM stats WHERE day = 3 AND site = $site");
     $row = mysql_fetch_array($res);

     if (($thursday = $row[day_count]) > $max) $max = $row[day_count];

     $res = mysql_query("SELECT COUNT(day) AS day_count FROM stats WHERE day = 4 AND site = $site");
     $row = mysql_fetch_array($res);

     if (($friday = $row[day_count]) > $max) $max = $row[day_count];

     $res = mysql_query("SELECT COUNT(day) AS day_count FROM stats WHERE day = 5 AND site = $site");
     $row = mysql_fetch_array($res);

     if (($saturday = $row[day_count]) > $max) $max = $row[day_count];

     $res = mysql_query("SELECT COUNT(day) AS day_count FROM stats WHERE day = 6 AND site = $site");
     $row = mysql_fetch_array($res);

     if (($sunday = $row[day_count]) > $max) $max = $row[day_count];

     $total = $monday+$tuesday+$wednesday+$thursday+$friday+$saturday+$sunday;

   }
 ?>
 	<p>

   	<table width=550 cellspacing=0 cellpadding=4 class="table">
 	<thead>
   	<th>Day</th>
   	<!--
   	<th>Percentage (%)</th>-->
   	<th>Total Hits</th>
 	</thead>
     <tr><td>Sunday</td><!--<td><img src="logger_bar.php?width=350&maximum=<?=$max?>&height=12&position=<?=$sunday?>"></td>--><td><?=$sunday?></td></tr>
     <tr><td>Monday</td><!--<td><img src="logger_bar.php?width=350&maximum=<?=$max?>&height=12&position=<?=$monday?>"></td>--><td><?=$monday?></td></tr>
     <tr><td>Tuesday</td><!--<td><img src="logger_bar.php?width=350&maximum=<?=$max?>&height=12&position=<?=$tuesday?>"></td>--><td><?=$tuesday?></td></tr>
     <tr><td>Wednesday</td><!--<td><img src="logger_bar.php?width=350&maximum=<?=$max?>&height=12&position=<?=$wednesday?>"></td>--><td><?=$wednesday?></td></tr>
     <tr><td>Thursday</td><!--<td><img src="logger_bar.php?width=350&maximum=<?=$max?>&height=12&position=<?=$thursday?>"></td>--><td><?=$thursday?></td></tr>
     <tr><td>Friday</td><!--<td><img src="logger_bar.php?width=350&maximum=<?=$max?>&height=12&position=<?=$friday?>"></td>--><td><?=$friday?></td></tr>
     <tr><td>Saturday</td><!--<td><img src="logger_bar.php?width=350&maximum=<?=$max?>&height=12&position=<?=$saturday?>"></td>--><td><?=$saturday?></td></tr>


     
</p>

<?php
include("includes/footer.php");
?>