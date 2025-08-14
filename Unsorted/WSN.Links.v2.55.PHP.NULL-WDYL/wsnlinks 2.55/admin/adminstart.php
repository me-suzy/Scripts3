<?php
/* This is stuff that goes at the top of a bunch of files.. */
error_reporting  (E_ERROR | E_WARNING | E_PARSE);

// start timing script execution, in case we're debugging
// (don't want to wait until we know, because that will throw off the time)
$mtime = microtime(); 
$mtime = explode(" ",$mtime); 
$mtime = $mtime[1] + $mtime[0]; 
$starttime = $mtime; 
$demomode = false; // to turn this into a demo, just set guests as having admin permissions and then change this line to true;

$querycount = 0;
$inadmindir = true;
require '../prestart.php';
require 'adminfunctions.php';
require 'admincommonfuncs.php';

$thismember = new member('dummy', 'dummy');

if ($languagegroup == '') $languagegroup = $thismember->language;
if (($languagegroup == '' || $thismember->usergroup == 1) && (strstr($settings->languages, $HTTP_COOKIE_VARS['wsn_language']))) $languagegroup = $HTTP_COOKIE_VARS['wsn_language'];
if ($languagegroup == '') $languagegroup = $settings->defaultlang;
$language = new language($languagegroup);

// allow date formats to be defined by language
if ($language->dateformat_regular != '') $settings->dateformat = $language->dateformat_regular;
if ($language->dateformat_comments != '') $settings->commentsdateformat = $language->dateformat_comments;
if ($language->dateformat_locale != '') $settings->locale = $language->dateformat_locale;

$templatesdir = $thismember->template;
if ($templatesdir == '') $templatesdir = $HTTP_COOKIE_VARS['templatesdir'];
if ($templatesdir == '') $templatesdir = $settings->templatesdir;
$customheader = str_replace('-', '/', $HTTP_GET_VARS['customheader'] .'.tpl');
if ($customheader == '.tpl') $customheader = 'header.tpl';
if (!file_exists('../'. $templatesdir .'/'. $customheader)) $templatesdir = $settings->templatesdir;
if (!file_exists('../'. $templatesdir .'/'. $customheader)) $templatesdir = getvalidtemplate();
$header = new template($customheader); // get header

$linkfields = $settings->linkfields;
$categoryfields = $settings->categoryfields;

if (!$templatesdir) echo "<p><font size=6><b>There seems to be a problem with your database, such that no templates directory is set. Most likely, you've forgotten to run <a href=setup.php>setup</a>.</b></font></p>";

if ($action == 'settemplatesdir')
{ // if directory works, save it in cookie
  if ($header)
  {
   if ($thismember->usergroup > 1) { $thismember->template = $templatesdir; $thismember->update('template'); }
   else setcookie("templatesdir", "$templatesdir", time() + 8000000);
  }
}

if ($action == 'setlanguage')
{ // if directory works, save it in cookie
  if (strlen($language->allnames()) > 7)
  {
   if ($thismember->usergroup > 1) { $thismember->language = $languagegroup; $thismember->update('language'); }
   else setcookie("wsn_language", "$languagegroup", time() + 8000000);
  }
}

if ($action == 'setstyle')
{ 
 if ($thismember->usergroup > 1) { $thismember->stylesheet = $style; $thismember->update('stylesheet'); }
 else setcookie("stylesheet", "$style", time() + 8000000);
}

if ($stylesheet != '') $settings->stylesheet = $stylesheet;
if ($thismember->stylesheet != '') $settings->stylesheet = $thismember->stylesheet;
$realstyle = '../templates/styles/'. end(explode('/', $settings->stylesheet)) .'.css';
if (!file_exists($realstyle)) $settings->stylesheet = '../styles/white';

$miscdata = ''; // use this in debugging to store more info if you like?

// make compatable when register_globals is off
$skip = " newpasswordquery newusergroupquery ";
while(list($key, $value) = each($HTTP_GET_VARS)) 
{
 if (!strstr($skip, " $key "))
 {
  if ($demomode) $$key = stripcode($value);
  else $$key = $value;
 }
} 
while(list($key, $value) = each($HTTP_POST_VARS)) 
{
 if (!strstr($skip, " $key "))
 {
  if ($demomode) $$key = stripcode($value);
  else $$key = $value;
  $varslist .= '|||'. $key .'|||';
 }
} 

$headinsertion = fileread("../$templatesdir/admin/headinsertion.tpl"); // insert code into head area
$headinsertion .= '</head>';
$header->replace('</head>', $headinsertion);

$template = false;

if (($action == 'logout') || ($action == 'userlogout'))
{
 $beginpath = "http://". $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) ."/index.php?action=logout";
 if ($settings->dirurl != '') $path = $settings->dirurl . '/index.php?action=logout';
 else $path = str_replace('/admin/', '/', $beginpath);
 header("Location: $path");
}

$area = $language->title_divider . $language->title_admin;

$dostatic = false;

$returnto = $HTTP_COOKIE_VARS['returnto'];
if (($settings->dirurl != '') && (!stristr($HTTP_COOKIE_VARS['returnto'], $settings->dirurl))) $returnto = '';
if ($returnto == '') $returnto = '../index.php';

$origlang = $language;

if ($demomode) require 'resetdemo.php';
?>