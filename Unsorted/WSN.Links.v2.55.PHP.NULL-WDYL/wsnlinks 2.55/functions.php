<?php

function updatecategoryselector()
{
 global $settings, $templatesdir;
 $settings->categoryselector = encodeit(filltheselector(0));
 $settings->sitemap = encodeit(fillsitemap(0));
 $settings->update('categoryselector,sitemap');
 return true;
}

function updatesitemap()
{
 global $settings;
 $settings->sitemap = encodeit(fillsitemap(0));
 $settings->update('sitemap');
 return true;
}

function filltheselector($next)
{
 global $db, $settings, $language;
 $order = $settings->ordercats;
 $query = $db->select('id,name,parentids', 'categoriestable', "parent=$next AND validated=1 AND hide=0 AND isalbum=0", "ORDER BY name ASC", '');
 $num = $db->numrows($query);
 for ($count=0; $count<$num; $count++)
 { 
  $row = $db->row($query);
  $id = $row[0];
  $name = $row[1];
  $parentids = $row[2];
  $parentarray = explode('|||', $parentids);
  if ($parentarray[0] > 0) $level = sizeof($parentarray) + 1;
  else $level = 1;
  $indent = '';
  if ($settings->selectorlevels >= $level)
  { 
   for ($dum=1; $dum<$level; $dum++)
     $indent .= $language->catselector_indent; 
   $categoryselect .= '<option value="'. $id .'">'. $indent .' '. $name .'</option>'; 
   // now we work on the children...
   if ($settings->selectorlevels > 1) $categoryselect .= filltheselector($id);
  }
 }
 return $categoryselect;  
}

function fillsitemap($next)
{
 global $db, $settings, $language;
 $query = $db->select('id,name,parentids,parent,type,numlinks,numsub,description', 'categoriestable', "parent=$next AND validated=1 AND hide=0 AND isalbum=0", $settings->ordercats, '');
 $num = $db->numrows($query);
 for ($count=0; $count<$num; $count++)
 { 
  $row = $db->row($query);
  $id = $row[0];
  $name = $row[1];
  $parentids = $row[2];
  $parent = $row[3];
  $type = $row[4];
  $numlinks = $row[5];
  $numsub = $row[6];  
  $description = $row[7];
  $parentarray = explode('|||', $parentids);
  if ($parentarray[0] > 0) $level = sizeof($parentarray) + 1;
  else $level = 1;
  $indent = '';
  if ($settings->maplevels >= $level)
  { 
   for ($dum=1; $dum<$level; $dum++)
     $indent .= $language->catselector_indent; 
   $categoryselect .= $indent . '[,]'. $id .'[,]'. $name .'[,]'. $parent .'[,]'. $type .'[,]'. $parentids .'[,]'. $numlinks .'[,]'. $numsub .'[,]'. $description .'[,]{ENDLINE}'; 
   // now we work on the children...
   if ($settings->maplevels > 1) $categoryselect .= fillsitemap($id);
  }
 }
 return $categoryselect;  
}

function catgrouporder()
{
//when we make catselector based on site map, this will be useful to know order of optgroups
 $checkcatorder = new template("main.tpl");
 $type = explode(',', $settings->cattypes);
 for ($x=0; $x<sizeof($type); $x++)
 {
  $pos = strpos($checkcatorder->text, '<!-- BEGIN '. strtoupper($type[$x]));
  $name = $type[$x];
  $cat[$$name] = $pos;
 }
 sort($cat, SORT_NUMERIC);
 $keys = array_keys($cat);
 return $keys; 
}

function mapgroup($group)
{
 global $settings, $parsedcats;
 $num = sizeof($parsedcats);
 for ($x=0; $x<$num; $x++)
 {
  if ($parsedcats[$x][3] == 0 && $parsedcats[$x][4] == $group) $map[] = $row;
 }
 return $map;
}

function suboptions($parent)
{
 global $settings, $parsedcats;
 $num = sizeof($parsedcats);
 for ($x=0; $x<$num; $x++)
 {
  if (strstr('|||'. $parsedcats[$x][5] .'|||', '|||'. $parent .'|||'))
  {
   $map .= '<option value="'. $parsedcats[$x][1] .'">'. $parsedcats[$x][0] .' '. $parsedcats[$x][2] .'</option>';
  }
 }
 return $map;
}

function subrows($parent)
{
 global $parsedcats;
 $num = sizeof($parsedcats);
 for ($x=0; $x<$num; $x++)
 {
  if ($parsedcats[$x][3] == $parent)
  {
   $rowdata[] = $parsedcats[$x];
  } 
 }
 return $rowdata;
}

function sublist($parent, $num, $template)
{
 global $settings, $language, $parsedcats;
 $n1 = $settings->totalcats;
 $done = 0;
 if (is_array($parsedcats))
 {
  foreach ($parsedcats as $thiscat)
  {
   if ($done < $num && $thiscat[3] == $parent)
   {
    $map .= subreplace($thiscat, $template);
    $letter = ord('a') + ($done);
    $map = str_replace('{LETTER}', chr($letter), $map);
    $map = str_replace('{U_LETTER}', strtoupper(chr($letter)), $map);
    $map = str_replace('{NUMBER}', $done + 1, $map);  
    $done++;
   }
  }
 }
 return $map;
}

function getmaprow($id)
{
 global $settings, $language, $parsedcats;
 if ($parsedcats[$id])
   $row = $parsedcats[$id];
 else
   $row = $db->row($db->select($settings->categoryfields, 'categoriestable', "id=$id", '', ''));
 return $row;
}

function subreplace($row, $template)
{
 $semicat = new category('empty', 'empty');
 $semicat->id = $row[1];
 $semicat->name = $row[2];
 $semicat->parent = $row[3];
 $semicat->type = $row[4];
 $semicat->parentids = $row[5];
 $semicat->numlinks = $row[6];
 $semicat->numsub = $row[7];
 $semicat->description = $row[8];
 $mapline = categoryreplacements($template, $semicat);
 if (strstr($mapline, '{CAT'))
 { // if we have to, guzzle server resources
  $fullcat = new category('id', $row[1]);
  $mapline = categoryreplacements($template, $fullcat);
 }
 return $mapline;
}

function makenewselector()
{ // generate category selector from site map data
 global $settings;
 $positions = catgrouporder();
 $data = $settings->sitemap;
 
 $positions = catgrouporder();
 if (is_array($positions))
 {
  foreach ($positions as $group)
  {
   //  get all top level categories of group $group
   $cats = mapgroup($group);
//   $catselector .= '<optgroup="'. $group .'"></optgroup>';
   if (is_array($cats))
   {
    foreach ($cats as $row)
	{
	 $catselector .= '<option value="'. $row[1] .'">'. $row[2] .'</option>';
	 $catselector .= suboptions($row[1]);
	}
   }
  }
 }
 return $catselector;
}

function catselector($selectedcat, $field)
{
 global $language, $settings;
 if ($field == 'parent') $categoryselect .= '<option value="0">'. $language->toplevel .'</option>';
 $categoryselect .= makeselection($settings->categoryselector, $selectedcat);
 return $categoryselect;
}


function relatedcatselector($related)
{
 global $settings;
 return makeselection($settings->categoryselector, $related);
}


function updatelinktotals($category)
{
 global $db;
 $query = $db->select('parentids', 'categoriestable', "id=$category", '', '');
 $parentids = $db->rowitem($query);
 if ($parentids != '')
 {
  $category .= '|||'. $parentids;
  $catlist = explode('|||', $category);
  $num = sizeof($catlist);
  for ($count=0; $count<$num; $count++)   
  {
   if ($catlist[$count] > 0)
   {
    $correcttotal = totalincat($catlist[$count]);
    $query = $db->update('categoriestable', 'numlinks', $correcttotal, 'id='. $catlist[$count]);
   }
  }
 }
 else
 {
  $correcttotal = totalincat($category);
  $query = $db->update('categoriestable', 'numlinks', $correcttotal, 'id='. $category); 
 }
 return true;
}

function totalincat($id)
{
 global $db, $settings;
 if ($id == '') $id = 0;
 $condition = "catid=$id AND hide=0 AND validated=1";
 if ($settings->condition != '') $condition .= ' AND '. $settings->condition;    
 $query = $db->select('id', 'linkstable', $condition, '', '');
 $total += $db->numrows($query);
 $subsubcats = $db->select('id', 'categoriestable', "parent=$id AND validated=1 AND hide=0", '', '');
 $num = $db->numrows($subsubcats);
 for ($x=0; $x<$num; $x++)
 {
  $nextsub = $db->rowitem($subsubcats);
  $total += totalincat($nextsub);
 }
 return $total;
}

function shownav($thiscategory)
{
 global $language;
 if (!method_exists($thiscategory, 'hasnew')) $thiscategory = new category('id', $thiscategory);
 return $thiscategory->nav();
}


function autovalidate($type, $usergroup)
{
 global $db;
 if ($type == 'category')
   $query = $db->select('validatecats', 'membergroupstable', "id=$usergroup", '', '');
 else if ($type == 'link')
    $query = $db->select('validatelinks', 'membergroupstable', "id=$usergroup", '', '');
 else if ($type == 'comments')
    $query = $db->select('validatecomments', 'membergroupstable', "id=$usergroup", '', '');
 
 $requirevalidate = $db->rowitem($query);
 if ($requirevalidate == 0) $validate = true; else $validate = false;
 return $validate;
}

function sendactivationcode($amember)
{
 global $language, $settings;
 $adminaddress = $settings->email;
 $submitter = $amember->email;
 $subject = memberreplacements($language->email_activationtitle, $amember);
 $message = memberreplacements($language->email_activationbody, $amember);   
 $message = str_replace('{DIRURL}', $settings->dirurl, $message);
 $message = str_replace('{ACTIVATIONSTRING}', $amember->password, $message); 
 $message = decodeit($message);
 sendemail("$submitter", "$subject", "$message", "From: $adminaddress");
 return true;
}

function typeselector ($selectedone)
{
 global $settings, $language;
 if ($selectedone == 'suggestlinkpage' && $settings->sponsortype == 'promotion') 
 { 
  $hidethis = $settings->sponsorlinktype;
 }
 if ($language->linktypes != '') $namesarray = explode(',', $language->linktypes);
 else $namesarray = explode(',', $settings->linktypes);
 $typearray = explode(',', $settings->linktypes);
 $num = sizeof($typearray);
 for ($c=0; $c<$num; $c++)
 {
  if ($typearray[$c] == $selectedone)
  {
   if ($typearray[$c] != $hidethis)
   $typeoptions .= '<option value="'. $typearray[$c] .'" selected>'. $namesarray[$c];
   if (strtolower($namesarray[$c] == 'recip')) $typeoptions .= 'rocal';
   $typeoptions .= '</option>';
  }
  else
  {
   if ($typearray[$c] != $hidethis)
   {
    $typeoptions .= '<option value="'. $typearray[$c] .'">'. $namesarray[$c];
    if (strtolower($namesarray[$c] == 'recip')) $typeoptions .= 'rocal';
    $typeoptions .= '</option>';
   }
  }
 }
 return $typeoptions;
}

function uploadfile($varname, $todo, $destination = '')
{
 global $settings, $_FILES;
 // contains full path to uploaded file in temprary storage
 $upload_temp = $_FILES[$varname]['tmp_name'];
 // get file name portion of source file
 $upload_file = $_FILES[$varname]['name'];
 // build target filename
 $ext = extension($_FILES[$varname]['name']);
 clearstatcache();
 $filesize = filesize($_FILES[$varname]['tmp_name']);
 if ($todo == 'randomize') $upload_file = md5(microtime()) .'.'. $ext;
 if ($destination == 'language')
 {
  $path = str_replace('attachments', 'languages', $settings->uploadpath);
  $target_file = $path . $upload_file; 
 }
 else
 {
  $target_file = $settings->uploadpath . $upload_file;
 }
 $uploaded = false;
 $allowed = false;
 $types = explode(',', strtolower($settings->filetypes));
 $num = sizeof($types);
 for ($x=0; $x<$num; $x++) if ($types[$x] == $ext) $allowed = true;
 if (($filesize < $settings->filesize) && ($allowed))
 { 
  // try to copy file to real upload directory
  if (move_uploaded_file($upload_temp, $target_file))
  {
   $uploaded = $upload_file;
  }
 }
 return $uploaded; // returns file name if it worked, or false if failed
}

function isduplicate($url, $level = 'soft')
{
 global $db;
 $doit = $db->select('url', 'linkstable', 'id>0', '', '');
 $num = $db->numrows($doit);
 for ($count=0; $count<$num; $count++)
 {
  $next = $db->rowitem($doit);
  if ($level == 'hard') 
  {
   $nextparts = parse_url($next);
   $next = $nextparts[host];
  }
  $urllist .= '|||'. $next .'|||';
 }
 $urlparts = @parse_url($url);
 if ($level == 'hard') $times = substr_count($urllist, '|||'. $urlparts[host] .'|||');
 else $times = substr_count($urllist, '|||'. $url .'|||');
 if ($times > 0) $duplicate = true;
 else $duplicate = false;
 return $duplicate;
}

?>