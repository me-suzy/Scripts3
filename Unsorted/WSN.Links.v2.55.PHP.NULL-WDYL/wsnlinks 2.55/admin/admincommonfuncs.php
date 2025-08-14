<?php

function integrationoptions($selected)
{ 
 $files = getfiles("../integration/");
 $integrationscripttitles = "None";
 foreach ($files as $file)
 {
  if (extension($file) == 'php' && !strstr($file, 'encoder'))
  {
   $integrationscripts .= ', '. str_replace('.php', '', $file);
   if ($file == 'invision.php')
    $integrationscripttitles .= ', Invision Board';
   else if ($file == 'vbulletin.php')
    $integrationscripttitles .= ', vBulletin 3.00';
   else if ($file == 'phpbb.php')
    $integrationscripttitles .= ', phpBB2';
   else if ($file == 'mambo.php')
    $integrationscripttitles .= ', Mambo Server';
   else if ($file == 'wsnlinks.php')
    $integrationscripttitles .= ', WSN Links';
   else if ($file == 'wsnguest.php')
    $integrationscripttitles .= ', WSN Guest';
   else if ($file == 'wsngallery.php')
    $integrationscripttitles .= ', WSN Gallery';					
   else if ($file == 'wsnmanual.php')
    $integrationscripttitles .= ', WSN Manual';						
   else
    $integrationscripttitles .= ', '. str_replace('.php', '', $file);						
  }
 }
 $arr = explode(', ', $integrationscripts);
 $arrt = explode(', ', $integrationscripttitles);
 $num = sizeof($arr);
 for ($x=0; $x<$num; $x++)
 {
  if ($arr[$x] == $selected) $options .= '<option value="'. $arr[$x] .'" selected>'. $arrt[$x] .'</option>';
  else $options .= '<option value="'. $arr[$x] .'">'. $arrt[$x] .'</option>';
 }
 return $options;
}

function getcatinfo($field, $fieldvalue, $condition)
{
 global $db, $settings;
 if ($condition == 'equals') $condition="$field='$fieldvalue'";
 if ($condition == 'like') $condition="$field LIKE '%$fieldvalue%'";
 return $db->select($settings->categoryfields, 'categoriestable', $condition, '', '');
}

function getlinkinfo ($field, $fieldvalue, $condition)
{
 global $db, $settings;
 if ($field == 'catid')
 {
  $query = $db->select('id', 'categoriestable', "name='$fieldvalue'", '', '');
  $fieldvalue = $db->rowitem($query);
 }
 if ($condition == 'equals') $condition = "$field='$fieldvalue'";
 else if ($condition == 'like') $condition = "$field LIKE '%$fieldvalue%'";
 else if ($condition == 'greater') $condition = "$field > '$fieldvalue'";
 else if ($condition == 'less') $condition = "$field < '$fieldvalue'";
 return $db->select($settings->linkfields, 'linkstable', $condition, '', '');
}

function getcommentinfo($field, $fieldvalue, $condition)
{
 global $db, $settings;
 if ($condition == 'equals') $condition="$field='$fieldvalue'";
 if ($condition == 'like') $condition="$field LIKE '%$fieldvalue%'";
 return $db->select($settings->commentfields, 'commentstable', $condition, '', '');
}

function getmemberinfo($field, $fieldvalue, $condition)
{
 global $db, $settings;
 if ($condition == 'equals') $condition="$field='$fieldvalue'";
 if ($condition == 'like') $condition="$field LIKE '%$fieldvalue%'";
 return $db->select($settings->memberfields, 'memberstable', $condition, '', '');
}

function outputoptions($selected)
{
 $outputoptions = ", jpeg, png, bmp";
 $outputtitles = "use original type, jpeg, png, bmp";
 $arr = explode(', ', $outputoptions);
 $arrt = explode(', ', $outputtitles);
 $num = sizeof($arr);
 for ($x=0; $x<$num; $x++)
 {
  if ($arr[$x] == $selected) $options .= '<option value="'. $arr[$x] .'" selected>'. $arrt[$x] .'</option>';
  else $options .= '<option value="'. $arr[$x] .'">'. $arrt[$x] .'</option>';
 }
 return $options;
}

function encodesqlline($sqltext)
{
 $sqltext = str_replace('&lt;', '&lt&', $sqltext);
 $sqltext = str_replace('&gt;', '&gt&', $sqltext);
 $sqltext = str_replace('&#59;', '&#59&', $sqltext);
 $sqltext = str_replace('&#125;', '&#125&', $sqltext);
 $sqltext = str_replace('&#123;', '&#123&', $sqltext);
 $sqltext = str_replace('&quot;', '&quot&', $sqltext);  
 $sqltext = str_replace('&amp;', '&amp&', $sqltext);
 $sqltext = str_replace('&#39;', '&#39&', $sqltext);
 $sqltext = str_replace('&#34;', '&#34&', $sqltext);
 $sqltext = str_replace('&#8242;', '&#8242&', $sqltext);
 $sqltext = str_replace(';', '{semicolon}', $sqltext);
 return $sqltext;
}


function decodesqlline($sqltext)
{
 $sqltext = encodeit($sqltext);
 $sqltext = str_replace('&lt&', '&lt;', $sqltext);
 $sqltext = str_replace('&gt&', '&gt;', $sqltext);
 $sqltext = str_replace('&#59&', '&#59;', $sqltext);
 $sqltext = str_replace('&#125&', '&#125;', $sqltext);
 $sqltext = str_replace('&#123&', '&#123;', $sqltext);
 $sqltext = str_replace('&quot&', '&quot;', $sqltext);  
 $sqltext = str_replace('&amp&', '&amp;', $sqltext);
 $sqltext = str_replace('&#39&', '&#39;', $sqltext);
 $sqltext = str_replace('&#34&', '&#34;', $sqltext);
 $sqltext = str_replace('&#8242&', '&#8242;', $sqltext);
 $sqltext = str_replace('{semicolon}', ';', $sqltext);
 return $sqltext;
}

function encodesql($sqltext)
{
 $sqltext = str_replace('&lt;', '&lt&', $sqltext);
 $sqltext = str_replace('&gt;', '&gt&', $sqltext);
 $sqltext = str_replace('&#59;', '&#59&', $sqltext);
 $sqltext = str_replace('&#125;', '&#125&', $sqltext);
 $sqltext = str_replace('&#123;', '&#123&', $sqltext);
 $sqltext = str_replace('&quot;', '&quot&', $sqltext);  
 $sqltext = str_replace('&amp;', '&amp&', $sqltext);
 $sqltext = str_replace('&#39;', '&#39&', $sqltext);
 $sqltext = str_replace('&#34;', '&#34&', $sqltext);
 $sqltext = str_replace('&#8242;', '&#8242&', $sqltext);
 return $sqltext;
}

function processsql($sqltext)
{
 global $db, $prefix;
 $sqltext = str_replace('{PREFIX}', $prefix, $sqltext);
 $sqlarray = explode(';', $sqltext);
 $num = sizeof($sqlarray);
 for ($count=0; $count<($num-1); $count++)
 {
  $sqlarray[$count] = str_replace('&lt&', '&lt;', $sqlarray[$count]);
  $sqlarray[$count] = str_replace('&gt&', '&gt;', $sqlarray[$count]);
  $sqlarray[$count] = str_replace('&#59&', '&#59;', $sqlarray[$count]);
  $sqlarray[$count] = str_replace('&#125&', '&#125;', $sqlarray[$count]);
  $sqlarray[$count] = str_replace('&#123&', '&#123;', $sqlarray[$count]);
  $sqlarray[$count] = str_replace('&quot&', '&quot;', $sqlarray[$count]);  
  $sqlarray[$count] = str_replace('&amp&', '&amp;', $sqlarray[$count]);
  $sqlarray[$count] = str_replace('&#39&', '&#39;', $sqlarray[$count]);
  $sqlarray[$count] = str_replace('&#34&', '&#34;', $sqlarray[$count]);
  $sqlarray[$count] = str_replace('&#8242&', '&#8242;', $sqlarray[$count]);
  $sqlarray[$count] = str_replace('{semicolon}', ';', $sqlarray[$count]);
  $sqlarray[$count] = stripslashes($sqlarray[$count]);
  $db->query($sqlarray[$count]);
 }
 return mysql_error();
}

function getunvallinks()
{
 global $db, $settings;
 $doit = $db->select($settings->linkfields, 'linkstable', 'validated=0', 'ORDER BY id ASC', '');
 return $doit;
}

function getunvalcats()
{
 global $db, $settings;
 $doit = $db->select($settings->categoryfields, 'categoriestable', 'validated=0', 'ORDER BY id ASC', '');
 return $doit;
}

function geturl($url)
{
 if (version_compare("4.3.0", phpversion(), "<"))
 { 
  $filecontents = @file_get_contents($url);
 }
 else
 {
  $fd = @fopen($url, 'rb');
  $filecontents = @fread ($fd, 30000000);
  @fclose ($fd);
 }
 return $filecontents;
}

function emailmembervalidation($thismem)
{
 global $language, $settings;
 $adminaddress = $settings->email;
 $submitter = $thismem->email;
 $subject = $language->email_validatemembertitle;
 $message = $language->email_validatememberbody;
 $message = memberreplacements($message, $thismem);   
 $message = str_replace('{DIRURL}', $settings->dirurl, $message);
 $message = decodeit($message);
 sendemail("$submitter", "$subject", "$message", "From: $adminaddress");
}


function fieldselector($type, $selected)
{
 global $settings, $searchfieldsissue;
 $selected = ' '. str_replace(',', ' ', $selected) . ' ';
 $fieldlist = $settings->$type;
 if ($type == 'linkfields' && !$searchfieldsissue) $fieldlist .= ',name,password';
 $fields = explode(',', $fieldlist);
 $num = sizeof($fields);
 for ($x=0; $x<$num; $x++)
 {
  if (strstr($selected, ' '. $fields[$x] .' ')) $stuff .= '<option value="'. $fields[$x] .'" selected>'. $fields[$x] .'</option>'; 
  else $stuff .= '<option value="'. $fields[$x] .'">'. $fields[$x] .'</option>';
 }
 return $stuff;
}


function languageupload($filename, $groupid)
{ // copy language from setup over to regular language dir
 global $inadmindir;
 if ($inadmindir) $path = '../';
 $path .= 'languages/'. $groupid .'.lng';
 $test = @copy($filename, $path);

 if (!$test) 
 { 
  echo 'Your languages directory is not writable, so the language file cannot be copied. Either the directory is not chmoded to 777 or the server permissions do not allow a file to be copied (most likely php is running in safe mode). You will have to manually move the file /languages/setup/'. $filename .' to /languages/'. $groupid .'.lng in order to select your languages properly.';
 }
 return $test;
}


function languageappend($filename, $groupid)
{
 global $itemsadded, $debug, $inadmindir;
 // This function adds language only where the item name doesn't already exist in the set
 $testlang = new language($groupid);
 $oldlangnames = $testlang->allnames();
 $text = fileread($filename);
 if (stristr($text, 'INSERT INTO {PREFIX}')) 
 { // maintain backwards compatability with 2.3x language sets
  echo "Cannot append using 2.3x style language. Please use a 2.40+ language file.";
  $result = false;
 }
 else
 {
  $thestuff = explode('|||END LINE|||', $text);
  $num = sizeof($thestuff);
  for ($c=0; $c<$num; $c++)
  {
   $line = explode('|||DIVIDER|||', $thestuff[$c]);
   $name = decodesqlline($line[0]);
   $value = decodesqlline($line[1]);
   if (($name != '') && (!stristr($oldlangnames, $name)))
   {
    $stufftoappend .= $name .'|||DIVIDER|||'. $value .'|||END LINE|||';
    if ($debug == 1) echo "$name was not found so its being inserted. <br>";
    $itemsadded .= '|||'. $name .'|||';
   }
  }
  if ($inadmindir) $path = '../';
  $path .= 'languages/'. $groupid .'.lng';
  if ($stufftoappend != '') { $test = fileappend($path, $stufftoappend); if (!$test) echo "Cannot write to the file $path! You must chmod it to 666."; }
 }
 return $result;
}


function languageoverwrite($filename, $groupid)
{
 global $inadmindir, $itemsadded, $debug;
 $result = true;
 /* This function writes the contents of the file to the language, overwriting existing values, but leaves anything not specified in the file alone. Useful for spelling corrections, etc. */
 $testlang = new language($groupid);
 $newdata = $testlang->sourcedata;
 $oldlangnames = $testlang->allnames();
 $text = fileread($filename);
 if (stristr($text, 'INSERT INTO {PREFIX}')) 
 { // maintain backwards compatability with 2.3x language sets
  echo "Cannot overwrite using 2.3x style language. Please use a 2.4+ language file.";
  $result = false;
 }
 else
 {
  $thestuff = explode('|||END LINE|||', $text);
  $num = sizeof($thestuff);
  for ($c=0; $c<$num; $c++)
  {
   $line = explode('|||DIVIDER|||', $thestuff[$c]);
   $name = encodesqlline($line[0]);
   $value = encodesqlline($line[1]);
   if (($name != '') && stristr($oldlangnames, $name))
   {
    $o = limitedtemplateextract($newdata, '|||END LINE|||'. $name ,'|||END LINE|||');
	$o = $name . $o .'|||END LINE|||';
	$n = $name .'|||DIVIDER|||'. $value .'|||END LINE|||';
    $newdata = str_replace($o, $n, $newdata);
    if ($debug == 1) echo "updated $name to $value <br>";
    $itemsadded .= '|||'. $name .'|||';
   }
  }
  if ($inadmindir) $path = '../';
  $path .= 'languages/'. $groupid .'.lng';
  $test = filewrite($path, $newdata);
  if (!$test) echo "Cannot write to the file $test, you must chmod it to 666.";
 }
 return $result;
}

function admindownload($content, $filename)
{
 header("Pragma: public");
 switch (extension($filename))
 {
 case 'zip':
 header("Content-type: application/zip");
 break;

 case 'gif':
 header("Content-type: image/gif");
 break;

 case 'png':
 header("Content-type: image/png");
 break;

 case 'jpeg':
 case 'jpg':
 header("Content-type: image/jpeg");
 break;
 
 case 'bmp':
 header("Content-type: image/x-xbitmap");
 break;

 case 'swf':
 header("Content-type:  application/x-shockwave-flash");
 break;
 
 default:
 header("Content-type: ". extension($filename)); 
 }
 // It will be called by the file title (say, x.zip), even though saved as something else (random id by default)
 header("Content-Disposition: attachment; filename=$filename");
 // The real source 
 echo $content;
 die(""); // stop processing so we don't get the current page stuck in the file
}

function extractphp($phpDocument)
{
 $questMark = "?";
 $phpOpenTag = "<${questMark}php";
 $phpCloseTag = "${questMark}>";
 return ("$phpCloseTag" . stripslashes($phpDocument) . "$phpOpenTag ");
}

function sponsortypeoptions($selected)
{
 if ($selected != 'none') $options = '<option value="none">None</option>';
 else $options = '<option value="none" selected>None</option>';
 if ($selected != 'promotion') $options .= '<option value="promotion">Link group promotion</option>';
 else $options .= '<option value="promotion" selected>Link group promotion</option>';
 return $options;
}


function debugmenu($current)
{
 if (($current == 0) || ($current == 'no')) $stuff = '<option value="0" selected>None</option>';
 else $stuff .= '<option value="0">Normal</option>';
 if ($current == 1) $stuff .= '<option value="1" selected>Show queries as executed</option>';
 else $stuff .= '<option value="1">Show queries as executed</option>';
 if ($current == 2) $stuff .= '<option value="2" selected>Show queries at bottom after page is complete</option>';
 else $stuff .= '<option value="2">Show queries at bottom after page is complete</option>';
 if ($current == 3) $stuff .= '<option value="3" selected>Only show queries to admin, not to other users</option>';
 else $stuff .= '<option value="3">Only show queries to admin, not to other users</option>';
 if ($current == 4) $stuff .= '<option value="4" selected>Only show totals and execution time</option>';
 else $stuff .= '<option value="4">Only show totals and execution time</option>';
 if ($current == 5) $stuff .= '<option value="5" selected>Show totals and execution time to admin only</option>';
 else $stuff .= '<option value="5">Show totals and execution time to admin only</option>';
 if ($current == 6) $stuff .= '<option value="6" selected>Suppress all errors</option>';
 else $stuff .= '<option value="6">Suppress all errors</option>'; 
 return $stuff;
}

function registrationmenu($current)
{
 if ($current == 'direct') $stuff = "<option value='direct' selected>Direct</option>";
 else $stuff .= "<option value='direct'>Direct</option>";
 if ($current == 'email') $stuff .= "<option value='email' selected>E-mail activation code</option>";
 else $stuff .= "<option value='email'>E-mail activation code</option>";
 if ($current == 'validate') $stuff .= "<option value='validate' selected>Require validation by admin</option>";
 else $stuff .= "<option value='validate'>Require validation by admin</option>";
 return $stuff;
}

function graphicsprog($current)
{
 if ($current == 'GD') $stuff = "<option value='GD' selected>GD</option>";
 else $stuff .= "<option value='GD'>GD</option>";
 if ($current == 'imagemagick') $stuff .= "<option value='imagemagick' selected>Image Magick</option>";
 else $stuff .= "<option value='imagemagick'>Image Magick</option>";
 return $stuff;
}

function customtemplates()
{ // list custom templates as options, by reading the custom directory
 global $templatesdir;
 if($handle = opendir("../$templatesdir/custom/"))
 {
  // Loop through all files
  while(false !== ($file = readdir($handle)))
  {  
   // Ignore hidden files
   if(!preg_match("/^\./", $file))
   {    
    // Put dirs in $dirs[] and files in $files[]
    if(!is_dir($file))
    {
     $files[] = $file;
    }
   }
  }
  closedir($handle);
  if(is_array($files))
  {
   sort($files);
   foreach($files as $file)
   {
    $base = str_replace('.'. extension($file), '', $file);
    if (extension($file) == 'tpl')
     $options .= '<option value="custom-'. $base .'">'. $base .'</option>';
   }
  }
 }
 return $options;
}

function templatesglobalreplace($previous, $new)
{
 global $templatesdir, $debug;
 $realdebug = $debug;
 $debug = 6; // don't give people a heart attack with all the warnings that would show up
 $dirs[0] = '../'. $templatesdir;
 $dirs[1] = '../'. $templatesdir .'/admin';
 $dirs[2] = '../'. $templatesdir .'/custom';
 $dirs[3] = '../templates/styles';
 for ($x=0; $x<sizeof($dirs); $x++)
 {
  $files = '';
  $inputdir = $dirs[$x];
  $outputdir = $dirs[$x];
  if($handle = opendir($inputdir))
  {
   while(false !== ($file = readdir($handle)))
   {  
    if(!preg_match("/^\./", $file))
    {    
     if(!is_dir($file))
     {
       $files[] = $file;
     }
    }
   }
   closedir($handle);
   if(is_array($files))
   {
    sort($files);
    foreach($files as $file)
    {
     $data = fileread($inputdir .'/'. $file);
     if (extension($file) == 'tpl')
     {
      $data = str_replace($previous, $new, $data);
      $doit = filewrite($outputdir .'/'. $file, $data);
      echo "Contents of file $file altered.<br>";
     }
    }
   }
  }
 }
 $debug = $realdebug;
 return true;
}
?>