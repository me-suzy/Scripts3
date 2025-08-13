<?
session_start();
include_once("admin/config/header.inc.php");
include_once("admin/inc.php");

if (!$special_mode)
{ include("navigation.php"); }
// { print("$menu_ordinary<p />"); }
// print("<h2>$name_of_site</h2>");
include_once("member_header.php");
check_valid_user();
?>
<table border="0" cellspacing="1" width="100%">
<tr>
    <td width="100%" valign="top">

<b><? echo $la_yourads; ?></b><p />
<?
if ($deleteid)
{
          delete_ads($deleteid);
          print "$la_deleted";
          print "<p />";
}

if ($soldid)
{
          $sql_sold = "update $ads_tbl set sold = 1 where ad_username = '$valid_user' AND siteid= $soldid";
          $result = mysql_query($sql_sold);
          print "$la_sold_marked";
}

if ($unsoldid)
{
          $sql_sold = "update $ads_tbl set sold = 0 where ad_username = '$valid_user' AND siteid= $unsoldid";
          $result = mysql_query($sql_sold);
          print "$la_unsold_marked";
}

?>

    
    
<table border="0" cellpadding="2" cellspacing="2" width="100%">
<tr>
  <td bgcolor="#C8D3E5"><? echo $title ?></td>
  <td bgcolor="#C8D3E5"><? echo $category ?></td>
  <td bgcolor="#C8D3E5"><? echo $modify ?></td>
</tr>
<?
$sql_select = "select * from $cat_tbl, $ads_tbl, $usr_tbl where catid=sitecatid AND email = '$valid_user' AND ad_username = email";
$result = mysql_query ($sql_select);

if ($fileimg_upload)
{
	$fn = "upload_file.php";
}
else 
{
	$fn = "upload_new.php";	
}

while ($row = mysql_fetch_array($result))
{
 $siteid = $row["siteid"];
 $sitetitle = $row["sitetitle"];
 $sitedescription = $row["sitedescription"];
 $siteurl = $row["siteurl"];
 $sitedate = $row["sitedate"];
 $sitecatid = $row["sitecatid"];
 $sitehits = $row["sitehits"];
 $sitevotes = $row["sitevotes"];
 $catid = $row["catid"];
 $catname = $row["catname"];
 $sold = $row["sold"];

print "<tr>";
print "<td width='40%'>$sitetitle</td>";
print "<td width='30%'>$catname</td>";
print "<td width='30%' nowrap><a href='add_ad.php?siteid=$siteid&amp;update_rq=1'><u>$la_ad</u></a>";
print " | <a href='$fn?pictures_siteid=$siteid'><u>$la_images</u></a>";
if ($sold <> 1)
{
	print " | <a href='ads.php?soldid=$siteid'><u>$la_sold</u></a>";
}
elseif ($sold == 1)
{
	print " | <a href='ads.php?unsoldid=$siteid'><u>$la_unsold</u></a>";
}
print " | <a href='ads.php?deleteid=$siteid'><u>$la_del</u></a>";
print "</td>";
print "</tr>";
}
print "</table></td></tr></table>";
include_once("member_footer.php");
include_once("admin/config/footer.inc.php");
?>