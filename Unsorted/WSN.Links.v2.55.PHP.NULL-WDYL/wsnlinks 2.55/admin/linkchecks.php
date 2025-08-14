<?php
require 'adminstart.php';

$returnto = $HTTP_COOKIE_VARS['returnto'];
if ($returnto == '') $returnto = 'linkchecks.php';

if ($showdetails) $showdetailsreplace = 'checked'; 
if ($sendemails) $sendemailsreplace = 'checked'; 

 if ($thismember->isadmin())
 {
 $template = new template("../$templatesdir/admin/linkchecks.tpl");
 $rowtemplate = templateextract($template->text, '<!-- BEGIN ROW -->', '<!-- END ROW -->');
 if ($todo == 'email')
 {
  foreach ($linkid as $id)
  { 
   $thislink = new onelink('id', $id); 
   if ($thislink->email == '' && $thislink->ownerid > 0) { $m = new member('id', $thislink->ownerid); $thislink->email = $m->email; }
   if ($thislink->email != '')
   {
    $subject = linkreplacements($language->email_recipnotfoundsubject, $thislink);
    $message = linkreplacements($language->email_recipnotfoundbody, $thislink);
    sendemail($thislink->email, $subject, $message, "From: ". $settings->email);
    echo "Dispatched an email to ". $thislink->email ."<br>";
   }
   else 
   {
    echo "No email address was provided with the link ". $thislink->title ."<br>";
   } 
  }
 }
 $higest = $db->rowitem($db->select('id', 'linkstable', 'id>0', 'ORDER BY id DESC', 'LIMIT 0,1'));
 if ($todo == 'suspend')
 { 
  foreach ($linkid as $x)
  {
   $thislink = new onelink('id', $x);
   if ($thislink->suspended == 0)
   {
    $thislink->suspended = 1;
    $thislink->hide = 1; 
    $thislink->update('suspended,hide');
    // send email notification of suspension
    $subject = linkreplacements($language->email_suspendsubject, $thislink);
    $message = linkreplacements($language->email_suspendbody, $thislink);
    if ($thislink->email == '' && $thislink->ownerid > 0) { $m = new member('id', $thislink->ownerid); $thislink->email = $m->email; }
    if ($thislink->email != '')
    {
     sendemail($thislink->email, $subject, $message, "From: ". $settings->email);
     echo "<p>The link has been suspended and the submitter has been notified.</p>";
    }
    else
    {
     echo "<p>The link has been suspended, but the submitter did not provide an email address for notification.</p>";
    }
   }
   else echo "<p>The link ". $thislink->title ." was already suspended, so no action is being taken.</p>";
  }
 }
 if ($todo == 'unsuspend')
 {
  $x = $id;
  $thislink = new onelink('id', $x);
  if ($thislink->suspended == 1)
  {
   $thislink->suspended = 0;
   $thislink->hide = 'no';
   $thislink->suspect = 0;
   $thislink->update('suspended,hide,suspect');
   // send email notification of restoration
   // send email notification of suspension
   $subject = linkreplacements($language->email_restorelinksubject, $thislink);
   $message = linkreplacements($language->email_restorelinkbody, $thislink);
   if ($thislink->email == '' && $thislink->ownerid > 0) { $m = new member('id', $thislink->ownerid); $thislink->email = $m->email; }
   if ($thislink->email != '')
   {
    sendemail($thislink->email, $subject, $message, "From: ". $settings->email);
    echo "<p>The link has been restored and the submitter has been notified.</p>";
   }
   else
   {
    echo "<p>The link has been restored, but the submitter did not provide an email address for notification.</p>";
   }
  }
  else echo "<p>The link ". $thislink->title ." was not previously suspended, so no action is being taken.</p>";
 }
 if ($todo == 'clearsuspect')
 {
  foreach ($linkid as $x)
  {
   $thislink = new onelink('id', $x);
   $thislink->suspect = 0;
   $thislink->update('suspect');
  }
 }
 if ($todo == 'nevercheckagain')
 {
  foreach ($linkid as $x)
  {
   $thislink = new onelink('id', $x);
   $thislink->suspect = 2;
   $thislink->update('suspect');
  }
 }
 if ($todo == 'delete')
 {
  foreach ($linkid as $x)
  {
   $thislink = new onelink('id', $x);
   $thislink->deletethis(); 
  }
 } 
 if ($action == 'showsuspect')
 {
  $query = $db->select($settings->linkfields, 'linkstable', 'suspect=1', 'ORDER BY id DESC', '');
  $num = $db->numrows($query);
  for ($c=0; $c<$num; $c++)
  {
   $row = $db->row($query);
   $thislink = new onelink('row', $row);
   $listresult .= linkreplacements($rowtemplate, $thislink);
  }
  $message = "These links have been marked as suspect by past link checks, for being duplicates, dead, or lacking a reciprocal link:";
  $template->replace('{MESSAGE}', $message);
  $template->replace($rowtemplate, $listresult);   
  $inputarea = templateextract($template->text, '<!-- BEGIN INPUT AREA -->', '<!-- END INPUT AREA -->');
  $template->replace($inputarea, '');
  $template->replace('{START}', $start);
  $template->replace('{TYPE}', $type);
 }
 else if ($action == 'duplicatecheck')
 {
  if ($filled)
  {
   $doit = $db->select('url', 'linkstable', 'alias=0', 'ORDER BY id DESC', '');
   $num = $db->numrows($doit);
   for ($count=0; $count<$num; $count++)
   {
    $next = $db->rowitem($doit);
    $urllist .= '|||'. $next;
   }
   $duplicates = 0;
   $geturls = $db->select('url', 'linkstable', 'suspect=0 and alias=0', 'ORDER BY id DESC', '');
   $num = $db->numrows($geturls);
   for ($count=0; $count<$num; $count++)
   {
    if ($showdetails == 'on') echo "Checking $url (link id#$id) ... ";
    $url = $db->rowitem($geturls);
    $timesinstring = @substr_count($urllist, $url);
    if ($showdetails == 'on') { if ($timesinstring>1) echo "DUPLICATE <a href=edit.php?action=link&field=id&condition=equals&fieldvalue=$id><img src=../$templatesdir/images/edit.gif border=0></a><br>"; else echo "OK <br>"; }
    if ($timesinstring > 1)
    {
     $duplicates++;
     $getdups = $db->select('id,url', 'linkstable', "url='$url' and alias=0", 'ORDER BY id DESC', '');
     $numdups = $db->numrows($getdups);
     for ($countb=0; $countb<$numdups; $countb++)
     {
      $therow = $db->row($getdups);
      $id = $therow[0];
      if ( !(strstr($donethese, " $id ")) )
      { 
       $donethese = ' '. $donethese .' '. $id .' ';
       $url = $therow[1];
       $thislink = new onelink('dummy', $id);
       $thislink->suspect = 1;
       $thislink->update('suspect');
       $listresult .= linkreplacements($rowtemplate, $thislink);
      }
     }
    }
   }
   if ($duplicates == 0) $message = "No duplicates found.";
   else $message = "These links seem to have duplicates (all copies including original are shown):";
   $template->replace('{MESSAGE}', $message);
   $inputarea = templateextract($template->text, '<!-- BEGIN INPUT AREA -->', '<!-- END INPUT AREA -->');
   $template->replace($inputarea, '');
   $template->replace($rowtemplate, $listresult);   
  }
 } 

 else if ($action == 'downcheck')
 {
  if ($filled)
  {
   if ($perpage != '') $nextstart = $start+$perpage; else $nextstart = 0;
   if ($perpage == '') $perpage = 20;
   if ($start == '') $start = 0;
   $getthem = $db->select('id,url', 'linkstable', 'suspect=0 and alias=0', 'ORDER BY id ASC', "LIMIT $start,$perpage"); 
   $numlinks = $db->numrows($getthem);
   $message = "$numlinks links checked. ";
   $badones = 0;
   for ($count=0; $count<$numlinks; $count++)
   {
    $row = $db->row($getthem);
    $id = $row[0];
    $url = $row[1];
    if ($showdetails == 'on') echo "Checking $url (link id#$id) ... ";
    $check = geturl($url);
    if ($showdetails == 'on') { if ($check) echo "OK <br>"; else echo "BAD LINK <a href=edit.php?action=link&field=id&condition=equals&fieldvalue=$id><img src=../$templatesdir/images/edit.gif border=0></a><br>"; }
    if (!$check)
    {
      $badones++;
      $thislink = new onelink('id', $id);
      $thislink->suspect = 1;
      $thislink->update('suspect');
      $listresult .= linkreplacements($rowtemplate, $thislink);
    }
   }
   if ($badones == 0) $message .= "All links check out perfectly, none are down.";
   else $message .= "$badones links seem to be down:";
   $template->replace($rowtemplate, $listresult);
   if ($start > $numlinks)    $template->replace('{RELOAD}', '');
   $template->replace('{MESSAGE}', $message);
   $template->replace('{TYPE}', 'downcheck');   
   $nextstart = $nextstart - $badones;
   $template->replace('{START}', $nextstart);
   $template->replace('{PERPAGE}', $perpage);
   $template->replace('{SHOWDETAILS}', $showdetailsreplace);
  }
  else
  {
   $template->replace($rowtemplate, '');
   $message = "Since it can take a long time to grab a web page and the script may timeout if it tries to do to many at once, we must do this incrementally. Fill in the number of links you wish to check... if you've already checked x links, then start checking at #x.";
   $template->replace('{MESSAGE}', $message);
   $template->replace('{TYPE}', 'downcheck');   
   $template->replace('{START}', $nextstart);
   $template->replace('{PERPAGE}', $perpage);
   $template->replace('{SHOWDETAILS}', $showdetailsreplace);
  }
 }
  
 else if ($action=='recipcheck')
 {
  if ($filled)
  {
   if ($perpage != '') $nextstart = $start+$perpage; else $nextstart = 0;
   if ($perpage == '') $perpage = 20;
   if ($start == '') $start = 0;
   if ($ourcondition == '') $ourcondition = "type='recip' AND suspect=0 and alias=0";
   $getthem = $db->select('id,recipurl,recipwith', 'linkstable', $ourcondition, 'ORDER BY id ASC', "LIMIT $start,$perpage");
   $numlinks = $db->numrows($getthem);
   $myurl = $settings->myurl;
   $myurl = str_replace('http://', '', $myurl);
   $myurl = str_replace('www.', '', $myurl);
   $myurl = rtrim($myurl, '/');
   $origmyurl = $myurl;
   if ($oursearch != '') $myurl = $oursearch;
   $badones = 0;
   for ($count=0; $count<$numlinks; $count++)
   { 
    $row = $db->row($getthem);
    $id = $row[0];
    $url = $row[1];
    $recipwith = $row[2];
    if ($recipwith != '') 
    {
     $myurl = end(explode("/", $recipwith)); // in case site links with relative url, only look for the script.php?id=x part
    }
    else 
    {
     $myurl = $origmyurl;
    }
    if ($showdetails == 'on') echo "Checking $url (link id#$id) for $myurl... ";
    $filetest = geturl($url);
    $test = stristr($filetest, $myurl);
    if ($showdetails == 'on')
	{
	 if ($test) echo "OK <br>";
	 else
	 {
	  echo "NO RETURN LINK FOUND ";
	   if ($sendemails) echo "... sending email... ";
	   echo "<a href=edit.php?action=link&field=id&condition=equals&fieldvalue=$id><img src=../$templatesdir/images/edit.gif border=0></a><br>";
     }
	}
    if (!($test))
    {
     $badones++;
     $thislink = new onelink('dummy', $id);
     $thislink->suspect = 1;
     $thislink->update('suspect');
     $listresult .= linkreplacements($rowtemplate, $thislink);
     if (($sendemails) && ($thislink->email != ''))
     {
      $subject = linkreplacements($language->email_recipnotfoundsubject, $thislink);
      $message = linkreplacements($language->email_recipnotfoundbody, $thislink);
      mail($thislink->email, $subject, $message, "From: ". $settings->email);
     }
    }
   }
   if ($badones == 0)  $message = "All reciprocal links check out perfectly.";
   if ($start > $numlinks) $message = "You only have $numlinks reciprocal links... no more to check.";
   else $message = "$badones suspect reciprocal links:";
   $template->replace($rowtemplate, $listresult);
   $template->replace('{MESSAGE}', $message);
   $template->replace('{TYPE}', 'recipcheck');
   $nextstart = $nextstart - $badones;
   $template->replace('{START}', $nextstart);
   $template->replace('{RELOAD}', '');
   $template->replace('{PERPAGE}', $perpage);
   $template->replace('{SHOWDETAILS}', $showdetailsreplace);
   $template->replace('{SENDEMAILS}', $sendemailsreplace);   
  }
  else
  {
   $template->replace($rowtemplate, '');
   $message = "Since it can take a long time to grab a web page and the script may timeout if it tries to do to many at once, we must do this incrementally. Fill in the number of reciprocal links you wish to verify in this run through... if you've already checked x links, then start checking at #x.";
   $template->replace('{MESSAGE}', $message);
   $template->replace('{TYPE}', 'recipcheck'); 
   $template->replace('{START}', $nextstart);
   $template->replace('{PERPAGE}', $perpage);
   $template->replace('{SHOWDETAILS}', $showdetailsreplace);
   $template->replace('{SENDEMAILS}', $sendemailsreplace);
  }
 }
}

if (!$reload) $template->replace('{RELOAD}', '');
require 'adminend.php';

?>