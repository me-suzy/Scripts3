<?php
require 'start.php';
$origlang = $language;

if ($filled)
{
 $requiredmembers = explode(',', $settings->requiredmembers);
 $y = sizeof($requiredmembers);
 for ($x=0; $x<$y; $x++)
 {
  if ($$requiredmembers[$x] == '' && $requiredmembers[$x] != '') $incomplete = true;
  $reqmemlist .= '|||'. $requiredmembers[$x] .'|||';
 }
 if ($incomplete)
 {
   $amem = new member('new', 'noid');
   $language = new language($languagegroup);
   if (!$template) $template = new template("$templatesdir/register.tpl");
   $template->replace('{LANGUAGEOPTIONS}', langoptions($amem->language));
   $template->replace('{TEMPLATEOPTIONS}', tempoptions($amem->template));
   $template->text = memberreplacements($template->text, $amem);
   $template->replace('{INCOMPLETE}', $language->suggest_incomplete);
   $template->replace('{DEFAULTLANG}', $settings->defaultlang);
 }  
 else
 {
  // First, see if username or email is taken
  $query = $db->select('name,email', 'memberstable', 'id>0', '', '');
  $num = $db->numrows($query);
  for ($x=0; $x<$num; $x++)
  {
   $row = $db->row($query);
   $list .= '|||'. $row[0] .'|||';
   $otherlist .= '|||'. $row[1] .'|||';  
  }
  if (($debug > 0) && ($debug < 4)) echo "Comparing |||$name||| with $list <br>";
  if (($debug > 0) && ($debug < 4)) echo "Comparing |||$email||| with $otherlist <br>";  
  if ((strstr($list, '|||'. $name .'|||')) && ($name != ''))
  {
   $amem = new member('new', 'noid');
   $language = new language($languagegroup);
   if (!$template) $template = new template("$templatesdir/register.tpl");
   $template->replace('{LANGUAGEOPTIONS}', langoptions($amem->language));
   $template->replace('{TEMPLATEOPTIONS}', tempoptions($amem->template));
   $template->text = memberreplacements($template->text, $amem);
   $template->replace('{INCOMPLETE}', $language->register_nametaken);
   $template->replace('{DEFAULTLANG}', $settings->defaultlang);   
  }
  else if ((strstr($otherlist, '|||'. $email .'|||')) && (strstr($reqmemlist, 'email')))
  {
   $amem = new member('new', 'noid');
   $language = new language($languagegroup);  
   if (!$template) $template = new template("$templatesdir/register.tpl");
   $template->replace('{LANGUAGEOPTIONS}', langoptions($amem->language));
   $template->replace('{TEMPLATEOPTIONS}', tempoptions($amem->tempoptions));
   $template->text = memberreplacements($template->text, $amem);
   $template->replace('{INCOMPLETE}', $language->register_emailtaken);
   $template->replace('{DEFAULTLANG}', $settings->defaultlang);   
  }  
  else if ($password != $passconfirm)
  {
   $amem = new member('new', 'noid');
   $language = new language($languagegroup);
   if (!$template) $template = new template("$templatesdir/register.tpl");
   $template->replace('{LANGUAGEOPTIONS}', langoptions($amem->language));
   $template->replace('{TEMPLATEOPTIONS}', tempoptions($amem->tempoptions));
   $template->text = memberreplacements($template->text, $amem);
   $template->replace('{INCOMPLETE}', $language->register_mismatch);
   $template->replace('{DEFAULTLANG}', $settings->defaultlang);
  }
  else
  {
   $links = 0;
   $comments = 0;
   if ($settings->registration == 'direct')
   {
     $validated = 1;
	 $settings->totalmembers += 1;
	 $settings->update('totalmembers');
     $regtext = $language->register_worked;
   }
   else
   {
    $validated = 0;
    if ($settings->registration == 'email')
     $regtext = $language->register_verifyaddr;
    else
     $regtext = $language->register_awaitvalidation;
   }
   $ip = $HTTP_SERVER_VARS['REMOTE_ADDR'];
   $time = time();
   $usergroup = 2;
   $amember = new member('new', 'blank');
   $amember->template = $thetemplate;
   $amember->language = $thelanguage;
   $amember->stylesheet = $HTTP_POST_VARS['stylesheet'];
   $amember->register();
   $id = mysql_insert_id();
   $amember->id = $id;
   $language = $origlang;
   if ($settings->registration == 'email') sendactivationcode($amember);
   $template = new template("$templatesdir/redirect.tpl");
   if (!$HTTP_COOKIE_VARS['testcookie']) $template->replace('{MESSAGE}', $language->register_needcookies);
   if ($settings->registration == 'direct') $template->replace('{MESSAGE}', $language->register_worked);
   else if ($settings->registration == 'email') $template->replace('{MESSAGE}', $language->register_activationsent);
   else $template->replace('{MESSAGE}', $language->register_awaitvalidation);
   if ($afterreg == '') $afterreg = $returnto;
   $template->replace('{DESTINATION}', $afterreg);
  }
 }
}
else
{
 if (!$template) $template = new template("$templatesdir/register.tpl");
 $template->replace('{LANGUAGEOPTIONS}', langoptions($languagegroup));
 $template->replace('{TEMPLATEOPTIONS}', tempoptions($settings->templatesdir));
 $amem = new member('blank', 'blank');
 $template->text = memberreplacements($template->text, $amem);
 $template->replace('{INCOMPLETE}', '');
 $template->replace('{DEFAULTLANG}', $settings->defaultlang); 
 $template->replace('{USERGROUPOPTIONS}', $thismember->usergroupoptions('2')); 
}
$area = $language->title_divider . $language->title_register;

require 'end.php';
?>