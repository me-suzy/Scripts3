<?php
require 'adminstart.php';

if ($thismember->isadmin())
{ // user has correct password, so they can enter
 if ($filled)
 {
  if ($action == 'edit')
  {
   if ($deleteit)
   {
    $db->delete('membergroupstable', "id=$id");
    if (!$template) $template = new template("../$templatesdir/redirect.tpl");
    $template->replace('{MESSAGE}', str_replace('{TYPE}', 'deleted', $language->admin_usergroupsdone));
    $template->replace('{DESTINATION}', 'usergroups.php');
   }
   else
   {
    $usergroupstuff = $settings->usergroupfields;
    $usergrouplist = explode(',', $usergroupstuff);
    $num = sizeof($usergrouplist);
    for ($count=0; $count<$num; $count++)
    { 
     if (($usergrouplist[$count] == 'id') || ($usergrouplist[$count] == 'title') || ($usergrouplist[$count] == 'limitlinks') || ($usergrouplist[$count] == 'limitlinksdaily'))
     {
      $valuetoadd = $$usergrouplist[$count];
     }
     else
     {
      if ($$usergrouplist[$count] == 'on') $valuetoadd = '1';	
      else if (($$usergrouplist[$count] == 'off') || ($$usergrouplist[$count] == '')) $valuetoadd = '0';	
      else $valuetoadd = $$usergrouplist[$count];
     }
     $db->update('membergroupstable', $usergrouplist[$count], $valuetoadd, 'id='. $id);
    }  
    if (!$template) $template = new template("../$templatesdir/redirect.tpl");
    $template->replace('{MESSAGE}', str_replace('{TYPE}', 'edited', $language->admin_usergroupsdone));
    $template->replace('{DESTINATION}', 'usergroups.php');
   }
  }
  else if ($action == 'add')
  {
   $usergroupstuff = $settings->usergroupfields;
   $usergrouplist = explode(',', $usergroupstuff);
   $num = sizeof($usergrouplist);
   for ($count=0; $count<$num; $count++)
   {
    $starttoadd = "'";
    if (($usergrouplist[$count] == 'id') || ($usergrouplist[$count] == 'title') || ($usergrouplist[$count] == 'limitlinks') || ($usergrouplist[$count] == 'limitlinksdaily'))
    {
     $middletoadd = $$usergrouplist[$count];
    }
    else
    {
     if ($$usergrouplist[$count] == 'on') $middletoadd = '1';	
     else if ($$usergrouplist[$count] == 'off') $middletoadd = '0';	
     else if ($usergrouplist[$count] == 'id') $middletoadd = ' ';
     else $middletoadd = $$usergrouplist[$count];
    }
    $endtoadd = "'";
    $valuetoadd = $starttoadd . $middletoadd . $endtoadd;
    $valuelist .= $valuetoadd; 
    if ($count<($num-1)) $valuelist .= ',';
   }  
   $db->insert('membergroupstable', $settings->usergroupfields, $valuelist);
   if (!$template) $template = new template("../$templatesdir/redirect.tpl");
   $template->replace('{MESSAGE}', str_replace('{TYPE}', 'added', $language->admin_usergroupsdone));
   $template->replace('{DESTINATION}', 'usergroups.php');   
  } 
 }
 else
 {
  if (!$template) $template = new template("../$templatesdir/admin/usergroups.tpl");
  $sub = templateextract($template->text, '<!-- BEGIN ROW -->', '<!-- END ROW -->');
  $template->replace($sub, '{SUB}');
  $query = $db->select($settings->usergroupfields, 'membergroupstable', 'id>0', '', '');
  $num = $db->numrows($query);
  $fullset = '';
  for ($count=0; $count<$num; $count++)
  {
   $row = $db->row($query);
   $thispart = $sub;
   $usergroupstuff = $settings->usergroupfields;
   $usergrouplist = explode(',', $usergroupstuff);
   $xnum = sizeof($usergrouplist);
   for ($xcount=0; $xcount<$xnum; $xcount++)
   {
    $thisvar = '{'. strtoupper($usergrouplist[$xcount]) .'}';
    if (($usergrouplist[$xcount] == 'id') || ($usergrouplist[$xcount] == 'title') || ($usergrouplist[$xcount] == 'limitlinks') || ($usergrouplist[$xcount] == 'limitlinksdaily'))
	{ 
	 $thispart = str_replace($thisvar, $row[$xcount], $thispart);
	}
    else
    { 
     if ($row[$xcount] == 1) $isselected = 'checked'; else $isselected = '';
     $thispart = str_replace($thisvar, $isselected, $thispart);
    }
   }
   $fullset .= $thispart;
  }
  $template->replace('{SUB}', $fullset);
  $template->replace('{LIMITLINKS}', '');
  $template->replace('{LIMITLINKSDAILY}', '');
 }
}
require 'adminend.php';

?>