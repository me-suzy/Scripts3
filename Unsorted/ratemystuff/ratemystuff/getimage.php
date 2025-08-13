<?php
#
# FILE: getimage.php
# DATE: Updated 05/31/03, Orig 01/10/03
# AUTHOR: ShaunC "Bulworth"
# PROJECT: RateMyStuff
# COPYRIGHT: PHPLabs.Com
# ----
# Description: Displays the picture associated with a tag (textid). You
# can use this script if you wish to place "static" links to an image
# somewhere within your site, e.g.
#
# <img src="getimage.php?tag=3e1fcce520209" alt="Image" border="0">
#

require_once('config.php');

#Return the image associated with a particular tag (textid)

$result = mysql_query("SELECT filename, url FROM pictures WHERE textid='$_GET[tag]' AND active=1");
if(mysql_num_rows($result) == 1){
  #Determine whether this picture is local or remote
  $myrow = mysql_fetch_array($result);
  $file = ($myrow[filename] == '') ? $myrow[url] : $imageurl . "/$myrow[filename]";
}
else{
  #Invalid tag was passed, display "unknown image" 
  $file = "$imagedir/unknown.gif";
}

#Stream the image to the browser
header('Content-type: image/gif');
ini_set('allow_url_fopen', 1);
readfile("$file");
?>