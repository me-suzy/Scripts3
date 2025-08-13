<?php

if ($myrow["paid"]=="No" && $myrow["id"]==$id){

$duedate = strftime("$dateformat",$duedate);

?>

<table width="570" border="0" cellspacing="0" cellpadding="2" style="border: 1 solid #000000">
<tr>
<td width="570" align="center" class="summary_message">
- <?php echo $invoice_notification ?> -
</td>
</tr>
<tr>
<td width="570" height="20" class="main">
<?php echo $invoice_message ?>
<br>
<br>
</td>
</tr>
<tr>
<td width="570" height="20" align="center">
<table width="250" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="250" class="main">
<b><?php echo $domainmsg ?></b> <a href="http://www.<?php echo $domainname ?><?php echo $domain ?>" target="_blank"
class="link">www.<?php echo $domainname ?><?php echo $domain ?></a>
<br>
<b><?php echo $packagemsg ?></b> <font color="#FF0000"><?php echo $package ?></font>
<br>
<b><?php echo $invoice_duedate ?> </b><font color="#FF0000"><?php echo $duedate ?></font>
<br>
<b><?php echo $invoice_method ?> </b><font color="#FF0000"><?php echo $payment ?></font>
<br>
<b><?php echo $invoice_schedule ?> </b><font color="#FF0000"><?php echo $schedule ?></font>
<br>
<b><?php echo $invoice_amount ?> $</b><font color="#FF0000"><?php echo $amount ?></font>
<br>
<br>
</td>
</tr>
</table>
</td>
</tr>
<tr>
<td width="570" height="20" class="main">
<?php echo $invoice_query ?>
</td>
</tr>
</table>

<?php

} else {

?>

<table width="570" border="0" cellspacing="0" cellpadding="2" style="border: 1 solid #000000">
<tr>
<td width="570" align="center" class="summary_message">
- <?php echo $invoice_receipt ?> -
</td>
</tr>
<tr>
<td width="570" height="20" class="main">
<?php echo $invoice_receiptmsg ?>
<br>
<br>
</td>
</tr>
<tr>
<td width="570" height="20" align="center">
<table width="250" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="250" class="main">
<b><?php echo $domainmsg ?></b> <a href="http://www.<?php echo $domainname ?><?php echo $domain ?>" target="_blank"
class="link">www.<?php echo $domainname ?><?php echo $domain ?></a>
<br>
<br>
<b><?php echo $invoice_amountreceived ?> $</b><font color="#FF0000"><?php echo $amount ?></font>
<br>
<br>
</td>
</tr>
</table>
</td>
</tr>
<tr>
<td width="570" height="20" class="main">
<?php echo $invoice_thankyou ?>
<br>
<br>
<?php echo $invoice_regards ?>
<br>
<br>
Admin
<br>
<?php echo $company ?> - Accounts Department
</td>
</tr>
</table>

<?php

}

?>