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
	include "templates/header.php";	
	$this_id = $GLOBALS["id"];
?>

<center>
<a href="admin_search_awaiting.php?approve=1&id=<?php echo $this_id ?>"><font color="red" face="arial"><b>approve</b></font></a>
&nbsp;&nbsp;&nbsp;
<a href="admin_search_awaiting.php?approve=0&id=<?php echo $this_id ?>"><font color="red" face="arial"><b>deny</b></font></a>
</center>
<br>

<?php
	$get_total_members = "SELECT * FROM members_waiting_approval  WHERE id = \"$this_id\"";
	$result = mysql_query($get_total_members);
	while($row = mysql_fetch_object($result)) {
?>
<FORM ACTION="process_waiting_form.php" METHOD="POST">
<TABLE border="1" bordercolor="#000000" width="400" cellspacing="3" cellpadding="3">
	<TR>
		<td width="40%" bgcolor="#cccccc">
			<font color="#000000" face="arial">
				First Name:
			</font>
		</td>
		<td width="60%" bgcolor="#ffffff">
			<font color="#000000" face="arial">
				<input type="text" name="first_name" value="<?php echo $row->first_name ?>" size="43">
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
				<input type="text" name="last_name" value="<?php echo $row->last_name ?>" size="43">
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
				<input type="text" name="address1" value="<?php echo $row->address1 ?>" size="43">
				
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
				<input type="text" name="address2" value="<?php echo $row->address2 ?>" size="43">
				
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
				<input type="text" name="city" value="<?php echo $row->city ?>" size="43">
				
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
				<input type="text" name="state" value="<?php echo $row->state ?>" size="43">
				
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
				<input type="text" name="postal_code" value="<?php echo $row->postal_code ?>" size="43">
				
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
				<input type="text" name="country" value="<?php echo $row->country ?>" size="43">
				
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
				<input type="text" name="telephone_number" value="<?php echo $row->telephone_number ?>" size="43">
	
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
				<input type="text" name="e_mail_address" value="<?php echo $row->e_mail_address ?>" size="43">
				
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
				<input type="text" name="user_name" value="<?php echo $row->user_name ?>" size="43">
				
			</font>
		</td>
	</TR>
<TR>
		<td width="40%" bgcolor="#cccccc">
			<font color="#000000" face="arial">
				Password:
			</font>
		</td>
		<td width="60%" bgcolor="#ffffff">
			<font color="#000000" face="arial">
				<input type="text" name="user_password" value="<?php echo $row->user_password ?>" size="43">
				
			</font>
		</td>
	</TR><TR>
		<td width="40%" bgcolor="#cccccc">
			<font color="#000000" face="arial">
				Payment Type:
			</font>
		</td>
		<td width="60%" bgcolor="#ffffff">
			<font color="#000000" face="arial">
				<input type="text" name="payment_type" value="<?php echo $row->payment_type ?>" size="43">
				
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
				<input type="text" name="account_type_name" value="<?php echo $row->account_type_name ?>" size="43">
				
			</font>
		</td>
	</TR><TR>
		<td width="40%" bgcolor="#cccccc">
			<font color="#000000" face="arial">
				Name On Card:
			</font>
		</td>
		<td width="60%" bgcolor="#ffffff">
			<font color="#000000" face="arial">
				<input type="text" name="name_on_card" value="<?php echo $row->name_on_card ?>" size="43">
				
			</font>
		</td>
	</TR><TR>
		<td width="40%" bgcolor="#cccccc">
			<font color="#000000" face="arial">
				Card Number:
			</font>
		</td>
		<td width="60%" bgcolor="#ffffff">
			<font color="#000000" face="arial">
				<input type="text" name="card_number" value="<?php echo $row->card_number ?>" size="43">
				
			</font>
		</td>
	</TR><TR>
		<td width="40%" bgcolor="#cccccc">
			<font color="#000000" face="arial">
				Expiration Date:
			</font>
		</td>
		<td width="60%" bgcolor="#ffffff">
			<font color="#000000" face="arial">
				<input type="text" name="exp_date" value="<?php echo $row->exp_date ?>" size="43">
				
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
				<input type="text" name="billing_address1" value="<?php echo $row->billing_address1 ?>" size="43">
				
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
				<input type="text" name="billing_address2" value="<?php echo $row->billing_address2 ?>" size="43">
				
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
				<input type="text" name="billing_city" value="<?php echo $row->billing_city ?>" size="43">
				
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
				<input type="text" name="billing_state" value="<?php echo $row->billing_state ?>" size="43">
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
				<input type="text" name="billing_postal_code" value="<?php echo $row->billing_postal_code ?>" size="43">
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
				<input type="text" name="billing_country" value="<?php echo $row->billing_country ?>" size="43">
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
				<input type="text" name="start_date" value="<?php echo $row->start_date ?>" size="43">
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
				<input type="text" name="paid_until_date" value="<?php echo $row->paid_until_date ?>" size="43">
			</font>
		</td>
	</TR>
	<TR>
		<td width="100%" bgcolor="#cccccc" colspan="2" align="right">
			<font color="#000000" face="arial">
				<input type="hidden" name="id" value="<?php echo $row->id ?>" size="43">
				<INPUT TYPE="SUBMIT" NAME="process" VALUE="Process">
			</font>
		</td>
	</TR>
</Table>
</form>
<br>
<center>
<a href="admin_search_awaiting.php?approve=1&id=<?php echo $this_id ?>"><font color="red" face="arial"><b>approve</b></font></a>
&nbsp;&nbsp;&nbsp;
<a href="admin_search_awaiting.php?approve=0&id=<?php echo $this_id ?>"><font color="red" face="arial"><b>deny</b></font></a>
</center>
<br>
<?php
		}
?>
<?php
include "templates/footer.php";	
?>	