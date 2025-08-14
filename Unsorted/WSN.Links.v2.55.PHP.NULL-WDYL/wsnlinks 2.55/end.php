<?php
// fix possible 500 errors by clariflying content type
 header("Content-type: text/html");

// Check for custom-specified permissions restrictions.
if (strstr($template->text, '<!-- RESTRICT TO GROUPS'))
{
 $groups = templateextract($template->text, '<!-- RESTRICT TO GROUPS ', ' -->');
 $groups = str_replace('<!-- RESTRICT TO GROUPS ', '', $groups);
 $thegroups = explode(',', $groups);
 $comeonin = false;
 $num = sizeof($thegroups);
 for ($x=0; $x<$num; $x++)
 {
  if ($thismember->usergroup == $thegroups[$x]) $comeonin = true;
 }
 if (!$comeonin) $template = new template("noaccess.tpl");
} 

if ($TID == 'codes') { $template->showwsncodes(); $template->showsmilies(); }
if ($headerfooter == 'no')
{
 if (@file_exists("templates/images_". $settings->stylesheet))
 {
  $template->replace('{TEMPLATESDIR}/images', 'templates/images_'. $settings->stylesheet);
  $template->replace($templatesdir .'/images', 'templates/images_'. $settings->stylesheet);
 }
 $template->replace('{TEMPLATESDIR}', $templatesdir);
}


if ($action == 'displaycat') 
{
 if ($thiscategory->headerinfo != '')
  $header->replace('{MAINMETA}', ''); // if category has tags, don't show non-cat meta tags
}

// add footer
$customfooter = str_replace('-', '/', $customfooter .'.tpl');
if ($customfooter == '.tpl') $customfooter = 'footer.tpl';
$footer = new template($customfooter);

// make sure [INSERTFILE=] will work in header and footer

if ($headerfooter != 'no')
 $totalpage = $header->text . $template->text . $footer->text;
else
 $totalpage = $template->text;

// global variables and macros


// total hits, hits in and total links are now updated just hourly to save queries on big directories
$totalhits = $settings->totalhits;
$totalhitsin = $settings->totalhitsin;
$n = $settings->totallinks;
if (strstr($totalpage, '{LASTUPDATE}')) $totalpage = str_replace('{LASTUPDATE}', strftime($settings->dateformat, $settings->lastupdate), $totalpage);
if (strstr($totalpage, '{TOTALLINKS}')) $totalpage = str_replace('{TOTALLINKS}', $n, $totalpage);
if ($totalhits == '') $totalhits = '0';
if (strstr($totalpage, '{TOTALHITS}')) $totalpage = str_replace('{TOTALHITS}', $totalhits, $totalpage);
if (strstr($totalpage, '{TOTALHITSIN}')) $totalpage = str_replace('{TOTALHITSIN}', $totalhitsin, $totalpage);
if ($totalcats == '') $totalcats = $db->numrows($db->select('id', 'categoriestable', 'validated=1 AND hide=0', '', ''));
if (strstr($totalpage, '{TOTALCATS}')) $totalpage = str_replace('{TOTALCATS}', $totalcats, $totalpage);
if (strstr($totalpage, '{TOTALSUBCATS}')) $totalpage = str_replace('{TOTALSUBCATS}', $db->numrows($db->select('id', 'categoriestable', 'validated=1 AND hide=0', '', '')), $totalpage);
if (strstr($totalpage, '{TOTALCOMMENTS}')) $totalpage = str_replace('{TOTALCOMMENTS}', $settings->totalcomments, $totalpage);

$totalpage = str_replace('{AREA}', $area, $totalpage); // define area for page title
if (strstr($totalpage, '{TOTALMEMBERS}')) $totalpage = str_replace('{TOTALMEMBERS}', $settings->totalmembers, $totalpage);

if (strstr($totalpage, '{LANG_'))
 $totalpage = langreplacements($totalpage);
if (strstr($totalpage, '{LANG_'))
 $totalpage = langreplacements($totalpage);
if (strstr($totalpage, '{LANG_'))
 $totalpage = langreplacements($totalpage);

// do toplists
$istoplist = true;
$type = $HTTP_GET_VARS['type'];
$num = $HTTP_GET_VARS['num'];
if (!$num) $num = $HTTP_POST_VARS['num'];
for ($loopthis = 1; $loopthis<30; $loopthis++) // they couldn't have more than 30 on a page, could they?
{
if (strstr($totalpage, '<!-- BEGIN TOPLIST '. $loopthis .' -->'))
{ 
 $blanktemplate = templateextract($totalpage, '<!-- BEGIN TOPLIST '. $loopthis .' -->', '<!-- END TOPLIST '. $loopthis .' -->');
 $totalpage = str_replace($blanktemplate, '{TOPLISTAREA}', $totalpage);
 if (!strstr($blanktemplate, '{'))
 { // if replacements already killed toplist content, get it back
  $blanktemplate = templateextract($template->original, '<!-- BEGIN TOPLIST '. $loopthis .' -->', '<!-- END TOPLIST '. $loopthis .' -->');
 }
 $commands = templateextract($blanktemplate, '<CONFIG>', '</CONFIG>');
 $blanktemplate = str_replace($commands, '', $blanktemplate);
 $blanktemplate = str_replace('</CONFIG>', '', $blanktemplate);
 $row = explode(',', $commands);

 if (!$type[$loopthis]) $type[$loopthis] = $row[0];
 if (!$field[$loopthis]) $field[$loopthis] = $row[1];
 if (!$number[$loopthis]) $number[$loopthis] = $row[2];
 if (!$order[$loopthis]) $order[$loopthis] = $row[3];
 if ((sizeof($row) > 3) && (!$thecondition[$loopthis])) $thecondition[$loopthis] = $row[4];
 if ((sizeof($row) > 4) && (!$thestart[$loopthis])) $thestart[$loopthis] = $row[5];
 if ((sizeof($row) > 5) && (!$thecolumns[$loopthis])) $thecolumns[$loopthis] = $row[6];
 else $thestart[$loopthis] = 0;
 $type[$loopthis] = str_replace('<CONFIG>', '', $type[$loopthis]);
 if ($type[$loopthis] == 'items' || $type[$loopthis] == 'entries' || $type[$loopthis] == 'images') $type[$loopthis] = 'links';
 if ($type[$loopthis] == 'links') $selectfields[$loopthis] = $settings->linkfields;
 if ($type[$loopthis] == 'categories') $selectfields[$loopthis] = $settings->categoryfields; 
 if ($type[$loopthis] == 'comments') $selectfields[$loopthis] = $settings->commentfields;  
 if ($type[$loopthis] == 'members') $selectfields[$loopthis] = $settings->memberfields;
 $realorder = array(); 
 if (($order[$loopthis] == 'ascending') || ($order[$loopthis] == 'asc')) $realorder[$loopthis] = "ASC";
 else $realorder[$loopthis] = "DESC";
 $table[$loopthis] = $type[$loopthis] . 'table';
 $condition = '';
 if (!$unval) $condition = 'validated=1'; 
 if (($type[$loopthis] == 'categories') || ($type[$loopthis] == 'links')) $condition .= ' AND hide=0';
 if (($type[$loopthis] != 'members') && ($settings->condition != '' && $type[$loopthis] == 'links') || ($thecondition[$loopthis] != '')) $condition .= ' AND ';
 if ($thecondition[$loopthis] != '') $condition .= $thecondition[$loopthis];
 if ($settings->condition != '' && $type[$loopthis] == 'links')
 {
  if ($thecondition[$loopthis] != '') $condition .= ' AND ';
  $condition .= $settings->condition;    
 }
 if ($condition == '') $condition = 'id>0';
 if ($thestart[$loopthis] == '') $thestart[$loopthis] = 0;
 if ($type[$loopthis] == 'links') $condition .= ' AND alias=0';
 $query = $db->select($selectfields[$loopthis], $table[$loopthis], $condition, 'ORDER BY '. $field[$loopthis] .' '. $realorder[$loopthis], 'LIMIT '. $thestart[$loopthis] .','. $number[$loopthis]);
 $num[$loopthis] = $db->numrows($query);
 $ourtemplate = '';
 $rowcounter = 0; 
 if ($thecolumns[$loopthis] != '') $ourtemplate = '<tr>';
 for ($count=0; $count<$num[$loopthis]; $count++)
 {
  $row = $db->row($query);
  if ($type[$loopthis] == 'links')
  {
   $ourobject = new onelink('row', $row);
   $ourtemplate .= linkreplacements($blanktemplate, $ourobject);
  }
  if ($type[$loopthis] == 'categories')
  {
   $ourobject = new category('row', $row);  
   $ourtemplate .= categoryreplacements($blanktemplate, $ourobject);  
  }
  if ($type[$loopthis] == 'comments')
  {
   $ourobject = new comment('row', $row);
   $ourtemplate .= commentreplacements($blanktemplate, $ourobject);  
  }
  if ($type[$loopthis] == 'members')
  {
   $ourobject = new member('row', $row);
   $ourtemplate .= memberreplacements($blanktemplate, $ourobject);  
  }
  $ourtemplate = str_replace('{NUMBER}', $count+1, $ourtemplate);
  if (($thecolumns[$loopthis] != '') && ($rowcounter == $thecolumns[$loopthis] - 1)) { $ourtemplate .= '</tr> <tr>'; $rowcounter = 0; }
  else $rowcounter++;
 }
 if ($thecolumns[$loopthis] != '') $ourtemplate .= '</tr>';
 $totalpage = str_replace('{TOPLISTAREA}', $ourtemplate, $totalpage);
}
}
$istoplist = false;

// allow user to display the number shown in a dynamic fashion
for ($loopthis=1;$loopthis<10;$loopthis++) $totalpage = str_replace('{TOPLISTTOTAL'. $loopthis.'}', $num[$loopthis], $totalpage);

if (strstr($totalpage, '{CATOPTIONS}'))
{
 $catoptions = $settings->categoryselector;
 if ($thiscat) $catoptions = makeselection($catoptions, $thiscat->id);
 $totalpage = str_replace('{CATOPTIONS}', $catoptions, $totalpage);
}

$totalpage = memberreplacements($totalpage, $thismember); // if any member vars left over, assume they're for viewer
$memberpref = '{THISMEMBER';
if (strstr($totalpage, '{THISMEMBER')) $totalpage = memberreplacements($totalpage, $thismember); // get ones specially intended for viewer
$memberpref = '{MEMBER';
if (strstr($totalpage, '{')) $totalpage = settingsreplacements($totalpage);
// in case there's anything left, replace all templatevarized GET and POST vars with the code-stripped values
if (strstr($totalpage, '{')) $totalpage = generalreplacements($totalpage);

$totalpage = str_replace('{TEMPLATESDIR}', $templatesdir, $totalpage);

$totalpage = str_replace('{TID}', $template->filename, $totalpage);

if ($catid > 0) $ourcat = new category('id', $catid);
else $ourcat = 0;
if (strstr($totalpage, '{CAT')) $totalpage = categoryreplacements($totalpage, $ourcat);
$totalpage = str_replace('{LANGOPTIONS}', langoptions($languagegroup), $totalpage);
$totalpage = str_replace('{TEMPOPTIONS}', tempoptions($templatesdir), $totalpage);
$totalpage = str_replace('{STYLEOPTIONS}', stylesheets($settings->stylesheet), $totalpage);

if (@file_exists("templates/images_". $settings->stylesheet))
{
 $totalpage = str_replace($templatesdir .'/images', 'templates/images_'. $settings->stylesheet, $totalpage);
}

$totalpage = decodeit($totalpage);
$totalpage = str_replace('<tr></tr>', '', $totalpage); // clean up
$phpized = OutputPhpDocument($totalpage);

$db->closedb($connection); 

if ($debug && $debug != 6)
{
 if ($debug == 2) echo $debuginfo;
 if ($debug == 3) if ($thismember->isadmin()) echo $debuginfo;
 if (($debug != 3) || ($thismember->isadmin()))
 {
  if ($debug == 5)
  {
   if ($thismember->isadmin())
   {
    echo "$querycount total queries<br>";
    $mtime = microtime(); 
    $mtime = explode(" ",$mtime); 
    $mtime = $mtime[1] + $mtime[0]; 
    $endtime = $mtime; 
    $totaltime = ($endtime - $starttime); 
    echo "This page was created in ".$totaltime." seconds"; 
   }
  }
  else
  {
   echo "$querycount total queries<br>";
   $mtime = microtime(); 
   $mtime = explode(" ",$mtime); 
   $mtime = $mtime[1] + $mtime[0]; 
   $endtime = $mtime; 
   $totaltime = ($endtime - $starttime); 
   echo "This page was created in ".$totaltime." seconds"; 
  }
 }
}

$output = ob_get_contents();
ob_end_clean();
echo $output;
?>