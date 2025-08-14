<?
$config=array(

//    Database Connection Parameters
  "mysqlhost" => "localhost", 
  "mysqldb" => "stickybeak", 
  "mysqltable" => "stickybeak_logs",    
  "mysqluser" => "", 
  "mysqlpass" => "", 
  
// location of p3p policy ... required for cross domain tracking  
  "p3pURL" => "http://www.example.com/w3c/p3p.xml",


  "image" => "TheStickybeak.png", 
  "imagetype" => "png"


);

include "TheStickybeak.php";
$s=new stickybeak($config);
$s->log();
?>