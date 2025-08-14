<?php
class query {
 var $query = "";

 function query($query) {
  $this->query=$query;	
 }
 
 function doquery() {
  global $db;
  
  $this->query = preg_replace("/(\n|^)#[^\n]*(\n|$)/", "\\1",trim($this->query));
  $buffer = array();
  $query_array = array();
  $in_string = false;
   
  for($i=0; $i<strlen($this->query)-1; $i++) {
   if($this->query[$i] == ";" && !$in_string) {
    $query_array[] = substr($this->query, 0, $i);
    $this->query = substr($this->query, $i + 1);
    $i = 0;
   }
   if(isset($buffer[1])) $buffer[0] = $buffer[1];
    
   if($in_string && $this->query[$i] == "'" && $buffer[0] != "\\") $in_string = false;
   elseif(!$in_string && $this->query[$i] == "'" && (!isset($buffer[0]) || $buffer[0] != "\\")) $in_string=true;
        
   $buffer[1] = $this->query[$i];
  }

  if(!empty($this->query)) $query_array[] = $this->query;
  for($i=0;$i<count($query_array);$i++) {
   $query_array[$i]=trim($query_array[$i]);
   if(!$query_array[$i]) continue;
   $db->unbuffered_query($query_array[$i]);
  }	
 }
}
?>