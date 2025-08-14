<?php
require 'start.php';

if ($scriptname == 'wsngallery' || $scriptname == 'wsnmanual')
{
 if ($settings->graphicsprog == 'imagemagick')
  require 'classes/imagemagick.php';
 else
  require 'classes/gd.php'; // default to GD since it's more common
 if ($settings->nohotlink == 'yes')
 {
  if ( (!strstr(getenv('HTTP_REFERER'), $_SERVER['SERVER_NAME'])) && (getenv('HTTP_REFERER') != '') && (strstr(getenv('HTTP_REFERER'), 'http')) ) 
  { 
   die("No hotlinking allowed");
  }
 }
}

if (!$thismember->groupcandownloadfiles)
{
 // no access message
 if (!$template) $template = new template("blank");
 $template->text = $language->noaccess; 
 require 'end.php';
}
else
{
 $thelink = new onelink('id', $id);
 $thelink->downloads += 1;
 $thelink->update('downloads');
 header("Pragma: public");

 if ($edit != '')
 {
  // load image
  $image_file = $thelink->filename;
  if ($useedit && file_exists($settings->uploadpath .'temp_'. $image_file)) $image_path = $settings->uploadpath .'temp_'. $image_file;
  else $image_path = $settings->uploadpath . $image_file;
   
  $edit($image_path);
 }
 else
 {
  $extension = extension($thelink->filename);
  switch ($extension)
  {
   case 'zip':
   header("Content-type: application/zip");
   break;  
   case 'png':
   header("Content-type: image/png");
   break;
   case 'jpeg':
   case 'jpg':
   header("Content-type: image/jpeg");
   break;
   case 'gif':
   header("Content-type: image/gif");
   break;
   case 'bmp':
   header("Content-type: image/x-xbitmap");
   break;
   case 'swf':
   header("Content-type:  application/x-shockwave-flash");
   break;
  }
  $imagetypes = ' png jpg jpeg gif bmp swf bmp ';
  if ($action == 'download' || !strstr($imagetypes, $extension)) header("Content-Disposition: attachment; filename=". $thelink->filetitle);
  readfile('attachments/'. $thelink->filename);
 } 
} 
?>