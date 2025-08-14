<?php
require 'start.php';

if ($settings->logsearches == 'yes' && $search != '' && !is_numeric($search)) fileappend("searchlog.txt", "\n" . $search);

$area = $language->title_divider . $language->title_search; // supply area for template


// get all vars with 'search' in the name. if all blank, show 'no search term' page or advanced search page

$varslist = array_merge($postvarslist, $getvarslist);
foreach ($varslist as $var)
{
 if (strstr($var, 'search')) { $searchterms .= $$var; $showsearch .= ' '. $$var; }
}
if ($searchterms == '')
{
 $filled = false; $action = ''; 
}


if ($whichtype == '') $whichtype = 'all'; 
 
if ($whichtype == 'items') $whichtype = 'links';
$realtype = $whichtype;

$search = stripcode($search);

$search = ltrim($search); // trim spaces 
$search = rtrim($search); // from both sides

if ($condition == '') $condition = 'like';

if (($filled) && (($search != '') || ($action == 'filter')))
{
 if (!$template)
 {
  if ($realtype == 'links') { $template = new template('search.tpl'); $linkfields = $settings->linkfields; }
  if ($whichtype == 'comments') { $template = new template('searchcomments.tpl'); $linkfields = $settings->commentfields; }
  if ($whichtype == 'categories') { $template = new template('searchcats.tpl'); $linkfields = $settings->categoryfields; }   
  if ($whichtype == 'members') { $template = new template('searchmembers.tpl'); $linkfields = $settings->memberfields; }
  if ($whichtype == 'all') { $template = new template("searchall.tpl"); $linkfields = $settings->linkfields; }
 }
 $template->replace('{LANG_SEARCH_FOUND}', $language->search_found);
 if ($whichtype == 'comments') $template->replace('{LANG_SEARCH_COMMENTFROM}', $language->search_commentfrom);
 if ($page=='') $page = 1;
 if ($perpage=='') $perpage = $settings->searchperpage;
 $start = ($page * $perpage) - $perpage;
 if ($searchfields == '') 
 { 
  if ($action != 'filter')
  { 
   if ($whichtype == 'links')
   {
    $searchfields = $settings->searchfields;
    $searchfields = explode(',', $searchfields);
   }
   else if ($whichtype == 'categories') $searchfields = explode(',', $settings->categoryfields);
   else if ($whichtype == 'comments') $searchfields = explode(',', $settings->commentfields);
   else if ($whichtype == 'members') $searchfields = explode(',', $settings->memberfields);   
  }
 }
 if (is_array($searchfields))
 {
  $num = sizeof($searchfields);
  $generatesearch = '(validated=1';
  if (($whichtype == 'links') || ($whichtype == 'categories')) $generatesearch .=' AND hide=0';
  $generatesearch .= ') AND (';
  if ($whichtype == 'links' && ($catid > 0  || $incat > 0 || $settings->condition != ''))
  {
   $generatesearch .= '(';
   if ($catid > 0) { $generatesearch .= 'catid='. $catid; if ($incat > 0 || $settings->condition != '') $generatesearch .= ' AND '; }
   if ($incat > 0) { $generatesearch .= '(catid='. $catid .' OR parentids LIKE \'%'. $catid .'%\')'; if ($settings->condition != '') $generatesearch .= ' AND '; }
   if ($settings->condition != '') $generatesearch .= $settings->condition;
   $generatesearch .= ') AND ';
  }
  $generatesearch .= '(';
  for ($count=0; $count<$num; $count++)
  {
   if ($condition == 'or')
   {
    $searchwords = explode(' ', $search);
    $num = sizeof($searchwords);
    for ($count=0; $count<$num; $count++)
    {
     $searchitem = $searchwords[$count];
     $generatesearch .= "$searchfields[$count] LIKE '%$searchitem%'";
     if ($count < ($num-1)) $generatesearch .= " OR ";
    }
   }
   else
   {
    $generatesearch .= "$searchfields[$count] ";
    if ($condition == 'like') $generatesearch .= "LIKE '%$search%'";
    else $generatesearch .= "$condition '$search'";
    if ($count < ($num-1)) $generatesearch .= " OR ";
   }
  }
  $generatesearch .= '))';
 }
 else
 {
  $generatesearch = '(validated=1';
  if ((($whichtype == 'links') || ($whichtype == 'categories')) && ($searchfields != 'hide')) $generatesearch .= ' AND hide=0';
  $generatesearch .= ') AND (';
  if ($action == 'filter')
  {
   if ($settings->condition != '' && $whichtype == 'links') $generatesearch .= $settings->condition .' AND ';
   $fieldops = explode(',', $linkfields);
   $n = sizeof($fieldops);
   for ($x=0; $x<$n; $x++)
   {
    $thiscondition = $fieldops[$x] .'condition';
    $thiscondition = $$thiscondition;
    $thissearch = $fieldops[$x] .'search';
    $thissearch = $$thissearch;
    if ($thissearch != '')
    { 	
     if ($thiscondition == 'like') $generatesearch .= $fieldops[$x] ." LIKE '%$thissearch%'";
     else if ($thiscondition == 'or')
     {
      $searchwords = explode(' ', $thissearch);
      $num = sizeof($searchwords);
      for ($count=0; $count<$num; $count++)
      {
       $searchitem = $searchwords[$count];
       $generatesearch .= $fieldops[$x] ." LIKE '%$searchitem%'";
       if ($count < ($num-1)) $generatesearch .= " OR ";
      }
     }
     else
	 {
	  $thiscondition = str_replace("&#60;", '<', $thiscondition);
	  $thiscondition = str_replace("&#62;", '>', $thiscondition);	 
          // allow advanced syntax searches
          if ($thiscondition == 'advanced')
          { // allows AND, OR, >, <, =
           $thissearch = str_replace('>', $fieldops[$x] . '>'. $thissearch, $thissearch);
           $thissearch = str_replace('<', $fieldops[$x] . '<'. $thissearch, $thissearch);
           $thissearch = str_replace('=', $fieldops[$x] . '='. $thissearch, $thissearch);
          }
	  if (($thiscondition == '>') || ($thiscondition == '<')) 
	  {
 	   $generatesearch .= $fieldops[$x] ." $thiscondition $thissearch";
	  }
          else if ($thiscondition == 'between')
          {
           $thissearch = str_replace('and ', '', $thissearch);
           $thissearch = str_replace(',', ' ', $thissearch);
           $thissearch = explode(' ', $thissearch);           
 	   $generatesearch .= $fieldops[$x] .' > '. $thissearch[0] .' AND '. $fieldops[$x] .' < '. $thissearch[1];
          }
	  else
	  {
	   $generatesearch .= $fieldops[$x] ." $thiscondition '$thissearch'";
	  }
	 }
     $generatesearch .= " AND ";
    }
   }
   $generatesearch = trimright($generatesearch, 5);
   $generatesearch .= ')';   
  }
  else
  {
   if ($settings->condition != '' && $whichtype == 'links') $generatesearch .= $settings->condition .' AND ';
   if ($condition == 'like') $generatesearch .= "$searchfields LIKE '%$search%'";
   else if ($condition == 'or')
   {
    $searchwords = explode(' ', $search);
    $num = sizeof($searchwords);
    for ($count=0; $count<$num; $count++)
    {
     $searchitem = $searchwords[$count];
     $generatesearch .= "$searchfields LIKE '%$searchitem%'";
     if ($count < ($num-1)) $generatesearch .= " OR ";
    }
   }
   else
   {
    $generatesearch .= "$searchfields $condition '$search'";
   }
   $generatesearch .= ')';   
  }
 }
 if ($whichtype == 'all')
 {
 // generate search
 $searchlinkfields = explode(',', $settings->searchfields);
 $searchcatfields = explode(',', $settings->categoryfields);
 $searchcomfields = explode(',', $settings->commentfields);   
 $searchmemfields = explode(',', $settings->memberfields);   
 $generatelinksearch = '(validated=1 AND hide=0) AND (';
 if ($settings->condition != '') $generatelinksearch .= $settings->condition .' AND ';
 $generatecatsearch = '(validated=1 AND hide=0) AND (';
 $generatecomsearch = '(validated=1) AND (';
 $generatememsearch = '(validated=1) AND (';
 for ($x=0; $x<sizeof($searchlinkfields); $x++)
 {
  $thisfield = $searchlinkfields[$x];
  if ($x>0) $generatelinksearch .= " OR ";
  $generatelinksearch .= "$thisfield LIKE '%$search%'";
 }
 $generatelinksearch .= ')';
 for ($x=0; $x<sizeof($searchcatfields); $x++)
 {
  $thisfield = $searchcatfields[$x];
  if ($x>0) $generatecatsearch .= " OR ";
  $generatecatsearch .= "$thisfield LIKE '%$search%'";
 }
 $generatecatsearch .= ')';
 for ($x=0; $x<sizeof($searchcomfields); $x++)
 {
  $thisfield = $searchcomfields[$x];
  if ($x>0) $generatecomsearch .= " OR ";
  $generatecomsearch .= "$thisfield LIKE '%$search%'";
 }
 $generatecomsearch .= ')';
 for ($x=0; $x<sizeof($searchmemfields); $x++)
 {
  $thisfield = $searchmemfields[$x];
  if ($x>0) $generatememsearch .= " OR ";
  $generatememsearch .= "$thisfield LIKE '%$search%'";
 }
 $generatememsearch .= ')';
 
 // get templates
  if ($settings->mixrecip == 'no') $dolinksearch = $db->select($linkfields, 'linkstable', "$generatelinksearch", 'ORDER BY typeorder ASC', "LIMIT $start,$perpage");
  else $dolinksearch = $db->select($linkfields, 'linkstable', "$generatelinksearch", '', "LIMIT $start,$perpage");  
  $numlinkresults = $db->numrows($db->select('id', 'linkstable', "$generatelinksearch", '', ""));  
  $docatsearch = $db->select($settings->categoryfields, 'categoriestable', "$generatecatsearch", '', "LIMIT $start,$perpage");
  $numcatresults = $db->numrows($db->select('id', 'categoriestable', "$generatecatsearch", '', "")); 
  $domemsearch = $db->select($settings->memberfields, 'memberstable', "$generatememsearch", '', "LIMIT $start,$perpage");
  if (!$newid) $newid = 'id';
  $nummemresults = $db->numrows($db->select($newid, 'memberstable', "$generatememsearch", '', "")); 
  $docomsearch = $db->select($settings->commentfields, 'commentstable', "$generatecomsearch", '', "LIMIT $start,$perpage");
  $numcomresults = $db->numrows($db->select('id', 'commentstable', "$generatecomsearch", '', "")); 

  $linkbody = templateextract($template->text, "<!-- BEGIN SEARCH $plurallinks RESULTS -->", "<!-- END SEARCH $plurallinks RESULTS -->");
  $template->replace($linkbody, '{LINKRESULTS}'); // replace with marker
  $catbody = templateextract($template->text, "<!-- BEGIN SEARCH categories RESULTS -->", "<!-- END SEARCH categories RESULTS -->");
  $template->replace($catbody, '{CATRESULTS}'); // replace with marker
  $membody = templateextract($template->text, "<!-- BEGIN SEARCH members RESULTS -->", "<!-- END SEARCH members RESULTS -->");
  $template->replace($membody, '{MEMRESULTS}'); // replace with marker
  $combody = templateextract($template->text, "<!-- BEGIN SEARCH comments RESULTS -->", "<!-- END SEARCH comments RESULTS -->");
  $template->replace($combody, '{COMRESULTS}'); // replace with marker
 }
 else
 {
  $getnumber = $db->select($linkfields, $whichtype.'table', "$generatesearch", '', '');
  $numresults = $db->numrows($getnumber); 
  if (($whichtype == 'links') && ($settings->mixrecip == 'no')) $dosearch = $db->select($linkfields, 'linkstable', "$generatesearch", 'ORDER BY type ASC', "LIMIT $start,$perpage");
  else $dosearch = $db->select($linkfields, $whichtype.'table', "$generatesearch", '', "LIMIT $start,$perpage");  
  $num = $db->numrows($dosearch);
  if ($whichtype == 'links') $searchbody = templateextract($template->text, "<!-- BEGIN SEARCH $plurallinks RESULTS -->", "<!-- END SEARCH $plurallinks RESULTS -->");
  else $searchbody = templateextract($template->text, "<!-- BEGIN SEARCH $realtype RESULTS -->", "<!-- END SEARCH $realtype RESULTS -->");
  $template->replace($searchbody, '{RESULTS}'); // replace with marker
  $removeothers = templateextract($template->text, "<!-- BEGIN SEARCH $plurallinks RESULTS -->", "<!-- END SEARCH $plurallinks RESULTS -->");
  if ($whichtype != 'links') $template->replace($removeothers, '');
  $template->replace($searchbody, '{RESULTS}'); // replace with marker
  $removeothers = templateextract($template->text, "<!-- BEGIN SEARCH categories RESULTS -->", "<!-- END SEARCH categories RESULTS -->");
  if ($whichtype != 'categories') $template->replace($removeothers, '');
  $removeothers = templateextract($template->text, "<!-- BEGIN SEARCH members RESULTS -->", "<!-- END SEARCH members RESULTS -->");
  if ($whichtype != 'members') $template->replace($removeothers, '');
  $removeothers = templateextract($template->text, "<!-- BEGIN SEARCH comments RESULTS -->", "<!-- END SEARCH comments RESULTS -->");
  if ($whichtype != 'comments') $template->replace($removeothers, '');
 }

 if ($whichtype == 'links' || $whichtype == 'all')
 {
  $listofem = explode(',', $settings->linktypes);
  $howmany = sizeof($listofem);
  for ($x=0; $x<$howmany; $x++)
  {
   $temp = 'ourtemplate'. $listofem[$x];
   if ($whichtype == 'all') $$temp = templateextract($linkbody, '<!-- BEGIN '. strtoupper($listofem[$x]) .' -->', '<!-- END '. strtoupper($listofem[$x]) .' -->');
   else $$temp = templateextract($searchbody, '<!-- BEGIN '. strtoupper($listofem[$x]) .' -->', '<!-- END '. strtoupper($listofem[$x]) .' -->');
   $first = 'ourtemplate'. $listofem[0];
   if ($$temp == '')
   {
    for ($p=sizeof($listtypes); $p>0; $p--)
    {
     $test = strtolower($listtypes[$p]). 'linksbodybase';
	 if ($$test) $thedefault = $$test;
    }   
    $$temp = $$thedefault;
   }
   if ($whichtype == 'all') { $linkbody = str_replace($$temp, '{'. strtoupper($listofem[$x]) .'TEMP}', $linkbody); }
   else $searchbody = str_replace($$temp, '{'. strtoupper($listofem[$x]) .'TEMP}', $searchbody);
  }
 }

 if ($whichtype == 'all')
 {
  while ($linkrow = $db->row($dolinksearch))
  {
   $thelink = new onelink('row', $linkrow); 
   if ($settings->mixrecip == 'no')
   {
    $thevar = $thelink->type . 'links';
    $temp = 'ourtemplate'. $thelink->type;
    $$thevar .= linkreplacements($$temp, $thelink); 
   }
   else
   {
    $thevar = $thelink->type . 'links';
    $temp = 'ourtemplate'. $thelink->type;
    $linkresults .= linkreplacements($$temp, $thelink); 
   }
  }
  while ($catrow = $db->row($docatsearch))
  {
   $thecat = new category('row', $catrow);  
   $catresults .= categoryreplacements($catbody, $thecat);        
  } 
  while ($memrow = $db->row($domemsearch))
  {
   $themem = new member('row', $memrow);
   $memresults .= memberreplacements($membody, $themem);  
  } 
  while ($comrow = $db->row($docomsearch))
  {
   $thecom = new comment('row', $comrow); 
   $comresults .= commentreplacements($combody, $thecom);
  }  
 }
 else
 {  
  for ($count=0; $count<$num; $count++)
  {  
   $row  =  $db->row($dosearch);   
   if ($whichtype == 'links') $thelink = new onelink('row', $row); 
   else if ($whichtype == 'comments') $thelink = new comment('row', $row); 
   else if ($whichtype == 'members') $thelink = new member('row', $row);
   else if ($whichtype == 'categories') $thelink = new category('row', $row);   
   if ($whichtype == 'links') 
   {
    if ($settings->mixrecip == 'no')
    {
     $thevar = $thelink->type . 'links';
     $temp = 'ourtemplate'. $thelink->type;
 	 $$thevar .= linkreplacements($$temp, $thelink); 
    }
    else
    {	
     $thevar = $thelink->type . 'links';
     $temp = 'ourtemplate'. $thelink->type;
	 $results .= linkreplacements($$temp, $thelink); 
    }	
   }
   else if ($whichtype == 'members') $results .= memberreplacements($searchbody, $thelink); 
   else if ($whichtype == 'comments') $results .= commentreplacements($searchbody, $thelink);     
   else if ($whichtype == 'categories') $results .= categoryreplacements($searchbody, $thelink);     
  }
 }  
 if ($numresults > 0 || $whichtype == 'all')
 {
  if ($whichtype == 'links' || $whichtype == 'all')
  {
   if ($settings->mixrecip == 'no')
   {
    $listofem = explode(',', $settings->linktypes);
    $howmany = sizeof($listofem);
    for ($x=0; $x<$howmany; $x++)
    {
     $thevar = $listofem[$x] . 'links'; 
     if ($whichtype == 'all')
     {
      if (strstr($linkbody, '{'. strtoupper($listofem[$x]) .'TEMP}')) $linkbody = str_replace('{'. strtoupper($listofem[$x]) .'TEMP}', $$thevar, $linkbody);
	  else $linkbody = str_replace('{'. strtoupper($listofem[0]) .'}', $$thevar, $linkbody);
     }
     else
     {
     if (strstr($searchbody, '{'. strtoupper($listofem[$x]) .'TEMP}')) $searchbody = str_replace('{'. strtoupper($listofem[$x]) .'TEMP}', $$thevar, $searchbody);
	 else $searchbody = str_replace('{'. strtoupper($listofem[0]) .'}', $$thevar, $searchbody);
     }
	 if ($whichtype == 'all') $linkresults = $linkbody;
    }
    $results = $searchbody;
   }
  }
  if ($whichtype == 'all')
  {
   $template->replace('{LINKRESULTS}', $linkresults);
   $template->replace('{CATRESULTS}', $catresults);
   $template->replace('{COMRESULTS}', $comresults);
   $template->replace('{MEMRESULTS}', $memresults);      
   $template->replace($pref .'TOTAL}', $numlinkresults);
   $template->replace('{CATEGORYTOTAL}', $numcatresults);
   $template->replace('{COMMENTTOTAL}', $numcomresults);
   $template->replace('{MEMBERTOTAL}', $nummemresults);         
   if ($numlinkresults == 0) $template->replace('{LINKRESULTS}', '');
   if ($numcatresults == 0) $template->replace('{CATRESULTS}', '');
   if ($numcomresults == 0) $template->replace('{COMRESULTS}', '');
   if ($nummemresults == 0) $template->replace('{MEMRESULTS}', '');            
   $numresults = $numlinkresults + $numcatresults + $numcomresults + $nummemresults;
   if ($numresults == '' || $numresults == 0)
   {
    $template = new template("redirect.tpl");
    $template->replace('{MESSAGE}', $language->search_nomatch);
    $template->replace('{DESTINATION}', 'search.php');
    $template->replace('{SECONDSDELAY}', $settings->secondsdelay * 3);
   }
  }
  if ($whichtype != 'all')
  {
  if ($numresults == '' || $numresults == 0)
  {
   $template = new template("redirect.tpl");
   $template->replace('{MESSAGE}', $language->search_nomatch);
   $template->replace('{DESTINATION}', 'search.php');
   $template->replace('{SECONDSDELAY}', $settings->secondsdelay * 3);
  }
  else
    $template->replace('{RESULTS}', $results);
  }
 }
 else
 {
  if ($numresults == '' || $numresults == 0)
  {
   $template = new template("redirect.tpl");
   $template->replace('{MESSAGE}', $language->search_nomatch);
   $template->replace('{DESTINATION}', 'search.php');
   $template->replace('{SECONDSDELAY}', $settings->secondsdelay * 3);
  }
  else
    $template->replace('{RESULTS}', '');
 }
 if ($numresults == '' || $numresults == 0)
   $template->replace('{NUMRESULTS}', '0');
 else
   $template->replace('{NUMRESULTS}', $numresults);

 if ($searchfields[0] == 'ownerid')
 {
  $getname = new member('id', $search);
  $template->replace('{SEARCHTERM}', $getname->name);
 }
 if ($action == 'filter'|| $action == 'advanced')
 {
  $template->replace('{SEARCHTERM}', $showsearch);  
 }
 else $template->replace('{SEARCHTERM}', $search);  
 $numpages = ceil($numresults / $perpage);
 $cnt = 0;
 if ($getvarslist)
 {
  foreach ($getvarslist as $var)
  {
   if ($var != 'page' && $$var != '')
   { 
    if (is_array($var) || $var == 'searchfields')
	{ 
	 $soa = sizeof($var);
	 for ($p=0; $p<$soa; $p++)
	 {
      if ($cnt > 0) $searchvars .= '&amp;';
      $searchvars .= $var .'['. $p .']' .'='. urlencode($$var[$p]);
	  
      $cnt++;
	 }
	}
	else
	{
     if ($cnt > 0) $searchvars .= '&amp;';
     $searchvars .= $var .'='. urlencode($$var);
     $cnt++;
	}
   }
  }
 }
 $cnt = 0;
 if ($postvarslist)
 {
  foreach ($postvarslist as $var)
  {
   if ($var != 'page' && $$var != '')
   {
    if (is_array($var) || $var == 'searchfields')
	{
	 $soa = sizeof($var);
	 for ($p=0; $p<$soa; $p++)
	 {
      if ($cnt > 0) $searchvars .= '&amp;';
      $searchvars .= $var .'['. $p .']' .'='. urlencode($$var[$p]);
      $cnt++;
	 }
	}
	else
	{
     if ($cnt > 0) $searchvars .= '&amp;';
     $searchvars .= $var .'='. urlencode($$var);
     $cnt++;
	}
   }
  }
 }

 $search = urlencode($search);
 for ($count=1; $count < $page; $count++)
 {
  $previouspages .= $language->pageselection_left. "<a href=\"search.php?";
  if ($action == 'filter') $previouspages .= $searchvars . "&amp;page=$count";
  else
  {
   $previouspages .= "filled=1&amp;perpage=$perpage&amp;page=$count&amp;condition=$condition&amp;whichtype=$whichtype";
   if (!is_array($searchfields)) $previouspages .= "&amp;searchfields[0]=". $searchfields;
   else if (sizeof($searchfields) < 2) $previouspages .= "&amp;searchfields[0]=". $searchfields[0];
  $previouspages .= "&amp;search=$search";
  }
  $previouspages .= "\">$count</a>". $language->pageselection_right;
 }
 for ($count=$page; $count<$numpages; $count++)
 {
  $next = $count+1;
  $nextpages .= $language->pageselection_left ."<a href=\"search.php?";
  if ($action == 'filter') $nextpages .= $searchvars . "&amp;page=$next";
  else 
  {
   $nextpages .= "filled=1&amp;page=$next&amp;perpage=$perpage&amp;whichtype=$whichtype";
   if (!is_array($searchfields)) $nextpages .= "&amp;searchfields[0]=". $searchfields;
   else if (sizeof($searchfields) < 2) $nextpages .= "&amp;searchfields[0]=". $searchfields[0];
   $nextpages .= "&amp;condition=$condition&amp;search=". $search;
  }
  $nextpages .= "\">$next</a>". $language->pageselection_right;
 }
 if ($previouspages != '' || $nextpages != '') $template->replace('{MULTIPAGE}', 1);
 else $template->replace('{MULTIPAGE}', 0);
 $template->replace('{PREVIOUS}', $previouspages);
 $template->replace('{NEXT}', $nextpages);
 $template->replace('{CURRENTPAGE}', $page);
 $template->replace('{PAGE}', $page);
 $template->replace('{PERPAGE}', $perpage); 
 $template->replace('{SEARCHURL}', addslashes(urlencode($search)));  
}
else
{
 if (!$template) $template = new template('searchadvanced.tpl');
}


require 'end.php';
?> 