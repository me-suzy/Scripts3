<?
	require("path.php");
	require($INC."config/config_main.php");
	require($INC."functions/functions_payment.php");
	mysql_connect ($DBHost, $DBLogin, $DBPassword);
	mysql_selectdb ($DBName);
		if(GetSettings("allowipn")!=0){
			
			// read the post from PayPal system and add 'cmd'
			$req = 'cmd=_notify-validate';

			foreach ($HTTP_POST_VARS as $key => $value) {
			  $value = urlencode(stripslashes($value));
			  $req .= "&$key=$value";
			}

			// post back to PayPal system to validate
			$header .= "POST /cgi-bin/webscr HTTP/1.0\r\n";
			$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
			$header .= 'Content-Length: ' . strlen($req) . "\r\n\r\n";
			$fp = fsockopen ('www.paypal.com', 80, $errno, $errstr, 30);
			// assign posted variables to local variables
			// note: additional IPN variables also available -- see IPN documentation
			
			$item_name = $HTTP_POST_VARS['item_name'];
			$receiver_email = $HTTP_POST_VARS['receiver_email'];
			$invoice = $HTTP_POST_VARS['invoice'];
			
			$payment_status = $HTTP_POST_VARS['payment_status'];
			
			$payment_gross = $HTTP_POST_VARS['payment_gross'];
			$payment_fee = $HTTP_POST_VARS['payment_fee'];
			
			$txn_id = $HTTP_POST_VARS['txn_id'];
			
			$payer_email = $HTTP_POST_VARS['payer_email'];
			
			list($id, $tid, $tmp) = explode(":", $invoice);
			
			$real_payer_email = GetMemberEmail($id);
			
			if (!$fp) {
			  // ERROR
			  echo "$errstr ($errno)";
			} else {
			  fputs ($fp, $header . $req);
			  while (!feof($fp)) {
			    $res = fgets ($fp, 1024);
			    if (strcmp ($res, "VERIFIED") == 0) {
			      // check the payment_status is Completed
			    	if(strcmp($payment_status, "Completed") == 0){
						if($receiver_email==GetSettings("appemail"))
						if($real_payer_email==$payer_email){
							if(dbSelectCount($TMembersTransfers, "TransID=$tid and STATUS!=2")>0){
								$add = ($payment_gross - $payment_fee);
								dbUpdate($TMembersBalance, "Balance=Balance+".$add, "MemberID=$id");
								dbUpdate($TMembersTransfers, "STATUS=3", "TransID=$tid");
								SendMessage(GetMemberIEmail($id) ,5, $add);
								SendMessage($ADMIN_MAIL , 6, $add, $id);
							}
						}
					}
			    }else if (strcmp ($res, "INVALID") == 0) {
			      // log for manual investigation
			    }
			  }
			  fclose ($fp);
			}
		}
	mysql_close();
?>