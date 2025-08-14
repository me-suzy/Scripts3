<?php
require 'start.php';

// reconstruct the current url and put it in a cookie, so we can know where to come back to if we go off somewhere soon... but only save it for 15 minutes

if ($settings->dirurl != '') $currenturl = $settings->dirurl .'/index.php';
else $currenturl =  'http://' . $HTTP_SERVER_VARS['SERVER_NAME'] . $HTTP_SERVER_VARS['PHP_SELF'];
$currenturl .= '?'. $HTTP_SERVER_VARS['QUERY_STRING'];
if ($action != 'post')
{
if (($currenturl != '')&&($HTTP_SERVER_VARS['PHP_SELF'] != '')&&($currenturl != '/?')&&($currenturl != 'http://?')&&($currenturl != 'http:///?'))
setcookie("returnto", "$currenturl",  900);
}

if ($action == 'profile')
{
 $area = $language->title_divider . $language->title_profile;
 if (!$template) $template = new template("viewprofile.tpl");
 if ($id > 0)
 {
  $amem = new member('id', $id);
 }
 else if ($name != '')
 {
  if (!$newname) $newname = 'name';
  $query = $db->select($newid, 'memberstable', "$newname='$name'", '', '');
  $amem = new member('id', $db->rowitem($query));
 }
 else
 { // assume they want their own profile
  $amem = $thismember;
 }
 $template->text = memberreplacements($template->text, $amem);
}
else
{ // show member list
 $area = $language->title_divider . $language->title_memberlist;
 if (!$template) $template = new template("memberlist.tpl");
 if ($page == '') $page = 1;
 if ($perpage == '') $perpage = $settings->perpage;
 $start = ($page * $perpage) - $perpage;
 if ($field == '') $order = $settings->memberlistorder;
 else $order = "ORDER BY $field $ascdesc";

 if (!$skipvalidation) $condition = 'validated=1';
 else $condition = "$newid > 0";
 $totalquery = $db->select($newid, 'memberstable', $condition, '', '');
 $total = $db->numrows($totalquery); 
 
 $memtemp = templateextract($template->text, '<!-- BEGIN MEMBERS -->', '<!-- END MEMBERS -->');
 $memsquery = $db->select($settings->memberfields, 'memberstable', $condition, $order, "LIMIT $start,$perpage");
 $num = $db->numrows($memsquery);
 for ($x=0; $x<$num; $x++)
 {
  $row = $db->row($memsquery);
  $amem = new member('row', $row);
  $subtemp .= memberreplacements($memtemp, $amem);
 }
 
 $template->replace($memtemp, $subtemp);
 $order = str_replace(' ', '%20', $order);
 $numpages = ceil($total / $perpage);
 $search = urlencode($search);
 for ($count=1; $count < $page; $count++)
   $previouspages .= $language->pageselection_left. "<a href=memberlist.php?field=$field&amp;ascdesc=$ascdesc&amp;page=$count&amp;perpage=$perpage>$count</a>". $language->pageselection_right;
 for ($count=$page; $count<$numpages; $count++)
 {
    $next = $count+1;
    $nextpages .= $language->pageselection_left ."<a href=memberlist.php?field=$field&amp;ascdesc=$ascdesc&amp;page=$next&amp;perpage=$perpage>$next</a>". $language->pageselection_right;
 }
 if ($previouspages || $nextpages) $template->replace('{MULTIPAGE}', 1);
 else $template->replace('{MULTIPAGE}', 0);
 $template->replace('{PREVIOUS}', $previouspages);
 $template->replace('{NEXT}', $nextpages);
 $template->replace('{CURRENTPAGE}', $page);
 $template->replace('{PAGE}', $page);
 $template->replace('{PERPAGE}', $perpage);
}

require 'end.php';
?>