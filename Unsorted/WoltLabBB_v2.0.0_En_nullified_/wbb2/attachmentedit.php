<?php
$filename="attachment.php";

require("./global.php");

if(!$wbbuserdata['canuploadattachments'] || ($wbbuserdata['canstarttopic']==0 && $wbbuserdata['canreplyowntopic']==0 && $wbbuserdata['canreplytopic']==0)) {
 eval("\$tpl->output(\"".$tpl->get("window_close")."\");");
 exit();
}

if(isset($_POST['action']) && $_POST['action']=="del") {
 if(isset($attachmentid)) {
  if($wbbuserdata['ismod']!=1 && $wbbuserdata['issupermod']!=1 && $wbbuserdata['canuseacp']!=1 && $attachment['userid']!=0 && ($wbbuserdata['userid']==0 || $attachment['userid']!=$wbbuserdata['userid'])) access_error();
      
  @unlink("attachments/attachment-".$attachmentid.".".$attachment['attachmentextension']);
  $db->unbuffered_query("DELETE FROM bb".$n."_attachments WHERE attachmentid = '$attachmentid'",1);
 }
 eval("\$tpl->output(\"".$tpl->get("window_close")."\");");
 exit();
}
if(isset($_POST['action']) && $_POST['action']=="add") {
 if(isset($attachmentid)) {
  if($wbbuserdata['ismod']!=1 && $wbbuserdata['issupermod']!=1 && $wbbuserdata['canuseacp']!=1 && $attachment['userid']!=0 && ($wbbuserdata['userid']==0 || $attachment['userid']!=$wbbuserdata['userid'])) access_error();
    
  @unlink("attachments/attachment-".$attachmentid.".".$attachment['attachmentextension']);
  $db->unbuffered_query("DELETE FROM bb".$n."_attachments WHERE attachmentid = '$attachmentid'",1);
 }
 if($_FILES['attachment_file']['tmp_name'] && $_FILES['attachment_file']['tmp_name']!="none") {
  $attachment_file_extension = strtolower(substr(strrchr($_FILES['attachment_file']['name'],"."),1));
  $attachment_file_name2 = substr($_FILES['attachment_file']['name'], 0, (intval(strlen($attachment_file_extension))+1)*-1);
  $allowextensions=explode("\n",$wbbuserdata['allowedattachmentextensions']);
  if(in_array($attachment_file_extension,$allowextensions) && $_FILES['attachment_file']['size'] <= $wbbuserdata['maxattachmentsize']) {
   $db->query("INSERT INTO bb".$n."_attachments (attachmentid,attachmentname,attachmentextension,attachmentsize) VALUES (NULL,'".addslashes($attachment_file_name2)."','".addslashes($attachment_file_extension)."','".$_FILES['attachment_file']['size']."')");
   $attachmentid=$db->insert_id();
   
   if(@move_uploaded_file($_FILES['attachment_file']['tmp_name'],"attachments/attachment-".$attachmentid.".".$attachment_file_extension)) {
    @chmod ("attachments/attachment-".$attachmentid.".".$attachment_file_extension,0777);
    eval("\$tpl->output(\"".$tpl->get("attachmentedit_give_parent")."\");");
    exit();
   }
   else {
    $db->unbuffered_query("DELETE FROM bb".$n."_attachments WHERE attachmentid='$attachmentid'",1);
    $uploaderror=1;
   }
  }
  else $uploaderror=1;
  if($uploaderror==1) eval ("\$uploaderror = \"".$tpl->get("attachmentedit_error")."\";");
 }
 unset($attachmentid);
}

if(isset($attachmentid)) {
 if($wbbuserdata['ismod']!=1 && $wbbuserdata['issupermod']!=1 && $wbbuserdata['canuseacp']!=1 && $attachment['userid']!=0 && ($wbbuserdata['userid']==0 || $attachment['userid']!=$wbbuserdata['userid'])) access_error();
 
 $allowedattachmentextensions = str_replace("\n"," ",$wbbuserdata['allowedattachmentextensions']);
 $maxattachmentsize = round($wbbuserdata['maxattachmentsize']/1024);
 eval("\$tpl->output(\"".$tpl->get("attachmentedit_edit")."\");");
}
else {
 $allowedattachmentextensions = str_replace("\n"," ",$wbbuserdata['allowedattachmentextensions']);
 $maxattachmentsize = round($wbbuserdata['maxattachmentsize']/1024);
 eval("\$tpl->output(\"".$tpl->get("attachmentedit_add")."\");");
}
?>