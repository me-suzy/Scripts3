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
$page_account = "admin";
require "include.php";

$insert_info = "UPDATE members_waiting_approval SET 
				first_name = \"$first_name\", 
				last_name = \"$last_name\", 
				address1 = \"$address1\",	
				address2 = \"$address2\", 
				city = \"$city\", 
				state = \"$state\",	
				postal_code = \"$postal_code\",	
				country = \"$country\",	
				telephone_number = \"$telephone_number\", 
				e_mail_address = \"$e_mail_address\", 
				user_name = \"$user_name\",	
				user_password = \"$user_password\",	
				payment_type = \"$payment_type\", 
				account_type_name = \"$account_type_name\", 
				name_on_card = \"$name_on_card\",
				card_number = \"$card_number\",
				exp_date = \"$exp_date\",
				billing_address1 = \"$billing_address1\",	
				billing_address2 = \"$billing_address2\", 
				billing_city = \"$billing_city\",	
				billing_state = \"$billing_state\",	
				billing_postal_code = \"$billing_postal_code\",	
				billing_country = \"$billing_country\",	
				start_date = \"$start_date\", 
				paid_until_date = \"$paid_until_date\" 
				WHERE id = \"$id\"";

mysql_query($insert_info);

header("Location: admin_view_details_waiting.php?id=$id");
exit;
?>