<?php
require 'adminstart.php';

$noparsing = true; // we don't want codes parsed when editing
$nomember = true;
$canedit = false;
$weareediting = true;
if ($action == 'aliaslink' && $thismember->groupcanalias) $canedit = true;
if ($action == 'copylink' && $thismember->groupcancopy) $canedit = true;
if ($thismember->groupisadmin) $canedit = true;
if (!$canedit)
{
if ($field == 'id')
{
 if ($action == 'link')
 {
  $something = new onelink('id', $fieldvalue);
  $cat = new category('id', $something->catid);
 }
 else if ($action == 'member') $amem = new member('id', $fieldvalue);
 else if ($action == 'category') $cat = new category('id', $fieldvalue);
 else if ($action == 'comment')
 {
  $com = new comment('id', $fieldvalue);
  $something = new onelink('id', $com->linkid);
  $cat = new category('id', $something->catid);
 }
 $canedit = $thismember->canedit($action, $fieldvalue);
}
else
{
 if ($action == 'link')
 {
  $something = new onelink('id', $id);
  $cat = new category('id', $something->catid);
 }
 else if ($action == 'member') $amem = new member('id', $id);
 else if ($action == 'category') $cat = new category('id', $id);
 else if ($action == 'comment')
 {
  $com = new comment('id', $id);
  $something = new onelink('id', $com->linkid);
  $cat = new category('id', $something->catid);
 }   
 $canedit = $thismember->canedit($action, $id);
} }


if ($canedit)
{
 $speciallogin = true;
 if ($action == 'aliaslink')
 {
  if ($filled)
  {
   if ($todo == 'delete')
   {
    $noaliasing = true;
	$todelete = new onelink('id', $aliasid);
	$todelete->deletethis();
	$noaliasing = false;
    if (!$template) $template = new template("../$templatesdir/redirect.tpl");
    $template->replace('{MESSAGE}', 'That alias has been deleted.');
    $template->replace('{DESTINATION}', "edit.php?action=aliaslink&amp;id=$id");	
   }
   else
   {
    if ($altcatid != '') $catid = $altcatid;
    $oldone = new onelink('id', $id);
    $thelink = new onelink('blank', 'blank');
	// set all fields of alias to be same as link it is aliasing
	$linkfieldarray = explode(',', $settings->linkfields);
	$skipfields = ' id catid alias validated ';
	foreach ($linkfieldarray as $linkfield)
	{
	 if (!strstr($skipfields, " $linkfield ")) $thelink->$linkfield = $oldone->$linkfield;
	}
    $thelink->catid = $catid;
    $thelink->alias = $id;
    $thelink->validated = 1;
    $aliasing = true; // avoid sending notification email
	$message = 'The alias has been created.';
    $check = $db->select('id', 'linkstable', 'alias='. $id .' AND catid='. $catid, '', '');
    $check = $db->rowitem($check);
    if (!($check > 0) && $oldone->catid != $catid) { $thelink->add(); $settings->uniquetotal -= 1; $settings->update('uniquetotal'); } // don't alias something into category it already has, or into its own category
    else $message = 'You already have an alias of this link in that category.';
    $aliasing = false;
    if (!$template) $template = new template("../$templatesdir/redirect.tpl");
    $template->replace('{MESSAGE}', $message);
    $template->replace('{DESTINATION}', $returnto);
   }
  }
  else
  {
   if (!$template) $template = new template("../$templatesdir/admin/alias.tpl");
   $list = templateextract($template->text, '<!-- BEGIN ALIAS LIST -->', '<!-- END ALIAS LIST -->');
   $query = $db->select('id,catid', 'linkstable', "alias=$id", '', '');
   $num = $db->numrows($query);
   for ($x=0; $x<$num; $x++)
   {
    $row = $db->row($query);
	$listpart .= $list;
	$listpart = str_replace('{ALIASID}', $row[0], $listpart);
	$acat = new category('id', $row[1]);
	$listpart = categoryreplacements($listpart, $acat);
   }
   $template->replace($list, $listpart);
  }
 }
 else if ($action == 'copylink')
 {
  $thislink = new onelink('id', $id);
  if (!$template) $template = new template("../$templatesdir/edit.tpl");
  $template->replace("edit.php?action=link", "../suggest.php?action=addlink");
  $template->replace("Edit", "Add Copied");
  $template->replace("{LANG_EDITLINK_EDIT}", "Add Copied");
  $template->replace("Edit Link", "Add Copied Link");
  $selector = $settings->categoryselector;
  $template->text = linkreplacements($template->text, $thislink);
  $hideselector = yesno($thislink->hide);
  $selector = makeselection($settings->categoryselector, $thislink->catid);
  $template->replace('{LINKTYPESELECTOR}', typeselector($thislink->type));
  $template->replace('{LINKHIDESELECTOR}', $hideselector);
  $template->replace('{OLDCATID}', $thislink->catid);
  $template->replace('{LINKRECIPSELECTOR}', $recipselector);
//  $template->replace('{CATSELECTOR}', $selector);
  $template->replace('{LINKID}', "''");
  $template->replace('{INCOMPLETE}', '');
  $template->text = linkreplacements($template->text, $thislink); 
 }
 else if ($action == 'link')
 { 
  if ($filled)
  {
    if ($delete=='on')
    {
     $thislink = new onelink('dummy', $id);	
     $result = $thislink->deletethis();
     $owner = $thislink->ownerid;
     $amem = new member('id', $owner);
     $amem->links -= 1;
     $amem->update('links');
     $acat = new category('id', $thislink->catid);
     $updateids = explode('|||', $acat->parentids);
     $n = sizeof($updateids);
     for ($x=0; $x<$n; $x++)
     {
      if ($updateids[$x] > 0) updatelinktotals($updateids[$x]);
     }
     if (!$template) $template = new template("../$templatesdir/redirect.tpl");
     $ourlang = $language->edit_deletelink;
     $template->replace('{MESSAGE}', $ourlang);
     if ($afterdellink == '') $afterdellink = $returnto;
     $template->replace("{DESTINATION}", $afterdellink);
    }
    else
    {

     if ($expire > 0) $expire = time() + ($expire * 86400); // calculate expiration date

     $fields = explode(',', $settings->linkfields);
     $num = sizeof($fields);
     for ($x=0; $x<$num; $x++)
     {
      if (is_array($$fields[$x])) { $$fields[$x] = implode('|||', $$fields[$x]); $$fields[$x] = str_replace('selected', '', $$fields[$x]); }
     }
     $newfiletitle = $_FILES['newfiletitle']['name'];
     if ($newfiletitle != '')
     {
      if (strstr('jpg gif png bmp swf psd tiff iff jp2 jpc swc', extension($newfiletitle)))
      {
       $info = @getimagesize($_FILES['filetitle']['tmp_name']); 
       if (($info[0] > 0 && $info[1] > 0) && !($info[0] > $settings->maximagewidth || $info[1] > $settings->maximageheight))
       {
        $incomplete = true; 
        $incompletemessage = $language->uploadfailed; 
       }
      }
      $oldlink = new onelink('id', $id);
      $oldfilename = $oldlink->filename;
      $filetitle = $newfiletitle;
      $filename = uploadfile('newfiletitle', 'randomize');
      if (!$filename) 
      {
       $incomplete = true; 
       $incompletemessage = $language->uploadfailed; 
      }
      else
      {
       $filesize = filesize($settings->uploadpath . $filename);
       $imagearray = getimagesize($settings->uploadpath . $filename);
       $xwidth = $imagearray[0];
       $yheight = $imagearray[1];
      }
     }

     // check for incompletes
     $requiredlinks = explode(',', $settings->requiredlinks);
     $y = sizeof($requiredlinks);
     for ($x=0; $x<$y; $x++)
     {
      if ($$requiredlinks[$x] == '' && $requiredlinks[$x] != '' && $requiredlinks[$x] != 'name' && $requiredlinks[$x] != 'password' && $requiredlinks[$x] != 'name') { $incomplete = true; $incompletemessage = $language->suggest_incomplete; }
      $reqlinklist .= '|||'. $requiredlinks[$x] .'|||';
     }
     if ($thismember->isadmin() && $settings->adminbypass == 'yes') $incomplete = false;
     if ($incomplete)
     {
      $thelink = new onelink('new', 'noid');
      $template = new template("../$templatesdir/edit.tpl");
      $template->text = linkreplacements($template->text, $thelink);
      $template->replace('{OLDCATID}', $oldcategory);
	  $template->replace('{INCOMPLETE}', $incompletemessage);
     }
     else
     {
      if ($thismember->groupvalidateedits)
      {
       $fieldlist = explode(',', $settings->linkfields);
       $num = sizeof($fieldlist);
       $thislink = new onelink('id', $id);
       $thislink->pendingedit = '';
       for ($count=0; $count<$num; $count++)
       {
        if ($fieldlist[$count] != 'filename' && $fieldlist[$count] != 'filetitle')
        { 
         if ((stristr($varslist, '|||'. $fieldlist[$count] .'|||') && ($$fieldlist[$count] != $thislink->$fieldlist[$count])) || (strstr($forcelist, $fieldlist[$count])))
         {
          $thislink->pendingedit .= $fieldlist[$count] .'[,]'. $$fieldlist[$count] .'|||END FIELD|||';
         }
         else
         {
          $thislink->pendingedit .= $fieldlist[$count] .'[,]'. $thislink->$fieldlist[$count] .'|||END FIELD|||';
         }
        }
       }
       if ($filename != '') $thislink->pendingedit .= 'filename[,]'. $filename .'|||END FIELD|||filetitle[,]'. $filetitle .'|||END FIELD|||';
       else $thislink->pendingedit .= 'filename[,]'. $thislink->filename .'|||END FIELD|||filetitle[,]'. $thislink->filetitle .'|||END FIELD|||';
       $thislink->update('pendingedit');
       $adminaddress = $settings->email;
       if ( ($settings->notify == 'yes') && ($adminaddress != '') )
       {  
        $subject = $language->email_newsuggestiontitle;
        $message = $language->email_newsuggestionbody;
        $message = str_replace('{TYPE}', 'image', $message);   
        $message = str_replace('{DIRURL}', $settings->dirurl, $message);
        $message = linkreplacements($message, $thislink);
        mail("$adminaddress", "$subject", "$message", "From: $adminaddress");
       } 
       $ourlang = $language->edit_pendingvalidation;
	   $template = new template("../$templatesdir/redirect.tpl");
       $template->replace('{MESSAGE}', $ourlang);
       if ($aftereditlink == '') $aftereditlink = $returnto;   
       $template->replace('{DESTINATION}', $aftereditlink);
      }
      else
      {
       if ($newfiletitle != '')
       {
        @unlink($settings->uploadpath . $oldfilename); // delete old attachment
        if ($pref == 'image') @unlink($settings->uploadpath .'thumb_'. $oldfilename); // delete old thumbnail if gallery
        if ($pref == 'image') @unlink($settings->uploadpath .'temp_'. $oldfilename); // delete old temp if gallery
       }

       $fieldlist = explode(',', $settings->linkfields);
       $num = sizeof($fieldlist);
       $thislink = new onelink('id', $id);
       for ($count=0; $count<$num; $count++)
       { 
        if ((stristr($varslist, '|||'. $fieldlist[$count] .'|||') && ($$fieldlist[$count] != $thislink->$fieldlist[$count])) || (strstr($forcelist, $fieldlist[$count])))
        {
         $thislink->$fieldlist[$count] = $$fieldlist[$count];
         $thislink->update($fieldlist[$count]);
        }
       }
       if ($newfiletitle != '')
       {
        $thislink->filetitle = $filetitle;
        $thislink->filename = $filename;
        $thislink->update('filetitle,filename');
       }
       $thislink->sumofvotes = ($thislink->votes) * ($thislink->rating);
       $thislink->lastedit = time();
       $thislink->update('sumofvotes,rating,votes,lastedit');

	   // set all fields of alias to be same as link it is aliasing
	   $getaliases = $db->select($settings->linkfields, 'linkstable', 'alias='. $thislink->id, '', '');
       $num = $db->numrows($getaliases);
	   $linkfieldarray = explode(',', $settings->linkfields);
	   $skipfields = ' id catid alias validated ';
	   for ($x=0; $x<$num; $x++)
	   {
	    $thisalias = new onelink('row', $db->row($getaliases));
	    foreach ($linkfieldarray as $linkfield)
	    {
	     if (!strstr($skipfields, " $linkfield ")) $thisalias->$linkfield = $thislink->$linkfield;
  	    }	   
	   }
	   
       updatelinktotals($thislink->catid);
       if ($oldcategory > 0 && $oldcategory != $thislink->catid) updatelinktotals($oldcategory);
       if ($oldcategory > 0 && $oldcategory != $thislink->catid)                 
         updatelinktotals($oldcategory);
       calctypeorder(); // set its type's order
       $acat = new category('id', $thislink->catid);
       $updateids = explode('|||', $acat->parentids);
       $n = sizeof($updateids);
       for ($x=0; $x<$n; $x++)
       {
        if ($updateids[$x] > 0) updatelinktotals($updateids[$x]);
       }
       if (!$template) $template = new template("../$templatesdir/redirect.tpl");
       $ourlang = $language->admin_editlink;
       if ($thismember->groupvalidateedits) $ourlang = $language->edit_pendingvalidation;
       $template->replace('{MESSAGE}', $ourlang);
       if ($aftereditlink == '') $aftereditlink = $returnto;   
       $template->replace('{DESTINATION}', $aftereditlink);
      }
     }
    }
  }
  else
  { 
   $linkinfo = getlinkinfo($field, $fieldvalue, $condition);
   $nummatching = $db->numrows($linkinfo);
   if ($nummatching > 1)
   {
    if (!$template) $template = new template("../$templatesdir/search.tpl");
	$subarea = templateextract($template->text, '<!-- BEGIN SEARCH links RESULTS -->', '<!-- END SEARCH links RESULTS -->');
	$suberarea = templateextract($subarea, '<!-- BEGIN REGULAR -->', '<!-- END REGULAR -->');
    $template->replace($subarea, '{SUBAREA}');	
    for ($count=0; $count<$nummatching; $count++)
    {
     $row = $db->row($linkinfo);
	 $thislink = new onelink('row', $row);
     $displaymatches .= linkreplacements($suberarea, $thislink);
    }
	$template->replace('{SUBAREA}', $displaymatches);
	$template->replace('{PREVIOUS}', '');
	$template->replace('{NEXT}', '');	
	$template->replace('{CURRENTPAGE}', '1');	
	$template->replace('{NUMRESULTS}', $nummatching);	
	$template->replace('{SEARCHTERM}', $fieldvalue);	
   }
   else if ($nummatching == 1)
   { // only one match so go direct to edit form
    if (!$template) $template = new template("../$templatesdir/edit.tpl");
    if (strstr($template->text, '{THIS')) $nomember = false;
    $row = $db->row($linkinfo);
	$thislink = new onelink('row', $row);
    $hideselector = yesno($thislink->hide);
	$selector = makeselection($settings->categoryselector, $thislink->catid);
	$template->text = linkreplacements($template->text, $thislink);
    $template->replace('{LINKTYPESELECTOR}', typeselector($thislink->type));
    $template->replace('{LINKHIDESELECTOR}', $hideselector);
    $template->replace('{CATSELECTOR}', $selector);
      $template->replace('{ALLOWEDEXTENSIONS}', $settings->filetypes);
	$template->replace('{OLDCATID}', $thislink->catid);
    $template->replace('{INCOMPLETE}', '');
   }
   else
   {
    if (!$template) $template = new template("../$templatesdir/redirect.tpl");
    $template->replace('{MESSAGE}', $language->search_nomatch);
    if ($aftereditlink == '') $aftereditlink = $returnto;
    $goto = $aftereditlink;
    $template->replace('{DESTINATION}', $goto);
   }
  }
 }

 else if ($action == 'member')
 { 
  if ($filled)
  {
   if ($delete=='on')
   {
    $amember = new member('id', $id);	
    $result = $amember->deletethis();
    if (!$template) $template = new template("../$templatesdir/redirect.tpl");
    $ourlang = $language->edit_deletemember;
	$settings->totalmembers -= 1;
	$settings->update('totalmembers');
    $template->replace('{MESSAGE}', $ourlang);
    $template->replace("{DESTINATION}", $returnto);
    if ($amember->avatarname != '') @unlink($settings->uploadpath . $amember->avatarname);
   }
   else
   {
    $fields = explode(',', $settings->memberfields);
    $num = sizeof($fields);
    for ($x=0; $x<$num; $x++)
    {
    if (is_array($$fields[$x])) { $$fields[$x] = implode('|||', $$fields[$x]); $$fields[$x] = str_replace('selected', '', $$fields[$x]); }
    }
    $test = $_FILES['newavatar']['name'];
    if ($test != '')
    { 
     $avatar = $_FILES['newavatar']['name'];
     $avatarname = uploadavatar('newavatar', 'randomize');
     if (!$avatarname) { $incomplete = true; $incompletemessage = $language->uploadfailed; }
    }
    $existing = $db->select('name,email', 'memberstable', "id != $id", '', '');
	$n = $db->numrows($existing);
	for($x=0; $x<$num; $x++)
	{
	 $row = $db->row($existing);
	 $currentmems .= '|||'. $row[0] .'|||';
	 $currentemail .= '|||'. $row[1] .'|||';
	}
	if (($name != '') && strstr($currentmems, '|||'. $name .'|||'))
	{
     $incomplete = true;
     $incompletemessage = $language->profile_nametaken;
	}	 
	else if (($email != '') && (strstr($currentemail, '|||'. $email .'|||')))
	{
     $incomplete = true;
     $incompletemessage = $language->register_emailtaken;	 
	}
	// check for incompletes
    $requiredmems = explode(',', $settings->requiredmembers);
    $y = sizeof($requiredmems);
    for ($x=0; $x<$y; $x++)
    { 
     if ($$requiredmems[$x] == '' && $requiredmems[$x] != '' && $requiredmems[$x] != 'password') { $incomplete = true; $incompletemessage = $language->suggest_incomplete; }
     $reqmemlist .= '|||'. $requiredmems[$x] .'|||';
    }
   if ($thismember->isadmin() && $settings->adminbypass == 'yes') $incomplete = false;
    if ($incomplete)
    {
     $themem = new member('new', 'new');
	 $themem->language = $thelanguage;
	 $themem->template = $thetemplate;
	 $language = $origlang;
     $template = new template("../$templatesdir/editmembers.tpl");
     $template->text = memberreplacements($template->text, $themem);
     $template->replace('{USERGROUPOPTIONS}', $themem->usergroupoptions);	  
     $template->replace('{INCOMPLETE}', $incompletemessage);
    }
    else
    {
     $fieldlist = explode(',', $settings->memberfields);
     $num = sizeof($fieldlist);
     $amember = new member('id', $id);
     for ($count=0; $count<$num; $count++)
     { 
      if ((stristr($varslist, '|||'. $fieldlist[$count] .'|||') && ($$fieldlist[$count] != $amember->$fieldlist[$count])) || (strstr($forcelist, $fieldlist[$count])))
      {
       $amember->$fieldlist[$count] = $$fieldlist[$count];
       $amember->update($fieldlist[$count]);
      }
      $amember->lastedit = time();
      $amember->update('lastedit');
     }
     if ($test)
     {
      $amember->avatarname = $avatarname;
      $amember->update('avatarname');
     }

     if (strstr($varslist, '|||thelanguage|||')) { $amember->language = $thelanguage; $amember->update('language'); }
     if (strstr($varslist, '|||thetemplate|||')) { $amember->template = $thetemplate; $amember->update('template'); }
     if ($replacepassword != '' && strstr($varslist, '|||replacepassword|||')) { $amember->password = md5($replacepassword); $amember->update('password'); if ($amember->id == $thismember->id) makecookie ('wsnpass', $amember->password, (time() + 3000000)); }
     $language = $origlang;
     if (!$template) $template = new template("../$templatesdir/redirect.tpl");
     $template->replace('{MESSAGE}', $language->profile_updated);  
     if ($aftereditprofile == '') $aftereditprofile = $returnto;   
     $template->replace('{DESTINATION}', $aftereditprofile);
    }
   }
  }
  else
  { 
   $memberinfo = getmemberinfo($field, $fieldvalue, $condition);
   $nummatching = $db->numrows($memberinfo);
   if ($nummatching > 1)
   {
    if (!$template) $template = new template("../$templatesdir/searchmembers.tpl");
	$subarea = templateextract($template->text, '<!-- BEGIN SEARCH members RESULTS -->', '<!-- END SEARCH members RESULTS -->');
    $template->replace($subarea, '{SUBAREA}');
    for ($count=0; $count<$nummatching; $count++)
    {
     $row = $db->row($memberinfo);
	 $amember = new member('row', $row);
     $displaymatches .= memberreplacements($subarea, $amember);
    }
	$template->replace('{SUBAREA}', $displaymatches);
	$template->replace('{PREVIOUS}', '');
	$template->replace('{NEXT}', '');	
	$template->replace('{CURRENTPAGE}', '1');	
	$template->replace('{NUMRESULTS}', $nummatching);	
	$template->replace('{SEARCHTERM}', $fieldvalue);		
   }
   else if ($nummatching == 1)
   { // only one match so go direct to edit form
    if (!$template) $template = new template("../$templatesdir/editmembers.tpl");
    $row = $db->row($memberinfo);
	$amember = new member('row', $row);
	$template->text = memberreplacements($template->text, $amember);
	$query = $db->select('id,title', 'membergroupstable', 'id>0', 'ORDER BY id ASC', '');
	$num = $db->numrows($query);
	for ($x=0; $x<$num; $x++)
	{
	 $next = $db->row($query);
	 if ($next[0] == $amember->usergroup)
	   $usergroupoptions .= '<option value="'. $next[0] .'" selected>'. $next[1] .'</option>';
	 else
	   $usergroupoptions .= '<option value="'. $next[0] .'">'. $next[1] .'</option>';
	}
	$template->replace('{USERGROUPOPTIONS}', $usergroupoptions);
	$template->replace('{LANGUAGEOPTIONS}', langoptions($amember->language));
	$template->replace('{TEMPLATEOPTIONS}', tempoptions($amember->template));
        $template->replace('{INCOMPLETE}', '');		
   }
   else
   {
    if (!$template) $template = new template("../$templatesdir/redirect.tpl");
    $template->replace('{MESSAGE}', $language->search_nomatch);
    if (strlen($returnto) < 10) $returnto = '../index.php';   
    $template->replace('{DESTINATION}', $returnto);
   }
  }
 }

 else if ($action == 'category')
 { // edit info of a category
  if ($filled)
  {
    if ($delete == 'on')
    {
     $ourcat = new category('id', $id);
     $result = $ourcat->deletethis();	
     if (!$template) $template = new template("../$templatesdir/redirect.tpl");
     $ourlang = $language->edit_deletecat;
     $template->replace('{MESSAGE}', $ourlang);
     if ($afterdelcat == '') $afterdelcat = $returnto;
     $template->replace('{DESTINATION}', $afterdelcat);
    }
   else
   { 
    $fields = explode(',', $settings->categoryfields);
    $num = sizeof($fields);
    for ($x=0; $x<$num; $x++)
    {
     if (is_array($$fields[$x])) { $$fields[$x] = implode('|||', $$fields[$x]); $$fields[$x] = str_replace('selected', '', $$fields[$x]); }
    }
    // check for incompletes
    $requiredcategory = explode(',', $settings->requiredcategories);
    $y = sizeof($requiredcategory);
    for ($x=0; $x<$y; $x++)
    {
     if ($$requiredcategory[$x] == '' && $requiredcategory[$x] != '') { $incomplete = true; $incompletemessage = $language->suggest_incomplete; $reqcatlist .= '|||'. $requiredcategory[$x] .'|||'; }
    }
    if ($thismember->isadmin() && $settings->adminbypass == 'yes') $incomplete = false;
    if ($incomplete)
    {
     if ($incompletemessage == '') $incompletemessage = $language->suggest_incomplete;
     $thecat = new category('new', 'noid');
     if (is_array($related)) $thecat->related = implode('|||', $related);
     $thecat->related = str_replace('selected', '', $thecat->related);	 
     if (is_array($permissions)) $thecat->permissions = implode('|||', $permissions);
     $thecat->permissions = str_replace('selected', '', $thecat->permissions);	 
     $template = new template("../$templatesdir/editcat.tpl");
     $template->text = categoryreplacements($template->text, $thecat);
     $template->replace('{OLDCATID}', $oldcategory);
     $template->replace('{INCOMPLETE}', $incompletemessage);
     $template->replace('{CATTYPEOPTIONS}', $thecat->typeoptions());
     $template->replace('{MIXTYPESOPTIONS}', defaultyesno($thecat->mixtypes));
    }
    else
    { 
     if (is_array($related)) $related = implode('|||', $related);
     $related = str_replace('selected', '', $related);
     if (is_array($permissions)) $permissions = implode('|||', $permissions);
     $permissions = str_replace('selected', '', $permissions);	 


     $forcelist .= '|||related|||'; // always update related selector since it's a multiselector and if blank we want to update to blank
     $fieldlist = explode(',', $settings->categoryfields);
     $num = sizeof($fieldlist);
     $ourcat = new category('id', $id);
     for ($count=0; $count<$num; $count++)
     { 
      if ((stristr($varslist, '|||'. $fieldlist[$count] .'|||') && ($$fieldlist[$count] != $ourcat->$fieldlist[$count])) || (strstr($forcelist, '|||'. $fieldlist[$count] .'|||')))
      {
       $ourcat->$fieldlist[$count] = $$fieldlist[$count];
       $ourcat->update($fieldlist[$count]);
	   $updatedfields .= ' '. $fieldlist[$count] .' ';
      }
     }
     if (strstr($updatedfields, ' name ')) $ourcat->lastedit = time();
     $ourcat->update('lastedit');

     if (!$template) $template = new template("../$templatesdir/redirect.tpl");
     $ourlang = $language->admin_editcategory;
     $template->replace('{MESSAGE}', $ourlang);
     if ($aftereditcat == '') $aftereditcat = $returnto;   
     $template->replace('{DESTINATION}', $aftereditcat);

     // update parentnames and numlinks if subcategory moved
     if ($parent != $oldcategory)
     {
      $ourcat->updateparents();
      $ourcat->update('parentids,parentnames'); 
	  if ($ourcat->id > 0) updatelinktotals($ourcat->id);
      $oldcat = new category('id', $oldcategory);
	  if ($oldcat->id > 0) updatelinktotals($oldcat->id);	  
      $oldcat->numsub = $oldcat->calcnumsub();
      $oldcat->update('numsub');
      if ($parent > 0)
      {
       $parcat = new category('id', $parent);
       $parcat->numsub = $parcat->calcnumsub();
       $parcat->update('numsub');
      }	  
      updatecategoryselector();
     }
    }
   }
  }
  else
  { 
   $catinfo = getcatinfo($field, $fieldvalue, $condition);
   $nummatching = $db->numrows($catinfo);
   if ($nummatching > 1)
   {
    if (!$template) $template = new template("../$templatesdir/searchcats.tpl");
	$subarea = templateextract($template->text, '<!-- BEGIN SEARCH categories RESULTS -->', '<!-- END SEARCH categories RESULTS -->');
    $template->replace($subarea, '{SUBAREA}');
    for ($count=0; $count<$nummatching; $count++)
    {
     $row = $db->row($catinfo);
	 $thiscat = new category('row', $row);
     $displaymatches .= categoryreplacements($subarea, $thiscat);
    }
    $template->replace('{SUBAREA}', $displaymatches);
	$template->replace('{PREVIOUS}', '');
	$template->replace('{NEXT}', '');	
	$template->replace('{CURRENTPAGE}', '1');	
	$template->replace('{NUMRESULTS}', $nummatching);	
	$template->replace('{SEARCHTERM}', $fieldvalue);		
   }
   else if ($nummatching == 1)
   { 
    // get info for category
	$idquery = $db->row($catinfo);
	$thiscat = new category('row', $idquery);
    // display a form through which everything can be modified
    if (!$template) $template = new template("../$templatesdir/editcat.tpl");
    $template->text = categoryreplacements($template->text, $thiscat);
	$selector = catselector($thiscat->parent, 'parent');
    $template->replace('{CATSELECTOR}', $selector);
    $template->replace('{RELATEDCATSELECTOR}', relatedcatselector($thiscat->related));
    $template->replace('{CATPERMISSIONSOPTIONS}', $thiscat->permissionsoptions());
	$hideyesno = yesno($thiscat->hide);
	$template->replace('{HIDESELECTOR}', $hideyesno);
    $template->replace('{RETURNTO}', $returnto);
	$template->replace('{OLDCATID}', $thiscat->parent);
    $template->replace('{CATTYPEOPTIONS}', $thiscat->typeoptions());
    $template->replace('{MIXTYPESOPTIONS}', defaultyesno($thiscat->mixtypes));
    $template->replace('{INCOMPLETE}', '');	
   }
   else
   {
    if (!$template) $template = new template("../$templatesdir/redirect.tpl");
	$nomatch = str_replace('links', 'categories', $language->search_nomatch);	
    $template->replace('{MESSAGE}', $nomatch);
    $template->replace('{DESTINATION}', $returnto);
   }
  }
 }
 else if ($action == 'comment')
 { // edit info of a comment
  if ($filled)
  {
   if ($delete=='on')
   {
    $ourcomment = new comment('dummy', $id);
    $result = $ourcomment->deletethis();
    if (!$template) $template = new template("../$templatesdir/redirect.tpl");
    $ourlang = $language->edit_deletecomment;
    $template->replace('{MESSAGE}', $ourlang);
    if ($afterdelcom == '') $afterdelcom = $returnto;
    $template->replace('{DESTINATION}', $afterdelcom);
    if ($scriptname == 'wsnlinks')
    {
     $settings->totalcomments -= 1;
     $settings->update('totalcomments');
    }
   }
   else
   {
    $fields = explode(',', $settings->commentfields);
    $num = sizeof($fields);
    for ($x=0; $x<$num; $x++)
    {
     if (is_array($$fields[$x])) { $$fields[$x] = implode('|||', $$fields[$x]); $$fields[$x] = str_replace('selected', '', $$fields[$x]); }
    }
    // check for incompletes
    $requiredcoms = explode(',', $settings->requiredcomments);
    $y = sizeof($requiredcoms);
    for ($x=0; $x<$y; $x++)
    {
     if ($$requiredcoms[$x] == '' && $requiredcoms[$x] != '') { $incomplete = true; $incompletemessage = $language->suggest_incomplete; }
     $reqcomlist .= '|||'. $requiredcoms[$x] .'|||';
    }
    if ($thismember->isadmin() && $settings->adminbypass == 'yes') $incomplete = false;
    if ($incomplete)
    {
     $thecom = new comment('new', 'noid');
     $thelink = new oneline('id', $thecom->linkid);
     $template = new template("../$templatesdir/editcomments.tpl");
     $template->text = commentreplacements($template->text, $thecom);
     $template->text = linkreplacements($template->text, $thelink);
     $template->replace('{HIDEHIDEYESNO}', yesno($thecom->hide));
     $template->replace('{INCOMPLETE}', $incompletemessage);
    }
    else
    {
     $fieldlist = explode(',', $settings->commentfields);
     $num = sizeof($fieldlist);
     $ourcomment = new comment('id', $id);
	 $ourcomment->id = $id;
     for ($count=0; $count<$num; $count++)
     { 
      if ((stristr($varslist, '|||'. $fieldlist[$count] .'|||') && ($$fieldlist[$count] != $ourcomment->$fieldlist[$count])) || (strstr($forcelist, $fieldlist[$count])))
      {
       $ourcomment->$fieldlist[$count] = $$fieldlist[$count];
       $ourcomment->update($fieldlist[$count]);
      }
      $ourcomment->lastedit = time();
      $ourcomment->update('lastedit');
     }
     if (!$template) $template = new template("../$templatesdir/redirect.tpl");
     $ourlang = $language->admin_editcomment;
     $template->replace('{MESSAGE}', $ourlang);
     if ($aftereditcom == '') $aftereditcom = $returnto;   
     $template->replace('{DESTINATION}', $aftereditcom);
     calccommenttypeorder();
    }
   }
  }
  else
  { 
   $commentinfo = getcommentinfo($field, $fieldvalue, $condition);
   $nummatching = $db->numrows($commentinfo);
   if ($nummatching > 1)
   {
    if (!$template) $template = new template("../$templatesdir/searchcomments.tpl");
	$subarea = templateextract($template->text, '<!-- BEGIN SEARCH comments RESULTS -->', '<!-- END SEARCH comments RESULTS -->');
    $template->replace($subarea, '{SUBAREA}');	
    for ($count=0; $count<$nummatching; $count++)
    {
     $row = $db->row($commentinfo);
	 $thiscomment = new comment('row', $row);
     $displaymatches .= commentreplacements($subarea, $thiscomment);
    }
	$template->replace('{SUBAREA}', $displaymatches);
	$template->replace('{PREVIOUS}', '');
	$template->replace('{NEXT}', '');	
	$template->replace('{CURRENTPAGE}', '1');	
	$template->replace('{NUMRESULTS}', $nummatching);	
	$template->replace('{SEARCHTERM}', $fieldvalue);		
   }
   else if ($nummatching == 1)
   {
    // get info for comment
	$idquery = $db->row($commentinfo);
	$thiscomment = new comment('row', $idquery);
    $thelink = new onelink('id', $thiscomment->linkid);
    // display a form through which everything can be modified
    if (!$template) $template = new template("../$templatesdir/editcomments.tpl");
    $template->text = commentreplacements($template->text, $thiscomment);
    $template->replace('{HIDEYESNO}', yesno($thiscomment->hide));
    $template->text = linkreplacements($template->text, $thelink);
    $template->replace('{INCOMPLETE}', '');
   }
   else
   {
    if (!$template) $template = new template("../$templatesdir/redirect.tpl");
	$nomatch = str_replace('links', 'comments', $language->search_nomatch);
    $template->replace('{MESSAGE}', $nomatch);
    if (strlen($returnto) < 10) $returnto = '../index.php';
    $template->replace('{DESTINATION}', $returnto);
   }
  }
 }
}
require 'adminend.php';

?>