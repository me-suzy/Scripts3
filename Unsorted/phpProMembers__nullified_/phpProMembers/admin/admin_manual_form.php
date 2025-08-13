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
<FORM ACTION="admin_manual_form.php" METHOD="POST">
<TABLE border="1" bordercolor="#000000" width="400" cellspacing="3" cellpadding="3">
	<TR>
		<td width="40%" bgcolor="#cccccc">
			<font color="#000000" face="arial">
				First Name:
			</font>
		</td>
		<td width="60%" bgcolor="#ffffff">
			<font color="#000000" face="arial">
				<input type="text" name="first_name" size="43">
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
				<input type="text" name="last_name" size="43">
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
				<input type="text" name="address1" size="43">
				
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
				<input type="text" name="address2" size="43">
				
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
				<input type="text" name="city" size="43">
				
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
				<input type="text" name="state" size="43">
				
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
				<input type="text" name="postal_code" size="43">
				
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
				<input type="text" name="country" size="43">
				
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
				<input type="text" name="telephone_number" size="43">
	
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
				<input type="text" name="e_mail_address" size="43">
				
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
				<input type="text" name="user_name" size="43">
				
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
				<input type="text" name="user_password" size="43">
				
			</font>
		</td>
	</TR>
	<TR>
		<td width="40%" bgcolor="#cccccc">
			<font color="#000000" face="arial">
				Name On Card:
			</font>
		</td>
		<td width="60%" bgcolor="#ffffff">
			<font color="#000000" face="arial">
				<input type="text" name="name_on_card" size="43">
				
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
				<input type="text" name="billing_address1" size="43">
				
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
				<input type="text" name="billing_address2" size="43">
				
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
				<input type="text" name="billing_city" size="43">
				
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
				<input type="text" name="billing_state" size="43">
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
				<input type="text" name="billing_postal_code" size="43">
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
				<input type="text" name="billing_country" size="43">
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
		<td width="100%" bgcolor="#cccccc" colspan="2" align="right">
			<font color="#000000" face="arial">
				<input type="hidden" name="op" value="1">
				<INPUT TYPE="SUBMIT" NAME="process" VALUE="Process">&nbsp;&nbsp;&nbsp;<input type="reset" name="reset">
			</font>
		</td>
	</TR>
</Table>
</form>
<?php
}else{
	if (trim($user_name) == "") {
		include "templates/header.php";	
		echo "<center><b>User Name field can not be blank!</b></center>";
		include "templates/footer.php";	
		exit;
	}

	if (trim($user_password) == "") {
		include "templates/header.php";	
		echo "<center><b>User Password field can not be blank!</b></center>";
		include "templates/footer.php";	
		exit;
	}

	if (trim($first_name) == "") {
		include "templates/header.php";	
		echo "<center><b>First Name field can not be blank!</b></center>";
		include "templates/footer.php";	
		exit;
	}

	if (trim($last_name) == "") {
		include "templates/header.php";	
		echo "<center><b>Last Name field can not be blank!</b></center>";
		include "templates/footer.php";	
		exit;
	}
	
	if (trim($start_date) == "") {
		include "templates/header.php";	
		echo "<center><b>Start Date field can not be blank!</b></center>";
		include "templates/footer.php";	
		exit;
	}
	
	if (trim($paid_until_date) == "") {
		include "templates/header.php";	
		echo "<center><b>Paid until date field can not be blank!</b></center>";
		include "templates/footer.php";	
		exit;
	}
	
	$insert_data_sql = "INSERT INTO members (id, first_name, last_name, address1, address2, city, state, postal_code, country, telephone_number, e_mail_address, user_name, user_password, name_on_card, billing_address1, billing_address2, billing_city, billing_state, billing_postal_code, billing_country)
								VALUES (\"\", \"$first_name\", \"$last_name\", \"$address1\", \"$address2\", \"$city\", \"$state\", \"$postal_code\", \"$country\", \"$telephone_number\", \"$e_mail_address\", \"$user_name\", \"$user_password\", \"$name_on_card\", \"$billing_address1\", 
								\"$billing_address2\", \"$billing_city\", \"$billing_state\", \"$billing_postal_code\", \"$billing_country\");";
	mysql_query($insert_data_sql);

	

	header("Location: admin_search_members.php");
	exit;
}
?>
<?php
include "templates/footer.php";	
?>	