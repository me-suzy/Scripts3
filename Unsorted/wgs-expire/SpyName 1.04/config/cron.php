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

  if (isset($_REQUEST['cron_start']) and ($vars['cron_running'] != 'true')) {
		$vars['cron_running'] = 'true';
		config_write($vars);
		$server = $_SERVER['SERVER_NAME'];
		$page = dirname(dirname($_SERVER['PHP_SELF']));
		$sock = fsockopen($server,80);
		fputs($sock,"GET http://$server$page/fakecron.php HTTP/1.0\r\n\r\n");
		fclose($sock);
		sleep(2);
		header("Location: ./?page=cron");
		exit;
  }

  if (isset($_REQUEST['cron_stop'])) {
  	$vars['cron_running'] = 'false';
  	config_write($vars);

  }

	if ($vars['cron_running'] == 'true') {
  	$status = "<font color='green'>ok</font>";
	} else {
		$status = "<font color='red'>not running</font>";
  };
  
  $_SESSION['status'] = '<b>'.$status.'</b>';
  
?>