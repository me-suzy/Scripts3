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
require "include.php";

if ($include_template == "1") {
	include "$template_directory/header.php";	
}

?>


<br>
<center>
<a href="member_modify.php"><font color="red" face="arial"><b>edit</b></font></a>
</center>
<br>

<?php
	$get_total_members = "SELECT * FROM members WHERE id = \"$valid_user\"";
	$result = mysql_query($get_total_members);
	while($row = mysql_fetch_object($result)) {
?>
<TABLE border="1" bordercolor="#000000" width="500" cellspacing="3" cellpadding="3">
	<TR>
		<td width="40%" bgcolor="#cccccc">
			<font color="#000000" face="arial">
				First Name:
			</font>
		</td>
		<td width="60%" bgcolor="#ffffff">
			<font color="#000000" face="arial">
				<?php echo $row->first_name ?>
			</font>
		</td>
	</TR>
	<TR>
		<td width="40%" bgcolor="#cccccc">
			<font color="#000000" face="arial">
				Last Name:
			</font>
		</td>
		<td width="60%" bgcolor="#ffffff">
			<font color="#000000" face="arial">
				<?php echo $row->last_name ?>
			</font>
		</td>
	</TR>
	<TR>
		<td width="40%" bgcolor="#cccccc">
			<font color="#000000" face="arial">
				Address1:
			</font>
		</td>
		<td width="60%" bgcolor="#ffffff">
			<font color="#000000" face="arial">
				<?php echo $row->address1 ?>
			</font>
		</td>
	</TR>
	<TR>
		<td width="40%" bgcolor="#cccccc">
			<font color="#000000" face="arial">
				Address2:
			</font>
		</td>
		<td width="60%" bgcolor="#ffffff">
			<font color="#000000" face="arial">
				<?php echo $row->address2 ?>
			</font>
		</td>
	</TR>
	<TR>
		<td width="40%" bgcolor="#cccccc">
			<font color="#000000" face="arial">
				City:
			</font>
		</td>
		<td width="60%" bgcolor="#ffffff">
			<font color="#000000" face="arial">
				<?php echo $row->city ?>
			</font>
		</td>
	</TR>
	<TR>
		<td width="40%" bgcolor="#cccccc">
			<font color="#000000" face="arial">
				State:
			</font>
		</td>
		<td width="60%" bgcolor="#ffffff">
			<font color="#000000" face="arial">
				<?php echo $row->state ?>
			</font>
		</td>
	</TR>
	<TR>
		<td width="40%" bgcolor="#cccccc">
			<font color="#000000" face="arial">
				Postal Code:
			</font>
		</td>
		<td width="60%" bgcolor="#ffffff">
			<font color="#000000" face="arial">
				<?php echo $row->postal_code ?>
			</font>
		</td>
	</TR><TR>
		<td width="40%" bgcolor="#cccccc">
			<font color="#000000" face="arial">
				Country:
			</font>
		</td>
		<td width="60%" bgcolor="#ffffff">
			<font color="#000000" face="arial">
				<?php echo $row->country ?>
			</font>
		</td>
	</TR><TR>
		<td width="40%" bgcolor="#cccccc">
			<font color="#000000" face="arial">
				Telephone Number:
			</font>
		</td>
		<td width="60%" bgcolor="#ffffff">
			<font color="#000000" face="arial">
				<?php echo $row->telephone_number ?>
			</font>
		</td>
	</TR><TR>
		<td width="40%" bgcolor="#cccccc">
			<font color="#000000" face="arial">
				E-Mail Address:
			</font>
		</td>
		<td width="60%" bgcolor="#ffffff">
			<font color="#000000" face="arial">
				<?php echo $row->e_mail_address ?>
			</font>
		</td>
	</TR><TR>
		<td width="40%" bgcolor="#cccccc">
			<font color="#000000" face="arial">
				User Name:
			</font>
		</td>
		<td width="60%" bgcolor="#ffffff">
			<font color="#000000" face="arial">
				<?php echo $row->user_name ?>
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
				<?php echo $row->payment_type ?>
			</font>
		</td>
	</TR><TR>
		<td width="40%" bgcolor="#cccccc">
			<font color="#000000" face="arial">
				Account Type:
			</font>
		</td>
		<td width="60%" bgcolor="#ffffff">
			<font color="#000000" face="arial">
				<?php echo $row->account_type_name ?>
			</font>
		</td>
	</TR><TR>
		<td width="40%" bgcolor="#cccccc">
			<font color="#000000" face="arial">
				Billing Address1:
			</font>
		</td>
		<td width="60%" bgcolor="#ffffff">
			<font color="#000000" face="arial">
				<?php echo $row->billing_address1 ?>
			</font>
		</td>
	</TR><TR>
		<td width="40%" bgcolor="#cccccc">
			<font color="#000000" face="arial">
				Billing Address2:
			</font>
		</td>
		<td width="60%" bgcolor="#ffffff">
			<font color="#000000" face="arial">
				<?php echo $row->billing_address2 ?>
			</font>
		</td>
	</TR><TR>
		<td width="40%" bgcolor="#cccccc">
			<font color="#000000" face="arial">
				Billing City:
			</font>
		</td>
		<td width="60%" bgcolor="#ffffff">
			<font color="#000000" face="arial">
				<?php echo $row->billing_city ?>
			</font>
		</td>
	</TR><TR>
		<td width="40%" bgcolor="#cccccc">
			<font color="#000000" face="arial">
				Billing State:
			</font>
		</td>
		<td width="60%" bgcolor="#ffffff">
			<font color="#000000" face="arial">
				<?php echo $row->billing_state ?>
			</font>
		</td>
	</TR><TR>
		<td width="40%" bgcolor="#cccccc">
			<font color="#000000" face="arial">
				Billing Postal Code:
			</font>
		</td>
		<td width="60%" bgcolor="#ffffff">
			<font color="#000000" face="arial">
				<?php echo $row->billing_postal_code ?>
			</font>
		</td>
	</TR><TR>
		<td width="40%" bgcolor="#cccccc">
			<font color="#000000" face="arial">
				Billing Country:
			</font>
		</td>
		<td width="60%" bgcolor="#ffffff">
			<font color="#000000" face="arial">
				<?php echo $row->billing_country ?>
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
				<?php echo $row->start_date ?>
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
				<?php echo $row->paid_until_date ?>
			</font>
		</td>
	</TR>
</Table>
<?php
		}
?>
<br>
<center>
<a href="member_modify.php"><font color="red" face="arial"><b>edit</b></font></a>
</center>
<br>
<?php
// Adding footer if we got it.
if ($include_template == "1") {
	include "$template_directory/footer.php";	
}
?>	