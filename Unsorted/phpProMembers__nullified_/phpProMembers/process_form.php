<?php
/*********************************************************************/
/* Program Name         : phpProMembers                              */
/* Home Page            : http://www.gacybertech.com                 */
/* Retail Price         : $149.99 United States Dollars              */
/* WebForum Price       : $000.00 Always 100% Free                   */
/* xCGI Price           : $000.00 Always 100% Free                   */
/* Supplied by          : South [WTN]                                */
/* Nullified by         : CyKuH [WTN]                                */
/* Distribution         : via WebForum and Forums File Dumps         */
/*********************************************************************/
$page_account = "form";
require "include.php";

$nnameoncard = $nameoncard;
$ccardnumber = $cardnumber;
$eexp = $exp;

// Getting Account Information.
$todays_date = date("Y-m-d");
$number_days_account_active = date("Y-m-d",time() + ($account_length * 86400)); 
		
if ($payment == "paypal" && $item_amount > "0") {
	$insert_data_sql = "INSERT INTO members_waiting_approval (id, first_name, last_name, address1, address2, city, state, postal_code, country, telephone_number, e_mail_address, user_name, user_password, payment_type, account_type_name, name_on_card, card_number, exp_date, billing_address1, billing_address2, billing_city, billing_state, billing_postal_code, billing_country, start_date, paid_until_date)
							VALUES (\"\", \"$fname\", \"$lname\", \"$address\", \"$address2\", \"$city\", \"$state\", \"$zip\", \"$country\", \"$phone\", \"$email\", \"$username\", \"$pwd\", \"$payment\", \"$item_name\", \"$nameoncard\", \"$cardnumber\", \"$exp\", \"$billingaddress\", \"$billingaddress2\", \"$ccity\", \"$cstate\", \"$czip\", \"$ccountry\", \"$todays_date\", \"$number_days_account_active\");";
	mysql_query($insert_data_sql);
	
	$temp_file_name = $fname."_".$lname;
	
	$temp_output = "$fname|$lname|$zip";
	$fp = fopen("$temp_directory/temp_".$temp_file_name.".txt", "w");
	fwrite($fp, $temp_output);
	
	if ($paypal_subsciptions == "1") {
		echo "
		<html>
			<head>
			</head>
			<BODY BGCOLOR=\"#DBDBDB\" onLoad=\"document.forms[0].submit()\">
			<form action='https://www.paypal.com/cgi-bin/webscr' method='post'>
				<input type='hidden' name='cmd' value='_xclick-subscriptions'>
				<input type=\"hidden\" name=\"business\" value=\"$paypal_email_address\">
				<input type=\"hidden\" name=\"item_name\" value=\"$item_name\">
				<input type=\"hidden\" name=\"no_shipping\" value=\"1\">
				<input type='hidden' name='no_note' value='1'>
				<input type='hidden' name='a3' value='$item_amount'>
				<input type='hidden' name='p3' value='$account_length'>
				<input type='hidden' name='t3' value='D'>
				<input type='hidden' name='src' value='1'>
				<input type='hidden' name='sra' value='1'>
				<input type=\"hidden\" name=\"return\" value=\"$full_url/return_paypal.php?name=$fname_$lname&a=$accounts\">
				<input type=\"hidden\" name=\"cancel_return\" value=\"$full_url/return_paypal.php?name=$temp_file_name&a=$accounts&c=1\">
			</form>	
			</body>	
			</html>";
			exit;		
	}else{
		echo "
			<html>
			<head>
			</head>
			<BODY BGCOLOR=\"#DBDBDB\" onLoad=\"document.forms[0].submit()\">
			<form action=\"https://www.paypal.com/cgi-bin/webscr\" method=\"post\" name=\"MyForm\">
				<input type=\"hidden\" name=\"cmd\" value=\"_xclick\">
				<input type=\"hidden\" name=\"business\" value=\"$paypal_email_address\">
				<input type=\"hidden\" name=\"item_name\" value=\"$item_name\">
				<input type=\"hidden\" name=\"amount\" value=\"$item_amount\">
				<input type=\"hidden\" name=\"no_shipping\" value=\"1\">
				<input type=\"hidden\" name=\"return\" value=\"$full_url/return_paypal.php?name=$fname_$lname&a=$accounts\">
				<input type=\"hidden\" name=\"cancel_return\" value=\"$full_url/return_paypal.php?name=$temp_file_name&a=$accounts&c=1\">
			</form>	
			</body>	
			</html>
		";
		exit;
	}
}

if ($payment == "clickbank" && $item_amount > "0") {
	$insert_data_sql = "INSERT INTO members_waiting_approval (id, first_name, last_name, address1, address2, city, state, postal_code, country, telephone_number, e_mail_address, user_name, user_password, payment_type, account_type_name, name_on_card, card_number, exp_date, billing_address1, billing_address2, billing_city, billing_state, billing_postal_code, billing_country, start_date, paid_until_date)
							VALUES (\"\", \"$fname\", \"$lname\", \"$address\", \"$address2\", \"$city\", \"$state\", \"$zip\", \"$country\", \"$phone\", \"$email\", \"$username\", \"$pwd\", \"$payment\", \"$item_name\", \"$nameoncard\", \"$cardnumber\", \"$exp\", \"$billingaddress\", \"$billingaddress2\", \"$ccity\", \"$cstate\", \"$czip\", \"$ccountry\", \"$todays_date\", \"$number_days_account_active\");";
	mysql_query($insert_data_sql);
	
	$temp_file_name = $fname."_".$lname;
	
	$temp_output = "$fname|$lname|$zip";
	$fp = fopen("$temp_directory/temp_".$temp_file_name.".txt", "w");
	fwrite($fp, $temp_output);
	
	$get_clickbank_url = "SELECT * FROM account_types WHERE account_name = \"$item_name\"";
	$result = mysql_query($get_clickbank_url);
	if ($row = mysql_fetch_object($result)) {  
		$clickbankurl = $row->clickbank;
	}	
	
	echo "
		<html>
		<head>
		</head>
		<BODY BGCOLOR=\"#DBDBDB\" onLoad=\"document.forms[0].submit()\">
		
		<form action=\"$clickbankurl\" method=\"post\" name=\"MyForm\">
		</form>	
		</body>	
		</html>
	";
	exit;
}else if ($payment == "regnow" && $item_amount > "0" ) {
	$insert_data_sql = "INSERT INTO members_waiting_approval (id, first_name, last_name, address1, address2, city, state, postal_code, country, telephone_number, e_mail_address, user_name, user_password, payment_type, account_type_name, name_on_card, card_number, exp_date, billing_address1, billing_address2, billing_city, billing_state, billing_postal_code, billing_country, start_date, paid_until_date)
							VALUES (\"\", \"$fname\", \"$lname\", \"$address\", \"$address2\", \"$city\", \"$state\", \"$zip\", \"$country\", \"$phone\", \"$email\", \"$username\", \"$pwd\", \"$payment\", \"$item_name\", \"$nameoncard\", \"$cardnumber\", \"$exp\", \"$billingaddress\", \"$billingaddress2\", \"$ccity\", \"$cstate\", \"$czip\", \"$ccountry\", \"$todays_date\", \"$number_days_account_active\");";
	mysql_query($insert_data_sql);
	
	$temp_file_name = $fname."_".$lname;
	
	$temp_output = "$fname|$lname|$zip";
	$fp = fopen("$temp_directory/temp_".$temp_file_name.".txt", "w");
	fwrite($fp, $temp_output);
	
	$get_clickbank_url = "SELECT * FROM account_types WHERE account_name = \"$item_name\"";
	$result = mysql_query($get_clickbank_url);
	if ($row = mysql_fetch_object($result)) {  
		$clickbankurl = $row->regnow;
	}	
	
	echo "
		<html>
		<head>
		</head>
		<BODY BGCOLOR=\"#DBDBDB\" onLoad=\"document.forms[0].submit()\">
		
		<form action=\"$clickbankurl\" method=\"post\" name=\"MyForm\">
		</form>	
		</body>	
		</html>
	";
	exit;
}else{
	
	$get_approval_sql = "SELECT * FROM account_types WHERE account_name = \"$payment\"";
	$result = mysql_query($get_approval_sql);
	if ($row = mysql_fetch_object($result)) {
		$automatic_approval = $row->wait_period;
	}else{
		$automatic_approval = "0";
	}	
	
	if ($automatic_approval == "1") {
			$insert_data_sql = "INSERT INTO members (id, first_name, last_name, address1, address2, city, state, postal_code, country, telephone_number, e_mail_address, user_name, user_password, card_number, exp_date, billing_address1, billing_address2, billing_city, billing_state, billing_postal_code, billing_country)
							VALUES (\"\", \"$fname\", \"$lname\", \"$address\", \"$address2\", \"$city\", \"$state\", \"$zip\", \"$country\", \"$phone\", \"$email\", \"$username\", \"$pwd\", \"$nameoncard\", \"$cardnumber\", \"$exp\", \"$billingaddress\", \"$billingaddress2\", \"$ccity\", \"$cstate\", \"$czip\", \"$ccountry\");";
			mysql_query($insert_data_sql);
			
			$insert_into_memberships = "INSERT INTO memberships (id, user_name, payment_type, package, start_date, paid_until_date, active)
							VALUES (\"\", \"$username\", \"$item_name\", \"$payment\", \"$todays_date\", \"$number_days_account_active\", \"1\",);";

			mysql_query($insert_into_memberships);
			
		$file = "$data_directory/approved.txt";
		$handle = fopen ($file, "r");
		$original_body = fread ($handle, 10000);
		fclose($handle);

		$query = "SELECT * FROM members WHERE first_name = \"$fname\" AND last_name = \"$lname\" AND user_name = \"$username\"";
		$result = mysql_query($query);
		while ($row = mysql_fetch_object($result)) {
			$thebody = $original_body;
		
			$thebody = eregi_replace("<<first_name>>", "$row->first_name", $thebody);
			$thebody = eregi_replace("<<last_name>>", "$row->last_name", $thebody);
			$thebody = eregi_replace("<<site_name>>", "$site_name", $thebody);
			$thebody = eregi_replace("<<user_name>>", "$row->user_name", $thebody);
			$thebody = eregi_replace("<<password>>", "$row->user_password", $thebody);
			$thebody = eregi_replace("<<company_email>>", "$company_email", $thebody);
			$thebody = eregi_replace("<<company_name>>", "$company_name", $thebody);
    
			$mailTo = "$row->e_mail_address";
    		$mailSubject = "$company_name Invoice";
    		$mailBody = "$thebody";
    		$mailHeaders = "From: $company_email";
    
			mail($mailTo, $mailSubject, $mailBody, $mailHeaders);
		}
		
		$insert_payment_info = "INSERT INTO payment_history (id, cust_id, payment_date, amount, type) VALUES (\"\", \"$username\", \"$todays_date\", \"$item_amount\", \"$payment\") ";
		mysql_query($insert_payment_info);
			
		header("Location: $thank_you_page");
		exit;
	}else{
		global $nnameoncard, $ccardnumber, $eexp;
			$insert_data_sql = "INSERT INTO members_waiting_approval (id, first_name, last_name, address1, address2, city, state, postal_code, country, telephone_number, e_mail_address, user_name, user_password, payment_type, account_type_name, name_on_card, card_number, exp_date, billing_address1, billing_address2, billing_city, billing_state, billing_postal_code, billing_country, start_date, paid_until_date)
							VALUES (\"\", \"$fname\", \"$lname\", \"$address\", \"$address2\", \"$city\", \"$state\", \"$zip\", \"$country\", \"$phone\", \"$email\", \"$username\", \"$pwd\", \"$payment\", \"$item_name\", \"$nnameoncard\", \"$ccardnumber\", \"$eexp\", \"$billingaddress\", \"$billingaddress2\", \"$ccity\", \"$cstate\", \"$czip\", \"$ccountry\", \"$todays_date\", \"$number_days_account_active\");";
			mysql_query($insert_data_sql);

		
		$file = "$data_directory/waiting.txt";
		$handle = fopen ($file, "r");
		$original_body = fread ($handle, 10000);
		fclose($handle);

		$query = "SELECT * FROM members_waiting_approval WHERE first_name = \"$fname\" AND last_name = \"$lname\" AND user_name = \"$username\"";
		$result = mysql_query($query);
		while ($row = mysql_fetch_object($result)) {
			$thebody = $original_body;
			
			$thebody = eregi_replace("<<first_name>>", "$row->first_name", $thebody);
			$thebody = eregi_replace("<<last_name>>", "$row->last_name", $thebody);
			$thebody = eregi_replace("<<site_name>>", "$site_name", $thebody);
			$thebody = eregi_replace("<<company_email>>", "$company_email", $thebody);
			$thebody = eregi_replace("<<company_name>>", "$company_name", $thebody);
    	
			$mailTo = "$row->e_mail_address";
	    	$mailSubject = "$company_name Invoice";
    		$mailBody = "$thebody";
    		$mailHeaders = "From: $company_email";
    
			mail($mailTo, $mailSubject, $mailBody, $mailHeaders);
		}
		header("Location: $thank_you_page_awaiting_approval");
		exit;
	}
}	

?>	