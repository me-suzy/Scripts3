<?php
/*
Tiny Tool for Web Hosts, Copyright (C) 2004 John Sinclair and Dennis Turner.
WebHost Tiny Tool comes with ABSOLUTELY NO WARRANTY; this is free software, 
and you are welcome to redistribute it under certain conditions; for details 
read WWW.TEATOAST.COM/GNU_GPL_LICENSE.HTML
*/
include 'config.php';
if (empty($_POST['item_number']))
	{
	$scrub_log=fopen("scrub_log.php", "a+");
					fwrite($scrub_log, date("D d-M-y g:i:s a T")." :: IP=". $_SERVER['REMOTE_ADDR'] . "\n");
					fclose($scrub_log);
					chmod("scrub_log.php", 0777);
	mail($receiver_email,"SCRUB:","new append to scrub_log");
	@header("Location: Order.php"); 
	exit; 
	}
else
	{
	@header("Status: 200 OK"); 
	$IPNsave = "";
	$IPNsend = "cmd=_notify-validate";
	foreach ($_POST as $key => $value)
		{
		if (get_magic_quotes_gpc()) $value = stripslashes ($value);
		if (!eregi("^[_0-9a-z-]{1,30}$",$key)	|| !strcasecmp ($key, 'cmd'))
			{
			unset ($key); 
			unset ($value); 
			}
		if ($key != '') 
			{
			$IPNvars[$key] = $value; 
			unset ($_POST); 
			$IPNsend.='&'.$key.'='.urlencode($value); 
			( in_array($key, $db_fields) ? $IPNsave.=", $key='$value'" : null );
			}
		}
	set_time_limit(60); 
	$socket = @fsockopen($post_to_URL,80,$errno,$errstr,30);
	$header = "POST /cgi-bin/webscr HTTP/1.0\r\n";
	$header.= "User-Agent: PHP/".phpversion()."\r\n";
	$header.= 'Referer: '.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'@'.$_SERVER['QUERY_STRING']."\r\n";
	$header.= 'Server: '.$_SERVER['SERVER_SOFTWARE']."\r\n";
	$header.= 'Host: '.$post_to_URL.":80\r\n";
	$header.= "Content-Type: application/x-www-form-urlencoded\r\n";
	$header.= 'Content-Length: '.strlen($IPNsend)."\r\n";
	$header.= "Accept: */*\r\n\r\n";
	if (!$socket)
		{
		$response = file_get_contents('http://'.$post_to_URL.':80/cgi-bin/webscr?'.$IPNsend); 
		}
	else
		{
		fputs ($socket,$header.$IPNsend."\r\n\r\n"); 
		while (!feof($socket))
			{
			$response = fgets ($socket,1024); 
			}
		}
	$response = trim ($response); 
	fclose ($socket); 
	extract($IPNvars);
	//
	if ( $response == "VERIFIED" )
		{
		$db = mysql_connect($db_host, $db_user, $db_pass) or die ('Could not CONNECT because: ' . mysql_error());
		mysql_select_db($db_name) or die ('Could not SELECT database because: ' . mysql_error());
		$sql = "SELECT * from $db_table WHERE txn_id='$txn_id'";
		$result = mysql_query($sql,$db);
		$num_rows = mysql_num_rows($result);
		if ( $num_rows == 0 ) 
			{
			$sql = "SELECT * from $db_table WHERE item_number='$item_number'";
			$result = mysql_query($sql,$db);
			while ($subscriber = mysql_fetch_array($result, MYSQL_ASSOC)) 
				{
				include '/usr/local/cpanel/Cpanel/Accounting.php.inc';
				switch($txn_type):
					case 'subscr_payment';
						switch($payment_status):
							case 'Completed';
								//			update DB record
								$sql = "UPDATE $db_table SET COMMENT='IPN Payment'";
								$sql .= $IPNsave;
								$sql .= " WHERE item_number='$item_number'";
								mysql_query($sql,$db) or die ('Could not UPDATE table because: ' . mysql_error());
								mysql_close($db);
								//			update subscriber's Contact Email Address in their WHM Account
								$WHM_packages = listpkgs($whm_host,$whm_user,$whm_accesshash,$whm_usessl);
								extract($WHM_packages);
								$script_URL = "http://{$whm_user}:{$whm_pass}@{$_SERVER['HTTP_HOST']}:2086/scripts2/dochangeemail";
/* 							CPanel equivalent -- http://phabp@phabp.com:2082/frontend/monsoon/contact/saveemail.html */
								$command = "?user={$option_selection2}&domain={$option_selection1}&email={$payer_email}";
								ob_start();
								$response = join("", file($script_URL . $command));
								ob_end_flush();
								break;
							case 'Pending';
								//			update DB record
								$sql = "UPDATE $db_table SET COMMENT='IPN Payment', pending_reason='$pending_reason'";
								$sql .= $IPNsave;
								$sql .= " WHERE item_number='$item_number'";
								mysql_query($sql,$db) or die ('Could not UPDATE table because: ' . mysql_error());
								mysql_close($db);
								//			update subscriber's Contact Email Address in their WHM Account
								$WHM_packages = listpkgs($whm_host,$whm_user,$whm_accesshash,$whm_usessl);
								extract($WHM_packages);
								$script_URL = "http://{$whm_user}:{$whm_pass}@{$_SERVER['HTTP_HOST']}:2086/scripts2/dochangeemail";
								$command = "?user={$option_selection2}&domain={$option_selection1}&email={$payer_email}";
								ob_start();
								$response = join("", file($script_URL . $command));
								ob_end_flush();
								break;
						endswitch;
						break;
					case 'subscr_signup';
							//			update DB record
							$init_pass = genpassword(5);
							$sql = "UPDATE $db_table SET COMMENT='IPN-VERIFIED', init_pass='$init_pass'";
							$sql.= $IPNsave;
							$sql.= " WHERE item_number='$item_number'";
							mysql_query($sql) or die ('Could not UPDATE table because: ' . mysql_error());
							mysql_close($db);
							//			activate WHM account
							$acctpass = $init_pass;
							$acctplan = $subscriber['whm_name'];
							$script_URL = "http://{$whm_user}:{$whm_pass}@{$_SERVER['HTTP_HOST']}:2086/scripts/wwwacct";
							$command = "?plan={$acctplan}&domain={$option_selection1}&username={$option_selection2}&password={$acctpass}&contactemail={$payer_email}";
							ob_start();
							$reply = join("", file($script_URL . $command));
							ob_end_flush();
						break;
					case 'subscr_cancel';
							//			update DB record
							$sql = "UPDATE $db_table SET COMMENT='Failed IPN Payment'";
							$sql.= $IPNsave;
							$sql.=" WHERE item_number='$item_number'";
							mysql_query($sql,$db) or die ('Could not UPDATE table because: ' . mysql_error());
							mysql_close($db);
							//			suspend for now, terminate later
							$script_URL = "http://{$whm_user}:{$whm_pass}@{$_SERVER['HTTP_HOST']}:2086/scripts2/suspendacct";
							$reason = 'Was Cancelled';
							$command = "?user={$option_selection2}&suspend-user=Suspend&reason={$reason}";
							ob_start();
							$response = join("", file($script_URL . $command));
							ob_end_flush();
							mail($receiver_email,"SUSPENDED: {$option_selection2}","{$response}\nUser Account {$option_selection2} has been suspended because it {$reason}");
							//			update subscriber's Contact Email Address in their WHM Account
							$script_URL = "http://{$whm_user}:{$whm_pass}@{$_SERVER['HTTP_HOST']}:2086/scripts2/dochangeemail";
							$command = "?user={$option_selection2}&domain={$option_selection1}&email={$payer_email}";
							ob_start();
							$response = join("", file($script_URL . $command));
							ob_end_flush();
						break;
					case 'subscr_failed';
							//			update DB record
							$sql = "UPDATE $db_table SET COMMENT='Failed IPN Payment'";
							$sql.= $IPNsave;
							$sql.=" WHERE item_number='$item_number'";
							mysql_query($sql,$db) or die ('Could not UPDATE table because: ' . mysql_error());
							mysql_close($db);
						break;
					case 'subscr_eot';
							//			update DB record
							$sql = "UPDATE $db_table SET COMMENT='IPN Subscription EOT'";
							$sql.= $IPNsave;
							$sql.=" WHERE item_number='$item_number'";
							mysql_query($sql,$db) or die ('Could not UPDATE table because: ' . mysql_error());
							mysql_close($db);
							//			suspend for now, terminate later
							$script_URL = "http://{$whm_user}:{$whm_pass}@{$_SERVER['HTTP_HOST']}:2086/scripts2/suspendacct";
							$reason=urlencode('Reached End Of Term');
							$command = "?user={$option_selection2}&suspend-user=Suspend&reason={$reason}";
							ob_start();
							$response = join("", file($script_URL . $command));
							ob_end_flush();
							mail($receiver_email,"SUSPENDED: {$option_selection2}","{$response}\nUser Account {$option_selection2} has been suspended because it {$reason}");
							//			update subscriber's Contact Email Address in their WHM Account
							$script_URL = "http://{$whm_user}:{$whm_pass}@{$_SERVER['HTTP_HOST']}:2086/scripts2/dochangeemail";
							$command = "?user={$option_selection2}&domain={$option_selection1}&email={$payer_email}";
							ob_start();
							$response = join("", file($script_URL . $command));
							ob_end_flush();
						break;
					case 'subscr_modify';
						// this is a PayPal feature that we may want to use in the future 
						break;
					default;	// something to do if 'txn_type' doesn't fit any case above
				endswitch;
				}
			}
		}
	else
		{
		//	IPN was NOT validated as genuine or is INVALID 
		//	save it for further investigation
		$scrub_log=fopen("scrub_log.php", "a+");
						fwrite($scrub_log, date("D d-M-y g:i:s a T")." :: ". $response . " :: IP= ". $_SERVER['REMOTE_ADDR'] . " :: post string= \"" . $IPNsend . "\"\n");
						fclose($scrub_log);
						chmod("scrub_log.php", 0777);
		mail($receiver_email,"SCRUB:","new append to scrub_log");
		}
	}
?>