<?php
require("./global.php");
isAdmin();
@set_time_limit(0);

function formatfield($field) {
 if((string)(intval($field))=="$field") return $field;
 else {
  $field=addslashes($field);
  $field=str_replace("\t","\\t",$field);
  $field=str_replace("\r","\\r",$field);
  $field=str_replace("\n","\\n",$field);
  //$field=str_replace("'","\'",$field);
  
  return "'$field'";	
 }
}

if(isset($_REQUEST['action'])) $action=$_REQUEST['action'];
else $action="";

if($action=="backup") {
 if(isset($_POST['send'])) {
  if($_POST['download']==2) shellBackup();
  if(!$_POST['tables'] || !count($_POST['tables'])) eval("acp_error(\"".gettemplate("error_notables_selected")."\");");
 
  $file="# WoltLab Burning Board $boardversion Database Backup\n# generated: ".formatdate($wbbuserdata['dateformat']." ".$wbbuserdata['timeformat'],time())."\n\n";
 
  while(list($key,$val)=each($_POST['tables'])) {
   if($_POST['structure']==1) {
    if($_POST['drop_table']==1) $file.="DROP TABLE IF EXISTS $val;\n";
    $file.="CREATE TABLE $val (\n";
    $result=$db->query("DESCRIBE $val");
    $fieldcount=$db->num_rows($result);
    $i=0;
    while($row=$db->fetch_array($result)) {
     $name = $row['Field'];
     $type = " ".$row['Type'];
     
     if($row['Null'] == "") $null = " NOT NULL";
     else $null = " NULL";
     
     if($row['Default'] == "") $default = "";
     else $default = " DEFAULT '".$row['Default']."'";
     
     if($row['Extra'] == "") $extra = "";
     else $extra = " ".$row['Extra'];
     
     $file .= "\t".$name.$type.$null.$default.$extra;
     $i++;
     if($i<$fieldcount) $file .= ", \n";
    }
    
    unset($index);
    $result=$db->query("SHOW KEYS FROM $val");
    while($row = $db->fetch_array($result)) {
     $keyname = $row['Key_name'];
     $comment = (isset($row['Comment'])) ? $row['Comment'] : "";
     $sub_part = (isset($row['Sub_part'])) ? $row['Sub_part'] : "";

     if($keyname != "PRIMARY" && $row['Non_unique'] == 0) $keyname = "UNIQUE|$keyname";
     if($comment == "FULLTEXT") $keyname = "FULLTEXT|$keyname";
     if(!isset($index[$keyname])) $index[$keyname] = array();
     if($sub_part > 1) $index[$keyname][] = $row['Column_name']."(".$sub_part.")";
     else $index[$keyname][] = $row['Column_name'];
    } 
        
    while(list($keyname,$columns)=@each($index)) {
     $file .= ", \n";
     if($keyname == "PRIMARY") $file .= "\tPRIMARY KEY (";
     elseif(substr($keyname, 0, 6) == "UNIQUE") $file .= "\tUNIQUE ".substr($keyname, 7)." (";
     elseif(substr($keyname, 0, 8) == "FULLTEXT") $file .= "\tFULLTEXT ".substr($keyname, 9)." (";
     else $file .= "\tKEY ".$keyname." (";
     $file .= implode($columns, ", ").")";
    }

    $file .= "\n);\n\n";
   }
   elseif($_POST['delete_all']==1) $file.="DELETE FROM $val;\n\n"; 
   
   $result = $db->query("SELECT * FROM $val");
   while($row=$db->fetch_row($result)) {
    $values="";
    while(list($key2,$field)=each($row)) {
     if($values) $values.=",".formatfield($field); 
     else $values=formatfield($field);
    }
    $file .= "INSERT INTO $val VALUES ($values);\n";	
   }
   $file .= "\n\n";
   $db->free_result($result);
  }
 	
  if($_POST['download']==1) {
   if($_POST['use_gz'] && function_exists("gzopen")) {
    $mime_type = (USR_BROWSER_AGENT == 'IE' || USR_BROWSER_AGENT == 'OPERA') ? 'application/octetstream' : 'application/octet-stream';
    $content_disp = (USR_BROWSER_AGENT == 'IE') ? 'inline; ' : 'attachment; ';
    header('Content-Type: '.$mime_type);
    header('Content-disposition: '.$content_disp.'filename="backup_wBB2_'.formatdate("YmdHi",time()).'.sql.gz"');
    header('Pragma: no-cache');
    header('Expires: 0');
    
    $tempname=tempnam("/tmp","gz");
    $zp=gzopen($tempname,"w9");
    gzwrite($zp,$file);
    gzclose($zp);
    readfile($tempname);	
   }	
   else {
    $mime_type = (USR_BROWSER_AGENT == 'IE' || USR_BROWSER_AGENT == 'OPERA') ? 'application/octetstream' : 'application/octet-stream';
    $content_disp = (USR_BROWSER_AGENT == 'IE') ? 'inline; ' : 'attachment; ';
    header('Content-Type: '.$mime_type);
    header('Content-disposition: '.$content_disp.'filename="backup_wBB2_'.formatdate("YmdHi",time()).'.sql"');
    header('Pragma: no-cache');
    header('Expires: 0');
    print($file);	
   }
  }
  else {
   if($_POST['use_gz'] && function_exists("gzopen")) {
    $zp=gzopen("backup_wBB2_".formatdate("YmdHi",time()).".sql.gz", "w9");	
    gzwrite($zp,$file);
    gzclose($zp);
   }
   else {
    $fp=fopen("backup_wBB2_".formatdate("YmdHi",time()).".sql","w+");
    fwrite($fp,$file);
    fclose($fp);	
   }
   eval("print(\"".gettemplate("database_backup_done")."\");");	
  }
    	
  exit();	
 }
 
 $result=mysql_list_tables($sqldb);
 $table_options="";
 while($row=$db->fetch_array($result)) $table_options.=makeoption($row[0],$row[0],$row[0],1);
 
 eval("print(\"".gettemplate("database_backup")."\");");
}

if($action=="query") {
 if(isset($_POST['send'])) {
  $query="";
  if(!empty($_FILES['uploadfile']['tmp_name']) && $_FILES['uploadfile']['tmp_name']!="none") {
   if(strtolower(substr($_POST['filename'],-3))==".gz") $query=implode("",gzfile($_FILES['uploadfile']['tmp_name']));
   else $query=implode("",file($_FILES['uploadfile']['tmp_name'])); 	
  }
  elseif($_POST['query']) $query=$_POST['query'];
  elseif($_POST['filename'] && file_exists($_POST['filename'])) {
   if(strtolower(substr($_POST['filename'],-3))==".gz") $query=implode("",gzfile($_POST['filename']));
   else $query=implode("",file($_POST['filename']));
  }
  if($query!="") {
   require("./lib/class_query.php");
   $sql_query = new query($query);
   $sql_query->doquery();
   
   eval("print(\"".gettemplate("database_query_done")."\");");
   exit();	
  }	
 }
 
 
 $result=mysql_list_tables($sqldb);
 $table_options="";
 while($row=$db->fetch_array($result)) $table_options.=makeoption($row[0],$row[0],"",0);
 
 if(file_exists("_data.inc.php")) eval ("\$delete_old = \"".gettemplate("database_delete_old")."\";");
 else $delete_old="";
 	
 eval("print(\"".gettemplate("database_query")."\");");	
}

if($action=="extra") {
 if(!$_POST['tables'] || !count($_POST['tables'])) eval("acp_error(\"".gettemplate("error_notables_selected")."\");");
 reset($_POST['tables']);
 
 if($_POST['what']==1) while(list($key,$val)=each($_POST['tables'])) $db->unbuffered_query("OPTIMIZE TABLE $val");	
 elseif($_POST['what']==2) while(list($key,$val)=each($_POST['tables'])) $db->unbuffered_query("REPAIR TABLE $val");	
 
 @header("Location: database.php?action=query&sid=$session[hash]");
 exit();
}

if($action=="delete_old") {
 if($_POST['sure']==1 && file_exists("_data.inc.php")) {
  $temp=$n;
  require("./_data.inc.php");
  
  $old_tables = array(
"bb".$n."_announcements",
"bb".$n."_avatars",
"bb".$n."_bbcode",
"bb".$n."_boards",
"bb".$n."_config",
"bb".$n."_folders",
"bb".$n."_groups",
"bb".$n."_notify",
"bb".$n."_object2board",
"bb".$n."_object2user",
"bb".$n."_pms",
"bb".$n."_pmsend",
"bb".$n."_poll",
"bb".$n."_posts",
"bb".$n."_ranks",
"bb".$n."_smilies",
"bb".$n."_style",
"bb".$n."_threads",
"bb".$n."_user_table",
"bb".$n."_useronline",
"bb".$n."_vote"
);	
  $n=$temp;
 
  while(list($key,$val)=each($old_tables)) $db->unbuffered_query("DROP TABLE IF EXISTS $val");
  @unlink("_data.inc.php");
 }
  
 @header("Location: database.php?action=query&sid=$session[hash]");
 exit();
}

function shellBackup() {
 global $db, $_POST;
 
 if(count($_POST['tables'])) {
  $shellstring="mysqldump --add-locks -qela";
  if($_POST['structure']==0) $shellstring.="t";
  if($_POST['drop_table']==1) $shellstring.=" --add-drop-table"; 
  $shellstring.=" -h{$db->server} -u{$db->user} -p{$db->password} {$db->database} ";
  $shellstring.=implode(" ",$_POST['tables']);
  if($_POST['use_gz']==1) $shellstring.=" | gzip > ./backup_wBB2_".formatdate("YmdHi",time()).".sql.gz"; 
  else $shellstring.=" > ./backup_wBB2_".formatdate("YmdHi",time()).".sql";
  system($shellstring);
  eval("print(\"".gettemplate("database_backup_done")."\");");
 }
 else eval("acp_error(\"".gettemplate("error_notables_selected")."\");");	
 exit();	
}
?>
