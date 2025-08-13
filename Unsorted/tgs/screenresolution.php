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
	<span class="error"><b>Screen Resolution</b></span>

		<? if (isset($site)) { ?>

		<p>The following list shows the screen resolution of your browsers computers. This is useful
		in determining the most appropriate screen layout for your site.</p>

		<table width=550 cellspacing=0 cellpadding=4 class="table">
		<thead>
		  <th>Screen Resolution</th>
		  <!--<th>Percentage (%)</th>-->
		  <th>Visitors</th>
		</thead>
	    <?
		  $res = mysql_query("SELECT COUNT(DISTINCT ip) AS resolution_count FROM stats WHERE site = $site");
		  $row = mysql_fetch_array($res);

		  $total_count = $row[resolution_count];
		$res3 = mysql_query("SELECT DISTINCT resolution, COUNT(DISTINCT ip) AS resolution_count FROM stats WHERE site = $site GROUP BY resolution ORDER BY resolution_count DESC");

		  $max = 0;

		  while (($row3 = mysql_fetch_array($res3)) != NULL) {
	         if ($row3[resolution_count] > $max) {
	           $max = $row3[resolution_count];
	         }
	       }
	      $res2 = mysql_query("SELECT DISTINCT resolution, COUNT(DISTINCT ip) AS resolution_count FROM stats WHERE site = $site GROUP BY resolution ORDER BY resolution_count DESC");

	      while (($row2 = mysql_fetch_array($res2)) != NULL) {
	      	if ($row2[resolution] == "")
	      	  $row2[resolution] = "Could Not Detect";
	    ?>

	    <tr>
	      <td><?=$row2[resolution]?></td>
	      <!--<td><img src="logger_bar.php?width=200&height=12&maximum=<?=$max?>&position=<?=$row2[resolution_count]?>"></td>-->
	      <td><?=$row2[resolution_count]?></td>
	    </tr>

	    <?
	      }
	    ?>

	    

	    <? } ?>

<?php
include("includes/footer.php");
?>