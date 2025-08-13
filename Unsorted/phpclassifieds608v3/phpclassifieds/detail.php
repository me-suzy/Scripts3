<?
session_start( );

if (!$siteid)
{
        $siteid = $annid;
}
include_once("admin/config/header.inc.php");
include_once("admin/inc.php");
require("functions.php");

$sql_links = "select * from $ads_tbl, $cat_tbl, $usr_tbl where catid=sitecatid AND ad_username = $usr_tbl.email AND siteid=$siteid";

$sql_result = mysql_query ($sql_links);
$num_links =  mysql_num_rows($sql_result);

for ($i=0; $i<$num_links; $i++)
{
        $row = mysql_fetch_array($sql_result);
        $siteid = $row["siteid"];
        $sitetitle = $row["sitetitle"];
        $sitedescription = $row["sitedescription"];
        $userid = $row["userid"];
        $sitedate = $row["sitedate"];
        $sitehits = $row["sitehits"];
        $sitevotes = $row["sitevotes"];
        $name = $row["name"];
        $phone = $row["phone"];
        $email = $row["email"];
        $sitevotes = $row["sitevotes"];
        $adressfield1 = $row["adressfield1"];
        $adressfield2 = $row["adressfield2"];
        $catname = $row["catname"];
        $catid = $row["catid"];
        $kid = $row["catid"];
        $custom_field_1 = $row["custom_field_1"];
        $custom_field_2 = $row["custom_field_2"];
        $custom_field_3 = $row["custom_field_3"];
        $custom_field_4 = $row["custom_field_4"];
        $custom_field_5 = $row["custom_field_5"];
        $custom_field_6 = $row["custom_field_6"];
        $custom_field_7 = $row["custom_field_7"];
        $custom_field_8 = $row["custom_field_8"];
        $usr_1 = $row["usr_1"];
        $usr_2 = $row["usr_2"];
        $usr_3 = $row["usr_3"];
        $usr_4 = $row["usr_4"];
        $usr_5 = $row["usr_5"];
		$sold = $row["sold"];
        $cattpl = $row["cattpl"];
        $datestamp = $row["datestamp"];
        $picture = $row["picture"];
        $hide_email = $row["hide_email"];
        $img_stored = $row["img_stored"];
        $expire_days = $row["expire_days"];
        $expire_ad = $row["expiredate"];
        $year=substr($row[datestamp],0,4);
        $month=substr($row[datestamp],4,2);
        $day=substr($row[datestamp],6,2);
        $sitedate1 = "$day.$month.$year";

        while ($counter < 15)
        {
                $counter = $counter + 1;
                $fieldnumber = "f" . $counter;
                $fieldid[$counter] = $row["$fieldnumber"];
        }
        
        if ($expire_days_option)
        {
        	find ("$year", "$month", "$day", "$delete_after_x_days");
        }
        else 
        {
        	find ("$year", "$month", "$day", "$expire_days");
        }
        
        if (!$special_mode) 
        { include("navigation.php"); }
//        { print("$menu_ordinary"); }

?>
    
<? require("link_title.php"); ?>
<!-- 1 -->
<table border="0" width="100%" bgcolor="#A9B8D1" cellspacing="0" cellpadding="0">
  <tr>
    <td width="100%" height="1"><img border="0" src="images/spacerbig.gif" width="1" height="1" /></td>
  </tr>
</table>        
<!-- // 1 -->        
        <script language="JavaScript">
        function openWin(URL) { aWindow=window.open(URL,"Large","toolbar=no,width=400,height=300,status=no,scrollbars=no,resize=no,menubars=no");
        }
        function openWin2(URL) {
aWindow=window.open(URL,"Large","toolbar=no,width=400,height=350,status=no,scrollbars=no,resize=no,menubars=no");
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         }
        </script>

<!-- 1 -->
<table border="0" width="100%" cellspacing="0" cellpadding="10">
  <tr>
    <td>
        
    
    
    
    <!-- 2 -->
	<table border="0" width="100%" cellspacing="0" cellpadding="0">
  	<tr>
    	<td>


        <span class="title"><? echo $sitetitle ?></span><br /><br />
        <?
        if ($sold == 1)
        {
        	print " <b>$la_sold</b> ";
        }
		?>

		<!-- 3 -->
		<table border="0" width="100%" cellspacing="0" cellpadding="0"><tr><td width="100%">
		
			<!-- 4 -->
			<table border="0" width="100%" cellspacing="0" cellpadding="0"><tr><td width="100%" valign="top" align="left">
		
				<!-- 5 -->
				<table border="0" width="100%" cellspacing="0" cellpadding="0"><tr><td valign="top" align="left">

                    
<?


// Setting
// if ($picture>0)
// {
// 	print "</tr></table></td></tr></table>";
// }
// else 
// {
// 	$colsp = "colspan=2";
// }
// 
if ($nl2br == 1)
{
$sitedescription = nl2br($sitedescription);
}
//  <td < ? echo $colsp ? > valign='top' width='100%'>
?>


 <!-- 6 -->
 <table border="0" bgcolor="#A9B8D1" cellspacing="0" cellpadding="1" width="100%"><tr><td>
 
 	<!-- 7 -->
 	<table border="0" bgcolor="#FFFFFF" cellspacing="0" cellpadding="10" width="100%"><tr><td valign="top" align="left">
  	<b><? print(" $description ");?></b><br /><? print(" $sitedescription "); ?>
  	</td></tr>
  	</table>
  	<!-- // 7 -->
  	
  	</td></tr>
 </table>
 <!-- // 6 -->
  
       
    <!-- 6 -->   
	<table border="0" cellpadding="1" cellspacing="1" width="90%">
  	<tr><td>
		<br />
    </td>
    
    <td></td>
  	</tr>
        <tr>
                <td colspan="2"><b> <? echo $general_details ?></b> </td>
        </tr>

  <tr>
    <td> <? echo $sold_by_text?></td>
    <td> <? echo $name ?></td>
  </tr>
  <tr>
    <td> <? echo $add_user_email ?></td>
    <td> 
<?

if ($email)
{
        if ($hide_email == 1)
        {
                print "<a href='contact.php?siteid=$siteid'>$la_contact_sale</a>";
        }
        else
        {
                print "<a href='mailto:$email'>$email</a>";
        }
}
else
{
                print $la_call;
}
?>

</td>
  </tr>
<?
if ($adressfield1 OR $adressfield2)
{
    print("<tr>");
    print("<td> $location_text</td>");
    print("<td> $adressfield1, $adressfield2</td>");
    print("</tr>");
}

if ($phone)
{
    print("<tr>");
    print("<td> $add_user_phone</td>");
    print("<td> $phone</td>");
    print("</tr>");
}
if ($usr_1_text AND $usr_1)
{
    print("<tr>");
    print("<td> $usr_1_text</td>");
    if ($usr_1_link) { print "<td><a href='$usr_1' target='_blank'>$la_link</a></td>"; }
    else {
    print("<td> $usr_1</td>");
    }
    print("</tr>");
}

if ($usr_2_text AND $usr_2)
{
    print("<tr>");
    print("<td> $usr_2_text</td>");
    if ($usr_2_link) { print "<td><a href='$usr_2' target='_blank'>$la_link</a></td>"; }
    else {
    print("<td> $usr_2</td>");
    }
    print("</tr>");
}

if ($usr_3_text AND $usr_3)
{
    print("<tr>");
    print("<td> $usr_3_text</td>");
    if ($usr_3_link) { print "<td><a href='$usr_3' target='_blank'>$la_link</a></td>"; }
    else {
    print("<td> $usr_3</td>");
    }
    print("</tr>");
}

if ($usr_4_text AND $usr_4)
{
    print("<tr>");
    print("<td> $usr_4_text</td>");
    if ($usr_4_link) { print "<td><a href='$usr_4' target='_blank'>$la_link</a></td>"; }
    else {
    print("<td> $usr_4</td>");
    }
    print("</tr>");
}

if ($usr_5_text AND $usr_5)
{
    print("<tr>");
    print("<td> $usr_5_text</td>");
    if ($usr_5_link) { print "<td><a href='$usr_5' target='_blank'>$la_link</a></td>"; }
    else {
    print("<td> $usr_5</td>");
    }
    print("</tr>");
}

if ($custom_field_1_text)
{
    print("<tr>");
    print("<td> $custom_field_1_text</td>");
    print("<td> $custom_field_1</td>");
    print("</tr>");

}

if ($custom_field_2_text)
{
    print("<tr>");
    print("<td> $custom_field_2_text </td>");
    print("<td> $custom_field_2 </td>");
    print("</tr>");

}

if ($custom_field_3_text)
{
    print("<tr>");
    print("<td> $custom_field_3_text </td>");
    print("<td> $custom_field_3 </td>");
    print("</tr>");

}
if ($custom_field_4_text)
{
    print("<tr>");
    print("<td> $custom_field_4_text </td>");
    print("<td> $custom_field_4 </td>");
    print("</tr>");

}
if ($custom_field_5_text)
{
    print("<tr>");
    print("<td> $custom_field_5_text </td>");
    print("<td> $custom_field_5 </td>");
    print("</tr>");

}
if ($custom_field_6_text)
{
    print("<tr>");
    print("<td> $custom_field_6_text </td>");
    print("<td> $custom_field_6 </td>");
    print("</tr>");

}
if ($custom_field_7_text)
{
    print("<tr>");
    print("<td> $custom_field_7_text </td>");
    print("<td> $custom_field_7 </td>");
    print("</tr>");

}
if ($custom_field_8_text)
{
    print("<tr>");
    print("<td> $custom_field_8_text </td>");
    print("<td> $custom_field_8 </td>");
    print("</tr>");
}


$string = "select * from template where name = '$cattpl'";
$result = mysql_query ($string);
$row = mysql_fetch_array($result);

while ($field < 15)
{

 $field = $field + 1;
 $fieldname = "f" . $field;
 $tmpfield1 = "f" . $field . "_caption";
 $tmpfield2 = "f" . $field . "_type";
 $tmpfield3 = "f" . $field . "_mandatory";
 $tmpfield4 = "f" . $field . "_length";
 $tmpfield5 = "f" . $field . "_filename";

 $caption = $row["$tmpfield1"];
 $type = $row["$tmpfield2"];
 $mandatory = $row["$tmpfield3"];
 $length = $row["$tmpfield4"];
 $filen = $row["$tmpfield5"];

 if ($fieldid[$field])
 {
    print("<tr>");
    print("<td> $caption </td>");
    if ($type == 'URL') { print "<td><a href='$fieldid[$field]' target='_blank'>$la_link</a></td>"; }
    else {
		print "<td> $fieldid[$field]</td>";
    }
    print("</tr>");
 }
}


?>

 <tr>
    <td><br />
    </td>
    <td></td>
  </tr>

  <tr>
    <td></td>
    <td></td>
  </tr>

  <tr>
    <td colspan="2"><b> <? echo $ad_details_text ?> </b> </td>

  </tr>
  <tr>
    <td> <? echo $la_adid ?></td>
    <td> <? echo $siteid ?></td>
  </tr>
  <tr>
    <td> <? echo $ad_views ?>&nbsp;</td>
    <td> <? echo $sitehits ?></td>
  </tr>
  <?
  if ($advanced_delete_activated)
  {
  ?>

  <tr>
    <td> <? echo $ad_expire ?></td>
    <td><b> <? echo $expire_ad ?></b></td>
  </tr>

  <?
  }
  ?>
  <tr>
    <td> <? echo $date_added ?></td>
    <td> <? echo $sitedate1 ?></td>
  </tr>
</table>  
<!-- // 6 -->






</td><td width="20"><img src="images/spacerbig.gif" width="10" height="5" alt="" /></td>
</tr>
</table>
<!-- // 5 -->


</td><td valign="top" align="right">


<? 

$query = "select id,imageh,imagew,filename from $pic_tbl where pictures_siteid=$siteid";
$sql_result = mysql_query ($query);
$num_pictures =  mysql_num_rows($sql_result);
	
for ($i=0; $i<$num_pictures; $i++)
{

?>
<!-- 5 -->
<table cellspacing="0" cellpadding="1" bgcolor="#A9B8D1" border="0" width="100%"><tr><td>

<!-- 6 -->
<table border="0" width="100%" bgcolor="#FFFFFF" cellspacing="0" cellpadding="5"><tr><td width="100%" align="center">  
<?

	$row = mysql_fetch_array($sql_result);  

	$w = $row["imagew"];
	$h = $row["imageh"];
	$id = $row["id"];
	$filename_stored = $row["filename"];
		
	if (!$fileimg_upload)
	{
		setImageSize("get.php?id=$picture"); 
	  	print("<table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\"><tr><td width=\"100%\" align=\"center\"><a href=\"javascript:openWin('large_picture.php?id=$id')\"><img border=\"0\" alt=\"$la_large_pic\" src=\"get.php?id=$id\"  width='$imgSizeArray[0]' height='$imgSizeArray[1]' valign=\"left\" alt='' /><img border=\"0\" src=\"images/zoom.gif\" width=\"19\" height=\"24\" alt='' /></a></td></tr></table>");

//			if ($picture > 1)
//			{
//				print "<table border=\"0\" width=\"100%\" height=\"15\" cellspacing=\"0\" cellpadding=\"0\"><tr><td width=\"100%\" height=\"15\"></td></tr></table><table border=\"0\" width=\"100%\" bgcolor=\"#A9B8D1\" cellspacing=\"0\" cellpadding=\"0\"><tr><td width=\"100%\" height=\"1\"><img border=\"0\" src=\"images/spacerbig.gif\" width=\"1\" height=\"1\" alt='' /></td></tr></table><table border=\"0\" width=\"100%\" height=\"15\" cellspacing=\"0\" cellpadding=\"0\"><tr><td width=\"100%\" height=\"15\"></td></tr></table>";
//			}
//			else
//			{
//				print "";
//			}
		

	}
	elseif ($fileimg_upload AND !$magic)
	{
		$fil = $full_path_to_public_program . "/images/" . $filename_stored;
		setImageSize($fil); 
		
		print("<table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\"><tr><td width=\"100%\" align=\"center\"><a href=\"javascript:openWin('images/$filename_stored')\"><img border=\"0\" alt=\"$la_large_pic\" src=\"images/$filename_stored\"  width='$imgSizeArray[0]' height='$imgSizeArray[1]' alt='' /><img border=\"0\" src=\"images/zoom.gif\" width=\"19\" height=\"24\" alt='' /></a></td></tr></table>");

//			if ($picture > 1)
//			{
//				print "<table border=\"0\" width=\"100%\" height=\"15\" cellspacing=\"0\" cellpadding=\"0\"><tr><td width=\"100%\" height=\"15\"></td></tr></table><table border=\"0\" width=\"100%\" bgcolor=\"#A9B8D1\" cellspacing=\"0\" cellpadding=\"0\"><tr><td width=\"100%\" height=\"1\"><img border=\"0\" src=\"images/spacerbig.gif\" width=\"1\" height=\"1\" alt='' /></td></tr></table><table border=\"0\" width=\"100%\" height=\"15\" cellspacing=\"0\" cellpadding=\"0\"><tr><td width=\"100%\" height=\"15\"></td></tr></table>";
//			}
//			else
//			{
//				print "";
//			}
		
	}
	elseif ($fileimg_upload AND $magic)
	{
		$ext=substr($filename_stored,-4);
		$file_without_ext=substr($filename_stored,0,-4);
		
		$large_image = $file_without_ext . "_large" . $ext;
		
		print("<table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\"><tr><td width=\"100%\" align=\"center\"><a href=\"javascript:openWin('images/$filename_stored')\"><img border=\"0\" alt=\"$la_large_pic\" src=\"images/$large_image\" alt='' /><img border=\"0\" src=\"images/zoom.gif\" width=\"19\" height=\"24\" alt='' /></a></td></tr></table>");

//			if ($picture > 1)
//			{
//				print "<table border=\"0\" width=\"100%\" height=\"15\" cellspacing=\"0\" cellpadding=\"0\"><tr><td width=\"100%\" height=\"15\"></td></tr></table><table border=\"0\" width=\"100%\" bgcolor=\"#A9B8D1\" cellspacing=\"0\" cellpadding=\"0\"><tr><td width=\"100%\" height=\"1\"><img border=\"0\" src=\"images/spacerbig.gif\" width=\"1\" height=\"1\"></td></tr></table><table border=\"0\" width=\"100%\" height=\"15\" cellspacing=\"0\" cellpadding=\"0\"><tr><td width=\"100%\" height=\"15\"></td></tr></table>";
//			}
//			else
//			{
//				print "";
//			}
		

	}

?>
</td></tr>
</table>
<!-- // 6 -->


</td></tr></table>
<!-- // 5 -->

<table border="0" width="100%" height="10" cellspacing="0" cellpadding="0">
  <tr>
    <td width="100%"></td>
  </tr>
</table>

<?

}


?>






</td></tr></table>
<!-- // 4 -->
</td></tr><tr><td width="100%">
</td></tr>
</table>
<!-- // 3 -->




    </td>
  </tr>
</table>
<!-- // 2 -->

    </td>
  </tr>
</table>
<!-- // 1 -->

<table border="0" width="100%" bgcolor="#A9B8D1" cellspacing="0" cellpadding="0">
  <tr>
    <td width="100%" height="1"><img border="0" src="images/spacerbig.gif" width="1" height="1" alt='' /></td>
  </tr>
</table>

<table border="0" width="100%" cellspacing="0" cellpadding="10">
  <tr>
    <td width="100%">

<?

print("<table border='0' cellspacing='0' cellpadding='0'><tr><td><a href='index.php?kid=$kid&catname=$catname'><img border='0' src='images/icon_moreadsincat.gif' width='28' height='20'></a></td><td><a href='index.php?kid=$kid&catname=$catname'>$la_similar</a>");
print("</td><td width='15'></td><td><a href='search.php?siteid=$siteid&adv=1'><img border='0' src='images/icon_adsfromseller.gif' width='28' height='20'></a></td><td><a href='search.php?siteid=$siteid&adv=1'>$la_similar_ads</a>");
print("</td><td width='15'></td><td><a href='contact.php?siteid=$siteid'><img border='0' src='images/icon_contactsalesperson.gif' width='28' height='20'></a></td><td><a href='contact.php?siteid=$siteid'>$la_contact_sale</a>");
print("</td><td width='15'></td><td><a href=\"javascript:openWin2('tellafriend.php?id=$siteid&adtitle=$sitetitle')\"><img border='0' src='images/icon_tellafriend.gif' width='28' height='20'></a></td><td><a href=\"javascript:openWin2('tellafriend.php?id=$siteid&adtitle=$sitetitle')\">$la_tell_a_friend</a>");
?>
</td><td width="15"></td><td><a href="javascript:window.print()"><img border="0" src="images/icon_print.gif" width="28" height="20"></a></td><td><a href="javascript:window.print()"><? echo $la_print ?></a>
</td></tr></table>


<?
}
$tell=$sitehits+1;
$s = "UPDATE $ads_tbl set sitehits=$tell,datestamp='$datestamp' where siteid=$siteid";
$result1=MYSQL_QUERY($s);

?>
    </td>
  </tr>
</table>


<?

include_once("admin/config/footer.inc.php");
?>
