<?php
// Database export utility for MySQL
require 'adminstart.php';

if ($thismember->isadmin())
{
if ($filled)
{
// Backup Structure \\
$ourtables[0] = $linkstable;
$ourtables[1] = $categoriestable;
$ourtables[2] = $commentstable;
$ourtables[3] = $metatable;
$ourtables[4] = $memberstable;
$ourtables[5] = $membergroupstable;


$numtables = sizeof($ourtables); 
for ($c=0; $c<$numtables; $c++)
{
 $tablename = $ourtables[$c];
 $filecontents .= "drop table if exists $tablename ;
 ";
}


for ($a=0; $a<$numtables; $a++) 
{ 
 $tablename = $ourtables[$a]; 

 //start building the table creation query 
 $sql .= "create table $tablename
 (
 "; 
 $q1 = mysql_query("select * from $tablename", $connection); 
 $numfields = mysql_num_fields($q1); 
 $numrows = $db->numrows($q1); 

 $fieldlist = ""; 

 for ($b=0; $b<$numfields; $b++) 
 { 
  $fieldname = mysql_field_name($q1, $b); 
  $fieldtype = mysql_fieldtype($q1, $b); 
  $fieldsize = mysql_field_len($q1, $b); 
  $sql .= "    $fieldname "; 

  $is_numeric=false; 
  switch(strtolower($fieldtype)) 
  {   
   case "int": 
      if ($fieldsize == 1) $sql .= "tinyint(1) default '0' NOT NULL"; 
      else $sql .= "int default '0' NOT NULL"; 
      $is_numeric = true; 
      break; 

   case "blob": 
      $sql .= "text NOT NULL"; 
      $is_numeric = false; 
      break; 

   case "real": 
   case "float":
   case "double":
      $sql .= $fieldtype ." default '0' NOT NULL"; 
      $is_numeric = true; 
      break; 

   case "string": 
       $sql .= "char($fs)"; 
       $is_numeric=false; 
       break; 

   case "unknown": 
        switch(intval($fieldsize)) 
        { 
           case 1:   
               $sql .= "tinyint(1) default '0' NOT NULL"; 
               $is_numeric = true; 
               break; 

            default: 
               $sql .= "int default '0' NOT NULL"; 
               $is_numeric = true; 
                break;   
         } 
         break; 

    case "timestamp": 
         $sql .= "timestamp";   
         $is_numeric = true; 
         break; 

    case "date": 
         $sql .= "date";   
         $is_numeric = false; 
         break; 

     case "datetime": 
          $sql .= "datetime";   
          $is_numeric=false; 
          break; 

     case "time": 
          $sql .= "time";   
          $is_numeric=false; 
          break; 

     default:  
         $sql.=$fieldtype; 
         $is_numeric=true;
         break; 
  } 

 if ($fieldname == 'id') $sql .= ' auto_increment';
 if ($b < $numfields-1) 
 { 
   $sql .= ",
   "; 
   $fieldlength .= $fieldname.", "; 
 } 
 else 
 { 
   $sql .= ",
 UNIQUE KEY id (id)
   );
   
   "; 
   $fieldlength .= $fn; 
 } 

}

}
$filecontents .= $sql;
$setupstuff = $filecontents;

if ($action != 'createsetup')
{
// now export link data \\

$query = $db->select($settings->linkfields, 'linkstable', 'id>0', '', '');
$num = $db->numrows($query);
$zz = sizeof(explode(',', $settings->linkfields));
for ($count = 0; $count < $num; $count++)
{
 $valuelist = $db->row($query);
 $values = '';
 for ($zzz=0; $zzz<$zz; $zzz++)
 { 
   $thisvalue = encodesqlline($valuelist[$zzz]);
   $values .= "'". $thisvalue ."'";
   if ($zzz < ($zz - 1)) $values .= ",";
 }
 $filecontents .= 'INSERT INTO '. $linkstable .'('. $settings->linkfields .') VALUES ('. $values .'); 
';
}

// now export category data \\

$query = $db->select($settings->categoryfields, 'categoriestable', 'id>0', '', '');
$num = $db->numrows($query);
$zz = sizeof(explode(',', $settings->categoryfields));
for ($count = 0; $count < $num; $count++)
{
 $valuelist = $db->row($query);
 $values = ''; 
 for ($zzz=0; $zzz<$zz; $zzz++)
 {
   $values .= "'". encodesqlline($valuelist[$zzz]) ."'";
   if ($zzz < ($zz - 1)) $values .= ",";
 }
 $filecontents .= 'INSERT INTO '. $categoriestable .'('. $settings->categoryfields .') VALUES ('. $values .'); 
';
}

// now export comment data \\

$query = $db->select($settings->commentfields, 'commentstable', 'id>0', '', '');
$num = $db->numrows($query);
$zz = sizeof(explode(',', $settings->commentfields));
for ($count = 0; $count < $num; $count++)
{
 $valuelist = $db->row($query);
 $values = ''; 
 for ($zzz=0; $zzz<$zz; $zzz++)
 {
   $values .= "'". encodesqlline($valuelist[$zzz]) ."'";
   if ($zzz < ($zz - 1)) $values .= ",";
 }
 $filecontents .= 'INSERT INTO '. $commentstable .'('. $settings->commentfields .') VALUES ('. $values .'); 
';
}
}

// now export member data \\

$query = $db->select($settings->memberfields, 'memberstable', 'id>0', '', '');
$num = $db->numrows($query);
$marray = explode(',', $settings->memberfields);
$zz = sizeof($marray);
for ($count = 0; $count < $num; $count++)
{
 $valuelist = $db->row($query);
 $values = '';
 $setupvalues = '';
 for ($zzz=0; $zzz<$zz; $zzz++)
 {
  $values .= "'". encodesqlline($valuelist[$zzz]) ."'";
  if ($zzz < ($zz - 1)) $values .= ', ';
  if ($action == 'createsetup')
  {
   if ($marray[$zzz] == 'name')
    $setupvalues .= "'{USERNAME}'";
   else if ($marray[$zzz] == 'password')
    $setupvalues .= "'{PASSWORD}'";
   else if ($marray[$zzz] == 'time')
    $setupvalues .= "'{TIME}'";
   else if ($marray[$zzz] == 'ip')
    $setupvalues .= "'{IP}'";
   else if ($marray[$zzz] == 'template')
    $setupvalues .= "'{OURTEMPLATESDIR}'";
   else if ($marray[$zzz] == 'language')
    $setupvalues .= "'{OURDEFAULTLANG}'";
   else if ($marray[$zzz] == 'usergroup')
    $setupvalues .= "'3'";
   else if ($marray[$zzz] == 'stylesheet')
    $setupvalues .= "'default'";
   else if ($marray[$zzz] == 'validated')
    $setupvalues .= "'1'";
   else if ($marray[$zzz] == 'links')
    $setupvalues .= "'0'";
   else if ($marray[$zzz] == 'comments')
    $setupvalues .= "'0'";
   else if ($marray[$zzz] == 'images')
    $setupvalues .= "'0'";
   else if ($marray[$zzz] == 'signature')
    $setupvalues .= "''";
   else if ($marray[$zzz] == 'email')
    $setupvalues .= "''";
   else if ($marray[$zzz] == 'avatarname')
    $setupvalues .= "''";
   else
    $setupvalues .= "'0'";
   if ($zzz < ($zz - 1)) $setupvalues .= ', ';
  }
 } 
 $filecontents .= 'INSERT INTO '. $memberstable .'('. $settings->memberfields .') VALUES ('. $values .');';
 if (($action == 'createsetup') && (!$doneit))
 {
  $setupstuff .= 'INSERT INTO '. $memberstable .'('. $settings->memberfields .') VALUES ('. $setupvalues .');';
  $doneit = true;
 }
}

// now export usergroup data \\

$query = $db->select($settings->usergroupfields, 'membergroupstable', 'id>0', '', '');
$num = $db->numrows($query);
$zz = sizeof(explode(',', $settings->usergroupfields));
for ($count = 0; $count < $num; $count++)
{
 $valuelist = $db->row($query);
 $values = '';
 for ($zzz=0; $zzz<$zz; $zzz++)
 {
   $values .= "'". encodesqlline($valuelist[$zzz]) ."'";
   if ($zzz < ($zz - 1)) $values .= ",";
 }
 $filecontents .= 'INSERT INTO '. $membergroupstable .'('. $settings->usergroupfields .') VALUES ('. $values .'); 
';
 if ($action == 'createsetup') $setupstuff .= 'INSERT INTO '. $membergroupstable .'('. $settings->usergroupfields .') VALUES ('. $values .'); 
';
}

// now export settings data \\

$query = $db->select("id,name,content", 'metatable', 'id>0', '', '');
$num = $db->numrows($query);
$zz = 3;
for ($count = 0; $count < $num; $count++)
{
 $valuelist = $db->row($query);
 $values = '';
 for ($zzz=0; $zzz<$zz; $zzz++)
 {
  if ($action == 'createsetup')
  {
   if ($valuelist[1] == 'templates') $valuelist[2] = '{OURTEMPLATES}';
   if ($valuelist[1] == 'templatesdir') $valuelist[2] = '{OURTEMPLATESDIR}';
   if ($valuelist[1] == 'defaultlang') $valuelist[2] = '{OURDEFAULTLANG}';
   if ($valuelist[1] == 'languages') $valuelist[2] = '{OURLANGUAGES}';
   if ($valuelist[1] == 'admindir') $valuelist[2] = '{OURADMINDIR}';
   if ($valuelist[1] == 'totalhits') $valuelist[2] = '0';   
   if ($valuelist[1] == 'totallinks') $valuelist[2] = '0';   
   if ($valuelist[1] == 'totalhitsin') $valuelist[2] = '0';   
   if ($valuelist[1] == 'totalcats') $valuelist[2] = '0';   
   if ($valuelist[1] == 'totalcats') $valuelist[2] = '0';   
   if ($valuelist[1] == 'totalcomments') $valuelist[2] = '0';
   if ($valuelist[1] == 'totalmembers') $valuelist[2] = '1';   
   if ($valuelist[1] == 'categoryselector') $valuelist[2] = '';
   if ($valuelist[1] == 'sitemap') $valuelist[2] = '';
  }
  $values .= "'". encodesqlline($valuelist[$zzz]) ."'";
  if ($zzz < ($zz - 1)) $values .= ",";
 }
 $filecontents .= 'INSERT INTO '. $metatable .'('. 'id,name,content' .') VALUES ('. $values .'); 
';
 if ($action == 'createsetup')
 {
  $setupstuff .= 'INSERT INTO '. $metatable .'('. 'id,name,content' .') VALUES ('. $values .'); 
'; 
 }
}

if ($action == 'createsetup') 
{
if ($scriptname == 'wsngallery') $setupstuff .= "INSERT INTO {PREFIX}categories (id, name, parent, validated, description, time, parentnames, parentids, numlinks, hide, lastlinktime, custom, lastedit, moderators, headerinfo, related, numsub, isalbum) VALUES (1, 'Member Albums', 0, 1, '', '{TIME}', '', '', 0, 1, 0, '', '{TIME}', '', '', '', 0, 1);";

// create default indecies
$linkindex = explode(' ', str_replace('ORDER BY ', '', $settings->orderlinks));
$linkindex = $linkindex[0];
if (strstr($db->fieldtype($linkstable, $linkindex), 'text')) $linkindex .= '(5)';
$linkindex2 = explode(' ', str_replace('ORDER BY ', '', $settings->orderlinks2));
$linkindex2 = $linkindex2[0];
if (strstr($db->fieldtype($linkstable, $linkindex2), 'text')) $linkindex2 .= '(5)';

$setupstuff .= "
CREATE INDEX wsnindex ON {PREFIX}links (catid,". $linkindex .",". $linkindex2 .");";

$catindex = explode(' ', str_replace('ORDER BY ', '', $settings->ordercats));
$catindex = $catindex[0];
if (strstr($db->fieldtype($categoriestable, $catindex), 'text')) $catindex .= '(5)';
$setupstuff .= "
CREATE INDEX wsnindex ON {PREFIX}categories (". $catindex .");";

$comindex = explode(' ', str_replace('ORDER BY ', '', $settings->ordercomments));
$comindex = $comindex[0];
if (strstr($db->fieldtype($commentstable, $comindex), 'text')) $comindex .= '(5)';	
$setupstuff .= "
CREATE INDEX wsnindex ON {PREFIX}comments (linkid,". $comindex .");";

$memindex = explode(' ', str_replace('ORDER BY ', '', $settings->memberlistorder));
$memindex = $memindex[0];
if (strstr($db->fieldtype($memberstable, $memindex), 'text')) $memindex .= '(5)';
$setupstuff .= "
CREATE INDEX wsnindex ON {PREFIX}members (id,". $memindex .");";

$setupstuff .= "
CREATE INDEX wsnindex ON {PREFIX}membergroups (id);";

$setupstuff = str_replace($prefix, '{PREFIX}', $setupstuff);
}

if ($sendto == 'file')
{
 if ($action == 'createsetup')  $check = filewrite("../setup.sql", $setupstuff);
 else $check = filewrite("backup.sql", $filecontents);
 if (!$template) $template = new template("../$templatesdir/blank.tpl");
 if ($action == 'createsetup')
 {
 if ($check) $template->text = "<p>Setup script written to <a href=../setup.sql>setup.sql</a>. </p><p>&nbsp;</p><p align=center>[<a href=index.php>Back to main admin page</a>]";
 else $template->text = "You must chmod 666 the file /admin/backup.sql before you can create your backup.";
}
else
{
 if ($check) $template->text = "<p>Backup written to <a href=backup.sql>backup.sql</a>. Be sure to replace this file with a blank one after downloading your backup, so that no one else can download your database information.</p><p>&nbsp;</p><p align=center>[<a href=index.php>Back to main admin page</a>]";
 else $template->text = "You must chmod 666 the file /admin/backup.sql before you can create your backup.";
}
}
else if ($sendto == 'browser')
{
 if ($action == 'createsetup') admindownload($setupstuff, 'setup.sql');
 else admindownload($filecontents, $scriptname .'.sql');
}

}
else
{
 if ($action == 'restore')
 {
  if (!$demomode)
  {
   $filetitle = $_FILES['filetitle']['tmp_name'];
   if ($filetitle != '')
   {
    $sql = fileread($filetitle);
    $docreation = processsql($sql);
    if (!$template) $template = new template("../$templatesdir/redirect.tpl");
    $template->replace('{MESSAGE}', 'The backup as been restored.');
    $template->replace('{DESTINATION}', 'export.php');	
   }
   else echo "Could not find file.";
  } 
 }
 else if ($action == 'setcron') 
 {
  $backupfile = md5(microtime()) .'.sql';
  $settings->backupfile = $backupfile;
  $settings->backupdelay = $backupdelay;
  $settings->update('backupfile,backupdelay');
  $template = new template("redirect.tpl");
  $template->replace('{MESSAGE}', "Now doing automated backups. To protect your database from theft, these backups will be saved to the randomly generated file name $backupfile ... please note that this is what you will want to download to restore. The file will be in your admin directory.");
  $template->replace('{DESTINATION}', 'export.php');
  $template->replace('{SECONDSDELAY}', '120');
 }
 else if ($action == 'removecron')
 {
  $settings->backupfile = '';
  $settings->backupdelay = '';
  $settings->update('backupfile,backupdelay');
  $template = new template("redirect.tpl");
  $template->replace('{MESSAGE}', 'No longer doing automated backups.');
  $template->replace('{DESTINATION}', 'export.php');
 }
 else
 {
  if (!$template) $template = new template("../$templatesdir/admin/backup.tpl");
 }
}

}

require 'adminend.php';
?>