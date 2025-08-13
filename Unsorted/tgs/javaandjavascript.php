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
?>
	<span class="error"><b>Javascript and Javascript Version</b></span>

	<? if (isset($site)) { ?>

		<p>The following list details the version capability of of the visitors browsers.</p>


	<table width=550 cellspacing=0 cellpadding=4 class="table">
	<thead>
	  <th>JavaScript Version</th>
	  <!--
	  <th>Percentage (%)</th>-->
	  <th>Visitors</th>
	</thead>
    <?
	  $res = mysql_query("SELECT COUNT(DISTINCT ip) AS jsver_count FROM stats WHERE site = $site");
	  $row = mysql_fetch_array($res);

	  $total_count = $row[jsver_count];

       $res3 = mysql_query("SELECT DISTINCT jsver, COUNT(DISTINCT ip) AS jsver_count FROM stats WHERE site = $site GROUP BY jsver ORDER BY jsver_count DESC");

	  $max = 0;

	  while (($row3 = mysql_fetch_array($res3)) != NULL) {
         if ($row3[jsver_count] > $max) {
           $max = $row3[jsver_count];
         }
       }

       $res2 = mysql_query("SELECT DISTINCT jsver, COUNT(DISTINCT ip) AS jsver_count FROM stats WHERE site = $site GROUP BY jsver ORDER BY jsver_count DESC");

      while (($row2 = mysql_fetch_array($res2)) != NULL) {

    ?>

    <tr>
      <td><?="JavaScript " . $row2[jsver]?></td>
      <!--<td><img src="logger_bar.php?width=200&height=12&maximum=<?=$max?>&position=<?=$row2[jsver_count]?>"></td>-->
      <td><?=$row2[jsver_count]?></td>
    </tr>

    <?
      }
    ?>

    <br>
    <p>The following shows the percentage of users who have JavaScript enabled on their browser.</p>
	<table width=550 cellspacing=0 cellpadding=4 class="table">
	<thead>
	  <th>JavaScript Enabled</th>
	  <!--
	  <th>Percentage (%)</th>-->
	  <th>Visitors</th>
	</thead>
    <?
	  $res = mysql_query("SELECT COUNT(DISTINCT ip) AS javaenabled_count FROM stats WHERE site = $site");
	  $row = mysql_fetch_array($res);

	  $total_count = $row[javaenabled_count];

       $res3 = mysql_query("SELECT DISTINCT javaenabled, COUNT(DISTINCT ip) AS javaenabled_count FROM stats WHERE site = $site GROUP BY javaenabled ORDER BY javaenabled_count DESC");

	  $max = 0;

	  while (($row3 = mysql_fetch_array($res3)) != NULL) {
         if ($row3[javaenabled_count] > $max) {
           $max = $row3[javaenabled_count];
         }
       }

       $res2 = mysql_query("SELECT DISTINCT javaenabled, COUNT(DISTINCT ip) AS javaenabled_count FROM stats WHERE site = $site GROUP BY javaenabled ORDER BY javaenabled_count DESC");

      while (($row2 = mysql_fetch_array($res2)) != NULL) {
      	if ($row2[javaenabled] == "true"){ $row2[javaenabled] = "Yes";}
      	else {$row[javaenabled] = "No";}
    ?>

    <tr>
      <td><?=$row2[javaenabled]?></td>
      <!--<td><img src="logger_bar.php?width=200&height=12&maximum=<?=$max?>&position=<?=$row2[javaenabled_count]?>"></td>-->
      <td><?=$row2[javaenabled_count]?></td>
    </tr>

    <?
      }
    ?>

    

    <? } else { ?>

    <p>This page cannot be viewed directly, you must view it through a link on a sites page.</p>

    <? } ?>

<?php
include("includes/footer.php");
?>