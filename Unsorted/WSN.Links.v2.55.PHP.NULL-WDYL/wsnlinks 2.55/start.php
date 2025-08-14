<?php
/* This is stuff that goes at the top of a bunch of files.. */
error_reporting  (E_ERROR | E_WARNING | E_PARSE);

// start timing script execution, in case we're debugging
// (don't want to wait until we know, because that will throw off the time)
$mtime = microtime(); 
$mtime = explode(" ",$mtime); 
$mtime = $mtime[1] + $mtime[0]; 
$starttime = $mtime; 

require 'prestart.php';

$isalbum = 0;

// get category data so that things will speed up later
$data = explode('{ENDLINE}', $settings->sitemap);
$n1 = sizeof($data);
$done = 0;
for ($x=0; ($x<$n1); $x++)
{
 $row = explode('[,]', $data[$x]);
 $rowindex = $row[1];
 $parsedcats[$rowindex] = $row;
}

$thismember = new member('dummy', 'dummy');
if ($languagegroup == '') $languagegroup = $thismember->language;
if (($languagegroup == '' || $thismember->usergroup == 1) && (strstr($settings->languages, $HTTP_COOKIE_VARS['wsn_language']))) $languagegroup = $HTTP_COOKIE_VARS['wsn_language'];
if ($languagegroup == '') $languagegroup = $settings->defaultlang;
if (file_exists('languages/'. $languagegroup .'.lng')) $language = new language($languagegroup);
else $language = new language($settings->defaultlang);

if ($templatesdir == '') $templatesdir = $thismember->template;
if ($templatesdir == '') $templatesdir = $HTTP_COOKIE_VARS['templatesdir'];
if ($templatesdir == '') $templatesdir = $settings->templatesdir;
$customheader = str_replace('-', '/', $HTTP_GET_VARS['customheader'] .'.tpl');
if ($customheader == '.tpl') $customheader = 'header.tpl';
if (!file_exists($templatesdir .'/'. $customheader)) $templatesdir = $settings->templatesdir;
if (!file_exists($templatesdir .'/'. $customheader)) $templatesdir = getvalidtemplate();
$header = new template($customheader); // get header

// if we're in multilingual template set but they're using english only language, switch automatically to fullenglish
if(strstr($templatesdir, 'multilingual') && $language->linktypes == '') $language = new language("English");

$linkfields = $settings->linkfields;
$categoryfields = $settings->categoryfields;
$commentfields = $settings->commentfields;

if (!$linkfields) die("<p><font size=6><b>Cannot connect properly to your database. Most likely, you've forgotten to run the needed upgrade scripts.</b></font></p>");

// make compatable when register_globals is off... and secure against php code input
$skip = " newpasswordquery newusergroupquery ";
while(list($key, $value) = each($HTTP_COOKIE_VARS)) 
{
 if (!strstr($skip, " $key ")) $$key = stripcode($value);
} 
while(list($key, $value) = each($HTTP_GET_VARS)) 
{
 $getvarslist[] = $key;
 if (!strstr($skip, " $key ")) $$key = stripcode($value);
} 
while(list($key, $value) = each($HTTP_POST_VARS)) 
{
 $postvarslist[] = $key;
 if (!strstr($skip, " $key ")) $$key = stripcode($value);
 if (strstr($key, 'rating') && ($key != 'rating')) $customratings .= ','. $key;
} 
if (($settings->dirurl != '') && (!stristr($HTTP_COOKIE_VARS['returnto'], $settings->dirurl))) $returnto = '';
if ($returnto == '') $returnto = 'index.php';

if ($thismember->usergroup == 1) $stylesheet = $HTTP_COOKIE_VARS['stylesheet'];
if ($stylesheet != '') $settings->stylesheet = $stylesheet;
if ($thismember->stylesheet != '' && $thismember->usergroup > 1) $settings->stylesheet = $thismember->stylesheet;
$realstyle = 'templates/styles/'. end(explode('/', $settings->stylesheet)) .'.css';
if (!file_exists($realstyle)) $settings->stylesheet = '../styles/white';


// check cookie to see if this is the admin
$isadmin = false;
if ($thismember->isadmin()) $isadmin = true;

if ($action == 'settemplatesdir')
{ // if directory works, save it in cookie
  if ($header)
  {
   if ($thismember->usergroup > 1) { $thismember->template = $templatesdir; $thismember->update('template'); }
   else setcookie("templatesdir", "$templatesdir", time() + 8000000);
   $header = new template("header.tpl");
  }
 if ($returnto != '') header("Location: ". $returnto);
}

if ($action == 'setstyle')
{ 
 if ($thismember->usergroup > 1) { $thismember->stylesheet = $style; $thismember->update('stylesheet'); $settings->stylesheet = $style; }
 else { setcookie("stylesheet", "$style", time() + 8000000); $settings->stylesheet = $style; }
 if ($returnto != '') header("Location: ". $returnto);
}

if ($action == 'setlanguage')
{ 
  if ($thismember->usergroup > 1) { $thismember->language = $languagegroup; $thismember->update('language'); }
  else setcookie("wsn_language", "$languagegroup", time() + 8000000);
  $language = new language($languagegroup);
 if ($returnto != '') header("Location: ". $returnto);
}

if ($action == 'logout')
{
 $thismember->logout();
 if (!$template) $template = new template("$templatesdir/blank.tpl");
 $template->text = $language->loggedout;
 $loggedout = true; 
}

// allow date formats to be defined by language
if ($language->dateformat_regular != '') $settings->dateformat = $language->dateformat_regular;
if ($language->dateformat_comments != '') $settings->commentsdateformat = $language->dateformat_comments;
if ($language->dateformat_locale != '') $settings->locale = $language->dateformat_locale;

if ($custom == 'yes') // custom templates
{ // access with index.php?custom=yes&TID=name&ext=ext
 if ($ext == '') $ext = 'tpl';
 $TID = str_replace('-', '/', $TID);
 $thefile = $TID .'.'. $ext;
 $thefile = $templatesdir .'/' . $thefile;
 $template = new template($thefile);
 // custom templates can use global variables, like the toplists, but any extra variables for custom templates go below this line, in the form $template->replace('{TEMPLATEVAR}', 'replacement');
 /* can use custom template for anything, just by adding &custom=yes&TID=name to the URL */
 if ($linkid > 0) { $lin = new onelink('id', $linkid); $template->text = linkreplacements($template->text, $lin); }
 if ($catid > 0 && $action != 'displaycat') { $lin = new category('id', $catid); $template->text = categoryreplacements($template->text, $lin); }
 if ($commentid > 0) { $lin = new comment('id', $commentid); $template->text = commentreplacements($template->text, $lin); }
 if ($memberid > 0) { $lin = new member('id', $memberid); $template->text = memberreplacements($template->text, $lin); }
}


if ($action == 'userlogin')
{
 if ($filled)
 {
  if (!$template) $template = new template("$templatesdir/redirect.tpl");
  if ($thismember->login())
  {
   // you're logged in
   if ($thismember->validated = 0) $ourlang = $language->login_unvalidated;
   else $ourlang = $language->login_worked;
   $template->replace('{MESSAGE}', $ourlang);
   if ($afterlogin == '') $afterlogin = $returnto;
   $template->replace('{DESTINATION}', $afterlogin);
  }
  else
  {
    // wrong username or password
    $template->replace('{MESSAGE}', $language->login_failed);
    $template->replace('{DESTINATION}', 'index.php?action=userlogin');
  }
 }
 else
 {
  $template = new template("$templatesdir/login.tpl");
 }
}

if ($action == 'userlogout')
{
 $thismember->logout();
 if (!$template) $template = new template("$templatesdir/redirect.tpl");
 $template->replace ('{MESSAGE}', $language->loggedout);
 if ($afterlogout == '') $afterlogout = $returnto;
 $template->replace('{DESTINATION}', $afterlogout);
}

if ($action == 'lostpw')
{
 if ($email == '') $email = 'nothing here';
 $query = $db->select('id', 'memberstable', "email='$email'", '', '');
 $theirid = $db->rowitem($query);
 if (!$theirid)
 {
  if (!$template) $template = new template("redirect.tpl");
  if ($afterresetpass == '') $afterresetpass = $returnto;
  $template->replace('{DESTINATION}', $afterresetpass);
  $template->replace('{MESSAGE}', $language->passwordreset_failed);
 }
 else
 {
  $ourmember = new member('id', $theirid);
  $adminaddress = $settings->email;
  $newpassword = md5(microtime());
  $encoded = md5($newpassword);
  $ourmember->password = $encoded;
  $ourmember->update('email,password');
  $subject = memberreplacements($language->email_passwordresetsubject, $ourmember);
  $message = str_replace('{NEWPASSWORD}', $newpassword, $language->email_passwordresetbody);
  $message = memberreplacements($message, $ourmember);
  mail("$email", "$subject", "$message", "From: $adminaddress");
  if (!$template) $template = new template("redirect.tpl");
  if ($afterresetpass == '') $afterresetpass = $returnto;
  $template->replace('{DESTINATION}', $afterresetpass);
  $template->replace('{MESSAGE}', $language->passwordreset_worked);
 }
}

$origlang = $language;
?>