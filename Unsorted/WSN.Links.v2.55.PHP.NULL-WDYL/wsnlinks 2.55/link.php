<?php
require 'start.php';
if ($scriptname == 'wsnmanual') $action = 'detail';
if ($action == 'detail')
{
 $test = $db->select('id', 'linkstable', "id=$id", '', '');
 $test = $db->rowitem($test);
 if ($test != $id)
 {
  header("Location: index.php");
  die('<meta http-equiv="redirect" content="0;index.php">');
 }
 $thislink = new onelink('id', $id); 
 if (!$template) $template = new template("details.tpl");
 $area = $language->title_divider . $thislink->title;
 $template->text = linkreplacements($template->text, $thislink);
 if ($scriptname == 'wsngallery')
 {
  $template = new template("detail.tpl");
  $cat = new category('id', $thislink->catid);
  $template->replace('{NAVIGATION}', shownav($cat));
  if ($edit != '' && $scriptname == 'wsngallery') { $thislink->imedit = time(); $thislink->update('imedit'); }
  $template->replace('{EDIT}', $edit);
  $template->replace('{PERCENT}', $percent);
  $template->replace('{ACTION}', $action);
  if ($edit != '') $template->replace('{USEEDIT}', 1);
  else $template->replace('{USEEDIT}', 0);
 }
 else if ($scriptname == 'wsnmanual')
 {
  // Check to see if this is unique or non-unique hit.
  if (!(strstr($HTTP_COOKIE_VARS['hits'], " $id ")) )
  {
   if ($thismember->isadmin() && $settings->dontcount == 'yes') $doesntcount = true;
   if (!$doesntcount)
   {
    $thislink->hits = $thislink->hits + 1;
    $thislink->update('hits');
    $aliases = $db->select($settings->linkfields, 'linkstable', 'alias='. $thislink->id, '', '');
    $n = $db->numrows($aliases);
    for ($x=0; $x<$n; $x++)
    {
     $alias = new onelink('row', $db->row($aliases));
     $alias->hits = $thislink->hits;
     $alias->update('hits');
    }
    $settings->totalhits += 1;
    $settings->update('totalhits');
    if ((!$custommemberdb) || ($newhits != ''))
    {
     $owner = new member('id', $thislink->ownerid);
     $owner->$newtotalhits += 1;
     $owner->update($newtotalhits);
    }
   }
   // set cookie to prevent counting again in future
   $idstring = ' '. $HTTP_COOKIE_VARS[hits] .' '. $id .' ';
   $clicktimer = $settings->clicktimer;
   setcookie("hits", "$idstring", time()+$clicktimer);  
  }
 } 
 require 'end.php';
}
else if ($action == 'sponsor')
{
 $thelink = new onelink('id', $id);
 makecookie("sponsorlink", $id, time() + 100000);
 $ip = $_SERVER['REMOTE_ADDR'];
 $thelink->ip = 'buy '. $ip;
 $thelink->update('ip');
 $language->sponsor_help = linkreplacements($language->sponsor_help, $thelink);
 $language->sponsor_help = settingsreplacements($language->sponsor_help);
 if (!$template) $template = new template("sponsor.tpl");
 $area = $language->title_divider . $language->title_sponsor;
 $template->text = linkreplacements($template->text, $thelink);
 require 'end.php';
}
else
{
 $goingtolink = true;
 if (($id == '') && ($url != ''))
 {
  $q = $db->select('id', 'linkstable', "url='$url'", '', '');
  $id = $db->rowitem($q);
 }

 $thislink = new onelink('id', $id);

 // Check to see if this is unique or non-unique hit.
 if (!(strstr($HTTP_COOKIE_VARS['hits'], " $id ")) )
 {
  if ($thismember->isadmin() && $settings->dontcount == 'yes') $doesntcount = true;
  if (!$doesntcount)
  {
   $thislink->hits = $thislink->hits + 1;
   $thislink->update('hits');
   $aliases = $db->select($settings->linkfields, 'linkstable', 'alias='. $thislink->id, '', '');
   $n = $db->numrows($aliases);
   for ($x=0; $x<$n; $x++)
   {
    $alias = new onelink('row', $db->row($aliases));
    $alias->hits = $thislink->hits;
    $alias->update('hits');
   }
   $settings->totalhits += 1;
   $settings->update('totalhits');
  if ((!$custommemberdb) || ($newhits != ''))
   {
    $owner = new member('id', $thislink->ownerid);
    $owner->totalhits += 1;
    $owner->update('totalhits');
   }
  }
  // set cookie to prevent counting again in future
  $idstring = ' '. $HTTP_COOKIE_VARS[hits] .' '. $id .' ';
  $clicktimer = $settings->clicktimer;
  setcookie("hits", "$idstring", time()+$clicktimer);
 }
 $url = $thislink->url;
 if ($url != '')
 {
  header("Location: $url"); 
  die('<meta http-equiv="refresh" content="0;url='. $url .'">');
 }
 else
 {
  echo "No URL selected.";
 }
}
?>