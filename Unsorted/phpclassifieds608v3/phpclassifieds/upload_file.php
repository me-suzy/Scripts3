<?
session_start();
include_once("admin/config/header.inc.php");
include_once("admin/inc.php");
if (!$special_mode)
{ include("navigation.php"); }
// { print("$menu_ordinary<p />"); }
// print("<h3>$name_of_site</h3>");
include_once("member_header.php");
check_valid_user();

?>
<script language="JavaScript">
        function openWin(URL) { aWindow=window.open(URL,"Large","toolbar=no,width=400,height=300,status=no,scrollbars=no,resize=no,menubars=no");
        }
        function openWin2(URL) {
aWindow=window.open(URL,"Large","toolbar=no,width=400,height=350,status=no,scrollbars=no,resize=no,menubars=no");
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         }
        </script>
<table border="0" cellspacing="1" width="100%" height="100%">
<tr>
    <td width="100%" valign="top"><b><? echo $la_pic_upload ?></b><br><br>
        

<?
print " $la_upload_limit " . floor($piclimit / 1024) . " kb. <br />";
if (!$submit)
{
        print " $upload_picture_text ";
}

$sq = "select id from $pic_tbl where pictures_siteid = $pictures_siteid";
$result2 = MYSQL_QUERY($sq);
$mysql_img = mysql_num_rows($result2);

if ($maxpic > $mysql_img)
{
	print "<form method='post' action='upload_file.php' enctype='multipart/form-data'>";
}
else 
{
	print "<p /> <b>$la_no_morepic</b> <p />";
}

?>



<INPUT TYPE="hidden" name="pictures_siteid" value="<? echo $pictures_siteid ?>" />
<INPUT TYPE="hidden" name="MAX_FILE_SIZE" value="<? echo $piclimit ?>" />


<?


if ($submit)
{

	if($photo_size)
	{
		// Get file extension
		$ext=substr($photo_name,-4);
		$file_without_ext=substr($photo_name,0,-4);
		
		
		// Allowed file takes
		if (strcasecmp($ext,".jpg") || strcasecmp($ext,".gif"))
		{
			if(is_uploaded_file($photo))
			{
				$rand = rand(200,19999999);
				$photo_name = $rand . $photo_name;
				$photo_name_large = $rand . $file_without_ext . "_large" . $ext;
				$photo_name_small = $rand . $file_without_ext . "_small" . $ext;
				move_uploaded_file($photo,"images/$photo_name");	
				
				
				// If imagemagic is installed and activated from cp
				if ($magic)
				{
					$command = "$magic_path -quality $magic_large_q -size $magic_large_size -scale $magic_large_size images/$photo_name images/$photo_name_large";
					$res = exec($command);
					//print "<p>- $command -<p>";
					
					$command = "$magic_path -quality $magic_small_q -size $magic_small_size -scale $magic_small_size images/$photo_name images/$photo_name_small";
					$res = exec($command);
					//print "<p>- $command -<p>";
				}
				
				
				$image_size = getimagesize("images/$photo_name"); 
				$w = $image_size[0]; 
				$h = $image_size[1]; 
				$imgSize = setImageSize("images/$photo_name"); 
				

				// Tell the database that picture is uploaded
				$sq = "INSERT INTO $pic_tbl (pictures_siteid,filesize,filename,imagew,imageh) VALUES ('$pictures_siteid','$photo_size','$photo_name','$w','$h')";
				$result=MYSQL_QUERY($sq);
				$id = mysql_insert_id();
				$sq = "UPDATE $ads_tbl set picture = picture+1 where siteid=$pictures_siteid";
				$result2 = MYSQL_QUERY($sq);
       			
				
    			if ($result2)
    			{        
    				print("<p /> $la_upload_success ");
    			}
    			
			}
		}
		else
		{
			die($la_onlygifjpg);
		}
	}
	else if($photo_name != "" && $photo_size == "0") 
	{
		die("$la_picsmaller $piclimit. ");
	}
	else 
	{
		print $la_no_pic;	
	}
 

}
?>
<p />
 <? echo $la_filename ?> <br /><input name="photo" type="file" /><br /><br />
<input type="submit" name="submit" value="<? echo $la_upload_button ?>" />
<?

print "<p />";

// Show pictures
if ($delete)
{
	
	
	$deleteimg = "images/" . $filename_del;
	
	$ext=substr($filename_del,-4);
	$file_without_ext=substr($filename_del,0,-4);
	
	$delete_result = unlink($deleteimg);
	
	$photo_name_large = "images/" . $rand . $file_without_ext . "_large" . $ext;
	$photo_name_small = "images/" . $rand . $file_without_ext . "_small" . $ext;
	
	
	if (file_exists($photo_name_large)) { 
		$delete_result = unlink($photo_name_large); 
	}
	
	if (file_exists($photo_name_small))  { 
		$delete_result = unlink($photo_name_small);
	}
	
	
	
	
	$sql_delete = "delete from $pic_tbl where id = $delete";	
	$result = mysql_query($sql_delete);
		
	if ($result)
	{
		print "<p /> <b>$la_del_img</b> <p />";	
		$sq = "UPDATE $ads_tbl set picture = picture-1 where siteid=$pictures_siteid";
    	$result = mysql_query($sq);
	}
	
}

$query = "select id,imageh,imagew,filetype,filename from $pic_tbl where pictures_siteid=$pictures_siteid order by id desc";
$sql_result = mysql_query ($query);
$num_pictures =  mysql_num_rows($sql_result);

for ($i=0; $i<$num_pictures; $i++)
{
      
    $row = mysql_fetch_array($sql_result);  

	$w = $row["imagew"];
	$h = $row["imageh"];
	$id = $row["id"];
	$filetype = $row["filetype"];
	$filename = $row["filename"];
	
	setImageSize("images/filename"); 
	print("<table border=\"0\" cellspacing=\"0\" cellpadding=\"1\" bgcolor=\"#A9B8D1\"><tr><td bgcolor=\"#A9B8D1\"><table border=\"0\" width=\"100%\" bgcolor=\"#FFFFFF\" cellspacing=\"0\" cellpadding=\"0\"><tr><td width=\"100%\"><table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"5\"><tr><td align=\"left\"><a href=\"javascript:openWin('images/$filename')\"><img border=\"0\" alt=\"$la_large_pic\" src=\"images/$filename\"  width='$imgSizeArray[0]' height='$imgSizeArray[1]' valign=\"left\" alt='' /></a></td></tr></table><table border=\"0\" width=\"100%\" bgcolor=\"#A9B8D1\" cellspacing=\"0\" cellpadding=\"4\"><tr><td width=\"100%\" align=\"center\"><a href='upload_file.php?pictures_siteid=$pictures_siteid&amp;delete=$id&amp;filename_del=$filename'><img src=\"images/pointer.gif\" border=\"0\">$la_del_img1</a></td></tr></table></td></tr></table></td></tr></table><br>");

	
}
print("</td></tr></table>");
include_once ("member_footer.php");
require("admin/config/footer.inc.php");
?>
