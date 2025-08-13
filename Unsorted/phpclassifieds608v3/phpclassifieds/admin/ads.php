<? require("admheader.php"); ?>

<!-- Table menu -->
<table border="1" cellpadding="3" cellspacing="0" width="100%">
<tr>
	<td bgcolor="lightgrey">
     &nbsp; Ads admin 
	</td>
</tr>

<tr bgcolor="white">
	<td width="100%">
     
    <?
	print("$list_message");
    ?>
     
    <p />
    <form method="post" action="ads.php">
    <?php require("list_admin.php"); ?>
    <input type="submit" class="txt" value="<? echo $check ?>" />
    </form>
    <?
    if ($del AND $siteid)
    {
             include("../functions.php");
             delete_ads("$siteid");
    }
    ?>
    <table border="0" cellpadding="2" cellspacing="2" width="100%">
     <tr>
        <td bgcolor="#E6E6E6"> <? echo $title ?> </td>
       <td bgcolor="#E6E6E6"> <? echo $category ?> </td>
       <td bgcolor="#E6E6E6"> View </td>
       <td bgcolor="#E6E6E6"> Change </td>
       <td bgcolor="#E6E6E6"> <? echo $delete_button ?> </td>
    </tr>
    <?
    if (!$sitecatid)
    {
             $sitecatid = "%%";
             $limit = "limit 10";
    }

    $sql_select = "select siteid,sitetitle,sitedescription,sitedate,ad_username,sitecatid,sitehits,sitevotes, catid, catname from $ads_tbl, $cat_tbl where catid=sitecatid AND sitecatid like '$sitecatid' order by siteid desc $limit";
    $result = mysql_query ($sql_select);

    while ($row = mysql_fetch_array($result))
	{
		$siteid = $row["siteid"];
		$ad_username = $row["ad_username"];
		$sitetitle = $row["sitetitle"];
        $sitedescription = $row["sitedescription"];
        $siteurl = $row["siteurl"];
        $sitedate = $row["sitedate"];
        $sitecatid = $row["sitecatid"];
        $sitehits = $row["sitehits"];
        $sitevotes = $row["sitevotes"];
        $catid = $row["catid"];
        $catname = $row["catname"];

        print("<tr>");
        print("<td width='30%'> $sitetitle </td>");
        print("<td width='17%'> <a href='../index.php?kid=$sitecatid&amp;catname=$catname' target=\"_blank\">$catname</a> </td>");
        print("<td width='17%'> <a href='../detail.php?annid=$siteid' target=\"_blank\"><u>View</u></a> </td>");
		print("<td width='17%'> <a href='../add_ad.php?siteid=$siteid&amp;update_rq=1&amp;usr=$ad_username&amp;sess=1' target='new'><u>Change</u></a> </td>");
        print("<td width='17%'> <a href='ads.php?siteid=$siteid&amp;del=1'><u>$delete_button</u></a> </td>");
        print("</tr>");
	}
    print("</table>");
	?>
    <p />
	</td>
</tr>
</table>
<!-- END Table menu -->
<? require("admfooter.php"); ?>
