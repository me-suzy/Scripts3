<?php
/////////////////////////////////////////////////////////////////////////////
//                                                                         //
// Program Name         : PHPCart                                          //
// Release Version      : 3.1                                              //
// Program Author       : Mr B Wiencierz                                   //
// Home Page            : http://www.phpcart.co.uk                         //
// Supplied by          : CyKuH [WTN]                                      //
// Nullified by         : CyKuH [WTN]                                      //
// Distribution         : via WebForum, ForumRU and associated file dumps  //
//                                                                         //
//                All code is ©2000-2004 Barry Wiencierz.                  //
//                                                                         //
/////////////////////////////////////////////////////////////////////////////

$l69 = "The program does not have permission to write in the file payment_1.php. This file needs to be chmod'ed 777.";
$l75 = "Access denied";
$l61 = "Settings";
require("admin_1.php");
require("payment_1.php");
function auth() {
	global $l61, $l75;

	header("WWW-Authenticate: Basic realm=\"$l61\"");
	header("HTTP/1.0 401 Unauthorized");

	echo $l75;
	exit;
}
if ($PHP_AUTH_USER != $adminUsername || $PHP_AUTH_PW != $adminPassword) auth();

require("hf.php");
pageHeader();
if (!is_writable("payment_1.php")) trigger_error($l69);

if ($submit) {

	if ($PHP_AUTH_USER=="demo") {
	echo "<BR><BR><center>\n";
	echo "<font color=\"#F26522\"><B>DEMO:</B></font> No Settings Updated!<br><br>\n";
	echo "Redirecting...\n";
	echo "<script>window.setTimeout('changeurl();',2000); function changeurl(){window.location='main.php'}</script>\n";
	echo "</center><BR><BR>\n";
	} else {

	$content  = "<?\n";
	$content .= "\$paystamp_active 		= \"$paystamp_activeNew\";\n";
	$content .= "\$affid			= \"".addslashes($affidNew)."\";\n";
	$content .= "\$returnurl		= \"".addslashes($returnurlNew)."\";\n";
	$content .= "\$twocheckout_active 	= \"$twocheckout_activeNew\";\n";
	$content .= "\$sid			= \"".addslashes($sidNew)."\";\n";
	$content .= "\$nochex_active 		= \"$nochex_activeNew\";\n";
	$content .= "\$nochex_email		= \"".addslashes($nochex_emailNew)."\";\n";
	$content .= "\$nochex_logo		= \"".addslashes($nochex_logoNew)."\";\n";
	$content .= "\$nochex_returnurl		= \"".addslashes($nochex_returnurlNew)."\";\n";
	$content .= "\$paypal_active 		= \"$paypal_activeNew\";\n";
	$content .= "\$paypal_email		= \"".addslashes($paypal_emailNew)."\";\n";
	$content .= "\$paypal_currency		= \"".addslashes($paypal_currencyNew)."\";\n";
	$content .= "\$fastpay_active 		= \"$fastpay_activeNew\";\n";
	$content .= "\$fastpay_email		= \"".addslashes($fastpay_emailNew)."\";\n";
	$content .= "\$worldpay_active 		= \"$worldpay_activeNew\";\n";
	$content .= "\$instid			= \"".addslashes($instidNew)."\";\n";
	$content .= "\$worldpay_currency 	= \"".addslashes($worldpay_currencyNew)."\";\n";
	$content .= "\$eway_active 		= \"$eway_activeNew\";\n";
	$content .= "\$ewayCustomerID		= \"".addslashes($ewayCustomerID)."\";\n";
	$content .= "\$ewayRURL			= \"".addslashes($ewayRURLNew)."\";\n";
	$content .= "\$auth_active 		= \"$auth_activeNew\";\n";
	$content .= "\$x_login	 		= \"$x_loginNew\";\n";
	$content .= "\$moneyb_active 		= \"$moneyb_activeNew\";\n";
	$content .= "\$moneyb_merchant_id	= \"$moneyb_merchant_idNew\";\n";
	$content .= "\$moneyb_currency 		= \"$moneyb_currencyNew\";\n";
	$content .= "\$moneyb_return_url	= \"$moneyb_return_urlNew\";\n";
	$content .= "\$moneyb_status_url	= \"$moneyb_status_urlNew\";\n";
	$content .= "\$moneyb_cancel_url	= \"$moneyb_cancel_urlNew\";\n";
	$content .= "\$ematters_active 		= \"$ematters_activeNew\";\n";
	$content .= "\$em_username 		= \"$em_usernameNew\";\n";
	$content .= "\$em_name	 		= \"$em_nameNew\";\n";
	$content .= "\$em_email 		= \"$em_emailNew\";\n";
	$content .= "\$em_returnurl 		= \"$em_returnurlNew\";\n";
	$content .= "\$asiadebit_active 	= \"$asiadebit_activeNew\";\n";
	$content .= "\$asiadebit_shopid		= \"".addslashes($asiadebit_shopidNew)."\";\n";
	$content .= "\$asiadebit_currency	= \"".addslashes($asiadebit_currencyNew)."\";\n";
	$content .= "\$paysystems_active 	= \"$paysystems_activeNew\";\n";
	$content .= "\$paysystems_id	= \"".addslashes($paysystems_idNew)."\";\n";
	$content .= "\$paysystems_redirect_done	= \"".addslashes($paysystems_redirect_doneNew)."\";\n";
	$content .= "\$paysystems_redirect_fail	= \"".addslashes($paysystems_redirect_failNew)."\";\n";

	$content .= "\$verisign_active	 	= \"$verisign_activeNew\";\n";
	$content .= "\$verisign_login		= \"".addslashes($verisign_loginNew)."\";\n";
	$content .= "\$verisign_returnurl	= \"".addslashes($verisign_returnurlNew)."\";\n";

	$content .= "\$offline_active 		= \"$offline_activeNew\";\n";
	$content .= "\$offlinename		= \"".addslashes($offlinenameNew)."\";\n";
	$content .= "\$offlineaddress		= \"".addslashes($offlineaddressNew)."\";\n";
	$content .= "?>";

	$filePointer = fopen("payment_1.php", "w");
	fwrite($filePointer, $content);
	fclose($filePointer);

	echo "<BR><BR><center>\n";
	echo "<B>Setting updated!</B><br><br>\n";
	echo "Redirecting...\n";
	echo "<script>window.setTimeout('changeurl();',2000); function changeurl(){window.location='main.php'}</script>\n";
	echo "</center><BR><BR>\n";

	pageFooter();
	}
}
if (!$submit) {
echo "<p align=center><font size=2 face=Verdana><b>Payment Configuration!</b></font></div>\n";
echo "<form action='payment.php' method='post'>\n";
echo "<div align='center'>\n";
echo "<center>\n";
echo "<center><font size='2' color='#FF0000' face='Verdana'><B>PayStamp</B></font></center>\n";
echo "<table border='1' cellpadding='0' cellspacing='4' width='55%'>\n";
echo "<tr>\n";
echo "<td><font size='2' face='Verdana'>Use Paystamp:</font></td>\n";
echo "<td width='10%'><select size='1' name='paystamp_activeNew'>  <option value='$paystamp_active'>$paystamp_active</option>  <option value='Yes'>Yes</option>  <option value='No'>No</option></select></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td><font size='2' face='Verdana'>Affiliate ID:</font></td>\n";
echo "<td width='10%'><input type='text' name='affidNew' size='10' value='$affid'></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td><font size='2' face='Verdana'>Return URL:</font></td>\n";
echo "<td width='10%'><input type='text' name='returnurlNew' size='25' value='$returnurl'></td>\n";
echo "</tr>\n";
echo "</table>\n";
echo "</center>\n";
echo "<BR>\n";
echo "<center>\n";
echo "<center><font size='2' color='#FF0000' face='Verdana'><B>2Checkout</B></font></center>\n";
echo "<table border='1' cellpadding='0' cellspacing='4' width='55%'>\n";
echo "<tr>\n";
echo "<td width='59%'><font size='2' face='Verdana'>Use 2Checkout:</font></td>\n";
echo "<td width='41%'><select size='1' name='twocheckout_activeNew'>  <option value='$twocheckout_active'>$twocheckout_active</option>  <option value='Yes'>Yes</option>  <option value='No'>No</option></select></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td width='59%'><font size='2' face='Verdana'>Store ID:</font></td>\n";
echo "<td width='41%'><input type='text' name='sidNew' size='10' value='$sid'></td>\n";
echo "</tr>\n";
echo "</table>\n";
echo "</center>\n";
echo "<BR>\n";
echo "<center>\n";
echo "<center><font size='2' color='#FF0000' face='Verdana'><B>Nochex</B></font></center>\n";
echo "<table border='1' cellpadding='0' cellspacing='4' width='55%'>\n";
echo "<tr>\n";
echo "<td width='59%'><font size='2' face='Verdana'>Use Nochex:</font></td>\n";
echo "<td width='41%'><select size='1' name='nochex_activeNew'>  <option value='$nochex_active'>$nochex_active</option>  <option value='Yes'>Yes</option>  <option value='No'>No</option></select></td>\n";
echo "</tr>\n";
echo "<td width='59%'><font size='2' face='Verdana'>Nochex Email Address:</font></td>\n";
echo "<td width='41%'><input type='text' name='nochex_emailNew' size='10' value='$nochex_email'></td>\n";
echo "</tr>\n";
echo "<td width='59%'><font size='2' face='Verdana'>Logo URL:</font></td>\n";
echo "<td width='41%'><input type='text' name='nochex_logoNew' size='25' value='$nochex_logo'></td>\n";
echo "</tr>\n";
echo "<td width='59%'><font size='2' face='Verdana'>Return URL:</font></td>\n";
echo "<td width='41%'><input type='text' name='nochex_returnurlNew' size='25' value='$nochex_returnurl'></td>\n";
echo "</tr>\n";
echo "</table>\n";
echo "</center>\n";
echo "<BR>\n";
echo "<center>\n";
echo "<center><font size='2' color='#FF0000' face='Verdana'><B>PayPal</B></font></center>\n";
echo "<table border='1' cellpadding='0' cellspacing='4' width='55%'>\n";
echo "<tr>\n";
echo "<td width='59%'><font size='2' face='Verdana'>Use PayPal:</font></td>\n";
echo "<td width='41%'><select size='1' name='paypal_activeNew'>  <option value='$paypal_active'>$paypal_active</option>  <option value='Yes'>Yes</option>  <option value='No'>No</option></select></td>\n";
echo "</tr>\n";
echo "<td width='59%'><font size='2' face='Verdana'>PayPal Email Address:</font></td>\n";
echo "<td width='41%'><input type='text' name='paypal_emailNew' size='25' value='$paypal_email'></td>\n";
echo "</tr>\n";
echo "<td width='59%'><font size='2' face='Verdana'>Currency:</font></td>\n";
echo "<td width='41%'><select size='1' name='paypal_currencyNew'>  <option value='$paypal_currency'>$paypal_currency</option>  <option value='EUR'>Euro</option>  <option value='CAD'>Canadian Dollar</option>  <option value='GBP'>Pounds Sterling</option>  <option value='JPY'>Japanese Yen</option>  <option value='USD'>US Dollar</option></select></td>\n";
echo "</tr>\n";
echo "</table>\n";
echo "</center>\n";
echo "<BR>\n";
echo "<center>\n";
echo "<center><font size='2' color='#FF0000' face='Verdana'><B>FastPay</B></font></center>\n";
echo "<table border='1' cellpadding='0' cellspacing='4' width='55%'>\n";
echo "<tr>\n";
echo "<td width='59%'><font size='2' face='Verdana'>Use FastPay:</font></td>\n";
echo "<td width='41%'><select size='1' name='fastpay_activeNew'>  <option value='$fastpay_active'>$fastpay_active</option>  <option value='Yes'>Yes</option>  <option value='No'>No</option></select></td>\n";
echo "</tr>\n";
echo "<td width='59%'><font size='2' face='Verdana'>FastPay Email Address:</font></td>\n";
echo "<td width='41%'><input type='text' name='fastpay_emailNew' size='25' value='$fastpay_email'></td>\n";
echo "</tr>\n";
echo "</table>\n";
echo "</center>\n";
echo "<BR>\n";
echo "<center>\n";
echo "<center><font size='2' color='#FF0000' face='Verdana'><B>WorldPay</B></font></center>\n";
echo "<table border='1' cellpadding='0' cellspacing='4' width='55%'>\n";
echo "<tr>\n";
echo "<td width='59%'><font size='2' face='Verdana'>Use WorldPay:</font></td>\n";
echo "<td width='41%'><select size='1' name='worldpay_activeNew'>  <option value='$worldpay_active'>$worldpay_active</option>  <option value='Yes'>Yes</option>  <option value='No'>No</option></select></td>\n";
echo "</tr>\n";
echo "<td width='59%'><font size='2' face='Verdana'>WorldPay Installation ID:</font></td>\n";
echo "<td width='41%'><input type='text' name='instidNew' size='10' value='$instid'></td>\n";
echo "</tr>\n";
echo "<td width='59%'><font size='2' face='Verdana'>Currency ID:</font></td>\n";
echo "<td width='41%'><input type='text' name='worldpay_currencyNew' size='10' value='$worldpay_currency'></td>\n";
echo "</tr>\n";
echo "</table>\n";
echo "</center>\n";
echo "<BR>\n";
echo "<center>\n";
echo "<center><font size='2' color='#FF0000' face='Verdana'><B>eWay</B></font></center>\n";
echo "<table border='1' cellpadding='0' cellspacing='4' width='55%'>\n";
echo "<tr>\n";
echo "<td width='59%'><font size='2' face='Verdana'>Use eWay:</font></td>\n";
echo "<td width='41%'><select size='1' name='eway_activeNew'>  <option value='$eway_active'>$eway_active</option>  <option value='Yes'>Yes</option>  <option value='No'>No</option></select></td>\n";
echo "</tr>\n";
echo "<td width='59%'><font size='2' face='Verdana'>eWay Customer Number:</font></td>\n";
echo "<td width='41%'><input type='text' name='ewayCustomerIDNew' size='25' value='$ewayCustomerID'></td>\n";
echo "</tr>\n";
echo "<td width='59%'><font size='2' face='Verdana'>Return URL:</font></td>\n";
echo "<td width='41%'><input type='text' name='ewayRURLNew' size='25' value='$ewayRURL'></td>\n";
echo "</tr>\n";
echo "</table>\n";
echo "</center>\n";
echo "<BR>\n";
echo "<center>\n";
echo "<center><font size='2' color='#FF0000' face='Verdana'><B>Authorize.net</B></font></center>\n";
echo "<table border='1' cellpadding='0' cellspacing='4' width='55%'>\n";
echo "<tr>\n";
echo "<td width='59%'><font size='2' face='Verdana'>Use Authorize:</font></td>\n";
echo "<td width='41%'><select size='1' name='auth_activeNew'>  <option value='$auth_active'>$auth_active</option>  <option value='Yes'>Yes</option>  <option value='No'>No</option></select></td>\n";
echo "</tr>\n";
echo "<td width='59%'><font size='2' face='Verdana'>Login:</font></td>\n";
echo "<td width='41%'><input type='text' name='x_loginNew' size='25' value='$x_login'></td>\n";
echo "</tr>\n";
echo "</table>\n";
echo "</center>\n";
echo "<BR>\n";
echo "<center>\n";
echo "<center><font size='2' color='#FF0000' face='Verdana'><B>MoneyBookers</B></font></center>\n";
echo "<table border='1' cellpadding='0' cellspacing='4' width='55%'>\n";
echo "<tr>\n";
echo "<td width='59%'><font size='2' face='Verdana'>Use MoneyBookers:</font></td>\n";
echo "<td width='41%'><select size='1' name='moneyb_activeNew'>  <option value='$monayb_active'>$moneyb_active</option>  <option value='Yes'>Yes</option>  <option value='No'>No</option></select></td>\n";
echo "</tr>\n";
echo "<td width='59%'><font size='2' face='Verdana'>MoneyBookers Login Number:</font></td>\n";
echo "<td width='41%'><input type='text' name='moneyb_merchant_idNew' size='25' value='$moneyb_merchant_id'></td>\n";
echo "</tr>\n";
echo "<td width='59%'><font size='2' face='Verdana'>Currency:</font></td>\n";
echo "<td width='41%'><input type='text' name='moneyb_currencyNew' size='25' value='$moneyb_currency'></td>\n";
echo "</tr>\n";
echo "<td width='59%'><font size='2' face='Verdana'>Return URL:</font></td>\n";
echo "<td width='41%'><input type='text' name='moneyb_return_urlNew' size='25' value='$moneyb_return_url'></td>\n";
echo "</tr>\n";
echo "<td width='59%'><font size='2' face='Verdana'>Status URL:</font></td>\n";
echo "<td width='41%'><input type='text' name='moneyb_status_urlNew' size='25' value='$moneyb_status_url'></td>\n";
echo "</tr>\n";
echo "<td width='59%'><font size='2' face='Verdana'>Cancel URL:</font></td>\n";
echo "<td width='41%'><input type='text' name='moneyb_cancel_urlNew' size='25' value='$moneyb_cancel_url'></td>\n";
echo "</tr>\n";
echo "</table>\n";
echo "</center>\n";
echo "<BR>\n";
echo "<center>\n";
echo "<center><font size='2' color='#FF0000' face='Verdana'><B>eMatters</B></font></center>\n";
echo "<table border='1' cellpadding='0' cellspacing='4' width='55%'>\n";
echo "<tr>\n";
echo "<td width='59%'><font size='2' face='Verdana'>Use eMatters:</font></td>\n";
echo "<td width='41%'><select size='1' name='ematters_activeNew'>  <option value='$ematters_active'>$ematters_active</option>  <option value='Yes'>Yes</option>  <option value='No'>No</option></select></td>\n";
echo "</tr>\n";
echo "<td width='59%'><font size='2' face='Verdana'>Username:</font></td>\n";
echo "<td width='41%'><input type='text' name='em_usernameNew' size='25' value='$em_username'></td>\n";
echo "</tr>\n";
echo "<td width='59%'><font size='2' face='Verdana'>Name:</font><font size='1' face='Verdana'><I>Seperate spaces with + mark</I></font></td>\n";
echo "<td width='41%'><input type='text' name='em_nameNew' size='25' value='$em_name'></td>\n";
echo "</tr>\n";
echo "<td width='59%'><font size='2' face='Verdana'>Email:</font></td>\n";
echo "<td width='41%'><input type='text' name='em_emailNew' size='25' value='$em_email'></td>\n";
echo "</tr>\n";
echo "<td width='59%'><font size='2' face='Verdana'>Return URL:</font></td>\n";
echo "<td width='41%'><input type='text' name='em_returnurlNew' size='25' value='$em_returnurl'></td>\n";
echo "</tr>\n";
echo "</table>\n";
echo "</center>\n";
echo "<BR>\n";
echo "<center>\n";
echo "<center><font size='2' color='#FF0000' face='Verdana'><B>Asia Debit</B></font></center>\n";
echo "<table border='1' cellpadding='0' cellspacing='4' width='55%'>\n";
echo "<tr>\n";
echo "<td width='59%'><font size='2' face='Verdana'>Use Asia Debit:</font></td>\n";
echo "<td width='41%'><select size='1' name='asiadebit_activeNew'>  <option value='$asiadebit_active'>$asiadebit_active</option>  <option value='Yes'>Yes</option>  <option value='No'>No</option></select></td>\n";
echo "</tr>\n";
echo "<td width='59%'><font size='2' face='Verdana'>Shop ID:</font></td>\n";
echo "<td width='41%'><input type='text' name='asiadebit_shopidNew' size='25' value='$asiadebit_shopid'></td>\n";
echo "</tr>\n";
echo "<td width='59%'><font size='2' face='Verdana'>Currency:</font></td>\n";
echo "<td width='41%'><select size='1' name='asiadebit_currencyNew'>  <option value='$asiadebit_currency'>$asiadebit_currency</option>  <option value='EUR'>Euro</option>  <option value='CAD'>Canadian Dollar</option>  <option value='GBP'>Pounds Sterling</option>  <option value='JPY'>Japanese Yen</option>  <option value='USD'>US Dollar</option></select></td>\n";
echo "</tr>\n";
echo "</table>\n";
echo "</center>\n";
echo "<BR>\n";
echo "<center>\n";
echo "<center><font size='2' color='#FF0000' face='Verdana'><B>Paysystems</B></font></center>\n";
echo "<table border='1' cellpadding='0' cellspacing='4' width='55%'>\n";
echo "<tr>\n";
echo "<td width='59%'><font size='2' face='Verdana'>Use Paysystems:</font></td>\n";
echo "<td width='41%'><select size='1' name='paysystems_activeNew'>  <option value='$paysystems_active'>$paysystems_active</option>  <option value='Yes'>Yes</option>  <option value='No'>No</option></select></td>\n";
echo "</tr>\n";
echo "<td width='59%'><font size='2' face='Verdana'>Company ID:</font></td>\n";
echo "<td width='41%'><input type='text' name='paysystems_idNew' size='25' value='$paysystems_id'></td>\n";
echo "</tr>\n";
echo "<td width='59%'><font size='2' face='Verdana'>Successful Return URL:</font></td>\n";
echo "<td width='41%'><input type='text' name='paysystems_redirect_doneNew' size='25' value='$paysystems_redirect_done'></td>\n";
echo "</tr>\n";
echo "<td width='59%'><font size='2' face='Verdana'>Failed Return URL:</font></td>\n";
echo "<td width='41%'><input type='text' name='paysystems_redirect_failNew' size='25' value='$paysystems_redirect_fail'></td>\n";
echo "</tr>\n";
echo "</table>\n";
echo "</center>\n";
echo "<BR>\n";

echo "<center>\n";
echo "<center><font size='2' color='#FF0000' face='Verdana'><B>Verisign</B></font></center>\n";
echo "<table border='1' cellpadding='0' cellspacing='4' width='55%'>\n";
echo "<tr>\n";
echo "<td width='59%'><font size='2' face='Verdana'>Use Verisign:</font></td>\n";
echo "<td width='41%'><select size='1' name='verisign_activeNew'>  <option value='$verisign_active'>$verisign_active</option>  <option value='Yes'>Yes</option>  <option value='No'>No</option></select></td>\n";
echo "</tr>\n";
echo "<td width='59%'><font size='2' face='Verdana'>Login ID:</font></td>\n";
echo "<td width='41%'><input type='text' name='verisign_loginNew' size='25' value='$paysystems_id'></td>\n";
echo "</tr>\n";
echo "<td width='59%'><font size='2' face='Verdana'>Return URL:</font></td>\n";
echo "<td width='41%'><input type='text' name='verisign_returnurlNew' size='25' value='$verisign_returnurl'></td>\n";
echo "</tr>\n";
echo "</table>\n";
echo "</center>\n";
echo "<BR>\n";

echo "<center>\n";
echo "<table border='1' cellpadding='0' cellspacing='4' width='55%'>\n";
echo "<tr>\n";
echo "<td width='100%'><center><font size='2' color='#FF0000' face='Verdana'><B>Offline Payments</B> <i>Cash/Cheque etc</i></font></center></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td width='59%'><font size='2' face='Verdana'>Use Offline Payments:</font></td>\n";
echo "<td width='41%'><select size='1' name='offline_activeNew'>  <option value='$offline_active'>$offline_active</option>  <option value='Yes'>Yes</option>  <option value='No'>No</option></select></td>\n";
echo "</tr>\n";
echo "<td width='59%'><font size='2' face='Verdana'>Cheque Name:</font></td>\n";
echo "<td width='41%'><input type='text' name='offlinenameNew' size='25' value='$offlinename'></td>\n";
echo "</tr>\n";
echo "<td width='59%'><font size='2' face='Verdana'>Address:</font></td>\n";
echo "<td width='41%'><textarea rows='4' name='offlineaddressNew' cols='23'>$offlineaddress</textarea></td>\n";
echo "</tr>\n";
echo "<BR>\n";
echo "<tr>\n";
echo "<td width='100%' colspan='2'>\n";
echo "<p align='center'><input type='submit' name='submit' value='Save the settings'></p>\n";
	if ($PHP_AUTH_USER=="demo") {
	echo "<div align=\"center\">";
	echo "  <center>";
	echo "  <table border=\"0\" cellpadding=\"5\" cellspacing=\"5\" width=\"80%\" align=\"center\">";
	echo "    <tr>";
	echo "      <td><b><font color=\"#F26522\">NOTE:</font></b><BR> As this is a demo no settings will be saved.</td>";
	echo "    </tr>";
	echo "  </table>";
	echo "  </center>";
	echo "</div>";
	}
echo "</td>\n";
echo "</tr>\n";
echo "</table>\n";
echo "</center>\n";
echo "</div>\n";
echo "</form>\n";
?>
<?pageFooter();
}
?>