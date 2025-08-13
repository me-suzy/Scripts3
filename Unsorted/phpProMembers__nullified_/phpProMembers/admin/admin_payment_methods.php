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

if ($a < 1) {
	include "templates/header.php";	
?>	

<br>
<center><h3><a href="add_a_payment.php"><font face="arial" size="-1" color="red">add a payment method</font></a></h3></center>
<br>

<TABLE border="0" bordercolor="#000000" width="100%" cellspacing="0" cellpadding="3">
	<TR>
		<TD bgcolor="#cccccc">
			<center><b><font face="arial" size="-1">Payment Type</font></b></center>
		</TD>
		<TD bgcolor="#cccccc">
			<center><b><font face="arial" size="-1">Active</font></b></center>
		</TD>
		<TD bgcolor="#cccccc">
			<center><b><font face="arial" size="-1">Activate</font></b></center>
		</TD>
		<TD bgcolor="#cccccc">
			<center><b><font face="arial" size="-1">De-Activate</font></b></center>
		</TD>
	</TR>		
<?php
	$x = 0;
	$get_awaiting_approval_sql = "SELECT * FROM method_of_payment";
	$result = mysql_query($get_awaiting_approval_sql);
	while($row = mysql_fetch_object($result)) {
		echo "<tr>
			<td bgcolor=\"#ffffff\" valign=\"top\" align=\"center\"><a href=\"payment_method_view.php?id=$row->id\"><font face=\"arial\" size=\"-2\" color=\"red\">$row->payment_type</font></a></td>
			<td bgcolor=\"#ffffff\" valign=\"top\" align=\"center\"><font face=\"arial\" size=\"-2\">";
		
		if ($row->active == "1") {		
			echo "active";
		}else{
			echo "inactive";
		}
								
		echo "</font></td>
			<td bgcolor=\"#ffffff\" align=\"center\" valign=\"top\"><a href=\"admin_payment_methods.php?a=1&id=$row->id\"><font face=\"arial\" size=\"-2\" color=\"red\">activate</font></a></td>
			<td bgcolor=\"#ffffff\" align=\"center\" valign=\"top\"><a href=\"admin_payment_methods.php?a=2&id=$row->id\"><font face=\"arial\" size=\"-2\" color=\"red\">deactivate</font></a></td>
		</tr>";
		echo "<tr><td colspan=\"4\"><hr size=1></td></tr>";
		$x += 1;
	}
	
	if ($x == "0") {
		echo "<TR><TD WIDTH=\"100%\" colspan=\"5\"><BR><CENTER><h3><FONT COLOR=\"Red\" FACE=\"arial\">There are no method of payments registered.</FONT></H3></CENTER></TD></TR>";
	}
	
?>

</TABLE>		

</CENTER>
<?php
}else{
	$this_id = $GLOBALS["id"];
	$action = $GLOBALS["a"];

	$action_sql = "SELECT * FROM method_of_payment WHERE id = \"$this_id\"";
	$result = mysql_query($action_sql);
	while($row = mysql_fetch_object($result)) {
		if ($action == "1") {
			$update_sql = "UPDATE method_of_payment SET active = \"1\" WHERE id = \"$this_id\"";
		}
		if ($action == "2") {
			$update_sql = "UPDATE method_of_payment SET active = \"0\" WHERE id = \"$this_id\"";
		}
		if ($action == "3") {
			$update_sql = "DELETE FROM method_of_payment WHERE id = \"$this_id\"";
		}
	}

	mysql_query($update_sql);

	header("Location: admin_payment_methods.php");
	exit;
}
?>
<?php
	include "templates/footer.php";	
?>	