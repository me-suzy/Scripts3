<?php
session_start();
include_once("admin/config/header.inc.php");
include_once("admin/inc.php");
if (!$special_mode)
{ include("navigation.php"); }
// { print("$menu_ordinary<p />"); }
if (empty($offset))
{
 $offset=0;
}
$limit = 10;
?>
    
<table border="0" width="100%" cellspacing="0" cellpadding="10">
  <tr>
    <td width="100%">
          
<?

print("<b>$la_picture_gallery</b><br />");
print(" $la_picute_gallery_text <p />");
if ($validation == 1) { $val_string = " AND valid = 1"; }



function setImageSize($image_file) { 
global $w;
global $h;
global $maxSize_gallery;
global $imgSize;
global $setImageSize;
global $imgSizeArray;


//$maxSize = 100; // set this varible to max width or height 
if (!$maxSize_gallery)
{
	$maxSize = 200;
}
else 
{
	$maxSize = $maxSize_gallery;
}
$width = $w; 
$height = $h; 

if($width > $maxSize || $height > $maxSize) { 

if($width > $height) { 
$i = $width - $maxSize; 
$imgSizeArray[0] = $maxSize; 
$imgSizeArray[1] = $height - ($height * ($i / $width)); 

} else { 

$i = $height - $maxSize; 
$imgSizeArray[0] = $width - ($width * ($i / $height)); 
$imgSizeArray[1] = $maxSize; 
} 

} else { 

$imgSizeArray[0] = $width; 
$imgSizeArray[1] = $height; 
} 

return $imgSizeArray; 
} ?> 


<? 

 
 $query = "select $ads_tbl.siteid,$pic_tbl.id,pictures_siteid,imagew,imageh,sitetitle,$pic_tbl.filename from $pic_tbl,$ads_tbl where $pic_tbl.pictures_siteid = $ads_tbl.siteid order by pictures_siteid desc limit $offset,$limit";

 $result = mysql_query ($query);
 print '<table border="0" cellspacing="10" cellpadding="10"><tr>';
 while ($row = mysql_fetch_array($result))
 {
  	$count_ads++;
  	
  	$siteid = $row["pictures_siteid"];
  	$id = $row["id"];
  	
  	$w = $row["imagew"];
	$h = $row["imageh"];
	$sitetitle = $row["sitetitle"];
	$fname = $row["filename"];

	if ($fileimg_upload)
	{
		if ($magic)
		{
			$ext=substr($fname,-4);
			$file_without_ext=substr($fname,0,-4);
		
			$small_image = $file_without_ext . "_small" . $ext;

			print("<td><a href=\"detail.php?annid=$siteid\"><img border=\"0\" alt=\"$sitetitle\" src=\"images/$small_image\" align=\"left\" alt=\"$sitetitle\" /></a></td>");
			$t++;
			if ($t==5)
			{ 
				print "</tr><tr>";
				$t=0;
			}
			
			
		}
		else 
		{
			$fil = $full_path_to_public_program . "/images/" . $fname;
			$imgSize = setImageSize($fil); 
			$img = $fname;
			print("<td><a href=\"detail.php?annid=$siteid\"><img border=\"0\" alt=\"$sitetitle\" src=\"images/$img\" width='$imgSize[0]' height='$imgSize[1]' align=\"left\" alt='$sitetitle' /></a></td>");
			$t++;
			if ($t==5)
			{ 
				print "</tr><tr>";
				$t=0;
			}
		}
	}
	else 
	{
		$imgSize = setImageSize("get.php?id=$id"); 
		print("<td><a href=\"detail.php?annid=$siteid\"><img border=\"0\" alt=\"$sitetitle\" src=\"get.php?id=$id\" width='$imgSize[0]' height='$imgSize[1]' align=\"left\" alt='$sitetitle' /></a></td>");		
		$t++;
			
		if ($t==5)
		{ 
				print "</tr><tr>";
				$t=0;
		}
	}
	

 }
 print "</tr></table>";

print "</td></tr></table>";

if (!$side==1)
{
          $side = 0;
}



 $number_of_ads_per_page = 20;
  
 $query = "select * from $pic_tbl";

 $result = mysql_query ($query);
 $numrows =  mysql_num_rows($result);


print '<table border="0" width="100%" cellspacing="0" cellpadding="10"><tr><td width="100%">';
 
if ($offset==1) { // bypass PREV link if offset is 0
    $prevoffset=$offset-20;
    print "<a href=\"$PHP_SELF?offset=$prevoffset$URLNEXT&$linkparam\">$previous_ads</a>\n";
}

// calculate number of pages needing links
$pages=intval($numrows/$limit);

// $pages now contains int of pages needed unless there is a remainder from division
if ($numrows%$limit) {
    // has remainder so add one page
    $pages++;
}

for ($i=1;$i<=$pages;$i++) { // loop thru
    $newoffset=$limit*($i-1);
    print "<a href=\"$PHP_SELF?offset=$newoffset$URLNEXT&$linkparam\">$i</a> \n";
}

// check to see if last page
if (!(($offset/$limit)==$pages) && $pages!=1) {
    // not last page so give NEXT link
    $newoffset=$offset+$limit;
    print "<a href=\"$PHP_SELF?offset=$newoffset$URLNEXT&$linkparam\">$next_ads</a>\n";
}
print '</td></tr></table>';
?>






<?
include_once("admin/config/footer.inc.php");
?>
