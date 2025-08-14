<?php

require 'adminstart.php';

if ($thismember->isadmin())
{ // user has correct password, so they can enter
 if ($filled)
 { 
  if ($type == 'remove')
  {
   $codelist = explode('|||', $settings->wsncodes);
   $num = sizeof($codelist);
   for ($p=0; $p<$num; $p++)
   {
    $thiscode = explode('[,]', $codelist[$p]);
    $original = $thiscode[0];
    if ($original == $thecode) $fullcode = $codelist[$p];
   }
   $settings->wsncodes = str_replace('|||'. $fullcode, '', $settings->wsncodes);
   $settings->wsncodes = str_replace($fullcode, '', $settings->wsncodes);   
   $settings->update('wsncodes');
   if (!$template) $template = new template("../$templatesdir/redirect.tpl");
   $template->replace('{MESSAGE}', "The WSN code $thecode has been removed.");
   $template->replace('{DESTINATION}', 'wsncodes.php');  
  }
  else if ($type == 'removesmilie')
  {
   $codelist = explode('|||', $settings->smilies);
   $num = sizeof($codelist);
   for ($p=0; $p<$num; $p++)
   {
    $thiscode = explode(',', $codelist[$p]);
    $original = $thiscode[0];
    if ($original == $thecode) $fullcode = $codelist[$p];
   }
   $settings->smilies = str_replace('|||'. $fullcode, '', $settings->smilies);
   $settings->smilies = str_replace($fullcode, '', $settings->smilies);   
   $settings->update('smilies');
   if (!$template) $template = new template("../$templatesdir/redirect.tpl");
   $template->replace('{MESSAGE}', "The smilies $thecode has been removed.");
   $template->replace('{DESTINATION}', 'wsncodes.php');  
  }
  else if ($type == 'add')
  {
   if ($settings->wsncodes == ' ') $settings->wsncodes = '';
   if (strlen($settings->wsncodes)>3) $settings->wsncodes .= '|||'. $thecode .'[,]'. $thecodeclose .'[,]'. $thereplacement .'[,]'. $thereplacementclose .'[,]'. $description .'[,]'. $format;
   else $settings->wsncodes = $thecode .'[,]'. $thecodeclose .'[,]'. $thereplacement .'[,]'. $thereplacementclose .'[,]'. $description .'[,]'. $format;
   $settings->update('wsncodes');
   if (!$template) $template = new template("../$templatesdir/redirect.tpl");
   $template->replace('{MESSAGE}', "The WSN Code $thecode has been added.");
   $template->replace('{DESTINATION}', 'wsncodes.php');
  }
  else if ($type == 'addsmilie')
  {
   $thereplacement = '<img src="{TEMPLATESDIR}/images/smilies/'. $thereplacement .'" border="0">';
   if ($settings->smilies == ' ') $settings->smilies = '';
   if (strlen($settings->smilies)>3) $settings->smilies .= '|||'. $thecode .','. $thereplacement;
   else $settings->smilies .= $thecode .','. $thereplacement;
   $settings->update('smilies');
   if (!$template) $template = new template("../$templatesdir/redirect.tpl");
   $template->replace('{MESSAGE}', "The smilie $thecode has been added.");
   $template->replace('{DESTINATION}', 'wsncodes.php');
  }
 }
 else
 {
  if (!$template) $template = new template("../$templatesdir/admin/wsncodes.tpl");
  $template->showwsncodes();
  $template->showsmilies();
 }
}
$leaveencoded = true;
require 'adminend.php';

?>