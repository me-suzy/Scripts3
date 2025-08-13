<form method="post" action="<?php echo $PHP_SELF?>">
<table width="570" border="0" cellspacing="0" cellpadding="2" style="border: 1 solid #000000">
        <tr>
		<td width="570" class="main">
                <b>Clients Name:<b>
                </td>
        </tr>
        <tr>
		<td width="570" class="main">
		Firstname:<input type="Text" size="20" name="firstname" class="formfield">
    &nbsp;          
		Surname:<input type="Text" size="20" name="surname" class="formfield">
                <br>
                <hr>
                </td>
        </tr>
        <tr>
		<td width="570" class="main">
                <b>Clients Address:<b>
                </td>
        </tr>
        <tr>
                <td width="570" class="main">
		House:<input type="Text" size="20" name="house" class="formfield">
		<br>
		Street:<input type="Text" size="50" name="street" class="formfield">
		<br>
		Town:<input type="Text" size="40" name="town" class="formfield">
		&nbsp;
		State:<input type="Text" size="20" name="state" class="formfield">
		<br>
		Country:<input type="Text" size="20" name="country" class="formfield">
		&nbsp;
		Postcode:<input type="Text" size="10" name="postcode" class="formfield">
                <br>
                <hr>
                </td>
        </tr>
        <tr>
		<td width="570" class="main">
                <b>Contact Details:<b>
                </td>
        </tr>
        <tr>
                <td width="570" class="main">
		Telephone Number:<input type="Text" size="20" name="telephone" class="formfield">
		&nbsp;
                ICQ:<input type="Text" size="10" name="icq" class="formfield">
                <br>
		Email Address:<input type="Text" size="30" name="email" class="formfield">
                <br>
                <hr>
                </td>
        </tr>
        <tr>
		<td width="570" class="main">
                <b>Package Details:<b>
                </td>
        </tr>
        <tr>
                <td width="570" class="main">
		Domain Name:<input type="Text" size="20" name="domainname" class="formfield">
		&nbsp;
		Domain:
		<select name="domain" class="formfield">
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
    <?php echo include("$rootdirectory/admin/packages.inc"); ?>
		</select>
                <br>
		Referrer:<input type="Text" size="20" name="referrer" class="formfield">
		&nbsp;
		Schedule:
		<select name="schedule" class="formfield">
		<option value="Monthly">Monthly</option>
		<option value="Quarterly">Quarterly</option>
		<option value="Bi-Annually">Bi-Annually</option>
		<option value="Yearly">Yearly</option>
		</select>
		&nbsp;
		Payment:
		<select name="payment" class="formfield">
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
		<td width="570" class="main">
                <b>Account Details:<b>
                </td>
        </tr>
        <tr>
                <td width="570" class="main">
		Username:<input type="Text" size="10" maxlength="8" name="username" class="formfield">
		&nbsp;
		Password:<input type="Text" size="10" name="password" class="formfield">
		&nbsp;
		IP Address:<input type="Text" size="20" name="ipaddress" class="formfield" value="<?php echo $ipaddress ?>">
		<br>
		Amount Due: <?php echo $currencytype ?><input type="Text" size="10" name="amount" class="formfield">
                <br>
                <hr>
                </td>
        </tr>
        <tr>
		<td width="570" class="main">
                <b>Notes:<b>
                </td>
        </tr>
        <tr>
                <td width="570" class="main">
		<textarea name="notes" rows="4" cols="90" class="formfield"></textarea>
		<br>
                <br>
		<center>
		<input type="Submit" name="submit" value="Add Client" class="formfield">
		</center>
		</td>
	</tr>
</table>
</form>
