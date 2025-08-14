<?php

require 'adminstart.php';


if ($thismember->isadmin())
{
 if ($filled)
 {
  if (($next == '') || ($next < 1)) $next = 0;
  if ($inc < 1) $inc = 10;
  if ($action == 'links')
  { 
   $query = $db->select('id', 'linkstable', 'id>0', '', '');
   $num = $db->numrows($query); 
   updatelinkcounters($next);
   $next += $inc;
   $template = new template("../$templatesdir/redirect.tpl");
   if ($next < ($num + $inc))
   {
    if ($next > $num) $inc = ($num - ($next-$inc));
    $template->replace('{MESSAGE}', "Updating next $inc links starting at $next ...");
    $template->replace('{DESTINATION}', "updatecounters.php?filled=1&action=links&next=$next&inc=$inc");
   }
   else
   {  
    $template->replace('{MESSAGE}', "All links have been updated, we're done.");
    $template->replace('{DESTINATION}', "updatecounters.php");
   }
  }
  else if ($action == 'categories')
  { 
   $query = $db->select('id', 'categoriestable', 'id>0', '', '');
   $num = $db->numrows($query); 
   updatecatcounters($next);
   $next += $inc;
   $template = new template("../$templatesdir/redirect.tpl");
   if ($next < ($num + $inc))
   { 
    if ($next > $num) $inc = ($num - ($next-$inc));
    $template->replace('{MESSAGE}', "Updating next $inc categories starting at $next ...");
    $template->replace('{DESTINATION}', "updatecounters.php?filled=1&action=categories&next=$next&inc=$inc");
   }
   else
   {  
    $template->replace('{MESSAGE}', "All categories have been updated, we're done.");
    $template->replace('{DESTINATION}', "updatecounters.php");
   }
  }
  else if ($action == 'members')
  { 
   $query = $db->select('id', 'memberstable', 'id>0', '', '');
   $num = $db->numrows($query); 
   updatememcounters($next); 
   $next += $inc;
   $template = new template("../$templatesdir/redirect.tpl");
   if ($next < ($num + $inc))
   { 
    $template->replace('{MESSAGE}', "Updating next $inc members starting at $next ...");
    $template->replace('{DESTINATION}', "updatecounters.php?filled=1&action=members&next=$next&inc=$inc");
   }
   else
   {  
    $template->replace('{MESSAGE}', "All members have been updated, we're done.");
    $template->replace('{DESTINATION}', "updatecounters.php");
   }
  }
 }
 else
 {
  if ($action == 'catselector') 
  {
   updatecategoryselector();
   $template = new template("../$templatesdir/redirect.tpl");
   $template->replace("{MESSAGE}", "The category selector has been regenerated.");
   $template->replace("{DESTINATION}", "updatecounters.php");
  }
  else if ($action == 'totals')
  {
   $q = $db->select('hits,hitsin', 'linkstable', 'hide=0 AND validated=1', '', '');
   $n = $db->numrows($q);
   for ($x=0; $x<$n; $x++)
   {
    $row = $db->row($q);
    $totalhits += $row[0];
    $totalhitsin += $row[1]; 
   } 
   $settings->totalhits = $totalhits;
   $settings->totalhitsin = $totalhitsin;
   $settings->totallinks = $n;
   $settings->uniquetotal = $db->numrows($db->select('id', 'linkstable', 'validated=1 AND hide=0 AND alias=0', '', ''));
   $settings->totalcomments = $db->numrows($db->select('id', 'commentstable', 'validated=1 AND hide=0', '', ''));
   $settings->totalmembers = $db->numrows($db->select('id', 'memberstable', 'validated=1', '', ''));
   $settings->lastupdate = $db->rowitem($db->select('lastedit', 'linkstable', "validated=1 AND hide=0", "ORDER BY lastedit DESC", "LIMIT 0,1"));
   $settings->update('totallinks,totalcomments,totalhitsin,totalhits,totalmembers,lastupdate,uniquetotal');
   $template = new template("../$templatesdir/redirect.tpl");
   $template->replace("{MESSAGE}", "The total number of links, comments, members, hits and hits in have been updated.");
   $template->replace("{DESTINATION}", "updatecounters.php");
  }
  else 
  {
   $template = new template("../$templatesdir/admin/updatecounters.tpl");
  }
 }
}

require 'adminend.php';

?>