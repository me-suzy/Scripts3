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
	
	if (!$op) {
	include "templates/header.php";	
?>
<FORM ACTION="account_types_edit.php" METHOD="POST">
<br>
<br>
<?php
	$get_total_members = "SELECT * FROM account_types WHERE id = \"$this_id\"";
	$result = mysql_query($get_total_members);
	while($row = mysql_fetch_object($result)) {
?>
<TABLE border="1" bordercolor="#000000" width="400" cellspacing="3" cellpadding="3">
	<TR>
		<td width="40%" bgcolor="#cccccc">
			<font color="#000000" face="arial">
				Account Name:
			</font>
		</td>
		<td width="60%" bgcolor="#ffffff">
			<font color="#000000" face="arial">
				<input type="text" name="account_name" value="<?php echo $row->account_name ?>">
				<input type="text" name="old_account_name" value="<?php echo $row->account_name ?>">
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
				<input type="text" name="account_setup_fee" value="<?php echo $row->account_setup_fee ?>">
			</font>
		</td>
	</TR>
	<TR>
		<td width="40%" bgcolor="#cccccc">
			<font color="#000000" face="arial">
				Account Fee:
			</font>
		</td>
		<td width="60%" bgcolor="#ffffff">
			<font color="#000000" face="arial">
				<input type="text" name="account_fee" value="<?php echo $row->account_fee ?>">
			</font>
		</td>
	</TR>
	<TR>
		<td width="40%" bgcolor="#cccccc">
			<font color="#000000" face="arial">
				Length In Days:
			</font>
		</td>
		<td width="60%" bgcolor="#ffffff">
			<font color="#000000" face="arial">
				<input type="text" name="length" value="<?php echo $row->length ?>">
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
				<select name="active">
					<option value="1">active</option>
					<option value="0">inactive</option>
				</select>
			</font>
		</td>
	</TR>
	<TR>
		<td width="40%" bgcolor="#cccccc">
			<font color="#000000" face="arial">
				Waiting Period:
			</font>
		</td>
		<td width="60%" bgcolor="#ffffff">
			<font color="#000000" face="arial">
				<select name="wait_period">
					<option value="0">yes</option>
					<option value="1">no</option>
				</select>
			</font>
		</td>
	</TR>
	<TR>
		<td width="40%" bgcolor="#cccccc">
			<font color="#000000" face="arial">
				Renewable:
			</font>
		</td>
		<td width="60%" bgcolor="#ffffff">
			<font color="#000000" face="arial">
				<select name="renewal">
					<option value="1">yes</option>
					<option value="0">no</option>
				</select>
			</font>
		</td>
	</TR>
	<TR>
		<td width="40%" bgcolor="#cccccc">
			<font color="#000000" face="arial">
				ClickBank URL:
			</font>
		</td>
		<td width="60%" bgcolor="#ffffff">
			<font color="#000000" face="arial">
				<input type="text" name="clickbank" value="<?php echo $row->clickbank ?>">
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
				<input type="text" name="regnow" value="<?php echo $row->regnow ?>">
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
	$update_payment_method = "UPDATE account_types SET 
								account_name = \"$account_name\", 
								account_setup_fee = \"$account_setup_fee\", 
								account_fee = \"$account_fee\",
								length = \"$length\",
								active = \"$active\",
								wait_period = \"$wait_period\",
								renewal = \"$renewal\",
								clickbank = \"$clickbank\",
								regnow = \"$regnow\" WHERE id = \"$id\"";
	
	mysql_query($update_payment_method);
	
	$update_memberships = "UPDATE memberships SET 
								package = \"$account_name\", 
								WHERE package = \"$old_account_name\"";
	
	mysql_query($update_memberships);
	
	
	
	header("Location: account_types_view.php?id=$id");
	exit;
}
?>
