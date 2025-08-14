<?php

function generatelinksoptions()
{
 global $settings;
 $linkfields = explode(',', $settings->linkfields);
 $num = sizeof($linkfields);
 for ($count=0; $count<$num; $count++)
 {
  $field = $linkfields[$count];
  if (!(strstr($settings->excludedtoadmin, '|'. $field . '|')))
  {
   $fieldname = $field;
   if ($field == 'catid') $fieldname = 'category name';
   if ($field == 'recipurl') $fieldname = 'reciprocal url';
   if ($field == 'numcomments') $fieldname = 'number of comments';
   $linksoptions .= "<option value=$field>$fieldname</option>";
  }
 }
 return $linksoptions;
}

function ordermemoptions($selected)
{
 global $settings;
 $memfields = explode(',', $settings->memberfields);
 sort($memfields);
 $num = sizeof($memfields);
 for ($count=0; $count<$num; $count++)
 {
  $field = $memfields[$count];
  $fieldfull = "ORDER BY $field ASC";
  $english = "By $field ascending";
  if ($fieldfull == $selected) $isselected = " selected"; else $isselected = '';
  $memsoptions .= "<option value='$fieldfull' $isselected>$english</option>";
  $fieldfull = "ORDER BY $field DESC";
  $english = "By $field descending";   
  if ($fieldfull == $selected) $isselected = " selected"; else $isselected = '';
  $memsoptions .= "<option value='$fieldfull' $isselected>$english</option>";
 }
 return $memsoptions;
}

function orderlinksoptions($selected)
{
 global $settings;
 $linkfields = explode(',', $settings->linkfields);
 sort($linkfields);
 $num = sizeof($linkfields);
 for ($count=0; $count<$num; $count++)
 {
  $field = $linkfields[$count];
  $fieldfull = "ORDER BY $field ASC";
  $english = "By $field ascending";
  if ($fieldfull == $selected) $isselected = " selected"; else $isselected = '';
  $linksoptions .= "<option value='$fieldfull' $isselected>$english</option>";
  $fieldfull = "ORDER BY $field DESC";
  $english = "By $field descending";   
  if ($fieldfull == $selected) $isselected = " selected"; else $isselected = '';
  $linksoptions .= "<option value='$fieldfull' $isselected>$english</option>";
 }
 return $linksoptions;
}

function ordercommentsoptions($selected)
{
 global $settings;
 $commentfields = explode(',', $settings->commentfields);
 sort($commentfields);
 $num = sizeof($commentfields);
 for ($count=0; $count<$num; $count++)
 {
  $field = $commentfields[$count];
  $fieldfull = "ORDER BY $field ASC";
  $english = "By $field ascending";
  if ($fieldfull == $selected) $isselected = " selected"; else $isselected = '';
  $commentsoptions .= "<option value='$fieldfull' $isselected>$english</option>";
  $fieldfull = "ORDER BY $field DESC";
  $english = "By $field descending";   
  if ($fieldfull == $selected) $isselected = " selected"; else $isselected = '';
  $commentsoptions .= "<option value='$fieldfull' $isselected>$english</option>";
 }
 return $commentsoptions;
}

function ordercatsoptions($selected)
{
 global $settings;
 $categoryfields = explode(',', $settings->categoryfields);
 sort($categoryfields);
 $num = sizeof($categoryfields);
 for ($count=0; $count<$num; $count++)
 {
  $field = $categoryfields[$count];
  $fieldfull = "ORDER BY $field ASC";
  $english = "By $field ascending";
  if ($fieldfull == $selected) $isselected = " selected"; else $isselected = '';
  $categoryoptions .= "<option value='$fieldfull' $isselected>$english</option>";
  $fieldfull = "ORDER BY $field DESC";
  $english = "By $field descending";   
  if ($fieldfull == $selected) $isselected = " selected"; else $isselected = '';
  $categoryoptions .= "<option value='$fieldfull' $isselected>$english</option>";
 }
 return $categoryoptions;
}

function generatecatsoptions()
{
 global $settings;
 $categoryfields = explode(',', $settings->categoryfields);
 sort($categoryfields);
 $num = sizeof($categoryfields);
 for ($count=0; $count<$num; $count++)
 {
  $field = $categoryfields[$count];
  if (!(strstr($settings->excludedtoadmin, '|'. $field . '|')))
  {
   $fieldname = $field;
   if ($field == 'numlinks') $fieldname = 'number of links';
   if ($field == 'numsub') $fieldname = 'number of subcategories';   
   $categoriesoptions .= '<option value="'. $field .'">'. $fieldname .'</option>';
  }
 }
 return $categoriesoptions;
}

function generatecommentoptions()
{
 global $settings;
 $commentfields = explode(',', $settings->commentfields);
 sort($commentfields);
 $num = sizeof($commentfields);
 for ($count=0; $count<$num; $count++)
 {
  $field = $commentfields[$count];
  if (!(strstr($settings->excludedtoadmin, '|'. $field . '|')))
  {
   $fieldname = $field;
   if ($field == 'postername') $fieldname = 'poster name';
   if ($field == 'linkname') $fieldname = 'link name';  
  $commentoptions .= "<option value=$field>$fieldname</option>";
  }
 }
 return $commentoptions;
}


function generatememberoptions()
{
 global $settings;
 $memberfields = explode(',', $settings->memberfields);
 sort($memberfields);
 $num = sizeof($memberfields);
 for ($count=0; $count<$num; $count++)
 {
  $field = $memberfields[$count];
  if (!(strstr($settings->excludedtoadmin, '|'. $field . '|')))
  {
   $fieldname = $field;
   if ($field == 'postername') $fieldname = 'poster name';
   if ($field == 'linkname') $fieldname = 'link name';  
  $memberoptions .= "<option value=$field>$fieldname</option>";
  }
 }
 return $memberoptions;
}

function sendvalidationemail($thislink)
{
 global $language, $settings;
 $adminaddress = $settings->email;
 if ($thislink->email == '' && $thislink->ownerid > 0) 
 {
  $mem = new member('id', $thislink->ownerid);
  $submitter = $mem->email; 
 }
 else
 {
  $submitter = $thislink->email;
 }
 $subject = $language->email_notifyusertitle;
 $message = $language->email_notifyuserbody;
 $message = linkreplacements($message, $thislink);   
 $message = str_replace('{DIRURL}', $settings->dirurl, $message);
 $thecat = new category('id', $thislink->catid);
 $message = categoryreplacements($message, $thecat);
 $message = decodeit($message);
 if ($submitter != '' && $adminaddress != '') sendemail("$submitter", "$subject", "$message", "From: $adminaddress");
 return true;
}

function updatelinkcounters($next)
{
 global $db, $settings, $inc;
 $next = (int)($next);
 // recalculate comment totals for links
 $query = $db->select('id', 'linkstable', 'id>0', 'ORDER BY id ASC', "LIMIT $next,$inc");
 $max = $db->numrows($query);
 while ($row = $db->row($query))
 {
  $alink = new onelink('row', $row);
  $id = $alink->id;
  if ($alink->alias > 0) 
  {
   $reallink = new onelink('id', $alink->alias); $id = $alink->alias;
  }
  $q2 = $db->select('id', 'commentstable', 'linkid='. $id .' AND validated=1', '', '');
  $alink->numcomments = $db->numrows($q2);
  $alink->update('numcommens');
  if ($alink->alias > 0) 
  {
   $linkfld = explode(',', $settings->linkfields);
   foreach ($linkfld as $field)
   {
    if ($field != 'id' && $field != 'alias' && $field != 'catid') 
    { $alink->$field = $reallink->$field; $alink->update($field); }
   }
  }
 }
 return true;
}

function updatecatcounters($next)
{
 global $db, $settings, $inc;
 // recalculate link totals and parents for categories
 $query = $db->select($settings->categoryfields, 'categoriestable', 'id>0', 'ORDER BY id ASC', "LIMIT $next,$inc");
 $num = $db->numrows($query);
 while ($row = $db->row($query))
 {
  $cat = new category('row', $row);
  $cat->updateparents();
  $cat->numsub = $cat->calcnumsub(); 
  $cat->numlinks = totalincat($cat->id);
  $totalcomments = 0;
  $get = $db->select('numcomments', 'linkstable', 'catid='. $cat->id, '', '');
  $n = $db->numrows($get);
  for ($x=0; $x<$n; $x++) $totalcomments += $db->rowitem($get);
  $cat->totalcomments = $totalcomments;
  $cat->update('parentids,parentnames,numsub,numlinks,totalcomments');
 }
 return true;
}

function updatememcounters($next)
{ 
 global $db, $settings, $inc;
 // re-calculate number of links, comments and hits for member profiles
 $query = $db->select($settings->memberfields, 'memberstable', 'id>0', 'ORDER BY id ASC', "LIMIT $next,$inc");
 $num = $db->numrows($query);
 while ($row = $db->row($query))
 {
  $allhits = 0;
  $allhitsin = 0;
  $mem = new member('row', $row);
  $q2 = $db->select('hits,hitsin', 'linkstable', "ownerid=". $mem->id ." AND hide=0 AND validated=1", '', '');
  $tot = $db->numrows($q2);
  $mem->links = $tot;
  for ($x=0; $x<$tot; $x++)
  {
    $row = $db->row($q2);
    $allhits += $row[0];
    $allhitsin += $row[1];
  }
  $mem->totalhits = $allhits;
  $mem->totalhitsin = $allhitsin;
  $q3 = $db->select('id', 'commentstable', 'ownerid='. $mem->id .' AND validated=1', '', '');
  $totcom = $db->numrows($q3);
  $mem->comments = $totcom;
  $mem->update('links,totalhits,totalhitsin,comments');
 } 
 return true;
}

?>