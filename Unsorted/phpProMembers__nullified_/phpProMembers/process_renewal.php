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
require "include.php";

if ($payment == "clickbank" && $item_amount > "0") {
	
	$get_user_info_sql = "SELECT * FROM members WHERE user_name = \"$PHP_AUTH_USER\"";
	$result = mysql_query($get_user_info_sql);
	while($row = mysql_fetch_object($result)) {
		$fname = $row->first_name;
		$lname = $row->last_name;
		$zip = $row->zip;
	}
	
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
}

if ($payment == "paypal" && $item_amount > "0") {
	$get_user_info_sql = "SELECT * FROM members WHERE user_name = \"$PHP_AUTH_USER\"";
	$result = mysql_query($get_user_info_sql);
	while($row = mysql_fetch_object($result)) {
		$account_type = $row->account_type_name;
	}
	
	$get_user_info_sql = "SELECT * FROM account_types WHERE account_name = \"$account_type\"";
	$result = mysql_query($get_user_info_sql);
	while($row = mysql_fetch_object($result)) {
		$account_length = "$row->length";
		$item_name = "$row->account_name";
		$item_amount = $row->account_fee + $row->account_setup_fee;
	}
	
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
			<input type=\"hidden\" name=\"return\" value=\"$full_url/paypal_renewal.php?user=$PHP_AUTH_USER\">
			<input type=\"hidden\" name=\"cancel_return\" value=\"$website_address\">
		</form>	
		</body>	
		</html>
	";
	exit;
}else{
	$get_user_info_sql = "SELECT * FROM members WHERE user_name = \"$PHP_AUTH_USER\"";
	$result = mysql_query($get_user_info_sql);
	while($row = mysql_fetch_object($result)) {
		$account_user_name = $row->account_type_name;
	}
	$thebody = "$PHP_AUTH_USER has renewed there account.";
    
	$mailTo = "$company_email";
   	$mailSubject = "User Renewal";
   	$mailBody = "$thebody";
   	$mailHeaders = "From: $company_email";
    mail($mailTo, $mailSubject, $mailBody, $mailHeaders);
	
	$todays_date = date("Y-m-d");
	$insert_payment_info = "INSERT INTO payment_history (id, cust_id, payment_date, amount, type) VALUES (\"\", \"$username\", \"$todays_date\", \"$item_amount\", \"$payment\") ";
	mysql_query($insert_payment_info);
	header("Location: $renewal_thank_you");
	exit;
}
?>
