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
include("templates/header.php");

$todays_date = date("Y-m-d");
$future_date = date("Y-m-d",time() + 604800);


$file = "$data_directory/renewal.txt";
$handle = fopen ($file, "r");
$original_body = fread ($handle, 10000);
fclose($handle);

$query = "SELECT * FROM members";
$result = mysql_query($query);
while ($row = mysql_fetch_object($result)) {
	$new_query = "SELECT * FROM memberships WHERE user_name = \"$row->user_name\" AND paid_until_date > \"$todays_date\" AND paid_until_date < \"$future_date\" AND active = \"1\"";
	$result2 = mysql_query($new_query);
	while ($row2 = mysql_fetch_object($result2)) {	
		$thebody = $original_body;	
		$thebody = eregi_replace("<<first_name>>", "$row->first_name", $thebody);
		$thebody = eregi_replace("<<last_name>>", "$row->last_name", $thebody);
		$thebody = eregi_replace("<<site_name>>", "$site_name", $thebody);
		$thebody = eregi_replace("<<renewal_link>>", "$renewal_link", $thebody);
		$thebody = eregi_replace("<<company_email>>", "$company_email", $thebody);
		$thebody = eregi_replace("<<company_name>>", "$company_name", $thebody);
		$thebody = eregi_replace("<<package>>", "$row2->package", $thebody);
    
		$mailTo = "$row->e_mail_address";
    	$mailSubject = "$company_name Invoice";
	    $mailBody = "$thebody";
    	$mailHeaders = "From: $company_email";
		mail($mailTo, $mailSubject, $mailBody, $mailHeaders);
	}
}
echo "<center><FONT COLOR=\"RED\" FACE=\"arial\"><b>Done<b></FONT></center>";
include("templates/footer.php");
exit;
?>	
