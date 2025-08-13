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
$username = $GLOBALS["id"];
	
if ($op != "1") {
	include "templates/header.php";	
?>
<FORM ACTION="add_a_membership.php" METHOD="POST">
<TABLE border="1" bordercolor="#000000" width="400" cellspacing="3" cellpadding="3">
	<TR>
		<td width="40%" bgcolor="#cccccc">
			<font color="#000000" face="arial">
				User Name:
			</font>
		</td>
		<td width="60%" bgcolor="#ffffff">
			<font color="#000000" face="arial">
				<?php echo $username; ?>
				<input type="hidden" name="user_name" value="<?php echo $username ?>">
			</font>
		</td>
	</TR>
	<TR>
		<td width="40%" bgcolor="#cccccc">
			<font color="#000000" face="arial">
				Payment Type:
			</font>
		</td>
		<td width="60%" bgcolor="#ffffff">
			<font color="#000000" face="arial">
			<SELECT NAME="payment_type">
<?php			
	$get_payment_type = "SELECT * FROM method_of_payment";
	$result = mysql_query($get_payment_type);
	while($row = mysql_fetch_object($result)) {
		if ($row->payment_type == "credit card") {
			echo "<OPTION VALUE=\"$row->payment_type\">$row->sub_type</OPTION>";
		}else{
			echo "<OPTION VALUE=\"$row->payment_type\">$row->payment_type</OPTION>";
		}
	}
?>
			</SELECT>	
			</font>
		</td>
	</TR>
	<TR>
		<td width="40%" bgcolor="#cccccc" valign="top">
			<font color="#000000" face="arial">
				Package:
			</font>
		</td>
		<td width="60%" bgcolor="#ffffff">
			<font color="#000000" face="arial">
			<SELECT NAME="package">
<?php			
	$get_payment_type = "SELECT * FROM account_types";
	$result = mysql_query($get_payment_type);
	while($row = mysql_fetch_object($result)) {
		echo "<OPTION VALUE=\"$row->account_name\">$row->account_name</OPTION>";
	}
?>			
			</SELECT>
			</font>
		</td>
	</TR>
	<TR>
		<td width="40%" bgcolor="#cccccc">
			<font color="#000000" face="arial">
				Start Date:
			</font>
		</td>
		<td width="60%" bgcolor="#ffffff">
			<font color="#000000" face="arial">
				<input type="text" name="start_date" size="43">
				<input onclick=DoCal(this.form.start_date) type=button value="Choose Date">
			</font>
		</td>
	</TR>
	<TR>
		<td width="40%" bgcolor="#cccccc">
			<font color="#000000" face="arial">
				Paid Until Date:
			</font>
		</td>
		<td width="60%" bgcolor="#ffffff">
			<font color="#000000" face="arial">
				<input type="text" name="paid_until_date" size="43">
				<input onclick=DoCal(this.form.paid_until_date) type=button value="Choose Date">
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
</CENTER>
<?php
}else{
	$insert_sql = "INSERT INTO memberships (id, user_name, payment_type, package, start_date, paid_until_date,  active)
				VALUES (\"\", \"$user_name\", \"$payment_type\", \"$package\", \"$start_date\", \"$paid_until_date\", \"$active\");";
	mysql_query($insert_sql);
	
	header("Location: view_memberships.php?id=$user_name");
	exit;
}
?>
<?php
	include "templates/footer.php";	
?>	