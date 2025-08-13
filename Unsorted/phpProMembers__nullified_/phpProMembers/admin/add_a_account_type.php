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

	
	$this_id = $GLOBALS["id"];
	
	if ($op != "1") {
		include("templates/header.php");
?>

<FORM ACTION="add_a_account_type.php" METHOD="POST">
<TABLE border="1" bordercolor="#000000" width="450" cellspacing="3" cellpadding="3">
	<TR>
		<td width="40%" bgcolor="#cccccc">
			<font color="#000000" face="arial">
				Account Name:
			</font>
		</td>
		<td width="60%" bgcolor="#ffffff">
			<font color="#000000" face="arial">
				<input type="text" name="account_name" size="43">
			</font>
		</td>
	</TR>
	<TR>
		<td width="40%" bgcolor="#cccccc">
			<font color="#000000" face="arial">
				Account Setup Fee:
			</font>
		</td>
		<td width="60%" bgcolor="#ffffff">
			<font color="#000000" face="arial">
				<input type="text" name="account_setup_fee" size="43">
			</font>
		</td>
	</TR>
	<TR>
		<td width="40%" bgcolor="#cccccc" valign="top">
			<font color="#000000" face="arial">
				Account Fee:
			</font>
		</td>
		<td width="60%" bgcolor="#ffffff">
			<font color="#000000" face="arial">
				<input type="text" name="account_fee" size="43">
			</font>
		</td>
	</TR>
	<TR>
		<td width="40%" bgcolor="#cccccc">
			<font color="#000000" face="arial">
				Length in Days:
			</font>
		</td>
		<td width="60%" bgcolor="#ffffff">
			<font color="#000000" face="arial">
				<input type="text" name="length" size="43">
			</font>
		</td>
	</TR>
	<TR>
		<td width="40%" bgcolor="#cccccc">
			<font color="#000000" face="arial">
				Active:
			</font>
		</td>
		<td width="60%" bgcolor="#ffffff">
			<font color="#000000" face="arial">
			<SELECT NAME="active">
				<OPTION VALUE="1">active</OPTION>
				<OPTION VALUE="0">inactive</OPTION>
			</SELECT>	
		</td>
	</TR>
	<TR>
		<td width="40%" bgcolor="#cccccc">
			<font color="#000000" face="arial">
				Automatic Approval Code:
			</font>
		</td>
		<td width="60%" bgcolor="#ffffff">
			<font color="#000000" face="arial">
			<SELECT NAME="wait_period">
				<OPTION VALUE="1">automatic approval</OPTION>
				<OPTION VALUE="0">admin approval</OPTION>
			</SELECT>	
		</td>
	</TR>	
	<TR>
		<td width="40%" bgcolor="#cccccc">
			<font color="#000000" face="arial">
				Renewal Code:
			</font>
		</td>
		<td width="60%" bgcolor="#ffffff">
			<font color="#000000" face="arial">
			<SELECT NAME="renewal">
				<OPTION VALUE="1">able to renew</OPTION>
				<OPTION VALUE="0">not able to renew</OPTION>
			</SELECT>	
			</font>
			
		</td>
	</TR>	
	<TR>
		<td width="40%" bgcolor="#cccccc">
			<font color="#000000" face="arial">
				Click Bank URL:
			</font>
		</td>
		<td width="60%" bgcolor="#ffffff">
			<font color="#000000" face="arial">
				<input type="text" name="clickbank" size="43">
			</font>
		</td>
	</TR>	
	<TR>
		<td width="40%" bgcolor="#cccccc">
			<font color="#000000" face="arial">
				RegNow URL:
			</font>
		</td>
		<td width="60%" bgcolor="#ffffff">
			<font color="#000000" face="arial">
				<input type="text" name="regnow" size="43">
			</font>
		</td>
	</TR>	
	<TR>
		<td width="100%" bgcolor="#cccccc" colspan="2" align="right">
			<font color="#000000" face="arial">
				<input type="hidden" name="op" value="1">
				<INPUT TYPE="SUBMIT" NAME="process" VALUE="Process">&nbsp;&nbsp;&nbsp;<input type="reset" name="reset">
			</font>
		</td>
	</TR>
</Table>
</form>
<br>

<br>

</CENTER>
<?php
}else{
	$insert_sql = "INSERT INTO account_types (id, account_name, account_setup_fee, account_fee, length, active, wait_period, renewal, clickbank, regnow)
				VALUES (\"\", \"$account_name\", \"$account_setup_fee\", \"$account_fee\", \"$length\", \"$active\", \"$wait_period\", \"$renewal\", \"$clickbank\", \"$regnow\");";

	mysql_query($insert_sql);

	header("Location: admin_account_types.php");
	exit;
}
?>
<?php
	include "templates/footer.php";	
?>	