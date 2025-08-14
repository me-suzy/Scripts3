<?php

class onelink
{
 function onelink($type, $input)
 { // constructor
  global $db, $settings, $linkownerarray, $templatesdir, $allcatdata, $linkfields, $noaliasing, $linkdataarray; 
  if ($linkfields != '') $settings->linkfields = $linkfields;
  $linklist = explode(',', $settings->linkfields);  

  if ($type == 'row')
  {
   $row = $input;
   $num = sizeof($row);
   for ($count=0; $count<$num; $count++)
   {
	$this->$linklist[$count] = stopvars($row[$count]);
   }  
  }
  else if ($type == 'blank')
  {
   $num = sizeof($linklist);
   for ($count=0; $count<$num; $count++)
   {
	$this->$linklist[$count] = '';
   }  
   $this->url = 'http://';
   $this->recipurl = 'http://';
   $this->id = 1;
  }
  else if ($type == 'new')
  {
   $num = sizeof($linklist);
   for ($count=0; $count<$num; $count++)
   {
    global $$linklist[$count];
   }
   if ($type == '') $type = 'regular'; // prevent invisible links
   for ($count=0; $count<$num; $count++)
   {
    $$linklist[$count] = stripcode($$linklist[$count]);
    if ($$linklist[$count] == 'on')
	$this->$linklist[$count] = 'yes';
    else if ($$linklink[$count] == 'off')
	$this->$linklist[$count] = 'no';
    else
	$this->$linklist[$count] = encodeit(stopvars($$linklist[$count]));
   }  
  }   
  else
  {
   $linkid = $input;
   if ($linkdataarray[$linkid])
   {
    $this = $linkdataarray[$linkid];
   }
   else
   {
    $linkdata = $db->select($settings->linkfields, 'linkstable', "id=$linkid", '', '');
    $row = $db->row($linkdata);
    $num = sizeof($row);
    for ($count=0; $count<$num; $count++)
    {
 	 $this->$linklist[$count] = stopvars($row[$count]);
    }
   }
  }

  if ($type != 'blank' && $type != 'new' && !$noaliasing)
  {
   if ($this->alias > 0)
   {
    $this->id = $this->alias;
    $borrow = new onelink('id', $this->alias);
    $num = sizeof($linklist);
    for ($x=0; $x<$num; $x++)
    {
     $field = $linklist[$x];
     if ($field != 'catid' && $field != 'alias')
	 {
	  $this->$field = stopvars($borrow->$field);
	 }
    }
   }
  }


  if (($type != 'new') && ($type != 'blank')) $linkownerarray[$this->id] = $this->ownerid;

  // extract data from custom rating fields
  $opts = explode(',', $settings->linkfields);
  $n = sizeof($opts);
  for ($a=0; $a<$n; $a++)
  {
   if (strstr($opts[$a], 'rating'))
   {
    $content = explode('[,]', $this->$opts[$a]);
    $v = $opts[$a] .'votes';
    $s = $opts[$a] .'sumofvotes';
    $r = $opts[$a] .'rating';
    $t = $opts[$a] .'stars';
    $this->$v = $content[0];
    $this->$s = $content[1];
    if ($content[0] > 0) $this->$r = round(((($content[1]) / ($settings->maxvote * $content[0])) * $settings->maxvote), $settings->ratingdecimal); else $this->$r = 0;
    $this->$t = '<img src="[IFADMIN../]'. $templatesdir .'/images/stars'. round($this->$r,0) .'.gif" alt="'. round($this->$r,0) .'">';
    $settings->customratinglinkfields .= ','. $v .','. $s .','. $r .','. $t;
   } 
  }

  if ($this->votes > 0) $this->rating = round(((($this->sumofvotes) / ($settings->maxvote * $this->votes)) * $settings->maxvote), $settings->ratingdecimal);      
  else $this->rating = 0;
 $this->stars = '<img src="[IFADMIN../]'. $templatesdir .'/images/stars'. round($this->rating,0) .'.gif" alt="'. round($this->$rating,0) .'">';
  $this->kb = round(($this->filesize / 1024), 0);

  for ($q=0; $q<sizeof($linkfields); $q++)
  {
   if (strstr($this->$linkfields[$q], '|||') && $linkfields[$q] != 'notify') $this->$linkfields[$q] = str_replace('|||', ', ', $this->$linkfields[$q]);
  }
  
  if ($this->catid > 0)
  {
   if ($allcatdata[$this->catid]->isalbum)
    $this->albumname = $allcatdata[$this->catid]->name;
  }
  
  if ($type != 'new' && $type != 'blank' && $this->alias == 0) $linkdataarray[$this->id] = $this;

 }
 
 function update($fields)
 {
  global $db, $settings, $language, $thismember;
  if ($fields == 'all') $linklist = explode(',', $settings->linkfields);
  else $linklist = explode(',', $fields);
  $num = sizeof($linklist);
  for ($count=0; $count<$num; $count++)
  {
   if ($linklist[$count] == 'hide')
   {
    if ($this->hide == 'yes') $this->hide = 1;
    if ($this->hide == 'no') $this->hide = 0;	
	$this->hidealiases($this->hide);
   }
   if (!$thismember->groupcanusehtml) $this->$linklist[$count] = striphtml($this->$linklist[$count]);
   $result = $db->update('linkstable', $linklist[$count], encodeit($this->$linklist[$count]), 'id='. $this->id);
  }
  global $oldcatid;
  if (($oldcatid != '') && ($oldcatid != $this->id)) { updatelinktotals($this->catid); updatelinktotals($oldcatid); }
  return true;
 }
 
 function hidealiases($hidevalue)
 {
  global $db;
  $query = $db->select('id', 'linkstable', "alias=". $this->id, '', '');
  $num = $db->numrows($query);
  for ($x=0; $x<$num; $x++)
  {
   $nextid = $db->rowitem($query);
   $db->update('linkstable', 'hide', $hidevalue, "id=$nextid");
  }
 }
 
 function add()
 {
  global $db, $settings, $language, $aliasing, $thismember;
  $linkstuff = $settings->linkfields;
  $linklist = explode(',', $linkstuff);
  $this->id = ''; // be sure to leave it to the autoincriment
  $num = sizeof($linklist);
  for ($count=0; $count<$num; $count++)
  {
   $starttoadd = "'";
   if (!$thismember->groupcanusehtml) $middletoadd = striphtml($this->$linklist[$count]);
   else $middletoadd = $this->$linklist[$count];
   if ($linklist[$count] == 'time') $middletoadd = time();
   if ($linklist[$count] == 'lastedit') $middletoadd = time();
   $endtoadd = "'";
   $valuetoadd = $starttoadd . $middletoadd . $endtoadd;
   if ($count < ($num - 1)) $valuelist .= $valuetoadd .','; 
   else $valuelist .= $valuetoadd;
  }
  $result = $db->insert('linkstable', $settings->linkfields, $valuelist);
  $adminaddress = $settings->email;
  if ( ($settings->notify == 'yes') && ($this->validated != 1) && ($adminaddress != '') && (!$aliasing))
  {
   global $scriptname;
   if ($scriptname == 'wsnlinks')
   $linkdata = '
	Title: '. $this->title .'
	URL: '. $this->url .'
	Description: '. $this->description .'
	Category: '. $this->catname() .'
	Type: '. $this->type .'
	Reciprocal URL: '. $this->recipurl;
   else if ($scriptname == 'wsnmanual')
   $linkdata = '
	Title: '. $this->title .'
	Description: '. $this->description .'
	Category: '. $this->catname() .'
	Type: '. $this->type .'
	Full text:
 '. $this->text;
   $q = $db->select('id', 'linkstable', 'time='. $this->time, '', '');
   $this->id = $db->rowitem($q);
   $subject = $language->email_newsuggestiontitle;
   $message = $language->email_newsuggestionbody;
   $message = str_replace('{TYPE}', 'link', $message); 
   $message = str_replace('{DIRURL}', $settings->dirurl, $message);
   $message = linkreplacements($message, $this);
   sendemail("$adminaddress", "$subject", $message .'
   '. $linkdata, "From: $adminaddress");
  } 
  
  if ($this->validated != 0)
  {
   // If this is validated, we need to update all the related category link totals
   $category = $this->catid;
   updatelinktotals($category);
   $settings->totallinks += 1;
   $settings->uniquetotal += 1;
   $settings->update('totallinks,uniquetotal');    
   $settings->lastupdate = time(); 
   $settings->update('lastupdate');
  } 
  return true;
 }
 
 function deletethis($noalias = '')
 {
  global $db, $settings;
  $id = $this->id;
  $result = $db->delete('linkstable', "id=$id");
  $result = $db->delete('commentstable', "linkid=$id"); // delete all of this link's comments
  if ($this->filename != '') 
  {
   @unlink($settings->uploadpath . $this->filename); // delete attachment from disk
   @unlink($settings->uploadpath . 'thumb_'. $this->filename); // delete thumbnail if applicable
   }
   updatelinktotals($this->catid);
   $mem = new member('id', $this->ownerid);
   $mem->totalbytes -= $this->filesize;
   $mem->update('totalbytes');
   $settings->totallinks -= 1;
   $settings->uniquetotal -= 1;
   $settings->update('totallinks,uniquetotal');
   // get rid of aliases
   if ($noalias != 'noalias')
   {
    $q = $db->select('id,catid', 'linkstable', 'alias='. $this->id, '', '');
    $n = $db->numrows($q);
    for ($x=0; $x<$n; $x++)
    {
     $row = $db->row($q);
     $db->delete('linkstable', 'id='. $row[0]);
     $cat = new category('id', $row[1]);
     $cat->numlinks -= 1;
     $cat->update('numlinks');
    }
   }
   return $result;
  }

 function reject($reason)
 {
  global $db, $settings, $language;
  $id = $this->id;
  if ($this->email == '' && $this->ownerid > 0) { $mem = new member('id', $this->ownerid); $thisemail = $mem->email; }
  else $thisemail = $this->email;
  if ($thisemail != '')
  {
   $subject = linkreplacements($language->email_rejectsubject, $this);
   $message = linkreplacements($language->email_rejectbody, $this);
   $message = str_replace('{REASON}', $reason, $message);
   sendemail($thisemail, $subject, $message, 'From: '. $settings->email);
  }
  $result = $db->delete('linkstable', "id=$id");
  $result = $db->delete('commentstable', "linkid=$id"); // delete all of this link's comments
  return $result;
 }
 
 function validate()
 { 
  global $db, $settings;
  $this->validated = 1;  
  sendvalidationemail($this);  
  $this->update('validated');
  updatelinktotals($this->catid);
  // update category lastlinktime
  $thiscat = new category('id', $this->catid);
  $thiscat->lastlinktime = time();
  $thiscat->update('lastlinktime');
  $parentlist = $thiscat->id;
  if ($thiscat->parentids != '') $parentlist .= '|||'. $thiscat->parentids;
  $parentarray = explode('|||', $parentlist);
  $num = sizeof($parentarray);
  for ($count=0; $count<$num; $count++)
    $updateit = $db->update('categoriestable', 'lastlinktime', time(), 'id='. $parentarray[$count]);
  // end update cat lastlinktime
  $settings->lastupdate = time(); 
  $settings->uniquetotal += 1;
  $settings->update('lastupdate,uniquetotal');
  return true;
 }
 
 function subscribe($email)
 {
  global $language;
  $old = 'visit http://www.philosophyforums.com';
  if (!strstr($email, '@')) { $mem = new member('id', $email); if ($mem->email != '') $old = $mem->email; }
  if (!strstr($this->notify, '|||'. $email .'|||') && !strstr($this->notify, '|||'. $old .'|||'))
  {
   $this->notify .= '|||'. $email .'|||';
   $message = $language->comments_subscribe;
  }
  else
  {
   if (strstr($this->notify, $email)) $this->notify = str_replace('|||'. $email .'|||', '', $this->notify);
   else $this->notify = str_replace('|||'. $old .'|||', '', $this->notify);
   $message = $language->comments_unsubscribe;
  }
  $this->update('notify');
  return $message;
 }
 
 
  // The functions below are simply for use as template variables, nothing else \\
 
 
 function date()
 {
  global $settings;
  setlocale(LC_ALL, $settings->locale);  
  $dateformat = $settings->dateformat;
  $thedate = strftime("$dateformat", $this->time);
  if ($this->time == 0) $thedate = '';
  return $thedate;
 }

 function bookmarktitle()
 {
  $title = str_replace('&#34;', '', $this->title);
  $title = str_replace('&#39;', '', $title);
  return $title;
 }
 
 function typeselector()
 {
  return typeselector($this->type);
 }

 function isnew()
 {
  return marknew($this->time);
 }
 
 function hideselector()
 {
  return yesno($this->hide);
 }

 function updated()
 {
  return $this->isupdated();
 }

 function isupdated()
 {
  global $settings, $inadmindir, $templatesdir;
  $time = $this->lastedit;
  $maxdaysago = $settings->marknewupdates;
  $maxtimeago = $maxdaysago * 24 * 60 * 60;
  $compare = time() - $time;
  if (($compare < $maxtimeago) && ($time > 0) && ($this->lastedit > $this->time))
  {
   $new = '<img src="';
   if ($inadmindir) $new .= '../';
   $new .= $templatesdir .'/images/updated.gif" alt="">';
  }
  else
    $new = '';
  return $new;
 }

 
 function admin()
 {
  global $thismember, $language, $incomplete, $templatesdir;
  if ((!$incomplete) && ($thismember->canedit('link', $this->id)))
  {
   $path = '[IFADMINadmin/]edit.php?action=link&amp;field=id&amp;condition=equals&amp;fieldvalue='. $this->id;
   $admin = str_replace('{PATH}', $path, $language->edit);
   $admin = str_replace('{TEMPLATESDIR}', '[IFADMIN../]'. $templatesdir, $admin);
  }
  else
  {
   $admin = '';
  }
  return $admin;
 }
 
 function expiredays()
 {
  // returns number of days left until this link expires
  if ($this->expire == 0) return false;
  else
  {
   $timeleft = $this->expire - time();
   $days = round(($timeleft / 86400), 3);
   return $days;
  }
 }
 
 function inalbum()
 {
  $test = new category('id', $this->catid);
  return $test->isalbum;
 }

 function hitsoutperday()
 {
  $x = time() - $this->time;
  $days = $x / 86400;
  $hpd = $this->hits / $days;
  return round($hpd, 0);  
 }

 function hitsoutpermonth()
 {
  $x = time() - $this->time;
  $months = $x / 2592000;
  $hpd = $this->hits / $months;
  return round($hpd, 0);  
 }

 function hitsinperday()
 {
  $x = time() - $this->time;
  $days = $x / 86400;
  $hpd = $this->hitsin / $days;
  return round($hpd, 0);  
 }

 function hitsinpermonth()
 {
  $x = time() - $this->time;
  $months = $x / 2592000;
  $hpd = $this->hitsin / $months;
  return round($hpd, 0);  
 }
 
 function daysold()
 {
  $x = time() - $this->time;
  $days = $x / 86400;
  return round($days, 0);
 }

 function catname()
 {
  global $db;
  $catid = $this->catid;
  $getit = $db->select('name', 'categoriestable', "id=$catid", '', '');
  $row = $db->row($getit);
  $thename = $row[0];
  return $thename;
 }
 
 function lasteditdate()
 {  
  global $settings;
  setlocale(LC_ALL, $settings->locale); 
  $dateformat = $settings->dateformat;
  $lasteditdate = strftime("$dateformat", $this->lastedit);
  if (($this->lastedit == 0) || ($this->lastedit == $this->time)) $lasteditdate = '';
  return $lasteditdate; 
 }

 function kb()
 {
  global $settings;
  if ($this->filesize > 0) $kb = round(($this->filesize / 1024), 0);
  else $kb = round((filesize($settings->uploadpath . $this->filename) / 1024), 0);
  return $kb;
 }

 function nav()
 {
  $cat = new category('id', $this->catid);
  return $cat->nav();
 } 

 function baseurl()
 {
  $urlparts = parse_url($this->url);
  $base = $urlparts[scheme].'://'.$urlparts[host];
  return $base;
 }

 function candelete()
 {
  global $thismember;
  $candelete = '0';
  if ($thismember->groupcandeleteall) $candelete = '1';
  if ($thismember->groupcandeleteown && ($thismember->id == $this->ownerid)) $candelete = '1';
  return $candelete;
 }

 function descriptionsentence()
 {
  $temp = explode('. ', $this->description .'. ');
  return $temp[0] .'. ';
 }


 function description($length = 0)
 {
  global $language;
  if ($length > 0)
  {
   if (strlen($this->description) > $length)
   {
    $descrip = trimtochars($this->description, $length);
    $descrip .= $language->cutoff;
   }
   else $descrip = $this->description;
  }
  else $descrip = $this->description;
  return $descrip;
 }

 function text($length = 0)
 {
  global $language;
  if ($length > 0)
  {
   if (strlen($this->text) > $length)
   {
    $descrip = trimtochars($this->text, $length);
    $descrip .= $language->cutoff;
   }
   else $descrip = $this->text;
  }
  else $descrip = $this->text;
  return $descrip;
 }

}

?>