<?php
/*********************************************************************/
/* Program Name         : phpProMembers                              */
/* Home Page            : http://www.gacybertech.com                 */
/* Retail Price         : $149.99 United States Dollars              */
/* WebForum Price       : $000.00 Always 100% Free                   */
/* xCGI Price           : $000.00 Always 100% Free                   */
/* Supplied by          : South [WTN]                                */
/* Nullified by         : CyKuH [WTN]                                */
/* Distribution         : via WebForum and Forums File Dumps         */
/*********************************************************************/
$page_account = "form";
require "include.php";
	
$nnameoncard = $nameoncard;
$ccardnumber = $cardnumber;
$eexp = $exp;
	
if ($payment == "clickbank") {
	$cookie_value = "$fname|$lname|$zip";
	setcookie("clickbank", $cookie_value);
}

if ($include_template == "1") {
	include "$template_directory/header.php";	
}

if ($include_agreement == "1") {
	if (agreement == "") {
		echo "<center><b>Please check that you agree to the terms and conditions.</b></center><br>";
		exit;		
	}
}	



if ($fname == "")	{
	echo "<center><b>Please enter your first name.</b></center><br>";
	exit;
}

if ($lname == "")	{
	echo "<center><b>Please enter your last name.</b></center><br>";
	exit;
}

if ($address == "")	{
	echo "<center><b>Please enter your adress.</b></center><br>";
	exit;
}

if ($city == "")	{
	echo "<center><b>Please enter your city.</b></center><br>";
	exit;
}

if ($state == "")	{
	echo "<center><b>Please enter your state.</b></center><br>";
	exit;
}

if ($zip == "")	{
	echo "<center><b>Please enter your postal code.</b></center><br>";
	exit;
}

if ($country == "")	{
	echo "<center><b>Please enter your country name.</b></center><br>";
	exit;
}

if ($phone == "")	{
	echo "<center><b>Please enter your phone number.</b></center><br>";
	exit;
}

if ($email == "")	{
	echo "<center><b>Please enter your email address.</b></center><br>";
	exit;
}

if ($username == "")	{
	echo "<center><b>Please enter a user name.</b></center><br>";
	exit;
}

if ($pwd == "")	{
	echo "<center><b>Please enter a password.</b></center><br>";
	exit;
}

if ($pwd2 != $pwd)	{
	echo "<center><b>Your passwords do not match, please re-enter.</b></center><br>";
	exit;
}

if ($include_billing == "1") {
	if ($billingaddress == "")	{
		echo "<center><b>Please enter your billing address.</b></center><br>";
		exit;
	}
	
	if ($ccity == "")	{
		echo "<center><b>Please enter your billing city.</b></center><br>";
		exit;
	}
	
	if ($cstate == "")	{
		echo "<center><b>Please enter your billing state.</b></center><br>";
		exit;
	}
	
	if ($czip == "")	{
		echo "<center><b>Please enter your billing postal code.</b></center><br>";
		exit;
	}
	
	if ($ccountry == "")	{
		echo "<center><b>Please enter your billing country.</b></center><br>";
		exit;
	}
}

if ($payment == "") {
	echo "<center><b>Please enter a payment option.</b></center><br>";
	exit;
}

if ($payment == "creditcard") {
	if ($nameoncard = "") {
		echo "<center><b>Please enter name on the credit card.</b></center><br>";
		exit;
	}
	if ($cardnumber = "") {
		echo "<center><b>Please enter your credit card number.</b></center><br>";
		exit;
	}
	if ($exp = "") {
		echo "<center><b>Please enter the expiration on your credit card.</b></center><br>";
		exit;
	}
}

?>	
<br><br>
<FORM ACTION="process_form.php" METHOD="POST">
<CENTER><FONT SIZE="2" FACE="verdana, arial, helvetica"><b>Is this correct?</b></Font></center>
<br><br>
<TABLE>
	<TR>
    	<TD>
			<FONT FACE="verdana, arial, helvetica">First Name:</font> <?php echo $fname ?>
		</TD>
    </TR>
	<TR>
    	<TD>
			<FONT FACE="verdana, arial, helvetica">Last Name:</font> <?php echo $lname ?>
		</TD>
    </TR>
	<TR>
    	<TD>
			<FONT FACE="verdana, arial, helvetica">Address:</font> <?php echo $address ?>
		</TD>
    </TR>
	<TR>
    	<TD>
			<FONT FACE="verdana, arial, helvetica">Address:</font> <?php echo $address2 ?>
		</TD>
    </TR>
	<TR>
    	<TD>
			<FONT FACE="verdana, arial, helvetica">City:</font> <?php echo $city ?>
		</TD>
    </TR>
	<TR>
    	<TD>
			<FONT FACE="verdana, arial, helvetica">State:</font> <?php echo $state ?>
		</TD>
    </TR>
	<TR>
    	<TD>
			<FONT FACE="verdana, arial, helvetica">Postal Code:</font> <?php echo $zip ?>
		</TD>
    </TR>
	<TR>
    	<TD>
			<FONT FACE="verdana, arial, helvetica">Country:</font> <?php echo $country ?>
		</TD>
    </TR>
	<TR>
    	<TD>
			<FONT FACE="verdana, arial, helvetica">Telephone Number:</font> <?php echo $phone ?>
		</TD>
    </TR>
	<TR>
    	<TD>
			<FONT FACE="verdana, arial, helvetica">E-Mail Address:</font> <?php echo $email ?>
		</TD>
    </TR>
</TABLE>

<?php
// Check and see if we need to get billing information.		
if ($include_billing == "1") {
?>
<TABLE>
	<TR>
    	<TD>
			<FONT FACE="verdana, arial, helvetica">Billing Address:</font> <?php echo $billingaddress ?>
		</td>
	</tr>
	<TR>
    	<TD>
			<FONT FACE="verdana, arial, helvetica">Billing Address:</font> <?php echo $billingaddress2 ?>
		</td>
	</tr>
	<TR>
    	<TD>
			<FONT FACE="verdana, arial, helvetica">Billing City:</font> <?php echo $ccity ?>
		</td>
	</tr>
	<TR>
    	<TD>
			<FONT FACE="verdana, arial, helvetica">Billing State:</font> <?php echo $cstate ?>
		</td>
	</tr>
	<TR>
    	<TD>
			<FONT FACE="verdana, arial, helvetica">Billing Postal Code:</font> <?php echo $czip ?>
		</td>
	</tr>
	<TR>
    	<TD>
			<FONT FACE="verdana, arial, helvetica">Billing Country:</font> <?php echo $ccountry ?>
		</td>
	</tr>
</table>
<?php
}
?>
<br><br>
<TABLE>
	<TR>
    	<TD>
			<FONT FACE="verdana, arial, helvetica">Payment Type:</font> <?php echo $payment ?>
		</TD>
	</TR>
</TABLE>
<br><br>
<TABLE>
	<TR>
    	<TD>
<?php
$get_payment_info_sql = "SELECT * FROM account_types WHERE id = \"$accounts\"";
$result = mysql_query($get_payment_info_sql);
while($row = mysql_fetch_object($result)) {
	$account_length = "$row->length";
	$item_name = "$row->account_name";
	$item_amount = $row->account_fee + $row->account_setup_fee;
}
?>		
			<FONT FACE="verdana, arial, helvetica">Total Bill:</font> <?php echo printf("%.2f", $item_amount);  ?>
		</TD>
	</TR>
	<TR>
		<TD>
			<input type="hidden" name="agreement" value="agree">
			<input type="hidden" name="accounts" value="<?php echo $accounts ?>">
			<input type="hidden" name="fname" value="<?php echo $fname ?>">
			<input type="hidden" name="lname" value="<?php echo $lname ?>">
			<input type="hidden" name="address" value="<?php echo $address ?>">
			<input type="hidden" name="address2" value="<?php echo $address2 ?>">
			<input type="hidden" name="city" value="<?php echo $city ?>">
			<input type="hidden" name="state" value="<?php echo $state ?>">
			<input type="hidden" name="zip" value="<?php echo $zip ?>">
			<input type="hidden" name="country" value="<?php echo $country ?>">
			<input type="hidden" name="phone" value="<?php echo $phone ?>">
			<input type="hidden" name="email" value="<?php echo $email ?>">
			<input type="hidden" name="username" value="<?php echo $username ?>">
			<input type="hidden" name="pwd" value="<?php echo $pwd ?>">
			<input type="hidden" name="billingaddress" value="<?php echo $billingaddress ?>">
			<input type="hidden" name="billingaddress2" value="<?php echo $billingaddress2 ?>">
			<input type="hidden" name="ccity" value="<?php echo $ccity ?>">
			<input type="hidden" name="cstate" value="<?php echo $cstate ?>">
			<input type="hidden" name="czip" value="<?php echo $czip ?>">
			<input type="hidden" name="ccountry" value="<?php echo $ccountry ?>">
			<input type="hidden" name="payment" value="<?php echo $payment ?>">
			<input type="hidden" name="creditcards" value="<?php echo $creditcards ?>">
			<input type="hidden" name="nameoncard" value="<?php echo $nnameoncard ?>">
			<input type="hidden" name="cardnumber" value="<?php echo $ccardnumber ?>">
			<input type="hidden" name="exp" value="<?php echo $eexp ?>">
			<input type="hidden" name="account_length" value="<?php echo $account_length ?>">
			<input type="hidden" name="item_name" value="<?php echo $item_name ?>">
			<input type="hidden" name="item_amount" value="<?php echo $item_amount ?>">
		</TD>
	</TR>
	<TR>
		<TD>
			<INPUT TYPE="SUBMIT" NAME="process" VALUE="Submit My Order">
		</TD>
	</TR>
</TABLE>
</CENTER>
</FORM>
	
<?php
// Adding footer if we got it.
if ($include_template == "1") {
	include "$template_directory/footer.php";	
}
?>	