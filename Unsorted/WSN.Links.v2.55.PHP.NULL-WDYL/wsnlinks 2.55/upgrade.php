<?php
require 'start.php';
require_once $admindir .'/admincommonfuncs.php';
require_once $admindir .'/adminfunctions.php';

if ($getgoing)
{ 

 if ($thismember->isadmin())
 {
  $template->text .= "<font size=1><p>Administrative account verified... starting upgrade.</p>";
  // add the new stuff
  updateconfigandtables(); // add needed stuff to config.php
  updatedatabasefields(); // add any needed database fields
  fixwrongtypes();  // fix any database field types if needed
  addsettings(); // add any needed settings
  misc(); // make misc changes for odd version differences  
  addlanguage($multilingual); // add any needed language variables
  $template->text .= '<p>Updating category selector and site map...</p>';
  updatecategoryselector();
  $template->text .= "<p>Attempting to CHMOD new templates for you...</p>";
  $debug = 6;
  chmodtemplates();
  $debug = 0;
  fixtemplates();
  calctypeorder(); // calculate type ordering to prevent mixtypes=no multipage bug
  $template->text .= "</font><br><br><p><font size=2>Upgrade complete. Have a look at what changed above,  then <a href=". $settings->admindir ."/prefs.php>check your settings</a> in case there are new options you can make use of.</p>";
 }
 else 
 {
  $template->text = "You are not logged in as an administrator. Please <a href=index.php?action=userlogin>login</a> and then return here.";
 } 
}
else
{
 $message = "<p>This will upgrade your $fullscripttitle database info ";
 if ($scriptname == 'wsnlinks') $message .= " from <b>version 2.30 or higher</b> ";
 $message .= "to the latest version... it will not harm any existing data.";
 if ($scriptname == 'wsnlinks') $message .= "If you're upgrading from an earlier version, please use the appropriate upgrades in order as needed to get your database updated to 2.3x format. Then you'll be able to return to this upgrade script. (Doing upgrades in the wrong order will damage your database structure.)";
 $message .= "</p>";

 $message .= "<form action=upgrade.php?getgoing=1 method=post>";
 if ($prefix == '') $message .= "Database tables prefix: <input type=text name=prefix> <br>";
 

 if ($scriptname == 'wsnlinks') $message .= "<b>Important:</b> Are you upgrading a multilingual version, or is it English-only?<br>
<input type=radio name=multilingual value=english checked>English-only<br>
<input type=radio name=multilingual value=multi>Multilingual<br>";
 else $message .= "<input type=hidden name=multilingual value=english>";
 $message .= "<input type=submit value=Upgrade>
</form>";
 $template->text = $message;
}

function updatedatabasefields()
{
 global $db, $settings, $linkstable, $categoriestable, $commentstable, $memberstable, $membergroupstable, $debug, $template;
 
 $template->text .= "<p>Checking for new database fields to add...</p>";

 $linkfields = ','. $settings->linkfields .',';
 $categoryfields = ','. $settings->categoryfields .',';
 $commentfields = ','. $settings->commentfields .',';
 $memberfields = ','. $settings->memberfields .',';
 $usergroupfields = ','. $settings->usergroupfields .',';
 
 $stuff = fileread("dbschema.wsn");  
 $stuff = templateextract($stuff, '<!-- BEGIN DB SCHEMA -->', '<!-- END DB SCHEMA -->');
 $stuff = str_replace('<!-- END DB SCHEMA -->', '', $stuff);
 $stuff = str_replace('<!-- BEGIN DB SCHEMA -->', '', $stuff); 
 $stuff = explode('TABLE ', $stuff);
 $num = sizeof($stuff);
 for ($x=0; $x<$num; $x++)
 {
  $pair = explode(' FIELDS ', $stuff[$x]);
  if ($pair[0] != '')
  {
   $tablename = $pair[0] .'stable';
   if ($pair[0] == 'usergroup') $tablename = 'membergroupstable';
   if ($pair[0] == 'category') $tablename = 'categoriestable';   
   $tablefields = $pair[0] .'fields';
   $fieldarray = explode(',', $pair[1]);
   $n = sizeof($fieldarray);
   for ($c=0; $c<$n; $c++)
   {
    $fieldarr = explode(' ', $fieldarray[$c]);
    $fieldname = $fieldarr[0];
    if (!stristr($$tablefields, ','. $fieldname .','))
    { 
     $result = $db->query("ALTER TABLE ". $$tablename . " ADD ". $fieldarray[$c]);
     $settings->$tablefields .= ','. $fieldname;
	 $newstuff = $pair[0] .'newstuff';
	 $$newstuff .= '|||'. $fieldname .'|||';
    }
   }
  }
 }
 $settings->update('linkfields,categoryfields,commentfields,memberfields,usergroupfields');
 if (stristr($usergropupnewstuff, 'candownloadfiles')) $db->update('membergroupstable', 'candownloadfiels', '1', 'id>0');
 if (stristr($membernewstuff, 'validated')) $db->update('memberstable', 'validated', '1', 'id>0'); 
 $template->text .= "<br>Adding $nameforlinks fields: ". str_replace('|||', ' ', $linknewstuff);
 $template->text .= "<br>Adding category fields: ". str_replace('|||', ' ', $categorynewstuff);
 $template->text .= "<br>Adding usergroup fields: ". str_replace('|||', ' ', $usergroupnewstuff);
 $template->text .= "<br>Adding comment fields: ". str_replace('|||', ' ', $commentnewstuff); 
 $template->text .= "<br>Adding member fields: ". str_replace('|||', ' ', $membernewstuff); 
 $template->text .= "<p>Done adding fields.</p>";
 return true;
}

function fixwrongtypes()
{
 global $db, $languagetable, $linkstable, $membergroupstable;
 $db->query("ALTER TABLE $languagetable CHANGE groupid groupid TEXT NOT NULL");
 $db->query("ALTER TABLE $linkstable CHANGE rating rating float default '0'"); 
 $db->query("ALTER TABLE $linkstable CHANGE lastedit lastedit int default '0'"); 
 $db->query("ALTER TABLE $linkstable CHANGE time time int default '0'"); 
 $db->query("ALTER TABLE $membergroupstable CHANGE canemailmembers canemailmembers int default '0'"); 
 $db->query("ALTER TABLE $membergroupstable CHANGE canusehtml canusehtml int default '0'"); 
 $db->query("ALTER TABLE $linkstable CHANGE funds funds FLOAT DEFAULT '0' NOT NULL");
 return true;
}

function addlanguage($multilingual)
{
 global $debug, $itemsadded, $scriptname, $template;

 $settings = new settingsdata; // re-create, in case something changed
 $langlist = explode(',', $settings->languages);
 $num = sizeof($langlist);
 $template->text .= "<p>Appending new language from "; 
 if ($multilingual == 'english' && $scriptname != 'wsnguest' && $scriptname != 'wsnmanual') $template->text .= "englishonly.lng ";
 else if ($scriptname == 'wsnmanual') $template->text .= "default.lng ";
 else { if ($scriptname == 'wsnguest') $template->text .= "english.lng"; else $template->text .= "fullenglish.lng "; }
 $template->text .= "to your current languages: <br>";
 for ($c=0; $c<$num; $c++)
 {
  $thislang = $langlist[$c];
  $lang = new language($thislang);
  // remove duplicates first
  $list = $lang->removeduplicates();
  if ($list) $template->text .= "<p>Removed these duplicates from $thislang: $list</p>";

  if ($multilingual == 'english' && $scriptname != 'wsnguest' && $scriptname != 'wsnmanual') languageappend("languages/setup/englishonly.lng", $thislang);
  else if ($scriptname == 'wsnmanual')  languageappend("languages/setup/default.lng", $thislang);
  else { if ($scriptname == 'wsnguest') languageappend($settings->admindir ."/english.lng", $thislang); else languageappend("languages/setup/fullenglish.lng", $thislang); }
  $template->text .= "<p>Added these items to $thislang: ". str_replace('|||', ' ', $itemsadded) ."</p>";
  $itemsadded = '';

  if (!strstr($lang->email_emaillinkbody, '{CUSTOMTEXT}'))
  {
   $lang->email_emaillinkbody .= "\n\n{CUSTOMTEXT}";
   $lang->updateitem('email_emaillinkbody');
  }

 }
 $template->text .= "<br>Done appending languages.</p>";
 // tell user what we've done
 return true;
}

function addsettings()
{
 global $db, $metatable, $settings, $template;
 $stuff = fileread("dbschema.wsn");
 $stuff = templateextract($stuff, '<!-- BEGIN SETTINGS SCHEMA -->', '<!-- END SETTINGS SCHEMA -->');
 $stuff = str_replace('<!-- END SETTINGS SCHEMA -->', '', $stuff);
 $stuff = str_replace('<!-- BEGIN SETTINGS SCHEMA -->', '', $stuff); 

 $settingslist = ','. $settings->allnames() .',';

 $stuff = explode('[ITEM] ', $stuff);
 $num = sizeof($stuff);
 for ($x=0; $x<$num; $x++)
 {
  $pair = explode(' [DEFAULT] ', $stuff[$x]);
  if ($pair[0] != '')
  {
   $itemname = $pair[0];
   $itemdefault = $pair[1];
   if (!stristr($settingslist, ','. $itemname .','))
   {
     $result = $db->insert('metatable', 'id,name,content', "'', '$itemname', '$itemdefault'");
    $newsettings .= '|||'. $itemname .'|||';
   }
  }
 }

 // inform user of what we've done
 if ($newsettings != '')
 {
  $template->text .= "<p>Adding these new settings: ";
  $show = explode('|||', $newsettings);
  $tot = sizeof($show);
  for ($q=0; $q<$tot; $q++) $template->text .= $show[$q] .' ';
  $template->text .= "</p>";
 }
 else
 {
  $template->text .= "<p>No new settings need to be added.</p>";
 }
 return true;
}

function misc()
{
 global $settings, $db, $template;

 if ($settings->uniquetotal == '') { $settings->uniquetotal = $db->numrows($db->select('id', 'linkstable', 'validated=1 AND hide=0 AND alias=0', '', '')); $settings->update('uniquetotal'); }

 $settings->excludedtoadmin = '';
 $settings->update('excludedtoadmin'); 

 // set category types where blank
 if ($settings->cattypes == '') { $settings->cattypes = 'regular'; $settings->update('cattypes'); }
 $gett = explode(',', $settings->cattypes);
 $default = $gett[0];
 $doit = $db->update('categoriestable', 'type', $default, "type=''");

 if ($settings->commenttypes == '') { $settings->commenttypes = 'regular'; $settings->update('commenttypes'); }
 $gett = explode(',', $settings->commenttypes);
 $default = $gett[0];
 $doit = $db->update('commentstable', 'type', $default, "type=''");
 
 // set email options where blank
 $db->update('memberstable', 'allowuseremail', 'yes', "allowuseremail=''");
 $db->update('memberstable', 'allowemail', 'yes', "allowemail=''");

 // regenerate totals in case their version didn't have them yet
 $q = $db->select('hits,hitsin', 'linkstable', 'hide=0 AND validated=1', '', '');
 $n = $db->numrows($q);
 for ($x=0; $x<$n; $x++)
 {
  $row = $db->row($q);
  $totalhits += $row[0];
  $totalhitsin += $row[1]; 
 } 
 $settings->totalhits = $totalhits;
 $settings->totalhitsin = $totalhitsin;
 $settings->totallinks = $n;
 $settings->totalcomments = $db->numrows($db->select('id', 'commentstable', 'validated=1 AND hide=0', '', ''));
 $settings->totalmembers = $db->numrows($db->select('id', 'memberstable', 'validated=1', '', ''));
 $settings->lastupdate = $db->rowitem($db->select('lastedit', 'linkstable', "validated=1 AND hide=0", "ORDER BY lastedit DESC", "LIMIT 0,1"));
 $settings->update('totallinks,totalcomments,totalhitsin,totalhits,totalmembers,lastupdate');

 // remove wsn codes if they're in the old format, leave alone if they're in the new format
 if (!strstr($settings->wsncodes, '[,]')) $mustreplace = true;
 $code = explode('[,]', $settings->wsncodes);
 if (!strstr($code[0], '[')) $mustreplace = true;
 if ($mustreplace) 
 { 
  $settings->wsncodes = '[url={PARAM}][,][/url][,]<a href={PARAM}>[,]</a>[,]Link text to a URL.[,]regular|||[url][,][/url][,]<a href={CONTENT}>[,]</a>[,]Create a link around a URL.[,]regular|||[font={PARAM}][,][/font][,]<font face={PARAM}>[,]</font>[,]Put text in a particular font.[,]regular|||[b][,][/b][,]<b>[,]</b>[,]Put text in bold.[,]regular|||[i][,][/i][,]<i>[,]</i>[,]Put text in italics.[,]regular';
  $template->text .= "<p>Erasing old format WSN Codes, inserting default new ones.</p>";
  $settings->update('wsncodes');
 }

 $test = $db->rowitem($db->select('stylesheet', 'memberstable', "usergroup=3", '', ''));
 if ($test == '' || $test == '0') 
 {
  $template->text .= "<p>Adding stylesheets to member profiles.</p>";
  $query = $db->select($settings->memberfields, 'memberstable', 'id>0', '', '');
  $num = $db->numrows($query);
  for ($x=0; $x<$num; $x++)
  {
   $mem = new member('row', $db->row($query));
   $mem->stylesheet = 'white';
   $mem->update('stylesheet');
  }
 }

 $maxsubs = $db->rowitem($db->select('numsub', 'categoriestable', 'id>0', 'ORDER BY numsub DESC', 'LIMIT 0,1'));
 if ($maxsubs < 1)
 {
  $template->text .= "<p>Calculating the number of subcategories for each category.</p>";
  $query = $db->select($settings->categoryfields, 'categoriestable', 'id>0', '', '');
  $num = $db->numrows($query);
  for ($x=0; $x<$num; $x++)
  {
   $row = $db->row($query);
   $thisitem = new category('row', $row);
   $thisitem->numsub = $thisitem->calcnumsub();
   $thisitem->update('numsub');
  }
 }
 return true;
}

function updateconfigandtables()
{
 require 'config.php';
 if (!$prefix)
 {
  $prefix = explode('_', $linkstable);
  $prefix = $prefix[0] .'_';
  $stufftoadd .= '$prefix = \''. $prefix .'\'; ';
 }
 if (!$databasename)
 {
  $configinfo = fileread("config.php");
  $pattern = "mysql_select_db\((.*?)\)";
  $pattern = '/'. $pattern .'/i';
  preg_match($pattern, $configinfo, $dbname);
  $databasename = str_replace("'", "", $dbname[1]);
  $databasename = str_replace('"', '', $databasename);   
  $stufftoadd = '$databasename = \''. $databasename .'\'; ';  
 }
// if (!$pmtable) $stufftoadd .= '$pmtable = \''. $prefix . 'pm' .'\'; ';
// if (!$memberstable) $stufftoadd .= '$memberstable = \''. $prefix . 'members' .'\'; '; 
 if ($stufftoadd != '')
 {
  $towrite = '<'.'?php '. $stufftoadd .'?'.'>';
 }

 if ($stufftoadd != '' || $towrite != '')
 {
  @chmod("config.php", 0666);
  $test = @fileappend("config.php", $towrite);
  if (!$test) die("You must chmod 666 or 777 your config.php file, and then return to run this upgrade again.");
  if (!$membergroupstable && $memberstable) die("Your database seems to be in 2.2x format. You will need to use the <a href=upgrade2.2-2.3.php>upgrade2.2-2.3.php</a> upgrade script before returning here.");
  if (!$memberstable && $commentstable) die("Your database seems to be in an older format (somewhere from 2.00 to 2.11), it is not in 2.3x format. You will need to use the appropriate upgrade scripts before returning here.");
  if (!$commentstable) die("Your database seems to be in 1.x format. You will need to use the <a href=upgradefrom1x.php>upgradefrom1x.php</a> upgrade script.");
 }
 return true;
}

function fixtemplates()
{
 return true;
}

require 'end.php';

?>