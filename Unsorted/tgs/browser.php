<?php
session_start();
ob_start();
include("includes/header.php");
include("includes/messages.php");
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
	<span class="error"><b>Browser</b></span>

<? if (isset($site)) { ?>

	<p>The following shows the breakdown of web browsers used to view your site.</p>

	<p>
	  <table width=550 cellspacing=0 cellpadding=4 class="table">
	  <thead>
	    <th>Browser</th>
	    <!--
	    <th>Percentage (%)</th>-->
	    <th>Visitors</th>
	  </thead>

	  <?
        $res = mysql_query("SELECT COUNT(DISTINCT ip) AS agent_count FROM stats WHERE site = $site");
	    $row = mysql_fetch_array($res);

	    $total_count = $row[agent_count];

	    $res2 = mysql_query("SELECT distinct description, agent, COUNT(DISTINCT ip) AS agent_count FROM stats INNER JOIN
	                   agentstrings ON agent=agentstring WHERE site = $site GROUP BY description ORDER BY agent_count DESC");

	    $res3 = mysql_query("SELECT distinct description, agent, COUNT(DISTINCT ip) AS agent_count FROM stats INNER JOIN
	                   agentstrings ON agent=agentstring WHERE site = $site GROUP BY description ORDER BY agent_count DESC");

	  $max = 0;

	  while (($row3 = mysql_fetch_array($res3)) != NULL) {
         if ($row3[agent_count] > $max) {
           $max = $row3[agent_count];
         }
       }

	  $temp_count = 0;

      while (($row2 = mysql_fetch_array($res2)) != NULL) {
        $temp_count += $row2[agent_count];

    ?>

    <tr>
      <td><?=$row2[description]?></td>
      <!--<td><img src="logger_bar.php?width=200&height=12&maximum=<?=$max?>&position=<?=$row2[agent_count]?>"></td>-->
      <td><?=$row2[agent_count]?></td>
    </tr>

    <?

      }

      if ($temp_count < $total_count)

      {
      	?>
    <tr>
      <td>Unknown</td>
      <!--<td><img src="logger_bar.php?width=200&height=12&maximum=<?=$max?>&position=<?=$total_count-$temp_count?>"></td>-->
      <td><?=$total_count-$temp_count?></td>
    </tr>

      	<?
      }
    ?>

   
    </p>

    <? } else { ?>

    <p>This page cannot be viewed directly, you must view it through a link on a sites page.</p>

    <? } ?>

<?php
include("includes/footer.php");
?>