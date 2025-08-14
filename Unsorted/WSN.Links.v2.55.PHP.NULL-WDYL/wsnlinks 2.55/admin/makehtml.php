<?php
// HTML version generator for WSN: creates html archive
require 'adminstart.php';
$settings->totalcats = $db->numrows($db->select('id', 'categoriestable', 'validated=1 AND hide=0', '', ''));
if ($filled)
{
 $template = new template("blank", "blank");
 if (!$start) $start = 0;
 if (!$perpage) $perpage = 5; 
 if ($type == 'gzip')
 {
  // create gziped html archive of previously generated pages and send download to browser
 }
 else
 {
  // create regular html pages in specified directory
  if (!file_exists($directory)) die("The directory specified does not exist.");
  // do main page
  if ($start == 0)
  {
   $details = '?dummy=dummy';
   if ($customindex != '') $details .= '&custom=yes&TID='. $customindex;
   if ($customhead) $details .= '&customheader='. $customhead;
   if ($customfoot) $details .= '&customfooter='. $customfoot;	
   $getpage = geturl($settings->dirurl .'/index.php'. $details);  
   $getpage = transform($getpage);
   filewrite($directory .'/index.htm', $getpage);
   $template->text .= "Writing <a href=index.htm>index.htm</a><br>";
  }
  // do category pages
  if ($settings->totalcats >= $start)
  {
   $cats = $db->select('id', 'categoriestable', 'validated=1 and hide=0', 'ORDER BY id ASC', "LIMIT $start,$perpage");
   $num = $db->numrows($cats);
   for ($x=0; $x<$num; $x++)
   {
    $details = '';
    $id = $db->rowitem($cats);
    if ($customcat) $details = '&custom=yes&TID='. $customcat;
	if ($customhead) $details .= '&customheader='. $customhead;
	if ($customfoot) $details .= '&customfooter='. $customfoot;	
    if ($headerfooter == 'no') $details .= '&headerfooter=no';
    $getpage = geturl($settings->dirurl .'/index.php?action=displaycat&perpage=500&catid='. $id . $details);
    // transform appropriate urls on the page to our html ones
    $getpage = transform($getpage);
    filewrite($directory .'/cat'. $id .'.htm', $getpage);
    $template->text .= 'Writing <a href='. $directory .'/cat'. $id .'.htm>'. $directory .'/cat'. $id .'.htm</a> <br>';
   }
  }
  if ($settings->totallinks >= $start)
  {
   $links = $db->select('id', 'linkstable', 'validated=1 and hide=0', 'ORDER BY id ASC', "LIMIT $start,$perpage");
   $num = $db->numrows($links);
   for ($x=0; $x<$num; $x++)
   {
    $details = '';
    $id = $db->rowitem($links);
    if ($customcat) $details = '&custom=yes&TID='. $customcomments;
	if ($customhead) $details .= '&customheader='. $customhead;
	if ($customfoot) $details .= '&customfooter='. $customfoot;		
    $getpage = geturl($settings->dirurl .'/comments.php?id='. $id . $details);
    if ($customcat) $details = '&custom=yes&TID='. $customitem;
	if ($customhead) $details .= '&customheader='. $customhead;
	if ($customfoot) $details .= '&customfooter='. $customfoot;		
    $getpage2 = geturl($settings->dirurl .'/link.php?action=detail&id='. $id . $details);
    // transform appropriate urls on the page to our html ones
    $getpage = transform($getpage);
    $getpage2 = transform($getpage2);
    filewrite($directory .'/comments'. $id .'.htm', $getpage);
    filewrite($directory .'/detail'. $id .'.htm', $getpage2);
    $template->text .= 'Writing <a href='. $directory .'/comments'. $id .'.htm>'. $directory .'/comments'. $id .'.htm</a> <br>';	
    $template->text .= 'Writing <a href='. $directory .'/detail'. $id .'.htm>'. $directory .'/detail'. $id .'.htm</a> <br>';
   }
  }
  if ($start < $settings->totalcats || $start < $settings->totallinks)
  {
  $start += $perpage;
  $template->text .= '<br><br><a href="makehtml.php?filled=1&type='. $type .'&directory='. $directory .'&start='. $start .'&perpage='. $perpage .'&headerfooter='. $headerfooter .'&customindex='. $customindex .'&customcat='. $customcat .'&customitem='. $customitem .'&customcomments='. $customcomments .'&TID='. $TID .'&customhead='. $customhead .'&customfoot='. $customfoot .'">Continue to next group</a>';
  $template->text .= '<meta http-equiv="refresh" content="5;url=makehtml.php?filled=1&type='. $type .'&directory='. $directory .'&start='. $start .'&perpage='. $perpage .'&headerfooter='. $headerfooter .'&customindex='. $customindex .'&customcat='. $customcat .'&customitem='. $customitem .'&customcomments='. $customcomments .'&TID='. $TID .'&customhead='. $customhead .'&customfoot='. $customfoot .'">';
 }
 else $template->text .= '<p>Done.</p><p align="center">[<a href="index.php">Back to main admin page</a>]</p>';
 }

}
else
{
 $template = new template("../$templatesdir/admin/makehtml.tpl");
}
require 'adminend.php';

function transform($getpage)
{ global $templatesdir;
$doc = $getpage;
$pattern = "href=\"(.*?).php?(.*?)>";   
$pattern = '/'. $pattern .'/i';
preg_match_all($pattern, $doc, $urls);
for ($i=0; $i<sizeof($urls[0]); $i++)
{
 if (strstr($urls[2][$i], 'action=displaycat&catid=') && !strstr($urls[2][$i], 'todo'))
 { 
  $getid = explode('&catid=', $urls[2][$i]);
  $getid[1] = str_replace('class="categoryname"', '', $getid[1]);
  $getid[1] = str_replace('"', '', $getid[1]);
  $getid[1] = str_replace(' ', '', $getid[1]);
  $altered = 'cat'. $getid[1] .'.htm"';
  $doc = str_replace($urls[1][$i] .'.php'. $urls[2][$i], $altered, $doc);
 }
 else if (strstr($urls[1][$i], 'comments'))
 {
  $getid = explode('?id=', $urls[2][$i]);
  $getid[1] = str_replace('"', '', $getid[1]);
  $altered = 'comments'. $getid[1] .'.htm"';
  $doc = str_replace($urls[1][$i] .'.php'. $urls[2][$i], $altered, $doc);
 }
 else if (strstr($urls[1][$i] .'.php', 'link.php'))
 {
  if (strstr($urls[2][$i], 'action'))
  {
   $getaction = explode('?action=', $urls[2][$i]);
   $getid = explode('&id=', $getaction[1]);
   $action = $getid[0];
   $id = str_replace('"', '', $getid[1]);
   $altered = 'detail'. $id .'.htm"';
   $doc = str_replace($urls[1][$i] .'.php'. $urls[2][$i], $altered, $doc);
  }
 }
}
$getpage = $doc;
$getpage = str_replace($templatesdir .'/images', 'images', $getpage);
$getpage = str_replace('templates/styles/', 'styles/', $getpage);
$getpage = str_replace('index.php', 'index.htm', $getpage);
return $getpage;
}
?>