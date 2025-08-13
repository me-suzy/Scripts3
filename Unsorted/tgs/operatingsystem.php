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
	<span class="error"><b>Operating System</b></span>

	<? if (isset($site)) { ?>

		<p>The following list shows the operating systems your viewers are using.</p>

		<table width=550 cellspacing=0 cellpadding=4 class="table">
		<thead>
		  <th>Operating System</th>
		  <!--
		  <th>Percentage (%)</th>-->
		  <th>Visitors</th>
		</thead>
	    <?
		  $res = mysql_query("SELECT COUNT(DISTINCT ip) AS os_count FROM stats WHERE site = $site");
		  $row = mysql_fetch_array($res);

		  $total_count = $row[os_count];

		  $max = 0;

		  $res3 = mysql_query("SELECT DISTINCT os, COUNT(DISTINCT ip) AS os_count FROM stats WHERE site = $site GROUP BY os ORDER BY os_count DESC");

		  while (($row3 = mysql_fetch_array($res3)) != NULL) {
	         if ($row3[os_count] > $max) {
	           $max = $row3[os_count];
	         }
	       }
	      $res2 = mysql_query("SELECT DISTINCT os, COUNT(DISTINCT ip) AS os_count FROM stats WHERE site = $site GROUP BY os ORDER BY os_count DESC");

	      while (($row2 = mysql_fetch_array($res2)) != NULL) {
	      	    	      	$res4 = mysql_query("SELECT * FROM os WHERE id = '$row2[os]'");
			$row4 = mysql_fetch_array($res4);



	      	if ($row4 != NULL) {
	      	  $os = $row4[description];
	      	} else {
	      	  $os = "<b>Unknown: ".$row2[os]."</b>"; }

	    ?>

	    <tr>
	      <td><?=$os?></td>
	      <!--<td><img src="logger_bar.php?width=200&height=12&maximum=<?=$max?>&position=<?=$row2[os_count]?>"></td>-->
	      <td><?=$row2[os_count]?></td>
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