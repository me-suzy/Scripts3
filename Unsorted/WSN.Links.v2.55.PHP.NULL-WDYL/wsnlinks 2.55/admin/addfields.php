<?php
require 'adminstart.php';

if ($thismember->isadmin())
{
if ($status=='done')
{ // addfields.php?status=done&type=link/cat/setting&newfield=value
 if (($action=='add') && ($newfield != 'sumbmit') && (!strstr($newfield, ' ')) && (!strstr($newfield, ':')) && (!strstr($newfield, ';')) && (!strstr($newfield, ',')))
 {
  if ($type == 'link')
  {
   $settings->linkfields .= ','. $newfield;
   if (strstr($settings->linkfields, $newfield))
   {
    $query = $db->alter('linkstable', 'ADD', $newfield, ' TEXT NOT NULL');
   }
   $tempvar = '{LINK'. strtoupper($newfield) .'}';
   $suggestfile = "../$templatesdir/suggestlink.tpl";
   $editfile = "../$templatesdir/edit.tpl";
  }
  else if ($type == 'member')
  {
   $settings->memberfields .= ','. $newfield;
   if (strstr($settings->memberfields, $newfield))
   {
    $query = $db->alter('memberstable', 'ADD', $newfield, ' TEXT NOT NULL');
   }
   $tempvar = '{MEMBER'. strtoupper($newfield) .'}';
   $suggestfile = "../$templatesdir/register.tpl";
   $editfile = "../$templatesdir/editmembers.tpl";
  }
  else if ($type == 'cat')
  { 
   $settings->categoryfields .= ','. $newfield;
   if (strstr($settings->categoryfields, $newfield)) $query = $db->alter('categoriestable', 'ADD', $newfield, ' TEXT NOT NULL');
   $tempvar = '{CAT'. strtoupper($newfield) .'}';
   $suggestfile = "../$templatesdir/suggestcat.tpl";
   $editfile = "../$templatesdir/editcat.tpl";   
  }
  else if ($type == 'comment')
  { 
   $settings->commentfields .= ','. $newfield;
   if (strstr($settings->commentfields, $newfield)) $query = $db->alter('commentstable', 'ADD', $newfield, ' TEXT NOT NULL');
   $tempvar = '{COMMENT'. strtoupper($newfield) .'}';  
   $suggestfile = "../$templatesdir/viewcomments.tpl"; 
   $editfile = "../$templatesdir/editcomments.tpl";
  }  
  else if ($type == 'setting')
  {
   $result = $db->insert('metatable', 'name,content', "'$newfield',' '");
   $tempvar = '{'. strtoupper($newfield) .'}';  
   $editfile = "../$templatesdir/admin/prefs.tpl";   
  }
  else if ($type == 'usergroup')
  {
   $settings->usergroupfields .= ','. $newfield;
   if (strstr($settings->usergroupfields, $newfield)) $query = $db->alter('membergroupstable', 'ADD', $newfield, ' TEXT NOT NULL');
   $tempvar = '{MEMBERGROUP'. strtoupper($newfield) .'}';  
   $editfile = "../$templatesdir/admin/usergroups.tpl";   
  }
  // now fix templates
  $edit = fileread($editfile);
  if (($type != 'setting') && ($type != 'usergroup'))
  { 
   $suggest = fileread($suggestfile);
   $verify1 = strstr($suggest, '<!-- INSERT NEW FIELDS HERE -->');
  }
  if ($selectoptions != '')
  {
   $selectoptions = explode(',', $selectoptions);
   for ($q=0; $q<sizeof($selectoptions); $q++)
   {
    $thisop = ltrim($selectoptions[$q]);
    if ($selecttype == 'checkbox') $optionlist .= '<input type="checkbox" name="'. $newfield .'[]" value="'. $thisop .'"> '. $thisop .' ';
    else if ($selecttype == 'radio') $optionlist .= '<input type="radio" name="'. $newfield .'[]" value="'. $thisop .'"> '. $thisop .' ';
    else $optionlist .= '<option value="'. $thisop .'">'. $thisop .'</option>';
   }
  }
  if ($newfieldtitle == '') $newfieldtitle = $newfield;
  $thenewcode = '<tr>
 <td class="labelscolumn"><span class="labels">'. $newfieldtitle .':</span></td>
 <td class="optionscolumn">';
  if ($selecttype == 'checkbox' || $selecttype == 'multiselect') $thenewcode .= '<input type="hidden" name="'. $newfield .'[123]" value="">';
 if ($selecttype == 'checkbox') 
 {
  if ($optionlist != '') $thenewcode .= $optionlist;
  else $thenewcode .= '<input type="checkbox" name="'. $newfield .'" value="'. $newfield .'" '. $tempvar .'>';
 }
 else if ($selecttype == 'multiselect') $thenewcode .= '<select multiple name="'. $newfield .'[]">'. $optionlist .'</select>';
 else if ($selecttype == 'radio') $thenewcode .= $optionlist;
 else if ($selecttype == 'textbox') $thenewcode .= '<textarea name="'. $newfield .'" rows="5" cols="45">'. $tempvar .'</textarea>'; 
 else if ($selecttype == 'selector') $thenewcode .= '<select name="'. $newfield .'">'. $optionlist .'</select>';
 else $thenewcode .= '<input type="text" name="'. $newfield .'" size="{STANDARDSIZE}" value="'. $tempvar .'">';
 $thenewcode .= '</td>
</tr>

<!-- INSERT NEW FIELDS HERE -->';
  if (($verify1) && ($showtouser=='on'))
  {
   $suggest = str_replace('<!-- INSERT NEW FIELDS HERE -->', $thenewcode, $suggest);
   if ($selecttype == 'hidden') $verify1 = true;
   else $verify1 = filewrite($suggestfile, $suggest);
  }
  if (($type == 'setting') || ($type == 'usergroup')) $verify1 = true;
  $verify2 = strstr($edit, '<!-- INSERT NEW FIELDS HERE -->');
  if ($verify1 && $verify2)
  {
   if ($type == 'usergroup')
   {
    $templatevariable = '{'. strtoupper($newfield) .'}';
    $edit = str_replace('<input type="submit" value="Update Usergroup">', '<input type="checkbox" name="'. $newfield .'" '. $templatevariable .'>'. $newfield .'<br><input type="submit" value="Update Usergroup">', $edit);
	$edit = str_replace('<input type="submit" value="Create Usergroup">', '<input type="checkbox" name="'. $newfield .'" '. $templatevariable .'>'. $newfield .'<br><input type="submit" value="Create Usergroup">', $edit);
    $verify2 = filewrite($editfile, $edit);
   }
   else if ($type == 'setting')
   {
    $edit = str_replace('<!-- INSERT NEW FIELDS HERE -->', '<br><br><span class="labels">'. $newfieldtitle .':</span> <input type="text" name="'. $newfield .'" size="{STANDARDSIZE}" value="'. $tempvar .'">

<!-- INSERT NEW FIELDS HERE -->', $edit);
    if ($selecttype == 'hidden') $verify2 = true;
	else $verify2 = filewrite($editfile, $edit);
   }
   else
   {
    if ($useredit) $edit = str_replace('<!-- INSERT NEW FIELDS HERE -->', $thenewcode, $edit);
	else
	{
         $thenewcode = str_replace('
<!-- INSERT NEW FIELDS HERE -->', '', $thenewcode);
         $thenewcode = '<IF {MEMBERGROUPISADMIN}>'. $thenewcode .'</IF>';
         $thenewcode .= '
<!-- INSERT NEW FIELDS HERE -->';
	 $edit = str_replace('<!-- INSERT NEW FIELDS HERE -->', $thenewcode, $edit);
	}
    if ($selecttype == 'hidden') $verify2 = true;
	else $verify2 = filewrite($editfile, $edit);
   }
  }
  if (!$template) $template = new template("../$templatesdir/redirect.tpl");
  $template->replace('refresh', ''); // pause so we can instruct
  if ($verify1 && $verify2)
  {
   $instructions = $language->admin_addfieldworked;
  }
  else if ($verify1 && ($type=='settings'))
  {
   $instructions = $language->admin_addfieldworkedsetting;
  }
  else if ($verify1 && ($type=='usergroup'))
  {
   $instructions = $language->admin_addfieldworkedusergroup;
  }

  else 
  {
   if ($type == 'link') $instructions = $language->admin_addfieldlink;
   if ($type == 'cat') $instructions = $language->admin_addfieldcat;
   if ($type == 'member') $instructions = $language->admin_addfieldmember;
   if ($type == 'comment') $instructions = $language->admin_addfieldcomment;   
   if ($type == 'setting') $instructions = $language->admin_addfieldsetting;
   if ($type == 'usergroup') $instructions = $language->admin_addfieldusergroup;
  } 
  if ($type == 'cat') $type = 'category'; // make more user friendly
  $instructions = str_replace('{TYPE}', $type, $instructions);
  $instructions = str_replace('{NEWTEMPVAR}', $tempvar, $instructions);  
  $instructions = str_replace('{NEWVAR}',  $newfield, $instructions);
  $template->replace('{MESSAGE}', $instructions);
  $template->replace('{DESTINATION}', 'addfields.php');     
 }

 else if ($action == 'rename')
 {
  if ($type == 'link')
  {
   $db->alter('linkstable', 'change', "`$newfield` `$newname`", '');
   $settings->linkfields = str_replace(",$newfield,", ",$newname,", $settings->linkfields);   
   if (strstr($settings->linkfields, $newfield)) { $settings->linkfields = str_replace(",$newfield,", ",$newname,", $settings->linkfields .','); $settings->linkfields = trimright($settings->linkfields, 1); }
   $tempvar = $pref . strtoupper($newname) .'}';
   $suggestfile = "../$templatesdir/suggestlink.tpl";
   $editfile = "../$templatesdir/edit.tpl";   
  }
  else if ($type == 'usergroup')
  {
   $db->alter('membergroupstable', 'change', "`$newfield` `$newname`", '');
   $settings->usergroupfields = str_replace(",$newfield,", ",$newname,", $settings->usergroupfields);
   if (strstr($settings->usergroupfields, $newfield)) { $settings->usergroupfields = str_replace(",$newfield,", ",$newname,", $settings->usergroupfields .','); $settings->usergroupfields = trimright($settings->usergroupfields, 1); }
   $editfile = "../$templatesdir/admin/usergroups.tpl";
  }
  else if ($type == 'member')
  {
   $db->alter('memberstable', 'change', "`$newfield` `$newname`", '');
   $settings->memberfields = str_replace(",$newfield,", ',', $settings->memberfields);
   if (strstr($settings->memberfields, $newfield)) { $settings->memberfields = str_replace(",$newfield,", ",$newname,", $settings->memberfields .','); $settings->memberfields = trimright($settings->memberfields, 1); }
   $tempvar = '{MEMBER'. strtoupper($newfield) .'}';
   $suggestfile = "../$templatesdir/register.tpl";
   $editfile = "../$templatesdir/editmembers.tpl";   
  }
  else if ($type == 'cat')
  { 
   $db->alter('categoriestable', 'change', "`$newfield` `$newname`", '');
   $settings->categoryfields = str_replace(",$newfield,", ",$newname,", $settings->categoryfields);
   if (strstr($settings->memberfields, $newfield)) { $settings->categoryfields = str_replace(",$newfield,", ",$newname,", $settings->categoryfields .','); $settings->categoryfields = trimright($settings->categoryfields, 1); }
   $tempvar = '{CAT'. strtoupper($newfield) .'}';
   $suggestfile = "../$templatesdir/suggestcat.tpl";
   $editfile = "../$templatesdir/editcat.tpl";   
  }
  else if ($type == 'comment')
  {
   $db->alter('commentstable', 'change', "`$newfield` `$newname`", '');
   $settings->commentfields = str_replace(",$newfield,", ",$newname,", $settings->commentfields);
   if (strstr($settings->commentfields, $newfield)) { $settings->commentfields = str_replace(",$newfield,", ",$newname,", $settings->commentfields .','); $settings->commentfields = trimright($settings->commentfields, 1); }
   $tempvar = '{COMMENT'. strtoupper($newfield) .'}';  
   $suggestfile = "../$templatesdir/viewcomments.tpl"; 
   $editfile = "../$templatesdir/editcomments.tpl";
  }
  else if ($type == 'setting')
  {
   $db->update('metatable', "name", "$newname", "name='$newfield'");
   $tempvar = '{'. strtoupper($newfield) .'}';  
   $editfile = "../$templatesdir/admin/prefs.tpl";   
  }
  $template = new template("redirect.tpl");
  $template->replace('{MESSAGE}', "Your field has been renamed. You must now update your templates wherever you used the old name to reflect the new name.");
  $template->replace('{DESTINATION}', 'addfields.php');    
  $template->replace('{SECONDSDELAY}', '10');      
 } 
 else if ($action == 'remove')
 {
  if ($type == 'link')
  {
   $db->alter('linkstable', 'drop', $newfield, '');
   $before = strlen($settings->linkfields);
   $settings->linkfields = str_replace(",$newfield,", ',', $settings->linkfields);
   $after = strlen($settings->linkfields);
   if ($before == $after) { $settings->linkfields = str_replace(",$newfield,", '', $settings->linkfields .','); $settings->linkfields = trimright($settings->linkfields, 1); }
   $tempvar = '{LINK'. strtoupper($newfield) .'}';
   $suggestfile = "../$templatesdir/suggestlink.tpl";
   $editfile = "../$templatesdir/edit.tpl";   
  }
  else if ($type == 'usergroup')
  {
   $db->alter('membergroupstable', 'drop', $newfield);
   $before = strlen($settings->usergroupfields);
   $settings->usergroupfields = str_replace(','. $newfield .',', ',', $settings->usergroupfields);
   $before = strlen($settings->usergroupfields);
   if ($before == $after) { $settings->usergroupfields = str_replace(','. $newfield .',', '', $settings->usergroupfields .','); $settings->usergroupfields = trimright($settings->usergroupfields, 1); }
   $editfile = "../$templatesdir/admin/usergroups.tpl";
  }
  else if ($type == 'member')
  {
   $db->alter('memberstable', 'drop', $newfield, '');
   $before = strlen($settings->memberfields);
   $settings->memberfields = str_replace(",$newfield,", ',', $settings->memberfields);
   $after = strlen($settings->memberfields);
   if ($before == $after) { $settings->memberfields = str_replace(",$newfield,", '', $settings->memberfields .','); $settings->memberfields = trimright($settings->memberfields, 1); }
   $tempvar = '{MEMBER'. strtoupper($newfield) .'}';
   $suggestfile = "../$templatesdir/register.tpl";
   $editfile = "../$templatesdir/editmembers.tpl";   
  }
  else if ($type == 'cat')
  { 
   $db->alter('categoriestable', 'drop', $newfield, '');     
   $before = strlen($settings->categoryfields);
   $settings->categoryfields = str_replace(",$newfield,", ',', $settings->categoryfields);
   $after = strlen($settings->categoryfields);
   if ($before == $after) { $settings->categoryfields = str_replace(",$newfield,", '', $settings->categoryfields .','); $settings->categoryfields = trimright($settings->categoryfields, 1); }
   $tempvar = '{CAT'. strtoupper($newfield) .'}';
   $suggestfile = "../$templatesdir/suggestcat.tpl";
   $editfile = "../$templatesdir/editcat.tpl";   
  }
  else if ($type == 'comment')
  {
   $db->alter('commentstable', 'drop', $newfield, '');  
   $before = strlen($settings->commentfields);
   $settings->commentfields = str_replace(",$newfield,", ',', $settings->commentfields);
   $after = strlen($settings->commentfields);
   if ($before == $after) { $settings->commentfields = str_replace(",$newfield,", '', $settings->commentfields .','); $settings->commentfields = trimright($settings->commentfields, 1); }
   $tempvar = '{COMMENT'. strtoupper($newfield) .'}';  
   $suggestfile = "../$templatesdir/viewcomments.tpl"; 
   $editfile = "../$templatesdir/editcomments.tpl";
  }
  else if ($type == 'setting')
  {
   $db->delete('metatable', "name='$newfield'");
   $tempvar = '{'. strtoupper($newfield) .'}';  
   $editfile = "../$templatesdir/admin/prefs.tpl";   
  }

  // now fix templates
  $edit = fileread($editfile);
  if ($type != 'setting')
  { 
   $suggest = fileread($suggestfile);
   $verify1 = strstr($suggest, '<input type="text" name="'. $newfield .'" size="40>"');
  }  
  if ($verify1)
  {
   $suggest = str_replace('<input type="text" name="'. $newfield .'" size="40>"', '', $suggest);
   $suggest = str_replace("<b>$newfield:</b>", '', $suggest);
   $verify1 = filewrite($suggestfile, $suggest);
  }
  $verify2 = strstr($edit, '<input type="submit"');
  if ($verify1 && $verify2)
  {
   $edit = str_replace('<input type="text" name="'. $newfield .'" size="30" value="'. $tempvar .'">', '', $edit);
   $verify2 = filewrite($editfile, $edit);
  }
  if (!$template) $template = new template("../$templatesdir/redirect.tpl");
  $template->replace('refresh', ''); // pause so we can instruct
  if ($type != 'settings')
  {
   $instructions = $language->admin_removefieldworked;
  }
  else if ($verify2 && $type=='settings')
  {
   $instructions = $language->admin_removefieldworkedsetting;
  }
  if ($type == 'cat') $type = 'category'; // make more user friendly
  $instructions = str_replace('{TYPE}', $type, $instructions);
  $instructions = str_replace('{NEWTEMPVAR}', $tempvar, $instructions);  
  $instructions = str_replace('{NEWVAR}',  $newfield, $instructions);
  $template->replace('{MESSAGE}', $instructions);
  $template->replace('{DESTINATION}', 'addfields.php');     
 }
}
else
{
 if (!$template) $template = new template("../$templatesdir/admin/addfields.tpl");
 $template->replace('{CURRENTCATFIELDS}', str_replace(',', ', ', $settings->categoryfields));
 $template->replace('{CURRENTLINKFIELDS}', str_replace(',', ', ', $settings->linkfields)); 
 $template->replace('{CURRENTCOMMENTFIELDS}', str_replace(',', ', ', $settings->commentfields)); 
 $template->replace('{CURRENTMEMBERFIELDS}', str_replace(',', ', ', $settings->memberfields)); 
 $template->replace('{CURRENTUSERGROUPFIELDS}', str_replace(',', ', ', $settings->usergroupfields));
 $settingsquery = $db->select('name', 'metatable', 'id>0', 'ORDER BY id ASC', '');
 $num = $db->numrows($settingsquery);
 for ($count=0;$count<$num;$count++)
 {
   if ($count>0) $settingsfields .= ', ';
   $settingsfields .= $db->rowitem($settingsquery);
 }
 $template->replace('{CURRENTSETTINGFIELDS}', $settingsfields);
}
} 

$template->replace('{TEMPLATEDATA}', '');

require 'adminend.php';

?>