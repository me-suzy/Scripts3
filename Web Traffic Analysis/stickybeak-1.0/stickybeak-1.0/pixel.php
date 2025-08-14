<?

$config_pixel=array(
  "mysqlhost" => "localhost", 
  "mysqldb" => "stickybeak", 
  "mysqltable" => "stickybeak_logs",    
  "mysqluser" => "", 
  "mysqlpass" => "",  
  "p3pURL" => "http://www.example.com/w3c/p3p.xml",
  "pixel" => true
);

include "TheStickybeak.php";
$s=new stickybeak($config_pixel);
$s->log();

?>