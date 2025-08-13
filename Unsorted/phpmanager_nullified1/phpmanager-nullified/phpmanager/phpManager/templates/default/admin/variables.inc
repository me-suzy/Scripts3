<table width="570" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="570" height="360" align="center" class="main">
<table width="570" border="0" cellspacing="0" cellpadding="2" class="main" style="border: 1 solid #000000">
	<tr>
		<td width="570" class="main_bold">
<center><button onclick="window.location='setup.php'" class="formfield">Back</button>
<br><br>
<span class="summary_message">- Common Variables -</span></center>
<form method="post" action="<?php echo $PHP_SELF ?>">
Client Manager Login Title:
<input type="text" size="50" name="new_clientmanagerlogin_title" class="formfield" value="<?php echo $clientmanagerlogin_title ?>">
<br><br>
Client Manager Title:
<input type="text" size="50" name="new_clientmanager_title" class="formfield" value="<?php echo $clientmanager_title ?>">
<br><br>
Account Manager Login Title:
<input type="text" size="50" name="new_accountmanagerlogin_title" class="formfield" value="<?php echo $accountmanagerlogin_title ?>">
<br><br>
Account Manager Title:
<input type="text" size="50" name="new_accountmanager_title" class="formfield" value="<?php echo $accountmanager_title ?>">
<br><br>
Company Name:
<input type="text" size="30" name="new_company" class="formfield" value="<?php echo $company ?>">
<br><br>
Payment Processor:
<select name="new_processor" class="formfield">
<option value="<?php echo $processor ?>"><?php echo $processor ?></option>
<option value="None">None</option>
<option value="2Checkout">2Checkout</option>
<option value="Revecom">Revecom</option>
<option value="Worldpay">Worldpay</option>
<option value="Other">Other</option>
</select>
<br><br>
Control Panel:
<select name="new_cp" class="formfield">
<option value="<?php echo $cp ?>"><?php echo $cp ?></option>
<option value="None">None</option>
<option value="WHM">WHM</option>
</select>
<br><br>
Date Format:
<select name="new_dateformat" class="formfield">
<option value="<?php echo $dateformat ?>"><?php echo $dateformat ?></option>
<option value="UK">dd/mm/yy</option>
<option value="US">mm/dd/yy</option>
</select>
<br><br>
Currency Type:
<select name="new_currencytype" class="formfield">
<option value="<?php echo $currencytype ?>"><?php echo $currencytype ?></option>
<option value="£">£</option>
<option value="$">$</option>
<option value=""></option>
</select>
<br><br>
Default Language:
<select name="new_language" class="formfield">
<option value="<?php echo $default_language ?>"><?php echo $default_language ?></option>
<option value="english.inc">English</option>
<option value="french.inc">French</option>
<option value="german.inc">German</option>
<option value="italian.inc">Italian</option>
<option value="spanish.inc">Spanish</option>
</select>
<br><br>
Default Template:
<select name="new_template" class="formfield">
<option value="<?php echo $default_template ?>"><?php echo $default_template ?></option>
<option value="default">Default</option>
<option value="aqua">Aqua</option>
</select>
<hr>
<center><span class="summary_message">- Emails -</span></center>
<br>
Admin Email:
<input type="text" size="50" name="new_admin_email" class="formfield" value="<?php echo $admin_email ?>">
<br><br>
Accounts Email:
<input type="text" size="50" name="new_accounts_email" class="formfield" value="<?php echo $accounts_email ?>">
<br><br>
Support Email:
<input type="text" size="50" name="new_supports_email" class="formfield" value="<?php echo $supports_email ?>">
<br><br>
2Checkout Email:
<input type="text" size="50" name="new_checkout_email" class="formfield" value="<?php echo $checkout_email ?>">
<br><br>
Revecom Email:
<input type="text" size="50" name="new_revecom_email" class="formfield" value="<?php echo $revecom_email ?>">
<br><br>
PayPal Email:
<input type="text" size="50" name="new_paypal_email" class="formfield" value="<?php echo $paypal_email ?>">
<br><br>
WorldPay Email:
<input type="text" size="50" name="new_worldpay_email" class="formfield" value="<?php echo $worldpay_email ?>">
<hr>
<center><span class="summary_message">- WorldPay Variables -</span></center>
<br>
Install ID:
<input type="text" size="10" name="new_instId" class="formfield" value="<?php echo $instId ?>">
<br><br>
Currency:
<input type="text" size="10" name="new_currency" class="formfield" value="<?php echo $currency ?>">
<br><br>
Callback Password:
<input type="text" size="10" name="new_worldpayPW" class="formfield" value="<?php echo $worldpayPW ?>">
<br><br>
<center><input type="submit" name="editvariables" value="Change" class="formfield"></center>
</form>
</td>
</tr>
</table>

</td>
</tr>
</table>