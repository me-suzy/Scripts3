<?
include_once("admin/config/general.inc.php");
include_once("admin/config/options.inc.php");
include_once("admin/config/globalad.inc.php");
include_once("admin/config/globaluser.inc.php");
include_once("admin/db.php");
if (file_exists("$full_path_to_public_program/language/$la.php"))
{
	require("$full_path_to_public_program/language/$la.php");
}

function setImageSize_links($image_file) { 

global $max_links;
global $imgSizeArray;
global $w_links;
global $h_links;

if (!$max_links)
{
	$max_links = 200;
}
$width = $w_links; 
$height = $h_links; 

if($width > $max_links || $height > $max_links) { 

if($width > $height) { 
$i = $width - $max_links; 
$imgSizeArray[0] = $max_links; 
$imgSizeArray[1] = $height - ($height * ($i / $width)); 

} else { 

$i = $height - $max_links; 
$imgSizeArray[0] = $width - ($width * ($i / $height)); 
$imgSizeArray[1] = $max_links; 
} 

} else { 

$imgSizeArray[0] = $width; 
$imgSizeArray[1] = $height; 
} 

return $imgSizeArray; 
} 

?>