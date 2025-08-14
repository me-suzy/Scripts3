<?php
class db {
  
 var $link_id  = 0;
 var $query_id = 0;
 var $record   = array();
 var $queries = array();
 var $errdesc    = "";
 var $errno   = 0;
 var $show_error = 1;
 var $phpversion = 1;
   
 var $server   = "";
 var $user     = "";
 var $password = "";
 var $database = "";

 var $appname  = "WoltLab Burning Board";
  
 function db($server,$user,$password,$database,$phpversion=4) {
  $this->server=$server;
  $this->user=$user;
  $this->password=$password;
  $this->database=$database;
  $this->phpversion=$phpversion;
  $this->connect();
 }    
  
 function connect() {
  $this->link_id=mysql_connect($this->server,$this->user,$this->password);
  if (!$this->link_id) $this->error("Link-ID == false, connect failed");
  if ($this->database!="") $this->select_db($this->database);
 }
 
 function geterrdesc() {
  $this->error=mysql_error();
  return $this->error;
 }

 function geterrno() {
  $this->errno=mysql_errno();
  return $this->errno;
 }

 function select_db($database="") {
  if ($database!="") $this->database=$database;
  if(!@mysql_select_db($this->database, $this->link_id)) $this->error("cannot use database ".$this->database);
 }

 function query($query_string,$limit=0,$offset=0) {
  if($limit!=0) $query_string.=" LIMIT $offset, $limit";
  $this->queries[]="$query_string";
  $this->query_id = mysql_query($query_string,$this->link_id);
  if (!$this->query_id) $this->error("Invalid SQL: ".$query_string);
  return $this->query_id;
 }
 
 function unbuffered_query($query_string,$LOW_PRIORITY=0) {
  if($this->phpversion<406) return $this->query($query_string);
  else {
   if($LOW_PRIORITY==1) $query_string=substr($query_string,0,6)." LOW_PRIORITY".substr($query_string,6);
   $this->queries[]="unbuffered: $query_string";
   $this->query_id = mysql_unbuffered_query($query_string,$this->link_id);
   if (!$this->query_id) $this->error("Invalid SQL: ".$query_string);
   return $this->query_id;
  }
 }

 function fetch_array($query_id=-1) {
  if ($query_id!=-1) $this->query_id=$query_id;
  $this->record = mysql_fetch_array($this->query_id);
  return $this->record;
 }
 
 function fetch_row($query_id=-1) {
  if ($query_id!=-1) $this->query_id=$query_id;
  $this->record = mysql_fetch_row($this->query_id);
  return $this->record;
 }

 function free_result($query_id=-1) {
  if ($query_id!=-1) $this->query_id=$query_id;
  return @mysql_free_result($this->query_id);
 }

 function query_first($query_string,$limit=0,$offset=0) {
  $this->query($query_string,$limit,$offset);
  $returnarray=$this->fetch_array($this->query_id);
  $this->free_result($this->query_id);
  return $returnarray;
 }

 function num_rows($query_id=-1) {
  if ($query_id!=-1) $this->query_id=$query_id;
  return mysql_num_rows($this->query_id);
 }
 
 function affected_rows() {
  return mysql_affected_rows($this->link_id);
 }
 
 function insert_id() {
  return mysql_insert_id($this->link_id);
 }

 function error($errormsg) {
  $this->errdesc=mysql_error();
  $this->errno=mysql_errno();
    		
  $errormsg="<b>Database error in $this->appname:</b> $errormsg\n<br>";
  $errormsg.="<b>mysql error:</b> $this->errdesc\n<br>";
  $errormsg.="<b>mysql error number:</b> $this->errno\n<br>";
  $errormsg.="<b>Date:</b> ".date("d.m.Y @ H:i")."\n<br>";
  $errormsg.="<b>Script:</b> ".getenv("REQUEST_URI")."\n<br>";
  $errormsg.="<b>Referer:</b> ".getenv("HTTP_REFERER")."\n<br><br>";

  if($this->show_error) $errormsg = "$errormsg";
  else $errormsg = "\n<!-- $errormsg -->\n";
  die("</table><font face=\"Verdana\" size=2><b>mysql-database error</b><font size=1><b>mysql-database error</b><br>".$errormsg."</font></font>");
 }
}
?>