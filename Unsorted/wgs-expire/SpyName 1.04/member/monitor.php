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

	require "../lib/db.php";
	require "../lib/config.php";
	require "../lib/class.Whois.php";
  
	db_connect();
	$r = mysql_query("SELECT monitor.id as id,monitor.notified,domain_name.name as domain_name,member.email FROM domain_name, monitor,member  WHERE monitor.domain_name = domain_name.id AND member.id=monitor.member");
	print mysql_error();
	$vars = config_read();
	while ($domain_rec = mysql_fetch_assoc($r)) {
		$whois = new Whois;
	    $domain = $domain_rec['domain_name'];
  		$s = $whois->lookup($domain);
	  	print_r($domain_rec);
		if (preg_match("/No/",$s)) {
			$state = "<font color='green'>FREE</font>";
			if ($domain_rec['notified'] == 0) {
					$subject = $vars['monitor_subject'];
					$body = $vars['monitor_message'];
					$to = $domain_rec['email'];

					foreach ($domain_rec as $name=>$value) {
						$body = preg_replace("/@$name@/",$value,$body);
					}

					$mail_from = $vars['mail_from'];
					mail($to,$subject,$body,"From: $mail_from");
					$id = $domain_rec['id'];
					print "UPDATE monitor SET notified = 1 WHERE id = $id";
					mysql_query("UPDATE monitor SET notified = 1 WHERE id = $id");
					print mysql_error();
			}
  	} else {
			$state = "<font color='red'>BUSY</font>";      			  	
	  }
		
	$state = mysql_escape_string($state);
	mysql_query("UPDATE domain_name SET state = '$state' WHERE name = '$domain'");
  }
  
  db_close();
?>