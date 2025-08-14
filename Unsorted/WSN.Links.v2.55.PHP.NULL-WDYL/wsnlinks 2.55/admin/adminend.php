<?php

if (!($thismember->isadmin()) && (!$speciallogin))
{
 // send to login page
 if ($settings->dirurl != '') $path = $settings->dirurl . '/adminlogin.php';
 else $path = str_replace('/'. $settings->admindir .'/', '/', "http://". $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) ."/adminlogin.php");
 header("Location: $path");   
 die('Redirecting to login... <meta http-equiv="refresh" content="0;url=../adminlogin.php">');
}

if (!$template) $template = new template("blank");

// add footer
$customfooter = str_replace('-', '/', $customfooter .'.tpl');
if ($customfooter == '.tpl') $customfooter = 'footer.tpl';
$footer = new template($customfooter);

if ($nomemberinfo)
{
 $header->text = settingsreplacements($header->text);
 $footer->text = settingsreplacements($footer->text);
 $template->replace('{SECONDSDELAY}', $settings->secondsdelay);
}

// global admin section replacements
if (!$leaveencoded)
{
 $template->replace('{OPTIONSLINKFIELDS}', generatelinksoptions());
 $template->replace('{OPTIONSCATEGORYFIELDS}', generatecatsoptions());  
 $template->replace('{OPTIONSCOMMENTFIELDS}', generatecommentoptions());  
 $template->replace('{OPTIONSMEMBERFIELDS}', generatememberoptions());  
}

$temp = str_replace('userlogout', 'logout', $temp);
$footer->replace('{LANG_FOOTER_PROFILELOGOUT}', $temp);

if (strstr($header, '{TOTALLINKS}')) $header->replace('{TOTALLINKS}', $settings->totallinks);
if (strstr($header, '{TOTALHITS}')) $header->replace('{TOTALHITS}', $settings->totalhits);
if (strstr($header, '{TOTALHITSIN}')) $header->replace('{TOTALHITSIN}', $settings->totalhitsin);
if (strstr($header, '{TOTALCATS}')) $header->replace('{TOTALCATS}', $db->numrows($db->select('id', 'categoriestable', 'parent=0', '', '')));
if (strstr($header, '{TOTALCOMMENTS}')) $header->replace('{TOTALCOMMENTS}', $settings->totalcomments);

// now that we've done everything, create page and display it
$header->replace('admin/index.php"', 'index.php"', $header);
$header->replace('index.php?action=displaycat', '../index.php?action=displaycat');
$footer->replace('admin/index.php', 'index.php');
$footer->replace('index.php?action=displaycat', '../index.php?action=displaycat');
$footer->replace('register.php', '../register.php');
$footer->replace('index.php?action=userlogin', '../index.php?action=userlogin');
if ($leaveencoded) { $justreplace = true; $leaveencoded = false; $header->text = OutputPhpDocument($header->text); $footer->text = OutputPhpDocument($footer->text); $justreplace = false; $leaveencoded = true;}
$header->text = categoryreplacements($header->text, 0);
$footer->text = categoryreplacements($footer->text, 0);
if ($nomemberinfo) { $footer->text = memberreplacements($footer->text, $thismember); 
$header->text = memberreplacements($header->text, $thismember); }

$totalpage = $header->text . $template->text . $footer->text;
$totalpage = str_replace('{TID}', $template->filename, $totalpage);


if (@file_exists("../templates/images_". $settings->stylesheet))
{
 $totalpage = str_replace('{TEMPLATESDIR}/images', '../templates/images_'. $settings->stylesheet, $totalpage);
 $totalpage = str_replace($templatesdir .'/images', '../templates/images_'. $settings->stylesheet, $totalpage);
}

// global variables and macros
$totalpage = str_replace('{AREA}', $area, $totalpage); // define area for page title
if (strstr($totalpage, '{TOTALMEMBERS}'))
{
 $gettotal = $db->select('id', 'memberstable', 'id>0', '', ''); 
 $totalmembers = $db->numrows($gettotal);
 $totalpage = str_replace('{TOTALMEMBERS}', $totalmembers, $totalpage);
}
if (strstr($totalpage, '{LANG_')) $totalpage = langreplacements($totalpage);
if (strstr($totalpage, '{CATOPTIONS}'))
{
 $catoptions = decodeit($settings->categoryselector);
 $totalpage = str_replace('{CATOPTIONS}', decodeit($catoptions), $totalpage);
}

$totalpage = str_replace('{LANGOPTIONS}', langoptions($languagegroup), $totalpage);
$totalpage = str_replace('{TEMPOPTIONS}', tempoptions($templatesdir), $totalpage);
$totalpage = str_replace('{STYLEOPTIONS}', stylesheets($settings->stylesheet), $totalpage);

if (!$nomemberinfo) 
{
 $totalpage = memberreplacements($totalpage, $thismember); // if any member vars left over, assume they're for viewer
 $totalpage = settingsreplacements($totalpage);

 if (strstr($totalpage, '{LASTUPDATE}')) $totalpage = str_replace('{LASTUPDATE}', strftime($settings->dateformat, $settings->lastupdate), $totalpage);
 
 $num = $HTTP_GET_VARS['num'];
 if (!$num) $num = $HTTP_POST_VARS['num'];
 for ($loopthis = 1; $loopthis<30; $loopthis++) // they couldn't have more than 30 on a page, could they?
 {
  if (strstr($totalpage, '<!-- BEGIN TOPLIST '. $loopthis .' -->'))
  { 
   $blanktemplate = templateextract($totalpage, '<!-- BEGIN TOPLIST '. $loopthis .' -->', '<!-- END TOPLIST '. $loopthis .' -->');
   $totalpage = str_replace($blanktemplate, '{TOPLISTAREA}', $totalpage);
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
   else $thestart[$loopthis] = 0;
   $type[$loopthis] = str_replace('<CONFIG>', '', $type[$loopthis]);
   if ($type[$loopthis] == 'entries' || $type[$loopthis] == 'items' || $type[$loopthis] == 'images') $type[$loopthis] = 'links';
   if ($type[$loopthis] == 'links') $selectfields[$loopthis] = $settings->linkfields;
   if ($type[$loopthis] == 'categories') $selectfields[$loopthis] = $settings->categoryfields; 
   if ($type[$loopthis] == 'comments') $selectfields[$loopthis] = $settings->commentfields;  
   if ($type[$loopthis] == 'members') $selectfields[$loopthis] = $settings->memberfields;
   if ($order[$loopthis] == 'ascending') $realorder[$loopthis] = 'ASC';
   else if ($order[$loopthis] == 'descending') $realorder[$loopthis] = 'DESC';
   else $realorder[$loopthis] = $order[$loopthis];
   $table[$loopthis] = $type[$loopthis] . 'table';
   $condition = '';
   if ($type[$loopthis] != 'members') $condition = 'validated=1'; 
   if (($type[$loopthis] == 'categories') || ($type[$loopthis] == 'links')) $condition .= ' AND hide=0';
   if (($type[$loopthis] != 'members') && ($settings->condition != '') || ($thecondition[$loopthis] != '')) $condition .= ' AND ';
   if ($thecondition[$loopthis] != '') $condition .= $thecondition[$loopthis];
   if ($settings->condition != '') $condition .= ' AND '. $settings->condition;    
   if ($condition == '') $condition = 'id>0';
   if ($thestart[$loopthis] == '') $thestart[$loopthis] = 0;
   if ($type[$loopthis] == 'links') $condition .= ' AND alias=0';
   $query = $db->select($selectfields[$loopthis], $table[$loopthis], $condition, "ORDER BY $field[$loopthis] $realorder[$loopthis]", "LIMIT $thestart[$loopthis],$number[$loopthis]");
   $num[$loopthis] = $db->numrows($query);
   $ourtemplate = '';
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
    $ourtemplate = str_replace('{NUMBER}', $count + 1, $ourtemplate);
   }
   $totalpage = str_replace('{TOPLISTAREA}', $ourtemplate, $totalpage);
  }
 }
 // allow user to display the number shown in a dynamic fashion
 for ($loopthis=1;$loopthis<10;$loopthis++) $totalpage = str_replace('{TOPLISTTOTAL'. $loopthis.'}', $num[$loopthis], $totalpage);
}

// in case there's anything left, replace all templatevarized GET and POST vars with the code-stripped values
$totalpage = generalreplacements($totalpage);
$memberpref = '{THISMEMBER';
$totalpage = memberreplacements($totalpage, $thismember); // get ones specially intended for viewer
$memberpref = '{MEMBER';

if (!$leaveencoded) $totalpage = decodeit($totalpage);
$phpized = OutputPhpDocument($totalpage);

if ($debug && $debug != 6)
{
 echo "$querycount total queries<br>";
 $mtime = microtime(); 
 $mtime = explode(" ",$mtime); 
 $mtime = $mtime[1] + $mtime[0]; 
 $endtime = $mtime; 
 $totaltime = ($endtime - $starttime); 
 echo "This page was created in ".$totaltime." seconds"; 
}
$db->closedb($connection); 

$output = ob_get_contents();
ob_end_clean();
echo $output;
?>