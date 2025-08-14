<?php
require 'adminstart.php';

if ($thismember->isadmin())
{
if ($filled)
{
if ($demomode) die("This is a demo. You cannot execute instructions in the demo which could potentially harm the database. Please click your browser's back button.");
   if ($type == 'templatereplace')
   {
    $previous = decodeit($previous);
    $new = decodeit($new);
    templatesglobalreplace($previous, $new);
    $template = new template("../$templatesdir/redirect.tpl");
    $template->replace("{MESSAGE}", "Template replacements complete.");
    $template->replace("{DESTINATION}", "advanced.php");
   }
   else if ($type == 'languagereplace')
   {
    $previous = decodeit($previous);
    $new = decodeit($new);
    $list = explode(',', $language->allnames());
    for ($x=0; $x<sizeof($list); $x++)
    {
     $thisone = $list[$x];
     $orig = $language->$thisone;
     $language->$thisone = str_replace($previous, $new, $orig);
     if ($language->$thisone != $orig)
     {
      $language->updateitem(encodesqlline($thisone));
      $changed = addslashes(stripall($language->$thisone));
      $orig = addslashes(stripall($orig));
      $message .= "<p>Altering $thisone from \"$orig\" to \"$changed\"</p>"; 
     }
    }   
    $template = new template("../$templatesdir/redirect.tpl");
    $template->text = $message . $template->text;
    $template->replace("{MESSAGE}", "Language replacements complete. (You may wish to review the list of changed items above.)");
    $template->replace("{SECONDSDELAY}", "100000");
    $template->replace("{DESTINATION}", "advanced.php");
   }
   else if ($type=='getfield')
   {
    if (!$template) $template = new template("../$templatesdir/blank.tpl");
    $getem = $db->select($field, $table, 'id>0', 'ORDER BY id ASC', '');
    $num = $db->numrows($getem);
    for ($count=0; $count<$num; $count++)
    {
	$row = $db->row($getem);
	$theemail = $row[0];
  	if ($theemail != '')
	{
	  $template->text .= $theemail . $sep;
	}
    }
   $template->text .= '<p align=center>[ <a href=advanced.php>Back</a> ]</p>';
   }
   else if ($type == 'resetfields')
   {

    if (is_array($resetfields)) $resetfields = implode(',', $resetfields);
    $resetfields = str_replace('selected', '', $resetfields);
    $settings->resetfields = $resetfields;
    $settings->resetdelay = $resetdelay;
    $settings->resetscript = $resetscript;
    $settings->update('resetfields');
    $settings->update('resetdelay');
    $settings->update('resetscript');
    if (!$template) $template = new template("../$templatesdir/redirect.tpl");
    $template->replace('{MESSAGE}', 'The timed action has been set.');
    $template->replace('{DESTINATION}', 'advanced.php');
   }
   else if ($type == 'setlinks')
   {
    if ($condition == 'contains') $doit = $db->update('linkstable', $field, $fieldvalue, "$searchfield LIKE '%$searchvalue%'");
    else if (is_numeric($searchvalue)) $doit = $db->update('linkstable', $field, $fieldvalue, "$searchfield $condition $searchvalue");
    else $doit = $db->update('linkstable', $field, $fieldvalue, "$searchfield $condition '$searchvalue'");
    if ( ($field == 'sumofvotes') || ($field == 'votes') )
    {
     $doit = $db->update('linkstable', 'rating', 'sumofvotes/votes', "$searchfield $condition $searchvalue");
    }
    if (!$template) $template = new template("../$templatesdir/redirect.tpl");
    $template->replace('{MESSAGE}', 'The links have been updated.');
    $template->replace('{DESTINATION}', 'advanced.php');
   }
   else if ($type == 'setcoms')
   {
    if ($condition == 'contains') $doit = $db->update('commentstable', $field, $fieldvalue, "$searchfield LIKE '%$searchvalue%'");
    else $doit = $db->update('commentstable', $field, $fieldvalue, "$searchfield $condition '$searchvalue'");
    if (!$template) $template = new template("../$templatesdir/redirect.tpl");
    $template->replace('{MESSAGE}', 'The comments have been updated.');
    $template->replace('{DESTINATION}', 'advanced.php');
   }
   else if ($type == 'setmems')
   {
    if ($condition == 'contains') $doit = $db->update('memberstable', $field, $fieldvalue, "$searchfield LIKE '%$searchvalue%'");
    else $doit = $db->update('memberstable', $field, $fieldvalue, "$searchfield $condition '$searchvalue'");
    if (!$template) $template = new template("../$templatesdir/redirect.tpl");
    $template->replace('{MESSAGE}', 'The members have been updated.');
    $template->replace('{DESTINATION}', 'advanced.php');
   }   
   else if ($type == 'setcats')
   {
    if ($condition == 'contains') $doit = $db->update('categoriestable', $field, $fieldvalue, "$searchfield LIKE %$searchvalue%");
    else $doit = $db->update('categoriestable', $field, $fieldvalue, "$searchfield $condition $searchvalue");   
    if (!$template) $template = new template("../$templatesdir/redirect.tpl");
    $template->replace('{MESSAGE}', 'The categories have been updated.');
    $template->replace('{DESTINATION}', 'advanced.php');
   }   
   else if ($type == 'changepassword')
   {
    if ($newpassconfirm == $newpass)
	{
	 $newpassword = md5($newpass);
	 $settings->password = $newpassword;
	 $settings->update('password');
	 if (!$template) $template = new template("../$templatesdir/redirect.tpl");
     $template->replace('{MESSAGE}', 'Your password has been changed.');
     $template->replace('{DESTINATION}', 'index.php?action=logout');
     setcookie('admin', md5($newpass), time()+1000000);
	}
	else
	{
     if (!$template) $template = new template("../$templatesdir/redirect.tpl");
     $template->replace('{MESSAGE}', 'You typed different passwords in each box, so your password has not been changed.');
     $template->replace('{DESTINATION}', 'advanced.php');
	}
   }
  else if ($type == 'dophp')
  {
if (!$demomode)
{
    $phpfiletitle = $_FILES['phpfiletitle']['tmp_name'];
    if ($phpfiletitle != '')
    {
     move_uploaded_file($phpfiletitle, $settings->uploadpath ."temp.php");
     $phpfiletitle = $settings->uploadpath . "temp.php";
     $phpfiletext = fileread($phpfiletitle);
     unlink($settings->uploadpath . "temp.php");
     $test = OutputPhpDocument($phpfiletext);
     if (!$template) $template = new template("../$templatesdir/redirect.tpl");
     $template->replace('{MESSAGE}', 'The PHP code from the file has been executed.');
     $template->replace('{DESTINATION}', 'advanced.php');
    }
    else
    {
     $test = OutputPhpDocument($phptext);
     if (!$template) $template = new template("../$templatesdir/redirect.tpl");
     $template->replace('{MESSAGE}', 'Your PHP code has been executed.');
     $template->replace('{DESTINATION}', 'advanced.php');
    }
}
 }
  else if ($type == 'sql')
  {
   if (!$demomode)
   {
    $filetitle = $_FILES['filetitle']['tmp_name'];
    if ($filetitle != '')
    {
     move_uploaded_file($filetitle, $settings->uploadpath ."temp.sql");
     $filetitle = $settings->uploadpath . "temp.sql";
     $sql = fileread($filetitle);
     unlink($settings->uploadpath . "temp.sql");
     $docreation = processsql($sql);
     if (!$template) $template = new template("../$templatesdir/redirect.tpl");
     $template->replace('{MESSAGE}', 'The SQL from the file has been executed.');
     $template->replace('{DESTINATION}', 'advanced.php');	
    }
    else
    {
     $sqltext = encodesql($sqltext);
     $test = processsql($sqltext);
     if (!$template) $template = new template("../$templatesdir/redirect.tpl");
     $template->replace('{MESSAGE}', 'MySQL returned this result (if nothing, it worked): ' . $test);
     $template->replace('{DESTINATION}', 'advanced.php');
    }
   }
  }
 }
 else
 {
  if (!$template) $template = new template("../$templatesdir/admin/advanced.tpl");
  $template->replace('{TEMPLATESDIR}', $templatesdir);
  $resetfieldsmenu = fieldselector('linkfields', $settings->resetfields);
  $template->replace('{RESETFIELDSMENU}', $resetfieldsmenu);  
  $template->replace('{RESETDELAY}', $settings->resetdelay);
  $template->replace('{RESETSCRIPT}', $settings->resetscript);
 }
} 

require 'adminend.php';  


?>