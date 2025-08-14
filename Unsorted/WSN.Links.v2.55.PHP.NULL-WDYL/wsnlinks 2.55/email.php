<?php
include 'start.php';

$area = $language->title_divider . $language->title_emaillink; // supply area for template

if ($filled)
{ // mail link
 if ($thismember->canemail())
 {
  if (strstr($toemail, '@') && strstr($fromemail, '@'))
  {
   $ourlink = new onelink('dummy', $id);
   $title = $language->email_emaillinktitle;
   $title = linkreplacements($title, $ourlink);
   $message = $language->email_emaillinkbody; 
   $message = linkreplacements($message, $ourlink);
   $message = str_replace('{COMMENT}', $comment, $message);
   $message = str_replace('{DIRURL}', $settings->dirurl, $message);
   $message = str_replace('{FROMEMAIL}', $fromemail, $message);
   $message = str_replace('{CUSTOMTEXT}', $customtext, $message);
   $message = str_replace('{SENDERIP}', $HTTP_SERVER_VARS['REMOTE_ADDR'], $message);
   $adminaddress = $settings->email;
   mail("$toemail", $title, "$message", "From: $fromemail");
   if (!$template) $template = new template('redirect.tpl');
   $template->replace('{MESSAGE}', $language->emaillink_thanks);
   if ($returnto == '') $returnto = 'index.php';
   $template->replace('{DESTINATION}', $returnto);
  }
  else
  {
   if (!$template) $template = new template('redirect.tpl');
   $template->replace('{MESSAGE}', $language->emaillink_invalid);
   $template->replace('{DESTINATION}', "email.php?id=$id");
  }
 }
 else
 {
  if (!$template) $template = new template('redirect.tpl');
  $template->replace('{MESSAGE}', $language->emaillink_cannotemail);
  $template->replace('refresh', '');
  $template->replace('{DESTINATION}', 'index.php');
 }
}
else
{ // show form
 $nomember = true;
 if (!$template) $template = new template('email.tpl');
 $thislink = new onelink('dummy', $id);
 $ourcategory = new category('dummy', $thislink->catid);
 $template->replace('{NAVIGATION}', shownav($ourcategory));
 $template->text = linkreplacements($template->text, $thislink);
 $template->replace('{SENDERIP}', $HTTP_SERVER_VARS['REMOTE_ADDR']); 
}

require 'end.php';
?>