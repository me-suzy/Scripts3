<?php
// These functions are common to all WSN scripts

function texttoascii($text)
{
 $chars = str_split($text);
 for ($x=0; $x<sizeof($chars); $x++)
 {
  $ascii .= ord($chars[$x]) .' | ';
 }
 return $ascii;
}

function asciitotext($ascii)
{
 $chars = explode(' | ', $ascii);
 for ($x=0; $x<sizeof($chars); $x++)
 {
  $text .= chr($chars[$x]);
 }
 return $text;
}

function marknew($time)
{
 global $settings, $inadmindir, $templatesdir;
 $maxdaysago = $settings->marknew;
 $maxtimeago = $maxdaysago * 24 * 60 * 60;
 $compare = time() - $time;
 if (($compare < $maxtimeago) && ($time > 0))
 {
  $new = '<img src="';
  if ($inadmindir) $new .= '../';
  $new .= $templatesdir .'/images/new.gif" alt="">';
 }
 else
   $new = '';
 return $new;
}

function makecookie($name, $value, $duration)
{
 setcookie($name, "$value", $duration);
 return true;
}

function stopvars($item)
{
 $item = str_replace('{', '{[NOTVAR]', $item);
 $item = str_replace('<!-- BEGIN ', '<!-- B[NOTVAR]EGIN ', $item);
 $item = str_replace('<!--', '&#60;&#33;&#45;&#45;', decodeit($item));
 $item = str_replace('-->', '&#45;&#45;&#62;', decodeit($item));
 $item = str_replace('[IFADMIN', '[IFAD[NOTVAR]MIN', $item);
 return $item;
}

function compressoutput($output)
{
  return gzencode($output);
}

function buffereval($code)
{
 ob_start();
 eval($code);
 $output = ob_get_contents();
 ob_end_clean();
 return $output;
}

function OutputPhpDocument($doc)
{
 // want to do something with the page before final output? the full page is in $doc
 global $inadmindir, $leaveencoded, $justreplace, $settings, $nomemberinfo;
 // begin apache mod_rewrite stuff
 if ($settings->apacherewrite == 'yes')
 { 
  if ($inadmindir)
  {
   require '../modrewrite.php';
  }
  else
  {
   require 'modrewrite.php';
  }
 }  
 // end mod_rewrite stuff

 if (!$leaveencoded)
 {
  if ($inadmindir)
  {
   $doc = str_replace('[IFADMIN../]', '../', decodeit($doc));
   $doc = str_replace('[IFADMINadmin/]', '', decodeit($doc));
  }
  else
  {
   $doc = str_replace('[IFADMIN../]', '', decodeit($doc));
   $doc = str_replace('[IFADMINadmin/]', $settings->admindir. '/', decodeit($doc));
  }
 }

 // translate conditionals syntax to php
 if ($inadmindir)
 {
  require '../conditionals.php';
 }
 else
 {
  require 'conditionals.php';
 }

 $doc = str_replace('[NOTVAR]', '', $doc);

 if ($justreplace)
   return $doc;
 else
   finaloutput($doc); 

}
 
function filewrite($filename, $data)
{
 global $debug;
// supply a file name and I'll write your data to it
 if ($debug == 6)
 {
  $fd = @fopen($filename, 'wb');
  $check = @fwrite ($fd, $data);
  @fclose ($fd); 
 }
 else
 {
  $fd = fopen($filename, 'wb');
  $check = fwrite ($fd, $data);
  fclose ($fd);
 }
 return $check;
}

function fileappend($filename, $data)
{
 global $debug;
// supply a file name and I'll append your data to the end of it
 if ($debug == 6)
 {
  $fd = @fopen($filename, 'ab');
  $check = @fwrite ($fd, $data);
  @fclose ($fd); 
 }
 else
 {
  $fd = fopen($filename, 'ab');
  $check = fwrite ($fd, $data);
  fclose ($fd);
 }
 return $check;
}

function parsemessage($message)
{
 global $settings, $inadmindir;
 // convert carrige returns to html line breaks
 if (is_numeric($message) || ($message=='')) { return $message; }
 if ($message != '')
 {
 $message = str_replace("\n", '<br>', $message);

 // apply censor/replacements
 $censorlist = explode('|||', $settings->censor);
 $num = sizeof($censorlist);
 for ($x=0; $x<$num; $x++)
 {
  $thiscensor = explode('[,]', $censorlist[$x]);
  $original = $thiscensor[0];
  $replacement = $thiscensor[1];
  if ($original != '' && strstr($message, $original)) $message = str_replace($original, $replacement, $message);
 }

 if (strstr($message, '[')) $message = dowsncodes($message); // WSN Codes are slow: only do if needed

 // do smilies
 $codelist = explode('|||', $settings->smilies);
 $num = sizeof($codelist);
 for ($c=0; $c<$num; $c++)
 {
  $thiscode = explode(',', $codelist[$c]);
  $original = $thiscode[0];
  $replacement = $thiscode[1];
  if ($original != '') { if (strstr($message, $original)) $message = str_replace(stripcode($original), $replacement, $message); }
 }

 $message = preg_replace("/( [\w]+:\/\/[\w-?&;#~=\.\/\@]+[\w\/])/i",
"<a href=\"$1\" target=\"_blank\">$1</A>", 
$message); //make all URLs into links but try not to dupicate
  $message = str_replace('\\', '\\\\', $message); 
 }
 return $message;
}

function sendtosite($url)
{
 $fd = @fopen($url, 'rb');
 @fclose ($fd);
 return true;
}

function extension($file)
{
 if (strstr($file, '.'))
 {
  $groups = explode('.', $file);
  $extension = end($groups);
  return strtolower($extension);
 }
 else return false;
}

function trimleft($old_string, $char_count)
{
 return substr($old_string, $char_count);
}

function trimright($old_string, $char_count)
{
 return substr($old_string, 0, -$char_count);
}

function trimtochars($item, $chars)
{
 return substr($item, 0, $chars);
}

function str_split($chaine, $length=1)
{ 
 $retour = FALSE;
 $incrmt= (int)$length;
 if (0 < $incrmt)
 {
  $retour= array();
  $offset= 0;
  $limite= strlen($chaine);
  while ($offset < $limite)
  {
   $retour[]= substr($chaine, $offset, $incrmt);
   $offset += $incrmt;
  }
 }
 return $retour;
}

function fileread($filename)
{ 
 global $debug;
// supply a file name and I'll send you the contents of that file
 if ($debug == 6)
 {
  $fd = @fopen($filename, 'rb');
  $filecontents = @fread ($fd, filesize($filename));
  @fclose ($fd); 
 }
 else
 {
  if (file_exists($filename))
  {
   $fd = fopen($filename, 'rb');
   $filecontents = fread ($fd, filesize($filename));
   fclose ($fd);
  }
  else echo "The file $filename does not exist.<br>";
 }
 return $filecontents;
}

function templateextract($template, $stringbegin, $stringend)
{
 // Must leave beginning string in so that we don't end up replacing two areas that have the rest of the same HTML!
 if (strstr($template, $stringbegin) && strstr($template, $stringend))
 {
  $start = strpos($template, $stringbegin); // get beginning position
  $stop = strpos($template, $stringend); // get end position
  $length = $stop - $start; // calculate length
  $subtemplate = substr($template, $start, $length); // carve out what we want
  return $subtemplate;
 }
 else return false;
}

function limitedtemplateextract($template, $stringbegin, $stringend)
{
 // Don't leave beginning string in
 if (strstr($template, $stringbegin) && strstr($template, $stringend))
 {
  $start = strpos($template, $stringbegin) + strlen($stringbegin); // get beginning position
  $restoftemplate = substr($template, $start);
  $stop = strpos($restoftemplate, $stringend) + $start; // get end position
  $length = $stop - $start; // calculate length
  $subtemplate = substr($template, $start, $length); // carve out what we want
  return $subtemplate;
 }
 else return false;
}

function yesno($selected)
{
 global $language;
 if (($selected == 'no') || ($selected == '0'))
 {
  $stuff = '<option value="yes">'. $language->yes .'</option><option value="no" selected>'. $language->no .'</option>';
 }
 else
 {
  $stuff = '<option value="yes" selected>'. $language->yes .'</option><option value="no">'. $language->no .'</option>';
 }
 return $stuff;
}

function radioyesno($thename, $selected)
{
 global $language;
 if (($selected == 'yes') || ($selected == '1'))
 {
  $stuff = '<input type="radio" name="'. $thename .'" value="yes" checked> '. $language->yes .' <input type="radio" name="'. $thename .'" value="no"> '. $language->no;
 }
 else
 {
  $stuff = '<input type="radio" name="'. $thename .'" value="yes"> '. $language->yes .' <input type="radio" name="'. $thename .'" value="no" checked> '. $language->no;
 }
 return $stuff;
}

function defaultyesno($selected)
{
 global $language;
 $stuff = '<option value="">'. $language->general_usedefault .'</option>';

 if ($selected == 'yes') $stuff .= '<option value="yes" selected>'. $language->yes .'</option>';
 else $stuff .= '<option value="yes">'. $language->yes .'</option>';

 if ($selected == 'no') $stuff .= '<option value="no" selected>'. $language->no .'</option>';
 else $stuff .= '<option value="no">'. $language->no .'</option>';

 return $stuff;
}

function encodeit($item)
{
 $item = ltrim($item);
 $item = str_replace("'", '&#39;', $item);
 $item = str_replace('"', '&#34;', $item);
 $item = str_replace('&quot;', '&#34;', $item);
 $item = str_replace('<', '&lt;', $item);
 $item = str_replace('>', '&gt;', $item);
 return $item;
}

function decodeit($item)
{
 if (is_numeric($item) || $item == '' || strpos($item, '&') === FALSE) { return $item; } 
 
 $item = str_replace('&#59;', ';', $item);
 $item = str_replace('&#125;', '}', $item);
 $item = str_replace('&#123;', '{', $item);
 $item = str_replace('&gt;', '>', $item); 
 $item = str_replace('&lt;', '<', $item);
 $item = str_replace('&quot;', "\"", $item);  
 $item = stripslashes($item);    
// $item = str_replace("&#39;", "'",$item);
 $item = str_replace('&#34;', '"', $item);
 $item = str_replace("&#8242;", "'", $item);
 return $item;
}

function decodelanguage($item)
{
 if (is_numeric($item) || $item == '' || strpos($item, '&') === FALSE) { return $item; } 

 $item = str_replace('&lt&', '&lt;', $item);
 $item = str_replace('&gt&', '&gt;', $item);
 $item = str_replace('&#59&', '&#59;', $item);
 $item = str_replace('&#125&', '&#125;', $item);
 $item = str_replace('&#123&', '&#123;', $item);
 $item = str_replace('&quot&', '&quot;', $item);  
 $item = str_replace('&amp&', '&amp;', $item);
 $item = str_replace('&#39&', '&#39;', $item);
 $item = str_replace('&#34&', '&#34;', $item);
 $item = str_replace('&#8242&', '&#8242;', $item);
 $item = str_replace('{semicolon}', ';', $item);
 $item = str_replace("&#39;", "'",$item); 
 $item = str_replace('&#59;', ';', $item);
 $item = str_replace('&#125;', '}', $item);
 $item = str_replace('&#123;', '{', $item);
 $item = str_replace('&gt;', '>', $item); 
 $item = str_replace('&lt;', '<', $item);
 $item = str_replace('&quot;', "\"", $item);  
 $item = stripslashes($item);    
 $item = str_replace('&#34;', '"', $item);
 $item = str_replace("&#8242;", "'", $item);
 return $item;
}

function decodelite($item)
{
 $item = str_replace('&#59;', ';', $item);
 $item = str_replace('&#125;', '}', $item);
 $item = str_replace('&#123;', '{', $item);
 $item = str_replace('&gt;', '>', $item); 
 $item = str_replace('&lt;', '<', $item);
 $item = str_replace('&quot;', '"', $item);  
// $item = str_replace("&#39;", "'",$item);
// $item = str_replace('&#34;', '"', $item);
 $item = str_replace("&#8242;", "'", $item);
 return $item;
}

function generalreplacements($totalpage)
{
 // in case there's anything left, replace all templatevarized GET and POST and COOKIE vars with the code-stripped values
 while(list($key, $value) = each($_REQUEST)) 
 {
  $tempvar = '{'. strtoupper($key) .'}';
  $totalpage = str_replace($tempvar, stripcode($value), $totalpage); 
 } 
 return $totalpage;
}

function sendemail($to, $subject, $message, $from)
{
 global $debug;
 if ($debug == 1) echo "<p>Sending e-mail titled $subject to $to , $from. Contents: $message";
 mail($to, $subject, $message, $from);
 return true;
}

function stripall($message)
{ // for spots where it shouldn't be left up to the setting
 $message = str_replace('<', '&#60;', $message);
 $message = str_replace('>', '&#62;', $message);
 $message = str_replace('&lt;', '&#60;', $message);
 $message = str_replace('&gt;', '&#62;', $message);
 return $message;
}

function striphtml($message)
{
 global $thismember;
 if (!($thismember->groupcanusehtml))
 {
  $message = str_replace('<', '&#60;', $message);
  $message = str_replace('>', '&#62;', $message);
  $message = str_replace('&lt;', '&#60;', $message);
  $message = str_replace('&gt;', '&#62;', $message);
 }
 return $message;
}

function stripcode($message)
{
 $message = str_replace('<?', '&#60;?', $message);
 $message = str_replace('?>', '?&#62;', $message);
 $message = str_replace('&lt;?', '&#60;?', $message);
 $message = str_replace('?&gt;', '?&#62;', $message);
 return $message;
}

function finaloutput($phpDocument)
{
 global $template, $debug;
 $phpDocument = str_replace('"/>', '">', $phpDocument); // for standards nazis
 $questMark = "?";
 $phpOpenTag = "<${questMark}php";
 $phpCloseTag = "${questMark}>";
 $result = buffereval("$phpCloseTag" . stripslashes($phpDocument) . "$phpOpenTag ");
 if (strstr($result, 'parse error') && !strstr($result, '</html>'))
 {
  if ($debug == 6) { global $header, $footer; echo $header->text . $template->text . $footer->text; die(); }  
  // make a very friendly parse error handler
  $linearray = explode('on line', $result);
  $line2 = $linearray[1];
  $line2 = explode(' ', ltrim($line2));
  $line = $line2[0];
  $line = str_replace('b', '', $line);
  $line = str_replace('r', '', $line);
  $line = str_replace('/', '', $line);
  $line = str_replace('>', '', $line);
  $line = str_replace('<', '', $line);
  echo "If you are not the administrator of this site, please report this page to the administrator. If you are the administrator, please pay careful attention: You have a parse error in your template ". $template->filename ." (or perhaps in your header or footer) which you need to repair before this page can be displayed correctly. The error is picked up on by php at line $line of the output.";

  $numopen = substr_count($template->text, '<IF');
  $numclose = substr_count($template->text, '</IF>');
  if ($numopen != $numclose) echo "You appear to have a different number of opening &lt;IF&gt; conditionals than you do closing &lt;/IF&gt; tags! This is almost certainly the cause of your parse error. You must close every conditional you open.";
  else
  {
   echo " Here are the nearby lines in your template where the problem is likely to be: <br>";
   global $header, $footer;
   $origtemplate = new template($template->filename);
   $parselinenumbers = explode("\n", $phpDocument);
   $tempdoc = $header->text . $origtemplate->text . $footer->text;
   $linenumbers = explode("\n", $tempdoc);
   echo "<b>Line #". ($line-3) .":</b> ". stripall($linenumbers[$line-3]).'<br>';
   echo "<b>Line #". ($line-2) .":</b> ". stripall($linenumbers[$line-2]).'<br>';
   echo "<b>Line #". ($line-1) .":</b> ". stripall($linenumbers[$line-1]).'<br>';
   echo "<b>Line #". $line .":</b> ". stripall($linenumbers[$line]).'<br>';
echo "<br>In case the above output is off, this is what the area looks like during parsing:<br>";
   echo "<b>Line #". ($line-3) .":</b> ". stripall($parselinenumbers[$line-3]).'<br>';
   echo "<b>Line #". ($line-2) .":</b> ". stripall($parselinenumbers[$line-2]).'<br>';
   echo "<b>Line #". ($line-1) .":</b> ". stripall($parselinenumbers[$line-1]).'<br>';
   echo "<b>Line #". $line .":</b> ". stripall($parselinenumbers[$line]).'<br>';

   $temppath = end(explode('/', $template->filename));
   $temppath = str_replace('.', '&ext=', $temppath);
   echo "<br>[<a href=admin/templates.php?TID=". $temppath .">Load this template in your template editor</a>]<br>";
   echo "<br>The source of your error is probably not on line $line itself, but most likely a line or two before it. Check your code carefully for syntax mistakes. If you cannot recognize one, copy and paste this output into a thread on the support forum.";
  }
   echo "<br>Now outputing the page without any conditional or php sections evaluated: <br>";
   echo $phpDocument;
 }
 else
 {
   echo $result;
 }
}

function fix($footer)
{
// Everyone's a customer now, so what is this function for? Nostalgia, I guess... and to take it out if anybody left their 'powered by' in.
  $footer = str_replace('{POWEREDBY}', '', $footer);
  return $footer;
} 

function makeselection($stuff, $selection)
{
  $stuff = decodeit($stuff);
  $num = sizeof(explode('|||', $selection));
  if ($num < 2)
  {
    $final = str_replace('value="'.$selection .'"', 'value="'. $selection .'" selected', $stuff);
  }
  else
  {
   $selection = explode('|||', $selection);
   $final = $stuff;
   for ($count=0; $count<$num; $count++) 
   { 
    $thevalue = $selection[$count];
    $final = str_replace('value="'. $thevalue .'">', 'value="'. $thevalue .'" selected>', $final);
   }
  }
 return $final;
}

function langoptions($selected)
{
 global $settings, $inadmindir;
 if ($inadmindir) $path = '../';
 $path .= 'languages';
 $sets = getfiles($path, 'lng');
 if (is_array($sets))
 {
  sort($sets);
  foreach ($sets as $dir)
  {
   if ( !strstr($dir, 'setup') && !strstr($dir, 'hide') && ((!strstr($dir, 'adminonly') || $thismember->isadmin())) )
   {
	$dir = str_replace('.lng', '', $dir);
    if ($dir == $selected) $langoptions .= '<option value="'. $dir .'" selected>'. $dir .'</option>';
    else $langoptions .= '<option value="'. $dir .'">'. $dir .'</option>';
   }
  }
 }
 return $langoptions;
}

function tempoptions($selected)
{
 global $settings, $inadmindir, $thismember;
 if ($inadmindir) $path = '../';
 $path .= 'templates/';
 $test = explode(',', $settings->templates);
 if (in_array('templates', $test) && file_exists($path.'header.tpl')) $sets[] = 'templates';
 $sets = getsubdirectories($path);
 if(is_array($sets))
 {
  sort($sets);
  foreach($sets as $dir)
  {
   if ( !strstr($dir, 'hide') && !strstr($dir, 'admin') && !strstr($dir, 'images') && !strstr($dir, 'style') && (!strstr($dir, 'adminonly') || $thismember->isadmin()) )
   {    
    if (file_exists($path . $dir .'/header.tpl'))
    {
     if (!strstr($dir, 'templates')) $dir = 'templates/'. $dir;
     $thedir = str_replace('templates/', '', $dir);
     if ($dir == $selected) $tempoptions .= '<option value="'. $dir .'" selected>'. $thedir .'</option>';
     else $tempoptions .= '<option value="'. $dir .'">'. $thedir .'</option>';
    }
   }
  }
 }
 return $tempoptions;
}

function getvalidlanguage()
{
 global $settings, $inadmindir, $thismember;
 if ($inadmindir) $path = '../';
 $path .= 'languages';
 $groups = getfiles($path, 'lng');
 if (is_array($groups))
 {
  sort($groups); 
  foreach ($groups as $group) 
  {
   $name = str_replace('.lng', '', $group);
   if ($name != '' && $name != 'setup') return $name;
  }
 }

 $path = '';
 if ($inadmindir) $path = '../';
 $path = 'languages/setup';
 $groups = getfiles($path, 'lng');
 if (is_array($groups))
 {
  sort($groups); 
  foreach ($groups as $group) 
  {
   $name = str_replace('.lng', '', $group);
   if ($name != '' && $name != 'setup') return $name;
  }
 }  
 die ("You don't have any languages! You must upload at least the languages/setup directory.");
 return $name;
}

function getvalidtemplate()
{
 global $settings, $inadmindir, $thismember;
 if ($inadmindir) $path = '../';
 $path .= 'templates/';
 $test = explode(',', $settings->templates);
 if (in_array('templates', $test) && file_exists($path.'header.tpl')) $sets[] = 'templates';
 $sets = getsubdirectories($path);
 if(is_array($sets))
 {
  sort($sets);
  foreach($sets as $dir)
  {
   if ( !strstr($dir, 'hide') && !strstr($dir, 'admin') && !strstr($dir, 'images') && !strstr($dir, 'style') && (!strstr($dir, 'adminonly') || $thismember->isadmin()) )
   {    
    if (file_exists($path . $dir .'/header.tpl'))
    {
     if (!strstr($dir, 'templates')) $dir = 'templates/'. $dir;
     $thedir = str_replace('templates/', '', $dir);
     return $dir;
    }
   }
  }
 }
 die("You do not have any template sets!");
}



function getsubdirectories($path)
{
 if($handle = opendir($path))
 {
  // Loop through all files
  while(false !== ($file = readdir($handle)))
  {  
   // Ignore hidden files
   if(!preg_match("/^\./", $file))
   {    
    // Put dirs in $dirs[] and files in $files[]
    if ((is_dir($file) || extension($file) == '') && $file != '')
    {
     $sets[] = $file;
    }
   }
  }
 }
 closedir($handle);
 return $sets;
}

function getfiles($path, $extension = '')
{ // collect all files from directory
 global $debug;
 if ($debug == 6)
 {
  if($handle = @opendir($path))
  {
   // Loop through all files
   while(false !== ($file = @readdir($handle)))
   {  
    // Ignore hidden files
    if(!preg_match("/^\./", $file))
    {    
     // Put files in $files[]
	 $ext = extension($file);
     if (!is_dir($file) && $ext != '' && $file != '')
     {
	  if ($extension == '' || $extension == $ext)
	  {
       $sets[] = $file;
	  }
     }
    }
   }
  }
  @closedir($handle);
 }
 else
 { 
  if($handle = @opendir($path))
  {
   // Loop through all files
   while(false !== ($file = readdir($handle)))
   {  
    // Ignore hidden files
    if(!preg_match("/^\./", $file))
    {    
     // Put files in $files[]
     if ((!is_dir($file) || extension($file) != '') && $file != '')
     {
      $sets[] = $file;
     }
    }
   }
  }
  closedir($handle);
 }
 return $sets;
}

function cattypeoptions($selected)
{
 global $settings, $language;
 if ($settings->cattypes == '') 
 {
  $typeoptions = '<option value="">N/A</option>';
 }
 else
 {
  if ($language->cattypes != '') $namesarray = explode(',', $language->cattypes);
  else if ($language->categorytypes != '') $namesarray = explode(',', $language->categorytypes);
  else $namesarray = explode(',', $settings->cattypes);
  $typearray = explode(',', $settings->cattypes);
  $num = sizeof($typearray);
  for ($c=0; $c<$num; $c++)
  {
   if ($typearray[$c] == $selected)
   {
    $typeoptions .= '<option value="'. $typearray[$c] .'" selected>'. $namesarray[$c];
    $typeoptions .= '</option>';
   }
   else
   {
    $typeoptions .= '<option value="'. $typearray[$c] .'">'. $namesarray[$c];
    $typeoptions .= '</option>';
   }
  }
 }
 return $typeoptions;
}


function categoryreplacements($template, $cat)
{ 
 global $language, $templatesdir, $settings, $thismember, $noparsing;
 $catpref = '{CAT';
 if ($cat == 0)
 {
  // blank out everything
  $cat = new category("blank", "blank");
 }
 
 // handle all the functions
 $catfuncarray = get_class_methods($cat);
 foreach($catfuncarray as $key) 
 {
  $tempvar = $catpref. strtoupper($key);
  if (strstr($template, $tempvar.'}'))
  {
   $func = $cat->$key();
   $replacewith = decodeit($func);
   $template = str_replace($tempvar .'}', $replacewith, $template);
  }
  if (strstr($template, $tempvar .'['))
  {
   $tempvar = $catpref . strtoupper($key) .'[';
   $param = limitedtemplateextract($template, $tempvar, ']');
   $func = $cat->$key($param);
   $replacewith = decodeit($func);
   $template = str_replace($tempvar . $param .']}', $replacewith, $template);
  }
 }  
 // now if not handled by functions, go direct to variables
 $catarray = get_object_vars($cat);
 while(list($key, $value) = each($catarray)) 
 {
  $tempvar = $catpref . strtoupper($key) .'}';
  $replacewith = decodeit($cat->$key);
  if ($key != 'headerinfo')
  {
   if (!strstr(' related subscribers parentnames parentids ', ' '. $key .' ') && strstr($replacewith, '|||'))
   {
    $replacewith = str_replace('|||', ', ', $replacewith);
    $subitems = explode(', ', $replacewith);
    for ($q=0; $q<sizeof($subitems); $q++)
    {
     $subitem = $item . $subitems[$q];
     $template = str_replace($catpref. strtoupper($key) . strtoupper($subitem) .'}', $language->yes, $template);
    }
   }
   $replacewith = stripcode($replacewith);
   if (!$noparsing) $replacewith = parsemessage($replacewith);
  }
  $template = str_replace($tempvar, $replacewith, $template);
  $yesnooption = $catref . strtoupper($key) . 'YESNO}';
  $template = str_replace($yesnooption, yesno($amember->$key), $template);  
 } 
 $template = str_replace('{DIRURL}', $settings->dirurl, $template); 
 $template = str_replace('{HIDESELECTOR}', yesno($cat->hide), $template); 
 $template = str_replace('{CATSELECTOR}', catselector($cat->parent, 'parent'), $template); 
 $template = str_replace('{RELATEDCATSELECTOR}', relatedcatselector($cat->related), $template); 
 $template = str_replace('{CATTYPEOPTIONS}', cattypeoptions($cat->type), $template); 
 
 // select appropriate items on custom fields
 $fields = explode(',', $settings->categoryfields);
 $num = sizeof($fields);
 for ($x=0; $x<$num; $x++)
 { 
  if (strstr($cat->$fields[$x], '|||'))
  {
   $fieldgroup = explode('|||', $cat->$fields[$x]);
   for ($y=0; $y<sizeof($fieldgroup); $y++) 
   {
    $template = selectit($template, $fields[$x], $fieldgroup[$y]);
   }
  }
 }
 return $template;
}  

function linkreplacements($template, $thelink)
{
 global $language, $isadmin, $templatesdir, $settings, $thismember, $noparsing, $admindir, $inadmindir, $incomplete, $pref, $count, $commentspage, $nomember, $weareediting;
 // handle all the functions
 $linkfuncarray = get_class_methods($thelink);
 foreach($linkfuncarray as $key) 
 {
  $tempvar = $pref . strtoupper($key);
  if (strstr($template, $tempvar .'}'))
  { 
   $func = $thelink->$key();
   $replacewith = decodeit($func);
   if (!$noparsing) $replacewith = parsemessage($replacewith);   
   $template = str_replace($tempvar .'}', $replacewith, $template);
  }
  if (strstr($template, $tempvar .'['))
  { 
   $tempvar .= '[';
   $param = limitedtemplateextract($template, $tempvar, ']');
   $func = $thelink->$key($param);
   $replacewith = decodeit($func);
   if (!$noparsing) $replacewith = parsemessage($replacewith);
   $template = str_replace($tempvar . $param .']}', $replacewith, $template);	
  }
 }
 // now if not handled by functions, go direct to variables
 if (strstr($template, 'YESNO}')) $isayesno = true; else $isayesno = false;
 $linkarray = get_object_vars($thelink);
 while(list($key, $value) = each($linkarray)) 
 {
  $tempvar = $pref . strtoupper($key) .'}';
  $replacewith = decodeit($thelink->$key);
 if (strstr($replacewith, '|||') && !strstr(' notify ', ' '. $key .' '))
  {
   $replacewith = trim(str_replace('|||', ', ', $replacewith), ', ');
   $template = str_replace($pref . strtoupper($key) .'}', $replacewith, $template);
   $subitems = explode(', ', $replacewith);
   for ($q=0; $q<sizeof($subitems); $q++)
   {
    $subitem = $item . $subitems[$q];
    $template = str_replace($pref . strtoupper($key) . strtoupper($subitem) .'}', $language->yes, $template);
   }
  }
  if (!strstr($key, 'stars'))
  {
   if ($incomplete) $replacewith = stripall($replacewith);
   else $replacewith = stripcode($replacewith);
   if (!$noparsing) $replacewith = parsemessage($replacewith);
  }
  $template = str_replace($tempvar, $replacewith, $template);
  $yesnooption = $pref . strtoupper($key) . 'YESNO}';
  if ($isayesno) $template = str_replace($yesnooption, yesno($thelink->$key), $template);  
 } 

 if ($count > 0) $template = str_replace('{NUMBER}', $count + 1, $template);
 $template = str_replace('{TRACKLINKURL}', 'link.php?id='. $thelink->id, $template); 
 $template = str_replace('{ALLOWEDEXTENSIONS}', $settings->filetypes, $template);
 if ($thelink->inalbum) $template = str_replace('{CATSELECTOR}', '<option value="'. $thelink->catid .'">'. $thelink->albumname, $template);
 else $template = str_replace('{CATSELECTOR}', catselector($thelink->catid, 'catid'), $template);
 $template = str_replace($pref .'NEW}', $thelink->isnew(), $template);
 $template = str_replace('{DIRURL}', $settings->dirurl, $template);
 $template = str_replace('{MYURL}', $settings->myurl, $template);
 if ($weareediting)
 {
  $template = str_replace('{NAVIGATION}', shownav($thelink->catid), $template);
 }
 if (!$commentspage && !$nomember && strstr($template, '{MEMBER') )
 {
  if ($thelink->ownerid > 0) $themem = new member('id', $thelink->ownerid); else $themem = new member('blank', 'blank');
  $template = memberreplacements($template, $themem);
 }

// select appropriate items on custom fields
 if ($weareediting)
 {
  $fields = explode(',', $settings->linkfields);
  $num = sizeof($fields);
  for ($x=0; $x<$num; $x++)
  { 
   if (strstr($thelink->$fields[$x], '|||'))
   {
    $fieldgroup = explode('|||', $thelink->$fields[$x]);
    for ($y=0; $y<sizeof($fieldgroup); $y++) 
    {
     $template = selectit($template, $fields[$x], $fieldgroup[$y]);
    }
   }
   else $template = selectit($template, $fields[$x], $thelink->$fields[$x]);
  }
 }
 return $template;
}

function selectit($text, $field, $item)
{
 $text = decodeit($text);
 $start = strpos($text, 'value="'. $item .'"');
 $start = $start-100;
 $section = substr($text, $start, 300); // assume we'll find it within 200 characters
 if (strstr($section, $field))
 {
  if (strstr($section, 'checkbox') || strstr($section, 'radio'))
  {
   if (!strstr($section, 'value="'. $item .'" checked'))
   {
    $newsection = str_replace('value="'. $item .'"', 'value="'. $item .'" checked', $section); 
    $text = str_replace($section, $newsection, $text);  
   }
  }
  else if (strstr($section, 'option value'))
  {
   if (!strstr($section, 'option value="'. $item .'" selected'))
   { 
    $newsection = str_replace('option value="'. $item .'"', 'option value="'. $item .'" selected', $section);
    $text = str_replace($section, $newsection, $text);  
   }
  }
 }
 return $text;
}

function settingsreplacements($template)
{
 global $settings;
 $listofem = $settings->allnames();
 $listarray = explode(',', $listofem);
 $num = sizeof($listarray);
 for ($count=0; $count<$num; $count++)
 {
  $setvar = '{'. strtoupper($listarray[$count]) .'}';
  $template = str_replace($setvar, str_replace('\\', '\\\\', $settings->$listarray[$count]), $template);
  $yesnooption = '{'. strtoupper($listarray[$count]) . 'YESNO}';
  $template = str_replace($yesnooption, yesno($settings->$listarray[$count]), $template);      
  $radiooption = '{'. strtoupper($listarray[$count]) . 'RADIO}';
  $template = str_replace($radiooption, radioyesno($listarray[$count], $settings->$listarray[$count]), $template);      
 }
 return $template;
}

function langreplacements($template)
{
 global $origlang;
 $language = $origlang;
 $langarray = get_object_vars($language);
 while(list($key, $value) = each($langarray)) 
 {
  $setvar = '{LANG_'. strtoupper($key) .'}';
  $template = str_replace($setvar, $value, $template);
  $setvar = '{U_LANG_'. strtoupper($key) .'}';
  $template = str_replace($setvar, strtoupper($value), $template);
  $setvar = '{L_LANG_'. strtoupper($listarray[$count]) .'}';
  $template = str_replace($setvar, strtolower($value), $template);
  $setvar = '{P_LANG_'. strtoupper($key) .'}';
  $template = str_replace($setvar, ucfirst($value), $template);
  $setvar = '{PALL_LANG_'. strtoupper($key) .'}';
  $template = str_replace($setvar, ucwords($value), $template);
 }
 return $template;
}

function commentreplacements($template, $comment)
{ 
 global $language, $isadmin, $templatesdir, $settings, $thismember, $noparsing, $admindir, $inadmindir, $istoplist;
 $commentpref = '{COMMENT';
 // handle all the functions
 $comfuncarray = get_class_methods($comment);
 foreach($comfuncarray as $key) 
 {
  $tempvar = $commentpref . strtoupper($key);
  if (strstr($template, $tempvar .'}'))
  {
   $func = $comment->$key();
   $replacewith = decodeit($func);
   if (!$noparsing) $replacewith = parsemessage($replacewith);   
   $template = str_replace($tempvar .'}', $replacewith, $template);
  }
  if (strstr($template, $tempvar .'['))
  {
   $tempvar .= '[';
   $param = limitedtemplateextract($template, $tempvar, ']');
   $func = $comment->$key($param);
   $replacewith = decodeit($func);
   if (!$noparsing) $replacewith = parsemessage($replacewith);
   $template = str_replace($tempvar . $param .']}', $replacewith, $template);
  }
 } 

 // now if not handled by functions, go direct to variables
 $comarray = get_object_vars($comment);
 while(list($key, $value) = each($comarray)) 
 {
  $tempvar = $commentpref . strtoupper($key) .'}';
  $replacewith = decodeit($comment->$key);
  if (!strstr(' ', ' '. $key .' ') && strstr($replacewith, '|||'))
  {
   $replacewith = str_replace('|||', ', ', $replacewith);
   $subitems = explode(', ', $replacewith);
   for ($q=0; $q<sizeof($subitems); $q++)
   {
    $subitem = $item . $subitems[$q];
    $template = str_replace('{COMMENT'. strtoupper($key) . strtoupper($subitem) .'}', $language->yes, $template);
   }
  }
  if ($incomplete) $replacewith = stripall($replacewith);
  else $replacewith = stripcode($replacewith);
  if (!$noparsing) $replacewith = parsemessage($replacewith);
  $template = str_replace($tempvar, $replacewith, $template);
  $yesnooption = $commentpref . strtoupper($key) . 'YESNO}';
  $template = str_replace($yesnooption, yesno($comment->$key), $template);  
 } 

 $template = str_replace('{DIRURL}', $settings->dirurl, $template); 
 if ($istoplist && strstr($template, '{MEMBER'))
 {
  if ($comment->ownerid > 0) $mem = new member('id', $comment->ownerid);
  else $mem = new member('blank', 'blank');
  $template = memberreplacements($template, $mem);
 }
 // select appropriate items on custom fields
 $fields = explode(',', $settings->commentfields);
 $num = sizeof($fields);
 for ($x=0; $x<$num; $x++)
 { 
  if (strstr($comment->$fields[$x], '|||'))
  {
   $fieldgroup = explode('|||', $comment->$fields[$x]);
   for ($y=0; $y<sizeof($fieldgroup); $y++) 
   {
    $template = selectit($template, $fields[$x], $fieldgroup[$y]);
   }
  }
 }

 return $template;
}  


function memberreplacements($template, $amember)
{
 global $language, $isadmin, $templatesdir, $settings, $thismember, $noparsing, $admindir, $inadmindir, $commentspage, $memberpref;
 // handle all the functions
 $memfuncarray = get_class_methods($amember);
 foreach($memfuncarray as $key) 
 {
  $tempvar = $memberpref . strtoupper($key);
  if (strstr($template, $tempvar .'}'))
  {
   $func = $amember->$key();
   $replacewith = decodeit($func);
   $template = str_replace($tempvar .'}', $replacewith, $template);
  }
  if (strstr($template, $tempvar .'['))
  {
   $tempvar .= '[';
   $param = limitedtemplateextract($template, $tempvar, ']');
   $func = $amember->$key($param);
   $replacewith = decodeit($func);
   $template = str_replace($tempvar . $param .']}', $replacewith, $template);
  }
 }  
 // now if not handled by functions, go direct to variables
 $memarray = get_object_vars($amember);
 while(list($key, $value) = each($memarray)) 
 {
  if (strstr($key, 'group') && ($key != 'usergroup') && ($key != 'mgroup') && ($key != 'group')) $tempvar = '{MEMBERGROUP'. strtoupper($key) .'}';
  $tempvar = $memberpref . strtoupper($key) .'}';
  $replacewith = decodeit($amember->$key);
  if (!strstr('  ', ' '. $key .' ') && strstr($replacewith, '|||'))
  {
   $replacewith = str_replace('|||', ', ', $replacewith);
   $subitems = explode(', ', $replacewith);
   for ($q=0; $q<sizeof($subitems); $q++)
   { // for custom array fields
    $subitem = $item . $subitems[$q];
    $template = str_replace('{MEMBER'. strtoupper($key) . strtoupper($subitem) .'}', $language->yes, $template);
   }
  }
  if ($incomplete) $replacewith = stripall($replacewith);
  else $replacewith = stripcode($replacewith);
  if (!$noparsing) $replacewith = parsemessage($replacewith);
  $template = str_replace($tempvar, $replacewith, $template);
  $yesnooption = $memberpref . strtoupper($key) . 'YESNO}';
  $template = str_replace($yesnooption, yesno($amember->$key), $template);
 }  

 $template = str_replace('{LANGUAGEOPTIONS}', langoptions($amem->language), $template);
 $template = str_replace('{TEMPLATEOPTIONS}', tempoptions($amem->tempoptions), $template);
 $template = str_replace('{DEFAULTLANG}', $settings->defaultlang, $template);
 $template = str_replace('{ALLOWEMAILOPTIONS}', yesno($amem->allowemail), $template);
 $ugfields = explode(',', $settings->usergroupfields);
 $num = sizeof($ugfields);
 for ($count=0; $count<$num; $count++) 
 {
  $tempvar = $memberpref .'GROUP'. strtoupper($ugfields[$count]) .'}';
  $item = 'group' . $ugfields[$count];
  $template = str_replace($tempvar, $amember->$item, $template);
 }
 
 // select appropriate items on custom fields
 $fields = explode(',', $settings->memberfields);
 $num = sizeof($fields);
 for ($x=0; $x<$num; $x++)
 { 
  if (strstr($amem->$fields[$x], '|||'))
  {
   $fieldgroup = explode('|||', $amem->$fields[$x]);
   for ($y=0; $y<sizeof($fieldgroup); $y++) 
   {
    $template = selectit($template, $fields[$x], $fieldgroup[$y]);
   }
  }
 }
 return $template;
}

function uploadavatar($varname, $todo)
{
 global $settings, $_FILES;
 // contains full path to uploaded file in temprary storage
 $upload_temp = $_FILES[$varname]['tmp_name'];
 // get file name portion of source file
 $upload_file = $_FILES[$varname]['name'];
 // build target filename
 $ext = extension($_FILES[$varname]['name']);
 clearstatcache();
 $filesize = filesize($_FILES[$varname]['tmp_name']);
 if ($todo == 'randomize') $upload_file = md5(microtime()) .'.'. $ext;
 $target_file = $settings->uploadpath . $upload_file;
 $uploaded = false;
 $allowed = false;
 $types = explode(',', strtolower($settings->avatartypes));
 $num = sizeof($types);
 for ($x=0; $x<$num; $x++) if (strtolower($types[$x]) == strtolower($ext)) $allowed = true;
 $imagearray = getimagesize($upload_temp);
 if ($imagearray[0] > $settings->avatarwidth) $allowed = false;
 if ($imagearray[1] > $settings->avatarheight) $allowed = false;
 if (($filesize < $settings->avatarsize) && ($allowed))
 { 
  // try to copy file to real upload directory
  if (move_uploaded_file($upload_temp, $target_file))
  {
   $uploaded = $upload_file;
  }
 }
 return $uploaded; // returns file name if it worked, or false if failed
}

function stylesheets($selected)
{ // list custom templates as options, by reading the custom directory
 global $templatesdir, $inadmindir, $settings, $foredit;
 if ($selected == '') $selected = $settings->stylesheet;
 if ($inadmindir) $path = '../';
 $path .= 'templates/styles/';
 $files = getfiles($path);
 if(is_array($files))
 {
  sort($files);
  foreach($files as $file)
  {
   $base = str_replace('.'. extension($file), '', $file);
   if (strstr($templatesdir, '/')) $prefixit = '../styles/';
   else $prefixit = 'styles/';
   if ($foredit)
   {
    if ($prefixit . $base == $selected) $options .= '<option value="'. $prefixit . $base .'" selected>'. $base .'</option>';
	else $options .= '<option value="'. $prefixit . $base .'">'. $base .'</option>';
   }
   else
   {
    if ($prefixit . $base == $selected || $base == $selected) $options .= '<option value="'. $base .'" selected>'. $base .'</option>';
	else $options .= '<option value="'. $base .'">'. $base .'</option>';	
   }
  }
 }
 return $options;
}

function chmodtemplates()
{
 @chmod("searchlog.txt", 0666); // chmod search log file
 @chmod("languages", 0777); // chmod languages directory
 @chmod("admin", 0777); // chmod languages directory 
 $indirs[0] = 'templates/styles';
 $indirs[1] = 'templates/default';
 $indirs[2] = 'templates/default/admin';
 $indirs[3] = 'templates/multilingual';
 $indirs[4] = 'templates/multilingual/admin';
 for ($x=0; $x<sizeof($indirs); $x++)
 {
  $files = '';
  $inputdir = $indirs[$x];
  $files = getfiles($inputdir);
  if(is_array($files))
  {
   sort($files);
   foreach($files as $file)
   {
    $fullfile = $indirs[$x] .'/'. $file;
    if (extension($file != '')) @chmod($fullfile, 0666);
	else @chmod($fullfile, 0777);
   }
  }
 }
 return true;
}

function skiptocat($selected)
{
 $options = '<option value="">None</option>';
 $options .= catselector($selected, 'skiptocat');
 if (!strstr($options, 'selected'))
 {
  $options = str_replace('value='. $selected .'>', 'value="'. $selected .'" selected>', $options);
 }
 return $options;
}

function calctypeorder()
{
 // recalculates order of types in displaylinks template and updates database appropriately
 global $settings, $db;
 $search = new template("displaylinks.tpl");
 $types = explode(',', $settings->linktypes);
 foreach ($types as $thistype)
 {
  $tofind = '<!-- BEGIN '. strtoupper($thistype) .' LINKS -->';
  $thistypeorder = strpos($search->text, $tofind);
  $db->update("linkstable", "typeorder", $thistypeorder, "type='$thistype'");
 }
 return true;
}

function calccommenttypeorder()
{
 // recalculates order of types in viewcomments template and updates database appropriately
 global $settings, $db;
 $search = new template("viewcomments.tpl");
 $types = explode(',', $settings->commenttypes);
 foreach ($types as $thistype)
 {
  $tofind = '<!-- BEGIN '. strtoupper($thistype) .' -->';
  $thistypeorder = strpos($search->text, $tofind);
  $db->update("commentstable", "typeorder", $thistypeorder, "type='$thistype'");
 }
 return true;
}

?>