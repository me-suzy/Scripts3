<?php
/************************************************************************/
/* BCB Spider Tracker: Simple Search Engine Bot Tracking                */
/* ============================================                         */
/*                                                                      */
/* Copyright (c) 2004 by www.bluecollarbrain.com                        */
/* http://bluecollarbrain.com                                           */
/*                                                                      */
/* This program is free software. You may use it as you wish.           */
/*   This File: spider_bcb.php                                          */ 
/*   This is the main function that attempts to match the incoming      */
/*   $_SERVER['HTTP_USER_AGENT'] against the list of names in the file  */
/*   spider_array.php.                                                  */
/*                                                                      */
/************************************************************************/

include($_SERVER['DOCUMENT_ROOT'].'/config/spider_config.php');  //edit this path if you move spider_config.php


// Declare the function that will compare the incoming $_SERVER['HTTP_USER_AGENT'] against our list of spider_strings.
function spiderBots($spider_string){
$useragent = $_SERVER['HTTP_USER_AGENT'];
	if(eregi("$spider_string", $useragent)) {
    	if ($_SERVER['QUERY_STRING'] != "") {
  	      	$url = "http://".$_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];
  	      	} else {
  	      	$url = "http://".$_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF'];
       	}
  			$sql = "INSERT INTO $tablename (url,agent) VALUES ('$url','$useragent')";
  			$result = mysql_query($sql);
  	} // End of if(eregi)
}   // End of function spiderBots

// Bring in the page that has the list of spider_strings to compare against.
require "includes/spider_array.php";  //change this path if you move spider_array.php

// Do it! Run the function spiderBots on each entry in our spider_array file.
array_walk($spider_array, 'spiderBots');

// Close db connection
mysql_close($connSpider);
?>
