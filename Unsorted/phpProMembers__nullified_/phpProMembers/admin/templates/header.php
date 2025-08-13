<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
	<title>phpProMembers</title>
	
<SCRIPT>
function DoCal(elTarget) {
  if (window.showModalDialog) {
    var sRtn;
    sRtn = showModalDialog("calendar.htm","","center=yes;dialogWidth=350pt;dialogHeight=200pt");
    if (sRtn!="")
      elTarget.value = sRtn;
  } else
    alert("Internet Explorer 4.0 or later is required.")
 }
</SCRIPT>

<style>
A:link {
color: #0000FF;
font: 12px Tahoma, Verdana, sans-serif;
text-decoration: none;
}
A:active {
color: #0000FF;
font: 12px Tahoma, Verdana, sans-serif;
text-decoration: none;
}
A:visited {
color: #0000FF;
font: 12px Tahoma, Verdana, sans-serif;
text-decoration: none;
}
A:hover {
color: #ffffff;
background: #000036;
font: 12px Tahoma, Verdana, sans-serif;
text-decoration: none;
}
</style>
</head>

<body bgcolor="#cccccc">
<center>
<table width="775" cellpadding="0" cellspacing="0" border=1 bgcolor="#BBCFF7" bordercolor="#000000">
<tr>
<td width="775" valign="top">
<table width="775" cellpadding="0" cellspacing="0">
	<tr>
		<td bgcolor="#ffffff">
			<!--CyKuH--><img src="title.gif" border="0">
			<font color="#000000" face="arial"><b>&nbsp;&nbsp;phpProMembers Admin</b></font>
		</td>
	</tr>
	<tr>
		<td bgcolor="#2663E2" width="100%" align="right">
			<a href="<?php echo $admin_home_page; ?>"><font color="#ffffff">admin home</font></a>&nbsp;&nbsp;
		</td>
	</tr>
</table>
</td>
</tr>
</table>

<table width="775" cellpadding="0" cellspacing="0" border=1 bgcolor="#BBCFF7" bordercolor="#000000">
<tr>
<td width="200" valign="top">

<table width="200" cellpadding="2" cellspacing="0">
	<tr>
		<td bgcolor="#BBCFF7">
				<font color="#000000" face="arial" size="2">
<?php
	$x = 0;
	$get_total_members = "SELECT * FROM members";
	$result = mysql_query($get_total_members);
	while($row = mysql_fetch_object($result)) {
		$x += 1;
	}
	echo "<b>Total Active Members:</b> <FONT COLOR=\"RED\" FACE=\"arial\">$x</FONT>";
?>						
						</font>
		</td>
	</tr>
	<tr>
		<td bgcolor="#BBCFF7">
				<font color="#000000" face="arial" size="2">
<?php
	$x = 0;
	$get_total_members = "SELECT * FROM members_waiting_approval";
	$result = mysql_query($get_total_members);
	while($row = mysql_fetch_object($result)) {
		$x += 1;
	}
	echo "<b>Total Awaiting Approval:</b> <a href=\"admin_search_awaiting.php\"><FONT COLOR=\"RED\" FACE=\"arial\" size=\"2\">$x</FONT></a>";
?>						
						</font>
		</td>
	</tr>
	<tr>
		<td bgcolor="#2663E2">
				<font color="#ffffff" face="arial" size="3">
					<center><b>Members Administration</b></center>
				</font>
		</td>
	</tr>
	<tr>
		<td bgcolor="#BBCFF7">
				<font color="#ffffff" face="arial" size="2">
					<b><a href="admin_search_members.php">Members</a></b>
				</font>
		</td>
	</tr>
	<tr>
		<td bgcolor="#BBCFF7">
				<font color="#ffffff" face="arial" size="2">
					<b><a href="admin_search_awaiting.php">Waiting Approval</a></b>
				</font>
		</td>
	</tr>
	<tr>
		<td bgcolor="#BBCFF7">
				<font color="#ffffff" face="arial" size="2">
					<b><a href="admin_search.php">Search For Members</a></b>
				</font>
		</td>
	</tr>
	<tr>
		<td bgcolor="#BBCFF7">
				<font color="#ffffff" face="arial" size="2">
					<b><a href="admin_mail_member.php">E-Mail A Member</a></b>
				</font>
		</td>
	</tr>
	<tr>
		<td bgcolor="#BBCFF7">
				<font color="#ffffff" face="arial" size="2">
					<b><a href="admin_mail_bills.php">Send Bills Out</a></b>
				</font>
		</td>
	</tr>
	<tr>
		<td bgcolor="#2663E2">
				<font color="#ffffff" face="arial" size="3">
					<center><b>phpProMembers Administration</b></center>
				</font>
		</td>
	</tr>
	<tr>
		<td bgcolor="#BBCFF7">
				<font color="#ffffff" face="arial" size="2">
					<b><a href="admin_manual_form.php">Add A Member</a></b>
				</font>
		</td>
	</tr>
	<tr>
		<td bgcolor="#BBCFF7">
				<font color="#ffffff" face="arial" size="2">
					<b><a href="add_a_payment.php">Add A Payment</a></b>
				</font>
		</td>
	</tr>
	<tr>
		<td bgcolor="#BBCFF7">
				<font color="#ffffff" face="arial" size="2">
					<b><a href="admin_payment_methods.php">Payment Methods Admin</a></b>
				</font>
		</td>
	</tr>
	<tr>
		<td bgcolor="#BBCFF7">
				<font color="#ffffff" face="arial" size="2">
					<b><a href="admin_account_types.php">Account Types Admin</a></b>
				</font>
		</td>
	</tr>
	<tr>
		<td bgcolor="#BBCFF7">
				<font color="#ffffff" face="arial" size="2">
					<b><a href="admin_manual_chron.php">Manual Chron Update</a></b>
				</font>
		</td>
	</tr>
	<tr>
		<td bgcolor="#BBCFF7">
				<font color="#ffffff" face="arial" size="2">
					<b><a href="add_download.php">Add A File</a></b>
				</font>
		</td>
	</tr>
</table>
</td>

<td width="575" bgcolor="#ffffff" valign="top" align="center">		
<center>
<br><br>
