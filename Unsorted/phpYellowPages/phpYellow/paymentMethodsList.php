<?php echo"\n\n\n";?>
<!-- START of paymentMethodsList.php -->
<?php /*
To remove a payment method type delete the line it is on.
Example: to remove 'Credit Card by Internet' delete all of line 9 
and next save this file as paymentMethodsList.php. This file is 
automatically included in the online form called checkout.php
*/?>
	<input type="radio" name="method" value="Credit Card by Internet" CHECKED> Credit Card by Internet
<br><input type="radio" name="method" value="Credit Card by Fax"> Credit Card by Fax
<br><input type="radio" name="method" value="Credit Card by Phone"> Credit Card by Phone
<br><input type="radio" name="method" value="Credit Card by Mail"> Credit Card by Mail
<br><input type="radio" name="method" value="Check by Fax"> Check by Fax
<br><input type="radio" name="method" value="Check by Mail"> Check by Mail
<br><input type="radio" name="method" value="Money Order by Mail"> Money Order by Mail
<br><input type="radio" name="method" value="Travelers Checks"> Travelers Checks
<br><input type="radio" name="method" value="Wire"> Wire
<br><input type="radio" name="method" value="Email Invoice"> Email Invoice to me
<br><input type="radio" name="method" value="US Currency"> US Currency
<br>( via courier or registered mail )
<!--END of paymentMethodsList.php -->
