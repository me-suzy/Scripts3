<?php
include 'start.php';

$area = $language->title_divider . $language->title_reportlink; // supply area for template

if ($filled)
{ // mail report
  $doit = $db->select($settings->linkfields, 'linkstable', "id=$id", '', '');
  $row = $db->row($doit);
  $ourlink = new onelink('row', $row);
  $message = $language->email_reportlinkbody; 
  $message = linkreplacements($message, $ourlink);
  $message = str_replace('{COMMENT}', $comment, $message);
  $message = str_replace('{DIRURL}', $settings->dirurl, $message);
  $message = str_replace('{PROBLEMTYPE}', $problemtype, $message);
  $message = decodeit($message);
  $emailtitle = $language->email_reportlinktitle;
  $emailtitle = str_replace('{PROBLEMTYPE}', $problemtype, $emailtitle);
  $adminaddress = $settings->email;
  sendemail("$adminaddress", $emailtitle, "$message", "From: $adminaddress");

  if (!$template) $template = new template('redirect.tpl');
  $template->replace('{MESSAGE}', $language->reportlink_thanks);
  $template->replace('{DESTINATION}', $returnto);
}
else
{
 if ($action == 'verify')
 {
  include 'admin/admincommonfuncs.php';
  $stuff = geturl("admin/verify.txt");
  $template = new template("blank");
  $template->text = $stuff;
 }
 // show form
 if (!$template) $template = new template('report.tpl');
 $thislink = new onelink('id', $id);
 $ourcategory = new category('id', $thislink->catid);
 $template->replace('{NAVIGATION}', shownav($ourcategory));
 $template->text = linkreplacements($template->text, $thislink);
}

require 'end.php';
?>