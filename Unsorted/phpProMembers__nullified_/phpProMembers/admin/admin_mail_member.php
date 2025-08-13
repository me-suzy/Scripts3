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

if ($op != "1") {
	include "templates/header.php";		
?>

<FORM ACTION="admin_mail_member.php" METHOD="POST">
<TABLE border="1" bordercolor="#000000" width="400" cellspacing="3" cellpadding="3">
	<TR>
		<td width="40%" bgcolor="#cccccc">
			<font color="#000000" face="arial">
				Member Name:
			</font>
		</td>
		<td width="60%" bgcolor="#ffffff">
			<font color="#000000" face="arial">
				<SELECT NAME="member">
				<OPTION VALUE="ALL">ALL</OPTION>
<?php
$account_type_sql = "SELECT * from members";
$result = mysql_query($account_type_sql);
while($row = mysql_fetch_object($result)) {
	echo "<OPTION VALUE=\"$row->id\">$row->user_name</OPTION>";
}
?>
				</SELECT>
			</font>
		</td>
	</TR>
	<TR>
		<td width="40%" bgcolor="#cccccc">
			<font color="#000000" face="arial">
				Subject:
			</font>
		</td>
		<td width="60%" bgcolor="#ffffff">
			<font color="#000000" face="arial">
				<input type="text" name="subject" size="43">
			</font>
		</td>
	</TR>
	<TR>
		<td width="40%" bgcolor="#cccccc">
			<font color="#000000" face="arial">
				E-Mail Body:
			</font>
		</td>
		<td width="60%" bgcolor="#ffffff">
			<font color="#000000" face="arial">
				<textarea cols="33" rows="5" name="body">
				</textarea>
			</font>
		</td>
	</TR>
	<TR>
		<td width="40%" bgcolor="#cccccc" colspan=2 align="right">
			<input type="hidden" name="op" value="1">
			<input type="submit" name="submit" value="Process">&nbsp;&nbsp;<input type="reset" name="reset" value="Reset">
		</td>
	</TR>
</Table>
</form>
</CENTER>
<?php
}else{
	if ($member == "ALL") {
		$account_type_sql = "SELECT * from members";
		$result = mysql_query($account_type_sql);
		while($row = mysql_fetch_object($result)) {
			$mailTo = $row->e_mail_address;
			$mailSubject = $subject;
			$mailBody = $body;
			$mailHeaders = "From: $company_email";
    
			mail($mailTo, $mailSubject, $mailBody, $mailHeaders);
		}
		header("Location: admin_home.php");
		exit;		
	}else{
		$account_type_sql = "SELECT * from members WHERE id = \"$member\"";
		$result = mysql_query($account_type_sql);
		while($row = mysql_fetch_object($result)) {
			$mail_to = $row->e_mail_address;
		}
    
		$mailTo = "$mail_to";
		$mailSubject = "$subject";
		$mailBody = "$body";
		$mailHeaders = "From: $company_email";
    
		mail($mailTo, $mailSubject, $mailBody, $mailHeaders);

		header("Location: admin_home.php");
		exit;
	}
}
?>
<?php
	include "templates/footer.php";	
?>	