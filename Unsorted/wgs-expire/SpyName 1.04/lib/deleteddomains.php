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

	function get_cid() {
  	$f = fopen("http://deleteddomains.com","r");
    while ($s = fgets($f,1024)) {
    	if (preg_match('/cid=(\d+-\d+)/',$s,$match)) {
      	$cid = $match[1];
      	break;
      }
    }
    fclose($f);
    if (isset($cid)) {
    	return $cid;
    } else {
    	return false;
    }
  }

	function get_info($cid) {
  	$f = fopen("http://deleteddomains.com/stats.shtml?cid=$cid","r");
  	$stats = '';
    while ($s = fgets($f,1024)) {
    	$stats .= $s;
    }
    fclose($f);
   	if (preg_match('/(?s)<table width="640" border="2" cellspacing="2" cellpadding="2">(.*?)<\/table>/',$stats,$match)) {
     	$info = $match[1];
    }

    if (isset($info)) {
    	return $info;
    } else {
    	return false;
    }
  }
  
  function get_sid($cid) {
  	$f = fopen("http://deleteddomains.com/search.shtml?cid=$cid","r");
    while ($s = fgets($f,1024)) {
    	if (preg_match('/<input type = "hidden"  value ="(\d+)" name="sid">/',$s,$match)) {
      	$sid = $match[1];
      }
    }
    fclose($f);
    if (isset($sid)) {
    	return $sid;
    } else {
    	return false;
    }
  }
  
?>