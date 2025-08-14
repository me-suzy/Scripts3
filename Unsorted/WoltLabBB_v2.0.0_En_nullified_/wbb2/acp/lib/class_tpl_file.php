<?php
class tpl {
 
 var $templates   = array();
 var $templatepackid = 0;
 var $subvariablepackid = 0;
 var $templatefolder = "";
 var $defaultfolder = "";
 
 /* constuctor */
 function tpl($templatepackid=1,$subvariablepackid=1,$prefix="") {
  $this->templatepackid = $templatepackid;
  $this->subvariablepackid = $subvariablepackid;
  $this->defaultfolder = $prefix."templates";
  if($this->templatepackid!=0) {
   global $db, $n;
   list($templatefolder)=$db->query_first("SELECT templatefolder FROM bb".$n."_templatepacks WHERE templatepackid='".$this->templatepackid."'");	
   $this->templatefolder=$prefix.$templatefolder;	
  }
 }
 
 /* get template */
 function get($templatename) {
  if(!isset($this->templates[$templatename])) {
   if($this->templatepackid!=0 && file_exists($this->templatefolder."/$templatename.tpl")) $this->templates[$templatename]=str_replace("\"","\\\"",implode("",file($this->templatefolder."/$templatename.tpl")));
   else $this->templates[$templatename]=str_replace("\"","\\\"",implode("",file($this->defaultfolder."/$templatename.tpl")));
  }
  return $this->templates[$templatename];
 }
 
 /* print template */
 function output($template) {
  headers::send();
  $template = $this->replacevars($template);
  print($template);
 }
 
 /* replace vars */
 function replacevars($template) {
  global $db, $n, $pmpopup, $PHP_SELF;
  $result = $db->query("SELECT variable,substitute FROM bb".$n."_subvariables WHERE subvariablepackid = '".$this->subvariablepackid."'");
  while($row = $db->fetch_array($result)) $template = str_replace($row['variable'],$row['substitute'],$template);
  return $template;
 }
}
?>