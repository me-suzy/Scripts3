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

$get_user_info_sql = "SELECT * FROM members WHERE user_name = \"$user\"";
$result = mysql_query($get_user_info_sql);
while($row = mysql_fetch_object($result)) {
	$account_type = $row->account_type_name;
	$paid_until_date = $row->paid_until_date;
	
}

$get_user_info_sql = "SELECT * FROM account_types WHERE account_name = \"$account_type\"";
$result = mysql_query($get_user_info_sql);
while($row = mysql_fetch_object($result)) {
	$account_length = "$row->length";
}

$todays_date = $paid_until_date;
$number_days_account_active = date("$todays_date",time() + ($account_length * 86400)); 

$update_sql = "UPDATE memberships SET paid_until_date = \"$number_days_account_active\" AND active = \"1\"  WHERE user_name = \"$user\" AND package = \"$account_type\"";
mysql_query($update_sql);

$todays_date = date("Y-m-d");
$insert_payment_info = "INSERT INTO payment_history (id, cust_id, payment_date, amount, type) VALUES (\"\", \"$username\", \"$todays_date\", \"$item_amount\", \"$payment\") ";
mysql_query($insert_payment_info);
		
header("Location: $renewal_thank_you");
exit;
?>