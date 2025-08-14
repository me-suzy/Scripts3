<?php
include 'start.php';

$area = $language->title_divider . $language->title_reportcomment; // supply area for template

if ($filled)
{ // mail report
  $doit = $db->select($settings->commentfields, 'commentstable', "id=$id", '', '');
  $row = $db->row($doit);
  $thiscomment = new comment('row', $row);
  $message = $language->email_reportcommentbody; 
  $message = commentreplacements($message, $thiscomment);
  $thislink = new onelink('id', $thiscomment->linkid);
  $message = linkreplacements($message, $thislink);
  $message = memberreplacements($message, $thismember);
  $message = str_replace('{COMMENT}', $comment, $message);
  $message = decodeit($message);
  $emailtitle = $language->email_reportcommenttitle;
  $adminaddress = $settings->email;
  sendemail($adminaddress, $emailtitle, $message, "From: $adminaddress");

  if (!$template) $template = new template('redirect.tpl');
  $template->replace('{MESSAGE}', $language->reportcomment_thanks);
  if (!strstr($returnto, 'comments.php')) $returnto = 'comments.php?id='. $thiscomment->linkid;
  $template->replace('{DESTINATION}', $returnto);
}
else
{
 // show form
 if (!$template) $template = new template('reportcomment.tpl');
 $thiscomment = new comment('id', $id);
 $thislink = new onelink('id', $thiscomment->linkid);
 $ourcategory = new category('id', $thislink->catid);
 $template->replace('{NAVIGATION}', shownav($ourcategory));
 $template->commentreplacements($thiscomment);
 $template->linkreplacements($thislink);
 $template->categoryreplacements($ourcategory);
}

require 'end.php';
?>