<?php
require 'adminstart.php';

if ($thismember->isadmin())
{
 if ($filled)
 {
  // format: redirectname[,]location|||
  $settings->redirects = 'afterlink[,]'. $afterlink .'|||aftercat[,]'. $aftercat .'|||aftercom[,]'. $aftercom .'|||afterreg[,]'. $afterreg .'|||aftereditlink[,]'. $aftereditlink .'|||aftereditcat[,]'. $aftereditcat .'|||aftereditcom[,]'. $aftereditcom .'|||aftereditprofile[,]'. $aftereditprofile .'|||afterlogin[,]'. $afterlogin .'|||afterlogout[,]'. $afterlogout .'|||afterresetpass[,]'. $afterresetpass .'|||aftersubscribecom[,]'. $aftersubscribecom .'|||aftersubscribecat[,]'. $aftersubscribecat .'|||afterdellink[,]'. $afterdellink .'|||afterdelcat[,]'. $afterdelcat .'|||afterdelcom[,]'. $afterdelcom;
  $settings->update('redirects');
  $template = new template("redirect.tpl");
  $template->replace("{MESSAGE}", "Redirects are now set.");
  $template->replace("{DESTINATION}", "index.php");
 }
 else
 {
  if (!$template) $template = new template("../$templatesdir/admin/redirects.tpl");
  $template->replace('{AFTERLINK}', $afterlink);
  $template->replace('{AFTERCAT}', $aftercat);
  $template->replace('{AFTERCOM}', $aftercom);
  $template->replace('{AFTERREG}', $afterreg);
  $template->replace('{AFTEREDITLINK}', $aftereditlink);
  $template->replace('{AFTEREDITCAT}', $aftereditcat);
  $template->replace('{AFTEREDITCOM}', $aftereditcom);
  $template->replace('{AFTEREDITPROFILE}', $aftereditprofile);
  $template->replace('{AFTERLOGIN}', $afterlogin);
  $template->replace('{AFTERLOGOUT}', $afterlogout);
  $template->replace('{AFTERRESETPASS}', $afterresetpass);
  $template->replace('{AFTERSUBSCRIBECOM}', $aftersubscribecom);
  $template->replace('{AFTERSUBSCRIBECAT}', $aftersubscribecat);
  $template->replace('{AFTERDELLINK}', $afterdellink);
  $template->replace('{AFTERDELCAT}', $afterdelcat);
  $template->replace('{AFTERDELCOM}', $afterdelcom);
 }
} 

require 'adminend.php';  


?>