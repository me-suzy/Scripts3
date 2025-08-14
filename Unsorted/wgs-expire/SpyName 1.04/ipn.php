<?php
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

include "lib/db.php";
include "lib/config.php";
include "lib/class.HtmlSource.php";

error_reporting('E_NONE');
//header("Content-type:text/plain");

function addlog($s) {
	$f = fopen("log/ipn.log","a");
	fputs($f,"$s\n");
	fclose($f);
}

function process_ipn ($ipn)
{
	// Process IPN after the connection to the Paypal secured server
	// Depending of the status (INVALID or VERIFIED or false)
	$login = $ipn['custom']; 
  	addlog("User's login name: ".$login);


  	if ($ipn['payment_status'] != "Completed") return;

    db_connect();
    if ($ipn['txn_type'] == "subscr_signup" or 
    		$ipn['txn_type'] == "subscr_payment") {
    	addlog('signup');
	    $sql = "UPDATE member SET active = 1 WHERE login = '$login'";
	
			$r = mysql_query("SELECT * FROM member WHERE login = '$login'");
			$line = mysql_fetch_assoc($r);

			$vars = config_read();
			$to = $line['email'];
			$body = $vars['welcome_message'];
			$subject = $vars['welcome_subject'];
			$from = $vars['mail_from'];
			foreach ($line as $name=>$value) {
				$body = preg_replace("/@$name@/",$value,$body);
			}

			mail($to,$subject,$body,"From: $from");
		 	addlog($sql);

    } elseif ($ipn['txn_type'] == "subscr_cancel") {
    	addlog('cancel');
	    $sql = "DELETE FROM member WHERE subscr_id = '$subscr_id'";
    }
    mysql_query($sql);
    addlog(mysql_error());
    db_close(); 
}

if(count($_POST)>0)
{
	addlog("----------------------------------");
	$paypal_host = "www.paypal.com";
//	$paypal_host = "www.eliteweaver.co.uk";

	$paypal_url = "http://www.paypal.com/cgi-bin/webscr";
//	$paypal_url = "http://www.eliteweaver.co.uk/testing/ipntest.php";
	
	$source = new HtmlSource();
	$source->host = $paypal_host;
	$source->page = $paypal_url;
	$source->method = "POST";

	foreach ($_POST as $name=>$value) {
//		addlog("$name=>$value");
		$source->addPostVar(urlencode($name),urlencode($value));
	}
	$source->addPostVar("cmd",urlencode("_notify-validate"));
	$s = $source->getSource();
	addlog($source->request);
	addlog(strlen($source->request));
	echo $s;
	if (preg_match('/VERIFIED/',$s)) {
		addlog("Request verified");
		process_ipn($_POST);
	} else {
		addlog("Invalid request");
	}
	addlog("----------------------------------");
}
?>