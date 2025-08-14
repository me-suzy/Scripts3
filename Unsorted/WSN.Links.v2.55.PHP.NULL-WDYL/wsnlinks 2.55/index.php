<?php
require 'start.php'; // header stuff
if ($mixtypes) $settings->mixrecip = $mixtypes;
if ($settings->skiptocat > 0 && !$catid && !$todo && !$action) { $action = 'displaycat'; $catid = $settings->skiptocat; }

// reconstruct the current url and put it in a cookie, so we can know where to come back to if we go off somewhere soon... but only save it for 15 minutes

if ($settings->dirurl != '') $currenturl = $settings->dirurl .'/index.php';
else $currenturl =  'http://' . $HTTP_SERVER_VARS['SERVER_NAME'] . $HTTP_SERVER_VARS['PHP_SELF'];
$currenturl .= '?'. $HTTP_SERVER_VARS['QUERY_STRING'];

if (($action != 'logout') && ($TID != 'blank') && ($action != 'login') && ($action != 'userlogin') && ($action != 'userlogout'))
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

if (!$HTTP_COOKIE_VARS['testcookie'])
{
 // test that their browser accepts cookies
 makecookie('testcookie', '1', time()+9999999999);
}

include 'timedactions.php';

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
$linkwidth = 'width="'.(int)(99/$linkcolumns) .'%"'; 
$catwidth = 'width="'.(int)(99/$catcolumns) .'%"';
$subcatwidth = 'width="'.(int)(99/$subcatcolumns) .'%"';
if ($settings->fixedwidth != '') $linkwidth = 'width="'. $settings->fixedwidth .'"'; 

if ($checkversion == 'docheck')
{
 echo "WSN version check... this is $fullscripttitle version ". $version;
}
 
if ($action == 'getvotingcode')
{
 $thislink = new onelink('id', $id);
 if (!$template) $template = new template("$templatesdir/showvotecode.tpl");
 $template->replace('{DIRURL}', $settings->dirurl);
 $template->text = linkreplacements($template->text, $thislink);
}

if ($catname != '' && $catid == '')
{  // determine id based on name, if unique
 $name = urldecode($catname);
 $q = $db->select('id', 'categoriestable', "validated=1 AND hide=0 AND name='$name'", '', '');
 $catid = $db->rowitem($q); 
}


if (($action == 'displaycat') && ($catid > 0))
{
 // display all the links in the selected category
 if ($page == '') $page = 1;

 $thiscategory = new category('id', $catid); // create proper category object
 if ($thiscategory->name == '')
 {
  header("Location: index.php");
  die ('<meta http-quiv="refresh" content="0;index.php">');
 }
 
 if ($thiscategory->mixtypes != '') $settings->mixrecip = $thiscategory->mixtypes;
 if ($thiscategory->orderlinks != $settings->orderlinks && $thiscategory->orderlinks != '')
 {
  $orderlinks = $thiscategory->orderlinks;
  if ($settings->orderlinks2 != '') $orderlinks .= str_replace('ORDER BY ', ',', $settings->orderlinks2); 
 }

  
 if ($thiscategory->custom != '')
 {
  $custom = $templatesdir .'/'. $thiscategory->custom;
  if (!$template) $template = new template($custom);
 }
 if (!$template) $template = new template('displaylinks.tpl');
 
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
  $rcompletedtemplate .= subreplace($rcat, $relatedtemplate);
 }  
 $template->replace('{RELATEDTEMPLATE}', $rcompletedtemplate);

 // now do subcategories
 $numsubcats = $thiscategory->numsubcats();
 $subcatsdata = $thiscategory->getsubcats();
 
 $template_subcats = templateextract($template->text, '<!-- BEGIN SUBCATEGORIES -->', '<!-- END SUBCATEGORIES -->');
 $subsub = templateextract($template_subcats, '<!-- BEGIN SUBSUB -->', '<!-- END SUBSUB -->');
 $template->replace($template_subcats, '{SUBCATEGORIES}'); // replace with marker
 if ($numsubcats == 0)
 {
  $template_subcats = '';
  $subcatsbody = '<tr><td></td></tr>'; // for standards Nazis
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
   $subcatsbody .= $template_subcats;
   $subcatsbody = str_replace($subsub, $thissub->subcats($subsub), $subcatsbody);
   $subcatsbody = str_replace('{NUMBER}', $count + 1, $subcatsbody);
   $letter = ord('a') + ($count);
   $subcatsbody = str_replace('{LETTER}', chr($letter), $subcatsbody);
   $subcatsbody = str_replace('{U_LETTER}', strtoupper(chr($letter)), $subcatsbody);
   $subcatsbody = categoryreplacements($subcatsbody, $thissub);
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
 for ($p=$num; $p>-1; $p--)
 { 
  if (strstr($template->text, '<!-- BEGIN '. $listtypes[$p] .' '. $preftype .' -->')) 
  { 
   $test = templateextract($template->text, '<!-- BEGIN '. $listtypes[$p] .' '. $preftype .' -->', '<!-- END '. $listtypes[$p] .' '. $preftype .' -->');
   if ($test != '') { $thedefault = $test; $listdefault = $listtypes[$p]; }
  }
 }
 for ($count=0; $count<$num; $count++)
 {
  $typex = $listtypes[$count];
  $varname = strtolower($typex) . 'linksbodybase';
  if (strstr($template->text, "<!-- BEGIN $typex $preftype -->")) $$varname = templateextract($template->text, "<!-- BEGIN $typex $preftype -->", "<!-- END $typex $preftype -->");
  if ($$varname != '') $template->replace($$varname, '{'. $typex .'LINKSBODY}'); // replace with marker
  else { $$varname = $thedefault; $template->replace('{'. $listdefault .'LINKSBODY}', '{'. $listdefault .'LINKSBODY} {'. $typex .'LINKSBODY}'); }
  $counter = strtolower($typex) .'count';
  $$counter = 0;
 } 
 $begin = $start;
 $end = $start + $perpage;
 $toshow = $perpage;
 if ( ($total == 0) || ($start >= $end) ) 
 {
  $linksbodybase = '';
  if ($numsubcats == 0 && $start < $end)
  {
   $template_linksbody = $language->showlinks_empty;
   $tmp = explode(',', $settings->linktypes); 
   $tmp = strtolower($tmp[0]) . 'linksbodybase';
   $$tmp = $language->showlinks_empty;
  }
 }
 else
 { 
  if ($settings->mixrecip == 'yes')
  {
   $reglinksdata = $thiscategory->linkdata();
   $columncount = 0;	
  }
  else
  {
   $reglinksdata = $thiscategory->linkdatabytype();
   $columncount = -5000; // get it out of the picture
  }
  for ($next=$start; ( ($next<$end) && ($next<$total) ); $next++)
  {
   // get next link
   $row = $db->row($reglinksdata);
   $thelink = new onelink('row', $row);
   $typex = strtoupper($thelink->type);
   $typel = strtolower($thelink->type);
   $counter = $typel . 'count';
   $templatename = $typel . 'linksbodybase';
   if ($columncount == 0 || ($settings->mixrecip == 'no' && $$counter == 0))
   {
    if ($next != $start) $links = '<tr> '. $$templatename;
	else $links = $$templatename;
   }
   else if ($columncount == $linkcolumns || ($settings->mixrecip == 'no' && $$counter == $linkcolumns))
    {
	 $links = '</tr><tr> '. $$templatename;
     if ($settings->mixrecip == 'yes') $columncount = 0;
	 else $$counter = 0;
    }
    else
    {
	 $links = $$templatename;
    }
   $columncount++;
   $$counter++;   
   // do replacements for single link
   if ($settings->mixrecip == 'yes')
   {
    $template_linksbody .= linkreplacements($links, $thelink);
   }
   else
   {
    $thevar = strtolower($thelink->type) . 'linksbody';
    $$thevar .= linkreplacements($links, $thelink);
    $$thevar = str_replace($pref .'WIDTH}', $linkwidth, $$thevar);
   }
   $thetotal++;
  }
  if ($thetotal<$linkcolumns)
  {
   if ($settings->mixrecip == 'no') $$thevar = str_replace($pref .'WIDTH}', '', $$thevar);
   $template_linksbody = str_replace($pref .'WIDTH}', '', $template_linksbody);
  }
  else
  { 
   if ($settings->mixrecip == 'no') $$thevar = str_replace($pref .'WIDTH}', $linkwidth, $$thevar);
   $template_linksbody = str_replace($pref .'WIDTH}', $linkwidth, $template_linksbody);
  }
 }
  
 $num = sizeof($listtypes);
 for ($count=0; $count<$num; $count++)
 {
  $typex = strtoupper($listtypes[$count]);
  $typel = strtolower($typex);
  $thevar = $typel . 'linksbody';  
  if ($settings->mixrecip == 'yes')
  {
   if ($typex == strtoupper($listdefault)) $template->replace('{'. $typex .'LINKSBODY}', $template_linksbody .'</tr>');
   else $template->replace('{'. $typex .'LINKSBODY}', '<tr><td></td></tr>'); // for standards Nazis
  }
  else
  {
   $thevar = $typel . 'linksbody';
   if ($$thevar == '') $$thevar = '<tr>';
   if (strstr($template->text, '{'. $typex .'LINKSBODY}')) $template->replace('{'. $typex . 'LINKSBODY}', $$thevar .'</tr>');   
//   else $template->replace('<!-- END '. strtoupper($listtypes[0]) .' LINKS -->', $$thevar);   
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
  if (strstr($permissions, '|||'. $thismember->usergroup .'|||')) $template = new template("noaccess.tpl");
 }

  
 // Get rid of extraneous link types in template that aren't in settings, to prevent annoying parse errors
 $pattern = "<!-- END (.*?) LINKS -->";   
 $pattern = '/'. $pattern .'/i';
 preg_match_all($pattern, $template->text, $badtypes);
 $template->replace($badtypes[0][0], '');
 for ($i=0; $i<sizeof($badtypes[0]); $i++)
 {
  if (!stristr($settings->linktypes, $badtypes[1][$i]))
  { 
   $problem = $badtypes[1][$i];
   $message = "Warning: You have a link type in your template which is not listed in your settings. It is being ignored.";
   $message .= "The link type $problem is used in your template, but you only have ". $settings->linktypes ." in your settings. Either add $problem to your <a href=". $settings->dirurl ."/admin/prefs.php>settings page</a> or remove it from <a href=". $settings->dirurl ."/admin/templates.php?TID=displaylinks>the template</a>.<br>";
   sendemail($settings->email, "WSN Links warning: problem with your links directory", $message, "From: ". $settings->email); 
   $remove = templateextract($template->text, '<!-- BEGIN '. $badtypes[1][$i] .' LINKS -->', $badtypes[0][$i]);
   $template->replace($remove, '');
  }
 }
 

}

else // no category selected, so display listing of categories

{
 $thiscategory = new category('id', '0'); // top level

 if (!$template) $template = new template('main.tpl');
 
 $catdata = $thiscategory->getsubcats();
 $totalcats = $thiscategory->numsubcats();

 $catsbodybase = templateextract($template->text, '<!-- BEGIN CATSBODY -->', '<!-- END CATSBODY -->');
 $subsub = templateextract($catsbodybase, '<!-- BEGIN SUBSUB -->', '<!-- END SUBSUB -->');
 $template->replace($catsbodybase, '{CATSBODY}'); // replace with marker

if ($settings->cattypes != '')
{ // formerly strstr($settings->cattypes, ',')
 $list = explode(',', $settings->cattypes);
 $n = sizeof($list);
 for ($q=0; $q<$n; $q++)
 {
  $var = 'template' . $list[$q];
  $$var = templateextract($catsbodybase, '<!-- BEGIN '. strtoupper($list[$q]) .' -->', '<!-- END '. strtoupper($list[$q]) .' -->');
  if ($$var == '') { $tmp = 'template'. $list[0]; $$var = $$tmp; } 
  $catsbodybase = str_replace($$var,  '{'.strtoupper($list[$q]) .'TEMP}', $catsbodybase);
  $subvar = $list[$q] . 'subsub';
  $$subvar = templateextract($$var, '<!-- BEGIN SUBSUB -->', '<!-- END SUBSUB -->');
  $thecount = $list[$q] .'col';
  $$thecount = 1;
  $tot = $list[$q] .'total';
  $$tot = 1;
  $avar = $cat->type .'data';
 }
 for ($currentcat=0; $currentcat<$totalcats; $currentcat++)
 {
  // get info for this category
  $area = $language->title_divider . $language->title_main;
  $row = $db->row($catdata);
  $cat = new category('row', $row); // the category to list next
  if ($cat->type == '') $cat->type = $list[0];
  $avar = $cat->type .'data';
  $temp = 'template' . $cat->type;
  if ($$temp != '') $$avar .= $$temp;
  else { $temp = 'template'. $cat->type; $$avar .= $$temp; }
  $thecount = $cat->type .'col';
  if ($$thecount == 0)
     $$avar .= '<tr>';
  else if ($$thecount == $catcolumns)
  {
    $$avar .= '</tr><tr>';
    $$thecount = 0;
  }
  $$thecount++;
  $subvar = $cat->type . 'subsub';
  if ($$subvar != '') $$avar = str_replace($$subvar, $cat->subcats($$subvar), $$avar);
  $$avar = categoryreplacements($$avar, $cat);
  $tot = $list[$q] .'total';
  $$tot += 1;
  $$avar = str_replace('{NUMBER}', $$tot, $$avar);
  $letter = ord('a') + ($$tot - 1);
  $$avar = str_replace('{LETTER}', chr($letter), $$avar);
  $$avar = str_replace('{U_LETTER}', strtoupper(chr($letter)), $$avar);
 }

 $template_catsbody = $catsbodybase;
 $list = explode(',', $settings->cattypes);
 $n = sizeof($list);
 for ($q=0; $q<$n; $q++)
 {
  $data = $list[$q] . 'data'; 
  // final </tr>
  $$data = '<tr>'. $$data .'</tr>';
  $template_catsbody = str_replace('{'.strtoupper($list[$q]) .'TEMP}', $$data, $template_catsbody);
 }
}
else
{
 $template_catsbody .= '<tr>';
 $columncount = 1;
 for ($currentcat=0; $currentcat<$totalcats; $currentcat++)
 {
 // get info for this category
  $area = $language->title_divider . $language->title_main;
  $row = $db->row($catdata);
  $cat = new category('row', $row); // the category to list next
 // display link to this category
  if ($columncount == 0)
     $template_catsbody .= '<tr>';
  else if ($columncount == $catcolumns)
  {
    $template_catsbody .= '</tr><tr>';
    $columncount = 0;
  }
  $columncount++;

  $thiscatbody = categoryreplacements($catsbodybase, $cat);
  $thiscatbody = str_replace('{NUMBER}', $currentcat + 1, $thiscatbody); 
  $template_catsbody .= $thiscatbody;
 }
 // final </tr>
 $template_catsbody .= '</tr>';

}
if ($totalcats < $catcolumns)
 $template_catsbody = str_replace('{CATWIDTH}', '', $template_catsbody);
else
 $template_catsbody = str_replace('{CATWIDTH}', $catwidth, $template_catsbody);

$template->replace('{CATSBODY}', $template_catsbody);

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