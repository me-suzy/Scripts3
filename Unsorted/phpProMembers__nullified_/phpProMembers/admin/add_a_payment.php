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
<FORM ACTION="add_a_payment.php" METHOD="POST">
<TABLE border="1" bordercolor="#000000" width="400" cellspacing="3" cellpadding="3">
	<TR>
		<td width="40%" bgcolor="#cccccc">
			<font color="#000000" face="arial">
				Payment Type:
			</font>
		</td>
		<td width="60%" bgcolor="#ffffff">
			<font color="#000000" face="arial">
				<input type="text" name="payment_type" size="43">
			</font>
		</td>
	</TR>
	<TR>
		<td width="40%" bgcolor="#cccccc">
			<font color="#000000" face="arial">
				Sub-Type:
			</font>
		</td>
		<td width="60%" bgcolor="#ffffff">
			<font color="#000000" face="arial">
				<input type="text" name="sub_type" size="43">
			</font>
		</td>
	</TR>
	<TR>
		<td width="40%" bgcolor="#cccccc" valign="top">
			<font color="#000000" face="arial">
				Payment Address:
			</font>
		</td>
		<td width="60%" bgcolor="#ffffff">
			<font color="#000000" face="arial">
				<textarea cols="33" rows="10" name="payment_address">
				</textarea>
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
	$insert_sql = "INSERT INTO method_of_payment (id, payment_type, sub_type, payment_address, active)
				VALUES (\"\", \"$payment_type\", \"$sub_type\", \"$payment_address\", \"$active\");";
	mysql_query($insert_sql);
	header("Location: admin_payment_methods.php");
	exit;
}
?>
<?php
	include "templates/footer.php";	
?>	