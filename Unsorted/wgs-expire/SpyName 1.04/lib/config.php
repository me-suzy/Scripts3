<?
/*****************************************************************/
/* Program Name         : WGS-Expire				             */
/* Program Version      : 1.02                                   */
/* Program Author       : Webguy Studios                         */
/* Site                 : http://www.webguystudios.com           */
/* Email                : contact@webguystudios.com              */
/*                                                               */
/* Copyright (c) 2002,2003 webguystudios.com All rights reserved.   */
/* Do NOT remove any of the copyright notices in the script.     */
/* This script can not be distributed or resold by anyone else   */
/* than the author, unless special permisson is given.           */
/*                                                               */
/*****************************************************************/

error_reporting(E_ALL);
function config_read() {
	if (file_exists("../domains.php")) {
  	$fname = "../domains.php"; 
  } else {
  	$fname = "domains.php"; 
  }
  
  $vars = array();
	$f = fopen($fname,"r");
	while ($s = fgets($f,16384)) {
//		print "123$s 123<br>";
  	if (preg_match("/(\S+)=(.*)/",$s,$match)) {
   		$match[2] = preg_replace('/\t/',"\r\n",$match[2]);
			$vars[$match[1]] = stripslashes($match[2]);
    }
	}
	fclose($f);
  return $vars;
}

function config_write($vars) {
    $f = fopen("../domains.php","w");
    fputs($f,"<?/*\n");
    foreach ($vars as $name=>$value) {
    	$value = preg_replace('/\r\n/',"\t",$value); 
    	$value = preg_replace('/\n/',"\t",$value); 
    	$value = preg_replace('/\r/',"\t",$value); 
    	fwrite($f,"$name=$value\n");
    }
    fputs($f,"*/?>");
		fclose($f);
}    

?>
