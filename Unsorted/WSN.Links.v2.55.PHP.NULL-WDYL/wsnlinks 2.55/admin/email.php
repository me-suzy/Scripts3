<?php

require 'adminstart.php';

if ($thismember->isadmin())
{ // user has correct password, so they can enter
 if ($filled)
 { // incoming: $subject, $message, $field, $condition, $fieldvalue 
if ($demomode) die("This is a demo. You are not allowed to use it to spam. Since it is too easy for a spammer to exploit the ability to send emails at will, you are not allowed to send emails in the demo. Please click your browser's back button.");
  if ($type == 'members')
  {
   if ($condition == 'LIKE') $fieldvalue = "%". $fieldvalue ."%";
   $query = $db->select($settings->memberfields, 'memberstable', "$field $condition '$fieldvalue' AND allowemail != 'no'", '', '');
   $num = $db->numrows($query);
   for ($count=0; $count<$num; $count++)
   {
    $row = $db->row($query);   
    $thismem = new member('row', $row);
    $submitter = $thismem->email;
    $adminaddress = $settings->email;
    $thissubject = stripslashes(memberreplacements($subject, $thismem));
    $thismessage = stripslashes(memberreplacements($message, $thismem));
    $thismessage = str_replace('{DIRURL}', $settings->dirurl, $thismessage);
    if (($submitter != '') && ($adminaddress != ''))
    {
     sendemail("$submitter", "$thissubject", "$thismessage", "From: $adminaddress");
     $sent++;
    }
   }
  }
  else
  {
   if ($condition == 'LIKE') $fieldvalue = "%". $fieldvalue ."%";
   $query = $db->select($settings->linkfields, 'linkstable', "$field $condition '$fieldvalue'", '', '');
   $num = $db->numrows($query);
   for ($count=0; $count<$num; $count++)
   {
    $row = $db->row($query);
    $thislink = new onelink('row', $row);
    $submitter = $thislink->email;
    $owner = $thislink->ownerid;
    if ($owner > 0) 
    {
     $mem = new member('id', $owner);
     if ($mem->email != '' && $submitter == '') $submitter = $mem->email;
     if ($mem->allowemail == 'no') $submitter = ''; // don't send if they don't want it
    } 
    $adminaddress = $settings->email;
    $thissubject = stripslashes(linkreplacements($subject, $thislink));
    $thismessage = stripslashes(linkreplacements($message, $thislink));
    $message = str_replace('{DIRURL}', $settings->dirurl, $message);
    if (($submitter != '') && ($adminaddress != ''))
    {
     sendemail("$submitter", "$thissubject", "$thismessage", "From: $adminaddress");
     $sent++;
    }
   }
  }
  if (!$template) $template = new template("../$templatesdir/redirect.tpl");
  $template->replace('{MESSAGE}', "$sent e-mails have been dispatched.");
  $template->replace('{DESTINATION}', 'index.php');
 }
 else
 {
  if (!$template) $template = new template("../$templatesdir/admin/email.tpl");
 }
}
require 'adminend.php';

?>