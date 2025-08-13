<html>
<head>
</head>

<body>
<?
include_once("admin/inc.php");
$query = "select imageh,imagew from $pic_tbl where id=$id";
$sql_result = mysql_query ($query);
$num_links =  mysql_num_rows($sql_result);
$row = mysql_fetch_array($sql_result);
$w = $row["imagew"];
$h = $row["imageh"];

function setImageSize($image_file) { 
global $w;
global $h;
global $maxSize_large;
//$maxSize = 100; // set this varible to max width or height 

if (!$maxSize_large)
{
	$maxSize = 500;
}
else 
{
	$maxSize = $maxSize_large;	
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
$imgSize = setImageSize("get.php?id=$id"); 
print("<img src='get.php?id=$id' alt='Stort bilde'  width='$imgSize[0]' height='$imgSize[1]'>");
?>
<br /><a href="javascript:window.close();"><? echo $la_large_picture_close ?></a>

</body>
</html>
