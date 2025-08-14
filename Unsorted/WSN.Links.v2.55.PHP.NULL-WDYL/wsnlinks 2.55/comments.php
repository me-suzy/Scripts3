<?php
require 'start.php';
$commentspage = true;

// check that this is a valid thread
if ($linkid == '') $linkid = $id;
$query = $db->select('id', 'linkstable', "id=$linkid", '', '');
$check = $db->rowitem($query);
if ($check != $linkid) 
{
 header("Location: index.php");
 die ('<meta http-quiv="refresh" content="0;index.php">');
}

// reconstruct the current url and put it in a cookie, so we can know where to come back to if we go off somewhere soon... but only save it for 15 minutes

if ($settings->dirurl != '') $currenturl = $settings->dirurl .'/comments.php';
else $currenturl =  'http://' . $HTTP_SERVER_VARS['SERVER_NAME'] . $HTTP_SERVER_VARS['PHP_SELF'];
$currenturl .= '?'. $HTTP_SERVER_VARS['QUERY_STRING'];
if ($action != 'post' && $action != 'subscribe' && $action != 'rate')
{
if (($currenturl != '')&&($HTTP_SERVER_VARS['PHP_SELF'] != '')&&($currenturl != '/?')&&($currenturl != 'http://?')&&($currenturl != 'http:///?'))
setcookie("returnto", "$currenturl",  900);
}

$area = $language->title_divider . $language->title_comments;

if ($action == 'subscribe')
{
 $thislink = new onelink('id', $id);
 $message = $thislink->subscribe($thismember->id);
 if (!$template) $template = new template("redirect.tpl");
 $template->replace('{MESSAGE}', $message);
 if ($aftersubscribecom == '') $aftersubscribecom = $returnto;
 $template->replace('{DESTINATION}', $aftersubscribecom);
}
else if ($action == 'rate')
{
 $thiscomment = new comment('id', $commentid);
 if (!strstr($previousvotes, " $commentid "))
 {
  if ($votevalue == 'plus')
  {
   $thiscomment->approved += 1;
   $thiscomment->votes += 1;
   $thiscomment->update('approved,votes');
  }
  else if ($votevalue == 'minus')
  {
   $thiscomment->votes += 1;
   $thiscomment->update('votes');
  }
  $message = $language->comments_vote;
  makecookie("previousvotes", $previousvotes ." $commentid ", time()+9999999);
 }
 else
 {
  $message = $language->comments_votedup;
 }
 $template = new template("redirect.tpl");
 $template->replace('{MESSAGE}', $message);
 $template->replace('{DESTINATION}', 'comments.php?id='. $id);
}

if (($action=='post') && ($thismember->groupcansubmitcomments))
{
 if ($thismember->canpost())
 {
  $fields = explode(',', $settings->commentfields);
  $num = sizeof($fields);
  for ($x=0; $x<$num; $x++)
  {
   if (is_array($$fields[$x])) { $$fields[$x] = implode('|||', $$fields[$x]); $$fields[$x] = str_replace('selected', '', $$fields[$x]); }
  } 
  $requiredcomments = explode(',', $settings->requiredcomments);
  $y = sizeof($requiredcomments);
  for ($x=0; $x<$y; $x++)
  {
   if ($$requiredcomments[$x] == '' && $requiredcomments[$x] != '') $incomplete = true;
  }
  if ($incomplete)
  {
   $noparsing = true; // don't parse until complete
   // access with comments.php?id=linkid&page=x
   if (!$template) $template = new template('viewcomments.tpl');
   $template = templatebasics($template);
   $acom = new comment('new', 'new');
   $template->text = commentreplacements($template->text, $acom);
   $template->replace('{INCOMPLETE}', $language->suggest_incomplete);
   $template->replace($pref .'ID}', $linkid);
   $template->showsmilies();
  }  
  else
  {
   if (autovalidate('comments', $thismember->usergroup)) $validated = 1; else $validated = 0;
   if ($postername == '') $postername = $thismember->name;
   $posterid = $thismember->id;
   $ownerid = $thismember->id;
   $votes = 0;
   $thiscomment = new comment('new', 'blank');
   $thiscomment->ip = $HTTP_SERVER_VARS['REMOTE_ADDR'];
   $thiscomment->add();
   if (!$template) $template = new template('redirect.tpl');
   if ($validated == 1) $thanksmessage = $language->comments_thanks;
   else $thanksmessage = $language->comments_pending;
   $template->replace('{MESSAGE}', $thanksmessage);
   if ($aftercom == '') $aftercom = $returnto;
   $template->replace('{DESTINATION}', $aftercom);
   if ($validated == 1)
   {
    $thiscomment->validate();
    $thismember->addcomment();
    $thelink = new onelink('id', $linkid);
    $cat = new category('id', $thelink->catid);
    $cat->totalcomments += 1; 
    $cat->update('totalcomments');
   }
   calccommenttypeorder();
  }
 }
 else
 {
  if (!$template) $template = new template('viewcomments.tpl');
  $template = commentbasics($template);
  $acom = new comment('new', 'new');
  $template->text = commentreplacements($template->text, $acom);
  $template->replace('{INCOMPLETE}', str_replace('{DELAYTIME}', $settings->floodcheck, $language->comments_floodcheck));
  $template->replace($pref .'ID}', $linkid);
  $template->showsmilies();  
 }
}
else if (($action == 'post') && !($thismember->groupcansubmitcomments))
{
 // not allowed to submit
 if (!$template) $template = new template('redirect.tpl');
 $template->replace('{MESSAGE}', $language->comments_cannotpost);
 $template->replace('{DESTINATION}', "comments.php?id=$linkid");
}
else
{
 // access with comments.php?id=linkid&page=x
  if (!$template) $template = new template('viewcomments.tpl');
  $template = templatebasics($template);
  $acom = new comment('blank', 'blank');
  $template->text = commentreplacements($template->text, $acom);
  $template->replace('{INCOMPLETE}', '');
  $template->replace($pref .'ID}', $linkid);
  $template->showsmilies();  
  if ($scriptname == 'wsnmanual')
  {
   $thislink = new onelink('id', $linkid);
   // Check to see if this is unique or non-unique hit.
   if (!(strstr($HTTP_COOKIE_VARS['hits'], " $id ")) )
   {
    if ($thismember->isadmin() && $settings->dontcount == 'yes') $doesntcount = true;
    if (!$doesntcount)
    {
     $thislink->hits = $thislink->hits + 1;
     $thislink->update('hits');
     $settings->totalhits += 1;
     $settings->update('totalhits');
     if ((!$custommemberdb) || ($newhits != ''))
     {
      $owner = new member('id', $thislink->ownerid);
      $owner->totalhits += 1;
      $owner->update('totalhits');
     }
    }
    // set cookie to prevent counting again in future
    $idstring = ' '. $HTTP_COOKIE_VARS[hits] .' '. $id .' ';
    $clicktimer = $settings->clicktimer;
    setcookie("hits", "$idstring", time()+$clicktimer);  
   }
  }
}

function templatebasics($template)
{
 global $id, $language, $db, $linkid, $pref, $page, $perpage, $orderby, $search, $settings, $commentspage;

 if ($linkid == '') $linkid = $id;
 $template->replace($pref .'ID}', $linkid); 
 $thislink = new onelink('dummy', $linkid);
 $template->text = linkreplacements($template->text, $thislink); 
 $ourcategory = new category('id', $thislink->catid);
 $template->replace('{NAVIGATION}', shownav($ourcategory));
 if ($page == '') $page = 1;
 if ($perpage == '') $perpage = $settings->perpage;
 $start = ($page * $perpage) - $perpage;
 if ($orderby == '') $orderby = $settings->ordercomments;
 if ($settings->mixcomments == 'no') $orderby = 'ORDER BY typeorder ASC,'. str_replace('ORDER BY ', '', $orderby);
 $totalquery = $db->select($settings->commentfields, 'commentstable', "linkid=$linkid AND validated=1 AND hide=0", $orderby, ''); 
 $total = $db->numrows($totalquery);
 $query = $db->select($settings->commentfields, 'commentstable', "linkid=$linkid AND validated=1 AND hide=0", $orderby, "LIMIT $start,$perpage");
 $posttemplate = templateextract($template->text, '<!-- BEGIN POST -->', '<!-- END POST -->');
 $template->replace($posttemplate, '{POSTAREA}');
 // get each comment type's template
 $ctypes = explode(',', $settings->commenttypes);
 for ($x=0; $x<sizeof($ctypes); $x++)
 {
  $var = $ctypes[$x] . 'template';
  $$var = templateextract($posttemplate, '<!-- BEGIN '. strtoupper($ctypes[$x]) .' -->', '<!-- END '. strtoupper($ctypes[$x]) .' -->');
  if ($$var == '') { $basic = 'regulartemplate'; $$var = $$basic; }
  $posttemplate = str_replace($$var, '{'. strtoupper($ctypes[$x]) .'}', $posttemplate);
 }
 $num = $db->numrows($query);
 for ($count=0; $count<$num; $count++)
 {
  $row = $db->row($query);
  $thispost = new comment('row', $row);
  if ($thispost->type == '') $thispost->type = 'regular';
  $type = $thispost->type;
  $var = $type . 'template';
  $onepost = commentreplacements($$var, $thispost);
  if (strstr($$var, '{MEMBER'))
  { 
   if ($thispost->ownerid == '') $postingmember = new member('blank', 'blank');
   else $postingmember = new member('id', $thispost->ownerid);
   $onepost = memberreplacements($onepost, $postingmember); 
  }
  if ($settings->mixcomments == 'yes') $posts .= $onepost;
  else { $tvar = $thispost->type . 'posts'; $$tvar .= $onepost; }
 }
 if ($settings->mixcomments == 'yes') $template->replace('{POSTAREA}', $posts);
 else
 {
  for ($x=0; $x<sizeof($ctypes); $x++)
  {
   $var = $ctypes[$x] . 'posts';
   $posttemplate = str_replace('{'. strtoupper($ctypes[$x]) .'}', $$var, $posttemplate);
  }
  $template->replace('{POSTAREA}', $posttemplate);
 }
 $numpages = ceil($total / $perpage);
 $search = urlencode($search);
 if ($settings->apacherewrite == 'yes')
 {
  for ($count=1; $count < $page; $count++)
   $previouspages .= $language->pageselection_left. '<a href="thread/'. $linkid .'/'. $count .'">'. $count .'</a>'. $language->pageselection_right;
  for ($count=$page; $count<$numpages; $count++)
  {
  $next = $count+1;
  $nextpages .= $language->pageselection_left .'<a href="thread/'. $linkid .'/'. $next .'">'. $next .'</a>'. $language->pageselection_right;
  }
 }
 else
 {
  for ($count=1; $count < $page; $count++)
   $previouspages .= $language->pageselection_left. '<a href="comments.php?id='. $linkid .'&amp;page='. $count .'&amp;perpage='. $perpage .'">'. $count .'</a>'. $language->pageselection_right;
  for ($count=$page; $count<$numpages; $count++)
  {
  $next = $count+1;
  $nextpages .= $language->pageselection_left .'<a href="comments.php?id='. $linkid .'&amp;page='. $next .'&amp;perpage='. $perpage .'">'. $next .'</a>'. $language->pageselection_right;
  }
 }
 if ($previouspages != '' || $nextpages != '') $template->replace('{MULTIPAGE}', 1);
 else $template->replace('{MULTIPAGE}', 0);
 $template->replace('{PREVIOUS}', $previouspages);
 $template->replace('{NEXT}', $nextpages);
 $template->replace('{CURRENTPAGE}', $page);
 $template->replace('{PAGE}', $page);
 $template->replace('{PERPAGE}', $perpage); 
 for ($x=0; $x<sizeof($ctypes); $x++)
 {
  $var = '{'. strtoupper($ctypes[$x] . 'total') .'}';
  if (strstr($template->text, $var))
  {
   $tot = $db->numrows($db->query('id', 'commentstable', "linkid=$linkid AND validated=1 AND hide=0 AND type='". $ctypes[$x] ."'", '', ''));
   $template->replace($var, $tot);
  }
  $posttemplate = str_replace('{'. strtoupper($ctypes[$x]) .'}', $$var, $posttemplate);
 }

 return $template;
}

require 'end.php';
?>