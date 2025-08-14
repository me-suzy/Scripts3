<?php
// Database export utility for MySQL
require 'adminstart.php';

if ($thismember->isadmin())
{
if ($filled)
{
// Backup Structure \\
$ourtables[0] = $linkstable;
$labels[0] = 'link';
$ourtables[1] = $categoriestable;
$labels[1] = 'category';
$ourtables[2] = $commentstable;
$labels[2] = 'comment';
$ourtables[3] = $memberstable;
$labels[3] = 'member';
$ourtables[4] = $membergroupstable;
$labels[4] = 'usergroup';

$numtables = sizeof($ourtables); 
$sql .= "<!-- BEGIN DB SCHEMA -->";
for ($a=0; $a<$numtables; $a++) 
{ 
 $tablename = $ourtables[$a]; 
 $tablelabel = $labels[$a];
 //start building the table creation query 
 $sql .= "TABLE $tablelabel FIELDS"; 
 $q1 = mysql_query("select * from $tablename", $connection); 
 $numfields = mysql_num_fields($q1); 
 $numrows =mysql_num_rows($q1); 

 $fieldlist = ""; 

 for ($b=0; $b<$numfields; $b++) 
 { 
  $fieldname = mysql_field_name($q1, $b); 
  $fieldtype = mysql_fieldtype($q1, $b); 
  $fieldsize = mysql_field_len($q1, $b); 

  if ($b == 0) $sql .= " $fieldname ";
  else $sql .= ",$fieldname "; 

  $is_numeric=false; 
  switch(strtolower($fieldtype)) 
  { 
   case "int": 
      $sql .= "int default '0' NOT NULL"; 
      $is_numeric = true; 
      break; 

   case "blob": 
      $sql .= "text NOT NULL"; 
      $is_numeric = false; 
      break; 

   case "real": 
      $sql .= "real"; 
      $is_numeric = true; 
      break; 

   case "string": 
       $sql .= "char($fs)"; 
       $is_numeric=false; 
       break; 

   case "unknown": 
        switch(intval($fieldsize)) 
        { 
           case 4:   
               $sql .= "tinyint default '0' NOT NULL"; 
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

   $fieldlength .= $fieldname.", "; 
 } 
 else 
 { 
   $fieldlength .= $fn; 
 } 

}

}
$sql .= "<!-- END DB SCHEMA -->";


// now export settings data \\
$sql .= "

<!-- BEGIN SETTINGS SCHEMA -->";
$query = $db->select("name,content", 'metatable', 'id>0', '', '');
$num = $db->numrows($query);
for ($count = 0; $count < $num; $count++)
{
 $valuelist = $db->row($query);
 $name = $valuelist[0];
 $default = $valuelist[1];
 if ($name == 'totallinks' || $name == 'totalcats') $default = '0';
 $sql .= "[ITEM] $name [DEFAULT] $default"; 
}
$sql .= "<!-- END SETTINGS SCHEMA -->";

$filecontents .= $sql;

if ($sendto == 'file')
{
 $check = filewrite("../dbschema.wsn", $filecontents);
 if (!$template) $template = new template("../$templatesdir/blank.tpl");
 if ($check) $template->text = "<p>Database schematic written to <a href=../dbschema.wsn>dbschema.wsn</a>.</p><p>&nbsp;</p><p align=center>[<a href=index.php>Back to main admin page</a>]";
 else $template->text = "You must chmod 666 the file /admin/backup.sql before you can create your backup.";
}
else if ($sendto == 'browser')
{
 admindownload($filecontents, 'dbschema.wsn');
}

}
else
{
$template = new template("blank");
$template->text = "<br><p>You can <a href=\"createschema.php?filled=1&sendto=browser\">click here</a> to download the database schematic through your browser, or chmod 666 dbschema.php and write to that file.</p>

<p align=center>
<form action=createschema.php?filled=1&sendto=file method=post>
<input type=submit value=\"Write Schema\">
</form>
</p>

<p align=center>[<a href=index.php>Back to main admin page</a>]</p>";
}

}

require 'adminend.php';