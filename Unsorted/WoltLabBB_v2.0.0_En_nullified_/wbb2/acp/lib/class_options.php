<?php
class options {
 var $path2lib = "";

 function options($path2lib) {
  $this->path2lib=$path2lib;	
 }
 
 function write() {
  global $db, $n;
  
  $fp=fopen($this->path2lib."/options.inc.php","w+");
  fwrite($fp, "<?php\n// automatic generated option file\n// do not change\n\n");
  $result=$db->query("SELECT varname, value FROM bb".$n."_options");	
  while($row=$db->fetch_array($result)) fwrite($fp, "\$".$row['varname']." = \"".str_replace("\"","\\\"",$row['value'])."\";\n");
  fwrite($fp, "?>");
  fclose($fp);	
 }
}
?>