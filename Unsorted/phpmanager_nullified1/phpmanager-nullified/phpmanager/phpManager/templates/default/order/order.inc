<table border="0" width="600" cellspacing="0" cellpadding="0" class="main_bold" style="border: 1 solid #000000">
	<tr>
		<td width="600" align="middle" class="borderback">
		<table width="580" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td width="580" height="370" class="main_bold">
				<center>
				
				All IP numbers accessing this page are logged for security purposes.
				<BR>
				<BR>
				Your IP address is: <span class="summary_message"><?php echo $REMOTE_ADDR ?></span>
				
				
<form action="<?php echo $PHP_SELF ?>" method="post">
<input type="hidden" name="ip" value="<?php echo $REMOTE_ADDR ?>">
<table border="0" width="500" cellspacing="0" cellpadding="0" class="main_bold">
<tr>
<td width="250" valign="top" align="right" class="main_bold">
Surname:<input name="surname" class="formfield">
<br>
Firstname:<input name="firstname" class="formfield">
<br>
Tel Number:<input name="telephone" class="formfield">
<br>
Email:<input name="email" class="formfield">
<br>
ICQ:
<input name="icq" value="Optional" class="formfield">
<br>
Username:<input name="username" maxlength="8" class="formfield">
<br>
Password:<input type="password" name="password" maxlength="8" class="formfield">
</td>
<td width="250" valign="top" align="right" class="main_bold">
House No:<input name="house" class="formfield">
<br>
Street:
<input name="street" class="formfield">
<br>
Town:<input name="town" class="formfield">
<br>
State:<input name="state" class="formfield">
<br>
Country:<input name="country" class="formfield">
<br>
Postcode:
<input name="postcode" class="formfield">
<br>
Referral ID:
<input name="referrer" value="Optional" class="formfield">
</td>
</tr>
</table>

<BR>
<BR>
Please enter your domain: www.
<input name="domainname" class="formfield">
.
<select name="domain" class="formfield">
<option value=".com" selected>com</option>
<option value=".org">org</option>
<option value=".net">net</option>
<option value=".info">info</option>
<option value=".biz">biz</option>
<option value=".tv">tv</option>
<option value=".co.uk">co.uk</option>
<option value=".org.uk">org.uk</option>
<option value=".ltd.uk">ltd.uk</option>
<option value=".plc.uk">plc.uk</option>
</select>
<BR>
<BR>
Hosting package:
<select name="package" class="formfield">
<?php echo include("$rootdirectory/admin/packages.inc"); ?>
</select>
Billing:
<select name="schedule" class="formfield">
<option value="Monthly" selected>Monthly</option>
<option value="Quarterly">Quarterly</option>
<option value="Bi-Annually">Bi-Annually</option>
<option value="Yearly">Yearly</option>
</select>
Payment:
<select name="payment" class="formfield">
<option value="Monies" selected>Monies</option>
<option value="Cheque">Cheque</option>
<option value="Creditcard">Credit Card</option>
<option value="Paypal">PayPal</option>
<option value="Nochex">NOCHEX</option>
</select>
<BR>
<BR>
I agree to the YOUR COMPANY <a href="http://www.yourdomain.com/legal.html" target="_blank" class="link">terms &
conditions</a>: <input Type="checkbox" name="terms" class="formfield">
<BR>
<BR>
<INPUT TYPE="submit" name="submit" value="Next" class="formfield">
</form>
</center>

				</td>
			</tr>
		</table>
		</td>
	</tr>
</table>