<?
/*****************************************************************/
/* Program Name         : WGS-Expire				             */
/* Program Version      : 1.02                                   */
/* Program Author       : Webguy Studios                         */
/* Site                 : http://www.webguystudios.com           */
/* Email                : contact@webguystudios.com              */
/*                                                               */
/*                                                               */
/* Copyright (c) 2002,2003 webguystudios.com All rights reserved.   */
/* Do NOT remove any of the copyright notices in the script.     */
/* This script can not be distributed or resold by anyone else   */
/* than the author, unless special permisson is given.           */
/*                                                               */
/*****************************************************************/

	error_reporting(E_ALL);
	include "lib/config.php";

	$path = dirname($_SERVER['PHP_SELF']);
	function addlog($s) {
		return;
		$f = fopen("log/cron.log","a");
		fputs($f,gmdate ("M d Y H:i:s ").$s);
		fclose($f);
	}

	$stamp = "cron.stamp";

	$vars = config_read();
	if ($vars['cron_running'] == 'false') exit;

	if (file_exists($stamp)) {
		$last_run = filemtime($stamp);
	} else {
		$last_run = time()-$vars['cron_interval']*2;
	}

	if ((time() - $last_run) >= $vars['cron_interval']) {
			touch($stamp);
			$server = $_SERVER['SERVER_NAME'];
			$sock = fsockopen($server,80);
			$page = $vars['cron_path'];
			addlog($path."\n");
			addlog("FakeCron requested 'http://$server$path/$page'\n");
			fputs($sock,"GET http://$server$path/$page HTTP/1.1\r\nHost:$server\r\n\r\n");
			addlog("Response ".fgets($sock));
			$write = false;
			while($s = fgets($sock)) {
				if ($s=="\r\n") $write = true;
				if ($write) addlog($s);
			}
			fclose($sock);
	}

	sleep(5);
	$server = $_SERVER['SERVER_NAME'];
	$sock = fsockopen($server,80);
	$path = dirname($_SERVER['PHP_SELF']);
	fputs($sock,"GET http://$server$path/fakecron.php HTTP/1.1\r\nHost:$server\r\n\r\n");
	fclose($sock);
?>
