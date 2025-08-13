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

global("$valid_user");

if ($include_template == "1") {
	include "$template_directory/header.php";	
}
	
$get_user_info_sql = "SELECT * FROM members WHERE user_name = \"$valid_user\"";
$result = mysql_query($get_user_info_sql);
if($row = mysql_fetch_object($result)) {
?>	
<FORM ACTION="member_modify_process.php" METHOD="POST">
	<CENTER>
		<TABLE border="1" bordercolor="#000000" width="500" cellspacing="3" cellpadding="3">
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
				<input type="text" name="user_password" value="<?php echo $row->user_password ?>" size="43">
				
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
	</CENTER>
</FORM>
<?php
}else{
	global $company_name, $company_email;
	echo "<FONT SIZE=\"2\" FACE=\"verdana, arial, helvetica\"><b><center>There was an error please contact $company_name at <a href=mailto:$company_email><font color=\"red\">$company_email</font></a> so we may be able to correct this.</center></b></font>";
}
?>	
<?php
// Adding footer if we got it.
if ($include_template == "1") {
	include "$template_directory/footer.php";	
}
?>	