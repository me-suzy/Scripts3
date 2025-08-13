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
	$this_member = $GLOBALS["member"];
	
	if (!$op) {
	include "templates/header.php";	
?>
<FORM ACTION="membership_edit.php" METHOD="POST">
<br>
<br>
<?php
	$get_total_members = "SELECT * FROM memberships WHERE id = \"$this_id\"";
	$result = mysql_query($get_total_members);
	while($row = mysql_fetch_object($result)) {
?>
<TABLE border="1" bordercolor="#000000" width="400" cellspacing="3" cellpadding="3">
	<TR>
		<td width="40%" bgcolor="#cccccc">
			<font color="#000000" face="arial">
				User Name:
			</font>
		</td>
		<td width="60%" bgcolor="#ffffff">
			<font color="#000000" face="arial">
				<?php echo $row->user_name ?>
				<input type="hidden" name="user_name" value="<?php echo $row->user_name ?>">
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
			<OPTION VALUE="<?php echo $row->payment_type ?>"><?php echo $row->payment_type ?></OPTION>
<?php			
	$get_payment_type = "SELECT * FROM method_of_payment";
	$result2 = mysql_query($get_payment_type);
	while($row2 = mysql_fetch_object($result2)) {
		if ($row2->payment_type == "credit card") {
			echo "<OPTION VALUE=\"$row2->payment_type\">$row2->sub_type</OPTION>";
		}else{
			echo "<OPTION VALUE=\"$row2->payment_type\">$row2->payment_type</OPTION>";
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
			<OPTION VALUE="<?php echo $row->package ?>"><?php echo $row->package ?></OPTION>
<?php			
	$get_payment_type = "SELECT * FROM account_types";
	$result3 = mysql_query($get_payment_type);
	while($row3 = mysql_fetch_object($result3)) {
		echo "<OPTION VALUE=\"$row3->account_name\">$row3->account_name</OPTION>";
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
				<input type="text" name="start_date" size="43" value="<?php echo $row->start_date ?>">
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
				<input type="text" name="paid_until_date" size="43" value="<?php echo $row->paid_until_date ?>">
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
	<tr>
		<td colspan = "2">
			<input type="hidden" name="op" value="1">
			<input type="hidden" name="id" value="<?php echo $this_id ?>">
			<input type="submit" name="submit" value="process">
			<input type="reset" name="reset">
			<a href="admin_account_types.php?a=3&id=<?php echo $this_id ?>">
			<font size=2 face="arial" color="red"><b>delete</b></font></a>
		</td>
	</tr>
</Table>
<br>
<br>
<?php
		}
?>

</form>
<?php
include "templates/footer.php";	
}else{
	$update_payment_method = "UPDATE memberships SET 
								payment_type = \"$payment_type\", 
								package = \"$package\",
								start_date = \"$start_date\",
								paid_until_date = \"$paid_until_date\",
								active = \"$active\" WHERE id = \"$id\"";
	
	mysql_query($update_payment_method);
	
	header("Location: view_memberships.php?id=$user_name");
	exit;
}
?>
