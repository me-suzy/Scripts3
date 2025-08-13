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

$data = $HTTP_COOKIE_VARS["clickbank"];
$temp_data = explode("|", $data);

$fname = $temp_data[0];
$lname = $temp_data[1];
$zip = $temp_data[2];

$transfer_sql = "SELECT * FROM members_waiting_approval WHERE last_name = \"$lname\" AND postal_code = \"$zip\"";
$result = mysql_query($transfer_sql);
while($row = mysql_fetch_object($result)) {
	$payment = $row->account_type_name;
}

$get_approval_sql = "SELECT * FROM account_types WHERE account_name = \"$payment\"";
$result = mysql_query($get_approval_sql);
if ($row = mysql_fetch_object($result)) {
	$automatic_approval = $row->wait_period;
}else{
	$automatic_approval == "0";
}	


if ($automatic_approval == "1") {
	$transfer_sql = "SELECT * FROM members_waiting_approval WHERE first_name = \"$fname\" AND last_name = \"$lname\" AND postal_code = \"$zip\"";
	$result = mysql_query($transfer_sql);
	while($row = mysql_fetch_object($result)) {
		$insert_data_sql = "INSERT INTO members (id, first_name, last_name, address1, address2, city, state, postal_code, country, telephone_number, e_mail_address, user_name, user_password, billing_address1, billing_address2, billing_city, billing_state, billing_postal_code, billing_country)
								VALUES (\"\", \"$row->first_name\", \"$row->last_name\", \"$row->address1\", \"$row->address2\", \"$row->city\", \"$row->state\", \"$row->postal_code\", \"$row->country\", \"$row->telephone_number\", \"$row->e_mail_address\", \"$row->user_name\", \"$row->user_password\", \"$row->billing_address1\", 
								\"$row->billing_address2\", \"$row->billing_city\", \"$row->billing_state\", \"$row->billing_postal_code\", \"$row->billing_country\");";
		mysql_query($insert_data_sql);
		
		$insert_into_memberships = "INSERT INTO memberships (id, user_name, payment_type, package, start_date, paid_until_date, active)
							VALUES (\"\", \"$row->user_name\", \"$row->payment_type\", \"$row->account_type_name\", \"$row->start_date\", \"$row->paid_until_date\", \"1\",);";

		mysql_query($insert_into_memberships);
	}
		
	$clean_up_sql = "DELETE FROM `members_waiting_approval` WHERE user_name = \"$username\"";
	mysql_query($clean_up_sql);
	
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
	
	$todays_date = date("Y-m-d");
	$insert_payment_info = "INSERT INTO payment_history (id, cust_id, payment_date, amount, type) VALUES (\"\", \"$username\", \"$todays_date\", \"$item_amount\", \"$payment\") ";
	mysql_query($insert_payment_info);
		
	header("Location: $thank_you_page");
	exit;

}else{
	unlink("$temp_directory/temp_".$fname."_".$lname.".txt");
	
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
	header("Location: $thank_you_page");
	exit;
}

?>