<? require("admheader.php"); ?>
<!-- Table menu -->
<table border="1" cellpadding="3" cellspacing="0" width="100%">
<tr>
 <td bgcolor="lightgrey">
  &nbsp; Validate ads 
</td>
</tr>

<tr bgcolor="white">
 <td width="100%">
  
 <p>
 <?
 if ($validation <> 1)
 {
      print " Validation is not activated in admin area <p>";
 }
 else
 {
 ?>
 From here you can approve/validate new ads. No ads will show up before they are approved from this
 admin screen. <p>
 You have now <b><?
 $sql_count = "select siteid from $ads_tbl, $cat_tbl where catid=sitecatid AND valid = '0'";
 $result = mysql_query ($sql_count);
 $ads_to_approve = mysql_num_rows($result);
 print $ads_to_approve;
 ?></b> ads to approve.
 <p><p>


 <?
 if ($del AND $siteid)
 {
  include("../functions.php");
  delete_ads("$siteid");
 }

 if ($val AND $siteid)
 {
    $sql_val = "update $ads_tbl set valid = 1 where siteid = $siteid";
    $result = mysql_query ($sql_val);
    print "<b>Ad with id $siteid is now validated</b><p>";

    $sql_select = "select * from $ads_tbl, $cat_tbl, $usr_tbl where $ads_tbl.ad_username = $usr_tbl.email AND catid=sitecatid
AND
siteid = $siteid";
    if ($debug) { print("$sql_select");}
    $result = mysql_query ($sql_select);

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
            $name = $row["name"];
            $email = $row["email"];
    }


require("config/mail.inc.php");
$val_msg = ereg_replace ("\{NAME\}", "$name", $val_msg);
$val_msg = ereg_replace ("\{AD_ID\}", "$siteid", $val_msg);
$val_msg = ereg_replace ("\{EMAIL\}", "$email", $val_msg);
$val_msg = ereg_replace ("\{TITLE\}", "$sitetitle", $val_msg);
$val_msg = ereg_replace ("\{CATNAME\}", "$catname", $val_msg);
$val_msg = ereg_replace ("\{URL\}", "$url", $val_msg);
$val_msg = ereg_replace ("\{SITENAME\}", "$name_of_site", $val_msg);

$sendto = "$email";
$from = "$from_adress";
$subject = "$val_ttl";
$message = "$val_msg";
$headers = "From: $from\r\n";


mail($sendto, $subject, $message, $headers);
 }


 ?>
<table border="0" cellpadding="2" cellspacing="2" width="100%">
<tr>
<td bgcolor="#E6E6E6"> <? echo $title ?> </td>
<td bgcolor="#E6E6E6"> <? echo $category ?> </td>
<td bgcolor="#E6E6E6"> <? echo $la_view ?> </td>
<td bgcolor="#E6E6E6"> <? echo $delete_button ?> </td>
<td bgcolor="#E6E6E6"> Approve? </td>
</tr>

<?


$sql_select = "select * from $ads_tbl, $cat_tbl where catid=sitecatid AND valid = '0' order by siteid desc limit 25";
if ($debug) { print("$sql_select");}
$result = mysql_query ($sql_select);

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
 print("<tr>");
 print("<td width='30%'> $sitetitle </td>");
 print("<td width='17%'> <a href='../index.php?kid=$sitecatid&catname=$catname' target=\"_blank\">$catname</a> </td>");
 print("<td width='17%'> <a href='../detail.php?annid=$siteid' target=\"_blank\"><u>View</u></a> </td>");
 print("<td width='17%'> <a href='validate.php?siteid=$siteid&del=1'><u>$delete_button</u></a> </td>");
 print("<td width='17%'> <a href='validate.php?siteid=$siteid&val=1'><u>Approve</u></a> </td>");
 print("</tr>");
}
print("</table>");
} // End if validation
?>
              <p>
         </td>
</tr>
</table>
<!-- END Table menu -->
<? require("admfooter.php"); ?>
