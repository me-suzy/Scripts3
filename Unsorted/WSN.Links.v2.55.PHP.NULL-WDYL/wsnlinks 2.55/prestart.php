<?php
if ($inadmindir) { require '../scriptinfo.php'; }
else { require 'scriptinfo.php'; }

$debuginfo = ''; // we'll fill this as we go

$querycount = 0;

$custommemberdb = false;
if ($inadmindir) { require '../config.php'; }
else { require 'config.php'; }
if (!$linkstable) die("<p><font size=4><b>Your config.php file is empty. It seems you have not yet run <a href=setup.php>setup</a>.</b></font></p>");
mysql_select_db("$databasename");

if ($inadmindir) { require '../functions.php'; }
else { require 'functions.php'; }
if ($inadmindir) { require '../commonfuncs.php'; }
else { require 'commonfuncs.php'; }
if ($inadmindir) { require '../classes/database.php'; }
else { require 'classes/database.php'; }
if ($inadmindir) { require '../classes/settings.php'; }
else { require 'classes/settings.php'; }
if ($inadmindir) { require '../classes/category.php'; }
else { require 'classes/category.php'; }
if ($inadmindir) { require '../classes/onelink.php'; }
else { require 'classes/onelink.php'; }
if ($inadmindir) { require '../classes/template.php'; }
else { require 'classes/template.php'; }
if ($inadmindir) { require '../classes/language.php'; }
else { require 'classes/language.php'; }
if ($inadmindir) { require '../classes/member.php'; }
else { require 'classes/member.php'; }
if ($inadmindir) { require '../classes/comments.php'; }
else { require 'classes/comments.php'; }
if ($inadmindir) { require '../wsncodes.php'; }
else { require 'wsncodes.php'; }

// variables that we'll use globally \\
$db = new database;
$settings = new settingsdata;
$origsettings = $settings; // in case we need unaltered data later

if ($settings->debug == 6 || $_GET['debug'] == 6) error_reporting (0);


if (strstr($HTTP_SERVER_VARS['HTTP_ACCEPT_ENCODING'], 'gzip') && $settings->compress == 'yes')
{
 ob_start("compressoutput");
 header("Content-Encoding: gzip");
}
else
{
 ob_start(); // prevent header warnings in debug mode
}

// fix IE 6 default requirement of privacy policy for cookies
Header("P3P: CP=\"CAO DSP COR CURa ADMa DEVa OUR IND PHY ONL UNI COM NAV INT DEM PRE\" policyref=\"www.somesite.com/w3c/p3p.xml\"");

// begin determine fields
if (!$databasename)
{
 $configinfo = fileread("config.php");
 $pattern = "mysql_select_db\((.*?)\)";
 $pattern = '/'. $pattern .'/i';
 preg_match($pattern, $configinfo, $dbname);
 $databasename = str_replace("'", "", $dbname[1]);
 $databasename = str_replace('"', '', $databasename); 
}

$settings->linkfields = $db->fieldnamelist($linkstable);
$settings->categoryfields = $db->fieldnamelist($categoriestable);
$settings->commentfields = $db->fieldnamelist($commentstable);
$settings->memberfields = $db->fieldnamelist($memberstable);
$settings->usergroupfields = $db->fieldnamelist($membergroupstable);
// end determine fields

if (!$settings->admindir) $admindir = 'admin';
else $admindir = $settings->admindir;

// see if user is IP banned
$ipbanarray = explode("\n", $settings->bannedips);
$num = sizeof($ipbanarray);
$visitor = $REMOTE_ADDR;
if ($visitor == '') $visitor = 'unreadable';
for ($i=0; $i<$num; $i++)
{
 if ($ipbanarray[$i] != '')
 {
  if (strstr($visitor, $ipbanarray[$i]))
  {
   $template = new template("noaccess.tpl");
   $template->replace('{LANG_NOACCESS}', $language->banned);
  } 
 }
}

// see if we're in debug mode
$debug = $settings->debug;
if ($settings->debug == 'no') $debug = false;
if ($settings->debug == 'yes') $debug = true;

// Include member integration file here, with a ../ in front of it if it's a level above us
$original = $settings->memberfields;
if (strlen($settings->integration) > 2)
{
 if ($inadmindir) $thefile = '../integration/'. $settings->integration .'.php';
 else $thefile = 'integration/'. $settings->integration .'.php';
 $custommemberdb = true;
 $skipvalidation = true;
 $origtable = $memberstable;
 include "$thefile";
 $oldfields = explode(',', $original);
 $n = sizeof($oldfields);
 for ($x=0; $x<$n; $x++)
 {
  $var = 'new'. $oldfields[$x];
  if (($oldfields[$x] != $$var) && ($$var != '') && ($oldfields[$x] != '')) 
  {
   $replacedfields .= $oldfields[$x] .'->'. $$var .',';
   $exists[] = $oldfields[$x];
  }
 }
 $replacedfields = trimright($replacedfields, 1);
 $newmemberfields = $db->fieldnamelist($memberstable);
 $oldmemberarray = explode(',', $settings->memberfields);
 $newmemberarray = explode(',', $newmemberfields);
 $alreadythere = array_merge($exists, $newmemberarray);
 $missing = array_diff($oldmemberarray, $alreadythere);
 if (is_array($missing))
 {
  foreach ($missing as $createthis)
  {
   $type = $db->fieldtype($origtable, $createthis);
   if (stristr($type, 'blob')) $type = "TEXT NOT NULL";
   if (stristr($type, 'int')) $type = "INT NOT NULL default '0'";
   if ($createthis == 'validated') $type = "INT NOT NULL default '1'";
   $db->alter('memberstable', 'ADD', $createthis, $type);
  }
  $newmemberfields = $db->fieldnamelist($memberstable); 
 }
 $settings->memberfields = $newmemberfields; 
}

// set newx vars where needed
$memfieldarray = explode(',', $original);
foreach ($memfieldarray as $afield) 
{
 $var = 'new'. $afield;
 if (!($$var)) $$var = $afield;
}

// get redirects info out of setting
$redirects = explode('|||', $settings->redirects);
$num = sizeof($redirects);
for ($x=0; $x<$num; $x++)
{
 $parts = explode('[,]', $redirects[$x]);
 $name = $parts[0];
 $value = $parts[1];
 $$name = $value;
}

// get usergroup data
$query = $db->select($settings->usergroupfields, 'membergroupstable', 'id>0', '', '');
while ($row = $db->row($query))
{
 $ugfields = explode(',', $settings->usergroupfields);
 $ugnum = sizeof($ugfields);
 $ugid = $row[0];
 for ($count=0; $count<$ugnum; $count++)
 {
  $item = $ugfields[$count];
  $usergroupdata[$ugid][$item] = $row[$count];
 }
}

$settings->version = $version;
?>