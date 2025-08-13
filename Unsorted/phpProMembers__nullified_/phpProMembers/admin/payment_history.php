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
<br>
<h3><font face="arial">&nbsp;&nbsp;Payment History For <font color="red"><?php echo $this_user ?></font></font></h3>
<TABLE border="0" bordercolor="#000000" width="400" cellspacing="0" cellpadding="3">
	<TR>
		<TD bgcolor="#cccccc">
			<center><b><font face="arial" size="-1">Payment Date</font></b></center>
		</TD>
		<TD bgcolor="#cccccc">
			<center><b><font face="arial" size="-1">Amount</font></b></center>
		</TD>
		<TD bgcolor="#cccccc">
			<center><b><font face="arial" size="-1">Type</font></b></center>
		</TD>
	</TR>		
<?php
	$x = 0;
	$now = date(Ymd000000);
	
		$get_awaiting_approval_sql = "SELECT * FROM payment_history WHERE cust_id = \"$this_id\" ORDER BY id";
		$result = mysql_query($get_awaiting_approval_sql);
		while($row = mysql_fetch_object($result)) {
			echo "<tr>
				<td bgcolor=\"#ffffff\"><font face=\"arial\" size=\"-2\">$row->payment_date</font></td>
				<td bgcolor=\"#ffffff\"><font face=\"arial\" size=\"-2\">$row->amount</font></td>
				<td bgcolor=\"#ffffff\"><font face=\"arial\" size=\"-2\">$row->type</font></td>
				</tr>";
			$x += 1;
		}

	
	if ($x == "0") {
		echo "<TR><TD WIDTH=\"100%\" colspan=\"7\"><BR><CENTER><h3><FONT COLOR=\"Red\" FACE=\"arial\">There is no payment history for this member.</FONT></H3></CENTER></TD></TR>";
	}
	
?>

</TABLE>		


<?php
include "templates/footer.php";	
?>	