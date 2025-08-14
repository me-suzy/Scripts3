<?php
// Automated database export utility for MySQL
require 'admin/admincommonfuncs.php';
if ($dobackup && !$_POST['dobackup'] && !$_GET['dobackup'] && !$_COOKIE['dobackup'])
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
 $numrows = mysql_num_rows($q1); 

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
   $values .= "'". encodesqlline($valuelist[$zzz]) ."'";
   if ($zzz < ($zz - 1)) $values .= ",";
 }
 $values = "'". implode("', '", $valuelist) ."'";
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
 } 
 $filecontents .= 'INSERT INTO '. $memberstable .'('. $settings->memberfields .') VALUES ('. $values .');';
 if (($action == 'createsetup') && (!$doneit))
 {
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
  $values .= "'". encodesqlline($valuelist[$zzz]) ."'";
  if ($zzz < ($zz - 1)) $values .= ",";
 }
 $filecontents .= 'INSERT INTO '. $metatable .'('. 'id,name,content' .') VALUES ('. $values .'); 
';
}
 if (!mysql_error())
  $check = filewrite('admin/'. $settings->backupfile, $filecontents);

}

?>