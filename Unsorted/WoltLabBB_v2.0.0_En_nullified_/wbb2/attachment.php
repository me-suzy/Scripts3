<?php
$filename="attachment.php";

require ("global.php");

if($wbbuserdata['candownloadattachments']==0) access_error();

if(isset($attachmentid)) {
 $db->query("UPDATE bb".$n."_attachments SET counter=counter+1 WHERE attachmentid = '$attachmentid'"); 
 
 $extension=$attachment['attachmentextension'];
 if($extension=="gif") $mime_type = 'image/gif';
 elseif($extension=="jpg" || $extension=="jpeg") $mime_type = 'image/jpeg';
 elseif($extension=="png") $mime_type = 'image/png';
 else {
  $mime_type = (USR_BROWSER_AGENT == 'IE' || USR_BROWSER_AGENT == 'OPERA') ? 'application/octetstream' : 'application/octet-stream';
  $content_disp = (USR_BROWSER_AGENT == 'IE') ? 'inline; ' : 'attachment; ';
 }
 header('Content-Type: '.$mime_type);
 header('Content-disposition: '.$content_disp.'filename="'.$attachment['attachmentname'].'.'.$attachment['attachmentextension'].'"');
 header('Pragma: no-cache');
 header('Expires: 0');
 readfile("attachments/attachment-".$attachment['attachmentid'].".".$attachment['attachmentextension']);
}
else eval("error(\"".$tpl->get("error_falselink")."\");");
?>
