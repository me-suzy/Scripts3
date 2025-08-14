<?php
// image functions customized to GD specifications

function grayscale($image_path)
{
 $img = prepare($image_path);
 $dither = true;
 imagetruecolortopalette($img, $dither, 255); // for function to work, must be 256 color
 for ($i=0; $i < imagecolorstotal($img); $i++)
 {
  $index = imagecolorsforindex($img, $i);
  $average = (int)((int)($index["red"] + $index["green"] + $index["blue"]) / 3);
  $set = imagecolorset($img, $i, $average, $average, $average);
 }
 output($img);
 return true;
}

function negate($image_path)
{
 $img = prepare($image_path);
 $dither = true;
 imagetruecolortopalette($img, $dither, 255); // for function to work, must be 256 color
 for ($i=0; $i<imagecolorstotal($img); $i++)
 {
  $index = imagecolorsforindex($img, $i);
  if ($index["red"] > 128)
  {
   $newred = $index["red"] - 128;
   $newred = 128 - $newred;
  }
  else 
  {
   $newred = 128 - $index["red"];
   $newred = 128 + $newred;
  }
  if ($index["green"] > 128)
  {
   $newgreen = $index["green"] - 128;
   $newgreen = 128 - $newgreen;
  }
  else 
  {
   $newgreen = 128 - $index["green"];
   $newgreen = 128 + $newgreen;
  }
  if ($index["blue"] > 128)
  {
   $newrblue = $index["blue"] - 128;
   $newblue = 128 - $newblue;
  }
  else 
  {
   $newblue = 128 - $index["blue"];
   $newblue = 128 + $newblue;
  }
  $set = imagecolorset($img, $i, $newred, $newgreen, $newblue);
 }
 output(&$img);
}

function output(&$img)
{
 global $image_path, $settings, $outputtype, $thelink;
 if (strlen($outputtype) < 2) $outputtype = $settings->outputtype;
 $extension = extension($image_path);  
 if (strlen($outputtype) < 2) $outputtype = $extension;
 $thelink->filetitle = str_replace($extension, $outputtype, $thelink->filetitle);     
 switch ($outputtype)
 {
  case 'png':
   $thelink->filetitle = str_replace($extension, 'png', $thelink->filetitle);   
   if ($action == 'download') header("Content-Disposition: attachment; filename=". $thelink->filetitle);     
   header("Content-type: image/png");
   imagepng($img);
   die();
   break;
  case 'jpeg':
  case 'jpg':
   if ($settings->jpeginterlace == 'yes') imageinterlace($img, 1);
   header("Content-type: image/jpeg");
   imagejpeg($img, '', $settings->jpegquality);
   die();
   break;
  case 'gif':
   imagetruecolortopalette($img, $dither, 255); // for function to work, must be 256 color    
   if ($action == 'download') header("Content-Disposition: attachment; filename=". $thelink->filetitle);     
   header("Content-type: image/png");
   imagepng($img);   
   die();
   break;
  case 'bmp':
   if ($action == 'download') header("Content-Disposition: attachment; filename=". $thelink->filetitle);     
   header("Content-type: image/x-xbitmap");
   imagexbm($img);
 }
 imagedestroy($img);
 return true;
}

function prepare($image_path)
{
 $ext = extension($image_path);
 if ($ext == 'jpg' || $ext == 'jpeg')
 {
  $img = @imagecreatefromjpeg($image_path);
 }
 else if ($ext == 'png') 
 {
  $img = @imagecreatefrompng($image_path);
 }
 else if ($ext == 'gif') 
 {
  $img = @imagecreatefrompng($image_path);
 }
 else if ($ext == 'bmp')
 {
  $img = @imagecreatefromwbmp($image_path);
 } 
 return $img;
}

function makethumbnail($image_path, $maxwidth, $maxheight)
{
 global $dontsave, $thelink;

 $img = null;

 $ext = extension($image_path);
 if (($ext == 'jpg') || ($ext == 'jpeg'))
 {
  $img = @imagecreatefromjpeg($image_path);
 }
 else if ($ext == 'png') 
 {
  $img = @imagecreatefrompng($image_path);
 }
 else if ($ext == 'bmp' || $ext == 'gif')
 {
  $img = false;
 }

 if ($img)
 {
  $width = imagesx($img);
  $height = imagesy($img);
  $scale = min($maxwidth/$width, $maxheight/$height);

  if ($scale < 1)
  {
   $new_width = floor($scale*$width);
   $new_height = floor($scale*$height);
   if (function_exists(imagecreatetruecolor))
   {
    $tmp_img = imagecreatetruecolor($new_width, $new_height);
   }
   else
   {
    $tmp_img = imagecreate($new_width, $new_height);
   }
   imagecopyresized($tmp_img, $img, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
   imagedestroy($img);
   $img = $tmp_img;
  }
 }

 if (!$img)
 {
  $dontsave = true;
  $img = imagecreatefrompng($templatesdir .'/images/'. extension($thelink->filename) .'.png'); 
  if (!$img) $img = imagecreatefrompng($templatesdir .'/images/nothumbnail.png'); 
  $width = imagesx($img);
  $height = imagesy($img);
  $scale = min($maxwidth/$width, $maxheight/$height);
  // If the image is larger than the max shrink it
  if ($scale < 1)
  {
   $new_width = floor($scale * $width);
   $new_height = floor($scale * $height);
   $tmp_img = imagecreatetruecolor($new_width, $new_height);
   imagecopyresized($tmp_img, $img, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
   imagedestroy($img);
   $img = $tmp_img;
  }
 }

 if (!$dontsave) imagejpeg($img, 'attachments/thumb_'. $thelink->filename);

 header("Content-type: image/jpeg");
 imagejpeg($img);
 die();
}

?>