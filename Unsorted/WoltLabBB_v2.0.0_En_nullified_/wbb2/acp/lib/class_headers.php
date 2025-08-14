<?php
class headers {
 
 /** send headers **/
 function send() {
  global $sendheaders,$sendnocacheheaders,$gzip;
  
  if($sendheaders==1) {
   @header("HTTP/1.0 200 OK");
   @header("HTTP/1.1 200 OK");
   @header("Content-type: text/html");
  }

  if($sendnocacheheaders==1) {
   @header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); 
   @header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
   @header("Cache-Control: no-cache, must-revalidate");
   @header("Pragma: no-cache");
  }
  if($gzip==1) headers::compress();
 }
 
 /** compress output **/
 function compress() {
  global $_SERVER, $gziplevel;
  
  if(strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') && $gziplevel != 0) {
   ob_start("gzoutput");
   @header("Content-Encoding: gzip");
  }
 }
}
/* old gzoutput
function gzoutput($output) {
 global $gziplevel;
 return gzencode($output,$gziplevel);	
}
*/

/* new gzoutput */
function gzoutput($output) {
 global $gziplevel;
 
 $size = strlen($output);
 $newoutput = "\x1f\x8b\x08\x00\x00\x00\x00\x00";
 $newoutput .= substr(gzcompress($output, $gziplevel), 0, -4);
 $newoutput .= pack("V", $size);

 return $newoutput;
}
?>