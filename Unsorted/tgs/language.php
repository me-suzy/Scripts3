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
	<span class="error"><b>Languages</b></span>

<? if (isset($site)) { ?>
	<p>The following shows the breakdown of the languages used by your site viewers.</p>
	<table width=550 cellspacing=0 cellpadding=4 class="table">
	<thead>
	  <th>Language</th>
	  <!--<th>Percentage (%)</th>-->
	  <th>Visitors</th>
	</thead>
    <?
	  $res = mysql_query("SELECT COUNT(DISTINCT ip) AS language_count FROM stats WHERE site = $site");
	  $row = mysql_fetch_array($res);

	  $total_count = $row[language_count];

	  $res3 = mysql_query("SELECT DISTINCT language, COUNT(DISTINCT ip) AS language_count FROM stats WHERE site = $site GROUP BY language ORDER BY language_count DESC");

	  $max = 0;

	  while (($row3 = mysql_fetch_array($res3)) != NULL) {
         if ($row3[language_count] > $max) {
           $max = $row3[language_count];
         }
       }

      $res2 = mysql_query("SELECT DISTINCT language, COUNT(DISTINCT ip) AS language_count FROM stats WHERE site = $site GROUP BY language ORDER BY language_count DESC");

      while (($row2 = mysql_fetch_array($res2)) != NULL) {

      	$res4 = mysql_query("SELECT * FROM languages WHERE id = '$row2[language]'");
		$row4 = mysql_fetch_array($res4);
      	if ($row4 != NULL) {
      	  $language = $row4[language];
      	} else {
      	  $language = "<b>Unknown: $row2[language]</b>"; }

    ?>

    <tr>
      <td><?=$language?></td>
      <!--<td><img src="logger_bar.php?width=350&height=12&maximum=<?=$max?>&position=<?=$row2[language_count]?>"></td>-->
      <td><?=$row2[language_count]?></td>
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