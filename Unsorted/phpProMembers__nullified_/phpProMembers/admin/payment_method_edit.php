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
<FORM ACTION="payment_method_edit.php" METHOD="POST">
<br>
<br>

<?php
	$get_total_members = "SELECT * FROM method_of_payment WHERE id = \"$this_id\"";
	$result = mysql_query($get_total_members);
	while($row = mysql_fetch_object($result)) {
?>
<TABLE border="1" bordercolor="#000000" width="400" cellspacing="3" cellpadding="3">
	<TR>
		<td width="40%" bgcolor="#cccccc">
			<font color="#000000" face="arial">
				Payment Type:
			</font>
		</td>
		<td width="60%" bgcolor="#ffffff">
			<font color="#000000" face="arial">
				<input type="text" name="payment_type" value="<?php echo $row->payment_type ?>">
			</font>
		</td>
	</TR>
	<TR>
		<td width="40%" bgcolor="#cccccc">
			<font color="#000000" face="arial">
				Payment Sub-Type:
			</font>
		</td>
		<td width="60%" bgcolor="#ffffff">
			<font color="#000000" face="arial">
				<input type="text" name="sub_type" value="<?php echo $row->sub_type ?>">
			</font>
		</td>
	</TR>
	<TR>
		<td width="40%" bgcolor="#cccccc">
			<font color="#000000" face="arial">
				Payment Address:
			</font>
		</td>
		<td width="60%" bgcolor="#ffffff">
			<font color="#000000" face="arial">
				<textarea cols="" rows="" name="payment_address"><?php echo $row->payment_address ?></textarea>
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
	<tr>
		<td colspan = "2">
			<input type="hidden" name="op" value="1">
			<input type="hidden" name="id" value="<?php echo $this_id ?>">
			<input type="submit" name="submit" value="process">
			<input type="reset" name="reset">
			<a href="admin_payment_methods.php?a=3&id=<?php echo $this_id ?>">
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
	$update_payment_method = "UPDATE method_of_payment SET payment_type = \"$payment_type\", sub_type = \"$sub_type\", payment_address = \"$payment_address\",
	active = \"$active\" WHERE id = \"$id\"";
	mysql_query($update_payment_method);
	
	header("Location: payment_method_view.php?id=$id");
	exit;
}
?>
