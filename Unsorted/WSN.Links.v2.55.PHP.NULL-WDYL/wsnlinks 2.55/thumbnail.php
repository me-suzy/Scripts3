<?php
require 'start.php';

if ($settings->graphicsprog == 'imagemagick')
 require 'classes/imagemagick.php';
else
 require 'classes/gd.php'; // default to GD since it's more common

$magickpath = $settings->magickpath;

if ($uploadpath == '') $uploadpath = $settings->uploadpath;
if ($thumbwidth == '') $thumbwidth = $settings->thumbwidth;
if ($thumbheight == '') $thumbheight = $settings->thumbheight;

define(IMAGE_BASE, $uploadpath);
define(MAX_WIDTH, $thumbwidth);
define(MAX_HEIGHT, $thumbheight);

$thelink = new onelink('id', $id);
$image_file = $thelink->filename;
$image_path = IMAGE_BASE . $image_file;

$test = fileread('attachments/thumb_'. $thelink->filename);
if ($test)
{
 $info = getimagesize('attachments/thumb_'. $thelink->filename);
 if ($thelink->xwidth > 0) $scale = min(MAX_WIDTH/$thelink->xwidth, MAX_HEIGHT/$thelink->yheight);

 if ($scale < 1) { $properwidth = floor($scale*$thelink->xwidth); $properheight = floor($scale*$thelink->yheight); }
 else { $properwidth = $thelink->xwidth; $properheight = $thelink->yheight; }
 if (($info[0] == $properwidth) && ($info[1] == $properheight))
 {
  header("Content-type: image/jpeg");
  readfile('attachments/thumb_'. $thelink->filename);
  die();
 }
}

makethumbnail($image_path, MAX_WIDTH, MAX_HEIGHT);

?> 