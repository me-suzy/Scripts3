<?php
// Apache mod_rewrite url shortening
$doc = str_replace('"index.php"', '"'. $settings->dirurl .'"', $doc);

$pattern = "href=\"(.*?).php?(.*?)>";   
$pattern = '/'. $pattern .'/i';
preg_match_all($pattern, $doc, $urls);
for ($i=0; $i<sizeof($urls[0]); $i++)
{
 if (substr_count($urls[0][$i], 'href') > 1) 
 {
  $splitit = explode('href', $urls[0][$i]);
  $urls[0][$i] = $splitit[2];
  $qs = explode('catid=', $urls[0][$i]);
  $qs = str_replace('>', '', $qs[1]);
  $doc = str_replace($urls[0][$i], '="'. $settings->dirurl .'/'. $qs .'/">', $doc);
 }
 if (!strstr($urls[0][$i], 'http://'))
 {
  if (strstr(str_replace('&amp;', '&', $urls[2][$i]), 'action=displaycat&catid=') && !strstr($urls[2][$i], 'todo'))
  { 
   $getid = explode('&catid=', str_replace('&amp;', '&', $urls[2][$i]));
   if (strstr($urls[0][$i], '[IFADMIN../]') || strstr($urls[0][$i], '../')) $altered = '[IFADMIN../]'; else $altered = '';  
   $altered .= $getid[1];
   $doc = str_replace($urls[1][$i] .'.php'. $urls[2][$i], $altered, $doc);
  }
  else if (strstr($urls[1][$i], 'edit.php?'))
  {
   // do nothing
  }
  else if (strstr($urls[1][$i], 'comments'))
  {
   $getid = explode('?id=', $urls[2][$i]);
   if (is_numeric(str_replace('"', '', $getid[1])))
   {
    $altered = 'thread/'. $getid[1];
    $doc = str_replace($urls[1][$i] .'.php'. $urls[2][$i], $altered, $doc);
   }
  }
  else if (strstr($urls[1][$i] .'.php', 'link.php'))
  {
   if (strstr($urls[2][$i], 'action'))
   {
    $getaction = explode('?action=', $urls[2][$i]);
    $getid = explode('&id=', str_replace('&amp;', '&', $getaction[1]));
    $action = $getid[0];
    $id = $getid[1];
    if (strstr($urls[1][$i], '[IFADMIN../]')) $altered = '[IFADMIN../]'; else $altered = '';
    $altered .= 'links/'. $action .'/'. $getid[1];
    $doc = str_replace($urls[1][$i] .'.php'. $urls[2][$i], $altered, $doc);
   }
   else
   {
    $getid = explode('?id=', $urls[2][$i]);
    $altered = 'links/'. $getid[1];
    $doc = str_replace($urls[1][$i] .'.php'. $urls[2][$i], $altered, $doc);
   }
  }
 }
}

// make all URLs absolute so that we don't get broken images and the like
if (!$nomemberinfo)
{
 $pattern = "href=\"(.*?)\"";   
 $pattern = '/'. $pattern .'/i';
 preg_match_all($pattern, $doc, $urls);
 for ($i=0; $i<sizeof($urls[0]); $i++)
 {
  if ( (substr_count('.', $urls[1][$i]) < 2) && (!strstr($urls[1][$i], 'http')) && (!strstr($urls[1][$i], 'javascript')))
  {
   $urls[1][$i] = str_replace('[IFADMIN../]', '', $urls[1][$i]);
   $urls[1][$i] = str_replace('[IFADMINadmin/]', '', $urls[1][$i]);
   $altered = "href=\"". $settings->dirurl .'/';
   if (($inadmindir && !strstr($urls[0][$i], '[IFADMIN../]')) || (!$inadmindir && strstr($urls[0][$i], '[IFADMINadmin/]'))) $altered .= $settings->admindir .'/'. $urls[1][$i];
   else $altered .= $urls[1][$i];
   $altered .= '"';
   $doc = str_replace($urls[0][$i], $altered, $doc);
  }
 }
 $pattern = "action=\"(.*?)\"";   
 $pattern = '/'. $pattern .'/i';
 preg_match_all($pattern, $doc, $urls);
 for ($i=0; $i<sizeof($urls[0]); $i++)
 {
  if ( (substr_count('.', $urls[1][$i]) < 2) && (!strstr($urls[1][$i], 'http')) && (!strstr($urls[1][$i], 'javascript')))
  {
   $altered = "action=\"". $settings->dirurl .'/';
   if ($inadmindir) $altered .= $settings->admindir .'/';
   $altered .= $urls[1][$i];
   $altered .= '"';
   $doc = str_replace($urls[0][$i], $altered, $doc);
  }
 }
 $pattern = "src=\"(.*?)\"";   
 $pattern = '/'. $pattern .'/i';
 preg_match_all($pattern, $doc, $urls);
 for ($i=0; $i<sizeof($urls[0]); $i++)
 {
  if ( (@substr_count('.', $urls[1][$i]) < 2) && (!strstr($urls[1][$i], 'http')) && (!strstr($urls[1][$i], 'javascript')))
  {
   $altered = "src=\"". $settings->dirurl .'/';
   if ($inadmindir) $altered .= $settings->admindir .'/';
   $altered .= $urls[1][$i];
   $altered .= '"';
   $doc = str_replace($urls[0][$i], $altered, $doc);
  }
 }
 $pattern = "src=(.*?)>";   
 $pattern = '/'. $pattern .'/i';
 preg_match_all($pattern, $doc, $urls);
 for ($i=0; $i<sizeof($urls[0]); $i++)
 {
  if ( (@substr_count('.', $urls[1][$i]) < 2) && (!strstr($urls[1][$i], 'http')) && (!strstr($urls[1][$i], 'javascript')))
  {
   $altered = "src=". $settings->dirurl .'/';
   if ($inadmindir) $altered .= $settings->admindir .'/';
   $altered .= $urls[1][$i];
   $altered .= '>';
   $doc = str_replace($urls[0][$i], $altered, $doc);
  }
 }
}
?>