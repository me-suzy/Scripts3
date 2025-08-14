<?php
include 'start.php';

if ($senderemail == '') $senderemail = $thismember->email;
if ($senderemail == '') $senderemail = $thismember->name;
if ($id == '') $id = $sendto; 
if ($title == '') $title = $subject; 

if ($filled && $senderemail)
{ // mail to member
 $recipient = new member('id', $id);
 if ($thismember->groupcanemailmembers && $recipient->allowuseremail != 'no')
 {
  $toemail = $recipient->email;
  sendemail("$toemail", $title, "$message", "From: $senderemail");
  if (!$template) $template = new template('redirect.tpl');
  $template->replace('{MESSAGE}', $language->emailmember_thanks);
  $template->replace('{DESTINATION}', $returnto);
 }
}
else
{ // show form
 $nomember = true;
 if (!$thismember->groupcanemailmembers)
 {
  if (!$template) $template = new template('redirect.tpl');
  $template->replace('{MESSAGE}', $language->emailmember_cannotemail);
  $template->replace('{DESTINATION}', $returnto);
 }
 $recipient = new member('id', $id);
 if ($recipient->allowuseremail == 'no' || $recipient->email == '')
 {
  if (!$template) $template = new template('redirect.tpl');
  $template->replace('{MESSAGE}', $language->emailmember_notallowed);
  $template->replace('{DESTINATION}', $returnto);
 }
 if ($filled && !$senderemail)
 {
  if (!$template) $template = new template('redirect.tpl');
  $template->replace('{MESSAGE}', $language->emailmember_mustfill);
  $template->replace('{DESTINATION}', 'emailmember.php?id='. $id);
 }
 if (!$template) $template = new template('emailmember.tpl');
 $recipient = new member('id', $id);
 $template->memberreplacements($recipient);
}

$area = $language->title_emailmember;

require 'end.php';
?>