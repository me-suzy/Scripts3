<?php
class useronline {

 var $modids = array();
 var $adminids = array();
 var $smodids = array();
 var $buddies = array();
 var $isadmin = 0;
 var $useronlinebit = "";
 
 function useronline($isadmin,$modids,$smodids,$adminids,$buddylist="") {
  $this->isadmin=$isadmin;
  $this->modids=explode(',',$modids); 
  $this->adminids=explode(',',$adminids); 
  $this->smodids=explode(',',$smodids);
  $this->buddies=explode(' ',$buddylist); 
 }

 function parse($userid,$username,$groupid,$invisible) {
  global $tpl, $session;
  
  if(in_array($userid,$this->buddies)) eval ("\$username = \"".$tpl->get("useronline_buddy")."\";");
    
  if(in_array($groupid,$this->adminids)) eval ("\$username = \"".$tpl->get("useronline_admin")."\";");
  elseif(in_array($groupid,$this->smodids)) eval ("\$username = \"".$tpl->get("useronline_smod")."\";");
  elseif(in_array($groupid,$this->modids)) eval ("\$username = \"".$tpl->get("useronline_mod")."\";");
  
  if($invisible==1) eval ("\$useronlinebit = \"".$tpl->get("index_useronline_invisible")."\";");
  else eval ("\$useronlinebit = \"".$tpl->get("index_useronline")."\";");
  return $useronlinebit;
 }

 function user($userid,$username,$groupid,$invisible) {
  if($invisible==1 && $this->isadmin==0) return "";
  if($this->useronlinebit!="") $this->useronlinebit .= ", ".$this->parse($userid,$username,$groupid,$invisible);
  else $this->useronlinebit = $this->parse($userid,$username,$groupid,$invisible);
 }
}
?>