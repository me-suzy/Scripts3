<?php
class member
{

 function member($type, $input)
 {
  global $settings, $db, $replacedfields, $admingroup, $skipvalidation, $debug, $newid, $newpassword, $newusergroup, $idcookiename, $passwordcookiename, $custommemberdb, $usergroupdata, $memberdataarray, $group4, $group5, $group6;  
  $memberfields = explode(',', $settings->memberfields);
  if ($type == 'row')
  {
   $row = $input;
   $num = sizeof($row);
   for ($count=0; $count<$num; $count++)
   {
	$this->$memberfields[$count] = $row[$count];
   }  
  }
  else if ($type == 'blank')
  {
   $num = sizeof($memberfields);
   for ($count=0; $count<$num; $count++)
   {
	$this->$memberfields[$count] = '';
   }  
   $this->id = 1;
  }
  else if ($type == 'new')
  {
   $num = sizeof($memberfields);
   for ($count=0; $count<$num; $count++)
   {
    global $$memberfields[$count];
   }
   for ($count=0; $count<$num; $count++)
   {
    $$memberfields[$count] = stripcode($$memberfields[$count]);
    if ($$memberfields[$count] == 'on')
	$this->$memberfields[$count] = 'yes';
    else if ($$memberfields[$count] == 'off')
	$this->$memberfields[$count] = 'no';
    else
	$this->$memberfields[$count] = encodeit($$memberfields[$count]);
   }    
  }
  else if ($type == 'id')
  {
   $id = $input;
   if ($memberdataarray[$id]) 
   {
    $this = $memberdataarray[$id];
   }
   else if ($id > 0)
   {
    $query = $db->select($settings->memberfields, 'memberstable', "$newid=$id", '', '');
    $row = $db->row($query);
    $num = sizeof($row);
    for ($count=0; $count<$num; $count++)
    {
	 $this->$memberfields[$count] = $row[$count];
    }
   }
   else
   {
    $row = explode(',', $settings->memberfields);
    $num = sizeof($row);
    for ($count=0; $count<$num; $count++)
    {
        $this->$memberfields[$count] = '';
	$this->usergroup = 1;
    }
   }  
  }  
  else
  {
   // generate from cookie data
   global $HTTP_COOKIE_VARS;
   if (!$passwordcookiename) $userpassword = $HTTP_COOKIE_VARS['wsnpass'];
   else 
   {
    $userpassword = $HTTP_COOKIE_VARS[$passwordcookiename];
	if ($userpassword == '') $userpassword = $HTTP_COOKIE_VARS['wsnpass'];
   }
   if (!$idcookiename) $id = $HTTP_COOKIE_VARS['wsnuser'];
   else 
   {
    $id = $HTTP_COOKIE_VARS[$idcookiename];
	if ($id == '') $id = $HTTP_COOKIE_VARS['wsnuser'];
   }
   if ($id == '')
   {
    $this->id = '';
	$this->usergroup = 1;
   }
   else
   {
    if (!$skipvalidation) $query = $db->select($settings->memberfields, 'memberstable', "$newid=$id AND validated=1", '', '');
	else
	{
	 $query = $db->select($settings->memberfields, 'memberstable', "$newid=$id", '', '');
	}
    $row = $db->row($query);
    $num = sizeof($memberfields);
    for ($count=0; $count<$num; $count++)
    {
     $this->$memberfields[$count] = $row[$count];
    }
    if ($userpassword != $this->$newpassword) { $this->id = 0; $this->name = ''; if ($newid) $this->$newid = 0; $this->$newname = ''; $this->password = ''; $this->usergroup = 1; $this->$newusergroup = 1; $skip = true; }
   }
  } 

  if (!$skip)
  {
  // For integration with other scripts, custom member DBs
  if ($custommemberdb)
  { 
   global $original;
   $oldfields = explode(',', $original);
   $n = sizeof($oldfields);
   for ($x=0; $x<$n; $x++)
   {
    $var = 'new'. $oldfields[$x];
    global $$var;
    if ($$var != '')
    {
     $this->$oldfields[$x] = $this->$$var;
    } 
   }
   if ($this->usergroup == $admingroup) $this->usergroup = 3;
   else if (($this->id == 0) || ($this->id == '')) $this->usergroup = 1;
   else if ($this->usergroup == 0) $this->usergroup = 2;
   else if ($this->usergroup == $group4) $this->usergroup = 4;
   else if ($this->usergroup == $group5) $this->usergroup = 5;
   else if ($this->usergroup == $group6) $this->usergroup = 6;
   else $this->usergroup = 2;
  }
  }
  $this->signature = encodeit($this->signature);
  if (($type != 'blank') && ($input != 'noid'))
  {
   $ugfields = explode(',', $settings->usergroupfields);
   $num = sizeof($ugfields);
   $id = $row[0];
   for ($count=0; $count<$num; $count++)
   {
    $item = $ugfields[$count];
    $var = 'group'. $item;
    $this->$var = $usergroupdata[$this->usergroup][$item];
   }
   $memberdataarray[$this->id] = $this;
  }
  if ($this->id == '') { $this->id = 0; $this->albumid = 0; }
 }

 function admin()
 {
  global $thismember, $language, $templatesdir;
  if ($thismember->canedit('member', $this->id))
  {
$path = '[IFADMINadmin/]edit.php?action=member&field=id&condition=equals&fieldvalue='. $this->id;
   $admin = str_replace('{PATH}', $path, $language->edit);
   $admin = str_replace('{TEMPLATESDIR}', '[IFADMIN../]'. $templatesdir, $admin);
  }
  else
  {
   $admin = '';
  }
  return $admin;
 }

 function login()
 { // login the user: set their cookies based on the data they've given'
  global $settings, $language, $inadmindir, $userpassword, $username, $db, $debug, $newid, $newname, $custommemberdb, $newpassword, $otherencoder, $connection, $newpassword, $newlastattempt, $newfailedattempts;
  $password = md5($userpassword);

  if (!$newid) $query = $db->select('id', 'memberstable', "name='$username'", '', '');
  else $query = $db->select($newid, 'memberstable', "$newname='$username'", '', '');
  $id = $db->rowitem($query);

  if ($otherencoder == 'yes') 
  {
   $thefile = '';
   if ($inadmindir) $thefile = '../';
   $thefile .= 'integration/'. $settings->integration .'encoder.php';
   if (!file_exists($thefile)) die("The encoder file $thefile does not exist!");
   require "$thefile"; 
  }

  if ($usesessions)
  {
   $session = session_id();
  }

  makecookie ('wsnuser', "$id", (time() + 3000000));
  makecookie ('wsnpass', "$password", (time() + 3000000));
  $query = $db->select("$newpassword,$newlastattempt,$newfailedattempts", 'memberstable', "$newname='$username'", '', '');
  $row = $db->row($query);
  $actual = $row[0];
  if (($debug > 0) && ($debug < 4)) echo "username is $username for user id number $id";
  $lastattempt = $row[1];
  if ($lastattempt > time()-5) { echo $language->login_mustwait; $actual = ''; }
  else $db->update('memberstable', "lastattempt", time(), "name='$username'");  
  if ($password != $actual) 
  {
   $attempts = $row[3];
   if ($attempts == 100) 
   { 
    global $prefix;
    $ip = getenv("REMOTE_ADDR");
    sendemail($settings->email, 'Member account locked', "There have been more than 100 failed attempts to access the account of the username $username within a 24 hour period. This indicates a likely automated 'brute force' hacking attack. The last attempt was from the IP address $ip. 
The member's account will become accessible again within the next 24 hours when the failed attempts are reset... to reactivate the account sooner, you can use phpmyadmin (or your 'advanced' page in WSN if this was not your admin account being locked) to run this query:
UPDATE ". $prefix ."members SET failedattempts = 0;", "From: ". $settings->email);
   }
   if ($attempts > 100) 
   {
    die("You have made more than 100 failed login attempts today. Since it is difficult to be that bad of a typist, the account has been locked for the rest of the day and your IP address has been sent to the site administrator.");
   }

   $db->update('memberstable', "failedattempts", 'failedattempts + 1', "name='$username'");
  }
  return ($password == $actual);
 }
 
 function register()
 {
  global $db, $settings, $language, $languagegroup,$newpassword,$newname;
  $memberstuff = $settings->memberfields;
  $memberlist = explode(',', $memberstuff);
  $num = sizeof($memberlist);
  $password = md5($this->password);
  $this->$newpassword = $password;
  for ($count=0; $count<$num; $count++)
  {
   $valuetoadd = "'";
   if (!$this->groupcanusehtml) $valuetoadd .= $this->$memberlist[$count];
   else $valuetoadd .= $this->$memberlist[$count];
   $valuetoadd .= "'";
   $valuelist .= $valuetoadd; 
   if ($count<($num-1)) $valuelist .= ',';
  }
  $result = $db->insert('memberstable', $memberstuff, $valuelist);
  $query = $db->select('id', 'memberstable', "$newname='". $this->name ."'", '', '');
  $id = $db->rowitem($query);
  setcookie('wsnuser', "$id", (time() + 3000000));
  setcookie('wsnpass', "$password", (time() + 3000000));
  global $origlang;
  $language = $origlang;
  $memberdata = '
	Name: '. $this->username .'
	E-mail: '. $this->email .'
	IP Address: '. $this->ip;  
  if (($settings->registration == 'validate') && ($settings->notify == 'yes')) sendemail($settings->email, $language->email_newsuggestiontitle, str_replace('{DIRURL}', $settings->dirurl, str_replace('{TYPE}', 'member', $language->email_newsuggestionbody)). '
'. $memberdata, "From: ". $settings->email); 
  if ($id != '') return true; else return false;
 }
 
 function logout()
 {
  setcookie('wsnuser', 'blah', (time() - 2592000));
  setcookie('wsnpass', 'blah', (time() - 2592000));
 }
 
 function isadmin()
 { // returns true if user is administrator
  return $this->groupisadmin;
 }
 
 function avatar()
 {
  return $this->avatarname;
 }

 function canpost()
 {
  // return false if member is not permited to post comments, true if they can
  global $db, $settings, $HTTP_SERVER_VARS;
  $canpost = $this->groupcansubmitcomments;
  if ($this->usergroup == 1)
  { // apply flood control to guests
   $theipaddress = $HTTP_SERVER_VARS['REMOTE_ADDR'];
   if ($theipaddress == '') $theipaddress = 'unknown';
   $query = $db->select('time', 'commentstable', "ip='$theipaddress'", 'ORDER BY time DESC', '');
   $latestbythisip = $db->rowitem($query);
   if ($latestbythisip > (time()-$settings->floodcheck)) $canpost = false;
  }  
  return $canpost;
 }
 
 function canvote()
 {
  // return false if member is not permited to rate links, true if they can
  global $db, $settings;
  return $this->groupcanvote;
 }

 function canemail()
 {
  // return false if member is not permited to email a link, true if they can
  global $db, $settings;
  return $this->groupcanemail;
 }

 function tempoptions()
 {
  global $settings, $inadmindir, $thismember;
  $selected = $this->template;
  if ($inadmindir) $path = '../';
  $path .= 'templates/';
  $test = explode(',', $settings->templates);
  if (in_array('templates', $test) && file_exists($path.'header.tpl')) $sets[] = 'templates'; 
  $sets = getsubdirectories($path);
  if (is_array($sets))
  {
   sort($sets);
   foreach ($sets as $dir)
   {
   if ( !strstr($dir, 'hide') && !strstr($dir, 'admin') && !strstr($dir, 'images') && !strstr($dir, 'style') && (!strstr($dir, 'adminonly') || $thismember->isadmin()) )
    {
     if (file_exists($path . $dir .'/header.tpl'))
     {
      if (!strstr($dir, 'templates')) $dir = 'templates/'. $dir;
      $thedir = str_replace('templates/', '', $dir);
      if ($dir == $selected) $tempoptions .= '<option value="'. $dir .'" selected>'. $thedir .'</option>';
      else $tempoptions .= '<option value="'. $dir .'">'. $thedir .'</option>';
     }
    }
   }
  }
  return $tempoptions;
 }

  function langoptions()
 {
  global $settings, $inadmindir, $thismember;
  $selected = $this->language;
  if ($inadmindir) $path = '../';
  $path .= 'languages/';
  $sets = getfiles($path, 'lng');
  if (is_array($sets))
  {
   sort($sets);
   foreach ($sets as $dir)
   {
    if ( !strstr($dir, 'hide') && ((!strstr($dir, 'adminonly') || $thismember->isadmin())) )
    {
	 $dir = str_replace('.lng', '', $dir);
     if ($dir == $selected) $langoptions .= '<option value="'. $dir .'" selected>'. $dir .'</option>';
     else $langoptions .= '<option value="'. $dir .'">'. $dir .'</option>'; 
    }
   }
  }
  return $langoptions;
 }
 
 function canedit($type, $id)
 {
  // return true if they can edit this, false otherwise
  // $type is the type: link, category, or comment. $id is the id of the link, cat or comment
  // this should be enough data to determine if member has permission
  global $db, $linkownerarray, $moderatorarray, $commentownerarray, $commentlinkarray, $linkdataarray, $pref;
  $canedit = false;
  if (!$isadmin)
  {
   if ($type == 'link')
   {
    if ($this->groupcaneditownlinks)
    {
     $owner = $linkownerarray[$id];
     if ($this->id == $owner) $canedit = true;
     $cat = $linkdataarray[$id]->catid;
     if ($cat != '')
     {
	  $mods = $moderatorarray[$cat];
     }
     if ($this->name != '') 
     {
      $mods = str_replace(', ', '', $mods);
      $modlist = explode(',', $mods);
      if (in_array($this->name, $modlist)) $canedit = true; 
 	 }
    }
    if ($this->groupcaneditalllinks) $canedit = true;	
    if ($pref == '{ENTRY')
    {
     if ($this->groupcaneditownentries)
     {
      $owner = $linkownerarray[$id];
      if ($this->id == $owner) $canedit = true;  
     }
     if ($this->groupcaneditallentries) $canedit = true;	
    }
   }
   else if ($type == 'category')
   {
    if ($this->groupcaneditowncategories)
    {
     $mods = $moderatorarray[$id];
     $mods = str_replace(', ', ',', $mods);
     $modlist = explode(',', $mods);
     if (in_array($this->name, $modlist)) $canedit = true; 
    }
    if ($this->groupcaneditallcategories) $canedit = true;
   }   
   else if ($type == 'comment')
   {
    if ($this->groupcaneditowncomments)
    {   
     $owner = $commentownerarray[$id];
     if ($this->id == $owner) $canedit = true;
     $linkid = $commentlinkarray[$id];
     $cat = $linkdataarray[$linkid]->catid;
     if ($cat != '')
     {
      $mods = $moderatorarray[$cat];
     }
     if ($this->name != '') 
     {
      $mods = str_replace(', ', '', $mods);
      $modlist = explode(',', $mods);
      if (in_array($this->name, $modlist)) $canedit = true;
     }	 
    }
    if ($this->groupcaneditallcomments) $canedit = true;
   }
   if ($type == 'member')
   {
    if ($this->groupcaneditownprofile)
    {   
     if ($this->id == $id) $canedit = true;
    }
    if ($this->groupcaneditallprofiles) $canedit = true;
   }
  }
  return $canedit;
 }
 
 function addlink()
 {
  global $scriptname;
  // incriment counter
  if ($scriptname == 'wsngallery')
  {
   $this->images += 1;
   $this->update('images');
  }
  else if ($scriptname == 'wsnlinks')
  {
   $this->links += 1;
   $this->update('links');
  }
  else if ($scriptname == 'wsnguest')
  {
   $this->entries += 1;
   $this->update('entries');
  }
  return true;
 }
  
 function addcomment()
 {
  global $newcomments;
  // incriment counter?
  $this->$newcomments += 1;
  $this->update($newcomments);
  return true;
 }
  
 function update($fields)
 {
  global $db, $settings, $language, $newid;
  if ($fields == 'all') $memberlist = explode(',', $settings->memberfields);
  else $memberlist = explode(',', $fields);
  $num = sizeof($memberlist);
  for ($count=0; $count<$num; $count++)
  {
   if (!$this->groupcanusehtml) $this->$memberlist[$count] = striphtml($this->$memberlist[$count]);
   $result = $db->update('memberstable', $memberlist[$count], encodeit($this->$memberlist[$count]), $newid .'='. $this->id);
  }
  return true;
 }

 function usergroupoptions($selected = '')
 {
  global $db;
  if (!$selected) $selected = $this->usergroup;
  $query = $db->select('id,title', 'membergroupstable', 'id>0', 'ORDER BY id ASC', '');
  $num = $db->numrows($query);
  for ($x=0; $x<$num; $x++)
  {
   $next = $db->row($query);
   if ($next[0] == $selected)
    $usergroupoptions .= '<option value="'. $next[0] .'" selected>'. $next[1] .'</option>';
   else
    $usergroupoptions .= '<option value="'. $next[0] .'">'. $next[1] .'</option>';
  }
  return $usergroupoptions;
 }

 function hassig()
 {
  if ($this->signature == '') return 0; 
  else return 1;
 }

 function allowuseremail()
 {
  if ($this->allowuseremail == 'no') return 0;
  else return 1;
 }
 
 function deletethis()
 {
  global $db;
  $id = $this->id;
  $result = $db->delete('memberstable', "id=$id");
  return $result;
 }
 
 function regdate()
 {
  global $settings;
  setlocale(LC_ALL, $settings->locale);  
  $dateformat = $settings->dateformat;
  $thedate = strftime("$dateformat", $this->time);
  if ($this->time == 0) $thedate = '';  
  return $thedate;
 }

 function hasemail()
 {
  if ($this->email == '') return 0;
  else return 1;
 }

 function isguest()
 {
  if ($this->usergroup == 1) return 1;
  else return 0;
 }

 function isregistered()
 {
  if ($this->usergroup > 1) return 1;
  else return 0;
 }

 function subscribedto($thread)
 {
  $thread = new onelink('id', $thread);
  if (strstr('|||'. $thread->notify .'|||', '|||'. $this->id .'|||')) return 1;
  else return 0;
 }
 
 function emaildisguise()
 {
  $change = str_replace('@', ' at ', $this->email);
  $change = str_replace('.', ' dot ', $change);
  return $change;
 }

 function menu()
 {
  // generates content for dropdown selector of members
  global $db, $settings, $newid, $newname;
  $selected = $this->id;
  $getall = $db->select("$newid,$newname", 'memberstable', 'validated=1', "ORDER BY $newname ASC", '');
  $num = $db->numrows($getall);
  for ($x=0; $x<$num; $x++)
  {
   $row = $db->row($getall);
   if ($row[0] == $selected) $options .= '<option value="'. $row[0] .'" selected>'. $row[1] .'</option>';
   else $options .= '<option value="'. $row[0] .'">'. $row[1] .'</option>';
  }
  return $options;
 }

 
}

?>