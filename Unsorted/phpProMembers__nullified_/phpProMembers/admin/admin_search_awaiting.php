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
if ($id < 1) {
	include "templates/header.php";	
?>	
<TABLE border="0" bordercolor="#000000" width="100%" cellspacing="0" cellpadding="3">
	<TR>
		<TD bgcolor="#cccccc">
			<center><b><font face="arial" size="-1">Name</font></b></center>
		</TD>
		<TD bgcolor="#cccccc">
			<center><b><font face="arial" size="-1">User Name</font></b></center>
		</TD>
		<TD bgcolor="#cccccc">
			<center><b><font face="arial" size="-1">Payment Type</font></b></center>
		</TD>
		<TD bgcolor="#cccccc">
			<center><b><font face="arial" size="-1">Package Chosen</font></b></center>
		</TD>
		<TD bgcolor="#cccccc">
			<center><b><font face="arial" size="-1">Paid Until Date</font></b></center>
		</TD>
		<TD bgcolor="#cccccc">
			<center><b><font face="arial" size="-1">Approve</font></b></center>
		</TD>
		<TD bgcolor="#cccccc">
			<center><b><font face="arial" size="-1">Delete</font></b></center>
		</TD>
	</TR>		
<?php
	$x = 0;
	$get_awaiting_approval_sql = "SELECT * FROM members_waiting_approval ORDER BY id";
	$result = mysql_query($get_awaiting_approval_sql);
	while($row = mysql_fetch_object($result)) {
		echo "<tr>
			<td bgcolor=\"#ffffff\"><a href=\"admin_view_details_waiting.php?id=$row->id\"><font face=\"arial\" size=\"-2\" color=\"red\">$row->first_name $row->last_name</font></a></td>
			<td bgcolor=\"#ffffff\"><font face=\"arial\" size=\"-2\">$row->user_name</font></td>
			<td bgcolor=\"#ffffff\"><font face=\"arial\" size=\"-2\">$row->payment_type</font></td>
			<td bgcolor=\"#ffffff\"><font face=\"arial\" size=\"-2\">$row->account_type_name</font></td>
			<td bgcolor=\"#ffffff\"><font face=\"arial\" size=\"-2\">$row->paid_until_date</font></td>
			<td bgcolor=\"#ffffff\" align=\"center\"><a href=\"admin_search_awaiting.php?approve=1&id=$row->id\"><font face=\"arial\" size=\"-2\" color=\"red\">approve</font></a></td>
			<td bgcolor=\"#ffffff\" align=\"center\"><a href=\"admin_search_awaiting.php?approve=0&id=$row->id\"><font face=\"arial\" size=\"-2\" color=\"red\">delete</font></a></td>					
		</tr>";
		$x += 1;
	}
	
	if ($x == "0") {
		echo "<TR><TD WIDTH=\"100%\" colspan=\"7\"><BR><CENTER><h3><FONT COLOR=\"Red\" FACE=\"arial\">There are no accounts waiting approval.</FONT></H3></CENTER></TD></TR>";
	}
	
?>
</TABLE>
<?php
}else{

$this_id = $GLOBALS["id"];
$approve = $GLOBALS["approve"];

if ($approve == "1") {
	$transfer_sql = "SELECT * FROM members_waiting_approval WHERE id = \"$this_id\"";
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

	$file = "$data_directory/approved.txt";
	$handle = fopen ($file, "r");
	$original_body = fread ($handle, 10000);
	fclose($handle);

	$query = "SELECT * FROM members_waiting_approval WHERE id = \"$this_id\"";
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

	$clean_up_sql = "DELETE FROM `members_waiting_approval` WHERE id = \"$this_id\"";
	mysql_query($clean_up_sql);
	
	$todays_date = date("Y-m-d");
	$insert_payment_info = "INSERT INTO payment_history (id, cust_id, payment_date, amount, type) VALUES (\"\", \"$username\", \"$todays_date\", \"$item_amount\", \"$payment\") ";
	mysql_query($insert_payment_info);
	
	header("Location: admin_search_awaiting.php");
	exit;
}else{
	$file = "$data_directory/denied.txt";
	$handle = fopen ($file, "r");
	$original_body = fread ($handle, 10000);
	fclose($handle);

	$query = "SELECT * FROM members_waiting_approval WHERE id = \"$this_id\"";
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

	$clean_up_sql = "DELETE FROM `members_waiting_approval` WHERE id = \"$this_id\"";
	mysql_query($clean_up_sql);
	
	header("Location: admin_search_awaiting.php");
	exit;
}
}
?>
<?php
include "templates/footer.php";	
?>	