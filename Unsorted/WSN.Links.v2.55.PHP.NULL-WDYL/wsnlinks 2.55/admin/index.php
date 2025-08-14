<?php

require 'adminstart.php';

setcookie('returnto', 'index.php', time() + 1000);

if ($thismember->isadmin())
{
if ($action == 'phpinfo')
{
 phpinfo();
 die();
}
else if ($action == 'checkforupdates')
{
    $template = new template("blank");
  $template->text = $versioninfo;
  $template->text .= '<p>[<a href=index.php>Back to main admin page</a>]</p>';
}
else if ($action == 'reject')
{
 if (($customreject != 'yes') || ($message != ''))
 {
  $thislink = new onelink('id', $id);
  $thislink->reject($reason);
  $template = new template("../$templatesdir/redirect.tpl");
  $template->replace('{MESSAGE}', str_replace('{ITEM}', 'link', $language->admin_delete));
  $template->replace('{DESTINATION}', 'index.php');
 }
 else
 {
  if (!$template) $template = new template("../$templatesdir/admin/reject.tpl");
  $template->replace('{ID}', $id);
 }
}
else if ($action == 'validatechecked')
{
 // handle members
 if (isset($member))
 {
  foreach ($member as $i)
  {
   if ($todo == 'approve')
   {
    $thismem = new member('id', $i);  
    $thismem->validated = 1;
    $thismem->update('validated');
	$settings->totalmembers += 1;
	$settings->update('totalmembers');
    if ($thismem->email != '') emailmembervalidation($thismem);
   }
   else if ($todo == 'reject')
   {
    $thismem = new member('id', $i);  
    $thismem->deletethis();
   }
  }
 }

 // handle comments
 if (isset($comment))
 {
  foreach ($comment as $i)
  {
   if ($todo == 'approve') 
   {
    $thiscomment = new comment('id', $i); 
    $thiscomment->validate();
    $memberid = $thiscomment->ownerid;
    if ($memberid > 0)
    {
     $amem = new member('id', $memberid);
     $amem->addcomment($i);
    }
    $settings->totalcomments += 1;
    $settings->update('totalcomments');
   }
   else if ($todo == 'reject')
   {
    $thiscomment = new comment('dummy', $i); 
    $thiscomment->deletethis();
   }
  }  
 }
 // handle categories
 if (isset($cat))
 {
  foreach ($cat as $i)
  {
   if ($todo == 'approve')
   {
    $thiscategory = new category('id', $i); 
    $thiscategory->validated = 1;
    $thiscategory->update('validated');
    if ($thiscategory->parent > 0)
    {
     $parentcat = new category('id', $thiscategory->parent);
     $parentcat->numsub = $parentcat->numsub + 1;
     $parentcat->update('numsub');
     $parentcat->sendsubscriptions();
     $newcategories = true;
    }
   }
   else if ($todo == 'reject')
   {
    $thiscategory = new category('id', $i);
    $thiscategory->deletethis();
   }
  }
 } 

 // handle links
 if (isset($link))
 {
  foreach ($link as $i)
  { 
   if ($todo == 'approve')
   {
    $thislink = new onelink('id', $i);
    $thislink->validate();
    $ourcat = new category('id', $thislink->catid);
    $ourcat->sendsubscriptions();
    $memberid = $thislink->ownerid;
    if ($memberid > 0)
    {
     $amem = new member('id', $memberid);
     $amem->addlink($i);
    }
    $settings->totallinks += 1;
    $settings->update('totallinks');
   }
   else if ($todo == 'reject')
   {
    $thislink = new onelink('id', $i);
    $thislink->reject($message);
   }
  }
 }
 // handle link edits
 if (isset($linkedit))
 {
  foreach ($linkedit as $i)
  {
   if ($todo == 'approve')
   {
    $thislink = new onelink('id', $i);
    $newstuff = explode('|||END FIELD|||', $thislink->pendingedit);
    $n = sizeof($newstuff);
    for ($p=0; $p<$n; $p++)
    {
     $parts = explode('[,]', $newstuff[$p]);
     $thislink->$parts[0] = $parts[1];
    }
    $thislink->lastedit = time();
    $thislink->pendingedit = '';
    $thislink->update('all'); 
   }
   else if ($todo == 'reject')
   {
    $thislink = new onelink('id', $i);
    $thislink->pendingedit = '';
    $thislink->update('pendingedit');
   }
  }
 }



 if (isset($cat)) updatecategoryselector();
 if (isset($linkedit)) 	calctypeorder(); // set its type's order
 // show completed status
 $template = new template("../$templatesdir/redirect.tpl");
 if ($todo == 'approve') $template->replace('{MESSAGE}', str_replace('{TYPE}', 'selected item(s)', $language->admin_validate));
 if ($todo == 'reject') $template->replace('{MESSAGE}', str_replace('{TYPE}', 'selected item(s)', $language->admin_reject)); 
 $template->replace('{DESTINATION}', 'index.php');
}

else if ($action=='generatestatic')
{
 $template = new template("blank");
 $template->text = "<p>Static page generation is laregly useless, since the directory is easily spiderable by almost any search engine already and interactive features will obviously not function on static pages. The apache mod_rewrite option works far better. However, if you can think of some use for it this option remains available. Your static page will be based on your static.tpl template.</p> <p>Attempting to write data to the file links.html in your main WSN Links directory...</p> <p>If your links.html file is properly chmoded to 666, view your result <a href=../links.html>here</a>.</p>";
 $dostatic = true;
}
else
{
  // display admin center and then display links awaiting validation
  $template = new template("../$templatesdir/admin/main.tpl");
 // get latest version number of WSN //fixed

  
  // get unvlidaed links
  $unvalidated = getunvallinks();
  $unval = $db->numrows($unvalidated); 
 
  $template_linksbody = templateextract($template->text, '<!-- BEGIN VALIDATE AREA -->', '<!-- END VALIDATE AREA -->');
  $template->replace($template_linksbody, '{VALIDATEAREA}'); // replace with marker

  $noparsing = true; // don't parse any stuff in case html/dangerous wsn code has been allowed and we need preview

  for ($count=0; $count<$unval; $count++)
  {
   $row = $db->row($unvalidated);  // get next unvalidated link
   $thislink = new onelink('row', $row);
   // print the details of this link to the screen, with links to click for validating
   $linksbody .= linkreplacements($template_linksbody, $thislink);
  }
  
  // get unvalidated link edits
  $q = $db->select('id,pendingedit', 'linkstable', "pendingedit != ''", 'ORDER BY id ASC', '');
  $unval = $db->numrows($q);
  $template_linkeditsbody = templateextract($template->text, '<!-- BEGIN VALIDATE EDITS AREA -->', '<!-- END VALIDATE EDITS AREA -->');
  $template->replace($template_linkeditsbody, '{VALIDATEEDITSAREA}'); // replace with marker  
  for ($p=0; $p<$unval; $p++)
  {
   $row = $db->row($q);
   $editinfo = explode('|||END FIELD|||', $row[1]);
   $thislink = new onelink('blank', 'blank');   
   $n = sizeof($editinfo);
   for ($r=0; $r<$n; $r++)
   { 
    $parts = explode('[,]', $editinfo[$r]);
    $thislink->$parts[0] = $parts[1]; 
   }
   $thislink->id = $row[0];
   $linkeditsbody .= linkreplacements($template_linkeditsbody, $thislink);
  }
  
  // now list unvalidated categories
  
  // find how many unvalidated categories we have
  $getunvalidated = getunvalcats();
  $unval = $db->numrows($getunvalidated);

  $template_catsbody = templateextract($template->text, '<!-- BEGIN VALIDATE CATS AREA -->', '<!-- END VALIDATE CATS AREA -->');
  $template->replace($template_catsbody, '{VALIDATECATSAREA}'); // replace with marker

  for ($count=0; $count<$unval; $count++)
  {
   $row = $db->row($getunvalidated);  // get next unvalidated category
   $thiscat = new category('row', $row);
   // print the details of this category to the screen, with links to click for validating or deleting
   $catsbody .= categoryreplacements($template_catsbody, $thiscat);
  }

  // now comments
  $template_comsbody = templateextract($template->text, '<!-- BEGIN VALIDATE COMMENTS AREA -->', '<!-- END VALIDATE COMMENTS AREA -->');
  $template->replace($template_comsbody, '{VALIDATECOMMENTS}'); // replace with marker
  $query = $db->select($settings->commentfields, 'commentstable', 'validated=0', 'ORDER BY id DESC', '');
  $num = $db->numrows($query);
  for ($count=0; $count<$num; $count++)
  {
   $row = $db->row($query);
   $thiscomment = new comment('row', $row);
   $comsbody .= commentreplacements($template_comsbody, $thiscomment);
  }

  // now members
  $template_memsbody = templateextract($template->text, '<!-- BEGIN VALIDATE MEMBERS AREA -->', '<!-- END VALIDATE MEMBERS AREA -->');
  $template->replace($template_memsbody, '{VALIDATEMEMBERS}'); // replace with marker
  if (!$skipvalidation)
  {
   // first, expire old unvalidated members
   if ($settings->expiretime > 0)
   {
    $expiretime = time() - (3600*($settings->expiretime));
    $query = $db->delete('memberstable', "validated=0 AND time<$expiretime");
   }
   $query = $db->select($settings->memberfields, 'memberstable', 'validated=0', 'ORDER BY id DESC', '');
   $num = $db->numrows($query);
   for ($count=0; $count<$num; $count++)
   {
    $row = $db->row($query);
    $thismem = new member('row', $row); 
    $memsbody .= memberreplacements($template_memsbody, $thismem);
   }
  }
  $template->replace('{VALIDATEMEMBERS}', $memsbody);
  $template->replace('{VALIDATECATSAREA}', $catsbody); 
  $template->replace('{VALIDATEAREA}', $linksbody);   
  $template->replace('{VALIDATEEDITSAREA}', $linkeditsbody);
  $template->replace('{VALIDATECOMMENTS}', $comsbody);

  $categoryselect = $settings->categoryselector;
  $template->replace('{CATSELECTOR}', $categoryselect);

  $template->replace('{VERSIONNUMBER}', $version);
  $template->replace('{HASPAID}', $haspaid);
  $template->replace('{PURCHASEREMINDER}', $purchasereminder);
  $template->text .= "WDYL";
 }
// now we're back to outermost section within which the user's password is validated

}
require 'adminend.php';

?>