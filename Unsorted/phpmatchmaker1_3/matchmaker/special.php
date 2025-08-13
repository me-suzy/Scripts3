<?
include "db_inc.php";
include "webmaster/settings_inc.php";

db_connect();
$sql = "select * from user where isSpecial = 1 AND image <> 0 order by rand() limit 4";
$result_sq = mysql_query($sql);


while($row = mysql_fetch_array($result_sq))
{
	$image = $row["image"];
	$username = $row["username"];
	if ($set_magic)
	{
	$ext=substr($image,-4);
	$file_without_ext=substr($image,0,-4);
 	$image = $rand . $file_without_ext . "_small" . $ext;
	}
	
 	print "<a href='detail.php?profile=$username'><img src='upload_images/$image' border='0'></a>&nbsp;";
}

?>

