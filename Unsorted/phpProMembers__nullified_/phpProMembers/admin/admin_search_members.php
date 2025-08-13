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
?>	
<TABLE border="0" bordercolor="#000000" width="100%" cellspacing="0" cellpadding="3">
	<TR>
		<TD bgcolor="#cccccc">
			<center><b><font face="arial" size="-1">Name</font></b></center>
		</TD>
		<TD bgcolor="#cccccc">
			<center><b><font face="arial" size="-1">User Name</font></b></center>
		</TD>
		<TD bgcolor="#cccccc">
			<center><b><font face="arial" size="-1">Memberships</font></b></center>
		</TD>
	</TR>		
<?php
	$x = 0;
	$get_awaiting_approval_sql = "SELECT * FROM members ORDER BY id";
	$result = mysql_query($get_awaiting_approval_sql);
	while($row = mysql_fetch_object($result)) {
		echo "<tr>
			<td bgcolor=\"#ffffff\" align=\"center\"><a href=\"admin_view_details.php?id=$row->id&user_name=$row->user_name\"><font face=\"arial\" size=\"-2\" color=\"red\">$row->first_name $row->last_name</font></a></td>
			<td bgcolor=\"#ffffff\" align=\"center\"><font face=\"arial\" size=\"-2\">$row->user_name</font></td>
			<td bgcolor=\"#ffffff\" align=\"center\"><a href=\"view_memberships.php?id=$row->user_name\"><font face=\"arial\" size=\"-2\" color=\"red\">view memberships</font></a></td>					
		</tr>";
		echo "<tr><td colspan=\"5\"><hr size=1></td></tr>";
		$x += 1;
	}
	
	if ($x == "0") {
		echo "<TR><TD WIDTH=\"100%\" colspan=\"3\"><BR><CENTER><h3><FONT COLOR=\"Red\" FACE=\"arial\">There are no members available.</FONT></H3></CENTER></TD></TR>";
	}
	
?>

</TABLE>		


<?php
include "templates/footer.php";	
?>	