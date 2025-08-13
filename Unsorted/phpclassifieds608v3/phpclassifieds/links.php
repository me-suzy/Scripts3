<?


if ($validation == 1) { $val_string = " AND valid = 1"; }
if (!$order)
{
        $order = "siteid desc";
}

if (!$tall | $tall==1) { $fra=0;   $til=$number_of_ads_per_page;   }
else
{       //print "Number of ads: $number_of_ads_per_page";
        $fra = ($tall * $number_of_ads_per_page) - $number_of_ads_per_page;
        $til = ($number_of_ads_per_page);
}

if ($latestmode)
{
 $sql_string = "select * from $ads_tbl, $cat_tbl, $usr_tbl where catid=sitecatid AND ad_username = $usr_tbl.email $val_string order by siteid desc limit 30";

?>
    
<table border="0" width="100%" cellspacing="0" cellpadding="10">
  <tr>
    <td width="100%">
          
<?

 print("<b>$la_view_l</b>");
 
?>
    </td>
  </tr>
</table>

<table border="0" width="100%" bgcolor="#A9B8D1" cellspacing="0" cellpadding="0">
  <tr>
    <td width="100%" height="1"><img border="0" src="images/spacerbig.gif" width="1" height="1" alt='' /></td>
  </tr>
</table>

<? 

}
elseif ($latestfrontpage)
{
 $sql_string = "select * from $ads_tbl, $cat_tbl, $usr_tbl where catid=sitecatid AND ad_username = $usr_tbl.email $val_string order by siteid desc limit 10";
 if (!$fp)
 {

?>
    
<table border="0" width="100%" cellspacing="0" cellpadding="10">
  <tr>
    <td width="100%">
          
<?

       print("<b>$la_view_l</b>");

?>
    </td>
  </tr>
</table>

<table border="0" width="100%" bgcolor="#A9B8D1" cellspacing="0" cellpadding="0">
  <tr>
    <td width="100%" height="1"><img border="0" src="images/spacerbig.gif" width="1" height="1" alt='' /></td>
  </tr>
</table>

<?

 }
}
elseif ($searchword AND !$adv)
{
 if (!$catid) { $catid = '%%'; }
 else { $extra = "OR catfatherid = $catid";  }
 if ($limit) { $til = $limit; }
 
 
 
 if ($custom_field_1 OR $custom_field_2 OR $custom_field_3 OR $custom_field_4 OR $custom_field_5 OR $custom_field_6 OR $custom_field_7 OR $custom_field_8)
 {
 	
 	include "advsearch.inc.php";
 	$sql_string = "select * from $ads_tbl, $cat_tbl where catid=sitecatid AND (sitetitle like '%$searchword%' or catname like '%$searchword%' or sitedescription like '%$searchword%') AND ($str) AND catid like '$catid' $val_string order by $order limit $fra, $til";
 }
 else 
 {	
 	// Subdir search AND main search in same string
 	$sql_string = "select * from $ads_tbl, $cat_tbl where catid=sitecatid AND (sitetitle like '%$searchword%' or catname like '%$searchword%' or sitedescription like '%$searchword%') AND (catid like '$catid' $extra) $val_string order by $order limit $fra, $til";
 }

?>
    
<table border="0" width="100%" cellspacing="0" cellpadding="10">
  <tr>
    <td width="100%">
          
<? 
 
 print("<b>$la_search_result</b>");
 
?>
    </td>
  </tr>
</table>

<table border="0" width="100%" bgcolor="#A9B8D1" cellspacing="0" cellpadding="0">
  <tr>
    <td width="100%" height="1"><img border="0" src="images/spacerbig.gif" width="1" height="1" alt='' /></td>
  </tr>
</table>

<?  
 
}
elseif ($mostviewed)
{
 $sql_string = "select * from $ads_tbl, $cat_tbl, $usr_tbl where catid=sitecatid AND ad_username = $usr_tbl.email $val_string order by sitehits desc limit 30";

?>
    
<table border="0" width="100%" cellspacing="0" cellpadding="10">
  <tr>
    <td width="100%">
          
<?

 print("<b>$la_most_viewed</b>");
 
?>
    </td>
  </tr>
</table>

<table border="0" width="100%" bgcolor="#A9B8D1" cellspacing="0" cellpadding="0">
  <tr>
    <td width="100%" height="1"><img border="0" src="images/spacerbig.gif" width="1" height="1" alt='' /></td>
  </tr>
</table>

<?
 
}
elseif ($adv)
{
 // When no ads on a user, we can´t select it...
 if ($siteid > 0)
 {
  $sql = "select ad_username from $ads_tbl where siteid = $siteid $val_string";
  $sql_resultads = mysql_query ($sql);
  $num_ads =  mysql_num_rows($sql_resultads);
 }

 for ($i=0; $i<$num_ads; $i++)
 {
      $row = mysql_fetch_array($sql_resultads);
      $ad_username = $row["ad_username"];
 }

 if (!$catid) { $catid = '%%'; }
 if (!$searchword) { $searchword = '%%'; }
 if (!$s_userid) { $s_userid = '%%'; }
 if (!$s_email) { $s_email = '%%'; }
 $sql_string = "select * from $ads_tbl, $cat_tbl where catid=sitecatid AND (sitetitle like '%$searchword%' or catname like '%$searchword%' or sitedescription like '%$searchword%') AND catfatherid like '$catid' AND $ads_tbl.ad_username = '$ad_username' $val_string order by $order limit $fra, $til";

?>

<table border="0" width="100%" cellspacing="0" cellpadding="10">
  <tr><td>     
          
<? 
 
 print("<span class=\"title\">$la_search</span>");

?>

    </td>
  </tr>
</table>


<?

}
else
{
	$sql_string = "select * from $ads_tbl, $cat_tbl, $usr_tbl where catid=sitecatid AND sitecatid='$kid' AND ad_username = $usr_tbl.email $val_string order by $order limit $fra, $til";
}

$sql_resultads = mysql_query ($sql_string);
//print $sql_string;
//if ($sql_resultads)
//{
 $num_ads =  mysql_num_rows($sql_resultads);
//}
//print "Antall: $num_ads";

$color = "#ECF0F6";

//----- Remove Toolbar if no results




if ($kid AND $num_ads>0)
{

?>
    <!--
<table border="0" width="100%" cellspacing="0" cellpadding="10">
  <tr>
    <td width="100%">
    -->
          
<?

        $catname = urlencode($catname);


        $filename = "templates/links_headrow.html";
        $fd = fopen ($filename, "r");
        $file= fread ($fd, filesize ($filename));
        $file = str_replace("{CATEGORYNAME}", "$category", $file);
        $file = str_replace("{TOTAL_ADS}", "$total", $file);
        if (!$latestfrontpage AND !$latestmode AND !$searchmode AND !$mostviewed AND !$adv) { $file = str_replace("{CATEGORY_URL}", "<a href=\"index.php?kid=$kid&amp;catname=$catname&amp;order=catname&amp;searchword=$searchword&amp;s_userid=$s_userid&amp;adv=$adv&amp;siteid=$siteid\">", $file); }
        else { $file = str_replace("{CATEGORY_URL}", "<a href=''>", $file); }
          $file = str_replace("{/CATEGORY_URL}", "</a>", $file);
          if (!$latestfrontpage AND !$latestmode AND !$searchmode AND !$mostviewed AND !$adv) { $file = str_replace("{AD_URL}", "<a href=\"index.php?kid=$kid&amp;catname=$catname&amp;tall=$tall&amp;order=sitetitle&amp;searchword=$searchword&amp;s_userid=$s_userid&amp;adv=$adv&amp;siteid=$siteid\">", $file); }
          else { $file = str_replace("{AD_URL}", "<a href=''>", $file); }
        $file = str_replace("{AD_URL}", "<a href=\"?kid=$kid&amp;catname=$catname&amp;tall=$tall&amp;order=sitetitle&amp;searchword=$searchword&amp;s_userid=$s_userid&amp;adv=$adv&amp;siteid=$siteid\">", $file);
        $file = str_replace("{/AD_URL}", "</a>", $file);
        $file = str_replace("{ADTITLE}", "$title", $file);
        $file = str_replace("{CUSTOM_FIELD1}", "$custom_field_1_text", $file);
        $file = str_replace("{CUSTOM_FIELD2}", "$custom_field_2_text", $file);
        $file = str_replace("{CUSTOM_FIELD3}", "$custom_field_3_text", $file);
        $file = str_replace("{CUSTOM_FIELD4}", "$custom_field_4_text", $file);
        $file = str_replace("{CUSTOM_FIELD5}", "$custom_field_5_text", $file);
        $file = str_replace("{CUSTOM_FIELD6}", "$custom_field_6_text", $file);
        $file = str_replace("{CUSTOM_FIELD7}", "$custom_field_7_text", $file);
        $file = str_replace("{CUSTOM_FIELD8}", "$custom_field_8_text", $file);
        if (!$latestfrontpage AND !$latestmode AND !$searchmode AND !$mostviewed AND !$adv) { $file = str_replace("{DATE_URL}", "<a href=\"?kid=$kid&amp;catname=$catname&amp;tall=$tall&amp;order=siteid&amp;searchword=$searchword&amp;s_userid=$s_userid&amp;adv=$adv&amp;siteid=$siteid\">", $file); }
        else { $file = str_replace("{DATE_URL}", "<a href=''>", $file); }
        $file = str_replace("{/DATE_URL}", "</a>", $file);
        $file = str_replace("{DATEADDED}", "$date_added", $file);

		
        $file = str_replace("{YESNOPICTURE}", "$picture_text", $file);

        print($file);

}


if ($num_ads > 0)
{

 for ($i=0; $i<$num_ads; $i++)
 {
      //print "<br />Links: $sql_string<br />";
      $row = mysql_fetch_array($sql_resultads);
      $siteid = $row["siteid"];
      $sitetitle = $row["sitetitle"];
      $sitedescription = $row["sitedescription"];
      $siteurl = $row["siteurl"];
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
      $datestamp = $row["datestamp"];
      $year=substr($row[datestamp],0,4);
      $month=substr($row[datestamp],4,2);
      $day=substr($row[datestamp],6,2);
      
      // Change date layout
      $regdate = "$day.$month.$year";
      
      $custom_field_1 = $row["custom_field_1"];
      $custom_field_2 = $row["custom_field_2"];
      $custom_field_3 = $row["custom_field_3"];
      $custom_field_4 = $row["custom_field_4"];
      $custom_field_5 = $row["custom_field_5"];
      $custom_field_6 = $row["custom_field_6"];
      $custom_field_7 = $row["custom_field_7"];
      $custom_field_8 = $row["custom_field_8"];
      $picture = $row["picture"];
      $img_stored = $row["img_stored"];


if ($color == "#ECF0F6")
{
                $color = "#FFFFFF";
}
elseif ($color== "#FFFFFF")
{
                 $color = "#ECF0F6";
}

// -----

if ($picture>0)
{
        $p = $yes;
}
else
{
        $p = $no;
}

        $count_ads++;

        $filename = "templates/links.html";
        $fd = fopen ($filename, "r");
        $file= fread ($fd, filesize ($filename));
        $file = str_replace("{CATEGORYNAME}", "$catname", $file);
        $file = str_replace("{AD_URL}", "<a href=\"detail.php?siteid=$siteid\"><img src=\"images/pointer.gif\" border=\"0\" alt='' />", $file);
        $file = str_replace("{/AD_URL}", "</a>", $file);
        $file = str_replace("{TITLE}", "$sitetitle", $file);
        $file = str_replace("{CUSTOM_FIELD1}", "$custom_field_1", $file);
        $file = str_replace("{CUSTOM_FIELD2}", "$custom_field_2", $file);
        $file = str_replace("{CUSTOM_FIELD3}", "$custom_field_3", $file);
        $file = str_replace("{CUSTOM_FIELD4}", "$custom_field_4", $file);
        $file = str_replace("{CUSTOM_FIELD5}", "$custom_field_5", $file);
        $file = str_replace("{CUSTOM_FIELD6}", "$custom_field_6", $file);
        $file = str_replace("{CUSTOM_FIELD7}", "$custom_field_7", $file);
        $file = str_replace("{CUSTOM_FIELD8}", "$custom_field_8", $file);
        $file = str_replace("{REGDATE}", "$regdate", $file);
        
        
        
        
        $file = str_replace("{COLOR}", "$color", $file);
        
        
     if ($list_img AND !$skip)
     {   
      
     	
        // Get picture
        $sql_picture = "select id,filename,imagew, imageh from $pic_tbl where pictures_siteid=$siteid order by id desc limit 1";
        $res = mysql_query($sql_picture);
		$row = mysql_fetch_array($res);
		$pic_id = $row["id"];
		$filename = $row["filename"];
		$imagew = $row["imagew"];
		$imageh = $row["imageh"];
        
				
		if (!$fileimg_upload)
		{
		
			if ($pic_id)
			{
	        	
				$w_links = $imagew;
				$h_links = $imageh;
				$fil = "get.php?id=$pic_id";
	        	setImageSize_links("$fil"); 
				$file = str_replace("{IMAGEYESNO}", "<img src='$fil' width='$imgSizeArray[0]' height='$imgSizeArray[1]' valign=\"left\" alt='' />", $file);
			}
			else 
			{
				$file = str_replace("{IMAGEYESNO}", "<img src='images/noimage.gif' alt='' />", $file);
				
			}
		}
		else 
		{
			if ($pic_id)
			{
	        	if ($magic)
	        	{
	        		$ext=substr($filename,-4);
					$file_without_ext=substr($filename,0,-4);
		
					$small_image = $file_without_ext . "_small" . $ext;
					$file = str_replace("{IMAGEYESNO}", "<img src='images/$small_image' valign=\"left\" alt='' />", $file);					
	        		
	        	}
	        	else 
	        	{
				
		        	$fil = "images/" . $filename;
		        	$w_links = $imagew;
					$h_links = $imageh;
		        	setImageSize_links("$fil"); 
					$file = str_replace("{IMAGEYESNO}", "<img src='$fil' width='$imgSizeArray[0]' height='$imgSizeArray[1]' valign=\"left\" alt='' />", $file);
	        	}
	        }
			else 
			{
				$file = str_replace("{IMAGEYESNO}", "<img src='images/noimage.gif' alt='' />", $file);
				
			}
		}
     }
     else 
     {
     	$file = str_replace("{IMAGEYESNO}", "$p", $file);	
     }
   
     
        print("$file");
		        
        
		

}
} // num ads > 0 end
if ($kid)
{
        //print("</table>");
}
// Previos and next link generator

if ($num_ads >0)
{

print ("<tr><td><p /> ");
if (!$latestmode AND !$latestfrontpage AND !$mostviewed)
{
 if ($searchword)
 {
 $sql_links = $sql_string;
 }
 else
 {
 $sql_links = "select * from $cat_tbl, $ads_tbl, $usr_tbl where catid=sitecatid AND sitecatid='$kid' AND ad_username = $usr_tbl.email $val_string order by $order";
 }
 $sql_result = mysql_query ($sql_links);
 $num_links =  mysql_num_rows($sql_result);


 if ($tall>1)
 {
       $tallet = $tall - 1;
       print("&nbsp;<a href='?kid=$kid&amp;catname=$catname&amp;tall=$tallet&amp;searchword=$searchword'><b>$previous_ads</b></a>&nbsp;");
 }
 if ($num_links>$number_of_ads_per_page)
 {

        if ($count_ads >= $number_of_ads_per_page)
        {
         if (!$tall)
         {
                 $tallet = $tall + 2;
                 print("<a href='?kid=$kid&amp;catname=$catname&amp;tall=$tallet&amp;searchword=$searchword'><b>$next_ads</b></a>&nbsp;");
         }
         else
         {
                 $tallet = $tall + 1;
                 print("<a href='?kid=$kid&amp;catname=$catname&amp;tall=$tallet&amp;searchword=$searchword'><b>$next_ads</b></a>&nbsp;");

         }
        }
 }
}
print (" </td></tr></table>");
}


?>

