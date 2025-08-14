<?php

class comment
{
 
 function comment($type, $input)
 { // constructor
  global $db, $settings, $commentownerarray, $commentfields, $commentlinkarray; 
  if ($commentfields != '') $settings->commentfields = $commentfields;
  $commentslist = explode(',', $settings->commentfields);  
  if ($type == 'row')
  {
   $row = $input;
   $num = sizeof($row);
   for ($count=0; $count<$num; $count++)
   {
	$this->$commentslist[$count] = $row[$count];
   }  
  }
  else if ($type == 'blank')
  {
   $num = sizeof($commentslist);
   for ($count=0; $count<$num; $count++)
   {
	$this->$commentslist[$count] = '';
   }  
   $this->id = 1;
  }
  else if ($type == 'new')
  {
   $this->id='0';
   $num = sizeof($commentslist);
   for ($count=0; $count<$num; $count++)
   {
    global $$commentslist[$count];
   }
   for ($count=0; $count<$num; $count++)
   {
	$this->$commentslist[$count] = encodeit($$commentslist[$count]);
    if (($commentslist[$count] == 'posterid') && ($posterid == '')) $this->posterid = '0';   	
   }  
  }   
  else
  {
   $id = $input;
   if ($id > 0)
   {
    $comdata = $db->select($settings->commentfields, 'commentstable', "id=$id", '', '');
    $row = $db->row($comdata);
    $num = sizeof($row);
    for ($count=0; $count<$num; $count++)
    {
 	 $this->$commentslist[$count] = $row[$count];
    }
   }
  } 
  $this->text = stopvars($this->text);
  $this->message = stopvars($this->message);
  if (($type != 'new') && ($type != 'blank')) { $commentownerarray[$this->id] = $this->ownerid; $commentlinkarray[$this->id] = $this->linkid; }
 }
 
 function add()
 {
  global $db, $settings, $language, $thismember;
  $commentstuff = $settings->commentfields;
  $commentlist = explode(',', $commentstuff);
  $num = sizeof($commentlist);
  for ($count=0; $count<$num; $count++)
  {
   $valuetoadd = "'";
   if (!$thismember->groupcanusehtml) $valuetoadd .= striphtml($this->$commentlist[$count]);
   else $valuetoadd .= $this->$commentlist[$count];
   if ($commentlist[$count] == 'time') $valuetoadd .= time();
   if ($commentlist[$count] == 'lastedit') $valuetoadd .= time();
   $valuetoadd .= "'";
   $valuelist .= $valuetoadd; 
   if ($count<($num-1)) $valuelist .= ',';
  }
  $result = $db->insert('commentstable', $commentstuff, $valuelist);
  // we'd better update the comment count for the link associated with this
  $id = $this->linkid;
  $query = $db->select('id', 'commentstable', "linkid=$id AND validated=1 AND hide=0", '', '');
  $num = $db->numrows($query);
  $result = $db->update('linkstable', 'numcomments', $num, "id=$id");

  $aliases = $db->select($settings->linkfields, 'linkstable', 'alias='. $id, '', '');
  $n = $db->numrows($aliases);
  for ($x=0; $x<$n; $x++)
  {
   $alias = new onelink('row', $db->row($aliases));
   $alias->numcomments = $num;
   $alias->update('numcomments');
  }
  if ($this->validated == 1) { $settings->totalcomments += 1; $settings->update('totalcomments'); }
  $query = $db->select('id', 'commentstable', "linkid=$id", '', '');
  $this->id = $db->rowitem($query);  
  $adminaddress = $settings->email;
  if ( ($settings->notify == 'yes') && ($this->validated != 1) && ($settings->email != '') )
  {  
   $comdata = '
	Poster Name: '. $this->postername .'
	Message: '. $this->message;
   $subject = commentreplacements($language->email_newsuggestiontitle, $this);
   $message = commentreplacements($language->email_newsuggestionbody, $this);
   $message = str_replace('{TYPE}', 'comment', $message);
   sendemail("$adminaddress", "$subject", $message . $comdata, "From: $adminaddress");
  }  
  return true;
 }
 
 function update($fields)
 {
  global $db, $settings, $language;
  if ($fields == 'all') $commentlist = explode(',', $settings->commentfields);
  else $commentlist = explode(',', $fields);
  $num = sizeof($commentlist);
  for ($count=0; $count<$num; $count++)
  {
   $value = encodeit($this->$commentlist[$count]);
   if (!$thismember->groupcanusehtml) $value = striphtml($value);
   if ($commentlist[$count] == 'hide') { if ($this->$commentlist[$count] == 'yes') $value = 1; if ($this->$commentlist[$count] == 'no') $value = 0; }
   $result = $db->update('commentstable', $commentlist[$count], $value, 'id='. $this->id);
  }
  if (strstr($fields, 'validated'))
  {
   $id = $this->linkid;
   $query = $db->select('id', 'commentstable', "linkid=$id AND validated=1", '', '');
   $num = $db->numrows($query);
   $result = $db->update('linkstable', 'numcomments', $num, "id=$id");  
  }
  return true;
 }
 
 function deletethis()
 {
  global $db;
  $id = $this->id;
  $result = $db->delete('commentstable', "id=$id");
  // we'd better update the comment count for the link associated with this
  $id = $this->linkid;
  $query = $db->select('id', 'commentstable', "linkid=$id AND validated=1", '', '');
  $num = $db->numrows($query);
  $result = $db->update('linkstable', 'numcomments', $num, "id=$id");
  if ($this->ownerid > 0)
  {
   $theowner = new member('id', $this->ownerid);
   $theowner->comments -= 1;
   $theowner->update('comments');
  }
  return $result;
 }

 function admin()
 {
  global $thismember, $language, $templatesdir;
  if ($thismember->canedit('comment', $this->id))
  {
   $path = '[IFADMINadmin/]edit.php?action=comment&amp;field=id&amp;condition=equals&amp;fieldvalue='. $this->id;
   $admin = str_replace('{PATH}', $path, $language->edit);
   $admin = str_replace('{TEMPLATESDIR}', '[IFADMIN../]'. $templatesdir, $admin);
  }
  else
  {
   $admin = '';
  }
  return $admin;
 }

 function typeoptions($selectedone = '')
 {
  global $settings, $language;
  if ($selectedone == '') $selectedone = $this->type;
  if ($language->commenttypes != '') $namesarray = explode(',', $language->commenttypes);
  else $namesarray = explode(',', $settings->commenttypes);
  $typearray = explode(',', $settings->commenttypes);
  $num = sizeof($typearray);
  for ($c=0; $c<$num; $c++)
  {
   if ($typearray[$c] == $selectedone)
    $typeoptions .= '<option value="'. $typearray[$c] .'" selected>'. $namesarray[$c] .'</option>';
   else
    $typeoptions .= '<option value="'. $typearray[$c] .'">'. $namesarray[$c] .'</option>';
  }
  return $typeoptions;
 }
 
 function preview($length = 50)
 {
  if (strlen($this->message) < $length) return $this->message;
  else return trimtochars($this->message, $length) .'...';
 }
 
 function date()
 {
  global $settings;
  setlocale(LC_ALL, $settings->locale);  
  $dateformat = $settings->commentsdateformat;
  $thedate = strftime("$dateformat", $this->time);
  return $thedate;
 }

 function lasteditdate()
 {
  global $settings;
  setlocale(LC_ALL, $settings->locale);  
  $dateformat = $settings->commentsdateformat;
  $thedate = strftime("$dateformat", $this->lastedit);
  if (($this->lastedit == '') || ($this->lastedit == $this->time)) $thedate = '';
  return $thedate;
 }

 function disapproved()
 {
  $bad = $this->votes - $this->approved;
  return $bad;
 }

 function validate()
 {
  global $language, $settings, $db;
  $this->validated = 1;
  $this->update('validated');
  // we'd better update the comment count for the link associated with this
  $id = $this->linkid;
  $query = $db->select('id', 'commentstable', "linkid=$id AND validated=1 AND hide=0", '', '');
  $num = $db->numrows($query);
  $result = $db->update('linkstable', 'numcomments', $num, "id=$id");
  $result = $db->update('linkstable', 'numcomments', $num, "alias=$id");
  // send emails to subscribers
  if ($settings->email != '')
  {
   $lin = new onelink('id', $id);
   $subscribers = explode('|||', $lin->notify);
   $num = sizeof($subscribers);
   for ($x=0; $x<$num; $x++)
   {
    if (strstr($subscribers[$x], '@')) $person = $subscribers[$x];
    else 
    {
     $mem = new member('id', $subscribers[$x]);
     $person = $mem->email;
    }
    if ($person != '')
    {
     $subject = linkreplacements($language->email_notifycommentsubject, $lin);
     $message = linkreplacements($language->email_notifycommentbody, $lin);
     $message = commentreplacements($message, $this);
     sendemail($person, $subject, $message, "From: ". $settings->email);
    }
   }
  }
  return true;
 }
 

 function message($length = 0)
 {
  global $language;
  if ($length > 0)
  {
   if (strlen($this->text) > $length)
   {
    $descrip = trimtochars($this->message, $length);
    $descrip .= $language->cutoff;
   }
   else $descrip = $this->message;
  }
  else $descrip = $this->message;
  return $descrip;
 }
 
}

?>