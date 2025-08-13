<form method="post" action="modify.php">
<table width="570" border="0" cellspacing="0" cellpadding="2" style="border: 1 solid #000000">
        <tr>
		<td width="570" class="main_bold">
		Client ID: <span class="summary_message"><?php echo $id ?></span>
		<br>
		<br>
		Fullname: <span class="summary_message"><?php echo $firstname ?> <?php echo $surname ?></span>
            <br>
		<br>
            Address:
		<br>
		<span class="summary_message"><?php echo $house ?> <?php echo $street ?>
		<br>
		<?php echo $town ?>
		<br>		
		<?php echo $country ?>
		<br>
		<?php echo $postcode ?></span>
		<br>
		<br>
		Telephone Number: <input type="Text" size="20" name="telephone" class="formfield" value="<?php echo $telephone ?>">
		<br>
		<br>
            ICQ: <input type="Text" size="10" name="icq" class="formfield" value="<?php echo $icq ?>">
            <br>
		<br>
		Email Address: <input type="Text" size="30" name="email" class="formfield" value="<?php echo $email ?>">
		&nbsp;
		<input type="Submit" name="submit" value="Edit" class="formfield">
		<br>
		<br>
		Domain Name: <a href="http://www.<?php echo $domainname ?><?php echo $domain ?>"
		target="_blank" class="link">www.<?php echo $domainname ?><?php echo $domain ?></a>
		<br>
		<br>
		Package: <span class="summary_message"><?php echo $package ?></span>
            <br>
		<br>
		Referrer: <span class="summary_message"><?php echo $referrer ?></span>
		<br>
		<br>
		Payment Schedule: <span class="summary_message"><?php echo $schedule ?></span>
		<br>
		<br>	
		Payment Method: <span class="summary_message"><?php echo $payment ?></span>
            <br>
		<br>
		Username: <span class="summary_message"><?php echo $clientname ?></span>
		<br>
		<br>
		IP Address: <span class="summary_message"><?php echo $ipaddress ?></span>
		<br>
		<br>
		Start Date: <span class="summary_message"><?php echo $startdate ?></span>
		<br>
		<br>
		Invoice Date: <span class="summary_message"><?php echo $invoicedate ?></span>
		<br>
		<br>
		Payment Due Date: <span class="summary_message"><?php echo $duedate ?></span>
		<br>
		<br>
		Amount Due: <?php echo $currencytype ?><span class="summary_message"><?php echo $amount ?></span>
		<br>
		<br>
		Status: <span class="summary_message"><?php echo $status ?></span>
            </td>
        </tr>
</table>
</form>