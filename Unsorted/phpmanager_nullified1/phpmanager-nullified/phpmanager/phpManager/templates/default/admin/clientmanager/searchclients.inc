<table width="570" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="570" height="360" align="center">
<form method="post" action="<?php echo $PHP_SELF?>?logonname=<?php echo $logonname ?>&logonid=<?php echo $logonid ?>">
<table width="300" align="center" border="0" cellspacing="0" cellpadding="2" class="main" style="border: 1 solid #000000">
	<tr>
		<td width="300" align="right" class="main_bold">
		Search by Client ID: <input type="text" name="id" class="formfield">
		<br>
		or Username: <input type="text" name="username" class="formfield">
		<br>
		or Domain: <input type="text" name="domainname" class="formfield">
		<br>
		<br>
		</td>
	</tr>
	<tr>
		<td width="300" align="center">
		<input type="Submit" name="submit" value="Search" class="formfield">
		&nbsp;
		<button onclick="window.location='viewclients.php?showall=yes'" class="formfield">Show All</button>
		</td>
	</tr>
</table>
</form>
</td>
</tr>
</table>