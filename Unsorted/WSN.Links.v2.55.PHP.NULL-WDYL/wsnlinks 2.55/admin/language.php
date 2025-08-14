<?php
require 'adminstart.php';
if ($thismember->isadmin())
{ // user has correct password, so they can enter
 if ($action == 'changeuserlang')
 {
  $thismember->language = $userlang;
  $thismember->update('language');
  $language = new language($userlang);
  $group = $userlang;
 }

  if ($filled)
  {
   if ($action == 'changegroupname')
   {
    $r = $db->update('memberstable', 'language', $newname, "language='$oldname'");	
    $settings->languages = str_replace($oldname, $newname, $settings->languages);
    if ($settings->defaultlang == $oldname) $settings->defaultlang = $newname;
    $settings->update('languages,defaultlang');
	$test = rename('../languages/'. $oldname .'.lng', '../languages/'. $newname .'.lng');
	if ($test) $result = "The language $oldname has been changed to $newname.";
	else $result = "Cannot change file name, permissions are not granted. You will have to manuall change the name of your language file by FTP.";
    if (!$template) $template = new template("../$templatesdir/redirect.tpl");
    $template->replace('{MESSAGE}', $result);
    $template->replace('{DESTINATION}', 'language.php');    
   }
   else if ($action == 'exportlang')
   {
    if ($download)
    {
     admindownload($language->downloadcontent(), $language->groupid .'.lng');
    }
    else
    {
	 $dowrite = $language->writelanguage();
     if ($dowrite)
     {
      if (!$template) $template = new template("../$templatesdir/redirect.tpl");
      $template->replace('{MESSAGE}', "The language has been exported to $writeto.");
      $template->replace('{DESTINATION}', 'language.php');    
     }
     else
     {
      if (!$template) $template = new template("../$templatesdir/redirect.tpl");
      $template->replace('{MESSAGE}', "Could not write the language. Please chmod 666 the file languages/$writeto.lng -- if you do not have such a file, you need to either chmod the languages directory to 777 so that the file can be created for you, or if that does not work on your server you can upload a dummy blank file as languages/$writeto.lng and chmod it to 666.");
      $template->replace('{DESTINATION}', 'language.php');    
	  $template->replace('{SECONDSDELAY}', $settings->secondsdelay * 4);
     }
    }
   }
   if ($action == 'appendgroup')
   {
    $fileloc = $_FILES[$thefilelocation]['tmp_name'];
    $result = languageappend($fileloc, $groupname);
    if ($result != false)
    {
     if (!$template) $template = new template("../$templatesdir/redirect.tpl");
     $template->replace('{MESSAGE}', "The language $groupname has been appended with the new data.");
     $template->replace('{DESTINATION}', 'language.php');    
    }
    else
    {
     if (!$template) $template = new template("../$templatesdir/redirect.tpl");
     $template->replace('{MESSAGE}', "Error: Could not make changes. Either the source file is not readable (check that the file is there and not empty) or the language to which you are appending is not writable (in which case you need to chmod it to 666).");
     $template->replace('{DESTINATION}', 'language.php');    
    } 
   }
   if ($action == 'overwritegroup')
   {
    $fileloc = $_FILES[$thefilelocation]['tmp_name'];
    $result = languageoverwrite($fileloc, $groupname);
    if ($result != false)
    {
     if (!$template) $template = new template("../$templatesdir/redirect.tpl");
     $template->replace('{MESSAGE}', "Items from the file $thefilelocation have now overwritten corresponding items in $groupname, while all items in $groupname which weren't specified in the file have been left as they were.");
     $template->replace('{DESTINATION}', 'language.php');    
    }
    else
    {
     if (!$template) $template = new template("../$templatesdir/redirect.tpl");
     $template->replace('{MESSAGE}', mysql_error() ."Error: Could not overwrite. Check that the soource file is there and that the language you're overwriting is writable (chmod to 666).");
     $template->replace('{DESTINATION}', 'language.php');    
    } 
   }
   if ($action == 'addgroup')
   { // write new language file based on current language
    if (stristr($settings->languages, $groupname)) $thedata = false;
    else $result = uploadfile($thefilelocation, 'leave as is', 'language');
    if ($result != false)
    {
     $settings->languages .= ','. $groupname;
     $settings->update('languages');
     if (!$template) $template = new template("../$templatesdir/redirect.tpl");
     $template->replace('{MESSAGE}', "The new language, $groupname, has been created.");
     $template->replace('{DESTINATION}', 'language.php');    
    }
    else
    {
     if (!$template) $template = new template("../$templatesdir/redirect.tpl");
     $template->replace('{MESSAGE}', mysql_error() ."Error: Could not create your language $groupname. You must chmod 777 the /languages/ directory before uploading.");
     $template->replace('{DESTINATION}', 'language.php');    
    } 
   }
   if ($action == 'deletegroup')
   {
    if ($groupname == $settings->defaultlang)
    {
     if (!$template) $template = new template("../$templatesdir/redirect.tpl");
     $template->replace('{MESSAGE}', "The language $groupname is the current default! It cannot be deleted.");
     $template->replace('{DESTINATION}', 'language.php');    
    }
    else
    {
     $query = $db->update('memberstable', 'language', $settings->defaultlang, "language='$groupname'");
     $test = @unlink($path = '../languages/'. $groupname .'.lng');
	 if ($test) $thelanghere = "The language $groupname has been deleted.";
	 else $thelanghere = "The file is not chmoded to 666, so it cannot be deleted -- you will have to delete it manually by FTP.";
     $query = $db->delete('languagetable', "groupid='$groupname'");
     $settings->languages = str_replace(','. $groupname, '', $settings->languages);
     $settings->languages = str_replace($groupname .',', '', $settings->languages);
     $settings->update('languages');
     if (!$template) $template = new template("../$templatesdir/redirect.tpl");
	 
     $template->replace('{MESSAGE}', $thelanghere);
     $template->replace('{DESTINATION}', 'language.php');    
    }
   }
   if ($action == 'changedefault')
   {
    $settings->defaultlang = $defaultlang;
    $settings->update('defaultlang');
    if (!$template) $template = new template("../$templatesdir/redirect.tpl");
    $template->replace('{MESSAGE}', "The default language has been changed to $defaultlang.");
    $template->replace('{DESTINATION}', 'language.php');
   }
   if ($action == 'delete')
   {
    $language->deleteitem($deleteid);
    if (!$template) $template = new template("../$templatesdir/redirect.tpl");
	$template->replace('{MESSAGE}', "The language variable $todelete has been deleted.");
	$template->replace('{DESTINATION}', 'language.php');
   }
   if ($action=='edit')
   {
    if ($group == '') $group = $languagegroup;
	$toupdate = new language($group);
    $langarray = get_object_vars($toupdate);
    while(list($name, $value) = each($langarray)) 
    {
	 $$name = str_replace('&#'. ord('&') .';amp', '&amp', $$name); 
	 $$name = encodeit($$name);	 
	 if (strstr($varslist, '|||'. $name .'|||')) $toupdate->$name = $$name;
	}
	$test = $toupdate->writelanguage();
	if ($test) $result = 'Language updated.';
	else $result = 'The language file languages/'. $group .'.lng is not writable. You must chmod it to 666, or if it does not yet exist you must either chmod the directory to 777 or upload a blank file with the same name chmoded to 666.';
    if (!$template) $template = new template("../$templatesdir/redirect.tpl");
	$template->replace('{MESSAGE}', $result);
	$template->replace('{DESTINATION}', 'language.php');
   }
   else if ($action=='new')
   {
    $name = encodeit($name);   
    $content = encodeit($content);	
	$toupdate = new language($group);
	if (!(method_exists($toupdate, $name)))
	{
	 $toupdate->$name = $content;
	 $test = $toupdate->writelanguage(); 
	 if ($test) $result = 'The new language variable '. $name .' has been created.';
	 else $result = 'The language file languages/'. $group .'.lng is not writable. You must chmod it to 666, or if it does not yet exist you must either chmod the directory to 777 or upload a blank file with the same name chmoded to 666.';
	}
	if ($result == '') $result = 'A language variable of that name already exists in this language. You must use a unique name.';
    if (!$template) $template = new template("../$templatesdir/redirect.tpl");
	$template->replace('{MESSAGE}', $result);
	$template->replace('{DESTINATION}', 'language.php');
   }
  }
  else
  {
   if (!$template) $template = new template("../$templatesdir/admin/language.tpl");
   if ($group == '') $group = $languagegroup;
   $template->replace('{THISLANG}', $group);
   $template->replace('{LANGOPTIONS}', langoptions($thismember->language));
   $template->replace('{DEFAULTOPTIONS}', langoptions($settings->defaultlang));
   $sub = templateextract($template->text, '<!-- BEGIN ROW -->', '<!-- END ROW -->');
   $template->replace($sub, '{SUBAREA}');
   if ($action == 'filter')
   {
    $toupdate = new language($group);
    $filteredarray = $toupdate->filter($field, $filter);
    $num = sizeof($filteredarray);
    for ($count=0; $count<$num; $count++)
    {
     $row = $filteredarray[$count];
 	 $name = $row[0];
	 $content = $row[1];
     $content = str_replace('&amp', '&#'. ord('&') .';amp', $content);
     $name = encodeit($name);
 	 $content = encodeit($content);
     $subarea .= $sub;
	 $subarea = str_replace('{NAME}', $name, $subarea);
	 $subarea = str_replace('{CONTENT}', $content, $subarea);	
     $subarea = str_replace('{ID}', '', $subarea);
    }
    $template->replace('{SUBAREA}', $subarea);     
   }
   else
   {
    $template->replace('{SUBAREA}', '');
    $template->replace('<input type="submit" value="Update All Language">', 'Make your selection of what language to view from the list above.');
   }
  }  
 }

$leaveencoded = true;
$nomemberinfo = true;
require 'adminend.php';

?>