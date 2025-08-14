<?php
require 'adminstart.php';

$templatedata = str_replace("\n\n", "\n", $templatedata);

if ($thismember->isadmin())
{ // user has correct password, so they can enter
 if ($action == 'changememberstyle')
 {
  $thismember->stylesheet = $TID;
  $thismember->update('stylesheet');
  $settings->stylesheet = $TID;
 }
 
  if ($filled)
  {
if ($demomode) die("This is a demo. You are not allowed to change templates in the demo, since PHP can be inserted in any template and you could potentially harm the database. Please click your browser's back button.");
   if ($preview)
   {
    $templatedata = stripslashes(decodeit($templatedata));
	$template = new template("blank");
    filewrite("../$templatesdir/blank.tpl", $templatedata);
    $templatedata = encodeit($templatedata);
    if (!$width) $width = 800;
    if (!$height) $height = 600;
    echo "<p>Below is a frame which contains your template as it should look at $width x $height. Note that this is only a general preview of the HTML, so none of the specific data is in it... in actual usage, data will be filled in. This is simply to give you an idea of what your design will look like with this template within the context of your header and footer.</p>

<iframe width=$width height=$height src=";
if (strstr($tempfile, 'register')) 
 echo "../register.php?";
else if (strstr($tempfile, 'displaylinks')) 
 echo "../index.php?action=displaycat&catid=1&";
else if (strstr($tempfile, 'viewcomments')) 
 echo "../comments.php?id=1&";
else if (strstr($tempfile, 'login')) 
 echo "../index.php?action=userlogin&";
else if (strstr($tempfile, 'report')) 
 echo "../report.php?id=1&";
else if (strstr($tempfile, 'email')) 
 echo "../email.php?id=1&";
else if (strstr($tempfile, 'memberlist')) 
 echo "../memberlist.php?";
else if (strstr($tempfile, 'viewprofile')) 
 echo "../memberlist.php?action=profile&id=1&";
else if (strstr($tempfile, 'searchadvanced')) 
 echo "../search.php?";
else if (strstr($tempfile, 'suggestlink')) 
 echo "../suggest.php?action=addlink&";
else if (strstr($tempfile, 'suggestcat')) 
 echo "../suggest.php?action=addcat&";
else
 echo "../index.php?";
echo "custom=yes&TID=blank></iframe>
 <p>Note: If the frame does not display a preview correctly, you need to chmod 666 your blank.tpl template file.</p> <p align=center><form action=templates.php?filled=1 method=post>
<input type=hidden name=tempfile value=$tempfile>
<textarea name=templatedata rows=15 cols=75>$templatedata</textarea><br>
<input type=checkbox name=preview>Preview again?<br>
<input type=submit value='Confirm Changes'></form></p>";
   die();
   }
   else
   {
    $templatedata = decodeit(stripslashes($templatedata));

    $check = filewrite("../$templatesdir/$tempfile", $templatedata);
    if ($check)
    {
     if ($TID == 'displaylinks') calctypeorder();
     if (!$template) $template = new template("../$templatesdir/redirect.tpl");
     $template->replace('{MESSAGE}', $language->admin_templateupdate);
     $extension = extension($tempfile);
     $tempbase = str_replace('.'. $extension, '', $tempfile);
     if ($reload) $template->replace('{DESTINATION}', "templates.php?TID=$tempbase&ext=$extension#edit");
     else $template->replace('{DESTINATION}', 'templates.php');
    }
    else
    {
     if (!$template) $template = new template("../$templatesdir/redirect.tpl");
     $template->replace('{MESSAGE}', $language->admin_chmod);
     $template->replace('{DESTINATION}', 'templates.php');
    }
   }
  }
  else
  {
   if (!$template) $template = new template("../$templatesdir/admin/templates.tpl");
   if ($action == 'download')
   {
    $TID = str_replace('#edit', '', $TID);
    $TID = str_replace('-', '/', $TID);
    $filename = $TID .'.'. $ext;
    admindownload(fileread("../$templatesdir/$filename"), str_replace('admin/', '', $filename));
    die("We ought to never get here if things are working.");
   }

   if ($TID)
   {  
    if ($ext == '') $ext = 'tpl';
	$tempfile = $TID .'.'. $ext;
	$tempfile = str_replace('-', '/', $tempfile);
    $template->replace('{TEMPFILE}', $tempfile);
    $templatedata = fileread("../$templatesdir/$tempfile");
    $templatedata = htmlspecialchars($templatedata);
    $templatedata = str_replace('}', '&#125;', $templatedata);
    $templatedata = str_replace('{', '&#123;', $templatedata);
    $template->replace('{TEMPLATEDATA}', $templatedata);
   }
   else
   {
        if ($action == 'changemembertemplate')
        {
         $thismember->template = $thetemplate;
         $thismember->update('template');
        }
        else if ($action == 'listvars')
	{
$skip = ' sendsubscriptions onelink hidealiases deletealiases subscribe category comment deletethis register add update reject validate updateparents calcnumsub getsubcats getsubcatsall linkdata linkdatabytype member login logout addlink addentry addcomment ';	
 $thelink = new onelink('blank', 'blank');
 $linkfuncarray = get_class_methods($thelink);
 foreach($linkfuncarray as $key) 
 {
  $tempvar = $pref . strtoupper($key) .'}';
  if (!strstr($skip, ' '. $key .' ')) $linkvars .= ' '. $tempvar;
 }
 $linkarray = get_object_vars($thelink);
 while(list($key, $value) = each($linkarray)) 
 {
  $tempvar = $pref . strtoupper($key) .'}';
  if (!strstr($skip, ' '. $key .' ')) $linkvars .= ' '. $tempvar;
 } 
 $thelink = new category('blank', 'blank');
 $linkfuncarray = get_class_methods($thelink);
 foreach($linkfuncarray as $key) 
 {
  $tempvar = '{CAT'. strtoupper($key) .'}';
  if (!strstr($skip, ' '. $key .' ')) $catvars .= ' '. $tempvar;
 }
 $linkarray = get_object_vars($thelink);
 while(list($key, $value) = each($linkarray)) 
 {
  $tempvar = '{CAT'. strtoupper($key) .'}';
  if (!strstr($skip, ' '. $key .' ')) $catvars .= ' '. $tempvar;
 } 
 $thelink = new comment('blank', 'blank');
 $linkfuncarray = get_class_methods($thelink);
 foreach($linkfuncarray as $key) 
 {
  $tempvar = '{COMMENT'. strtoupper($key) .'}';
  if (!strstr($skip, ' '. $key .' '))  $commentvars .= ' '. $tempvar;
 }
 $linkarray = get_object_vars($thelink);
 while(list($key, $value) = each($linkarray)) 
 {
  $tempvar = '{COMMENT'. strtoupper($key) .'}';
  if (!strstr($skip, ' '. $key .' '))  $commentvars .= ' '. $tempvar;
 } 
 $thelink = new member('blank', 'blank');
 $linkfuncarray = get_class_methods($thelink);
 foreach($linkfuncarray as $key) 
 {
  $tempvar = '{M&#69;MBER' . strtoupper($key) .'}';
  if (!strstr($skip, ' '. $key .' '))  $membervars .= ' '. $tempvar;
 }
 $linkarray = get_object_vars($thelink);
 while(list($key, $value) = each($linkarray)) 
 {
  $tempvar = '{M&#69;MBER' . strtoupper($key) .'}';
  if (!strstr($skip, ' '. $key .' '))  $membervars .= ' '. $tempvar;
 } 
	 $ourtemplatevariables = "All variables which are written into a URL, submitted by a form, or stored in a cookie are available in any template simply by using {VARIABLENAME}. You can easily pass your own custom values between pages in this manner.

There are many other template variables you may encounter, but these are the main ones available across many templates:

Link variables:
$linkvars

Category variables:
$catvars

Comment variables:
$commentvars

Member variables:
$membervars

Global template variables (work anywhere):
{&#67;&#65;TOPTIONS} (gives the options data for a dropdown menu of categories, for use within a selector)
{TOT&#65;LLINKS}, {TOTALC&#65;TEGORIES}, {TOT&#65;LHITS}, {L&#65;STUPDATE}

All member variables are usable globally. All language can also be used anywhere with a template variable of the form &#123;LANG_NAME}, where NAME is the upcase version of the name of that language. For example, &#123;LANG_TITLE_VOTE}. All settings work globally by using &#123;SETTINGNAME}.

Toplists work anywhere:
<!-- BEGIN TOPLIST 1 -->
<&#67;ONFIG>[links/categories/comments/members],[field],[number to display],[ascending/descending],[optional filtering condition], [optional starting number]</&#67;ONFIG>
<a href={LINKURL}>{LINKTITLE}</a>
<br>
<!-- END TOPLIST 1 -->
(All link/category/comment/member variables work in the toplists of their type)
";
     $template->replace('{TEMPLATEDATA}', stopvars($ourtemplatevariables));  
	}
	else
	{
     $template->replace('{TEMPLATEDATA}', $language->admin_selecttemplate);    
	}
    $template->replace('{FILENAME}', 'none');
	$template->replace('<input type="submit" value="Submit Changes">', '');   
$template->replace('<input type="submit" name="reload" value="Save and Reload">', '');   
   }
  }
 	 $template->replace('{TEMPLATEOPTIONS}', tempoptions($settings->templatesdir));
 	 $template->replace('{MEMTEMPLATEOPTIONS}', tempoptions($thismember->template));

 }

$template->replace('{TEMPLATEDATA}', $language->admin_selecttemplate);    
$template->replace('{CUSTOMTEMPLATES}', customtemplates());
$foredit = true;
$base = str_replace('.'. extension($file), '', $file);
if (strstr($templatesdir, '/')) $prefixit = '../styles/';
$template->replace('{STYLEEDITOPTIONS}', stylesheets($prefixit . $base . $thismember->stylesheet));
$foredit = false; 
$leaveencoded = true;
require 'adminend.php';

?>