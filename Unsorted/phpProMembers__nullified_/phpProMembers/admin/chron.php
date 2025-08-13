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
$todays_date = date("Y-m-d");

$get_user_date = "SELECT * FROM members WHERE paid_until_date < \"$todays_date\" AND active = \"1\"";
$result = mysql_query($get_user_date);
while($row = mysql_fetch_object($result)) {
	$edit_users_sql = "UPDATE members SET active = \"0\" WHERE id = \"$row->id\"";
	mysql_query($edit_users_sql);
}


$file = "$data_directory/renewal.txt";
$handle = fopen ($file, "r");
$original_body = fread ($handle, 10000);
fclose($handle);

$future_date = date("Y-m-d",time() + 604800);
$send_email_to_sql = "SELECT * FROM members WHERE paid_until_date > \"$todays_date\" AND paid_until_date < \"$future_date\" AND active = \"1\"";
$result = mysql_query($send_email_to_sql);
while ($row = mysql_fetch_object($result)) {
	$thebody = $original_body;
		
	$thebody = eregi_replace("<<first_name>>", "$row->first_name", $thebody);
	$thebody = eregi_replace("<<last_name>>", "$row->last_name", $thebody);
	$thebody = eregi_replace("<<site_name>>", "$site_name", $thebody);
	$thebody = eregi_replace("<<renewal_link>>", "$renewal_link", $thebody);
	$thebody = eregi_replace("<<company_email>>", "$company_email", $thebody);
	$thebody = eregi_replace("<<company_name>>", "$company_name", $thebody);
    
	$mailTo = "$row->e_mail_address";
    $mailSubject = "$company_name Invoice";
    $mailBody = "$thebody";
    $mailHeaders = "From: $company_email";
    
	mail($mailTo, $mailSubject, $mailBody, $mailHeaders);
	
}



?>
