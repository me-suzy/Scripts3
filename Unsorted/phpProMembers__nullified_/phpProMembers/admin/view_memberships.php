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
$username = $GLOBALS["id"];
?>
<br>
<center>
<a href="add_a_membership.php?id=<?php echo $username ?>"><font color="red" face="arial"><b>add membership to user</b></font></a>
</center>
<br>
<?php
echo "<font face=\"arial\"><h3>Memberships for $username</h3></font>";
?>	
<TABLE border="0" bordercolor="#000000" width="100%" cellspacing="0" cellpadding="3">
	<TR>
		<TD bgcolor="#cccccc">
			<center><b><font face="arial" size="-1">Package</font></b></center>
		</TD>
		<TD bgcolor="#cccccc">
			<center><b><font face="arial" size="-1">Payment Type</font></b></center>
		</TD>
		<TD bgcolor="#cccccc">
			<center><b><font face="arial" size="-1">Start Date</font></b></center>
		</TD>
		<TD bgcolor="#cccccc">
			<center><b><font face="arial" size="-1">Paid Until Date</font></b></center>
		</TD>
		<TD bgcolor="#cccccc">
			<center><b><font face="arial" size="-1">Active</font></b></center>
		</TD>
		<TD bgcolor="#cccccc">
			<center><b><font face="arial" size="-1">Update</font></b></center>
		</TD>	
		<TD bgcolor="#cccccc">
			<center><b><font face="arial" size="-1">Edit</font></b></center>
		</td>
		<td bgcolor="#cccccc">
			
		</td>	
	</TR>		
<?php
	$x = 0;
	$get_awaiting_approval_sql = "SELECT * FROM memberships WHERE user_name = \"$username\" ORDER BY id";
	$result = mysql_query($get_awaiting_approval_sql);
	while($row = mysql_fetch_object($result)) {
		echo "<tr>
			<td bgcolor=\"#ffffff\" align=\"center\"><font face=\"arial\" size=\"-2\">$row->package</font></td>
			<td bgcolor=\"#ffffff\" align=\"center\"><font face=\"arial\" size=\"-2\">$row->payment_type</font></td>
			<td bgcolor=\"#ffffff\" align=\"center\"><font face=\"arial\" size=\"-2\">$row->start_date</font></td>		
			<td bgcolor=\"#ffffff\" align=\"center\"><font face=\"arial\" size=\"-2\">$row->paid_until_date</font></td>
			<td bgcolor=\"#ffffff\" align=\"center\"><font face=\"arial\" size=\"-2\">";
			
		if ($row->active == "1") {
			echo "active";
		}else{
			echo "in-active";
		}
			
		echo "</font></td>
			<td bgcolor=\"#ffffff\" align=\"center\"><font face=\"arial\" size=\"-2\">
				<a href=\"membership_process.php?process=activate&id=$row->id&member=$row->user_name\"><font color=\"red\">activate</font></a><br>
				<a href=\"membership_process.php?process=deactivate&id=$row->id&member=$row->user_name\"><font color=\"red\">de-activate</font></a><br>			
				<a href=\"membership_process.php?process=delete&id=$row->id&member=$row->user_name\"><font color=\"red\">delete</font></a>			
			</td>
			<td bgcolor=\"#ffffff\" align=\"center\"><font face=\"arial\" size=\"-2\">
				<a href=\"membership_edit.php?id=$row->id&member=$row->user_name\"><font color=\"red\">edit</font></a>
			</td>
			</tr>";
			
		echo "<tr><td colspan=\"7\"><hr size=1></td></tr>";
		$x += 1;
	}
	
	if ($x == "0") {
		echo "<TR><TD WIDTH=\"100%\" colspan=\"7\"><BR><CENTER><h3><FONT COLOR=\"Red\" FACE=\"arial\">There are no memberships available for this member.</FONT></H3></CENTER></TD></TR>";
	}
	
?>

</TABLE>		


<?php
include "templates/footer.php";	
?>	