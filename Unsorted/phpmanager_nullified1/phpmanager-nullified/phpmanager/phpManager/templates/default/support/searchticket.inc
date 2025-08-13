<table width="570" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="570" align="center">
<form method="post" action="<?php echo $PHP_SELF?>">
<table width="300" border="0" cellspacing="0" cellpadding="2" class="main_bold" style="border: 1 solid #000000">
	<tr>
		<td width="300" align="center">
		Ticket Number: <input type="text" name="ticketno" class="formfield">
		<br>
		<br>
		</td>
	<tr>
		<td width="300" align="center">
		<input type="Submit" name="submit" value="View Ticket" class="formfield">
		</td>
	</tr>
</table>
</form>
</td>
</tr>
<tr>
<td width="570" align="center">

<?php

include ("$rootdirectory/support/assignedsupport.inc");

?>

</td>
</tr>
</table>