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
	$this_user = $GLOBALS["user_name"];
?>

<br>
<center>
<a href="admin_edit.php?id=<?php echo $this_id ?>"><font color="red" face="arial"><b>edit</b></font></a>&nbsp;&nbsp;|&nbsp;&nbsp;
<a href="payment_history.php?id=<?php echo $this_id ?>&user_name=<?php echo $this_user ?>"><font color="red" face="arial"><b>payment history</b></font></a>&nbsp;&nbsp;|&nbsp;&nbsp;
<a href="admin_members_stats.php?id=<?php echo $this_id ?>&user_name=<?php echo $this_user ?>"><font color="red" face="arial"><b>stats</b></font></a>&nbsp;&nbsp;|&nbsp;&nbsp;
<a href="view_memberships.php?id=<?php echo $this_user ?>"><font color="red" face="arial"><b>view memberships</b></font></a>
</center>
<br>

<?php
	$get_total_members = "SELECT * FROM members WHERE id = \"$this_id\"";
	$result = mysql_query($get_total_members);
	while($row = mysql_fetch_object($result)) {
?>
<TABLE border="1" bordercolor="#000000" width="400" cellspacing="3" cellpadding="3">
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
				Password:
			</font>
		</td>
		<td width="60%" bgcolor="#ffffff">
			<font color="#000000" face="arial">
				<?php echo $row->user_password ?>
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
				<?php echo $row->name_on_card ?>
			</font>
		</td>
	</TR><TR>
		<td width="40%" bgcolor="#cccccc">
			<font color="#000000" face="arial">
				card_number:
			</font>
		</td>
		<td width="60%" bgcolor="#ffffff">
			<font color="#000000" face="arial">
				<?php echo $row->card_number ?>
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
				<?php echo $row->exp_date ?>
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
	
</Table>
<TABLE border="1" bordercolor="#000000" width="400" cellspacing="3" cellpadding="3">
	<TR>
		<td  bgcolor="#cccccc" align="center">
			<font color="#000000" face="arial">
				<b>Membership</b>
			</font>
		</td>
		<td  bgcolor="#cccccc" align="center">
			<font color="#000000" face="arial">
				<b>Active</b>
			</font>
		</td>
	</tr>
<?php
$get_total_members = "SELECT * FROM memberships WHERE user_name = \"$user\"";
$result3 = mysql_query($get_total_members);
while($row3 = mysql_fetch_object($result3)) {	
?>
	<tr>
		<td width="60%" bgcolor="#ffffff">
			<font color="#000000" face="arial">
				 <?php echo $row3->package ?>
			</font>
		</td>
		<td width="60%" bgcolor="#ffffff">
			<font color="#000000" face="arial">
				<?php 
					if ($row3->active == "1") {
						echo "active";
					}else{
						echo "in-active";
					}
				?>
			</font>
		</td>
	</TR>
<?php	
}
?>
</table>
<br><br>

<?php
		}
?>
<?php
include "templates/footer.php";	
?>	