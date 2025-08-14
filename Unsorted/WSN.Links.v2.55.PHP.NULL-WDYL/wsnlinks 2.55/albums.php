<?php
require 'start.php'; // header stuff
if ($mixtypes) $settings->mixrecip = $mixtypes;

if ($id != '')
{
 $amem = new member('id', $id);
 $catid = $amem->albumid; 
}
if ($name != '')
{
 if (!$newname) $newname = 'name';
 $getit = $db->select('albumid', 'memberstable', "$newname='$name'", '', '');
 $catid = $db->rowitem($getit); 
}

if ($catid == '')
{
 if ($scriptname == 'wsngallery') $catid = 1; // default to album list for gallery
 else $catid = $thismember->albumid; // and default to viewer's album for links and others
}
if ($action == 'createalbum')
{
 if ((!($thismember->albumid > 0)) && ($thismember->usergroup > 1))
 {
  $newcat = new category('blank', 'blank');
  $newcat->name = encodeit(memberreplacements($language->newalbum, $thismember));
  $newcat->id = '';
  if ($scriptname == 'wsngallery') $newcat->parent = 1;
  else $newcat->parent = 0;
  $newcat->hide = 1;
  if ($newcat->moderators == '') $newcat->moderators = encodeit($thismember->name);
  else $newcat->moderators .= ','. encodeit($thismember->name);
  $newcat->validated = 1;
  $newcat->isalbum = 1;
  $newcat->custom = 'albums.tpl';
  $newcat->add();
  $getit = $db->select('id', 'categoriestable', "name='". encodeit(memberreplacements($language->newalbum, $thismember)) ."'", '', '');
  $theiralbum = $db->rowitem($getit);
  $thismember->albumid = $theiralbum;
  $thismember->update('albumid');
  $action = 'displaycat';
  $catid = $thismember->albumid;
 }
}

if ($add > 0 && $catid > 0) 
{
 $oldone = new onelink('id', $add);
 $thelink = new onelink('blank', 'blank');
 $thelink->catid = $catid;
 $thelink->type = $oldone->type;
 $thelink->alias = $add;
 $thelink->validated = 1;
 $aliasing = true; // avoid sending notification email
 $check = $db->select('id', 'linkstable', 'alias='. $add .' AND catid='. $catid, '', '');
 $check = $db->rowitem($check);
 if (!($check > 0)) $thelink->add(); // don't alias something into category it already has one in
 $aliasing = false;
}

$action = 'displaycat';


// reconstruct the current url and put it in a cookie, so we can know where to come back to if we go off somewhere soon... but only save it for 15 minutes

if ($settings->dirurl != '') $currenturl = $settings->dirurl .'/index.php';
else $currenturl =  'http://' . $HTTP_SERVER_VARS['SERVER_NAME'] . $HTTP_SERVER_VARS['PHP_SELF'];
$currenturl .= '?'. $HTTP_SERVER_VARS['QUERY_STRING'];

if (($action != 'logout') && ($TID != 'blank') && ($action != 'login'))
{
if (($currenturl != '')&&($HTTP_SERVER_VARS['PHP_SELF'] != '')&&($currenturl != '/?')&&($currenturl != 'http://?')&&($currenturl != 'http:///?'))
setcookie("returnto", "$currenturl",  900);
else
setcookie("returnto", "index.php",  900);
}

if ($todo == 'subscribe' && $thismember->id > 0)
{
 $thiscategory = new category('id', $catid);
 if (!$template) $template = new template("redirect.tpl");
 if (strstr($thiscategory->subscribers, '|||'. $thismember->id .'|||'))
  $template->replace('{MESSAGE}', $language->subscribecat_unsubscribe);
 else
  $template->replace('{MESSAGE}', $language->subscribecat_subscribe);
 if ($aftersubscribecat == '') $aftersubscribecat = $returnto;
 $template->replace('{DESTINATION}', $aftersubscribecat);
 $thiscategory->subscribe($thismember->id);
}
else if ($todo == 'ordercats')
{
 $ordercats = "ORDER BY $catfield $catascdesc";
 if ($ordercats != 'ORDER BY  ') setcookie('ordercats', "$ordercats", 10000000);
}
else if ($todo == 'orderlinks')
{
 $orderlinks = "ORDER BY $orderlinks $ascdesc";
 if ($settings->orderlinks2 != '') $orderlinks .= str_replace('ORDER BY ', ',', $settings->orderlinks2);
 if ($orderlinks != 'ORDER BY  ') setcookie('orderlinks', "$orderlinks", 10000000);
 if ($perpage > 0) setcookie('perpage', "$perpage", 10000000);
}

if ($linkcolumns < 1) $linkcolumns = $settings->linkcols;
$catcolumns = $settings->catcols;
$subcatcolumns = $settings->subcatcols;
if (($ascdesc != '') && (!stristr($orderlinks, 'ORDER'))) { $orderlinks = 'ORDER BY '. $orderlinks .' '. $ascdesc; if ($settings->orderlinks2 != '') $orderlinks .= str_replace('ORDER BY ', ',', $settings->orderlinks2); }
if ($orderlinks == '' || substr_count($orderlinks, 'ORDER') > 1)
{ // let user override value
 $orderlinks = $settings->orderlinks;
 if ($settings->orderlinks2 != '') $orderlinks .= str_replace('ORDER BY ', ',', $settings->orderlinks2); 
}
if ($ordercats == '')
{ // let user override value
 $ordercats = $settings->ordercats;
}
if ($perpage == '')
{ // let user override value
 $perpage = $settings->perpage;
}
$realorder = $orderlinks;
if ($settings->orderlinks2 != '') $realorder .= str_replace('ORDER BY ', ',', $settings->orderlinks2); 

// in case someone is being stupid
if ($linkcolumns < 1) $linkcolumns = 1;
if ($catcolumns < 1) $catcolumns = 1;
if ($subcatcolumns < 1) $subcatcolumns = 1;

// deduce needed widths for table cells
if ($settings->fixedwidth != '') $linkwidth = 'width="'. $settings->fixedwidth .'"'; 
$linkwidth = 'width="'.(int)(99/$linkcolumns) .'%"'; 
$catwidth = 'width="'.(int)(99/$catcolumns) .'%"';
$subcatwidth = 'width="'.(int)(99/$subcatcolumns) .'%"';

 // display the album
 if ($page == '') $page = 1;

 if ($thismember->usergroup == 1 && $scriptname != 'wsngallery') header("Location: index.php?action=userlogin");

 $thiscategory = new category('id', $catid); // create proper category object
 if ($thiscategory->mixtypes != '') $settings->mixrecip = $thiscategory->mixtypes;
  
 if ($thiscategory->custom != '')
 {
  $custom = $templatesdir .'/'. $thiscategory->custom;
  if (!$template) $template = new template($custom);
 }
 if (!$template) 
 {
  if ($scriptname == 'wsngallery' && $catid == 1) $template = new template('albumlist.tpl');
  else $template = new template('albums.tpl');
 }
 
 $area = $language->title_divider . $thiscategory->name; // supply area for template
 $template->replace('{NAVIGATION}', shownav($thiscategory)); // do navigation
 
 // do related categories
 $relatedtemplate = templateextract($template->text, '<!-- BEGIN RELATED -->', '<!-- END RELATED -->');
 $template->replace($relatedtemplate, '{RELATEDTEMPLATE}');
 $relatedarray = explode('|||', $thiscategory->related);
 if (($thiscategory->related == '') || ($thiscategory->related == ' ')) $num = 0;
 else $num = sizeof($relatedarray);
 for ($x=0; $x<$num; $x++)
 {
  $rcat = getmaprow($relatedarray[$x]);
  $rcompletedtemplate .= subreplace($row, $relatedtemplate);
 }  
 $template->replace('{RELATEDTEMPLATE}', $rcompletedtemplate);

 // now do subcategories
 // now do subcategories
 $numsubcats = $thiscategory->numsubcatsall();
 $thiscategory->numsub = $numsubcats;
 $subcatsdata = $thiscategory->getsubcatsall();
 
 $template_subcats = templateextract($template->text, '<!-- BEGIN SUBCATEGORIES -->', '<!-- END SUBCATEGORIES -->');
 $template->replace($template_subcats, '{SUBCATEGORIES}'); // replace with marker
 if ($numsubcats == 0)
 {
  $template_subcats = '';
 }
 else
 {
  $columncount = 0;
  for ($count=0; $count<$numsubcats; $count++)
  { // display each subcategory
   $thissub = new category('row', $db->row($subcatsdata));
   if ($columncount == 0)
   {
    $subcatsbody .= '<tr>';
   }
   else if ($columncount == $subcatcolumns)
   {
    $subcatsbody .= '</tr>';
    $columncount = 0;
   }
   $columncount++; 
   $subcatsbody .= categoryreplacements($template_subcats, $thissub);
  }
  $subcatsbody .= '</tr>';
 }


 // set custom subcat column width
 $width = (int)(100/$subcatcolumns);
 if ($numsubcats < $subcatcolumns)
    $subcatsbody = str_replace('{SUBCATWIDTH}', '', $subcatsbody);
 else
   $subcatsbody = str_replace('{SUBCATWIDTH}', $subcatwidth, $subcatsbody);

 //find total number of validated links for this category, then total recip and total non-recip
 $total = $thiscategory->linkshere();
 $typetotals = explode(',', $settings->linktypes);
 $n = sizeof($typetotals);
 for ($x=0; $x<$n; $x++)
 {
  $var = $typetotals[$x] . 'total';
  $$var = $thiscategory->typetotal($typetotals[$x]);
  $template->replace('{'. strtoupper($var).'}', $$var);
 }

 if ($total == 0)
 {
  $template->replace('{LINKSBODY}', ''); 
  $template->replace('{TOTALINCATEGORY}', '0');
 }

 $start = ($page * $perpage) - $perpage; // have to adjust for starting the numbering at 1
 $end = $start + $perpage - 1;

 // get all the templates by cycling through the possibilities
 // link types will be dropdown option on editing link
 // can add/remove through admin panel
 $thetypes = strtoupper($settings->linktypes);
 $listtypes = explode(',', $thetypes);
 $num = sizeof($listtypes);
 for ($count=0; $count<$num; $count++)
 {
  $typex = $listtypes[$count];
  $varname = strtolower($typex) . 'linksbodybase';
  if (strstr($template->text, "<!-- BEGIN $typex $preftype -->")) $$varname = templateextract($template->text, "<!-- BEGIN $typex $preftype -->", "<!-- END $typex $preftype -->");
  if ($$varname == '') { $first = strtolower($listtypes[0]) . 'linksbodybase'; $$varname = $$first; }
  $template->replace($$varname, '{'. $typex .'LINKSBODY}'); // replace with marker
 }

  $begin = $start;
  $end = $start + $perpage;
  $toshow = $perpage;
  if ( ($total == 0) || ($start >= $end) ) { $linksbodybase = ''; }
  else
  { 
   if ($settings->mixrecip == 'yes')
    $reglinksdata = $thiscategory->linkdata();
   else
    $reglinksdata = $thiscategory->linkdatabytype();
   $columncount = 0;
   for ($next=$start; ( ($next<$end) && ($next<$total) ); $next++)
   {
    // get next link
    $row = $db->row($reglinksdata);
    $thelink = new onelink('row', $row);

    $typex = strtoupper($thelink->type);
	if ($columncount == 0)
	{
	 $counter = strtolower($typex) . 'count';
     $templatename = strtolower($typex) . 'linksbodybase';
	 $$counter++;
	 $links = '<tr> '. $$templatename;
	}
    else if ($columncount == $linkcolumns)
    {
	 $counter = strtolower($typex) . 'count';
     $templatename = strtolower($typex) . 'linksbodybase';
	 $$counter++;
	 $links = '</tr> '. $$templatename;
     $columncount = 0;
    }
    else
    {
	 $counter = strtolower($typex) . 'count';
     $templatename = strtolower($typex) . 'linksbodybase';
	 $$counter++;
	 $links = $$templatename;
    }
   $columncount++;
   // do replacements for single link
   if ($settings->mixrecip == 'yes')
   {
    if (($thelink->ownerid > 0) && (strstr($links, '{MEMBER'))) 
      $themem = new member('id', $thelink->ownerid);
    else
      $themem = new member('blank', 'blank');
    $template_linksbody .= memberreplacements(linkreplacements($links, $thelink), $themem);
   }
   else
   {
    $thevar = strtolower($thelink->type) . 'linksbody';
    if (($thelink->ownerid > 0) && (strstr($links, '{MEMBER'))) 
      $themem = new member('id', $thelink->ownerid);
    else
      $themem = new member('blank', 'blank');
    $$thevar .= memberreplacements(linkreplacements($links, $thelink), $themem);
   }
   $thetotal++;
  }
  // final </tr>
  $template_linksbody .= '</tr>';
  if ($thetotal<$linkcolumns)
  {
    if ($settings->mixrecip == 'no') $$var = str_replace('{LINKWIDTH}', '', $$var);
    $template_linksbody = str_replace($pref .'WIDTH}', '', $template_linksbody);
  }
  else
  {
    if ($settings->mixrecip == 'no') $$var = str_replace('{LINKWIDTH}', $linkwidth, $$var);
    $template_linksbody = str_replace($pref .'WIDTH}', $linkwidth, $template_linksbody);
  }
  }
  
 $num = sizeof($listtypes);
 for ($count=0; $count<$num; $count++)
 {
  $typex = strtoupper($listtypes[$count]);
  if ($settings->mixrecip == 'yes')
  {
   if ($count == 0) $template->replace('{'. $typex .'LINKSBODY}', $template_linksbody);
   else $template->replace('{'. $typex .'LINKSBODY}', '');
  }
  else
  {
   $thevar = strtolower($typex) . 'linksbody';
   if (strstr($template->text, '{'. $typex .'LINKSBODY}')) $template->replace('{'. $typex . 'LINKSBODY}', $$thevar);   
   else $template->replace('<!-- END '. strtoupper($listtypes[0]) .' LINKS -->', $$thevar);   
  }
 }  

 
 if ($perpage < 1) $perpage = 1;
 $numpages = ceil($total / $perpage);
 if ($settings->apacherewrite == 'yes')
 {
  for ($count=1; $count < $page; $count++)
    $previouspages .= $language->pageselection_left. '<a href="'. $catid .'/'. $count.'">'. $count .'</a>'. $language->pageselection_right;
  for ($count=$page; $count<$numpages; $count++)
  {
    $next = $count+1;
    $nextpages .= $language->pageselection_left .'<a href="'. $catid .'/'. $next .'">'. $next .'</a>'. $language->pageselection_right;
  }
 }
 else
 {
  for ($count=1; $count < $page; $count++)
    $previouspages .= $language->pageselection_left. '<a href="index.php?action=displaycat&amp;catid='. $catid .'&amp;page='. $count .'&amp;orderlinks='. $sendorder .'&amp;perpage='. $perpage .'">'. $count .'</a>'. $language->pageselection_right;
  for ($count=$page; $count<$numpages; $count++)
  {
    $next = $count+1;
    $nextpages .= $language->pageselection_left .'<a href="index.php?action=displaycat&amp;catid='. $catid .'&amp;page='. $next .'&amp;orderlinks='. $sendorder .'&amp;perpage='. $perpage .'">'. $next .'</a>'. $language->pageselection_right;
  }
 }
 $template->replace('{PREVIOUS}', $previouspages);
 if ($previouspages != '' || $nextpages != '') $template->replace('{MULTIPAGE}', '1');
 else $template->replace('{MULTIPAGE}', '0');
 $template->replace('{NEXT}', $nextpages);
 $template->replace('{CURRENTPAGE}', $page);
 $template->replace('{PAGE}', $page);
 $template->replace('{REORDER}', $reorder);
 $template->replace('{PERPAGE}', $perpage);
 $template->replace('{TOTALINCATEGORY}', $total); // total shown in category
 $template->replace('{NUMBEROFSUBCATS}', $numsubcats); // total shown in category
 $template->replace('{SUBCATEGORIES}', $subcatsbody); 
 $template->text = categoryreplacements($template->text, $thiscategory);

 if ($thiscategory->permissions != '')
 {
  $permissions = '|||'. $thiscategory->permissions .'|||';
  if (strstr($permissions, '|||'. $thismember->usergroup .'|||')) $template = new template("noaccess.txt");
 }
 
// properly select items in cat and links ordering selectors
$template->text = stripslashes($template->text);

$ordering = explode(' ', $ordercats);
// 0: ORDER 1: BY 2: field 3: ASC/DESC
$ourorder = '<option value="'. $ordering[2] .'">';
$selectit = '<option value="'. $ordering[2] .'" selected>';
$template->replace($ourorder, $selectit);
$startord = strpos($template->text, '<select name="catascdesc">');
if ($startord)
{
 $ourorder = substr($template->text, $startord, 100);
 $selectit = str_replace('"'. strtolower($ordering[3]) .'"', '"'. strtolower($ordering[3]) .'" selected', $ourorder);
 $template->replace($ourorder, $selectit);
}

$ordering = explode(' ', $realorder);
// 0: order 1: by 2: field 3:ascdesc
$ourorder = '<option value="'. $ordering[2] .'">';
$selectit = '<option value="'. $ordering[2] .'" selected>';
$template->replace($ourorder, $selectit);
$startord = strpos($template->text, '<select name="ascdesc">');
if ($startord)
{
 $ourorder = substr($template->text, $startord, 100);
 $selectit = str_replace('"'. strtolower($ordering[3]) .'"', '"'. strtolower($ordering[3]) .'" selected', $ourorder);
 $template->replace($ourorder, $selectit);
}
$startord = strpos($template->text, '<select name="perpage">');
if ($startord)
{ 
 $ourorder = substr($template->text, $startord, 100);
 $selectit = str_replace('"'. $perpage .'"', '"'. $perpage .'" selected', $ourorder);
 $template->replace($ourorder, $selectit);
}

require 'end.php';
?>