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

if ($op != "1") {
?>	

<FORM ACTION="admin_search.php" METHOD="POST">
	<CENTER>
		<TABLE width="400">
		 <TR>
            <TD>
				<INPUT TYPE="TEXT" NAME="info"> 
			</td>
			<td>
				<select name="what">
					<option value="firstname">First Name</option>
					<option value="lastname">Last Name</option>
					<option value="username">User Name</option>
					<option value="emailaddress">E-Mail Address</option>
				</select>
			</td>
        
		 	<TD colspan="2" align="right">
				<INPUT TYPE="Hidden" name="op" value="1">
				<INPUT TYPE="SUBMIT" NAME="process" VALUE="Process"><INPUT TYPE="RESET" NAME="">
			</TD>
		 </TR>
		</TABLE>
</FORM>

<?php
}else{
?>
<TABLE border="0" bordercolor="#000000" width="400" cellspacing="0" cellpadding="3">
	<tr>
		<td colspan = 2>
			<font face="arial"><h3>Search Results</h3></font>
		</td>
	</tr>
	<TR>
		<TD bgcolor="#cccccc">
			<center><b><font face="arial" size="-1">Name</font></b></center>
		</TD>
		<TD bgcolor="#cccccc">
			<center><b><font face="arial" size="-1">User Name</font></b></center>
		</TD>
	</TR>		
<?php
	$x = 0;
	
	if ($what == "firstname") {
		$get_awaiting_approval_sql = "SELECT * FROM members WHERE first_name = \"$info\" ORDER BY id";
	}else if ($what == "lastname") {
		$get_awaiting_approval_sql = "SELECT * FROM members WHERE last_name = \"$info\" ORDER BY id";
	}else if ($what == "username") {
		$get_awaiting_approval_sql = "SELECT * FROM members WHERE user_name = \"$info\" ORDER BY id";
	}else if ($what == "emailaddress") {
		$get_awaiting_approval_sql = "SELECT * FROM members WHERE e_mail_address = \"$info\" ORDER BY id";
	}
	
	if ($get_awaiting_approval_sql != "") {
	$result = mysql_query($get_awaiting_approval_sql);
	while($row = mysql_fetch_object($result)) {
		echo "<tr>
			<td bgcolor=\"#ffffff\" align=\"center\"><a href=\"admin_view_details.php?id=$row->id&user_name=$row->user_name\"><font face=\"arial\" size=\"-2\" color=\"red\">$row->first_name $row->last_name</font></a></td>
			<td bgcolor=\"#ffffff\" align=\"center\"><font face=\"arial\" size=\"-2\">$row->user_name</font></td>
		</tr>";
		echo "<tr>
				<td colspan=2>
					<hr size=1>
				</td>
			</tr>";
		$x += 1;
	}
	}
	
	if ($x == "0") {
		echo "<TR><TD WIDTH=\"100%\" colspan=\"2\"><BR><CENTER><h3><FONT COLOR=\"Red\" FACE=\"arial\">There where no members found.</FONT></H3></CENTER></TD></TR>";
	}
	
?>

</TABLE>		
<?php	
}
?>
<?php
include "templates/footer.php";	
?>	