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

<br>
<center>
<a href="account_types_edit.php?id=<?php echo $this_id ?>"><font color="red" face="arial"><b>edit</b></font></a>
</center>
<br>

<?php
	$get_total_members = "SELECT * FROM account_types WHERE id = \"$this_id\"";
	$result = mysql_query($get_total_members);
	while($row = mysql_fetch_object($result)) {
?>
<TABLE border="1" bordercolor="#000000" width="400" cellspacing="3" cellpadding="3">
	<TR>
		<td width="40%" bgcolor="#cccccc">
			<font color="#000000" face="arial">
				Account Name:
			</font>
		</td>
		<td width="60%" bgcolor="#ffffff">
			<font color="#000000" face="arial">
				<?php echo $row->account_name ?>
			</font>
		</td>
	</TR>
	<TR>
		<td width="40%" bgcolor="#cccccc">
			<font color="#000000" face="arial">
				Account Setup Fee:
			</font>
		</td>
		<td width="60%" bgcolor="#ffffff">
			<font color="#000000" face="arial">
				<?php echo $row->account_setup_fee ?>
			</font>
		</td>
	</TR>
	<TR>
		<td width="40%" bgcolor="#cccccc">
			<font color="#000000" face="arial">
				Account Fee:
			</font>
		</td>
		<td width="60%" bgcolor="#ffffff">
			<font color="#000000" face="arial">
				<?php echo $row->account_fee ?>
			</font>
		</td>
	</TR>
	<TR>
		<td width="40%" bgcolor="#cccccc">
			<font color="#000000" face="arial">
				Length In Days:
			</font>
		</td>
		<td width="60%" bgcolor="#ffffff">
			<font color="#000000" face="arial">
				<?php echo $row->length ?>
			</font>
		</td>
	</TR>
	<TR>
		<td width="40%" bgcolor="#cccccc">
			<font color="#000000" face="arial">
				Active:
			</font>
		</td>
		<td width="60%" bgcolor="#ffffff">
			<font color="#000000" face="arial">
				<?php 
					if ($row->active == "1") {
						echo "active";
					}else{
						echo "inactive";
					}
				?>
			</font>
		</td>
	</TR>
	<TR>
		<td width="40%" bgcolor="#cccccc">
			<font color="#000000" face="arial">
				Waiting Period:
			</font>
		</td>
		<td width="60%" bgcolor="#ffffff">
			<font color="#000000" face="arial">
				<?php 
					if ($row->wait_period == "1") {
						echo "no";
					}else{
						echo "yes";
					}
				?>
			</font>
		</td>
	</TR>
	<TR>
		<td width="40%" bgcolor="#cccccc">
			<font color="#000000" face="arial">
				ClickBank URL:
			</font>
		</td>
		<td width="60%" bgcolor="#ffffff">
			<font color="#000000" face="arial">
				<?php 
					echo $row->clickbank;
				?>
			</font>
		</td>
	</TR>
	<TR>
		<td width="40%" bgcolor="#cccccc">
			<font color="#000000" face="arial">
				RegNow URL:
			</font>
		</td>
		<td width="60%" bgcolor="#ffffff">
			<font color="#000000" face="arial">
				<?php 
					echo $row->regnow;
				?>
			</font>
		</td>
	</TR>
	<TR>
		<td width="40%" bgcolor="#cccccc">
			<font color="#000000" face="arial">
				Renewable:
			</font>
		</td>
		<td width="60%" bgcolor="#ffffff">
			<font color="#000000" face="arial">
				<?php 
					if ($row->renewal == "1") {
						echo "yes";
					}else{
						echo "no";
					}
				?>
			</font>
		</td>
	</TR>
</Table>
<br>
<center>
<a href="account_types_edit.php?id=<?php echo $this_id ?>"><font color="red" face="arial"><b>edit</b></font></a>
</center>
<br>
<?php
		}
?>
<?php
include "templates/footer.php";	
?>	