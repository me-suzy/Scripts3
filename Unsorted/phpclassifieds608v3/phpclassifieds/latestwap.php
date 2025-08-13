<?
session_start();
include_once("admin/config/header.inc.php");
include_once("admin/inc.php");
print "<script src=\"http://www.gelon.net/wapalizer.js\"></script>";


if ($validation == 1) { $val_string = " AND valid = 1"; }
if ($validation == 1) { $val_string2 = " where valid = 1"; }

$sql_links = "select siteid,sitetitle,sitedescription,datestamp from $ads_tbl $val_string2 order by siteid desc limit 30";
$sql_result = mysql_query ($sql_links);
$num_links =  mysql_num_rows($sql_result);
$file_pointer = fopen("wap/index.wml", "w");
fwrite($file_pointer,"<?xml version=\"1.0\"?> "
. "<!DOCTYPE wml PUBLIC \"-//WAPFORUM//DTD WML 1.1//EN\" \"http://www.wapforum.org/DTD/wml_1.1.xml\">"
. "<wml><card title='Latest ads'><table columns='1'>");
fclose($file_pointer);

for ($i=0; $i<$num_links; $i++)
{
      $row = mysql_fetch_array($sql_result);
      $siteid = $row["siteid"];
      $sitetitle = $row["sitetitle"];
      $sitedescription = $row["sitedescription"];
      $siteurl = $row["siteurl"];
      $sitedate = $row["sitedate"];
      $sitehits = $row["sitehits"];
      $datestamp = $row["datestamp"];
      $year=substr($row[datestamp],0,4);
      $month=substr($row[datestamp],4,2);
      $day=substr($row[datestamp],6,2);
      $sitedate1 = "$day.$month.$year";
      $fp=fopen("wap/index.wml", 'a');
      fwrite($fp,"\n<tr><td><a href=\"detail.wml#$siteid\">$sitetitle</a> $sitedate1</td></tr>");
      fclose($fp);
}

      $fi=fopen("wap/index.wml", 'a');
      fwrite($fi,"</table></card></wml>");
      fclose($fi);

$num_links =  mysql_num_rows($sql_result);


$sql_links = "select siteid,sitetitle,sitedescription,name,phone,email from $ads_tbl, $usr_tbl where ad_username = email $val_string order by siteid desc limit 10";
$sql_result = mysql_query ($sql_links);
$num_links =  mysql_num_rows($sql_result);

 for ($i=0; $i<$num_links; $i++)
 {
      $row = mysql_fetch_array($sql_result);
      $siteid = $row["siteid"];
      $sitetitle = $row["sitetitle"];
      $sitedescription = $row["sitedescription"];
      $siteurl = $row["siteurl"];
      $sitedate = $row["sitedate"];
      $sitehits = $row["sitehits"];
      $catname = $row["catname"];
      $name = $row["name"];
      $phone = $row["phone"];
      $email = $row["email"];
      $datestamp = $row["datestamp"];
      $year=substr($row[datestamp],0,4);
      $month=substr($row[datestamp],4,2);
      $day=substr($row[datestamp],6,2);
      $sitedate1 = "$day.$month.$year";

      $array[] = "<card title='$sitetitle' id='$siteid'>$sitetitle</br>$sitedescription</br>$name ( $phone $email )\n</card>";
}





// DETAILED PAGES
$start = "<?xml version=\"1.0\"?><!DOCTYPE wml PUBLIC \"-//WAPFORUM//DTD WML 1.1//EN\" \"http://www.wapforum.org/DTD/wml_1.1.xml\"><wml>";
$arrelm0 = $array[0];
$arrelm1 = $array[1];
$arrelm2 = $array[2];
$arrelm3 = $array[3];
$arrelm4 = $array[4];
$arrelm5 = $array[5];
$arrelm6 = $array[6];
$arrelm7 = $array[7];
$arrelm8 = $array[8];
$arrelm9 = $array[9];
$arrelm10 = $array[10];
$slutt= "</wml>";

$file_pointer = fopen("wap/detail.wml", "w");
fwrite($file_pointer,"$start $arrelm0 $arrelm1 $arrelm2 $arrelm3 $arrelm4 $arrelm5 $arrelm6 $arrelm7 $arrelm8 $arrelm9 $arrelm10 $slutt");
fclose($file_pointer);



// END OF DETAILED PAGES

if (!$special)
{
include("navigation.php");
//        print("$menu_ordinary<p>");
}
// print("<h2>$name_of_site</h2>");

?>
    
<table border="0" width="100%" cellspacing="0" cellpadding="10">
  <tr>
    <td width="100%">
          
<?

print("$la_wap<p><p>");
?>
<FORM onsubmit="if(validateForm())wap(this.url.value);return false;" name="wapalizerform">
<INPUT TYPE="TEXT" NAME="url" value="http://<? echo $url ?>/wap/index.wml">
<INPUT TYPE="submit" value="Wapalize">
</FORM>

   </td>
  </tr>
</table>


<?
require("admin/config/footer.inc.php");
?>