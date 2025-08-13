<table width="570" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="570" class="main_bold">
Welcome to your account manager <?php echo $firstname ?>. Please use the links to the left
to manage your account details.
<br>
<br>
</td>
</tr>
<tr>
<td width="570" align="center" class="summary_message">
- Admin Messages -
<br>
<textarea rows="4" cols="90" class="formfield" readonly><?php echo $notes ?></textarea>
<br>
<br>
</td>
</tr>
<tr>
<td width="570" align="center" valign="top">
<?php include ("$rootdirectory/clients/unpaid.inc"); ?>
</tr>
</table>