<?php
// *************************************************************************************************
// Title: 		PHP AGTC-IP Ban v1.0a
// Developed by: Andy Greenhalgh
// Email:		andy@agtc.co.uk
// Website:		agtc.co.uk
// Copyright:	2005(C)Andy Greenhalgh - (AGTC)
// Licence:		GPL, You may distribute this software under the terms of this General Public License
// *************************************************************************************************
//
// HERE IS WHERE WE CHECK THE ip.txt FILE FOR ANY BANNED ip'S
$BannedIP = GetHostByName($REMOTE_ADDR); 
$theFile = file_get_contents('ip.txt');
$lines = array();
$lines = explode("\n", $theFile);
$lineCount = count($lines);
for ($i = 0; $i < $lineCount; $i++){ 
if ($lines[$i] == $BannedIP) {
echo "You are banned !!"; // IF THE IP IS BANNED THEN WE ECHO MESSAGE SAYING THIS
exit();}
 }
 ?>