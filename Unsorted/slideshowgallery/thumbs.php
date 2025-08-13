<?php

$image = $_GET['image'] ; 
$newwidth = $_GET['newwidth'];
$newheight = $_GET['newheight'];
$height = $_GET['height'];
$width = $_GET['width'];


$src = imagecreatefromjpeg("$image");
$im = imagecreatetruecolor($newwidth,$newheight);  
imagecopyresampled($im,$src,0,0,0,0,$newwidth,$newheight,$width,$height); 
imagejpeg($im, '',85); 
imagedestroy($im); 

?>