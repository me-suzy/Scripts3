<?php

class category
{
   
 function category($type, $input)
 { // constructor: (new, x) makes new out of global vars, (x, catid) creates for cat3catid
  global $db, $settings, $moderatorarray, $allcatdata, $language; 
  $categorylist = explode(',', $settings->categoryfields);  
  
  if ($type == 'row')
  {
   $row = $input;
   $num = sizeof($row);
   for ($count=0; $count<$num; $count++)
   {
	$this->$categorylist[$count] = $row[$count];
   }  
  }
  else if ($type == 'blank')
  {
   $num = sizeof($categorylist);
   for ($count=0; $count<$num; $count++)
   {
	$this->$categorylist[$count] = '';
   }  
   $this->id = 1;
  }
  else if ($type == 'empty')
  {
   // don't want any data here, so that {CATs not filled in won't be replaced
  }
  else if ($type == 'new')
  {
   $num = sizeof($categorylist);
   if ($id = '') $id = 0;
   for ($count=0; $count<$num; $count++)
   {
    global $$categorylist[$count];
   }
   for ($count=0; $count<$num; $count++)
   {
    if ($categorylist[$count] == 'headerinfo') 	$this->$categorylist[$count] = encodeit($$categorylist[$count]);
    else $this->$categorylist[$count] = encodeit(stripcode($$categorylist[$count]));
   }  
  }   
  else
  {
   $catid = $input;
   if ($catid > 0)
   {
    if ($allcatdata[$catid])
	{
	 $this = $allcatdata[$catid];
	}
	else
	{
     $catdata = $db->select($settings->categoryfields, 'categoriestable', "id=$catid", '', '');
     $row = $db->row($catdata);
     $num = sizeof($row);
     for ($count=0; $count<$num; $count++)
     {
 	  $this->$categorylist[$count] = $row[$count];
     }
	}
   }
   else
   { 
    $num = sizeof($categorylist); 
    for ($count=0; $count<$num; $count++)
    {
 	 $this->$categorylist[$count] = '';
    } 
    $this->id = $catid;
	$this->headerinfo = ' ';
    $this->name = $language->toplevel;		
   }
  }
  if (($type != 'new') && ($type != 'blank'))
  {
   $moderatorarray[$this->id] = $this->findmoderators();
   if ($input != 'noid') $allcatdata[$this->id] = $this;
  }
 }
 
 function add()
 {
  global $db, $settings, $language, $thismember;
  $this->id = ''; // be sure we let autoincriment handle id assignment
  // generate parentids and parentnames for navigation
  $parentid = $this->parent;
  if ($parentid != 0)
  {
  $query = $db->select('id,name,parent', 'categoriestable', "id=$parentid AND validated=1", '', '');
  $parentdata = $db->row($query);
  $parentids[0] = $parentdata[0];
  $parentnames[0] = $parentdata[1];
  if ($parentids[0] != 0)
  { // hey, we're in a subcategory
     for ($count=1; $parentdata[0] != 0; $count++)
   { 
    $query = $db->select('id,name,parent', 'categoriestable', 'id='. $parentdata[2] .' AND validated=1', '', '');
    $parentdata = $db->row($query);
	if ($parentdata[0] != 0)
	{
     $parentids[$count] = $parentdata[0];	
     $parentnames[$count] = $parentdata[1];
	}
   }
  }
  $this->parentids = implode('|||', $parentids);
  $this->parentnames = implode('|||', $parentnames);
  }
  // end nav generation
 
  $categorystuff = $settings->categoryfields;
  $categorylist = explode(',', $categorystuff);
  $num = sizeof($categorylist);
  for ($count=0; $count<$num; $count++)
  {
   $valuetoadd = "'";
   if (!$thismember->groupcanusehtml && $categorylist[$count] != 'headerinfo') $valuetoadd .= striphtml($this->$categorylist[$count]);
   else $valuetoadd .= $this->$categorylist[$count];
   if ($categorylist[$count] == 'time') $valuetoadd .= time();
   if ($categorylist[$count] == 'lastedit') $valuetoadd .= time();
   if ($valuetoadd == '') $valuetoadd = "''";
   $valuetoadd .= "'";
   $valuelist .= $valuetoadd; 
   if ($count<($num-1)) $valuelist .= ',';
  }
  $result = $db->insert('categoriestable', $settings->categoryfields, $valuelist);
  updatecategoryselector(); 
  return true;
 }

 function nav()
 {
  global $language, $inadmindir;
  if ($inadmindir) $isadm = '../';
  $parentnames = explode('|||', $this->parentnames);
  $parentids = explode('|||', $this->parentids);
  $num = sizeof($parentnames);
  for ($count=($num-1); $count>=0; $count--) // pull out backwards, as they were put in backwards
  {
   if (($num > 0) && ($parentids[0] != '')) $nav .= $language->nav_separator .'<a href="index.php?action=displaycat&amp;catid='. $parentids[$count] .'">'. $parentnames[$count] .'</a>';
  }
  $navigation = $nav. $language->nav_separator . '<a href="'. $isadm .'index.php?action=displaycat&amp;catid='. $this->id .'">'. $this->name .'</a>';
  if ($num=='0') $navigation = $language->nav_separator . '<a href="'. $isadm .'index.php?action=displaycat&amp;catid='. $this->id .'>'. $this->name .'</a>';
  return $navigation;
 }
 
 function subscribe($id)
 {
  if (strstr($this->subscribers, '|||'. $id. '|||'))
  { // unsubscribe
   $this->subscribers = str_replace('|||'. $id .'|||', '', $this->subscribers);
  }
  else
  {
   $this->subscribers .= '|||'. $id . '|||';
  }
  $this->update('subscribers');
  return true;
 }

 function sendsubscriptions()
 {
  global $settings, $language;
  $subscribers = explode('|||', $this->subscribers);
  $num = sizeof($subscribers);
  for($x=0; $x<$num; $x++)
  {
   if ($subscribers[$x] != '')
   { 
    $mem = new member('id', $subscribers[$x]);
    if ($mem->email != '')
    {
     $subject = memberreplacements(categoryreplacements($language->email_catsubscribesubject, $this), $mem);
     $message = memberreplacements(categoryreplacements($language->email_catsubscribebody, $this), $mem);
     sendemail($mem->email, $subject, $message, "From: ". $settings->email);
    }
   }
  }
  // find my parents and send to their subscribers also 
  $parentlist = explode('|||', $this->parentids);
  $num = sizeof($parentlist);
  for ($x=0; $x<$num; $x++)
  {
   if ($parentlist[$x] != '')
   {
    $thisone = new category('id', $parentlist[$x]);
    $thisone->sendsubscriptions();
   }
  }
  return true;
 }

 function orderlinks()
 {
  global $settings;
  $selected = $this->orderlinks;
  $linkfields = explode(',', $settings->linkfields);
  sort($linkfields);
  $num = sizeof($linkfields);
  for ($count=0; $count<$num; $count++)
  {
   $field = $linkfields[$count];
   $fieldfull = "ORDER BY $field ASC";
   $english = "By $field ascending";
   if ($fieldfull == $selected) $isselected = " selected"; else $isselected = '';
   $linksoptions .= '<option value="'. $fieldfull .'"'. $isselected .'>'. $english .'</option>';
   $fieldfull = "ORDER BY $field DESC";
   $english = "By $field descending";   
   if ($fieldfull == $selected) $isselected = " selected"; else $isselected = '';
   $linksoptions .= '<option value="'. $fieldfull .'"'. $isselected .'>'. $english .'</option>';
  }
  return $linksoptions;
 }
  
 function deletethis()
 {
  global $db;
  $id = $this->id;
  $result = $db->delete('categoriestable', "id=$id");
  $getlinks = $db->select('id', 'linkstable', "catid=$id", '', '');
  $num = $db->numrows($getlinks);
  for ($x=0; $x<$num; $x++)
  {
   $idnum = $db->rowitem($getlinks);
   $result = $db->delete('commentstable', "linkid=$idnum");
  }
  $result = $db->delete('linkstable', "catid=$id"); // delete all associated links  
  if ($this->parent > 0)
  {
   $parentcat = new category('id', $this->parent);
   $parentcat->numsub = $parentcat->numsub - 1;
   $parentcat->update('numsub');
  }
  updatecategoryselector();  
  updatelinktotals($this->parent);  
  return $result;
 }
 
 function update($fields)
 {
  global $db, $settings, $language, $thismember;
  if ($this->id == $this->parent)
  {
   echo "this->id is ". $this->id .' and this->parent is '. $this->parent;
   echo "Error: You're trying to make a category be its own parent. That's not legal, it would create an infinite loop!";
  }
  else
  {
   if ($fields == 'all') $categorylist = explode(',', $settings->categoryfields); 
   else $categorylist = explode(',', $fields);
   $num = sizeof($categorylist);
   for ($count=0; $count<$num; $count++)
   {
    if ($categorylist[$count] == 'hide')
    {
     if ($this->hide == 'yes') $this->hide = 1;
     if ($this->hide == 'no') $this->hide = 0;	
    }
    if (!$thismember->groupcanusehtml && $categorylist[$count] != 'headerinfo') $this->$categorylist[$count] = striphtml($this->$categorylist[$count]);
    $result = $db->update('categoriestable', $categorylist[$count], encodeit($this->$categorylist[$count]), 'id='. $this->id);
   }
  }
  return $result;
 }


function updateparents()
{
 global $db;
 $parentid = $this->parent;
 $query = $db->select('id,name,parent', 'categoriestable', "id=$parentid", '', '');
 $parentdata = $db->row($query);
 $parentids[0] = $parentdata[0];
 $parentnames[0] = $parentdata[1];
 if ($parentids[0] != 0)
 { // hey, we're in a subcategory
  for ($count=1; $parentdata[0] != 0; $count++)
  { 
   $query = $db->select('id,name,parent', 'categoriestable', 'id='. $parentdata[2] .' AND validated=1', '', '');
   $parentdata = $db->row($query);
   if ($parentdata[0] != 0)
   {
    $parentids[$count] = $parentdata[0];	
    $parentnames[$count] = $parentdata[1];
   }
  }
  $this->parentids = implode('|||', $parentids);
  $this->parentnames = implode('|||', $parentnames);
 }
 else
 {
  $this->parentids = '';
  $this->parentnames = '';
 }
 return true;
}

function permissionsoptions()
{
 global $usergroupdata, $ugnum, $ugfields, $settings;
 $permissions = '|||'. $this->permissions .'|||';
 $keys = array_keys($usergroupdata);
 foreach ($keys as $x)
 {
  if (strstr($permissions, '|||'. $usergroupdata[$x]['id'] .'|||'))
   $options .= '<option value="'. $usergroupdata[$x]['id'] .'" selected>'. $usergroupdata[$x]['title'] .'</option>';
  else
   $options .= '<option value="'. $usergroupdata[$x]['id'] .'">'. $usergroupdata[$x]['title'] .'</option>';
 }
 return $options;
}

function hasnew()
{
 global $settings, $inadmindir, $templatesdir;
 $time = $this->lastlinktime;
 $maxdaysago = $settings->marknewupdates;
 $maxtimeago = $maxdaysago * 24 * 60 * 60;
 $compare = time() - $time;
 if (($compare < $maxtimeago) && ($time > 0) && ($this->lastlinktime > $this->time))
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
  global $thismember, $language, $templatesdir;
  if ( $thismember->canedit('category', $this->id) )
  {
   $path = '[IFADMINadmin/]edit.php?action=category&amp;field=id&amp;condition=equals&amp;fieldvalue='. $this->id;
   $admin = str_replace('{PATH}', $path, $language->edit);
   $admin = str_replace('{TEMPLATESDIR}', '[IFADMIN../]'. $templatesdir, $admin);
  }
  else
  {
   $admin = '';
  }
  return $admin;
 }
 
 function numsubcats()
 {
  if ($this->id == 0) 
  {
   global $db;
   $getit = $db->select('id', 'categoriestable', "parent=0 AND validated=1 AND hide=0", '', '');
   $num = $db->numrows($getit);  
  }
  else
  {
   $num = $this->numsub;
  }
  return $num;
 }

 function numsubcatsall()
 {
  global $db;
  $getit = $db->select('id', 'categoriestable', "parent=". $this->id ." AND validated=1 AND isalbum=0", '', '');
  $num = $db->numrows($getit);  
  return $num;
 }
 
 function calcnumsub()
 {
  global $db;
  $catid = $this->id;
  $getit = $db->select('id', 'categoriestable', "parent=$catid AND validated=1 AND hide=0", '', '');
  $num = $db->numrows($getit);
  return $num;
 }
 
 function getsubcats()
 {
  global $ordercats, $settings, $db;
  $catid = $this->id;
  $subcatsdata = $db->select($settings->categoryfields, 'categoriestable', "parent=$catid AND validated=1 AND hide=0 AND isalbum=0", $ordercats, '');
  return $subcatsdata;
 }

 function getsubcatsall()
 {
  global $ordercats, $settings, $db;
  $catid = $this->id;
  $subcatsdata = $db->select($settings->categoryfields, 'categoriestable', "parent=$catid AND validated=1 AND hide=0", $ordercats, '');
  return $subcatsdata;
 }
 
 function linkdata()
 { 
  global $db, $settings, $orderlinks, $begin, $toshow;
  if ($begin == '') $begin = 0;
  if ($toshow == '') $toshow = 1000;
  $catid = $this->id;
  $condition = "catid=$catid AND hide=0 AND validated=1";
  if ($settings->condition != '') $condition .= ' AND '. $settings->condition;
  $linkdata = $db->select($settings->linkfields, 'linkstable', $condition, $orderlinks, "LIMIT $begin,$toshow");
  return $linkdata;
 }

 function linkdatabytype()
 { 
  global $db, $settings, $orderlinks, $begin, $toshow;
  if ($begin == '') $begin = 0;
  if ($toshow == '') $toshow = 1000;
  $catid = $this->id;
  $condition = "catid=$catid AND hide=0 AND validated=1";
  if ($settings->condition != '') $condition .= ' AND '. $settings->condition;
  $orderlinks = str_replace('ORDER BY ', 'ORDER BY typeorder ASC,', $orderlinks);
  $linkdata = $db->select($settings->linkfields, 'linkstable', $condition, $orderlinks, "LIMIT $begin,$toshow");
  return $linkdata;
 }
 
 function numlinks()
 {
  global $db, $settings;
  if ($this->id == 0)
  {
   $total = $settings->totallinks;
  }
  else
  {
   $total = $this->numlinks;
  }
  return $total;
 }   

 function typetotal($type)
 {
  global $db, $settings;
  $catid = $this->id;
  $condition = "catid=$catid AND validated=1 AND hide=0 AND type='$type'";
  if ($settings->condition != '') $condition .= ' AND '. $settings->condition;
  $doit = $db->select('id', 'linkstable', $condition, '', '');
  $num = $db->numrows($doit);
  return $num;
 }
 
 function linkshere()
 {
  global $db, $settings;
  $here = $this->id;
  $condition = "catid=$here AND hide=0 AND validated=1";
  if ($settings->condition != '') $condition .= ' AND '. $settings->condition;
  $query = $db->select('id', 'linkstable', $condition, '', '');
  $total = $db->numrows($query);
  return $total;
 }
 
 function grandtotal()
 {
  return $this->numlinks;
 }

 function subcats($template = '')
 {
  global $language, $db, $settings;
  if ($template == '') $template = $language->subcats;
  $prenum = $this->numsub;
  if ($prenum > $settings->maxsubcats)
   $num = $settings->maxsubcats;
  else
   $num = $prenum;
  $subcats = sublist($this->id, $num, $template);
  if ($prenum > $settings->maxsubcats)
    $subcats .= categoryreplacements($language->subcats_more, $this);
  else
    $subcats = rtrim($subcats, " \t\n\r,|");
  return $subcats;
 } 

 function parentname()
 {
  global $db, $language;
  $parentid = $this->parent;
  if ($parentid > 0)
  {
   $get = $db->select('name', 'categoriestable', "id=$parentid", '', '');
   $row = $db->row($get);
   $thename = $row[0];
  }
  else
  {
   $thename = $language->toplevel;
  }
  return $thename;
 }

 function numberrelated()
 {
  if (($this->related == '') || ($this->related == ' '))
  {
   $num = 0;
  }
  else
  {
   $check = explode('|||', $this->related);
   $num = sizeof($check);
  }
  return $num;
 }
 
 function date()
 {
  global $settings;
  setlocale(LC_ALL, $settings->locale);  
  $dateformat = $settings->dateformat;
  $thedate = strftime("$dateformat", $this->time);
  if ($this->time == 0) $thedate = '';
  return $thedate;
 }

 function lasteditdate()
 {
  global $settings;
  setlocale(LC_ALL, $settings->locale);  
  $dateformat = $settings->dateformat;
  $thedate = strftime("$dateformat", $this->time);
  if (($this->lastedit < 1) || ($this->lastedit == $this->time))  $thedate = '';
  return $thedate;
 }
 
 function lastlinktime()
 {
  return $this->lastlinktime;
 }

 function typeoptions()
 {
  return cattypeoptions($this->type);
 }

 function linkselector()
 {
  global $settings, $db, $orderlinks;
  $query = $db->select('id,title', 'linkstable', 'catid='. $this->id .' AND validated=1 AND hide=0', $orderlinks, '');
  $num = $db->numrows($query);
  for ($x=0; $x<$num; $x++)
  {
   $row = $db->row($query);
   $theoptions .= '<option value='. $row[0] .'>'. $row[1] .'</option>';
  }
  return $theoptions;
 }

 function ismoderator($mem)
 {
  $ismod = false;
  if ($mem->usergroup > 1)
  {
   $modlist = explode(' ', $this->moderators);
   $ismod = in_array($mem->name, $modlist);
   if (!$ismod && $this->parent > 0)
   {
    $parent = new category('id', $this->parent);
    if ($parent->ismoderator($mem)) $ismod = true;
   }
  }
  return $ismod;
 }

 function findmoderators()
 {
  $modlist = str_replace(', ', ',', $this->moderators);
  $mods = $modlist;
  $modlist = explode(',', $modlist);
  if ($this->parent > 0)
  {
   $parentlist = explode('|||', $this->parentids);
   foreach ($parentlist as $parent)
   {
    $thisparent = new category('id', $parent);
    $thismods = str_replace(', ', ',', $thisparent->moderators);
    $mods .= $thismods;
   }
  }
  return $mods;
 }
 
 function issubcategoryof($somecatid)
 {
  $result = 0;
  if ($this->parent == $somecatid) $result = 1;
  if (strstr('|||'. $this->parentids .'|||', '|||'. $somecatid .'|||')) $result = 1;
  return $result;
 }

}

?>