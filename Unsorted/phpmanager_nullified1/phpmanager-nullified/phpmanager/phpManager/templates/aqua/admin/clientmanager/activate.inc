<form method="post" action="<?php echo $PHP_SELF ?>">
<input type="hidden" name="logonname" value="<?php echo $logonname ?>">
<input type="hidden" name="logonid" value="<?php echo $logonid ?>">
<table width="570" border="0" cellspacing="0" cellpadding="2" style="border: 1 solid #000000">
        <tr>
		<td width="570" class="main_bold">
                Clients Name:
            </td>
        </tr>
        <tr>
		<td width="570" class="main_bold">
		ID:<input type="Text" size="4" name="id" class="formfield" value="<?php echo $id ?>">
		&nbsp;
		Firstname:<input type="Text" size="20" name="firstname" class="formfield" value="<?php echo $firstname ?>">
 		&nbsp;                
		Surname:<input type="Text" size="20" name="surname" class="formfield" value="<?php echo $surname ?>">
            <br>
            <hr>
            </td>
        </tr>
        <tr>
		<td width="570" class="main_bold">
            Clients Address:
            </td>
        </tr>
        <tr>
            <td width="570" class="main_bold">
	      House:<input type="Text" size="20" name="house" class="formfield" value="<?php echo $house ?>">
		<br>
		Street:<input type="Text" size="50" name="street" class="formfield" value="<?php echo $street ?>">
		<br>
		Town:<input type="Text" size="40" name="town" class="formfield" value="<?php echo $town ?>">
		&nbsp;
		State:<input type="Text" size="20" name="state" class="formfield" value="<?php echo $state ?>">
		<br>
		Country:<input type="Text" size="20" name="country" class="formfield" value="<?php echo $country ?>">
		&nbsp;
		Postcode:<input type="Text" size="10" name="postcode" class="formfield" value="<?php echo $postcode ?>">
            <br>
            <hr>
            </td>
        </tr>
        <tr>
		<td width="570" class="main_bold">
            Contact Details:
            </td>
        </tr>
        <tr>
            <td width="570" class="main_bold">
		Telephone Number:<input type="Text" size="20" name="telephone" class="formfield" value="<?php echo $telephone ?>">
		&nbsp;
            ICQ:<input type="Text" size="10" name="icq" class="formfield" value="<?php echo $icq ?>">
            <br>
		Email Address:<input type="Text" size="30" name="email" class="formfield" value="<?php echo $email ?>">
            <br>
            <hr>
            </td>
        </tr>
        <tr>
		<td width="570" class="main_bold">
            Package Details:
            </td>
        </tr>
        <tr>
            <td width="570" class="main_bold">
		Domain Name:<input type="Text" size="20" name="domainname" class="formfield" value="<?php echo $domainname ?>">
		&nbsp;
		Domain:
		<select name="domain" class="formfield">
		<option value="<?php echo $domain ?>" selected><?php echo $domain ?></option>
		<option value=".com">.com</option>
		<option value=".org">.org</option>
		<option value=".net">.net</option>
		<option value=".info">.info</option>
		<option value=".biz">.biz</option>
		<option value=".tv">.tv</option>
		<option value=".co.uk">.co.uk</option>
		<option value=".org.uk">.org.uk</option>
		<option value=".ltd.uk">.ltd.uk</option>
		<option value=".plc.uk">.plc.uk</option>
		</select>
		&nbsp;
		Package:
		<select name="package" class="formfield">
		<option value="<?php echo $package ?>"><?php echo $package ?></option>
    <?php echo include("$rootdirectory/admin/packages.inc"); ?>
		</select>
            <br>
		Referrer:<input type="Text" size="20" name="referrer" class="formfield" value="<?php echo $referrer ?>">
		&nbsp;
		Schedule:
		<select name="schedule" class="formfield">
		<option value="<?php echo $schedule ?>"><?php echo $schedule ?></option>
		<option value="Monthly">Monthly</option>
		<option value="Quarterly">Quarterly</option>
		<option value="Bi-Annually">Bi-Annually</option>
		<option value="Yearly">Yearly</option>
		</select>
		&nbsp;	
		Payment:
		<select name="payment" class="formfield">
		<option value="<?php echo $payment ?>"><?php echo $payment ?></option>
		<option value="Monies">Monies</option>
		<option value="Cheque">Cheque</option>
		<option value="Creditcard">Credit Card</option>
		<option value="Paypal">PayPal</option>
		<option value="Nochex">NOCHEX</option>
		</select>
            <br>
            <hr>
            </td>
        </tr>
        <tr>
		<td width="570" class="main_bold">
            Account Details:
            </td>
        </tr>
        <tr>
            <td width="570" class="main_bold">
		Username:<input type="Text" size="10" maxlength="8" name="username" class="formfield" value="<?php echo $username ?>">
		&nbsp;
		Password:<input type="Text" size="10" name="password" class="formfield" value="<?php echo $password ?>">
		&nbsp;
		IP Address:<input type="Text" size="20" name="ipaddress" class="formfield" value="<?php echo $ipaddress ?>">
		<br>	
		Amount Due: <?php echo $currencytype ?><input type="Text" size="10" name="amount" class="formfield" value="<?php echo $price ?>">
            <br>
            <hr>
            </td>
        </tr>
        <tr>
		<td width="570" class="main_bold">
            Notes:
            </td>
        </tr>
        <tr>
            <td width="570" class="main_bold">
		<textarea name="notes" class="formfield" rows="4" cols="90">None</textarea>
		<br>
		<br>
		<center><input type="Submit" name="submit" value="Activate Client" class="formfield"></center>
		</td>
	</tr>
</table>
</form>