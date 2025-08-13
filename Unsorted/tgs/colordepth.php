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
	<span class="error"><b>Color Depth</b></span>

	<? if (isset($site)) { ?>

	<p>The following list shows the color depth of your browsers computers. This is useful
	in determining the most appropriate screen layout for your site.</p>
	<table width=550 cellspacing=0 cellpadding=4 class="table">
	<thead>
	  <th>Color Depth</th>
	  <!--<th>Percentage (%)</th>-->
	  <th>Visitors</th>
	</thead>
    <?
	  $res = mysql_query("SELECT COUNT(DISTINCT ip) AS colordepth_count FROM stats WHERE site = $site");
	  $row = mysql_fetch_array($res);

	  $total_count = $row[colordepth_count];

	  $res3 = mysql_query("SELECT DISTINCT colordepth, COUNT(DISTINCT ip) AS colordepth_count FROM stats WHERE site = $site GROUP BY colordepth ORDER BY colordepth_count DESC");

	  $max = 0;

	  while (($row3 = mysql_fetch_array($res3)) != NULL) {
         if ($row3[colordepth_count] > $max) {
           $max = $row3[colordepth_count];
         }
       }


      $res2 = mysql_query("SELECT DISTINCT colordepth, COUNT(DISTINCT ip) AS colordepth_count FROM stats WHERE site = $site GROUP BY colordepth ORDER BY colordepth_count DESC");

      while (($row2 = mysql_fetch_array($res2)) != NULL) {

      	if ($row2[colordepth] == "")
      	  $row2[colordepth] = "Could Not Detect";
      	else
      	  $row2[colordepth] = $row2[colordepth] ."-bit";

    ?>

    <tr>
      <td><?=$row2[colordepth]?></td>
      <!--<td><img src="logger_bar.php?width=200&height=12&maximum=<?=$max?>&position=<?=$row2[colordepth_count]?>"></td>-->
      <td><?=$row2[colordepth_count]?></td>
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