<?php

require 'adminstart.php';

if ($thismember->isadmin())
{ // user has correct password, so they can enter
 if ($filled)
 { 
  if ($type == 'remove')
  {
   $codelist = explode('|||', $settings->censor);
   $num = sizeof($codelist);
   for ($p=0; $p<$num; $p++)
   {
    $thiscode = explode('[,]', $codelist[$p]);
    $original = $thiscode[0];
    if ($original == $thecode) $fullcode = $codelist[$p];
   }
   $settings->censor = str_replace('|||'. $fullcode .'|||', '|||', $settings->censor);
   $settings->censor = str_replace($fullcode .'|||', '', $settings->censor);   
   $settings->censor = str_replace('|||'. $fullcode, '', $settings->censor);      
   $settings->update('censor');
   if (!$template) $template = new template("../$templatesdir/redirect.tpl");
   $template->replace('{MESSAGE}', "The replacement of $thecode has been removed.");
   $template->replace('{DESTINATION}', 'censor.php');  
  }
  else if ($type == 'add')
  {
   if ($settings->censor == ' ') $settings->censor = '';
   if (strlen($settings->censor)>3) $settings->censor .= '|||'. $thecode .'[,]'. $thereplacement;
   else $settings->censor = $thecode .'[,]'. $thereplacement;
   $settings->update('censor');
   if (!$template) $template = new template("../$templatesdir/redirect.tpl");
   $template->replace('{MESSAGE}', "$thecode is now replaced with $thereplacement.");
   $template->replace('{DESTINATION}', 'censor.php');
  }
 }
 else
 {
  if (!$template) $template = new template("../$templatesdir/admin/censor.tpl");
  $template->showcensor();
 }
}
$leaveencoded = true;
require 'adminend.php';

?>