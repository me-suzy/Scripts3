<table width="570" border="0" cellspacing="0" cellpadding="2" class="main_bold" style="border: 1 solid #000000">
<tr>
<td width="570" colspan="3" align="center">
<span class="summary_message">- Clients Still To Pay -</span>
</td>
</tr>
<tr>
<td width="50">
ID:
</td>
<td width="350">
Name: (Click To Confirm Payment Received)
</td>
<td width="170">
Payment Due Date:
</td>
</tr>
<?php include ("$rootdirectory/admin/clientmanager/unpaid.inc"); ?>
</table>
<br>
<table width="570" border="0" cellspacing="0" cellpadding="2" class="main_bold" style="border: 1 solid #000000">
<tr>
<td width="570" colspan="3" align="center">
<span class="summary_message">- Clients To Invoice -</span>
</td>
</tr>
<tr>
<td width="50">
ID:
</td>
<td width="350">
Name: (Click To Invoice)
</td>
<td width="170">
Invoice Date:
</td>
</tr>
<?php include ("$rootdirectory/admin/clientmanager/invoice.inc"); ?>
</table>
<br>
<table width="570" border="0" cellspacing="0" cellpadding="2" class="main_bold" style="border: 1 solid #000000">
<tr>
<td width="570" colspan="3" align="center">
<span class="summary_message">- Pending Clients -</span>
</td>
</tr>
<tr>
<td width="50">
ID:
</td>
<td width="350">
Name: (Click To Activate)
</td>
<td width="170">
Cancel Order:
</td>
</tr>
<?php include ("$rootdirectory/admin/clientmanager/pending.inc"); ?>
</table>
<br>
<table width="570" border="0" cellspacing="0" cellpadding="2" class="main_bold" style="border: 1 solid #000000">
<tr>
<td width="570" colspan="3" align="center">
<span class="summary_message">- Support Requests -</span>
</td>
</tr>
<tr>
<td width="150">
Ticket Number:
</td>
<td width="250">
Username: (Click To View)
</td>
<td width="170">
Date/Time Submitted:
</td>
</tr>
<?php include ("$rootdirectory/support/support.inc"); ?>
</table>