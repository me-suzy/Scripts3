<?php
require 'start.php';
$nomember = true;

if ($returnto == '') $returnto = 'index.php';

if (($action=='addcat') && ($thismember->groupcansubmitcategories))
{
 if ($filled)
 { 
  if (!$template) $template = new template('suggestcat.tpl');
  $fields = explode(',', $settings->categoryfields);
  $num = sizeof($fields);
  for ($x=0; $x<$num; $x++)
  {
   if (is_array($$fields[$x])) { $$fields[$x] = implode('|||', $$fields[$x]); $$fields[$x] = str_replace('selected', '', $$fields[$x]); }
  }
  $area = $language->title_divider . $language->title_suggestnewcat;
  $requiredcategories = explode(',', $settings->requiredcategories);
  $y = sizeof($requiredcategories);
  for ($x=0; $x<$y; $x++)
  {
   if ($$requiredcategories[$x] == '' && $requiredcategories[$x] != '') $incomplete = true;
  }
  if ($thismember->isadmin() && $settings->adminbypass == 'yes') $incomplete = false;
  if ($incomplete)
  {
   $blankcat = new category('new', 'new');
   $template->replace('{RELATEDCATSELECTOR}', relatedcatselector($blankcat->related));
   $hideyesno = yesno($blankcat->hide);
   $template->replace('{HIDESELECTOR}', $hideyesno);
   if ($parent == '') $parent = 0;
   $selector = catselector($parent, "parent");
   if ($isalbum)
   {
    $getit = $db->select('name', 'categoriestable', "id=$parent", '', '');
    $parname = $db->rowitem($getit);   
    $selector = '<option value="'. $parent .'">'. $parname .'</option>';
   }
   $template->replace('{CATSELECTOR}', $selector);
   if ($parent > 0) $template->replace('{NAVIGATION}', shownav($parent));
   else $template->replace('{NAVIGATION}', '');
   $template->replace('{CATNAV}', shownav($parent));
   $template->replace('{INCOMPLETE}', $language->suggest_incomplete);
   $template->text = categoryreplacements($template->text, $blankcat);
   $template->replace('{MIXTYPESOPTIONS}', defaultyesno($blankcat->mixtypes));
   if (!$isalbum) $template->replace('{ISALBUM}', '0');      
  }  
  else
  {
   // is validation on or off?
   if (autovalidate('category', $thismember->usergroup))
      $validated = 1;
   else
      $validated = 0;
   if ($parent > 0)
   {
    $thecat = new category('id', $parent);
    if ($thecat->ismoderator($thismember) && $settings->modapprovecats == 'yes') $validated = 1;	  
   }
   // insert all the category data into the database
   $thiscategory = new category('new', '0');
   $thiscategory->add();
   $ourid = mysql_insert_id();
   $thiscategory->id = $ourid;

   $adminaddress = $settings->email;
   if ( ($settings->notify == 'yes') && ($adminaddress != '') && ($validated != 1) )
   {
    $catdata = '
	Name: '. $thiscategory->name .'
	Description: '. $thiscategory->description;
    $subject = $language->email_newsuggestiontitle;
    $message = $language->email_newsuggestionbody;
    $message = str_replace('{TYPE}', 'category', $message);   
    $message = str_replace('{DIRURL}', $settings->dirurl, $message);
    $message = categoryreplacements($message, $thiscategory);
    sendemail("$adminaddress", "$subject", $message . $catdata, "From: $adminaddress");
   } 
   if ($validated == 1)
   {
    if ($thiscategory->parent > 0)
    {
     $parentcat = new category('id', $thiscategory->parent);
     $parentcat->numsub = $parentcat->numsub + 1;
     $parentcat->update('numsub');
     if ($settings->email != '') $parentcat->sendsubscriptions();
    }
    updatecategoryselector();
   }
   // thank you and good night
   $template = new template('redirect.tpl');
   $template->replace('{MESSAGE}', $language->suggest_cat);
   if ($aftercat == '') $aftercat = $returnto;
   $template->replace('{DESTINATION}', $aftercat); 	
  }
 }
 else
 {
  if (!$template) $template = new template('suggestcat.tpl');
  $template->replace('{RELATEDCATSELECTOR}', relatedcatselector(''));
  $hideyesno = yesno('no');
  $template->replace('{HIDESELECTOR}', $hideyesno);
  if ($parent == '') $parent = 0;
  $selector = catselector($parent, "parent");
  $template->replace('{CATSELECTOR}', $selector);
  if ($parent > 0) $template->replace('{NAVIGATION}', shownav($parent));
  else $template->replace('{NAVIGATION}', '');
  if ($parent > 0) $template->replace('{CATNAV}', shownav($parent));
  else $template->replace('{NAVIGATION}', '');
  $template->replace('{INCOMPLETE}', '');
  $blankcat = new category('blank', 'blank');
  $template->text = categoryreplacements($template->text, $blankcat);
  $template->replace('{MIXTYPESOPTIONS}', defaultyesno($blankcat->mixtypes));
  if (!$isalbum) $template->replace('{ISALBUM}', '0');      
 }
}

else if (($action=='addlink') && ($thismember->groupcansubmitlinks))
{ 
 if ($isalbum) $inalbum = 1; else $inalbum = 0;
 $area = $language->title_divider . $language->title_suggestnewlink;
 if ($filled)
 {
  if ($sitelist)
  {
   if (is_array($sitelist)) $sitelist = implode(',', $sitelist);
   $sitelist = explode(',', $sitelist);
   $querystring .= 'suggest.php?action=addlink&filled=1';
   foreach ($_POST as $key => $value)
   { 
    $value = urlencode(stripslashes($value)); 
    if (($key != 'filled') && ($key != 'sitelist')) $querystring .= "&$key=$value";
   }
   $num = sizeof($sitelist);
   for ($q=0; $q<$num; $q++)
   {
    // trigger add link with this info on other site by loading URL
    $submissionstring = $sitelist[$q] . $querystring;
    if (($debug > 0) && ($debug < 4)) echo "getting the url $submissionstring <br>";
    sendtosite($submissionstring);
   }  
  }

  if (1 == 1)
  {
   $time = time(); 
   // register if they want to
   if (($name != '') && ($password != ''))
   {
    // First, see if username is taken
    $query = $db->select('name,email', 'memberstable', 'id>0', '', '');
    $num = $db->numrows($query);
    for ($x=0; $x<$num; $x++)
    {
     $row = $db->row($query);
     $list .= '|||'. $row[0] .'|||';
     $otherlist .= '|||'. $row[1] .'|||';  
    }
    if (($debug > 0) && ($debug < 4)) echo "Comparing |||$name||| with $list <br>";
    if (($debug > 0) && ($debug < 4)) echo "Comparing |||$email||| with $otherlist <br>"; 
    if (strstr($list, '|||'. $name .'|||'))
    {
     $incomplete = true;
     $incompletemessage = $language->register_nametaken;
    }
    else if (($email == '' || (strstr($otherlist, $email))) && (strstr($reqmems, 'email')))
    {
     $incomplete = true;
     $incompletemessage = $language->register_emailtaken;
    }
    else
    {
     $links = 0;
     $comments = 0;
     $templang = $origlang;
     if ($settings->registration == 'direct')
     {
      $valid = 1;
      $correctlang = $templang->register_worked;
     }
     else
     {
      $valid = 0;
      $correctlang = $templang->register_awaitvalidation;
     }
     $ip = $HTTP_SERVER_VARS['REMOTE_ADDR'];
     $usergroup = 2;
     $amember = new member('new', 'blank');
     $amember->validated = $valid;
     if ($thetemplate == '') $thetemplate = $settings->templatesdir;
     $amember->template = $thetemplate;
     if ($thelanguage == '') $thelanguage = $settings->defaultlang;
     $amember->language = $thelanguage;
     $amember->time = $time;
     $amember->register();
     $query = $db->select('id', 'memberstable', "time='$time'", '', '');
     $amember->id = $db->rowitem($query);
     $language = new language($languagegroup);
     if ($settings->registration == 'email')
     {
      sendactivationcode($amember);
      $correctlang = $language->register_activationsent;
     }
     if (!$HTTP_COOKIE_VARS['testcookie']) $correctlang = $language->register_needcookies;
     $correctlang .= '<br>'. $language->suggest_link;
    }
    $q = $db->select('id', 'memberstable', "time='$time'", '', '');
    $ownerid = $db->rowitem($q);
   }
   // now add link
   $ip = $HTTP_SERVER_VARS['REMOTE_ADDR'];
   $fields = explode(',', $settings->linkfields);
   $num = sizeof($fields);
   for ($x=0; $x<$num; $x++)
   {
    if (is_array($$fields[$x])) { $$fields[$x] = implode('|||', $$fields[$x]); $$fields[$x] = str_replace('selected', '', $$fields[$x]); }
   }

   $requiredlinks = explode(',', $settings->requiredlinks);
   $y = sizeof($requiredlinks);
   for ($x=0; $x<$y; $x++)
   {
    if ($$requiredlinks[$x] == '' && ($requiredlinks[$x] == 'name' || $requiredlinks[$x] == 'password')) 
      { if ($thismember->usergroup == 1) $incomplete = true; }
    else
      { if ($$requiredlinks[$x] == '' && $requiredlinks[$x] != '') $incomplete = true; }
   }
   if ( (strstr($settings->requiredlinks, 'email')) && (!strstr($email, '@')) )
   {
    $incomplete = true;
    $incompletemessage .= $language->suggest_invalidemail;
   }
   if ($settings->checkfordup == 'yes')
   { 
    if (($settings->adminbypass != 'yes' || !$thismember->isadmin()) && (isduplicate($url, 'soft') || ($settings->checkfordupdomain == 'yes' && isduplicate($url, 'hard'))))
    {
	$incomplete = true;
	$incompletemessage .= $language->suggest_duplicate;
    }
   }
   if (($thismember->grouplimitlinks > 0) && ($thismember->links > $thismember->grouplimitlinks))
   {
    $incomplete = true;
    $incompletemessage .= $language->suggestlink_limit;
   }
   if ($thismember->grouplimitlinksdaily > 0) 
   {
    $yesterday = time() - 86400;
    $numnew = $db->numrows($db->select('id', 'linkstable', 'ownerid='. $thismember->ownerid .' AND time > '. $yesterday, '', ''));
    if ($numnew > $thismember->grouplimitlinksdaily)
    {
     $incomplete = true;
     $incompletemessage .= $language->suggestlink_limitdaily;
    }
   }
   $filetitle = $_FILES['filetitle']['name'];
   if ($filetitle != '')
   {
    if (strstr('jpg gif png bmp swf psd tiff iff jp2 jpc swc', extension($filetitle)))
    { // it's an image, so check size
     $info = @getimagesize($_FILES['filetitle']['tmp_name']); 
     if (($info[0] > 0 && $info[1] > 0) && !($info[0] > $settings->maximagewidth || $info[1] > $settings->maximageheight))
      $filename = uploadfile('filetitle', 'randomize');   
    }
    else $filename = uploadfile('filetitle', 'randomize');
    if (!$filename)
    { // incomplete screen
     $incomplete = true;
     $incompletemessage = settingsreplacements($language->uploadfailed);
    }
   }
   if ($thismember->isadmin() && $settings->adminbypass == 'yes') $incomplete = false;
   if ($incomplete)
   {
    if (!$template) $template = new template('suggestlink.tpl');
    if ($incompletemessage == '') $incompletemessage = $language->suggest_incomplete;
    $template->replace('{INCOMPLETE}', $incompletemessage);
	if ($notify) $template->replace('{LINKNOTIFY}', 'checked');
	else $template->replace('{LINKNOTIFY}', '');
    $hideselector = yesno($hide);
    $template->replace('{LINKHIDESELECTOR}', $hideselector);
    $template->replace('{TYPEOPTIONS}', typeselector($type));
    $template->replace('{ALLOWEDEXTENSIONS}', $settings->filetypes);
    if ($catid == '') $catid = 0;
    $selector = makeselection($settings->categoryselector, $catid);
    if ($isalbum)
	{
	 $ourcategory = new category('id', $catid);
	 $selector = '<option value="'. $catid .'">'. $ourcategory->name .'</option>';  
	}
    $template->replace('{CATSELECTOR}', $selector);

    if ($catid > 0)
    { 
     $ourcategory = new category('id', $catid);
     $template->replace('{NAVIGATION}', shownav($ourcategory));
     $template->text = categoryreplacements($template->text, $ourcategory);
    }
    else
    { 
     $template->replace('{NAVIGATION}', '');
     $ourcategory = new category('blank', 'blank');
     $template->text = categoryreplacements($template->text, $ourcategory);
    }
    $blanklink = new onelink('new', 'new');
    $template->text = linkreplacements($template->text, $blanklink);
    if (!$isalbum) $template->replace('{ISALBUM}', '0');      	
   }
   else
   {
    // is validation on or off?
    if (autovalidate('link', $thismember->usergroup))
      $validated = 1;
    else
      $validated = 0;
    $thecat = new category('id', $catid);
    if ($thecat->ismoderator($thismember) && $settings->modapprove == 'yes') $validated = 1;
    $id = '';
    if (is_numeric($expire) && $expire > 0) $expire = time() + ($expire * 86400);
    $votes = $HTTP_POST_VARS['votes'];
    $rating = $HTTP_POST_VARS['rating'];
    $hitsin = $HTTP_POST_VARS['hitsin'];
    if ($votes == '') $votes = 0;
    if ($rating == '') $rating = 0;
    if ($hide == '') $hide = 'no';	 	 
    $hits = $HTTP_POST_VARS['hits'];
    if ($hits == '') $hits = 0;
	$sumofvotes = $rating * $votes;
    if ($hitsin == '') $hitsin = 0;
    if ($ownerid == '') $ownerid = $thismember->id;
    if ($type == '') $type = current(explode($settings->linktypes));
    $thislink = new onelink('new', 'new');
    $result = $thislink->add();
    if ($notify) $thislink->subscribe($email);
    if ($validated == 1) $thismember->addlink(); 
    // update category lastlinktime
    if ($validated == 1)
    {
     $thiscat = new category('id', $thislink->catid);
     if ($settings->email != '') $thiscat->sendsubscriptions();
     $parentlist = $thiscat->id;
     if ($thiscat->parentids != '') $parentlist .= '|||'. $thiscat->parentids;
     $parentarray = explode('|||', $parentlist);
     $num = sizeof($parentarray);
     for ($count=0; $count<$num; $count++)
       $updateit = $db->update('categoriestable', 'lastlinktime', time(), "id=$parentarray[$count]");
    }
    // end update cat lastlinktime
	calctypeorder(); // set its type's order
    $template = new template('redirect.tpl');
    $template->replace('{MESSAGE}', $language->suggest_link);
    if ($afterlink == '') $afterlink = $returnto;
    $template->replace('{DESTINATION}', $afterlink);
   }
  }
 } 
 else
 {
  if (!$template) $template = new template('suggestlink.tpl');
  $hideselector = yesno('no');
  $template->replace('{LINKNOTIFY}', '');  
  $template->replace('{LINKHIDESELECTOR}', $hideselector);
  $template->replace('{TYPEOPTIONS}', typeselector('suggestlinkpage'));
  $template->replace('{LINKDESCRIPTION}', '');
  $template->replace('{LINKKEYWORDS}', '');
  $template->replace('{ALLOWEDEXTENSIONS}', $settings->filetypes);
  if ($catid == '') $catid = 0;
  $selector = makeselection($settings->categoryselector, $catid);
  if ($isalbum)
  {
   $ourcategory = new category('id', $catid);
   $selector = '<option value="'. $catid .'">'. $ourcategory->name .'</option>';  
  }
  $template->replace('{ISALBUM}', $isalbum);
  $template->replace('{CATSELECTOR}', $selector);
  $ourcategory = new category('id', $catid);
  $template->replace('{NAVIGATION}', shownav($ourcategory));
  $template->replace('{INCOMPLETE}', '');
  $template->text = categoryreplacements($template->text, $ourcategory);
  $blanklink = new onelink('blank', 'blank');
  $template->text = linkreplacements($template->text, $blanklink);
  $template->text = settingsreplacements($template->text);  
 }
}
else
{
 // not allowed to submit
 if (!$template) $template = new template("blank");
 $template->text = $language->noaccess; 
}


require 'end.php';
?>