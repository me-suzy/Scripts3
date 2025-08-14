<?php
require 'adminstart.php';

if ($thismember->isadmin())
{
  if ($filled)
  {
   $origstyle = $settings->stylesheet;
   if ($newtemplatesdir) { $settings->templatesdir = $newtemplatesdir; $settings->update('templatesdir'); }
   $uploadpath = str_replace("&#92;", '\\', $uploadpath);
   if (is_array($requiredlinks)) $requiredlinks = implode(",", $requiredlinks);
   $requiredlinks = str_replace('selected', '', $requiredlinks);
   if (is_array($requiredcategories)) $requiredcategories = implode(",", $requiredcategories);
   $requiredcategories = str_replace('selected', '', $requiredcategories);
   if (is_array($requiredcomments)) $requiredcomments = implode(",", $requiredcomments);
   $requiredcomments = str_replace('selected', '', $requiredcomments);
   if (is_array($requiredmembers)) $requiredmembers = implode(",", $requiredmembers);
   $requiredmembers = str_replace('selected', '', $requiredmembers);
   if (is_array($searchfields)) $searchfields = implode(",", $searchfields);
   $searchfields = str_replace('selected', '', $searchfields);

$forcelist = ' requiredcategories requiredlinks requiredmembers searchfields ';

   $namelist = explode(',', $settings->allnames());
   $num = sizeof($namelist);
   $redoindex = false;
   if ($settings->orderlinks != $orderlinks || $settings->orderlinks2 != $orderlinks2 || $settings->ordercats != $ordercats || $settings->ordercomments != $ordercomments || $settings->memberlistorder != $memberlistorder) $redoindex = true;
   
   for ($count=0; $count<$num; $count++)
   { 
    if ((stristr($varslist, '|||'. $namelist[$count] .'|||') && ($$namelist[$count] != $settings->$namelist[$count])) || (strstr($forcelist, $namelist[$count])))
    {
     $settings->$namelist[$count] = $$namelist[$count];
     $settings->update($namelist[$count]);
    }
   }

   if ($redoindex)
   {
    processsql("ALTER TABLE $linkstable DROP INDEX wsnindex;");
	processsql("ALTER TABLE $categoriestable DROP INDEX wsnindex;");
	processsql("ALTER TABLE $commentstable DROP INDEX wsnindex;");
	processsql("ALTER TABLE $commentstable DROP INDEX wsnindex;");
	processsql("ALTER TABLE $memberstable DROP INDEX wsnindex");
    $linkindex = explode(' ', str_replace('ORDER BY ', '', $settings->orderlinks));
    $linkindex = $linkindex[0];
	if (strstr($db->fieldtype($linkstable, $linkindex), 'text')) $linkindex .= '(5)';
    $linkindex2 = explode(' ', str_replace('ORDER BY ', '', $settings->orderlinks2));
    $linkindex2 = $linkindex2[0];
	if (strstr($db->fieldtype($linkstable, $linkindex2), 'text')) $linkindex2 .= '(5)';	
    processsql("CREATE INDEX wsnindex ON {PREFIX}links (catid,". $linkindex .",". $linkindex2 .");");
    $catindex = explode(' ', str_replace('ORDER BY ', '', $settings->ordercats));
    $catindex = $catindex[0];
	if (strstr($db->fieldtype($categoriestable, $catindex), 'text')) $catindex .= '(5)';	
    processsql("CREATE INDEX wsnindex ON {PREFIX}categories (". $catindex .");");
    $comindex = explode(' ', str_replace('ORDER BY ', '', $settings->ordercomments));
    $comindex = $comindex[0];
	if (strstr($db->fieldtype($commentstable, $comindex), 'text')) $comindex .= '(5)';	
    processsql("CREATE INDEX wsnindex ON {PREFIX}comments (linkid,". $comindex .");");
    $memindex = explode(' ', str_replace('ORDER BY ', '', $settings->memberlistorder));
    $memindex = $memindex[0];
	if (strstr($db->fieldtype($memberstable, $memindex), 'text')) $memindex .= '(5)';
    processsql("CREATE INDEX wsnindex ON {PREFIX}members (id,". $memindex .");");   
   }
   
   $settings->templatesdir = $newtemplatesdir;
   $settings->stylesheet = $stylesheet;
   $settings->update('stylesheet,templatesdir');
   if ($debug1 != '') { $settings->debug = $debug1; $settings->update('debug'); }
   if (!$template) $template = new template("../$templatesdir/redirect.tpl");
   $settings->stylesheet = $thismember->stylesheet;
   $template->replace('{MESSAGE}', $language->admin_settingsupdate);
   $template->replace('{DESTINATION}', 'index.php');
   $settings->stylesheet = $origstyle;
   $stylesheet = $origstyle;
  }
  else
  {
   // improve display:
   $settings->externallinks = encodeit($settings->externallinks);
   $settings->standardtable = encodeit($settings->standardtable);
   $settings->mainmeta = encodeit($settings->mainmeta);
   if (!$template) $template = new template("../$templatesdir/admin/prefs.tpl");
   $template->replace('{UPLOADPATH}', str_replace("\\", "&#92;", $settings->uploadpath));
   $template->text = settingsreplacements($template->text);
   $orderlinks = orderlinksoptions($settings->orderlinks);
   $orderlinks2 = orderlinksoptions($settings->orderlinks2);
   $ordercats = ordercatsoptions($settings->ordercats);
   $ordermembers = ordermemoptions($settings->memberlistorder);
   $ordercommentsmenu = ordercommentsoptions($settings->ordercomments);
   $selectmixrecip = yesno($settings->mixrecip);
   $htmloptions = yesno($settings->allowhtml);
   $selectdebug = debugmenu($settings->debug);
   $selectnotify = yesno($settings->notify);
   $registrationoptions = registrationmenu($settings->registration);
   $requiredlinksmenu = fieldselector('linkfields', $settings->requiredlinks);

   $requiredcatsmenu = fieldselector('categoryfields', $settings->requiredcategories);
   $requiredcommentsmenu = fieldselector('commentfields', $settings->requiredcomments);
   $requiredmembersmenu = fieldselector('memberfields', $settings->requiredmembers);
   $searchfieldsissue = true;
   $searchfieldsmenu = fieldselector('linkfields', $settings->searchfields);
   $searchfieldsissue = false;
   $origsettings = new settingsdata;
   $template->replace('{STYLEDEFAULTOPTIONS}', stylesheets($origsettings->stylesheet));
   $template->replace('{COMPRESSOPTIONS}', yesno($settings->compress));
   $template->replace('{LOGSEARCHESOPTIONS}', yesno($settings->logsearches));
   $template->replace('{STYLEIMAGESOPTIONS}', yesno($settings->styleimages));
   $template->replace('{ADMINBYPASSOPTIONS}', yesno($settings->adminbypass));
   $template->replace('{MIXCOMMENTSOPTIONS}', yesno($settings->mixcomments));
   $template->replace('{APACHEREWRITEOPTIONS}', yesno($settings->apacherewrite));
   $template->replace('{SKIPTOCATOPTIONS}', skiptocat($settings->skiptocat));
   $template->replace('{TEMPOPTIONS}', tempoptions($origsettings->templatesdir));
   $template->replace('{GRAPHICSPROGOPTIONS}', graphicsprog($settings->graphicsprog));
   $template->replace('{MODAPPROVEOPTIONS}', yesno($settings->modapprove));
   $template->replace('{SPONSORTYPEOPTIONS}', sponsortypeoptions($settings->sponsortype));
   $template->replace('{SPONSORLINKTYPEOPTIONS}', typeselector($settings->sponsorlinktype));
   $template->replace('{JPEGINTERLACEOPTIONS}', yesno($settings->jpeginterlace));
   $template->replace('{NOHOTLINKOPTIONS}', yesno($settings->nohotlink));
   $template->replace('{ORDERMEMBERS}', $ordermembers);  
   $template->replace('{SEARCHFIELDSMENU}', $searchfieldsmenu);  
   $template->replace('{REQUIREDLINKSMENU}', $requiredlinksmenu);
   $template->replace('{REQUIREDCATEGORIESMENU}', $requiredcatsmenu);
   $template->replace('{REQUIREDCOMMENTSMENU}', $requiredcommentsmenu);
   $template->replace('{REQUIREDMEMBERSMENU}', $requiredmembersmenu);
   $template->replace('{ORDERCOMMENTSMENU}', $ordercommentsmenu);
   $template->replace('{ORDERLINKSMENU}', $orderlinks);
   $template->replace('{ORDERLINKS2MENU}', $orderlinks2);
   $template->replace('{ORDERCATSMENU}', $ordercats);
   $template->replace('{MIXRECIPMENU}', $selectmixrecip);
   $template->replace('{DEBUGMENU}', $selectdebug);
   $template->replace('{NOTIFYMENU}', $selectnotify);
   $template->replace('{HTMLOPTIONS}', $htmloptions);
   $template->replace('{REGISTRATIONOPTIONS}', $registrationoptions); 
   $template->replace('{DONTCOUNTOPTIONS}', yesno($settings->dontcount));
   $template->replace('{INTEGRATIONOPTIONS}', integrationoptions($settings->integration));

  // for wsn guest
   $template->replace('{NOTIFYADMINOPTIONS}', yesno($settings->notifyadmin));
   $template->replace('{THANKSMENU}', yesno($settings->sendthanks));        
   $template->replace('{ATTEMPTCOUNTRYOPS}',  yesno($settings->attemptcountry));  
   
  }
 }
 $leaveencoded = true;
 require 'adminend.php';

?>