<?php
function dowsncodes($message)
{
global $settings, $inadmindir;
// Parse WSN Codes into HTML
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
 $format = $thiscode[5];
 if (strstr($codelist[$p], 'PARAM')) $format = 'param';
 if (strstr($codelist[$p], '['))
 {
  if ($original != '')
  {
   if ($format == 'param')
   {
    $pattern = "{ORIGINAL}(.*?){ORIGINALCLOSE}";   
    $pattern = str_replace('{ORIGINAL}', $original, $pattern);
    if (strstr($pattern, '{PARAM}')) $paramtype = true;
    $pattern = str_replace('{PARAM}', '(.*?)', $pattern);
    $pattern = str_replace('{ORIGINALCLOSE}', $originalclose, $pattern);
    $pattern = str_replace('[', '\[', $pattern);
    $pattern = str_replace(']', '\]', $pattern);
    $pattern = str_replace('/', '\/', $pattern);
    $pattern = '/'. $pattern .'/i';	
    $changeto = "{REPLACEMENT}$2{REPLACEMENTCLOSE}";	
    $changeto = str_replace('{REPLACEMENT}', $replacement, $changeto);
    $changeto = str_replace('{REPLACEMENTCLOSE}', $replacementclose, $changeto);	
    if ($paramtype) 
    {
     $changeto = str_replace('{PARAM}', '$1', $changeto);
     $changeto = str_replace('{CONTENT}', '$2', $changeto);		
    }
    else
     $changeto = str_replace('{CONTENT}', '$1', $changeto);		
    $message = preg_replace($pattern, $changeto, $message);
   }
   else
   { 
    $pattern = "{ORIGINAL}(.*?){ORIGINALCLOSE}";   
    $pattern = str_replace('{ORIGINAL}', $original, $pattern);
    if (strstr($pattern, '{PARAM}')) $paramtype = true; else $paramtype = false;	
    $pattern = str_replace('{PARAM}', '(.*?)', $pattern);	
    $pattern = str_replace('{ORIGINALCLOSE}', $originalclose, $pattern);
    $pattern = str_replace('[', '\[', $pattern);
    $pattern = str_replace(']', '\]', $pattern);
    $pattern = str_replace('/', '\/', $pattern);
    $pattern = '/'. $pattern .'/i';
    if ($paramtype) 
    {
     $changeto = str_replace('{PARAM}', '$1', $changeto);
     $changeto = str_replace('{CONTENT}', '$2', $changeto);		
     $changeto = "{REPLACEMENT}$2{REPLACEMENTCLOSE}";		 
    }
    else
     $changeto = "{REPLACEMENT}$1{REPLACEMENTCLOSE}";	
    $changeto = str_replace('{REPLACEMENT}', $replacement, $changeto);
    $changeto = str_replace('{REPLACEMENTCLOSE}', $replacementclose, $changeto);	
    $changeto = str_replace('{CONTENT}', '$1', $changeto);
    $message = preg_replace($pattern, $changeto, $message);   
   }
  }
 }
}
return $message;
}
?>