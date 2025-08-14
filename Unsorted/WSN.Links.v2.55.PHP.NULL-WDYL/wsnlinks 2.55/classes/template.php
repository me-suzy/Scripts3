<?php
class template
{
 var $text;
 var $filename;

 function template($file)
 {
  global $settings, $templatesdir, $inadmindir;
  if ($file == 'blank')
  {
   $this->text = '';
   $this->filename = 'none';
  }
  else if (! strstr($file, $templatesdir))
  {
   if ($inadmindir) $path = "../$templatesdir/$file";
   else $path = "$templatesdir/$file";
   if (file_exists($path)) $this->text = fileread($path);
   $this->filename = "$templatesdir/$file";
  }
  else
  {
   $this->text = fileread($file);
   $this->filename = $file;
  }

  $this->original = $this->text; // save original in case needed later... now we modify

  if (strstr($this->text, '[INSERTFILE='))
  { 
   $num = substr_count($this->text, '[INSERTFILE=');
   for ($count = 0; $count < $num; $count++)
   {
    $pos1 = strpos($this->text, '[INSERTFILE=');
    $rest = substr($this->text, $pos1, 50);
    $pos2 = strpos($rest, ']');
    $filename = substr($rest, 0, $pos2);
    $filename = str_replace('[INSERTFILE=', '', $filename);
    if ($filename != '')
    {
     if ($inadmindir) $contents = fileread("../$templatesdir/custom/$filename.tpl");
     else $contents = fileread("$templatesdir/custom/$filename.tpl");
     $this->text = str_replace('[INSERTFILE='. $filename .']', $contents, $this->text);
    }
   }
  }
 }
 
 function replace($var, $value)
 {
  $this->text = str_replace($var, $value, $this->text);
 }
 
 function update()
 {
 $filename = '../'. $this->filename;
 $fd = fopen($filename, 'w');
 $check = fwrite ($fd, $this->text);
 fclose ($fd);
 return $check;
 }

 function showwsncodes()
 {
  global $settings, $language;
  $area = templateextract($this->text, '<!-- BEGIN WSN CODES -->', '<!-- END WSN CODES -->');
  $this->text = str_replace($area, '{WSNCODESAREA}', $this->text);
  $codelist = explode('|||', $settings->wsncodes);
  $num = sizeof($codelist);
  for ($p=0; $p<$num; $p++)
  {
   $thiscode = explode('[,]', $codelist[$p]);
   $original = $thiscode[0];
   $originalclose = $thiscode[1];
   $replacement = $thiscode[2];
   $replacementclose = $thiscode[3];   
   $description = $thiscode[4];
   $type = $thiscode[5];
   $thisline = str_replace('{ORIGINAL}', $original, $area);
   $thisline = str_replace('{ORIGINALCLOSE}', $originalclose, $thisline);
   $thisline = str_replace('{REPLACEMENT}', stripall($replacement), $thisline);
   $thisline = str_replace('{REPLACEMENTCLOSE}', stripall($replacementclose), $thisline);   
   $thisline = str_replace('{DESCRIPTION}', $description, $thisline);
   $thisline = str_replace('{TYPE}', $type, $thisline);   
   if ($type == 'param') $paramstuff = "=". $language->wsncodes_parameter; else $paramstuff = '';
   $thisline = str_replace('{PARAMSTUFF}', $paramstuff, $thisline);
   $thisline = str_replace('{PARAM}', $language->wsncodes_parameter, $thisline);
   $thisline = str_replace('{CONTENT}', $language->codes_yourtext, $thisline);
   $thestuff .= $thisline;
  }
  if (strlen($settings->wsncodes)<3) $thestuff = '';
  $this->text = str_replace('{WSNCODESAREA}', $thestuff, $this->text);
  return true; 
 }

 function showsmilies()
 {
  global $settings, $templatesdir, $inadmindir;
  $area = templateextract($this->text, '<!-- BEGIN SMILIES -->', '<!-- END SMILIES -->');
  $this->text = str_replace($area, '{SMILIESAREA}', $this->text);
  $codelist = explode('|||', $settings->smilies);
  $num = sizeof($codelist);
  for ($p=0; $p<$num; $p++)
  {
   $thiscode = explode(',', $codelist[$p]);
   $original = $thiscode[0];
   $replacement = $thiscode[1];
   $thisline = str_replace('{ORIGINAL}', $original, $area);
   if ($inadmindir) $thisline = str_replace('{REPLACEMENT}', str_replace('{TEMPLATESDIR}', '../'. $templatesdir, decodeit($replacement)), $thisline);
   else $thisline = str_replace('{REPLACEMENT}', str_replace('{TEMPLATESDIR}', $templatesdir, decodeit($replacement)), $thisline);
   $thestuff .= $thisline;
  }
  if (strlen($settings->smilies)<3) $thestuff = '';
  $this->text = str_replace('{SMILIESAREA}', $thestuff, $this->text);
  return true; 
 }

 function showcensor()
 {
  global $settings, $templatesdir;
  $area = templateextract($this->text, '<!-- BEGIN CENSOR -->', '<!-- END CENSOR -->');
  $this->text = str_replace($area, '{CENSORAREA}', $this->text);
  $codelist = explode('|||', $settings->censor);
  $num = sizeof($codelist);
  for ($p=0; $p<$num; $p++)
  {
   $thiscode = explode('[,]', $codelist[$p]);
   $original = $thiscode[0];
   $replacement = $thiscode[1];
   $thisline = str_replace('{ORIGINAL}', $original, $area);
   $thisline = str_replace('{REPLACEMENT}', $replacement, $thisline);
   $thestuff .= $thisline;
  }
  if (strlen($settings->censor)<3) $thestuff = '';
  $this->replace('{CENSORAREA}', $thestuff);
  return true; 
 }
 
 function memberreplacements($mem)
 {
  $this->text = memberreplacements($this->text, $mem);
  return true;
 }

 function linkreplacements($lin)
 {
  $this->text = linkreplacements($this->text, $lin);
  return true;
 }

 function categoryreplacements($cat)
 {
  $this->text = categoryreplacements($this->text, $cat);
  return true;
 }

 function commentreplacements($com)
 {
  $this->text = commentreplacements($this->text, $com);
  return true;
 }

}

?>